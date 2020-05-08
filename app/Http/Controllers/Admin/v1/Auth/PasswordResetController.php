<?php

namespace App\Http\Controllers\Admin\v1\Auth;


use App\Enums\Action;
use App\Http\Controllers\Controller;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordResetSuccessNotification;
use Illuminate\Http\Request;
use App\UserHistory;
use Carbon\Carbon;
use App\User;
use App\PasswordReset;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    /**
     * Create token password reset
     *
     * @param  [string] email
     * @return [string] message
     */
    public function create()
    {

        request()->validate([
            'email' => ['required'],
        ]);
        $user = User::where(['email' => request('email')])->first();
        if ($user==null)
            return response([
                'message' => "We can't find a user with that e - mail address . "
            ], 404);


        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Str::random(60)
            ]
        );
        UserHistory::create(['user_id' => $user->id, 'action' => Action::RequestResetPassword,'ip'=>request()->getClientIp()]);


        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetNotification($passwordReset->token, $user->email,$user->first_name, true)
            );
        return response()->json([
            'message' => 'We have e - mailed your password reset link!'
        ]);
    }

    /**
     * Find token password reset
     *
     * @param  [string] $token
     * @return [string] message
     * @return [json] passwordReset object
     */
    public function find($token)
    {
        $passwordReset = PasswordReset::where('token', $token)
            ->first();
        if (!$passwordReset)
            return response([
                'message' => 'This password reset token is invalid . '
            ], 404);
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return response([
                'message' => 'This password reset token is invalid . '
            ], 404);
        }
        return response()->json($passwordReset);
    }

    /**
     * Reset password
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @param  [string] token
     * @return [string] message
     * @return [json] user object
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required | string | email',
            'password' => 'required | string | confirmed',
            'token' => 'required | string'
        ]);
        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return response()->json([
                'message' => 'This password reset token is invalid . '
            ], 404);
        $user = User::where('email', $passwordReset->email)->first();
        if (!$user)
            return response()->json([
                'message' => "We can't find a user with that e-mail address."
            ], 404);
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        UserHistory::create(['user_id' => $user->id, 'action' => Action::ResetPassword,'ip'=>request()->getClientIp()]);
        $user->notify(new PasswordResetSuccessNotification($passwordReset));
        return response()->json($user);
    }
}
