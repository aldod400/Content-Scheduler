<?php

namespace App\Console\Commands;

use App\Jobs\PublishPostJob;
use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all scheduled posts whose time has come';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::where('status', 'scheduled')
            ->where('scheduled_time', '<=', now())
            ->get();

        foreach ($posts as $post) {
            PublishPostJob::dispatch($post);
            $this->info("Dispatched post #{$post->id}");
        }

        return Command::SUCCESS;
    }
}
