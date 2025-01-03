<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $client;
    public $password;

    public function __construct(User $client, $password)
    {
        $this->client = $client;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Welcome to Our Service')
                    ->view('emails.welcome')
                    ->with([
                        'client' => $this->client,
                        'password' => $this->password,
                    ]);
    }
}
