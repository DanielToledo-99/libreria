
<meta name="csrf-token" content="{{ csrf_token() }}">
@extends('index')
@section('content')
<div class="container">
    <form id="filterForm" class="mb-3">
        <div class="row">
            <div class="col-md-2">
                <label for="fecha" style="color: white">Fecha:</label>
                <input type="date" class="form-control" id="fecha" name="fecha">
            </div>
            <div class="col-md-2">
                <label for="metodoPago" style="color: white">Método de Pago:</label>
                <select class="form-control" id="metodoPago" name="metodoPago">
                    <option value="">Seleccionar</option>
                    <option value="YAPE">YAPE</option>
                    <option value="EFECTIVO">EFECTIVO</option>
                    <option value="DEUDA">DEUDA</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="total" style="color: white">Total:</label>
                <input type="number" class="form-control" id="total" name="total">
            </div>
            <div class="col-md-2">
                <label for="clienteDeuda" style="color: white">Cliente Deuda:</label>
                <input type="text" class="form-control" id="clienteDeuda" name="clienteDeuda">
            </div>
            <div class="col-md-2">
                <label for="estado" style="color: white">Estado:</label>
                <select class="form-control" id="estado" name="estado">
                    <option value="">Seleccionar</option>
                    <option value="0">Pagado</option>
                    <option value="1">Pendiente de Pago</option>
                </select>
            </div>
            <div class="col-md-2">
                <label>&nbsp;</label><br>
                <button type="submit" class="btn btn-primary">Filtrar</button>
                <button type="reset" class="btn btn-secondary">Limpiar</button>
            </div>
        </div>
    </form>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <h3 style="color: white">Resumen de Ventas Realizadas</h3>
            <table  class="table table-bordered table-sm" id="table-cart">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Fecha</th>
                        <th>Método de Pago</th>
                        <th>Estado</th>
                        <th>Cliente Deuda </th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach($ventas as $venta)
                    <tr>
                        <td >{{ $venta->id }}</td>
                        <td >{{ $venta->fecha }}</td>
                        <td>{{ $venta->metodo_pago }}</td>
                        <td>
                            @if($venta->estado == 0)
                                Pagado
                            @elseif($venta->estado == 1)
                                Pendiente de Pago
                            @endif
                        </td>
                        <td>{{$venta->cliente_deuda}}</td>
                        <td > s/. {{ $venta->total }}</td>
                        <td style="text-align: center; vertical-align: middle;">
                            <button class="btn btn-primary btn-sm ver-venta" data-id="{{ $venta->id }}">
                                <i class="fas fa-plus-circle" style="color: white;"></i> 
                            </button>
                            <button class="btn btn-info btn-sm editar-venta" data-id="{{ $venta->id }}">
                                <i class="fas fa-edit" style="color: white;"></i> 
                            </button>
                            <button class="btn btn-danger btn-sm eliminar-venta" data-id="{{ $venta->id }}">
                                <i class="fas fa-trash-alt" style="color: white;"></i> 
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-between" >
            @if ($ventas->previousPageUrl())
                <a href="{{ $ventas->previousPageUrl() }}" class="btn btn-primary">Anterior</a>
            @endif
        
            @if ($ventas->nextPageUrl())
                <a href="{{ $ventas->nextPageUrl() }}" class="btn btn-primary">Siguiente</a>
            @endif
        </div>
    </div>
</div>
@endsection
@section('modals')
<div class="modal fade" id="detalleVentaModal" tabindex="-1" role="dialog" aria-labelledby="detalleVentaModalLabel" aria-hidden="true">
    <div style="max-width: 70%" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleVentaModalLabel">Detalles de la Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h6>Fecha de venta: <span id="fechaVenta"></span></h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>

                            <th>Producto</th>
                            <th>Descripcion</th>
                            <th>Precio</th>
                            <th>Cantidad</th>
                            <th>Venta x Mayor? </th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody id="detalleVentaBody">
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-md-12">
                        <h4 class="text-right">Total: <span id="totalVenta"></span></h4>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="editarVentaModal" tabindex="-1" role="dialog" aria-labelledby="editarVentaModalLabel" aria-hidden="true">
    <div style="max-width: 70%" class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editarVentaModalLabel">Editar Venta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editarVentaForm">
                    <input type="hidden" id="id" name="id"> 
                    <div class="form-group">
                        <label for="deudor">Deudor:</label>
                        <input type="text" class="form-control" id="deudor" name="deudor" readonly>
                    </div>

                    <div class="form-group">
                        <label for="estado">Estado:</label>
                        <select class="form-control" id="estado" name="estado">
                            <option value="1">Pendiente de Pago</option>
                            <option value="0">Pagado</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="metodoPagoEditar">Método de Pago:</label>
                        <select class="form-control" id="metodoPagoEditar" name="metodoPagoEditar">
                            <option value="YAPE">YAPE</option>
                            <option value="EFECTIVO">EFECTIVO</option>
                            <option value="DEUDA">DEUDA</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <button type="submit" class="btn btn-success">Editar Productos</button> 
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('resources/js/ventas.js') }}?t={{ time() }}"></script>
@endsection 
 