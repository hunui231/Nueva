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
</style>
<h2 class="maspudo2"> INDICADORES RH CPA </h2>
   <br><br>
   <h1 class="box-title">Rotación de Personal CI</h1>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<canvas id="rotacionChart"></canvas>
<canvas id="rotacionChart2" style="display: none;"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartRotacion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartRotacion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Rotación de Personal CI</h2>
@can('rh.update')
<form id="dataFormRotacion">
  <label for="monthRotacion">Mes:</label>
  <select id="monthRotacion" name="monthRotacion"></select><br><br>
  <label for="performanceRotacion">Desempeño (%):</label>
  <input type="number" id="performanceRotacion" name="performanceRotacion" min="0" max="100" step="0.01"><br><br>
  @can('admin.update')
  <label for="areaRotacion">Área de cumplimiento (%):</label>
  <input type="number" id="areaRotacion" name="areaRotacion" min="0" max="100" step="0.01" ><br><br>
  @endcan
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico
  const ctxRotacion = document.getElementById('rotacionChart').getContext('2d');
  const ctxRotacion2 = document.getElementById('rotacionChart2').getContext('2d');

  // Función para generar opciones de meses y años
  function generateMonthOptions(startYear, endYear) {
    const months = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    let options = [];

    for (let year = startYear; year <= endYear; year++) {
      months.forEach((month) => {
        options.push(`${month}-${year.toString().slice(-2)}`);
      });
    }

    return options;
  }

  const currentYear = new Date().getFullYear();
  const dataLabelsRotacion = generateMonthOptions(23, 24); // Genera desde 2023 hasta 2024
  const dataLabelsRotacion2 = generateMonthOptions(25, 25); // Genera solo 2025

  let rotacionData = Array(24).fill(null); // Datos iniciales para el gráfico 1
  let metaData = Array(24).fill(4.5); // Datos iniciales para el gráfico 1

  let rotacionData2 = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let metaData2 = Array(12).fill(4.5); // Datos iniciales para el gráfico 2

  const rotacionChart = new Chart(ctxRotacion, {
    type: 'line',
    data: {
      labels: dataLabelsRotacion,
      datasets: [
        {
          label: 'Rotación (%)',
          data: rotacionData,
          borderColor: '#007bff',
          backgroundColor: '#007bff',
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: '#007bff',
        },
        {
          label: 'Meta',
          data: metaData,
          borderColor: 'red',
          borderWidth: 2,
          fill: false,
          pointRadius: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return value + '%';
            },
          },
        },
      },
    },
  });

  // Configuración del gráfico 2
  const rotacionChart2 = new Chart(ctxRotacion2, {
    type: 'line',
    data: {
      labels: dataLabelsRotacion2,
      datasets: [
        {
          label: 'Rotación (%)',
          data: rotacionData2,
          borderColor: '#007bff',
          backgroundColor: '#007bff',
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: '#007bff',
        },
        {
          label: 'Meta',
          data: metaData2,
          borderColor: 'red',
          borderWidth: 2,
          fill: false,
          pointRadius: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            callback: function(value) {
              return value + '%';
            },
          },
        },
      },
    },
  });

  // Generar las opciones de meses en el formulario
  const monthSelectRotacion = document.getElementById('monthRotacion');
  dataLabelsRotacion.concat(dataLabelsRotacion2).forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectRotacion.appendChild(option);
  });

  // Obtener la fecha actual
  const currentDate = new Date();

  // Formatear el mes abreviado (ej: "Feb")
  const month = currentDate.toLocaleString('default', { month: 'short' }).toLowerCase();

  // Formatear el año en dos dígitos (ej: "25")
  const year = currentDate.getFullYear().toString().slice(-2);

  // Crear el formato "MMM-AA" (ej: "Feb-25")
  const currentMonthYear = `${month}-${year}`;

  // Establecer el valor predeterminado como el mes actual
  monthSelectRotacion.value = currentMonthYear;

  // Validar y actualizar el gráfico
  document.getElementById('dataFormRotacion').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    try {
        // 1. Obtener datos del formulario de manera segura
        const mesSeleccionado = document.getElementById('monthRotacion')?.value;
        const desempenoInput = document.getElementById('performanceRotacion');
        
        // Verificar existencia de elementos críticos
        if (!mesSeleccionado || !desempenoInput) {
            throw new Error('Elementos del formulario no encontrados');
        }

        // 2. Obtener valores con comprobación de nulidad
        const desempeno = desempenoInput.value;
        const areaInput = document.getElementById('areaRotacion');
        const area = areaInput ? areaInput.value : null; // Handle case where areaInput doesn't exist

        // 3. Convertir y validar datos
        const desempenoNum = desempeno !== "" ? parseFloat(desempeno) : null;
        const areaNum = area !== null && area !== "" ? parseFloat(area) : null;

        // Validaciones
        if (desempenoNum === null || isNaN(desempenoNum)) {
            throw new Error('El campo Desempeño es obligatorio');
        }

        if (desempenoNum < 0 || desempenoNum > 100) {
            throw new Error('El valor de Desempeño debe estar entre 0% y 100%');
        }

        if (areaInput && (areaNum === null || isNaN(areaNum))) {
            throw new Error('El campo Área de cumplimiento es obligatorio');
        }

        if (areaInput && (areaNum < 0 || areaNum > 100)) {
            throw new Error('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
        }

        // 4. Enviar datos al servidor
        const respuesta = await axios.post('/rotacion/store', {
            mes: mesSeleccionado,
            desempeno: desempenoNum,
            area_cumplimiento: areaNum
        });

        // Rest of your code remains the same...
        if (!respuesta.data.success) {
            throw new Error('Error al guardar los datos');
        }

        // 5. Actualizar gráficos
        const indice = dataLabelsRotacion.indexOf(mesSeleccionado);
        const indice2 = dataLabelsRotacion2.indexOf(mesSeleccionado);
        
        if (indice !== -1) {
            rotacionData[indice] = desempenoNum;
            if (areaNum !== null) metaData[indice] = areaNum;
            rotacionChart.update();
        }
        
        if (indice2 !== -1) {
            rotacionData2[indice2] = desempenoNum;
            if (areaNum !== null) metaData2[indice2] = areaNum;
            rotacionChart2.update();
        }

        // 6. Feedback visual
        const btn = evento.target.querySelector('button[type="submit"]');
        if (btn) {
            const originalText = btn.textContent;
            btn.textContent = '✓ Actualizado';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        }

    } catch (error) {
        console.error('Error en el formulario:', error);
        if (error.message) {
            alert(error.message);
        }
    }
});
  // Obtener los datos actualizados del servidor
  function fetchDataRotacion() {
    axios.get('/rotacion/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsRotacion.indexOf(item.mes);
          if (index !== -1) {
            rotacionData[index] = item.desempeno;
            metaData[index] = item.area_cumplimiento;
          } else {
            const index2 = dataLabelsRotacion2.indexOf(item.mes);
            if (index2 !== -1) {
              rotacionData2[index2] = item.desempeno;
              metaData2[index2] = item.area_cumplimiento;
            }
          }
        });
        rotacionChart.update(); // Actualizar el gráfico 1
        rotacionChart2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchDataRotacion();

  // Alternar entre gráficos
  let currentChartRotacion = 1;
  document.getElementById('nextChartRotacion').addEventListener('click', () => {
    if (currentChartRotacion === 1) {
      document.getElementById('rotacionChart').style.display = 'none';
      document.getElementById('rotacionChart2').style.display = 'block';
      currentChartRotacion = 2;
    } else {
      document.getElementById('rotacionChart').style.display = 'block';
      document.getElementById('rotacionChart2').style.display = 'none';
      currentChartRotacion = 1;
    }
  });

  document.getElementById('prevChartRotacion').addEventListener('click', () => {
    if (currentChartRotacion === 1) {
      document.getElementById('rotacionChart').style.display = 'none';
      document.getElementById('rotacionChart2').style.display = 'block';
      currentChartRotacion = 2;
    } else {
      document.getElementById('rotacionChart').style.display = 'block';
      document.getElementById('rotacionChart2').style.display = 'none';
      currentChartRotacion = 1;
    }
  });
