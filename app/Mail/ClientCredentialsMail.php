<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;

class ClientCredentialsMail extends Mailable
{
    public $user;
    public $password;

    public function __construct(User $user, $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    public function build()
    {
        return $this->subject('Credenciais de Acesso')
                    ->view('emails.client_credentials');
    }
    
}
