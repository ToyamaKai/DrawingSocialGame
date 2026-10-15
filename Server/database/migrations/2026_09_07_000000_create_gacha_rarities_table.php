<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gacha_rarities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gacha_id');
            $table->unsignedTinyInteger('rarity');
            $table->unsignedInteger('draw_rate')->comment('レアリティ排出率（10,000分率）');
            $table->unsignedInteger('pickup_rate')->default(0)->comment('レアリティ内のピックアップ率（10,000分率）');
            $table->datetime('created_at');
            $table->datetime('updated_at');
            $table->datetime('deleted_at')->nullable();

            $table->unique(['gacha_id', 'rarity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gacha_rarities');
    }
};