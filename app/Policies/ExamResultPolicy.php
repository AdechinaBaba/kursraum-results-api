<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ExamResult;

class ExamResultPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ExamResult $result): bool
    {
        // Super admin voit tout
        if ($user->role === 'super_admin') {
            return true;
        }

        // Secretary voit seulement son centre
        return $user->center_id === $result->examSession->center_id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'secretary']);
    }

    public function update(User $user, ExamResult $result): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->center_id === $result->examSession->center_id;
    }

    public function delete(User $user, ExamResult $result): bool
    {
        return $user->role === 'super_admin';
    }
}