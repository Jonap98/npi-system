<div class="modal fade" id="solicitudMaterial" tabindex="-1" aria-labelledby="solicitudMaterial" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="#" method="POST">
                {{-- <form action="{{ route('requerimientos.solicitar') }}" method="POST"> --}}
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="solicitudMaterial">Solicitar material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="m-2">
                        <label for="">Kit</label>
                        <select name="kit" id="kit" class="form-select">
                            @foreach ($kits as $kit)
                                <option value="{{ $kit->id }}">{{ $kit->kit_nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="m-2">
                        <label for="">Número de parte</label>
                        <input type="text" id="numero_parte" name="numero_parte" class="form-control">
                    </div> --}}

                    <div class="m-2">
                        <label for="">Solicitante</label>
                        <input type="text" id="solicitante" name="solicitante" class="form-control">
                    </div>

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