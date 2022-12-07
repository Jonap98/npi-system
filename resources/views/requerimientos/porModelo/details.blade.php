@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="md-3">
                <h5>Requerimientos por modelo detalles</h5>
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-between">

                    <div class="col-md-3">
                        <form method="POST"  action="{{ route('boms.import') }}" enctype="multipart/form-data" style="display: inline-flex">
                            @csrf
                            <div class="col-md-12">
                                <label for="">Cargar BOM</label>
                                <input type="file" name="import" class="form-control mt-2 mb-2" id="import" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                                <button class="btn btn-success" type="submit">Cargar</button> 
                            </div>
                        </form>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#solicitudMaterial">Solicitud de material</button>
                    </div>
                </div>

                <hr>
                <div class="container">
                    <div class="row">
                        @if (session('success'))
                            <div class="alert alert-success mt-2" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif
                        {{-- <form name="movimientos" method="post" action="#"> --}}
                            {{-- @csrf --}}
                            <div class="row g-2">
                                {{-- <div class="col m-2">
                                    <span>Proyecto</span>
                                </div> --}}
    
                                <div class="card col-md-12">
                                    <div class="mt-2 table-responsive">
    
                                        {{-- <table id="movimientos" class="table table-striped m-2"> --}}
                                        <table id="requerimientos" class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Kit nombre</th>
                                                    <th scope="col">Nivel</th>
                                                    <th scope="col">Num. parte</th>
                                                    <th scope="col">Selección</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @foreach ($modelos as $modelo) --}}
                                                <tr>
                                                    <td>kit_nombre</td>
                                                    <td>nivel</td>
                                                    <td>num_parte</td>
                                                    <td>
                                                        <input type="checkbox" name="" id="">
                                                    </td> 
                                                </tr>
                                                {{-- @endforeach --}}
                                            </tbody>
                                        </table>
                                        
                                    </div>
                                </div>
                            </div>
                    {{-- @include('requerimientos.manual.solicitar') --}}

                    </div>
                    {{-- </form> --}}
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
            $(document).ready(function () {
                $('#requerimientos').DataTable();
            });
        </script>

    @endsection
@endsection