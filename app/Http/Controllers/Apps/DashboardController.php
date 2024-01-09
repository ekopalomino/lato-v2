<?php

namespace iteos\Http\Controllers\Apps;

use Illuminate\Http\Request;
use iteos\Http\Controllers\Controller;
use iteos\Models\Product;
use iteos\Models\Inventory;
use DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        /*Top Widget */
        $products = Product::where('deleted_at',NULL)->count();
        $bogorStocks = Inventory::where('branch_id','1')->sum('closing_amount');
        return view('apps.pages.dashboard',compact('products','bogorStocks'));
    }
}
