@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
<style>
    
  .maspudo{
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        color: white;
        background-color:rgb(250, 0, 0); 
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        transition: transform 0.3s; 
    }
    .maspudo2{
        font-size: 24px;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        color: white;
        background-color:rgb(18, 136, 247); 
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        transition: transform 0.3s; 
    }
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
</style>
<h2 class="maspudo2"> INDICADORES RH CPA </h2>
   <br><br>

<!-- Rotación de Personal CI -->
<h1>Rotación de Personal CI</h1>
<!-- Incluir Axios y Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="rotacionChart"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Rotación de Personal CI</h2>
<form id="dataFormRotacion">
    <label for="monthRotacion">Mes:</label>
    <select id="monthRotacion" name="monthRotacion"></select><br><br>

    <label for="performanceRotacion">Desempeño (%):</label>
    <input type="number" id="performanceRotacion" name="performanceRotacion" min="0" max="100" step="0.01" required><br><br>

    <label for="areaRotacion">Área de cumplimiento (%):</label>
    <input type="number" id="areaRotacion" name="areaRotacion" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctxRotacion = document.getElementById('rotacionChart').getContext('2d');
    const rotacionChart = new Chart(ctxRotacion, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'],
            datasets: [
                {
                    label: 'Rotación (%)',
                    data: Array(24).fill(null), // Inicializar con valores nulos
                    borderColor: '#007bff',
                    backgroundColor: '#007bff',
                    fill: false,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: '#007bff'
                },
                {
                    label: 'Meta',
                    data: Array(24).fill(4.5), // Línea media sin leyenda
                    borderColor: 'red',
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 0.1
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
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) { return value + '%'; }
                    }
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectRotacion = document.getElementById('monthRotacion');
    const months = ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'];
    months.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectRotacion.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('dataFormRotacion').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectRotacion.value;
        const performance = parseFloat(document.getElementById('performanceRotacion').value);
        const area = parseFloat(document.getElementById('areaRotacion').value);

        // Validar que los valores estén dentro del rango
        if (performance < 0 || performance > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/rotacion/store', {
            mes: month,
            desempeno: performance,
            area_cumplimiento: area,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = months.indexOf(month);
                rotacionChart.data.datasets[0].data[index] = performance;
                rotacionChart.data.datasets[1].data[index] = area;
                rotacionChart.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/rotacion/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = months.indexOf(item.mes);
                if (index !== -1) {
                    rotacionChart.data.datasets[0].data[index] = item.desempeno;
                    rotacionChart.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            rotacionChart.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

<br><br>

<h1>Permanencia Personal Reclutado CI</h1>
<canvas id="permanenciaChart"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Permanencia de Personal Reclutado CI</h2>
<form id="dataFormPermanencia">
    <label for="monthPermanencia">Mes:</label>
    <select id="monthPermanencia" name="monthPermanencia"></select><br><br>

    <label for="performancePermanencia">Desempeño (%):</label>
    <input type="number" id="performancePermanencia" name="performancePermanencia" min="0" max="100" step="0.01" required><br><br>

    <label for="areaPermanencia">Área de cumplimiento (%):</label>
    <input type="number" id="areaPermanencia" name="areaPermanencia" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctxPermanencia = document.getElementById('permanenciaChart').getContext('2d');
    const permanenciaChart = new Chart(ctxPermanencia, {
        type: 'line',
        data: {
            labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"],
            datasets: [
                {
                    label: "Permanencia (%)",
                    data: Array(13).fill(null), // Inicializar con valores nulos
                    borderColor: "#007bff",
                    backgroundColor: "#007bff",
                    fill: false,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Meta",
                    data: Array(13).fill(85), // Línea media sin leyenda
                    borderColor: "red",
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 0.1
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
                            return tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 120,
                    ticks: {
                        callback: function (value) { return value + "%"; }
                    }
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectPermanencia = document.getElementById('monthPermanencia');
    const monthspermanencia = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"
];
    monthspermanencia.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectPermanencia.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('dataFormPermanencia').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectPermanencia.value;
        const performance = parseFloat(document.getElementById('performancePermanencia').value);
        const area = parseFloat(document.getElementById('areaPermanencia').value);

        // Validar que los valores estén dentro del rango
        if (performance < 0 || performance > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/permanencia/store', {
            mes: month,
            desempeno: performance,
            area_cumplimiento: area,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = months.indexOf(month);
                permanenciaChart.data.datasets[0].data[index] = performance;
                permanenciaChart.data.datasets[1].data[index] = area;
                permanenciaChart.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/permanencia/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = months.indexOf(item.mes);
                if (index !== -1) {
                    permanenciaChart.data.datasets[0].data[index] = item.desempeno;
                    permanenciaChart.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            permanenciaChart.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

<br><br>

<h2 class="maspudo"> INDICADORES RH GIC </h2>

<br><br>

<h1>Rotación de Personal GIC</h1>
<canvas id="rotationChartGIC"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Rotación de Personal GIC</h2>
<form id="dataFormRotacionGIC">
    <label for="monthRotacionGIC">Mes:</label>
    <select id="monthRotacionGIC" name="monthRotacionGIC"></select><br><br>

    <label for="performanceRotacionGIC">Desempeño (%):</label>
    <input type="number" id="performanceRotacionGIC" name="performanceRotacionGIC" min="0" max="100" step="0.01" required><br><br>

    <label for="areaRotacionGIC">Área de cumplimiento (%):</label>
    <input type="number" id="areaRotacionGIC" name="areaRotacionGIC" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctxRotationGIC = document.getElementById('rotationChartGIC').getContext('2d');
    const rotationChartGIC = new Chart(ctxRotationGIC, {
        type: 'line',
        data: {
            labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"],
            datasets: [
                {
                    label: "Rotación (%)",
                    data: Array(12).fill(null), // Inicializar con valores nulos
                    borderColor: "#007bff",
                    backgroundColor: "#007bff",
                    fill: false,
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Referencia",
                    data: Array(12).fill(5), // Línea de referencia
                    borderColor: "#FF0000",
                    borderWidth: 2,
                    fill: false,
                    pointRadius: 0
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
                            return tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 18,
                    title: { display: true, text: "% de Rotación" }
                },
                x: {
                    title: { display: true, text: "Meses" }
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectRotacionGIC = document.getElementById('monthRotacionGIC');
    const monthsGIC = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"
];
    monthsGIC.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectRotacionGIC.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('dataFormRotacionGIC').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectRotacionGIC.value;
        const performance = parseFloat(document.getElementById('performanceRotacionGIC').value);
        const area = parseFloat(document.getElementById('areaRotacionGIC').value);

        // Validar que los valores estén dentro del rango
        if (performance < 0 || performance > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/rotacion-gic/store', {
            mes: month,
            desempeno: performance,
            area_cumplimiento: area,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = monthsGIC.indexOf(month);
                rotationChartGIC.data.datasets[0].data[index] = performance;
                rotationChartGIC.data.datasets[1].data[index] = area;
                rotationChartGIC.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/rotacion-gic/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = monthsGIC.indexOf(item.mes);
                if (index !== -1) {
                    rotationChartGIC.data.datasets[0].data[index] = item.desempeno;
                    rotationChartGIC.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            rotationChartGIC.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

<br><br>
<h1>Permanencia Personal Reclutado GIC</h1>
<canvas id="permanenceChartGIC"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>
<h2>Actualizar Datos de Permanencia de Personal Reclutado GIC</h2>
<form id="dataFormPermanenciaGIC">
    <label for="monthPermanenciaGIC">Mes:</label>
    <select id="monthPermanenciaGIC" name="monthPermanenciaGIC"></select><br><br>

    <label for="performancePermanenciaGIC">Desempeño (%):</label>
    <input type="number" id="performancePermanenciaGIC" name="performancePermanenciaGIC" min="0" max="100" step="0.01" required><br><br>

    <label for="areaPermanenciaGIC">Área de cumplimiento (%):</label>
    <input type="number" id="areaPermanenciaGIC" name="areaPermanenciaGIC" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctxPermanenceGIC = document.getElementById('permanenceChartGIC').getContext('2d');
    const permanenceChartGIC = new Chart(ctxPermanenceGIC, {
        type: 'line',
        data: {
            labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"
],
            datasets: [
                {
                    label: "Permanencia (%)",
                    data: Array(12).fill(null), // Inicializar con valores nulos
                    borderColor: "#007bff",
                    backgroundColor: "#007bff",
                    fill: false,
                    tension: 0.1,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Fondo",
                    data: Array(12).fill(100), // Línea de fondo
                    borderColor: "red",
                    backgroundColor: "red", // Fondo rojo semitransparente
                    fill: true,
                    borderWidth: 0,
                    pointRadius: 0
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
                            return tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                }
            },
            scales: {
                y: {
                    min: 0,
                    max: 120,
                    title: { display: true, text: "% de Permanencia" }
                },
                x: {
                    title: { display: true, text: "Meses" }
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectPermanenciaGIC = document.getElementById('monthPermanenciaGIC');
    const monthsGICpermanencia = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"
];
    monthsGICpermanencia.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectPermanenciaGIC.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('dataFormPermanenciaGIC').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectPermanenciaGIC.value;
        const performance = parseFloat(document.getElementById('performancePermanenciaGIC').value);
        const area = parseFloat(document.getElementById('areaPermanenciaGIC').value);

        // Validar que los valores estén dentro del rango
        if (performance < 0 || performance > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/permanencia-gic/store', {
            mes: month,
            desempeno: performance,
            area_cumplimiento: area,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = monthsGIC.indexOf(month);
                permanenceChartGIC.data.datasets[0].data[index] = performance;
                permanenceChartGIC.data.datasets[1].data[index] = area;
                permanenceChartGIC.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/permanencia-gic/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = monthsGIC.indexOf(item.mes);
                if (index !== -1) {
                    permanenceChartGIC.data.datasets[0].data[index] = item.desempeno;
                    permanenceChartGIC.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            permanenceChartGIC.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

@endsection