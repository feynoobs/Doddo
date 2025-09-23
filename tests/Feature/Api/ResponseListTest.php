<?php

namespace Tests\Feature\Api;

use App\Models\Board;
use App\Models\Group;
use App\Models\Thread;
use App\Models\Response as ThreadResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ResponseListTest extends TestCase
{
    use RefreshDatabase;

    public function test_responses_endpoint_returns_thread_and_responses_when_thread_exists(): void
    {
        $group = Group::factory()->create();
        $board = Board::factory()->create(['group_id' => $group->id]);
        $thread = Thread::factory()->create(['board_id' => $board->id]);
        ThreadResponse::factory()->count(3)->create(['thread_id' => $thread->id]);

        $response = $this->postJson(route('api.responses'), ['id' => $thread->id]);

        $response->assertOk();
        $json = $response->json();
        $this->assertArrayHasKey('thread', $json);
        $this->assertArrayHasKey('responses', $json);
        $this->assertCount(3, $json['responses']);
    }

    public function test_responses_endpoint_returns_empty_when_thread_missing(): void
    {
        $response = $this->postJson(route('api.responses'), ['id' => 999999]);

        $response->assertOk();
        $this->assertSame([], $response->json());
    }
}


