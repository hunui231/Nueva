@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')

<h1>Bienvenido!,  Este es tu Apartado Calidad: </h1>

<br>


<div style=" max-width: 600px;">
    <!-- Primer gráfico -->
    <div style="max-height: 300px; max-width: 300px;">
      <canvas id="myChart1"></canvas>
    </div>
  
    <!-- Segundo gráfico -->
    <div style="max-height: 280px; max-width: 280px;">
      <canvas id="myChart2"></canvas>
    </div>
  </div>
  
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <script>
      // Gráfico de defectos por tipo de causa
      const ctx1 = document.getElementById('myChart1');
      new Chart(ctx1, {
        type: 'doughnut',
        data: {
          labels: ['Fallas de Material', 'Errores en el Proceso', 'Fallas Humanas', 'Problemas en el Diseño', 'Otras Causas'],
          datasets: [{
            label: 'Porcentaje de Defectos',
            data: [35, 25, 20, 15, 5], // Porcentajes de defectos por tipo de causa
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF'], // Colores opcionales para cada segmento
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
    // Gráfico de efectividad de las acciones correctivas
  const ctx3 = document.getElementById('myChart2');
  new Chart(ctx3, {
    type: 'doughnut',
    data: {
      labels: ['Capacitación del Personal', 'Mejoras en el Proceso', 'Actualización de Equipos', 'Revisión de Proveedores'],
      datasets: [{
        label: 'Impacto de Acciones Correctivas',
        data: [40, 30, 20, 10], // Porcentaje de impacto de cada acción correctiva
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0'], 
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


@endsection