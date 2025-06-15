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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('thread_id')->comment('所属するスレッドのID');
            $table->string('name')->nullable()->comment('名前');
            $table->string('email')->nullable()->comment('メールアドレス');
            $table->string('uid')->nullable()->comment('投稿者のID');
            $table->string('ip')->comment('投稿者のIPアドレス');
            $table->text('message')->collation('utf8mb4_unicode_520_ci')->comment('投稿内容');
            $table->softDeletes();
            $table->timestamps();
            $table->comment('レステーブル');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
