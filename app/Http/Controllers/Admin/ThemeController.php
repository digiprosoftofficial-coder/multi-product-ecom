<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
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
            return view('admin.themes.index', [
                'themes' => [],
                'activeTheme' => setting('active_frontend_theme', 'organic-v1'),
            ]);
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

        return view('admin.themes.index', compact('themes', 'activeTheme'));
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
