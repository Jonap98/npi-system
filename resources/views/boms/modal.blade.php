<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalupdate" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('boms.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="modalupdate">Confirmar selección</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="m-2">
                        {{-- <label>Asignación de: {{ Auth::user()->name }}</label> --}}
                        <div id="data"></div>
                    </div>

                    <span>¿Seguro que desea cambiar el requerimiento de estos numeros?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>