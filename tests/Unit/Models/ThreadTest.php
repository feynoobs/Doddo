<?php

namespace Tests\Unit\Models;

use App\Models\Thread;
use App\Models\Board;
use App\Models\Group;
use App\Models\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ThreadTest extends TestCase
{
    use RefreshDatabase;

    /**
     * スレッドが作成でき、属性値が正しく保存されることを検証する
     */
    public function test_thread_can_be_created(): void
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

        $this->assertInstanceOf(Thread::class, $thread);
        $this->assertEquals('Test Thread', $thread->name);
        $this->assertEquals($board->id, $thread->board_id);
        $this->assertEquals(1, $thread->sequence);
    }

    /**
     * fillable 属性に想定の項目が設定されていることを検証する
     */
    public function test_thread_fillable_attributes(): void
    {
        $thread = new Thread();
        
        $expectedFillable = ['board_id', 'name', 'sequence'];
        $this->assertEquals($expectedFillable, $thread->getFillable());
    }

    /**
     * 板への belongsTo 関係が正しく機能することを検証する
     */
    public function test_thread_belongs_to_board(): void
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

        $this->assertEquals($board->id, $thread->board_id);
    }

    /**
     * responses リレーションが取得できることを検証する
     */
    public function test_thread_has_responses_relationship(): void
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

        $this->assertInstanceOf(Response::class, $thread->responses()->first());
        $this->assertEquals('Test message', $thread->responses()->first()->message);
    }

    /**
     * responses リレーションがID順（作成順）で取得できることを検証する
     */
    public function test_thread_responses_are_ordered_by_id(): void
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

        $response1 = Response::create([
            'thread_id' => $thread->id,
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'ip' => '127.0.0.1',
            'message' => 'First message'
        ]);

        $response2 = Response::create([
            'thread_id' => $thread->id,
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Second message'
        ]);

        $responses = $thread->responses;
        $this->assertEquals(2, $responses->count());
        $this->assertEquals('First message', $responses->first()->message);
        $this->assertEquals('Second message', $responses->last()->message);
    }

    /**
     * 複数レスポンスを持てること（hasMany）が正しく機能することを検証する
     */
    public function test_thread_can_have_multiple_responses(): void
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

        Response::create([
            'thread_id' => $thread->id,
            'name' => 'User 1',
            'email' => 'user1@example.com',
            'ip' => '127.0.0.1',
            'message' => 'First message'
        ]);

        Response::create([
            'thread_id' => $thread->id,
            'name' => 'User 2',
            'email' => 'user2@example.com',
            'ip' => '127.0.0.1',
            'message' => 'Second message'
        ]);

        $this->assertEquals(2, $thread->responses()->count());
    }

    /**
     * ソフトデリートが有効に機能することを検証する
     */
    public function test_thread_uses_soft_deletes(): void
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

        $thread->delete();

        $this->assertSoftDeleted('threads', ['id' => $thread->id]);
    }
}
