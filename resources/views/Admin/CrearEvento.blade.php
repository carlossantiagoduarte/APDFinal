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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">

    <style>
        /* --- ESTILOS ADICIONALES PARA INTEGRAR JUECES Y RÚBRICA AL DISEÑO ORIGINAL --- */
        
        /* Contenedor de la lista de jueces */
        .judges-container {
            border: 1px solid #ccc; /* Mismo borde que tus inputs */
            border-radius: 5px;
            padding: 15px;
            background: #fff;
            max-height: 300px;
            overflow-y: auto;
            margin-bottom: 25px;
        }
        
        /* Items de la lista de jueces */
        .judge-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px;
            border-bottom: 1px solid #eee;
            transition: background 0.2s;
        }
        .judge-item:hover { background-color: #f9f9f9; }
        .judge-item input[type="checkbox"] { width: auto; margin: 0; cursor: pointer; }
        .judge-item label { margin: 0; cursor: pointer; width: 100%; font-family: 'Inter', sans-serif; }

        /* Tabla de Rúbrica */
        .rubric-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
        .rubric-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .rubric-table th { text-align: left; background: #f4f4f4; padding: 10px; font-weight: 600; color: #333; border: 1px solid #ddd; }
        .rubric-table td { padding: 8px; border: 1px solid #ddd; }
        /* Hacemos que los inputs de la tabla se parezcan a los tuyos */
        .rubric-table input { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; font-family: 'Inter', sans-serif; }

        /* Botones pequeños para la rúbrica */
        .btn-add-rubric { background-color: #28a745; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: 600; font-size: 0.9em; }
        .btn-remove-rubric { background-color: #dc3545; color: white; border: none; padding: 6px 12px; border-radius: 4px; cursor: pointer; font-weight: bold; }
        .btn-add-rubric:hover { background-color: #218838; }
        .btn-remove-rubric:hover { background-color: #c82333; }

        /* Input de búsqueda estilo original */
        .search-input-styled {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 10px; box-sizing: border-box; font-family: 'Inter', sans-serif;
        }

        /* Utilidades del sistema original */
        .step { display: none; }
        .step.active { display: block; }
        .hidden { display: none; }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const toggle = document.getElementById("user-toggle");
            const menu = document.getElementById("user-menu");
            if(toggle && menu) {
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
        <h2 class="hero-title">¡Registra un nuevo Evento!</h2>
    </section>

    <div class="event">
        <div class="form-container">
            <h2>Información del Evento</h2>

            @if ($errors->any())
                <div style="background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
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
                            <label>Categoría Principal</label>
                            @php
                                $standardCategories = ['Tecnología', 'Programación', 'Ciberseguridad', 'Inteligencia Artificial', 'Diseño UI/UX', 'Robótica'];
                                $oldCategory = old('main_category');
                                $isOtherOld = $oldCategory && !in_array($oldCategory, $standardCategories);
                            @endphp
                            <select id="categorySelect" name="main_category" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 5px;" required>
                                <option value="" disabled {{ !$oldCategory ? 'selected' : '' }}>Selecciona una categoría</option>
                                @foreach ($standardCategories as $cat)
                                    <option value="{{ $cat }}" {{ $oldCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                @endforeach
                                <option value="Otro" {{ $isOtherOld || $oldCategory === 'Otro' ? 'selected' : '' }}>Otro (Escribir manualmente)</option>
                            </select>
                            <input type="text" id="otherCategoryInput" name="other_category" value="{{ $isOtherOld ? $oldCategory : old('other_category') }}" placeholder="Escribe el nombre de la categoría..." style="margin-top: 10px;" class="{{ old('other_category') || $isOtherOld ? '' : 'hidden' }}" disabled>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label>Organización / Responsable</label>
                            <input type="text" name="organizer" placeholder="Nombre del Organizador" value="{{ old('organizer') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Modalidad</label>
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
                            <input type="email" name="email" placeholder="contacto@evento.com" value="{{ old('email', Auth::user()->email) }}" required>
                        </div>
                        <div class="form-group">
                            <label>Número de contacto</label>
                            <input type="text" name="phone" placeholder="+52..." value="{{ old('phone', Auth::user()->phone ?? '') }}" required>
                        </div>
                        <div class="form-group">
                            <label>Capacidad</label>
                            <input type="number" name="max_participants" placeholder="Ej: 200" value="{{ old('max_participants') }}" required>
                        </div>
                    </div>
                    
                    <label>Requisitos de participación</label>
                    <textarea name="requirements" placeholder="Lista de requisitos...">{{ old('requirements') }}</textarea>

                    <div class="buttons">
                        <button type="button" onclick="window.location='{{ route('dashboard.admin') }}'">Cancelar</button>
                        <button type="button" onclick="changeStep(1, 2)">Siguiente →</button>
                    </div>
                </div>

                <div class="step" id="step2">
                    <div class="form-row">
                        <div class="form-group"><label>Fecha Inicio</label><input type="date" name="start_date" value="{{ old('start_date') }}" required></div>
                        <div class="form-group"><label>Fecha Fin</label><input type="date" name="end_date" value="{{ old('end_date') }}" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Hora Inicio</label><input type="time" name="start_time" value="{{ old('start_time') }}" required></div>
                        <div class="form-group"><label>Hora Fin</label><input type="time" name="end_time" value="{{ old('end_time') }}" required></div>
                    </div>
                    <div class="form-row">
                        <div class="form-group"><label>Imagen Cuadrada (URL)</label><input type="url" name="image_url" placeholder="https://..." value="{{ old('image_url') }}"></div>
                        <div class="form-group"><label>Banner Horizontal (URL)</label><input type="url" name="banner_url" placeholder="https://..." value="{{ old('banner_url') }}"></div>
                    </div>
                    <div class="form-group">
                        <label>Link Registro Externo (Opcional)</label>
                        <input type="url" name="registration_link" placeholder="https://..." value="{{ old('registration_link') }}">
                    </div>
                    <label>Documentos adjuntos / Info Extra</label>
                    <textarea name="documents_info" placeholder="Enlaces a PDFs...">{{ old('documents_info') }}</textarea>

                    <div class="buttons">
                        <button type="button" onclick="changeStep(2, 1)">← Anterior</button>
                        <button type="button" onclick="changeStep(2, 3)">Siguiente →</button>
                    </div>
                </div>

                <div class="step" id="step3">
                    
                    <h2 style="font-size: 1.5em; margin-bottom: 15px; border-bottom: 2px solid #eee; padding-bottom: 10px;">Asignación de Jueces</h2>
                    <p style="color: #666; margin-bottom: 10px;">Selecciona los jueces que evaluarán este evento.</p>
                    
                    <input type="text" id="judgeSearch" class="search-input-styled" placeholder="Buscar juez por nombre..." onkeyup="filterJudges()">

                    <div class="judges-container">
                        <div class="judges-list" id="judgesList">
                            {{-- USAMOS LA VARIABLE CORRECTA: $judges --}}
                            @if(isset($judges) && count($judges) > 0)
                                @foreach($judges as $judge)
                                    <div class="judge-item" data-name="{{ strtolower($judge->name . ' ' . $judge->lastname) }}">
                                        <input type="checkbox" name="judges[]" value="{{ $judge->id }}" id="juez_{{ $judge->id }}"
                                        {{ (is_array(old('judges')) && in_array($judge->id, old('judges'))) ? 'checked' : '' }}>
                                        
                                        <label for="juez_{{ $judge->id }}">
                                            <strong style="color: #333;">{{ $judge->name }} {{ $judge->lastname }}</strong>
                                            <span style="display:block; font-size:0.85em; color:#777;">{{ $judge->email }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <p style="padding: 20px; text-align: center; color: #777;">No se encontraron jueces registrados.</p>
                            @endif
                        </div>
                    </div>

                    <br>

                    <div class="rubric-header" style="border-bottom: 2px solid #eee; padding-bottom: 10px; margin-bottom: 15px;">
                        <h2 style="font-size: 1.5em; margin: 0;">Rúbrica de Evaluación</h2>
                        <h3 style="margin: 0; color: #555;">Total: <span id="totalPoints" style="font-weight: bold; color: #333;">0</span>/100</h3>
                    </div>
                    <p style="color: #666; margin-bottom: 15px;">Define los criterios. La suma debe ser exactamente 100 puntos.</p>

                    <table class="rubric-table" id="rubricTable">
                        <thead>
                            <tr>
                                <th style="width: 60%;">Criterio (Ej: Diseño)</th>
                                <th style="width: 25%;">Puntos Máx.</th>
                                <th style="width: 15%; text-align: center;">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" name="criteria_name[]" placeholder="Nombre del criterio" required></td>
                                <td><input type="number" name="criteria_points[]" class="points-input" placeholder="0" min="1" max="100" required oninput="validateSum(this)"></td>
                                <td><button type="button" class="btn-remove-rubric" disabled style="opacity: 0.5; cursor: not-allowed;">Eliminar</button></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="btn-add-rubric" onclick="addRubricRow()">+ Agregar Criterio</button>

                    <br><br><br>

                    <div class="buttons">
                        <button type="button" onclick="changeStep(3, 2)">← Anterior</button>
                        <button type="submit" id="submitBtn">Crear Evento</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <script>
        const form = document.getElementById('eventForm');

        // --- 1. CONTROL DE PASOS Y VALIDACIÓN ---
        function changeStep(current, next) {
            const currentStep = document.getElementById('step' + current);
            const nextStep = document.getElementById('step' + next);

            // Validar antes de avanzar
            if (next > current) {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }
            }

            currentStep.classList.remove('active');
            nextStep.classList.add('active');
            
            // Habilitar/Deshabilitar campos para evitar problemas al enviar
            toggleFields(currentStep, true); // Deshabilitar el que dejamos
            toggleFields(nextStep, false);   // Habilitar el nuevo
        }

        function toggleFields(stepDiv, disable) {
            const fields = stepDiv.querySelectorAll('input, select, textarea');
            fields.forEach(f => f.disabled = disable);
            // Excepción para la categoría "Otro" en el paso 1
            if (!disable && stepDiv.id === 'step1') {
                const catSelect = document.getElementById('categorySelect');
                const otherInput = document.getElementById('otherCategoryInput');
                if (catSelect.value !== 'Otro') otherInput.disabled = true;
            }
        }

        // --- 2. LÓGICA CATEGORÍA "OTRO" ---
        const categorySelect = document.getElementById('categorySelect');
        const otherCategoryInput = document.getElementById('otherCategoryInput');
        if(categorySelect) {
            categorySelect.addEventListener('change', function() {
                if (this.value === 'Otro') {
                    otherCategoryInput.classList.remove('hidden'); otherCategoryInput.required = true; otherCategoryInput.disabled = false; otherCategoryInput.focus();
                } else {
                    otherCategoryInput.classList.add('hidden'); otherCategoryInput.required = false; otherCategoryInput.value = ''; otherCategoryInput.disabled = true;
                }
            });
        }

        // --- 3. BUSCADOR DE JUECES ---
        function filterJudges() {
            const filter = document.getElementById('judgeSearch').value.toLowerCase();
            const items = document.getElementsByClassName('judge-item');
            for (let item of items) {
                item.style.display = item.dataset.name.includes(filter) ? "" : "none";
            }
        }

        // --- 4. RÚBRICA INTELIGENTE (SUMA 100) ---
        function validateSum(input) {
            let totalOthers = 0;
            document.querySelectorAll('.points-input').forEach(i => { if (i !== input) totalOthers += parseInt(i.value) || 0; });
            let currentVal = parseInt(input.value) || 0;
            let remaining = 100 - totalOthers;

            if (currentVal > remaining) { input.value = remaining; } // No permitir pasarse
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.points-input').forEach(i => total += parseInt(i.value) || 0);
            const totalSpan = document.getElementById('totalPoints');
            totalSpan.innerText = total;
            totalSpan.style.color = (total === 100) ? "#28a745" : "#dc3545";
            return total;
        }

        function addRubricRow() {
            if (updateTotal() >= 100) { alert("Ya has alcanzado los 100 puntos."); return; }
            const table = document.getElementById('rubricTable').getElementsByTagName('tbody')[0];
            const newRow = table.insertRow();
            newRow.innerHTML = `
                <td><input type="text" name="criteria_name[]" placeholder="Nuevo Criterio" required></td>
                <td><input type="number" name="criteria_points[]" class="points-input" placeholder="0" min="1" max="100" required oninput="validateSum(this)"></td>
                <td style="text-align: center;"><button type="button" class="btn-remove-rubric" onclick="removeRow(this)">Eliminar</button></td>`;
        }

        function removeRow(btn) {
            const row = btn.closest('tr');
            if (document.querySelectorAll('#rubricTable tbody tr').length > 1) {
                row.remove(); updateTotal();
            }
        }

        // --- INICIALIZACIÓN Y SUBMIT FINAL ---
        document.addEventListener("DOMContentLoaded", () => {
            toggleFields(document.getElementById('step2'), true);
            toggleFields(document.getElementById('step3'), true);
            updateTotal();
        });

        form.addEventListener('submit', (e) => {
            if (updateTotal() !== 100) {
                e.preventDefault();
                alert("La rúbrica debe sumar exactamente 100 puntos.");
                return;
            }
            // Habilitar campos ocultos antes de enviar
            form.querySelectorAll('input:disabled, select:disabled, textarea:disabled').forEach(f => {
                if (f.id === 'otherCategoryInput' && categorySelect.value !== 'Otro') return;
                f.disabled = false;
            });
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