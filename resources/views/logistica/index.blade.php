@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
<h1>Bienvenido!,  Este es tu Apartado Logistica.</h1>

<br>


<br>
        <h3>Tus indicacores:</h3>
<div style=" max-width: 600px;">
 
  <div style="max-height: 300px; max-width: 300px;">
    <canvas id="myChart4"></canvas>
  </div>
  <div style="max-height: 300px; max-width: 300px;">
    <canvas id="myChart3"></canvas>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Gráfico de cumplimiento de entregas
    const ctx1 = document.getElementById('myChart3');
    new Chart(ctx1, {
      type: 'doughnut',
      data: {
        labels: ['Entregas a tiempo', 'Entregas con retraso', 'Entregas adelantadas'],
        datasets: [{
          label: 'Cumplimiento de Entregas',
          data: [70, 20, 10], // Porcentajes de entregas
          backgroundColor: ['#4CAF50', '#FF5722', '#FFC107'], // Colores para cada categoría
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
                return tooltipItem.label + ': ' + tooltipItem.raw + '%'; // Muestra los porcentajes
              }
            }
          }
        }
      }
    });

    // Gráfico de tiempos en cada etapa del proceso logístico
  const ctx2 = document.getElementById('myChart4');
  new Chart(ctx2, {
    type: 'doughnut',
    data: {
      labels: ['Recepción de materiales', 'Almacenamiento', 'Preparación de pedidos', 'Transporte', 'Distribución final'],
      datasets: [{
        label: 'Tiempos en el proceso logístico',
        data: [20, 15, 25, 30, 10], // Porcentajes de tiempo en cada etapa
        backgroundColor: ['#1E88E5', '#D32F2F', '#43A047', '#FB8C00', '#8E24AA'], // Colores para cada fase 
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
<br>


<br>
<br>
<h3>Tus graficos:</h3>
<h4>Este Mes:</h4>


    <!-- Primer gráfico -->
    <div style="max-height: 700px; max-width: 700px;">
      <canvas id="myChart1"></canvas>
    </div>
  
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  

<script>
  // Obtener los datos de la vista
  const logisticData = @json($logisticas);

  // Procesar los datos para el gráfico
  const fechas = logisticData.map(logistica => logistica.fecha);
  const datos = logisticData.map(logistica => logistica.dato);
  const metas = logisticData.map(logistica => logistica.meta);
  const califs = logisticData.map(logistica => logistica.calif);

  // Mapear las calificaciones a valores numéricos para el gráfico
  const califValues = califs.map(calif => {
    switch(calif) {
      case 'aprobada': return 100;
      case 'reprobada': return 0;
      case 'pendiente': return 50;
      default: return 0;
    }
  });

  // Crear el gráfico
  const ctx = document.getElementById('myChart1').getContext('2d');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: fechas,
      datasets: [{
        label: 'Dato',
        data: datos,
        backgroundColor: 'rgba(75, 192, 192, 0.2)',
        borderColor: 'rgba(75, 192, 192, 1)',
        borderWidth: 1
      }, {
        label: 'Meta',
        data: metas,
        backgroundColor: 'rgba(153, 102, 255, 0.2)',
        borderColor: 'rgba(153, 102, 255, 1)',
        borderWidth: 1
      }, {
        label: 'Calificación',
        data: califValues,
        backgroundColor: 'red',
        borderColor: 'rgba(255, 159, 64, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        x: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Fecha'
          }
        },
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Valor'
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
            }
          }
        }
      }
    }
  });
</script>


@endsection