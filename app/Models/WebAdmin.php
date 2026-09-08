<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebAdmin extends Model
{
    protected $table = 'web_admin';

    protected $primaryKey = 'admin_id';

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(WebUser::class, 'admin_user_id', 'user_id');
    }
}
