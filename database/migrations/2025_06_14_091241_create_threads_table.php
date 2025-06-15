<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->unsignedBigInteger('board_id')->comment('所属する板のID');
            $table->string('name')->comment('スレッド名');
            $table->unsignedBigInteger('sequence')->comment('並び順');
            $table->softDeletes();
            $table->timestamps();
            $table->comment('スレッドテーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('threads');
    }
};
