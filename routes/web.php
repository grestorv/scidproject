<?php

use Illuminate\Support\Facades\Route;

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

Route::get('magazine/list', 'MagazineController@list');
Route::post('magazine/add', 'MagazineController@add');
Route::post('magazine/update', 'MagazineController@update');
Route::post('magazine/delete', 'MagazineController@delete');

Route::get('author/list', 'AuthorController@list');
Route::post('author/add', 'AuthorController@add');
Route::post('author/update', 'AuthorController@update');
Route::post('author/delete', 'AuthorController@delete');
