<?php

namespace App\Services;

use App\Models\User;
use App\Models\Gacha;
use App\Models\GachaCharacter;
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
            $gacha = Gacha::findOrFail($gachaId);

            // 石が足りるか確認
            if ($user->stone < $gacha->ten_draw_cost) {
                throw new RuntimeException('石が不足しています。');
            }

            // ガチャ対象キャラクター取得
            $gachaCharacters = GachaCharacter::where('gacha_id', $gachaId)
                ->with('character')
                ->get();

            if ($gachaCharacters->isEmpty()) {
                throw new RuntimeException('ガチャ対象が存在しません。');
            }

            $results = [];

            // 10回抽選
            for ($i = 0; $i < 10; $i++) {

                $gachaCharacter = $this->drawCharacter($gachaCharacters);

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
            $user->decrement('stone', $gacha->ten_draw_cost);

            return [
                'remaining_stone' => $user->stone - $gacha->ten_draw_cost,
                'results' => $results,
            ];
        });
    }

    private function drawCharacter($gachaCharacters)
    {
        $random = mt_rand(1, 10000);

        $total = 0;

        foreach ($gachaCharacters as $gachaCharacter) {

            $total += $gachaCharacter->draw_rate;

            if ($random <= $total) {
                return $gachaCharacter;
            }
        }

        throw new RuntimeException('ガチャ抽選に失敗しました。');
    }
}