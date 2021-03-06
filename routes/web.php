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

Route::get('/', 'MicropostsController@index'); //Controller ( MicropostsController@index ) を経由してwelcomeを表示する

//2022.07.03..2245TKT
// ユーザ登録
Route::get('signup', 'Auth\RegisterController@showRegistrationForm')->name('signup.get');
Route::post('signup', 'Auth\RegisterController@register')->name('signup.post');
//->name() はこのルーティングに名前をつけているだけです。
//->name() は、のちほど、Formやlink_to_route() で使用することになります。


// 認証 L15 C7.2
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login')->name('login.post');
Route::get('logout', 'Auth\LoginController@logout')->name('logout.get');

//認証付きのルーティング。ユーザ一覧とユーザ詳細はログインしていない閲覧者には見せたくありません。そのようなときは auth ミドルウェアを使いましょう。
Route::group(['middleware' => ['auth']], function () {
    Route::group(['prefix' => 'users/{id}'], function () {
        Route::post('follow', 'UserFollowController@store')->name('user.follow');
        Route::delete('unfollow', 'UserFollowController@destroy')->name('user.unfollow');
        Route::get('followings', 'UsersController@followings')->name('users.followings');
        Route::get('followers', 'UsersController@followers')->name('users.followers');
    }); //authの Route::group の中に ['prefix' => 'users/{id}'] とした Route::group を追加しています。Lesson 15Chapter 10.2 Router
Route::resource('users', 'UsersController', ['only' => ['index', 'show']]); //
Route::resource('microposts', 'MicropostsController', ['only' => ['store', 'destroy']]); //認証を必要とするルーティンググループ内に、 Micropostsのルーティングを設定します（登録のstoreと削除のdestroyのみ）。これで、認証済みのユーザだけがこれらのアクションにアクセスできます。
}); //groupの閉じ括弧２つ

