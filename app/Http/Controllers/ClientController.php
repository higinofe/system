<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\SendClientCredentialsEmail;

class ClientController extends Controller
{
    public function createClient(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
        ]);

        $username = 'user_' . strtolower(str_random(8));
        $password = str_random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        // Envia o e-mail de forma assíncrona
        SendClientCredentialsEmail::dispatch($user, $password);

        return response()->json([
            'message' => 'Cliente cadastrado com sucesso!',
            'client' => $user
        ], 201);
    }

    public function store(Request $request)
    {
        // Validação do request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
        ]);

        // Geração automática de nome de usuário e senha
        $username = strtolower(str_replace(' ', '_', $request->name));
        $password = str_random(12);  // Geração de senha aleatória

        // Criação do novo cliente
        $client = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        // Envio de e-mail com as credenciais
        Mail::to($client->email)->send(new WelcomeEmail($client, $password));

        return response()->json(['message' => 'Client created successfully!', 'client' => $client]);
    }
}
