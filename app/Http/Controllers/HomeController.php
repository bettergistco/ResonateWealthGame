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

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }
}
