<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Information | CodeVision</title>
    <link rel="stylesheet" href="../styles/calificar.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
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

    <div class="container">

        <h2>Evaluación del equipo</h2>
        <h2>HackaTec</h2>

        <!-- DATOS DEL EQUIPO -->
        <div class="card">
            <p><strong>Equipo:</strong> Nombre del Equipo</p>
            <p><strong>Número de integrantes:</strong> 5</p>

            <h4>Integrantes</h4>
            <ul class="members">
                <li>Juan Pérez</li>
                <li>María López</li>
                <li>Carlos Díaz</li>
                <li>Andrea Torres</li>
                <li>Luis Hernández</li>
            </ul>
        </div>

        <!-- ARCHIVO -->
        <div class="card">
            <p><strong>Archivo entregado:</strong></p>

            <a id="downloadBtn" href="#" class="btn download" download>
                Descargar archivo del equipo
            </a>
        </div>

        <!-- CALIFICACIÓN -->
        <div class="card">
            <label>Calificación</label>

            <input type="number" id="score" disabled min="0" max="100" placeholder="Ej. 85">

            <div class="buttons">
                <button id="editBtn" class="btn edit" disabled>Calificar</button>
                <button id="saveBtn" class="btn save" disabled>Guardar</button>
                <button class="btn cancel" onclick="window.history.back()">Cancelar</button>
            </div>
        </div>

    </div>

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
const downloadBtn = document.getElementById("downloadBtn");
const editBtn = document.getElementById("editBtn");
const saveBtn = document.getElementById("saveBtn");
const score = document.getElementById("score");

// SIMULA SI YA EXISTÍA CALIFICACIÓN (CAMBIA A true SI YA TIENE)
let alreadyScored = false;

if (alreadyScored) {
    editBtn.disabled = false;
    score.value = 85;
}

downloadBtn.addEventListener("click", () => {
    editBtn.disabled = false;
    downloadBtn.innerHTML = "✅ Archivo descargado";
    downloadBtn.classList.add("done");
});

editBtn.addEventListener("click", () => {
    score.disabled = false;
    saveBtn.disabled = false;
    score.focus();
});

saveBtn.addEventListener("click", () => {
    if (!score.value) {
        alert("Ingresa una calificación.");
        return;
    }
    alert("Calificación guardada correctamente ✅");
    score.disabled = true;
    saveBtn.disabled = true;
});
</script>

</body>

</html>