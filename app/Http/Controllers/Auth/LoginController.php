<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected function redirectTo()
    {
        if (Auth()->user()->role == UserRole::ADMIN) {
            return route('dashboard')->with('success', __('Login successfuly'));
        }

        if (Auth()->user()->status == UserSatus::BAN) {
            return route('home')->with('error', __('Your account is banned by admin'));
        }

        return route('home')->with('success', __('Login successfuly'));
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $input = $request->all();
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(['email' => $input['email'], 'password' => $input['password']])) {
            if ((auth()->user()->role == UserRole::ADMIN)) {
                return redirect()->route('dashboard')->with('success', __('Login successfuly'));
            } elseif (auth()->user()->role == UserRole::USER) {
                return redirect()->route('home')->with('success', __('Login successfuly'));
            }
        } else {
            return redirect()->route('login')->with('error', __('Input are wrong'));
        }
    }
}
