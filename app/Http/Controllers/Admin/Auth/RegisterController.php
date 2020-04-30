<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Enums\Action;
use App\Http\Controllers\Controller;
use App\Notifications\VerifyEmailNotification;
use App\User;
use App\UserHistory;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Support\Facades\Mail;


class RegisterController extends Controller
{

    use VerifiesEmails;

    public function store()
    {


        $data = request()->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'personal_mobile_number' => [],
            'more_info' => [],
        ]);


        /**
         * @var User $user
         */
        $data['password'] = bcrypt($data['password']);

        $user = null;

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'phone' => request()->personal_mobile_number,
            'more_info' => request()->more_info,
        ]);

        $data['user_id'] = $user->id;

        UserHistory::create(['user_id' => $user->id, 'action' => Action::CreateProfile, 'ip' => request()->getClientIp()]);


        $user->notify(new VerifyEmailNotification($user->first_name));

        return response(['status' => true, 'message' => $user]);
    }


}
