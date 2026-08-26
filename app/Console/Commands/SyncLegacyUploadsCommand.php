<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class SyncLegacyUploadsCommand extends Command
{
    protected $signature = 'uploads:sync-legacy {--force : Overwrite files that already exist on the public disk}';

    protected $description = 'Copy uploads from storage/app/public into the public uploads disk (for production fix)';

    public function handle(): int
    {
        $legacy = Storage::disk('legacy_public');
        $public = Storage::disk('public');

        if (! $legacy->exists('uploads')) {
            $this->warn('No legacy uploads found at storage/app/public/uploads');

            return self::SUCCESS;
        }

        $files = $legacy->allFiles('uploads');
        $copied = 0;
        $skipped = 0;

        foreach ($files as $file) {
            if ($public->exists($file) && ! $this->option('force')) {
                $skipped++;
                continue;
            }

            $public->put($file, $legacy->get($file));
            $copied++;
        }

        $this->info("Copied {$copied} file(s), skipped {$skipped}.");

        return self::SUCCESS;
    }
}
