<div class="modal fade" id="solicitarKit{{ $kit->id }}" tabindex="-1" aria-labelledby="solicitarKit{{ $kit->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('requerimientos.solicitar') }}" method="POST">
                {{-- <form action="{{ route('requerimientos.solicitar') }}" method="POST"> --}}
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="solicitarKit{{ $kit->id }}">Solicitar Kit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="m-2">
                        <h5>{{ $kit->kit_nombre }}</h5>
                        <h6>{{ $kit->num_parte }}</h6>
                    </div>

                    <div class="m-2">
                        <label for="">Cantidad</label>
                        <input type="number" name="cantidad" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Solicitante</label>
                        <input type="text" id="solicitante" name="solicitante" class="form-control">
                    </div>

                    <input type="hidden" name="num_parte" value="{{ $kit->num_parte }}">

                    {{-- <span>¿Seguro que desea eliminar este registro?</span> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Solicitar</button>
                </div>
            </form>
        </div>
    </div>
</div>