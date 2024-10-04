@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'calidad' @endphp
@endsection

@section('content')

<h1 class="text-center mb-4">Bienvenido!, Este es tu Apartado Calidad: </h1>

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
    <h4> Indicador Logistica Semana 1 OCTUBRE:</h4>
    <h5>30/09/2024- 04/10/2024</h5>
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
                    text: 'KPI',
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
                    text: 'KPI',
                }
            }
        }
    });
</script>

<br>
    <style>
     /* Contenedor principal para el gráfico */
    .graph-container {
        width: 100%; /* Cambiado a 100% para mayor flexibilidad */
        max-width: 800px; /* Mantiene el tamaño máximo */
        margin: 0 auto; /* Centra el contenedor */
        padding: 20px; /* Añade un poco de espacio interno */
     /* Sombra para resaltar el gráfico */        
    }

    /* Estilos para el gráfico */
    #kpiGraph {
        font-family: Arial, sans-serif;
        color: #ffffff; /* Cambia el color del texto a blanco para mejor contraste */
        background-color: #000000; /* Mantiene el fondo del gráfico en negro */
        border-radius: 8px; /* Bordes redondeados para el gráfico */
        padding: 20px; /* Espacio interno en el gráfico */
    }

    /* Responsividad para dispositivos móviles */
    @media (max-width: 600px) {
        .graph-container {
            width: 90%; /* Aumenta el ancho en pantallas más pequeñas */
            padding: 10px; /* Reduce el padding en pantallas pequeñas */
        }

        #kpiGraph {
            max-height: 400px; /* Ajusta la altura máxima en dispositivos pequeños */
        }
    }
    </style>

    <div class="graph-container">
        <canvas id="kpiGraph"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos de ejemplo (actualizados)
        const mesesEtiquetas = ['feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24'];
        const rendimientoDatos = {
            labels: mesesEtiquetas,
            datasets: [
                {
                    label: 'Evaluación de Desempeño',
                    data: [80, 85, 90, 92, 95, 98, 99, 98, 97, 98, 98.5, 99, 99.2, 99.5, 99.8, 99.9, 100, 98.9, 99.5, 100],
                    backgroundColor: 'rgba(255, 0, 0, 0.5)',
                    borderColor: 'rgba(255, 0, 0, 1)',
                    borderWidth: 1,
                    fill: true,  // Esto permite el área de fondo
                    yAxisID: 'y'
                },
                {
                    label: 'Meta (100%)',
                    data: Array(mesesEtiquetas.length).fill(100),
                    borderColor: 'rgba(0, 0, 255, 1)',
                    borderWidth: 2,
                    fill: false,
                    type: 'line',
                    tension: 0.1  // Línea curva suave
                }
            ]
        };

        // Configuración del gráfico
        const configuracionGrafico = {
            type: 'line',
            data: rendimientoDatos,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Cumplimiento Mensual Maquinado'
                    }
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Meses'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Porcentaje'
                        },
                        min: 75,
                        max: 105,
                        ticks: {
                            callback: function(valor) {
                                return valor + '%';
                            }
                        }
                    }
                }
            }
        };

        // Renderizar el gráfico
        const contextoGrafico = document.getElementById('kpiGraph').getContext('2d');
        const graficoKPI = new Chart(contextoGrafico, configuracionGrafico);
    </script>
@endsection
