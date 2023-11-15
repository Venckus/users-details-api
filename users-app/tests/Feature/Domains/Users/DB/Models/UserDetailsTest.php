<?php

namespace Tests\Feature\Domains\Users\DB\Models;

use App\Domains\Users\DB\Models\UserDetails;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserDetailsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function testShouldCreateUserDetails(): void
    {
        $user = User::factory()->create();
        $userDetails = UserDetails::create([
            UserDetails::USER_ID => $user->id,
            UserDetails::ADDRESS => 'test address',
        ]);

        $this->assertDatabaseCount('user_details', 1);
        $this->assertDatabaseHas('user_details', [
            UserDetails::USER_ID => $user->id,
            UserDetails::ADDRESS => $userDetails->address,
        ]);
    }
}
