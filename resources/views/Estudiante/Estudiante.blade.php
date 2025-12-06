<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Resultados del evento | CodeVision</title>
    <link rel="stylesheet" href="../styles/equipos.css">
    <link rel="icon" type="image/png" href="../images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Jomolhari&display=swap" rel="stylesheet">
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="navbar-left">
            <img src="../images/logo.png" class="logo">
            <span class="site-title">CodeVision</span>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">
                Andrés López
            </div>
        </div>
    </nav>

    <h1>Resultados del evento</h1>

    <!-- Tabla de Equipos -->
    <table id="tablaEquipos">
        <thead>
            <tr>
                <th>#</th>
                <th>Equipo</th>
                <th>Tu calificación</th>
                <th>Promedio</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <!-- Botón "Subir proyecto" alineado a la derecha y debajo de la tabla -->
    <div class="subir-box" style="text-align: right; margin-top: 20px; margin-right: 20px;">
        <input type="file" id="archivo" hidden>
        <button onclick="document.getElementById('archivo').click()">Subir proyecto</button>
        <p id="nombreArchivo"></p>
    </div>

    <div class="footer-controls">
        <div id="paginacion"></div>
        <div id="panelExtra"></div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer-grid">
            <div>
                <h3>CodeVision</h3>
                <p>Plataforma oficial del Instituto Tecnológico de Oaxaca para gestión de eventos tecnológicos.</p>
            </div>
        </div>
        <p class="footer-copy">© 2023 CodeVision - Instituto Tecnológico de Oaxaca</p>
    </footer>

    <script>
        const equipos = Array.from({ length: 32 }, (_, i) => ({
            id: i + 1,
            nombre: "Equipo " + (i + 1),
            calificaciones: [],
            miCalificacion: null
        }));

        const porPagina = 10;
        let paginaActual = 1;

        document.addEventListener("DOMContentLoaded", () => {
            renderPagina(1);
        });

        function renderPagina(pagina) {
            const tbody = document.querySelector("#tablaEquipos tbody");
            tbody.innerHTML = "";

            const inicio = (pagina - 1) * porPagina;
            const page = equipos.slice(inicio, inicio + porPagina);

            page.forEach(e => {
                const fila = document.createElement("tr");

                fila.innerHTML = `
                    <td>${e.id}</td>
                    <td>${e.nombre}</td>
                    <td>${e.miCalificacion ?? "-"}</td>
                    <td>${promedio(e.calificaciones)}</td>
                `;

                tbody.appendChild(fila);
            });

            renderPaginacion();
        }

        function renderPaginacion() {
            const pag = document.getElementById("paginacion");
            pag.innerHTML = "";
            let total = Math.ceil(equipos.length / porPagina);

            for (let i = 1; i <= total; i++) {
                pag.innerHTML += `<button onclick="renderPagina(${i})">${i}</button>`;
            }
        }

        function promedio(a) {
            if (a.length === 0) return "-";
            return (a.reduce((s, v) => s + v, 0) / a.length).toFixed(1);
        }
    </script>

</body>

</html>
