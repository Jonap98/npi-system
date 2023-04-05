<div class="modal modal-xl fade" id="solicitarKit{{ $kit->id }}" tabindex="-1" aria-labelledby="solicitarKit{{ $kit->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('requerimientos.kit.solicitarKits') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="solicitarKit{{ $kit->id }}">Solicitar Kit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="m-2">
                        <h5>{{ $kit->kit_nombre }} - {{ $kit->team }}</h5>
                        <input type="hidden" name="kit_nombre" value="{{ $kit->kit_nombre }}">
                        <span>Contenido:</span>

                        <div id="parts{{ $kit->id }}"></div>
                    </div>

                    <div class="m-2">
                        <label for="">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Solicitante</label>
                        <input type="text" disabled id="solicitante" name="solicitante" class="form-control" value="{{ Auth::user()->name }}">
                        <input type="hidden" id="solicitante" name="solicitante" class="form-control" value="{{ Auth::user()->name }}">
                        <input type="hidden" id="id" name="id" class="form-control" value="{{ $kit->id }}">
                    </div>

                    <input type="hidden" name="num_parte" value="{{ $kit->num_parte }}">
                    <input type="hidden" name="team" value="{{ $kit->team }}">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Solicitar</button>
                </div>
            </form>
        </div>
    </div>
</div>