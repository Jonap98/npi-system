<!-- Modal editar -->
<div class="modal fade" id="editModal{{ $usuario->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="m-2">
                        <label>Nombre</label>
                        <input type="text" required class="form-control" name="nombre" placeholder="Nombre" value="{{ $usuario->name }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Usuario</label>
                        <input type="text" required class="form-control" name="username" placeholder="Usuario" value="{{ $usuario->username }}">
                        @error('username')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="example@whirlpool.com" value="{{ $usuario->email }}">
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="m-2">
                        <label>Rol</label>
                        <select name="role" id="role" class="form-select">
                            <option selected >{{ $usuario->role }}</option>
                            <option value="NPI-adm">Administrador</option>
                            <option value="NPI-whs">Almacén</option>
                            <option value="NPI-usr">Usuario ingeniería</option>
                            <option value="NPI-eng">Admin Ingeniería</option>
                        </select>
                        @error('role')
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
