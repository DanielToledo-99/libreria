<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Producto;
use App\Models\otherProducts;
use Illuminate\Support\Facades\DB;
class ProductosController extends Controller
{
    public function verProductos(){
        try {
            $Productos = DB::table('productos')->paginate(10); 
            return $Productos;
        } catch (\Exception $e) {
            return back()->withError('Error al obtener los productos: ' . $e->getMessage());
        }
    }

    public function getOneProducto(Request $request){
        try{
            $id = $request->input('id');
            $producto = DB::table('productos')->where('id', $id)->first();
            if($producto) {
                return response()->json(['producto' => $producto], 200);
            } else {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
        }
        catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener el Producto: ' . $e->getMessage()], 500);
        }
    }
    public function updateProducto(Request $request){
        try {
            $id = $request->input('id');
            $datos = $request->input('datos');
            $producto = Producto::find($id);
            if ($producto) {
                $producto->nombre = $datos[0]['nombre'];
                $producto->marca = $datos[0]['marca'];
                $producto->categoria = $datos[0]['categoria'];
                $producto->precio_venta = $datos[0]['precio_venta'];
                $producto->precio_compra = $datos[0]['precio_compra'];
                $producto->fecha_vencimiento = $datos[0]['fecha_vencimiento'];
                $producto->stock = $datos[0]['stock'];
                $producto->save();

                return response()->json(['message' => 'Producto actualizado correctamente'], 200);
            } else {
                return response()->json(['error' => 'Producto no encontrado'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el producto: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function filterProducts(Request $request){
        try {
            $nombreProducto = $request->input('nombreProducto');
            $productosFilter = DB::table('productos')
                ->where('nombre', 'like', '%' . $nombreProducto . '%');
            if ($request->has('marca')) {
                $marcaProducto = $request->input('marca');
                $productosFilter->where(function($query) use ($marcaProducto) {
                    $query->where('marca', 'like', '%' . $marcaProducto . '%')
                          ->orWhere('nombre', 'like', '%' . $marcaProducto . '%');
                });
            }
    
            $productosFilter = $productosFilter->get();
    
            return response()->json(['productos' => $productosFilter], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al filtrar los productos: ' . $e->getMessage()], 500);
        }
    }
    
    public function viewProductos(){
        try {
            $productos = $this->verProductos();
            return view("productos.viewProductos", ['productos' => $productos]);
        } catch (\Exception $e) {
            return back()->withError('Error al obtener los datos: ' . $e->getMessage());
        }
    }

    public function other(){
        try{
            return view("productos.merma");
        }catch (\Exception $e) {
            return back()->withError('Error al obtener los datos: ' . $e->getMessage());
        }
    }


    public function deleteProducto(Request $request)
    {
        try {
            $id = $request->input('id');
            $producto = Producto::findOrFail($id);
            $producto->delete();
            return response()->json([
                'message' => 'Producto eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return back()->withError('Error al intentar eliminar el producto: ' . $e->getMessage());
        }
    }
    public function createProductos(Request $request)
    {
        try {
            $request->validate([
                'nombre' => 'required|string|max:255',
                'marca' => 'required|string|max:255',
                'precio_compra' => 'required|numeric|min:0',
                'precio_venta' => 'required|numeric|min:0',
                'categoria' => 'required|string|max:255',
                'stock' => 'required|integer|min:0',
            ]);
    
            $producto = new Producto();
            $producto->nombre = $request->input('nombre');
            $producto->marca = $request->input('marca');
            $producto->precio_compra = $request->input('precio_compra');
            $producto->precio_venta = $request->input('precio_venta');
            $producto->categoria = $request->input('categoria');
            $producto->ventaxmayor = $request->input('ventaxmayor');
            $ganancia = (($request->input('precio_venta') - $request->input('precio_compra')) / $request->input('precio_compra')) * 100;
            $producto->ganancia = $ganancia;
            $producto->fecha_vencimiento = $request->input('fecha_vencimiento');
            $producto->stock = $request->input('stock');
            $producto->save();
    
            return response()->json([
                'message' => 'Producto creado correctamente',
                'producto' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function otherProducts(Request $request){
        try {
            $type = $request->input('type');
            $productosFilter = DB::table('otherproducts')
                ->where('type', $type)
                ->paginate(10); 
            return $productosFilter;
        }
        catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al obtener los otros productos: ' . $e->getMessage()
            ], 500);
        }
    }


    public function createOther(Request $request)
    {
        try {
            $producto = OtherProducts::create([
                'nombre' => $request->input('nombre'),
                'descripcion' => $request->input('descripcion'),
                'cantidad' => $request->input('cantidad'),
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'Producto creado correctamente',
                'producto' => $producto
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el producto: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deleteOther(Request $request)
    {
        try {
            OtherProducts::where('id', $request->input('id'))->delete();
            return response()->json([
                'message' => 'Producto eliminado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al borrar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOther(Request $request)
    {
        try {
            $data = $request->all();
            OtherProducts::where('id', $request->input('id'))->update($data);
            return response()->json([
                'message' => 'Producto actualizado correctamente'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al actualizar el producto: ' . $e->getMessage()
            ], 500);
        }
    }

}