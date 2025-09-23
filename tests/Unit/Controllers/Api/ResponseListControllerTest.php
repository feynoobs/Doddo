<?php

namespace Tests\Unit\Controllers\Api;

use App\Http\Controllers\Api\ResponseListController;
use App\Models\Group;
use App\Models\Board;
use App\Models\Thread;
use App\Models\Response as ResponseModel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class ResponseListControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_response_list_controller_returns_thread_with_responses(): void
    {
        // Create test data
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $board = Board::create([
            'group_id' => $group->id,
            'name' => 'Test Board',
            'sequence' => 1
        ]);

        $thread = Thread::create([
            'board_id' => $board->id,
            'name' => 'Test Thread',
            'sequence' => 1
        ]);

        $response1 = ResponseModel::create([
            'thread_id' => $thread->id,
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'ip' => '127.0.0.1',
            'message' => 'First message'
        ]);

        $response2 = ResponseModel::create([
            'thread_id' => $thread->id,
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Second message'
        ]);

        // Create request with thread id
        $request = Request::create('/api/responses', 'GET', ['id' => $thread->id]);

        // Execute the controller
        $controller = new ResponseListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        
        // Check response structure
        $this->assertArrayHasKey('thread', $data);
        $this->assertArrayHasKey('responses', $data);
        
        // Check thread data
        $this->assertEquals('Test Thread', $data['thread']['name']);
        $this->assertEquals($thread->id, $data['thread']['id']);
        
        // Check responses data
        $this->assertCount(2, $data['responses']);
        $this->assertEquals('First message', $data['responses'][0]['message']);
        $this->assertEquals('Second message', $data['responses'][1]['message']);
    }

    public function test_response_list_controller_returns_empty_array_when_thread_not_found(): void
    {
        // Create request with non-existent thread id
        $request = Request::create('/api/responses', 'GET', ['id' => 999]);

        // Execute the controller
        $controller = new ResponseListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    public function test_response_list_controller_handles_thread_without_responses(): void
    {
        // Create test data
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $board = Board::create([
            'group_id' => $group->id,
            'name' => 'Test Board',
            'sequence' => 1
        ]);

        $thread = Thread::create([
            'board_id' => $board->id,
            'name' => 'Empty Thread',
            'sequence' => 1
        ]);

        // Create request with thread id
        $request = Request::create('/api/responses', 'GET', ['id' => $thread->id]);

        // Execute the controller
        $controller = new ResponseListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        
        // Check response structure
        $this->assertArrayHasKey('thread', $data);
        $this->assertArrayHasKey('responses', $data);
        
        // Check thread data
        $this->assertEquals('Empty Thread', $data['thread']['name']);
        $this->assertEquals($thread->id, $data['thread']['id']);
        
        // Check that responses array is empty
        $this->assertCount(0, $data['responses']);
    }

    public function test_response_list_controller_without_id_parameter(): void
    {
        // Create request without id parameter
        $request = Request::create('/api/responses', 'GET');

        // Execute the controller
        $controller = new ResponseListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    public function test_response_list_controller_returns_responses_ordered_by_id(): void
    {
        // Create test data
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $board = Board::create([
            'group_id' => $group->id,
            'name' => 'Test Board',
            'sequence' => 1
        ]);

        $thread = Thread::create([
            'board_id' => $board->id,
            'name' => 'Test Thread',
            'sequence' => 1
        ]);

        // Create responses in reverse order to test ordering
        $response2 = ResponseModel::create([
            'thread_id' => $thread->id,
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Second message'
        ]);

        $response1 = ResponseModel::create([
            'thread_id' => $thread->id,
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'ip' => '127.0.0.1',
            'message' => 'First message'
        ]);

        // Create request with thread id
        $request = Request::create('/api/responses', 'GET', ['id' => $thread->id]);

        // Execute the controller
        $controller = new ResponseListController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        $data = $response->getData(true);
        
        // Check that responses are ordered by id (first created should come first)
        $this->assertCount(2, $data['responses']);
        $this->assertEquals('Second message', $data['responses'][0]['message']);
        $this->assertEquals('First message', $data['responses'][1]['message']);
    }
}
