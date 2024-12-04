<?php

Route::group(['as' => "", 'namespace' => "Web", 'middleware' => ["web"]], function(){
    Route::group(['middleware' => "web.guest"], function(){
        Route::get('/', ['as' => 'index', 'uses' => "MainController@index"]);
    });
});