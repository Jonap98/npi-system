@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <div class="md-3">
                        <span>Kits</span>
                    </div>

                    <div class="md-3">
                        <button class="btn btn-primary">Cargar BOM</button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solicitudMaterial">Solicitud de material</button>
                    </div>
                </div>

                <hr>

                <div class="container mt-4 d-flex justify-content-center">
                    <div class="row">

                        @foreach ($kits as $kit)
                            <div class="col-auto mb-3">
                                {{-- <a href="{{ route('requerimientos.kit.detalles', $kit->num_parte) }}"> --}}
                                <a href="" data-bs-toggle="modal" data-bs-target="#solicitarKit{{ $kit->id }}" >
                        {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solicitudMaterial">Solicitud de material</button> --}}
                                    
                                    <div class="card" style="width: 18rem;">
                                        <div class="card-body col-xs-1 text-center">
                                            <img src="https://m.media-amazon.com/images/I/71H-vvz0PXL._AC_SY879_.jpg" height="200" alt="" class="m-2">
                                            <h5 class="card-title">{{ $kit->kit_nombre }}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">{{ $kit->num_parte }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @include('requerimientos.porModelo.store')
                        @endforeach

                    </div>
                </div>


                <div class="container">
                    <div class="row">
                        {{-- <form name="movimientos" method="post" action="#"> --}}
                            {{-- @csrf --}}
                            <div class="row g-2">
                                {{-- <div class="col m-2">
                                    <span>Proyecto</span>
                                </div> --}}
    
                                <div class="d-flex justify-content-between">

                                    {{-- @foreach ($modelos as $modelo)
                                        <div class="col-md-6 mr-2 m-2">
                                            <a href="{{ route('requerimientos.kit', $modelo->num_parte) }}">
                                                <div class="card">
                                                    <canvas id="porDia"></canvas>
                                                </div>
                                                <span>{{ $modelo->num_parte }}</span>
                                            </a>
                                        </div>    
                                    @endforeach --}}

                                    {{-- <div class="col-md-6 mr-2 m-2">
                                        <div class="card">
                                            <canvas id="porDia"></canvas>
                                        </div>
                                        <span>W32187</span>
                                    </div>

                                    <div class="col-md-6 mr-2 m-2">
                                        <div class="card">
                                            <canvas id="porDia"></canvas>
                                        </div>
                                        <span>W12345</span>
                                    </div> --}}

                                </div>
                            </div>
                    {{-- @include('requerimientos.manual.index') --}}

                    </div>
                    {{-- </form> --}}
                </div>
            </div>
        </div>
    </div>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    @endsection
@endsection