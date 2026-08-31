<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Niño</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/danielito.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #mapa-detalle-casa { 
            height: 320px; 
            width: 100%; 
            border: 1px solid #ccc;
            border-radius: 8px;
            margin-top: 10px;
        }
        .badge-si { background-color: #28a745; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-no { background-color: #dc3545; color: white; padding: 3px 8px; border-radius: 12px; font-size: 12px; font-weight: bold; }
    </style>
</head>
<body>

@include('partials.topbar')

<div class="container-form">
    <h1 class="form-title">📋 Detalle del Niño</h1>

    <div class="form-grid">
        <div class="full-width"><h4 style="color: #0d6efd; margin-top: 15px;">INFORMACIÓN GENERAL</h4></div>
        
        <div class="form-group">
            <label>Código</label>
            <input type="text" value="{{ $nino->codigo }}" readonly>
        </div>
        <div class="form-group">
            <label>Nombres</label>
            <input type="text" value="{{ $nino->nombres }}" readonly>
        </div>
        <div class="form-group">
            <label>Apellidos</label>
            <input type="text" value="{{ $nino->apellidos }}" readonly>
        </div>
        <div class="form-group">
            <label>Fecha nacimiento</label>
            <input type="text" value="{{ $nino->fecha_nacimiento }}" readonly>
        </div>
        <div class="form-group">
            <label>Edad</label>
            <input type="text" value="{{ $nino->edad }} años" readonly>
        </div>
        <div class="form-group">
            <label>Contacto</label>
            <input type="text" value="{{ $nino->contacto }}" readonly>
        </div>

        <div class="full-width"><h4 style="color: #0d6efd; margin-top: 15px;">INFORMACIÓN ACADÉMICA</h4></div>
        
        <div class="form-group">
            <label>Curso</label>
            <input type="text" value="{{ $nino->curso }}" readonly>
        </div>
        <div class="form-group">
            <label>Colegio</label>
            <input type="text" value="{{ $nino->colegio }}" readonly>
        </div>
        <div class="form-group">
            <label>Grupo</label>
            <input type="text" value="{{ $nino->grupo->nombre ?? 'Sin grupo' }}" readonly>
        </div>
        <div class="form-group">
            <label>Maestro</label>
            <input type="text" value="{{ $nino->maestro->nombre ?? '' }} {{ $nino->maestro->apellido ?? '' }}" readonly>
        </div>

        <div class="full-width"><h4 style="color: #0d6efd; margin-top: 15px;">INFORMACIÓN ESPIRITUAL</h4></div>
        
        <div class="form-group">
            <label>Fue al encuentro</label>
            <div style="margin-top: 8px;">
                <span class="{{ $nino->fue_al_encuentro ? 'badge-si' : 'badge-no' }}">{{ $nino->fue_al_encuentro ? 'Sí' : 'No' }}</span>
            </div>
        </div>
        <div class="form-group">
            <label>Bautizado</label>
            <div style="margin-top: 8px;">
                <span class="{{ $nino->bautizado ? 'badge-si' : 'badge-no' }}">{{ $nino->bautizado ? 'Sí' : 'No' }}</span>
            </div>
        </div>
        <div class="form-group">
            <label>Asiste iglesia</label>
            <div style="margin-top: 8px;">
                <span class="{{ $nino->asiste_iglesia ? 'badge-si' : 'badge-no' }}">{{ $nino->asiste_iglesia ? 'Sí' : 'No' }}</span>
            </div>
        </div>
        <div class="form-group">
            <label>Nombre iglesia</label>
            <input type="text" value="{{ $nino->nombre_iglesia }}" readonly>
        </div>
        <div class="form-group">
            <label>Nombre célula</label>
            <input type="text" value="{{ $nino->nombre_celula }}" readonly>
        </div>

        <div class="full-width"><h4 style="color: #0d6efd; margin-top: 15px;">INFORMACIÓN SOCIAL</h4></div>
        
        <div class="form-group">
            <label>Vulnerable</label>
            <div style="margin-top: 8px;">
                <span class="{{ $nino->vulnerable ? 'badge-si' : 'badge-no' }}">{{ $nino->vulnerable ? 'Sí' : 'No' }}</span>
            </div>
        </div>
        <div class="form-group">
            <label>Activo</label>
            <div style="margin-top: 8px;">
                <span class="{{ $nino->activo ? 'badge-si' : 'badge-no' }}">{{ $nino->activo ? 'Activo' : 'Inactivo' }}</span>
            </div>
        </div>
        @if($nino->vulnerable)
            <div class="form-group full-width">
                <label>Motivo vulnerabilidad</label>
                <textarea readonly>{{ $nino->motivo_vulnerabilidad }}</textarea>
            </div>
        @endif
        <div class="form-group full-width">
            <label>Observaciones</label>
            <textarea readonly>{{ $nino->observaciones }}</textarea>
        </div>

        <div class="full-width" style="margin-top: 20px;">
            <div style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 20px; background-color: #fff;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h5 style="color: #2d3748; font-weight: bold; font-size: 14px; text-transform: uppercase; margin: 0;">
                        Ubicación de la Casa
                    </h5>
                    @if($nino->latitud && $nino->longitud)
                        <a href="https://www.google.com/maps?q={{ $nino->latitud }},{{ $nino->longitud }}" 
                           target="_blank" 
                           style="background-color: #1a73e8; color: white; padding: 6px 12px; border-radius: 4px; text-decoration: none; font-size: 13px; font-weight: 500;">
                           📍 Ver en Google Maps
                        </a>
                    @endif
                </div>
                
                @if($nino->latitud && $nino->longitud)
                    <div id="mapa-detalle-casa"></div>
                @else
                    <p class="text-muted" style="font-size: 14px; margin: 0; color: #6c757d;">
                        ⚠️ Este niño aún no tiene una ubicación registrada en el mapa.
                    </p>
                @endif
            </div>
        </div>
    </div>

    <div style="margin-top: 20px;">
        <a href="{{ route('ninos.index') }}" class="btn-volver" style="text-decoration: none; background-color: #6c757d; color: white; padding: 10px 20px; border-radius: 4px; display: inline-block;">
            ← Volver
        </a>
    </div>
</div>

@if($nino->latitud && $nino->longitud)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const lat = {{ $nino->latitud }};
        const lng = {{ $nino->longitud }};

        const mapaDetalle = L.map('mapa-detalle-casa').setView([lat, lng], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(mapaDetalle);

        L.marker([lat, lng]).addTo(mapaDetalle)
            .bindPopup("<b>Casa de {{ $nino->nombres }}</b>")
            .openPopup();
    </script>
@endif

</body>
</html>