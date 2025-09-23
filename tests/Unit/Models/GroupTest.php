<?php

namespace Tests\Unit\Models;

use App\Models\Group;
use App\Models\Board;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GroupTest extends TestCase
{
    use RefreshDatabase;

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

    public function test_group_fillable_attributes(): void
    {
        $group = new Group();
        
        $expectedFillable = ['name', 'sequence'];
        $this->assertEquals($expectedFillable, $group->getFillable());
    }

    public function test_group_has_boards_relationship(): void
    {
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        $board = Board::create([
            'group_id' => $group->id,
            'name' => 'Test Board',
            'sequence' => 1
        ]);

        $this->assertInstanceOf(Board::class, $group->boards()->first());
        $this->assertEquals('Test Board', $group->boards()->first()->name);
    }

    public function test_group_can_have_multiple_boards(): void
    {
        $group = Group::create([
            'name' => 'Test Group',
            'sequence' => 1
        ]);

        Board::create([
            'group_id' => $group->id,
            'name' => 'Board 1',
            'sequence' => 1
        ]);

        Board::create([
            'group_id' => $group->id,
            'name' => 'Board 2',
            'sequence' => 2
        ]);

        $this->assertEquals(2, $group->boards()->count());
    }

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
