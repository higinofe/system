<!-- resources/views/emails/usage_alert.blade.php -->

@extends('layouts.email') <!-- Estende o layout de e-mail -->

@section('subject', 'Alerta de Excesso de Uso de Banco de Dados') <!-- Define o assunto -->

@section('content') <!-- Conteúdo do e-mail -->
    <h1>Alerta de Uso de Banco de Dados</h1>
    <p>
        Olá {{ $user->name }},
    </p>
    <p>
        Este é um alerta informando que o seu banco de dados <strong>{{ $database->name }}</strong> excedeu 80% de sua cota de armazenamento.
    </p>
    <p>
        Por favor, tome as ações necessárias para evitar a interrupção do serviço.
    </p>
    <p>
        Caso precise de ajuda, entre em contato com nossa equipe de suporte.
    </p>

    <div class="footer">
        <p>Este é um e-mail automático. Por favor, não responda a este e-mail.</p>
    </div>
@endsection
