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

namespace App\Jobs;

use App\Helpers\MailManager;
use App\Mail\CustomEmail;
use App\Models\EmailTemplate;
use App\Models\User;
use App\Notifications\PasswordReset as PasswordResetNotification;
use Exception;

class SendPasswordResetEmailJob
{
    /** @var User::class */
    protected $user;

    /**
     * The Password Reset Token.
     * @var string
     */
    protected $token;

    /**
     * SendPasswordResetEmailJob constructor.
     *
     * @param User   $user
     * @param string $token
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->sendPasswordResetEmail($this->user, $this->token);
    }

    /**
     * Sends the Password Reset email.
     *
     * @param User $user
     */
    protected function sendPasswordResetEmail(User $user, $token)
    {
        // @FIXME: Need to implement this.
    }

    public function failed(Exception $e)
    {
        // Send user notification of failure, etc...
    }
}
