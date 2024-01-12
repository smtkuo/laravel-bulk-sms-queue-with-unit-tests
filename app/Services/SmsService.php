<?php 

// app/Services/SmsService.php

namespace App\Services;

use App\Repositories\SmsLogRepository;
use App\Repositories\SmsReportRepository;

class SmsService
{

    public function __construct(
        protected SmsLogRepository $smsLogRepository,
        protected SmsReportRepository $smsReportRepository
    )
    {}

}