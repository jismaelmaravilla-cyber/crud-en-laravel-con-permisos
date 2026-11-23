<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">Editar usuario</h4>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('usuarios.update', $usuario->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label">Nombre completo</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name', $usuario->name) }}"
                                    required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Correo electrónico</label>
                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', $usuario->email) }}"
                                    required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <hr>
                            <h6>Cambiar contraseña</h6>
                            <p class="text-muted small">
                                Deja estos campos vacíos si no deseas cambiarla.
                            </p>

                            <div class="mb-3">
                                <label class="form-label">Nueva contraseña</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control">
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirmar contraseña</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control">
                            </div>

                            {{-- Solo el Administrador puede cambiar el rol --}}
                            @if (auth()->user()->role?->nombre === 'Administrador')
                                <div class="mb-3">
                                    <label class="form-label">Rol</label>
                                    <select name="role_id" class="form-select" required>
                                        @foreach ($roles as $rol)
                                            <option value="{{ $rol->id }}"
                                                {{ old('role_id', $usuario->role_id) == $rol->id ? 'selected' : '' }}>
                                                {{ $rol->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Regresar</a>
                                <button type="submit" class="btn btn-warning">Guardar cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>