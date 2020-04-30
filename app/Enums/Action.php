<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class Action extends Enum
{
     const Login = 'User Logged in';
     const UpdatePassword = 'User updated his password';
     const RequestResetPassword = 'User has requested to reset his password';
     const ResetPassword = 'User has reset his password';
     const CreateProfile = 'User has created his profile';
}
