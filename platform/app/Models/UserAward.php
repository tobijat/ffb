<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserAward extends Model
{
    protected $table = 'ffb_user_award';

    protected $primaryKey = 'user_award_id';

    public $timestamps = false;

    public function defines(): HasMany
    {
        return $this->hasMany(UserAwardDefines::class, 'user_award_defines_award_id', 'user_award_id');
    }
}
