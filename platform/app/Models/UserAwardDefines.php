<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAwardDefines extends Model
{
    protected $table = 'ffb_user_award_defines';

    protected $primaryKey = 'user_award_defines_id';

    public $timestamps = false;

    protected $fillable = [
        'user_award_defines_award_id',
        'user_award_defines_rank',
        'user_award_defines_rank_name',
        'user_award_defines_aim',
        'user_award_defines_aim_dbtable',
        'user_award_defines_aim_operator',
        'user_award_defines_aim_count',
        'user_award_defines_aim_automatic',
        'user_award_defines_aim_function_name',
        'user_award_defines_image',
        'user_award_defines_description',
    ];

    public function award(): BelongsTo
    {
        return $this->belongsTo(UserAward::class, 'user_award_defines_award_id', 'user_award_id');
    }

    public function finished(): HasMany
    {
        return $this->hasMany(UserAwardFinished::class, 'user_award_finished_award_defines_id', 'user_award_defines_id');
    }
}
