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
       Route::get('/dossiers','FolderController@index')->name('index-folder')->middleware('can:index-folder');
       Route::get('/dossiers/search/{field?}/{query?}','FolderController@search')->name('search-folder')->middleware('can:search-folder');
       Route::post('/dossiers/nouveau','FolderController@store')->name('store-folder')->middleware('can:store-folder');
       Route::post('/dossiers/{id}/edition','FolderController@update')->name('update-folder')->middleware('can:update-folder');
       Route::get('/dossiers/{id}/supression','FolderController@destroy')->name('destroy-folder')->middleware('can:destroy-folder');

       // Documents
       Route::get('/dossiers/{id}/documents','DocumentController@index')->name('index-document')->middleware('can:index-document');
       Route::get('/dossiers/{id}/documents/search/{field?}/{query?}','DocumentController@search')->name('search-document')->middleware('can:search-document');
       Route::post('/dossiers/{id}/documents/nouveau','DocumentController@store')->name('store-document')->middleware('can:store-document');
       Route::post('/dossiers/{id_folder}/documents/{id_doc}/edition','DocumentController@update')->name('update-document')->middleware('can:update-document');
       Route::get('/dossiers/{id_folder}/documents/{id_doc}/supression','DocumentController@destroy')->name('destroy-document')->middleware('can:destroy-document');

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
