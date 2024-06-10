@extends('index')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h3 style="color: white">Resumen de Compra</h3>
            <table class="table table-bordered table-sm" id="table-cart">
                <thead class="thead-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Venta x Mayor? </th>
                        <th>Helada?</th>
                        <th>Total</th>
                        <th></th>
                        
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4">Total Venta</th>
                        <th id="total-general">0.00</th>
                    </tr>
                </tfoot>
            </table>
                <div class="form-group">
        <label style="color:white" for="metodo-pago">Método de Pago:</label>
        <select class="form-control" id="metodo-pago">
            <option value="YAPE">YAPE</option>
            <option value="EFECTIVO">Efectivo</option>
            <option value="DEUDA">Deuda</option>
        </select>
    </div>
    <div class="form-group" id="clienteDeudor" style="display: none;">
        <label style="color:white" for="nombre-cliente-deudor">Nombre del Cliente Deudor:</label>
        <input type="text" class="form-control" id="nombre-cliente-deudor">
    </div>
            <div id="efectivo-section" style="display: none;">
                <div class="form-group">
                    <label style="color:white" for="monto-cancelar">Monto a Cancelar:</label>
                    <input type="number" class="form-control" id="monto-cancelar">
                </div>
                <div>
                    <label style="color:rgb(255, 255, 255)">Vuelto:</label>
                    <span id="vuelto" style="color:rgb(255, 254, 254)">0.00</span>
                </div>
            </div>
            <button id="guardar-venta-btn" class="btn btn-primary" style="background-color: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">GUARDAR VENTA</button>

        </div>
        <div class="col-md-6">
            <div style="max-width: 100%" class="container">
                <div class="card-body">
                    <h3 style="color: white" class="card-title">Buscar Producto</h3>
                    <form id="form-buscar-producto">
                        <div class="row">
                            <div class="col-10">
                                <input type="text" class="form-control" placeholder="Buscar producto" name="search" id="input-buscar-producto">
                            </div>
                            <div class="col-2">
                                <button class="btn btn-primary" type="button" id="btn-buscar">Buscar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <table id="table_products"class="table table-bordered table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descricpcion</th>
                            <th>Precio</th>
                            <th>Stock</th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->marca }}</td>
                            <td>{{ $producto->precio_venta }}</td>
                            <td>
                                @if($producto->stock == 0)
                                <span class="text-danger">Sin unidades disponibles</span>
                                @elseif($producto->stock == 1)
                                <span class="text-warning">Última unidad</span>
                                @else
                                <span class="text-success"> {{ $producto->stock }} unidades</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm add-to-cart" data-id="{{ $producto->id }} " data-category="{{ $producto->categoria }}">
                                    <i class="fas fa-shopping-cart" style="color: white;"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                
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

@endsection




@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('resources/js/productos.js') }}?t={{ time() }}"></script>
@endsection
