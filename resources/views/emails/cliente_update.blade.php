<!-- resources/views/emails/cliente_update.blade.php -->

@extends('layouts.email') <!-- Usa o layout de e-mail compartilhado -->

@section('subject', 'Atualização de Cliente') <!-- Assunto do e-mail -->

@section('content') <!-- Conteúdo do e-mail -->
    <h1>Olá, {{ $user->name }}!</h1>

    <p>Gostaríamos de informar que houve uma atualização em sua senha:</p>

    <blockquote style="font-size: 16px; font-style: italic; color: #555;">
        {{ $statusMessage }}
    </blockquote>

    <p>Se precisar de mais informações, não hesite em nos contatar.</p>

    <p>Atenciosamente,<br>
    A Equipe do Sistema</p>
@endsection
