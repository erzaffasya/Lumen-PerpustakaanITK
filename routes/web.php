<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

// $router->get('user/{id}', 'UserController@show');

// $router->get('/', function () use ($router) {
//     echo "<center> Welcome </center>";
// });

// $router->get('/version', function () use ($router) {
//     return $router->app->version();
// });
Route::group(['prefix' => 'api'], function ($router) {
    Route::post('login', 'AuthController@login');
    Route::get('logout', 'AuthController@logout');
    Route::get('profile', 'AuthController@me');
    Route::post('refresh', 'AuthController@refresh');
});
Route::group(['prefix' => 'api', 'middleware' => 'auth'], function ($router) {

    // Ruangan Baca 
    Route::get('ruangan-baca', 'RuanganBacaController@index');
    Route::post('ruangan-baca', 'RuanganBacaController@store');
    Route::get('ruangan-baca/{id}', 'RuanganBacaController@show');
    Route::put('ruangan-baca/{id}', 'RuanganBacaController@update');
    Route::delete('ruangan-baca/{id}', 'RuanganBacaController@destroy');

    // Kursi Baca 
    Route::get('kursi-baca', 'KursiBacaController@index');
    Route::post('kursi-baca', 'KursiBacaController@store');
    Route::get('kursi-baca/{id}', 'KursiBacaController@show');
    Route::put('kursi-baca/{id}', 'KursiBacaController@update');
    Route::delete('kursi-baca/{id}', 'KursiBacaController@destroy');

    //peminjaman-ruangan
    Route::get('peminjaman-ruangan', 'PeminjamanRuanganController@index');
    Route::post('peminjaman-ruangan', 'PeminjamanRuanganController@store');
    Route::get('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@show');
    Route::put('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@update');
    Route::delete('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@destroy');
    Route::get('ruang-kosong/{ruang}/waktu/{tanggal}', 'PeminjamanRuanganController@RuanganKosong');

    //Kategori
    Route::get('kategori', 'KategoriController@index');
    Route::post('kategori', 'KategoriController@store');
    Route::get('kategori/{id}', 'KategoriController@show');
    Route::put('kategori/{id}', 'KategoriController@update');
    Route::delete('kategori/{id}', 'KategoriController@destroy');

    //Bookmark
    Route::get('bookmark', 'BookmarkController@index');
    Route::post('bookmark', 'BookmarkController@store');
    Route::get('bookmark/{id}', 'BookmarkController@show');
    Route::put('bookmark/{id}', 'BookmarkController@update');
    Route::delete('bookmark/{id}', 'BookmarkController@destroy');

    //Dokumen
    Route::get('dokumen', 'DokumenController@index');
    Route::post('dokumen', 'DokumenController@store');
    Route::get('dokumen/{id}', 'DokumenController@show');
    Route::put('dokumen/{id}', 'DokumenController@update');
    Route::delete('dokumen/{id}', 'DokumenController@destroy');

    Route::get('/showDokumen/{id}/{data}', 'DokumenController@showfile');
    // Route::get('/dokumen/{id}/download', [DokumenController::class, 'download']);
    Route::get('/dokumen/{id}/view/{data}', 'DokumenController@view');
    // Route::get('/view/{filename}', [DokumenController::class, 'view_dokumen'])->name('viewdoc');

    //Peminjaman
    Route::get('peminjaman-dokumen', 'PeminjamanController@index');
    Route::post('peminjaman-dokumen', 'PeminjamanController@store');
    Route::get('peminjaman-dokumen/{id}', 'PeminjamanController@show');
    Route::put('peminjaman-dokumen/{id}', 'PeminjamanController@update');
    Route::delete('peminjaman-dokumen/{id}', 'PeminjamanController@destroy');
});
