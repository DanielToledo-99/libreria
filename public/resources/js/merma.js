$(document).ready(function() {
    $('#selectOption').change(function() {
        var selectedOption = $(this).val();
        var token = $('meta[name="csrf-token"]').attr('content'); 
        $('h3').text('Otros Productos -- ' + selectedOption.charAt(0).toUpperCase() + selectedOption.slice(1));
        if (selectedOption === "BONIFICACIONES") {
            $('#table-merma').hide();
            $('#table-bonificaciones').show();
            $('#addProductModal .modal-title').text('Añadir Producto de Bonificaciones');
            $('#fechaVencimientoGroup').show();
        } else if (selectedOption === "MERMA") {
            $('#table-merma').show();
            $('#table-bonificaciones').hide();
            $('#addProductModal .modal-title').text('Añadir Producto de Merma');
            $('#fechaVencimientoGroup').hide();
        } else {
            $('#table-merma').hide();
            $('#table-bonificaciones').hide();
        }
        $.ajax({
            url: '/productos/getother', 
            method: 'POST',
            data: { _token: token, type: selectedOption }, 
            success: function(response) {
                actualizarTabla(response.data, selectedOption);
            },
            error: function(xhr, status, error) {
                console.error('Error al obtener los datos: ' + error);
            }
        });
    });

    function actualizarTabla(data, type) {
        var table = type === 'BONIFICACIONES' ? '#table-bonificaciones tbody' : '#table-merma tbody';
        $(table).empty();
        $.each(data, function(index, producto) {
            if (producto && producto.id && producto.nombre && producto.descripcion && producto.cantidad !== undefined) {
                var fila = '<tr>' +
                    '<td>' + producto.id + '</td>' +
                    '<td>' + producto.nombre + '</td>' +
                    '<td>' + producto.descripcion + '</td>' +
                    '<td>' + producto.cantidad + '</td>' +
                    (type === 'BONIFICACIONES' ? '<td>' + producto.fechaVencimiento + '</td>' : '') +
                    "<td style='text-align: center; vertical-align: middle;'>" +
                    "<button class='btn btn-info btn-sm editar-producto' data-id='" + producto.id + "'>" +
                    "<i class='fas fa-edit' style='color: white;'></i></button>" +
                    "<button class='btn btn-danger btn-sm eliminar-producto' data-id='" + producto.id + "'>" +
                    "<i class='fas fa-trash-alt' style='color: white;'></i></button>" +
                    "</td>" +
                    '</tr>';
                $(table).append(fila);
            } else {
                console.warn('Producto inválido encontrado:', producto);
            }
        });
        $('#pagination').empty();
        if (data.prev_page_url) {
            $('#pagination').append('<a href="' + data.prev_page_url + '" class="btn btn-primary">Anterior</a>');
        }
        if (data.next_page_url) {
            $('#pagination').append('<a href="' + data.next_page_url + '" class="btn btn-primary">Siguiente</a>');
        }
    }

    $('#addProductBtn').click(function() {
        var selectedOption = $('#selectOption').val();
        if (selectedOption === 'MERMA' || selectedOption === 'BONIFICACIONES') {
            $('#addProductModal').modal('show');
        } else {
            console.log('Seleccione una opción válida');
        }
    });

    $('#addProductForm').submit(function(event) {
        event.preventDefault(); 

        var token = $('meta[name="csrf-token"]').attr('content');
        var formData = {
            nombre: $('#nombre').val(),
            descripcion: $('#marca').val(),
            cantidad: $('#stock').val(),
            type: $('#selectOption').val(),
            _token: token
        };
        if ($('#selectOption').val() === 'BONIFICACIONES') {
            formData.fechaVencimiento = $('#fechaVencimiento').val();
        }

        $.ajax({
            url: '/productos/createOther', 
            method: 'POST',
            data: formData,
            success: function(response) {
                $('#addProductModal').modal('hide'); 
                $('#addProductForm')[0].reset(); 

                Swal.fire({
                    icon: 'success',
                    title: 'Producto añadido',
                    text: 'Producto añadido correctamente',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function() {
                    location.reload(); 
                });
            },
            error: function(xhr, status, error) {
                console.error('Error al añadir el producto: ' + error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al añadir el producto',
                });
            }
        });
    });

    $('#filterForm').submit(function(event) {
        event.preventDefault();

        const nombre = $('input[name="nombre"]').val().trim();
        const descripcion = $('input[name="marca"]').val().trim();
        const selectOption = $('#selectOption').val();
        var token = $('meta[name="csrf-token"]').attr('content');
        if ( selectOption) {
            $.ajax({
                url: '/productos/filterOtherProducto', 
                method: 'POST',
                data: { _token: token, type: selectOption, nombreProducto:nombre, descripcion:descripcion }, 
                success: function(response) {
                    actualizarTabla(response.data, selectOption);
                },
                error: function(xhr, status, error) {
                    console.error('Error al obtener los datos: ' + error);
                }
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Advertencia',
                text: 'Por favor seleccione un tipo de productos.',
            });
        }
    });

    $('#selectOption').trigger('change');
});
