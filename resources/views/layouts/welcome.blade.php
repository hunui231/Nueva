@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'dashboard' @endphp
@endsection

@section('content')

<h1>Bienvenido, has Iniciado Sesión!</h1>

<br>

<!-- Incluye la librería de Google Charts -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">

  google.charts.load('current', {'packages':['corechart']});
  google.charts.setOnLoadCallback(drawCharts);

  function drawCharts() {
    var dataLine = google.visualization.arrayToDataTable([
      ['Año', 'Producción', 'Control de Piezas'],
      ['2021',  1000,      400],
      ['2022',  1170,      460],
      ['2023',  660,       1120],
      ['2024',  1030,      540]
    ]);

    var optionsLine = {
      title: 'CONPLASA PROMEDIO',
      curveType: 'function',
      legend: { position: 'bottom' }
    };

    var chartLine = new google.visualization.LineChart(document.getElementById('curve_chart'));
    chartLine.draw(dataLine, optionsLine);

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

<!-- Contenedor para el gráfico de línea -->
<div id="curve_chart" style="width: 100%; max-width: 900px; height: 500px;"></div>

<br>

<!-- Contenedores para los gráficos de pastel uno al lado del otro -->
<div class="flex flex-col sm:flex-row sm:gap-4">
  <div id="piechart_3d_1" style="width: 100%; max-width: 500px; height: 500px;"></div>
  <div id="piechart_3d_2" style="width: 100%; max-width: 500px; height: 500px;"></div>
</div>

@endsection
