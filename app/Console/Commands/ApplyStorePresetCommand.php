<?php

namespace App\Console\Commands;

use App\Support\StorePreset;
use Illuminate\Console\Command;

class ApplyStorePresetCommand extends Command
{
    protected $signature = 'store:preset {preset : grocery, gadget, or fashion} {--demo-products : Also create a few sample products}';

    protected $description = 'Apply a boxed-store niche preset (theme, homepage copy, categories)';

    public function handle(): int
    {
        $preset = strtolower((string) $this->argument('preset'));

        if (! in_array($preset, StorePreset::slugs(), true)) {
            $this->error('Unknown preset. Use: '.implode(', ', StorePreset::slugs()));

            return self::FAILURE;
        }

        $result = StorePreset::apply($preset, $this->option('demo-products'));

        $this->info('Applied preset: '.$result['slug']);
        $this->line('Theme: '.$result['theme']);
        $this->line('Categories: '.implode(', ', $result['categories']));
        if ($result['products'] > 0) {
            $this->line('Demo products: '.$result['products']);
        }

        $this->comment('Existing categories/products were not deleted. Review homepage and theme in admin.');

        return self::SUCCESS;
    }
}
