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

Route::get('/', function () {
    return view('home/index/index');
});
//Route::get('/', 'HomeController@index');

Route::prefix('home')->namespace('Home')->group(function (){
    Route::prefix('index')->group(function (){
        Route::get('index','IndexController@index');
        Route::get('ajaxGetIndexInfo','IndexController@ajaxGetIndexInfo');
        Route::post('create','IndexController@create');
        Route::get('show/{id}','IndexController@show')->where('id','[0-9]+');
        Route::get('edit','IndexController@edit');
        Route::get('update','IndexController@update');
        Route::get('destroy','IndexController@destroy');
        Route::post('uploadImage','IndexController@uploadImage');
        Route::post('addFolderName','IndexController@addFolderName');
        Route::post('collectionImage','IndexController@collectionImage');
        Route::get('ajaxGetFolderName','IndexController@ajaxGetFolderName');
        Route::get('ajaxDownload','IndexController@ajaxDownload');
        Route::get('ajaxShowNum','IndexController@ajaxShowNum');
    });

    /*用户*/
    Route::prefix('user')->group(function (){
        Route::get('index','UserController@index');
        Route::post('updateAvatar','UserController@updateAvatar')->middleware('auth')->name('users.updateAvatar');
        Route::get('userinfo','UserController@userinfo');
        Route::get('myCollection','UserController@myCollection');
        Route::get('ajaxGetMyCollection','UserController@ajaxGetMyCollection');
        Route::get('ajaxGetMyUploadByType','UserController@ajaxGetMyUploadByType');
        Route::get('myUpload','UserController@myUpload');
        Route::get('show/{id}','UserController@show')->where('id','[0-9]+');
        Route::get('edit','UserController@edit');
        Route::get('update','UserController@update');
        Route::get('destroy','UserController@destroy');
        Route::get('ajaxMyFolder','UserController@ajaxMyFolder');
        Route::get('ajaxCollection','UserController@ajaxCollection');
        Route::post('ajaxDeleteImage','UserController@ajaxDeleteImage');
    });

    /*排行榜*/
    Route::prefix('leaderboard')->group(function (){
        Route::get('index','LeaderboardController@index');
        Route::get('ajaxGetData','LeaderboardController@ajaxGetData');
    });
    /*排行榜*/

    /*审核*/
    Route::middleware(['adminAuth'])->group(function () {
        Route::prefix('examin')->group(function (){
            Route::get('index','ExaminController@index');
            Route::get('ajaxGetImageByType','ExaminController@ajaxGetImageByType');
            Route::post('ajaxExamin','ExaminController@ajaxExamin');
            Route::get('userManagement','ExaminController@userManagement');
            Route::get('ajaxGetUserInfo','ExaminController@ajaxGetUserInfo');
            Route::post('ajaxDeleteUser','ExaminController@ajaxDeleteUser');
        });
    });

    Route::prefix('admin')->namespace('Admin')->group(function () {
        Route::prefix('admin')->group(function () {
            Route::get('index', 'LeaderBoardController@index');
            Route::get('ajaxGetData', 'LeaderBoardController@ajaxGetData');
        });
    });
});


Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

/*Route::match(['get', 'post'], 'foo', function () {
    return 'This is a request from get or post';
});*/
Route::get('home/test','HomeController@test');

Route::prefix('user')->group(function () {
    Route::get('index', 'UserController@index');
    Route::get('test1', 'UserController@test1');
    Route::get('store', 'UserController@store');
});
Route::prefix('admin')->namespace('Admin')->group(function (){
   Route::prefix('index')->group(function (){
       Route::get('index','IndexController@index');
       Route::get('create','IndexController@create');
       Route::get('show/{id}','IndexController@show')->where('id','[0-9]+');
       Route::get('edit','IndexController@edit');
       Route::get('update','IndexController@update');
       Route::get('destroy','IndexController@destroy');
   });
});

Route::post('create','RegisterController@create');