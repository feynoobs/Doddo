<?php

namespace Tests\Unit\Models;

use App\Models\Board;
use App\Models\Group;
use App\Models\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BoardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 板が作成でき、属性値が正しく保存されることを検証する
     */
    public function test_board_can_be_created(): void
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

        $this->assertInstanceOf(Board::class, $board);
        $this->assertEquals('Test Board', $board->name);
        $this->assertEquals($group->id, $board->group_id);
        $this->assertEquals(1, $board->sequence);
    }

    /**
     * fillable 属性に想定の項目が設定されていることを検証する
     */
    public function test_board_fillable_attributes(): void
    {
        $board = new Board();
        
        $expectedFillable = ['group_id', 'name', 'sequence', 'default_response_name'];
        $this->assertEquals($expectedFillable, $board->getFillable());
    }

    /**
     * グループへの belongsTo 関係が正しく機能することを検証する
     */
    public function test_board_belongs_to_group(): void
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

        $this->assertEquals($group->id, $board->group_id);
    }

    /**
     * threads リレーションが取得できることを検証する
     */
    public function test_board_has_threads_relationship(): void
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

        $this->assertInstanceOf(Thread::class, $board->threads()->first());
        $this->assertEquals('Test Thread', $board->threads()->first()->name);
    }

    /**
     * 複数スレッドを持てること（hasMany）が正しく機能することを検証する
     */
    public function test_board_can_have_multiple_threads(): void
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

        Thread::create([
            'board_id' => $board->id,
            'name' => 'Thread 1',
            'sequence' => 1
        ]);

        Thread::create([
            'board_id' => $board->id,
            'name' => 'Thread 2',
            'sequence' => 2
        ]);

        $this->assertEquals(2, $board->threads()->count());
    }

    /**
     * ソフトデリートが有効に機能することを検証する
     */
    public function test_board_uses_soft_deletes(): void
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

        $board->delete();

        $this->assertSoftDeleted('boards', ['id' => $board->id]);
    }
}
