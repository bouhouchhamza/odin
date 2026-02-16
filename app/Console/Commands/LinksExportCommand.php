<?php

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class LinksExportCommand extends Command
{
    protected $signature = 'links:export
        {--user= : User ID}
        {--from= : From date (Y-m-d)}
        {--to= : To date (Y-m-d)}
        {--path=storage/app/exports/links.csv : Output path}';

    protected $description = 'Export links to CSV';

    public function handle(): int
    {
        $path = base_path($this->option('path'));
        File::ensureDirectoryExists(dirname($path));

        $query = Link::query()->with(['category:id,name', 'tags:id,name']);

        if ($this->option('user')) {
            $query->where('user_id', (int) $this->option('user'));
        }

        if ($this->option('from')) {
            $query->whereDate('created_at', '>=', $this->option('from'));
        }

        if ($this->option('to')) {
            $query->whereDate('created_at', '<=', $this->option('to'));
        }

        $handle = fopen($path, 'w');
        fputcsv($handle, ['id', 'title', 'url', 'category', 'tags', 'created_at']);

        $query->chunk(200, function ($links) use ($handle) {
            foreach ($links as $link) {
                fputcsv($handle, [
                    $link->id,
                    $link->title,
                    $link->url,
                    optional($link->category)->name,
                    $link->tags->pluck('name')->implode('|'),
                    optional($link->created_at)?->toDateTimeString(),
                ]);
            }
        });

        fclose($handle);

        $this->info("Export completed: {$path}");

        return self::SUCCESS;
    }
}
