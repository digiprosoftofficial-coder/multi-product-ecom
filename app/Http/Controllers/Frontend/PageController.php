<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\ContactMessage as ContactMessageMail;
use App\Models\ContactMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
        return view('frontend.contact', [
            'mapUrl' => $this->contactMapUrl(),
        ]);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'subject' => 'nullable|string|max:160',
            'message' => 'required|string|max:5000',
        ]);

        $recipient = setting('contact_email') ?: config('mail.from.address');

        ContactMessage::create($validated);

        if ($recipient) {
            try {
                Mail::to($recipient)->send(new ContactMessageMail($validated));
            } catch (\Throwable $e) {
                Log::warning('Contact form mail failed: '.$e->getMessage());
            }
        }

        return redirect()->route('contact')
            ->with('success', 'Thank you! Your message has been sent. We will get back to you soon.');
    }

    protected function contactMapUrl(): ?string
    {
        $custom = trim((string) setting('contact_map_url'));

        if ($custom !== '') {
            return $custom;
        }

        $address = trim((string) setting('contact_address'));

        if ($address === '') {
            return null;
        }

        return 'https://maps.google.com/maps?q='.urlencode($address).'&t=&z=14&ie=UTF8&iwloc=&output=embed';
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
