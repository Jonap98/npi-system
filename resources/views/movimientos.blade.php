@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <span>Inventario nuevas partes</span>
                <hr>
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-success" onclick="addRow()">Agregar parte</button>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#storeUbicacion">
                            Crear ubicación
                        </button>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success mt-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form name="movimientos" method="post" action="{{ route('store') }}">
                        @csrf
                        <div class="row mt-2 g-2">
                        <div class="d-flex flex-row-reverse">

                            <div class="w-25">
                                <select class="form-select" name="tipo">
                                    <option value="Entrada" selected>Tipo</option>
                                    <option value="Entrada" class="btn btn-success">Entrada</option>
                                    <option value="Salida" class="btn btn-danger">Salida</option>
                                    <!-- <option value="Ajuste" class="btn btn-warning">Ajuste</option> -->
                                </select>
                                @error('tipo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="p-2">
                                <span>Tipo de movimiento</span>
                            </div>
                                
                            </div>

                            <div class="card col-md-12">
                                <input type='text' class="form-control" hidden  id="counter" name="counter" />
                                <div class="d-flex flex-row-reverse">
                                    <button class="btn btn-primary m-4 col-md-2" onclick="getRowsCount()" id="mybutton">Guardar</button>
                                </div>
                                <table class="table table-striped m-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">Número de parte</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Unidad de medida</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Comentario</th>
                                            <th scope="col">Ubicación</th>
                                            {{-- <th scope="col">Fila</th> --}}
                                            <th scope="col">Palet</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                            <tr id="row0">
                                                <td>
                                                    <div class="dropdown">
                                                        <div id="myDropdown" class="dropdown-content">
                                                            <input class="form-select" type="text" placeholder="# de parte" id="myInput" onkeyup="filterFunction()" autocomplete="off">
                                                                @foreach ($partes as $p)
                                                                    <option style="display: none" id="parte" class="partOption" onclick="cambioParte({{$p}})" value="{{ $p }}">{{ $p->numero_de_parte }}</option>
                                                                @endforeach
                                                        </div>
                                                    </div>
                                                    @error('parte0')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type='text' class="form-control" disabled id='descripcion' name="descripcion0" />

                                                    <input type='text'  class="form-control" hidden  id='proyecto' name="proyecto0" wire:model="proyecto" />
                                                    <input type='text'  class="form-control" hidden id='id_parte' name="id_parte0" />
                                                </td>
                                                <td>
                                                    <input type='text' class="form-control" disabled id='unidad_de_medida' name="unidad_de_medida0" />
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="cantidad" name="cantidad0" wire:model="cantidad">
                                                    @error('cantidad0')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <textarea class="form-control" name="comentario0" wire:model="comentario"></textarea>
                                                    @error('comentario0')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select class="form-select" id="ubicacion" name="ubicacion0" aria-placeholder="Ubicación">
                                                        <option selected >Ubicación</option>
                                                        @foreach ($ubicaciones as $ubicacion)
                                                            <option value="{{$ubicacion->ubicacion}}"> {{ $ubicacion->ubicacion }} </option>
                                                        @endforeach
                                                    </select>
                                                    @error('ubicacion')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select class="form-select" id="palet" name="palet0">
                                                        <option selected >Palet</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                    @error('palet')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger" onclick="deleteRow('0')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                                          </svg>
                                                    </button>
                                                </td>
                                            </tr>
                                        </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    @include('ubicaciones.store')

                </div>
            </div>
        </div>
    </div>

    
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#movimientos').DataTable();
        });
    </script>

    {{-- Selección dropdown --}}
    <script>
        function cambioParte(p) {
            const parte = p;

            let cleanedWord = parte.descripcion.replaceAll(`"`, "''");

            document.getElementById('id_parte').value=parte.id;
            document.getElementById('myInput').value=parte.numero_de_parte;
            document.getElementById('proyecto').value=parte.proyecto;
            document.getElementById('descripcion').value=cleanedWord;
            document.getElementById('unidad_de_medida').value=parte.um;

            const option = document.getElementsByClassName("partOption")
            for (i = 0; i < option.length; i++) {
                option[i].style.display = "none";
            }
        }

        function filterFunction() {
            var input, filter, ul, li, option, i;
            input = document.getElementById("myInput");
            filter = input.value.toUpperCase();
            div = document.getElementById("myDropdown");
            option = div.getElementsByTagName("option");
            
            for (i = 0; i < option.length; i++) {
                txtValue = option[i].textContent || option[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1 ) {
                    option[i].style.display = "";
                } else {
                    option[i].style.display = "none";
                }
                if(document.getElementById("myInput").value == "" || document.getElementById("myInput").value == null) {
                    option[i].style.display = "none";
                }
            }
        }
    </script>

    <script>
        let rows = 0;

        function deleteRow(index) {
            const row = document.getElementById(`row${index}`);
            row.remove();

            // console.log(index);
        }

        function addRow() {
            rows++;
            
            const tbody = document.getElementById("tbody");
            let row = tbody.insertRow(0);
            
            let cell1 = row.insertCell(0);
            let cell2 = row.insertCell(1);
            let cell3 = row.insertCell(2);
            let cell4 = row.insertCell(3);
            let cell5 = row.insertCell(4);
            let cell6 = row.insertCell(5);
            let cell7 = row.insertCell(6);
            let cell8 = row.insertCell(7);
            
            row.setAttribute('id', `row${rows}`);

            cell1.innerHTML = `
            <div class="dropdown">
                <div id="myDropdown" class="dropdown-content">
                    <input class="form-select" type="text" placeholder="# de parte" id="myInput" onkeyup="filterFunction()" autocomplete="off">
                        @foreach ($partes as $p)
                        <option style="display: none" id="parte" class="partOption" onclick="cambioParte({{$p}})" value="{{ $p }}">{{ $p->numero_de_parte }}</option>
                        @endforeach
                </div>
            </div>
            @error('parte${rows}')
                <span class="text-danger">{{ $message }}</span>
            @enderror`;

            cell2.innerHTML = `
            <input type='text' class="form-control" disabled id='descripcion' name="descripcion${rows}" />

            <input type='text'  class="form-control" hidden  id='proyecto' name="proyecto${rows}" wire:model="proyecto" />
            <input type='text'  class="form-control" hidden id='id_parte' name="id_parte${rows}" />
            `

            cell3.innerHTML = `
            <input type='text' class="form-control" disabled id='unidad_de_medida' name="unidad_de_medida${rows}" />
            `

            cell4.innerHTML = `
            <input type="number" class="form-control" id="cantidad" name="cantidad${rows}" wire:model="cantidad">
                                                    @error('cantidad${rows}')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
            `

            cell5.innerHTML = `
            <textarea class="form-control" name="comentario${rows}" wire:model="comentario"></textarea>
                                                    @error('comentario${rows}')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
            `

            cell6.innerHTML = `
            <select class="form-select" id="ubicacion" name="ubicacion${rows}" aria-placeholder="Ubicación">
                                                    <option selected >Ubicación</option>
                                                    @foreach ($ubicaciones as $ubicacion)
                                                        <option value="{{$ubicacion->ubicacion}}"> {{ $ubicacion->ubicacion }} </option>
                                                    @endforeach
                                                </select>
                                                @error('ubicacion${rows}')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
            `

            cell7.innerHTML = `
            <select class="form-select" id="palet" name="palet${rows}">
                                                        <option selected >Palet</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                    </select>
                                                    @error('palet${rows}')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
            `

            cell8.innerHTML = `
            <td>
                                                    <button type="button" class="btn btn-danger" onclick="deleteRow('${rows}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                                        </svg>
                                                    </button>
                                                </td>
            `
            
        }

        function getRowsCount() {
            document.getElementById("counter").value = rows + 1;
        }

    </script>
@endsection