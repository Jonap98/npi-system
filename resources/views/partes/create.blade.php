@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-contnet-center">
            <div class="col-md-12">
                <span>Registrar partes</span>
                <hr>
                <div class="container">
                    <form name="partes" method="post" action="{{ route('partes.store') }}">
                        @csrf
                        <tr>
                            <div class="m-2">
                                <label>Proyecto</label>
                                <input type="text" class="form-control" name="proyecto" placeholder="Proyecto">
                                @error('proyecto')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="m-2">
                                <label>Número de parte</label>
                                <input type="text" class="form-control" name="numero_de_parte" placeholder="# de parte">
                                @error('numero_de_parte')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="m-2">
                                <label>Descripción</label>
                                <textarea class="form-control" name="descripcion" placeholder="Descripción..."></textarea>
                                @error('descripcion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="m-2">
                                <label>Unidad de medida</label>
                                <select class="form-select" name="um">
                                    <option selected>Unidades</option>
                                    <option value="Piezas">Piezas</option>
                                    <option value="IN">IN</option>
                                    <option value="KG">KG</option>
                                    <option value="M">M</option>
                                  </select>
                                  @error('um')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm m-2">Guardar</button>

                        </tr>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
