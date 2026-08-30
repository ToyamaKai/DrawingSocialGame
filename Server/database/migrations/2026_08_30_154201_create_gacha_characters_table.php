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
        Schema::create('gacha_characters', function (Blueprint $table) {
            $table->unsignedAutoIncrement('id')->comment('ID');
            $table->unsignedBigInteger('gacha_id')->comment('ガチャID');
            $table->unsignedBigInteger('character_id')->comment('キャラクターID');
            $table->unsignedTinyInteger('weight')->comment('排出率');
            $table->datetime('created_at')->comment('作成日時');
            $table->datetime('updated_at')->comment('更新日時');
            $table->datetime('deleted_at')->nullable()->comment('削除日時');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gacha_characters');
    }
};
