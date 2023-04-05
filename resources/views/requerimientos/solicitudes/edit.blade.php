<!-- Modal editar -->
<div class="modal fade" id="modalEdit{{ $ubicaciones->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('solicitud.requerimientos.edit') }}">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar editar {{ $ubicaciones->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
    
                <div class="modal-body">
    
                    <div class="m-2">
                        <label>Cantidad</label>
                        {{-- <input type="text" id="cantidad{{ $ubicaciones->id }}" name="cantidad" class="form-control" value="{{ round($ubicaciones->cantidad, 0) }}"> --}}
                    </div>
    
                    <span>¿Seguro que desea editar cantidad de la ubicación: {{ $ubicaciones->ubicacion }}?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    {{-- <button type="button" class="btn btn-primary" onclick="sendFormEdit('{{ $ubicaciones->id }}')">Guardar</button> --}}
                </div>

            </form>
            
        </div>
    </div>
</div>