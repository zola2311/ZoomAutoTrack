<?php

namespace App\Policies;

use App\Models\TeamMember;
use App\Models\User;

class TeamMemberPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('team_members.view_any');
    }

    public function view(User $user, TeamMember $member): bool
    {
        return $user->can('team_members.view_any');
    }

    public function create(User $user): bool
    {
        return $user->can('team_members.create');
    }

    public function update(User $user, TeamMember $member): bool
    {
        return $user->can('team_members.update');
    }

    public function delete(User $user, TeamMember $member): bool
    {
        return $user->can('team_members.delete');
    }

    public function restore(User $user, TeamMember $member): bool
    {
        return $user->can('team_members.restore');
    }

    public function forceDelete(User $user, TeamMember $member): bool
    {
        return $user->can('team_members.force_delete');
    }
}
