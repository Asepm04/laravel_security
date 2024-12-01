<?php

namespace App\Providers;
//Provider/AppUserProvider
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Auth\Access\Response;

use App\Http\Providers\Guard;
use App\Http\Providers\Guard\TokenGuard;
use Illuminate\Foundation\Application; //untuk closur application
// use App\Providers\Application;
use Illuminate\Http\Reqeust;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // //kode registrasi guard


        // Auth::extend("token",function(Application $app,string $name,array  $config){
        //     $tokenGuard = new TokenGuard(Auth::createUserProvider($config["provider"]),$app->make(Reqeust::class));
        //     $app->refresh("request",$tokenGuard,"setRequest");
        //     return $tokenGuard;
        // });

        Gate::define("get-contact",function(User $user,Contact $contact)
        {
            return $user->id == $contact->user_id ;
        });

        Gate::define("update-contact",function(User $user,Contact $contact)
        {
            return $user->id == $contact->user_id ;
        });

        Gate::define("delete-contact",function(User $user,Contact $contact)
        {
            return $user->id == $contact->user_id ;
        });

        Gate::define("create-contact",function(User $user)
        {
            if($user->name == 'admin')
            {
                return Response::allow();

            }
            else{
              return  Response::deny("you are not admin");
            }
        });
    }
}
