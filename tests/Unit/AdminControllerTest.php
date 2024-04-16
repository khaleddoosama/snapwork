<?php

namespace Tests\Feature\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AdminControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create([
            'role' => 'admin',
            'password' => Hash::make('password')
        ]);
        $this->actingAs($this->admin, 'web');
    }

    public function testHome()
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertViewIs('admin.home');
    }

    public function testProfile()
    {
        $response = $this->get('/admin/profile');
        $response->assertStatus(200);
        $response->assertViewIs('admin.profile');
        $response->assertViewHas('user', $this->admin);
    }

    public function testUpdateProfile()
    {
        $data = [
            'name' => 'New Name',
            'email' => 'newemail@example.com',
            'phone' => '11111111111'
        ];
        $response = $this->put('/admin/profile', $data);

        $this->assertDatabaseHas('users', [
            'id' => $this->admin->id,
            'name' => 'New Name',
            'email' => 'newemail@example.com',
            'phone' => '11111111111'
        ]);
        $response->assertRedirect();
        $response->assertSessionHas('toastr:success');
    }

    public function testChangePassword()
    {
        $response = $this->put('/admin/change-password', [
            '_method' => 'PUT',
            '_token' => csrf_token(), // Or fetch the token dynamically if necessary
            'id' => $this->admin->id,
            'old_password' => 'password',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword'
        ]);

        $this->assertTrue(Hash::check('newpassword', $this->admin->fresh()->password));
        $response->assertRedirect();
        $response->assertSessionHas('toastr:success');
    }

    public function testIndex()
    {
        $response = $this->get('/admin/all_admin');
        $response->assertStatus(200);
        $response->assertViewIs('admin.admin.index');
        $response->assertViewHas('admins');
    }
}