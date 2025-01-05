<!-- resources/views/emails/client_credentials.blade.php -->

@extends('layouts.email')

@section('subject', 'credenciais de usuario')

@section('co1ntent') 

<h1>Alerta de credenciais de usuario</h1>

    <p>
        Olá {{ $user->name }},
    </p>
    <p>
        Suas credenciais de acesso são:
    </p>
    <p>
        Nome de usuário: {{ $user->username }}
    </p>
    <p>
        Senha: {{ $password }}
    </p>

@endsection