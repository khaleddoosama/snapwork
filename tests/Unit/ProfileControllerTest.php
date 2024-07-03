<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\ChangePasswordRequest;
use App\Http\Requests\Api\UpdateSkillsRequest;
use App\Http\Resources\ProfileResource;
use App\Http\Resources\SpecializationResource;
use App\Http\Resources\UserResource;
use App\Models\Skill;
use App\Models\Specialization;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');
    }

    public function testChangePassword()
    {
        $data = ['password' => 'newpassword'];

        $response = $this->putJson('/api/change-password', $data);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Password changed successfully',
                     'status' => 200
                 ]);

        $this->assertTrue(Auth::validate(['email' => $this->user->email, 'password' => 'newpassword']));
    }

    public function testUpdateSkills()
    {
        $skills = ['PHP', 'Laravel'];
        $data = ['skills' => $skills];

        $response = $this->putJson('/api/update-skills', $data);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Skills updated successfully',
                     'status' => 200
                 ]);

        foreach ($skills as $skill) {
            $this->assertDatabaseHas('skills', ['name' => $skill]);
            $this->assertDatabaseHas('skill_user', ['user_id' => $this->user->id, 'skill_id' => Skill::where('name', $skill)->first()->id]);
        }
    }

    public function testGetSpecializations()
    {
        Specialization::factory()->count(3)->create();

        $response = $this->getJson('/api/specializations');
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Specializations fetched successfully',
                     'status' => 200
                 ]);

        $this->assertCount(3, $response->json()['data']);
    }

    public function testUpdatePicture()
    {
        $data = ['picture' => UploadedFile::fake()->image('profile.jpg')];

        $response = $this->postJson('/api/update-picture', $data);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Profile picture updated successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('users', ['id' => $this->user->id, 'picture' => 'profile.jpg']);
    }

    public function testGetFreelancers()
    {
        $specialization = Specialization::factory()->create();
        User::factory()->count(10)->create(['specialization_id' => $specialization->id, 'role' => 'freelancer']);

        $response = $this->getJson('/api/freelancers/' . $specialization->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Freelancers fetched successfully',
                     'status' => 200
                 ]);

        $this->assertCount(10, $response->json()['data']);
    }

    public function testPrevFreelancers()
    {
        $freelancer = User::factory()->create(['role' => 'freelancer']);
        $job = Job::factory()->create(['client_id' => $this->user->id]);
        $application = Application::factory()->create(['job_id' => $job->id, 'freelancer_id' => $freelancer->id, 'status' => 'hired']);

        $response = $this->getJson('/api/prev-freelancers');
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Prev Freelancers fetched successfully',
                     'status' => 200
                 ]);

        $this->assertCount(1, $response->json()['data']);
    }
}
