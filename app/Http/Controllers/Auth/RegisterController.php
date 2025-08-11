<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\MasterMenu;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        // Check if user registration is enabled
        if (!setting('user_registration', true)) {
            return redirect()->route('login')
                ->with('error', 'Pendaftaran user baru saat ini tidak diizinkan.');
        }

        return view('auth.register');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(\Illuminate\Http\Request $request)
    {
        // Check if user registration is enabled
        if (!setting('user_registration', true)) {
            return redirect()->route('login')
                ->with('error', 'Pendaftaran user baru saat ini tidak diizinkan.');
        }

        $this->validator($request->all())->validate();

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
                    ? new \Illuminate\Http\JsonResponse([], 201)
                    : redirect($this->redirectPath());
    }

    /**
     * Get the post-registration redirect path.
     */
    public function redirectTo()
    {
        $beranda = MasterMenu::getBeranda();
        return $beranda ? $beranda->slug : '/beranda';
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users', 'regex:/^[a-zA-Z0-9_]+$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];

        $messages = [
            // Name messages
            'name.required' => config('auth_messages.register.name_required'),
            'name.max' => config('auth_messages.register.name_max'),

            // Username messages
            'username.required' => config('auth_messages.register.username_required'),
            'username.unique' => config('auth_messages.register.username_unique'),
            'username.regex' => config('auth_messages.register.username_format'),

            // Email messages
            'email.required' => config('auth_messages.register.email_required'),
            'email.email' => config('auth_messages.register.email_valid'),
            'email.unique' => config('auth_messages.register.email_unique'),

            // Password messages
            'password.required' => config('auth_messages.register.password_required'),
            'password.min' => config('auth_messages.register.password_min'),
            'password.confirmed' => config('auth_messages.register.password_confirmed'),
        ];

        // Add captcha validation only if captcha is enabled
        if (setting('captcha_setting', false)) {
            $rules['g-recaptcha-response'] = ['required', 'captcha'];
            $messages['g-recaptcha-response.required'] = config('auth_messages.register.recaptcha_required');
            $messages['g-recaptcha-response.captcha'] = config('auth_messages.register.recaptcha_failed');
        }

        return Validator::make($data, $rules, $messages);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}
