<!-- Modal eliminar -->
<div class="modal fade" id="deleteModal{{ $usuario->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('usuarios.delete', $usuario->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span>¿Seguro que desea eliminar este usuario?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a  class="btn btn-danger" href="{{ route('usuarios.delete', $usuario->id) }}">Borrar</a>
                </div>
            </form>
        </div>
    </div>
</div>