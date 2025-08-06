<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function username()
    {
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha',
        ], [
            'email.required' => config('auth_messages.login.email_required'),
            'password.required' => config('auth_messages.login.password_required'),
            'g-recaptcha-response.required' => config('auth_messages.login.recaptcha_required'),
            'g-recaptcha-response.captcha' => config('auth_messages.login.recaptcha_failed'),
        ]);
    }

    /**
     * Override method untuk custom error message saat kredensial salah
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [config('auth_messages.login.invalid_credentials')],
        ]);
    }

    protected function credentials(Request $request)
    {
        $field = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        return [
            $field => $request->email,
            'password' => $request->password,
        ];
    }
}
