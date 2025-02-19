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
        background-color:rgb(255, 0, 0); 
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

<h2 class="maspudo">Operacion MM</h2>

 <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

 <script>
    const ctxb = document.getElementById('scrapChart').getContext('2d');
    const scrapChart = new Chart(ctxb, {
        type: 'line',
        data: {
            labels: ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24',  'ago-24',  'sep-24',  'oct-24'],
            datasets: [
                {
                    label: 'SCRAP Forjas',
                    data: [6.0, 6.0, 6.5, 5.5, 5.8, 6.2, 7.5, 6.0, 5.5, 5.7, 5.0, 4.8, 4.5, 4.6, 4.9, 5.1, 5.6, 6.0, 5.97,5.6, 6.0, 5.97],
                    borderColor: 'blue',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'blue',
                },
                {
                    label: 'Meta',
                    data: [10.0, 10.0, 10.0, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5, 7.5],
                    borderColor: 'red',
                    fill: false,
                    tension: 0.1,
                    borderWidth: 2,
                    pointBackgroundColor: 'red',
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                title: {
                    display: true,
                    text: 'SCRAP Forjas'
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: '% SCRAP'
                    }
                }
            }
        }
    });
</script>

<br>
<h2>SCRAP Donaldson</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chart-container">
    <canvas id="scrapChart1"></canvas>
</div>

