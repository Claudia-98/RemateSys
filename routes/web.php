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
Route::get('/producto', 'ProductoController@index');
Route::post('/producto/registrar', 'ProductoController@store');
Route::put('/producto/actualizar', 'ProductoController@update');
Route::put('/producto/desactivar', 'ProductoController@desactivar');
Route::put('/producto/activar', 'ProductoController@activar');
Route::get('/producto/obtenerMedida', 'ProductoController@obtenerMedida');
Route::get('/producto/obtenerPersona', 'ProductoController@obtenerPersona');
Route::get('/producto/obtenerTipo', 'ProductoController@obtenerTipo');
Route::get('/producto/obtenerProducto', 'ProductoController@obtenerProducto');
Route::get('/producto/listarProducto','ProductoController@listarProducto');

// RUTAS DE ESPACIO
Route::get('/espacio', 'EspacioController@index');
Route::get('/espacio/obtenerProducto', 'EspacioController@obtenerProducto');
Route::get('/espacio/obtenerStock', 'EspacioController@obtenerStock');
Route::get('/espacio/productos', 'EspacioController@obtenerSP');
Route::post('/espacio/registrar', 'EspacioController@store');
Route::post('/espacio/registrarP', 'EspacioController@storeP');
Route::put('/espacio/actualizar', 'EspacioController@update');
Route::put('/espacio/actualizarP', 'EspacioController@updateP');
Route::put('/espacio/desactivarP', 'EspacioController@desactivarP');
Route::put('/espacio/activarP', 'EspacioController@activarP');
Route::put('/espacio/desactivar', 'EspacioController@desactivar');
Route::put('/espacio/activar', 'EspacioController@activar');


// RUTAS DE PERSONA
Route::get('/persona', 'PersonaController@index');
Route::post('/persona/registrar', 'PersonaController@store');
Route::put('/persona/actualizar', 'PersonaController@update');
Route::put('/persona/desactivar', 'PersonaController@desactivar');
Route::put('/persona/activar', 'PersonaController@activar');

// RUTAS DE COMPRAS
Route::get('/compra', 'CompraController@index');
Route::post('/compra/registrar', 'CompraController@store');
Route::put('/compra/actualizar', 'CompraController@update');
Route::put('/compra/desactivar', 'CompraController@desactivar');
Route::put('/compra/activar', 'CompraController@activar');
Route::get('/compra/obtenerPersona', 'CompraController@obtenerPersona');
<<<<<<< HEAD

// RUTAS DE MOVIMIENTOS
Route::get('/movimiento','MovimientoController@index');
Route::get('/movimiento/obtenerEspacio','MovimientoController@obtenerEspacio');
Route::get('/movimientos/noRecibo','MovimientoController@obtenerNoRecibo');
Route::post('/movimiento/registrar','MovimientoController@store');

// RUTA DE PRUEBAS
Route::get('/pruebas','PruebasController@store');
=======
Route::post('/compra/registrarPersona', 'PersonaController@store');
Route::get('/compra/obtenerUltimaPersona', 'CompraController@ultimaPersona');
<<<<<<< HEAD
Route::get('/compra/buscarProducto', 'CompraController@buscarProducto');

=======
>>>>>>> d13ea4a0a9384a5558a0e8baa2ead87c357b9248
>>>>>>> 46eb66aa2a30ac7d532b1f8dc298239ae6320e8b
