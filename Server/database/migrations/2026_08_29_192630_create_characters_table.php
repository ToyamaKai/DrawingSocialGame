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
        Schema::create('characters', function (Blueprint $table) {
            $table->unsignedAutoIncrement('id')->comment('ID');
            $table->string('character_name', 50)->comment('キャラクター名');
            $table->unsignedTinyInteger('rarity')->comment('レアリティ');
            $table->unsignedMediumInteger('attack')->comment('初期攻撃力');
            $table->unsignedMediumInteger('hit_point')->comment('初期体力');
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
        Schema::dropIfExists('characters');
    }
};
