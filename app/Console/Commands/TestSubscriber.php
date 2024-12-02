<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class TestSubscriber extends Command
{

    protected $signature = 'app:test-subscriber';

    protected $description = 'Command description';

    public function handle()
    {
        Redis::subscribe(["channel-1","channel-2"],function(string $message){
            echo $message . PHP_EOL;
        });
    }
}
