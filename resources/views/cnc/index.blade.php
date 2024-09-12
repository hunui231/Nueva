@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')

<h1>Bienvenido!,  Este es tu Apartado CNC: </h1>

<div style="max-width: 600px;">
    <!-- Primer gráfico -->
    <div style="max-height: 300px; max-width: 300px;">
      <canvas id="myChart1"></canvas>
    </div>
  
    <!-- Segundo gráfico -->
    <div style="max-height: 300px; max-width: 300px;">
      <canvas id="myChart2"></canvas>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
      // Gráfico de distribución de tiempo en operaciones de CNC
      const ctx4 = document.getElementById('myChart1');
      new Chart(ctx4, {
        type: 'doughnut',
        data: {
          labels: ['Tiempo de Corte', 'Tiempo de Configuración', 'Tiempo de Inactividad', 'Tiempo de Mantenimiento', 'Tiempo de Ajustes'],
          datasets: [{
            label: 'Distribución de Tiempo',
            data: [50, 20, 15, 10, 5], // Porcentaje de tiempo en cada actividad
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Colores para cada actividad
            borderWidth: 1
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            tooltip: {
              callbacks: {
                label: function(tooltipItem) {
                  return tooltipItem.label + ': ' + tooltipItem.raw + '%'; 
                }
              }
            }
          }
        }
      });
      
     // Gráfico de tasa de defectos por tipo de error en CNC
  const ctx5 = document.getElementById('myChart2');
  new Chart(ctx5, {
    type: 'doughnut',
    data: {
      labels: ['Errores de Dimensión', 'Errores de Superficie', 'Errores de Posicionamiento', 'Errores de Procesamiento'],
      datasets: [{
        label: 'Tipo de Defecto',
        data: [40, 30, 20, 10], // Porcentaje de cada tipo de error
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'], // Colores para cada tipo de defecto
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.label + ': ' + tooltipItem.raw + '%';
            }
          }
        }
      }
    }
  });
  </script>

<br>
  <html>
    <head>
      <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
      <script type="text/javascript">
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);
  
        function drawChart() {
          var data = google.visualization.arrayToDataTable([
            ['Año', 'Ventas', 'Problemas'],
            ['2022',  1000,      400],
            ['2023',  1170,      460],
            ['2024',  660,       1120],
            ['2025',  1030,      540]
          ]);
  
          var options = {
            title: 'Promedio CNC',
            hAxis: {title: 'Año',  titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
          };
  
          var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
          chart.draw(data, options);
        }
      </script>
    </head>
    <body>
      <div id="chart_div" style="width: 100%; height: 300px;"></div>
    </body>
  </html>
  
@endsection