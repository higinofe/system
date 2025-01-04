<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Container\Attributes\Database;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Domain;


class DomainController
{
    
    // Created domain
    public function create(Request $request)
    {
        $request->validate([
            'domain_name' => 'required|unique:domains|max:255',
        ]);

        $domain = Domain::create([
            'name' => $request->domain_name,
            'user_id' => auth()->user()->id, 
        ]);

        return response()->json(['message' => 'Domínio criado com sucesso!', 'domain' => $domain], 201);
    }

     //function to create the database for domain
     public function createDatabase(Request $request, $domainId)
     {
         $request->validate([
             'database_name' => 'required|unique:databases|max:255',
         ]);
 
         $domain = Domain::findOrFail($domainId);
 
         $database = Database::create([
             'name' => $request->database_name,
             'domain_id' => $domain->id,
             'user_id' => auth()->user()->id,
         ]);
 
         return response()->json(['message' => 'Banco de dados criado com sucesso!', 'database' => $database], 201);
     }

    // check used DB 
    public function checkDatabaseUsage($databaseId)
    {
        $database = Database::findOrFail($databaseId);

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

    // Function to provision SSL Let's Encrypt
    public function provisionSSL($domainId)
    {
        $domain = Domain::findOrFail($domainId);

        $domain->ssl_certificate = 'certificados/ssl';
        $domain->save();

        return response()->json(['message' => 'Certificado SSL provisionado com sucesso!']);
    }

}
