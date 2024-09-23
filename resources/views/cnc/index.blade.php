@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')

<h1>Bienvenido!,  Este es tu Apartado CNC: </h1>

<div style="max-width: 600px;">
    <div style="max-height: 300px; max-width: 300px;">
      <canvas id="myChart1"></canvas>
    </div>
  
    <div style="max-height: 300px; max-width: 300px;">
      <canvas id="myChart2"></canvas>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>

    const ctx4 = document.getElementById('myChart1');
      new Chart(ctx4, {
        type: 'doughnut',
        data: {
          labels: ['Tiempo de Corte', 'Tiempo de Configuración', 'Tiempo de Inactividad', 'Tiempo de Mantenimiento', 'Tiempo de Ajustes'],
          datasets: [{
            label: 'Distribución de Tiempo',
            data: [50, 20, 15, 10, 5], 
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
  <br>
  <canvas id="kpiChart" style="max-height: 400px; max-width: 600px;"></canvas>
  <script>
      const ctx = document.getElementById('kpiChart').getContext('2d');


      const fechas = ['01/09/2024', '02/09/2024', '03/09/2024', '04/09/2024', '05/09/2024'];
      const datos = [80, 15, 80, 100, 97];
      const metas = [90, 90, 90, 90, 90];

      const kpiChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: fechas,
              datasets: [
                  {
                      label: 'KPI',
                      data: datos,
                      borderColor: 'rgba(0, 123, 255, 1)',  // Color de la línea azul
                      backgroundColor: 'rgba(0, 123, 255, 0.2)',  // Área debajo de la línea
                      borderWidth: 2,
                      fill: true  // Rellenar área bajo la línea
                  },
                  {
                      label: 'Meta',
                      data: metas,
                      borderColor: 'rgba(255, 99, 132, 1)',  // Color de la línea roja
                      backgroundColor: 'rgba(255, 99, 132, 0.2)',  // Área debajo de la línea
                      borderWidth: 1,
                      borderDash: [5, 5], // Línea punteada
                      fill: true  // Rellenar área bajo la línea
                  }
              ]
          },
          options: {
              responsive: true,
              plugins: {
                  title: {
                      display: true,
                      text: 'Cumplimiento  al Plan de Produccion Maquinados Forjas',
                      color: 'white'
                  },
                  tooltip: {
                      callbacks: {
                          label: function(tooltipItem) {
                              const calificaciones = ['No cumplido', 'Cumplido', 'No cumplido', 'Excedido', 'Cumplido'];
                              return `Fecha: ${fechas[tooltipItem.dataIndex]}, KPI: ${tooltipItem.raw}, Calificación: ${calificaciones[tooltipItem.dataIndex]}`;
                          }
                      }
                  }
              },
              scales: {
                  y: {
                      beginAtZero: true,
                      title: {
                          display: true,
                          text: 'Valor',
                          color: 'white'
                      },
                      ticks: {
                          color: 'white' // Color de los números del eje Y
                      },
                      grid: {
                          color: 'rgba(255, 255, 255, 0.2)'  // Líneas del grid
                      }
                  },
                  x: {
                      title: {
                          display: true,
                          text: 'Fecha',
                          color: 'white'
                      },
                      ticks: {
                          color: 'white'  // Color de los números del eje X
                      },
                      grid: {
                          color: 'rgba(255, 255, 255, 0.2)'  // Líneas del grid
                      }
                  }
              }
          }
      });
  </script>
  
    <style>
       #kpiChart{
        background-color: black;
       }
    </style>
@endsection