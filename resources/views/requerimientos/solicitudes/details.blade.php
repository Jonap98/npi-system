@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="d-flex justify-content-between">
                <span>Detalles de solicitud</span>
                <form method="POST" action="{{ route('solicitud.requerimientos.export') }}" id="pdfForm" name="pdfForm">
                    @csrf
    
                    <input type="text" hidden id="folio" name="folio" value="{{ $folio }}">
    
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        </svg>
                        Imprimir
                    </button>
                </form>
            </div>
            <hr>
            <div class="container">
                <div class="row">
                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="requerimientos" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Número de parte</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col">Ubicación</th>
                                        <th scope="col">Cantidad requerida</th>
                                        <th scope="col">Cantidad ubicación</th>
                                        <th scope="col">Solicitante</th>
                                        <th scope="col">Status</th>                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requerimientos as $requerimiento)
                                        <tr>
                                            <td>{{ $requerimiento->folio }}</td>
                                            <td>{{ $requerimiento->num_parte }}</td>
                                            <td>{{ $requerimiento->descripcion }}</td>
                                            <td>Ubicación</td>
                                            <td>{{ $requerimiento->cantidad_requerida }}</td>
                                            <td>{{ $requerimiento->cantidad_ubicacion }}</td>
                                            <td>{{ $requerimiento->solicitante }}</td>
                                            <td>{{ $requerimiento->status }}</td>
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