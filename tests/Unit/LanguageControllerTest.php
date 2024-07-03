<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\LanguageRequest;
use App\Http\Resources\LanguageResource;
use App\Models\Language;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Auth;

class LanguageControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->language = Language::factory()->create(['user_id' => $this->user->id]);
    }

    public function testAddLanguage()
    {
        $this->actingAs($this->user, 'api');

        $data = Language::factory()->make()->toArray();
        $response = $this->postJson('/api/languages', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Language added successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('languages', [
            'name' => $data['name'],
            'user_id' => $this->user->id
        ]);
    }

    public function testUpdateLanguage()
    {
        $this->actingAs($this->user, 'api');

        $data = Language::factory()->make()->toArray();
        $response = $this->putJson('/api/languages/' . $this->language->id, $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Language updated successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseHas('languages', [
            'id' => $this->language->id,
            'name' => $data['name']
        ]);
    }

    public function testDeleteLanguage()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->deleteJson('/api/languages/' . $this->language->id);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Language deleted successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseMissing('languages', ['id' => $this->language->id]);
    }

    public function testInvoke()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->json('POST', '/api/languages/invoke');
        $response->assertStatus(405)
                 ->assertJson([
                     'message' => 'Method not allowed',
                     'status' => 405
                 ]);
    }
}
