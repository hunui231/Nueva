@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')

<h2 class="text-center mb-4">Bienvenido!,  Este es tu Apartado Taller: </h2>
<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart1"></canvas>
</div>
<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart2"></canvas>
</div>
<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart3"></canvas>
</div>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const data1 = {{ $cnc->dato1 ?? 90 }};
    const meta1 = 100; 
    const data2 = {{ $cnc->dato2 ?? 67 }}; 
    const meta2 = 80; 
    const data3 = {{ $cnc->dato3 ?? 58}};
    const meta3 = 60;
    const createDoughnutChart = (ctx, data, meta, label) => {
        const chartData = {
            labels: [label, 'Meta'],
            datasets: [{
                label: 'Resultado',
                data: [data, meta - data],
                backgroundColor: ['#4CAF50', '#F44336'],
                borderWidth: 1
            }]
        };

        new Chart(ctx, {
            type: 'doughnut',
            data: chartData,
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
                        text: label,
                    }
                }
            }
        });
    };

    const ctx1 = document.getElementById('myDoughnutChart1').getContext('2d');
    const ctx2 = document.getElementById('myDoughnutChart2').getContext('2d');
    const ctx3 = document.getElementById('myDoughnutChart3').getContext('2d');

    createDoughnutChart(ctx1, data1, meta1, 'Semana 5 SEP');
    createDoughnutChart(ctx2, data2, meta2, 'Semana 1 OCT');
    createDoughnutChart(ctx3, data3, meta3, 'Semana 2 OCT');
</script>



<br>
 
 <style>
    #graficoScrapTaller {
        font-family: Arial, sans-serif;
        background-color: #0a0a0a;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .contenedor-grafico {
        width: 80%;
        max-width: 800px;
    }
    h1 {
        text-align: center;
        color: #333;
    }
</style>

<div class="contenedor-grafico">
    <h1>KPIs Operación Metal Mecánica</h1>
    <canvas id="graficoScrapTaller"></canvas>
</div>

<!-- Cargar la biblioteca Chart.js -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
  
    const contextoGrafico = document.getElementById('graficoScrapTaller').getContext('2d');
    
    
    const configuracionGraficoScrapTaller = new Chart(contextoGrafico, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24'],
            datasets: [
                {
                    label: 'SCRAP Taller',
                    data: [0.5, 1.0, 0.8, 10.0, 4.0, 2.0, 1.0, 0.5, 0.2, 0.1, 0.05, 0.0, 0.1, 0.0, 0.0, 0.1, 0.0, 0.0, 0.0],
                    borderColor: 'blue',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'blue',
                },
                {
                    label: 'Meta',
                    data: [3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0],
                    borderColor: 'red',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'red',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'SCRAP Taller'
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '% SCRAP'
                    }
                }
            }
        }
    });
</script>
 <br>
 
 <div class="chart-container">
    <h1>KPIs Operación Metal Mecánica</h1>
    <canvas id="scrapChart"></canvas>
 </div>

  <style>
    #scrapChart{
        font-family: Arial, sans-serif;
        background-color: #0a0a0a;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
        
    .chart-container {
        width: 80%;
        max-width: 800px;
    }
    h1 {
        text-align: center;
        color: #333;
    }
</style>


 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const ctxb = document.getElementById('scrapChart').getContext('2d');
    const scrapChart = new Chart(ctxb, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24'],
            datasets: [
                {
                    label: 'SCRAP Forjas',
                    data: [6.0, 6.0, 6.5, 5.5, 5.8, 6.2, 7.5, 6.0, 5.5, 5.7, 5.0, 4.8, 4.5, 4.6, 4.9, 5.1, 5.6, 6.0, 5.97],
                    borderColor: 'blue',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'blue',
                },
                {
                    label: 'Meta',
                    data: [10.0, 10.0, 10.0, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5],
                    borderColor: 'red',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'red',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'SCRAP Forjas'
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '% SCRAP'
                    }
                }
            }
        }
    });
</script>

<br>

 <h6>KPIs Operación Metal Mecánica</h6>
 <style>
    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
    }
    canvas {
      
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }
    h1 {
        font-size: 1.5em;
        color: #333;
    }
</style>
 <div class="container">
    <canvas id="kpiChart" width="800" height="400"></canvas>
</div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <script>
    const ctx = document.getElementById('kpiChart').getContext('2d');
    const kpiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24'],
            datasets: [{
                label: 'Plan de Producción',
                data: [100, 50, 80, 90, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100],
                backgroundColor: 'rgba(255, 99, 132, 0.5)',
                borderColor: 'rgba(255, 99, 132, 1)',
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    max: 120,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw + '%';
                        }
                    }
                }
            }
        }
    });
</script>

@endsection