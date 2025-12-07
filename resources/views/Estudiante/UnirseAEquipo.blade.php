<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Unirse a equipo | CodeVision</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('styles/join-team.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Kadwa:wght@700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>

    <nav class="navbar">
        <div class="navbar-left">
            <a href="{{ route('dashboard.estudiante') }}" style="text-decoration:none; color:inherit; display:flex; align-items:center; gap:10px;">
                <img src="{{ asset('images/logo.png') }}" class="logo">
                <span class="site-title">CodeVision</span>
            </a>
        </div>
        <div class="user-menu-container">
            <div id="user-toggle" class="user-name">{{ Auth::user()->name }}</div>
        </div>
    </nav>

    <section class="join-layout">
        <div class="banner">
            <h1>ÚNETE A UN EQUIPO</h1>
        </div>

        <div class="content">
            <div class="card">
                
                @if(session('success'))
                    <div style="background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px;">
                        {{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div style="background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px;">
                        {{ $errors->first() }}
                    </div>
                @endif

                <h2 id="title">Explora equipos o usa un código</h2>
                <div class="tabs">
                    <button id="btnPublic" class="active">Equipo público</button>
                    <button id="btnPrivate">Equipo privado</button>
                    <button id="btnCreate" onclick="window.location.href='{{ route('teams.create') }}'">Crear equipo</button>
                </div>

                <div id="publicSection">
                    <h3>Equipos Públicos Disponibles</h3>
                    
                    <div class="teams" style="max-height: 400px; overflow-y: auto;">
                        @forelse($equipos as $equipo)
                            <div class="team-card" style="border:1px solid #eee; padding:15px; margin-bottom:10px; border-radius:8px;">
                                <h4>{{ $equipo->name }}</h4>
                                <span class="tag blue" style="font-size:0.8em; color:#555;">Evento: {{ $equipo->event->title }}</span>
                                <p style="font-size:0.9em;">Líder: {{ $equipo->leader_name }}</p>
                                <p style="font-size:0.8em; color:gray;">{{ $equipo->description }}</p>
                                
                                <form action="{{ route('teams.join.request') }}" method="POST" style="margin-top:10px;">
                                    @csrf
                                    <input type="hidden" name="team_id" value="{{ $equipo->id }}">
                                    <button type="submit" style="background:#333; color:white; border:none; padding:8px 12px; border-radius:4px; cursor:pointer;">
                                        Solicitar unirse
                                    </button>
                                </form>
                            </div>
                        @empty
                            <p style="text-align:center; color:#777; margin-top:20px;">No hay equipos públicos disponibles en este momento.</p>
                        @endforelse
                    </div>
                </div>

                <div id="privateSection" class="hidden" style="display: none;">
                    <h2>Equipo privado</h2>
                    <p>Únete con tu código de invitación</p>

                    <form action="{{ route('teams.joun.code') }}" method="POST" class="form">
                        @csrf
                        <input name="invite_code" placeholder="Ej: ITO-AX45-TEAM" required style="width:100%; padding:10px; margin-bottom:15px; border:1px solid #ccc; border-radius:5px;">
                        
                        <div class="buttons">
                            <button type="submit" class="dark" style="background:#000; color:#fff; padding:10px 20px; border:none; border-radius:5px; cursor:pointer;">
                                Unirme al equipo
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </section>

    <footer class="footer">
        <p class="footer-copy">© 2023 CodeVision</p>
    </footer>

    <script>
        const btnPublic = document.getElementById("btnPublic");
        const btnPrivate = document.getElementById("btnPrivate");
        const publicSection = document.getElementById("publicSection");
        const privateSection = document.getElementById("privateSection");

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
    </script>

</body>
</html>
