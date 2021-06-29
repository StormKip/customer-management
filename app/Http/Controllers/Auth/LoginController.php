<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    public function redirectTo() {
        $user = Auth::user();

        $role = $user->hasRole('admin') ? 'admin' : 'customer'; 
        switch ($role) {
          case 'admin':
            return '/admin/dashboard';
            break;
          case 'customer':
            return '/customer/dashboard';
            break; 
      
          default:
            return '/'; 
          break;
        }
      }
}
