<!-- Modal editar -->
<div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            {{-- <form method="POST" action="{{ route('solicitud.requerimientos.delete') }}">
                @csrf --}}

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar eliminar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="m-2">
                        <h4>¿Seguro que desea eliminar este requerimiento? Folio: <b id="folio-span"></b> </h4>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>

                        <input type="hidden" id="folio-delete" name="folio">
                        <button type="button" class="btn btn-danger" onclick="deleteFolio()">Eliminar</button>
                    </div>

                </div>
            {{-- </form> --}}


        </div>
    </div>
</div>
