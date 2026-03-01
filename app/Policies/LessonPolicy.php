<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;

class LessonPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdminOrEditor();
    }

    public function view(User $user, Lesson $lesson): bool
    {
        return $user->isAdminOrEditor();
    }

    public function create(User $user): bool
    {
        return $user->isAdminOrEditor();
    }

    public function update(User $user, Lesson $lesson): bool
    {
        return $user->isAdminOrEditor();
    }

    /** Only admins can delete lessons. */
    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }
}
