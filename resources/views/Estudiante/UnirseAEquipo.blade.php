<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Unirse a Equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="{{ asset('styles/join-team.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ESTILOS MEJORADOS DIRECTOS PARA ESTA VISTA */
        body {
            background-color: #f3f4f6; /* Fondo gris claro */
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding-bottom: 60px;
        }

        .main-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }

        /* HEADER */
        .page-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .page-title {
            font-family: 'Jomolhari', serif;
            font-size: 2.2rem;
            color: #1f2937;
            margin-bottom: 10px;
        }
        .event-info {
            background-color: #dbeafe;
            color: #1e40af;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-block;
            font-size: 0.9rem;
        }

        /* CARD PRINCIPAL */
        .content-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 30px;
            overflow: hidden;
        }

        /* TABS (PESTAÑAS) */
        .tabs {
            display: flex;
            border-bottom: 2px solid #e5e7eb;
            margin-bottom: 30px;
            gap: 20px;
        }
        .tab-btn {
            background: none;
            border: none;
            padding: 10px 0;
            font-size: 1rem;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            position: relative;
            transition: color 0.3s;
        }
        .tab-btn.active {
            color: #2563eb; /* Azul activo */
        }
        .tab-btn.active::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #2563eb;
        }
        
        .btn-create-link {
            margin-left: auto;
            align-self: center;
            background-color: #28a745;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background 0.2s;
        }
        .btn-create-link:hover { background-color: #218838; }

        /* LISTA DE EQUIPOS PÚBLICOS */
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            max-height: 500px;
            overflow-y: auto;
            padding-right: 5px; /* Para el scroll */
        }

        .team-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            flex-direction: column;
        }
        .team-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: #2563eb;
        }
        .team-name {
            font-size: 1.2rem;
            font-weight: 700;
            color: #111;
            margin: 0 0 5px 0;
        }
        .team-meta {
            font-size: 0.85rem;
            color: #6b7280;
            margin-bottom: 10px;
        }
        .team-desc {
            font-size: 0.9rem;
            color: #4b5563;
            flex-grow: 1;
            margin-bottom: 15px;
            font-style: italic;
        }
        .btn-join {
            width: 100%;
            background-color: #1f2937;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-join:hover { background-color: #000; }

        /* SECCIÓN PRIVADA */
        .private-box {
            text-align: center;
            padding: 40px 20px;
            background: #f9fafb;
            border-radius: 8px;
            border: 2px dashed #d1d5db;
        }
        .code-input {
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1.1rem;
            width: 250px;
            text-align: center;
            letter-spacing: 2px;
            margin-bottom: 15px;
        }
        .code-input:focus {
            border-color: #2563eb;
            outline: none;
        }

        /* UTILIDADES */
        .hidden { display: none; }
        .alert { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

        .btn-back {
            display: inline-block;
            margin-top: 20px;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
        }
        .btn-back:hover { color: #111; text-decoration: underline; }
    </style>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ route('dashboard.estudiante') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
            </div>
        </div>
    </nav>

    <div class="main-container">

        <div class="page-header">
            <h1 class="page-title">Únete a un Equipo</h1>
            @if(isset($eventoPreseleccionado))
                <div class="event-info">
                    🏆 Evento: <strong>{{ $eventoPreseleccionado->title }}</strong>
                </div>
            @endif
        </div>

        <div class="content-card">
            
            {{-- Mensajes de Feedback --}}
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-error">{{ $errors->first() }}</div>
            @endif

            {{-- Pestañas de Navegación --}}
            <div class="tabs">
                <button id="btnPublic" class="tab-btn active">🌐 Equipos Públicos</button>
                <button id="btnPrivate" class="tab-btn">🔒 Con Código</button>
                
                {{-- Botón directo a Crear si no encuentra equipo --}}
                <a href="{{ route('teams.create', isset($eventoPreseleccionado) ? ['event_id' => $eventoPreseleccionado->id] : []) }}" 
                   class="btn-create-link">
                   + Crear mi propio equipo
                </a>
            </div>

            {{-- SECCIÓN PÚBLICA --}}
            <div id="publicSection">
                @if($equipos->count() > 0)
                    <div class="team-grid">
                        @foreach($equipos as $equipo)
                            <div class="team-card">
                                <h3 class="team-name">{{ $equipo->name }}</h3>
                                <div class="team-meta">
                                    <span>👤 Líder: {{ $equipo->leader_name }}</span><br>
                                    <span style="color: #2563eb; font-weight: bold;">📅 {{ $equipo->event->title }}</span>
                                </div>
                                <p class="team-desc">
                                    {{ $equipo->requirements ? '"'.$equipo->requirements.'"' : 'Sin descripción' }}
                                </p>
                                
                                <form action="{{ route('teams.join.request') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="team_id" value="{{ $equipo->id }}">
                                    <button type="submit" class="btn-join">Solicitar Unirse</button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="text-align: center; padding: 40px; color: #6b7280;">
                        <p style="font-size: 1.1rem;">No hay equipos públicos disponibles en este momento.</p>
                        <p>¡Sé el primero en crear uno!</p>
                    </div>
                @endif
            </div>

            {{-- SECCIÓN PRIVADA --}}
            <div id="privateSection" class="hidden">
                <div class="private-box">
                    <h3>¿Tienes un código de invitación?</h3>
                    <p style="color: #666; margin-bottom: 20px;">Ingrésalo abajo para unirte automáticamente.</p>

                    <form action="{{ route('teams.join.code') }}" method="POST">
                        @csrf
                        <input type="text" name="invite_code" class="code-input" placeholder="ITO-XXXX-TEAM" required>
                        <br>
                        <button type="submit" class="btn-join" style="width: auto; padding: 10px 30px;">
                            Validar Código
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <div style="text-align: right;">
            <a href="{{ isset($eventoPreseleccionado) ? route('student.event.show', $eventoPreseleccionado->id) : route('dashboard.estudiante') }}" 
               class="btn-back">
               ← Volver {{ isset($eventoPreseleccionado) ? 'al Evento' : 'al Dashboard' }}
            </a>
        </div>

    </div>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

    <script>
        const btnPublic = document.getElementById("btnPublic");
        const btnPrivate = document.getElementById("btnPrivate");
        const publicSection = document.getElementById("publicSection");
        const privateSection = document.getElementById("privateSection");

        btnPublic.onclick = () => {
            publicSection.style.display = "block";
            privateSection.style.display = "none";
            btnPublic.classList.add("active");
            btnPrivate.classList.remove("active");
        };

        btnPrivate.onclick = () => {
            publicSection.style.display = "none";
            privateSection.style.display = "block";
            btnPrivate.classList.add("active");
            btnPublic.classList.remove("active");
        };
    </script>

</body>
</html>