<?php

namespace Tests\Feature;

use App\Models\Characters;
use App\Models\GachaRarity;
use App\Models\Gacha_characters;
use App\Models\Gachas;
use App\Models\User;
use App\Services\GachaService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GachaServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_draws_only_pickup_characters_when_pickup_rate_is_one_hundred_percent(): void
    {
        $user = User::create([
            'user_id' => 'test-user',
            'user_name' => 'テストユーザー',
        ]);
        $gacha = Gachas::create([
            'gacha_name' => 'テストガチャ',
            'start_at' => now(),
        ]);
        GachaRarity::create([
            'gacha_id' => $gacha->id,
            'rarity' => 5,
            'draw_rate' => 10000,
            'pickup_rate' => 10000,
        ]);

        $pickup = Characters::create([
            'character_name' => 'ピックアップ',
            'rarity' => 5,
            'attack' => 1,
            'hit_point' => 1,
        ]);
        $regular = Characters::create([
            'character_name' => '通常キャラクター',
            'rarity' => 5,
            'attack' => 1,
            'hit_point' => 1,
        ]);
        Gacha_characters::create([
            'gacha_id' => $gacha->id,
            'character_id' => $pickup->id,
            'is_pickup' => true,
        ]);
        Gacha_characters::create([
            'gacha_id' => $gacha->id,
            'character_id' => $regular->id,
            'is_pickup' => false,
        ]);

        $result = app(GachaService::class)->drawTen($user->user_id, $gacha->id);

        $this->assertCount(10, $result['results']);
        $this->assertSame(
            array_fill(0, 10, $pickup->id),
            array_column($result['results'], 'character_id')
        );
    }
}