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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// $router->get('/version', function () use ($router) {
//     return $router->app->version();
// });
$router->group(['prefix' => 'api'], function ($router) {
    
    $router->get('gcalender', 'PeminjamanRuanganController@gcalender');
    $router->post('login', 'AuthController@login');
    $router->get('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->get('/showDokumen/{id}/{data}', 'DokumenController@showfile');
});
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function ($router) {

    $router->get('profile', 'AuthController@me');
    // Ruangan Baca 
    $router->get('ruangan-baca', 'RuanganBacaController@index');
    $router->post('ruangan-baca', 'RuanganBacaController@store');
    $router->get('ruangan-baca/{id}', 'RuanganBacaController@show');
    $router->put('ruangan-baca/{id}', 'RuanganBacaController@update');
    $router->delete('ruangan-baca/{id}', 'RuanganBacaController@destroy');

    // Kursi Baca 
    $router->get('kursi-baca', 'KursiBacaController@index');
    $router->post('kursi-baca', 'KursiBacaController@store');
    $router->get('kursi-baca/{id}', 'KursiBacaController@show');
    $router->put('kursi-baca/{id}', 'KursiBacaController@update');
    $router->delete('kursi-baca/{id}', 'KursiBacaController@destroy');

    //peminjaman-ruangan
    $router->get('peminjaman-ruangan', 'PeminjamanRuanganController@index');
    $router->post('peminjaman-ruangan', 'PeminjamanRuanganController@store');
    $router->get('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@show');
    $router->put('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@update');
    $router->delete('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@destroy');
    $router->get('ruang-kosong/{ruang}/waktu/{tanggal}', 'PeminjamanRuanganController@RuanganKosong');

    //Kategori
    $router->get('kategori', 'KategoriController@index');
    $router->post('kategori', 'KategoriController@store');
    $router->get('kategori/{id}', 'KategoriController@show');
    $router->put('kategori/{id}', 'KategoriController@update');
    $router->delete('kategori/{id}', 'KategoriController@destroy');

    //Bookmark
    $router->get('bookmark', 'BookmarkController@index');
    $router->post('bookmark', 'BookmarkController@store');
    $router->get('bookmark/{id}', 'BookmarkController@show');
    $router->put('bookmark/{id}', 'BookmarkController@update');
    $router->delete('bookmark/{id}', 'BookmarkController@destroy');

    //Dokumen
    $router->get('dokumen', 'DokumenController@index');
    $router->post('dokumen', 'DokumenController@store');
    $router->get('dokumen/{id}', 'DokumenController@show');
    $router->put('dokumen/{id}', 'DokumenController@update');
    $router->delete('dokumen/{id}', 'DokumenController@destroy');

    // $router->get('/dokumen/{id}/download', [DokumenController::class, 'download']);
    $router->get('/dokumen/{id}/view/{data}', 'DokumenController@view');
    // $router->get('/view/{filename}', [DokumenController::class, 'view_dokumen'])->name('viewdoc');

    //Peminjaman
    $router->get('peminjaman-dokumen', 'PeminjamanController@index');
    $router->post('peminjaman-dokumen', 'PeminjamanController@store');
    $router->get('peminjaman-dokumen/{id}', 'PeminjamanController@show');
    $router->put('peminjaman-dokumen/{id}', 'PeminjamanController@update');
    $router->delete('peminjaman-dokumen/{id}', 'PeminjamanController@destroy');
});
