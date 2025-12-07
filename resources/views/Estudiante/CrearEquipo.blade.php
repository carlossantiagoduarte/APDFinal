<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Crear Equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('styles/crear-team.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        .step { display: none; }
        .step.active { display: block; }
        .readonly-input { background-color: #e9ecef; color: #495057; cursor: not-allowed; border: 1px solid #ced4da; }
        .event-badge { background-color: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid #bee5eb; font-weight: bold; text-align: center; }
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
            {{-- Nota: El menú desplegable no está aquí, asumimos que está en una plantilla base --}}
        </div>
    </nav>

    @if(session('equipo_creado'))
        <section id="step3" class="step active">
            <div class="final-container">
                <h1>¡Equipo creado con éxito!</h1>
                <p>Comparte este código con tus compañeros para que se unan.</p>

                <div class="invite-box">
                    <h3>CÓDIGO DE INVITACIÓN</h3>
                    <div id="inviteCode">{{ session('equipo_creado') }}</div>
                    <button class="btn-copy" onclick="copiarCodigo()">Copiar código</button>
                </div>

                <button class="btn-primary" onclick="window.location.href='{{ route('dashboard.estudiante') }}'">Ir al Dashboard</button>
            </div>
        </section>

    @else

        <section id="step1" class="step active">
            <div class="header-banner">¡Crea tu Equipo Ahora!</div>

            <div class="form-container">
                <h1 class="title">Datos del Equipo</h1>

                @if($errors->any())
                    <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                {{-- ACCIÓN CORREGIDA: equipos.store -> teams.store --}}
                <form id="createTeamForm" action="{{ route('teams.store') }}" method="POST">
                    @csrf

                    <div class="form-grid">
                        
                        <div>
                            <label>Evento *</label>

                            @if(isset($eventoPreseleccionado) && $eventoPreseleccionado)
                                <div class="event-badge">
                                    Participando en: {{ $eventoPreseleccionado->title }}
                                </div>
                                <input type="hidden" name="event_id" value="{{ $eventoPreseleccionado->id }}">
                            @else
                                <select name="event_id" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">
                                    <option value="" disabled selected>-- Selecciona un evento --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->title }}</option>
                                    @endforeach
                                </select>
                            @endif

                            <label>Nombre del Equipo *</label>
                            <input type="text" name="name" required placeholder="Ej: Los Innovadores" value="{{ old('name') }}">

                            <label>Visibilidad *</label>
                            <select name="visibility" required style="width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 5px;">
                                <option value="public">Público (Cualquiera puede unirse)</option>
                                <option value="private">Privado (Solo con código)</option>
                            </select>
                            
                            <label>Número máximo de integrantes</label>
                            <input type="number" name="max_members" min="2" max="10" value="5" required>
                        </div>

                        <div>
                            <label>Líder del Equipo (Tú)</label>
                            <input type="text" value="{{ Auth::user()->name }} {{ Auth::user()->lastname }}" readonly class="readonly-input">

                            <label>Correo de contacto</label>
                            <input type="email" value="{{ Auth::user()->email }}" readonly class="readonly-input">

                            <label>Requisitos para los miembros (Opcional)</label>
                            <textarea name="requirements" placeholder="Ej: Saber Java, tener laptop..." rows="4">{{ old('requirements') }}</textarea>
                        </div>

                    </div>

                    <div class="buttons-row" style="margin-top: 20px; display: flex; gap: 10px; justify-content: flex-end;">
                        <button type="button" class="btn-secondary" 
                            onclick="window.location.href='{{ isset($eventoPreseleccionado) ? route('student.event.show', $eventoPreseleccionado->id) : route('dashboard.estudiante') }}'">
                            Cancelar
                        </button>
                        
                        <button type="submit" class="btn-primary">Crear Equipo</button>
                    </div>

                </form>
            </div>
        </section>

    @endif

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

    <script>
        function copiarCodigo() {
            const codigo = document.getElementById("inviteCode").innerText;
            navigator.clipboard.writeText(codigo).then(() => {
                alert("¡Código copiado al portapapeles!");
            });
        }
    </script>

</body>
</html>
