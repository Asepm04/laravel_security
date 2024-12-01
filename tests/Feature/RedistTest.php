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
}
