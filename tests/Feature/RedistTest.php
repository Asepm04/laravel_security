<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Redis;

class RedistTest extends TestCase
{
    public function testPing()
    {
        $response = Redis::command('ping');
        self::assertEquals('PONG',$response);

        //atau kita juga bisa langsung panggil nama method nya

        $response = Redis::ping();
        self::assertEquals('PONG',$response);

    }

    public function testString()
    {
        $set = Redis::setEx("name",2,"yadi"); //2 adalah limitt waktu 2s
        self::assertEquals("yadi",Redis::get("name"));

        sleep(5); //break selama 5 detik sebelum printah selanjutnya
        self::assertEquals(null,Redis::get("name"));

    }

    public function testList()
    {
        Redis::del("names"); //hapus jika ada data names

        Redis::rpush("names","asep");
        Redis::rpush("names","mul");
        Redis::rpush("names","yadi");

        $response = Redis::lrange("names",0,-1); //dapatkan semua data names

        self::assertEquals(["asep","mul","yadi"],$response);
        self::assertEquals("asep",Redis::lpop("names"));
        self::assertEquals("mul",Redis::lpop("names"));
        self::assertEquals("yadi",Redis::lpop("names"));
    }

    // public function testSet()
    // {
    //     Redis::del("names");
    //     Redis::sadd("names","asep");
    //     Redis::sadd("names","asep");
    //     Redis::sadd("names","mul");
    //     Redis::sadd("names","mul");
    //     Redis::sadd("names","yadi");
    //     Redis::sadd("names","yadi");

    //     $response = Redis::smembers("names");

    //     self::assertEquals(["asep","mul","yadi"],$response);
    // }

    public function testSortedSet()
    {
        Redis::del("names");
        Redis::zadd("names",84,"asep");
        Redis::zadd("names",84,"asep");
        Redis::zadd("names",84,"asep");
        Redis::zadd("names",85,"mul");
        Redis::zadd("names",80,"yadi");

        //angka ditengah adalah score atau nilai karena akan diurutkan secara asc

        $response = Redis::zrange("names",0,-1);
        self::assertEquals(["yadi","asep","mul"],$response);
    }


    public function testHash()
    {
        Redis::del("user:1");
        Redis::hset("user:1","name","yadi");
        Redis::hset("user:1","email","yadi@com");
        Redis::hset("user:1","age",24);
        $response = Redis::hgetall("user:1");

        self::assertEquals($response,
    [
        "name"=>"yadi",
        "email"=>"yadi@com",
        "age"=>24
    ]);
    }

    public function testGeoInt()
    {
        Redis::del("sellers");
        Redis::geoadd("sellers",106.631905,-6.179860, "Toko A");
        Redis::geoadd("sellers",106.632498,-6.182975, "Toko B");

        //untuk mendapatkan jarak antara toko a dan toko dalam km
        $response = Redis::geodist("sellers","Toko A","Toko B","km");
        self::assertEquals(0,$response); //hasil belum diketahui karena tidak didukung redis v3 keabawah
        
        //untuk mencari jarak terdekat dari radius 5 km /fromlonlat adalah .point radius
        $result = Redis::geosearch("seller",new FromLonLat(106.633358,-6.181988), new byRadius(5,"km"));
    }

    public function testHyperLoglog()
    {
        Redis::pfadd("visitors","asep",'mul','yadi');
        Redis::pfadd("visitors","asep",'mul','dew');
        Redis::pfadd("visitors","asep",'mul','nov');

        $response = Redis::pfcount("visitors");
        self::assertEquals(5,$response);
    }

    public function testPipeline()
    {
        Redis::pipeline(function($pipeline)
        {
            $pipeline->setex("name",2,"yadi");
            $pipeline->setex("city",2,"england");
        });

        $response = Redis::get("name");
        self::assertEquals("yadi",$response);

        $response2 = Redis::get("city");
        self::assertEquals("england",$response2);

    }

    public function testTransaction()
    {
        Redis::transaction(function($transaction)
        {
            $transaction->setex("name",2,"eko");
            $transaction->setex("city",2,"papua");
        });

        $response = Redis::get("name");
        self::assertEquals("eko",$response);

        $response2 = Redis::get("city");
        self::assertEquals("papua",$response2);

    }

    public function testPublish()
    {
        for($i = 0 ; $i < 10; $i++)
        {
            Redis::publish("channel-1","hello world $i");
            Redis::publish("channel-2","Good world $i");
        }
        self::assertTrue(true);
    }


}
