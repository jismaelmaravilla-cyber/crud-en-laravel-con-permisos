<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold">Usuarios</h1>
                <p class="text-muted mb-0">
                    Bienvenido, {{ auth()->user()->name }}
                    ({{ auth()->user()->role?->nombre }})
                </p>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger">Cerrar sesión</button>
            </form>
        </div>

        {{-- Mensajes --}}
        @if (session('mensaje'))
            <div class="alert alert-success">
                {{ session('mensaje') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Tabla de usuarios --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Listado de usuarios</h5>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">Nuevo usuario</a>
                </div>
            </div>

            <div class="card-body">
                {{-- Solo el administrador puede ver los usuarios eliminados --}}
                @if (auth()->user()->role?->nombre === 'Administrador')
                    <a href="{{ route('usuarios.trashed') }}" class="btn btn-outline-secondary btn-sm mb-3">
                        Usuarios eliminados
                    </a>
                @endif

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($usuarios as $usuario)
                                <tr>
                                    <td>{{ $usuario->id }}</td>
                                    <td>{{ $usuario->name }}</td>
                                    <td>{{ $usuario->email }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $usuario->role?->nombre ?? 'Sin rol' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{-- Acciones disponibles para ambos roles --}}
                                        <a href="{{ route('usuarios.show', $usuario->id) }}" class="btn btn-info btn-sm">
                                            Ver
                                        </a>

                                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning btn-sm">
                                            Editar
                                        </a>

                                        {{-- Acciones exclusivas del administrador --}}
                                        @if (auth()->user()->role?->nombre === 'Administrador')
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Deshabilitar este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    Deshabilitar
                                                </button>
                                            </form>

                                            <form action="{{ route('usuarios.forceDestroy', $usuario->id) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('¿Eliminar permanentemente este usuario?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-dark btn-sm">
                                                    Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No hay usuarios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>