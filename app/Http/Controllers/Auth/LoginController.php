<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
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

    /**
     * Get the post-login redirect path.
     */
    public function redirectTo()
    {
        // Find beranda menu from database dynamically
        $berandaMenu = MasterMenu::where('nama_menu', 'Beranda')
            ->where('is_active', true)
            ->first();
        
        if ($berandaMenu && $berandaMenu->slug) {
            return $berandaMenu->slug;
        }
        
        // Fallback to default beranda
        return '/beranda';
    }

    public function username()
    {
        return 'email';
    }

    protected function validateLogin(Request $request)
    {
        $rules = [
            'email' => 'required|string',
            'password' => 'required|string',
        ];

        $messages = [
            'email.required' => config('auth_messages.login.email_required'),
            'password.required' => config('auth_messages.login.password_required'),
        ];

        // Add captcha validation only if captcha is enabled
        if (setting('captcha_setting', false)) {
            $rules['g-recaptcha-response'] = 'required|captcha';
            $messages['g-recaptcha-response.required'] = config('auth_messages.login.recaptcha_required');
            $messages['g-recaptcha-response.captcha'] = config('auth_messages.login.recaptcha_failed');
        }

        $request->validate($rules, $messages);
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
