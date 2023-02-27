@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <div class="md-3">
                        <span>Make</span>
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

                        @foreach ($makes as $kit)
                            <div class="col-auto mb-3">
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body col-xs-1 text-center">
                                        <img src="https://m.media-amazon.com/images/I/71H-vvz0PXL._AC_SY879_.jpg" height="200" alt="" class="m-2">
                                        <h5 class="card-title text-card" style="text-decoration: none">{{ $kit->kit_nombre }}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted text-card">{{ $kit->num_parte }}</h6>
                                    </div>
                                    <div class="mb-2 col-xs-1 text-center">
                                        <button class="btn btn-sm" style="background-color: #347aeb; color: #fff" data-bs-toggle="modal" data-bs-target="#solicitarKit{{ $kit->id }}">
                                            Solicitar Make
                                        </button>
                                    </div>
                                </div>
                            </div>


                            @include('requerimientos.porModelo.make.store')
                        @endforeach

                    </div>
                </div>

            </div>
        </div>
    </div>

    @section('js')
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>


        <script>
            window.addEventListener('load', (event) => {
                createElements();
            })

            const createElements = () => {
                const makesList = {!! json_encode($makes) !!};

                
                let index = 0;
                makesList.forEach(({details, id}) => {
                    const parts = document.getElementById(`parts${id}`);
                    parts.innerHTML = '';

                    const formulario = document.getElementById('makes');

                    details.forEach(({num_parte, id}) => {

                        const span = document.createElement('span');
                        span.innerText = num_parte;
                        span.className = 'btn btn-secondary btn-sm m-2';
                        parts.appendChild(span);

                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `num_parte${index}`;
                        input.value = num_parte;

                        formulario.appendChild(input);

                        index++;
                    });
                });

            }
        </script>
    @endsection
@endsection