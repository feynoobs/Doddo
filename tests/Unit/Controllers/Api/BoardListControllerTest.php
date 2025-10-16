<?php

namespace Tests\Unit\Controllers\Api;

use App\Http\Controllers\Api\BoardListContoller;
use App\Models\Group;
use App\Models\Board;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BoardListControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * グループごとの板一覧を返し、構造と件数が正しいことを検証する
     */
    public function test_board_list_controller_returns_groups_with_boards(): void
    {
        // Create test data
        $group1 = Group::create([
            'name' => 'Group 1',
            'sequence' => 1
        ]);

        $group2 = Group::create([
            'name' => 'Group 2',
            'sequence' => 2
        ]);

        Board::create([
            'group_id' => $group1->id,
            'name' => 'Board 1-1',
            'sequence' => 1,
            'default_response_name' => 'Anon'
        ]);

        Board::create([
            'group_id' => $group1->id,
            'name' => 'Board 1-2',
            'sequence' => 2,
            'default_response_name' => 'Anon'
        ]);

        Board::create([
            'group_id' => $group2->id,
            'name' => 'Board 2-1',
            'sequence' => 1,
            'default_response_name' => 'Anon'
        ]);

        // Execute the controller
        $controller = new BoardListContoller();
        $response = $controller(new \Illuminate\Http\Request());

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        
        // Check that we have both groups
        $this->assertCount(2, $data);
        
        // Check first group structure
        $this->assertArrayHasKey($group1->id, $data);
        $this->assertArrayHasKey('group', $data[$group1->id]);
        $this->assertArrayHasKey('boards', $data[$group1->id]);
        
        // Check second group structure
        $this->assertArrayHasKey($group2->id, $data);
        $this->assertArrayHasKey('group', $data[$group2->id]);
        $this->assertArrayHasKey('boards', $data[$group2->id]);
        
        // Check first group data
        $this->assertEquals('Group 1', $data[$group1->id]['group']['name']);
        $this->assertCount(2, $data[$group1->id]['boards']);
        
        // Check second group data
        $this->assertEquals('Group 2', $data[$group2->id]['group']['name']);
        $this->assertCount(1, $data[$group2->id]['boards']);
    }

    /**
     * グループが存在しない場合、空配列が返ることを検証する
     */
    public function test_board_list_controller_returns_empty_array_when_no_groups(): void
    {
        // Execute the controller with no data
        $controller = new BoardListContoller();
        $response = $controller(new \Illuminate\Http\Request());

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    /**
     * 板が存在しないグループが含まれる場合のレスポンス構造を検証する
     */
    public function test_board_list_controller_handles_groups_without_boards(): void
    {
        // Create a group without boards
        $group = Group::create([
            'name' => 'Empty Group',
            'sequence' => 1
        ]);

        // Execute the controller
        $controller = new BoardListContoller();
        $response = $controller(new \Illuminate\Http\Request());

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        
        $this->assertCount(1, $data);
        $this->assertArrayHasKey($group->id, $data);
        $this->assertEquals('Empty Group', $data[$group->id]['group']['name']);
        $this->assertCount(0, $data[$group->id]['boards']);
    }
}