</script>
<br><br>
<h1 class="box-title">Permanencia Personal Reclutado CI</h1>
<canvas id="permanenciaChart"></canvas>
<canvas id="permanenciaChart2" style="display: none;"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartPermanencia" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartPermanencia" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Permanencia de Personal Reclutado CI</h2>
@can('rh.update')
<form id="dataFormPermanencia">
  <label for="monthPermanencia">Mes:</label>
  <select id="monthPermanencia" name="monthPermanencia"></select><br><br>
  <label for="performancePermanencia">Desempeño (%):</label>
  <input type="number" id="performancePermanencia" name="performancePermanencia" min="0" max="100" step="0.01"><br><br>
  @can('admin.update')
  <label for="areaPermanencia">Área de cumplimiento (%):</label>
  <input type="number" id="areaPermanencia" name="areaPermanencia" min="0" max="100" step="0.01" ><br><br>
  @endcan

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico
  const ctxPermanencia = document.getElementById('permanenciaChart').getContext('2d');
  const ctxPermanencia2 = document.getElementById('permanenciaChart2').getContext('2d');

  // Función para generar opciones de meses y años
  function generateMonthOptionsPermanencia(startYear, endYear) {
    const months = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    let options = [];

    for (let year = startYear; year <= endYear; year++) {
      months.forEach((month) => {
        options.push(`${month}-${year.toString().slice(-2)}`);
      });
    }

    return options;
  }

  const currentYearPermanencia = new Date().getFullYear();
  const dataLabelsPermanencia = generateMonthOptionsPermanencia(23, 24); // Genera desde 2023 hasta 2024
  const dataLabelsPermanencia2 = generateMonthOptionsPermanencia(25, 25); // Genera solo 2025

  let permanenciaData = Array(24).fill(null); // Datos iniciales para el gráfico 1
  let metaDataPermanencia = Array(24).fill(85); // Datos iniciales para el gráfico 1

  let permanenciaData2 = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let metaDataPermanencia2 = Array(12).fill(85); // Datos iniciales para el gráfico 2

  // Configuración del gráfico 1
  const permanenciaChart = new Chart(ctxPermanencia, {
    type: 'line',
    data: {
      labels: dataLabelsPermanencia,
      datasets: [
        {
          label: "Permanencia (%)",
          data: permanenciaData,
          borderColor: "#007bff",
          backgroundColor: "#007bff",
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: "#007bff",
        },
        {
          label: "Meta",
          data: metaDataPermanencia,
          borderColor: "red",
          borderWidth: 2,
          fill: false,
          pointRadius: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.raw.toFixed(2) + "%";
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          max: 120,
          ticks: {
            callback: function(value) {
              return value + "%";
            },
          },
        },
      },
    },
  });

  // Configuración del gráfico 2
  const permanenciaChart2 = new Chart(ctxPermanencia2, {
    type: 'line',
    data: {
      labels: dataLabelsPermanencia2,
      datasets: [
        {
          label: "Permanencia (%)",
          data: permanenciaData2,
          borderColor: "#007bff",
          backgroundColor: "#007bff",
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: "#007bff",
        },
        {
          label: "Meta",
          data: metaDataPermanencia2,
          borderColor: "red",
          borderWidth: 2,
          fill: false,
          pointRadius: 0.1,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.raw.toFixed(2) + "%";
            },
          },
        },
      },
      scales: {
        y: {
          beginAtZero: true,
          max: 120,
          ticks: {
            callback: function(value) {
              return value + "%";
            },
          },
        },
      },
    },
  });

  // Generar las opciones de meses en el formulario
  const monthSelectPermanencia = document.getElementById('monthPermanencia');
  dataLabelsPermanencia.concat(dataLabelsPermanencia2).forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectPermanencia.appendChild(option);
  });

  // Obtener la fecha actual
  const currentDatePermanencia = new Date();

  // Formatear el mes abreviado (ej: "Feb")
  const monthPermanencia = currentDatePermanencia.toLocaleString('default', { month: 'short' }).toLowerCase();

  // Formatear el año en dos dígitos (ej: "25")
  const yearPermanencia = currentDatePermanencia.getFullYear().toString().slice(-2);

  // Crear el formato "MMM-AA" (ej: "Feb-25")
  const currentMonthYearPermanencia = `${monthPermanencia}-${yearPermanencia}`;

  // Establecer el valor predeterminado como el mes actual
  monthSelectPermanencia.value = currentMonthYearPermanencia;

  document.getElementById('dataFormPermanencia').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    try {
        // 1. Obtener elementos del formulario de manera segura
        const mesSeleccionado = document.getElementById('monthPermanencia')?.value;
        const performanceInput = document.getElementById('performancePermanencia');
        const areaInput = document.getElementById('areaPermanencia'); // Campo condicional
        
        // Verificar existencia de elementos críticos
        if (!mesSeleccionado || !performanceInput) {
            throw new Error('Elementos del formulario no encontrados');
        }

        // 2. Obtener valores con comprobación de nulidad
        const performance = performanceInput.value;
        const area = areaInput ? areaInput.value : null;

        // 3. Convertir y validar datos
        const performanceNum = performance !== "" ? parseFloat(performance) : null;
        const areaNum = area !== null && area !== "" ? parseFloat(area) : null;

        // Validaciones básicas
        if (performanceNum === null || isNaN(performanceNum)) {
            throw new Error('El campo Desempeño es obligatorio');
        }

        if (performanceNum < 0 || performanceNum > 100) {
            throw new Error('El valor de Desempeño debe estar entre 0% y 100%');
        }

        // Validaciones para el área (solo si existe el campo)
        if (areaInput) {
            if (areaNum === null || isNaN(areaNum)) {
                throw new Error('El campo Área de cumplimiento es obligatorio');
            }
            if (areaNum < 0 || areaNum > 100) {
                throw new Error('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
            }
        }

        // 4. Preparar datos para enviar al servidor
        const datos = {
            mes: mesSeleccionado,
            desempeno: performanceNum
        };

        // Solo agregar area_cumplimiento si existe el campo
        if (areaInput) {
            datos.area_cumplimiento = areaNum;
        }

        // 5. Enviar datos al servidor
        const respuesta = await axios.post('/permanencia/store', datos);

        if (!respuesta.data.success) {
            throw new Error('Error al guardar los datos');
        }

        // 6. Actualizar gráficos
        const indice = dataLabelsPermanencia.indexOf(mesSeleccionado);
        const indice2 = dataLabelsPermanencia2.indexOf(mesSeleccionado);
        
        if (indice !== -1) {
            permanenciaData[indice] = performanceNum;
            if (areaNum !== null) metaDataPermanencia[indice] = areaNum;
            permanenciaChart.update();
        }
        
        if (indice2 !== -1) {
            permanenciaData2[indice2] = performanceNum;
            if (areaNum !== null) metaDataPermanencia2[indice2] = areaNum;
            permanenciaChart2.update();
        }

        // 7. Feedback visual
        const btn = evento.target.querySelector('button[type="submit"]');
        if (btn) {
            const originalText = btn.textContent;
            btn.textContent = '✓ Actualizado';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        }

    } catch (error) {
        console.error('Error en el formulario:', error);
        if (error.message) {
            alert(error.message);
        }
    }
});
  // Obtener los datos actualizados del servidor
  function fetchDataPermanencia() {
    axios.get('/permanencia/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsPermanencia.indexOf(item.mes);
          if (index !== -1) {
            permanenciaData[index] = item.desempeno;
            metaDataPermanencia[index] = item.area_cumplimiento;
          } else {
            const index2 = dataLabelsPermanencia2.indexOf(item.mes);
            if (index2 !== -1) {
              permanenciaData2[index2] = item.desempeno;
              metaDataPermanencia2[index2] = item.area_cumplimiento;
            }
          }
        });
        permanenciaChart.update(); // Actualizar el gráfico 1
        permanenciaChart2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchDataPermanencia();

  // Alternar entre gráficos
  let currentChartPermanencia = 1;
  document.getElementById('nextChartPermanencia').addEventListener('click', () => {
    if (currentChartPermanencia === 1) {
      document.getElementById('permanenciaChart').style.display = 'none';
      document.getElementById('permanenciaChart2').style.display = 'block';
      currentChartPermanencia = 2;
    } else {
      document.getElementById('permanenciaChart').style.display = 'block';
      document.getElementById('permanenciaChart2').style.display = 'none';
      currentChartPermanencia = 1;
    }
  });

  document.getElementById('prevChartPermanencia').addEventListener('click', () => {
    if (currentChartPermanencia === 1) {
      document.getElementById('permanenciaChart').style.display = 'none';
      document.getElementById('permanenciaChart2').style.display = 'block';
      currentChartPermanencia = 2;
    } else {
      document.getElementById('permanenciaChart').style.display = 'block';
      document.getElementById('permanenciaChart2').style.display = 'none';
      currentChartPermanencia = 1;
    }
  });
