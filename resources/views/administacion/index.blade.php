@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
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
h1 {
  font-family: "Lato", sans-serif;
  font-size: 32px;
  font-weight: bold;
  color:rgb(49, 50, 51);
  text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
  text-align: center;
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<h1>Cobranza 2024 CI - 2025 CI</h1>
<canvas id="cobranzaChartCI"></canvas>
<canvas id="graficoX"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>

<h2>Ingresar Datos - CI</h2>
@can('adm.update')
<form id="dataFormCI">
  <label for="weekCI">Semana:</label>
  <select id="weekCI" name="weekCI"></select><br><br>

  <label for="enTiempoCI">EN TIEMPO:</label>
  <input type="number" id="enTiempoCI" name="enTiempoCI" min="0" max="100" required><br><br>

  <label for="rango1CI">RANGO 1:</label>
  <input type="number" id="rango1CI" name="rango1CI" min="0" max="100" required><br><br>

  <label for="rango2CI">RANGO 2:</label>
  <input type="number" id="rango2CI" name="rango2CI" min="0" max="100" required><br><br>

  <label for="rango3CI">RANGO 3:</label>
  <input type="number" id="rango3CI" name="rango3CI" min="0" max="100" required><br><br>

  <label for="rango4CI">RANGO 4:</label>
  <input type="number" id="rango4CI" name="rango4CI" min="0" max="100" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico CI</button>
</form>
@endcan
<hr>
<h1>Cobranza 2024 GIC - 2025 GIC</h1>
<canvas id="cobranzaChartGIC"></canvas>
<canvas id="graficoXGIC"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartGIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChartGIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>

<!-- Formulario para GIC -->
<h2>Ingresar Datos - GIC</h2>
@can('adm.update')
<form id="dataFormGIC">
  <label for="weekGIC">Semana:</label>
  <select id="weekGIC" name="weekGIC"></select><br><br>

  <label for="enTiempoGIC">EN TIEMPO:</label>
  <input type="number" id="enTiempoGIC" name="enTiempoGIC" min="0" max="100" required><br><br>

  <label for="rango1GIC">RANGO 1:</label>
  <input type="number" id="rango1GIC" name="rango1GIC" min="0" max="100" required><br><br>

  <label for="rango2GIC">RANGO 2:</label>
  <input type="number" id="rango2GIC" name="rango2GIC" min="0" max="100" required><br><br>

  <label for="rango3GIC">RANGO 3:</label>
  <input type="number" id="rango3GIC" name="rango3GIC" min="0" max="100" required><br><br>

  <label for="rango4GIC">RANGO 4:</label>
  <input type="number" id="rango4GIC" name="rango4GIC" min="0" max="100" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico GIC</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Función para obtener la semana actual
function getCurrentWeek() {
  const now = new Date();
  const startOfYear = new Date(now.getFullYear(), 0, 1);
  const pastDaysOfYear = (now - startOfYear) / 86400000;
  return Math.ceil((pastDaysOfYear + startOfYear.getDay() + 1) / 7);
}

// Función para generar opciones de semanas dinámicamente
function generateWeekOptions(selectElement, year) {
  const currentYear = new Date().getFullYear();
  const currentWeek = getCurrentWeek();
  const totalWeeks = year === currentYear ? currentWeek : 52; // 52 semanas en un año

  for (let week = 1; week <= totalWeeks; week++) {
    const option = document.createElement('option');
    option.value = `SEMANA ${week}`;
    option.textContent = `SEMANA ${week}`;
    if (year === currentYear && week === currentWeek) {
      option.selected = true; // Seleccionar la semana actual
    }
    selectElement.appendChild(option);
  }
}

// Validación de años futuros
function validateYear(year) {
  const currentYear = new Date().getFullYear();
  return year >= currentYear;
}

// Configuración inicial de gráficos y datos
const ctxCI = document.getElementById('cobranzaChartCI').getContext('2d');
let cobranzaChartCI;

const initialDataCI = {
  labels: ['SEMANA 6', 'SEMANA 8', 'SEMANA 10', 'SEMANA 12', 'SEMANA 15', 'SEMANA 17', 'SEMANA 19', 'SEMANA 21', 'SEMANA 23', 'SEMANA 25', 'SEMANA 27', 'SEMANA 31', 'SEMANA 35', 'SEMANA 37', 'SEMANA 39', 'SEMANA 43', 'SEMANA 45', 'SEMANA 49'],
  datasets: [
    { label: 'EN TIEMPO', data: [99, 90, 92, 88, 93, 95, 91, 89, 94, 96, 97, 92, 93, 95, 96], backgroundColor: 'lightgreen', stack: 'stack1' },
    { label: 'RANGO 1', data: [3, 3, 2, 4, 2, 1, 3, 4, 1, 2, 1, 2, 2, 1, 1], backgroundColor: 'yellow', stack: 'stack1' },
    { label: 'RANGO 2', data: [3, 3, 2, 3, 2, 1, 2, 3, 2, 1, 1, 2, 2, 2, 1], backgroundColor: 'orange', stack: 'stack1' },
    { label: 'RANGO 3', data: [3, 2, 1, 2, 1, 1, 1, 2, 1, 1, 1, 1, 1, 1, 1], backgroundColor: 'red', stack: 'stack1' },
    { label: 'RANGO 4', data: [2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1], backgroundColor: 'darkred', stack: 'stack1' },
  ],
};

const configCI = {
  type: 'bar',
  data: initialDataCI,
  options: {
    responsive: true,
    plugins: { legend: { position: 'top' } },
    scales: {
      x: { stacked: true },
      y: { stacked: true, max: 105, ticks: { callback: (value) => value + '%' } },
    },
  },
};

// Función para crear el gráfico
function crearGrafico() {
  if (cobranzaChartCI) cobranzaChartCI.destroy();
  cobranzaChartCI = new Chart(ctxCI, configCI);
}

// Función para cargar los datos desde el backend
async function cargarDatos() {
  try {
    const response = await fetch('/obtener-ci');
    const datos = await response.json();

    if (response.ok) {
      initialDataCI.labels = datos.map(item => item.semana);
      initialDataCI.datasets[0].data = datos.map(item => item.en_tiempo);
      initialDataCI.datasets[1].data = datos.map(item => item.rango1);
      initialDataCI.datasets[2].data = datos.map(item => item.rango2);
      initialDataCI.datasets[3].data = datos.map(item => item.rango3);
      initialDataCI.datasets[4].data = datos.map(item => item.rango4);
      crearGrafico();
    } else {
      console.error('Error al cargar los datos:', datos.error);
    }
  } catch (error) {
    console.error('Error al cargar los datos:', error);
  }
}

// Inicialización al cargar la página
window.addEventListener('load', () => {
  const weekSelectCI = document.getElementById('weekCI');
  const weekSelectGIC = document.getElementById('weekGIC');
  const currentYear = new Date().getFullYear();

  // Generar opciones de semanas para CI y GIC
  generateWeekOptions(weekSelectCI, currentYear);
  generateWeekOptions(weekSelectGIC, currentYear);

  // Crear gráficos y cargar datos
  crearGrafico();
  cargarDatos();
});

// Generar las opciones de semanas en el formulario CI
const weekSelectCI = document.getElementById('weekCI');
initialDataCI.labels.forEach((label) => {
    const option = document.createElement('option');
    option.value = label; // Nombre de la semana (ej: "SEMANA 6")
    option.textContent = label;
    weekSelectCI.appendChild(option);
});

const ctxGIC = document.getElementById('cobranzaChartGIC').getContext('2d');
let cobranzaChartGIC;  // Variable para almacenar la instancia del gráfico

const initialDataGIC = {
    labels: ['SEMANA 6', 'SEMANA 10', 'SEMANA 12', 'SEMANA 15', 'SEMANA 17', 'SEMANA 19', 'SEMANA 23', 'SEMANA 27', 'SEMANA 31', 'SEMANA 35', 'SEMANA 37', 'SEMANA 39', 'SEMANA 43', 'SEMANA 45', 'SEMANA 49'],
    datasets: [
        {
            label: 'EN TIEMPO',
            data: [99, 90, 92, 88, 93, 95, 91, 89, 94, 96, 97, 92, 93, 95, 96],
            backgroundColor: 'lightgreen',
            stack: 'stack1',
        },
        {
            label: 'RANGO 1',
            data: [3, 3, 2, 4, 2, 1, 3, 4, 1, 2, 1, 2, 2, 1, 1],
            backgroundColor: 'yellow',
            stack: 'stack1',
        },
        {
            label: 'RANGO 2',
            data: [3, 3, 2, 3, 2, 1, 2, 3, 2, 1, 1, 2, 2, 2, 1],
            backgroundColor: 'orange',
            stack: 'stack1',
        },
        {
            label: 'RANGO 3',
            data: [3, 2, 1, 2, 1, 1, 1, 2, 1, 1, 1, 1, 1, 1, 1],
            backgroundColor: 'red',
            stack: 'stack1',
        },
        {
            label: 'RANGO 4',
            data: [2, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1],
            backgroundColor: 'darkred',
            stack: 'stack1',
        },
    ],
};

const configGIC = {
    type: 'bar',
    data: initialDataGIC,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
        },
        scales: {
            x: {
                stacked: true,
            },
            y: {
                stacked: true,
                max: 120,
                ticks: {
                    callback: function (value) {
                        return value + '%';
                    },
                },
            },
        },
    },
};

