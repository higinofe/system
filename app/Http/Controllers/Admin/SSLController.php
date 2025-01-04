<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\Database;

class SSLController extends Controller
{
    public function provisionSSL(Database $database)
    {
        $domain = $database->domain->name; 

        // generate ssl certificate command
        $command = "sudo certbot --apache -d {$domain}";

        try {
            // Execute shell command
            $output = shell_exec($command);

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
