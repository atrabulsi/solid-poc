<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/solid/sr/no-cache/{id}', 'SingleResponsibilityController@getWithoutCache');
$router->get('/solid/sr/with-cache-in-service/{id}', 'SingleResponsibilityController@getWithCacheInService');
$router->get('/solid/sr/with-cacheable-repo/{id}', 'SingleResponsibilityController@getWithCacheableRepo');

$router->get('/solid/di/json/{id}', 'DependencyInversionController@getWave');
$router->get('/solid/di/html/{id}', 'DependencyInversionController@getWaveAsHtml');

$router->post('/ddd/waves', 'DomainDrivenDesignController@createWave');
$router->get('/ddd/waves/{id:[0-9]+}', 'DomainDrivenDesignController@getWave');