<!-- Formulario SCRAP Donaldson -->
<form id="formScrap1">
    <label for="monthScrap1">Mes:</label>
    <select id="monthScrap1" name="monthScrap1"></select><br><br>

    <label for="desempeno1">Desempeño (%):</label>
    <input type="number" id="desempeno1" name="desempeno1" min="0" max="100" step="0.01" required><br><br>

    <label for="areaCumplimiento1">Área de Cumplimiento (%):</label>
    <input type="number" id="areaCumplimiento1" name="areaCumplimiento1" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctx1 = document.getElementById('scrapChart1').getContext('2d');
    const scrapChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"],
            datasets: [
                {
                    label: "Scrap %",
                    data: Array(24).fill(null), // Inicializar con valores nulos
                    borderColor: "#007bff",
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Límite",
                    data: Array(24).fill(2.0), // Línea roja fija en 2.0%
                    borderColor: "red",
                    borderWidth: 2,
                    pointRadius: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 3,
                    ticks: {
                        callback: value => value + "%" // Agrega % a los valores del eje Y
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectScrap1 = document.getElementById('monthScrap1');
    const monthsScrap1 = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];
    monthsScrap1.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectScrap1.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('formScrap1').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectScrap1.value;
        const desempeno = parseFloat(document.getElementById('desempeno1').value);
        const areaCumplimiento = parseFloat(document.getElementById('areaCumplimiento1').value);

        // Validar que los valores estén dentro del rango
        if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/scrap-donaldson/store', {
            mes: month,
            desempeno: desempeno,
            area_cumplimiento: areaCumplimiento,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = monthsScrap1.indexOf(month);
                scrapChart1.data.datasets[0].data[index] = desempeno;
                scrapChart1.data.datasets[1].data[index] = areaCumplimiento;
                scrapChart1.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/scrap-donaldson/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = monthsScrap1.indexOf(item.mes);
                if (index !== -1) {
                    scrapChart1.data.datasets[0].data[index] = item.desempeno;
                    scrapChart1.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            scrapChart1.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

<br><br>
<!--SCRAP TALLER-->

<h2>SCRAP Taller</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chart-container">
    <canvas id="scrapChartTaller"></canvas>
</div>

<!-- Formulario SCRAP Taller -->
<form id="formScrapTaller">
    <label for="monthScrapTaller">Mes:</label>
    <select id="monthScrapTaller" name="monthScrapTaller"></select><br><br>

    <label for="desempenoTaller">Desempeño (%):</label>
    <input type="number" id="desempenoTaller" name="desempenoTaller" min="0" max="100" step="0.01" required><br><br>

    <label for="areaCumplimientoTaller">Área de Cumplimiento (%):</label>
    <input type="number" id="areaCumplimientoTaller" name="areaCumplimientoTaller" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctx2 = document.getElementById('scrapChartTaller').getContext('2d');
    const scrapChartTaller = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"],
            datasets: [
                {
                    label: "SCRAP %",
                    data: Array(24).fill(null), // Inicializar con valores nulos
                    borderColor: "#007bff",
                    borderWidth: 2,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Meta",
                    data: Array(24).fill(3), // Línea roja fija en 3%
                    borderColor: "red",
                    borderWidth: 2,
                    pointRadius: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: value => value + "%"
                    }
                }
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' + context.raw.toFixed(2) + '%';
                        }
                    }
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectScrapTaller = document.getElementById('monthScrapTaller');
    const monthsScrapTaller = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];
    monthsScrapTaller.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectScrapTaller.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('formScrapTaller').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectScrapTaller.value;
        const desempeno = parseFloat(document.getElementById('desempenoTaller').value);
        const areaCumplimiento = parseFloat(document.getElementById('areaCumplimientoTaller').value);

        // Validar que los valores estén dentro del rango
        if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/scrap-taller/store', {
            mes: month,
            desempeno: desempeno,
            area_cumplimiento: areaCumplimiento,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = monthsScrapTaller.indexOf(month);
                scrapChartTaller.data.datasets[0].data[index] = desempeno;
                scrapChartTaller.data.datasets[1].data[index] = areaCumplimiento;
                scrapChartTaller.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/scrap-taller/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = monthsScrapTaller.indexOf(item.mes);
                if (index !== -1) {
                    scrapChartTaller.data.datasets[0].data[index] = item.desempeno;
                    scrapChartTaller.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            scrapChartTaller.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

<br><br>
<h2>SCRAP Forjas</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chart-container">
    <canvas id="scrapChart2"></canvas>
</div>

<!-- Formulario SCRAP Forjas -->
<form id="formScrapForjas">
    <label for="monthScrapForjas">Mes:</label>
    <select id="monthScrapForjas" name="monthScrapForjas"></select><br><br>

    <label for="desempenoForjas">Desempeño (%):</label>
    <input type="number" id="desempenoForjas" name="desempenoForjas" min="0" max="100" step="0.01" required><br><br>

    <label for="areaCumplimientoForjas">Área de Cumplimiento (%):</label>
    <input type="number" id="areaCumplimientoForjas" name="areaCumplimientoForjas" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctxbforjas = document.getElementById('scrapChart2').getContext('2d');
    const scrapChartForjas = new Chart(ctxbforjas, { // Cambia ctxb por ctxbforjas
    type: 'line',
    data: {
        labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"],
        datasets: [
            {
                label: "SCRAP %",
                data: Array(24).fill(null), // Inicializar con valores nulos
                borderColor: '#007bff',
                fill: false,
                tension: 0.1,
                borderWidth: 2,
                pointRadius: 4,
                pointBackgroundColor: '#007bff',
            },
            {
                label: "Límite",
                data: Array(24).fill(7.5), // Línea roja fija en 7.5%
                borderColor: 'red',
                fill: false,
                tension: 0.1,
                borderWidth: 2,
                pointBackgroundColor: 'red',
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            title: {
                display: false,  // Ocultar el título
            },
            legend: {
                display: false, // Ocultar la leyenda
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        return context.dataset.label + ': ' + context.raw.toFixed(2) + '%';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: '% SCRAP'
                }
            }
        }
    }
});

    // Generar las opciones de meses en el formulario
    const monthSelectScrapForjas = document.getElementById('monthScrapForjas');
    const monthsScrapForjas = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];
    monthsScrapForjas.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectScrapForjas.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('formScrapForjas').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectScrapForjas.value;
        const desempeno = parseFloat(document.getElementById('desempenoForjas').value);
        const areaCumplimiento = parseFloat(document.getElementById('areaCumplimientoForjas').value);

        // Validar que los valores estén dentro del rango
        if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/scrap-forjas/store', {
            mes: month,
            desempeno: desempeno,
            area_cumplimiento: areaCumplimiento,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = monthsScrapForjas.indexOf(month);
                scrapChartForjas.data.datasets[0].data[index] = desempeno;
                scrapChartForjas.data.datasets[1].data[index] = areaCumplimiento;
                scrapChartForjas.update(); // Actualizar el gráfico
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    axios.get('/scrap-forjas/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = monthsScrapForjas.indexOf(item.mes);
                if (index !== -1) {
                    scrapChartForjas.data.datasets[0].data[index] = item.desempeno;
                    scrapChartForjas.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            scrapChartForjas.update(); // Actualizar el gráfico
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>

<br><br>
<h2>Cumplimiento Plan de Producción Taller</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chart-container">
    <canvas id="produccionChart"></canvas>
</div>

<!-- Formulario Cumplimiento Plan de Producción -->
<form id="formProduccion">
    <label for="monthProduccion">Mes:</label>
    <select id="monthProduccion" name="monthProduccion"></select><br><br>

    <label for="desempenoProduccion">Desempeño (%):</label>
    <input type="number" id="desempenoProduccion" name="desempenoProduccion" min="0" max="100" step="0.01" required><br><br>

    <label for="areaCumplimientoProduccion">Área de Cumplimiento (%):</label>
    <input type="number" id="areaCumplimientoProduccion" name="areaCumplimientoProduccion" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctx3 = document.getElementById('produccionChart').getContext('2d');
    const produccionChart = new Chart(ctx3, {
        type: 'line',
        data: {
            labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"],
            datasets: [
                {
                    label: "Cumplimiento %", 
                    data: Array(24).fill(null), 
                    borderColor: "#007bff",
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Banda roja",
                    data: Array(24).fill(95), 
                    backgroundColor: "rgba(255, 0, 0, 0.97)",
                    borderWidth: 0,
                    fill: true,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: false,
                    max: 100,
                    ticks: {
                        callback: value => value + "%" 
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    const monthSelectProduccion = document.getElementById('monthProduccion');
    const monthsProduccion = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];
    monthsProduccion.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectProduccion.appendChild(option);
    });

    // Validar y actualizar el gráfico
    document.getElementById('formProduccion').addEventListener('submit', (event) => {
        event.preventDefault();

        const month = monthSelectProduccion.value;
        const desempeno = parseFloat(document.getElementById('desempenoProduccion').value);
        const areaCumplimiento = parseFloat(document.getElementById('areaCumplimientoProduccion').value);

        // Validar que los valores estén dentro del rango
        if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor con la ruta actualizada
        axios.post('/cumplimiento-taller/store', {
            mes: month,
            desempeno: desempeno,
            area_cumplimiento: areaCumplimiento,
        })
        .then(response => {
            if (response.data.success) {
                // Actualizar los datos del gráfico
                const index = monthsProduccion.indexOf(month);
                produccionChart.data.datasets[0].data[index] = desempeno;
                produccionChart.data.datasets[1].data[index] = areaCumplimiento;
                produccionChart.update(); 
            } else {
                console.error('Error en la respuesta del servidor:', response.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página con la ruta correcta
    axios.get('/cumplimiento-taller/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = monthsProduccion.indexOf(item.mes);
                if (index !== -1) {
                    produccionChart.data.datasets[0].data[index] = item.desempeno;
                    produccionChart.data.datasets[1].data[index] = item.area_cumplimiento;
                }
            });
            produccionChart.update(); 
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>


<br>
<h2>Cumplimiento al Plan de Producción<br>Maquinados Forjas</h2>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="chart-container">
    <canvas id="forjasProduccionChart"></canvas>
</div>

<!-- Formulario Cumplimiento Plan de Producción -->
<form id="formForjasProduccion">
    <label for="monthForjasProduccion">Mes:</label>
    <select id="monthForjasProduccion" name="monthForjasProduccion"></select><br><br>

    <label for="forjasProduccion">Desempeño (%):</label>
    <input type="number" id="forjasProduccion" name="forjasProduccion" min="0" max="100" step="0.01" required><br><br>

    <label for="forjasCumplimiento">Área de Cumplimiento (%):</label>
    <input type="number" id="forjasCumplimiento" name="forjasCumplimiento" min="0" max="100" step="0.01" required><br><br>

    <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script>
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const ctx = document.getElementById('forjasProduccionChart').getContext('2d');

    const forjasChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23",
                     "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"],
            datasets: [
                {
                    label: "Cumplimiento %",
                    data: Array(24).fill(null), // Inicializar vacío
                    borderColor: "#0095ff",
                    borderWidth: 2,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: "#0095ff",
                },
                {
                    label: "Banda roja",
                    data: Array(24).fill(90), // Línea fija en 90%
                    backgroundColor: "rgb(255, 0, 0)",
                    borderWidth: 0,
                    fill: true,
                    pointRadius: 0
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: false,
                    min: 40,
                    max: 110,
                    ticks: {
                        callback: value => value + "%"
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ": " + tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                },
                legend: {
                    display: false
                }
            },
            elements: {
                line: {
                    borderWidth: 2
                }
            }
        }
    });

    // Llenar el select con los meses
    const monthSelect = document.getElementById('monthForjasProduccion');
    const months = forjasChart.data.labels;
    months.forEach(month => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelect.appendChild(option);
    });

    // Manejar el envío del formulario
    document.getElementById('formForjasProduccion').addEventListener('submit', function(event) {
        event.preventDefault();

        const mes = monthSelect.value;
        const desempeno = parseFloat(document.getElementById('forjasProduccion').value);
        const areaCumplimiento = parseFloat(document.getElementById('forjasCumplimiento').value);

        if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        axios.post('/forjas-produccion/store', { mes, desempeno, area_cumplimiento: areaCumplimiento })
            .then(response => {
                if (response.data.success) {
                    const index = months.indexOf(mes);
                    if (index !== -1) {
                        forjasChart.data.datasets[0].data[index] = desempeno;
                        forjasChart.update();
                    }
                } else {
                    console.error('Error en la respuesta del servidor:', response.data);
                }
            })
            .catch(error => {
                console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
            });
    });

    // Cargar datos del servidor
    axios.get('/forjas-produccion/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = months.indexOf(item.mes);
                if (index !== -1) {
                    forjasChart.data.datasets[0].data[index] = item.desempeno;
                }
            });
            forjasChart.update();
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });
</script>


@endsection
