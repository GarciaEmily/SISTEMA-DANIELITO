<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Niño</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapa-casa { 
            height: 380px; 
            width: 100%; 
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 10px;
            margin-bottom: 20px; 
        }
    </style>
</head>

<body>
    @section('panel-title', 'Editar Niño')

@include('partials.topbar')

<div class="container-form">

<h1 class="form-title">
    ✏️ Editar Niño
</h1>

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

    <form method="POST" action="{{ route('ninos.update', $nino->id) }}">
        @csrf
        @method('PUT')

        <div class="form-grid">

            <div class="form-group">
                <label>Código</label>
                <input type="text"
                       name="codigo"
                       value="{{ old('codigo', $nino->codigo) }}">

                @error('codigo')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label>Grupo</label>
                <select name="grupo_id" required>
                    @if(!$nino->grupo_id)
                        <option value="" selected disabled>
                            Sin grupo asignado — selecciona uno
                        </option>
                    @endif
                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}"
                            {{ $nino->grupo_id == $grupo->id ? 'selected' : '' }}>
                            {{ $grupo->nombre }}{{ !$grupo->activo ? ' (inactivo)' : '' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nombres</label>
                <input type="text"
                       name="nombres"
                       value="{{ old('nombres', $nino->nombres) }}"
                       required>
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <input type="text"
                       name="apellidos"
                       value="{{ old('apellidos', $nino->apellidos) }}"
                       required>
            </div>

            <div class="form-group">
                <label>Fecha nacimiento</label>
                <input type="date"
                       id="fecha_nacimiento"
                       name="fecha_nacimiento"
                       value="{{ old('fecha_nacimiento', $nino->fecha_nacimiento?->format('Y-m-d')) }}">
            </div>

            <div class="form-group">
                <label>Edad</label>
                <input type="number"
                       id="edad"
                       name="edad"
                       value="{{ old('edad', $nino->edad) }}"
                       readonly>
            </div>

            <div class="form-group">
                <label>Contacto</label>
                <input type="text"
                       name="contacto"
                       value="{{ old('contacto', $nino->contacto) }}">
            </div>

            <div class="form-group">
                <label>Curso</label>
                <input type="text"
                       name="curso"
                       value="{{ old('curso', $nino->curso) }}">
            </div>

            <div class="form-group">
                <label>Colegio</label>
                <input type="text"
                       name="colegio"
                       value="{{ old('colegio', $nino->colegio) }}">
            </div>

            <div class="form-group full-width" style="margin-top: 15px;">
                <label style="font-weight: bold;">Ubicación de la Casa:</label>
                <p class="text-muted small">Haz clic en el mapa o arrastra el marcador azul para corregir o asignar la ubicación de la casa.</p>
                <div id="mapa-casa"></div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox"
                       id="vulnerable"
                       name="vulnerable"
                       value="1"
                       {{ $nino->vulnerable ? 'checked' : '' }}>
                <label>Vulnerable</label>
            </div>

            <div class="full-width"
                 id="motivo_vulnerabilidad_container"
                 style="{{ $nino->vulnerable ? '' : 'display:none;' }}">
                <div class="form-group">
                    <label>Motivo vulnerabilidad</label>
                    <textarea name="motivo_vulnerabilidad">{{ old('motivo_vulnerabilidad', $nino->motivo_vulnerabilidad) }}</textarea>
                </div>
            </div>

            <div class="form-group full-width">
                <label>Observaciones</label>
                <textarea name="observaciones">{{ old('observaciones', $nino->observaciones) }}</textarea>
            </div>

            <div class="checkbox-group">
                <input type="checkbox"
                       name="fue_al_encuentro"
                       value="1"
                       {{ $nino->fue_al_encuentro ? 'checked' : '' }}>
                <label>Fue al encuentro</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox"
                       name="bautizado"
                       value="1"
                       {{ $nino->bautizado ? 'checked' : '' }}>
                <label>Bautizado</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox"
                       id="asiste_iglesia"
                       name="asiste_iglesia"
                       value="1"
                       {{ $nino->asiste_iglesia ? 'checked' : '' }}>
                <label>Asiste iglesia</label>
            </div>

            <div class="full-width"
                 id="datos_iglesia_container"
                 style="{{ $nino->asiste_iglesia ? '' : 'display:none;' }}">
                <div class="form-group">
                    <label>Nombre iglesia</label>
                    <input type="text"
                           name="nombre_iglesia"
                           value="{{ old('nombre_iglesia', $nino->nombre_iglesia) }}">
                </div>
                <br>
                <div class="form-group">
                    <label>Nombre célula</label>
                    <input type="text"
                           name="nombre_celula"
                           value="{{ old('nombre_celula', $nino->nombre_celula) }}">
                </div>
            </div>

            <div class="checkbox-group">
                <input type="checkbox"
                       name="activo"
                       value="1"
                       {{ $nino->activo ? 'checked' : '' }}>
                <label>Activo</label>
            </div>

        </div>

        <input type="hidden" name="latitud" id="latitud" value="{{ old('latitud', $nino->latitud) }}">
        <input type="hidden" name="longitud" id="longitud" value="{{ old('longitud', $nino->longitud) }}">

        <button type="submit" class="btn-guardar">
            Actualizar Niño
        </button>

        <div>
            <a href="{{ route('ninos.index') }}" class="btn-volver" style="text-decoration: none;">
                ← Volver
            </a>
        </div>

    </form>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // 1. Determinar el centro inicial basado en si ya cuenta con coordenadas
    let latInicial = {{ $nino->latitud ?? -17.5147 }};
    let lngInicial = {{ $nino->longitud ?? -63.1678 }};
    const centroInicial = [latInicial, lngInicial];

    // 2. Inicializar el mapa
    const mapa = L.map('mapa-casa').setView(centroInicial, 15);

    // 3. Cargar el diseño
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 4. Crear el pin arrastrable
    const marcador = L.marker(centroInicial, { draggable: true }).addTo(mapa);

    // Función para actualizar los campos ocultos
    function actualizarCoordenadasFormulario(lat, lng) {
        document.getElementById('latitud').value = lat.toFixed(6);
        document.getElementById('longitud').value = lng.toFixed(6);
    }

    // EVENTO 1: Al arrastrar el pin
    marcador.on('dragend', function (e) {
        const posicion = marcador.getLatLng();
        actualizarCoordenadasFormulario(posicion.lat, posicion.lng);
    });

    // EVENTO 2: Al hacer clic en el mapa
    mapa.on('click', function (e) {
        marcador.setLatLng(e.latlng);
        actualizarCoordenadasFormulario(e.latlng.lat, e.latlng.lng);
    });

    // Manejo de la Fecha de nacimiento y Edad
    document.getElementById('fecha_nacimiento').addEventListener('change', function () {
        let fechaNacimiento = new Date(this.value);
        let hoy = new Date();
        let edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
        let mes = hoy.getMonth() - fechaNacimiento.getMonth();

        if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
            edad--;
        }
        document.getElementById('edad').value = edad;
    });

    // Visibilidad condicional: Vulnerabilidad
    const vulnerableCheckbox = document.getElementById('vulnerable');
    const motivoContainer = document.getElementById('motivo_vulnerabilidad_container');

    vulnerableCheckbox.addEventListener('change', function () {
        if (this.checked) {
            motivoContainer.style.display = 'block';
        } else {
            motivoContainer.style.display = 'none';
        }
    });

    // Visibilidad condicional: Iglesia
    const iglesiaCheckbox = document.getElementById('asiste_iglesia');
    const datosIglesia = document.getElementById('datos_iglesia_container');

    iglesiaCheckbox.addEventListener('change', function () {
        if (this.checked) {
            datosIglesia.style.display = 'block';
        } else {
            datosIglesia.style.display = 'none';
        }
    });
</script>

</body>
</html>