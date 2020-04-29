<?php

namespace App\Http\Controllers\Admin\Auth;

use App\AccessType;
use App\Http\Controllers\Controller;
use App\User;
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

        if (!$user || !Hash::check(request()->password, $user->password) || $user->access_type != AccessType::ADMIN) {
            return response([
                'email' => [trans('auth.failed')]
            ], 422);
        }

        return $user->createToken(request()->device_name)->plainTextToken;

    }

}
