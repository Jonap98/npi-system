<div class="modal fade" id="edit" tabindex="-1" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('boms.edit') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Editar registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <div class="modal-body">

                    <input type="hidden" name="id" id="id">

                    <div class="m-2">
                        <label for="">Número de parte</label>
                        <input type="text" name="num_parte" id="num_parte" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Kit nombre</label>
                        <input type="text" name="kit_nombre" id="kit_nombre" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Descripción</label>
                        <input type="text" name="kit_descripcion" id="kit_descripcion" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Status</label>
                        <input type="text" name="status" id="status" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Cantidad</label>
                        <input type="text" name="cantidad" id="cantidad" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Ubicación</label>
                        <input type="text" name="ubicacion" id="ubicacion" class="form-control">
                    </div>

                    <div class="m-2">
                        <label for="">Temp</label>
                        <input type="text" name="team" id="team" class="form-control">
                    </div>

                </div>

                <div class="modal-body">
                    <div class="m-2">
                        <h4>¿Seguro que desea editar este registro?</h4>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

