<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollResult extends Model
{
    protected $table = 'ffb_poll_result';

    protected $primaryKey = 'poll_result_id';

    public $timestamps = false;

    protected $fillable = [
        'poll_result_poll_id',
        'poll_result_user_id',
        'poll_result_poll_answer_id',
        'poll_result_text',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class, 'poll_result_poll_id', 'poll_id');
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(PollAnswer::class, 'poll_result_poll_answer_id', 'poll_answer_id');
    }
}
