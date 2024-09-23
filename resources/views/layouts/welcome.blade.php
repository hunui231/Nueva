@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'dashboard' @endphp
@endsection

@section('content')

<h1>Bienvenido, has Iniciado Sesión!</h1>

<br>
 <style>
  #kpiChart {
    background-color: black;
    color: white;
    width: 100%; 
    height: auto;
    aspect-ratio: 3 / 2;
    padding: 10px;
    box-sizing: border-box;
  }
     @media (max-width: 600px) {
    #kpiChart {
      aspect-ratio: 1 / 1; /* Cambia a una proporción más cuadrada en móviles */
      padding: 5px; /* Reduce el padding en pantallas pequeñas */
    }
  }
</style>
 <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="kpiChart"></canvas>
 <script>
    const ctx = document.getElementById('kpiChart').getContext('2d');
 
   
    const fechas = ['01/09/2024', '02/09/2024', '03/09/2024', '04/09/2024', '05/09/2024'];
    
    
    const produccion = [500, 450, 600, 550, 620]; 
    const defectos = [10, 15, 5, 8, 12]; 
    const tiempoInactividad = [1, 2, 0.5, 1.5, 1];
    const eficiencia = [85, 80, 90, 88, 92]; 
    
    
    const kpiChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: fechas,
            datasets: [
                {
                    label: 'Producción (Piezas)',
                    data: produccion,
                    borderColor: 'rgba(0, 123, 255, 1)',
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',  
                    borderWidth: 2,
                    fill: true 
                },
                {
                    label: 'Defectos (Piezas)',
                    data: defectos,
                    borderColor: 'rgba(255, 99, 132, 1)',  
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderWidth: 1,
                    fill: true  
                },
                {
                    label: 'Tiempo Inactividad (Horas)',
                    data: tiempoInactividad,
                    borderColor: 'rgba(255, 206, 86, 1)', 
                    backgroundColor: 'rgba(255, 206, 86, 0.2)', 
                    borderWidth: 2,
                    fill: true  
                },
                {
                    label: 'Eficiencia (%)',
                    data: eficiencia,
                    borderColor: 'rgba(75, 192, 192, 1)', 
                    backgroundColor: 'rgba(75, 192, 192, 0.2)', 
                    borderWidth: 2,
                    fill: true 
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'Indicadores Clave de Rendimiento (KPI) -  Metalmecánica',
                    color: 'white'
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const kpiNames = [
                                'Producción',
                                'Defectos',
                                'Tiempo Inactividad',
                                'Eficiencia'
                            ];
                            const values = [
                                produccion[tooltipItem.dataIndex],
                                defectos[tooltipItem.dataIndex],
                                tiempoInactividad[tooltipItem.dataIndex],
                                eficiencia[tooltipItem.dataIndex]
                            ];
                            return `${kpiNames[tooltipItem.datasetIndex]}: ${values[tooltipItem.dataIndex]}`;
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
 <script type="text/javascript">

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawCharts);

  function drawCharts() {
   
    var dataPie1 = google.visualization.arrayToDataTable([
      ['Tareas', 'Horas por Día'],
      ['Taller', 11],
      ['CNC', 2],
      ['Almacén', 2],
      ['Compras', 2],
      ['Gasket', 7]
    ]);

    var optionsPie1 = {
      title: 'ACTIVIDADES POR ÁREA',
      is3D: true,
    };

    

    var chartPie1 = new google.visualization.PieChart(document.getElementById('piechart_3d_1'));
    chartPie1.draw(dataPie1, optionsPie1);

    var dataPie2 = google.visualization.arrayToDataTable([
      ['Tareas', 'Horas por Día'],
      ['Taller', 11],
      ['CNC', 2],
      ['Almacén', 2],
      ['Compras', 2],
      ['Gasket', 7]
    ]);

     

    
    var optionsPie2 = {
      title: 'PRODUCCIÓN',
      is3D: true,
    };

    var chartPie2 = new google.visualization.PieChart(document.getElementById('piechart_3d_2'));
    chartPie2.draw(dataPie2, optionsPie2);
  }
</script>

<br>

<div class="flex flex-col sm:flex-row sm:gap-4">
  <div id="piechart_3d_1" style="width: 100%; max-width: 500px; height: 500px;"></div>
  <div id="piechart_3d_2" style="width: 100%; max-width: 500px; height: 500px;"></div>
</div>

@endsection
