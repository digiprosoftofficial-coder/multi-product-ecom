<?php

namespace App\Support;

use Illuminate\Support\Str;

class Seo
{
    public static function defaultDescription(): string
    {
        $desc = trim((string) setting('seo_meta_description', ''));
        if ($desc !== '') {
            return Str::limit($desc, 160);
        }

        $footer = trim((string) setting('footer_text', ''));
        if ($footer !== '') {
            return Str::limit($footer, 160);
        }

        return site_name();
    }

    public static function currencyCode(): string
    {
        $code = strtoupper(trim((string) setting('currency_code', 'USD')));

        return $code !== '' ? $code : 'USD';
    }

    public static function ogImageUrl(): ?string
    {
        return setting_image_url(setting('seo_og_image')) ?: site_logo_url();
    }

    public static function excerpt(?string $html, string $fallback, int $limit = 160): string
    {
        $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $html)));

        return Str::limit($text !== '' ? $text : $fallback, $limit);
    }

    public static function breadcrumbJsonLd(array $items): array
    {
        $elements = [];
        $position = 1;

        foreach ($items as $item) {
            $name = trim((string) ($item['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $entry = [
                '@type' => 'ListItem',
                'position' => $position++,
                'name' => $name,
            ];

            if (! empty($item['url'])) {
                $entry['item'] = $item['url'];
            }

            $elements[] = $entry;
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $elements,
        ];
    }

    public static function organizationJsonLd(): array
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => site_name(),
            'url' => url('/'),
        ];

        if ($logo = site_logo_url()) {
            $data['logo'] = $logo;
        }

        $contactPoints = [];

        if ($phone = trim((string) setting('contact_phone', ''))) {
            $contactPoints[] = [
                '@type' => 'ContactPoint',
                'telephone' => $phone,
                'contactType' => 'customer service',
            ];
        }

        if ($email = trim((string) setting('contact_email', ''))) {
            $contactPoints[] = [
                '@type' => 'ContactPoint',
                'email' => $email,
                'contactType' => 'customer service',
            ];
        }

        if ($contactPoints) {
            $data['contactPoint'] = count($contactPoints) === 1 ? $contactPoints[0] : $contactPoints;
        }

        return $data;
    }

    public static function webSiteJsonLd(): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => site_name(),
            'url' => url('/'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => route('products.index').'?search={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }
}
