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

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PHPExperts\ConciseUuid\ConciseUuid;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id'          => ConciseUuid::generateNewId(),
            'name'        => 'Jane',
            'email'       => 'jane@example.com',
            'password'    => '123456!',
            // Luck is a continuum between -1.0 Tragic Life and +1.0 Godling
            'luck'        => 0.0,
            // The peak wealth a user has successfully reached at least 3x.
            'peak_wealth' => 0,
            'days_played' => 0,
            // The biggest number of consecutive days played.
            'days_streak' => 5,
            // The number of days currently played consecutively.
            'live_streak' => 5,
            // The user's wealth horizon near-goal (progrmatically determined).
            'near_goal'   => 1000,
            // The user's specified desired long-term wealth horizon.
            'far_goal'    => 50000,
            'created_at'  => '2020-12-12 19:52',
            'updated_at'  => '2020-12-12 19:52',
        ]);
    }
}
