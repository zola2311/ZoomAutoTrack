<div>
    @php
        $photos = $getRecord()->defectPhotos ?? collect();
    @endphp

    @if ($photos->isEmpty())
        <div class="text-sm text-gray-500 italic p-3 bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-700">
            No defect or vehicle pictures were uploaded with this inquiry.
        </div>
    @else
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
            @foreach ($photos as $photo)
                @php
                    $url = asset('uploads/' . $photo->path);
                @endphp
                <div class="relative group rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-900 shadow-sm hover:shadow-md transition">
                    <a href="{{ $url }}" target="_blank" title="Click to view full image in new tab">
                        <img src="{{ $url }}" 
                             alt="{{ $photo->original_name }}" 
                             class="w-full h-32 object-cover hover:opacity-90 transition">
                    </a>
                    <div class="p-1.5 bg-white dark:bg-gray-800 text-[11px] truncate flex justify-between items-center">
                        <span class="truncate text-gray-700 dark:text-gray-300 font-medium" title="{{ $photo->original_name }}">
                            {{ $photo->original_name }}
                        </span>
                        <a href="{{ $url }}" target="_blank" class="text-primary-600 hover:underline flex-shrink-0 ml-1">
                            View ↗
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
