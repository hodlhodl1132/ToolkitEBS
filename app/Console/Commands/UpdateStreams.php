<?php

namespace App\Console\Commands;

use App\Library\Services\TwitchApi;
use App\Models\Stream;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateStreams extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stream:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get live streams from Twitch API and update database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $liveStreams = TwitchApi::getStreams();
        if ($liveStreams == null)
        {
            $this->error('No live streams to get from Twitch API');
            return 1;
        }
        foreach ($liveStreams->data as $liveStream) {
            $this->info('Updating stream: ' . $liveStream->user_name);
            $stream = Stream::where('channel_id', $liveStream->user_id)->first();
            if ($stream != null) {
                $stream->user_name = $liveStream->user_name;
                $stream->title = $liveStream->title;
                $stream->language = $liveStream->language;
                $stream->thumbnail_url = $liveStream->thumbnail_url;
                $stream->viewer_count = $liveStream->viewer_count;
                $stream->save();
            } else {
                $this->error('Could not find Stream for: ' . $liveStream->user_name);
            }
        }

        return 0;
    }
}
