<?php

$crudui_path = '\Helori\LaravelCrudui\Controllers';

Route::get('/crud/{model}/items', ['uses' => $crudui_path.'\CrudController@getItems']);
Route::get('/crud/{model}/create-item', ['uses' => $crudui_path.'\CrudController@getCreateItem']);
Route::post('/crud/{model}/store-item', ['uses' => $crudui_path.'\CrudController@postStoreItem']);
Route::get('/crud/{model}/edit-item/{id}', ['uses' => $crudui_path.'\CrudController@getEditItem']);
Route::post('/crud/{model}/update-item/{id}', ['uses' => $crudui_path.'\CrudController@postUpdateItem']);
Route::get('/crud/{model}/delete-item/{id}', ['uses' => $crudui_path.'\CrudController@getDeleteItem']);
Route::post('/crud/{model}/update-position', ['uses' => $crudui_path.'\CrudController@postUpdatePosition']);
Route::post('/crud/{model}/update-field', ['uses' => $crudui_path.'\CrudController@postUpdateField']);

Route::get('/crud/{parent_model}/{parent_id}/{model}/items', ['uses' => $crudui_path.'\CrudController@getItems']);
Route::get('/crud/{parent_model}/{parent_id}/{model}/create-item', ['uses' => $crudui_path.'\CrudController@getCreateItem']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/store-item', ['uses' => $crudui_path.'\CrudController@postStoreItem']);
Route::get('/crud/{parent_model}/{parent_id}/{model}/edit-item/{id}', ['uses' => $crudui_path.'\CrudController@getEditItem']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/update-item/{id}', ['uses' => $crudui_path.'\CrudController@postUpdateItem']);
Route::get('/crud/{parent_model}/{parent_id}/{model}/delete-item/{id}', ['uses' => $crudui_path.'\CrudController@getDeleteItem']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/update-position', ['uses' => $crudui_path.'\CrudController@postUpdatePosition']);
Route::post('/crud/{parent_model}/{parent_id}/{model}/update-field', ['uses' => $crudui_path.'\CrudController@postUpdateField']);

Route::get('/ru/{model}/items', ['uses' => $crudui_path.'\CrudSingleController@getItems']);
Route::post('/ru/{model}/update-item/{id}', ['uses' => $crudui_path.'\CrudSingleController@postUpdateItem']);

Route::post('/medias/{model}/upload-media', ['uses' => $crudui_path.'\MediasController@postUploadMedia']);
Route::post('/medias/{model}/upload-medias', ['uses' => $crudui_path.'\MediasController@postUploadMedias']);
Route::post('/medias/{model}/delete-media', ['uses' => $crudui_path.'\MediasController@postDeleteMedia']);
Route::get('/medias/{model}/media/{id}/{collection}', ['uses' => $crudui_path.'\MediasController@getMedia']);
Route::get('/medias/{model}/medias/{id}/{collection}', ['uses' => $crudui_path.'\MediasController@getMedias']);
Route::post('/medias/{model}/update-medias-position', ['uses' => $crudui_path.'\MediasController@postUpdateMediasPosition']);

Route::get('/global-medias', ['uses' => $crudui_path.'\GlobalMediasController@index']);
Route::get('/global-medias/read-all', ['uses' => $crudui_path.'\GlobalMediasController@readAll']);
Route::post('/global-medias/upload', ['uses' => $crudui_path.'\GlobalMediasController@upload']);
Route::post('/global-medias/delete', ['uses' => $crudui_path.'\GlobalMediasController@delete']);
Route::post('/global-medias/update', ['uses' => $crudui_path.'\GlobalMediasController@update']);
Route::get('/global-medias/download/{id}', ['uses' => $crudui_path.'\GlobalMediasController@download']);
