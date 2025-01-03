<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\ClientCredentialsMail;
use App\Mail\SendClientCredentialsMail;
use App\Models\Database;

class ClientController extends Controller
{
    // Função para cadastrar um novo cliente
    public function createClient(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
        ]);

        // Gera um nome de usuário e senha aleatória
        $username = 'user_' . strtolower(str_random(8)); // Gera um nome de usuário
        $password = str_random(10); // Gera uma senha aleatória

        // Cria o cliente
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        // Envia as credenciais para o cliente por e-mail
        Mail::to($user->email)->send(new SendClientCredentialsMail($user->email, $password));

        return response()->json(['message' => 'Client created and email sent successfully!']);
    }

    // Função para definir cotas de banco de dados para o cliente
    public function setDatabaseQuota(Request $request, Database $database)
    {
        $request->validate([
            'max_quota' => 'required|integer|min:1',
        ]);

        // Atualiza a cota máxima
        $database->max_quota = $request->max_quota;
        $database->save();

        return response()->json(['message' => 'Database quota updated successfully!', 'data' => $database]);
    }

    // Função para bloquear ou desbloquear o cliente
    public function toggleClientStatus($clientId)
    {
        $user = User::findOrFail($clientId);

        // Alterna entre ativo e bloqueado
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => $user->is_active ? 'Cliente desbloqueado' : 'Cliente bloqueado',
            'status' => $user->is_active
        ]);
    }
    
}
