<?php

namespace App\Mail;

use App\Models\Database;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UsageAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $database;
    public $message;

    public function __construct(Database $database, $message)
    {
        $this->database = $database;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('Database Usage Alert')
                    ->view('emails.usage_alert');
    }
}
