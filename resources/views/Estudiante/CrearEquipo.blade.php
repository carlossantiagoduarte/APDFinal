<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Unirse a equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/crear-team.css">

    <link rel="icon" type="image/png" href="../images/logo.png">

    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@700&family=Inter:wght@300;400;600&display=swap"
        rel="stylesheet">
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

    <!-- NAVBAR -->
    <nav class="navbar">

        <div class="navbar-left">
            <!-- LOGO -->
            <img src="../images/logo.png" class="logo">
            <span class="site-title">CodeVision</span>
        </div>

        <div class="user-menu-container">

            <!-- NOMBRE DEL USUARIO -->
            <div id="user-toggle" class="user-name">
                Andrés López

                <!-- FLECHITA -->
                <svg class="arrow" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 12 15 18 9" />
                </svg>
            </div>

            <!-- MENU -->
            <div id="user-menu" class="dropdown">

                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 9.5L12 3l9 6.5V21H3z" />
                    </svg>
                    Inicio
                </a>

                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <circle cx="12" cy="7" r="4" />
                        <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                    </svg>
                    Perfil
                </a>

                <a href="#">
                    <svg viewBox="0 0 24 24" fill="none" stroke="#111" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                        <polyline points="16 17 21 12 16 7" />
                        <line x1="21" y1="12" x2="9" y2="12" />
                    </svg>
                    Cerrar sesión
                </a>

            </div>

        </div>

    </nav>


<!-- SECCIÓN 1 — FORMULARIO PRINCIPAL -->
    <section id="step1" class="step active">

        <div class="header-banner">Registra tu Equipo Aquí!</div>

        <div class="form-container">

            <h1 class="title">Formulario de Inscripción</h1>

            <div class="form-grid">

                <div class="event-card">
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475" alt="event">
                    <h3>Título del Evento</h3>
                    <p>Descripción del evento</p>

                    <div class="details">
                        <p>📅 Fecha y hora</p>
                        <p>📍 Ubicación</p>
                        <p>👥 Número de personas</p>
                    </div>
                </div>

                <form id="form1">

                    <label>Nombre del Equipo</label>
                    <input type="text" required placeholder="Nombre de tu equipo">

                    <label>Nombre Completo</label>
                    <input type="text" required placeholder="Tu nombre completo">

                    <label>Correo Electrónico</label>
                    <input type="email" required placeholder="example@gmail.com">

                    <label>Carrera</label>
                    <input type="text" required placeholder="Carrera a la que perteneces">

                    <label>Semestre</label>
                    <input type="text" required placeholder="Semestre que cursas">

                    <label>Experiencia (Opcional)</label>
                    <textarea placeholder="Experiencia con la que cuentas"></textarea>

                    <label>Número de integrantes del equipo</label>
                    <input type="number" id="totalMembers" min="2" value="5" required>

                    <label>Equipo privado o público</label>
                    <select>
                        <option>Privado</option>
                        <option>Público</option>
                    </select>

                    
                    <button type="button" class="btn-secondary" onclick="goToStep1()">Cancelar</button>
                    <button type="button" class="btn-primary" onclick="goToStep2()">Siguiente</button>
                </form>

            </div>
        </div>

    </section>

    <!-- SECCIÓN 2 — MIEMBROS DINÁMICOS -->
    <section id="step2" class="step">

        <div class="header-banner">Registra tu Equipo Aquí!</div>

        <div class="members-container">

            <h2>Miembros del Equipo</h2>

            <form id="membersForm">
                <div id="membersDynamic"></div>

                <label>Requisitos de los participantes</label>
                <textarea placeholder="Escribe los requisitos necesarios..."></textarea>

                <div class="buttons-row">
                    <button type="button" class="btn-secondary" onclick="goToStep1()">Cancelar</button>
                    <button type="button" class="btn-primary" onclick="goToStep3()">Siguiente</button>
                </div>
            </form>

        </div>

    </section>

    <!-- SECCIÓN 3 — CÓDIGO GENERADO -->
    <section id="step3" class="step">

        <div class="final-container">
            <h1>¡Equipo creado!</h1>
            <p>Comparte tu código de invitación</p>

            <div class="invite-box">
                <h3>CÓDIGO DE INVITACIÓN</h3>
                <div id="inviteCode">ITO-XXXX-TEAM</div>

                <button class="btn-copy" onclick="navigator.clipboard.writeText(inviteCode.innerText)">
                    Copiar código
                </button>
            </div>

            <button class="btn-primary" onclick="goToStep1()">Cerrar</button>
        </div>

    </section>

    <!-- ========================== -->
    <!--      SCRIPT LÓGICA JS      -->
    <!-- ========================== -->
    <script>
        function goToStep1() {
            showStep(1);
        }

        function goToStep2() {
            const total = parseInt(document.getElementById("totalMembers").value);

            if (total < 2) return alert("Debe haber al menos 2 integrantes.");

            generateMembers(total - 1);
            showStep(2);
        }

        function goToStep3() {
            generateInviteCode();
            showStep(3);
        }

        function showStep(num) {
            document.querySelectorAll(".step").forEach(s => s.classList.remove("active"));
            document.querySelector("#step" + num).classList.add("active");
        }

        function generateMembers(count) {
            const container = document.getElementById("membersDynamic");
            container.innerHTML = "";

            for (let i = 1; i <= count; i++) {
                container.innerHTML += `
                <div class="member-box">
                <h3>Miembro ${i}</h3>

                <div class="member-label">Nombre Completo</div>
                <div class="member-input"><input type="text" placeholder="Nombre completo" required></div>

                <div class="member-label">Correo Electrónico</div>
                <div class="member-input"><input type="email" placeholder="example@gmail.com" required></div>

                <div class="member-label">Carrera</div>
                <div class="member-input"><input type="text" placeholder="Ing./Lic." required></div>

                <div class="member-label">Número de contacto</div>
                <div class="member-input"><input type="tel" placeholder="Número de teléfono" required></div>
            </div>
                `;
            }
        }

        function generateInviteCode() {
            const random = Math.random().toString(36).substring(2, 6).toUpperCase();
            document.getElementById("inviteCode").innerText = `ITO-${random}-TEAM`;
        }
    </script>

        <!-- FOOTER -->
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

        <p class="footer-copy">© 2023 CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>
</body>

</html>
