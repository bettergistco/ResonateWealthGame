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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ExpensesController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(Expense::all());
    }

    public function store(Request $request)
    {
        try {
            $data = $this->validate($request, [
                'name'         => 'required',
                'cost'         => 'required|integer',
                'calories'     => 'required|int',
                'weight'       => 'required|int',
                'luck_percent' => 'required|numeric',
            ]);
            $data['global'] = false;
        } catch (ValidationException $e) {
            return new JsonResponse([
                'error'   => $e->getMessage(),
                'details' => $e->errors(),
            ], JsonResponse::HTTP_PRECONDITION_FAILED);
        }

        $expense = Expense::query()->create($data);

        return new JsonResponse($expense);
    }
}
