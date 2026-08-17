<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::get($key, $default);
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
