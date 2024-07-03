<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\RateRequest;
use App\Http\Resources\RateResource;
use App\Models\Rate;
use App\Models\User;
use App\Notifications\RateNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class RateControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'api');

        $this->rate = Rate::factory()->create(['rated_by' => $this->user->id]);
    }

    public function testIndex()
    {
        $response = $this->getJson('/api/rates');
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Rates fetched successfully',
                     'status' => 200
                 ]);

        $this->assertEquals($this->user->id, $response->json()['data'][0]['rated_by']);
    }

    public function testStore()
    {
        $data = Rate::factory()->make(['rated_by' => $this->user->id])->toArray();
        Notification::fake();

        $response = $this->postJson('/api/rates', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Rate created successfully',
                     'status' => 200
                 ]);

        Notification::assertSentTo(
            [$this->user],
            RateNotification::class
        );

        $this->assertDatabaseHas('rates', [
            'rated_by' => $this->user->id,
            'rate' => $data['rate']
        ]);
    }

    public function testShow()
    {
        $response = $this->getJson('/api/rates/' . $this->rate->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Rate fetched successfully',
                     'status' => 200
                 ]);

        $this->assertEquals($this->rate->id, $response->json()['data']['id']);
    }
}

