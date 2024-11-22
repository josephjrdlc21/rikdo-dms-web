<?php

Route::group(['as' => "portal.", 'namespace' => "Portal", 'middleware' => ["web"]], function(){
    Route::group(['as' => "auth."], function (){
        Route::group(['middleware' => "portal.guest"], function(){
            Route::get('/login', ['as' => 'login', 'uses' => "AuthController@login"]);
            Route::post('/login', ['uses' => "AuthController@authenticate"]);
            Route::get('/register', ['as' => 'register', 'uses' => "AuthController@register"]);
            Route::post('/register', ['uses' => "AuthController@store"]);
            Route::get('/forgot-password', ['as' => "forgot_password", 'uses' => "AuthController@forgot_password"]);
            Route::post('/forgot-password', ['uses' => "AuthController@forgot_password_email"]);
            Route::get('/reset-password/{refid?}', ['as' => "reset_password", 'uses' => "AuthController@reset_password"]);
            Route::post('/reset-password/{refid?}', ['uses' => "AuthController@store_password"]);
            Route::get('/cancel', ['as' => "cancel", 'uses' => "AuthController@cancel"]);
            Route::get('/step-back/{step?}', ['as' => "step_back", 'uses' => "AuthController@step_back"]);
        });
        Route::get('/logout', ['as' => "logout", 'uses' => "AuthController@logout"]);
    });

    Route::group(['middleware' => ["portal.auth", "portal.status"]], function(){
        Route::get('/', ['as' => 'index', 'uses' => "MainController@index"]);

        Route::group(['prefix' => "users-kyc", 'as' => "users_kyc."], function(){
            Route::get('/', ['as' => "index", 'uses' => "UsersKYCController@index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "UsersKYCController@approved"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "UsersKYCController@rejected"]);
            Route::get('/update-status/{id?}/{status?}', ['as' => "update_status", 'uses' => "UsersKYCController@update_status"]);
            Route::get('/{id?}', ['as' => "show", 'uses' => "UsersKYCController@show"]);
        });

        Route::group(['prefix' => "users", 'as' => "users."], function(){
            Route::get('/', ['as' => "index", 'uses' => "UsersController@index", 'middleware' => "portal.permission:portal.users.index"]);
            Route::get('/create', ['as' => "create", 'uses' => "UsersController@create", 'middleware' => "portal.permission:portal.users.create"]);
            Route::post('/create', ['uses' => "UsersController@store", 'middleware' => "portal.permission:portal.users.create"]);
            Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "UsersController@edit", 'middleware' => "portal.permission:portal.users.update"]);
            Route::post('/edit/{id?}', ['uses' => "UsersController@update", 'middleware' => "portal.permission:portal.users.update"]);
            Route::get('/update-password/{id?}', ['as' => "update_password", 'uses' => "UsersController@update_password", 'middleware' => "portal.permission:portal.users.edit_password"]);
            Route::get('/update-status/{id?}', ['as' => "update_status", 'uses' => "UsersController@update_status", 'middleware' => "portal.permission:portal.users.update_status"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "UsersController@show", 'middleware' => "portal.permission:portal.users.view"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "UsersController@destroy", 'middleware' => "portal.permission:portal.users.delete"]);
            Route::get('/cancel', ['as' => "cancel", 'uses' => "UsersController@cancel", 'middleware' => ["portal.permission:portal.users.create", "portal.permission:portal.users.update"]]);
            Route::get('/success', ['as' => "success", 'uses' => "UsersController@success", 'middleware' => ["portal.permission:portal.users.create", "portal.permission:portal.users.update"]]);
            Route::get('/step-back/{step?}/{id?}', ['as' => "step_back", 'uses' => "UsersController@step_back", 'middleware' => ["portal.permission:portal.users.create", "portal.permission:portal.users.update"]]);
        });

        Route::group(['prefix' => "research", 'as' => "research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "ResearchController@index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "ResearchController@approved"]);
            Route::get('/for-revision', ['as' => "for_revision", 'uses' => "ResearchController@for_revision"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "ResearchController@rejected"]);
            Route::get('/create', ['as' => "create", 'uses' => "ResearchController@create"]);
            Route::post('/create', ['uses' => "ResearchController@store"]);
            Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "ResearchController@edit"]);
            Route::post('/edit/{id?}', ['uses' => "ResearchController@update"]);
            Route::get('/edit-share/{id?}', ['as' => "edit_share", 'uses' => "ResearchController@edit_share"]);
            Route::post('/edit-share/{id?}', ['uses' => "ResearchController@update_share"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "ResearchController@destroy"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "ResearchController@show"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "ResearchController@download"]);
        });

        Route::group(['prefix' => "student-research", 'as' => "student_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "StudentResearchController@index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "StudentResearchController@approved"]);
            Route::get('/for-revision', ['as' => "for_revision", 'uses' => "StudentResearchController@for_revision"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "StudentResearchController@rejected"]);
            Route::get('/edit-share/{id?}', ['as' => "edit_share", 'uses' => "StudentResearchController@edit_share"]);
            Route::post('/edit-share/{id?}', ['uses' => "StudentResearchController@update_share"]);
            Route::get('/edit-status/{id?}/{status?}', ['as' => "edit_status", 'uses' => "StudentResearchController@edit_status"]);
            Route::post('/edit-status/{id?}/{status?}', ['uses' => "StudentResearchController@update_status"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "StudentResearchController@destroy"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "StudentResearchController@show"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "StudentResearchController@download"]);
        });

        Route::group(['prefix' => "all-research", 'as' => "all_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "AllResearchController@index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "AllResearchController@approved"]);
            Route::get('/for-revision', ['as' => "for_revision", 'uses' => "AllResearchController@for_revision"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "AllResearchController@rejected"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "AllResearchController@destroy"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "AllResearchController@show"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "AllResearchController@download"]);
        });

        Route::group(['prefix' => "completed-research", 'as' => "completed_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "CompletedResearchController@index"]);
            Route::get('/create', ['as' => "create", 'uses' => "CompletedResearchController@create"]);
            Route::post('/create', ['uses' => "CompletedResearchController@store"]);
            Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "CompletedResearchController@edit"]);
            Route::post('/edit/{id?}', ['uses' => "CompletedResearchController@update"]);
            Route::get('/edit-status/{id?}/{status?}', ['as' => "edit_status", 'uses' => "CompletedResearchController@edit_status"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "CompletedResearchController@show"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "CompletedResearchController@destroy"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "CompletedResearchController@download"]);
        });

        Route::group(['prefix' => "posted-research", 'as' => "posted_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "PostedResearchController@index"]);
            Route::get('/create/{id?}', ['as' => "create", 'uses' => "PostedResearchController@create"]);
            Route::post('/create/{id?}', ['uses' => "PostedResearchController@store"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "PostedResearchController@show"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "PostedResearchController@download"]);
        });

        Route::group(['prefix' => "archives", 'as' => "archives."], function(){
            Route::get('/', ['as' => "index", 'uses' => "ArchivesController@index"]);
            Route::get('/completed', ['as' => "completed", 'uses' => "ArchivesController@completed"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "ArchivesController@show"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "ArchivesController@destroy"]);
            Route::get('/restore/{id?}', ['as' => "restore", 'uses' => "ArchivesController@restore"]);
        });

        Route::group(['prefix' => "research-reports", 'as' => "research_reports."], function(){
            Route::get('/', ['as' => "index", 'uses' => "ResearchReportsController@index"]);
            Route::get('/completed', ['as' => "completed", 'uses' => "ResearchReportsController@completed"]);
            Route::get('/posted', ['as' => "posted", 'uses' => "ResearchReportsController@posted"]);
            Route::get('/summary', ['as' => "summary", 'uses' => "ResearchReportsController@summary"]);
            Route::get('/export', ['as' => "export", 'uses' => "ResearchReportsController@export"]);
        });

        Route::group(['prefix' => "cms", 'as' => "cms."], function(){
            Route::group(['prefix' => "roles", 'as' => "roles."], function(){
                Route::get('/', ['as' => "index", 'uses' => "RolesController@index", 'middleware' => "portal.permission:portal.cms.roles.index"]);
                Route::get('/create', ['as' => "create", 'uses' => "RolesController@create", 'middleware' => "portal.permission:portal.cms.roles.create"]);
                Route::post('/create', ['uses' => "RolesController@store", 'middleware' => "portal.permission:portal.cms.roles.create"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "RolesController@edit", 'middleware' => "portal.permission:portal.cms.roles.update"]);
                Route::post('/edit/{id?}', ['uses' => "RolesController@update", 'middleware' => "portal.permission:portal.cms.roles.update"]);
            });

            Route::group(['prefix' => "permissions", 'as' => "permissions."], function(){
                Route::get('/', ['as' => "index", 'uses' => "PermissionsController@index", 'middleware' => "portal.permission:portal.cms.permissions.index"]);
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

            Route::group(['prefix' => "research-types", 'as' => "research_types."], function(){
                Route::get('/', ['as' => "index", 'uses' => "ResearchTypesController@index"]);
                Route::get('/create', ['as' => "create", 'uses' => "ResearchTypesController@create"]);
                Route::post('/create', ['uses' => "ResearchTypesController@store"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "ResearchTypesController@edit"]);
                Route::post('/edit/{id?}', ['uses' => "ResearchTypesController@update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "ResearchTypesController@destroy"]);
            });
        });

        Route::group(['prefix' => "audit-trail", 'as' => "audit_trail."], function(){
            Route::get('/', ['as' => "index", 'uses' => "AuditTrailController@index"]);
            Route::get('/export', ['as' => "export", 'uses' => "AuditTrailController@export"]);
        });

        Route::group(['prefix' => "profile", 'as' => "profile."], function(){
            Route::get('/', ['as' => "index", 'uses' => "ProfileController@index"]);
            Route::post('/', ['uses' => "ProfileController@update_profile"]);
            Route::get('/edit-password', ['as' => "edit_password", 'uses' => "ProfileController@edit_password"]);
            Route::post('/edit-password', ['uses' => "ProfileController@update_password"]);
        });
    });
});