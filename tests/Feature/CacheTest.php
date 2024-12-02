<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use  Illuminate\Support\Facades\Cache;
use  Illuminate\Support\Facades\RateLimiter;
class CacheTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    
     public function testCache()
     {
        Cache::put("name","yadi",2);
        Cache::put("city","england",2);
        $name = Cache::get("name");
        $city = Cache::get("city");

        self::assertEquals("yadi",$name);
        self::assertEquals("england",$city);

        sleep(5);

        $name = Cache::get("name");
        $city = Cache::get("city");

        self::assertEquals("",$name);
        self::assertEquals("",$city);
     }

     public function testRateLimiting()
     {
      // limit request hanya 5 kali ,jika telah 5 kali maka ditolak (false)
       $success = RateLimiter::attempt("send-message-1",5,function()
      {
         echo "send-message-1";
      },50); //defaultnya /60 detik ,angka 50s adalah batas waktu request

      $this->assertTrue($success);

     }

}
