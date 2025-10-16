<?php

namespace Tests\Unit\Models;

use App\Models\Response;
use App\Models\Thread;
use App\Models\Board;
use App\Models\Group;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResponseTest extends TestCase
{
    use RefreshDatabase;

    /**
     * レスポンスが作成でき、属性値が正しく保存されることを検証する
     */
    public function test_response_can_be_created(): void
    {
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

        $thread = Thread::create([
            'board_id' => $board->id,
            'name' => 'Test Thread',
            'sequence' => 1
        ]);

        $response = Response::create([
            'thread_id' => $thread->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Test message'
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('Test User', $response->name);
        $this->assertEquals('test@example.com', $response->email);
        $this->assertEquals('127.0.0.1', $response->ip);
        $this->assertEquals('Test message', $response->message);
        $this->assertEquals($thread->id, $response->thread_id);
    }

    /**
     * fillable 属性に想定の項目が設定されていることを検証する
     */
    public function test_response_fillable_attributes(): void
    {
        $response = new Response();
        
        $expectedFillable = ['thread_id', 'name', 'email', 'ip', 'message'];
        $this->assertEquals($expectedFillable, $response->getFillable());
    }

    /**
     * スレッドへの belongsTo 関係が正しく機能することを検証する
     */
    public function test_response_belongs_to_thread(): void
    {
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

        $thread = Thread::create([
            'board_id' => $board->id,
            'name' => 'Test Thread',
            'sequence' => 1
        ]);

        $response = Response::create([
            'thread_id' => $thread->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Test message'
        ]);

        $this->assertEquals($thread->id, $response->thread_id);
    }

    /**
     * 任意項目（name・email）を省略した場合でも作成できることを検証する
     */
    public function test_response_can_be_created_with_nullable_fields(): void
    {
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

        $thread = Thread::create([
            'board_id' => $board->id,
            'name' => 'Test Thread',
            'sequence' => 1
        ]);

        $response = Response::create([
            'thread_id' => $thread->id,
            'name' => 'tester',
            'email' => null,
            'ip' => '127.0.0.1',
            'message' => 'Anonymous message'
        ]);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotNull($response->name);
        $this->assertNull($response->email);
        $this->assertEquals('127.0.0.1', $response->ip);
        $this->assertEquals('Anonymous message', $response->message);
    }

    /**
     * ソフトデリートが有効に機能することを検証する
     */
    public function test_response_uses_soft_deletes(): void
    {
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

        $thread = Thread::create([
            'board_id' => $board->id,
            'name' => 'Test Thread',
            'sequence' => 1
        ]);

        $response = Response::create([
            'thread_id' => $thread->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Test message'
        ]);

        $response->delete();

        $this->assertSoftDeleted('responses', ['id' => $response->id]);
    }
}
