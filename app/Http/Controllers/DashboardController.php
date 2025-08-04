<?php

namespace App\Http\Controllers;

use Custom;
use helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:Dashboard|dashboard', ['only' => ['index']]);
        // $this->middleware('permission:Roads Dashboard', ['only' => ['roads_dashboard']]);
        // $this->middleware('permission:Water Dashboard', ['only' => ['water_dashboard']]);
    }

    public function index(Request $request)
    {
        return view('dashboard.index');
    }

    
}
