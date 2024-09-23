@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'calidad' @endphp
@endsection

@section('content')

<h1>Bienvenido!, Este es tu Apartado Calidad: </h1>

<br>


@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@can('calidad.update')
 <form action="{{ route('calidad.update') }}" method="POST">
    @csrf
    <label for="dato">Dato (%):</label>
    <input type="number" name="dato" id="dato" value="{{ $calidad->dato ?? 0 }}" required>
    
    <label for="meta">Meta (%):</label>
    <input type="number" name="meta" id="meta" value="{{ $calidad->meta ?? 100 }}" required>

    <button type="submit" class="btn btn-secondary">Actualizar Gráfico</button>
</form>
@endcan
<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart"></canvas>
</div>
 
 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const ctx = document.getElementById('myDoughnutChart').getContext('2d');

    // Definir los datos
    const data = {
        labels: ['Aprobada', 'No Aprobada'],
        datasets: [{
            label: 'Resultado',
            data: [{{ $calidad->dato ?? 0 }}, {{ 100 - ($calidad->dato ?? 0) }}],
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
                    text: 'KPI Semana 2 SEP',
                }
            }
        }
    });
</script>

<br>
<br>

@if(session('success2'))
    <div>{{ session('success2') }}</div>
@endif
@can('calidad.update')
<form action="{{ route('calidad.grafico2.update') }}" method="POST">
    @csrf
    <label for="dato">Dato (%):</label>
    <input type="number" name="dato" id="dato" value="{{ $calidadGrafico2->dato ?? 0 }}" required>
    
    <label for="meta">Meta (%):</label>
    <input type="number" name="meta" id="meta" value="{{ $calidadGrafico2->meta ?? 100 }}" required>

    <button type="submit" class="btn btn-secondary">Actualizar Gráfico</button>
</form>
@endcan
<div style="max-width: 600px; margin: 0 auto;">
    <canvas id="myDoughnutChart2"></canvas>
</div>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
 
 <script>
    // Cambia el nombre de la variable ctx para evitar conflictos
    const ctxGrafico2 = document.getElementById('myDoughnutChart2').getContext('2d');

    // Verificar si hay datos
    const dato = {{ $calidadGrafico2->dato ?? 0 }};
    const noAprobada = 100 - dato;

    // Definir los datos
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
                    text: 'KPI Semana 3 SEP',
                }
            }
        }
    });
</script>
@endsection
