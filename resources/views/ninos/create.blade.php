<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Niño</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
</head>

<body>
    @section('panel-title', 'Registrar Niño')

@include('partials.topbar')

<div class="container-form">
<h1 class="form-title">
    👦 Registrar Niño
</h1>
    <a href="{{ route('dashboard') }}" class="btn-volver">
        ← Volver al panel
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

    <form method="POST" action="{{ route('ninos.store') }}">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Código</label>
                <input type="text" name="codigo">

                @error('codigo')
                    <div class="error-text">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label>Grupo</label>

                <select name="grupo_id" required>
                    <option value="">Seleccione</option>

                    @foreach($grupos as $grupo)
                        <option value="{{ $grupo->id }}">
                            {{ $grupo->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Nombres</label>
                <input type="text" name="nombres" required>
            </div>

            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" required>
            </div>

            <div class="form-group">
                <label>Fecha de nacimiento</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento">
            </div>

            <div class="form-group">
                <label>Edad</label>
                <input type="number" id="edad" name="edad" readonly>
            </div>

            <div class="form-group">
                <label>Contacto</label>
                <input type="text" name="contacto">
            </div>

            <div class="form-group">
                <label>Curso</label>
                <input type="text" name="curso">
            </div>

            <div class="form-group">
                <label>Colegio</label>
                <input type="text" name="colegio">
            </div>
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
<div class="form-group full-width">
    <label style="font-weight: bold;">Ubicación de la Casa:</label>
    <p class="text-muted small">Haz clic en el mapa o arrastra el marcador azul hasta la casa del niño.</p>
    
    <div id="mapa-casa"></div>
</div>

<input type="hidden" name="latitud" id="latitud">
<input type="hidden" name="longitud" id="longitud">


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Coordenadas para centrar el mapa al abrir 
    const centroInicial = [-17.5147, -63.1678]; 

    // 1. Inicializar el mapa
    const mapa = L.map('mapa-casa').setView(centroInicial, 15);

    // 2. Cargar el diseño del mapa (OpenStreetMap gratuito)
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(mapa);

    // 3. Crear el marcador arrastrable (Pin)
    const marcador = L.marker(centroInicial, { draggable: true }).addTo(mapa);

    // Función para copiar las coordenadas del mapa a los inputs ocultos del formulario
    function actualizarCoordenadasFormulario(lat, lng) {
        document.getElementById('latitud').value = lat.toFixed(6);
        document.getElementById('longitud').value = lng.toFixed(6);
    }

    // Cargar los valores iniciales por defecto
    actualizarCoordenadasFormulario(centroInicial[0], centroInicial[1]);

    // EVENTO 1: Si el usuario arrastra y suelta el marcador azul
    marcador.on('dragend', function (e) {
        const posicion = marcador.getLatLng();
        actualizarCoordenadasFormulario(posicion.lat, posicion.lng);
    });

    // EVENTO 2: Si el usuario hace un clic directo en cualquier punto del mapa
    mapa.on('click', function (e) {
        marcador.setLatLng(e.latlng);
        actualizarCoordenadasFormulario(e.latlng.lat, e.latlng.lng);
    });
</script>
            <div class="checkbox-group">
    <input type="checkbox" id="vulnerable" name="vulnerable" value="1">
    <label>Vulnerable</label>
</div>

<div class="form-group full-width"
     id="motivo_vulnerabilidad_container"
     style="display:none;">

    <label>Motivo vulnerabilidad</label>

    <textarea name="motivo_vulnerabilidad"></textarea>
</div>

            <div class="form-group full-width">
                <label>Observaciones</label>
                <textarea name="observaciones"></textarea>
            </div>

<div id="datos_iglesia_container"
     class="full-width"
     style="display:none;">

    <div class="form-group">
        <label>Nombre iglesia</label>
        <input type="text" name="nombre_iglesia">
    </div>

    <br>

    <div class="form-group">
        <label>Nombre célula</label>
        <input type="text" name="nombre_celula">
    </div>

</div>
            <div class="checkbox-group">
                <input type="checkbox" name="fue_al_encuentro" value="1">
                <label>Fue al encuentro</label>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="bautizado" value="1">
                <label>Bautizado</label>
            </div>

<div class="checkbox-group">
    <input type="checkbox"
           id="asiste_iglesia"
           name="asiste_iglesia"
           value="1">

    <label>Asiste iglesia</label>
</div>
            <div class="checkbox-group">
                <input type="checkbox" name="activo" value="1" checked>
                <label>Activo</label>
            </div>

        </div>

        <button type="submit" class="btn-guardar">
            Guardar Niño
        </button>

    </form>

</div>

<script>
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
</script>
<script>
const vulnerableCheckbox = document.getElementById('vulnerable');

const motivoContainer = document.getElementById('motivo_vulnerabilidad_container');

vulnerableCheckbox.addEventListener('change', function () {

    if (this.checked) {
        motivoContainer.style.display = 'flex';
    } else {
        motivoContainer.style.display = 'none';
    }

});
</script>
<script>
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