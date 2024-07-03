<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\EducationRequest;
use App\Http\Resources\EducationResource;
use App\Models\Education;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class EducationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->education = Education::factory()->create(['user_id' => $this->user->id]);
    }

    public function testAddEducation()
    {
        $this->actingAs($this->user, 'api');

        $data = Education::factory()->make()->toArray();
        $response = $this->postJson('/api/educations', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Education added successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('educations', [
            'degree' => $data['degree'],
            'user_id' => $this->user->id
        ]);
    }

    public function testUpdateEducation()
    {
        $this->actingAs($this->user, 'api');

        $data = Education::factory()->make()->toArray();
        $response = $this->putJson('/api/educations/' . $this->education->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Education updated successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('educations', [
            'id' => $this->education->id,
            'degree' => $data['degree']
        ]);
    }

    public function testDeleteEducation()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->deleteJson('/api/educations/' . $this->education->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Education deleted successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseMissing('educations', ['id' => $this->education->id]);
    }
}
