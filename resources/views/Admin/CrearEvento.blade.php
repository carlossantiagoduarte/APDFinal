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
        /* Estilo para ocultar/mostrar */
        .hidden {
            display: none !important; 
        }
        /* Aseguramos que solo los pasos activos se muestren */
        .step {
            display: none;
        }
        .step.active {
            display: block;
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
                {{-- RUTA CORREGIDA --}}
                <a href="{{ route('profile.edit') }}">Perfil</a> 
                <form action="{{ route('logout') }}" method="POST" style="display: block;">
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
                            
                            @php
                                $standardCategories = ['Tecnología', 'Programación', 'Ciberseguridad', 'Inteligencia Artificial', 'Diseño UI/UX', 'Robótica'];
                                $oldCategory = old('main_category');
                                $isOtherOld = $oldCategory && !in_array($oldCategory, $standardCategories);
                            @endphp

                            <select id="categorySelect" name="main_category" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
                                <option value="" disabled {{ !$oldCategory ? 'selected' : '' }}>Selecciona una categoría</option>
                                @foreach($standardCategories as $cat)
                                    <option value="{{ $cat }}" {{ $oldCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                                <option value="Otro" {{ $isOtherOld || $oldCategory === 'Otro' ? 'selected' : '' }}>Otro (Escribir manualmente)</option>
                            </select>

                            <input type="text" id="otherCategoryInput" name="other_category" 
                                    value="{{ $isOtherOld ? $oldCategory : old('other_category') }}"
                                    placeholder="Escribe el nombre de la categoría..." 
                                    style="margin-top: 10px;" 
                                    class="{{ old('other_category') || $isOtherOld ? '' : 'hidden' }}"
                                    {{-- El atributo 'disabled' inicial será manejado por el JS en initializeForm() --}}
                                    disabled 
                                    {{ old('other_category') || $isOtherOld ? 'required' : '' }}>
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
                                <option value="Presencial" {{ old('modality') == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                <option value="Virtual" {{ old('modality') == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                                <option value="Híbrido" {{ old('modality') == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
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
        const form = document.getElementById('eventForm'); 
        const step1 = document.getElementById('step1');
        const step2 = document.getElementById('step2');
        
        const categorySelect = document.getElementById('categorySelect');
        const otherCategoryInput = document.getElementById('otherCategoryInput');

        // --- FUNCIONES DE MANEJO DE PASOS ---

        const toggleStepFields = (stepElement, enable) => {
            const fields = stepElement.querySelectorAll('input, select, textarea');
            fields.forEach(field => {
                field.disabled = !enable;
            });
        };

        const initializeForm = () => {
            // 1. Al cargar: Habilitamos el Paso 1 y Deshabilitamos el Paso 2
            toggleStepFields(step1, true);
            toggleStepFields(step2, false); 

            // 2. Lógica de Old Input para 'Otro' (Asegura que el campo esté deshabilitado si no se usó)
            const isOtherSelected = categorySelect.value === 'Otro' || (otherCategoryInput.value !== '' && otherCategoryInput.value !== null);

            if (isOtherSelected) {
                 otherCategoryInput.classList.remove('hidden');
                 otherCategoryInput.required = true;
                 otherCategoryInput.disabled = false; // Habilitar si ya tiene valor previo
            } else {
                 otherCategoryInput.classList.add('hidden');
                 otherCategoryInput.required = false;
                 otherCategoryInput.disabled = true; // Deshabilitar si está oculto
            }
        };
        
        // --- LÓGICA DE CATEGORÍA ---

        categorySelect.addEventListener('change', function() {
            if (this.value === 'Otro') {
                otherCategoryInput.classList.remove('hidden'); 
                otherCategoryInput.required = true;
                otherCategoryInput.disabled = false; // HABILITAR
                otherCategoryInput.focus();
            } else {
                otherCategoryInput.classList.add('hidden'); 
                otherCategoryInput.required = false;
                otherCategoryInput.value = '';
                otherCategoryInput.disabled = true; // DESHABILITAR
            }
        });

        // --- EVENTOS DE NAVEGACIÓN ---

        // Al avanzar al Paso 2: Deshabilita Paso 1, Habilita Paso 2
        nextBtn.addEventListener('click', (e) => {
            if (!form.checkValidity()) {
                 form.reportValidity();
                 return; 
            }
            
            // Deshabilita Paso 1 y Habilita Paso 2
            toggleStepFields(step1, false); 
            toggleStepFields(step2, true);
            
            step1.classList.remove('active');
            step2.classList.add('active');
        });

        // Al volver al Paso 1: Habilita Paso 1, Deshabilita Paso 2
        prevBtn.addEventListener('click', () => {
            // Habilita Paso 1 y Deshabilita Paso 2
            toggleStepFields(step2, false); 
            toggleStepFields(step1, true); 

            // Reajustar el input "Otro" (si no es 'Otro' debe estar deshabilitado)
            if (categorySelect.value !== 'Otro') {
                 otherCategoryInput.disabled = true;
            }

            step2.classList.remove('active');
            step1.classList.add('active');
        });
        
        // --- SOLUCIÓN CRÍTICA: Copiar valores deshabilitados a campos ocultos al enviar ---
        form.addEventListener('submit', (e) => {
            // Recorremos los campos del Paso 1 (y los del Paso 2 al volver) que están disabled
            const disabledFields = form.querySelectorAll('input:disabled, select:disabled, textarea:disabled');
            
            disabledFields.forEach(field => {
                // Solo si el campo tiene un valor (para evitar enviar basura)
                if (field.name && field.value) { 
                     // Creamos un input hidden temporal con el valor del campo deshabilitado
                     const hiddenInput = document.createElement('input');
                     hiddenInput.type = 'hidden';
                     hiddenInput.name = field.name;
                     hiddenInput.value = field.value;
                     form.appendChild(hiddenInput);
                }
            });
            
            // Nota: Aquí no necesitamos re-habilitar los campos originales. 
            // Con el input hidden, Laravel recibe los datos aunque el original esté disabled.
        });

        // Inicializar el formulario al cargar la página
        initializeForm();
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
                    <li><a href="{{ route('dashboard.admin') }}">Inicio</a></li>
                    <li><a href="#">Eventos</a></li> 
                    <li><a href="#">Categorías</a></li>
                    <li><a href="#">Calendario</a></li>
                </ul>
            </div>
            <div>
                <h3>Recursos</h3>
                <ul>
                    <li><a href="#">Preguntas frecuentes</a></li>
                    <li><a href="#">Cómo inscribirse</a></li>
                    <li><a href="#">Políticas de evento</a></li>
                </ul>
            </div>
            <div>
                <h3>Contactos</h3>
                <ul>
                    <li><a href="#">Información de Contacto</a></li>
                    <li><a href="#">Ubicación</a></li>
                    <li><a href="#">Redes Sociales</a></li>
                </ul>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>
</html>
