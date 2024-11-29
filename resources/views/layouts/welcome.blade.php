@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'dashboard' @endphp
@endsection

@section('content')
<h1 class="maspudo">Bienvenido, has Iniciado Sesión!</h1>

 <style>
    .chart-row {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        width: 100%;
        max-width: 700px;
        margin: 0 auto;
    }
    .chart-container {
        width: 48%;
        margin-bottom: 20px; 
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .chart-title {
        font-family: 'Arial', sans-serif;
        font-size: 2.5em;
        text-align: center;
        color: #fff;
        background: linear-gradient(135deg, #3498db, #ff0b0b);
        padding: 15px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        width: fit-content;
        letter-spacing: 2px;
        margin-bottom: 10px;
    }
    .indicators {
        display: flex;
        justify-content: space-between;
        width: 100%;
        margin-top: 10px;
    }
    .indicator {
        background-color: #f0f0f0;
        padding: 5px;
        margin: 5px;
        border-radius: 5px;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .indicator canvas {
        width: 50px;
        height: 50px;
    }
    .indicator-text {
        margin-right: 10px;
        text-align: left;
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

    @media (max-width: 768px) {
        .chart-container {
            width: 100%; 
        }
        .indicators {
            flex-direction: column;
            align-items: center;
        }
        .indicator {
            width: 100%;
            max-width: 300px;
        }
    }
</style>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
<div class="chart-row">

    <div class="chart-container">
        <h2 class="chart-title">Operación MM</h2>
        <canvas id="mainChart1"></canvas>
        <div class="indicators">
            <div class="indicator">
                <canvas id="miniChart1"></canvas>
                <div class="indicator-text">Falta</div>
            </div>
            <div class="indicator">
                <canvas id="miniChart2"></canvas>
                <div class="indicator-text">Bien</div>
            </div>
        </div>
    </div>

    <div class="chart-container">
        <h2 class="chart-title" onclick="window.location.href='{{ route('calidad.index') }}'">
            CALIDAD
        </h2>
        <canvas id="mainChart2"></canvas>
        <div class="indicators">
            <div class="indicator">
                <canvas id="miniChart3"></canvas>
                <div class="indicator-text">Falta</div>
            </div>
            <div class="indicator">
                <canvas id="miniChart4"></canvas>
                <div class="indicator-text">Bien</div>
            </div>
        </div>
    </div>
</div>

<div class="chart-row">
    <div class="chart-container">
        <h2 class="chart-title" onclick="window.location.href='{{ route('logistica.index') }}'">
            Logistica
        </h2>
        <canvas id="mainChart3"></canvas>
        <div class="indicators">
            <div class="indicator">
                <canvas id="miniChart5"></canvas>
                <div class="indicator-text">Falta</div>
            </div>
            <div class="indicator">
                <canvas id="miniChart6"></canvas>
                <div class="indicator-text">Bien</div>
            </div>
        </div>
    </div>
    <div class="chart-container">
        <h2 class="chart-title" onclick="window.location.href='{{ route('cnc.index') }}'">
            CNC
        </h2>
        <canvas id="mainChart4"></canvas>
        <div class="indicators">
            <div class="indicator">
                <canvas id="miniChart7"></canvas>
                <div class="indicator-text">Falta</div>
            </div>
            <div class="indicator">
                <canvas id="miniChart8"></canvas>
                <div class="indicator-text">Bien</div>
            </div>
        </div>
    </div>
</div>

 <script>
    function createMainChart(canvasId, data, colors) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Meta', 'Dato'],
                datasets: [{
                    data: data,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    }

    createMainChart('mainChart1', [20, 80], ['#F44336', '#4CAF50']);
    createMainChart('mainChart2', [30, 70], ['#F44336', '#4CAF50']);
    createMainChart('mainChart3', [50, 50], ['#F44336', '#4CAF50']);
    createMainChart('mainChart4', [60, 40], ['#F44336', '#4CAF50']);

    function createMiniChart(canvasId, data, colors) {
        var ctx = document.getElementById(canvasId).getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: data,
                    backgroundColor: colors
                }]
            },
            options: {
                responsive: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    createMiniChart('miniChart1', [20, 80], ['red', '#e6e6e6']);
    createMiniChart('miniChart2', [20, 80], ['#e6e6e6', '#66ff66']);
    createMiniChart('miniChart3', [30, 70], ['red', '#e6e6e6']);
    createMiniChart('miniChart4', [30, 70], ['#e6e6e6', '#66ff66']);
    createMiniChart('miniChart5', [50, 50], ['red', '#e6e6e6']);
    createMiniChart('miniChart6', [50, 50], ['#e6e6e6', '#66ff66']);
    createMiniChart('miniChart7', [60, 40], ['red', '#e6e6e6']);
    createMiniChart('miniChart8', [60, 40], ['#e6e6e6', '#66ff66']);
</script>

<br>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.css" rel="stylesheet" />
 <style>
    body {
        overflow-x: hidden;
    }
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

<div class="container-fluid">
<div class="row mt-4">
    <div class="col-md-6">
        <h3 class="maspudo">Indicadores Clave Metal Mecanica</h3>
        <canvas id="salesChart"></canvas>
    </div>
    <div class="col-md-6">
        <h3 class="maspudo"> KPIs Produccion Metal Mecanica</h3>
        <canvas id="incomeChart"></canvas>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <h3 class="maspudo">Calendario</h3>
        <div id="calendar" style="max-width: 800px; max-height: 500px;"></div>
    </div>
</div>
</div>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
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
                title: 'Reunion',
                start: '2024-10-31'
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
.title {
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
    .title {
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
<div class="title">SISTEMA DE GESTIÓN DE CALIDAD</div>
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
    .title {
        font-size: 28px; /* Aumentar el tamaño de la fuente */
        background-color: #00376b;
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
    