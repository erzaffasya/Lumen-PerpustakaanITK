<?php

/** @var \Laravel\Lumen\Routing\Router $router */


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

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function ($router) {

    $router->get('revisi-dokumen', 'DokumenController@revisiDokumen');

    $router->post('login', 'AuthController@login');
    $router->get('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');

    $router->get('/pengunjung', 'PengunjungController@index');
    $router->post('/pengunjung', 'PengunjungController@tambahPengunjung');
    $router->delete('/pengunjung/{id}', 'PengunjungController@destroy');

    $router->get('/QRCode', 'PengunjungController@qrcode');
    $router->post('/checkin-pengunjung', 'PengunjungController@store');
    $router->get('/showDokumen/{id}/{data}', 'DokumenController@showfile');
});
$router->group(['prefix' => 'api', 'middleware' => 'auth'], function ($router) {
    $router->group(['middleware' => 'role:Admin'], function () use ($router) {
        $router->post('ruangan', 'RuanganController@store');
        $router->put('ruangan/{id}', 'RuanganController@update');
        $router->delete('ruangan/{id}', 'RuanganController@destroy');
        $router->put('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@update');
        $router->post('kategori', 'KategoriController@store');
        $router->put('kategori/{id}', 'KategoriController@update');
        $router->delete('kategori/{id}', 'KategoriController@destroy');
        // $router->put('bookmark/{id}', 'BookmarkController@update');
        $router->put('revisi-dokumen/{id}', 'DokumenController@revisiDokumen');
        $router->put('yudisium/{id}', 'YudisiumController@update');
        $router->delete('yudisium/{id}', 'YudisiumController@destroy');
        $router->post('yudisium', 'YudisiumController@store');
        $router->put('yudisium-mahasiswa/{id}', 'YudisiumMahasiswaController@update');
        $router->put('user/{id}', 'UserController@update');
        $router->delete('user/{id}', 'UserController@destroy');
    });

    $router->group(['middleware' => 'role:Admin,Dosen'], function () use ($router) {
        $router->get('/download-dokumen/{id}/{data}', 'DokumenController@downloadFile');
    });

    $router->get('/cek-akses-dokumen/{id}/{data}', 'DokumenController@cekAksesDokumen');
    $router->get('gcalender/{namaEvent}/{tanggal}/{waktuAwal}/{waktuAkhir}', 'PeminjamanRuanganController@gcalender');
    $router->get('profil', 'AuthController@me');


    // Ruangan 
    $router->get('ruangan', 'RuanganController@index');
    // $router->post('ruangan', 'RuanganController@store');
    $router->get('ruangan/{id}', 'RuanganController@show');
    // $router->put('ruangan/{id}', 'RuanganController@update');
    // $router->delete('ruangan/{id}', 'RuanganController@destroy');

    //peminjaman-ruangan
    $router->get('peminjaman-ruangan', 'PeminjamanRuanganController@index');
    $router->post('peminjaman-ruangan', 'PeminjamanRuanganController@store');
    $router->get('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@show');
    // $router->put('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@update');
    $router->delete('peminjaman-ruangan/{id}', 'PeminjamanRuanganController@destroy');
    $router->get('ruang-kosong/{tanggal}/waktu_awal/{waktu_awal}/waktu_akhir/{waktu_akhir}', 'PeminjamanRuanganController@RuanganKosong');

    //Kategori
    $router->get('kategori', 'KategoriController@index');
    // $router->post('kategori', 'KategoriController@store');
    $router->get('kategori/{id}', 'KategoriController@show');
    // $router->put('kategori/{id}', 'KategoriController@update');
    // $router->delete('kategori/{id}', 'KategoriController@destroy');

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
    // $router->put('revisi-dokumen/{id}', 'DokumenController@revisiDokumen');


    $router->get('cek-dokumen-perjurusan', 'DokumenController@cekDokumenPerjurusan');
    $router->get('/dokumen/{id}/view/{data}', 'DokumenController@view');
    // $router->get('/download-dokumen/{id}/{data}', 'DokumenController@downloadFile');
    $router->get('/cari-dokumen/{id}', 'DokumenController@cariDokumen');
    $router->get('/data-dokumen', 'DokumenController@dataDokumen');
    $router->get('dokumen/riwayat-peminjaman/{id}', 'PeminjamanDokumenController@riwayatPeminjaman');

    //Peminjaman
    $router->get('peminjaman-dokumen', 'PeminjamanDokumenController@index');
    $router->post('peminjaman-dokumen', 'PeminjamanDokumenController@store');
    $router->get('peminjaman-dokumen/{id}', 'PeminjamanDokumenController@show');
    $router->put('peminjaman-dokumen/{id}', 'PeminjamanDokumenController@update');
    $router->delete('peminjaman-dokumen/{id}', 'PeminjamanDokumenController@destroy');
    // $router->get('riwayat-peminjaman-dokumen', 'PeminjamanDokumenController@riwayatPeminjamanDokumen');

    //Pembimbing
    $router->get('pembimbing', 'PembimbingController@index');
    $router->post('pembimbing', 'PembimbingController@store');
    $router->get('pembimbing/{id}', 'PembimbingController@show');
    $router->put('pembimbing/{id}', 'PembimbingController@update');
    $router->delete('pembimbing/{id}', 'PembimbingController@destroy');
    $router->get('pembimbing/{id}/dokumen', 'PembimbingController@getByDokukumenId');

    //Yudisium
    $router->get('yudisium', 'YudisiumController@index');
    // $router->post('yudisium', 'YudisiumController@store');
    $router->get('yudisium/{id}', 'YudisiumController@show');
    // $router->put('yudisium/{id}', 'YudisiumController@update');
    // $router->delete('yudisium/{id}', 'YudisiumController@destroy');

    //MahasiswaYudisium
    $router->get('yudisium-mahasiswa', 'YudisiumMahasiswaController@index');
    $router->post('yudisium-mahasiswa', 'YudisiumMahasiswaController@store');
    $router->get('yudisium-mahasiswa/{id}', 'YudisiumMahasiswaController@show');
    // $router->put('yudisium-mahasiswa/{id}', 'YudisiumMahasiswaController@update');
    $router->delete('yudisium-mahasiswa/{id}', 'YudisiumMahasiswaController@destroy');

    //Notifikasi
    $router->get('notifikasi', 'NotifikasiController@index');
    $router->get('baca-notifikasi', 'NotifikasiController@bacaNotifikasi');

    //User
    $router->get('user', 'UserController@index');
    $router->post('user', 'UserController@store');
    $router->get('user/{id}', 'UserController@show');
    // $router->put('user/{id}', 'UserController@update');
    // $router->delete('user/{id}', 'UserController@destroy');
    $router->post('bebas-pustaka', 'UserController@bebasPustaka');

    //Statistik
    $router->get('jumlah-dokumen', 'StatistikController@jumlahDokumen');
    $router->get('jumlah-peminjaman-dokumen', 'StatistikController@jumlahPeminjamanDokumen');
    $router->get('jumlah-ruangan', 'StatistikController@jumlahRuangan');
    $router->get('jumlah-peminjaman-ruangan', 'StatistikController@jumlahPeminjamanRuangan');
    $router->get('jumlah-pengunjung', 'StatistikController@jumlahPengunjung');
    $router->get('jumlah-yudisium-mahasiswa', 'StatistikController@jumlahYudisium');
    $router->get('pengunjung-terakhir', 'StatistikController@pengunjungTerbaru');
    $router->get('grafik-perpustakaan', 'StatistikController@grafikPerpustakaan');
    $router->get('peminjaman-dokumen-populer', 'StatistikController@peminjamanDokumenPopuler');
    $router->get('peminjaman-ruangan-populer', 'StatistikController@peminjamanRuanganPopuler');

    //Aktif
    $router->get('peminjaman-ruangan-aktif', 'PeminjamanRuanganController@peminjamanRuanganAktif');
    $router->get('peminjaman-dokumen-aktif', 'PeminjamanDokumenController@peminjamanDokumenAktif');
});
