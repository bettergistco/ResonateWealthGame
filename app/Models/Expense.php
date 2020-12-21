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
 * @property string $name
 * @property int    $cost
 * @property int    $calories      How much weight you'll pack on when you eat it.
 * @property int    $weight        How much weight you'll have to lug around if you keep.
 * @property float  $luck_percent  Plus or minus max range of expense's cost,
 *                                 based on the user's internal luck factor.
 * @property bool   $global        Whether the expense is globally usable or just for the player.
 * @property Carbon $created_at
 * @property Carbon $updated_at
 **/
class Expense extends ConciseUuidModel
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
