<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\JobRequest;
use App\Http\Resources\JobResource;
use App\Models\Job;
use App\Models\User;
use App\Services\Api\JobService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class JobControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->jobService = $this->createMock(JobService::class);
        $this->app->instance(JobService::class, $this->jobService);

        $this->client = User::factory()->create(['role' => 'client']);
        $this->job = Job::factory()->create(['client_id' => $this->client->id]);
    }

    public function testIndex()
    {
        $this->actingAs($this->client, 'api');

        $jobs = collect([$this->job]);
        $this->jobService->method('getAll')->willReturn($jobs);

        $response = $this->getJson('/api/jobs');
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         [
                             'id' => $this->job->id,
                             // other fields...
                         ]
                     ],
                     'message' => 'Jobs fetched successfully',
                     'status' => 200
                 ]);
    }

    public function testGetForClient()
    {
        $this->actingAs($this->client, 'api');

        $jobs = collect([$this->job]);
        $this->jobService->method('getForClient')->willReturn($jobs);

        $response = $this->getJson('/api/jobs');
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         [
                             'id' => $this->job->id,
                             // other fields...
                         ]
                     ],
                     'message' => 'Jobs fetched successfully',
                     'status' => 200
                 ]);
    }

    public function testStore()
    {
        $this->actingAs($this->client, 'api');

        $data = Job::factory()->make()->toArray();
        $this->jobService->method('save')->willReturn(new Job($data));

        $response = $this->postJson('/api/jobs', $data);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Job created successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('jobs', [
            'title' => $data['title'],
            'client_id' => $this->client->id
        ]);
    }

    public function testShow()
    {
        $this->actingAs($this->client, 'api');

        $response = $this->getJson('/api/jobs/' . $this->job->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         'id' => $this->job->id,
                         // other fields...
                     ],
                     'message' => 'Job found',
                     'status' => 200
                 ]);
    }

    public function testUpdate()
    {
        $this->actingAs($this->client, 'api');

        $data = Job::factory()->make()->toArray();
        $this->jobService->method('update')->willReturn(new Job($data));

        $response = $this->putJson('/api/jobs/' . $this->job->id, $data);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Job updated successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('jobs', [
            'id' => $this->job->id,
            'title' => $data['title']
        ]);
    }
}

