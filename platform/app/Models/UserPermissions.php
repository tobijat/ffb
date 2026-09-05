<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermissions extends Model
{
    protected $table = 'web_user_permissions';

    protected $primaryKey = 'user_id';

    public $incrementing = false;

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'user_id', 'user_id');
    }
}
