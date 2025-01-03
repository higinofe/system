<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Database;

class SSLController extends Controller
{
    public function provisionSSL(Database $database)
    {
        $domain = $database->domain->name;  // Obtém o nome do domínio associado ao banco de dados

        // Comando para gerar o certificado SSL usando certbot
        $command = "sudo certbot --apache -d {$domain}";

        try {
            // Executa o comando shell
            $output = shell_exec($command);

            // Verifica se o comando foi bem-sucedido
            if ($output) {
                return response()->json(['message' => 'SSL certificate provisioned successfully!', 'output' => $output]);
            } else {
                return response()->json(['error' => 'Failed to provision SSL certificate.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error provisioning SSL: ' . $e->getMessage());
            return response()->json(['error' => 'Error provisioning SSL certificate.'], 500);
        }
    }
}
