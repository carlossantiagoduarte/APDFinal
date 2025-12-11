<!DOCTYPE html>
<html>
<head>
    <title>Constancia de Participación</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            text-align: center;
            padding: 40px;
            border: 10px solid #111;
            position: relative;
        }
        .inner-border {
            border: 2px solid #666;
            padding: 30px;
            height: 900px; /* Altura fija aproximada para A4 */
        }
        h1 { font-size: 40px; text-transform: uppercase; margin-bottom: 10px; color: #1a1a1a; }
        h2 { font-size: 20px; font-weight: normal; margin-top: 0; color: #555; }
        .name {
            font-size: 35px; font-weight: bold; margin: 30px 0;
            border-bottom: 1px solid #333; display: inline-block; padding: 0 20px; color: #000;
        }
        .text { font-size: 18px; line-height: 1.6; color: #333; margin: 20px 50px; }
        .evaluation-details {
            width: 100%; margin-top: 50px;
            display: table; table-layout: fixed; border-collapse: collapse;
        }
        .col-left { display: table-cell; width: 50%; padding: 0 10px; vertical-align: top; text-align: center; }
        .col-right { display: table-cell; width: 50%; padding: 0 10px; vertical-align: top; text-align: left; }
        
        .signature-line {
            border-top: 1px solid #333; width: 80%; margin: 60px auto 0 auto;
            padding-top: 5px; font-size: 14px; font-weight: bold;
        }
        .grade {
            font-size: 30px; font-weight: bold; color: #007bff;
            border: 3px solid #007bff; border-radius: 50%;
            width: 80px; height: 80px; line-height: 74px;
            margin: 10px auto 20px auto;
        }
        .date { margin-top: 60px; font-style: italic; color: #666; }
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
            <div class="col-left">
                @if($averageScore != 'N/A')
                    <p style="font-size: 16px; margin-bottom: 5px;">Calificación Final:</p>
                    <div class="grade">{{ $averageScore }}</div>
                @else
                    <p style="font-size: 16px; margin-top: 40px; color: #cc3300;">Participación sin nota final</p>
                @endif
                
                <div class="signature-line">
                    {{ $event->organizer }}<br>
                    <span style="font-weight: normal;">Comité Organizador</span>
                </div>
            </div>

            <div class="col-right">
                <p style="font-size: 16px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #ccc;">Jurado Evaluador:</p>
                @if($evaluations->isNotEmpty())
                    @foreach($evaluations as $eval)
                        <div style="margin-bottom: 8px; font-size: 14px;">
                            <strong>Juez:</strong> {{ $eval->judge->name ?? 'Juez' }} <br>
                            <span style="color: #555;">Puntaje otorgado: {{ $eval->score }}</span>
                        </div>
                    @endforeach
                @else
                    <p style="color: #999; font-size: 14px;">Sin evaluaciones registradas.</p>
                @endif
            </div>
        </div>

        <p class="date">
            Expedida en {{ $event->location }} el {{ date('d') }} de {{ date('F') }} del {{ date('Y') }}.
        </p>
    </div>

</body>
</html>