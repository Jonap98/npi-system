<div class="modal fade" id="solicitarKit{{ $kit->id }}" tabindex="-1" aria-labelledby="solicitarKit{{ $kit->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="makes{{ $kit->id }}" action="{{ route('requerimientos.solicitar') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="solicitarKit{{ $kit->id }}">Solicitar MAKE</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="m-2">
                        <h5>{{ $kit->status }}</h5>
                        <span>Contenido:</span>

                        <div id="parts{{ $kit->id }}" class="text-center">
                        </div>
                    </div>

                    <div class="m-2">
                        <label for="">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Solicitante</label>
                        <input type="text" disabled id="solicitante" name="solicitante" class="form-control" value="{{ Auth::user()->name }}">
                        <input type="hidden" id="solicitante" name="solicitante" class="form-control" value="{{ Auth::user()->name }}">
                    </div>

                    <input type="hidden" name="id" value="{{ $kit->id }}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Solicitar</button>
                </div>
            </form>
        </div>
    </div>
</div>