<?php

Route::group(['as' => "portal.", 'namespace' => "Portal", 'middleware' => ["web"]], function(){
    Route::group(['as' => "auth."], function (){
        Route::group(['middleware' => "portal.guest"], function(){
            Route::get('/login', ['as' => 'login', 'uses' => "AuthController@login"]);
            Route::post('/login', ['uses' => "AuthController@authenticate"]);
        });
        Route::get('/logout', ['as' => "logout", 'uses' => "AuthController@logout"]);
    });

    Route::group(['middleware' => ["portal.auth", "portal.status"]], function(){
        Route::get('/', ['as' => 'index', 'uses' => "MainController@index"]);

        Route::group(['prefix' => "cms", 'as' => "cms."], function(){
            Route::group(['prefix' => "roles", 'as' => "roles."], function(){
                Route::get('/', ['as' => "index", 'uses' => "RolesController@index"]);
            });

            Route::group(['prefix' => "permissions", 'as' => "permissions."], function(){
                Route::get('/', ['as' => "index", 'uses' => "PermissionsController@index"]);
            });
        });
    });
});