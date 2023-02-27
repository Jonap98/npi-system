<!-- Modal image -->
<div class="modal fade" id="inventarioModal{{ $inventario->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{ route('partes.update', $parte->id) }" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Parte: {{ $inventario->numero_de_parte }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="m-2">
                        <img src="/tkav/storage/{{$inventario->numero_de_parte}}.jpg" alt="" width="100%">
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Aceptar</button>
                </div>
            </form>
        </div>
    </div>
</div>