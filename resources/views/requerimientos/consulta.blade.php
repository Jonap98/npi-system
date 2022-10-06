@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span>Requerimientos</span>
            <hr>
            <div class="container">
                <div class="row">
                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="requerimientos" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">folio</th>
                                        <th scope="col">proyecto</th>
                                        <th scope="col">numero_de_parte</th>
                                        <th scope="col">descripcion</th>
                                        <th scope="col">um</th>
                                        <th scope="col">cantidad</th>
                                        <th scope="col">comentario</th>
                                        <th scope="col">fecha</th>
                                        <th scope="col">persona_que_reuiere</th>
                                        <th scope="col">status</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requerimientos as $requerimiento)
                                        <tr>
                                            <td>{{ $requerimiento->folio }}</td>
                                            <td>{{ $requerimiento->proyecto }}</td>
                                            <td>{{ $requerimiento->numero_de_parte }}</td>
                                            <td>{{ $requerimiento->descripcion }}</td>
                                            <td>{{ $requerimiento->um }}</td>
                                            <td>{{ $requerimiento->cantidad }}</td>
                                            <td>{{ $requerimiento->comentario }}</td>
                                            <td>{{ $requerimiento->fecha }}</td>
                                            <td>{{ $requerimiento->persona_que_reuiere }}</td>
                                            <td>{{ $requerimiento->status }}</td>
                                            {{-- <td>
                                                <button type="button" class="btn btn-success">Editar</button>
                                            </td> --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {{ $escaneos->links('pagination::Bootstrap-4') }} --}}
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
            $('#requerimientos').DataTable();
        });
    </script>

@endsection

@endsection