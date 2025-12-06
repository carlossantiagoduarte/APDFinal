<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CodeVision</title>
    <link rel="stylesheet" href="styles/dashboard.css">
    <link rel="icon" type="../image/png" href="images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Jomolhari&family=Kadwa:wght@400;700&display=swap"
        rel="stylesheet">


    <!-- SCRIPT DROPDOWN -->
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



    <!-- HERO -->
    <section class="hero">
        <h2 class="hero-title">¡Bienvenido!</h2>
    </section>

    <!-- BUSCADOR -->
    <section class="search-section">
        <div class="search-box">
            <input type="text" placeholder="Buscar Eventos, Categorías o Tecnologías..." />
            <button class="btn-search">Buscar</button>
        </div>

        <div class="filters">
            <select>
                <option>Todas las categorías</option>
            </select>
            <select>
                <option>Cualquier fecha</option>
            </select>
            <select>
                <option>Todos los lugares</option>
            </select>
            <!-- Botones de administración -->
            <div class="new-event">
                <button class="btn-search">Crear evento</button>
            </div>
        </div>
    </section>

    <!-- EVENTOS -->
    <section class="events">
        <div class="events-header">
            <h2>Eventos y concursos de tecnología</h2>
            <a href="#" class="view-all">Ver todos los eventos →</a>
        </div>

        <div class="events-grid">

            <!-- Card 1 -->
            <div class="event-card">
                <a href="evento.html" class="card-link"></a>
                <img src="../images/evento1.jpeg" class="event-img" />
                <div class="event-info">
                    <p class="event-date">📅 15 Oct, 2023 - 20:00 hrs</p>
                    <h3 class="event-title">InnovaTecNM</h3>
                    <p class="event-description">
                        Programa de innovación tecnológica, emprendimiento y desarrollo de proyectos creativos...
                    </p>
                    <p class="event-location">📍 ITO Campus Central</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="event-card">
                <a href="evento.html" class="card-link"></a>
                <img src="../images/evento2.jpeg" class="event-img" />
                <div class="event-info">
                    <p class="event-date">📅 22 Oct, 2023 - 09:00 hrs</p>
                    <h3 class="event-title">HackaTec</h3>
                    <p class="event-description">
                        Certamen donde los participantes resuelven desafíos tecnológicos...
                    </p>
                    <p class="event-location">📍 Plaza de la Tecnología</p>

                </div>
            </div>

            <!-- Card 3 -->
            <div class="event-card">
                <a href="evento.html" class="card-link"></a>
                <img src="../images/evento3.jpeg" class="event-img" />
                <div class="event-info">
                    <p class="event-date">📅 5 Nov, 2023 - 16:00 hrs</p>
                    <h3 class="event-title">Oaxaca Emprende</h3>
                    <p class="event-description">
                        Evento que reúne proyectos innovadores de diversas universidades...
                    </p>
                    <p class="event-location">📍 Auditorio Principal</p>
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

</body>

</html>
