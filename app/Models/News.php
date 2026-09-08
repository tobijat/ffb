<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    protected $table = 'ffb_news';

    protected $primaryKey = 'news_id';

    public $timestamps = false;

    protected $fillable = [
        'news_title',
        'news_text',
        'news_symbol',
        'news_priority',
        'news_game_id',
        'news_date',
    ];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'news_game_id', 'game_id');
    }
}
