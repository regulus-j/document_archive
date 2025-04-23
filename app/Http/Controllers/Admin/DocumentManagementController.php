<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentAttachment;
use App\Models\DocumentAudit;
use App\Models\DocumentDeletionSchedule;
use App\Models\CompanyAccount;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class DocumentManagementController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:company-admin']);
    // }
    
    /**
     * Display document management dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }

        // Get all users in this company
        $companyUserIds = User::whereHas('companies', function($query) use ($company) {
            $query->where('company_accounts.id', $company->id);
        })->pluck('id')->toArray();
        
        // Get document counts using the approach from ReportController::getStorageMetrics
        $totalDocuments = Document::whereIn('uploader', $companyUserIds)->count();

        // Count archived documents
        $archivedDocuments = Document::whereIn('uploader', $companyUserIds)
            ->whereHas('status', function($q) {
                $q->where('status', 'archived');
            })
            ->count();
        
        // Calculate storage usage
        $storageUsage = 0;
        $documents = Document::whereIn('uploader', $companyUserIds)->get();
        $documentIds = $documents->pluck('id')->toArray();
        
        // Calculate document sizes
        foreach ($documents as $document) {
            $docPath = $document->path ?? '';
            if ($docPath && Storage::disk('public')->exists($docPath)) {
                $size = Storage::disk('public')->size($docPath);
                $storageUsage += $size;
            }
        }
        
        // Add attachment sizes
        $attachments = DocumentAttachment::whereIn('document_id', $documentIds)->get();
        foreach ($attachments as $attachment) {
            $attachPath = $attachment->path ?? '';
            if ($attachPath && Storage::disk('public')->exists($attachPath)) {
                $size = Storage::disk('public')->size($attachPath);
                $storageUsage += $size;
            }
        }
        
        $storageUsageMB = $storageUsage ? round($storageUsage / 1024 / 1024, 2) : 0;
        
        // Get deletion schedule
        $deletionSchedule = DocumentDeletionSchedule::where('company_id', $company->id)
            ->first();
            
        // Recent audit logs - update to use the same approach
        $auditLogs = DocumentAudit::with(['document', 'user'])
            ->whereHas('document', function ($q) use ($companyUserIds) {
                $q->whereIn('uploader', $companyUserIds);
            })
            ->latest()
            ->take(10)
            ->get();
            
        return view('admin.document-management.index', compact(
            'totalDocuments', 
            'archivedDocuments', 
            'storageUsageMB', 
            'deletionSchedule',
            'auditLogs'
        ));
    }
    
    /**
     * Display a listing of documents with search and filtering
     */
    public function documents(Request $request)
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        // Get all users in this company
        $companyUserIds = User::whereHas('companies', function($query) use ($company) {
            $query->where('company_accounts.id', $company->id);
        })->pluck('id')->toArray();
        
        // Modified query to use the uploader field with company users
        $query = Document::with(['user', 'status', 'categories', 'transaction.fromOffice', 'transaction.toOffice'])
                ->whereIn('uploader', $companyUserIds);
            
        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Apply date filters
        if ($request->has('date_from') && $request->date_from) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }
        
        // Apply archived filter using status relationship
        if ($request->has('show_archived')) {
            if ($request->show_archived) {
                $query->whereHas('status', function($q) {
                    $q->where('status', 'archived');
                });
            } else {
                $query->whereHas('status', function($q) {
                    $q->where('status', '!=', 'archived');
                });
            }
        }
            
        $documents = $query->latest()->paginate(15);
        
        return view('admin.document-management.documents', compact('documents'));
    }
    
    /**
     * Show document details
     */
    public function show($id)
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        // Get all users in this company for proper filtering
        $companyUserIds = User::whereHas('companies', function($query) use ($company) {
            $query->where('company_accounts.id', $company->id);
        })->pluck('id')->toArray();
        
        // Get the document with needed relationships
        $document = Document::with([
            'user', 
            'categories', 
            'attachments', 
            'status',
            'transaction.fromOffice',
            'transaction.toOffice',
            'documentWorkflow.sender',
            'documentWorkflow.recipient',
            'documentWorkflow.recipientOffice'
        ])->whereIn('uploader', $companyUserIds);
        
        $document = $document->findOrFail($id);
        
        // Get audit logs for this document
        $auditLogs = DocumentAudit::where('document_id', $document->id)
            ->with('user')
            ->latest()
            ->get();
            
        // Get workflows and organize by step order for display
        $workflows = $document->documentWorkflow()
            ->orderBy('step_order')
            ->get();

        $docRoute = [];

        // Group recipients by step order
        foreach ($workflows as $workflow) {
            // Add user recipient if available
            if ($workflow->recipient) {
                $recipientName = trim(($workflow->recipient->first_name ?? '') . ' ' . ($workflow->recipient->last_name ?? ''));
                $docRoute[$workflow->step_order][] = [
                    'name' => $recipientName,
                    'type' => 'user',
                    'workflow' => $workflow
                ];
            }
            
            // Add office recipient if available
            if ($workflow->recipient_office && $workflow->recipientOffice) {
                $docRoute[$workflow->step_order][] = [
                    'name' => $workflow->recipientOffice->name,
                    'type' => 'office',
                    'workflow' => $workflow
                ];
            }
            
            // If neither recipient nor office, add placeholder
            if ((!$workflow->recipient && !$workflow->recipient_office) || 
                ($workflow->recipient_office && !$workflow->recipientOffice)) {
                $docRoute[$workflow->step_order][] = [
                    'name' => 'Unassigned',
                    'type' => 'none',
                    'workflow' => $workflow
                ];
            }
        }
            
        return view('admin.document-management.show', compact('document', 'auditLogs', 'docRoute', 'workflows'));
    }
    
    /**
     * Delete a document
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        // Get the document with the proper relationship filter
        $documentQuery = Document::query();
        
        if (Schema::hasColumn('documents', 'company_id')) {
            $document = $documentQuery->where('company_id', $company->id)->findOrFail($id);
        } else {
            $document = $documentQuery->whereHas('user.companies', function($q) use ($company) {
                $q->where('companies.id', $company->id);
            })->findOrFail($id);
        }
        
        // Log the deletion
        DocumentAudit::logDocumentAction(
            $document->id,
            $user->id,
            'delete',
            'deleted',
            'Document deleted by company admin'
        );
        
        // Delete the document
        $document->delete();
        
        return redirect()->route('admin.document-management.documents')
            ->with('success', 'Document has been deleted.');
    }
    
    /**
     * Toggle archive status of a document
     */
    public function toggleArchive($id)
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        // Get the document with the proper relationship filter
        $documentQuery = Document::with('status');
        
        if (Schema::hasColumn('documents', 'company_id')) {
            $document = $documentQuery->where('company_id', $company->id)->findOrFail($id);
        } else {
            $document = $documentQuery->whereHas('user.companies', function($q) use ($company) {
                $q->where('companies.id', $company->id);
            })->findOrFail($id);
        }
        
        // Check the current status
        $currentStatus = $document->status ? $document->status->status : 'pending';
        $newStatus = $currentStatus === 'archived' ? 'pending' : 'archived';
        
        // Update the document status
        if ($document->status) {
            $document->status()->update(['status' => $newStatus]);
        } else {
            $document->status()->create(['status' => $newStatus]);
        }
        
        // Log the action
        DocumentAudit::logDocumentAction(
            $document->id,
            $user->id,
            'archive',
            $newStatus,
            "Document {$newStatus} by company admin"
        );
        
        return redirect()->route('admin.document-management.show', $document->id)
            ->with('success', "Document has been {$newStatus}.");
    }
    
    /**
     * Delete documents in bulk based on criteria
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
        ]);
        
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        \Log::info('Starting bulk delete operation', [
            'user_id' => $user->id,
            'company_id' => $company->id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to
        ]);
        
        // Build query with proper company filter
        $query = Document::query();
        
        // Get all users in this company for proper filtering
        $companyUserIds = User::whereHas('companies', function($query) use ($company) {
            $query->where('company_accounts.id', $company->id);
        })->pluck('id')->toArray();
        
        \Log::info('Company users found', ['count' => count($companyUserIds)]);
        
        // Find documents uploaded by users in this company
        $query->whereIn('uploader', $companyUserIds);
        
        // Apply date from filter if provided
        if ($request->date_from) {
            $fromDate = Carbon::parse($request->date_from)->startOfDay();
            $query->where('created_at', '>=', $fromDate);
        }
        
        // Apply date to filter if provided
        if ($request->date_to) {
            $toDate = Carbon::parse($request->date_to)->endOfDay();
            $query->where('created_at', '<=', $toDate);
        }
        
        // Exclude archived documents using direct query on the status relationship
        // We're checking:
        // 1. Documents with no status record
        // 2. Documents with a status that's not 'archived'
        $documentsToDelete = $query->where(function($q) {
            $q->whereDoesntHave('status')
              ->orWhereHas('status', function($subq) {
                  $subq->where('status', '!=', 'archived');
              });
        })->get();
        
        $count = $documentsToDelete->count();
        
        \Log::info('Documents found for bulk deletion', [
            'count' => $count,
            'query_sql' => $query->toSql(),
            'query_bindings' => $query->getBindings()
        ]);
        
        if ($count === 0) {
            return redirect()->route('admin.document-management.documents')
                ->with('info', 'No documents found matching the deletion criteria.');
        }
        
        $deletedCount = 0;
        
        // Process each document for proper deletion
        foreach ($documentsToDelete as $document) {
            try {
                // Log the deletion action
                DocumentAudit::logDocumentAction(
                    $document->id,
                    $user->id,
                    'bulk-delete',
                    'deleted',
                    'Document deleted as part of bulk operation by company admin'
                );
                
                // Delete document file from storage
                if ($document->path && Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }
                
                // Delete attachment files from storage
                foreach ($document->attachments as $attachment) {
                    if ($attachment->path && Storage::disk('public')->exists($attachment->path)) {
                        Storage::disk('public')->delete($attachment->path);
                    }
                }
                
                // Actually delete the document record (this will use soft delete)
                $document->delete();
                $deletedCount++;
                
            } catch (\Exception $e) {
                \Log::error('Error deleting document', [
                    'document_id' => $document->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }
        
        \Log::info('Bulk delete completed', [
            'found' => $count,
            'deleted' => $deletedCount
        ]);
        
        return redirect()->route('admin.document-management.documents')
            ->with('success', "{$deletedCount} documents have been deleted.");
    }
    
    /**
     * Show deletion schedule form
     */
    public function showDeletionSchedule()
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        $schedule = DocumentDeletionSchedule::where('company_id', $company->id)->first();
        
        // Get all users in this company
        $companyUserIds = User::whereHas('companies', function($query) use ($company) {
            $query->where('company_accounts.id', $company->id);
        })->pluck('id')->toArray();
        
        // Calculate storage usage using the approach from ReportController
        $storageUsage = 0;
        $documents = Document::whereIn('uploader', $companyUserIds)->get();
        $documentIds = $documents->pluck('id')->toArray();
        
        // Calculate document sizes
        foreach ($documents as $document) {
            $docPath = $document->path ?? '';
            if ($docPath && Storage::disk('public')->exists($docPath)) {
                $size = Storage::disk('public')->size($docPath);
                $storageUsage += $size;
            }
        }
        
        // Add attachment sizes
        $attachments = DocumentAttachment::whereIn('document_id', $documentIds)->get();
        foreach ($attachments as $attachment) {
            $attachPath = $attachment->path ?? '';
            if ($attachPath && Storage::disk('public')->exists($attachPath)) {
                $size = Storage::disk('public')->size($attachPath);
                $storageUsage += $size;
            }
        }
        
        $storageUsageMB = $storageUsage ? round($storageUsage / 1024 / 1024, 2) : 0;
        
        return view('admin.document-management.schedule', compact('schedule', 'storageUsageMB'));
    }
    
    /**
     * Save or update deletion schedule
     */
    public function saveDeletionSchedule(Request $request)
    {
        // Validate with proper conditional validations for different criteria
        $validated = $request->validate([
            'criteria' => ['required', Rule::in(['age', 'storage', 'both'])],
            'retention_days' => 'required_if:criteria,age,both|integer|min:1',
            'storage_limit_mb' => 'required_if:criteria,storage,both|integer|min:1',
        ]);
        
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        // The logic below was causing retention_days to be null when criteria was 'storage',
        // but the database column doesn't allow nulls. Set default values for all cases.
        
        // Always provide a retention days value, default to existing or 365 days
        $schedule = DocumentDeletionSchedule::where('company_id', $company->id)->first();
        $retentionDays = $request->retention_days ?? ($schedule ? $schedule->retention_days : 365);
        
        // Always provide a storage limit value, default to existing or 100MB
        $storageLimit = $request->storage_limit_mb ?? ($schedule ? $schedule->storage_limit_mb : 100);
        
        // Properly handle the checkbox value (checkbox inputs use 'on' as value when checked)
        $isActive = $request->has('is_active');
        
        \Log::info('Saving deletion schedule', [
            'company_id' => $company->id,
            'criteria' => $request->criteria,
            'retention_days' => $retentionDays,
            'storage_limit_mb' => $storageLimit,
            'is_active' => $isActive
        ]);
        
        $schedule = DocumentDeletionSchedule::updateOrCreate(
            ['company_id' => $company->id],
            [
                'retention_days' => $retentionDays,
                'storage_limit_mb' => $storageLimit,
                'criteria' => $request->criteria,
                'is_active' => $isActive,
            ]
        );
        
        return redirect()->route('admin.document-management.schedule')
            ->with('success', 'Document deletion schedule has been saved.');
    }
    
    /**
     * Run the document deletion schedule manually
     */
    public function runDeletionSchedule()
    {
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        $schedule = DocumentDeletionSchedule::where('company_id', $company->id)->first();
        
        if (!$schedule) {
            return redirect()->route('admin.document-management.schedule')
                ->with('error', 'No deletion schedule found.');
        }
        
        if (!$schedule->is_active) {
            return redirect()->route('admin.document-management.schedule')
                ->with('error', 'The deletion schedule is not active.');
        }
        
        // Get documents to delete
        $documents = collect();
        
        // Base query with company filter
        $baseQuery = Document::with(['status', 'attachments']);
        if (Schema::hasColumn('documents', 'company_id')) {
            $baseQuery->where('company_id', $company->id);
        } else {
            $baseQuery->whereHas('user.companies', function($q) use ($company) {
                $q->where('companies.id', $company->id);
            });
        }
        
        // Only look at non-archived documents
        $baseQuery->whereHas('status', function($q) {
            $q->where('status', '!=', 'archived');
        });
        
        // Process by age criteria
        if ($schedule->criteria === 'age' || $schedule->criteria === 'both') {
            $cutoffDate = Carbon::now()->subDays($schedule->retention_days);
            $ageQuery = clone $baseQuery;
            $ageDocuments = $ageQuery->where('created_at', '<', $cutoffDate)->get();
            $documents = $documents->merge($ageDocuments);
        }
        
        // Process by storage criteria
        if ($schedule->criteria === 'storage' || $schedule->criteria === 'both') {
            if ($schedule->storage_limit_mb) {
                // Calculate current storage usage with proper company filter
                $storageUsage = 0;
                
                // Get company user IDs for filtering
                $companyUserIds = User::whereHas('companies', function($query) use ($company) {
                    $query->where('company_accounts.id', $company->id);
                })->pluck('id')->toArray();
                
                // Get all documents for this company
                $allCompanyDocs = Document::whereIn('uploader', $companyUserIds)->get();
                $documentIds = $allCompanyDocs->pluck('id')->toArray();
                
                // Calculate actual storage usage by checking file sizes
                foreach ($allCompanyDocs as $document) {
                    $docPath = $document->path ?? '';
                    if ($docPath && Storage::disk('public')->exists($docPath)) {
                        $size = Storage::disk('public')->size($docPath);
                        $storageUsage += $size;
                        
                        // Update storage_size field for future reference if it exists
                        if (Schema::hasColumn('documents', 'storage_size') && (!$document->storage_size || $document->storage_size != $size)) {
                            $document->storage_size = $size;
                            $document->save();
                        }
                    }
                }
                
                // Add attachment sizes
                $attachments = DocumentAttachment::whereIn('document_id', $documentIds)->get();
                foreach ($attachments as $attachment) {
                    $attachPath = $attachment->path ?? '';
                    if ($attachPath && Storage::disk('public')->exists($attachPath)) {
                        $size = Storage::disk('public')->size($attachPath);
                        $storageUsage += $size;
                    }
                }
                
                $storageUsageMB = round($storageUsage / 1024 / 1024, 2);
                
                if ($storageUsageMB > $schedule->storage_limit_mb) {
                    // Get oldest documents first that aren't archived, until we get below the limit
                    $deleteQuery = clone $baseQuery;
                    $documentsToDelete = $deleteQuery->orderBy('created_at')->get();
                    
                    $currentSize = $storageUsageMB;
                    $storageDocuments = collect();
                    
                    foreach ($documentsToDelete as $doc) {
                        if ($currentSize <= $schedule->storage_limit_mb) {
                            break;
                        }
                        
                        // Get actual document size from storage
                        $docSize = 0;
                        $docPath = $doc->path ?? '';
                        if ($docPath && Storage::disk('public')->exists($docPath)) {
                            $docSize = Storage::disk('public')->size($docPath);
                        }
                        
                        // Add attachment sizes
                        foreach ($doc->attachments as $attachment) {
                            $attachPath = $attachment->path ?? '';
                            if ($attachPath && Storage::disk('public')->exists($attachPath)) {
                                $docSize += Storage::disk('public')->size($attachPath);
                            }
                        }
                        
                        $docSizeMB = $docSize / 1024 / 1024;
                        $currentSize -= $docSizeMB;
                        $storageDocuments->push($doc);
                    }
                    
                    $documents = $documents->merge($storageDocuments);
                }
            }
        }
        
        // Remove duplicates
        $documents = $documents->unique('id');
        $count = $documents->count();
        
        if ($count === 0) {
            return redirect()->route('admin.document-management.schedule')
                ->with('info', 'No documents found eligible for deletion.');
        }
        
        // Delete documents and log
        foreach ($documents as $document) {
            try {
                // Log before deletion
                DocumentAudit::logDocumentAction(
                    $document->id,
                    $user->id,
                    'scheduled-deletion',
                    'deleted',
                    'Document deleted by automatic deletion schedule'
                );
                
                // Delete document file from storage
                if ($document->path && Storage::disk('public')->exists($document->path)) {
                    Storage::disk('public')->delete($document->path);
                }
                
                // Delete attachment files from storage
                foreach ($document->attachments as $attachment) {
                    if ($attachment->path && Storage::disk('public')->exists($attachment->path)) {
                        Storage::disk('public')->delete($attachment->path);
                    }
                }
                
                // Delete the document record (will cascade to attachments via foreign key)
                $document->delete();
            } catch (\Exception $e) {
                \Log::error('Error deleting document in schedule: ' . $e->getMessage());
            }
        }
        
        // Update last executed timestamp
        $schedule->last_executed_at = Carbon::now();
        $schedule->save();
        
        return redirect()->route('admin.document-management.schedule')
            ->with('success', "{$count} documents have been deleted according to the schedule.");
    }
}