function crearGraficoGIC() {
    if (cobranzaChartGIC) {
        cobranzaChartGIC.destroy();  // Si ya existe una instancia, la destruimos antes de crear una nueva
    }
    cobranzaChartGIC = new Chart(ctxGIC, configGIC);  // Crear el gráfico
}

async function cargarDatosGIC() {
    try {
        const response = await fetch('/obtener-gic');
        const datos = await response.json();

        if (response.ok) {
            console.log(datos); // Verifica si los datos son correctos

            // Actualiza los labels y datasets con los datos recuperados
            initialDataGIC.labels = datos.map(item => item.semana);  // Asumiendo que el campo 'semana' existe
            initialDataGIC.datasets[0].data = datos.map(item => item.en_tiempo);
            initialDataGIC.datasets[1].data = datos.map(item => item.rango1);
            initialDataGIC.datasets[2].data = datos.map(item => item.rango2);
            initialDataGIC.datasets[3].data = datos.map(item => item.rango3);
            initialDataGIC.datasets[4].data = datos.map(item => item.rango4);

            // Crear el gráfico con los datos cargados
            crearGraficoGIC();
        } else {
            console.error('Error al cargar los datos:', datos.error);
        }
    } catch (error) {
        console.error('Error al cargar los datos:', error);
    }
}

