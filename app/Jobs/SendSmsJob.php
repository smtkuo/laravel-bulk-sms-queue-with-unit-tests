<?php

namespace App\Jobs;

use App\Models\Sms;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected Sms $sms
    )
    {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(0);
        $response = Http::post(url('/api/send-mock-sms'), [
            'phone_number' => $this->sms->phone_number,
            'message' => $this->sms->message
        ]);
        if ($response->successful() && $response->json() ) {
            $this->sms->update(['status' => 'delivered', 'response' => $response->json()]);
        } else {
            $this->sms->update(['status' => 'failed']);
            $this->fail();
        }
    }
}
