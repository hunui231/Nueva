@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
<style>
  button {
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
    background:rgb(255, 0, 0);
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    display: inline-block;
  }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<h2 class="box-title">Evaluación de Desempeño de Proveedores GIC</h2>
<canvas id="kpiChartGIC"></canvas>
<canvas id="kpiChartGIC2" style="display: none;"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartKPI_GIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartKPI_GIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Ingresar Datos Proveedores GIC</h2>
@can('admgic.update')
<form id="dataFormKPI_GIC">
  <label for="monthKPI_GIC">Mes:</label>
  <select id="monthKPI_GIC" name="monthKPI_GIC"></select><br><br>

  <label for="performanceKPI_GIC">Desempeño (%):</label>
  <input type="number" id="performanceKPI_GIC" name="performanceKPI_GIC" min="0" max="100" step="0.01"><br><br>
  
  @can('admin.update')
  <label for="areaKPI_GIC">Área de cumplimiento (%):</label>
  <input type="number" id="areaKPI_GIC" name="areaKPI_GIC" min="0" max="100" step="0.01"><br><br>
  @else
  <input type="hidden" id="areaKPI_GIC" name="areaKPI_GIC" value="">
  @endcan
  
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico GIC
  const ctxKPI_GIC = document.getElementById('kpiChartGIC').getContext('2d');
  const ctxKPI_GIC2 = document.getElementById('kpiChartGIC2').getContext('2d');

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
  const dataLabelsKPI_GIC = generateMonthOptions(23, 24); // 2023-2024
  const dataLabelsKPI_GIC2 = generateMonthOptions(25, 25); // 2025

  let performanceDataKPI_GIC = Array(24).fill(null);
  let areaDataKPI_GIC = Array(24).fill(null);
  let performanceDataKPI_GIC2 = Array(12).fill(0);
  let areaDataKPI_GIC2 = Array(12).fill(0);

  // Configuración del gráfico GIC 1
  const kpiChartGIC = new Chart(ctxKPI_GIC, {
    type: 'line',
    data: {
      labels: dataLabelsKPI_GIC,
      datasets: [
        {
          label: 'Desempeño (%)',
          data: performanceDataKPI_GIC,
          borderColor: '#87CEEB',
          backgroundColor: 'transparent',
          tension: 0.2,
        },
        {
          label: 'Área de cumplimiento (%)',
          data: areaDataKPI_GIC,
          backgroundColor: 'rgba(255, 0, 0, 0.86)', 
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
          min: 0,
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

  // Configuración del gráfico GIC 2
  const kpiChartGIC2 = new Chart(ctxKPI_GIC2, {
    type: 'line',
    data: {
      labels: dataLabelsKPI_GIC2,
      datasets: [
        {
          label: 'Desempeño (%)',
          data: performanceDataKPI_GIC2,
          borderColor: '#87CEEB',
          backgroundColor: 'transparent',
          tension: 0.2,
        },
        {
          label: 'Área de cumplimiento (%)',
          data: areaDataKPI_GIC2,
          backgroundColor: 'rgba(255, 0, 0, 0.86)', 
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
          min: 0,
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

  // Inicialización del formulario
  const initKPIForm = () => {
    const form = document.getElementById('dataFormKPI_GIC');
    if (!form) {
      console.log('Formulario no disponible para este usuario');
      return;
    }

    // Generar opciones de meses
    const monthSelect = document.getElementById('monthKPI_GIC');
    if (monthSelect) {
      dataLabelsKPI_GIC.concat(dataLabelsKPI_GIC2).forEach((label) => {
        const option = document.createElement('option');
        option.value = label;
        option.textContent = label;
        monthSelect.appendChild(option);
      });

      // Establecer mes actual como valor predeterminado
      const currentDate = new Date();
      const month = currentDate.toLocaleString('default', { month: 'short' }).toLowerCase();
      const year = currentDate.getFullYear().toString().slice(-2);
      monthSelect.value = `${month}-${year}`;
    }

    form.addEventListener('submit', async (event) => {
      event.preventDefault();

      const monthSelect = document.getElementById('monthKPI_GIC');
      const performanceInput = document.getElementById('performanceKPI_GIC');
      const areaInput = document.getElementById('areaKPI_GIC');

      if (!monthSelect || !performanceInput) {
        console.error('Elementos requeridos no encontrados');
        return;
      }

      try {
        const formData = {
          mes: monthSelect.value,
          desempeno: performanceInput.value || null,
          area_cumplimiento: areaInput ? areaInput.value || null : null
        };

        // Validación de año
        const selectedYear = parseInt(formData.mes.split('-')[1], 10) + 2000;
        if (selectedYear < new Date().getFullYear()) {
          alert('No se pueden ingresar datos para años anteriores.');
          return;
        }

        const response = await axios.post('/proveedores-gic/store', formData);

        if (response.data.success) {
          updateChartData(formData.mes, formData.desempeno, formData.area_cumplimiento);
          showSubmitFeedback(event.target);
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Ocurrió un error al procesar la solicitud');
      }
    });
  };

  // Actualizar datos de gráficos
  function updateChartData(month, performance, area) {
    const performanceNum = performance ? parseFloat(performance) : null;
    const areaNum = area ? parseFloat(area) : null;

    // Actualizar gráfico 1 (2023-2024)
    const index = dataLabelsKPI_GIC.indexOf(month);
    if (index !== -1) {
      if (performanceNum !== null) performanceDataKPI_GIC[index] = performanceNum;
      if (areaNum !== null) areaDataKPI_GIC[index] = areaNum;
      kpiChartGIC.update();
    }

    // Actualizar gráfico 2 (2025)
    const index2 = dataLabelsKPI_GIC2.indexOf(month);
    if (index2 !== -1) {
      if (performanceNum !== null) performanceDataKPI_GIC2[index2] = performanceNum;
      if (areaNum !== null) areaDataKPI_GIC2[index2] = areaNum;
      kpiChartGIC2.update();
    }
  }

  // Feedback visual
  function showSubmitFeedback(form) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    
    submitBtn.textContent = '✓ Actualizado';
    submitBtn.style.backgroundColor = '#28a745';
    
    setTimeout(() => {
      submitBtn.textContent = originalText;
      submitBtn.style.backgroundColor = '#28a745';
    }, 2000);
  }

  // Obtener datos iniciales del servidor
  async function fetchDataKPI_GIC() {
    try {
      const response = await axios.get('/proveedores-gic/get-data');
      const data = response.data;
      
      data.forEach(item => {
        const index = dataLabelsKPI_GIC.indexOf(item.mes);
        if (index !== -1) {
          performanceDataKPI_GIC[index] = item.desempeno;
          areaDataKPI_GIC[index] = item.area_cumplimiento;
        } else {
          const index2 = dataLabelsKPI_GIC2.indexOf(item.mes);
          if (index2 !== -1) {
            performanceDataKPI_GIC2[index2] = item.desempeno;
            areaDataKPI_GIC2[index2] = item.area_cumplimiento;
          }
        }
      });
      
      kpiChartGIC.update();
      kpiChartGIC2.update();
    } catch (error) {
      console.error('Error al obtener datos:', error);
    }
  }

  // Inicialización al cargar la página
  document.addEventListener('DOMContentLoaded', () => {
    initKPIForm();
    fetchDataKPI_GIC();
  });

  // Alternar entre gráficos
  let currentChartKPI_GIC = 1;
  document.getElementById('nextChartKPI_GIC').addEventListener('click', () => {
    toggleCharts();
  });

  document.getElementById('prevChartKPI_GIC').addEventListener('click', () => {
    toggleCharts();
  });

  function toggleCharts() {
    if (currentChartKPI_GIC === 1) {
      document.getElementById('kpiChartGIC').style.display = 'none';
      document.getElementById('kpiChartGIC2').style.display = 'block';
      currentChartKPI_GIC = 2;
    } else {
      document.getElementById('kpiChartGIC').style.display = 'block';
      document.getElementById('kpiChartGIC2').style.display = 'none';
      currentChartKPI_GIC = 1;
    }
  }
</script>
<br><br>
<meta name="csrf-token" content="{{ csrf_token() }}">
<h2 class="box-title">Cumplimiento de Compras a Tiempo GIC</h2>
<canvas id="comprasChartGIC"></canvas>
<canvas id="comprasChartGIC2" style="display: none;"></canvas>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartCompras_GIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartCompras_GIC" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Ingresar Datos - Compras GIC</h2>
@can('admgic.update')
<form id="dataFormCompras_GIC">
  <label for="monthCompras_GIC">Mes:</label>
  <select id="monthCompras_GIC" name="monthCompras_GIC"></select><br><br>

  <label for="performanceCompras_GIC">Desempeño (%):</label>
  <input type="number" id="performanceCompras_GIC" name="performanceCompras_GIC" min="0" max="100" step="0.01"><br><br>
  @can('admin.update')
  <label for="areaCompras_GIC">Área de cumplimiento (%):</label>
  <input type="number" id="areaCompras_GIC" name="areaCompras_GIC" min="0" max="100" step="0.01"><br><br>
   @endcan
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico
  const ctxCompras_GIC = document.getElementById('comprasChartGIC').getContext('2d');
  const ctxCompras_GIC2 = document.getElementById('comprasChartGIC2').getContext('2d');

  // Función para generar opciones de meses y años
  function generateMonthOptionsCompras(startYear, endYear) {
    const months = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    let options = [];

    for (let year = startYear; year <= endYear; year++) {
      months.forEach((month) => {
        options.push(`${month}-${year.toString().slice(-2)}`);
      });
    }

    return options;
  }

  const currentYearCompras = new Date().getFullYear();
  const dataLabelsCompras_GIC = generateMonthOptionsCompras(23, 24); // Genera desde 2023 hasta 2024
  const dataLabelsCompras_GIC2 = generateMonthOptionsCompras(25, 25); // Genera solo 2025

  let performanceDataCompras_GIC = Array(24).fill(null); // Datos iniciales para el gráfico 1
  let areaDataCompras_GIC = Array(24).fill(null); // Datos iniciales para el gráfico 1

  let performanceDataCompras_GIC2 = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let areaDataCompras_GIC2 = Array(12).fill(0); // Datos iniciales para el gráfico 2

  // Configuración del gráfico 1
  const comprasChartGIC = new Chart(ctxCompras_GIC, {
    type: 'line',
    data: {
      labels: dataLabelsCompras_GIC,
      datasets: [
        {
          label: 'Desempeño (%)',
          data: performanceDataCompras_GIC,
          borderColor: '#87CEEB',
          backgroundColor: 'transparent',
          tension: 0.2,
        },
        {
          label: 'Área de cumplimiento',
          data: areaDataCompras_GIC,
          backgroundColor: 'rgba(255, 0, 0, 0.96)', // Fondo rojo semitransparente
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

  // Configuración del gráfico 2
  const comprasChartGIC2 = new Chart(ctxCompras_GIC2, {
    type: 'line',
    data: {
      labels: dataLabelsCompras_GIC2,
      datasets: [
        {
          label: 'Desempeño (%)',
          data: performanceDataCompras_GIC2,
          borderColor: '#87CEEB',
          backgroundColor: 'transparent',
          tension: 0.2,
        },
        {
          label: 'Área de cumplimiento',
          data: areaDataCompras_GIC2,
          backgroundColor: 'rgba(255, 0, 0, 0.96)', 
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

  // Generar las opciones de meses en el formulario
  const monthSelectCompras_GIC = document.getElementById('monthCompras_GIC');
  dataLabelsCompras_GIC.concat(dataLabelsCompras_GIC2).forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectCompras_GIC.appendChild(option);
  });

  // Obtener la fecha actual
  const currentDateCompras = new Date();

  // Formatear el mes abreviado (ej: "Feb")
  const monthCompras = currentDateCompras.toLocaleString('default', { month: 'short' }).toLowerCase();

  // Formatear el año en dos dígitos (ej: "25")
  const yearCompras = currentDateCompras.getFullYear().toString().slice(-2);

  // Crear el formato "MMM-AA" (ej: "Feb-25")
  const currentMonthYearCompras = `${monthCompras}-${yearCompras}`;

  // Establecer el valor predeterminado como el mes actual
  monthSelectCompras_GIC.value = currentMonthYearCompras;

  // Validar y actualizar el gráfico
  document.getElementById('dataFormCompras_GIC').addEventListener('submit', (event) => {
  event.preventDefault();

  // 1. Obtención SEGURA de elementos
  const monthSelect = document.getElementById('monthCompras_GIC');
  const performanceInput = document.getElementById('performanceCompras_GIC');
  const areaInput = document.getElementById('areaCompras_GIC'); // Puede ser null

  // Verificación básica de elementos requeridos
  if (!monthSelect || !performanceInput) {
    console.error('Elementos requeridos no encontrados');
    return;
  }

  // 2. Obtención de valores
  const month = monthSelect.value;
  const performance = performanceInput.value;
  const area = areaInput ? areaInput.value : null;
    // 3. Conversión a números (con manejo de nulos como en tu primer código)
    const performanceNum = performance === "" ? null : parseFloat(performance);
    const areaNum = area === "" ? null : parseFloat(area);

    // 4. Validación de año (igual que tu versión)
    const selectedYear = parseInt(month.split('-')[1], 10) + 2000;
    if (selectedYear < new Date().getFullYear()) {
        alert('No se pueden ingresar datos para años anteriores.');
        return;
    }

    // 5. Envío al servidor (igual que tu versión)
    axios.post('/compras-gic/store', {
        mes: month,
        desempeno: performanceNum,
        area_cumplimiento: areaNum,
    })
    .then(response => {
        if (response.data.success) {
            // 6. Actualización de gráficos (optimizada)
            const index = dataLabelsCompras_GIC.indexOf(month);
            const index2 = dataLabelsCompras_GIC2.indexOf(month);
            
            if (index !== -1) {
                if (performanceNum !== null) performanceDataCompras_GIC[index] = performanceNum;
                if (areaNum !== null) areaDataCompras_GIC[index] = areaNum;
                comprasChartGIC.update();
            }
            
            if (index2 !== -1) {
                if (performanceNum !== null) performanceDataCompras_GIC2[index2] = performanceNum;
                if (areaNum !== null) areaDataCompras_GIC2[index2] = areaNum;
                comprasChartGIC2.update();
            }

            // Feedback visual (mejorado pero discreto)
            const submitBtn = event.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.textContent = '✓ Actualizado';
            setTimeout(() => {
                submitBtn.textContent = originalText;
            }, 2000);
        } else {
            console.error('Error en el servidor:', response.data);
        }
    })
    .catch(error => {
        console.error('Error de conexión:', error);
    });
});
  // Obtener los datos actualizados del servidor
  function fetchDataCompras_GIC() {
    axios.get('/compras-gic/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsCompras_GIC.indexOf(item.mes);
          if (index !== -1) {
            performanceDataCompras_GIC[index] = item.desempeno;
            areaDataCompras_GIC[index] = item.area_cumplimiento;
          } else {
            const index2 = dataLabelsCompras_GIC2.indexOf(item.mes);
            if (index2 !== -1) {
              performanceDataCompras_GIC2[index2] = item.desempeno;
              areaDataCompras_GIC2[index2] = item.area_cumplimiento;
            }
          }
        });
        comprasChartGIC.update(); // Actualizar el gráfico 1
        comprasChartGIC2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchDataCompras_GIC();

  // Alternar entre gráficos
  let currentChartCompras_GIC = 1;
  document.getElementById('nextChartCompras_GIC').addEventListener('click', () => {
    if (currentChartCompras_GIC === 1) {
      document.getElementById('comprasChartGIC').style.display = 'none';
      document.getElementById('comprasChartGIC2').style.display = 'block';
      currentChartCompras_GIC = 2;
    } else {
      document.getElementById('comprasChartGIC').style.display = 'block';
      document.getElementById('comprasChartGIC2').style.display = 'none';
      currentChartCompras_GIC = 1;
    }
  });

  document.getElementById('prevChartCompras_GIC').addEventListener('click', () => {
    if (currentChartCompras_GIC === 1) {
      document.getElementById('comprasChartGIC').style.display = 'none';
      document.getElementById('comprasChartGIC2').style.display = 'block';
      currentChartCompras_GIC = 2;
    } else {
      document.getElementById('comprasChartGIC').style.display = 'block';
      document.getElementById('comprasChartGIC2').style.display = 'none';
      currentChartCompras_GIC = 1;
    }
  });
</script>
@endsection