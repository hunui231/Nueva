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
<canvas id="scrapChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

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

    // Datos iniciales del gráfico 1
    const ctxScrap = document.getElementById('scrapChart').getContext('2d');
    const ctxScrap2 = document.getElementById('scrapChart2').getContext('2d');

    const months = ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'];
    let performanceDataScrap1 = Array(24).fill(null); // Datos iniciales para el gráfico 1
    let areaDataScrap1 = Array(24).fill(null); // Datos iniciales para el gráfico 1
    const months2 = ['ene-25', 'feb-25', 'mar-25', 'abr-25', 'may-25', 'jun-25', 'jul-25', 'ago-25', 'sep-25', 'oct-25', 'nov-25', 'dic-25'];
    let performanceDataScrap2 = Array(24).fill(null); // Datos iniciales para el gráfico 2
    let areaDataScrap2 = Array(24).fill(null); // Datos iniciales para el gráfico 2

    // Configuración del gráfico 1
    const scrapChart = new Chart(ctxScrap, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Desempeño',
                    data: performanceDataScrap1,
                    borderColor: '#007bff',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Área de cumplimiento',
                    data: areaDataScrap1,
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

    // Configuración del gráfico 2
    const scrapChart2 = new Chart(ctxScrap2, {
        type: 'line',
        data: {
            labels: months2,
            datasets: [
                {
                    label: 'Desempeño',
                    data: performanceDataScrap2,
                    borderColor: '#007bff',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3
                },
                {
                    label: 'Área de cumplimiento',
                    data: areaDataScrap2,
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
                // Actualizar los datos del gráfico activo
                const index = months.indexOf(month);
                if (currentScrapChart === 1) {
                    scrapChart.data.datasets[0].data[index] = performance;
                    scrapChart.data.datasets[1].data[index] = area;
                    scrapChart.update();
                } else {
                    scrapChart2.data.datasets[0].data[index] = performance;
                    scrapChart2.data.datasets[1].data[index] = area;
                    scrapChart2.update();
                }
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

    // Alternar entre gráficos
    let currentScrapChart = 1;
    document.getElementById('nextChart').addEventListener('click', () => {
        if (currentScrapChart === 1) {
            document.getElementById('scrapChart').style.display = 'none';
            document.getElementById('scrapChart2').style.display = 'block';
            currentScrapChart = 2;
        } else {
            document.getElementById('scrapChart').style.display = 'block';
            document.getElementById('scrapChart2').style.display = 'none';
            currentScrapChart = 1;
        }
    });

    document.getElementById('prevChart').addEventListener('click', () => {
        if (currentScrapChart === 1) {
            document.getElementById('scrapChart').style.display = 'none';
            document.getElementById('scrapChart2').style.display = 'block';
            currentScrapChart = 2;
        } else {
            document.getElementById('scrapChart').style.display = 'block';
            document.getElementById('scrapChart2').style.display = 'none';
            currentScrapChart = 1;
        }
    });
</script>
<br><br>

<!-- Rendimiento Operacional CI -->
<!-- Rendimiento Operacional CI -->
<h2>Rendimiento Operacional CI</h2>
<canvas id="rendimientoChart"></canvas>
<canvas id="rendimientoChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartRendimiento" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartRendimiento" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Datos iniciales del gráfico 1
  const ctxRendimiento = document.getElementById('rendimientoChart').getContext('2d');
  const ctxRendimiento2 = document.getElementById('rendimientoChart2').getContext('2d');

  const monthsr = [
    'ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23',
    'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'
  ];

  const monthsr2 = [
    'ene-25', 'feb-25', 'mar-25', 'abr-25', 'may-25', 'jun-25', 'jul-25', 'ago-25', 'sep-25', 'oct-25', 'nov-25', 'dic-25'
  ];

  let desempenoData = Array(24).fill(null); // Datos iniciales para el gráfico 1
  let areaData = Array(24).fill(null); // Datos iniciales para el gráfico 1

  let desempenoData2 = Array(12).fill(null); // Datos iniciales para el gráfico 2
  let areaData2 = Array(12).fill(null); // Datos iniciales para el gráfico 2

  // Configuración del gráfico 1
  const rendimientoChart = new Chart(ctxRendimiento, {
    type: 'line',
    data: {
        labels: monthsr,
        datasets: [
            {
                label: 'Desempeño',
                data: desempenoData,
                borderColor: '#007bff',
                backgroundColor: '#007bff',
                fill: false,
                tension: 0.3
            },
            {
                label: 'Área de cumplimiento',
                data: areaData,
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

  // Configuración del gráfico 2
  const rendimientoChart2 = new Chart(ctxRendimiento2, {
    type: 'line',
    data: {
        labels: monthsr2,
        datasets: [
            {
                label: 'Desempeño',
                data: desempenoData2,
                borderColor: '#007bff',
                backgroundColor: '#007bff',
                fill: false,
                tension: 0.3
            },
            {
                label: 'Área de cumplimiento',
                data: areaData2,
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

  // Alternar entre gráficos
  let currentChartRendimiento = 1;
  document.getElementById('nextChartRendimiento').addEventListener('click', () => {
    if (currentChartRendimiento === 1) {
      document.getElementById('rendimientoChart').style.display = 'none';
      document.getElementById('rendimientoChart2').style.display = 'block';
      currentChartRendimiento = 2;
    } else {
      document.getElementById('rendimientoChart').style.display = 'block';
      document.getElementById('rendimientoChart2').style.display = 'none';
      currentChartRendimiento = 1;
    }
  });

  document.getElementById('prevChartRendimiento').addEventListener('click', () => {
    if (currentChartRendimiento === 1) {
      document.getElementById('rendimientoChart').style.display = 'none';
      document.getElementById('rendimientoChart2').style.display = 'block';
      currentChartRendimiento = 2;
    } else {
      document.getElementById('rendimientoChart').style.display = 'block';
      document.getElementById('rendimientoChart2').style.display = 'none';
      currentChartRendimiento = 1;
    }
  });
</script>

<br><br>
<!-- Cumplimiento al Plan de Producción -->
<h2>Cumplimiento al Plan de Producción</h2>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="productionChart"></canvas>
<canvas id="productionChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartProduccion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartProduccion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

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

    // Datos iniciales del gráfico 1
    const ctxProduction = document.getElementById('productionChart').getContext('2d');
    const ctxProduction2 = document.getElementById('productionChart2').getContext('2d');

    const monthsproduccion = [
        'ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23',
        'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'
    ];

    const monthsproduccion2 = [
        'ene-25', 'feb-25', 'mar-25', 'abr-25', 'may-25', 'jun-25', 'jul-25', 'ago-25', 'sep-25', 'oct-25', 'nov-25', 'dic-25'
    ];

    let cumplimientoData = Array(24).fill(null); // Datos iniciales para el gráfico 1
    let metaData = Array(24).fill(null); // Datos iniciales para el gráfico 1

    let cumplimientoData2 = Array(12).fill(null); // Datos iniciales para el gráfico 2
    let metaData2 = Array(12).fill(null); // Datos iniciales para el gráfico 2

    // Configuración del gráfico 1
    const productionChart = new Chart(ctxProduction, {
        type: 'line',
        data: {
            labels: monthsproduccion,
            datasets: [
                {
                    label: 'Cumplimiento',
                    data: cumplimientoData,
                    borderColor: 'red',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    pointBackgroundColor: 'red',
                    fill: false
                },
                {
                    label: 'Meta',
                    data: metaData,
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

    // Configuración del gráfico 2
    const productionChart2 = new Chart(ctxProduction2, {
        type: 'line',
        data: {
            labels: monthsproduccion2,
            datasets: [
                {
                    label: 'Cumplimiento',
                    data: cumplimientoData2,
                    borderColor: 'red',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    pointBackgroundColor: 'red',
                    fill: false
                },
                {
                    label: 'Meta',
                    data: metaData2,
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
                const index = monthsproduccion.indexOf(month);
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
                const index = monthsproduccion.indexOf(item.mes);
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

    // Alternar entre gráficos
    let currentChartProduccion = 1;
    document.getElementById('nextChartProduccion').addEventListener('click', () => {
        if (currentChartProduccion === 1) {
            document.getElementById('productionChart').style.display = 'none';
            document.getElementById('productionChart2').style.display = 'block';
            currentChartProduccion = 2;
        } else {
            document.getElementById('productionChart').style.display = 'block';
            document.getElementById('productionChart2').style.display = 'none';
            currentChartProduccion = 1;
        }
    });

    document.getElementById('prevChartProduccion').addEventListener('click', () => {
        if (currentChartProduccion === 1) {
            document.getElementById('productionChart').style.display = 'none';
            document.getElementById('productionChart2').style.display = 'block';
            currentChartProduccion = 2;
        } else {
            document.getElementById('productionChart').style.display = 'block';
            document.getElementById('productionChart2').style.display = 'none';
            currentChartProduccion = 1;
        }
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