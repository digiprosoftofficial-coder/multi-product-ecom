<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Support\OwnerAccess;
use Illuminate\Http\Request;

class OwnerAccessController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);

        $features = [];
        foreach (OwnerAccess::features() as $id => $meta) {
            $features[$id] = array_merge($meta, [
                'enabled' => OwnerAccess::enabled($id),
            ]);
        }

        return view('admin.owner-access.index', compact('features'));
    }

    public function update(Request $request)
    {
        abort_unless(auth()->user()?->isSuperAdmin(), 403);

        foreach (array_keys(OwnerAccess::features()) as $feature) {
            OwnerAccess::set($feature, $request->boolean($feature));
        }

        return redirect()->route('admin.owner-access.index')
            ->with('success', 'Owner access updated.');
    }
}
