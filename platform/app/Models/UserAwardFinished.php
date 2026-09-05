<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAwardFinished extends Model
{
    protected $table = 'ffb_user_award_finished';

    protected $primaryKey = 'user_award_finished_id';

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'user_award_finished_user_id', 'user_id');
    }

    public function define(): BelongsTo
    {
        return $this->belongsTo(UserAwardDefines::class, 'user_award_finished_award_defines_id', 'user_award_defines_id');
    }
}