// Llamar a la función para cargar los datos de GIC cuando la página se cargue
window.addEventListener('load', cargarDatosGIC);

// Generar las opciones de semanas en el formulario GIC
const weekSelectGIC = document.getElementById('weekGIC');
initialDataGIC.labels.forEach((label) => {
    const option = document.createElement('option');
    option.value = label; // Nombre de la semana (ej: "SEMANA 6")
    option.textContent = label;
    weekSelectGIC.appendChild(option);
});

let charts = ['cobranzaChartCI', 'graficoX'];
let currentChartIndex = 0;

document.getElementById('prevChart').addEventListener('click', () => {
    currentChartIndex = (currentChartIndex === 0) ? charts.length - 1 : currentChartIndex - 1;
    updateChartVisibility();
});

document.getElementById('nextChart').addEventListener('click', () => {
    currentChartIndex = (currentChartIndex === charts.length - 1) ? 0 : currentChartIndex + 1;
    updateChartVisibility();
});

function updateChartVisibility() {
    charts.forEach((chartId, index) => {
        document.getElementById(chartId).style.display = (index === currentChartIndex) ? 'block' : 'none';
    });
}

const ctx = document.getElementById('graficoX').getContext('2d');
const semanas = Array.from({ length: 22 }, (_, i) => `Semana ${i * 2 + 6}`);

