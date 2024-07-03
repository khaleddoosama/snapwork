<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->notification = DatabaseNotification::factory()->create(['notifiable_id' => $this->user->id]);
    }

    public function testIndex()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->getJson('/api/notifications');
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Notifications fetched successfully',
                     'status' => 200
                 ]);

        $this->assertCount(1, $response->json()['data']);
    }

    public function testReadNotification()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->putJson('/api/notifications/read/' . $this->notification->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Notification read successfully',
                     'status' => 200
                 ]);

        $this->assertTrue($this->notification->fresh()->read());
    }
}
