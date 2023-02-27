@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span> <b>TEST</b> Movimientos</span>
            <a href="{{ route('test.movimientos.create') }}" class="btn btn-primary btn-sm ms-5">Crear movimiento</a>
            <hr>
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
                                        </tr>
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

@endsection

@endsection