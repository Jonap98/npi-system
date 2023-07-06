<!-- Modal store -->
<div class="modal fade" id="storeUbicacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('ubicaciones.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear ubicación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="m-2">
                        <label>Ubicación</label>
                        <input type="text" class="form-control" name="ubicacion" placeholder="Ubicación">
                        @error('ubicacion')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Tipo</label>
                        <select name="tipo" id="tipo" class="form-select">
                            <option value="NPI">NPI</option>
                            <option value="INGENIERIA">Ingeniería</option>
                            <option value="OTROS">Otros</option>
                        </select>
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
