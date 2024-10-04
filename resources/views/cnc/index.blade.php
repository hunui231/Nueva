@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')

<h1 class="text-center mb-4">Bienvenido!,  Este es tu Apartado CNC: </h1>


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
    <h4> Indicador CNC Semana 3 SEP:</h4>
    <h5>16/09/2024 - 20/09/2024</h5>
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
                    text: 'KPI',
                }
            }
        }
    });
</script>

<br><br>

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
    <h4> Indicador CNC Semana 3 SEP:</h4>
    <h5>16/09/2024 - 20/09/2024</h5>
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
                    text: 'KPI',
                }
            }
        }
    });
</script>
@endsection