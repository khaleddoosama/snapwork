<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\EmploymentRequest;
use App\Http\Resources\EmploymentResource;
use App\Models\EmploymentHistory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class EmploymentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->employment = EmploymentHistory::factory()->create(['user_id' => $this->user->id]);
    }

    public function testAddEmployment()
    {
        $this->actingAs($this->user, 'api');

        $data = EmploymentHistory::factory()->make()->toArray();
        $response = $this->postJson('/api/employments', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Employment added successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('employment_histories', [
            'job_title' => $data['job_title'],
            'user_id' => $this->user->id
        ]);
    }

    public function testUpdateEmployment()
    {
        $this->actingAs($this->user, 'api');

        $data = EmploymentHistory::factory()->make()->toArray();
        $response = $this->putJson('/api/employments/' . $this->employment->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Employment updated successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('employment_histories', [
            'id' => $this->employment->id,
            'job_title' => $data['job_title']
        ]);
    }

    public function testDeleteEmployment()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->deleteJson('/api/employments/' . $this->employment->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Employment deleted successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseMissing('employment_histories', ['id' => $this->employment->id]);
    }
}
