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
                                  </select>
                                  @error('um')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- <div class="m-2">
                                <label>Ubicación</label>
                                <select class="form-select" id="ubicacion" name="ubicacion">
                                    <option selected >Ubicacion</option>
                                    @foreach ($ubicaciones as $ubicacion)
                                        <option value="{{$ubicacion->ubicacion}}"> {{ $ubicacion->ubicacion }} </option>
                                    @endforeach
                                </select>
                                @error('ubicacion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> -->

                            <!-- <div class="m-2">
                                <label>Palet</label>
                                <select class="form-select" name="palet">
                                    <option selected>Palet</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                                    <option value="3">6</option>
                                    <option value="3">7</option>
                                  </select>
                                  @error('palet')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> -->

                            <!-- <div class="m-2">
                                <label>Fila</label>
                                <select class="form-select" name="fila">
                                    <option selected>Fila</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="3">4</option>
                                    <option value="3">5</option>
                                    <option value="3">6</option>
                                    <option value="3">7</option>
                                  </select>
                                  @error('fila')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div> -->
                            
                            <button type="submit" class="btn btn-primary btn-sm m-2">Guardar</button>

                        </tr>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
@endsection