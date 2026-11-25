<?php

namespace App\Models;

use App\Mail\PasswordResetMail;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;

class WebUser extends Model implements AuthenticatableContract, CanResetPasswordContract
{
    use Authenticatable;
    use CanResetPassword;
    use Notifiable;

    protected $table = 'web_user';

    protected $primaryKey = 'user_id';

    public $timestamps = false;

    protected $hidden = [
        'user_password',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        ];
    }

    public function hasVerifiedEmail(): bool
    {
        return $this->email_verified_at !== null;
    }

    public function markEmailAsVerified(): bool
    {
        return $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

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

    public function getEmailForPasswordReset(): string
    {
        return (string) $this->user_email;
    }

    public function sendPasswordResetNotification($token): void
    {
        $email = $this->getEmailForPasswordReset();
        $resetUrl = url(route('password.reset', [
            'token' => $token,
            'email' => $email,
        ], false));

        $host = request()?->getHost()
            ?: (parse_url((string) config('app.url'), PHP_URL_HOST) ?: 'localhost');

        Mail::to($email)->send(new PasswordResetMail(
            (string) $this->user_nickname,
            $resetUrl,
            (string) $host,
        ));
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
