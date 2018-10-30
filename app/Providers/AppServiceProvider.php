<?php

namespace App\Providers;

use App\User;
use App\Product;
use App\Mail\userCreated;
use App\Mail\UserMailChanged;

use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        User::created(function($user){
            retry(5, function() use ($user){
                Mail::to($user->email)->send(new userCreated($user));
            },100);
        });

        
        User::updated(function($user){
            if($user->isDirty('email')) {
                retry(5,function() use ($user){
                    Mail::to($user->email)->send(new UserMailChanged($user));
                },100);
                
            }
        });

        Product::updated(function($product){
            
            if($product->quantity == 0 && $product->estaDisponible()){
                
                $product->status = Product::PRODUCT_NO_DISPONIBLE;

                $product->save();
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
