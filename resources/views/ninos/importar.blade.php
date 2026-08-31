<x-app-layout>
    @php
        \Carbon\Carbon::setLocale('es');
        $fechaActual = \Carbon\Carbon::now()->translatedFormat('l d \d\e F \d\e Y');
        $nombreUsuario = auth()->user()->nombre ?? '';
        $apellidoUsuario = auth()->user()->apellido ?? '';
        $iniciales = strtoupper(
            substr($nombreUsuario, 0, 1) .
            substr($apellidoUsuario, 0, 1)
        );
    @endphp

    {{-- Inyectamos los estilos (Vite), FontAwesome y Google Fonts --}}
    <x-slot name="header">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    </x-slot>

    {{-- Estilos avanzados para el Topbar y las Tarjetas Modernas --}}
    <style>
        .import-wrapper {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            min-height: 85vh;
            padding-bottom: 3rem;
        }

        .topbar {
            background: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 30px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
            border-bottom: 1px solid #e2e8f0;
        }
        .topbar-brand {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .topbar-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
            border-radius: 50%;
        }
        .topbar-brand h1 {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin: 0;
        }
        .topbar-brand h1 span {
            color: #0d9488;
        }
        .topbar-brand p {
            font-size: 0.8rem;
            color: #64748b;
            margin: 0;
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .user-avatar {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #0d9488, #0f766e);
            color: #ffffff;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 0.95rem;
            box-shadow: 0 4px 10px rgba(13, 148, 136, 0.2);
        }
        .user-text {
            display: flex;
            flex-direction: column;
        }
        .profile-link {
            color: #1e293b;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.2s;
        }
        .profile-link:hover {
            color: #0d9488;
        }
        .user-text span {
            font-size: 0.75rem;
            color: #64748b;
            text-transform: capitalize;
        }
        .logout-btn {
            background: #fee2e2;
            color: #dc2626;
            border: none;
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .logout-btn:hover {
            background: #fecaca;
            color: #b91c1c;
        }
        .wave-divider {
            height: 6px;
            background: linear-gradient(90deg, #0d9488, #38bdf8, #0d9488);
            margin-bottom: 2rem;
        }

        .styled-card {
            background: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04), 0 1px 3px rgba(0, 0, 0, 0.02);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: hidden;
        }
        .styled-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        }
        .icon-circle-amber {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #d97706;
            width: 75px;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto;
            box-shadow: 0 8px 16px rgba(217, 119, 6, 0.12);
        }
        .icon-circle-teal {
            background: linear-gradient(135deg, #ccfbf1, #99f6e4);
            color: #0d9488;
            width: 75px;
            height: 75px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto;
            box-shadow: 0 8px 16px rgba(13, 148, 136, 0.12);
        }
        .btn-action-download {
            background: #ffffff;
            border: 2px solid #e2e8f0;
            color: #1e293b;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .btn-action-download:hover {
            background: #f8fafc;
            border-color: #cbd5e1;
            color: #0f172a;
        }
        .btn-action-submit {
            background: linear-gradient(135deg, #0d9488, #0f766e);
            border: none;
            color: #ffffff;
            font-weight: 600;
            border-radius: 12px;
            transition: all 0.2s ease;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        }
        .btn-action-submit:hover {
            background: linear-gradient(135deg, #0f766e, #115e59);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.35);
        }
        .custom-file-input {
            border: 2px dashed #cbd5e1;
            background: #f8fafc;
            border-radius: 14px;
            padding: 1.2rem;
            transition: all 0.2s ease;
        }
        .custom-file-input:focus, .custom-file-input:hover {
            border-color: #0d9488;
            background: #f0fdfa;
        }
    </style>

    {{-- Inserción del Topbar y la barra divisoria --}}
    <div class="topbar">
        <div class="topbar-brand">
            <img src="{{ asset('img/logo-danielito.png') }}" alt="Logo Fundación Danielito" class="topbar-logo">
            <div>
                <h1>Fundación <span>Danielito</span></h1>
                <p>@yield('panel-title', 'Sistema de Gestión')</p>
            </div>
        </div>

        <div class="user-info">
            <div class="user-avatar">
                {{ $iniciales }}
            </div>
            <div class="user-text">
                <strong>
                    <a href="{{ route('profile.edit') }}" class="profile-link">
                        {{ $nombreUsuario }} {{ $apellidoUsuario }}
                    </a>
                </strong>
                <span>{{ $fechaActual }}</span>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>
    <div class="wave-divider"></div>

    {{-- Contenido de Importación --}}
    <div class="import-wrapper">
        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="h3 fw-bold text-dark mb-1 font-montserrat">
                        <i class="fas fa-file-excel text-success me-2"></i> Importar Registros de Niños
                    </h2>
                    <p class="text-muted small mb-0">Carga masiva rápida y segura mediante plantilla de Excel estructurada.</p>
                </div>
                <a href="{{ route('ninos.index') }}" class="btn btn-light border btn-sm shadow-sm px-4 py-2 text-secondary fw-semibold rounded-pill">
                    <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                </a>
            </div>

            <div class="row g-4">
                {{-- Columna 1: Descarga de Plantilla --}}
                <div class="col-lg-4">
                    <div class="styled-card h-100 p-4 d-flex flex-column justify-content-between">
                        <div>
                            <div class="mb-4 pt-2">
                                <div class="icon-circle-amber">
                                    <i class="fas fa-file-download fa-lg"></i>
                                </div>
                            </div>
                            <h5 class="card-title text-center mb-3 fw-bold text-dark font-montserrat">1. Descargar Plantilla</h5>
                            <p class="card-text text-muted small text-center px-2 leading-relaxed">
                                Para evitar errores de formato en el sistema, utiliza nuestra estructura predefinida. <strong>No alteres ni elimines</strong> el orden de las columnas originales.
                            </p>
                        </div>
                        <div class="d-grid mt-4 pt-3">
                            <a href="{{ route('ninos.plantilla') }}" class="btn btn-action-download w-100 py-3 shadow-sm">
                                <i class="fas fa-download me-2 text-warning"></i> Descargar Excel Base
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Columna 2: Formulario de Subida --}}
                <div class="col-lg-8">
                    <div class="styled-card h-100 p-4 p-md-5">
                        <div class="card-body p-0">
                            <div class="mb-4 text-center">
                                <div class="icon-circle-teal">
                                    <i class="fas fa-cloud-upload-alt fa-lg"></i>
                                </div>
                            </div>
                            <h5 class="card-title text-center mb-4 fw-bold text-dark font-montserrat">2. Subir y Procesar Archivo</h5>
                            
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-4 mb-4" role="alert">
                                    <i class="fas fa-exclamation-triangle me-2"></i> <strong>Error en el archivo o formulario:</strong>
                                    <ul class="mb-0 mt-2 small">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form action="{{ route('ninos.importar.store') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="archivo" class="form-label fw-semibold small text-secondary mb-2">Selecciona tu archivo Excel procesado (.xlsx, .xls)</label>
                                    <input class="form-control custom-file-input @error('archivo') is-invalid @enderror" 
                                           type="file" 
                                           id="archivo" 
                                           name="archivo" 
                                           accept=".xlsx, .xls" 
                                           required>
                                    
                                    @error('archivo')
                                        <div class="invalid-feedback fw-bold mt-2">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    
                                    <div class="form-text text-muted small mt-2 d-flex align-items-center">
                                        <i class="fas fa-info-circle text-teal me-2"></i> Tamaño máximo recomendado: 5MB. Comprueba que el archivo no esté abierto en segundo plano.
                                    </div>
                                </div>

                                <div class="d-grid mt-4 pt-2">
                                    <button type="submit" class="btn btn-action-submit btn-lg w-100 py-3 shadow" id="submitBtn">
                                        <i class="fas fa-upload me-2"></i> Iniciar Importación Masiva
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('importForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Procesando registros, por favor espere...`;
        });
    </script>
    @endpush
</x-app-layout>