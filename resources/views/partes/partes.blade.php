@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span>Partes</span>
            @if(Auth::user()->role == 'NPI-adm')
                <a href="{{ route('partes.create') }}" class="btn btn-primary btn-sm ms-5">Crear parte</a>
            @endif
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
                                        @if (Auth::user() && (Auth::user()->role == 'NPI-adm'))
                                            <th scope="col">Acción</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partes as $parte)
                                        <tr>
                                            <td>{{ $parte->id }}</td>
                                            <td>{{ $parte->numero_de_parte }}</td>
                                            <td>{{ $parte->descripcion }}</td>
                                            <td>{{ $parte->um }}</td>
                                            @if (Auth::user() && (Auth::user()->role == 'NPI-adm'))
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm mx-auto" data-bs-toggle="modal" data-bs-target="#editModal" onclick="editPart('{{ $parte->id }}', '{{ $parte->numero_de_parte }}', '{{ $parte->descripcion }}')">
                                                        Editar
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm mx-auto" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deletePart('{{ $parte->id }}')">
                                                        Eliminar
                                                    </button>
                                                </td>
                                            @endif
                                        </tr>

                                    @endforeach

                                    @if (Auth::user() && (Auth::user()->role == 'NPI-adm'))
                                        {{-- Modal editar --}}
                                        @include('partes.edit')
                                        {{-- Modal eliminar --}}
                                        @include('partes.delete')
                                    @endif
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
            $('#partes').DataTable({
                order: [0, 'desc']
            });
        });
    </script>

    <script>
        const editPart = (id, num_parte, descripcion) => {
            const inputId = document.getElementById('id_parte');
            const inputNumParte = document.getElementById('num_parte_id');
            const inputDescripcion = document.getElementById('descripcion_id');

            inputId.value = id;
            inputNumParte.value = num_parte;
            inputDescripcion.value = descripcion;
        }

        const deletePart = (id) => {
            const deleteInputId = document.getElementById('delete_id');

            deleteInputId.value = id;
        }
    </script>

@endsection

@endsection
