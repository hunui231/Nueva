@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')

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
.lus{
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
<h1 class="lus">Bienvenido!, Este es tu Apartado Logistica</h1>

<br>
@if(session('success'))
    <div>{{ session('success') }}</div>
@endif
@can('logistica.update')
<form action="{{ route('logistica.update') }}" method="POST">
    @csrf
    <div class="form-group">
    <label for="dato">Dato (%):</label>
    <input type="number" name="dato" id="dato" value="{{ $tlogistica2->dato ?? 0 }}" required>
    </div>
    <div class="form-group">
    <label for="meta">Meta (%):</label>
    <input type="number" name="meta" id="meta" value="{{ $tlogistica2->meta ?? 100 }}" required>
    </div>

    <button type="submit" class="btn btn-primary">Actualizar Gráfico</button>
</form>
@endcan
<br>

<center>
    <div class="titulo-container">
        <h4 class="titulo">Indicador Logística Semana 2 ENERO:</h4>
        <h5 class="subtitulo">13/01/2025 - 17/01/2025</h5>
    </div>
    </center>
<div style="max-width: 300px; margin: 0 auto;">
    <canvas id="myDoughnutChart"></canvas>
</div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const tlogisticaData = @json($tlogistica2);

    const dato = tlogisticaData.dato ?? 0;
    const meta = tlogisticaData.meta ?? 100;

    const ctx = document.getElementById('myDoughnutChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Aprobado', 'No Aprobado'],
            datasets: [{
                data: [dato, meta - dato], 
                backgroundColor: ['#4CAF50', '#F44336'],
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        }
    });
</script>

@can('logistica.update')
<form action="{{ route('logistica.updateDona2') }}" method="POST">
    @csrf
    <div class="form-group">
    <label for="dato_dona2">Dato (%):</label>
    <input type="number" name="dato_dona2" id="dato_dona2" value="{{ $tlogistica3->dato ?? 0 }}" required>
    </div>
    <div class="form-group">
    <label for="meta_dona2">Meta (%):</label>
    <input type="number" name="meta_dona2" id="meta_dona2" value="{{ $tlogistica3->meta ?? 100 }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Gráfico</button>
</form>
@endcan
       
<br>
<center>
    <div class="titulo-container">
    <h4 class="titulo"> Indicador Logistica Semana 3 ENERO:</h4>
    <h5 class="subtitulo">20/01/2025 - 27/01/2025</h5>
    </div>
</center>
<div style="max-width: 300px; margin: 0 auto;">
    <canvas id="myDoughnutChart2"></canvas>
</div>

 <script>
    const tlogisticaData2 = @json($tlogistica3);

    const dato2 = tlogisticaData2.dato ?? 0;
    const meta2 = tlogisticaData2.meta ?? 100;
       
    const ctx2 = document.getElementById('myDoughnutChart2').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Aprobado', 'No Aprobado'],
            datasets: [{
                data: [dato2, meta2 - dato2], // Valores para el segundo gráfico
                backgroundColor: ['#4CAF50', '#F44336'], // Colores para los sectores
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
            },
        }
    });
</script>

<br>
<br>
<h3 style="font-size: 24px;
font-weight: bold;
text-align: center;
padding: 10px;
color: white;
background-color: #030303;
border-radius: 8px; 
box-shadow: 0 4px 8px rgba(26, 24, 24, 0.2); 
transition: transform 0.3s;">LOGISTICA GRAFICOS:</h3>

 <br>

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

  const califValues = califs.map(calif => {
    switch(calif) {
      case 'aprobada': return 100;
      case 'reprobada': return 0;
      case 'pendiente': return 50;
      default: return 0;
    }
  });

  const ctb = document.getElementById('myChart1').getContext('2d');
  new Chart(ctb, {
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

 
<br>
<br>

<h5>KPI PRODUCCION Logistica</h5>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 <style>

    #myChart {
        max-width: 700px;
        max-height: 400px;
        background-color: black;
    }
</style>
  
   <canvas id="myChart"></canvas>
 <script>
    const labels = ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'Ago-24', 'Sep-24'];
    const data = {
        labels: labels,
        datasets: [
            {
                label: 'Inventarios (%)',
                data: [90, 65, 78, 88, 92, 95, 98, 90, 94, 92, 93, 95, 97, 98, 94, 92, 95, 96, 96, 99, 98],
                borderColor: '#0066cc', // Color azul para la línea
                backgroundColor: 'rgba(0, 102, 204, 0.3)', // Color de relleno
                tension: 0.3, // Suavizar la curva
                fill: false, // Sin relleno debajo de la línea
                borderWidth: 3,
                pointBackgroundColor: '#0066cc',
            },
            {
                label: 'Rango Base',
                data: Array(21).fill(85), // Área roja inferior constante
                backgroundColor: 'rgba(255, 0, 0, 0.6)', // Color rojo para el área
                borderWidth: 0,
                fill: true, // Relleno en rojo
                tension: 0,
            }
        ]
    };


    const config = {
        type: 'line',
        data: data,
        options: {
            scales: {
                y: {
                    beginAtZero: false,
                    min: 60, // Ajustar el mínimo al 60%
                    max: 100, // Ajustar el máximo al 100%
                    ticks: {
                        callback: function(value) {
                            return value + '%'; // Mostrar valores como porcentaje
                        }
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.raw + '%'; // Tooltip con porcentaje
                        }
                    }
                },
                legend: {
                    display: false, // Ocultar la leyenda
                }
            }
        }
    };

    // Renderizarel 
        const myChart = new Chart(
        document.getElementById('myChart'),
        config
    );
</script>

<br>
 <style>

  .chart-containerB {
      max-width: 900px;
      max-height: 600px;
      background-color: #000
  }

    #scrapChart{
      width: 100% !important;
      height: auto !important;
  }
</style>
 <h5>SCRAP Donaldson</h5>
 <div class="chart-containerB">
   <canvas id="scrapChart"></canvas>
 </div>

<!-- Incluimos la librería de Chart.js -->
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
  const ctxy = document.getElementById('scrapChart').getContext('2d');

  const scrapData = {
      labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24'],
      datasets: [
          {
              label: 'SCRAP Donaldson',
              data: [0.5, 1.2, 0.7, 0.3, 0.8, 0.4, 1.5, 2.8, 0.6, 1.0, 0.4, 0.7, 1.0, 2.5, 0.3, 0.1, 0.5, 0.2, 0],
              borderColor: 'blue',
              backgroundColor: 'rgba(0, 0, 255, 0.1)',
              borderWidth: 2,
              fill: true,
              tension: 0.3 // Hace que las líneas sean un poco más suaves
          },
          {
              label: 'Línea de meta',
              data: Array(19).fill(2), // La línea roja será constante en 2% durante todo el gráfico
              borderColor: 'red',
              borderWidth: 2,
              fill: false,
              borderDash: [5, 5], // Línea discontinua
          }
      ]
  };

  const configu = {
      type: 'line',
      data: scrapData,
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  max: 3, // Aseguramos que el eje Y llegue a 3% como en la imagen
                  ticks: {
                      callback: function(value) {
                          return value + '%'; // Añadir porcentaje a las etiquetas del eje Y
                      }
                  }
              }
          },
          plugins: {
              legend: {
                  display: false // Si no quieres mostrar la leyenda, puedes cambiar esto
              },
              tooltip: {
                  callbacks: {
                      label: function(tooltipItem) {
                          return tooltipItem.formattedValue + '%';
                      }
                  }
              }
          }
      }
  };

  const scrapCharte = new Chart(ctxy, configu);
</script>



@endsection