<?php

namespace App\Policies;

use App\Models\Response;
use App\Models\User;
use Illuminate\Auth\Access\HandlerResponse;

class ResponsePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Response $response): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Response $response): bool
    {
        // Use response_by instead of user_id to match your data model
    return $user->id === $response->response_by;
    }
    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Response $response): bool
    {
    // Use response_by instead of user_id to match your data model
    return $user->id === $response->response_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Response $response): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Response $response): bool
    {
        return false;
    }
}
