<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Response::macro('apiSuccess', function ($message = '', $data = null) {
            return response()->json(['success' => true,'message' => $message,'data' => $data]);
        });
        Response::macro('apiFail', function ($message = '', $statusCode = '') {
            return response()->json(['success' => false,'message' => $message,'errorCode' => $statusCode]);
        });
    }
}
