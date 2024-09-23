@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
<h1>Bienvenido!, Este es tu Apartado Logistica.</h1>

<br>

<h3>Tus indicacores:</h3>
<div style=" max-width: 600px;">
  <div style="max-height: 300px; max-width: 300px;">
    <canvas id="myChart3"></canvas>
</div>
    <div style="max-height: 300px; max-width: 300px;">
        <canvas id="myChart4"></canvas>
    </div>
   
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Reemplazo del gráfico de cumplimiento de entregas con el gráfico de Dato vs Meta
    const ctx1 = document.getElementById('myChart3');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ['Aprobada', 'No Aprobada'],
            datasets: [{
                label: 'Calificación',
                data: [95, 100 - 95], // 95% alcanzado, 5% restante
                backgroundColor: ['#4CAF50', '#F44336'], // Verde para aprobado, Rojo para no aprobado
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
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
                    text: 'Calificación del 02/09/2024 al 07/09/2024'
                },
                subtitle: {
                    display: true,
                    text: 'Meta: 90% - Dato: 95%'
                }
            }
        }
    });


    const ctx2 = document.getElementById('myChart4');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Aprobada', 'No Aprobada'],
            datasets: [{
                label: 'Calificación',
                data: [75, 100 - 75], // 95% alcanzado, 5% restante
                backgroundColor: ['#4CAF50', '#F44336'], // Verde para aprobado, Rojo para no aprobado
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
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
                    text: 'Calificación del 09/09/2024 al 13/09/2024'
                },
                subtitle: {
                    display: true,
                    text: 'Meta: 90% - Dato: 75%'
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
    <div style="max-height: 700px; max-width: 700px; background-color:black;">
      <canvas id="myChart1"></canvas>
    </div>
  
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  

 <script>

  const logisticData = @json($logisticas);


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