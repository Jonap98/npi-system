@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span>Movimientos</span>
            @if(Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-whs'))
                <a href="{{ route('movimientos.create') }}" class="btn btn-primary btn-sm ms-5">Crear movimiento</a>
            @endif
            <a href="{{ route('exportar') }}" class="btn btn-success btn-sm ms-5">Exportar excel</a>
            <hr>
            <div class="row">
                <div class="col-md-3 m-3">
                    <form id="qty-form" action="{{ route('movimientos.filters') }}" method="POST">
                        @csrf
                        <span>Cantidad a mostrar</span>
                        <select name="cantidad" id="filtro-cantidad" class="form-select" onchange="filterFunction()">
                            <option value="">Seleccionar cantidad</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="1500">1500</option>
                            <option value="all">Todos</option>
                        </select>
                    </form>
                </div>
                <div class="col align-self-center">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filtrar-numeros">Por número</button>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="consulta" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Proyecto</th>
                                        <th scope="col">Número de parte</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Unidad de medida</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Comentario</th>
                                        <th scope="col">Fecha de registro</th>
                                        <th scope="col">Ubicación</th>
                                        <th scope="col">Palet</th>
                                        <th scope="col">Número de guía</th>
                                        <th scope="col">Usuario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($movimientos as $movimiento)
                                        <tr>
                                            <td>{{ $movimiento->id }}</td>
                                            <td>{{ $movimiento->proyecto }}</td>
                                            <td>{{ $movimiento->numero_parte }}</td>
                                            <td>{{ $movimiento->descripcion }}</td>
                                            <td>{{ $movimiento->unidad_de_medida }}</td>
                                            <td>{{ $movimiento->tipo }}</td>
                                            <td>{{ $movimiento->cantidad }}</td>
                                            <td>{{ $movimiento->comentario }}</td>
                                            <td>{{ $movimiento->fecha_registro }}</td>
                                            <td>{{ $movimiento->ubicacion }}</td>
                                            <td>{{ $movimiento->palet }}</td>
                                            <td>{{ $movimiento->numero_guia }}</td>
                                            <td>{{ $movimiento->usuario }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    @include('movimientos.filters.numeros')

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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#consulta').DataTable({
                order: [0, 'desc']
            });
        });
    </script>

    <script>
        function filterFunction() {
            const cantidad = document.getElementById('filtro-cantidad');

            document.getElementById('qty-form').submit();

        }

        function buscar() {
            let materialsList = [];
            let lines = document.getElementById('materiales');
            let lines2 = document.getElementById('materiales2');

            document.getElementById('materials-button').setAttribute('disabled', '');

            materialsList = lines.value.split('\n');

            lines2.value = materialsList;

            console.log(materialsList)

            document.getElementById('numeros-id').submit();
        }
    </script>

@endsection

@endsection
