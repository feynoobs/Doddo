<?php

namespace Tests\Unit\Models;

use App\Models\Group;
use App\Models\Board;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * グループが作成でき、属性値が正しく保存されることを検証する
     */
    public function test_group_can_be_created(): void
    {
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals('Test Group', $group->name);
        $this->assertEquals(1, $group->sequence);
    }

    /**
     * fillable 属性に想定の項目が設定されていることを検証する
     */
    public function test_group_fillable_attributes(): void
    {
        $group = new Group();
        
        $expectedFillable = ['name', 'sequence'];
        $this->assertEquals($expectedFillable, $group->getFillable());
    }

    /**
     * boards リレーション（hasMany）が取得できることを検証する
     */
    public function test_group_has_boards_relationship(): void
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

        $this->assertInstanceOf(Board::class, $group->boards()->first());
        $this->assertEquals('Test Board', $group->boards()->first()->name);
    }

    /**
     * 複数の板を持てること（hasMany）が正しく機能することを検証する
     */
    public function test_group_can_have_multiple_boards(): void
    {
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        Board::create([
            'group_id' => $group->id,
            'name' => 'Board 1',
            'sequence' => 1,
            'default_response_name' => 'Anon'
        ]);

        Board::create([
            'group_id' => $group->id,
            'name' => 'Board 2',
            'sequence' => 2,
            'default_response_name' => 'Anon'
        ]);

        $this->assertEquals(2, $group->boards()->count());
    }

    /**
     * ソフトデリートが有効に機能することを検証する
     */
    public function test_group_uses_soft_deletes(): void
    {
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $group->delete();

        $this->assertSoftDeleted('groups', ['id' => $group->id]);
    }
}
