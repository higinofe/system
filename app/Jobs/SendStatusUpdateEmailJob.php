<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use App\Mail\StatusUpdateEmail;

class SendStatusUpdateEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $username;
    public $password;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($username, $message)
    {
        $this->username = $username;
        $this->password = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // send Email server
        Mail::to($this->email)->send(new StatusUpdateEmail($this->username, $this->message));

    }
}
 