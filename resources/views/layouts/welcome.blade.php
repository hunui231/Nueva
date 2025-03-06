@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'dashboard' @endphp
@endsection

@section('content')

@php
    use App\Models\Indicador;
    $indicadores = Indicador::all(); 
@endphp

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

 <h1 class="maspudo">Bienvenido, has Iniciado Sesión!</h1>
<h2 class="maspudo">CONPLASA INYECCION</h2>
<style>
       * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        overflow-x: hidden; /* Evitar scroll horizontal */
    }
        .dashboard {
            background: white;
            padding: 20px;
            width: 300px;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            border-bottom: 2px solid red;
            margin-bottom: 15px;
        }
        .indicator {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }
        .circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            color: white;
            margin-right: 10px;
        }
        .gray { background: #444; }
        .green { background: green; }
        .red { background: red; }
        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            margin-top: 5px;
        }
        .chart-container canvas {
            width: 100px;
            height: 100px;
        }
        .chart-text {
            position: absolute;
            font-size: 18px;
            font-weight: bold;
        }
        .nav-buttons {
            text-align: right;
            margin-top: 5px;
        }
        .nav-buttons a {
            text-decoration: none;
            font-size: 30px;
            font-weight: bold;
            color: rgb(255, 16, 16);
            padding: 1px;
        }
    .chart-row {
        justify-content: center; 
        display: flex;
        gap: 20px; 
        flex-wrap: wrap;
        width: 100%;
        max-width: 700px;
        margin: 0 auto;

    }
  
    .maspudo{
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        color: white;
        background-color: #4A90E2; 
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        transition: transform 0.3s; 
    }
    .maspudo2{
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        color: white;
        background-color:rgb(243, 14, 14); 
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        transition: transform 0.3s; 
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="chart-row">
    @php
        // Mapear los indicadores por su nombre (seccion)
        $indicadoresMap = [];
        foreach ($indicadores as $indicador) {
            $indicadoresMap[$indicador->seccion] = $indicador;
        }
    @endphp
    <div class="dashboard">
        <div class="title-cpa">Administración</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['Administración']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['Administración']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['Administración']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart1"></canvas>
            <div class="chart-text">{{ $indicadoresMap['Administración']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('administracion.index')
            <a href="{{ route('administracion.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>

    <!-- Indicador 2: Ventas -->
    <div class="dashboard">
        <div class="title-cpa">Ventas</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['Ventas']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['Ventas']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['Ventas']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart2"></canvas>
            <div class="chart-text">{{ $indicadoresMap['Ventas']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('Ventas.index')
                <a href="{{ route('Ventas.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>
</div>
<br>

<div class="chart-row">
    <!-- Indicador 3: Producción -->
    <div class="dashboard">
        <div class="title-cpa">Producción</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['Producción']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['Producción']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['Producción']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart3"></canvas>
            <div class="chart-text">{{ $indicadoresMap['Producción']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('produccion.index')
                <a href="{{ route('produccion.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>

    <!-- Indicador 4: RRHH -->
    <div class="dashboard">
        <div class="title-cpa">RRHH</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['RRHH']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['RRHH']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['RRHH']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart4"></canvas>
            <div class="chart-text">{{ $indicadoresMap['RRHH']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('rh.index')
                <a href="{{ route('rh.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>
</div>
<br>

<script>
    function renderChart(canvasId, percentage) {
        const ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [percentage, 100 - percentage],
                    backgroundColor: ['green', 'white'],
                }]
            },
            options: {
                responsive: false,
                cutout: '70%',
                plugins: { legend: { display: false } }
            }
        });
    }

    renderChart('chart1', {{ $indicadoresMap['Administración']->porcentaje ?? 0 }});
    renderChart('chart2', {{ $indicadoresMap['Ventas']->porcentaje ?? 0 }});
    renderChart('chart3', {{ $indicadoresMap['Producción']->porcentaje ?? 0 }});
    renderChart('chart4', {{ $indicadoresMap['RRHH']->porcentaje ?? 0 }});
</script>

<br>
<h1 class="maspudo2">Grupo industrial Conplasa</h1>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="chart-row">
    <!-- Indicador 5: Operacion MM -->
    <div class="dashboard">
        <div class="title-gic">Operacion MM</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['OperacionMM']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['OperacionMM']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['OperacionMM']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart5"></canvas>
            <div class="chart-text">{{ $indicadoresMap['OperacionMM']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('taller.index')
                <a href="{{ route('taller.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>

    <!-- Indicador 6: Administracion GIC -->
    <div class="dashboard">
        <div class="title-gic">Administracion GIC</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['AdministracionGIC']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['AdministracionGIC']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['AdministracionGIC']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart6"></canvas>
            <div class="chart-text">{{ $indicadoresMap['AdministracionGIC']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('administraciongic.index')
                <a href="{{ route('administraciongic.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>
</div>
<br>

<div class="chart-row">
    <!-- Indicador 7: Produccion Log -->
    <div class="dashboard">
        <div class="title-gic">Produccion Log</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['ProduccionLog']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['ProduccionLog']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['ProduccionLog']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart7"></canvas>
            <div class="chart-text">{{ $indicadoresMap['ProduccionLog']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('logistica.index')
                <a href="{{ route('logistica.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>

    <!-- Indicador 8: RRHH GIC -->
    <div class="dashboard">
        <div class="title-gic">RRHH GIC</div>
        <div class="indicator"><div class="circle gray">{{ $indicadoresMap['RRHHGIC']->total ?? 0 }}</div> INDICADORES</div>
        <div class="indicator"><div class="circle green">{{ $indicadoresMap['RRHHGIC']->cumplen ?? 0 }}</div> CUMPLEN</div>
        <div class="indicator"><div class="circle red">{{ $indicadoresMap['RRHHGIC']->no_cumplen ?? 0 }}</div> NO CUMPLEN</div>
        <div class="chart-container">
            <canvas id="chart8"></canvas>
            <div class="chart-text">{{ $indicadoresMap['RRHHGIC']->porcentaje ?? 0 }}%</div>
        </div>
        <div class="nav-buttons">
            @can('rh.index')
                <a href="{{ route('rh.index') }}"><i class="fas fa-angle-double-right"></i></a>
            @endcan
        </div>
    </div>
</div>
<script>
    renderChart('chart5', {{ $indicadoresMap['OperacionMM']->porcentaje ?? 0 }});
    renderChart('chart6', {{ $indicadoresMap['AdministracionGIC']->porcentaje ?? 0 }});
    renderChart('chart7', {{ $indicadoresMap['ProduccionLog']->porcentaje ?? 0 }});
    renderChart('chart8', {{ $indicadoresMap['RRHHGIC']->porcentaje ?? 0 }});
</script>
 <style>
  
    #calendar {
        max-width: 100%;
        margin: 20px auto;
    }
    @media (max-width: 768px) {
        h2, h3 {
            font-size: 1.5rem;
        }
    }
</style>
<div>

<div>
<div class="container-fluid">
<div class="row mt-4">
    <div class="col-12">
        <h3 class="maspudo">Calendario</h3>
        <div id="calendar" style="max-width: 800px; max-height: 500px;"></div>
    </div>
</div>
</div>                                           
 <script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'es', 
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: [
            {
                title: 'Cumpleaños',
                start: '2025-02-26'
            },
            {
                title: 'Entregas',
                start: '2024-10-09',
                end: '2024-10-12'
            }
        ]
    });
    calendar.render();
});


const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'bar',
    data: {
        labels: ['Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre'],
        datasets: [
            {
                label: 'Scrap',
                data: [1200, 1900, 3000, 5000, 2000],
                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Producción',
                data: [8000, 9000, 8500, 9500, 9200],
                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            },
            {
                label: 'Eficiencia',
                data: [85, 88, 90, 87, 89],
                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1
            },
            {
                label: 'Calidad',
                data: [95, 94, 96, 97, 95],
                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            },
            {
                label: 'Costo de Producción',
                data: [5000, 5500, 5300, 5800, 5700],
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});


const incomeCtx = document.getElementById('incomeChart').getContext('2d');
const incomeChart = new Chart(incomeCtx, {
    type: 'line',
    data: {
        labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo'],
        datasets: [{
            label: 'Produccion',
            data: [1000, 1500, 2500, 4000, 3000],
            fill: false,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true
    }
});
</script>


<br>
<br>
 <style>
.container {
    width: 90%;
    max-width: 1000px;
    margin: 0 auto;
    text-align: center;
    border: 2px solid #000;
    padding: 20px;
    background-color: #e8f3ff;
    box-sizing: border-box;
}
.title-table {
    font-size: 24px;
    background-color: #00376b;
    color: white;
    padding: 10px;
    margin-bottom: 20px;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid black;
    padding: 10px;
    text-align: center;
    font-size: 14px;
}
th {
    background-color: #00529b;
    color: white;
}
tr:nth-child(even) {
    background-color: #f2f2f2;
}
.sign {
    margin-top: 20px;
    text-align: left;
}
.footer {
    text-align: right;
    margin-top: 20px;
}

@media screen and (max-width: 600px) {
    .container {
        width: 100%;
        padding: 10px;
    }
    .title-table {
        font-size: 20px;
    }
    th, td {
        font-size: 12px;
        padding: 8px;
    }
    h2 {
        font-size: 18px;
    }
    .footer {
        font-size: 12px;
    }
}
</style>

<div class="container">
<div class="title-table">SISTEMA DE GESTIÓN DE CALIDAD</div>
<h2>OBJETIVOS DE CALIDAD</h2>
<table>
<thead>
    <tr>
        <th></th>
        <th>Objetivo</th>
        <th>Área</th>
        <th>Detalle</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>1</td>
        <td>Reducir el Porcentaje de Defectivo</td>
        <td>Plásticos</td>
        <td>Scrap Plásticos &lt; 2.5%</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Metal Mecánica</td>
        <td>
            Scrap Donaldson &lt; 2%<br>
            Scrap Taller &lt; 3%<br>
            Scrap Forjas &lt; 5%
        </td>
    </tr>
    <tr>
        <td>2</td>
        <td>Cumplimiento al Plan de Producción</td>
        <td>Plásticos</td>
        <td>&gt; 90%</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td>Metal Mecánica</td>
        <td>
            Ensamble Donaldson &gt; 95%<br>
            Taller &gt; 95%<br>
            Maquinados en serie Forjas &gt; 98%
        </td>
    </tr>
    <tr>
        <td>3</td>
        <td>Entregas a Tiempo al Cliente</td>
        <td>Plásticos, Metal Mecánica</td>
        <td>100%</td>
    </tr>
    <tr>
        <td>4</td>
        <td>Evitar Reclamos de Cliente</td>
        <td>Plásticos, Metal Mecánica</td>
        <td>0</td>
    </tr>
</tbody>
</table>

<div class="sign">
<strong>CONPLASA</strong><br>
Ing. Ricardo Daniel Solís Quiróz
</div>

<div class="footer">
Rev. 06<br>
Fecha: 18.Enero.24
</div>
</div>
 

@endsection

 <style>
    .container {
        width: 90%;
        max-width: 800px;
        margin: 0 auto;
        text-align: center;
        border: 2px solid #00376b; /* Cambiado a color de título */
        border-radius: 10px; /* Bordes redondeados */
        padding: 20px;
        background-color: #e8f3ff;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); /* Sombra sutil */
        box-sizing: border-box;
    }
    .title-Gic {
        font-size: 28px; /* Aumentar el tamaño de la fuente */
        background-color:rgb(236, 30, 30);
        color: white;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px; /* Bordes redondeados */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra sutil */
    }
    .title-cpa {
        font-size: 28px; /* Aumentar el tamaño de la fuente */
        background-color:rgb(42, 110, 238);
        color: white;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px; /* Bordes redondeados */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Sombra sutil */
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px; /* Espacio entre la tabla y el título */
    }
    th, td {
        border: 1px solid #ddd; /* Cambiar a un color más claro */
        padding: 10px;
        text-align: center;
        font-size: 16px; /* Aumentar el tamaño de la fuente */
    }
    th {
        background-color: #00529b;
        color: white;
        font-weight: bold; /* Negrita en los encabezados */
    }
    tr:nth-child(even) {
        background-color: #f2f2f2;
    }
    .sign {
        margin-top: 30px; /* Aumentar margen superior */
        text-align: left;
        font-weight: bold; /* Negrita */
    }
    .footer {
        text-align: right;
        margin-top: 30px; /* Aumentar margen superior */
        color: #555; /* Color más suave para el pie de página */
    }
    </style>
    