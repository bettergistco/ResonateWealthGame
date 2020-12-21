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

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class VersionOnePointOh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->float('luck')->default(0);
            $table->integer('peak_wealth')->default(0);
            $table->integer('days_played')->default(0);
            $table->integer('days_streak')->default(0);
            $table->integer('live_streak')->default(0);
            $table->integer('near_goal')->nullable();
            $table->integer('far_goal')->nullable();
            $table->dropColumn(['created_at', 'updated_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->timestamps();
        });

        Schema::create('games', function (Blueprint $table) {
            $table->string('id', 22)->primary();
            $table->string('name');
            $table->integer('peak_wealth');
            $table->integer('game_days');
            $table->dateTime('last_played_at')->nullable();
            $table->timestamps();
        });

        Schema::create('users_games', function (Blueprint $table) {
            $table->string('id', 22)->primary();
            $table->string('user_id', 22);
            $table->string('game_id', 22);

            $table->unique(['user_id', 'game_id']);

            $table->foreign('user_id')
                ->references('id')
                ->on('users');

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');
        });

        Schema::create('game_rounds', function (Blueprint $table) {
            $table->string('id', 22)->primary();
            $table->string('game_id', 22);
            $table->integer('spending_goal');
            $table->integer('actual_differential');
            $table->timestamp('created_at');

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->string('id', 22)->primary();
            $table->string('name');
            $table->integer('cost');
            $table->integer('calories');
            $table->integer('weight');
            $table->float('luck_percent');
            $table->boolean('global');
            $table->timestamps();
        });

        Schema::create('rounds_expenses', function (Blueprint $table) {
            $table->string('id', 22)->primary();
            $table->string('round_id', 22);
            $table->string('expense_id');

            $table->foreign('round_id')
                ->references('id')
                ->on('game_rounds')
                ->onDelete('cascade');

            $table->foreign('expense_id')
                ->references('id')
                ->on('expenses');
        });

        $this->userTokens();
    }

    protected function userTokens()
    {
        Schema::create('user_tokens', function (Blueprint $table) {
            $table->string('token', 32);
            $table->string('user_id', 22);
            $table->timestamp('created_at')->useCurrent();
            // Constraints
            $table->primary('token');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            // Index the Foreign Keys
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('luck');
            $table->dropColumn('peak_wealth');
            $table->dropColumn('days_played');
        });

        Schema::dropIfExists('user_tokens');
        Schema::dropIfExists('rounds_expenses');
        Schema::dropIfExists('users_games');
        Schema::dropIfExists('game_rounds');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('games');
    }
}