</script>
<br><br>

<h2 class="maspudo"> INDICADORES RH GIC </h2>

<br><br>
<h1 class="box-title">Rotación de Personal GIC</h1>
<canvas id="rotationChartGIC"></canvas>
<canvas id="rotationChartGIC2" style="display: none;"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartRotacionGIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartRotacionGIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Rotación de Personal GIC</h2>
@can('rh.update')
<form id="dataFormRotacionGIC">
  <label for="monthRotacionGIC">Mes:</label>
  <select id="monthRotacionGIC" name="monthRotacionGIC"></select><br><br>
  <label for="performanceRotacionGIC">Desempeño (%):</label>
  <input type="number" id="performanceRotacionGIC" name="performanceRotacionGIC" min="0" max="100" step="0.01"><br><br>
  @can('admin.update')
  <label for="areaRotacionGIC">Área de cumplimiento (%):</label>
  <input type="number" id="areaRotacionGIC" name="areaRotacionGIC" min="0" max="100" step="0.01" ><br><br>
  @endcan
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico
  const ctxRotationGIC = document.getElementById('rotationChartGIC').getContext('2d');
  const ctxRotationGIC2 = document.getElementById('rotationChartGIC2').getContext('2d');

  // Función para generar opciones de meses y años
  function generateMonthOptionsRotacionGIC(startYear, endYear) {
    const months = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    let options = [];

    for (let year = startYear; year <= endYear; year++) {
      months.forEach((month) => {
        options.push(`${month}-${year.toString().slice(-2)}`);
      });
    }

    return options;
  }

  const currentYearRotacionGIC = new Date().getFullYear();
  const dataLabelsRotacionGIC = generateMonthOptionsRotacionGIC(23, 24); // Genera desde 2023 hasta 2024
  const dataLabelsRotacionGIC2 = generateMonthOptionsRotacionGIC(25, 25); // Genera solo 2025

  let rotacionDataGIC = Array(24).fill(null); // Datos iniciales para el gráfico 1
  let referenciaDataGIC = Array(24).fill(5); // Datos iniciales para el gráfico 1

  let rotacionData2GIC = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let referenciaData2GIC = Array(12).fill(5); // Datos iniciales para el gráfico 2

  // Configuración del gráfico 1
  const rotationChartGIC = new Chart(ctxRotationGIC, {
    type: 'line',
    data: {
      labels: dataLabelsRotacionGIC,
      datasets: [
        {
          label: "Rotación (%)",
          data: rotacionDataGIC,
          borderColor: "#007bff",
          backgroundColor: "#007bff",
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: "#007bff",
        },
        {
          label: "Referencia",
          data: referenciaDataGIC,
          borderColor: "#FF0000",
          borderWidth: 2,
          fill: false,
          pointRadius: 0,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.raw.toFixed(2) + "%";
            },
          },
        },
      },
      scales: {
        y: {
          min: 0,
          max: 18,
          title: { display: true, text: "% de Rotación" },
        },
        x: {
          title: { display: true, text: "Meses" },
        },
      },
    },
  });

  // Configuración del gráfico 2
  const rotationChartGIC2 = new Chart(ctxRotationGIC2, {
    type: 'line',
    data: {
      labels: dataLabelsRotacionGIC2,
      datasets: [
        {
          label: "Rotación (%)",
          data: rotacionData2GIC,
          borderColor: "#007bff",
          backgroundColor: "#007bff",
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: "#007bff",
        },
        {
          label: "Referencia",
          data: referenciaData2GIC,
          borderColor: "#FF0000",
          borderWidth: 2,
          fill: false,
          pointRadius: 0,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.raw.toFixed(2) + "%";
            },
          },
        },
      },
      scales: {
        y: {
          min: 0,
          max: 18,
          title: { display: true, text: "% de Rotación" },
        },
        x: {
          title: { display: true, text: "Meses" },
        },
      },
    },
  });

  // Generar las opciones de meses en el formulario
  const monthSelectRotacionGIC = document.getElementById('monthRotacionGIC');
  dataLabelsRotacionGIC.concat(dataLabelsRotacionGIC2).forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectRotacionGIC.appendChild(option);
  });

  // Obtener la fecha actual
  const currentDateRotacionGIC = new Date();

  // Formatear el mes abreviado (ej: "Feb")
  const monthRotacionGIC = currentDateRotacionGIC.toLocaleString('default', { month: 'short' }).toLowerCase();

  // Formatear el año en dos dígitos (ej: "25")
  const yearRotacionGIC = currentDateRotacionGIC.getFullYear().toString().slice(-2);

  // Crear el formato "MMM-AA" (ej: "Feb-25")
  const currentMonthYearRotacionGIC = `${monthRotacionGIC}-${yearRotacionGIC}`;

  // Establecer el valor predeterminado como el mes actual
  monthSelectRotacionGIC.value = currentMonthYearRotacionGIC;

  // Validar y actualizar el gráfico
  document.getElementById('dataFormRotacionGIC').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    try {
        // 1. Obtener elementos del formulario de manera segura
        const mesSeleccionado = document.getElementById('monthRotacionGIC')?.value;
        const performanceInput = document.getElementById('performanceRotacionGIC');
        const areaInput = document.getElementById('areaRotacionGIC'); // Campo condicional
        
        // Verificar existencia de elementos críticos
        if (!mesSeleccionado || !performanceInput) {
            throw new Error('Elementos del formulario no encontrados');
        }

        // 2. Obtener valores con comprobación de nulidad
        const performance = performanceInput.value;
        const area = areaInput ? areaInput.value : null;

        // 3. Convertir y validar datos
        const performanceNum = performance !== "" ? parseFloat(performance) : null;
        const areaNum = area !== null && area !== "" ? parseFloat(area) : null;

        // Validaciones básicas
        if (performanceNum === null || isNaN(performanceNum)) {
            throw new Error('El campo Desempeño es obligatorio');
        }

        if (performanceNum < 0 || performanceNum > 100) {
            throw new Error('El valor de Desempeño debe estar entre 0% y 100%');
        }

        // Validaciones para el área (solo si existe el campo)
        if (areaInput) {
            if (areaNum === null || isNaN(areaNum)) {
                throw new Error('El campo Área de cumplimiento es obligatorio');
            }
            if (areaNum < 0 || areaNum > 100) {
                throw new Error('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
            }
        }

        // 4. Preparar datos para enviar al servidor
        const datos = {
            mes: mesSeleccionado,
            desempeno: performanceNum
        };

        // Solo agregar area_cumplimiento si existe el campo
        if (areaInput) {
            datos.area_cumplimiento = areaNum;
        }

        // 5. Enviar datos al servidor
        const respuesta = await axios.post('/rotacion-gic/store', datos);

        if (!respuesta.data.success) {
            throw new Error('Error al guardar los datos');
        }

        // 6. Actualizar ambos gráficos donde corresponda
        const indice = dataLabelsRotacionGIC.indexOf(mesSeleccionado);
        const indice2 = dataLabelsRotacionGIC2.indexOf(mesSeleccionado);
        
        if (indice !== -1) {
            rotacionDataGIC[indice] = performanceNum;
            if (areaNum !== null) referenciaDataGIC[indice] = areaNum;
            rotationChartGIC.update();
        }
        
        if (indice2 !== -1) {
            rotacionData2GIC[indice2] = performanceNum;
            if (areaNum !== null) referenciaData2GIC[indice2] = areaNum;
            rotationChartGIC2.update();
        }

        // 7. Feedback visual
        const btn = evento.target.querySelector('button[type="submit"]');
        if (btn) {
            const originalText = btn.textContent;
            btn.textContent = '✓ Actualizado';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        }

    } catch (error) {
        console.error('Error en el formulario:', error);
        if (error.message) {
            alert(error.message);
        }
    }
});
  // Obtener los datos actualizados del servidor
  function fetchDataRotacionGIC() {
    axios.get('/rotacion-gic/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsRotacionGIC.indexOf(item.mes);
          if (index !== -1) {
            rotacionDataGIC[index] = item.desempeno;
            referenciaDataGIC[index] = item.area_cumplimiento;
          } else {
            const index2 = dataLabelsRotacionGIC2.indexOf(item.mes);
            if (index2 !== -1) {
              rotacionData2GIC[index2] = item.desempeno;
              referenciaData2GIC[index2] = item.area_cumplimiento;
            }
          }
        });
        rotationChartGIC.update(); // Actualizar el gráfico 1
        rotationChartGIC2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchDataRotacionGIC();

  // Alternar entre gráficos
  let currentChartRotacionGIC = 1;
  document.getElementById('nextChartRotacionGIC').addEventListener('click', () => {
    if (currentChartRotacionGIC === 1) {
      document.getElementById('rotationChartGIC').style.display = 'none';
      document.getElementById('rotationChartGIC2').style.display = 'block';
      currentChartRotacionGIC = 2;
    } else {
      document.getElementById('rotationChartGIC').style.display = 'block';
      document.getElementById('rotationChartGIC2').style.display = 'none';
      currentChartRotacionGIC = 1;
    }
  });

  document.getElementById('prevChartRotacionGIC').addEventListener('click', () => {
    if (currentChartRotacionGIC === 1) {
      document.getElementById('rotationChartGIC').style.display = 'none';
      document.getElementById('rotationChartGIC2').style.display = 'block';
      currentChartRotacionGIC = 2;
    } else {
      document.getElementById('rotationChartGIC').style.display = 'block';
      document.getElementById('rotationChartGIC2').style.display = 'none';
      currentChartRotacionGIC = 1;
    }
  });
