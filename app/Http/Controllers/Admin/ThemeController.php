<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Symfony\Component\HttpFoundation\Response;

class ThemeController extends Controller
{
    /** Base path for frontend theme views. */
    private function themesBasePath(): string
    {
        return resource_path('views/frontend');
    }

    /**
     * Scan frontend folder: valid theme = has index.blade.php AND theme.json.
     * Returns array of theme slug => metadata (from theme.json).
     */
    public function index()
    {
        $basePath = $this->themesBasePath();
        $themes = [];

        if (!is_dir($basePath)) {
            return view('admin.themes.index', array_merge([
                'themes' => [],
                'activeTheme' => setting('active_frontend_theme', 'organic-v1'),
                'adminThemeColor' => Setting::get('admin_theme_color', '#16a34a'),
            ], $this->authDesign()));
        }

        foreach (scandir($basePath) as $name) {
            if ($name === '.' || $name === '..' || !is_dir($basePath . DIRECTORY_SEPARATOR . $name)) {
                continue;
            }
            $indexPath = $basePath . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'index.blade.php';
            $metaPath = $basePath . DIRECTORY_SEPARATOR . $name . DIRECTORY_SEPARATOR . 'theme.json';
            if (!file_exists($indexPath) || !file_exists($metaPath)) {
                continue;
            }
            $meta = $this->readThemeJson($metaPath);
            $meta['slug'] = $name;
            $themes[$name] = $meta;
        }

        ksort($themes);
        $activeTheme = setting('active_frontend_theme', 'organic-v1');
        $adminThemeColor = Setting::get('admin_theme_color', '#16a34a');

        return view('admin.themes.index', array_merge(
            compact('themes', 'activeTheme', 'adminThemeColor'),
            $this->authDesign()
        ));
    }

    public function updateAuthDesign(Request $request)
    {
        $validated = $request->validate([
            'auth_page_color' => ['required', 'string', 'max:7', 'regex:/^#([0-9a-fA-F]{6})$/'],
            'auth_page_image' => image_upload_rules(),
            'remove_auth_page_image' => 'nullable|boolean',
        ]);

        Setting::set(
            'auth_page_color',
            \App\Support\Homepage::normalizeColor($validated['auth_page_color'], '#8d6b78')
        );

        $this->storeAuthImage($request);

        return redirect()->route('admin.themes.index')
            ->with('success', 'Login page design updated.');
    }

    private function authDesign(): array
    {
        return [
            'authPageColor' => Setting::get('auth_page_color', '#8d6b78'),
            'authPageImage' => setting_image_url(Setting::get('auth_page_image')),
        ];
    }

    private function storeAuthImage(Request $request): void
    {
        $current = Setting::get('auth_page_image');

        if ($request->boolean('remove_auth_page_image') && ! $request->hasFile('auth_page_image')) {
            $this->deleteAuthImage($current);
            Setting::set('auth_page_image', '');

            return;
        }

        if (! $request->hasFile('auth_page_image')) {
            return;
        }

        $this->deleteAuthImage($current);

        $file = $request->file('auth_page_image');
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $filename = 'auth_page_'.time().'.'.$extension;

        if (! Storage::disk('public')->exists('uploads/settings')) {
            Storage::disk('public')->makeDirectory('uploads/settings');
        }

        if ($file instanceof UploadedFile && $file->getRealPath() && ! in_array($extension, ['svg'], true)) {
            $manager = new ImageManager(new Driver());
            $img = $manager->read($file->getRealPath());
            $img->scaleDown(width: 1400, height: 1600);
            Storage::disk('public')->put('uploads/settings/'.$filename, $img->encode());
        } else {
            Storage::disk('public')->putFileAs('uploads/settings', $file, $filename);
        }

        Setting::set('auth_page_image', $filename);
    }

    private function deleteAuthImage(?string $filename): void
    {
        if (! $filename) {
            return;
        }

        $path = 'uploads/settings/'.$filename;
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function updateAdminColor(Request $request)
    {
        $validated = $request->validate([
            'admin_theme_color' => ['required', 'string', 'max:7', 'regex:/^#([0-9a-fA-F]{6})$/'],
        ]);

        Setting::set(
            'admin_theme_color',
            \App\Support\Homepage::normalizeColor($validated['admin_theme_color'], '#16a34a')
        );

        return redirect()->route('admin.themes.index')
            ->with('success', 'Admin theme color updated.');
    }

    private function readThemeJson(string $path): array
    {
        $default = [
            'name' => basename(dirname($path)),
            'slug' => basename(dirname($path)),
            'description' => '',
            'version' => '1.0.0',
            'author' => '',
            'preview' => 'preview.png',
            'supports' => ['light'],
            'deletable' => true,
        ];
        $content = @file_get_contents($path);
        if ($content === false) {
            return $default;
        }
        $decoded = json_decode($content, true);
        return is_array($decoded) ? array_merge($default, $decoded) : $default;
    }

    public function activate(Request $request)
    {
        $request->validate(['theme' => 'required|string|max:100|regex:/^[a-z0-9\-]+$/']);

        $slug = $request->input('theme');
        $basePath = $this->themesBasePath();
        $indexPath = $basePath . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . 'index.blade.php';
        $metaPath = $basePath . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . 'theme.json';

        if (!file_exists($indexPath) || !file_exists($metaPath)) {
            return back()->with('error', 'Theme cannot be activated: index.blade.php and theme.json are required.');
        }

        Setting::set('active_frontend_theme', $slug);
        Cache::flush();

        return redirect()->route('admin.themes.index')->with('success', 'Theme "' . ($slug) . '" activated.');
    }

    public function destroy(Request $request)
    {
        $request->validate(['theme' => 'required|string|max:100|regex:/^[a-z0-9\-]+$/']);

        $slug = $request->input('theme');
        $activeTheme = setting('active_frontend_theme', 'organic-v1');

        if ($slug === $activeTheme) {
            return back()->with('error', 'Cannot delete the active theme. Activate another theme first.');
        }

        $themePath = $this->themesBasePath() . DIRECTORY_SEPARATOR . $slug;
        if (!is_dir($themePath)) {
            return back()->with('error', 'Theme folder not found.');
        }

        $metaPath = $themePath . DIRECTORY_SEPARATOR . 'theme.json';
        if (file_exists($metaPath)) {
            $meta = $this->readThemeJson($metaPath);
            if (!($meta['deletable'] ?? true)) {
                return back()->with('error', 'This theme cannot be deleted (deletable is false).');
            }
        }

        File::deleteDirectory($themePath);

        return redirect()->route('admin.themes.index')->with('success', 'Theme "' . $slug . '" deleted.');
    }

    /** Serve theme preview image from views folder (admin only). */
    public function preview(string $slug): Response
    {
        if (!preg_match('/^[a-z0-9\-]+$/', $slug)) {
            abort(404);
        }
        $path = $this->themesBasePath() . DIRECTORY_SEPARATOR . $slug . DIRECTORY_SEPARATOR . 'preview.png';
        if (!file_exists($path) || !is_file($path)) {
            abort(404);
        }
        return response()->file($path, ['Content-Type' => 'image/png']);
    }
}
