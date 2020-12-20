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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Notifications\Notifiable;
use PHPExperts\ConciseUuid\ConciseUuidAuthModel;

/**
 * @property string $id
 * @property string $name         The user's display name.
 * @property string $email
 * @property string $password
 * @property float  $luck         Luck is a continuum between -1.0 Tragic Life and +1.0 Godling
 * @property int    $peak_wealth  The peak wealth a user has successfully reached at least 3x.
 * @property int    $days_played  The number of days in total a user has played.
 * @property int    $days_streak  The biggest number of consecutive days played.
 * @property int    $live_streak  The number of days you've currently played consec
 * @property int    $near_goal    The user's wealth horizon near-goal (progrmatically determined).
 * @property int    $far_goal     The user's specified desired long-term wealth horizon.
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property Game[] $games
 **/
class User extends ConciseUuidAuthModel
{
    use Notifiable;

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class);
    }
}
