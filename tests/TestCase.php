<?php

namespace Tests;

use App\Models\Database;
use App\Models\User;
use Carbon\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function testClientBlockedWhenQuotaExceeds()
    {         
        $user = factory(User::class)->create();
        $database = factory(Database::class)->create(['user_id' => $user->id, 'usage' => 101]);

        $response = $this->getJson("/api/database/{$database->id}/check-usage");

        $response->assertStatus(200);
        $this->assertTrue($user->fresh()->is_blocked);
    }
        
}
 