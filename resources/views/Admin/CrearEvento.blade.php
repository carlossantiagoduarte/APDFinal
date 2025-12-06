<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Event | CodeVision</title>
    <link rel="stylesheet" href="{{ asset('styles/event-register.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("user-toggle");
            const menu = document.getElementById("user-menu");

            toggle.addEventListener("click", () => {
                toggle.classList.toggle("active");
                menu.classList.toggle("show");
            });

            document.addEventListener("click", (e) => {
                if (!toggle.contains(e.target) && !menu.contains(e.target)) {
                    toggle.classList.remove("active");
                    menu.classList.remove("show");
                }
            });
        });
    </script>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <img src="{{ asset('images/logo.png') }}" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name ?? 'Usuario' }}
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>
            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard.admin') }}">Inicio</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>

    <section class="hero">
        <h2 class="hero-title">¡Registra un nuevo Evento!</h2>
    </section>

    <div class="event">
        <div class="form-container">
            <h2>Información del Evento</h2>

            @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="eventForm" action="{{ route('events.store') }}" method="POST">
                @csrf <div class="step active" id="step1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre del Evento</label>
                            <input type="text" name="title" placeholder="Ej: Hackatec" value="{{ old('title') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Organización o Responsable</label>
                            <input type="text" name="organizer" placeholder="Nombre del Organizador" value="{{ old('organizer') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Lugar</label>
                            <input type="text" name="location" placeholder="Ej: Auditorio principal ITO" value="{{ old('location') }}" required>
                        </div>
                    </div>

                    <label>Descripción</label>
                    <textarea name="description" placeholder="Describe el evento..." required>{{ old('description') }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Correo Electrónico</label>
                            <input type="email" name="email" placeholder="example@gmail.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Número de contacto</label>
                            <input type="text" name="phone" placeholder="+52 951-123-4567" value="{{ old('phone') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Capacidad máxima</label>
                            <input type="number" name="max_participants" placeholder="Número total" value="{{ old('max_participants') }}" required>
                        </div>
                    </div>

                    <label>Requisitos de participación</label>
                    <textarea name="requirements" placeholder="Requisitos...">{{ old('requirements') }}</textarea>

                    <div class="buttons">
                        <button type="button" id="cancelBtn1" onclick="window.location='{{ route('dashboard.admin') }}'">Cancelar</button>
                        <button type="button" id="nextBtn">Siguiente →</button>
                    </div>
                </div>

                <div class="step" id="step2">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha de Inicio</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Fecha de Finalización</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}" required>
                        </div>
                        <div class="form-group">
                            <label>URL de la imagen del Evento</label>
                            <input type="url" name="image_url" placeholder="https://ejemplo.com/image.jpg" value="{{ old('image_url') }}">
                        </div>
                    </div>

                    <label>Documentos adjuntos (Info extra)</label>
                    <textarea name="documents_info" placeholder="Información sobre documentos...">{{ old('documents_info') }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Hora de inicio</label>
                            <input type="time" name="start_time" value="{{ old('start_time') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Hora de finalización</label>
                            <input type="time" name="end_time" value="{{ old('end_time') }}" required>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="button" id="cancelBtn2" onclick="window.location='{{ route('dashboard.admin') }}'">Cancelar</button>
                        <button type="button" id="prevBtn">← Anterior</button>
                        <button type="submit">Guardar Evento</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const nextBtn = document.getElementById('nextBtn');
        const prevBtn = document.getElementById('prevBtn');
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        const form = document.getElementById('eventForm'); // Solo para el reset, NO para el submit

        // Función para ir al siguiente paso
        nextBtn.addEventListener('click', () => {
            // Validación simple para que no pase si está vacío
            const inputs = step1.querySelectorAll('input[required], textarea[required]');
            let valid = true;
            inputs.forEach(input => {
                if(!input.value) {
                    valid = false;
                    input.style.border = "2px solid red";
                } else {
                    input.style.border = "1px solid #ccc";
                }
            });

            if(valid) {
                step1.classList.remove('active');
                step2.classList.add('active');
            } else {
                alert("Por favor completa los campos obligatorios.");
            }
        });

        // Función para regresar al paso anterior
        prevBtn.addEventListener('click', () => {
            step2.classList.remove('active');
            step1.classList.add('active');
        });

        // Función para cancelar (Solo resetea, no evita envío porque el botón cancelar es type="button")
        function resetForm() {
            if (confirm("¿Seguro que quieres cancelar? Se borrará toda la información.")) {
                form.reset(); 
                step2.classList.remove('active');
                step1.classList.add('active');
            }
        }
        
        // NOTA: NO HAY addEventListener('submit') AQUÍ.
        // Eso permite que Laravel reciba los datos.
    </script>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li>Inicio</li>
                    <li>Eventos</li>
                    <li>Categorías</li>
                    <li>Calendario</li>
                </ul>
            </div>
            <div>
                <h3>Recursos</h3>
                <ul>
                    <li>Preguntas frecuentes</li>
                    <li>Cómo inscribirse</li>
                    <li>Políticas de evento</li>
                </ul>
            </div>
            <div>
                <h3>Contactos</h3>
                <ul>
                    <li>Inicio</li>
                    <li>Eventos</li>
                    <li>Categorías</li>
                </ul>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>
</html>
