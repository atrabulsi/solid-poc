<?php

namespace App\Providers;

use App\Core\DependencyInversion\Interfaces\WaveRepositoryInterface;
use App\Core\DependencyInversion\Repositories\WaveCacheableRepository;
use App\Core\DependencyInversion\Repositories\WaveElasticSearchRepository;
use App\Core\DependencyInversion\Repositories\WaveRepository;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;
use Predis\Client;

class RepositoryProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(WaveRepositoryInterface::class, function(){
            $driver = config('repository.driver');
            if ($driver === 'redis') {
                $waveRepository = app(WaveRepository::class);
                $cache = app(Client::class);
                return new WaveCacheableRepository($waveRepository, $cache);
            } elseif ($driver === 'elasticsearch') {
                return new WaveElasticSearchRepository();
            } else {
                return app(WaveRepository::class);
            }
        });
        $this->app->singleton(\App\Core\DDD\Domain\Interfaces\WaveRepositoryInterface::class, function(){
            return app(\App\Core\DDD\Infrastructure\Repositories\WaveRepository::class);
        });
    }
}
