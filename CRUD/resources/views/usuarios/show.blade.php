<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow border-0">
                    <div class="card-header bg-info">
                        <h4 class="mb-0">Detalles del usuario</h4>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-3">
                            <label class="text-muted">ID</label>
                            <h5>{{ $usuario->id }}</h5>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted">Nombre</label>
                            <h5>{{ $usuario->name }}</h5>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted">Correo electrónico</label>
                            <h5>{{ $usuario->email }}</h5>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted">Rol</label>
                            <h5>
                                <span class="badge bg-info text-dark">
                                    {{ $usuario->role?->nombre ?? 'Sin rol' }}
                                </span>
                            </h5>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="text-muted">Fecha de registro</label>
                            <p class="mb-0">
                                {{ $usuario->created_at?->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Regresar</a>
                            <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-warning">Editar usuario</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>