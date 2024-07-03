<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Http\Requests\Api\BookmarkRequest;
use App\Http\Resources\BookmarkResource;
use App\Models\Bookmark;
use App\Models\User;
use App\Services\Api\BookmarkService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookmarkControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->bookmarkService = $this->createMock(BookmarkService::class);
        $this->app->instance(BookmarkService::class, $this->bookmarkService);

        $this->user = User::factory()->create();
        $this->bookmark = Bookmark::factory()->create(['user_id' => $this->user->id]);
    }

    public function testIndex()
    {
        $this->actingAs($this->user, 'api');

        $bookmarks = collect([$this->bookmark]);
        $this->bookmarkService->method('get')->willReturn($bookmarks);

        $response = $this->getJson('/api/bookmarks');
        $response->assertStatus(200)
                 ->assertJson([
                     'data' => [
                         [
                             'id' => $this->bookmark->id,
                         ]
                     ],
                     'message' => 'Bookmarks fetched successfully',
                     'status' => 200
                 ]);
    }

    public function testStore()
    {
        $this->actingAs($this->user, 'api');

        $data = Bookmark::factory()->make()->toArray();
        $this->bookmarkService->method('save')->willReturn(new Bookmark($data));

        $response = $this->postJson('/api/bookmarks', $data);
        $response->assertStatus(201)
                 ->assertJson([
                     'data' => [
                         'id' => $data['id'],
                     ],
                     'message' => 'Bookmark created successfully',
                     'status' => 201
                 ]);
    }

    public function testDestroyUnauthorized()
    {
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser, 'api');

        $response = $this->deleteJson('/api/bookmarks/' . $this->bookmark->id);
        $response->assertStatus(401)
                 ->assertJson([
                     'message' => 'Unauthorized',
                     'status' => 401
                 ]);
    }

    public function testDestroySuccessfully()
    {
        $this->actingAs($this->user, 'api');

        $response = $this->deleteJson('/api/bookmarks/' . $this->bookmark->id);
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Bookmark deleted successfully',
                     'status' => 200
                 ]);

        $this->assertDatabaseMissing('bookmarks', ['id' => $this->bookmark->id]);
    }
}