</script>

<br><br>
<h1 class="box-title">Permanencia Personal Reclutado GIC</h1>
<canvas id="permanenceChartGIC"></canvas>
<canvas id="permanenceChartGIC2" style="display: none;"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartPermanenciaGIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartPermanenciaGIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Permanencia de Personal Reclutado GIC</h2>
@can('rh.update')
<form id="dataFormPermanenciaGIC">
  <label for="monthPermanenciaGIC">Mes:</label>
  <select id="monthPermanenciaGIC" name="monthPermanenciaGIC"></select><br><br>

  <label for="performancePermanenciaGIC">Desempeño (%):</label>
  <input type="number" id="performancePermanenciaGIC" name="performancePermanenciaGIC" min="0" max="100" step="0.01" ><br><br>
  @can('admin.update')
  <label for="areaPermanenciaGIC">Área de cumplimiento (%):</label>
  <input type="number" id="areaPermanenciaGIC" name="areaPermanenciaGIC" min="0" max="100" step="0.01" ><br><br>
  @endcan
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico
  const ctxPermanenceGIC = document.getElementById('permanenceChartGIC').getContext('2d');
  const ctxPermanenceGIC2 = document.getElementById('permanenceChartGIC2').getContext('2d');

  // Función para generar opciones de meses y años
  function generateMonthOptionsPermanenciaGIC(startYear, endYear) {
    const months = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    let options = [];

    for (let year = startYear; year <= endYear; year++) {
      months.forEach((month) => {
        options.push(`${month}-${year.toString().slice(-2)}`);
      });
    }

    return options;
  }

  const currentYearPermanenciaGIC = new Date().getFullYear();
  const dataLabelsPermanenciaGIC = generateMonthOptionsPermanenciaGIC(23, 24); // Genera desde 2023 hasta 2024
  const dataLabelsPermanenciaGIC2 = generateMonthOptionsPermanenciaGIC(25, 25); // Genera solo 2025

  let permanenciaDataGIC = Array(24).fill(0); // Datos iniciales para el gráfico 1
  let fondoDataGIC = Array(24).fill(100); // Datos iniciales para el gráfico 1

  let permanenciaData2GIC = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let fondoData2GIC = Array(12).fill(100); // Datos iniciales para el gráfico 2

  // Configuración del gráfico 1
  const permanenceChartGIC = new Chart(ctxPermanenceGIC, {
    type: 'line',
    data: {
      labels: dataLabelsPermanenciaGIC,
      datasets: [
        {
          label: "Permanencia (%)",
          data: permanenciaDataGIC,
          borderColor: "#007bff",
          backgroundColor: "#007bff",
          fill: false,
          tension: 0.1,
          pointRadius: 5,
          pointBackgroundColor: "#007bff",
        },
        {
          label: "Fondo",
          data: fondoDataGIC,
          borderColor: "red",
          backgroundColor: "rgb(255, 0, 0)", 
          fill: true,
          borderWidth: 0,
          pointRadius: 0,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.raw.toFixed(2) + "%";
            },
          },
        },
      },
      scales: {
        y: {
          min: 0,
          max: 120,
          title: { display: true, text: "% de Permanencia" },
        },
        x: {
          title: { display: true, text: "Meses" },
        },
      },
    },
  });

  // Configuración del gráfico 2
  const permanenceChartGIC2 = new Chart(ctxPermanenceGIC2, {
    type: 'line',
    data: {
      labels: dataLabelsPermanenciaGIC2,
      datasets: [
        {
          label: "Permanencia (%)",
          data: permanenciaData2GIC,
          borderColor: "#007bff",
          backgroundColor: "#007bff",
          fill: false,
          tension: 0.1,
          pointRadius: 5,
          pointBackgroundColor: "#007bff",
        },
        {
          label: "Fondo",
          data: fondoData2GIC,
          borderColor: "red",
          backgroundColor: "rgb(223, 10, 10)", 
          fill: true,
          borderWidth: 0,
          pointRadius: 0,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.raw.toFixed(2) + "%";
            },
          },
        },
      },
      scales: {
        y: {
          min: 0,
          max: 120,
          title: { display: true, text: "% de Permanencia" },
        },
        x: {
          title: { display: true, text: "Meses" },
        },
      },
    },
  });

  // Generar las opciones de meses en el formulario
  const monthSelectPermanenciaGIC = document.getElementById('monthPermanenciaGIC');
  dataLabelsPermanenciaGIC.concat(dataLabelsPermanenciaGIC2).forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectPermanenciaGIC.appendChild(option);
  });

  // Obtener la fecha actual
  const currentDatePermanenciaGIC = new Date();

  // Formatear el mes abreviado (ej: "Feb")
  const monthPermanenciaGIC = currentDatePermanenciaGIC.toLocaleString('default', { month: 'short' }).toLowerCase();

  // Formatear el año en dos dígitos (ej: "25")
  const yearPermanenciaGIC = currentDatePermanenciaGIC.getFullYear().toString().slice(-2);

  // Crear el formato "MMM-AA" (ej: "Feb-25")
  const currentMonthYearPermanenciaGIC = `${monthPermanenciaGIC}-${yearPermanenciaGIC}`;

  // Establecer el valor predeterminado como el mes actual
  monthSelectPermanenciaGIC.value = currentMonthYearPermanenciaGIC;

  document.getElementById('dataFormPermanenciaGIC').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    try {
        // 1. Obtener elementos del formulario de manera segura
        const mesSeleccionado = document.getElementById('monthPermanenciaGIC')?.value;
        const performanceInput = document.getElementById('performancePermanenciaGIC');
        const areaInput = document.getElementById('areaPermanenciaGIC'); // Campo condicional
        
        // Verificar existencia de elementos críticos
        if (!mesSeleccionado || !performanceInput) {
            throw new Error('Elementos del formulario no encontrados');
        }

        // 2. Obtener valores con comprobación de nulidad
        const performance = performanceInput.value;
        const area = areaInput ? areaInput.value : null;

        // 3. Convertir y validar datos
        const performanceNum = performance !== "" ? parseFloat(performance) : null;
        const areaNum = area !== null && area !== "" ? parseFloat(area) : null;

        // Validaciones básicas
        if (performanceNum === null || isNaN(performanceNum)) {
            throw new Error('El campo Desempeño es obligatorio');
        }

        if (performanceNum < 0 || performanceNum > 100) {
            throw new Error('El valor de Desempeño debe estar entre 0% y 100%');
        }

        // Validaciones para el área (solo si existe el campo)
        if (areaInput) {
            if (areaNum === null || isNaN(areaNum)) {
                throw new Error('El campo Área de cumplimiento es obligatorio');
            }
            if (areaNum < 0 || areaNum > 100) {
                throw new Error('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
            }
        }

        // 4. Preparar datos para enviar al servidor
        const datos = {
            mes: mesSeleccionado,
            desempeno: performanceNum
        };

        // Solo agregar area_cumplimiento si existe el campo
        if (areaInput) {
            datos.area_cumplimiento = areaNum;
        }

        // 5. Enviar datos al servidor
        const respuesta = await axios.post('/permanencia-gic/store', datos);

        if (!respuesta.data.success) {
            throw new Error('Error al guardar los datos');
        }

        // 6. Actualizar ambos gráficos donde corresponda
        const indice = dataLabelsPermanenciaGIC.indexOf(mesSeleccionado);
        const indice2 = dataLabelsPermanenciaGIC2.indexOf(mesSeleccionado);
        
        if (indice !== -1) {
            permanenciaDataGIC[indice] = performanceNum;
            if (areaNum !== null) fondoDataGIC[indice] = areaNum;
            permanenceChartGIC.update();
        }
        
        if (indice2 !== -1) {
            permanenciaData2GIC[indice2] = performanceNum;
            if (areaNum !== null) fondoData2GIC[indice2] = areaNum;
            permanenceChartGIC2.update();
        }

        // 7. Feedback visual
        const btn = evento.target.querySelector('button[type="submit"]');
        if (btn) {
            const originalText = btn.textContent;
            btn.textContent = '✓ Actualizado';
            btn.disabled = true;
            
            setTimeout(() => {
                btn.textContent = originalText;
                btn.disabled = false;
            }, 2000);
        }

    } catch (error) {
        console.error('Error en el formulario:', error);
        if (error.message) {
            alert(error.message);
        }
    }
});

  // Obtener los datos actualizados del servidor
  function fetchDataPermanenciaGIC() {
    axios.get('/permanencia-gic/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsPermanenciaGIC.indexOf(item.mes);
          if (index !== -1) {
            permanenciaDataGIC[index] = item.desempeno;
            fondoDataGIC[index] = item.area_cumplimiento;
          } else {
            const index2 = dataLabelsPermanenciaGIC2.indexOf(item.mes);
            if (index2 !== -1) {
              permanenciaData2GIC[index2] = item.desempeno;
              fondoData2GIC[index2] = item.area_cumplimiento;
            }
          }
        });
        permanenceChartGIC.update(); // Actualizar el gráfico 1
        permanenceChartGIC2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchDataPermanenciaGIC();

  // Alternar entre gráficos
  let currentChartPermanenciaGIC = 1;
  document.getElementById('nextChartPermanenciaGIC').addEventListener('click', () => {
    if (currentChartPermanenciaGIC === 1) {
      document.getElementById('permanenceChartGIC').style.display = 'none';
      document.getElementById('permanenceChartGIC2').style.display = 'block';
      currentChartPermanenciaGIC = 2;
    } else {
      document.getElementById('permanenceChartGIC').style.display = 'block';
      document.getElementById('permanenceChartGIC2').style.display = 'none';
      currentChartPermanenciaGIC = 1;
    }
  });

  document.getElementById('prevChartPermanenciaGIC').addEventListener('click', () => {
    if (currentChartPermanenciaGIC === 1) {
      document.getElementById('permanenceChartGIC').style.display = 'none';
      document.getElementById('permanenceChartGIC2').style.display = 'block';
      currentChartPermanenciaGIC = 2;
    } else {
      document.getElementById('permanenceChartGIC').style.display = 'block';
      document.getElementById('permanenceChartGIC2').style.display = 'none';
      currentChartPermanenciaGIC = 1;
    }
  });
</script>
@endsection