<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\MessageRequest;
use App\Http\Resources\MessageResource;
use App\Models\Message;
use App\Models\User;
use App\Notifications\MessageNotification;
use App\Services\Api\MessageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageService = $this->createMock(MessageService::class);
        $this->app->instance(MessageService::class, $this->messageService);

        $this->user = User::factory()->create();
        $this->receiver = User::factory()->create();
        $this->message = Message::factory()->create(['sender_id' => $this->user->id, 'receiver_id' => $this->receiver->id]);
    }

    public function testIndex()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->getJson('/api/messages');
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Messages fetched successfully',
                     'status' => 200
                 ]);

        $this->assertEquals($this->user->messages->count(), count($response->json()['data']));
    }

    public function testStore()
    {
        $this->actingAs($this->user, 'api');

        $data = Message::factory()->make(['receiver_id' => $this->receiver->id])->toArray();
        $this->messageService->method('save')->willReturn(new Message($data));

        Notification::fake();

        $response = $this->postJson('/api/messages', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Message sent successfully',
                     'status' => 200
                 ]);

        Notification::assertSentTo(
            [$this->receiver],
            MessageNotification::class
        );

        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->user->id,
            'receiver_id' => $data['receiver_id'],
            'content' => $data['content']
        ]);
    }

    public function testShow()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->getJson('/api/messages/' . $this->receiver->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Messages fetched successfully',
                     'status' => 200
                 ]);

        $this->assertEquals($this->message->content, $response->json()['data'][0]['content']);
    }

    public function testMarkAsRead()
    {
        $this->actingAs($this->user, 'api');

        $this->messageService->method('markAsRead')->willReturn($this->message);

        $response = $this->putJson('/api/messages/' . $this->message->id . '/read');
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Message marked as read successfully',
                     'status' => 200
                 ]);

        $this->assertTrue($this->message->fresh()->is_read);
    }
}

