<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{ asset('css/pdf-styles.css') }}"> --}}
    <style>
        body, th, td{
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }
        body {
            padding-left: 2rem;
            padding-right: 2rem;
        }
        th {
            border: 1px solid;
            text-align: center;
            /* max-width: 200px; */
        }
        td {
            text-align: center;
            /* max-width: 200px; */
        }
        .header {
            /* margin: auto;
            width: 75%;
            padding-top: 10px; */
            display: flex;
            justify-content: center;
        }
        .header:h1 {
            color: #9e1b15;
        }
        span {
            font-size: 10px;
        }
        b {
            font-size: 10px;
        }
        span.head {
            font-size: 12px;
        }
        span.title {
            font-size: 20px;
            font-weight: bold;
            color:#9e1b15;
            margin-right: 20%;
        }
        .block {
            display: block;
        }
        .head {
            margin-right: 20px;
        }
        .flx-container {
            display: flex;
        }
        table tr td {
            width: 1%;
            white-space: nowrap;
            padding: 2px;
        }
        .ubicacion {
            white-space: normal;
        }

    </style>
    <title>Vista previa Solicitud de material NPI</title>
</head>
<body>
    <div class="header">
        <div class="flx-container">
            <div class="inline">
                <span class="title">Vista previa solicitud de material</span>
                {{-- <span class="title">Solicitud de material NPI: {{ $kit }} - SKU {{ $team }}</span> --}}
                {{-- <img src="{{ asset('assets/whirlpool-logo.png') }}" alt="" width="50px"> --}}
                {{-- <img src="https://m.media-amazon.com/images/I/71H-vvz0PXL._AC_SY879_.jpg" height="200" alt="" class="m-2"> --}}
            </div>
            <div class="block">
                <div class="col">
                    {{-- <span class="head"><b>Fecha de impresión: </b>{{ Carbon\Carbon::now('America/Monterrey')->format('d-m-y H:i') }}</span> --}}
                    {{-- <span class="head"><b>Folio: </b>{{ $folio }}</span>
                    <span class="head"><b>Solicitante: </b>{{ $solicitante }}</span>
                    <span class="head"><b>Cantidad total: </b>{{ $count }}</span> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="py-3 table-responsive">
        <table class="table table-striped" id="produccion">
            <thead>
                {{-- <th>Make</th> --}}
                <th>Número de parte</th>
                <th>Descripción</th>
                <th>Kit Nombre</th>
                {{-- <th class="ubicacion">Ubicación</th> --}}
                <th>Cantidad requerida</th>
            </thead>

            <tbody>
                @foreach ($requerimientos as $requerimiento)
                    <tr>
                        {{-- <td class=""><span>{{ $requerimiento->folio }}</span></td> --}}
                        <td class=""><span>{{ $requerimiento['num_parte'] }}</span></td>
                        <td class=""><span>{{ $requerimiento['kit_descripcion'] }}</span></td>
                        <td class=""><span>{{ $requerimiento['kit_nombre'] }}</span></td>
                        {{-- <td class=""><span>{{ $requerimiento['ubicacion'] }}</span></td> --}}
                        <td class=""><span>{{ round($requerimiento['cantidad'], 0) }}</span></td>
                        {{-- <td class=""><span>{{ $requerimiento['kit_nombre'] }}</span></td> --}}
                        {{-- <td class=""><span>{{ $requerimiento->num_parte }}</span></td>
                        <td class=""><span>{{ $requerimiento->descripcion }}</span></td>
                        <td class="" class="ubicacion"><span>{{ $requerimiento->ubicacion }}</span></td>
                        <td class=""><span>{{ round($requerimiento->cantidad_requerida, 0) }}</span></td>
                        <td class="">
                            @foreach ($requerimiento->ubicaciones_registradas as $ubicaciones)
                                <div>
                                    <span>
                                        {{ $ubicaciones->ubicacion }}:
                                    </span>
                                    <b>
                                        @foreach ($ubicaciones->palets_registrados as $palets)
                                            {{ $palets->palet }},
                                        @endforeach
                                    </b>
                                </div>
                            @endforeach
                        </td> --}}
                        {{-- <td class=""><span>{{ $requerimiento->solicitante }}</span></td> --}}
                        {{-- <td class=""><span>{{ $requerimiento->created_at->format('d-m-Y H:i') }}</span></td> --}}
                        {{-- <td class=""><span>{{ substr($requerimiento->created_at, 0, 10) }}</span></td> --}}
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
