<?php

namespace App\Policies;

use App\Models\Training;
use App\Models\User;

class TrainingPolicy
{
    /** Herkese açık eğitim listesi. */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /** Eğitim detayını görüntüleme. */
    public function view(?User $user, Training $training): bool
    {
        return true;
    }

    /** Gated alanlara erişim: meeting_url ve resources_url */
    public function viewGatedContent(User $user, Training $training): bool
    {
        if (!$training->is_premium_only) {
            return true;
        }
        return $user->isPremium();
    }

    /** Admin veya Editor oluşturabilir. */
    public function create(User $user): bool
    {
        return $user->isAdminOrEditor();
    }

    /** Admin veya Editor güncelleyebilir. */
    public function update(User $user, Training $training): bool
    {
        return $user->isAdminOrEditor();
    }

    /** Sadece Admin silebilir (soft delete dahil). */
    public function delete(User $user, ?Training $training = null): bool
    {
        return $user->isAdmin();
    }

    /** Sadece Admin geri yükleyebilir. */
    public function restore(User $user, ?Training $training = null): bool
    {
        return $user->isAdmin();
    }

    /** Sadece Admin kalıcı silebilir. */
    public function forceDelete(User $user, ?Training $training = null): bool
    {
        return $user->isAdmin();
    }
}
