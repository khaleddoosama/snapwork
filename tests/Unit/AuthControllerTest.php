<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Test successful login.
     */
    public function testLoginSuccessfully()
    {
        // Arrange: Create a user with known credentials
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt($password = 'validPassword123'),
        ]);

        // Act: Attempt to login with correct credentials
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        // Assert: Check response status and structure
        $response->assertStatus(200)
                 ->assertJsonStructure(['data' => [
                     'access_token',
                     'token_type',
                     'expires_in',
                     'user' => [
                        'id',
                        'name',
                        'username',
                        'slug',
                        'bio',
                        'email',
                        'email_verified_at',
                        'picture',
                        'cover',
                        'video',
                        'phone',
                        'phone_verified_at',
                        'country',
                        'address',
                        'role',
                        'specialization',
                        'job_title',
                        'gender',
                        'dob',
                        'balance',
                        'status',
                        'skills',
                        'languages',
                        'projects',
                        'educations',
                        'Employment',
                        'certifications',
                        'created_at',
                     ],
                    ]
                ]);
    }

    /**
     * Test login failure with incorrect password.
     */
    public function testLoginFailureWithIncorrectPassword()
    {
        // Arrange: Create a user with a known password
        $user = User::factory()->create([
            'email' => 'user@example.com',
            'password' => bcrypt('validPassword123'),
        ]);

        // Act: Attempt to login with incorrect password
        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrongPassword',
        ]);

        // Assert: Check response status for unauthorized
        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthorized',
                 ]);
    }

    /**
     * Test login failure due to validation errors.
     */
    public function testLoginFailureWithValidationErrors()
    {
        // Act: Attempt to login with missing fields
        $response = $this->postJson('/api/auth/login', [
            'email' => '',
            'password' => '', // Missing password
        ]);

        // Assert: Check response status for validation error
        $response->assertStatus(422);
    }

    // /**
    //  * Test successful registration with complete required data.
    //  */
    // public function testRegisterSuccessfully()
    // {
    //     // Act: Attempt to register with complete and valid data
    //     $response = $this->postJson('/api/auth/register', [
    //         'name' => 'John Doe',
    //         'username' => 'john_doe',
    //         'email' => 'john@example.com',
    //         'password' => 'validPassword123',
    //         'password_confirmation' => 'validPassword123',
    //         'specialization_id' => 1,
    //         'role' => 'client',
    //         'gender' => 'male',
    //         'dob' => '1990-01-01',
    //         'job_title' => 'Web Developer'
    //     ]);
        
    //     // Assert: Check response status and JSON structure
    //     $response->assertStatus(201)
    //              ->assertJsonStructure([
    //                  'data' => [
    //                      'id',
    //                      'name',
    //                      'username',
    //                      'slug',
    //                      'email',
    //                      'phone',
    //                      'picture',
    //                      'status'
    //                  ]
    //              ]);
    // }

    // /**
    //  * Test registration with invalid data.
    //  */
    // public function testRegisterWithInvalidData()
    // {
    //     // Act: Attempt to register with invalid email and mismatching password confirmation
    //     $response = $this->postJson('/api/auth/register', [
    //         'name' => 'John Doe',
    //         'username' => 'john_doe',
    //         'email' => 'not-an-email',
    //         'password' => 'validPassword123',
    //         'password_confirmation' => 'invalidPassword123',
    //         'specialization_id' => 1, // Assuming an ID without creating, could cause a failure if checked against the database
    //         'role' => 'client',
    //         'gender' => 'male',
    //         'dob' => '1990-01-01',
    //         'job_title' => 'Web Developer'
    //     ]);

    //     // Assert: Check response status and JSON error messages
    //     $response->assertStatus(400);
    // }

    // /**
    //  * Test registration failure due to missing fields.
    //  */
    // public function testRegisterFailureMissingFields()
    // {
    //     // Act: Attempt to register with missing fields
    //     $response = $this->postJson('/api/auth/register', [
    //         // Missing most required fields
    //         'email' => 'john@example.com',
    //     ]);

    //     // Assert: Check response status
    //     $response->assertStatus(400);
    // }
}
