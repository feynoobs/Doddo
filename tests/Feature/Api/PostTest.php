<?php

namespace Tests\Feature\Api;

use App\Models\Thread;
use App\Models\Response as ThreadResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_endpoint_validates_and_creates_response(): void
    {
        $thread = Thread::factory()->create();

        $payload = [
            'thread_id' => $thread->id,
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'message' => 'Hello world',
            'ip' => '127.0.0.1',
        ];

        $response = $this->postJson(route('api.post'), $payload);

        $response->assertOk();
        $this->assertDatabaseHas('responses', [
            'thread_id' => $thread->id,
            'name' => 'Alice',
            'email' => 'alice@example.com',
            'message' => 'Hello world',
        ]);
    }

    public function test_post_endpoint_requires_message(): void
    {
        $response = $this->postJson(route('api.post'), []);
        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['message']);
    }
}


