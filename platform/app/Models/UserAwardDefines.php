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

    public function award(): BelongsTo
    {
        return $this->belongsTo(UserAward::class, 'user_award_defines_award_id', 'user_award_id');
    }

    public function finished(): HasMany
    {
        return $this->hasMany(UserAwardFinished::class, 'user_award_finished_award_defines_id', 'user_award_defines_id');
    }
}
