<?php

Route::get('/', 'PostsController@index')->name('posts.index');
Route::get('posts/create', 'PostsController@create')->name('posts.create');
Route::post('posts', 'PostsController@store')->name('posts.store');

Route::get('posts/{post}/edit', 'PostsController@edit')->name('posts.edit');
Route::patch('posts/{post}', 'PostsController@update')->name('posts.update');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
