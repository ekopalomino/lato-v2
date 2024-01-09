<?php

namespace iteos\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use iteos\Models\Sale;
use iteos\Models\Purchase;
use iteos\Models\InternalTransfer;
use iteos\Models\Delivery;
use iteos\Models\Invoice;
use iteos\Models\Manufacture;
use Auth;

class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('apps.includes.sidebar',function($views) {
            if(Auth::check()) {
                $views->with('purchases',Purchase::where('status','13')->count())
                      ->with('transfers',InternalTransfer::where('status_id','8083f49e-f0aa-4094-894f-f64cd2e9e4e9')->count())
                      ->with('ex_receipt',Purchase::where('status','13')->count())
                      ->with('receipts',Purchase::where('status','314f31d1-4e50-4ad9-ae8c-65f0f7ebfc43')->count());
            }
        });
    }
}
