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

            <form method="POST" action="{{ route('solicitud.requerimientos.filter') }}">
                @csrf
                <div class="d-flex align-items-center">
                    <div class="col-md-3 m-3">
                        <span>Status</span>
                        <select name="status" id="filtro-status" class="form-select">
                            <option value="">Seleccionar status</option>
                            <option value="RECIBIDO">Recibido</option>
                            <option value="SOLICITADO">Solicitado</option>
                            <option value="PREPARADO">Preparado</option>
                        </select>
                    </div>
                    <div class="col-md-3 m-3">
                        <span>Cantidad a mostrar</span>
                        <select name="cantidad" id="filtro-cantidad" class="form-select">
                            <option value="">Seleccionar cantidad</option>
                            <option value="500">500</option>
                            <option value="1000">1000</option>
                            <option value="1500">1500</option>
                            <option value="{{ $qty }}">Todos</option>
                        </select>
                    </div>

                    <div class="col-md-3 m-3">
                        <button type="submit" class="btn btn-primary">
                            Buscar
                        </button>
                    </div>

                </div>
            </form>

            <div class="d-flex justify-content-end">
                <div class="col-md-3 m-3">
                    <button type="submit" class="btn btn-danger">
                        Limpiar filtros
                    </button>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    @if(session('success'))
                        <div class="alert alert-success mt-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="card col-md-12">

                        <div class="mt-2 table-responsive">
                            <table id="requerimientos" class="table table-striped">
                                <thead>
                                    <tr>
                                        @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                            <th scope="col">Eliminar</th>
                                        @endif
                                        <th scope="col">Folio</th>
                                        <th scope="col">Solicitante</th>
                                        <th scope="col">Kit</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Fecha</th>
                                        @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                            <th scope="col">Recibir</th>
                                        @endif
                                        <th scope="col">Ver detalles</th>
                                        <th scope="col">Imprimir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($requerimientos as $requerimiento)
                                        <tr>
                                            @if(Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                                <td>
                                                    @if ($requerimiento->status == 'SOLICITADO')
                                                        <button class="btn" style="background-color: #ba3636; color: #fff" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="addFolioToDelete({{ $requerimiento->folio }})">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </td>
                                            @endif
                                            <td>{{ $requerimiento->folio }}</td>
                                            <td>{{ $requerimiento->solicitante }}</td>
                                            <td>{{ $requerimiento->kit_nombre }}</td>
                                            <td>{{ $requerimiento->team }}</td>
                                            <td>{{ $requerimiento->status }}</td>
                                            <td>{{ $requerimiento->fecha }}</td>
                                            @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                                <td>
                                                    <button {{ ($requerimiento->status != 'PREPARADO') ? 'disabled' : '' }} class="btn" style="background-color: #4aba36; color: #fff" onclick="sendAction({{ $requerimiento->id }}, '2', {{ $requerimiento->folio }})" data-bs-toggle="modal" data-bs-target="#update{{ $requerimiento->id }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-arrow-down-fill" viewBox="0 0 16 16">
                                                            <path d="M12 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM8 5a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 9.293V5.5A.5.5 0 0 1 8 5z"/>
                                                        </svg>
                                                    </button>
                                                </td>
                                            @endif
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
                                    @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                        @include('requerimientos.solicitudes.delete')
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
        function sendAction(id, action, folio) {
            const title = document.getElementById(`title${id}`);
            const accion = document.getElementById(`action${id}`);
            const folioInput = document.getElementById(`folio${id}`);
            const confirmationMessage = document.getElementById(`confirmationMessage${id}`);

            title.innerText = (action == '1') ? 'Preparar solicitud' : 'Recibir solicitud';
            accion.value = action;
            folioInput.value = folio;
            confirmationMessage.innerText = (action == '1') ? '¿Desea preparar la solicitud?' : '¿Desea recibir la solicitud?';
        }

        function addFolioToDelete( folio ) {
            const folioInput = document.getElementById('folio-delete');
            folioInput.value = folio;

            const folioSpan = document.getElementById('folio-span');
            folioSpan.innerText = folio;

        }

        function deleteFolio() {
            const folioInput = document.getElementById('folio-delete');


            $.ajax({
                type: 'POST',
                url: `/solicitudes/requerimientos/delete`,
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').content
                },
                data: {
                    "folio": folioInput.value
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
    </script>

@endsection

@endsection
