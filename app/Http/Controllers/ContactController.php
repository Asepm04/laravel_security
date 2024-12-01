<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ContactController extends Controller
{
    public function create(Request $request)
    {
        $this->authorize("create",Contact::class);

        return response()->json(["message"=>"success"]);
    }
}
