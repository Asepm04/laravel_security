<?php

namespace App\Providers\Guard;

use Illuminate\Contracts\Guard;
use Illuminate\Auth\GuardHelpers;
// use Illuminate\Auth\UserProvider;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class TokenGuard implements Guard

{
    use GuardHelpers; //fungsinya agar kita tidak perlu memanggil semua method

    private Request $requset;

    public function __construct(UserProvider $provider, Request $reqeust)
    {
        $this->provider = $provider;
        $this->request = $request;
    }

    public function setRequest(Request $request ):void
    {
        $this->request  =  $request;

    }

    public function user()
    {
        if($this->user != null)
        {
            return $this->user;
        }
        $token = $this->request->header("API-Key");
        if($token)
        {
            $this->user = $this->provider->retrieveByCredentials(["token"=>$token]);
        }
        return $this->user;
    }

    public function validate(array $credentials = [])
    {
        return $this->provider->validateCredentials($this->user,$credentials);
    }
}

?>