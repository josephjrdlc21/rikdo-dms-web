<?php

Route::group(['as' => "portal.", 'namespace' => "Portal", 'middleware' => ["web"]], function(){
    Route::get('/home', ['as' => 'home', 'uses' => "MainController@home", 'middleware' => "portal.guest"]);
    Route::get('/about', ['as' => 'about', 'uses' => "MainController@about", 'middleware' => "portal.guest"]);
    Route::get('/contact', ['as' => 'contact', 'uses' => "MainController@contact", 'middleware' => "portal.guest"]);
    Route::get('/researches', ['as' => 'researches', 'uses' => "MainController@researches", 'middleware' => "portal.guest"]);
    Route::get('/researches/{id?}', ['as' => 'research', 'uses' => "MainController@research", 'middleware' => "portal.guest"]);
    Route::get('/statistics', ['as' => 'statistics', 'uses' => "MainController@statistics", 'middleware' => "portal.guest"]);

    Route::group(['as' => "auth."], function (){
        Route::group(['middleware' => "portal.guest"], function(){
            Route::get('/login/{uri?}', ['as' => 'login', 'uses' => "AuthController@login"]);
            Route::post('/login/{uri?}', ['uses' => "AuthController@authenticate"]);
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
            Route::get('/', ['as' => "index", 'uses' => "UsersKYCController@index", 'middleware' => "portal.permission:portal.users_kyc.index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "UsersKYCController@approved", 'middleware' => "portal.permission:portal.users_kyc.approved"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "UsersKYCController@rejected", 'middleware' => "portal.permission:portal.users_kyc.rejected"]);
            Route::get('/update-status/{id?}/{status?}', ['as' => "update_status", 'uses' => "UsersKYCController@update_status", 'middleware' => "portal.permission:portal.users_kyc.update_status"]);
            Route::get('/{id?}', ['as' => "show", 'uses' => "UsersKYCController@show", 'middleware' => "portal.permission:portal.users_kyc.view"]);
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
            Route::get('/', ['as' => "index", 'uses' => "ResearchController@index", 'middleware' => "portal.permission:portal.research.index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "ResearchController@approved", 'middleware' => "portal.permission:portal.research.approved"]);
            Route::get('/for-revision', ['as' => "for_revision", 'uses' => "ResearchController@for_revision", 'middleware' => "portal.permission:portal.research.for_revision"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "ResearchController@rejected", 'middleware' => "portal.permission:portal.research.rejected"]);
            Route::get('/create', ['as' => "create", 'uses' => "ResearchController@create", 'middleware' => "portal.permission:portal.research.create"]);
            Route::post('/create', ['uses' => "ResearchController@store", 'middleware' => "portal.permission:portal.research.create"]);
            Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "ResearchController@edit", 'middleware' => "portal.permission:portal.research.update"]);
            Route::post('/edit/{id?}', ['uses' => "ResearchController@update", 'middleware' => "portal.permission:portal.research.update"]);
            Route::get('/edit-share/{id?}', ['as' => "edit_share", 'uses' => "ResearchController@edit_share", 'middleware' => "portal.permission:portal.research.update_share"]);
            Route::post('/edit-share/{id?}', ['uses' => "ResearchController@update_share", 'middleware' => "portal.permission:portal.research.update_share"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "ResearchController@destroy", 'middleware' => "portal.permission:portal.research.delete"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "ResearchController@show", 'middleware' => "portal.permission:portal.research.view"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "ResearchController@download", 'middleware' => "portal.permission:portal.research.download"]);
        });

        Route::group(['prefix' => "student-research", 'as' => "student_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "StudentResearchController@index", 'middleware' => "portal.permission:portal.student_research.index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "StudentResearchController@approved", 'middleware' => "portal.permission:portal.student_research.approved"]);
            Route::get('/for-revision', ['as' => "for_revision", 'uses' => "StudentResearchController@for_revision", 'middleware' => "portal.permission:portal.student_research.for_revision"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "StudentResearchController@rejected", 'middleware' => "portal.permission:portal.student_research.rejected"]);
            Route::get('/edit-share/{id?}', ['as' => "edit_share", 'uses' => "StudentResearchController@edit_share", 'middleware' => "portal.permission:portal.student_research.update_share"]);
            Route::post('/edit-share/{id?}', ['uses' => "StudentResearchController@update_share", 'middleware' => "portal.permission:portal.student_research.update_share"]);
            Route::get('/edit-status/{id?}/{status?}', ['as' => "edit_status", 'uses' => "StudentResearchController@edit_status", 'middleware' => "portal.permission:portal.student_research.update_status"]);
            Route::post('/edit-status/{id?}/{status?}', ['uses' => "StudentResearchController@update_status", 'middleware' => "portal.permission:portal.student_research.update_status"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "StudentResearchController@destroy", 'middleware' => "portal.permission:portal.student_research.delete"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "StudentResearchController@show", 'middleware' => "portal.permission:portal.student_research.view"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "StudentResearchController@download", 'middleware' => "portal.permission:portal.student_research.download"]);
        });

        Route::group(['prefix' => "all-research", 'as' => "all_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "AllResearchController@index", 'middleware' => "portal.permission:portal.all_research.index"]);
            Route::get('/approved', ['as' => "approved", 'uses' => "AllResearchController@approved", 'middleware' => "portal.permission:portal.all_research.approved"]);
            Route::get('/for-revision', ['as' => "for_revision", 'uses' => "AllResearchController@for_revision", 'middleware' => "portal.permission:portal.all_research.for_revision"]);
            Route::get('/rejected', ['as' => "rejected", 'uses' => "AllResearchController@rejected", 'middleware' => "portal.permission:portal.all_research.rejected"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "AllResearchController@destroy", 'middleware' => "portal.permission:portal.all_research.delete"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "AllResearchController@show", 'middleware' => "portal.permission:portal.all_research.view"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "AllResearchController@download", 'middleware' => "portal.permission:portal.all_research.download"]);
        });

        Route::group(['prefix' => "completed-research", 'as' => "completed_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "CompletedResearchController@index", 'middleware' => "portal.permission:portal.completed_research.index"]);
            Route::get('/create', ['as' => "create", 'uses' => "CompletedResearchController@create", 'middleware' => "portal.permission:portal.completed_research.create"]);
            Route::post('/create', ['uses' => "CompletedResearchController@store", 'middleware' => "portal.permission:portal.completed_research.create"]);
            Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "CompletedResearchController@edit", 'middleware' => "portal.permission:portal.completed_research.update"]);
            Route::post('/edit/{id?}', ['uses' => "CompletedResearchController@update", 'middleware' => "portal.permission:portal.completed_research.update"]);
            Route::get('/edit-status/{id?}/{status?}', ['as' => "edit_status", 'uses' => "CompletedResearchController@edit_status", 'middleware' => "portal.permission:portal.completed_research.update_status"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "CompletedResearchController@show", 'middleware' => "portal.permission:portal.completed_research.view"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "CompletedResearchController@destroy", 'middleware' => "portal.permission:portal.completed_research.delete"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "CompletedResearchController@download", 'middleware' => "portal.permission:portal.completed_research.download"]);
        });

        Route::group(['prefix' => "posted-research", 'as' => "posted_research."], function(){
            Route::get('/', ['as' => "index", 'uses' => "PostedResearchController@index", 'middleware' => "portal.permission:portal.posted_research.index"]);
            Route::get('/create/{id?}', ['as' => "create", 'uses' => "PostedResearchController@create", 'middleware' => "portal.permission:portal.posted_research.create"]);
            Route::post('/create/{id?}', ['uses' => "PostedResearchController@store", 'middleware' => "portal.permission:portal.posted_research.create"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "PostedResearchController@show", 'middleware' => "portal.permission:portal.posted_research.view"]);
            Route::get('/download/{id?}', ['as' => "download", 'uses' => "PostedResearchController@download", 'middleware' => "portal.permission:portal.posted_research.download"]);
        });

        Route::group(['prefix' => "archives", 'as' => "archives."], function(){
            Route::get('/', ['as' => "index", 'uses' => "ArchivesController@index", 'middleware' => "portal.permission:portal.archives.index"]);
            Route::get('/completed', ['as' => "completed", 'uses' => "ArchivesController@completed", 'middleware' => "portal.permission:portal.archives.completed"]);
            Route::get('/show/{id?}', ['as' => "show", 'uses' => "ArchivesController@show", 'middleware' => "portal.permission:portal.archives.view"]);
            Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "ArchivesController@destroy", 'middleware' => "portal.permission:portal.archives.delete"]);
            Route::get('/restore/{id?}', ['as' => "restore", 'uses' => "ArchivesController@restore", 'middleware' => "portal.permission:portal.archives.restore"]);
        });

        Route::group(['prefix' => "research-reports", 'as' => "research_reports."], function(){
            Route::get('/', ['as' => "index", 'uses' => "ResearchReportsController@index", 'middleware' => "portal.permission:portal.research_reports.index"]);
            Route::get('/completed', ['as' => "completed", 'uses' => "ResearchReportsController@completed", 'middleware' => "portal.permission:portal.research_reports.completed"]);
            Route::get('/posted', ['as' => "posted", 'uses' => "ResearchReportsController@posted", 'middleware' => "portal.permission:portal.research_reports.posted"]);
            Route::get('/summary', ['as' => "summary", 'uses' => "ResearchReportsController@summary", 'middleware' => "portal.permission:portal.research_reports.summary"]);
            Route::get('/export', ['as' => "export", 'uses' => "ResearchReportsController@export", 'middleware' => "portal.permission:portal.research_reports.export"]);
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
                Route::get('/', ['as' => "index", 'uses' => "DepartmentsController@index", 'middleware' => "portal.permission:portal.cms.departments.index"]);
                Route::get('/create', ['as' => "create", 'uses' => "DepartmentsController@create", 'middleware' => "portal.permission:portal.cms.departments.create"]);
                Route::post('/create', ['uses' => "DepartmentsController@store", 'middleware' => "portal.permission:portal.cms.departments.create"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "DepartmentsController@edit", 'middleware' => "portal.permission:portal.cms.departments.update"]);
                Route::post('/edit/{id?}', ['uses' => "DepartmentsController@update", 'middleware' => "portal.permission:portal.cms.departments.update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "DepartmentsController@destroy", 'middleware' => "portal.permission:portal.cms.departments.delete"]);
            });

            Route::group(['prefix' => "courses", 'as' => "courses."], function(){
                Route::get('/', ['as' => "index", 'uses' => "CoursesController@index", 'middleware' => "portal.permission:portal.cms.courses.index"]);
                Route::get('/create', ['as' => "create", 'uses' => "CoursesController@create", 'middleware' => "portal.permission:portal.cms.courses.create"]);
                Route::post('/create', ['uses' => "CoursesController@store", 'middleware' => "portal.permission:portal.cms.courses.create"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "CoursesController@edit", 'middleware' => "portal.permission:portal.cms.courses.update"]);
                Route::post('/edit/{id?}', ['uses' => "CoursesController@update", 'middleware' => "portal.permission:portal.cms.courses.update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "CoursesController@destroy", 'middleware' => "portal.permission:portal.cms.courses.delete"]);
            });

            Route::group(['prefix' => "yearlevels", 'as' => "yearlevels."], function(){
                Route::get('/', ['as' => "index", 'uses' => "YearlevelsController@index", 'middleware' => "portal.permission:portal.cms.yearlevels.index"]);
                Route::get('/create', ['as' => "create", 'uses' => "YearlevelsController@create", 'middleware' => "portal.permission:portal.cms.yearlevels.create"]);
                Route::post('/create', ['uses' => "YearlevelsController@store", 'middleware' => "portal.permission:portal.cms.yearlevels.create"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "YearlevelsController@edit", 'middleware' => "portal.permission:portal.cms.yearlevels.update"]);
                Route::post('/edit/{id?}', ['uses' => "YearlevelsController@update", 'middleware' => "portal.permission:portal.cms.yearlevels.update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "YearlevelsController@destroy", 'middleware' => "portal.permission:portal.cms.yearlevels.delete"]);
            });

            Route::group(['prefix' => "research-types", 'as' => "research_types."], function(){
                Route::get('/', ['as' => "index", 'uses' => "ResearchTypesController@index", 'middleware' => "portal.permission:portal.cms.research_types.index"]);
                Route::get('/create', ['as' => "create", 'uses' => "ResearchTypesController@create", 'middleware' => "portal.permission:portal.cms.research_types.create"]);
                Route::post('/create', ['uses' => "ResearchTypesController@store", 'middleware' => "portal.permission:portal.cms.research_types.create"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "ResearchTypesController@edit", 'middleware' => "portal.permission:portal.cms.research_types.update"]);
                Route::post('/edit/{id?}', ['uses' => "ResearchTypesController@update", 'middleware' => "portal.permission:portal.cms.research_types.update"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "ResearchTypesController@destroy", 'middleware' => "portal.permission:portal.cms.research_types.delete"]);
            });

            Route::group(['prefix' => "pages", 'as' => "pages."], function(){
                Route::get('/', ['as' => "index", 'uses' => "PagesController@index", 'middleware' => "portal.permission:portal.cms.pages.index"]);
                Route::get('/create', ['as' => "create", 'uses' => "PagesController@create", 'middleware' => "portal.permission:portal.cms.pages.create"]);
                Route::post('/create', ['uses' => "PagesController@store", 'middleware' => "portal.permission:portal.cms.pages.create"]);
                Route::get('/edit/{id?}', ['as' => "edit", 'uses' => "PagesController@edit", 'middleware' => "portal.permission:portal.cms.pages.update"]);
                Route::post('/edit/{id?}', ['uses' => "PagesController@update", 'middleware' => "portal.permission:portal.cms.pages.update"]);
                Route::get('/show/{id?}', ['as' => "show", 'uses' => "PagesController@show", 'middleware' => "portal.permission:portal.cms.pages.view"]);
                Route::any('/delete/{id?}', ['as' => "delete", 'uses' => "PagesController@destroy", 'middleware' => "portal.permission:portal.cms.pages.delete"]);
            });
        });

        Route::group(['prefix' => "audit-trail", 'as' => "audit_trail."], function(){
            Route::get('/', ['as' => "index", 'uses' => "AuditTrailController@index", 'middleware' => "portal.permission:portal.audit_trail.index"]);
            Route::get('/export', ['as' => "export", 'uses' => "AuditTrailController@export", 'middleware' => "portal.permission:portal.audit_trail.export"]);
        });

        Route::group(['prefix' => "profile", 'as' => "profile."], function(){
            Route::get('/', ['as' => "index", 'uses' => "ProfileController@index"]);
            Route::post('/', ['uses' => "ProfileController@update_profile"]);
            Route::get('/edit-password', ['as' => "edit_password", 'uses' => "ProfileController@edit_password"]);
            Route::post('/edit-password', ['uses' => "ProfileController@update_password"]);
        });
    });
});