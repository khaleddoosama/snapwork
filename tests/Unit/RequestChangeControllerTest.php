<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\RequestChangeRequest;
use App\Models\Application;
use App\Models\Job;
use App\Models\RequestChange;
use App\Models\User;
use App\Notifications\RequestChangeNotification;
use App\Services\Api\RequestChangeService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RequestChangeControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->job = Job::factory()->create(['client_id' => $this->user->id]);
        $this->application = Application::factory()->create(['job_id' => $this->job->id]);
        $this->requestChangeService = $this->createMock(RequestChangeService::class);
        $this->app->instance(RequestChangeService::class, $this->requestChangeService);

        $this->actingAs($this->user, 'api');
    }

    public function testRequestChange()
    {
        $data = RequestChange::factory()->make()->toArray();
        Notification::fake();

        $this->requestChangeService->method('requestChange')->willReturn(new RequestChange($data));

        $response = $this->postJson('/api/request-change/' . $this->job->id . '/' . $this->application->id, $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Request change created successfully',
                     'status' => 201
                 ]);

        Notification::assertSentTo(
            [$this->job->client],
            RequestChangeNotification::class
        );

        $this->assertDatabaseHas('request_changes', [
            'job_id' => $this->job->id,
            'application_id' => $this->application->id,
            'type' => $data['type']
        ]);
    }

    public function testRequestSubmit()
    {
        $data = RequestChange::factory()->make(['type' => 'submit'])->toArray();
        Notification::fake();

        $this->requestChangeService->method('requestChange')->willReturn(new RequestChange($data));

        $response = $this->putJson('/api/request-submit/' . $this->job->id . '/' . $this->application->id, $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Request submit created successfully',
                     'status' => 201
                 ]);

        Notification::assertSentTo(
            [$this->job->client],
            RequestChangeNotification::class
        );

        $this->assertDatabaseHas('request_changes', [
            'job_id' => $this->job->id,
            'application_id' => $this->application->id,
            'type' => 'submit'
        ]);
    }

    public function testRequestCancel()
    {
        $data = RequestChange::factory()->make(['type' => 'cancel'])->toArray();
        Notification::fake();

        $this->requestChangeService->method('requestChange')->willReturn(new RequestChange($data));

        $response = $this->putJson('/api/request-cancel/' . $this->job->id . '/' . $this->application->id, $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Request cancel created successfully',
                     'status' => 201
                 ]);

        Notification::assertSentTo(
            [$this->job->client],
            RequestChangeNotification::class
        );

        $this->assertDatabaseHas('request_changes', [
            'job_id' => $this->job->id,
            'application_id' => $this->application->id,
            'type' => 'cancel'
        ]);
    }

    public function testResponseAccept()
    {
        $requestChange = RequestChange::factory()->create(['status' => 'pending']);
        $this->requestChangeService->method('handleAcceptance')->willReturn($requestChange);

        $response = $this->putJson('/api/response-accept/' . $requestChange->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Request change accepted successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('request_changes', [
            'id' => $requestChange->id,
            'status' => 'accept'
        ]);
    }

    public function testResponseDecline()
    {
        $requestChange = RequestChange::factory()->create(['status' => 'pending']);

        $response = $this->putJson('/api/response-decline/' . $requestChange->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Request change declined successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('request_changes', [
            'id' => $requestChange->id,
            'status' => 'decline'
        ]);
    }
}
