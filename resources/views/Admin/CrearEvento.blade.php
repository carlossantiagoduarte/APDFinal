<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>New Event | CodeVision</title>
    <link rel="stylesheet" href="styles/event-register.css">
    <link rel="icon" type="image/png" href="images/logo.png">
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
            <img src="images/logo.png" class="logo">
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
        <h2 class="hero-title">¡Registra un nuevo Evento!</h2>
    </section>
    <div class="event">
    <div class="form-container">
        <h2>Información del Evento</h2>

        <form id="eventForm">
            <!-- Paso 1 -->
            <div class="step active" id="step1">
                <div class="form-row">
                    <div class="form-group">
                        <label>Nombre del Evento</label>
                        <input type="text" placeholder="Ej: Hackatec">
                    </div>
                    <div class="form-group">
                        <label>Organización o Responsable</label>
                        <input type="text" placeholder="Nombre del Organizador o Empresa">
                    </div>
                    <div class="form-group">
                        <label>Lugar</label>
                        <input type="text" placeholder="Ej: Auditorio principal ITO">
                    </div>
                </div>

                <label>Descripción</label>
                <textarea placeholder="Describe el evento, sus objetivos y a quién va dirigido..."></textarea>

                <div class="form-row">
                    <div class="form-group">
                        <label>Correo Electrónico</label>
                        <input type="email" placeholder="example@gmail.com">
                    </div>
                    <div class="form-group">
                        <label>Número de contacto</label>
                        <input type="text" placeholder="+52 951-123-4567">
                    </div>
                    <div class="form-group">
                        <label>Capacidad máxima</label>
                        <input type="number" placeholder="Número total de participantes">
                    </div>
                </div>

                <label>Requisitos de participación</label>
                <textarea placeholder="Requisitos que necesitan las personas para poder participar en dicho evento..."></textarea>

                <div class="buttons">
                    <button type="button" id="cancelBtn1">Cancelar</button>
                    <button type="button" id="nextBtn">Siguiente →</button>
                </div>
            </div>

            <!-- Paso 2 -->
            <div class="step" id="step2">
                <div class="form-row">
                    <div class="form-group">
                        <label>Fecha de Inicio</label>
                        <input type="date">
                    </div>
                    <div class="form-group">
                        <label>Fecha de Finalización</label>
                        <input type="date">
                    </div>
                    <div class="form-group">
                        <label>URL de la imagen del Evento</label>
                        <input type="url" placeholder="https://ejemplo.com/image.jpg">
                    </div>
                </div>

                <label>Documentos adjuntos</label>
                <textarea placeholder="Escribe algo..."></textarea>

                <div class="form-row">
                    <div class="form-group">
                        <label>Hora de inicio</label>
                        <input type="time">
                    </div>
                    <div class="form-group">
                        <label>Hora de finalización</label>
                        <input type="time">
                    </div>
                </div>

                <div class="buttons">
                    <button type="button" id="cancelBtn2">Cancelar</button>
                    <button type="button" id="prevBtn">← Anterior</button>
                    <button type="submit">Guardar</button>
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
        const form = document.getElementById('eventForm');
        const cancelBtn1 = document.getElementById('cancelBtn1');
        const cancelBtn2 = document.getElementById('cancelBtn2');

        // Función para ir al siguiente paso
        nextBtn.addEventListener('click', () => {
            step1.classList.remove('active');
            step2.classList.add('active');
        });

        // Función para regresar al paso anterior
        prevBtn.addEventListener('click', () => {
            step2.classList.remove('active');
            step1.classList.add('active');
        });

        // Función para cancelar y resetear el formulario
        function resetForm() {
            if (confirm("¿Seguro que quieres cancelar? Se borrará toda la información.")) {
                form.reset(); // Limpia todos los campos
                step2.classList.remove('active');
                step1.classList.add('active'); // Vuelve al paso 1
            }
        }

        cancelBtn1.addEventListener('click', resetForm);
        cancelBtn2.addEventListener('click', resetForm);

        // Evitar recargar al enviar
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            alert('Evento guardado correctamente!');
        });
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
