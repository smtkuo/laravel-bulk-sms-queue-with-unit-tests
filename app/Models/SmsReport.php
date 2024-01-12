<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsReport extends Model
{
    use HasFactory;

    protected $table = 'sms_report';

    protected $fillable = [
        'number', 
        'message', 
        'send_time'
    ];
}
