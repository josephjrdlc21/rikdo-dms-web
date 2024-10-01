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

        Route::group(['prefix' => "users", 'as' => "users."], function(){
            Route::get('/', ['as' => "index", 'uses' => "UsersController@index"]);
            Route::get('/create', ['as' => "create", 'uses' => "UsersController@create"]);
            Route::post('/create', ['uses' => "UsersController@store"]);
            Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "UsersController@edit"]);
            Route::post('/edit/{id?}', ['uses' => "UsersController@update"]);
            Route::get('/cancel', ['as' => "cancel", 'uses' => "UsersController@cancel"]);
            Route::get('/success', ['as' => "success", 'uses' => "UsersController@success"]);
            Route::get('/step-back/{step?}/{id?}', ['as' => "step_back", 'uses' => "UsersController@step_back"]);
        });

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

            Route::group(['prefix' => "courses", 'as' => "courses."], function(){
                Route::get('/', ['as' => "index", 'uses' => "CoursesController@index"]);
                Route::get('/create', ['as' => "create", 'uses' => "CoursesController@create"]);
                Route::post('/create', ['uses' => "CoursesController@store"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "CoursesController@edit"]);
                Route::post('/edit/{id?}', ['uses' => "CoursesController@update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "CoursesController@destroy"]);
            });

            Route::group(['prefix' => "yearlevels", 'as' => "yearlevels."], function(){
                Route::get('/', ['as' => "index", 'uses' => "YearlevelsController@index"]);
                Route::get('/create', ['as' => "create", 'uses' => "YearlevelsController@create"]);
                Route::post('/create', ['uses' => "YearlevelsController@store"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "YearlevelsController@edit"]);
                Route::post('/edit/{id?}', ['uses' => "YearlevelsController@update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "YearlevelsController@destroy"]);
            });
        });
    });
});