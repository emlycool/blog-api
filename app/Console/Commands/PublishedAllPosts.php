<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;

class PublishedAllPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish all posts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = 0;
        $posts = Post::whereNull('publish_at')->update(['publish_at' => now()]);
        // dump($count = $posts->count());
        \dump("done");
    }
}
