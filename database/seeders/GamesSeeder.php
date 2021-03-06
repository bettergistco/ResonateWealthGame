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

class GamesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('games')->insert([
            'id'             => ConciseUuid::generateNewId(),
            'name'           => 'A Fortuitous Fortune',
            'peak_wealth'    => 0,
            'game_days'      => 0,
            'last_played_at' => null,
            'created_at'     => '2020-12-20 17:52',
            'updated_at'     => '2020-12-20 17:52',
        ]);
    }
}
