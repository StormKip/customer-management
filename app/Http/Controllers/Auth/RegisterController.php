<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

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
     * Where to redirect admin users after registration.
     *
     * @var string
     */
    protected $redirectAdmin = '/admin/dashboard';

    /**
     * Where to redirect admin users after registration.
     *
     * @var string
     */
    protected $redirectCustomer = '/customer/dashboard';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {   
        if(isset($data['admin']) && $data['admin']){
            $admin = new Admin();
            $admin->type = 'admin';
            $admin->save();

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'userable_type' => 'App\admin',
                'userable_id'  => $admin->id
            ]);
            $admin->user()->save($user);
            $user->assignRole('admin');
            $user->save();
        }else{
            $customer = new Customer();
            $customer->save();

            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'userable_type' => 'App\Customer',
                'userable_id'  => $customer->id,
            ]);
            $customer->user()->save($user);
            $user->assignRole('customer');
            $user->save();
        }
        return $user;
    }

        /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }
        return $request->wantsJson()
                    ? new JsonResponse([], 201)
                        : ($user->hasRole('admin') ?  redirect($this->redirectAdmin) : redirect($this->redirectCustomer));
    }
    /**
     * Show customer registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Show admin registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showAdminRegistrationForm()
    {
        $admin = true;
        return view('auth.register')->with('admin', $admin);
    }
}
