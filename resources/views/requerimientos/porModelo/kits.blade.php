@extends('layouts.app')

@section('css')
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous"> --}}
@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <div class="md-3">
                        <span>kits</span>
                    </div>

                    <div class="md-3">
                        <button class="btn btn-primary">Cargar BOM</button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solicitudMaterial">Solicitud de material</button>
                    </div>
                </div>

                <hr>

                @if(session('success'))
                    <div class="alert alert-success mt-2" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="container mt-4 d-flex justify-content-center">
                    <div class="row">

                        @foreach ($kits as $kit)
                            <div class="col-auto mb-3">
                                    <div class="card" style="width: 18rem;">
                                        <div class="card-body col-xs-1 text-center">
                                            <img src="https://m.media-amazon.com/images/I/71H-vvz0PXL._AC_SY879_.jpg" height="200" alt="" class="m-2">
                                            <h5 class="card-title text-card" style="text-decoration: none">{{ $kit->kit_nombre }}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted text-card">{{ $kit->num_parte }}</h6>
                                        </div>
                                        <div class="mb-2 col-xs-1 text-center">
                                            <a class="btn btn-sm" href="{{ route('requerimientos.kit.make', $kit->num_parte) }}" style="text-decoration: none; background-color: #eb8c34; color: #fff">
                                                Solicitar MAKE
                                            </a>
                                            <button class="btn btn-sm" style="background-color: #347aeb; color: #fff" onclick="showKits('{{ $kit->id }}')" data-bs-toggle="modal" data-bs-target="#solicitarKit{{ $kit->id }}">
                                                Solicitar KIT
                                            </button>
                                        </div>
                                    </div>
                            </div>
                            @include('requerimientos.porModelo.store')
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

        <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

        <script>
            const showKits = (id) => {

                $.ajax({
                    type: 'GET',
                    url: `/requerimientos/${id}/showKits`,
                    success: function({data}) {

                        const parts = document.getElementById(`parts${id}`);
                        parts.className = 'text-center';
                        parts.innerHTML = '';

                        data.forEach(element => {
                            const span = document.createElement('span');
                            span.innerText = element.num_parte;
                            span.className = 'btn btn-secondary btn-sm m-1';
                        
                            parts.appendChild(span);
                        });
                    },

                });
            }
        </script>

    @endsection
@endsection