<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Teamprice extends Model
{
    protected $table = 'ffb_teamprice';

    protected $primaryKey = 'teamprice_id';

    public $timestamps = false;

    protected $fillable = [
        'teamprice_team_id',
        'teamprice_matchround_id',
        'teamprice_price',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'teamprice_team_id', 'team_id');
    }

    public function matchround(): BelongsTo
    {
        return $this->belongsTo(Matchround::class, 'teamprice_matchround_id', 'matchround_id');
    }
}
