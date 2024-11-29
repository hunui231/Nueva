@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')

<h2 class="maspudo">Bienvenido!,  Apartado de Taller: </h2>

<br>
 
 <style>
    .um{
        font-family: 'Roboto', sans-serif;
        color: #FFFFFF; 
        text-align: center; 
        font-weight: 500; 
        font-size: 1em; 
        text-transform: uppercase; 
        background: linear-gradient(90deg, #0a0a0a, #434948); 
        padding: 20px; 
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        margin: 20px 0; 
        transition: transform 0.3s;
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
   
</style>


<div class="contenedor-grafico">
    <h1 class="um">KPI Operación Metal Mecánica</h1>
    <canvas id="graficoScrapTaller"></canvas>
</div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const contextoGrafico = document.getElementById('graficoScrapTaller').getContext('2d');
    
    
    const configuracionGraficoScrapTaller = new Chart(contextoGrafico, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24' ],
            datasets: [
                {
                    label: 'SCRAP Taller',
                    data: [0.5, 1.0, 0.8, 10.0, 4.0, 2.0, 1.0, 0.5, 0.2, 0.1, 0.05, 0.0, 0.1, 0.0, 0.0, 0.1, 0.0, 0.0, 0.0,  0.1, 0.0, 0.0,],
                    borderColor: 'blue',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'blue',
                },
                {
                    label: 'Meta',
                    data: [3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0, 3.0,  3.0, 3.0, 3.0,],
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
    <h1 class="um">KPI Operación Metal Mecánica</h1>
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
    .lias{
        background-color: #0a0a0a;
        
    }
</style>


 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const ctxb = document.getElementById('scrapChart').getContext('2d');
    const scrapChart = new Chart(ctxb, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24',  'ago-24',  'sep-24',  'oct-24'],
            datasets: [
                {
                    label: 'SCRAP Forjas',
                    data: [6.0, 6.0, 6.5, 5.5, 5.8, 6.2, 7.5, 6.0, 5.5, 5.7, 5.0, 4.8, 4.5, 4.6, 4.9, 5.1, 5.6, 6.0, 5.97,5.6, 6.0, 5.97],
                    borderColor: 'blue',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'blue',
                },
                {
                    label: 'Meta',
                    data: [10.0, 10.0, 10.0, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5],
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

 <h6 class="um">KPI Operación Metal Mecánica</h6>
 <style>
    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 20px;
        background-color:#0a0a0a;
    }
    canvas {
      
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24',  'ago-24', 'sep-24', 'oct-24'],
            datasets: [{
                label: 'Plan de Producción',
                data: [100, 50, 80, 90, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100, 100],
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