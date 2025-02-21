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
</style>
<h2>Evaluación de Desempeño de Proveedores GIC</h2>
<canvas id="kpiChartGIC"></canvas>
<meta name="csrf-token" content="{{ csrf_token() }}">

<h2>Ingresar Datos Proveedores GIC</h2>
@can('admgic.update')
<form id="dataFormKPI_GIC">
  <label for="monthKPI_GIC">Mes:</label>
  <select id="monthKPI_GIC" name="monthKPI_GIC">
    <!-- Las opciones se generarán dinámicamente con JavaScript -->
  </select><br><br>

  <label for="performanceKPI_GIC">Desempeño (%):</label>
  <input type="number" id="performanceKPI_GIC" name="performanceKPI_GIC" min="0" max="100" step="0.01" required><br><br>

  <label for="areaKPI_GIC">Área de cumplimiento (%):</label>
  <input type="number" id="areaKPI_GIC" name="areaKPI_GIC" min="0" max="100" step="0.01" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  const ctxKPI_GIC = document.getElementById('kpiChartGIC').getContext('2d');

  // Datos iniciales del gráfico GIC
  const dataLabelsKPI_GIC = [
    'ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 
    'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 
    'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 
    'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'
  ];
  let performanceDataKPI_GIC = Array(24).fill(null); // Inicializar con valores nulos (24 meses)
  let areaDataKPI_GIC = Array(24).fill(null); // Inicializar con valores nulos (24 meses)

  // Configuración del gráfico GIC
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
          backgroundColor: 'rgba(255, 0, 0, 0.86)', // Fondo rojo semitransparente
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

  // Generar las opciones de meses en el formulario
  const monthSelectKPI_GIC = document.getElementById('monthKPI_GIC');
  dataLabelsKPI_GIC.forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectKPI_GIC.appendChild(option);
  });

  // Validar y actualizar el gráfico GIC
  document.getElementById('dataFormKPI_GIC').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectKPI_GIC.value;
    const performance = parseFloat(document.getElementById('performanceKPI_GIC').value);
    const area = parseFloat(document.getElementById('areaKPI_GIC').value);

    // Validar que los valores estén dentro del rango
    if (performance < 0 || performance > 100 || area < 0 || area > 100) {
      alert('Los valores deben estar entre 0% y 100%');
      return;
    }

    // Enviar los datos al servidor
    axios.post('/proveedores-gic/store', {
      mes: month,
      desempeno: performance,
      area_cumplimiento: area,
    })
    .then(response => {
      if (response.data.success) {
        // Actualizar los datos del gráfico
        const index = dataLabelsKPI_GIC.indexOf(month);
        performanceDataKPI_GIC[index] = performance;
        areaDataKPI_GIC[index] = area;

        kpiChartGIC.update(); // Actualizar el gráfico
      } else {
        console.error('Error en la respuesta del servidor:', response.data);
      }
    })
    .catch(error => {
      console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
    });
  });

  // Obtener los datos actualizados del servidor
  function fetchData() {
    axios.get('/proveedores-gic/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsKPI_GIC.indexOf(item.mes);
          if (index !== -1) {
            performanceDataKPI_GIC[index] = item.desempeno;
            areaDataKPI_GIC[index] = item.area_cumplimiento;
          }
        });
        kpiChartGIC.update(); // Actualizar el gráfico
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchData();
</script>


<br><br>
<meta name="csrf-token" content="{{ csrf_token() }}">

<h2>Cumplimiento de Compras a Tiempo GIC</h2>
<canvas id="comprasChartGIC"></canvas>

<h2>Ingresar Datos - Compras GIC</h2>
@can('admgic.update')
<form id="dataFormCompras_GIC">
  <label for="monthCompras_GIC">Mes:</label>
  <select id="monthCompras_GIC" name="monthCompras_GIC">
  </select><br><br>

  <label for="performanceCompras_GIC">Desempeño (%):</label>
  <input type="number" id="performanceCompras_GIC" name="performanceCompras_GIC" min="0" max="100" step="0.01" required><br><br>

  <label for="areaCompras_GIC">Área de cumplimiento (%):</label>
  <input type="number" id="areaCompras_GIC" name="areaCompras_GIC" min="0" max="100" step="0.01" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico de compras GIC
  const ctxCompras_GIC = document.getElementById('comprasChartGIC').getContext('2d');

  const dataLabelsCompras_GIC = [
    'ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23',
    'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'
  ];

  let performanceDataCompras_GIC = Array(24).fill(null); // Inicializar con valores nulos
  let areaDataCompras_GIC = Array(24).fill(null); // Inicializar con valores nulos

  // Configuración del gráfico de compras GIC
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

  // Generar las opciones de meses en el formulario de compras GIC
  const monthSelectCompras_GIC = document.getElementById('monthCompras_GIC');
  dataLabelsCompras_GIC.forEach((label, index) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectCompras_GIC.appendChild(option);
  });

  // Validar y actualizar el gráfico de compras GIC
  document.getElementById('dataFormCompras_GIC').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectCompras_GIC.value;
    const performance = parseFloat(document.getElementById('performanceCompras_GIC').value);
    const area = parseFloat(document.getElementById('areaCompras_GIC').value);

    // Validar que los valores estén dentro del rango
    if (performance < 0 || performance > 100 || area < 0 || area > 100) {
      alert('Los valores deben estar entre 0% y 100%.');
      return;
    }

    // Enviar los datos al servidor
    axios.post('/compras-gic/store', {
      mes: month,
      desempeno: performance,
      area_cumplimiento: area,
    })
    .then(response => {
      if (response.data.success) {
        // Actualizar los datos del gráfico
        const index = dataLabelsCompras_GIC.indexOf(month);
        performanceDataCompras_GIC[index] = performance;
        areaDataCompras_GIC[index] = area;

        comprasChartGIC.update(); // Actualizar el gráfico
      } else {
        console.error('Error en la respuesta del servidor:', response.data);
      }
    })
    .catch(error => {
      console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
    });
  });

  // Obtener los datos actualizados del servidor
  function fetchData() {
    axios.get('/compras-gic/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsCompras_GIC.indexOf(item.mes);
          if (index !== -1) {
            performanceDataCompras_GIC[index] = item.desempeno;
            areaDataCompras_GIC[index] = item.area_cumplimiento;
          }
        });
        comprasChartGIC.update(); // Actualizar el gráfico
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchData();
</script>

@endsection