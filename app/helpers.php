<?php

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }
}

if (!function_exists('site_name')) {
    function site_name(): string
    {
        return (string) setting('site_name', config('app.name', 'Store'));
    }
}

if (!function_exists('setting_image_url')) {
    function setting_image_url(?string $filename): ?string
    {
        if (! $filename) {
            return null;
        }

        $path = 'uploads/settings/'.$filename;

        if (Storage::disk('public')->exists($path)) {
            $version = Storage::disk('public')->lastModified($path);

            return '/storage/'.$path.($version ? '?v='.$version : '');
        }

        if (file_exists(public_path($path))) {
            return asset($path);
        }

        return null;
    }
}

if (!function_exists('site_logo_url')) {
    function site_logo_url(): ?string
    {
        return setting_image_url(setting('site_logo'));
    }
}

if (!function_exists('footer_logo_url')) {
    function footer_logo_url(): ?string
    {
        return setting_image_url(setting('footer_logo')) ?: site_logo_url();
    }
}

if (!function_exists('favicon_url')) {
    function favicon_url(): ?string
    {
        return setting_image_url(setting('favicon')) ?: site_logo_url();
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        $symbol = trim((string) setting('currency_symbol', '$'));

        return $symbol !== '' ? $symbol : '$';
    }
}

if (!function_exists('money')) {
    function money($amount): string
    {
        return currency_symbol().number_format((float) $amount, 2);
    }
}

if (!function_exists('image_upload_rules')) {
    function image_upload_rules(int $maxKb = 10240, array $extensions = ['jpg', 'jpeg', 'jfif', 'png', 'gif', 'webp']): array
    {
        return [
            'nullable',
            'file',
            'max:'.$maxKb,
            function (string $attribute, $value, $fail) use ($extensions) {
                if (! $value instanceof \Illuminate\Http\UploadedFile) {
                    return;
                }

                if (! $value->isValid()) {
                    $fail('The '.$attribute.' failed to upload. Use a JPG, PNG, GIF, or WEBP file under 10MB.');

                    return;
                }

                $ext = strtolower($value->getClientOriginalExtension());
                $mime = (string) $value->getMimeType();
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

                if (! in_array($ext, $extensions, true) && ! in_array($mime, $allowedMimes, true)) {
                    $fail('The '.$attribute.' must be a JPG, PNG, GIF, or WEBP image.');

                    return;
                }

                $path = $value->getRealPath();
                if (! $path || @getimagesize($path) === false) {
                    $fail('The '.$attribute.' must be a valid image.');
                }
            },
        ];
    }
}

if (!function_exists('compare_price_enabled')) {
    function compare_price_enabled(): bool
    {
        return (string) setting('enable_compare_price', '1') === '1';
    }
}

if (!function_exists('sanitize_rich_text')) {
    function sanitize_rich_text(?string $html): ?string
    {
        if ($html === null) {
            return null;
        }

        $html = trim($html);
        if ($html === '') {
            return null;
        }

        $plain = trim(html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $plain = str_replace("\xC2\xA0", ' ', $plain);
        if ($plain === '') {
            return null;
        }

        $allowedTags = [
            'p' => [],
            'br' => [],
            'strong' => [],
            'b' => [],
            'em' => [],
            'i' => [],
            'u' => [],
            'h2' => [],
            'h3' => [],
            'ul' => [],
            'ol' => [],
            'li' => [],
            'blockquote' => [],
            'a' => ['href', 'title', 'target', 'rel'],
            'table' => [],
            'thead' => [],
            'tbody' => [],
            'tr' => [],
            'th' => [],
            'td' => [],
        ];

        $dom = new DOMDocument();
        $dom->preserveWhiteSpace = false;
        libxml_use_internal_errors(true);
        $loaded = $dom->loadHTML(
            '<?xml encoding="UTF-8"><body>'.$html.'</body>',
            LIBXML_HTML_NODEFDTD
        );
        libxml_clear_errors();

        if (! $loaded) {
            return e($plain);
        }

        $root = $dom->getElementsByTagName('body')->item(0);
        if (! $root) {
            return e($plain);
        }

        $cleanNode = function (DOMNode $node) use (&$cleanNode, $allowedTags): void {
            $child = $node->firstChild;
            while ($child) {
                $next = $child->nextSibling;

                if ($child instanceof DOMComment) {
                    $node->removeChild($child);
                    $child = $next;
                    continue;
                }

                if ($child instanceof DOMElement) {
                    $tag = strtolower($child->tagName);
                    if (! array_key_exists($tag, $allowedTags)) {
                        while ($child->firstChild) {
                            $node->insertBefore($child->firstChild, $child);
                        }
                        $node->removeChild($child);
                        $child = $node->firstChild;
                        continue;
                    }

                    $allowed = $allowedTags[$tag];
                    $remove = [];
                    foreach (iterator_to_array($child->attributes ?? []) as $attribute) {
                        $name = strtolower($attribute->name);
                        if (! in_array($name, $allowed, true)) {
                            $remove[] = $attribute->name;
                        }
                    }
                    foreach ($remove as $name) {
                        $child->removeAttribute($name);
                    }

                    if ($tag === 'a') {
                        $href = $child->getAttribute('href');
                        if ($href === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $href)) {
                            $child->removeAttribute('href');
                        }
                        if ($child->getAttribute('target') === '_blank') {
                            $child->setAttribute('rel', 'noopener noreferrer');
                        }
                    }

                    $cleanNode($child);
                }

                $child = $next;
            }
        };

        $cleanNode($root);

        $output = '';
        foreach ($root->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }

        $output = trim($output);

        return $output === '' ? null : $output;
    }
}
