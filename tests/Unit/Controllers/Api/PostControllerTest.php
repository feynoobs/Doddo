<?php

namespace Tests\Unit\Controllers\Api;

use App\Http\Controllers\Api\PostController;
use App\Models\Response;
use App\Models\Thread;
use App\Models\Board;
use App\Models\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_post_controller_creates_response_with_all_fields(): void
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

        // Create request data
        $requestData = [
            'thread_id' => $thread->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Test message'
        ];

        $request = Request::create('/api/post', 'POST', $requestData);

        // Execute the controller
        $controller = new PostController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        // Check that response was created in database
        $this->assertDatabaseHas('responses', [
            'thread_id' => $thread->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Test message'
        ]);
    }

    public function test_post_controller_creates_response_with_nullable_fields(): void
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

        // Create request data with only required message field
        $requestData = [
            'thread_id' => $thread->id,
            'message' => 'Anonymous message'
        ];

        $request = Request::create('/api/post', 'POST', $requestData);

        // Execute the controller
        $controller = new PostController();
        $response = $controller($request);

        // Assert response
        $this->assertEquals(200, $response->getStatusCode());
        
        // Check that response was created in database
        $this->assertDatabaseHas('responses', [
            'thread_id' => $thread->id,
            'name' => null,
            'email' => null,
            'message' => 'Anonymous message'
        ]);
    }

    public function test_post_controller_validates_required_message_field(): void
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

        // Create request data without required message field
        $requestData = [
            'thread_id' => $thread->id,
            'name' => 'Test User'
        ];

        $request = Request::create('/api/post', 'POST', $requestData);

        // Execute the controller and expect validation exception
        $controller = new PostController();
        
        $this->expectException(\Illuminate\Validation\ValidationException::class);
        $controller($request);
    }

    public function test_post_controller_returns_json_response(): void
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

        // Create request data
        $requestData = [
            'thread_id' => $thread->id,
            'message' => 'Test message'
        ];

        $request = Request::create('/api/post', 'POST', $requestData);

        // Execute the controller
        $controller = new PostController();
        $response = $controller($request);

        // Assert response type and content
        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('[]', $response->getContent());
    }
}
