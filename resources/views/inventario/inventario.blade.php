@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <span>Inventario</span>
            <a href="{{ route('exportar.inventario') }}" class="btn btn-success btn-sm ms-5">Exportar excel</a>
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
                                        <th scope="col">Descripcióin</th>
                                        <th scope="col">Unidad de medida</th>
                                        <th scope="col">Inventario</th>
                                        <th scope="col">Ubicación</th>
                                        {{-- <th scope="col">Palet</th>
                                        <th scope="col">Fila</th> --}}
                                        <th scope="col">Imagen</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventarios as $inventario)
                                        <tr>
                                            <td>{{ $inventario->id }}</td>
                                            <td>{{ $inventario->proyecto }}</td>
                                            <td>{{ $inventario->numero_de_parte }}</td>
                                            <td>{{ $inventario->descripcion }}</td>
                                            <td>{{ $inventario->um }}</td>
                                            <td>{{ $inventario->cantidad }}</td>
                                            {{-- <td>{{ $inventario->ubicacion }}</td> --}}

                                            {{-- <td>{{ $inventario->ubicaciones[0]->ubicacion ?? 'NA'  }}</td> --}}

                                            <td>
                                                {{-- <select name="" id="" class="form-select"> --}}
                                                    @foreach($inventario->ubicaciones as $ubi)
                                                    <div>
                                                        {{ $ubi->ubicacion }},{{ $ubi->palet }},{{ $ubi->fila }}
                                                    </div>
                                                    @endforeach 
                                                {{-- </select> --}}
                                                {{-- @if ($inventario->ubicaciones) --}}
                                                
                                                    
                                                {{-- @endif --}}
                                            </td>
                                            
                                            {{-- <td>{{ ($inventario->ubicaciones) ? 
                                                $inventario->ubicaciones[count($inventario->ubicaciones)-1]->ubicacion
                                            : 's' }}</td> --}}
                                            {{-- <td>{{ $inventario->palet }}</td>
                                            <td>{{ $inventario->fila }}</td> --}}
                                            <td>
                                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#inventarioModal{{ $inventario->id }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-image" viewBox="0 0 16 16">
                                                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                                                        <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z"/>
                                                    </svg>
                                                </button>
                                            </td>
                                        </tr>
                                        @include('inventario.image')

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
            $('#consulta').DataTable({
                order: [0, 'desc']
            });
        });
    </script>

@endsection

@endsection