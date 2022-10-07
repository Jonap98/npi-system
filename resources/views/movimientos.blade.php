@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('js')
    {{-- Selección dropdown --}}
    <script>
        function cambioParte(p) {
            const parte = p;

            let cleanedWord = parte.descripcion.replaceAll(`"`, "''");

            document.getElementById('id_parte').value=parte.id;
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
            
        }

        function getRowsCount() {
            document.getElementById("counter").value = rows + 1;
        }

    </script>
@endsection

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <span>Inventario nuevas partes</span>
                <hr>
                <div class="container">
                    <button class="btn btn-success" onclick="addRow()">Agregar parte</button>
                    <form name="movimientos" method="post" action="{{ route('store') }}">
                        @csrf
                        <div class="row mt-2 g-2">
                        <div class="d-flex flex-row-reverse">

                            <div class="w-25">
                                <select class="form-select" name="tipo">
                                    <option selected>Tipo</option>
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

                            {{-- <div class="w-25">
                                <select class="form-select" id="fila" name="fila">
                                    <option selected >Fila</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                </select>
                                @error('fila')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="p-2">
                                <span>Fila</span>
                            </div> --}}

                            {{-- <div class="w-25">
                                <select class="form-select" id="palet" name="palet">
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
                            </div> --}}
                            {{-- <div class="p-2">
                                <span>Palet</span>
                            </div> --}}

                                {{-- <div class="w-25">
                                    <select class="form-select" id="ubicacion" name="ubicacion">
                                        <option selected >Ubicación</option>
                                        @foreach ($ubicaciones as $ubicacion)
                                            <option value="{{$ubicacion->ubicacion}}"> {{ $ubicacion->ubicacion }} </option>
                                        @endforeach
                                    </select>
                                    @error('ubicacion')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="p-2">
                                    <span>Ubicación</span>
                                </div> --}}
                                
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
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                            <tr>
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
                                                {{-- <td>
                                                    <select class="form-select" id="fila" name="fila">
                                                        <option selected >Fila</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                        <option value="6">6</option>
                                                        <option value="7">7</option>
                                                        <option value="8">8</option>
                                                        <option value="9">9</option>
                                                        <option value="10">10</option>
                                                    </select>
                                                    @error('fila')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td> --}}

                                                {{-- <td> --}}
                                                    {{-- <button type="submit" class="btn btn-success btn-sm">Agregar</button> --}}
                                                    {{-- <button class="btn btn-success btn-sm" onclick="addRow()">Agregar</button> --}}
                                                {{-- </td> --}}
                                            </tr>
                                        </form>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
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
                $('#movimientos').DataTable();
            });
        </script>

    @endsection
@endsection