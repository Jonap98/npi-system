<div class="modal fade" id="edit{{ $bom->id }}" tabindex="-1" aria-labelledby="edit{{ $bom->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('boms.edit.name') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="edit{{ $bom->id }}">Editar nombre</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <div class="m-2">
                        <label class="d-block" for="">{{ $bom->num_parte }}</label>
                        <label class="d-block" for="">{{ $bom->kit_descripcion }}</label>
                    </div>

                    <input type="hidden" name="id" value="{{ $bom->id }}">

                    <div class="m-2">
                        <label for="">Cantidad</label>
                        <input type="nombre" name="nombre" class="form-control" value="{{ $bom->kit_nombre }}">
                    </div>

                    {{-- <span>¿Seguro que desea eliminar este registro?</span> --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>