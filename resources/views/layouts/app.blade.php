<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta id="tkn" name="csrf-token" content="{{ csrf_token() }}">

    <title>NPI</title>

    <!-- Scripts -->
    {{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet"> --}}
    {{-- @livewireStyles --}}
    <!-- CSS only -->
    @yield('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css') }}">

</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ route('movimientos') }}">
                    <img src="{{ asset('assets/whirlpool-logo.png') }}" alt="" width="100px">
                </a>
                <div class="navbar-link">
                    <b>Inventario de nuevas partes</b>
                </div>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('ubicaciones') }}">Ubicaciones</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link active" aria-current="page" href="{{ route('partes') }}">Partes</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('movimientos') }}">Movimientos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('inventario') }}">Inventario</a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('boms') }}" class="nav-link active">BOM</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('solicitud.requerimientos') }}">Requerimientos</a>
                    </li>


                    <li class="nav-item dropdown">
                        <a href="#" class="nav-link active dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Solicitar
                        </a>
                        <ul class="dropdown-menu dropdown-bar-content" aria-labelledby="navbarDropdown">
                            <li>
                                <a href="{{ route('requerimientos.manual') }}" class="nav-link active">Requerimientos por número</a>
                            </li>

                            <li>
                                <a href="{{ route('requerimientos.modelo') }}" class="nav-link active">Requerimientos por modelo</a>
                            </li>
                        </ul>
                    </li>

                    @auth
                        
                        @if(Auth::user()->role == 'NPI-admin')
                        
                            <li class="nav-item dropdown">
                                <a href="#" id="navbarDropdow" class="nav-link active dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                    Test
                                </a>
                                <ul class="dropdown-menu dropdown-bar-content" aria-labelledby="navbarDropdown">
                                    <li>
                                        <a href="{{ route('test.movimientos') }}" class="nav-link active">Movimientos</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('test.inventario') }}" class="nav-link active">Inventario</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('test.partes') }}" class="nav-link active">Partes</a>
                                    </li>
                                </ul>
                            </li>

                            <a href="{{ route('usuarios') }}" class="nav-link active">Usuarios</a>

                        @endif
                        
                    @endauth


                </ul>

                {{-- Autenticado --}}
                @auth
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn">Logout</button>
                    </form>
                @endauth
                <a class="btn" href="../">Regresar al portal</a>

                {{-- No autenticado --}}
                @guest
                    
                @endguest
                
            </div>
            <div class=""></div>
            
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    {{-- @livewireScripts --}}
    <!-- JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
@yield('js')
</body>
</html>