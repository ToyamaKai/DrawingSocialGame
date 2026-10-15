<?php

namespace App\Services;

use App\Models\User;
use App\Models\Gachas;
use App\Models\Gacha_characters;
use App\Models\GachaRarity;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class GachaService
{
    public function drawTen(string $userId, int $gachaId): array
    {
        return DB::transaction(function () use ($userId, $gachaId) {

            // ユーザー取得
            $user = User::where('user_id', $userId)
                ->lockForUpdate()
                ->firstOrFail();

            // ガチャ取得
            $gacha = Gachas::findOrFail($gachaId);

            // 石が足りるか確認
            // if ($user->stone < $gacha->ten_draw_cost) {
            //     throw new RuntimeException('石が不足しています。');
            // }

            // ガチャ対象キャラクター取得
            $rarities = GachaRarity::where('gacha_id', $gachaId)->get();
            if ($rarities->isEmpty() || $rarities->sum('draw_rate') !== 10000 || $rarities->contains(
                fn (GachaRarity $rarity) => $rarity->pickup_rate > 10000
            )) {
                throw new RuntimeException('ガチャのレアリティ排出率が不正です。');
            }

            $gachaCharacters = Gacha_characters::where('gacha_id', $gachaId)
                ->with('character')
                ->get();

            if ($gachaCharacters->isEmpty()) {
                throw new RuntimeException('ガチャ対象が存在しません。');
            }

            $results = [];

            // 10回抽選
            for ($i = 0; $i < 10; $i++) {

                $rarity = $this->drawRarity($rarities);
                $charactersInRarity = $gachaCharacters->filter(
                    fn (Gacha_characters $gachaCharacter) => $gachaCharacter->character->rarity === $rarity->rarity
                );

                if ($charactersInRarity->isEmpty()) {
                    throw new RuntimeException('レアリティに対応するキャラクターが存在しません。');
                }

                $gachaCharacter = $this->drawCharacter($charactersInRarity, $rarity->pickup_rate);

                // ユーザーにキャラクター付与
                $user->userCharacters()->create([
                    'character_id' => $gachaCharacter->character_id,
                    'level' => 1,
                ]);

                $results[] = [
                    'character_id' => $gachaCharacter->character_id,
                    'character_name' => $gachaCharacter->character->character_name,
                    'rarity' => $gachaCharacter->character->rarity,
                ];
            }

            // 石を消費
            // $user->decrement('stone', $gacha->ten_draw_cost);

            return [
                'remaining_stone' => $user->stone - $gacha->ten_draw_cost,
                'results' => $results,
            ];
        });
    }

    private function drawRarity($rarities): GachaRarity
    {
        return $this->drawWeighted($rarities, 'draw_rate');
    }

    private function drawCharacter($gachaCharacters, int $pickupRate): Gacha_characters
    {
        $pickupCharacters = $gachaCharacters->filter(fn (Gacha_characters $character) => $character->is_pickup);
        $regularCharacters = $gachaCharacters->reject(fn (Gacha_characters $character) => $character->is_pickup);

        if ($pickupCharacters->isNotEmpty() && $regularCharacters->isNotEmpty() && $pickupRate > 0) {
            $group = mt_rand(1, 10000) <= $pickupRate ? $pickupCharacters : $regularCharacters;
        } else {
            $group = $pickupCharacters->isNotEmpty() ? $pickupCharacters : $regularCharacters;
        }

        if ($group->isEmpty()) {
            throw new RuntimeException('ガチャ対象が存在しません。');
        }

        return $group->random();
    }

    private function drawWeighted($items, string $weightColumn)
    {
        $total = $items->sum($weightColumn);
        if ($total <= 0) {
            throw new RuntimeException('ガチャ排出率が不正です。');
        }

        $random = mt_rand(1, $total);

        $current = 0;

        foreach ($items as $item) {
            $current += $item->{$weightColumn};

            if ($random <= $current) {
                return $item;
            }
        }

        throw new RuntimeException('ガチャ抽選に失敗しました。');
    }
}