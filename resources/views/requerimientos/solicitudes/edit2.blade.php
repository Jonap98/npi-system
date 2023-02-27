<!-- Modal editar -->
<div class="modal fade" id="modalEdit{{ $ubicaciones->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div id="divPadre{{ $ubicaciones->id }}">

                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Confirmar editar {{ $ubicaciones->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="m-2">
                            <label>Cantidad</label>
                            <input type="text" id="cantidad{{ $ubicaciones->id }}" name="cantidad" class="form-control" value="{{ round($ubicaciones->cantidad, 0) }}">
                            <input type="hidden" id="cantidad_id{{ $ubicaciones->id }}" name="cantidad_id" class="form-control" value="{{ round($ubicaciones->id, 0) }}">

                        </div>

                        <div class="m-2">
                            <label>Número de parte</label>
                            <input type="text" id="num_parte{{ $ubicaciones->id }}" name="num_parte" class="form-control" value="{{ $ubicaciones->num_parte }}">
                        </div>

                        <div class="m-2">
                            <label>Ubicación</label>
                            <input type="text" disabled id="ubicacion_mostrada{{ $ubicaciones->id }}" name="ubicacion_mostrada" class="form-control" value="{{ $ubicaciones->ubicacion }} {{ $ubicaciones->palet }}">
                            <input type="hidden" id="ubicacion{{ $ubicaciones->id }}" name="ubicacion" class="form-control" value="{{ $ubicaciones->ubicacion }}">
                            <input type="hidden" id="palet{{ $ubicaciones->id }}" name="palet" class="form-control" value="{{ $ubicaciones->palet }}">
                            <input type="hidden" id="cantidad_en_ubicacion{{ $ubicaciones->id }}" name="cantidad_en_ubicacion" class="form-control" value="{{ $ubicaciones->palet }}">
                        </div>

                        <span>¿Seguro que desea editar cantidad de la ubicación: {{ $ubicaciones->ubicacion }}?</span>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" onclick="sendFormEdit('{{ $ubicaciones->id }}')">Guardar</button>
                    </div>
                
            </div>
        </div>
    </div>
</div>