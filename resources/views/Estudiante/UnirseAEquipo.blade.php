<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Unirse a equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../styles/join-team.css">

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


    <section class="join-layout">

        <!-- LADO IZQUIERDO -->
        <div class="banner">
            <h1>ÚNETE A UN EQUIPO</h1>
        </div>

        <!-- LADO DERECHO -->
        <div class="content">

            <div class="card">

                <h2 id="title">Selecciona un equipo público, privado o crea equipo</h2>
                <p id="subtitle">
                    Explora equipos por categoría o cambia a "Equipo privado" si tienes un código.
                </p>

                <div class="tabs">
                    <button id="btnPublic" class="active">Equipo público</button>
                    <button id="btnPrivate">Equipo privado</button>
                    <button id="btnCreate">Crear equipo</button>
                </div>

                <!-- PUBLICO -->
                <div id="publicSection">

                    <h3>Buscar equipos públicos</h3>

                    <div class="filters">
                        <input placeholder="Ej: IA, Frontend, Ciberseguridad">
                        <select>
                            <option>Todas</option>
                            <option>Frontend</option>
                            <option>IA</option>
                            <option>Ciberseguridad</option>
                        </select>
                        <select>
                            <option>Cualquier nivel</option>
                            <option>Principiante</option>
                            <option>Intermedio</option>
                            <option>Avanzado</option>
                        </select>
                    </div>

                    <div class="teams">

                        <div class="team-card">
                            <h4>DataWizards ITO</h4>
                            <span class="tag blue">IA / Datos</span>
                            <span class="tag green">Intermedio</span>
                            <p>3/5 integrantes · Mentoría disponible</p>
                            <button>Solicitar unirse</button>
                        </div>

                        <div class="team-card">
                            <h4>Frontend Oaxaca</h4>
                            <span class="tag violet">Desarrollo Web</span>
                            <span class="tag orange">Principiante</span>
                            <p>4/5 integrantes · React, Tailwind</p>
                            <button>Solicitar unirse</button>
                        </div>

                        <div class="team-card">
                            <h4>SecOps Tigers</h4>
                            <span class="tag red">Ciberseguridad</span>
                            <span class="tag dark">Avanzado</span>
                            <p>2/4 integrantes · Red Team</p>
                            <button>Solicitar unirse</button>
                        </div>

                    </div>
                </div>

                <!-- PRIVADO -->
                <div id="privateSection" class="hidden">

                    <h2>Equipo privado</h2>
                    <p>Únete con tu código de invitación</p>

                    <div class="form">
                        <input placeholder="Ej: ITO-AX45-TEAM">
                        <input placeholder="Ej: 2019SC0123">
                    </div>

                    <div class="buttons">
                        <button class="dark">Unirme al equipo</button>
                        <button class="outline" id="volverPublico">Volver a inicio</button>
                    </div>

                    <small>Tu solicitud será validada por el líder del equipo.</small>

                </div>

            </div>

        </div>

    </section>

    

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

    <script>
        const btnPublic = document.getElementById("btnPublic");
        const btnPrivate = document.getElementById("btnPrivate");
        const btnCreate = document.getElementById("btnCreate");

        const publicSection = document.getElementById("publicSection");
        const privateSection = document.getElementById("privateSection");
        const volverPublico = document.getElementById("volverPublico");

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

        btnCreate.onclick = () => {
            window.location.href = "/crear-equipo";
        };

        volverPublico.onclick = () => btnPublic.click();
    </script>

</body>

</html>
