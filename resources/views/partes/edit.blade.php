<!-- Modal editar -->
<div class="modal fade" id="editModal{{ $parte->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('partes.update', $parte->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar parte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="m-2">
                        <label>Número de parte</label>
                        <input type="text" class="form-control" name="numero_de_parte" placeholder="# de parte" value="{{ $parte->numero_de_parte }}">
                        @error('numero_parte')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Descripción</label>
                        <textarea class="form-control" name="descripcion" placeholder="Descripción..."  > {{ $parte->descripcion }} </textarea>
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Unidad de medida</label>
                        <select class="form-select" name="um">
                            <option selected>{{ $parte->um }}</option>
                            <option value="Piezas">Piezas</option>
                            <option value="IN">IN</option>
                            <option value="KG">KG</option>
                          </select>
                          @error('um')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Ubicación</label>
                        <select class="form-select" id="ubicacion" name="ubicacion">
                            <option selected >{{ $parte->ubicacion }}</option>
                            @foreach ($ubicaciones as $ubicacion)
                                <option value="{{$ubicacion->ubicacion}}"> {{ $ubicacion->ubicacion }} </option>
                            @endforeach
                        </select>
                        @error('ubicacion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Palet</label>
                        <select class="form-select" name="palet">
                            <option selected>{{ $parte->palet }}</option>
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
                    </div>

                    <div class="m-2">
                        <label>Fila</label>
                        <select class="form-select" name="fila">
                            <option selec   ted>{{ $parte->fila }}</option>
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
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>