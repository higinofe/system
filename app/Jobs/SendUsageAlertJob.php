<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

use App\Mail\UsageAlert;
use App\Models\Database;

class SendUsageAlertJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $database;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Database $database, $message)
    {
        $this->database = $database;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = $this->database->user;

        Mail::to($user->email)->send(new UsageAlert($this->database, $this->message));

    }

}
