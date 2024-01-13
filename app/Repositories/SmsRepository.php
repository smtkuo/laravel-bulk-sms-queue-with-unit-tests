<?php 

namespace App\Repositories;

use App\Models\Sms;
use Illuminate\Database\Eloquent\Builder;

class SmsRepository
{
    /**
     * @param array $data
     * 
     * @return Sms
     * 
     */
    public function create(array $data): Sms
    {
        return Sms::create($data);
    }

    /**
     * @return Builder
     * 
     */
    public function query(): Builder
    {
        return Sms::query();
    }
}
