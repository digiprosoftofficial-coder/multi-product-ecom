<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.about") ? "frontend.{$theme}.about" : 'frontend.about';
        return view($view);
    }

    public function contact()
    {
        $theme = setting('active_frontend_theme', 'organic-v1');
        $view = \Illuminate\Support\Facades\View::exists("frontend.{$theme}.contact") ? "frontend.{$theme}.contact" : 'frontend.contact';
        return view($view);
    }
}
