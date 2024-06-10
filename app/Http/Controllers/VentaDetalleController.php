<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class VentaDetalleController extends Controller
{

    public function verVenta(Request $request){
        try {
            $id = $request->input('ventaId');
            $ventaFecha = DB::table('ventas')
                ->where('id',$id )
                ->get()
                ->toArray();
            $venta = DB::table('venta_producto')
                ->where('id_venta', $id)
                ->get()
                ->toArray();
        
            if ($venta) {
                $productos = [];
                foreach ($venta as $ventaProducto) {
                    $producto = DB::table('productos')
                        ->where('id', $ventaProducto->id_producto)
                        ->get();
                    
                    if ($producto) {
                        $productos[] = $producto->toArray();
                    }
                }
        
                return response()->json([
                    'venta' => $venta,
                    'productos' => $productos,
                    'fecha' => $ventaFecha
                ], 200);
            } else {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateProducto(Request $request){
        try{
            $idVenta = $request->input('id');
            $idProductoNuevo = $request->input('id_producto');
            DB::table('venta_producto')
                ->where('id_venta', $idVenta)
                ->update(['id_producto' => $idProductoNuevo]);
            return response()->json(['message' => 'Detalles de venta actualizados correctamente'], 200);
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar los detalles de la venta: ' . $e->getMessage()], 500);
        }
    }
    
}