<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class News extends Model
{
    protected $table = 'ffb_news';

    protected $primaryKey = 'news_id';

    public $timestamps = false;

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class, 'news_game_id', 'game_id');
    }
}
