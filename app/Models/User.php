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

use App\Jobs\SendPasswordResetEmailJob;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPExperts\ConciseUuid\ConciseUuidAuthModel;
use Tymon\JWTAuth\Contracts\JWTSubject;

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
class User extends ConciseUuidAuthModel implements AuthenticatableContract, JWTSubject
{
    use DispatchesJobs;
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

    /**
     * Validation rules for user registration.
     *
     * @return array
     */
    public static function registrationRules(): array
    {
        return [
            'name'                  => 'required',
            'email'                 => ['bail', 'required', 'max:255', 'email', 'unique:users,email,NULL,id'],
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
        ];
    }

    /**
     * Validation rules for additional user creation.
     *
     * @return array
     */
    public static function accountCreationRules(): array
    {
        $rules = [
            'email'      => ['bail', 'required', 'max:255', 'email', 'unique:users,email,NULL,id'],
            'name'       => ['required', 'max:255'],
        ];

        return $rules;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @param  string $userId
     * @return array
     */
    public static function updateValidationRules(string $userId): array
    {
        $rules = [
            'email'      => ['sometimes', 'required', 'email', 'max:255', 'unique:users,email,' . $userId],
            'name'       => ['sometimes', 'required', 'max:255'],
        ];

        return $rules;
    }

    /**
     * Validation rules for resetting password.
     *
     * @return array
     */
    public static function resetPasswordValidationRules(): array
    {
        return [
            'reset_token'           => 'required_with:password',
            'password'              => 'sometimes|required|min:6|confirmed',
            'password_confirmation' => 'sometimes|required|min:6',
        ];
    }

    /**
     * Custom error message for admin domain validation while adding/updating user information.
     *
     * @return array
     */
    public static function validationFailureMessages()
    {
        $customMessages = [
            'email.regex' => '@' . env('ADMIN_DOMAIN_NAME').' domain name is restricted. Please use another domain name.',
            'phone.regex' => 'Please provide a 10 digit phone number.',
        ];

        return $customMessages;
    }

    /**
     * Returns the loggedIn user if present, else seeded UnknownUser.
     *
     * @return User
     */
    public static function currentUser(): self
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser) {
            $currentUser = self::query()->find(self::SYSTEM_USER_ID);
        }

        return $currentUser;
    }

    /**
     * @return string
     */
    public function getJWTIdentifier(): string
    {
        return $this->id;
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Set the user password.
     *
     * @param string $password
     */
    public function setPasswordAttribute(?string $password): void
    {
//        $this->plaintextPassword = $password;
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Send resent password email to the user.
     * @param User|null $loggedInUser
     */
    public function sendResetPasswordEmail(self $loggedInUser = null): void
    {
        $token = UserToken::generateResetToken($this->id);
        $job = (new SendPasswordResetEmailJob($this, $token));

        $this->dispatch($job);
    }
}
