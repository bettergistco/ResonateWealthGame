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

//use App\Helpers\JWTHelper;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use PHPExperts\JWTHelper\JWTHelper;
use RuntimeException;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;

class AuthenticateController extends Controller
{
    /**
     * Registers the user.
     *
     * @param JWTAuth $jwt
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function register(JWTAuth $jwt, Request $request) : JsonResponse
    {
        DB::beginTransaction();
        // Get all of the input from the request.
        $payload = $request->all();
        // Force all emails to be in lower case.
        $payload['email'] = strtolower($request->input('email', null));
        // Replace the input of the current Request.
        $request->replace($payload);
        // Perform validation on Request.
        try {
            $this->validate(
                $request,
                User::registrationRules(),
                User::validationFailureMessages()
            );
        } catch (ValidationException $e) {
            return new JsonResponse([
                'error' => $e->getMessage(),
                'details' => $e->errors(),
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        unset($payload['password_confirmation']);

        // user is created, socialLoginEntry is created and notified.
        $user = User::query()->create($payload);
        try {
            $token = JWTHelper::login($user);
        } catch (JWTException $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Could not create token',
            ], 401);
        }

        DB::commit();

        return response()->json([
            'token' => $token,
            'user'  => $user,
        ]);
    }

    /**
     * Login authentication.
     *
     * @param JWTAuth $jwt
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function login(JWTAuth $jwt, Request $request): JsonResponse
    {
        $this->validateWith([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], $request);

        $payload = $request->only(['email', 'password']);
        $payload = array_replace($payload, ['email' => strtolower($payload['email'])]);

        /** @var User $user */
        $user = User::query()->where(['email' => $payload['email']])->first();
        if (empty($user)) {
            return response()->json([
                'message' => 'Invalid username or password',
            ], 401);
        }

        try {
            $rememberMe = filter_var($request->input('remember_me'),
                FILTER_VALIDATE_BOOLEAN
            );
            if ($rememberMe === true) {
                JWTHelper::setRememberMe();
            }

            if (!$token = JWTHelper::login($user)) {
                return response()->json([
                    'message' => 'Invalid username or password',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Could not validate token',
                'details' => $e->getMessage(),
                'trace' => $e->getTrace()
            ], 401);
        }

        return response()->json([
            'token'                   => $token,
            'user'                    => $user,
        ]);
    }

    /**
     * Login authentication.
     *
     * @param JWTAuth $jwt
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function oauth(JWTAuth $jwt, Request $request): JsonResponse
    {
        $this->validateWith([
            'client_id'     => ['required'],
            'client_secret' => ['required'],
            'grant_type'    => ['required'],
        ], $request);

        $payload = [
            'email'    => $request->input('client_id'),
            'password' => $request->input('client_secret'),
        ];

        $user = User::query()->where(['email' => $payload['email']])->first();
        if (empty($user)) {
            return response()->json([
                'message' => 'Invalid username or password',
            ], 401);
        }

        $statusMessage = $user->checkIsActive();

        if (!$statusMessage) {
            return response()->json([
                'error' => 'You have not verified your email yet.',
            ], 401);
        }

        try {
            if (!$token = JWTHelper::attempt($jwt, $payload)) {
                return response()->json([
                    'message' => 'Invalid client_id or client_secret.',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Could not validate token',
            ], 401);
        }

        return response()->json([
            'success'      => true,
            'access_token' => $token,
        ]);
    }

    /**
     * Refresh the token.
     *
     * @param JWTAuth $jwt
     * @return JsonResponse
     */
    public function refreshToken(JWTAuth $jwt): JsonResponse
    {
        $token = $jwt->getPayload()->toArray();
        if (isset($token['remember_me']) && $token['remember_me']) {
            JWTHelper::setRememberMe($jwt);
        }
        $token = $jwt->refresh();

        return response()->json([
            'token' => $token,
            'time'  => time(),
        ]);
    }

    /**
     * Logs out the user.
     *
     * @param JWTAuth $jwt
     * @return JsonResponse
     */
    public function logout(JWTAuth $jwt): JsonResponse
    {
        if ($jwt->invalidate()) {
            return response()->json([
                'message' => 'Logout successful',
            ]);
        }

        return response()->json([
            'message' => 'Logout failed',
        ], 401);
    }

    /**
     * Generates and emails a password reset token.
     *
     * @param JWTAuth $jwt
     * @param string $email The user's email.
     * @return JsonResponse
     */
    public function requestResetToken(JWTAuth $jwt, string $email): JsonResponse
    {
        // 0. Ensure that the requested email is registered.
        $user = User::query()->where(['email' => strtolower($email)])->first();
        if (!$user) {
            // Due to very real security concerns, we always want to return this message.
            // This is so that hackers cannot determine what is a valid email or not, by
            // sending bruteforce requests.
            return response()->json([
                'message' => 'Reset token sent.',
            ], 200);
        }

        // 1. Attempt to authenticate with an existing JWT Token.
        $loggedInUser = null;

        try {
//            $loggedInUser = JWTHelper::authenticateWithToken($jwt);
            $loggedInUser = JWTHelper::authenticate();

            // Verify it the model is accessible for the loggedInUser
            if ($loggedInUser->companyId !== $user->company_id) {
                return response()->json([
                    'message' => 'You do not have access to company ' . $user->company_id,
                ], 400);
            }
        } catch (Exception $e) {
            // Silently bail if they aren't able to authenticate.
        }

        // 2. Send the email.
        $user->sendResetPasswordEmail($loggedInUser);

        return response()->json([
            'message' => 'Reset token sent.',
        ]);
    }

    /**
     * Verifies if a user token is valid or not.
     *
     * @param  Request      $request
     * @return JsonResponse
     */
    public function verifyResetToken(Request $request)
    {
        if (!($token = $request->get('token'))) {
            throw new InvalidArgumentException('No reset token was provided.');
        }

        $user = UserToken::ensureValidToken($token);

        return response()->json([
            'user_id'     => $user->id,
            'reset_token' => $token,
        ]);
    }

    /**
     * Updates a user's password.
     *
     * @param  Request      $request
     * @param  string       $userId
     * @return JsonResponse
     */
    public function resetPassword(Request $request, string $userId): JsonResponse
    {
        if ($request->isMethod('put')) {
            throw new RuntimeException('PUT call has not been implemented');
        }

        try {
            $user = User::findOrFail($userId);
        } catch (ModelNotFoundException $e) {
            throw new InvalidArgumentException('Invalid user ID');
        }

        $this->validate($request, User::resetPasswordValidationRules());

        // Make sure that the token is valid.
        $resetToken = $request->input('reset_token');
        UserToken::ensureValidToken($resetToken, $user->email);

        // @security ONLY pass these 3 parameters to the model.
        //           Passing more would allow an OUTSIDER to change ANY user's details
        //           with merely a valid resetToken...
        $user->update([
            'reset_token' => $resetToken,
            'password'    => $request->input('password'),
            'status'      => 'active',
        ]);

        $payload = [
            'email'    => $user->email,
            'password' => $request->input('password'),
        ];

        $token = JWTHelper::attempt($payload);

        //Check and return if companySettings are set
        $companySetting = CompanySetting::where(['company_id' => $user->company_id])->first();

        return response()->json([
            'token'                   => $token,
            'is_user_new'             => ($companySetting === null),
            'internal_id_requirement' => $companySetting ? $companySetting->internal_employee_id_requirement : false,
            'user'                    => $user,
        ]);
    }
}
