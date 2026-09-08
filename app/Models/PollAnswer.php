<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollAnswer extends Model
{
    protected $table = 'ffb_poll_answer';

    protected $primaryKey = 'poll_answer_id';

    public $timestamps = false;

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_answer_poll_id', 'poll_id');
    }
}
