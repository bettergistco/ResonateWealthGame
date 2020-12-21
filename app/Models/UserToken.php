<?php declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;

/**
 * App\Models\UserToken.
 *
 * @property string $token       The UUID token issued to the User to reset their password.
 * @property string $user_id     The primary key of the User this token is being issued to.
 * @property Carbon $created_at  The date that this UserToken instance was first stored in DBMS.
 */
class UserToken extends Model
{
    /** @var string The database table used by the model. */
    protected $table = 'user_tokens';

    /** @var string */
    protected $primaryKey = 'token';

    /** @var bool Indicates that the IDs are not auto-incrementing. */
    public $incrementing = false;

    /** @var bool Indicates if the model should be timestamped. */
    public $timestamps = false;

    /** @var array The attributes that are mass assignable. */
    protected $fillable = [
        'token_type', 'user_id',
    ];

    /** @var array The attributes that aren't mass-assignable */
    protected $guarded = ['createdAt'];

    /**
     * Generate a secure token on persisting the object.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        /**
         * Attach to the 'creating' Model Event to provide a UUID
         * for the `id` field (provided by $model->getKeyName()).
         */
        static::saving(function (self $model) {
            $model->{$model->getKeyName()} = (string) $model->generateNewToken();
        });
    }

    /**
     * Generates a cryptographically-secure random hash for the password reset token.
     *
     * @return string The new token.
     */
    public function generateNewToken(): string
    {
        // Grab some truly random bytes, converted to hex code.
        $randomBytes = bin2hex(openssl_random_pseudo_bytes(23));

        // Convert the hex to Base62 [0-9a-zA-Z].
        $this->token = trim(gmp_strval(gmp_init(($randomBytes), 16), 62));

        return $this->token;
    }

    /**
     * Generates a password reset token.
     *
     * @param  string $userId
     * @return string
     */
    public static function generateResetToken(string $userId): string
    {
        $userToken = self::query()->updateOrCreate([
            'user_id'    => $userId,
            'created_at' => Carbon::now(),
        ]);

        return $userToken->token;
    }

    /**
     * Check whether the token is valid or not.
     * @param  string      $token
     * @param  string|null $forEmail
     * @return User
     */
    public static function ensureValidToken(?string $token, string $forEmail = null): User
    {
        try {
            $userToken = self::query()->findOrFail($token);
        } catch (ModelNotFoundException $e) {
            throw new InvalidArgumentException('For some strange reason, your security token has become corrupted and is no longer valid. Please try clearing your browser\'s cookies for this site, going into Incognito mode, or use another browser. If all else fails, please contact Customer Support.');
        }

        try {
            /** @var User $user */
            $user = User::query()->findOrFail($userToken->user_id);
        } catch (ModelNotFoundException $e) {
            throw new InvalidArgumentException('The security token provided by your browser was issued for somebody else\'s account. Please log in again.');
        }

        if ($forEmail && $user->email != $forEmail) {
            throw new LogicException("A reset token was used for the wrong email: $forEmail.");
        }

        return $user;
    }
}
