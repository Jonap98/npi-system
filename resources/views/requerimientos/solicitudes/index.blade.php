@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span>Solicitudes de requerimientos</span>
            <hr>
            <div class="container">
                <div class="row">
                    @if(session('success'))
                        <div class="alert alert-success mt-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between">
                        <div class="col-md-2 mb-4">
                            <form method="POST" action="{{ route('solicitud.requerimientos.filter') }}" class="" >
                                @csrf
                                <label for="">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="RECIBIDO">Recibido</option>
                                    <option value="SOLICITADO">Solicitado</option>
                                    <option value="PREPARADO">Preparado</option>
                                </select>
                                <button type="submit" class="mt-2 btn btn-primary">
                                    Buscar
                                </button>
                            </form>
                        </div>
    
                        <div class="mb-4 align-self-end">
                            <a href="{{ route('solicitud.requerimientos') }}" class="btn" style="background-color: #dc6534; color: #fff">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#fff" class="bi bi-funnel-fill" viewBox="0 0 16 16">
                                    <path d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5v-2z"/>
                                </svg>
                                Limpiar filtros
                            </a>
                        </div>
                    </div>

                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="requerimientos" class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">Folio</th>
                                        <th scope="col">Solicitante</th>
                                        <th scope="col">Kit</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Recibir</th>
                                        <th scope="col">Ver detalles</th>
                                        <th scope="col">Imprimir</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requerimientos as $requerimiento)
                                        <tr>
                                            <td>{{ $requerimiento->folio }}</td>
                                            <td>{{ $requerimiento->solicitante }}</td>
                                            <td>{{ $requerimiento->kit_nombre }}</td>
                                            <td>{{ $requerimiento->status }}</td>
                                            <td>{{ $requerimiento->fecha }}</td>
                                            <td>
                                                <button {{ ($requerimiento->status != 'PREPARADO') ? 'disabled' : '' }} class="btn" style="background-color: #4aba36; color: #fff" onclick="sendAction({{ $requerimiento->id }}, '2')" data-bs-toggle="modal" data-bs-target="#update{{ $requerimiento->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-arrow-down-fill" viewBox="0 0 16 16">
                                                        <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM8 5a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5A.5.5 0 0 1 8 5z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('solicitud.requerimientos.detalles', $requerimiento->folio) }}" class="btn" style="background-color: #f0ac3e; color: #fff">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-text-fill" viewBox="0 0 16 16">
                                                        <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM5 4h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm-.5 2.5A.5.5 0 0 1 5 6h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1-.5-.5zM5 8h6a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1zm0 2h3a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1z"/>
                                                    </svg>
                                                </a>
                                            </td>
                                            <td>
                                                <form method="POST" action="{{ route('solicitud.requerimientos.export') }}" id="pdfForm" name="pdfForm">
                                                    @csrf

                                                    <input type="text" hidden id="folio" name="folio" value="{{ $requerimiento->folio }}">

                                                    <button type="submit" class="btn btn-primary">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                                                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                                                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                                                          </svg>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @include('requerimientos.solicitudes.update')
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
            $('#requerimientos').DataTable({
                order:[0, 'desc']
            });
        });
    </script>

    <script>
        function sendAction(id, action) {
            const title = document.getElementById(`title${id}`);
            const accion = document.getElementById(`action${id}`);
            const confirmationMessage = document.getElementById(`confirmationMessage${id}`);

            title.innerText = (action == '1') ? 'Preparar solicitud' : 'Recibir solicitud';
            accion.value = action;
            confirmationMessage.innerText = (action == '1') ? '¿Desea preparar la solicitud?' : '¿Desea recibir la solicitud?';
        }
    </script>

@endsection

@endsection