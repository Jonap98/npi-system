<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <title>Registro e-kanban</title>
</head>
<body>

    {{-- <div class="container alert alert-success mt-2" role="alert">
        Usuario creado exitosamente!
    </div> --}}
    @if(session('success'))
        <div class="container alert alert-success mt-2" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="p-3 container d-flex align-items-center justify-content-center">



        <div class="p-2 m-2 border rounded">
            <div class="d-flex justify-content-center">
                <img src="{{ asset('assets/whirlpool-logo.png') }}" alt="" width="250px">
            </div>

            <h2 class="py-2">Registrar usuarios e-kanban</h2>

            <form action="{{ route('e-kanban.register.register') }}" method="POST">
                @csrf
                <div class="py-2">
                    <h4>Nombre</h4>
                    <input type="text" class="form-control" name="name" placeholder="Nombre...">
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="py-2">
                    <h4>Usuario</h4>
                    <input type="text" class="form-control" name="username" placeholder="Usuario...">
                    @error('username')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="py-2">
                    <h4>Contraseña</h4>
                    <input type="password" class="form-control" name="password" placeholder="Contraseña...">
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="py-2 d-grid gap-2">
                    <button class="btn btn-primary">
                        Registrar
                    </button>
                </div>

            </form>
            <div class="py-2 d-grid gap-2">
                <a href="#" class="btn btn-secondary">
                    Volver al portal
                </a>
            </div>
        </div>
    </div>

</body>
</html>
