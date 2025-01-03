<?php

namespace App\Http\Controllers;

use App\Models\Database;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class DatabaseController extends Controller
{
    public function checkUsage(Database $database)
    {
        // Simulação de cálculo do uso (substitua por sua lógica real)
        $used = rand(0, 100);  // Valor aleatório para o uso (substitua com a lógica real)
        $database->usage = $used;
        $database->save();

        // Verifique se o uso atingiu ou superou 80%
        if ($used >= 80) {
            $this->sendUsageAlert($database);
        }

        return response()->json([
            'usage' => $used, 
            'max_quota' => $database->max_quota
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'user_id' => 'required|exists:users,id',
            'name' => 'required|string|unique:databases,name',
            'max_quota' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Criar banco de dados no MySQL
            $domain = Domain::findOrFail($request->domain_id);
            $user = User::findOrFail($request->user_id);

            // Nome do banco de dados e criação no MySQL
            $dbName = 'db_' . $request->name . '_' . $domain->id;
            DB::statement("CREATE DATABASE {$dbName}");

            // Criar o registro na tabela databases
            $database = Database::create([
                'name' => $dbName,
                'domain_id' => $domain->id,
                'user_id' => $user->id,
                'usage' => 0, // Inicialmente o uso é 0
                'max_quota' => $request->max_quota,
            ]);

            DB::commit();

            return response()->json(['message' => 'Database provisioned successfully!', 'data' => $database], 201);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Failed to provision database: ' . $e->getMessage()], 500);
        }
    }

    // Função para enviar o alerta de uso
    private function sendUsageAlert(Database $database)
    {
        $user = $database->user;
        $message = "Alert: Your database '{$database->name}' has exceeded 80% of its storage quota.";

        // Envio de e-mail (substitua isso pelo seu sistema de e-mails)
        Mail::to($user->email)->send(new UsageAlert($database, $message));
    }

    private function updateClientStatus(User $user, $usage)
    {
        if ($usage >= 100) {
            // Bloquear o cliente
            $user->is_blocked = true;
            $user->save();
            $message = "Your account has been blocked due to exceeding the database quota.";
        } else {
            // Desbloquear o cliente
            $user->is_blocked = false;
            $user->save();
            $message = "Your account has been unlocked as your usage is below 100%.";
        }

        // Aqui você pode enviar um e-mail informando sobre o bloqueio/desbloqueio, se necessário.
        // Exemplo de envio de e-mail para o cliente (implementação simples):
        Mail::to($user->email)->send(new StatusUpdateEmail($user, $message));
    }
}
