    <!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />

    <title>Sistema de Control de Ventas</title>
    <!-- ICONO -->
    {{-- <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('resources/image/diamante_icon.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('resources/image/diamante_icon.png') }}"> --}}

    <!-- BOOTSTRAP  -->
    <link href="{{ asset('resources/css/cdn/bootstrap.min.css') }}" rel="stylesheet">

    <!-- CUSTOM -->
    <link href="{{ asset('resources/css/index.css') }}?t={{ time() }}" rel="stylesheet">

    <!-- BOOTSTRAP SELECT -->
    <link href="{{ asset('resources/css/cdn/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Font Awesome -->
    <!-- <script src="https://use.fontawesome.com/055674d01b.js"></script> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></link> -->

    @yield('css')

    </head>
    <body style="height: 100vh;">
    @include('loading')
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="{{ route('ventas.home') }}">Sistema de Control de Ventas</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link" href="{{ route('ventas.viewVentas') }}">Ventas</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown"
                  aria-haspopup="true" aria-expanded="false">
                  Productos
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="{{ route('productos.viewProductos') }}">Ver Productos</a>
                  <a class="dropdown-item" href="{{ route('productos.other') }}">Otros Productos</a>
                </div>
              </li>
        </div>
    </nav>
    
    <div class="container">
        @yield('content')
    </div>
    @yield('modals')
    </body>

    <!-- jQuery CDN - Slim version (=without AJAX) -->
    <script src="{{ asset('resources/js/cdn/jquery-3.4.1.min.js') }}"></script>
    <!-- AJAX CDN -->
    <script src="{{ asset('resources/js/cdn/jquery.min.js') }}"></script>
    <!-- Popper.JS -->
    <script src="{{ asset('resources/js/cdn/popper.min.js') }}"></script>
    <!-- Bootstrap JS -->
    <script src="{{ asset('resources/js/cdn/bootstrap.min.js') }}"></script>
    <!-- select picker -->
    <script src="{{ asset('resources/js/cdn/bootstrap-select.min.js') }}"></script>
    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- CUSTOM -->


    @yield('js')

    </html>