<?php



Route::get('/', function () {
    return view('contenido/contenido');
});

// RUTAS DE CATEGORIAS DE PRODUCTO
Route::get('/categoria', 'CategoriaController@index');
Route::post('/categoria/registrar', 'CategoriaController@store');
Route::put('/categoria/actualizar', 'CategoriaController@update');
Route::put('/categoria/desactivar', 'CategoriaController@desactivar');
Route::put('/categoria/activar', 'CategoriaController@activar');

// RUTAS DE MEDIDAS DE PRODUCTO
Route::get('/medida', 'MedidaProductoController@index');
Route::post('/medida/registrar', 'MedidaProductoController@store');
Route::put('/medida/actualizar', 'MedidaProductoController@update');
Route::put('/medida/desactivar', 'MedidaProductoController@desactivar');
Route::put('/medida/activar', 'MedidaProductoController@activar');

// RUTAS DE PRODUCTO
