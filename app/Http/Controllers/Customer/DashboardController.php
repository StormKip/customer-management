<?php

namespace App\Http\Controllers\Customer;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the customer dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $name  = $user->first_name;
        $surname = $user->last_name;
        return view('customer.dashboard', compact('name','surname'));
    }
}
