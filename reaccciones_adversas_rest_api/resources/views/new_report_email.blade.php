<!DOCTYPE html>
<html>
    <head>
        <title>Nuevo reporte</title>
    </head>
    <body>
        <h1>Nuevo reporte</h1>
        <ul>
            <li>Usuario reportante: {{$report->usuario->name}}</li>
            <li>Numero de documento de paciente: {{ $report->num_id_paciente }}</li>
            <li>Tipo de documento de paciente: {{ $report->tipo_id_paciente }}</li>
            {{--*/ $date = new \DateTime($report->created_at) /*--}}
            <li>Reporte creado a las: {{ $date->format('d/m/Y') }}</li>
            <li>Diagnostico principal: {{ $report->informacion_eas->first()->diagnostico->descripcion }}</li>
        </ul>
        <ul>
            <li> Medicamentos
            @foreach ($report->informacion_medicamentos as $imed)
                <ul>
                    <li>Nombre generico medicamento: {{ $imed->medicamento->medNombreGenerico }}  Forma farmaceutica medicamento: {{ $imed->medicamento->medFormaFarmaceutica }}</li>
                    @if ($imed->dosis)
                        <li>Dosis: {{ $imed->dosis }}</li>
                    @endif
                    @if ($imed->unidad_de_medida)
                        <li>Unidad de medida: {{ $imed->unidad_de_medida }}</li>
                    @endif
                    @if ($imed->id_via_de_administracion)
                        <li>Via de administracion: {{ $imed->via_de_administracion->nombre }}</li>
                    @endif
                    @if ($imed->fecha_inicio_medicamento)
                        {{--*/ $date = new \DateTime($imed->fecha_inicio_medicamento) /*--}}
                        <li>Fecha de inicio medicamento: {{ $date->format('d/m/Y') }}</li>
                    @endif
                    @if ($imed->fecha_finalizacion_medicamento)
                        {{--*/ $date = new \DateTime($imed->fecha_finalizacion_medicamento) /*--}}
                        <li>Fecha finalizacion medicamento: {{ $date->format('d/m/Y') }}</li>
                    @endif
                    @if ($imed->id_mecanismo_causa_ra)
                        <li>Mecanismo de causa: {{ $imed->mecanismo_causa_ra->nombre }}</li>
                    @endif
                </ul>
            @endforeach
            </li>
        </ul>
    </body>
</html>