<?php


namespace Tests\Feature\Auth;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function testGuestCannotLogout()
    {
        $this->postJson('logout')
            ->assertStatus(401);
    }

    public function testUserIsLoggedOutProperly()
    {
        $user = User::factory()->create([
            'email'     => 'testuser@mail.com',
            'password'  => bcrypt('password')
        ]);

        $this->actingAs($user)
            ->postJson('logout')
            ->assertStatus(204);

        $this->assertGuest('web');
    }
}
