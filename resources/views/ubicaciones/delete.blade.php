<!-- Modal eliminar -->
<div class="modal fade" id="deleteModal{{ $ubicacion->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <span>¿Seguro que desea eliminar la ubicación: {{ $ubicacion->ubicacion }}?</span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a  class="btn btn-danger" href="{{ route('ubicaciones.delete', $ubicacion->id) }}">Borrar</a>
            </div>
        </div>
    </div>
</div>