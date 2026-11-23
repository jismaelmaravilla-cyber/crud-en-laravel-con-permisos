<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Registrar usuario</h4>
                    </div>

                    <div class="card-body p-4">
                        <form action="{{ route('usuarios.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Nombre completo</label>
                                <input
                                    type="text"
                                    name="name"
                                    class="form-control"
                                    value="{{ old('name') }}"
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
                                    value="{{ old('email') }}"
                                    required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Contraseña</label>
                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Confirmar contraseña</label>
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    class="form-control"
                                    required>
                            </div>

                            {{-- Solo el Administrador puede elegir el rol; si es Empleado, el controlador asigna 'Empleado' --}}
                            @if (auth()->user()->role?->nombre === 'Administrador')
                                <div class="mb-3">
                                    <label class="form-label">Rol</label>
                                    <select name="role_id" class="form-select" required>
                                        <option value="">Selecciona un rol</option>
                                        @foreach ($roles as $rol)
                                            <option value="{{ $rol->id }}" {{ old('role_id') == $rol->id ? 'selected' : '' }}>
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
                                <button type="submit" class="btn btn-primary">Registrar usuario</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>