<!-- Modal editar -->
<div class="modal fade" id="dynamicModalEdit" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Confirmar editar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <div class="m-2">
                        <label>Cantidad</label>
                        <input type="hidden" id="id_dynamic_edit" name="cantidad_id" class="form-control">
                        <input type="hidden" id="id_movimiento_dynamic_edit" name="movimiento_id" class="form-control">
                        <input type="text" id="cantidad_dynamic_edit" name="cantidad" class="form-control">
                    </div>

                    <span>¿Seguro que desea editar cantidad de la ubicación?</span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" onclick="saveDynamicInfo()">Guardar</button>
                </div>

        </div>
    </div>
</div>
