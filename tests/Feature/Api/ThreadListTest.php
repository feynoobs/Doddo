<?php

namespace Tests\Feature\Api;

use App\Models\Board;
use App\Models\Group;
use App\Models\Thread;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThreadListTest extends TestCase
{
    use RefreshDatabase;

    public function test_threads_endpoint_returns_board_and_threads_when_board_exists(): void
    {
        $group = Group::factory()->create();
        $board = Board::factory()->create(['group_id' => $group->id]);
        Thread::factory()->count(2)->create(['board_id' => $board->id]);

        $response = $this->postJson(route('api.threads'), ['id' => $board->id]);

        $response->assertOk();
        $response->assertJsonFragment(['id' => $board->id]);
        $json = $response->json();
        $this->assertArrayHasKey('board', $json);
        $this->assertArrayHasKey('threads', $json);
        $this->assertCount(2, $json['threads']);
    }

    public function test_threads_endpoint_returns_empty_when_board_missing(): void
    {
        $response = $this->postJson(route('api.threads'), ['id' => 999999]);

        $response->assertOk();
        $this->assertSame([], $response->json());
    }
}


