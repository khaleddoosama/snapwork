<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\InvitationRequest;
use App\Http\Resources\InvitationResource;
use App\Models\Invitation;
use App\Models\Job;
use App\Models\User;
use App\Notifications\InvitedNotification;
use App\Services\Api\InvitationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class InvitationControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->invitationService = $this->createMock(InvitationService::class);
        $this->app->instance(InvitationService::class, $this->invitationService);

        $this->client = User::factory()->create(['role' => 'client']);
        $this->freelancer = User::factory()->create(['role' => 'freelancer']);
        $this->job = Job::factory()->create(['client_id' => $this->client->id]);
        $this->invitation = Invitation::factory()->create(['job_id' => $this->job->id, 'freelancer_id' => $this->freelancer->id]);
    }

    public function testStoreInvitation()
    {
        $this->actingAs($this->client, 'api');

        $data = Invitation::factory()->make()->toArray();
        $this->invitationService->method('save')->willReturn(new Invitation($data));

        Notification::fake();

        $response = $this->postJson('/api/invitations', $data);

        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Invitation sent successfully',
                     'status' => 201
                 ]);

        Notification::assertSentTo(
            [$this->freelancer],
            InvitedNotification::class
        );

        $this->assertDatabaseHas('invitations', [
            'job_id' => $data['job_id'],
            'freelancer_id' => $data['freelancer_id']
        ]);
    }
}

