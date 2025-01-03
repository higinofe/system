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
    //Created Client
    public function createClient(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
        ]);

        // generates random username and password
        $username = 'user_' . strtolower(str_random(8)); 
        $password = str_random(10);

        // Created Client
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $username,
            'password' => Hash::make($password),
        ]);

        // Send Email to the Client
        Mail::to($user->email)->send(new SendClientCredentialsMail($user->email, $password));

        return response()->json(['message' => 'Client created and email sent successfully!']);
    }

    // quotas for client
    public function setDatabaseQuota(Request $request, Database $database)
    {
        $request->validate([
            'max_quota' => 'required|integer|min:1',
        ]);

        $database->max_quota = $request->max_quota;
        $database->save();

        return response()->json(['message' => 'Database quota updated successfully!', 'data' => $database]);
    }

    // Function to block or unblock the client
    public function toggleClientStatus($clientId)
    {
        $user = User::findOrFail($clientId);

        // active end inactive
        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => $user->is_active ? 'Cliente desbloqueado' : 'Cliente bloqueado',
            'status' => $user->is_active
        ]);
    }
    
}
