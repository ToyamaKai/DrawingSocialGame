<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('gacha_characters', 'weight') && !Schema::hasColumn('gacha_characters', 'is_pickup')) {
            Schema::table('gacha_characters', function (Blueprint $table) {
                $table->boolean('is_pickup')->default(false)->after('character_id');
                $table->dropColumn('weight');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('gacha_characters', 'is_pickup') && !Schema::hasColumn('gacha_characters', 'weight')) {
            Schema::table('gacha_characters', function (Blueprint $table) {
                $table->unsignedTinyInteger('weight')->default(0)->after('character_id');
                $table->dropColumn('is_pickup');
            });
        }
    }
};