$(document).ready(function() {

    // Function to calculate the total for a row
    function calcularTotalFila(fila) {
        var quantity = parseInt(fila.find('.quantity').val());
        var price = parseFloat(fila.find('.price input').val());
        var isHalfUnit = fila.find('.bebida-check').is(':checked');
        
        var total = price * quantity + (isHalfUnit ? quantity * 0.5 : 0);
        fila.find('.total').text(total.toFixed(2));
    }

    // Function to calculate the overall total
    function calcularTotalGeneral() {
        var totalGeneral = 0;
        $('#table-cart tbody tr').each(function() {
            var totalProducto = parseFloat($(this).find('.total').text());
            totalGeneral += totalProducto;
        });
        $('#total-general').text(totalGeneral.toFixed(2));
    }

    $('#table_products').on('click', '.add-to-cart', function() {
        var productId = $(this).data('id');
        var productName = $(this).closest('tr').find('td:eq(1)').text();
        var productPrice = parseFloat($(this).closest('tr').find('td:eq(3)').text());
        var productStock = $(this).closest('tr').find('td:eq(4)').text();
        var productCategory = $(this).data('category');
    
        if (productStock.includes('Sin unidades disponibles')) {
            Swal.fire({
                title: 'Producto sin stock',
                text: 'Este producto no tiene unidades disponibles',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Confirmar'
            });
            return;
        }

        var newRow = $('<tr>').attr('data-id', productId).attr('data-original-price', productPrice);
        newRow.append(
            $('<td>').text(productName),
            $('<td>').addClass('price').append(
                $('<input>').attr({ type: 'text', class: 'form-control', value: productPrice.toFixed(2), readonly: true })
            ),
            $('<td>').addClass('text-center').append(
                $('<input>').attr({ type: 'number', class: 'form-control quantity', value: 1 })
            ),
            $('<td>').addClass('text-center').append(
                $('<input>').attr({ type: 'checkbox', class: 'form-check-input ventaxmayor-check' })
            )
        );

        if (productCategory === 'BEBIDA') {
            newRow.append(
                $('<td>').addClass('text-center').append(
                    $('<input>').attr({ type: 'checkbox', class: 'form-check-input bebida-check' })
                )
            );
        } else {
            newRow.append(
                $('<td>')
            );
        }
        newRow.append(
            $('<td>').addClass('total').text(productPrice.toFixed(2)),
            $('<td>').append(
                $('<button>').addClass('btn btn-danger btn-sm remove-from-cart').append(
                    $('<i>').addClass('fas fa-trash-alt')
                )
            )
        );

        $('#table-cart tbody').append(newRow);
    
        calcularTotalGeneral();
    
        sessionStorage.setItem('cart', $('#table-cart tbody').html());
    });

    $('#table-cart').on('change', '.bebida-check', function() {
        var fila = $(this).closest('tr');
        calcularTotalFila(fila);
        calcularTotalGeneral();
        sessionStorage.setItem('cart', $('#table-cart tbody').html());
    });

    $('#table-cart').on('input', '.quantity', function() {
        var fila = $(this).closest('tr');
        calcularTotalFila(fila);
        calcularTotalGeneral();
    });

    $('#table-cart').on('click', '.remove-from-cart', function() {
        $(this).closest('tr').remove();
        calcularTotalGeneral();
        sessionStorage.setItem('cart', $('#table-cart tbody').html());
    });

    $('#metodo-pago').change(function() {
        if ($(this).val() === 'EFECTIVO') {
            $('#efectivo-section').show();
        } else {
            $('#efectivo-section').hide();
        }
        if ($(this).val() === 'DEUDA') {
            $('#clienteDeudor').show();
        } else {
            $('#clienteDeudor').hide();
        }
    });

    $('#monto-cancelar').on('input', function() {
        var montoCancelar = parseFloat($(this).val());
        var totalGeneral = parseFloat($('#total-general').text());
        var vuelto = montoCancelar - totalGeneral;
        $('#vuelto').text(vuelto.toFixed(2));
    });

    $('#btn-buscar').click(function() {
        var token = $('meta[name="csrf-token"]').attr('content'); 
        var searchValue = $('#input-buscar-producto').val();
        if (searchValue.trim() !== '') {
            $.ajax({
                url: "/productos/filterProducto",
                method: "POST",
                data: { nombreProducto: searchValue, _token: token },
                success: function(data) {
                    updateTable(data);
                },
                error: function(data) {
                    alertError(data.responseJSON.message);
                }
            });
        } else {
            $.ajax({
                url: "/productos/verProductos",
                method: "GET",
                data: { _token: token },
                success: function(data) {
                    updateTable(data);
                },
                error: function(data) {
                    alertError(data.responseJSON.message);
                }
            });
        }
    });

    function updateTable(data) {
        var tbody = $('#table_products tbody');
        tbody.empty(); 

        $.each(data.productos, function(index, producto) {
            var stockText;
            if (producto.stock == 0) {
                stockText = '<span class="text-danger">Sin unidades disponibles</span>';
            } else if (producto.stock == 1) {
                stockText = '<span class="text-warning">Última unidad</span>';
            } else {
                stockText = '<span class="text-success">' + producto.stock + ' unidades</span>';
            }

            var row = $('<tr>').append(
                $('<td>').text(producto.id),
                $('<td>').text(producto.nombre),
                $('<td>').text(producto.marca),
                $('<td>').text(producto.precio_venta),
                $('<td>').html(stockText),
                $('<td>').append(
                    $('<button>').addClass('btn btn-primary btn-sm add-to-cart').attr('data-id', producto.id)
                    .attr('data-category', producto.categoria)
                    .append(
                        $('<i>').addClass('fas fa-shopping-cart').css('color', 'white')
                    )
                )
            );

            tbody.append(row);
        });

        var pagination = $('.d-flex.justify-content-between');
        pagination.empty();
        if (data.previousPageUrl) {
            pagination.append($('<a>').attr('href', data.previousPageUrl).addClass('btn btn-primary').text('Anterior'));
        }
        if (data.nextPageUrl) {
            pagination.append($('<a>').attr('href', data.nextPageUrl).addClass('btn btn-primary').text('Siguiente'));
        }
    }

    $('#guardar-venta-btn').click(function() {
        var metodoPago = $('#metodo-pago').val();
        var clienteDeudor = $('#nombre-cliente-deudor').val();
        var totalVenta = $('#total-general').text();
        var productosSeleccionados = [];
    
        $('#table-cart tbody tr').each(function() {
            var producto = {
                id: $(this).data('id'),
                cantidad: $(this).find('.quantity').val(),
                total: $(this).find('.total').text(),
                ventaxmayor: $(this).find('.ventaxmayor-check').is(':checked') ? 1 : 0, 
            };
            productosSeleccionados.push(producto);
        });
        console.log(productosSeleccionados);
        var token = $('meta[name="csrf-token"]').attr('content'); 
        $.ajax({
            url: "/ventas/guardarVenta",
            method: "POST",
            data: {
                _token: token,
                metodo_pago: metodoPago,
                cliente_deuda: clienteDeudor,
                total: Number(totalVenta),
                productos_seleccionados: productosSeleccionados
            },
            success: function(data) {
                $('#table-cart tbody').empty();
                $('#total-general').text('0.00');
                sessionStorage.clear(); 
                Swal.fire({
                    title: 'Venta guardada con éxito',
                    text: 'La venta se ha guardado correctamente.',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error',
                    text: xhr.responseJSON.error || 'Error al guardar la venta.',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Cerrar'
                });
                console.error(xhr.responseText);
            }
        });
    });

    $('#table-cart').on('input', '.quantity', function() {
        var fila = $(this).closest('tr');
        calcularTotalFila(fila);
        calcularTotalGeneral();
    });

    $('#table-cart').on('change', '.ventaxmayor-check', function() {
        var $row = $(this).closest('tr');
        var $priceInput = $row.find('.price input');
        var originalPrice = parseFloat($row.data('original-price'));
    
        if ($(this).is(':checked')) {
            var discountPrice = originalPrice * 0.5;
            $priceInput.val(discountPrice.toFixed(2));
            $priceInput.prop('readonly', false); // Make the input editable
        } else {
            $priceInput.val(originalPrice.toFixed(2));
            $priceInput.prop('readonly', true); // Make the input read-only
        }
    
        calcularTotalFila($row);
        calcularTotalGeneral();
    });

    // Make price editable when ventaxmayor is checked
    $('#table-cart').on('input', '.price input', function() {
        var fila = $(this).closest('tr');
        calcularTotalFila(fila);
        calcularTotalGeneral();
    });
});
