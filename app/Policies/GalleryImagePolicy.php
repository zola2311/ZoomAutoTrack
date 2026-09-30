<?php

namespace App\Policies;

use App\Models\GalleryImage;
use App\Models\User;

class GalleryImagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('gallery_images.view_any');
    }

    public function view(User $user, GalleryImage $image): bool
    {
        return $user->can('gallery_images.view_any');
    }

    public function create(User $user): bool
    {
        return $user->can('gallery_images.create');
    }

    public function update(User $user, GalleryImage $image): bool
    {
        return $user->can('gallery_images.update');
    }

    public function delete(User $user, GalleryImage $image): bool
    {
        return $user->can('gallery_images.delete');
    }

    public function restore(User $user, GalleryImage $image): bool
    {
        return $user->can('gallery_images.restore');
    }

    public function forceDelete(User $user, GalleryImage $image): bool
    {
        return $user->can('gallery_images.force_delete');
    }
}
