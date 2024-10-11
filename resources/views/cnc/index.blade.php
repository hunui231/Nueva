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
    .lu{
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

<h1  class="lu">Bienvenido!,  Este es tu Apartado CNC</h1>

<br>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@can('cnc.update')
 <form action="{{ route('cnc.update') }}" method="POST" class="mb-4">
    @csrf
    <div class="form-group">
    <label for="dato">Dato (%):</label>
    <input type="number" name="dato" id="dato" value="{{ $cnc->dato ?? 0 }}" required>
    </div>
    <div class="form-group">
    <label for="meta">Meta (%):</label>
    <input type="number" name="meta" id="meta" value="{{ $cnc->meta ?? 100 }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Gráfico</button>
</form>
@endcan
<br>
<center>
    <div class="titulo-container">
        <h4 class="titulo"> Indicador Logistica Semana 1 OCTUBRE:</h4>
        <h5 class="subtitulo">30/09/2024- 04/10/2024</h5>
        </div>
</center>
 <div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart"></canvas>
 </div>
 
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const ctx = document.getElementById('myDoughnutChart').getContext('2d');
  
    const data = {
        labels: ['Aprobada', 'No Aprobada'],
        datasets: [{
            label: 'Resultado',
            data: [{{ $cnc->dato ?? 0 }}, {{ 100 - ($cnc->dato ?? 0) }}],
            backgroundColor: ['#4CAF50', '#F44336'],
            borderWidth: 1
        }]
    };
    const myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
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
                }
            }
        }
    });
</script>


@if(session('success2'))
    <div>{{ session('success2') }}</div>
@endif


<br>
@can('cnc.update')
<form action="{{ route('cnc.grafico2.update') }}" method="POST" class="mb-4">
    @csrf
    <div class="form-group">
    <label for="dato">Dato (%):</label>
    <input type="number" name="dato" id="dato" value="{{ $cncGrafico2->dato ?? 0 }}" required>
    </div>
    <div class="form-group">
    <label for="meta">Meta (%):</label>
    <input type="number" name="meta" id="meta" value="{{ $cncGrafico2->meta ?? 100 }}" required>
    </div>
    <button type="submit" class="btn btn-primary">Actualizar Gráfico</button>
</form>
@endcan
<center>
    <div class="titulo-container">
        <h4 class="titulo"> Indicador Logistica Semana 2 OCTUBRE:</h4>
        <h5 class="subtitulo">07/09/2024- 11/10/2024</h5>
        </div>
</center>

<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart2"></canvas>
</div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const ctxGrafico2 = document.getElementById('myDoughnutChart2').getContext('2d');
    const dato = {{ $cncGrafico2->dato ?? 0 }};
    const noAprobada = 100 - dato;

    const dataGrafico2 = {
        labels: ['Aprobada', 'No Aprobada'],
        datasets: [{
            label: 'Resultado',
            data: [dato, noAprobada],
            backgroundColor: ['#4CAF50', '#F44336'],
            borderWidth: 1
        }]
    };

    const myDoughnutChart2 = new Chart(ctxGrafico2, {
        type: 'doughnut',
        data: dataGrafico2,
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
                }
            }
        }
    });
</script>
@endsection