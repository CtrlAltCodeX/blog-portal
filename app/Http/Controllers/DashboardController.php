<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $active = User::where('status', 1)->count();

        $inactive = User::where('status', 0)->count();

        $allUser = User::count();

        return view('dashboard.index', compact('allUser', 'inactive', 'active'));
    }
}
