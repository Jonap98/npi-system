<div class="modal fade" id="filtrar-numeros" tabindex="-1" aria-labelledby="movimientoMaterial" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Buscar movimientos por número</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <form id="numeros-id" action="{{ route('movimientos.filters') }}" method="POST">
                    @csrf
                    <div class="m-2">
                        <p>Ingrese los números que desea buscar</p>

                        <textarea name="materiales" id="materiales" class="form-control" style="height: 200px; width: 300px"></textarea>
                        <textarea name="materiales2" id="materiales2" class="form-control" style="height: 200px; width: 300px"></textarea>

                    </div>

                </form>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" id="materials-button" class="btn btn-primary" onclick="buscar()">Buscar</button>
            </div>

        </div>
    </div>
</div>
