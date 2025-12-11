<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Editar: {{ $event->title }} | CodeVision</title>

    <link rel="stylesheet" href="{{ asset('styles/editarevento.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* ESTILOS PARA LA RÚBRICA Y JUECES */
        .judges-container {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            background: #fafafa;
            max-height: 250px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .search-box {
            width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;
        }
        .judge-item {
            display: flex; align-items: center; gap: 10px; padding: 8px; border-bottom: 1px solid #eee; background: white;
        }
        .rubric-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .rubric-table th, .rubric-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .rubric-table th { background-color: #f4f4f4; font-weight: 600; }
        .rubric-table input { width: 100%; box-sizing: border-box; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        
        .btn-add { background-color: #28a745; color: white; border: none; padding: 8px 12px; border-radius: 4px; cursor: pointer; }
        .btn-remove { background-color: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; }
        
        .step { display: none; }
        .step.active { display: block; }
        .hidden { display: none; }
        /* Solo aplicaremos esta clase a los inputs de texto, no a los botones */
        .editable { background-color: white !important; cursor: text !important; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("user-toggle");
            const menu = document.getElementById("user-menu");
            if(toggle && menu){
                toggle.addEventListener("click", () => { toggle.classList.toggle("active"); menu.classList.toggle("show"); });
                document.addEventListener("click", (e) => { if (!toggle.contains(e.target) && !menu.contains(e.target)) { toggle.classList.remove("active"); menu.classList.remove("show"); } });
            }
        });
    </script>
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ route('dashboard.admin') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9" /></svg>
            </div>
            <div id="user-menu" class="dropdown">
                <a href="{{ route('dashboard.admin') }}">Inicio (Admin)</a>
                <a href="{{ route('profile.edit') }}">Perfil</a>
                <a href="{{ route('gestion') }}">Gestión de Usuarios</a>
                <form action="{{ route('logout') }}" method="POST" style="display: block;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();">Cerrar sesión</a>
                </form>
            </div>
        </div>
    </nav>

    <a href="javascript:history.back()" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
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
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

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
                        <button type="button" onclick="changeStep(1, 2)">Siguiente →</button>
                    </div>
                </div>

                <div class="step" id="step2">
                    <div class="form-row">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="date" name="start_date" value="{{ old('start_date', $event->start_date) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Fecha Fin</label>
                            <input type="date" name="end_date" value="{{ old('end_date', $event->end_date) }}" disabled required>
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
                                @foreach ($standardCategories as $cat)
                                    <option value="{{ $cat }}" {{ $currentCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                                <option value="Otro" {{ $isOther ? 'selected' : '' }}>Otro (Escribir manualmente)</option>
                            </select>
                            <input type="text" id="otherCategoryInput" name="other_category" value="{{ $isOther ? $currentCategory : '' }}" placeholder="Escribe la categoría..." style="margin-top: 10px;" class="{{ $isOther ? '' : 'hidden' }}" disabled>
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

                    <div class="form-group">
                        <label>Banner Horizontal (URL)</label>
                        <input type="url" name="banner_url" value="{{ old('banner_url', $event->banner_url) }}" disabled>
                    </div>

                    <div class="form-group">
                        <label>Link de Registro Externo (Opcional)</label>
                        <input type="url" name="registration_link" value="{{ old('registration_link', $event->registration_link) }}" disabled>
                    </div>

                    <label>Documentos / Info Extra</label>
                    <textarea name="documents_info" disabled>{{ old('documents_info', $event->documents_info) }}</textarea>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Hora Inicio</label>
                            <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($event->start_time)->format('H:i')) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Hora Fin</label>
                            <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($event->end_time)->format('H:i')) }}" disabled required>
                        </div>
                        <div class="form-group">
                            <label>Imagen URL</label>
                            <input type="url" name="image_url" value="{{ old('image_url', $event->image_url) }}" disabled>
                        </div>
                    </div>

                    <div class="buttons">
                        <button type="button" onclick="changeStep(2, 1)">← Anterior</button>
                        <button type="button" onclick="changeStep(2, 3)">Siguiente →</button>
                    </div>
                </div>

                <div class="step" id="step3">
                    
                    <h3>Asignación de Jueces</h3>
                    <div class="judges-container">
                        <input type="text" id="judgeSearch" class="search-box" placeholder="Buscar juez..." onkeyup="filterJudges()" disabled>
                        <div class="judges-list" id="judgesList">
                            @if(isset($judges) && count($judges) > 0)
                                @foreach($judges as $judge)
                                    @php
                                        // Verificar si el juez ya está asignado
                                        $isSelected = $event->jueces->contains($judge->id);
                                    @endphp
                                    <div class="judge-item" data-name="{{ strtolower($judge->name . ' ' . $judge->lastname) }}">
                                        <input type="checkbox" name="judges[]" value="{{ $judge->id }}" 
                                               id="juez_{{ $judge->id }}" {{ $isSelected ? 'checked' : '' }} disabled>
                                        <label for="juez_{{ $judge->id }}">
                                            <strong>{{ $judge->name }} {{ $judge->lastname }}</strong> ({{ $judge->email }})
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p style="padding:10px;">No hay jueces registrados.</p>
                            @endif
                        </div>
                    </div>

                    <br>

                    <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #ddd; padding-bottom: 10px;">
                        <h3>Rúbrica de Evaluación</h3>
                        <h4>Total: <span id="totalPoints">0</span>/100</h4>
                    </div>
                    
                    <table class="rubric-table" id="rubricTable">
                        <thead>
                            <tr>
                                <th width="60%">Criterio</th>
                                <th width="30%">Puntos</th>
                                <th width="10%">Borrar</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- CARGAR CRITERIOS EXISTENTES --}}
                            @forelse($event->criteria as $criterio)
                                <tr>
                                    <td><input type="text" name="criteria_name[]" value="{{ $criterio->name }}" required disabled></td>
                                    <td><input type="number" name="criteria_points[]" class="points-input" 
                                               value="{{ $criterio->max_points }}" min="1" max="100" required disabled 
                                               oninput="validateSum(this)"></td>
                                    <td><button type="button" class="btn-remove" disabled onclick="removeRow(this)">×</button></td>
                                </tr>
                            @empty
                                {{-- Si no hay criterios, fila vacía --}}
                                <tr>
                                    <td><input type="text" name="criteria_name[]" placeholder="Nuevo Criterio" required disabled></td>
                                    <td><input type="number" name="criteria_points[]" class="points-input" 
                                               placeholder="0" min="1" max="100" required disabled 
                                               oninput="validateSum(this)"></td>
                                    <td><button type="button" class="btn-remove" disabled>×</button></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <button type="button" id="btnAddRubric" class="btn-add" onclick="addRubricRow()" disabled>+ Criterio</button>

                    <br><br>

                    <div class="buttons">
                        <button type="button" onclick="changeStep(3, 2)">← Anterior</button>
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
        // Referencias
        const editarBtn = document.getElementById("editarBtn");
        const guardarBtn = document.getElementById("guardarBtn");
        const deleteForm = document.getElementById("deleteForm");
        const eliminarBtn = document.getElementById("eliminarBtn");
        const form = document.getElementById('eventForm');
        
        // Elementos de Rubrica y Jueces
        const judgeSearch = document.getElementById('judgeSearch');
        const btnAddRubric = document.getElementById('btnAddRubric');

        // Navegación
        function changeStep(curr, next) {
            document.getElementById('step'+curr).classList.remove('active');
            document.getElementById('step'+next).classList.add('active');
        }

        // --- RÚBRICA ---
        function validateSum(input) {
            let totalOthers = 0;
            document.querySelectorAll('.points-input').forEach(i => { if (i !== input) totalOthers += parseInt(i.value) || 0; });
            let currentVal = parseInt(input.value) || 0;
            let remaining = 100 - totalOthers;
            if (currentVal > remaining) { input.value = remaining; }
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.points-input').forEach(i => total += parseInt(i.value) || 0);
            const span = document.getElementById('totalPoints');
            span.innerText = total;
            span.style.color = (total === 100) ? 'green' : 'red';
            return total;
        }

        function addRubricRow() {
            if (updateTotal() >= 100) { alert("Límite de 100 puntos alcanzado."); return; }
            const table = document.getElementById('rubricTable').getElementsByTagName('tbody')[0];
            const row = table.insertRow();
            row.innerHTML = `
                <td><input type="text" name="criteria_name[]" required></td>
                <td><input type="number" name="criteria_points[]" class="points-input" required oninput="validateSum(this)"></td>
                <td><button type="button" class="btn-remove" onclick="removeRow(this)">×</button></td>
            `;
        }

        function removeRow(btn) {
            const row = btn.closest('tr');
            row.remove();
            updateTotal();
        }

        // --- HABILITAR EDICIÓN (CORREGIDO) ---
        editarBtn.addEventListener("click", () => {
            
            // 1. Habilitar TODOS los elementos funcionales (incluyendo botones de rúbrica)
            const allElements = form.querySelectorAll('input, select, textarea, button');
            allElements.forEach(el => {
                // No habilitar botones que no queremos que toquen
                if(el.id !== 'eliminarBtn' && el.id !== 'editarBtn') {
                    el.disabled = false;
                }
            });

            // 2. Aplicar estilo visual "editable" SOLO a campos de texto (NO a botones)
            const textFields = form.querySelectorAll('input, select, textarea');
            textFields.forEach(el => {
                el.classList.add("editable");
            });

            // Manejo especial categoría 'Otro'
            const catSelect = document.getElementById('categorySelect');
            const otherInput = document.getElementById('otherCategoryInput');
            if (catSelect.value !== 'Otro') {
                otherInput.disabled = true;
                otherInput.classList.remove('editable');
            }

            // Cambios visuales
            guardarBtn.style.backgroundColor = "#4CAF50";
            guardarBtn.style.cursor = "pointer";
            
            editarBtn.textContent = "Edición Activa";
            editarBtn.disabled = true;
            editarBtn.style.backgroundColor = "#ccc";
        });

        // --- CATEGORÍA ---
        const categorySelect = document.getElementById('categorySelect');
        const otherCategoryInput = document.getElementById('otherCategoryInput');
        if(categorySelect) {
            categorySelect.addEventListener('change', function() {
                if (this.value === 'Otro') {
                    otherCategoryInput.classList.remove('hidden'); otherCategoryInput.disabled = false; otherCategoryInput.focus();
                } else {
                    otherCategoryInput.classList.add('hidden'); otherCategoryInput.disabled = true; otherCategoryInput.value = '';
                }
            });
        }

        // --- BUSCADOR JUECES ---
        function filterJudges() {
            const filter = document.getElementById('judgeSearch').value.toLowerCase();
            const items = document.getElementsByClassName('judge-item');
            for (let item of items) {
                item.style.display = item.dataset.name.includes(filter) ? "" : "none";
            }
        }

        // Eliminar
        eliminarBtn.addEventListener("click", () => {
            if (confirm("¿Estás seguro de eliminar este evento?")) deleteForm.submit();
        });

        // Init
        document.addEventListener("DOMContentLoaded", () => {
            updateTotal();
        });

        // --- CORRECCIÓN CRÍTICA PARA GUARDAR ---
        form.addEventListener('submit', (e) => {
            // Validar suma 100
            if (updateTotal() !== 100) {
                e.preventDefault();
                alert("La rúbrica debe sumar exactamente 100 puntos.");
                return;
            }

            // HABILITAR TODOS LOS CAMPOS para que se envíen
            const disabledInputs = form.querySelectorAll('input:disabled, select:disabled, textarea:disabled');
            disabledInputs.forEach(input => {
                // Excepción para input 'Otro' si no se usa
                if (input.id === 'otherCategoryInput' && categorySelect.value !== 'Otro') {
                    return; 
                }
                input.disabled = false;
            });
        });
    </script>

    <footer class="footer">
        <p class="footer-copy">© {{ date('Y') }} CodeVision</p>
    </footer>

</body>
</html>