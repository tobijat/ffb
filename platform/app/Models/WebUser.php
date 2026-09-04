<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WebUser extends Model
{
    protected $table = 'web_user';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $hidden = [
        'user_password',
        'user_activation_code',
    ];

    public function details(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'user_id', 'user_id');
    }

    public function userteams(): HasMany
    {
        return $this->hasMany(Userteam::class, 'userteam_user_id', 'user_id');
    }
}
