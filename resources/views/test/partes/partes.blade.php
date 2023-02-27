@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span> <b>TEST</b> Partes</span>
            <a href="{{ route('test.partes.create') }}" class="btn btn-primary btn-sm ms-5">Crear parte</a>
            <hr>
            @if(session('success'))
                <div class="alert alert-success mt-2" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <div class="container">
                <div class="row">
                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="partes" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Número de parte</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col">UM</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partes as $parte)
                                        <tr>
                                            <td>{{ $parte->id }}</td>
                                            <td>{{ $parte->numero_de_parte }}</td>
                                            <td>{{ $parte->descripcion }}</td>
                                            <td>{{ $parte->um }}</td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm mx-auto" data-bs-toggle="modal" data-bs-target="#editModal{{ $parte->id }}">
                                                    Editar
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm mx-auto" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $parte->id }}">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal editar --}}
                                        @include('test.partes.edit')
                                        {{-- Modal eliminar --}}
                                        @include('test.partes.delete')

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