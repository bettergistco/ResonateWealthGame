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

namespace App\Models;

use Carbon\Carbon;
use PHPExperts\ConciseUuid\ConciseUuidModel;

/**
 * @property string $id
 * @property string $game_id
 * @property int    $spending_goal
 * @property int    $actual_differential
 * @property Carbon $created_at
 **/
class GameRound extends ConciseUuidModel
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
