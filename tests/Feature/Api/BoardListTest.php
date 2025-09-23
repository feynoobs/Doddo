<?php

namespace Tests\Feature\Api;

use App\Models\Board;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BoardListTest extends TestCase
{
    use RefreshDatabase;

    public function test_boards_endpoint_returns_group_and_boards(): void
    {
        Group::factory()->count(2)->create();
        // attach boards to each group
        Group::all()->each(function (Group $group) {
            Board::factory()->count(3)->create(['group_id' => $group->id]);
        });

        $response = $this->postJson(route('api.boards'));

        $response->assertOk();
        $data = $response->json();

        // Expect two group keys
        $this->assertCount(2, $data);
        foreach ($data as $item) {
            $this->assertArrayHasKey('group', $item);
            $this->assertArrayHasKey('boards', $item);
            $this->assertGreaterThanOrEqual(1, count($item['boards']));
        }
    }
}


