<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar: {{ $event->title }} | CodeVision</title>
    
    <link rel="stylesheet" href="{{ asset('styles/event-information.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

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
        }
        .back-link:hover {
            background-color: #f0f0f0;
        }
        /* Clase para inputs editables */
        .editable {
            background-color: #fff !important;
            border: 1px solid #4CAF50 !important;
            cursor: text !important;
        }
        .hidden {
            display: none;
        }
    </style>
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
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
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
        <h2 class="hero-title">Información del Evento</h2>
    </section>

    <div class="event">
        <div class="form-container">
            <h2>Datos del Evento: {{ $event->title }}</h2>

            @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 15px;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- CORRECCIÓN: La URL usa el modelo como parámetro de ruta --}}
            <form id="eventForm" action="{{ route('events.update', $event) }}" method="POST">
                @csrf
                @method('PUT') 

                <div class="step active" id="step1">

                    <div class="form-row">
                        <div class="form-group">
                            <label>Nombre del Evento</label>
                            <input type="text" name="title" value="{{ old('title', $event->title) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Organización</label>
                            <input type="text" name="organizer" value="{{ old('organizer', $event->organizer) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Lugar</label>
                            <input type="text" name="location" value="{{ old('location', $event->location) }}" disabled required>
                        </div>
                    </div>

                    <label>Descripción</label>
                    <textarea name="description" disabled required>{{ old('description', $event->description) }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Correo</label>
                            <input type="email" name="email" value="{{ old('email', $event->email) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Teléfono</label>
                            <input type="text" name="phone" value="{{ old('phone', $event->phone) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Capacidad</label>
                            <input type="number" name="max_participants" value="{{ old('max_participants', $event->max_participants) }}" disabled required>
                        </div>
                    </div>

                    <label>Requisitos</label>
                    <textarea name="requirements" disabled>{{ old('requirements', $event->requirements) }}</textarea>

                    <div class="buttons">
                        <button type="button" id="editarBtn" style="background-color: #ff9800; color: white;">Habilitar Edición</button>
                        <button type="button" id="eliminarBtn" style="background-color: #f44336; color: white;">Eliminar Evento</button>
                        <button type="button" id="nextBtn">Siguiente →</button>
                    </div>

                </div>

                <div class="step" id="step2">
                    {{-- Nota: El JS maneja el disabled de estos campos al cambiar de paso --}}
                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $event->start_date) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Fecha Fin</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $event->end_date) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Imagen URL</label>
                            <input type="url" name="image_url" value="{{ old('image_url', $event->image_url) }}" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                         @php
                            $standardCategories = ['Tecnología', 'Programación', 'Ciberseguridad', 'Inteligencia Artificial', 'Diseño UI/UX', 'Robótica'];
                            $currentCategory = old('main_category', $event->main_category);
                            $isOther = !in_array($currentCategory, $standardCategories);
                         @endphp

                         <div class="form-group">
                            <label>Categoría</label>
                            <select id="categorySelect" name="main_category" disabled style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
                                @foreach($standardCategories as $cat)
                                    <option value="{{ $cat }}" {{ $currentCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                                <option value="Otro" {{ $isOther ? 'selected' : '' }}>Otro (Escribir manualmente)</option>
                            </select>

                            <input type="text" id="otherCategoryInput" name="other_category" 
                                    value="{{ $isOther ? $currentCategory : '' }}"
                                    placeholder="Escribe la categoría..." 
                                    style="margin-top: 10px;" 
                                    class="{{ $isOther ? '' : 'hidden' }}"
                                    disabled>
                        </div>

                        <div class="form-group">
                            <label>Modalidad</label>
                            <select name="modality" disabled style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                                <option value="Presencial" {{ $event->modality == 'Presencial' ? 'selected' : '' }}>Presencial</option>
                                <option value="Virtual" {{ $event->modality == 'Virtual' ? 'selected' : '' }}>Virtual</option>
                                <option value="Híbrido" {{ $event->modality == 'Híbrido' ? 'selected' : '' }}>Híbrido</option>
                            </select>
                        </div>
                    </div>
                    
                    {{-- Campo Banner URL --}}
                    <div class="form-group">
                        <label>Banner Horizontal (URL)</label>
                        <input type="url" name="banner_url" value="{{ old('banner_url', $event->banner_url) }}" disabled>
                    </div>
                    
                    {{-- Campo Link de Registro Externo --}}
                    <div class="form-group">
                        <label>Link de Registro Externo (Opcional)</label>
                        <input type="url" name="registration_link" value="{{ old('registration_link', $event->registration_link) }}" disabled>
                    </div>

                    <label>Documentos / Info Extra</label>
                    <textarea name="documents_info" disabled>{{ old('documents_info', $event->documents_info) }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Hora Inicio</label>
                            <input type="time" name="start_time" value="{{ old('start_time', $event->start_time) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Hora Fin</label>
                            <input type="time" name="end_time" value="{{ old('end_time', $event->end_time) }}" disabled required>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="button" id="prevBtn">← Anterior</button>
                        <button type="submit" id="guardarBtn" disabled style="background-color: #ccc; cursor: not-allowed;">Guardar Cambios</button>
                    </div>

                </div>

            </form>
        </div>
    </div>

    <form id="deleteForm" action="{{ route('events.destroy', $event) }}" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        const editarBtn = document.getElementById("editarBtn");
        const eliminarBtn = document.getElementById("eliminarBtn");
        const guardarBtn = document.getElementById("guardarBtn");
        const deleteForm = document.getElementById("deleteForm");
        
        const inputs = document.querySelectorAll("#eventForm input, #eventForm textarea, #eventForm select");
        
        const nextBtn = document.getElementById("nextBtn");
        const prevBtn = document.getElementById("prevBtn");
        const step1 = document.getElementById("step1");
        const step2 = document.getElementById("step2");

        const categorySelect = document.getElementById('categorySelect');
        const otherCategoryInput = document.getElementById('otherCategoryInput');

        // Función para habilitar/deshabilitar campos de un paso
        const toggleStepFields = (stepElement, enable) => {
            const fields = stepElement.querySelectorAll('input, select, textarea');
            fields.forEach(field => {
                field.disabled = !enable;
            });
        };

        // Al cargar: Deshabilitar Paso 2 inicialmente para validación
        toggleStepFields(step2, false);

        // Lógica para Select de Categoría
        if (categorySelect.value !== 'Otro') {
            otherCategoryInput.classList.add('hidden');
        } else {
             // Si el valor actual es 'Otro', lo habilitamos inicialmente para el modo lectura/escritura
             otherCategoryInput.disabled = true; // Inicia deshabilitado en modo lectura
        }


        categorySelect.addEventListener('change', function() {
            const isEditing = !editarBtn.disabled;
            
            if (this.value === 'Otro') {
                otherCategoryInput.classList.remove('hidden');
                otherCategoryInput.required = true;
                if (isEditing) {
                    otherCategoryInput.disabled = false; // Habilitar
                    otherCategoryInput.classList.add("editable");
                }
                otherCategoryInput.focus();
            } else {
                otherCategoryInput.classList.add('hidden');
                otherCategoryInput.required = false;
                otherCategoryInput.disabled = true; 
                otherCategoryInput.classList.remove("editable");
                otherCategoryInput.value = '';
            }
        });

        // Habilitar Edición
        editarBtn.addEventListener("click", () => {
            // Re-habilita todos los inputs y selecciona el paso 1 por defecto
            toggleStepFields(step1, true);
            toggleStepFields(step2, false); 
            
            inputs.forEach(el => {
                el.disabled = false;
                el.classList.add("editable");

                // Manejo especial para el input de "Otra Categoría"
                if (el.id === 'otherCategoryInput' && categorySelect.value !== 'Otro') {
                    // Si no está seleccionada la opción 'Otro', deshabilitamos el input de texto extra
                    el.disabled = true;
                    el.classList.remove("editable");
                }
            });

            guardarBtn.disabled = false;
            guardarBtn.style.backgroundColor = "#4CAF50";
            guardarBtn.style.cursor = "pointer";
            
            editarBtn.textContent = "Edición Activa";
            editarBtn.disabled = true;
            editarBtn.style.backgroundColor = "#ccc";
        });

        // Navegación entre pasos
        nextBtn.addEventListener("click", () => {
            const form = document.getElementById('eventForm');
            
            // 1. Validar solo el Paso 1
            if (!form.checkValidity()) {
                 form.reportValidity();
                 return; 
            }

            // 2. Mover la validación y el CSS
            toggleStepFields(step1, false); // Deshabilita Paso 1
            toggleStepFields(step2, true);  // Habilita Paso 2

            step1.classList.remove("active");
            step2.classList.add("active");
        });

        prevBtn.addEventListener("click", () => {
            // 1. Mover la validación y el CSS
            toggleStepFields(step2, false); // Deshabilita Paso 2
            toggleStepFields(step1, true);  // Habilita Paso 1

            // Reajustar el input "Otro" al volver, si no es 'Otro' debe estar deshabilitado
            if (categorySelect.value !== 'Otro') {
                 otherCategoryInput.disabled = true;
            }

            step2.classList.remove("active");
            step1.classList.add("active");
        });

        // Eliminar Evento
        eliminarBtn.addEventListener("click", () => {
            if (confirm("¿Estás SEGURO de eliminar este evento? Esta acción no se puede deshacer.")) {
                deleteForm.submit();
            }
        });
    </script>

    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca.</p>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

</body>

</html>
