<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gestión de Usuarios | CodeVision</title>

    <link rel="stylesheet" href="{{ asset('styles/gestionusuarios.css') }} ">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
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
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            {{-- ENLACE AL DASHBOARD ESTUDIANTE --}}
            <a href="{{ route('dashboard.admin') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>

        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                {{ Auth::user()->name }} {{ Auth::user()->lastname ?? '' }}
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <div id="user-menu" class="dropdown">
                {{-- ENLACE A INICIO (DASHBOARD ADMIN) --}}
                <a href="{{ route('dashboard.admin') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 9.5L12 3l9 6.5V21H3z" />
                    </svg>
                    Inicio (Admin)
                </a>
                
                {{-- ENLACE A PERFIL --}}
                <a href="{{ route('profile.edit') }}">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                    </svg>
                    Perfil
                </a>

                <a style="background-color: #f0f0f0;">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                    Gestión de Usuarios
                </a>

                {{-- FORMULARIO LOGOUT --}}
                <form action="{{ route('logout') }}" method="POST" style="display: block;">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit();"
                        style="display: flex; align-items: center; gap: 10px;">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" style="width: 20px;">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Cerrar sesión
                    </a>
                </form>
            </div>
        </div>
    </nav>

    <a href="{{ route('dashboard.admin') }}" class="back-link">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        Regresar al Dashboard
    </a>

    <h1 style="text-align: center; margin-top: 30px;">Gestión de Usuarios</h1>

    <section class="search-section">
        <div class="search-box">
            <input type="text" id="search" placeholder="Buscar usuarios..." oninput="searchTable()" />
            <select id="roleFilter" onchange="searchTable()">
                <option value="">Filtrar por rol</option>
                <option value="admin">Administrador</option>
                <option value="judge">Juez</option>
                <option value="student">Estudiante</option>
            </select>
            <button class="btn-search" onclick="searchTable()">Filtrar</button>
        </div>
    </section>

    @if (session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert" style="background-color: #f44336;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="table-container">
        <table id="usersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr data-role="{{ $user->role }}">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }} {{ $user->lastname ?? '' }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <form action="{{ route('users.updateRole', $user->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <select name="role" class="form-control" style="width: 90%;">
                                    <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>
                                        Administrador</option>
                                    <option value="judge" {{ $user->role == 'judge' ? 'selected' : '' }}>Juez
                                    </option>
                                    <option value="student" {{ $user->role == 'student' ? 'selected' : '' }}>
                                        Estudiante</option>
                                </select>
                                <button type="submit" class="btn-admin btn-admin-green"
                                    style="font-size: 0.8rem; padding: 5px 10px;">
                                    Guardar
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-admin btn-admin-red">
                                    Borrar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div id="pagination" class="pagination">
    </div>


    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>
            <div>
                <h3>Enlaces Rápidos</h3>
                <ul>
                    <li><a href="{{ route('dashboard.admin') }}">Inicio</a></li>
                    <li><a href="#">Eventos</a></li>
                </ul>
            </div>
            <div>
                <h3>Contactos</h3>
                <ul>
                    <li><a href="#">Información de Contacto</a></li>
                </ul>
            </div>
        </div>
        <p class="footer-copy">© {{ date('Y') }} CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

    <script>
        // --- Lógica de Búsqueda y Filtrado ---
        function searchTable() {
            let input = document.getElementById('search');
            let filter = input.value.toLowerCase();
            let roleFilter = document.getElementById('roleFilter').value.toLowerCase();
            let table = document.getElementById("usersTable");
            let tr = table.getElementsByTagName("tr");

            // Recorremos todas las filas, ocultando las que no coinciden
            // Comenzamos en i=1 para saltar el encabezado
            let visibleRowsCount = 0;

            // Primero filtramos todo
            for (let i = 1; i < tr.length; i++) {
                let tdName = tr[i].getElementsByTagName("td")[1]; // Columna Nombre
                let tdEmail = tr[i].getElementsByTagName("td")[2]; // Columna Email
                let match = false;

                // Verificamos Texto
                if (tdName || tdEmail) {
                    let nameVal = tdName.textContent || tdName.innerText;
                    let emailVal = tdEmail.textContent || tdEmail.innerText;

                    if (nameVal.toLowerCase().indexOf(filter) > -1 || emailVal.toLowerCase().indexOf(filter) > -1) {
                        match = true;
                    }
                }

                // Verificamos Rol
                let role = tr[i].getAttribute('data-role');
                if (roleFilter && role !== roleFilter) {
                    match = false;
                }

                // Aplicar visibilidad temporal para el conteo (la paginación manejará el display final)
                if (match) {
                    tr[i].classList.add('filtered-visible');
                    tr[i].classList.remove('filtered-hidden');
                    visibleRowsCount++;
                } else {
                    tr[i].classList.remove('filtered-visible');
                    tr[i].classList.add('filtered-hidden');
                    tr[i].style.display = "none";
                }
            }

            // Reiniciar paginación basada en los resultados filtrados
            currentPage = 1;
            displayTable(currentPage);
        }

        // --- Lógica de Paginación ---
        let currentPage = 1;
        const rowsPerPage = 5;

        function displayTable(page) {
            const table = document.getElementById("usersTable");
            const rows = table.querySelectorAll("tbody tr"); // Solo filas del cuerpo

            // Filtramos solo las filas que pasaron la búsqueda (o todas si no hay búsqueda)
            // Si no se ha hecho búsqueda, todas se consideran visibles
            let visibleRows = [];
            rows.forEach(row => {
                if (!row.classList.contains('filtered-hidden')) {
                    visibleRows.push(row);
                } else {
                    row.style.display = "none"; // Asegurar que sigan ocultas
                }
            });

            const totalRows = visibleRows.length;
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;

            // Ocultamos todas y mostramos solo el rango de la página actual
            visibleRows.forEach((row, index) => {
                if (index >= start && index < end) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            renderPagination(totalRows);
        }

        function renderPagination(totalRows) {
            const pagination = document.getElementById("pagination");
            const pageCount = Math.ceil(totalRows / rowsPerPage);
            pagination.innerHTML = "";

            if (pageCount <= 1) return; // No mostrar si solo hay una página

            // Botón "Anterior"
            if (currentPage > 1) {
                const prevButton = document.createElement("button");
                prevButton.textContent = "Anterior";
                prevButton.onclick = () => {
                    currentPage--;
                    displayTable(currentPage);
                };
                pagination.appendChild(prevButton);
            }

            // Botones de página (Limitado a 5 para no saturar)
            let startPage = Math.max(1, currentPage - 2);
            let endPage = Math.min(pageCount, currentPage + 2);

            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement("button");
                pageButton.textContent = i;
                if (i === currentPage) {
                    pageButton.classList.add("active");
                }
                pageButton.onclick = () => {
                    currentPage = i;
                    displayTable(currentPage);
                };
                pagination.appendChild(pageButton);
            }

            // Botón "Siguiente"
            if (currentPage < pageCount) {
                const nextButton = document.createElement("button");
                nextButton.textContent = "Siguiente";
                nextButton.onclick = () => {
                    currentPage++;
                    displayTable(currentPage);
                };
                pagination.appendChild(nextButton);
            }
        }

        // Inicialización
        displayTable(currentPage);
    </script>
</body>

</html>
