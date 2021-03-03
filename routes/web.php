<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Users auth
Route::get('/','Auth\LoginController@showLoginForm')->name('create-login');
Route::post('/','Auth\LoginController@login')->name('store-login');

// Email verify routes
Route::get('/email/verification/{id}','Auth\VerificationController@verify')->name('verification.verify');
Route::middleware(['auth'])->group(function () {
    Route::group(['middleware' => ['second-login']], function() {
       // Dashboard route
       Route::get('/tableau-de-bord','DashboardController@index')->name('index-dashboard')->middleware('can:list-dashboard');
       Route::get('/search/fullname/','DashboardController@search')->name('fullname-dashboard')->middleware('can:fullname-dashboard');
       Route::get('/search/decret/','DashboardController@search')->name('decret-dashboard')->middleware('can:decret-dashboard');
       Route::get('/search/datedecret/','DashboardController@search')->name('datedecret-dashboard')->middleware('can:datedecret-dashboard');

       // Stat
       Route::get('/statistiques','StatController@index')->name('index-stat')->middleware('can:index-stat');
       Route::get('/statistiques/{id}/{date}/search','StatController@search');
            
       // Users
       Route::get('/utilisateurs','UserController@index')->name('index-user')->middleware('can:index-user');
       Route::get('/utilisateurs/search/{field?}/{query?}','UserController@search')->name('search-user')->middleware('can:search-user');
       Route::post('/utilisateurs/nouveau','UserController@store')->name('store-user')->middleware('can:store-user');
       Route::post('/utilisateurs/{id}/edition','UserController@update')->name('update-user')->middleware('can:update-user');
       Route::get('/utilisateurs/{id}/supression','UserController@destroy')->name('destroy-user')->middleware('can:destroy-user');

       // Roles
       Route::get('/roles','RoleController@index')->name('index-role')->middleware('can:index-role');
       Route::get('/roles/search/{field?}/{query?}','RoleController@search')->name('search-role')->middleware('can:search-role');
       Route::post('/roles/nouveau','RoleController@store')->name('store-role')->middleware('can:store-role');
       Route::post('/roles/{id}/edition','RoleController@update')->name('update-role')->middleware('can:update-role');
       Route::get('/roles/{id}/supression','RoleController@destroy')->name('destroy-role')->middleware('can:destroy-role');

       // Folders
       Route::get('/decrets','FolderController@index')->name('index-folder')->middleware('can:index-folder');
       Route::get('/decrets/nouveau','FolderController@create')->name('create-folder')->middleware('can:create-folder');
       Route::get('/decrets/search/{field?}/{query?}','FolderController@search')->name('search-folder')->middleware('can:search-folder');
       Route::post('/decrets/nouveau','FolderController@store')->name('store-folder')->middleware('can:store-folder');
       Route::get('/decrets/{id}/edition','FolderController@edit')->name('edit-folder')->middleware('can:edit-folder');
       Route::post('/decrets/{id}/edition','FolderController@update')->name('update-folder')->middleware('can:update-folder');
       Route::get('/decrets/{id}/supression','FolderController@destroy')->name('destroy-folder')->middleware('can:destroy-folder');

       // Documents
       Route::get('/decrets/{id}/documents','DocumentController@index')->name('index-document')->middleware('can:index-document');
       Route::get('/decrets/{id}/documents/search/{field?}/{query?}','DocumentController@search')->name('search-document')->middleware('can:search-document');
       Route::post('/decrets/{id}/documents/nouveau','DocumentController@store')->name('store-document')->middleware('can:store-document');
       Route::post('/decrets/{id_folder}/documents/{id_doc}/edition','DocumentController@update')->name('update-document')->middleware('can:update-document');
       Route::get('/decrets/{id_folder}/documents/{id_doc}/supression','DocumentController@destroy')->name('destroy-document')->middleware('can:destroy-document');

       // Decorations
       Route::get('/decorations','DecorationController@index')->name('index-decoration')->middleware('can:index-decoration');
       Route::get('/decorations/search/{field?}/{query?}','DecorationController@search')->name('search-decoration')->middleware('can:search-decoration');
       Route::post('/decorations/nouveau','DecorationController@store')->name('store-decoration')->middleware('can:store-decoration');
       Route::post('/decorations/{id}/edition','DecorationController@update')->name('update-decoration')->middleware('can:update-decoration');
       Route::get('/decorations/{id}/supression','DecorationController@destroy')->name('destroy-decoration')->middleware('can:destroy-decoration');

       // SubDecorations
       Route::get('/grades','SubDecorationController@index')->name('index-grade')->middleware('can:index-grade');
       Route::post('/grades/nouveau','SubDecorationController@store')->name('store-grade')->middleware('can:store-grade');
       Route::post('/grades/{id}/edition','SubDecorationController@update')->name('update-grade')->middleware('can:update-grade');
       Route::get('/grades/{id}/supression','SubDecorationController@destroy')->name('destroy-grade')->middleware('can:destroy-grade');
       Route::get('/grades/{id}/search','SubDecorationController@search');

       Route::get('/profil','ProfileController@edit')->name('edit-profile')->middleware('can:edit-profile');
       Route::post('/profil','ProfileController@update')->name('update-profile')->middleware('can:update-profile');

        // Auth route
        Route::get('/deconnexion','Auth\LoginController@logout')->name('destroy-login');
    });
    Route::group(['middleware' => ['first-login']], function (){
        Route::get('/mot-de-passe','FirstLoginController@create')->name('first-login-create');
        Route::post('/mot-de-passe','FirstLoginController@store')->name('first-login-store');
    });

});
