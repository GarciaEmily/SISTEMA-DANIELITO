<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Visita</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Editar Visita')

@include('partials.topbar')

<div class="container-form">
    <h1 class="form-title">✏️ Editar Visita</h1>

    <a href="{{ route('visitas.index') }}" class="btn-volver">
        ← Volver a visitas
    </a>

    @if ($errors->any())
        <div class="error-box">
            <strong>Hay errores en el formulario:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('visitas.update', $visita->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>Niño</label>
                <input type="text" value="{{ $visita->nino->nombres ?? '' }} {{ $visita->nino->apellidos ?? '' }}" readonly>
            </div>

            <div class="form-group">
                <label>Fecha de la visita</label>
                <input type="date" value="{{ \Carbon\Carbon::parse($visita->fecha_visita)->toDateString() }}" disabled>

                <small style="color: var(--text-mid); display: block; margin-top: 5px;">
                    * La fecha no se puede modificar una vez registrada la visita.
                </small>
            </div>

            <div class="form-group full-width">
                <label>Motivo</label>
                <textarea name="motivo" rows="3">{{ old('motivo', $visita->motivo) }}</textarea>

                @error('motivo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Observación</label>
                <textarea name="observacion" rows="3">{{ old('observacion', $visita->observacion) }}</textarea>

                @error('observacion')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Seguimiento</label>
                <textarea name="seguimiento" rows="3">{{ old('seguimiento', $visita->seguimiento) }}</textarea>

                @error('seguimiento')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Foto de evidencia</label>

                @if($visita->foto_path)
                    <div style="margin-top: 8px;">
                        <a href="{{ Storage::url($visita->foto_path) }}" target="_blank">
                            <img src="{{ Storage::url($visita->foto_path) }}" alt="Foto de la visita" style="max-width: 220px; max-height: 220px; border-radius: 8px; border: 1px solid #e2e8f0;">
                        </a>
                    </div>
                @else
                    <p style="color: var(--text-mid);">Esta visita no tiene foto registrada.</p>
                @endif

                <small style="color: var(--text-mid); display: block; margin-top: 5px;">
                    * La foto no se puede reemplazar una vez registrada la visita.
                </small>
            </div>

        </div>

        <button type="submit" class="btn-guardar">
            💾 Guardar cambios
        </button>

    </form>

</div>

</body>
</html>
