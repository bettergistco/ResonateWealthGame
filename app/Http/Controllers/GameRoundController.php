<?php declare(strict_types=1);

/**
 * This file is part of The Resonate Wealth Game, a Bettergist Collective Project.
 *
 * Copyright © 2020 Theodore R. Smith <hopeseekr@gmail.com>
 *   GPG Fingerprint: D8EA 6E4D 5952 159D 7759  2BB4 EEB6 CE72 F441 EC41
 *   https://github.com/BettergistCo/ResonateWealthGame
 *   https://www.resonance.how/wealth-game/
 *
 * This file is licensed under the Creative Commons Attribution v4.0 License.
 */

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Game;
use App\Models\GameRound;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class GameRoundController extends Controller
{
    public function store(Request $request, string $gameId)
    {
        try {
            $data = $this->validate($request, [
                'game_id'  => 'required',
                'spending_goal' => 'required|numeric',
                'expenses' => 'required|expenses',
            ]);

            DB::beginTransaction();
            GameRound::query()->create([
                'game_id' => $gameId,
                'spending_goal' => $request->instance('spending_goal'),
            ]);

            DB::commit();
        } catch (ValidationException $e) {
            DB::rollBack();

            return new JsonResponse([
                'error'   => $e->getMessage(),
                'details' => $e->errors(),
            ], JsonResponse::HTTP_PRECONDITION_FAILED);
        }

        return new JsonResponse($game);
    }
}
