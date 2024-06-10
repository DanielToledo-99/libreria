<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\ProductosController;
use App\Models\Venta;
use App\Models\VentaProducto;
use App\Models\Producto;
class VentasController extends Controller
{
    public function home()
    {
        try {
            $productosController = app(ProductosController::class);
            $productos = $productosController->verProductos();
            return view("ventas.viewVentas", ['productos' => $productos]);
        } catch (\Exception $e) {
            return back()->withError('Error al obtener los datos: ' . $e->getMessage());
        }
    }
    public function guardarVenta(Request $request) {
        try {
            $datosVenta = $request->all();
    
            foreach ($datosVenta['productos_seleccionados'] as $productoData) {
                $producto = Producto::find($productoData['id']);
                if ($producto) {
                    if ($producto->stock < $productoData['cantidad']) {
                        return response()->json(['error' => 'La cantidad solicitada del producto ' . $producto->nombre . ' excede la cantidad disponible en inventario.'], 400);
                    }
                } else {
                    return response()->json(['error' => 'Producto no encontrado'], 404);
                }
            }
                
            $venta = new Venta();
            $venta->fecha = now();
            $venta->total = $datosVenta['total'];
            $venta->metodo_pago = $datosVenta['metodo_pago'];
            $venta->cliente_deuda = !empty($datosVenta['cliente_deuda']) ? $datosVenta['cliente_deuda'] : null;
            $venta->estado = empty($datosVenta['cliente_deuda']) ? 0 : 1;
            $venta->save();
            foreach ($datosVenta['productos_seleccionados'] as $productoData) {
                $ventaProducto = new VentaProducto();
                $ventaProducto->id_venta = $venta->id;
                $ventaProducto->id_producto = $productoData['id'];
                $ventaProducto->cantidad = $productoData['cantidad'];
                $ventaProducto->subtotal = $productoData['total'];
                $ventaProducto->ventaxmayor = $productoData['ventaxmayor'];
                $ventaProducto->save();
    
                $producto = Producto::find($productoData['id']);
                if ($producto) {
                    $producto->stock -= $ventaProducto->cantidad;
                    $producto->save();
                }
            }
    
            return response()->json(['message' => 'Venta guardada con éxito'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al guardar la venta: ' . $e->getMessage()], 500);
        }
    }
    public function viewVentas(){
        try {

            $ventas = $this->getVentas();
            return view("ventas.ventas", ['ventas' => $ventas]);
        } catch (\Exception $e) {
            return back()->withError('Error al obtener los datos: ' . $e->getMessage());
        }
    }
    public function getVentas()
    {
        try {
            $ventas = DB::table('ventas')->orderBy('created_at', 'desc')->paginate(10);
            return $ventas;
        } catch (\Exception $e) {
            return back()->withError('Error al obtener las ventas: ' . $e->getMessage());
        }
    }
    public function searchVenta(Request $request){
        try{
            $id = $request->input('id');
            $venta = DB::table('ventas')->where('id', $id)->first();
            if($venta) {
                return response()->json(['venta' => $venta], 200);
            } else {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener la venta: ' . $e->getMessage()], 500);
        }
    }
    public function updateVenta(Request $request){
        try{
            $id = $request->input('id');
            $metodo_pago = $request->input('metodo_pago');
            $cliente_deuda = $request->input('cliente_deuda');
            $estado = $request->input('estado');
            
            $venta = DB::table('ventas')->where('id', $id)->first();
            
            if($venta) {
                $metodo_pago = $metodo_pago ?: $venta->metodo_pago;
                $cliente_deuda = $cliente_deuda ?: $venta->cliente_deuda;
                $estado = $estado ?: $venta->estado;
                
                DB::table('ventas')->where('id', $id)->update([
                    'metodo_pago' => $metodo_pago,
                    'cliente_deuda' => $cliente_deuda,
                    'estado' => $estado
                ]);
                
                return response()->json(['message' => 'Venta actualizada correctamente'], 200);
            } else {
                return response()->json(['error' => 'Venta no encontrada'], 404);
            }
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar la venta: ' . $e->getMessage()], 500);
        }
    }
    
    public function filterVentas(Request $request){
        try{
            $query = DB::table('ventas');
            if ($request->fecha) {
                $query->where('fecha', $request->fecha);
            }
            if ($request->metodoPago) {
                $query->where('metodo_pago', $request->metodoPago);
            }
            if ($request->total) {
                $query->where('total', $request->total);
            }
            if ($request->clienteDeuda) {
                $query->where('cliente_deuda', $request->clienteDeuda);
            }
            if ($request->estado) {
                $query->where('estado', $request->estado);
            }
            $ventas = $query->get();
            return response()->json($ventas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al filtrar : ' . $e->getMessage()], 500);
        }
    }
    public function deleteVenta(Request $request)
    {
        
        try {
            $id = $request->input('id');
            $venta = Venta::findOrFail($id);
            $ventasProductos = VentaProducto::where('id_venta', $venta->id)
                ->get();
            if ($ventasProductos->isNotEmpty()) {
                foreach ($ventasProductos as $ventaProducto) {
                    $ventaProducto->delete();
                }
            }
            $venta->delete();
            return response()->json([
                'message' => 'Venta eliminada correctamente'
            ], 200);
        } catch (\Exception $e) {
            return back()->withError('Error al intentar eliminar el producto: ' . $e->getMessage());
        }
    }

}