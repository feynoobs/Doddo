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
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->comment('所属するグループのID');
            $table->string('name')->comment('板名');
            $table->unsignedBigInteger('sequence')->comment('並び順');
            $table->softDeletes();
            $table->timestamps();
            $table->comment('板テーブル');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
