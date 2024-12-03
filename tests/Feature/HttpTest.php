<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\HTTP;
use Illuminate\Http\Client\Response;

class HttpTest extends TestCase
{
   //http://requestbin.webengage.org/1kvgtgd1

  public function testGetHttp()
  {
    $request = HTTP::get('http://requestbin.webengage.org/1kvgtgd1');
    self::assertTrue($request->ok());
  }

  public function testPostHttp()
  {
    $request = HTTP::post('http://requestbin.webengage.org/1kvgtgd1');
    self::assertTrue($request->ok());
  }

  public function testDeletetHttp()
  {
    $request = HTTP::delete('http://requestbin.webengage.org/1kvgtgd1');
    self::assertTrue($request->ok());
  }
  public function testResponsetHttp()
  {
    $response = HTTP::get('http://requestbin.webengage.org/1kvgtgd1');
    self::assertTrue($response->ok());
    self::assertEquals($response->status(),200);
    self::assertNotNull($response->headers());
    self::assertNotNull($response->body());
    self::assertTrue($response->json(["success"]));
  }

  public function testQueryParamaters()
  {
    $response = HTTP::withQueryParameters([
        "page"=>1,
        "limit"=>5
    ])->withHeaders(["id"=>12546])->get('http://requestbin.webengage.org/1kvgtgd1');

    self::assertTrue($response->ok());
  }

  public function testHeaders()
  {
     $response =HTTP::withHeaders(["author"=>"true"])->get('http://requestbin.webengage.org/1kvgtgd1');
     self::assertTrue($response->ok());
  }

  public function testCookiea()
  {
    $response = HTTP::withQueryParameters([
        "page"=>1,
        "limit"=>5
    ])->withHeaders(["id"=>12546])->withCookies(
        [
            "user_id"=>1,
            "author"=>"stia"
        ],"http://requestbin.webengage.org/1kvgtgd1"
    )->get('http://requestbin.webengage.org/1kvgtgd1');

    self::assertTrue($response->ok());
  }

  public function testFormPost()
  {
     Http::asForm()->post('http://requestbin.webengage.org/1kvgtgd1',["user"=>"admin","password"=>"admin123"]);
  }

  public function testJson()
  {
     Http::asJson()->post('http://requestbin.webengage.org/1kvgtgd1',["user"=>"admin","password"=>"admin123"]);
  }

  public function testtimeout()
  {
     Http::timeout(1)->post('http://requestbin.webengage.org/1kvgtgd1',["user"=>"admin","password"=>"admin123"]);
  }

  public function testRetry()
  {
     Http::asJson()->timeout(1)->retry(5,1)
     ->post('http://requestbin.webengage.org/1kvgtgd1',
     ["user"=>"admin","password"=>"admin123"]);
  }
}
