<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\TeamMember;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('site.about');
    }

    public function team(): View
    {
        return view('site.team', [
            'team' => TeamMember::active()->orderBy('sort_order')->get(),
        ]);
    }

    public function gallery(): View
    {
        return view('site.gallery', [
            'gallery' => GalleryImage::active()->orderBy('sort_order')->get(),
        ]);
    }
}
