@extends('layouts.app')

@section('css')

@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <div class="md-3">
                        <span>Make - <b>{{ $make }} {{ $temp }}</b> </span>
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
                                    <div class="card col-xs-1 text-center">
                                        <img id="img{{ $kit->id }}" src="" height="200" alt="" class="m-2">
                                        <h5 class="card-title text-card" style="text-decoration: none">{{ $kit->status }}</h5>
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

                const noPartImage = {!! json_encode( asset('assets/objects.png')) !!};

                console.log(makesList);

                makesList.forEach(({num_parte, details, id}) => {
                    const parts = document.getElementById(`parts${id}`);
                    parts.innerHTML = '';

                    const table = document.createElement('table');
                    table.className = 'table table-striped';

                    const tbody = document.createElement('tbody');
                    table.appendChild(tbody);
                    parts.appendChild(table);

                    const formulario = document.getElementById(`makes${id}`);

                    console.log(num_parte);
                    const myRequest = new Request(`http://10.40.129.40:99/tkav/storage/${num_parte}.jpg`);

                    fetch(myRequest).then(({status}) => {
                        const imagen = document.getElementById(`img${id}`);

                        if(status == 200) {
                            imagen.src = myRequest.url;
                            imagen.className = 'mb-2 rounded object-fit-cover w-100';
                        } else {
                            imagen.src = noPartImage;
                        }
                    });

                    let index = 0;
                    details.forEach(({kit_descripcion, num_parte, id, cantidad}) => {

                        const row = document.createElement('tr');

                        const partField = document.createElement('td');
                        partField.innerText = num_parte;

                        const descField = document.createElement('td');
                        descField.innerText = kit_descripcion;

                        const countField = document.createElement('td');
                        countField.innerText = Math.round(cantidad);

                        row.appendChild(partField);
                        row.appendChild(descField);
                        row.appendChild(countField);

                        tbody.appendChild(row);

                        // const span = document.createElement('span');
                        // span.innerText = `${kit_descripcion}\n${num_parte}: ${Math.round(cantidad)}`;
                        // span.className = 'btn btn-secondary btn-sm m-2';
                        // parts.appendChild(span);

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
