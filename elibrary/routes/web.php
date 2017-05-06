<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/
Route::get('/', [
		'uses' => "UserController@getLoginPage",
		'as' => 'intro'
]);

Route::get('/login', [
		'uses' => "UserController@getLoginPage",
		'as' => 'loginpage'
]);

Route::post('/signIn', [
		'uses' => "UserController@UserSignIn",
		'as' => 'user-in'
]);

Route::get('/dashboard', [
		'uses' => "UserController@getDashboard",
		'as' => 'home',
		'middleware' => 'auth'
]);

Route::get('/logout', [
		'uses' => "UserController@logoutAccount",
		'as' => 'user-out'
]);

Route::get('/library/category/{category}/item/{id}', [
	'uses'=> "LibraryController@getLibraryItem",
	'as' => 'get-library-item-content',
	'middleware' => 'auth'
]);

Route::get('/library/search/ebook', [
		'uses' => "UserController@getSearchEbook",
		'as' => 'search-ebook',
		'middleware' => 'auth'
]);

Route::post('/forms/{type}/{action}/{user}', [
	'uses'=> "FormController@processAction",
	'as' => 'user-form-processor',
	'middleware' => 'auth'
]);

//API
Route::group(['prefix'=>'api'], function(){
	Route::group(['prefix'=>'user'], function(){

		Route::get('{id}', ['uses'=>"APIUserController@getUser"]);

		Route::get('/login/{id}/{key}', ['uses'=>"APIUserController@getUserLoginAPI"]);

	});
});

//..................ADMIN..............................................

Route::get('/web/admin', [
		'uses' => "AdminAuth\AuthController@getLogin",
		'as' => 'adminSignIn'
]); 

Route::get('/admin/logout', [
		'uses' => "AdminController@logoutUser",
		'as' => 'admin-out'
]);

Route::post('/web/admin/signin', [
		'uses' => "AdminController@adminSignIn",
		'as' => 'postSignIn'
]);

Route::get('/admin/library/dashboard', [
		'uses' => "AdminController@getAdminDashBoard",
		'as' => 'admin-dashboard',
		'middleware' => 'admin'
]);

Route::get('/admin/library/categories', [
		'uses' => "LibraryController@getCategory",
		'as' => 'admin-library-category',
		'middleware' => 'admin'
]);

Route::post('/admin/library/category/addNew', [
		'uses'=>"LibraryController@createCategory",
		'as'=>'create-library-category',
		'middleware'=>'admin'
]);

Route::get('/admin/manage/library', [
		'uses' => "LibraryController@getLibraryContent",
		'as' => 'admin-library-content',
		'middleware' => 'admin'
]);

Route::post('/admin/manage/library/add', [
		'uses' => "LibraryController@addToLibrary",
		'as' => 'add-library-content',
		'middleware' => 'admin'
]);








Route::get('/admin/portal/course-information', [
		'uses' => "AdminController@getAdminCoursePage",
		'as' => 'course-page',
		'middleware' => 'admin'
]);

Route::get('/admin/portal/course-set-up', [
		'uses' => "AdminController@getAdminCourseSetupPage",
		'as' => 'course-setup-page',
		'middleware' => 'admin'
]);

Route::get('/admin/portal/migration', [
		'uses' => "AdminController@getAdminDashBoard",
		'as' => 'admin-migration',
		'middleware' => 'admin'
]);

Route::get('/admin/manage/user', [
		'uses' => "AdminController@getAdminUsers",
		'as' => 'admin-user-manager',
		'middleware' => 'admin'
]);

Route::post('/admin/dataForm/process/{formNow}', [
		'uses'=>"AdminController@submitFormData",
		'as'=>'adminProcessDataForm',
		'middleware'=>'admin'
]);

Route::get('/admin/manage/{action}/{user}', [
		'uses' => "AdminController@getActionUser",
		'as' => 'admin-user-control',
		'middleware' => 'admin'
]);

Route::get('/admin-select-option/{listType}/{optionValue}', [
		'uses'=>"FormController@getSelectOption",
		'as'=>'admin-form-select-option',
		'middleware'=>'admin'
]);

Route::post('/admin-process-file/{fileAction}/{optionValue}', [
		'uses'=>"FormController@fileProcessor",
		'as'=>'admin-file-processor',
		'middleware'=>'admin'
]);

