<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Event Information | CodeVision</title>
    <link rel="stylesheet" href="../styles/envio.css">
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

    <!-- CONTENIDO -->
    <main class="container">

        <h1 id="evento">Entrega del proyecto</h1>
        <p class="team-name">Equipo: <span id="equipo">Los Devs Legendarios</span></p>

        <div class="box">

            <div id="subida">

                <label class="file-label">
                    Seleccionar carpeta del proyecto
                    <input type="file" id="archivo" webkitdirectory directory multiple onchange="mostrarCarpeta()">
                </label>

                <p id="preview" class="preview-text">Ninguna carpeta seleccionada</p>


                <button class="btn enviar" onclick="enviar()">Enviar proyecto</button>
            </div>

            <div id="entregado" class="hidden">

                <h3>Proyecto enviado ✅</h3>
                <p id="nombreArchivo"></p>

                <div class="actions">
                    <button class="btn editar" onclick="editar()">Editar</button>
                    <button class="btn eliminar" onclick="eliminar()">Eliminar</button>
                    <button class="btn salir" onclick="salir()">Salir</button>

                </div>

            </div>

        </div>

    </main>

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

    <!-- SCRIPT -->
    <script>
        const archivo = document.getElementById("archivo");
        const subida = document.getElementById("subida");
        const entregado = document.getElementById("entregado");
        const nombreArchivo = document.getElementById("nombreArchivo");

        const ESTADO = "proyectoEnviado";
        const NOMBRE = "nombreCarpeta";

        window.onload = () => {

            if (localStorage.getItem(ESTADO) === "true") {
                const carpeta = localStorage.getItem(NOMBRE);

                subida.classList.add("hidden");
                entregado.classList.remove("hidden");
                nombreArchivo.innerText = "Carpeta enviada: " + carpeta;
            }
        };

        function enviar() {

            if (archivo.files.length === 0) {
                alert("Debes seleccionar la carpeta del proyecto");
                return;
            }

            let carpeta = archivo.files[0].webkitRelativePath.split('/')[0];

            // GUARDAR ESTADO
            localStorage.setItem(ESTADO, "true");
            localStorage.setItem(NOMBRE, carpeta);
            nombreArchivo.innerText = "Carpeta enviada: " + carpeta;

            subida.classList.add("hidden");
            entregado.classList.remove("hidden");
        }

        function editar() {
            subida.classList.remove("hidden");
            entregado.classList.add("hidden");
        }

        function eliminar() {
            localStorage.removeItem(ESTADO);
            localStorage.removeItem(NOMBRE);
            archivo.value = "";
            subida.classList.remove("hidden");
            entregado.classList.add("hidden");
            document.getElementById("preview").innerText = "Ninguna carpeta seleccionada";
                return;
        }

        function salir() {
            window.location.href = "eventos.html"; // CAMBIA si tu página de eventos tiene otro nombre
        }

        function mostrarCarpeta() {

            if (archivo.files.length === 0) {
                document.getElementById("preview").innerText = "Ninguna carpeta seleccionada";
                return;
            }

            let carpeta = archivo.files[0].webkitRelativePath.split('/')[0];
            document.getElementById("preview").innerText = "Carpeta seleccionada: " + carpeta;
        }
    </script>

</body>

</html>