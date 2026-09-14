<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Lista de Usuarios</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Usuarios')

@include('partials.topbar')

<div class="container">

    <div class="quick-actions">
        <div style="display:flex; justify-content:space-between; align-items:center; gap:15px; flex-wrap:wrap;">

            <div>
                <h2>👤 Lista de Usuarios</h2>
                <p style="margin:0; color:var(--text-mid); font-weight:500;">
                    Administración general de usuarios registrados en la fundación.
                </p>
            </div>

            <div class="buttons">
                <a href="{{ route('usuarios.create') }}" class="btn btn-naranja">
                    ➕ Crear usuario
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secundario">
                    ← Volver al panel
                </a>
            </div>

        </div>
    </div>

    @if(session('success'))
        <div class="alerts">
            <p>✅ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="error-box">
            <p class="error-text">❌ {{ session('error') }}</p>
        </div>
    @endif

    @if($usuarios->count())

        <div class="panel">

            <h2>Usuarios registrados</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Correo electrónico</th>
                            <th>Rol</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($usuarios as $usuario)
                            <tr>
                                <td><strong>{{ $usuario->nombre }}</strong></td>
                                <td>{{ $usuario->apellido }}</td>
                                <td>{{ $usuario->email }}</td>
                                <td>
                                    @if($usuario->role)
                                        <span class="badge badge-info">{{ $usuario->role->nombre }}</span>
                                    @else
                                        <span style="color:#999;">Sin rol</span>
                                    @endif
                                </td>
                                <td>
                                    @if($usuario->activo)
                                        <span class="badge badge-active">Activo</span>
                                    @else
                                        <span class="badge badge-inactive">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="acciones" style="display: flex; flex-direction: column; gap: 5px;">
                                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn-action btn-edit">
                                            ✏️ Editar
                                        </a>

                                        @if($usuario->id !== auth()->id())
                                            <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Seguro que deseas eliminar a {{ $usuario->nombre }} {{ $usuario->apellido }}?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                                                    🗑 Eliminar
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>

    @else
        <div class="alerts">
            <p>⚠️ No hay usuarios registrados.</p>
        </div>
    @endif

    <p class="footer-brand">© Fundación Danielito — Warnes, Santa Cruz</p>

</div>

</body>
</html>