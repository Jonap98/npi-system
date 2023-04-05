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
                        <span>kits - <b>{{ $modelo }}</b> </span>
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
                                            {{-- <img src="not-found.png" /> --}}
                                            {{-- <img id="img{{ $kit->id }}" src="/tkav/storage/{{$kit->num_parte}}.jpg" alt="" height="200" class="m-2"> --}}
                                            <img id="img{{ $kit->id }}" src="" height="200" alt="" class="m-2 rounded">
                                            <h5 class="card-title text-card" style="text-decoration: none">{{ $kit->kit_nombre }}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted text-card">{{ $kit->num_parte }}</h6>
                                        </div>
                                        <div class="mb-2 col-xs-1 text-center">
                                            <a class="btn btn-sm" href="{{ route('requerimientos.kit.make', $kit->id) }}" style="text-decoration: none; background-color: #eb8c34; color: #fff">
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

            const kitsList = {!! json_encode($kits) !!};

            const noPartImage = {!! json_encode( asset('assets/objects.png')) !!};

            kitsList.forEach(({num_parte, id}) => {


                const myRequest = new Request(`http://10.40.129.40:99/tkav/storage/${num_parte}.jpg`);

                fetch(myRequest).then(({status}) => {
                    const imagen = document.getElementById(`img${id}`);

                    if(status == 200) {
                        imagen.src = myRequest.url;
                        imagen.className = 'mb-2 rounded object-fit-cover w-100';
                    } else {
                        imagen.src = noPartImage;
                    }
                    // response.blob().then((myBlob) => {

                    //     // const objectURL = URL.createObjectURL(myBlob);
                    //     // myImage.src = objectURL;
                    // });
                });

            });
            
        </script>

        <script>
            const showKits = (id) => {

                $.ajax({
                    type: 'GET',
                    url: `/requerimientos/${id}/showKits`,
                    success: function({data}) {

                        const parts = document.getElementById(`parts${id}`);
                        parts.className = 'text-center';
                        parts.innerHTML = '';

                        const table = document.createElement('table');
                        table.className = 'table table-striped';

                        const tbody = document.createElement('tbody');
                        table.appendChild(tbody);
                        parts.appendChild(table);

                        data.forEach(({kit_descripcion, num_parte, cantidad}) => {

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
                            // span.className = 'm-2 btn btn-secondary btn-sm m-1';
                        
                            // parts.appendChild(span);
                        });
                    },

                });
            }
        </script>

    @endsection
@endsection