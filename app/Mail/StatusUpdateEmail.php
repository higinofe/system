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
        return $this->view('emails.cliente_update')
                    ->with([
                        'user' => $this->user,
                        'statusMessage' => $this->message,
                    ])
                    ->subject('Atualização de Status');
    }
}
