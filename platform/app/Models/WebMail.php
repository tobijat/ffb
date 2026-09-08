<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebMail extends Model
{
    protected $table = 'web_mail';

    protected $primaryKey = 'mail_id';

    public $timestamps = false;

    protected $fillable = [
        'mail_date',
        'mail_sender',
        'mail_to',
        'mail_cc',
        'mail_bc',
        'mail_subject',
        'mail_text',
        'mail_num_reciepients',
        'mail_criteria',
    ];
}
