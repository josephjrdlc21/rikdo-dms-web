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
                Route::get('/create', ['as' => "create", 'uses' => "RolesController@create"]);
                Route::post('/create', ['uses' => "RolesController@store"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "RolesController@edit"]);
                Route::post('/edit/{id?}', ['uses' => "RolesController@update"]);
            });

            Route::group(['prefix' => "permissions", 'as' => "permissions."], function(){
                Route::get('/', ['as' => "index", 'uses' => "PermissionsController@index"]);
            });

            Route::group(['prefix' => "departments", 'as' => "departments."], function(){
                Route::get('/', ['as' => "index", 'uses' => "DepartmentsController@index"]);
                Route::get('/create', ['as' => "create", 'uses' => "DepartmentsController@create"]);
                Route::post('/create', ['uses' => "DepartmentsController@store"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "DepartmentsController@edit"]);
                Route::post('/edit/{id?}', ['uses' => "DepartmentsController@update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "DepartmentsController@destroy"]);
            });
        });
    });
});