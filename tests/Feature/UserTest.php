<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Contact;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
   // public function testAuthToken()
   // {
   //  $this->get("api/user/current",["API-Key"=>"test"])
   //  ->assertOk();
   // }

   public function testGate()
   {
      $user  = User::where("email","yadi@gmai.com")->first();
      Auth::login($user);
      $contact = Contact::where("email","test@gmail.com")->first();

      self::assertTrue(Gate::allows('get-contact',$contact));
      self::assertTrue(Gate::allows('update-contact',$contact));
      self::assertTrue(Gate::allows('delete-contact',$contact));
   }

   public function testGateFailed()
   {
      $user  = User::where("email","test@gmail.com")->first();
      Auth::login($user);
      $contact = Contact::where("email","test@gmail.com")->first();

      self::assertFalse(Gate::allows('get-contact',$contact));
     
   }

   //untuk mengecek  user tanpa login dahulu
   public function testGateNonLogin()
   {
      $user  = User::where("email","yadi@gmai.com")->first();
      $gate = Gate::forUser($user);
      $contact = Contact::where("email","test@gmail.com")->first();

      self::assertTrue($gate->allows('get-contact',$contact));
      self::assertTrue($gate->allows('update-contact',$contact));
      self::assertTrue($gate->allows('delete-contact',$contact));
   }

   public function testResponsegate()
   {
    $user  = User::where("email","yadi@gmai.com")->first();
    Auth::login($user);

    self::assertFalse(Gate::inspect('create-contact')->allowed());
    self::assertEquals("you are not admin", Gate::inspect('create-contact')->message());

   }

   public function testPolicy()
   {
      $user  = User::where("email","yadi@gmai.com")->first();
      Auth::login($user);
      $contact = Contact::first();

      self::assertTrue(Gate::allows('update',$contact));
      self::assertTrue(Gate::allows('delete',$contact));
      self::assertTrue(Gate::allows('restore',$contact));
   }

   public function testAuthorizable()
   {
      $user  = User::where("email","yadi@gmai.com")->first();
      $contact = Contact::first();

      self::assertTrue($user->can('update',$contact));
      self::assertTrue($user->can('delete',$contact));
      self::assertTrue($user->can('restore',$contact));
   }

   // public function testAuthRequest()
   // {
   //    $user  = User::where("email","yadi@gmai.com")->first();
   //    $this->actingAs($user);

   //    $this->get('api/users')
   //    ->assertStatus(200);
   // }

   public function testBlade()
   {
      $user  = User::where("email","yadi@gmai.com")->first();
      Auth::login($user);
      $contacts = Contact::get();
      $this->view("contact",["contacts"=>$contacts])
      ->assertSeeText('Edit')
      ->assertSeeText('Delete');
   }

   public function testGuest()
   {
      
      self::assertTrue(Gate::allows('create',User::class));
   }
   public function testNotGuest()
   {
      $user  = User::where("email","yadi@gmai.com")->first();
      Auth::login($user);
      
      self::assertFalse(Gate::allows('create',User::class));
   }

   public function testEncryption()
   {
      $value = "asep mulyadi";
      $encryption = Crypt::encryptString($value);
      $decrypt = Crypt::decryptString($encryption);

      self::assertEquals($value,$decrypt);
   }

}
