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

class ExpensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('expenses')->insert([
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Fastfood Taco',
                'cost'           => 1,
                'calories'       => 2,
                'weight'         => 0,
                'luck_percent'   => '0.05',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Large Softdrink',
                'cost'           => 2,
                'calories'       => 3,
                'weight'         => 0,
                'luck_percent'   => '0.35',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Small Coffee',
                'cost'           => 3,
                'calories'       => 0,
                'weight'         => 0,
                'luck_percent'   => '0.25',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Medium Cappuccino',
                'cost'           => 4,
                'calories'       => 1,
                'weight'         => 0,
                'luck_percent'   => '0.25',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Fastfood Burger',
                'cost'           => 5,
                'calories'       => 333,
                'weight'         => 6,
                'luck_percent'   => '0.15',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Fastfood Combo Meal',
                'cost'           => 10,
                'calories'       => 333,
                'weight'         => 11,
                'luck_percent'   => '0.15',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Netflix Standard',
                'cost'           => 15,
                'calories'       => 0,
                'weight'         => 0,
                'luck_percent'   => '0.00',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'Pair of Pants',
                'cost'           => 20,
                'calories'       => 333,
                'weight'         => 3,
                'luck_percent'   => '0.20',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
            [
                'id'             => ConciseUuid::generateNewId(),
                'name'           => 'USB-C Phone Charger',
                'cost'           => 25,
                'calories'       => 333,
                'weight'         => 1,
                'luck_percent'   => '0.25',
                'global'         => true,
                'created_at'     => '2020-12-20 17:52',
                'updated_at'     => '2020-12-20 17:52',
            ],
        ]);
    }
}
