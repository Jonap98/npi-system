<!-- Modal editar -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('partes.update') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar número de parte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="m-2">
                        <label>Número de parte</label>
                        <input type="text" class="form-control" id="num_parte_id" name="numero_de_parte" placeholder="# de parte">
                        <input type="hidden" class="form-control" id="id_parte" name="id">
                        @error('numero_parte')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Descripción</label>
                        <textarea class="form-control" id="descripcion_id" name="descripcion" placeholder="Descripción..."  ></textarea>
                        @error('descripcion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
