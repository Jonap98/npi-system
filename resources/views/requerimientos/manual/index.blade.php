@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')

    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <span>Requerimientos</span>
                <hr>
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center">
                        <button class="btn btn-success" onclick="addRow()">Agregar parte</button>
                        <button class="btn btn-primary m-4 col-md-2" data-bs-toggle="modal" data-bs-target="#confirm">Guardar</button>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success mt-2" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <form id="requerimientos-form" method="POST" action="{{ route('requerimientos.manual.solicitar') }}">
                        @csrf
                        <div class="row mt-2 g-2">


                            <div class="card col-md-12">
                                <input type="text" class="form-control" hidden  id="counter" name="counter" />
                                <div class="d-flex justify-content-between">
                                </div>
                                <table class="table table-striped m-2">
                                    <thead>
                                        <tr>
                                            <th scope="col">Número de parte</th>
                                            <th scope="col">Descripción</th>
                                            <th scope="col">Unidad de medida</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                            <tr id="row0">
                                                <td>
                                                    <div class="dropdown">
                                                        <div id="myDropdown" class="dropdown-content">
                                                            <input class="form-select" type="text" placeholder="# de parte" id="myInput" name="num_parte0" onkeyup="filterFunction()" autocomplete="off">
                                                                @foreach ($partes as $p)
                                                                    <option style="display: none" id="parte" class="partOption" onclick="cambioParte({{ $p->id }}, '{{ $p->kit_nombre }}', '{{ $p->num_parte }}', '{{ $p->kit_descripcion }}', {{ $p->nivel }}, '{{ $p->um }}' )" value="{{ $p }}">{{ $p->num_parte }}</option>
                                                                @endforeach
                                                        </div>
                                                    </div>
                                                    @error('parte0')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type='text' class="form-control" disabled id='descripcion' name="descripcion0" />
                                                    <input type='text' class="form-control" hidden id='descripcion' name="descripcion0" />

                                                    <input type='text'  class="form-control" hidden id='id_parte' name="id_parte0" />
                                                </td>
                                                <td>
                                                    <input type='text' class="form-control" disabled id='unidad_de_medida' name="unidad_de_medida0" />
                                                    <input type='text' class="form-control" hidden id='unidad_de_medida' name="unidad_de_medida0" />
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control" id="cantidad_requerida" name="cantidad_requerida0" wire:model="cantidad_requerida">
                                                    @error('cantidad_requerida0')
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
                    @include('requerimientos.manual.confirm')

                </div>
            </div>
        </div>
    </div>


@endsection

@section('js')

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
        // function cambioParte(p) {
        function cambioParte(id, kit_nombre, num_parte, kit_descripcion, nivel, um) {

            let cleanedWord = kit_descripcion.replaceAll(`"`, "''");

            document.getElementById('id_parte').value=id;
            document.getElementById('myInput').value=num_parte;
            document.getElementById('descripcion').value=cleanedWord;
            document.getElementById('unidad_de_medida').value=um;

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

            row.setAttribute('id', `row${rows}`);

            cell1.innerHTML = `
            <div class="dropdown">
                <div id="myDropdown" class="dropdown-content">
                    <input class="form-select" type="text" placeholder="# de parte" id="myInput" name="num_parte${rows}" onkeyup="filterFunction()" autocomplete="off">
                        @foreach ($partes as $p)
                        <option style="display: none" id="parte" class="partOption" onclick="cambioParte({{ $p->id }}, '{{ $p->kit_nombre }}', '{{ $p->num_parte }}', '{{ $p->kit_descripcion }}', {{ $p->nivel }}, '{{ $p->um }}' )" value="{{ $p }}">{{ $p->num_parte }}</option>
                        @endforeach
                </div>
            </div>
            @error('parte${rows}')
                <span class="text-danger">{{ $message }}</span>
            @enderror`;

            cell2.innerHTML = `
            <input type='text' class="form-control" disabled id='descripcion' name="descripcion${rows}" />

            <input type='text'  class="form-control" hidden id='id_parte' name="id_parte${rows}" />
            `

            cell3.innerHTML = `
            <input type='text' class="form-control" disabled id='unidad_de_medida' name="unidad_de_medida${rows}" />
            `

            cell4.innerHTML = `
            <input type="number" class="form-control" id="cantidad_requerida" name="cantidad_requerida${rows}" wire:model="cantidad">
                                                    @error('cantidad_requerida${rows}')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
            `

            cell5.innerHTML = `
            <td>
                                                    <button type="button" class="btn btn-danger" onclick="deleteRow('${rows}')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                                        </svg>
                                                    </button>
                                                </td>
            `

        }

        function confirm() {
            document.getElementById('confirmButton').setAttribute('disabled', 'true');

            document.getElementById("counter").value = rows + 1;

            const formulario = document.getElementById('requerimientos-form').submit();
        }

    </script>
@endsection
