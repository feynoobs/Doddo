<?php

namespace Tests\Unit\Controllers\Api;

use App\Http\Controllers\Api\ThreadListController;
use App\Models\Group;
use App\Models\Board;
use App\Models\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class ThreadListControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 指定した板に紐づくスレッド一覧と板情報を返すことを検証する
     */
    public function test_thread_list_controller_returns_board_with_threads(): void
    {
        // Create test data
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $board = Board::create([
            'group_id' => $group->id,
            'name' => 'Test Board',
            'sequence' => 1,
            'default_response_name' => 'Anon'
        ]);

        $thread1 = Thread::create([
            'board_id' => $board->id,
            'name' => 'Thread 1',
            'sequence' => 1
        ]);

        $thread2 = Thread::create([
            'board_id' => $board->id,
            'name' => 'Thread 2',
            'sequence' => 2
        ]);

        // Create request with board id
        $request = Request::create('/api/threads', 'GET', ['id' => $board->id]);

        // Execute the controller
        $controller = new ThreadListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        
        // Check response structure
        $this->assertArrayHasKey('board', $data);
        $this->assertArrayHasKey('threads', $data);
        
        // Check board data
        $this->assertEquals('Test Board', $data['board']['name']);
        $this->assertEquals($board->id, $data['board']['id']);
        
        // Check threads data
        $this->assertCount(2, $data['threads']);
        $this->assertEquals('Thread 1', $data['threads'][0]['name']);
        $this->assertEquals('Thread 2', $data['threads'][1]['name']);
    }

    /**
     * 存在しない板IDを指定した場合、空配列が返ることを検証する
     */
    public function test_thread_list_controller_returns_empty_array_when_board_not_found(): void
    {
        // Create request with non-existent board id
        $request = Request::create('/api/threads', 'GET', ['id' => 999]);

        // Execute the controller
        $controller = new ThreadListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    /**
     * スレッドが存在しない板を指定した場合のレスポンス構造を検証する
     */
    public function test_thread_list_controller_handles_board_without_threads(): void
    {
        // Create test data
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $board = Board::create([
            'group_id' => $group->id,
            'name' => 'Empty Board',
            'sequence' => 1,
            'default_response_name' => 'Anon'
        ]);

        // Create request with board id
        $request = Request::create('/api/threads', 'GET', ['id' => $board->id]);

        // Execute the controller
        $controller = new ThreadListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        
        // Check response structure
        $this->assertArrayHasKey('board', $data);
        $this->assertArrayHasKey('threads', $data);
        
        // Check board data
        $this->assertEquals('Empty Board', $data['board']['name']);
        $this->assertEquals($board->id, $data['board']['id']);
        
        // Check that threads array is empty
        $this->assertCount(0, $data['threads']);
    }

    /**
     * リクエストにIDが無い場合、空配列が返ることを検証する
     */
    public function test_thread_list_controller_without_id_parameter(): void
    {
        // Create request without id parameter
        $request = Request::create('/api/threads', 'GET');

        // Execute the controller
        $controller = new ThreadListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }
}
