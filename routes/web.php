<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\VentasController;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\VentaDetalleController;

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
    return view('welcome');
});

// ----------------------------- PRUEBA DE CONEXION SQL ---------------------------------

Route::get('/testSql', function(){
    try {
        $results = DB::select('SELECT 1');
        echo "Conexión exitosa a la base de datos SQL.";
    } catch (\Exception $e) {
        echo "Error al conectar a la base de datos SQL: " . $e->getMessage();
    }
});



// ----------------------------- ROUTES VENTAS ---------------------------------

Route::get('/ventas/verVentas', [VentasController::class, 'viewVentas'])->name('ventas.viewVentas');
Route::post('/ventas/searchVenta',[VentasController::class, 'searchVenta'])->name('ventas.searchVenta');
Route::post('/ventas/updateVenta',[VentasController::class, 'updateVenta'])->name('ventas.updateVenta'); 
Route::get('/ventas/home',[VentasController::class, 'home'])->name('ventas.home');
Route::post('/ventas/guardarVenta',[VentasController::class,'guardarVenta'])->name('ventas.guardarVenta');
Route::post('/ventas/filterVentas',[VentasController::class,'filterVentas'])->name('ventas.filterVentas');
Route::post('/ventas/deleteVenta',[VentasController::class,'deleteVenta'])->name('ventas.deleteVenta');

// ----------------------------- ROUTES PRODUCTOS ---------------------------------

Route::get('/productos/verProductos', [ProductosController::class, 'verProductos'])->name('productos.verProductos');
Route::post('/productos/getOneProducto',[ProductosController::class, 'getOneProducto'])->name('productos.getOneProducto');
Route::post('/productos/updateProducto',[ProductosController::class, 'updateProducto'])->name('productos.updateProducto'); 
Route::post('/productos/filterProducto',[ProductosController::class,'filterProducts'])->name('productos.filterProducts');
Route::get('/productos/verProductos',[ProductosController::class,'viewProductos'])->name('productos.viewProductos');
Route::get('/productos/other',[ProductosController::class,'other'])->name('productos.other');
Route::post('/productos/getother',[ProductosController::class,'otherProducts'])->name('productos.otherProducts');
Route::post('/productos/deleteProducto', [ProductosController::class, 'deleteProducto'])->name('productos.deleteProducto');
Route::post('/productos/create', [ProductosController::class, 'createProductos'])->name('productos.createProductos');
Route::post('/productos/createOther',[ProductosController::class, 'createOther'])->name('productos.createOther'); 
Route::post('/productos/filterOtherProducto',[ProductosController::class,'filterOtherProducts'])->name('productos.filterOtherProducts');
// ----------------------------- ROUTES DETALLES DE LA VENTA ---------------------------------

Route::post('/details/verVenta',[VentaDetalleController::class, 'verVenta'])->name('details.verVenta');
Route::post('/details/updateProducto',[VentaDetalleController::class, 'updateProducto'])->name('details.updateProducto');
