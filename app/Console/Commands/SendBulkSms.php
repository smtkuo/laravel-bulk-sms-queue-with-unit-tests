<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SmsService;

class SendBulkSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send-bulk {userId} {count=500}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(SmsService $smsService)
    {
        $userId = $this->argument('userId');
        $count = $this->argument('count');
        $messages = $this->getMessages($count);

        if ($smsService->sendBulkSMS($userId, $messages)) {
            $this->info('Bulk SMS sent successfully.');
        } else {
            $this->error('Failed to send bulk SMS.');
        }
    }

    private function getMessages($count)
    {
        $messages = [];
        for ($i = 0; $i < $count; $i++) {
            $messages[] = ['phone_number' => rand(1234567890, 9234567890), 'message' => "Message $i"];
        }

        return $messages;
    }
}
