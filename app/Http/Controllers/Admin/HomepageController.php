<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\Homepage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class HomepageController extends Controller
{
    public function index()
    {
        $settings = [];
        foreach (array_keys(Homepage::defaults()) as $key) {
            if (str_starts_with($key, 'about_')) {
                continue;
            }
            $settings[$key] = Homepage::get($key);
        }

        return view('admin.homepage.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'home_hero_title' => 'required|string|max:255',
            'home_hero_highlight' => 'nullable|string|max:80',
            'home_hero_subtitle' => 'nullable|string|max:500',
            'home_hero_btn1_text' => 'nullable|string|max:80',
            'home_hero_btn1_url' => 'nullable|string|max:255',
            'home_hero_btn2_text' => 'nullable|string|max:80',
            'home_hero_btn2_url' => 'nullable|string|max:255',
            'home_hero_image' => image_upload_rules(),
            'remove_home_hero_image' => 'nullable|boolean',
            'home_stat1_value' => 'nullable|string|max:40',
            'home_stat1_label' => 'nullable|string|max:80',
            'home_stat2_value' => 'nullable|string|max:40',
            'home_stat2_label' => 'nullable|string|max:80',
            'home_stat3_value' => 'nullable|string|max:40',
            'home_stat3_label' => 'nullable|string|max:80',
            'home_categories_title' => 'nullable|string|max:120',
            'home_best_selling_title' => 'nullable|string|max:120',
            'home_featured_title' => 'nullable|string|max:120',
            'home_popular_title' => 'nullable|string|max:120',
            'home_new_title' => 'nullable|string|max:120',
            'home_best_selling_limit' => 'required|integer|min:1|max:24',
            'home_featured_limit' => 'required|integer|min:1|max:24',
            'home_popular_limit' => 'required|integer|min:1|max:24',
            'home_new_limit' => 'required|integer|min:1|max:24',
            'home_banner1_title' => 'nullable|string|max:120',
            'home_banner1_text' => 'nullable|string|max:255',
            'home_banner1_url' => 'nullable|string|max:255',
            'home_banner2_title' => 'nullable|string|max:120',
            'home_banner2_text' => 'nullable|string|max:255',
            'home_banner2_url' => 'nullable|string|max:255',
            'home_banner3_title' => 'nullable|string|max:120',
            'home_banner3_text' => 'nullable|string|max:255',
            'home_banner3_url' => 'nullable|string|max:255',
            'home_newsletter_title' => 'nullable|string|max:255',
            'home_newsletter_text' => 'nullable|string|max:500',
            'home_feature_1_title' => 'nullable|string|max:80',
            'home_feature_1_text' => 'nullable|string|max:255',
            'home_feature_2_title' => 'nullable|string|max:80',
            'home_feature_2_text' => 'nullable|string|max:255',
            'home_feature_3_title' => 'nullable|string|max:80',
            'home_feature_3_text' => 'nullable|string|max:255',
            'home_feature_4_title' => 'nullable|string|max:80',
            'home_feature_4_text' => 'nullable|string|max:255',
            'home_feature_5_title' => 'nullable|string|max:80',
            'home_feature_5_text' => 'nullable|string|max:255',
        ]);

        foreach ([
            'home_show_categories',
            'home_show_best_selling',
            'home_show_banners',
            'home_show_featured',
            'home_show_popular',
            'home_show_new',
            'home_show_newsletter',
            'home_show_features',
        ] as $toggle) {
            Setting::set($toggle, $request->boolean($toggle) ? '1' : '0');
        }

        unset($validated['home_hero_image'], $validated['remove_home_hero_image']);

        foreach ($validated as $key => $value) {
            Setting::set($key, (string) ($value ?? ''));
        }

        $this->storeHeroImage($request);

        return redirect()->route('admin.homepage.index')
            ->with('success', 'Homepage updated successfully.');
    }

    protected function storeHeroImage(Request $request): void
    {
        if ($request->boolean('remove_home_hero_image') && ! $request->hasFile('home_hero_image')) {
            $this->deleteFile(Setting::get('home_hero_image'));
            Setting::set('home_hero_image', '');

            return;
        }

        if (! $request->hasFile('home_hero_image')) {
            return;
        }

        $this->deleteFile(Setting::get('home_hero_image'));

        $file = $request->file('home_hero_image');
        $filename = 'hero_'.time().'.'.$file->getClientOriginalExtension();
        Storage::disk('public')->makeDirectory('uploads/settings');

        $manager = new ImageManager(new Driver());
        $img = $manager->read($file->getRealPath());
        $img->scaleDown(1600, 900);
        Storage::disk('public')->put('uploads/settings/'.$filename, $img->encode());
        Setting::set('home_hero_image', $filename);
    }

    protected function deleteFile(?string $filename): void
    {
        if (! $filename) {
            return;
        }
        Storage::disk('public')->delete('uploads/settings/'.$filename);
    }
}
