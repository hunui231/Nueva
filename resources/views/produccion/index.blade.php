@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
<style>
  .box-title {
    font-size: 28px;
    font-weight: bold;
    text-align: center;
    background:rgb(46, 180, 204);
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    display: inline-block;
  }
</style>
<h2 class="box-title">Producción SCRAP CI</h2>
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

    // Función para generar opciones de meses y años
    function generarOpcionesMesesScrap(anioInicio, anioFin) {
        const meses = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
        let opciones = [];

        for (let anio = anioInicio; anio <= anioFin; anio++) {
            meses.forEach((mes) => {
                opciones.push(`${mes}-${anio.toString().slice(-2)}`);
            });
        }

        return opciones;
    }

    // Generar las etiquetas de los meses
    const anioActualScrap = new Date().getFullYear();
    const dataLabelsScrap = generarOpcionesMesesScrap(23, 24); // Genera desde 2023 hasta 2024
    const dataLabelsScrap2 = generarOpcionesMesesScrap(25, 25); // Genera solo 2025

    // Datos iniciales para los gráficos
    let desempenoDataScrap1 = Array(24).fill(null); // Datos iniciales para el gráfico 1
    let areaDataScrap1 = Array(24).fill(null); // Datos iniciales para el gráfico 1

    let desempenoDataScrap2 = Array(12).fill(1.0); // Datos iniciales para el gráfico 2
    let areaDataScrap2 = Array(12).fill(3.5); // Datos iniciales para el gráfico 2

    // Configuración del gráfico 1
    const scrapChart = new Chart(document.getElementById('scrapChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dataLabelsScrap,
            datasets: [
                {
                    label: 'Desempeño',
                    data: desempenoDataScrap1,
                    borderColor: '#007bff',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
                },
                {
                    label: 'Área de cumplimiento',
                    data: areaDataScrap1,
                    borderColor: '#ff0000',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1,
                    spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
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
    const scrapChart2 = new Chart(document.getElementById('scrapChart2').getContext('2d'), {
        type: 'line',
        data: {
            labels: dataLabelsScrap2,
            datasets: [
                {
                    label: 'Desempeño',
                    data: desempenoDataScrap2,
                    borderColor: '#007bff',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.3,
                    spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
                },
                {
                    label: 'Área de cumplimiento',
                    data: areaDataScrap2,
                    borderColor: '#ff0000',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.1,
                    spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
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
    const selectorMesScrap = document.getElementById('monthScrap');
    dataLabelsScrap.concat(dataLabelsScrap2).forEach((etiqueta) => {
        const opcion = document.createElement('option');
        opcion.value = etiqueta;
        opcion.textContent = etiqueta;
        selectorMesScrap.appendChild(opcion);
    });

    // Obtener la fecha actual
    const fechaActual = new Date();

    // Formatear el mes abreviado (ej: "Feb")
    const mesActual = fechaActual.toLocaleString('default', { month: 'short' }).toLowerCase();

    // Formatear el año en dos dígitos (ej: "25")
    const anioActual = fechaActual.getFullYear().toString().slice(-2);

    // Crear el formato "MMM-AA" (ej: "Feb-25")
    const mesAnioActual = `${mesActual}-${anioActual}`;

    // Establecer el valor predeterminado como el mes actual
    selectorMesScrap.value = mesAnioActual;

    // Validar y actualizar el gráfico
    document.getElementById('dataFormScrap').addEventListener('submit', (evento) => {
        evento.preventDefault();

        const mesSeleccionado = selectorMesScrap.value;
        const desempeno = parseFloat(document.getElementById('performanceScrap').value);
        const area = parseFloat(document.getElementById('areaScrap').value);

        // Validar que los valores estén dentro del rango
        if (desempeno < 0 || desempeno > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/scrap/store', {
            mes: mesSeleccionado,
            desempeno: desempeno,
            area_cumplimiento: area,
        })
        .then(respuesta => {
            if (respuesta.data.success) {
                // Actualizar los datos del gráfico activo
                const indice = dataLabelsScrap.indexOf(mesSeleccionado);
                if (currentScrapChart === 1) {
                    desempenoDataScrap1[indice] = desempeno;
                    areaDataScrap1[indice] = area;
                    scrapChart.update();
                } else {
                    const indice2 = dataLabelsScrap2.indexOf(mesSeleccionado);
                    if (indice2 !== -1) {
                        desempenoDataScrap2[indice2] = desempeno;
                        areaDataScrap2[indice2] = area;
                        scrapChart2.update();
                    }
                }
            } else {
                console.error('Error en la respuesta del servidor:', respuesta.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    function obtenerDatosScrap() {
        axios.get('/scrap/get-data')
            .then(respuesta => {
                const datos = respuesta.data;
                datos.forEach(item => {
                    const indice = dataLabelsScrap.indexOf(item.mes);
                    if (indice !== -1) {
                        desempenoDataScrap1[indice] = item.desempeno;
                        areaDataScrap1[indice] = item.area_cumplimiento;
                    } else {
                        const indice2 = dataLabelsScrap2.indexOf(item.mes);
                        if (indice2 !== -1) {
                            desempenoDataScrap2[indice2] = item.desempeno;
                            areaDataScrap2[indice2] = item.area_cumplimiento;
                        }
                    }
                });
                scrapChart.update(); // Actualizar el gráfico 1
                scrapChart2.update(); // Actualizar el gráfico 2
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
            });
    }

    obtenerDatosScrap();

    // Alternar entre gráficos
    let graficoActualScrap = 1;
    document.getElementById('nextChart').addEventListener('click', () => {
        if (graficoActualScrap === 1) {
            document.getElementById('scrapChart').style.display = 'none';
            document.getElementById('scrapChart2').style.display = 'block';
            graficoActualScrap = 2;
        } else {
            document.getElementById('scrapChart').style.display = 'block';
            document.getElementById('scrapChart2').style.display = 'none';
            graficoActualScrap = 1;
        }
    });

    document.getElementById('prevChart').addEventListener('click', () => {
        if (graficoActualScrap === 1) {
            document.getElementById('scrapChart').style.display = 'none';
            document.getElementById('scrapChart2').style.display = 'block';
            graficoActualScrap = 2;
        } else {
            document.getElementById('scrapChart').style.display = 'block';
            document.getElementById('scrapChart2').style.display = 'none';
            graficoActualScrap = 1;
        }
    });
</script>
<br><br>

<h2 class="box-title">Rendimiento Operacional CI</h2>
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

  // Función para generar opciones de meses y años
  function generarOpcionesMesesRendimiento(anioInicioRend, anioFinRend) {
    const mesesRend = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    let opcionesRend = [];

    for (let anio = anioInicioRend; anio <= anioFinRend; anio++) {
      mesesRend.forEach((mes) => {
        opcionesRend.push(`${mes}-${anio.toString().slice(-2)}`);
      });
    }

    return opcionesRend;
  }

  // Generar las etiquetas de los meses
  const anioActualRend = new Date().getFullYear();
  const dataLabelsRend = generarOpcionesMesesRendimiento(23, 24); // Genera desde 2023 hasta 2024
  const dataLabelsRend2 = generarOpcionesMesesRendimiento(25, 25); // Genera solo 2025

  // Datos iniciales para los gráficos
  let desempenoDataRend = Array(24).fill(null); // Datos iniciales para el gráfico 1
  let areaDataRend = Array(24).fill(null); // Datos iniciales para el gráfico 1

  let desempenoDataRend2 = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let areaDataRend2 = Array(12).fill(0); // Datos iniciales para el gráfico 2

  
  const rendimientoChart = new Chart(document.getElementById('rendimientoChart').getContext('2d'), {
    type: 'line',
    data: {
      labels: dataLabelsRend,
      datasets: [
        {
          label: 'Desempeño',
          data: desempenoDataRend,
          borderColor: '#007bff',
          backgroundColor: '#007bff',
          fill: false,
          tension: 0.3,
          spanGaps: true, 
        },
        {
          label: 'Área de cumplimiento',
          data: areaDataRend,
          borderColor: 'red',
          backgroundColor: 'red',
          fill: false,
          borderWidth: 2,
          tension: 0.1,
          spanGaps: true, 
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
  const rendimientoChart2 = new Chart(document.getElementById('rendimientoChart2').getContext('2d'), {
    type: 'line',
    data: {
      labels: dataLabelsRend2,
      datasets: [
        {
          label: 'Desempeño',
          data: desempenoDataRend2,
          borderColor: '#007bff',
          backgroundColor: '#007bff',
          fill: false,
          tension: 0.3,
          spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
        },
        {
          label: 'Área de cumplimiento',
          data: areaDataRend2,
          borderColor: 'red',
          backgroundColor: 'red',
          fill: false,
          borderWidth: 2,
          tension: 0.1,
          spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
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
  const selectorMesRend = document.getElementById('monthRendimiento');
  dataLabelsRend.concat(dataLabelsRend2).forEach((etiqueta) => {
    const opcion = document.createElement('option');
    opcion.value = etiqueta;
    opcion.textContent = etiqueta;
    selectorMesRend.appendChild(opcion);
  });

  // Obtener la fecha actual
  const fechaActualRend = new Date();

  // Formatear el mes abreviado (ej: "Feb")
  const mesActualRend = fechaActualRend.toLocaleString('default', { month: 'short' }).toLowerCase();

  // Formatear el año en dos dígitos (ej: "25")
  const anioActualRendu = fechaActualRend.getFullYear().toString().slice(-2);

  // Crear el formato "MMM-AA" (ej: "Feb-25")
  const mesAnioActualRend = `${mesActualRend}-${anioActualRendu}`;

  // Establecer el valor predeterminado como el mes actual
  selectorMesRend.value = mesAnioActualRend;

  // Validar y actualizar el gráfico
  document.getElementById('dataFormRendimiento').addEventListener('submit', (evento) => {
    evento.preventDefault();

    const mesSeleccionado = selectorMesRend.value;
    const desempeno = parseFloat(document.getElementById('performanceRendimiento').value);
    const area = parseFloat(document.getElementById('areaRendimiento').value);

    // Validar que los valores estén dentro del rango
    if (desempeno < 0 || desempeno > 100 || area < 0 || area > 100) {
      alert('Los valores deben estar entre 0% y 100%.');
      return;
    }

    // Enviar los datos al servidor
    axios.post('/rendimiento/store', {
      mes: mesSeleccionado,
      desempeno: desempeno,
      area_cumplimiento: area,
    })
    .then(respuesta => {
      if (respuesta.data.success) {
        // Actualizar los datos del gráfico activo
        const indice = dataLabelsRend.indexOf(mesSeleccionado);
        if (currentChartRendimiento === 1) {
          desempenoDataRend[indice] = desempeno;
          areaDataRend[indice] = area;
          rendimientoChart.update();
        } else {
          const indice2 = dataLabelsRend2.indexOf(mesSeleccionado);
          if (indice2 !== -1) {
            desempenoDataRend2[indice2] = desempeno;
            areaDataRend2[indice2] = area;
            rendimientoChart2.update();
          }
        }
      } else {
        console.error('Error en la respuesta del servidor:', respuesta.data);
      }
    })
    .catch(error => {
      console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
    });
  });

  // Cargar los datos iniciales al cargar la página
  function obtenerDatosRendimiento() {
    axios.get('/rendimiento/get-data')
      .then(respuesta => {
        const datos = respuesta.data;
        datos.forEach(item => {
          const indice = dataLabelsRend.indexOf(item.mes);
          if (indice !== -1) {
            desempenoDataRend[indice] = item.desempeno;
            areaDataRend[indice] = item.area_cumplimiento;
          } else {
            const indice2 = dataLabelsRend2.indexOf(item.mes);
            if (indice2 !== -1) {
              desempenoDataRend2[indice2] = item.desempeno;
              areaDataRend2[indice2] = item.area_cumplimiento;
            }
          }
        });
        rendimientoChart.update(); // Actualizar el gráfico 1
        rendimientoChart2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  obtenerDatosRendimiento();

  // Alternar entre gráficos
  let graficoActualRendimiento = 1;
  document.getElementById('nextChartRendimiento').addEventListener('click', () => {
    if (graficoActualRendimiento === 1) {
      document.getElementById('rendimientoChart').style.display = 'none';
      document.getElementById('rendimientoChart2').style.display = 'block';
      graficoActualRendimiento = 2;
    } else {
      document.getElementById('rendimientoChart').style.display = 'block';
      document.getElementById('rendimientoChart2').style.display = 'none';
      graficoActualRendimiento = 1;
    }
  });

  document.getElementById('prevChartRendimiento').addEventListener('click', () => {
    if (graficoActualRendimiento === 1) {
      document.getElementById('rendimientoChart').style.display = 'none';
      document.getElementById('rendimientoChart2').style.display = 'block';
      graficoActualRendimiento = 2;
    } else {
      document.getElementById('rendimientoChart').style.display = 'block';
      document.getElementById('rendimientoChart2').style.display = 'none';
      graficoActualRendimiento = 1;
    }
  });
</script>
<br><br>
<!-- Cumplimiento al Plan de Producción -->
<h2 class="box-title">Cumplimiento al Plan de Producción</h2>
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

    // Función para generar opciones de meses y años
    function generarOpcionesMesesProduccion(anioInicioProd, anioFinProd) {
        const mesesProd = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
        let opcionesProd = [];

        for (let anio = anioInicioProd; anio <= anioFinProd; anio++) {
            mesesProd.forEach((mes) => {
                opcionesProd.push(`${mes}-${anio.toString().slice(-2)}`);
            });
        }

        return opcionesProd;
    }

    // Generar las etiquetas de los meses
    const anioActualProd = new Date().getFullYear();
    const dataLabelsProduccion = generarOpcionesMesesProduccion(23, 24); // Genera desde 2023 hasta 2024
    const dataLabelsProduccion2 = generarOpcionesMesesProduccion(25, 25); // Genera solo 2025

    // Datos iniciales para los gráficos
    let cumplimientoDataProd = Array(24).fill(null); // Datos iniciales para el gráfico 1
    let metaDataProd = Array(24).fill(null); // Datos iniciales para el gráfico 1

    let cumplimientoDataProd2 = Array(12).fill(0); // Datos iniciales para el gráfico 2
    let metaDataProd2 = Array(12).fill(0); // Datos iniciales para el gráfico 2

    // Configuración del gráfico 1
    const productionChart = new Chart(document.getElementById('productionChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dataLabelsProduccion,
            datasets: [
                {
                    label: 'Cumplimiento',
                    data: cumplimientoDataProd,
                    borderColor: 'red',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    pointBackgroundColor: 'red',
                    fill: false,
                    spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
                },
                {
                    label: 'Meta',
                    data: metaDataProd,
                    borderColor: '#007bff',
                    backgroundColor: '#007bff',
                    tension: 0.1,
                    pointBackgroundColor: '#007bff',
                    fill: false,
                    spanGaps: true, 
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
    const productionChart2 = new Chart(document.getElementById('productionChart2').getContext('2d'), {
        type: 'line',
        data: {
            labels: dataLabelsProduccion2,
            datasets: [
                {
                    label: 'Cumplimiento',
                    data: cumplimientoDataProd2,
                    borderColor: 'red',
                    backgroundColor: 'transparent',
                    tension: 0.3,
                    pointBackgroundColor: 'red',
                    fill: false,
                    spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
                },
                {
                    label: 'Meta',
                    data: metaDataProd2,
                    borderColor: '#007bff',
                    backgroundColor: '#007bff',
                    tension: 0.1,
                    pointBackgroundColor: '#007bff',
                    fill: false,
                    spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
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
    const selectorMesProduccion = document.getElementById('monthProduccion');
    dataLabelsProduccion.concat(dataLabelsProduccion2).forEach((etiqueta) => {
        const opcion = document.createElement('option');
        opcion.value = etiqueta;
        opcion.textContent = etiqueta;
        selectorMesProduccion.appendChild(opcion);
    });

    // Obtener la fecha actual
    const fechaActualProd = new Date();

    // Formatear el mes abreviado (ej: "Feb")
    const mesActualProd = fechaActualProd.toLocaleString('default', { month: 'short' }).toLowerCase();

    // Formatear el año en dos dígitos (ej: "25")
    const anioActualProdt = fechaActualProd.getFullYear().toString().slice(-2);

    // Crear el formato "MMM-AA" (ej: "Feb-25")
    const mesAnioActualProd = `${mesActualProd}-${anioActualProdt}`;

    // Establecer el valor predeterminado como el mes actual
    selectorMesProduccion.value = mesAnioActualProd;

    // Validar y actualizar el gráfico
    document.getElementById('dataFormProduccion').addEventListener('submit', (evento) => {
        evento.preventDefault();

        const mesSeleccionado = selectorMesProduccion.value;
        const desempeno = parseFloat(document.getElementById('performanceProduccion').value);
        const area = parseFloat(document.getElementById('areaProduccion').value);

        // Validar que los valores estén dentro del rango
        if (desempeno < 0 || desempeno > 100 || area < 0 || area > 100) {
            alert('Los valores deben estar entre 0% y 100%.');
            return;
        }

        // Enviar los datos al servidor
        axios.post('/produccion/store', {
            mes: mesSeleccionado,
            desempeno: desempeno,
            area_cumplimiento: area,
        })
        .then(respuesta => {
            if (respuesta.data.success) {
                // Actualizar los datos del gráfico activo
                const indice = dataLabelsProduccion.indexOf(mesSeleccionado);
                if (currentChartProduccion === 1) {
                    cumplimientoDataProd[indice] = desempeno;
                    metaDataProd[indice] = area;
                    productionChart.update();
                } else {
                    const indice2 = dataLabelsProduccion2.indexOf(mesSeleccionado);
                    if (indice2 !== -1) {
                        cumplimientoDataProd2[indice2] = desempeno;
                        metaDataProd2[indice2] = area;
                        productionChart2.update();
                    }
                }
            } else {
                console.error('Error en la respuesta del servidor:', respuesta.data);
            }
        })
        .catch(error => {
            console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
        });
    });

    // Cargar los datos iniciales al cargar la página
    function obtenerDatosProduccion() {
        axios.get('/produccion/get-data')
            .then(respuesta => {
                const datos = respuesta.data;
                datos.forEach(item => {
                    const indice = dataLabelsProduccion.indexOf(item.mes);
                    if (indice !== -1) {
                        cumplimientoDataProd[indice] = item.desempeno;
                        metaDataProd[indice] = item.area_cumplimiento;
                    } else {
                        const indice2 = dataLabelsProduccion2.indexOf(item.mes);
                        if (indice2 !== -1) {
                            cumplimientoDataProd2[indice2] = item.desempeno;
                            metaDataProd2[indice2] = item.area_cumplimiento;
                        }
                    }
                });
                productionChart.update(); // Actualizar el gráfico 1
                productionChart2.update(); // Actualizar el gráfico 2
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
            });
    }

    obtenerDatosProduccion();

    // Alternar entre gráficos
    let graficoActualProduccion = 1;
    document.getElementById('nextChartProduccion').addEventListener('click', () => {
        if (graficoActualProduccion === 1) {
            document.getElementById('productionChart').style.display = 'none';
            document.getElementById('productionChart2').style.display = 'block';
            graficoActualProduccion = 2;
        } else {
            document.getElementById('productionChart').style.display = 'block';
            document.getElementById('productionChart2').style.display = 'none';
            graficoActualProduccion = 1;
        }
    });

    document.getElementById('prevChartProduccion').addEventListener('click', () => {
        if (graficoActualProduccion === 1) {
            document.getElementById('productionChart').style.display = 'none';
            document.getElementById('productionChart2').style.display = 'block';
            graficoActualProduccion = 2;
        } else {
            document.getElementById('productionChart').style.display = 'block';
            document.getElementById('productionChart2').style.display = 'none';
            graficoActualProduccion = 1;
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