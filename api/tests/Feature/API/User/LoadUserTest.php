<?php


namespace Tests\Feature\API\User;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoadUserTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanLoadProfileData()
    {
        $userData = [
            'name'  => 'John Doe',
            'email' => 'johndoe@mail.com'
        ];

        $user = User::factory()->create($userData);

        $this->actingAs($user)
            ->getJson(route('api.me'))
            ->assertStatus(200)
            ->assertJson($userData);
    }

    public function testGuestCannotLoadProfileData()
    {
        $this->getJson(route('api.me'))
            ->assertStatus(401);
    }
}
