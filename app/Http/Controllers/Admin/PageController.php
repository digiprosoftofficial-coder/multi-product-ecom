<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Homepage;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $pages = [
            'about_title' => Homepage::get('about_title') ?: 'About '.site_name(),
            'about_content' => Setting::get('about_content', ''),
            'privacy_content' => Setting::get('privacy_content', ''),
            'terms_content' => Setting::get('terms_content', ''),
        ];

        return view('admin.pages.index', compact('pages'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'about_title' => 'nullable|string|max:255',
            'about_content' => 'nullable|string',
            'privacy_content' => 'nullable|string',
            'terms_content' => 'nullable|string',
        ]);

        Setting::set('about_title', $validated['about_title'] ?? '');
        Setting::set('about_content', sanitize_rich_text($validated['about_content'] ?? null) ?? '');
        Setting::set('privacy_content', sanitize_rich_text($validated['privacy_content'] ?? null) ?? '');
        Setting::set('terms_content', sanitize_rich_text($validated['terms_content'] ?? null) ?? '');

        return redirect()->route('admin.pages.index')
            ->with('success', 'Pages updated successfully.');
    }
}
