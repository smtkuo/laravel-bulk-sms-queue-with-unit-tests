<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    use HasFactory;

    protected $table = 'sms_logs';

    protected $fillable = [
        'user_id', 
        'phone_number', 
        'message', 
        'status', 
        'sent_at'
    ];

    /**
     * @return BelongsTo
     * 
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
