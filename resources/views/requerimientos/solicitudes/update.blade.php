<div class="modal fade" id="update{{ $requerimiento->id }}" tabindex="-1" aria-labelledby="update{{ $requerimiento->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('solicitud.requerimientos.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="title{{ $requerimiento->id }}"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="m-2">
                        <label id="confirmationMessage{{ $requerimiento->id }}" for=""></label>
                    </div>

                    <input type="hidden" id="action{{ $requerimiento->id }}" name="action">
                    <input type="hidden" name="id" value="{{ $requerimiento->id }}">
                    <input type="hidden" name="folio" id="folio{{ $requerimiento->id }}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>
