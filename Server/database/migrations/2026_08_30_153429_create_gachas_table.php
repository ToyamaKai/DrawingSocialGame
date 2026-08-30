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
        Schema::create('gachas', function (Blueprint $table) {
            $table->unsignedAutoIncrement('id')->comment('ID');
            $table->string('gacha_name', 50)->comment('ガチャ名');
            $table->datetime('start_at')->comment('開始日時');
            $table->datetime('end_at')->nullable()->comment('終了日時');
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
        Schema::dropIfExists('gachas');
    }
};
