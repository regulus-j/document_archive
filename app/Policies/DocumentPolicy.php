<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use App\Services\DocumentAccessService;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    protected $documentAccessService;

    public function __construct(DocumentAccessService $documentAccessService)
    {
        $this->documentAccessService = $documentAccessService;
    }

    /**
     * Determine whether the user can view the document.
     */
    public function view(User $user, Document $document): bool
    {
        return $this->documentAccessService->canViewDocument($document, $user);
    }

    /**
     * Determine whether the user can edit the document.
     */
    public function update(User $user, Document $document): bool
    {
        return $this->documentAccessService->canEditDocument($document, $user);
    }

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        return $this->documentAccessService->canDeleteDocument($document, $user);
    }

    /**
     * Determine whether the user can restore the document.
     */
    public function restore(User $user, Document $document): bool
    {
        // Only document owners can restore their documents
        return $document->uploader === $user->id;
    }
}
