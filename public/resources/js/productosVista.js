$(document).ready(function() {
    $('button[type="reset"]').click(function() {
        $('#nombre').val('');
        $('#marca').val('');
        $('#filterForm').submit();
    });
    $('#filterForm').submit(function(event) {
        event.preventDefault();
        var nombre = $('input[name="nombre"]').val();
        var marca = $('input[name="marca"]').val();
        var token = $('meta[name="csrf-token"]').attr('content'); 
        $.ajax({
            url: "/productos/filterProducto",
            method: "POST",
            data: { 
                _token: token,
                nombreProducto:nombre,
                marca:marca
            }, 
            success: function(data) {

            $('#table-cart tbody').empty();

            $.each(data.productos, function(index, producto) {
                var newRow = "<tr>" +
                             "<td>" + producto.id + "</td>" +
                             "<td>" + producto.nombre + "</td>" +
                             "<td>" + producto.marca + "</td>" +
                             "<td> s/. " + producto.precio_venta + "</td>" +
                             "<td> s/. " + producto.precio_compra + "</td>" +
                             '<td>' + producto.ganancia +  '%</td>' +
                             "<td>" + producto.stock + " unidad(es)</td>" +
                             "<td> " + producto.fecha_vencimiento + "</td>" +
                             "<td style='text-align: center; vertical-align: middle;'>" +
                             "<button class='btn btn-info btn-sm editar-producto' data-id='" + producto.id + "'>" +
                             "<i class='fas fa-edit' style='color: white;'></i></button>" +
                             "<button class='btn btn-danger btn-sm eliminar-producto' data-id='" + producto.id + "'>" +
                             "<i class='fas fa-trash-alt' style='color: white;'></i></button>" +
                             "</td>" +
                             "</tr>";
                $('#table-cart tbody').append(newRow);
            });
            }
        });
    });


    var id; 

    $(document).on('click', '.editar-producto', function() {
        var productoId = $(this).data('id');
        var token = $('meta[name="csrf-token"]').attr('content'); 
        $.ajax({
            url: "/productos/getOneProducto", 
            data: { _token: token, id: productoId},
            method: 'POST',
            success: function(data) {
                id = productoId;
                $('#editarProductoModal tbody').empty();
                $.each(data, function(key, value) {
                    var fila = '<tr>' +
                    '<td><input type="text" class="form-control" style="width: 150px;" value="' + value.nombre + '"></td>' +
                    '<td><input type="text" class="form-control" style="width: 150px;" value="' + value.marca + '"></td>' +
                    '<td><select class="form-control" style="width: 120px;"><option value="' + value.categoria + '">' + value.categoria + '</option><option value="OtraCategoria">OtraCategoria</option><option value="OtraCategoria2">OtraCategoria2</option></select></td>'+
                    '<td><input type="text" class="form-control" style="width: 60px;" value="' + value.precio_venta + '"></td>' +
                    '<td><input type="text" class="form-control" style="width: 60px;" value="' + value.precio_compra + '"></td>' +
                    '<td><input type="date" class="form-control" style="width: 150px;" value="' + value.fecha_vencimiento + '"></td>' +
                    '<td><input type="text" class="form-control" style="width: 60px;" value="' + value.stock + '"></td>' +
                '</tr>';
                    $('#editarProductoModal tbody').append(fila);
                });
                $('#editarProductoModal').modal('show');
            },
            error: function(data) {
                console.log("Error al obtener la información del producto.");
            }
        });
    });

    $('#guardarCambios').click(function() {
        var datos = {};
        $('#editarProductoModal tbody tr').each(function(index, fila) {
            var nombre = $(fila).find('td:eq(0) input').val();
            var marca = $(fila).find('td:eq(1) input').val();
            var categoria = $(fila).find('td:eq(2) select').val();
            var precio_venta = $(fila).find('td:eq(3) input').val();
            var precio_compra = $(fila).find('td:eq(4) input').val();
            var fecha_vencimiento=$(fila).find('td:eq(5) input').val();
            var stock = $(fila).find('td:eq(6) input').val();
            datos[index] = { nombre: nombre, marca: marca, categoria:categoria, precio_venta: precio_venta, precio_compra: precio_compra,fecha_vencimiento:fecha_vencimiento,stock: stock };
        });
        var token = $('meta[name="csrf-token"]').attr('content'); 
        console.log(datos);
        $.ajax({
            url: "/productos/updateProducto", 
            data: { _token: token, id: id, datos: datos }, 
            method: 'POST',
            success: function(data) {
                Swal.fire({
                    title: 'Producto actualizado',
                    text: 'El producto se ha actualizado correctamente',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    
                    $('#editarProductoModal').modal('hide');
                    location.reload()
                }); 
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: 'Hubo un error al actualizar el producto: ' + error,
                    icon: 'error',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Cerrar'
                });
            }
        });
    }); 
    $(document).on('click', '.eliminar-producto', function() {
        var productoId = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el producto permanentemente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    var token = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        url: "/productos/deleteProducto",
                        method: "POST",
                        data: { 
                            _token: token,
                            id: productoId
                        }, 
                        success: function(data) {
                            Swal.fire({
                                title: 'Éxito',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Aceptar'
                            }).then(() => {
                                location.reload();
                            });
                        }
                    });
                }
            });
        });
        $('#addProductBtn').click(function() {
            $('#addProductModal').modal('show');
        });
        $('#addProductForm').submit(function(event) {
            event.preventDefault();

            var formData = {
                nombre: $('#nombre').val(),
                marca: $('#marca').val(),
                precio_venta: $('#precio_venta').val(),
                precio_compra: $('#precio_compra').val(),
                fecha_vencimiento: $('#fecha_vencimiento').val() ,
                stock: $('#stock').val(),
                categoria:$('#categoria').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            };

            $.ajax({
                url: "/productos/create",
                method: "POST",
                data: formData,
                success: function(response) {
                    Swal.fire({
                        title: 'Éxito',
                        text: response.message,
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        $('#addProductModal').modal('hide');
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error',
                        text: xhr.responseJSON.message,
                        icon: 'error',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Cerrar'
                    });
                }
            });
        });
    
});

