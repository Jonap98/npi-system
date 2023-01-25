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
                {{-- <form method="POST" action="{{ route('solicitud.requerimientos.export') }}" id="pdfForm" name="pdfForm">
                    @csrf
    
                    <input type="text" hidden id="folio" name="folio" value="{{ $folio }}">
    
                    <button type="submit" class="btn btn-primary">
                        Guardar
                    </button>
                </form> --}}
                {{-- <form method="POST" action="{{ route('solicitud.requerimientos.export') }}" id="pdfForm" name="pdfForm">
                    @csrf
    
                    <input type="text" hidden id="folio" name="folio" value="{{ $folio }}">
    
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-printer-fill" viewBox="0 0 16 16">
                            <path d="M5 1a2 2 0 0 0-2 2v1h10V3a2 2 0 0 0-2-2H5zm6 8H5a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1z"/>
                            <path d="M0 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-1v-2a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v2H2a2 2 0 0 1-2-2V7zm2.5 1a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        </svg>
                        Imprimir
                    </button>
                </form> --}}
            </div>
            <hr>
            <span>{{ $status }}</span>
            <div class="container">
                <div id="location"></div>
                @if ($status == 'SOLICITADO')
                    <form action="{{ route('solicitud.requerimientos.preparar') }}" method="POST">
                @endif
                @if ($status == 'PREPARADO')
                    <form action="{{ route('solicitud.requerimientos.preparar') }}" method="POST">
                @endif
                    @csrf
                    <div class="row">
                        <div class="d-flex flex-row-reverse">
                            <div class="col-md-3">
                                @if ($status == 'SOLICITADO')
                                    <button type="submit" class="btn btn-primary">
                                        Guardar
                                    </button>
                                @endif
                                @if ($status == 'PREPARADO')
                                    <button type="submit" class="btn btn-primary">
                                        Confirmar
                                    </button>
                                @endif
                            </div>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success mt-2" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger mt-2" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <span id="counter"></span>
                            <div class="card col-md-12 mt-4">
                                <div class="mt-2 table-responsive">
                                    <table id="requerimientos" class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th scope="col">Folio</th>
                                                <th scope="col">Número de parte</th>
                                                <th scope="col">Descripcion</th>
                                                <th scope="col">Cantidad requerida</th>
                                                <th scope="col">Cantidad solicitada</th>
                                                <th scope="col">Cantidad en ubicación</th>
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
                                                    <td>{{ $requerimiento->cantidad_requerida }}</td>
                                                    <td>
                                                        <b>
                                                            @forelse ($requerimiento->solicitudes as $ubicaciones)
                                                                <div class="mb-2 d-flex justify-content-between">
                                                                    <span>{{ $ubicaciones->id }}</span>
                                                                    <b>{{ $ubicaciones->ubicacion }} {{ $ubicaciones->palet }}: {{ round($ubicaciones->cantidad, 0) }}</b>
                                                                    {{-- <input type="checkbox" name="" id="" style="top: 1.2 rem; scale: 1.7; margin-right: 0.8rem" 
                                                                        onchange="agregarCantidad('{{ $ubicaciones->id }}', '{{ $ubicaciones->folio_solicitud }}', '{{ $ubicaciones->num_parte }}', '{{ $ubicaciones->ubicacion }}', '{{ $ubicaciones->cantidad }}')"
                                                                    > --}}
                                                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $ubicaciones->id }}">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                                @include('requerimientos.solicitudes.edit')
                                                            @empty
                                                                <b>0</b>
                                                            @endforelse
                                                        </b>

                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="folio" value="{{ $requerimiento->folio }}">

                                                        {{-- <div>
                                                            <span>En inventario: {{ $requerimiento->inventario }}</span>
                                                        </div> --}}

                                                        @forelse ($requerimiento->ubicaciones as $ubicacion)
                                                            <div>
                                                                <b>{{ $ubicacion->ubicacion }} {{ $ubicacion->palet }}: {{ $ubicacion->cantidad }}</b>
                                                                @if ($status == 'SOLICITADO')
                                                                    {{-- <input type="hidden" id="{{ $ubicacion->id }}" class="form-control" name="num_part{{ $requerimiento->num_parte }}_{{ $ubicacion->id }}" value="{{ $requerimiento->num_parte }}"> --}}
                                                                    <input type="hidden" id="{{ $ubicacion->id }}" class="form-control" name="num_parte{{ $requerimiento->num_parte }}_{{ $ubicacion->id }}" value="{{ $requerimiento->num_parte }}">
                                                                    <input type="hidden" id="{{ $ubicacion->id }}" class="form-control" name="ubicacion{{ $requerimiento->num_parte }}_{{ $ubicacion->id }}" value="{{ $ubicacion->ubicacion }}">
                                                                    <input type="hidden" id="{{ $ubicacion->id }}" class="form-control" name="palet{{ $requerimiento->num_parte }}_{{ $ubicacion->id }}" value="{{ $ubicacion->palet }}">
                                                                    <input type="number" id="{{ $ubicacion->id }}" class="form-control" name="cantidad{{ $requerimiento->num_parte }}_{{ $ubicacion->id }}" >
                                                                    {{-- <input type="text" id="{{ $ubicacion->id }}" class="form-control" name="cantidad{{ $ubicacion->id }}" > --}}
                                                                @endif
                                                            </div>
                                                        @empty
                                                            <b>0</b>
                                                        @endforelse
                                                    </td>
                                                    {{-- <td>{{ $requerimiento->cantidad_ubicacion }}</td> --}}
                                                    <td>{{ $requerimiento->solicitante }}</td>
                                                    <td>{{ $requerimiento->status }}</td>
                                                </tr>

                                                {{-- @include('requerimientos.solicitudes.edit') --}}

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
            $('#requerimientos').DataTable();
        });
    </script>










    <script>
        const sendForm = () => {
            
        }

    </script>

































    <script>
        const sendFormEdit = (id) => {
            console.log(id);
            // const formEdit = document.getElementById(`formEdit${id}`);
            const formEdit = document.getElementById(`formEdit`);
            console.log(formEdit);
            // formEdit.innerHTML = '';
            const cantidad = document.getElementById(`cantidad${id}`);
            const num_parte = document.getElementById(`num_parte${id}`);
            const another_property = document.getElementById(`another_property${id}`);


            formEdit.remove(cantidad);
            formEdit.remove(num_parte);
            formEdit.remove(another_property);


            console.log(formEdit);
            // formEdit.submit();

            // const formContainer = document.getElementById('formContainer')

            // const form = document.createElement('form');
            
    // <meta name="csrf-token" content="{{ csrf_token() }}">

            // const token = document.getElementById('tkn');
            // console.log(token);

            
            // const cantidad = document.getElementById(`cantidad${id}`);
            // const num_parte = document.getElementById(`num_parte${id}`);
            // const another_property = document.getElementById(`another_property${id}`);

            // formEdit.appendChild(token);

            formEdit.appendChild(cantidad);
            formEdit.appendChild(num_parte);
            formEdit.appendChild(another_property);
            // formEdit.appendChild(padre);
            console.log(formEdit);

            // formEdit.appendChild(padre);


            // const another_property = document.getElementById('another_property');

            // const cantidad = document.getElementById('cantidad');
            // const num_parte = document.getElementById('num_parte');
            
            // formEdit.appendChild(another_property);
            // formEdit.appendChild(cantidad);
            // formEdit.appendChild(num_parte);

            formEdit.submit();
            // console.log(another_property.value);
        }
    </script>

    {{-- Confirmación del material, comentado porque ya no se requiere, pero originalmente estaba en el requerimiento --}}
    {{-- Se dejará por si acaso se requiere, solo hasta subir al servidor --}}
    {{-- <script>

        // const ubicacionesForm = document.createElement('form');
        // const token = document.querySelector("#csrf input").value;
        // ubicacionesForm.appendChild(token);

        const counter = document.getElementById('counter');


        // Botón enviar
        const buttonLocation = document.getElementById('location');

        const sendButton = document.createElement('button');
        sendButton.className = 'btn btn-primary disabled';
        sendButton.innerText = 'Confirmar';

        buttonLocation.appendChild(sendButton);

        const ubicacionesList = [];

        const agregarCantidad = (id, folio, num_parte, ubicacion, cantidad) => {
            console.log(id);
            console.log(folio);
            console.log(num_parte);
            console.log(ubicacion);
            console.log(cantidad);

            if(ubicacionesList.length > 0) {
                buttonLocation.removeAttribute('disabled');
            } 
            else {
                buttonLocation.classList('disabled');
            }

            if(ubicacionesList.includes(id)) {
                ubicacionesList.splice(ubicacionesList.indexOf(id), 1);
            } else {
                ubicacionesList.push(id);
            }
            
            

            // ubicacionesForm.appendChild();

            counter.innerText = `${ubicacionesList.length} registros agregados`;
        }
    </script> --}}

@endsection

@endsection