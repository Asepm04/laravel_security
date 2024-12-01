<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Contact;
class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where("email","test@com")->firstOrFail();

        $contact  = new Contact();
        $contact->name = "yadi";
        $contact->email = "test1@gmail.com";
        $contact->user_id = $user->id;
        $contact->save();

        // $user = new User();
        // $user->name ="test";
        // $user->email = "test@com";
        // $user->password = bcrypt("test123");
        // $user->save();
    }
}
