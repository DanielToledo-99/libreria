@extends('index')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div style="display: flex; align-items: center;">
                <h3 style="color: white; ">Gestion de Productos</h3>
                <button id="addProductBtn" style="
                    background-color: green;
                    border-color: green; 
                    margin-left: 780px; /* Ajusta según sea necesario */
                    margin-bottom: 20px;" type="success" class="btn btn-primary">
                    Añadir Producto
                </button>
            </div>
            <form id="filterForm">
                <div class="form-row mb-3">
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Nombre" name="nombre">
                    </div>
                    <div class="col">
                        <input type="text" class="form-control" placeholder="Descripcion" name="marca">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filtrar</button>
                        <button type="reset" class="btn btn-secondary">Limpiar</button>

                    </div>
                </div>
            </form>
            <table  class="table table-bordered table-sm" id="table-cart">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Precio Venta</th>
                        <th>Precio Compra </th>
                        <th>Ganancia</th>
                        <th>Stock</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($productos as $producto)
                    <tr>
                        <td >{{ $producto->id }}</td>
                        <td >{{ $producto->nombre }}</td>
                        <td >{{ $producto->marca }}</td>
                        <td > s/. {{ $producto->precio_venta }}</td>
                        <td>s/. {{ $producto->precio_compra }}</td>
                        <td>{{ $producto->ganancia }}%</td>
                        <td>{{$producto->stock}} unidad(es) </td>
                        <td>{{ $producto->fecha_vencimiento }}</td>
                        
                        
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn btn-info btn-sm editar-producto" data-id="{{ $producto->id }}">
                                <i class="fas fa-edit" style="color: white;"></i> 
                            </button>
                            <button class="btn btn-danger btn-sm eliminar-producto" data-id="{{ $producto->id }}">
                                <i class="fas fa-trash-alt" style="color: white;"></i> 
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between" >
            @if ($productos->previousPageUrl())
                <a href="{{ $productos->previousPageUrl() }}" class="btn btn-primary">Anterior</a>
            @endif
        
            @if ($productos->nextPageUrl())
                <a href="{{ $productos->nextPageUrl() }}" class="btn btn-primary">Siguiente</a>
            @endif
        </div>
    </div>
</div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="editarProductoModal" tabindex="-1" role="dialog" aria-labelledby="editarProductoModalLabel" aria-hidden="true">
    <div  style="max-width: 70%" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarProductoModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Categoria</th>
                            <th>Precio Venta</th>
                            <th>Precio Compra </th>
                            <th>Fecha de Vencimiento</th>
                            <th>Stock</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarCambios">Guardar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="crearProducto" aria-hidden="true">
    <div style="max-width: 70%" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearProducto">Crear Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addProductForm">
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="marca">Descripcion</label>
                        <input type="text" class="form-control" id="marca" name="marca" required>
                    </div>
                    <div class="form-group">
                        <label for="categoria">Categoria</label>
                        <input type="text" class="form-control" id="categoria" name="categoria" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio Venta </label>
                        <input type="number" step="0.01" class="form-control" id="precio_venta" name="precio_venta" required>
                    </div>
                    <div class="form-group">
                        <label for="precio">Precio Compra </label>
                        <input type="number" step="0.01" class="form-control" id="precio_compra" name="precio_compra" required>
                    </div>
                    <div class="form-group">
                        <label for="fecha_vencimiento">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" >
                    </div>
                    <div class="form-group">
                        <label for="stock">Stock</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary mr-2">Guardar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>

               

        </div>
    </div>
</div>


@endsection

@section('js')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('resources/js/productosVista.js') }}?t={{ time() }}"></script>

@endsection
