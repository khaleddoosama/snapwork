<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use App\Services\Api\ApplicationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class ApplicationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->applicationService = $this->createMock(ApplicationService::class);
        $this->app->instance(ApplicationService::class, $this->applicationService);

        $this->artisan('db:seed');

        $this->freelancer = User::where('role', 'freelancer')->first();
        $this->client = User::where('role', 'client')->first();

        $this->job = Job::first();
        $this->application = Application::first();
    }

    public function testGetForFreelancer()
    {
        $this->actingAs($this->freelancer, 'api');

        $applications = collect([$this->application]);
        $this->applicationService->method('getForFreelancer')->willReturn($applications);

        $response = $this->getJson('/api/applications');
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    [
                        'id' => $this->application->id,
                    ]
                ],
                'message' => 'Applications retrieved successfully',
                'status' => 200
            ]);
    }

    public function testStore()
    {
        $this->actingAs($this->freelancer, 'api');

        $data = [
            'slug' => 'new-application',
            'bid' => 1200,
            'duration' => 40,
            'cover_letter' => 'New cover letter for testing.',
            'attachments' => json_encode(["new_resume.pdf"]),
            'job_id' => $this->job->id,
            'freelancer_id' => $this->freelancer->id,
        ];

        $this->applicationService->method('save')->willReturn(new Application($data));

        $response = $this->postJson('/api/applications', $data);
        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'slug' => 'new-application',
                ],
                'message' => 'Application sent successfully',
                'status' => 201
            ]);
    }

    public function testHireUnauthorized()
    {
        $this->actingAs($this->freelancer, 'api');

        $response = $this->putJson('/api/hire/' . $this->job->id . '/' . $this->application->id);
        $response->assertStatus(403); // Forbidden
    }

    public function testHireJobMismatch()
    {
        $this->actingAs($this->client, 'api');

        Gate::shouldReceive('authorize')->with('hire', $this->job)->andReturn(true);

        $differentApplication = Application::factory()->create([
            'job_id' => 999, // non-existing job
            'freelancer_id' => $this->freelancer->id,
        ]);

        $response = $this->putJson('/api/hire/' . $this->job->id . '/' . $differentApplication->id);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Not Responsible',
                'status' => 401
            ]);
    }

    public function testHireAlreadyHired()
    {
        $this->actingAs($this->client, 'api');

        Gate::shouldReceive('authorize')->with('hire', $this->job)->andReturn(true);

        $this->job->update(['status' => 'hired']);

        $response = $this->putJson('/api/hire/' . $this->job->id . '/' . $this->application->id);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'error: Job is hired before',
                'status' => 401
            ]);
    }

    public function testHireApplicationAlreadyHired()
    {
        $this->actingAs($this->client, 'api');

        Gate::shouldReceive('authorize')->with('hire', $this->job)->andReturn(true);

        $this->application->update(['status' => 'hired']);

        $response = $this->putJson('/api/hire/' . $this->job->id . '/' . $this->application->id);
        $response->assertStatus(401)
            ->assertJson([
                'message' => 'Application is hired',
                'status' => 401
            ]);
    }

    public function testHireSuccessfully()
    {
        $this->actingAs($this->client, 'api');

        Gate::shouldReceive('authorize')->with('hire', $this->job)->andReturn(true);

        $this->applicationService->method('hire')->willReturn($this->application);

        $response = $this->putJson('/api/hire/' . $this->job->id . '/' . $this->application->id);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $this->application->id,
                ],
                'message' => 'Hired',
                'status' => 200
            ]);
    }
}
