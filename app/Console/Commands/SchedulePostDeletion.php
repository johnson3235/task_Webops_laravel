<?php

namespace App\Console\Commands;
use App\Models\Post;
use Illuminate\Console\Command;

class SchedulePostDeletion extends Command
{
    protected $signature = 'schedule:post-deletion';
    protected $description = 'Schedule post deletion after 24 hours';

    public function handle()
    {
        Post::where('created_at', '<=', now()->subDay())->delete();
        $this->info('Scheduled post deletion completed.');
    }
}
