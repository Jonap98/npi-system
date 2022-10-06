@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <span>Requerimientos</span>
                <hr>
                <div class="container">
                    <form name="movimientos" method="post" action="{{ route('store') }}">
                        @csrf
                        <div class="row g-2">
                            <div class="col m-2">
                                <span>Proyecto</span>
                            </div>
                            <div class="col m-2">
                                <select class="form-select" name="proyecto">
                                    <option selected>Proyectos</option>
                                    <option value="Proyecto 1">Proyecto 1</option>
                                    <option value="Proyecto 2">Proyecto 2</option>
                                    <option value="Proyecto 3">Proyecto 3</option>
                                </select>
                            </div>
                            <div class="col m-2">
                                <span>Tipo de movimiento 
                                </span>
                            </div>
                            <div class="col m-2">
                                <select class="form-select" name="tipo">
                                    <option selected>Tipo</option>
                                    <option value="Entrada">Entrada</option>
                                    <option value="Salida">Salida</option>
                                    <option value="Ajuste">Ajuste</option>
                                </select>
                            </div>

                            <div class="card col-md-12">
                                {{-- <table id="movimientos" class="table table-striped m-2"> --}}
                                <table class="table table-striped m-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">Número de parte</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Comentario</th>
                                            <th scope="col">Disponibilidad</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($partes as $parte)
                                            <tr>
                                                <td>{{ $parte->numero_de_parte }}</td>
                                                <td>{{ $parte->descripcion }}</td>
                                                <td>{{ $parte->um }}</td>
                                                <td>{{ $parte->cantidad }}</td>
                                                <td>{{ $parte->comentario }}</td>
                                            </tr>
                                        @endforeach
                                        </form>
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </form>
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
                $('#movimientos').DataTable();
            });
        </script>

    @endsection
@endsection