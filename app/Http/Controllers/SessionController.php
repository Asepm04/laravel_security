<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function set(Request $request)
    {
        $name = $request->query("name");

        $request->session()->put($name,[
            "name"=>fake()->name(),
            "city"=>fake()->city()
        ]);

        return response()->json(["status"=>"success"],201);


    }

    public function get(Request $request)
    {
        $name = $request->query("name");
        $value = $request->session()->get($name);

        return response()->json($value);

    }
}
