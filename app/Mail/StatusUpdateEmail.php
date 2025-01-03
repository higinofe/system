<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class StatusUpdateEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $message;

    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function build()
    {
        return $this->subject('Account Status Update')
                    ->view('emails.status_update')
                    ->with([
                        'user' => $this->user,
                        'message' => $this->message,
                    ]);
    }
}