const data = {
    labels: semanas,
    datasets: [
        {
            label: 'EN TIEMPO',
            backgroundColor: 'lightgreen',
            data: Array.from({ length: 22 }, () => Math.random() * 10 + 85),
            stack: 'Stack 0',
        },
        {
            label: 'RANGO 1',
            backgroundColor: 'green',
            data: Array.from({ length: 22 }, () => Math.random() * 5),
            stack: 'Stack 0',
        },
        { 
            label: 'RANGO 2',
            backgroundColor: 'yellow',
            data: Array.from({ length: 22 }, () => Math.random() * 3),
            stack: 'Stack 0',
        },
        {
            label: 'RANGO 3',
            backgroundColor: 'orange',
            data: Array.from({ length: 22 }, () => Math.random() * 2),
            stack: 'Stack 0',
        },
        {
            label: 'RANGO 4',
            backgroundColor: 'red',
            data: Array.from({ length: 22 }, () => Math.random() * 5),
            stack: 'Stack 0',
        }
    ]
};

new Chart(ctx, {
    type: 'bar',
    data: data,
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true,
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + "%";
                    }
                }
            }
        }
    }
});

const ctxGICX = document.getElementById('graficoXGIC').getContext('2d');
const semanasGIC = Array.from({ length: 22 }, (_, i) => `Semana ${i * 2 + 6}`);

const dataGICX = {
    labels: semanasGIC,
    datasets: [
        {
            label: 'EN TIEMPO',
            backgroundColor: 'lightgreen',
            data: Array.from({ length: 22 }, () => Math.random() * 10 + 85),
            stack: 'Stack 0',
        },
        {
            label: 'RANGO 1',
            backgroundColor: 'green',
            data: Array.from({ length: 22 }, () => Math.random() * 5),
            stack: 'Stack 0',
        },
        {
            label: 'RANGO 2',
            backgroundColor: 'yellow',
            data: Array.from({ length: 22 }, () => Math.random() * 3),
            stack: 'Stack 0',
        },
        {
            label: 'RANGO 3',
            backgroundColor: 'orange',
            data: Array.from({ length: 22 }, () => Math.random() * 2),
            stack: 'Stack 0',
        },
        {
            label: 'RANGO 4',
            backgroundColor: 'red',
            data: Array.from({ length: 22 }, () => Math.random() * 5),
            stack: 'Stack 0',
        }
    ]
};

new Chart(ctxGICX, {
    type: 'bar',
    data: dataGICX,
    options: {
        responsive: true,
        scales: {
            x: {
                stacked: true
            },
            y: {
                stacked: true,
                beginAtZero: true,
                max: 100,
                ticks: {
                    callback: function(value) {
                        return value + "%";
                    }
                }
            }
        }
    }
});

// Configurar la semana actual en los formula
function setCurrentWeek() {
    const currentWeek = `SEMANA ${new Date().getWeek()}`;
    document.getElementById('weekCI').value = currentWeek;
    document.getElementById('weekGIC').value = currentWeek;
}

Date.prototype.getWeek = function() {
    const date = new Date(this.getTime());
    date.setHours(0, 0, 0, 0);
    date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
    const week1 = new Date(date.getFullYear(), 0, 4);
    return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
};

window.addEventListener('load', setCurrentWeek);

// Carrusel para GIC
let chartsGIC = ['cobranzaChartGIC', 'graficoXGIC'];
let currentChartIndexGIC = 0;

document.getElementById('prevChartGIC').addEventListener('click', () => {
    currentChartIndexGIC = (currentChartIndexGIC === 0) ? chartsGIC.length - 1 : currentChartIndexGIC - 1;
    updateChartVisibilityGIC();
});

document.getElementById('nextChartGIC').addEventListener('click', () => {
    currentChartIndexGIC = (currentChartIndexGIC === chartsGIC.length - 1) ? 0 : currentChartIndexGIC + 1;
    updateChartVisibilityGIC();
});

function updateChartVisibilityGIC() {
    chartsGIC.forEach((chartId, index) => {
        document.getElementById(chartId).style.display = (index === currentChartIndexGIC) ? 'block' : 'none';
    });
}

