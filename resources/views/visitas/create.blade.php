<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Visita</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>

@section('panel-title', 'Registrar Visita')

@include('partials.topbar')

<div class="container-form">
    <h1 class="form-title">🏠 Registrar Visita</h1>

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

    <form method="POST" action="{{ route('visitas.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Niño</label>

                <select name="nino_id" required>
                    <option value="">Seleccione</option>

                    @foreach($ninos as $nino)
                        <option value="{{ $nino->id }}" @selected(old('nino_id', $ninoSeleccionado) == $nino->id)>
                            {{ $nino->nombres }} {{ $nino->apellidos }} ({{ $nino->grupo->nombre ?? 'Sin grupo' }})
                        </option>
                    @endforeach
                </select>

                @error('nino_id')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label>Fecha de la visita</label>

                <input type="date"
                       name="fecha_visita"
                       value="{{ old('fecha_visita', now()->toDateString()) }}"
                       max="{{ now()->toDateString() }}"
                       required>

                <small style="color: var(--text-mid); display: block; margin-top: 5px;">
                    * Una vez registrada, la fecha ya no se podrá modificar.
                </small>

                @error('fecha_visita')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Motivo</label>
                <textarea name="motivo" rows="3">{{ old('motivo') }}</textarea>

                @error('motivo')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Observación</label>
                <textarea name="observacion" rows="3">{{ old('observacion') }}</textarea>

                @error('observacion')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Seguimiento</label>
                <textarea name="seguimiento" rows="3">{{ old('seguimiento') }}</textarea>

                @error('seguimiento')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group full-width">
                <label>Foto de evidencia</label>
                <input type="file" name="foto" accept="image/*" capture="environment" required>

                <small style="color: var(--text-mid); display: block; margin-top: 5px;">
                    * Obligatoria. Formatos: JPG, PNG o WEBP, máximo 8MB. Una vez guardada, no se podrá reemplazar.
                </small>

                @error('foto')
                    <div class="error-text">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <button type="submit" class="btn-guardar">
            💾 Guardar visita
        </button>

    </form>

</div>

</body>
</html>
