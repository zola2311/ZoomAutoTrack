<?php

namespace App\Concerns;

use App\Models\Media;

trait HandlesMediaUploads
{
    /**
     * Sync uploaded file paths (from a Filament FileUpload field) into the
     * polymorphic media table for a given collection.
     *
     * @param array $paths Array of storage paths returned by FileUpload's state
     * @param string $collection e.g. 'before_photos', 'after_photos'
     * @param string $disk The disk the files were uploaded to
     */
    public function syncMediaCollection(array $paths, string $collection, string $disk = 'public_uploads'): void
    {
        $existing = $this->media()->where('collection', $collection)->pluck('path')->toArray();

        // Remove media rows for files no longer present
        $removed = array_diff($existing, $paths);
        if ($removed) {
            $this->media()->where('collection', $collection)->whereIn('path', $removed)->delete();
        }

        // Add media rows for new files
        $added = array_diff($paths, $existing);
        foreach ($added as $path) {
            $this->media()->create([
                'collection'    => $collection,
                'disk'          => $disk,
                'path'          => $path,
                'mime_type'     => \Illuminate\Support\Facades\Storage::disk($disk)->mimeType($path) ?: null,
                'size'          => \Illuminate\Support\Facades\Storage::disk($disk)->size($path) ?: null,
                'original_name' => basename($path),
                'uploaded_by'   => auth()->id(),
            ]);
        }
    }

    /**
     * Get just the storage paths for a given media collection —
     * used to populate a FileUpload field's default state.
     */
    public function getMediaCollectionPaths(string $collection): array
    {
        return $this->media()
            ->where('collection', $collection)
            ->orderBy('created_at')
            ->pluck('path')
            ->toArray();
    }

}
