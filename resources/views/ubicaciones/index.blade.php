@extends('layouts.app')

@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css"> --}}
@endsection


@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span>Ubicaciones</span>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storeUbicacion">
                Crear ubicación
            </button>
            @if(session('success'))
                <div class="alert alert-success mt-2" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            <hr>
            <div class="container">
                <div class="row">
                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="ubicaciones" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Ubicación</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ubicaciones as $ubicacion)
                                        <tr>
                                            <td>{{ $ubicacion->ubicacion }}</td>
                                            <td>{{ $ubicacion->tipo }}</td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-sm mx-auto" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $ubicacion->id }}">
                                                    Eliminar
                                                </button>
                                            </td>
                                        </tr>

                                        {{-- Modal editar --}}
                                        @include('ubicaciones.store')
                                        {{-- Modal eliminar --}}
                                        @include('ubicaciones.delete')

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

    {{-- Datatable --}}
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#ubicaciones').DataTable({
                order: [0, 'desc']
            });
        });
    </script>

@endsection

@endsection
