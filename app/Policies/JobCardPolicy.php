<?php

namespace App\Policies;

use App\Models\JobCard;
use App\Models\User;

class JobCardPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('job_cards.view_any');
    }

    public function view(User $user, JobCard $jobCard): bool
    {
        // Global permission to view all job cards
        if ($user->can('job_cards.view_all')) {
            return true;
        }

        // Permission to view only assigned job cards (e.g. Mechanics)
        if ($user->can('job_cards.view_own')) {
            return $jobCard->mechanic_id === $user->id;
        }

        return $user->can('job_cards.view_any');
    }

    public function create(User $user): bool
    {
        return $user->can('job_cards.create');
    }

    public function update(User $user, JobCard $jobCard): bool
    {
        // Permission to update any job card
        if ($user->can('job_cards.update_all')) {
            return true;
        }

        // Permission to update only assigned job cards
        if ($user->can('job_cards.update_own')) {
            return $jobCard->mechanic_id === $user->id;
        }

        return false;
    }

    public function delete(User $user, JobCard $jobCard): bool
    {
        return $user->can('job_cards.delete');
    }

    public function restore(User $user, JobCard $jobCard): bool
    {
        return $user->can('job_cards.restore');
    }

    public function forceDelete(User $user, JobCard $jobCard): bool
    {
        return $user->can('job_cards.force_delete');
    }
}
