<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Jobs\SendWelcomeEmailJob;
use App\Jobs\SendClientCredentialsEmail;
use App\Jobs\SendStatusUpdateEmailJob;
use App\Models\User;

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

        // Send email to asynchronous queue
        dispatch(new SendWelcomeEmailJob($user->email));
        dispatch(new SendClientCredentialsEmail($user->email, $password));

        return response()->json(['message' => 'Client created and email sent successfully!']);

    }

    //Update Client
    public function updateClient(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
        ]);

        $user = User::findOrFail($id);

        $user->update($request->only(['name', 'email']));

        $statusMessage = "Your profile has been updated successfully!";

        dispatch(new SendStatusUpdateEmailJob($user, $statusMessage));

        return response()->json([
            'message' => 'Client updated successfully and email sent!',
            'user' => $user,
        ]);
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
