<?php

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
            $table->float('luck');
            $table->integer('peak_wealth');
            $table->integer('days_played');
        });

        Schema::create('games', function (Blueprint $table) {
            $table->string('id', 22)->primary();
            $table->string('title');
            $table->integer('peak_wealth');
            $table->integer('game_days');
            $table->dateTime('last_played_at');
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
            $table->timestamps();

            $table->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->string('id', 22)->primary();
            $table->string('name');
            $table->integer('cost');
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

        Schema::dropIfExists('rounds_expenses');
        Schema::dropIfExists('users_games');
        Schema::dropIfExists('game_rounds');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('games');
    }
}
