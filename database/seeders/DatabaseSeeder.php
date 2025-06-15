<?php

namespace Database\Seeders;

use App\Models\User;
use \App\Models\Group;
use App\Models\Board;
use App\Models\Thread;
use App\Models\Response;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();
        Group::factory(4)->create();
        Board::factory(40)->create();
        Thread::factory(400)->create();
        Response::factory(32000)->create();
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
