$(document).ready(function() {
    $(document).on('click', '.ver-venta', function() {
        var ventaId = $(this).data('id');
        $('#detalleVentaModal').modal('show');
        var token = $('meta[name="csrf-token"]').attr('content'); 
        $.ajax({
            url: "/details/verVenta",
            method: "POST",
            data: { ventaId: ventaId, _token: token },
            success: function(data) {

                $('#fechaVenta').text(data.fecha[0].fecha);
                $('#detalleVentaBody').empty();
                $.each(data.productos, function(index, productosArray) {
                    $.each(productosArray, function(index, producto) {
                        var ventaProducto = data.venta.find(v => v.id_producto === producto.id);
                        var ventaxmayorText = ventaProducto.ventaxmayor == 1 ? 'si' : 'no';
                        $('#detalleVentaBody').append('<tr>' +
                            '<td>' + producto.nombre + '</td>' +
                            '<td>' + producto.marca + '</td>' +
                            '<td>' + 's/. ' + ventaProducto.subtotal /ventaProducto.cantidad + '</td>' +
                            '<td>'+ ventaProducto.cantidad+'</td>'+
                           '<td>' + ventaxmayorText + '</td>' +
                            
                            '<td>'+'s/. ' +ventaProducto.subtotal +'</td>' +
                            '</tr>');
                    });
                });
                $('#totalVenta').text('s/. ' + data.fecha[0].total);
            },
            error: function(data){
                alertError( data.responseJSON.message );

            }
        });
    });
    $('#filterForm').submit(function(event) {
        event.preventDefault();
        var token = $('meta[name="csrf-token"]').attr('content'); 
        var fecha = $("#fecha").val();
        var metodoPago = $("#metodoPago").val();
        var total = $("#total").val();
        var clienteDeuda = $("#clienteDeuda").val();
        var estado = $("#estado").val();
        $.ajax({
            url: "/ventas/filterVentas",
            method: "POST",
            data: { 
                fecha: fecha, 
                metodoPago: metodoPago, 
                total: total, 
                clienteDeuda: clienteDeuda, 
                estado: estado,
                _token: token 
            },
            success: function(data) {
                $('#table-cart tbody').empty();
                data.forEach(function(venta) {
                    $('#table-cart tbody').append(
                        '<tr>' +
                            '<td>' + venta.id + '</td>' +
                            '<td>' + venta.fecha + '</td>' +
                            '<td>' + venta.metodo_pago + '</td>' +
                            '<td>' + (venta.estado === 0 ? 'Pagado' : 'Pendiente de Pago') + '</td>'+
                            '<td>' + (venta.cliente_deuda ? venta.cliente_deuda : "") + '</td>' +
                            '<td>' + venta.total + '</td>' +
                            '<td style="text-align: center; vertical-align: middle;">' +
                                '<button class="btn btn-primary btn-sm ver-venta" data-id="' + venta.id + '">' +
                                    '<i class="fas fa-plus-circle" style="color: white;"></i>' +
                                '</button>' +
                                '<button class="btn btn-info btn-sm editar-venta" data-id="' + venta.id + '">' +
                                    '<i class="fas fa-edit" style="color: white;"></i>' +
                                '</button>' +
                                '<button class="btn btn-danger btn-sm eliminar-venta" data-id="' + venta.id + '">' +
                                    '<i class="fas fa-trash-alt" style="color: white;"></i>' +
                                '</button>' +
                            '</td>' +
                        '</tr>'
                    );
                });
            }
        });
    });

    $('button[type="reset"]').click(function(event) {
        event.preventDefault();
        $("#fecha").val("");
        $("#metodoPago").val("");
        $("#total").val("");
        $("#clienteDeuda").val("");
        $("#estado").val("");
        $('#filterForm').submit();
    });
    $(document).on('click', '.eliminar-venta', function() {
            var ventaId = $(this).data('id');
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará la venta permanentemente.',
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
                        url: "/ventas/deleteVenta",
                        method: "POST",
                        data: { 
                            _token: token,
                            id: ventaId
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
    
        $('.editar-venta').click(function() {
            var token = $('meta[name="csrf-token"]').attr('content');
            var venta_id = $(this).data('id');
            
            $.ajax({
                url: '/ventas/searchVenta',
                method: "POST",
                data: {
                    _token: token,
                    id: venta_id
                },
                success: function(response) {
                    var data = response.venta;
        
                    if (data.id && data.cliente_deuda && data.estado && data.metodo_pago !== undefined) {
                        $('#id').val(data.id);
                        $('#estado').val(data.estado).change(); 
                        $('#metodoPagoEditar').val(data.metodo_pago).change();
                        $('#deudor').val(data.cliente_deuda);
                        
                        $('#editarVentaModal').modal('show');
                    } else {
                        console.error('Entro al otro modal');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                }
            });
        });
        $('#editarVentaForm').submit(function(event) {
            event.preventDefault(); 
            var token = $('meta[name="csrf-token"]').attr('content');
            var id = $('#id').val();
            var deudor = $('#deudor').val();
            var metodoPago = $('#metodoPagoEditar').val();
            $.ajax({
                url: '/ventas/updateVenta',
                method: "POST",
                data: {
                    _token: token,
                    id: id,
                    metodo_pago:metodoPago,
                    cliente_deuda:deudor,
                    estado: 0,
                },
                success: function(response) {
                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'Los cambios se guardaron correctamente.',
                        icon: 'success',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Aceptar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload(); 
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error en la petición AJAX:', error);
                }
            });
        });
 
});
