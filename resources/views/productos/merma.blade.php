@extends('index')
@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div style="display: flex; align-items: center;">
                <h3 style="color: white; margin-right: auto;">Otros Productos -- </h3>
                <select id="selectOption" class="form-control form-control-sm" style="width: 150px; margin-right: 20px;">
                    <option value="">Selecciona</option>
                    <option value="MERMA">Merma</option>
                    <option value="BONIFICACIONES">Bonificaciones</option>
                </select>
                <button id="addProductBtn" style="background-color: green; border-color: green;" type="success" class="btn btn-primary">
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
            <table id="table-merma" class="table table-bordered table-sm">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <table id="table-bonificaciones" class="table table-bordered table-sm" style="display:none;">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Fecha de Vencimiento</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="pagination" class="d-flex justify-content-between mt-3"></div>
        </div>
    </div>
</div>
@endsection

@section('modals')
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="crearProducto" aria-hidden="true">
    <div style="max-width: 70%" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="crearProducto">Añadir Producto</h5>
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
                        <label for="stock">Cantidad</label>
                        <input type="number" class="form-control" id="stock" name="stock" required>
                    </div>
                    <div class="form-group" id="fechaVencimientoGroup" style="display:none;">
                        <label for="fechaVencimiento">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" id="fechaVencimiento" name="fechaVencimiento">
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="{{ asset('resources/js/merma.js') }}?t={{ time() }}"></script>
@endsection
