<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactMessageController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactMessage::query()->latest();

        if ($search = trim((string) $request->get('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        if ($request->get('status') === 'unread') {
            $query->where('is_read', false);
        } elseif ($request->get('status') === 'read') {
            $query->where('is_read', true);
        }

        $messages = $query->paginate(20)->withQueryString();
        $unreadCount = ContactMessage::where('is_read', false)->count();

        if ($request->ajax()) {
            return view('admin.contact-messages.partials.results', compact('messages'))->render();
        }

        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }

    public function show(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();

        return view('admin.contact-messages.show', [
            'message' => $contactMessage,
        ]);
    }

    public function destroy(ContactMessage $contactMessage)
    {
        $contactMessage->delete();

        return redirect()->route('admin.contact-messages.index')
            ->with('success', 'Message deleted successfully.');
    }

    public function markRead(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();

        return redirect()->back()->with('success', 'Marked as read.');
    }

    public function markUnread(ContactMessage $contactMessage)
    {
        $contactMessage->forceFill(['is_read' => false])->save();

        return redirect()->back()->with('success', 'Marked as unread.');
    }
}
