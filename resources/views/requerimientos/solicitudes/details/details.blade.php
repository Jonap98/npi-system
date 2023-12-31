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
            </div>
            <hr>
            <span>{{ $status }}</span>
            <div class="container">
                <div id="location"></div>
                    <div class="d-flex justify-content-end">
                        @if ($status == 'SOLICITADO')
                            @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-whs'))
                                <button type="submit" class="btn btn-primary" id="confirmButton" onclick="guardarInfo()">
                                    Guardar
                                </button>
                            @endif
                        @endif
                    </div>

                    <div class="row">
                        <div id="snackbar" class="alert alert-success mt-2" hidden></div>
                        <div id="error-alert" class="alert alert-danger mt-2" hidden></div>
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
                        <div id="cantidad-a-eliminar-indicador" style="display: none">
                            <span>Cantidad de registros a eliminar: <b id="cantidad-a-eliminar"></b> </span>
                            <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalDelete" onclick="enviarDatosEliminar()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                    <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5Zm-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5ZM4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06Zm6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528ZM8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5Z"/>
                                </svg>
                                Eliminar
                            </button>
                        </div>
                            <div class="card col-md-12 mt-4">
                                <div class="mt-2 table-responsive">
                                    <table id="requerimientos" class="table table-striped">
                                        <thead>
                                            <tr>
                                                @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                                    <th scope="col">Eliminar</th>
                                                @endif
                                                <th scope="col">Folio</th>
                                                <th scope="col">Número de parte</th>
                                                <th scope="col">Kit nombre</th>
                                                <th scope="col">Descripcion</th>
                                                <th scope="col">Cantidad requerida</th>
                                                <th scope="col">Cantidad solicitada</th>
                                                <th scope="col">Cantidad en ubicación</th>
                                                <th scope="col">Solicitante</th>
                                                <th scope="col">Ubicación</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Quien surte</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($requerimientos as $requerimiento)
                                                <tr>
                                                    @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                                        <td class="text-center">
                                                            @if ( $requerimiento->status == 'SOLICITADO' )
                                                                <input type="checkbox" style="top: 1.2rem; scale: 2; margin-right: 0.8rem;" name="" id="" onchange="addDeleteIndex('{{ $requerimiento->id }}')">
                                                            @endif
                                                        </td>
                                                    @endif
                                                    <td>{{ $requerimiento->folio }}</td>
                                                    <td>{{ $requerimiento->num_parte }}</td>
                                                    <td>{{ $requerimiento->kit_nombre }}</td>
                                                    <td>{{ $requerimiento->descripcion }}</td>
                                                    <td>{{ round($requerimiento->cantidad_requerida, 0) }}</td>
                                                    <td id="requerimiento{{ $requerimiento->id }}">
                                                        <b>
                                                            @forelse ($requerimiento->cantidad_solicitada as $ubicaciones)
                                                                <div class="mb-2 d-flex justify-content-between">
                                                                    <b>{{ $ubicaciones->ubicacion }} {{ $ubicaciones->palet }}: {{ round($ubicaciones->cantidad, 0) }}</b>

                                                                    @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-whs'))
                                                                        @if ($status != 'RECIBIDO')
                                                                            <button type="button" class="btn btn-sm" style="background-color: #de7e35; color: #fff" data-bs-toggle="modal" data-bs-target="#modalEdit{{ $ubicaciones->id }}">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                                                                </svg>
                                                                            </button>
                                                                        @endif
                                                                    @endif
                                                                </div>

                                                                @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-whs'))
                                                                    @include('requerimientos.solicitudes.details.edit2')
                                                                @endif
                                                            @empty
                                                                <b>0</b>
                                                            @endforelse
                                                        </b>

                                                    </td>
                                                    <td>
                                                        @forelse ($requerimiento->cantidad_ubicacion as $ubicacion)
                                                            <div id="ubicacion{{ $ubicacion->id }}{{ $requerimiento->id }}">
                                                                <b class="ubicacion{{ $ubicacion->id }}"> {{ $ubicacion->ubicacion }} {{ $ubicacion->palet }}: {{ round($ubicacion->cantidad, 0) }} </b>
                                                                    @if ($requerimiento->status   == 'SOLICITADO')
                                                                        @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-whs'))
                                                                            <input
                                                                                id="input{{ $ubicacion->id }}"
                                                                                type="number"
                                                                                class="form-control"
                                                                                name="cantidad"
                                                                                onblur="addQty( {{ $ubicacion->cantidad }}, {{ $ubicacion->element_index }}, value)">
                                                                        @endif
                                                                    @endif
                                                                </b>
                                                            </div>
                                                        @empty

                                                        @endforelse

                                                        @include('requerimientos.solicitudes.details.edit')
                                                    </td>
                                                    <td>{{ $requerimiento->solicitante }}</td>
                                                    <td>{{ $requerimiento->ubicacion }}</td>
                                                    <td>{{ $requerimiento->status }}</td>
                                                    <td>{{ $requerimiento->usuario ?? '' }}</td>
                                                </tr>

                                            @endforeach
                                            @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                                                @include('requerimientos.solicitudes.details.delete')
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
        let ubicacionesList = [];
        let inputList = [];

        $(document).ready(function () {
            $('#requerimientos').DataTable();

            // Se crea una lista para asignar índices a los input, si no hay input, no aumenta el índice
            ubicacionesList = @json($requerimientos);

            let index = 0;
            ubicacionesList.forEach(requerimiento => {
                if(requerimiento.cantidad_ubicacion.length > 0) {

                    // se asigna cada una de las propiedades
                    requerimiento.cantidad_ubicacion.forEach(ubicacion => {
                        inputList.push({
                            index: index,
                            folio: requerimiento.folio,
                            num_parte: requerimiento.num_parte,
                            id: ubicacion.id,
                            ubicacion: ubicacion.ubicacion,
                            palet: ubicacion.palet,
                            cantidad: 0,
                            id_requerimiento: requerimiento.id
                        });
                        index++;
                    });
                }
            });

        });

        const addQty = (disponible, index, value) => {

            if( value > disponible ) {
                value = 0;

                alert('No tienes inventario suficiente');
            }

            // Agrega cantidad a la lista de inputs
            inputList[index].cantidad = value;
        }

        const guardarInfo = () => {

            document.getElementById('confirmButton').setAttribute('disabled', 'true');

            $.ajax({
                type: "POST",
                url: '/solicitudes/requerimientos/preparar',
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').content
                },
                data: {
                    "cantidades": inputList,
                },
                dataType: "json",
                success: function({folio, msg, data}) {

                    const snack = document.getElementById('snackbar');
                    snack.innerText = msg;
                    snack.removeAttribute('hidden');

                    setTimeout(() => {
                        window.location.reload()
                    }, 2000);

                },
                error: function(error) {
                    const snack = document.getElementById('error-alert');
                    snack.innerText = 'No se pudo surtir el requerimiento, intente de nuevo';
                    snack.removeAttribute('hidden');

                }
            });
        }


    </script>

    <script>
        const sendFormEdit = (id) => {

            // Creación del form
            const form = document.createElement('form');
            form.id = `formEdit${id}`;
            form.action = @json(route('solicitud.requerimientos.edit') );
            form.method = 'POST';

            // Token
            const token = document.createElement('input');
            token.type = 'hidden';
            token.name = '_token';
            token.value = document.querySelector('meta[name="csrf-token"]').content;

            // Inputs
            const cantidadInput = document.getElementById(`cantidad${id}`);
            const cantidadActualInput = document.getElementById(`cantidad_actual${id}`);
            const cantidadIdInput = document.getElementById(`cantidad_id${id}`);
            const numParteInput = document.getElementById(`num_parte${id}`);
            const ubicacionInput = document.getElementById(`ubicacion${id}`);
            const paletInput = document.getElementById(`palet${id}`);


            form.appendChild(token);
            form.appendChild(cantidadInput.cloneNode(true));
            form.appendChild(cantidadActualInput.cloneNode(true));
            form.appendChild(cantidadIdInput.cloneNode(true));
            form.appendChild(numParteInput.cloneNode(true));
            form.appendChild(ubicacionInput.cloneNode(true));
            form.appendChild(paletInput.cloneNode(true));
            document.body.appendChild(form);

            form.submit();

        }

    </script>

    <script>

        let listaParaEliminar = [];

        const addDeleteIndex = ( id ) => {
            if(listaParaEliminar.includes(id)) {
                listaParaEliminar.splice(listaParaEliminar.indexOf(id), 1);
            } else {
                listaParaEliminar.push(id);
            }

            const cantidadIndicador = document.getElementById('cantidad-a-eliminar-indicador');

            if(listaParaEliminar.length > 0) {
                cantidadIndicador.style.display = 'block';

                const cantidad = document.getElementById('cantidad-a-eliminar');
                cantidad.innerText = listaParaEliminar.length;
            } else {
                cantidadIndicador.style.display = 'none';

            }

        }

        function enviarDatosEliminar() {
            const inputRegistros = document.getElementById('registros');
            inputRegistros.value = listaParaEliminar;
        }

    </script>

    <script>
        const setValues = (ubicacion, palet, cantidad) => {
            const cantidadSolicitada = `
            <b> ${ubicacion} ${palet}:  ${cantidad}</b>

            <button type="button" class="btn btn-sm" style="background-color: #de7e35; color: #fff" data-bs-toggle="modal" data-bs-target="#dynamicModalEdit">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                </svg>
            </button>
            `;

            return cantidadSolicitada;
        }

        // Ya no se usará esta lógica
        const registrarCantidad = (num_parte, folio, ubicacion, palet, id_requerimiento, cantidad, id_ubicacion, cantidad_actual) => {
            $.ajax({
                type: "POST",
                url: '/solicitudes/requerimientos/update-individual',
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').content
                },
                data: {
                    "num_parte": num_parte,
                    "folio": folio,
                    "ubicacion": ubicacion,
                    "palet": palet,
                    "id_requerimiento": id_requerimiento,
                    "cantidad": cantidad
                },
                dataType: "json",
                success: function({msg, data, movimiento}) {
                    if( data ) {
                        const field = document.getElementById(`requerimiento${id_requerimiento}`);
                        const div = document.createElement('div');
                        div.innerHTML = setValues(ubicacion, palet, cantidad);
                        field.appendChild(div);

                        const cantidadUbicacion = document.getElementsByClassName(`ubicacion${id_ubicacion}`);

                        const ubicacionElement = document.getElementById(`ubicacion${id_ubicacion}${id_requerimiento}`);
                        ubicacionElement.removeChild(ubicacionElement.lastElementChild)

                        for (let index = 0; index < cantidadUbicacion.length; index++) {

                            // Cálculo de acumulado
                            // Se calcula un acumulado re los movimientos para realizar la resta vs el total
                            $.ajax({
                                type: "POST",
                                url: '/solicitudes/requerimientos/calcular-acumulado',
                                headers: {
                                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').content
                                },
                                data: {
                                    "folio": folio,
                                    "num_parte": num_parte,
                                    "ubicacion": ubicacion,
                                    "palet": palet,
                                },
                                dataType: "json",
                                success: function({acumulado}) {
                                    if( acumulado ) {
                                        cantidadUbicacion[index].innerText = `${ubicacion} ${palet}: ${cantidad_actual - acumulado}`;

                                    }

                                },
                                error: function(error) {
                                }
                            });
                            // cantidadUbicacion[index].innerText = `${ubicacion} ${palet}: ${cantidad_actual - acumulado}`;
                        }

                        // Cargar info al modal
                        const modalMovimientoIdInput = document.getElementById('id_movimiento_dynamic_edit');
                        modalMovimientoIdInput.value = movimiento;

                        const modalIdInput = document.getElementById('id_dynamic_edit');
                        modalIdInput.value = data.id;

                        const modalNumParteInput = document.getElementById('num_parte_dynamic_edit');
                        modalNumParteInput.value = data.num_parte;

                        const modalUbicacionInput = document.getElementById('ubicacion_dynamic_edit');
                        modalUbicacionInput.value = data.ubicacion;

                        const modalPaletInput = document.getElementById('palet_dynamic_edit');
                        modalPaletInput.value = data.palet;

                        const modalCantidadInput = document.getElementById('cantidad_dynamic_edit');
                        modalCantidadInput.value = data.cantidad;

                    }

                },
                error: function(error) {
                }
            });
        }

        const saveDynamicInfo = () => {
            const cantidad_id = document.getElementById('id_dynamic_edit').value;
            const movimiento_id = document.getElementById('id_movimiento_dynamic_edit').value;
            const cantidad = document.getElementById('cantidad_dynamic_edit').value;

            $.ajax({
                type: "POST",
                url: '/solicitudes/requerimientos/dynamic-edit',
                headers: {
                    "X-CSRF-Token": document.querySelector('meta[name="csrf-token"]').content
                },
                data: {
                    "cantidad_id": cantidad_id,
                    "movimiento_id": movimiento_id,
                    "cantidad": cantidad,
                },
                dataType: "json",
                success: function({msg, data}) {
                    window.location.reload();
                },
                error: function(error) {
                }
            });


        }

    </script>

@endsection

@endsection
