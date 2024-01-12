<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiAccessLog extends Model
{
    use HasFactory;

    protected $table = 'api_access_logs';

    protected $fillable = [
        'user_id', 
        'endpoint', 
        'method', 
        'ip_address', 
        'response_status'
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
