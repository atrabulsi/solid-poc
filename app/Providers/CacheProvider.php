<?php

namespace App\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use Predis\Client;

class CacheProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function(){
            $driver = [
                'scheme' => 'tcp',
                'host' => 'redis',
                'port' => 6379
            ];
            return new Client($driver);
        });
    }
}
