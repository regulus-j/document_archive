<?php

namespace App\Services;

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DocumentAccessService
{
    /**
     * Check if a user can view document details
     *
     * @param Document $document
     * @param User|null $user
     * @return bool
     */
    public function canViewDocument(Document $document, User $user = null): bool
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        // Document uploader can always view their own documents
        if ($document->uploader === $user->id) {
            return true;
        }

        // Company admins can view all documents in their company
        if ($user->hasRole('company-admin')) {
            return $this->isInSameCompany($document, $user);
        }

        // Super admins can view everything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        // Check based on classification
        switch ($document->classification) {
            case 'Public':
                // Public documents can be viewed by anyone in the same company
                return $this->isInSameCompany($document, $user);

            case 'Office Only':
                // Office Only documents can be viewed by users in the same office as the uploader
                return $this->isInSameOffice($document, $user);

            case 'Custom Offices':
                // Custom Offices documents can be viewed by users in the allowed offices
                return $this->isInAllowedOffice($document, $user);

            default:
                // For backward compatibility with old "Private" classification
                if ($document->classification === 'Private') {
                    // Check if user has specific permissions through allowed viewers
                    return $document->allowedViewers()->where('user_id', $user->id)->exists();
                }
                return false;
        }
    }

    /**
     * Check if user is in same company as document uploader
     *
     * @param Document $document
     * @param User $user
     * @return bool
     */
    private function isInSameCompany(Document $document, User $user): bool
    {
        $documentUploaderCompanies = $document->user->companies()->pluck('id');
        $userCompanies = $user->companies()->pluck('id');

        return $documentUploaderCompanies->intersect($userCompanies)->isNotEmpty();
    }

    /**
     * Check if user is in same office as document uploader
     *
     * @param Document $document
     * @param User $user
     * @return bool
     */
    private function isInSameOffice(Document $document, User $user): bool
    {
        $documentUploaderOffices = $document->user->offices()->pluck('offices.id');
        $userOffices = $user->offices()->pluck('offices.id');

        return $documentUploaderOffices->intersect($userOffices)->isNotEmpty();
    }

    /**
     * Check if user is in an office that's allowed to view the document
     *
     * @param Document $document
     * @param User $user
     * @return bool
     */
    private function isInAllowedOffice(Document $document, User $user): bool
    {
        $allowedOfficeIds = $document->allowedOffices()->pluck('office_id');
        $userOfficeIds = $user->offices()->pluck('offices.id');

        return $allowedOfficeIds->intersect($userOfficeIds)->isNotEmpty();
    }

    /**
     * Get filtered documents based on user's access level
     *
     * @param User|null $user
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAccessibleDocuments(User $user = null)
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return Document::whereRaw('1 = 0'); // Return empty query
        }

        $query = Document::query();

        // Super admins can see everything
        if ($user->hasRole('super-admin')) {
            return $query;
        }

        // Company admins can see all documents in their companies
        if ($user->hasRole('company-admin')) {
            $userCompanies = $user->companies()->pluck('id');
            return $query->whereHas('user.companies', function ($q) use ($userCompanies) {
                $q->whereIn('company_accounts.id', $userCompanies);
            });
        }

        // Regular users: get documents they can access
        $userOffices = $user->offices()->pluck('id');
        $userCompanies = $user->companies()->pluck('id');

        return $query->where(function ($q) use ($user, $userOffices, $userCompanies) {
            // Documents uploaded by the user
            $q->where('uploader', $user->id)
            // OR Public documents in same company
            ->orWhere(function ($subQ) use ($userCompanies) {
                $subQ->where('classification', 'Public')
                     ->whereHas('user.companies', function ($companyQ) use ($userCompanies) {
                         $companyQ->whereIn('company_accounts.id', $userCompanies);
                     });
            })
            // OR Office Only documents in same office
            ->orWhere(function ($subQ) use ($userOffices) {
                $subQ->where('classification', 'Office Only')
                     ->whereHas('user.offices', function ($officeQ) use ($userOffices) {
                         $officeQ->whereIn('offices.id', $userOffices);
                     });
            })
            // OR Custom Offices documents where user's office is in allowed offices
            ->orWhere(function ($subQ) use ($userOffices) {
                $subQ->where('classification', 'Custom Offices')
                     ->whereHas('allowedOffices', function ($allowedOfficeQ) use ($userOffices) {
                         $allowedOfficeQ->whereIn('office_id', $userOffices);
                     });
            })
            // OR Private documents with specific permissions (backward compatibility)
            ->orWhere(function ($subQ) use ($user) {
                $subQ->where('classification', 'Private')
                     ->whereHas('allowedViewers', function ($viewerQ) use ($user) {
                         $viewerQ->where('user_id', $user->id);
                     });
            });
        });
    }

    /**
     * Get a human-readable description of document access level
     *
     * @param Document $document
     * @return string
     */
    public function getAccessDescription(Document $document): string
    {
        switch ($document->classification) {
            case 'Public':
                return 'Visible to all company users';
            case 'Office Only':
                return 'Visible to users in the same office';
            case 'Private':
                return 'Visible to selected users only';
            default:
                return 'Access level unknown';
        }
    }

    /**
     * Check if current user can edit a document
     *
     * @param Document $document
     * @param User|null $user
     * @return bool
     */
    public function canEditDocument(Document $document, User $user = null): bool
    {
        if (!$user) {
            $user = Auth::user();
        }

        if (!$user) {
            return false;
        }

        // Document uploader can edit their own documents
        if ($document->uploader === $user->id) {
            return true;
        }

        // Company admins can edit documents in their company
        if ($user->hasRole('company-admin')) {
            return $this->isInSameCompany($document, $user);
        }

        // Super admins can edit everything
        if ($user->hasRole('super-admin')) {
            return true;
        }

        return false;
    }
}
