<?php

namespace App\Http\Controllers;

use Str;
use Auth;
use Mail;
use Hash;
use App\Models\User;
use App\Models\Admin;
use App\Models\Customer;
use App\Mail\InviteNewUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Invite new user t.
     * @param  Request $request
     * @return
     */
    public function inviteNewUser(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,email',
        ]);
        $user = new User([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'signup_link' => Str::random(10),
            'password'  => null
           ]);
        if ($request->type === 'admin') {
            $admin = new Admin();
            $admin->type = 'admin';
            $admin->save();
            $admin->user()->save($user);
            $user->assignRole('admin');
        } else {
            $customer = new Customer();
            $customer->save();
            $customer->user()->save($user);
            $user->assignRole('customer');
        }

        $user->save();
        Mail::to($user->email)
            ->queue(new InviteNewUser($user));

        return $request;
    }

    /**
     * Invite-only signup.
     * @param  Request $request
     * @param  string  $token   signup_link
     * @return
     */
    public function signup(Request $request, $token)
    {
        $user       = User::where('signup_link', $token)->firstOrFail();
        $firstname  = $user->first_name;
        $lastname   = $user->last_name;
        $email      = $user->email;
        return view('user.signup', compact('firstname', 'lastname','email','token'));
    }

    /**
     * Complete new user signup.
     * @param  Request $request
     * @param  string  $token
     * @return
     */
    public function completeSignup(Request $request, $token)
    {
        $this->validate($request, [
       'password'   => 'required|confirmed',
       'first_name' => 'required',
       'last_name'  => 'required',
       'email'      => 'required'
     ]);


        $user = User::where('signup_link', $token)->firstOrFail();

        $user->password = Hash::make($request->password);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;

        $user->signup_link = '';
        $user->save();
        Auth::loginUsingId($user->id);

        if($user->hasRole('admin')){
            return redirect('/admin/dashboard');
        }else{
            return redirect('/customer/dashboard');
        }
    }

}
