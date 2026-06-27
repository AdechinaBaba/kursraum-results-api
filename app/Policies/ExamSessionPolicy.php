<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ExamSession;

class ExamSessionPolicy
{
    /**
     * Voir toutes les sessions
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'secretary']);
    }

    /**
     * Voir une session spécifique
     */
    public function view(User $user, ExamSession $session): bool
    {
        // Super admin voit tout
        if ($user->role === 'super_admin') {
            return true;
        }

        // Secretary voit seulement ses sessions
        if ($user->role === 'secretary') {
            return $user->center_id === $session->center_id;
        }

        return false;
    }

    /**
     * Créer une session
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'secretary']);
    }

    /**
     * Mettre à jour une session
     */
    public function update(User $user, ExamSession $session): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'secretary') {
            return $user->center_id === $session->center_id;
        }

        return false;
    }

    /**
     * Supprimer une session
     */
    public function delete(User $user, ExamSession $session): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        // Une secrétaire ne peut pas supprimer une session
        return false;
    }

    /**
     * Exporter une session en PDF
     */
    public function export(User $user, ExamSession $session): bool
    {
        // Super admin peut tout exporter
        if ($user->role === 'super_admin') {
            return true;
        }

        // Secretary ne peut exporter que ses sessions
        if ($user->role === 'secretary') {
            return $user->center_id === $session->center_id;
        }

        return false;
    }

    /**
     * Importer des résultats dans une session
     */
    public function import(User $user, ExamSession $session): bool
    {
        // Super admin peut importer dans toutes les sessions
        if ($user->role === 'super_admin') {
            return true;
        }

        // Secretary ne peut importer que dans ses sessions
        if ($user->role === 'secretary') {
            return $user->center_id === $session->center_id;
        }

        return false;
    }
}