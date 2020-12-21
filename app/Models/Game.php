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
use Illuminate\Database\Eloquent\Relations\HasMany;
use PHPExperts\ConciseUuid\ConciseUuidModel;

/**
 * @property string $id
 * @property string $name            The game's title.
 * @property int    $peak_wealth     The max wealth achieved in the game at least 3x.
 * @property int    $game_days       The number of make-believe days imagined.
 * @property Carbon $last_played_at  The date at which the user last played the game.
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property User[]      $users
 * @property GameRound[] $gameRounds
 **/
class Game extends ConciseUuidModel
{
    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    protected static function booted()
    {
        static::created(function (Game $game) {
            GameRound::start();
        });

        parent::booted();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function gameRounds(): HasMany
    {
        return $this->hasMany(GameRound::class);
    }
}
