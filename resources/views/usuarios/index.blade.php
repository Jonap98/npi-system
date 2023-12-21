@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="col-md-12 d-flex justify-content-between">
                <span>Usuarios</span>

                @if(Auth::user()->role == 'NPI-adm')
                    <a class="btn btn-primary" href="{{ route('registro') }}" class="btn">
                        Crear usuario
                    </a>
                @endif
            </div>
            <hr>
            @if(session('success'))
                <div class="alert alert-success mt-2" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger mt-2" role="alert">
                    {{ session('error') }}
                </div>
            @endif
            <div class="container">
                <div class="row">
                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="partes" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Rol</th>
                                        @if (Auth::user() && (Auth::user()->role == 'NPI-adm'))
                                            <th scope="col">Acciones</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $usuario)
                                        <tr>
                                            <td>{{ $usuario->name }}</td>
                                            <td>{{ $usuario->username }}</td>
                                            <td>{{ $usuario->email }}</td>
                                            <td>{{ $usuario->role }}</td>
                                            @if (Auth::user() && (Auth::user()->role == 'NPI-adm'))
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm mx-auto" data-bs-toggle="modal" data-bs-target="#editModal{{ $usuario->id }}">
                                                        Editar
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm mx-auto" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $usuario->id }}">
                                                        Eliminar
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>

                                        @if (Auth::user() && (Auth::user()->role == 'NPI-adm'))
                                            {{-- Modal editar --}}
                                            @include('usuarios.edit')
                                            {{-- Modal eliminar --}}
                                            @include('usuarios.delete')
                                        @endif

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="container">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@section('js')

    {{-- Carga datos modal --}}
    <script>
        function cambioParte(numero_de_parte) {
            const id = id;
            const numero_de_parte = numero_de_parte;
            const descripcion = descripcion;
            const um = um;

            document.getElementById('id').value=id;
            document.getElementById('numero_de_parte').value=numero_de_parte;
            document.getElementById('descripcion').value=descripcion;
            document.getElementById('um').value=um;
        }
    </script>

    {{-- Datatable --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#partes').DataTable({
                order: [0, 'desc']
            });
        });
    </script>

@endsection

@endsection
