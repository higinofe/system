<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendClientCredentialsMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_client()
    {
        $user = User::factory()->create([
            'email' => 'fernandohigino.fsh@gmail.com',
            'password' => Hash::make('172839Fe@1'),
        ]);

        $response = $this->postJson('/oauth/token', [
            'grant_type' => 'password',
            'client_id' => '4',
            'client_secret' => '4SsegCUsd3baO5QxdMaLfMZYhnn0ZYPuM6z6UJYd', 
            'username' => 'fernandohigino.fsh@gmail.com', 
            'password' => '172839Fe@1',
            'scope' => ''
        ]);

        $data = $response->json();
        $token = $data['access_token'];

        $data = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ];

        Mail::fake();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/client/create', $data);

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Client created and email sent successfully!',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
        ]);
        
        Mail::assertSent(SendClientCredentialsMail::class, function ($mail) {
            return $mail->hasTo('johndoe@example.com');
        });
    }
}
