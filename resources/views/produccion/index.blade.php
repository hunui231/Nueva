@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
<!-- Scrap Chart -->
<h2>Producción SCRAP CI</h2>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="scrapChart"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Producción SCRAP CI</h2>
@can('produccion.update')
<form id="dataFormScrap">
    <label for="monthScrap">Mes:</label>
    <select id="monthScrap" name="monthScrap"></select><br><br>

    <label for="performanceScrap">Desempeño (%):</label>
    <input type="number" id="performanceScrap" name="performanceScrap" min="0" max="100" step="0.01" required><br><br>

    <label for="areaScrap">Área de cumplimiento (%):</label>
    <input type="number" id="areaScrap" name="areaScrap" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctxScrap = document.getElementById('scrapChart').getContext('2d');
    const scrapChart = new Chart(ctxScrap, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'],
            datasets: [
                {
                    label: 'Desempeño',
                    data: Array(24).fill(null), // Inicializar con valores nulos
                    borderColor: '#007bff',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Área de cumplimiento',
                    data: Array(24).fill(null), // Inicializar con valores nulos
                    borderColor: '#ff0000',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: false,
                    min: 1,
                    max: 3.5,
                    ticks: {
                        callback: function (value) {
                            return value.toFixed(2) + '%';
                        }
                    }
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectScrap = document.getElementById('monthScrap');
    const months = ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'];
    months.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectScrap.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('dataFormScrap').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectScrap.value;
        const performance = parseFloat(document.getElementById('performanceScrap').value);
        const area = parseFloat(document.getElementById('areaScrap').value);

        // Validar que los valores estén dentro del rango
        if (performance < 0 || performance > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/scrap/store', {
            mes: month,
            desempeno: performance,
            area_cumplimiento: area,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = months.indexOf(month);
                scrapChart.data.datasets[0].data[index] = performance;
                scrapChart.data.datasets[1].data[index] = area;
                scrapChart.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/scrap/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = months.indexOf(item.mes);
                if (index !== -1) {
                    scrapChart.data.datasets[0].data[index] = item.desempeno;
                    scrapChart.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            scrapChart.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>
<br><br>

<!-- Rendimiento Operacional CI -->
<h2>Rendimiento Operacional CI</h2>
<canvas id="rendimientoChart"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<h2>Actualizar Datos de Rendimiento Operacional CI</h2>
@can('produccion.update')
<form id="dataFormRendimiento">
    <label for="monthRendimiento">Mes:</label>
    <select id="monthRendimiento" name="monthRendimiento"></select><br><br>

    <label for="performanceRendimiento">Desempeño (%):</label>
    <input type="number" id="performanceRendimiento" name="performanceRendimiento" min="0" max="100" step="0.01" required><br><br>

    <label for="areaRendimiento">Área de cumplimiento (%):</label>
    <input type="number" id="areaRendimiento" name="areaRendimiento" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script>
// Configurar el token CSRF en Axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Datos iniciales del gráfico
const ctxRendimiento = document.getElementById('rendimientoChart').getContext('2d');
const rendimientoChart = new Chart(ctxRendimiento, {
    type: 'line',
    data: {
        labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'],
        datasets: [
            {
                label: 'Desempeño',
                data: Array(24).fill(null), // Inicializar con valores nulos
                borderColor: '#007bff',
                backgroundColor: '#007bff',
                fill: false,
                tension: 0.3
            },
            {
                label: 'Área de cumplimiento',
                data: Array(24).fill(null), // Inicializar con valores nulos
                borderColor: 'red',
                backgroundColor: 'red',
                fill: false,
                borderWidth: 2,
                tension: 0.1
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function (tooltipItem) {
                        return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                    }
                }
            }
        },
        scales: {
            y: {
                min: 75,
                max: 105,
                ticks: {
                    callback: function (value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Generar las opciones de meses en el formulario
const monthSelectRendimiento = document.getElementById('monthRendimiento');
const monthsr = ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'];
monthsr.forEach((month) => {
    const option = document.createElement('option');
    option.value = month;
    option.textContent = month;
    monthSelectRendimiento.appendChild(option);
});

// Validar y actualizar el gráfico
document.getElementById('dataFormRendimiento').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectRendimiento.value;
    const performance = parseFloat(document.getElementById('performanceRendimiento').value);
    const area = parseFloat(document.getElementById('areaRendimiento').value);

    // Validar que los valores estén dentro del rango
    if (performance < 0 || performance > 100 || area < 0 || area > 100) {
        alert('Los valores deben estar entre 0% y 100%.');
        return;
    }

    // Enviar los datos al servidor
    axios.post('/rendimiento/store', {
        mes: month,
        desempeno: performance,
        area_cumplimiento: area,
    })
    .then(response => {
        if (response.data.success) {
            // Actualizar los datos del gráfico
            const index = monthsr.indexOf(month);
            rendimientoChart.data.datasets[0].data[index] = performance;
            rendimientoChart.data.datasets[1].data[index] = area;
            rendimientoChart.update(); // Actualizar el gráfico
        } else {
            console.error('Error en la respuesta del servidor:', response.data);
        }
    })
    .catch(error => {
        console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
    });
});

// Cargar los datos iniciales al cargar la página
axios.get('/rendimiento/get-data')
    .then(response => {
        const data = response.data;
        data.forEach(item => {
            const index = monthsr.indexOf(item.mes);
            if (index !== -1) {
                rendimientoChart.data.datasets[0].data[index] = item.desempeno;
                rendimientoChart.data.datasets[1].data[index] = item.area_cumplimiento;
            }
        });
        rendimientoChart.update(); // Actualizar el gráfico
    })
    .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
    });
</script>

<br><br>
<!-- Cumplimiento al Plan de Producción -->
<h2>Cumplimiento al Plan de Producción</h2>
<!-- Incluir Axios y Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="productionChart"></canvas>

<h2>Actualizar Datos de Cumplimiento al Plan de Producción</h2>
@can('produccion.update')
<form id="dataFormProduccion">
    <label for="monthProduccion">Mes:</label>
    <select id="monthProduccion" name="monthProduccion"></select><br><br>

    <label for="performanceProduccion">Desempeño (%):</label>
    <input type="number" id="performanceProduccion" name="performanceProduccion" min="0" max="100" step="0.01" required><br><br>

    <label for="areaProduccion">Área de cumplimiento (%):</label>
    <input type="number" id="areaProduccion" name="areaProduccion" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctxProduction = document.getElementById('productionChart').getContext('2d');
    const productionChart = new Chart(ctxProduction, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'],
            datasets: [
                {
                    label: 'Cumplimiento',
                    data: Array(24).fill(null), // Inicializar con valores nulos
                    borderColor: 'red',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    pointBackgroundColor: 'red',
                    fill: false
                },
                {
                    label: 'Meta',
                    data: Array(24).fill(null), // Inicializar con valores nulos
                    borderColor: '#007bff',
                    backgroundColor: '#007bff',
                    tension: 0.1,
                    pointBackgroundColor: '#007bff',
                    fill: false,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (tooltipItem) {
                            return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 92,
                    max: 101,
                    ticks: {
                        callback: function (value) { return value + '%'; }
                    }
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectProduccion = document.getElementById('monthProduccion');
    const monthsproduccion = ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'];
    monthsproduccion.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectProduccion.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('dataFormProduccion').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectProduccion.value;
        const performance = parseFloat(document.getElementById('performanceProduccion').value);
        const area = parseFloat(document.getElementById('areaProduccion').value);

        // Validar que los valores estén dentro del rango
        if (performance < 0 || performance > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/produccion/store', {
            mes: month,
            desempeno: performance,
            area_cumplimiento: area,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = months.indexOf(month);
                productionChart.data.datasets[0].data[index] = performance;
                productionChart.data.datasets[1].data[index] = area;
                productionChart.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/produccion/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = months.indexOf(item.mes);
                if (index !== -1) {
                    productionChart.data.datasets[0].data[index] = item.desempeno;
                    productionChart.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            productionChart.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

<style>
       .button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #45a049;
}
    </style>

@endsection