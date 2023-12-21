@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <div class="d-flex justify-content-between">
                    <div class="md-3">
                        <span>Modelos</span>
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
                        @foreach ($modelos as $modelo)
                            <div class="col-auto mb-3">
                                <a href="{{ route('requerimientos.kit.modelo', $modelo->num_parte) }}" style="text-decoration: none">
                                    <div class="card" style="width: 18rem;">
                                        <div class="card-body col-xs-1 text-center">
                                            <img id="img{{ $modelo->id }}" src="" height="200" alt="" class="m-2 rounded">
                                            <h5 class="card-title">{{ $modelo->kit_nombre }} {{ $modelo->team }}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">{{ $modelo->num_parte }}</h6>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            {{-- @include('requerimientos.porModelo.store') --}}
                        @endforeach

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

            const modelosList = {!! json_encode($modelos) !!};

            const noModelImage = {!! json_encode( asset('assets/no-model2.jpg')) !!};

            modelosList.forEach(({num_parte, id}) => {

                const myRequest = new Request(`http://10.40.129.40:99/tkav/storage/${num_parte}.jpg`);

                fetch(myRequest).then(({status}) => {
                    const imagen = document.getElementById(`img${id}`);

                    if(status == 200) {
                        imagen.src = myRequest.url;
                    } else {
                        imagen.src = noModelImage;
                    }
                });
            });
        </script>

        <script>
            const getKits = (kit, id) => {

                $.ajax({
                    type: 'GET',
                    url: `/requerimientos/getkit/${kit}`,
                    success: function({data}) {
                        const parts = document.getElementById(`parts${id}`);
                        parts.innerHTML = '';
                        data.forEach(element => {
                            const span = document.createElement('span');
                            span.innerText = element.num_parte;
                            span.className = 'btn btn-secondary btn-sm m-2';
                            parts.appendChild(span);
                        });
                    }

                });

            }
        </script>
    @endsection
@endsection
