<?php

namespace App\Services;

use App\Repositories\SmsRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendSmsJob;
use App\Models\Sms;

class SmsService
{

    public function __construct(
        protected SmsRepository $smsRepository
    ) {
    }

    /**
     * @param int $userId
     * @param string $phoneNumber
     * @param string $message
     * 
     * @return Sms
     * 
     */
    public function sendSMS(int $userId, string $phoneNumber, string $message): Sms
    {
        $smsCreate = $this->smsRepository->create([
            'user_id' => $userId,
            'phone_number' => $phoneNumber,
            'message' => $message,
            'status' => 'waiting',
            'sent_at' => now()
        ]);
        SendSmsJob::dispatch($smsCreate);

        return $smsCreate;
    }

    /**
     * @param int $userId
     * @param array $messages
     * 
     * @return bool
     * 
     */
    public function sendBulkSMS(int $userId, array $messages): bool
    {
        foreach ($messages as $sms) {
            $this->sendSMS(
                $userId,
                $sms['phone_number'],
                $sms['message']
            );
        }

        return true;
    }

    /**
     * @param int|null $userId
     * @param string|null $status
     * @param string|null $startDate
     * @param string|null $endDate
     * @param string|null $message
     * 
     * @return Collection
     * 
     */
    public function getSmsReport(int $userId = null, string $status = null, string $startDate = null, string $endDate = null, string $message = null): Collection
    {
        $query = $this->smsRepository->query();

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($startDate) {
            $query->whereDate('sent_at', '>=', $startDate);
        }

        if ($endDate) {
            $query->whereDate('sent_at', '<=', $endDate);
        }

        if ($message) {
            $query->where('message', 'like', "%{$message}%");
        }

        return $query->get();
    }

    /**
     * @param int $userId
     * 
     * @return array
     * 
     */
    public function getDetail(int $userId): array
    {
        $totalSms = $this->smsRepository->query()
            ->where('user_id', $userId)
            ->count();
        $deliveredSms = $this->smsRepository->query()
            ->where('user_id', $userId)
            ->where('status', 'delivered')->count();
        $failedSms = $this->smsRepository->query()
            ->where('user_id', $userId)
            ->where('status', 'failed')
            ->count();

        return [
            'total_sms' => $totalSms,
            'delivered_sms' => $deliveredSms,
            'failed_sms' => $failedSms,
        ];
    }
}
