<div class="modal modal-lg fade" id="solicitarKit" tabindex="-1" aria-labelledby="solicitarKit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Solicitar MAKE</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">

                <div class="m-2">
                    <h5 id="status-team">Status - Team</h5>
                    <span>Contenido:</span>

                    <div id="parts" class="text-center">
                    </div>
                </div>

                <div class="m-2">
                    <label for="">Cantidad</label>
                    <input type="number" id="cantidad-registrada" name="cantidad" class="form-control">
                </div>

                <div class="m-2">
                    <label for="">Solicitante</label>
                    <input type="text" disabled id="solicitante" name="solicitante" class="form-control" value="{{ Auth::user()->name }}">
                    <input type="hidden" id="solicitante" name="solicitante" class="form-control" value="{{ Auth::user()->name }}">
                </div>

            </div>

            @if (Auth::user() && (Auth::user()->role == 'NPI-adm' || Auth::user()->role == 'NPI-eng' || Auth::user()->role == 'NPI-usr'))
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="solicitarRequerimiento()" data-bs-dismiss="modal">Solicitar</button>
                </div>
            @endif

        </div>
    </div>
</div>
