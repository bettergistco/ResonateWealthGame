<?php

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
