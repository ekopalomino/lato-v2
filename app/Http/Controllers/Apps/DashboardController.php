<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        
        return view('apps.pages.dashboard');
    }
}
