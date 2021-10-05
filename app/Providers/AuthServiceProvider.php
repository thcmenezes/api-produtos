<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Firebase\JWT\JWT;
use Illuminate\Auth\GenericUser;

class AuthServiceProvider extends ServiceProvider
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
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function (Request $request) {
        /* if (!$request->hasHeader('Authorization')) {
                return null;
            }

            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $dadosAutenticacao = JWT::decode($token, env('JWT_KEY'), ['HS256']);

            //return new GenericUser(['email' => $dadosAutenticacao]);
            return User::where('email', $dadosAutenticacao->email)->first(); */
            
        });
    }
}
