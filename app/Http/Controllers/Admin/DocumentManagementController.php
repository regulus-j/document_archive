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
        
        // Build query with proper company filter
        $query = Document::with('status');
        
        if (Schema::hasColumn('documents', 'company_id')) {
            $query->where('company_id', $company->id);
        } else {
            // Filter by company through users relationship
            $query->whereHas('user.companies', function($q) use ($company) {
                $q->where('companies.id', $company->id);
            });
        }
        
        // Only delete non-archived documents
        $query->whereHas('status', function($q) {
            $q->where('status', '!=', 'archived');
        });
            
        if ($request->date_from) {
            $query->where('created_at', '>=', Carbon::parse($request->date_from));
        }
        
        if ($request->date_to) {
            $query->where('created_at', '<=', Carbon::parse($request->date_to)->endOfDay());
        }
        
        // Get count before deletion for message
        $count = $query->count();
        
        if ($count === 0) {
            return redirect()->route('admin.document-management.documents')
                ->with('info', 'No documents found matching the deletion criteria.');
        }
        
        // Get document IDs for logging
        $documentIds = $query->pluck('id')->toArray();
        
        // Log the deletion for each document
        foreach ($documentIds as $docId) {
            DocumentAudit::logDocumentAction(
                $docId,
                $user->id,
                'bulk-delete',
                'deleted',
                'Document deleted as part of bulk operation by company admin'
            );
        }
        
        // Delete the documents
        $query->delete();
        
        return redirect()->route('admin.document-management.documents')
            ->with('success', "{$count} documents have been deleted.");
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
        $validated = $request->validate([
            'criteria' => ['required', Rule::in(['age', 'storage', 'both'])],
            'retention_days' => 'required_if:criteria,age,both|integer|min:1',
            'storage_limit_mb' => 'required_if:criteria,storage,both|integer|min:1',
            'is_active' => 'boolean',
        ]);
        
        $user = Auth::user();
        $company = $user->companies()->first();
        
        if (!$company) {
            return redirect()->route('dashboard')
                ->with('error', 'You do not have a company associated with your account.');
        }
        
        $schedule = DocumentDeletionSchedule::updateOrCreate(
            ['company_id' => $company->id],
            [
                'retention_days' => $request->retention_days ?? 365,
                'storage_limit_mb' => $request->storage_limit_mb,
                'criteria' => $request->criteria,
                'is_active' => $request->has('is_active'),
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
        $baseQuery = Document::with('status');
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
                if (Schema::hasColumn('documents', 'storage_size')) {
                    $storageUsageQuery = clone $baseQuery;
                    $storageUsage = $storageUsageQuery->sum('storage_size');
                }
                
                $storageUsageMB = round($storageUsage / 1024 / 1024, 2);
                
                if ($storageUsageMB >= $schedule->storage_limit_mb) {
                    // Get oldest documents first that aren't archived, until we get below the limit
                    $deleteQuery = clone $baseQuery;
                    $documentsToDelete = $deleteQuery->orderBy('created_at')->get();
                    
                    $currentSize = $storageUsageMB;
                    $storageDocuments = collect();
                    
                    foreach ($documentsToDelete as $doc) {
                        if ($currentSize <= $schedule->storage_limit_mb) {
                            break;
                        }
                        
                        $docSizeMB = $doc->storage_size ? ($doc->storage_size / 1024 / 1024) : 0;
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
            DocumentAudit::logDocumentAction(
                $document->id,
                $user->id,
                'scheduled-deletion',
                'deleted',
                'Document deleted by automatic deletion schedule'
            );
            
            $document->delete();
        }
        
        // Update last executed timestamp
        $schedule->last_executed_at = Carbon::now();
        $schedule->save();
        
        return redirect()->route('admin.document-management.schedule')
            ->with('success', "{$count} documents have been deleted according to the schedule.");
    }
}
