<!DOCTYPE html>
<html>
<head>
    <title>Constancia de Participación</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            text-align: center;
            padding: 40px;
            border: 10px solid #111; /* Marco exterior */
            position: relative; /* Necesario para posicionar el borde interno */
        }
        .inner-border {
            border: 2px solid #666; /* Marco interior */
            padding: 30px;
            height: 90%;
            /* Utilizamos padding en el body para el marco, ajustamos el tamaño */
        }
        h1 {
            font-size: 40px;
            text-transform: uppercase;
            margin-bottom: 10px;
            color: #1a1a1a;
        }
        h2 {
            font-size: 20px;
            font-weight: normal;
            margin-top: 0;
            color: #555;
        }
        .name {
            font-size: 35px;
            font-weight: bold;
            margin: 30px 0;
            border-bottom: 1px solid #333;
            display: inline-block;
            padding: 0 20px;
            color: #000;
        }
        .text {
            font-size: 18px;
            line-height: 1.6;
            color: #333;
            margin: 20px 50px;
        }
        .evaluation-details {
            width: 100%;
            margin-top: 50px;
            display: table; /* Usamos tabla para la distribución */
            table-layout: fixed;
            border-collapse: collapse;
        }
        .evaluation-details > div {
            display: table-cell;
            width: 50%;
            padding: 0 10px;
        }
        .signature-line {
            border-top: 1px solid #333;
            width: 80%;
            margin: 10px auto 0 auto;
            padding-top: 5px;
            font-size: 14px;
        }
        .grade {
            font-size: 30px;
            font-weight: bold;
            color: #007bff;
            border: 3px solid #007bff;
            border-radius: 50%;
            width: 80px;
            height: 80px;
            line-height: 74px; /* Ajuste para centrar verticalmente */
            margin: 10px auto 20px auto;
        }
    </style>
</head>
<body>

    <div class="inner-border">
        
        <h2>Instituto Tecnológico de Oaxaca</h2>
        
        <h1>CONSTANCIA DE PARTICIPACIÓN</h1>
        
        <p class="text">Se otorga la presente constancia a:</p>
        
        <div class="name">
            {{ $user->name }} {{ $user->lastname }}
        </div>
        
        <p class="text">
            Por su destacada participación como integrante del equipo <strong>"{{ $participacion->name }}"</strong> 
            en el evento tecnológico <strong>"{{ $event->title }}"</strong>.
        </p>

        <div class="evaluation-details">
            <div style="text-align: center;">
                @if($averageScore != 'N/A')
                    <p style="font-size: 16px; margin-bottom: 5px;">Calificación Final del Jurado:</p>
                    <div class="grade">
                        {{ $averageScore }}
                    </div>
                @else
                    <p style="font-size: 16px; margin-top: 40px; color: #cc3300;">Evaluación Pendiente</p>
                @endif
                
                <div style="margin-top: 40px;">
                    <div class="signature-line">
                        <strong>{{ $event->organizer }}</strong><br>
                        Comité Organizador
                    </div>
                </div>
            </div>

            <div style="text-align: left; padding-left: 20px;">
                <p style="font-size: 16px; font-weight: bold; margin-bottom: 10px;">Jurado Evaluador:</p>
                @if($evaluations->isNotEmpty())
                    @foreach($evaluations as $eval)
                        <p style="margin: 0; font-size: 14px;">- {{ $eval->judge->name }} (Nota: {{ $eval->score }})</p>
                    @endforeach
                @else
                     <p style="color: #999; font-size: 14px;">El jurado aún no ha emitido su veredicto final.</p>
                @endif
            </div>
        </div>

        <div style="margin-top: 40px;">
            <p class="date">
                Expedida en {{ $event->location }} el {{ date('d') }} de {{ date('F') }} del {{ date('Y') }}.
            </p>
        </div>

    </div>

</body>
</html>