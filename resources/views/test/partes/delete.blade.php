<!-- Modal eliminar -->
<div class="modal fade" id="deleteModal{{ $parte->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('test.partes.update', $parte->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> <b>TEST</b> Confirmar eliminar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>¿Seguro que desea eliminar este registro?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a  class="btn btn-danger" href="{{ route('partes.delete', $parte->id) }}">Borrar</a>
                </div>
            </form>
        </div>
    </div>
</div>