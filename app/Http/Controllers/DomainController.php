<?php
namespace App\Http\Controllers;

use App\Models\Domain;
use Illuminate\Container\Attributes\Database;
use Illuminate\Http\Request;

class DomainController extends Controller
{
    // Função para criar um novo domínio
    public function createDomain(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|unique:domains|max:255',
        ]);

        $domain = Domain::create([
            'name' => $request->domain_name,
            'user_id' => auth()->user()->id, // Assumindo que o domínio é associado ao usuário autenticado
        ]);

        return response()->json(['message' => 'Domínio criado com sucesso!', 'domain' => $domain], 201);
    }

    // Função para criar um banco de dados para o domínio
    public function createDatabase(Request $request, $domainId)
    {
        $request->validate([
            'database_name' => 'required|unique:databases|max:255',
        ]);

        // Verifica se o domínio existe
        $domain = Domain::findOrFail($domainId);

        // Criação do banco de dados associado ao domínio
        $database = Database::create([
            'name' => $request->database_name,
            'domain_id' => $domain->id,
            'user_id' => auth()->user()->id, // Associa o banco de dados ao usuário
        ]);

        return response()->json(['message' => 'Banco de dados criado com sucesso!', 'database' => $database], 201);
    }

    // Função para verificar a cota de uso do banco de dados
    public function checkDatabaseUsage($databaseId)
    {
        $database = Database::findOrFail($databaseId);

        // Aqui você pode implementar a lógica para calcular a utilização do banco
        // Suponha que tenha um campo 'usage' no banco de dados
        if ($database->usage >= 80) {
            return response()->json([
                'message' => 'Atenção: Sua cota de banco de dados atingiu 80% de uso.',
                'usage' => $database->usage
            ]);
        }

        return response()->json([
            'message' => 'O uso do banco de dados está dentro do limite.',
            'usage' => $database->usage
        ]);
    }

    // Função para provisionar SSL Let's Encrypt
    public function provisionSSL($domainId)
    {
        $domain = Domain::findOrFail($domainId);

        // Aqui você pode integrar com Let's Encrypt para provisionar o certificado SSL
        // Isso geralmente exige a interação com uma API ou pacotes específicos

        // Supondo que o SSL foi provisionado com sucesso:
        $domain->ssl_certificate = 'Caminho/do/certificado/ssl';
        $domain->save();

        return response()->json(['message' => 'Certificado SSL provisionado com sucesso!']);
    }
}
