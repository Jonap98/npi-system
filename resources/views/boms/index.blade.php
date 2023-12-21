@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <H5>BOMS</H5>
                <hr>
                @if(session('success'))
                    <div class="alert alert-success mt-2" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger mt-2" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="d-flex justify-content-between">

                    @if(Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng'))
                        <form method="POST"  action="{{ route('boms.import') }}" enctype="multipart/form-data" style="display: inline-flex">
                            @csrf
                            <div class="">
                                <label for="">Cargar BOM</label>
                                <input type="file" name="import" class="form-control mt-2 mb-2" id="import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                                <button class="btn btn-success" type="submit">Cargar</button>
                            </div>
                        </form>
                    @endif

                </div>

                <hr>
                <div class="m-2">
                    <span id="counter" class="ms-4"></span>
                </div>
                <div class="container">

                        <div class="row g-2">

                            <div class="card col-md-12 p-2">
                                <table id="boms" class="table table-striped m-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">Id</th>
                                            <th scope="col">Número de parte</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Nivel</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Ubicación</th>
                                            <th scope="col">Temp</th>
                                            @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng'))
                                                <th scope="col">Editar</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($boms as $bom)
                                            <tr>
                                                <td>{{ $bom->id }}</td>
                                                <td id="{{ $bom->id }}">{{ $bom->num_parte }}</td>
                                                <td>{{ $bom->kit_nombre }}</td>
                                                <td>{{ $bom->kit_descripcion }}</td>
                                                <td>{{ $bom->nivel }}</td>
                                                <td>{{ $bom->status }}</td>
                                                <td>{{ round($bom->cantidad, 2) }}</td>
                                                <td>{{ $bom->ubicacion }}</td>
                                                <td>{{ $bom->team }}</td>
                                                @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng'))
                                                    <td>
                                                        <button
                                                            class="btn btn-primary"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#edit"
                                                            onclick="loadData(
                                                                {{ $bom->id }},
                                                                '{{ $bom->num_parte }}',
                                                                '{{ $bom->kit_nombre }}',
                                                                '{{ $bom->kit_descripcion }}',
                                                                '{{ $bom->status }}',
                                                                '{{ round($bom->cantidad, 2) }}',
                                                                '{{ $bom->ubicacion }}',
                                                                '{{ $bom->team }}',
                                                            )">
                                                            Editar
                                                        </button>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng'))
                        {{-- @include('boms.modal') --}}
                        @include('boms.edit')
                    @endif
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
                $('#boms').DataTable({
                    columnDefs: [
                        { orderable: false }
                    ]
                });
            });
        </script>


        <script>
            let list = [];

            const container = document.getElementById('counter');
            const modal = document.getElementById('data');

            const formList = [];

            function agregarParte(parte) {

                const p = document.getElementById(parte);
                const numParte = p.innerText;

                let part = '';


                if(list.includes(parte)) {
                    list.splice(list.indexOf(parte), 1);

                    modal.removeChild(document.getElementById(parte));
                } else {
                    list.push(parte);

                    const numPartLabel = document.createElement('div');
                    numPartLabel.innerText = numParte;

                    const elementDiv = document.createElement('input');
                    elementDiv.className = 'form-control mb-2';
                    elementDiv.id = parte;
                    elementDiv.name = 'num_parte'+list.indexOf(parte);
                    elementDiv.value = parte;
                    elementDiv.hidden = true;

                    modal.append(numPartLabel);
                    modal.append(elementDiv);
                }

                // Counter
                const counter = document.createElement('input');
                    counter.hidden = true;
                    counter.name = 'counter';
                    counter.value = list.length;

                    modal.append(counter);

                container.innerHTML = `${list.length} registros agregados`;
            }

            function loadData(
                id,
                num_parte,
                kit_nombre,
                descripcion,
                status,
                cantidad,
                ubicacion,
                team,
            ) {
                const idInput = document.getElementById('id');
                idInput.value = id;

                const num_parteInput = document.getElementById('num_parte');
                num_parteInput.value = num_parte;

                const kit_nombreInput = document.getElementById('kit_nombre');
                kit_nombreInput.value = kit_nombre;

                const descripcionInput = document.getElementById('kit_descripcion');
                descripcionInput.value = descripcion;

                const statusInput = document.getElementById('status');
                statusInput.value = status;

                const cantidadInput = document.getElementById('cantidad');
                cantidadInput.value = cantidad;

                const ubicacionInput = document.getElementById('ubicacion');
                ubicacionInput.value = ubicacion;

                const teamInput = document.getElementById('team');
                teamInput.value = team;

            }
        </script>

    @endsection
@endsection
