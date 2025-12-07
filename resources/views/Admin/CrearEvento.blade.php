<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nuevo Evento | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/event-register.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <style>
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            margin: 20px 0 0 40px;
            padding: 8px 12px;
            border-radius: 5px;
            transition: background 0.2s;
            cursor: pointer;
        }
        .back-link:hover {
            background-color: #f0f0f0;
        }
        /* Estilo para ocultar/mostrar suavemente */
        .hidden {
            display: none;
        }
    </style>

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
            <a href="{{ route('dashboard.admin') }}" style="text-decoration: none; display: flex; align-items: center; color: inherit; gap: 10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision</span>
            </a>
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
                <a href="{{ route('editarperfil') }}">Perfil</a>
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>

    <a href="javascript:history.back()" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Regresar
    </a>

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
                @csrf 
                
                <div class="step active" id="step1">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre del Evento</label>
                            <input type="text" name="title" placeholder="Ej: Hackatec 2025" value="{{ old('title') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Categoría Principal <span style="font-size:0.8em; color:gray;">(Nuevo)</span></label>
                            
                            <select id="categorySelect" name="main_category" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
                                <option value="" disabled selected>Selecciona una categoría</option>
                                <option value="Tecnología">Tecnología</option>
                                <option value="Programación">Programación</option>
                                <option value="Ciberseguridad">Ciberseguridad</option>
                                <option value="Inteligencia Artificial">Inteligencia Artificial</option>
                                <option value="Diseño UI/UX">Diseño UI/UX</option>
                                <option value="Robótica">Robótica</option>
                                <option value="Otro">Otro (Escribir manualmente)</option>
                            </select>

                            <input type="text" id="otherCategoryInput" name="other_category" 
                                   placeholder="Escribe el nombre de la categoría..." 
                                   style="margin-top: 10px; display: none;" 
                                   class="hidden">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Organización / Responsable</label>
                            <input type="text" name="organizer" placeholder="Nombre del Organizador" value="{{ old('organizer') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Modalidad <span style="font-size:0.8em; color:gray;">(Nuevo)</span></label>
                            <select name="modality" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
                                <option value="Presencial">Presencial</option>
                                <option value="Virtual">Virtual</option>
                                <option value="Híbrido">Híbrido</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Lugar</label>
                            <input type="text" name="location" placeholder="Ej: Auditorio principal" value="{{ old('location') }}" required>
                        </div>
                    </div>

                    <label>Descripción</label>
                    <textarea name="description" placeholder="Describe el evento..." required>{{ old('description') }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Correo Electrónico</label>
                            <input type="email" name="email" placeholder="contacto@evento.com" value="{{ old('email') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Número de contacto</label>
                            <input type="text" name="phone" placeholder="+52 951..." value="{{ old('phone') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Capacidad</label>
                            <input type="number" name="max_participants" placeholder="Ej: 200" value="{{ old('max_participants') }}" required>
                        </div>
                    </div>

                    <label>Requisitos de participación</label>
                    <textarea name="requirements" placeholder="Lista de requisitos...">{{ old('requirements') }}</textarea>

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
                    </div>

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

                    <div class="form-row">
                        <div class="form-group">
                            <label>Imagen Cuadrada (URL)</label>
                            <input type="url" name="image_url" placeholder="https://..." value="{{ old('image_url') }}">
                        </div>
                        <div class="form-group">
                            <label>Banner Horizontal (URL) <span style="font-size:0.8em; color:gray;">(Nuevo)</span></label>
                            <input type="url" name="banner_url" placeholder="https://..." value="{{ old('banner_url') }}">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Link de Registro Externo (Opcional) <span style="font-size:0.8em; color:gray;">(Nuevo)</span></label>
                        <input type="url" name="registration_link" placeholder="https://forms.google.com/..." value="{{ old('registration_link') }}">
                    </div>

                    <label>Documentos adjuntos / Info Extra</label>
                    <textarea name="documents_info" placeholder="Enlaces a PDFs o información adicional...">{{ old('documents_info') }}</textarea>

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
        
        // Elementos para la lógica de "Otro"
        const categorySelect = document.getElementById('categorySelect');
        const otherCategoryInput = document.getElementById('otherCategoryInput');

        // Escuchar cambios en el Select
        categorySelect.addEventListener('change', function() {
            if (this.value === 'Otro') {
                otherCategoryInput.style.display = 'block'; // Mostrar
                otherCategoryInput.required = true;         // Hacer obligatorio
                otherCategoryInput.focus();
            } else {
                otherCategoryInput.style.display = 'none';  // Ocultar
                otherCategoryInput.required = false;        // Quitar obligatorio
                otherCategoryInput.value = '';              // Limpiar
            }
        });

        nextBtn.addEventListener('click', () => {
            // Validamos solo los campos visibles
            // El truco es que si el input "Otro" está oculto, no molestará
            const inputs = step1.querySelectorAll('input:not([style*="display: none"]), select, textarea');
            
            let valid = true;
            inputs.forEach(input => {
                if(input.hasAttribute('required') && !input.value) {
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

        prevBtn.addEventListener('click', () => {
            step2.classList.remove('active');
            step1.classList.add('active');
        });
    </script>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca.</p>
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