updateChartVisibility();
updateChartVisibilityGIC();
</script>
<br>
<br>
<h2>Evaluación de Desempeño de Proveedores CI</h2>
<canvas id="kpiChart2"></canvas>
<canvas id="kpiChart3" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->
<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartbtn" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartbtn" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Ingresar Datos Provedores IC</h2>
@can('adm.update')
<form id="dataFormKPI2">
  <label for="monthKPI2">Mes:</label>
  <select id="monthKPI2" name="monthKPI2"></select><br><br>

  <label for="performanceKPI2">Desempeño (%):</label>
  <input type="number" id="performanceKPI2" name="performanceKPI2" min="0" max="100" step="0.01"><br><br>

  <label for="areaKPI2">Área de cumplimiento (%):</label>
  <input type="number" id="areaKPI2" name="areaKPI2" min="0" max="100" step="0.01"><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  function createGradient(ctx, chartArea) {
    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
    gradient.addColorStop(0, 'rgb(233, 16, 16)'); // Rojo oscuro en la parte inferior
    gradient.addColorStop(1, 'rgb(255, 0, 0)'); // Rojo intenso en la parte superior
    return gradient;
  }

  const ctxKPI2 = document.getElementById('kpiChart2').getContext('2d');
  const ctxKPI3 = document.getElementById('kpiChart3').getContext('2d');

  // Datos iniciales del gráfico 1
  const dataLabelsKPI2 = ['ene-23', 'mar-23', 'may-23', 'jul-23', 'sep-23', 'nov-23', 'ene-24', 'mar-24', 'may-24', 'jul-24', 'sep-24', 'nov-24'];
  let performanceDataKPI2 = [97, 98, 98, 99, 97, 96, 97, 96, 98, 99, 97, 99.23];
  let areaDataKPI2 = [80, 80, 80, 95, 95, 95, 95, 96, 96, 96, 96, 97];

  // Datos iniciales del gráfico 2
  const dataLabelsKPI3 = ['ene-25', 'feb-25', 'mar-25', 'abr-25', 'may-25', 'jun-25', 'jul-25', 'ago-25', 'sep-25', 'oct-25', 'nov-25', 'dic-25'];
  let performanceDataKPI3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
  let areaDataKPI3 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

  // Configuración del gráfico 1
  const kpiChart2 = new Chart(ctxKPI2, {
    type: 'line',
    data: {
      labels: dataLabelsKPI2,
      datasets: [
        {
          label: 'Desempeño (%)',
          data: performanceDataKPI2,
          borderColor: '#87CEEB',
          backgroundColor: 'transparent',
          tension: 0.2,
        },
        {
          label: 'Área de cumplimiento',
          data: areaDataKPI2,
          backgroundColor: function(context) {
            const chart = context.chart;
            const { ctx, chartArea } = chart;
            if (!chartArea) return null;
            return createGradient(ctx, chartArea);
          },
          borderWidth: 0,
          fill: true,
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
          min: 70,
          max: 105,
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
  const kpiChart3 = new Chart(ctxKPI3, {
    type: 'line',
    data: {
      labels: dataLabelsKPI3,
      datasets: [
        {
          label: 'Desempeño (%)',
          data: performanceDataKPI3,
          borderColor: '#87CEEB',
          backgroundColor: 'transparent',
          tension: 0.2,
        },
        {
          label: 'Área de cumplimiento',
          data: areaDataKPI3,
          backgroundColor: function(context) {
            const chart = context.chart;
            const { ctx, chartArea } = chart;
            if (!chartArea) return null;
            return createGradient(ctx, chartArea);
          },
          borderWidth: 0,
          fill: true,
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
          min: 70,
          max: 105,
          ticks: {
            callback: function(value) {
              return value + '%';
            },
          },
        },
      },
    },
  });

  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Generar las opciones de meses en el formulario
  const monthSelectKPI2 = document.getElementById('monthKPI2');
  const generateMonthOptions = () => {
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();

    for (let year = currentYear; year <= currentYear + 2; year++) {
      for (let month = 0; month < 12; month++) {
        const monthLabel = new Date(year, month).toLocaleString('default', { month: 'short' }).toLowerCase();
        const yearLabel = year.toString().slice(-2);
        const fullLabel = `${monthLabel}-${yearLabel}`;

        const option = document.createElement('option');
        option.value = fullLabel;
        option.textContent = fullLabel;

        if (year === currentYear && month < currentMonth) {
          option.disabled = false; // Deshabilitar meses pasados
        }

        monthSelectKPI2.appendChild(option);
      }
    }
  };

  generateMonthOptions();

  // Establecer el mes actual como seleccionado por defecto
  const currentDate = new Date();
  const currentMonth = currentDate.toLocaleString('default', { month: 'short' }).toLowerCase();
  const currentYear = currentDate.getFullYear().toString().slice(-2);
  const currentMonthLabel = `${currentMonth}-${currentYear}`;
  monthSelectKPI2.value = currentMonthLabel;


  // Validar y actualizar SOLO el gráfico 2 (2025)
document.getElementById('dataFormKPI2').addEventListener('submit', (event) => {
  event.preventDefault();

  const month = monthSelectKPI2.value;
  const performance = parseFloat(document.getElementById('performanceKPI2').value);
  const area = parseFloat(document.getElementById('areaKPI2').value);

  // Enviar los datos al servidor
  axios.post('/proveedores-ci/store', {
    mes: month,
    desempeno: performance,
    area_cumplimiento: area,
  })
  .then(response => {
    if (response.data.success) {
      // Actualizar SOLO el gráfico 2 (2025)
      const index = dataLabelsKPI3.indexOf(month);
      
      if (index !== -1) {
        performanceDataKPI3[index] = performance;
        areaDataKPI3[index] = area;
        kpiChart3.update();
      }

      // Feedback visual
      const submitBtn = event.target.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = '✓ Guardado';
      setTimeout(() => {
        submitBtn.textContent = originalText;
      }, 2000);
    } else {
      console.error('Error en la respuesta del servidor:', response.data);
    }
  })
  .catch(error => {
    console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
  });
});

// Cargar datos iniciales SOLO para el gráfico 2 (2025)
axios.get('/proveedores-ci/get-data')
  .then(response => {
    const data = response.data;
    data.forEach(item => {
      const index = dataLabelsKPI3.indexOf(item.mes);
      
      if (index !== -1) {
        performanceDataKPI3[index] = item.desempeno;
        areaDataKPI3[index] = item.area_cumplimiento;
      }
    });
    kpiChart3.update();
  })
  .catch(error => {
    console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
  });

  // Alternar entre gráficos
  let currentChart = 1;
  document.getElementById('nextChartbtn').addEventListener('click', () => {
    if (currentChart === 1) {
      document.getElementById('kpiChart2').style.display = 'none';
      document.getElementById('kpiChart3').style.display = 'block';
      currentChart = 2;
    } else {
      document.getElementById('kpiChart2').style.display = 'block';
      document.getElementById('kpiChart3').style.display = 'none';
      currentChart = 1;
    }
  });

  document.getElementById('prevChartbtn').addEventListener('click', () => {
    if (currentChart === 1) {
      document.getElementById('kpiChart2').style.display = 'none';
      document.getElementById('kpiChart3').style.display = 'block';
      currentChart = 2;
    } else {
      document.getElementById('kpiChart2').style.display = 'block';
      document.getElementById('kpiChart3').style.display = 'none';
      currentChart = 1;
    }
  });
</script>
<br><br>
<h2>Cumplimiento de compras a tiempo IC</h2>
<canvas id="comprasChartIC"></canvas>
<canvas id="comprasChartIC2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartComprasIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartComprasIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Ingresar Datos - Compras IC</h2>
@can('adm.update')
<form id="dataFormComprasIC">
  <label for="monthComprasIC">Mes:</label>
  <select id="monthComprasIC" name="monthComprasIC"></select><br><br>

  <label for="performanceComprasIC">Desempeño (%):</label>
  <input type="number" id="performanceComprasIC" name="performanceComprasIC" min="0" max="100" step="0.01" ><br><br>

  <label for="areaComprasIC">Área de cumplimiento (%):</label>
  <input type="number" id="areaComprasIC" name="areaComprasIC" min="0" max="100" step="0.01"><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  function createGradient(ctx, chartArea) {
    const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
    gradient.addColorStop(0, 'rgb(233, 16, 16)'); // Rojo oscuro en la parte inferior
    gradient.addColorStop(1, 'rgb(255, 0, 0)'); // Rojo intenso en la parte superior
    return gradient;
  }

  // Contexto y datos iniciales del gráfico 1
  const ctxComprasIC = document.getElementById('comprasChartIC').getContext('2d');
  const ctxComprasIC2 = document.getElementById('comprasChartIC2').getContext('2d');

  const dataLabelsComprasIC = [
    'ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23',
    'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'
  ];

  let performanceDataComprasIC = [
    86, 86, 79, 82, 86, 84, 92, 80, 81, 86, 86, 84, 96, 88, 94, 94, 91, 89, 89, 94, 89, 85, 86, 88.75
  ];

  let areaDataComprasIC = [
    70, 70, 70, 70, 70, 80, 80, 80, 80, 80, 80, 80, 80, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85, 85
  ];

  // Datos iniciales del gráfico 2
  const dataLabelsComprasIC2 = [
    'ene-25', 'feb-25', 'mar-25', 'abr-25', 'may-25', 'jun-25', 'jul-25', 'ago-25', 'sep-25', 'oct-25', 'nov-25', 'dic-25'
  ];

  let performanceDataComprasIC2 = [
    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
  ];

  let areaDataComprasIC2 = [
    0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0
  ];

  // Configuración del gráfico 1
  const comprasChartIC = new Chart(ctxComprasIC, {
    type: 'line',
    data: {
      labels: dataLabelsComprasIC,
      datasets: [
        {
          label: 'Desempeño (%)',
          data: performanceDataComprasIC,
          borderColor: '#87CEEB',
          backgroundColor: 'transparent',
          tension: 0.2,
        },
        {
          label: 'Área de cumplimiento',
          data: areaDataComprasIC,
          backgroundColor: function(context) {
            const chart = context.chart;
            const { ctx, chartArea } = chart;
            if (!chartArea) return null;
            return createGradient(ctx, chartArea);
          },
          borderWidth: 0,
          fill: true,
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
          min: 60,
          max: 100,
          ticks: {
            callback: function(value) {
              return value + '%';
            },
          },
        },
      },
    },
  });
// Configuración del gráfico 2 (2025) - Versión corregida
const comprasChartIC2 = new Chart(ctxComprasIC2, {
  type: 'line',
  data: {
    labels: dataLabelsComprasIC2,
    datasets: [
      {
        label: 'Desempeño (%)',
        data: performanceDataComprasIC2,
        borderColor: '#0066cc', // Azul más oscuro
        backgroundColor: 'transparent',
        borderWidth: 3, // Línea más gruesa
        tension: 0.2,
        pointBackgroundColor: '#0066cc',
        pointRadius: 5,
        pointHoverRadius: 7,
        pointBorderWidth: 2,
        order: 1 // Se dibuja primero
      },
      {
        label: 'Área de cumplimiento',
        data: areaDataComprasIC2,
        backgroundColor: 'rgb(255, 0, 0)', // Rojo más transparente
        borderWidth: 0,
        fill: true,
        order: 2 // Se dibuja después
      }
    ]
  },
  options: {
    responsive: true,
    plugins: {
      legend: { 
        display: true, // Temporalmente visible para diagnóstico
        position: 'top'
      },
      tooltip: {
        callbacks: {
          label: function(tooltipItem) {
            return tooltipItem.dataset.label + ': ' + tooltipItem.raw.toFixed(2) + '%';
          }
        }
      }
    },
    scales: {
      y: {
        min: 60,
        max: 100,
        ticks: {
          callback: function(value) {
            return value + '%';
          }
        }
      }
    }
  }
});

  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Generar las opciones de meses en el formulario
  const monthSelectComprasIC = document.getElementById('monthComprasIC');
  const generateMonthOptionsl = () => {
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();

    for (let year = currentYear; year <= currentYear + 2; year++) {
      for (let month = 0; month < 12; month++) {
        const monthLabel = new Date(year, month).toLocaleString('default', { month: 'short' }).toLowerCase();
        const yearLabel = year.toString().slice(-2);
        const fullLabel = `${monthLabel}-${yearLabel}`;

        const option = document.createElement('option');
        option.value = fullLabel;
        option.textContent = fullLabel;

        if (year === currentYear && month < currentMonth) {
          option.disabled = false; // Deshabilitar meses pasados
        }

        monthSelectComprasIC.appendChild(option);
      }
    }
  };

  generateMonthOptionsl();

  // Establecer el mes actual como seleccionado por defecto
  const currentDatel = new Date();
  const currentMonthl = currentDatel.toLocaleString('default', { month: 'short' }).toLowerCase();
  const currentYearl = currentDatel.getFullYear().toString().slice(-2);
  const currentMonthLabel2 = `${currentMonthl}-${currentYearl}`;
  monthSelectComprasIC.value = currentMonthLabel2;

  document.getElementById('dataFormComprasIC').addEventListener('submit', (event) => {
  event.preventDefault();

  const month = monthSelectComprasIC.value;
  const performance = parseFloat(document.getElementById('performanceComprasIC').value);
  const area = parseFloat(document.getElementById('areaComprasIC').value);

  // Validación
  if (performance < 0 || performance > 100 || area < 0 || area > 100) {
    alert('Los valores deben estar entre 0% y 100%.');
    return;
  }

  // Determinar qué gráfico está visible
  const isChart2Visible = document.getElementById('comprasChartIC2').style.display !== 'none';

  // Enviar datos al servidor
  axios.post('/cumplimiento-compras-ic/store', {
    mes: month,
    desempeno: performance,
    area_cumplimiento: area,
  })
  .then(response => {
    if (response.data.success) {
      // Actualizar el gráfico correspondiente
      if (isChart2Visible) {
        const index = dataLabelsComprasIC2.indexOf(month);
        if (index !== -1) {
          performanceDataComprasIC2[index] = performance;
          areaDataComprasIC2[index] = area;
          comprasChartIC2.update();
        }
      } else {
        const index = dataLabelsComprasIC.indexOf(month);
        if (index !== -1) {
          performanceDataComprasIC[index] = performance;
          areaDataComprasIC[index] = area;
          comprasChartIC.update();
        }
      }
      
      // Feedback visual
      const submitBtn = event.target.querySelector('button[type="submit"]');
      const originalText = submitBtn.textContent;
      submitBtn.textContent = '✓ Guardado';
      setTimeout(() => {
        submitBtn.textContent = originalText;
      }, 2000);
    }
  })
  .catch(error => {
    console.error('Error:', error);
  });
});

// Cargar datos iniciales para 2025 - Versión corregida
axios.get('/cumplimiento-compras-ic/get-data')
  .then(response => {
    const data = response.data;
    data.forEach(item => {
      // Actualizar gráfico 1 (2023-2024)
      const index1 = dataLabelsComprasIC.indexOf(item.mes);
      if (index1 !== -1) {
        performanceDataComprasIC[index1] = item.desempeno;
        areaDataComprasIC[index1] = item.area_cumplimiento;
      }
      
      // Actualizar gráfico 2 (2025)
      const index2 = dataLabelsComprasIC2.indexOf(item.mes);
      if (index2 !== -1) {
        performanceDataComprasIC2[index2] = item.desempeno;
        areaDataComprasIC2[index2] = item.area_cumplimiento;
      }
    });
    comprasChartIC.update();
    comprasChartIC2.update();
  })
  .catch(error => {
    console.error('Error:', error);
  });
  // Alternar entre gráficos
  let currentChartComprasIC = 1;
  document.getElementById('nextChartComprasIC').addEventListener('click', () => {
    if (currentChartComprasIC === 1) {
      document.getElementById('comprasChartIC').style.display = 'none';
      document.getElementById('comprasChartIC2').style.display = 'block';
      currentChartComprasIC = 2;
    } else {
      document.getElementById('comprasChartIC').style.display = 'block';
      document.getElementById('comprasChartIC2').style.display = 'none';
      currentChartComprasIC = 1;
    }
  });

  document.getElementById('prevChartComprasIC').addEventListener('click', () => {
    if (currentChartComprasIC === 1) {
      document.getElementById('comprasChartIC').style.display = 'none';
      document.getElementById('comprasChartIC2').style.display = 'block';
      currentChartComprasIC = 2;
    } else {
      document.getElementById('comprasChartIC').style.display = 'block';
      document.getElementById('comprasChartIC2').style.display = 'none';
      currentChartComprasIC = 1;
    }
  });
</script>
@endsection