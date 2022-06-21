<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'channel_id',
        'channel_name'
    ];

    /**
     * The link to the Stream Channel
     *
     * @var string
     */
    public function channelLink()
    {
        return 'https://www.twitch.tv/' . $this->user_name;
    }

    /**
     * The filtered image thumbnail for the Stream
     *
     * @var string
     */
    public function thumbnailLink()
    {
        $thumbnail_url = str_replace('{width}', '320', $this->thumbnail_url);
        $thumbnail_url = str_replace('{height}', '180', $thumbnail_url);
        return $thumbnail_url;
    }
}
