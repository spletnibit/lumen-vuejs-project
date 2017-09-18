<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->group(['middleware' => ['auth']], function () use ($app) {
    $app->group(['prefix' => '/products'], function () use ($app) {
        $app->get('/categories', 'ProductCategoryController@index');
        $app->post('/add', 'ProductController@create');
        $app->get('/search', 'ProductController@search');
        $app->post('/categories/add', 'ProductCategoryController@create');

        $app->get('/', 'ProductController@index');
        $app->get('/{id}', 'ProductController@edit');
        $app->put('/{id}', 'ProductController@update');

    });

    $app->group(['prefix' => '/customers'], function () use ($app) {
        $app->get('/', 'CustomerController@index');
        $app->get('/{id}', 'CustomerController@edit');
        $app->put('/{id}', 'CustomerController@update');
        $app->delete('/{id}', 'CustomerController@destroy');
        $app->post('/add', 'CustomerController@create');
    });
    
    $app->group(['prefix' => '/offers'], function () use ($app) {
        $app->get('/', 'OfferController@index');
        $app->get('/{id}', 'OfferController@edit');
        $app->get('/pdf/{id}', 'OfferController@generatePdf');

        $app->put('/{id}', 'OfferController@update');
        //    $app->delete('/{id}', 'OfferController@destroy');
        $app->post('/add', 'OfferController@create');
    });

    $app->post('/logout', 'AuthController@postLogout');
});

$app->post('/login', 'AuthController@postLogin');
