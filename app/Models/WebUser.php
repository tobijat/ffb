<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WebUser extends Model implements AuthenticatableContract
{
    use Authenticatable;

    protected $table = 'web_user';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $hidden = [
        'user_password',
        'user_activation_code',
    ];

    public function getAuthPassword(): string
    {
        return (string) $this->user_password;
    }

    public function getAuthPasswordName(): string
    {
        return 'user_password';
    }

    /**
     * web_user has no remember_token column; remember-me is unused.
     */
    public function getRememberToken(): ?string
    {
        return null;
    }

    public function setRememberToken($value): void
    {
        // no-op
    }

    public function getRememberTokenName(): string
    {
        return '';
    }

    public function details(): HasOne
    {
        return $this->hasOne(UserDetails::class, 'user_id', 'user_id');
    }

    public function permissions(): HasOne
    {
        return $this->hasOne(UserPermissions::class, 'user_id', 'user_id');
    }

    public function userteams(): HasMany
    {
        return $this->hasMany(Userteam::class, 'userteam_user_id', 'user_id');
    }
}
