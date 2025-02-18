@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'calidad' @endphp
@endsection

@section('content')

<<<<<<< HEAD
=======
 <style>
    .titulo-container {
        text-align: center;
        background-color: #4A90E2; 
        padding: 15px; 
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3); 
    }
    
    .titulo {
        font-size: 1.3em; 
        color: #f7f7f7; 
        margin: 0;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3); 
    }
    
    .subtitulo {
        font-size: 1em; 
        color: #f8fbfd; 
        margin-top: 5px; 
        font-style: italic; 
    }
    .lul{
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

</style>

<h1 class="lul">Bienvenido!, Este es tu Apartado Calidad</h1>

<br>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@can('calidad.update')
 <form action="{{ route('calidad.update') }}" method="POST" class="mb-4">
    @csrf
    <div class="form-group">
    <label for="dato">Dato (%):</label>
    <input type="number" name="dato" id="dato" value="{{ $calidad->dato ?? 0 }}" required>
    </div>
    <div class="form-group">
    <label for="meta">Meta (%):</label>
    <input type="number" name="meta" id="meta" value="{{ $calidad->meta ?? 100 }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Gráfico</button>
</form>
@endcan
<center>
    <div class="titulo-container">
        <h4 class="titulo">Indicador Logística Semana 1 NOVIEMBRE:</h4>
        <h5 class="subtitulo">04/11/2024 - 08/11/2024</h5>
        </div>
</center>
<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart"></canvas>
</div>
 
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const ctx = document.getElementById('myDoughnutChart').getContext('2d');

    // Definir los datos
    const data = {
        labels: ['Aprobada', 'No Aprobada'],
        datasets: [{
            label: 'Resultado',
            data: [{{ $calidad->dato ?? 0 }}, {{ 100 - ($calidad->dato ?? 0) }}],
            backgroundColor: ['#4CAF50', '#F44336'],
            borderWidth: 1
        }]
    };

    const myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return label + ': ' + value + '%';
                        }
                    }
                },
                title: {
                    display: true,
                   
                }
            }
        }
    });
</script>

<br>
<br>

@if(session('success2'))
    <div>{{ session('success2') }}</div>
@endif

@can('calidad.update')
<form action="{{ route('calidad.grafico2.update') }}" method="POST">
    @csrf
    <div class="form-group">
    <label for="dato">Dato (%):</label>
    <input type="number" name="dato" id="dato" value="{{ $calidadGrafico2->dato ?? 0 }}" required>
    </div>
    <div class="form-group">
    <label for="meta">Meta (%):</label>
    <input type="number" name="meta" id="meta" value="{{ $calidadGrafico2->meta ?? 100 }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Gráfico</button>
</form>
@endcan
<br>
<center>
<div class="titulo-container">
    <h4 class="titulo"> Indicador Logistica Semana 2 NOVIEMBRE:</h4>
    <h5 class="subtitulo">11/11/2024- 15/11/2024</h5>
    </div>
</center>

<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart2"></canvas>
</div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
 <script>
   
    const ctxGrafico2 = document.getElementById('myDoughnutChart2').getContext('2d');

   
    const dato = {{ $calidadGrafico2->dato ?? 0 }};
    const noAprobada = 100 - dato;

    const dataGrafico2 = {
        labels: ['Aprobada', 'No Aprobada'],
        datasets: [{
            label: 'Resultado',
            data: [dato, noAprobada],
            backgroundColor: ['#4CAF50', '#F44336'],
            borderWidth: 1
        }]
    };

    const myDoughnutChart2 = new Chart(ctxGrafico2, {
        type: 'doughnut',
        data: dataGrafico2,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            return label + ': ' + value + '%';
                        }
                    }
                },
                title: {
                    display: true,
                }
            }
        }
    });
</script>

<br>

    <style>
        #lineChart1{
            background-color: black;
            max-width: 800px;
            max-height: 400px;
        }
        #lineChart2{
            background-color: black;
            max-width: 800px;
            max-height: 400px;
        }
        #lineChart3{
            background-color: black;
            max-width: 800px;
            max-height: 400px;
        }
    </style>
<center>
    <div>
        <canvas id="lineChart1"></canvas>
    </div>

    <br>

    <div>
        <canvas id="lineChart2"></canvas>
    </div>
</center>
    <script>
        var ctx1 = document.getElementById('lineChart1').getContext('2d');
        var lineChart1 = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Porcentaje de Piezas Defectuosas',
                    data: [5, 6, 4, 7, 5, 3], // Ejemplo de defectos en %
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Meta de Defectos (5%)',
                    data: [5, 5, 5, 5, 5, 5],  // Línea de meta
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    borderDash: [5, 5],  // Línea discontinua para meta
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 10  // Ajustamos el máximo para la escala
                    }
                }
            }
        });

        var ctx2 = document.getElementById('lineChart2').getContext('2d');
        var lineChart2 = new Chart(ctx2, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Medidas Promedio de Tolerancia (mm)',
                    data: [0.05, 0.04, 0.06, 0.05, 0.03, 0.04], // Ejemplo de medidas en milímetros
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Meta de Tolerancia (0.05 mm)',
                    data: [0.05, 0.05, 0.05, 0.05, 0.05, 0.05],  // Línea de meta
                    borderColor: 'rgba(255, 206, 86, 1)',
                    borderWidth: 2,
                    borderDash: [5, 5],  // Línea discontinua para meta
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 0.1  // Ajustamos el máximo de la escala para medidas
                    }
                }
            }
        });
    </script>

    <br>
    <div>
        <canvas id="lineChart3"></canvas>
    </div>

    <script>
        var ctx3 = document.getElementById('lineChart3').getContext('2d');
        var lineChart3 = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
                datasets: [{
                    label: 'Índice de Satisfacción del Cliente (%)',
                    data: [88, 92, 85, 90, 95, 93], // Ejemplo de índice de satisfacción
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                },
                {
                    label: 'Meta de Satisfacción (90%)',
                    data: [90, 90, 90, 90, 90, 90],  // Línea de meta
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    borderDash: [5, 5],  // Línea discontinua para meta
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        suggestedMax: 100  // Ajustamos el máximo de la escala
                    }
                }
            }
        });
    </script>
>>>>>>> 2e9bd5332d1d7d651d8fece620534bd5b9c8cf17
@endsection
