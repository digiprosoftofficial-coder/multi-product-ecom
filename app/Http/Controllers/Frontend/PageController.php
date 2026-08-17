<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;

class PageController extends Controller
{
    public function about()
    {
        return view('frontend.about', [
            'title' => filled($title = \App\Support\Homepage::get('about_title')) ? $title : 'About '.site_name(),
            'content' => setting('about_content'),
        ]);
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function privacy()
    {
        return view('frontend.privacy', [
            'content' => setting('privacy_content'),
        ]);
    }

    public function terms()
    {
        return view('frontend.terms', [
            'content' => setting('terms_content'),
        ]);
    }
}
