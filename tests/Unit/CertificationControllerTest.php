<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\CertificationRequest;
use App\Http\Resources\CertificationResource;
use App\Models\Certification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CertificationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->user = User::factory()->create();
        $this->certification = Certification::factory()->create(['user_id' => $this->user->id]);
    }

    public function testAddCertification()
    {
        $this->actingAs($this->user, 'api');

        $data = Certification::factory()->make()->toArray();
        $response = $this->postJson('/api/certifications', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Certification added successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('certifications', [
            'name' => $data['name'],
            'user_id' => $this->user->id
        ]);
    }

    public function testUpdateCertification()
    {
        $this->actingAs($this->user, 'api');

        $data = Certification::factory()->make()->toArray();
        $response = $this->putJson('/api/certifications/' . $this->certification->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Certification updated successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('certifications', [
            'id' => $this->certification->id,
            'name' => $data['name']
        ]);
    }

    public function testDeleteCertification()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->deleteJson('/api/certifications/' . $this->certification->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Certification deleted successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseMissing('certifications', ['id' => $this->certification->id]);
    }
}
