<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can delete the document.
     */
    public function delete(User $user, Document $document): bool
    {
        // Document owner can delete their document
        if ($document->user_id === $user->id) {
            return true;
        }

        // Company admin can delete any document in their company
        if ($user->isCompanyAdmin()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can restore the document.
     */
    public function restore(User $user, Document $document): bool
    {
        // Document owner can restore their document
        if ($document->user_id === $user->id) {
            return true;
        }

        // Company admin can restore any document in their company
        if ($user->isCompanyAdmin()) {
            return true;
        }        return false;
    }
}
