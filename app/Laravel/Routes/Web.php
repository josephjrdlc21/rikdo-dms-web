<?php

Route::group(['as' => "", 'namespace' => "Web", 'middleware' => ["web"]], function(){
    Route::group(['middleware' => "web.guest"], function(){
    });
});