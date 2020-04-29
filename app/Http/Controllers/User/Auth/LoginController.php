<?php

namespace App\Http\Controllers\User\Auth;

use App\AccessType;
use App\Action;
use App\Http\Controllers\Controller;
use App\User;
use App\UserHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function __invoke()
    {

        request()->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required'
        ]);

        $user = User::where('email', request()->email)->first();

        if (!$user || !Hash::check(request()->password, $user->password) || $user->access_type != AccessType::USER) {
            return response([
                'email' => [trans('auth.failed')]
            ], 422);
        }
        UserHistory::create(['user_id' => $user->id, 'action' => Action::Login, 'ip' => request()->getClientIp()]);


        return response(['success'=>true,'token'=>$user->createToken(request()->device_name)->plainTextToken]);

    }

}
