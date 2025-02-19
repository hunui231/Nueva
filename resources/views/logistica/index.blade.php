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
<meta name="csrf-token" content="{{ csrf_token() }}">

<h2>Entrega de Materiales a Tiempo</h2>
<canvas id="grafico"></canvas>


<!-- Formulario para ingresar datos -->
<h2>Ingresar Datos - Entrega de Materiales</h2>
<form id="dataFormEntregaMateriales">
  <label for="monthEntregaMateriales">Mes:</label>
  <select id="monthEntregaMateriales" name="monthEntregaMateriales">
    <!-- Las opciones se generarán dinámicamente con JavaScript -->
  </select><br><br>

  <label for="desempenoEntregaMateriales">Desempeño (%):</label>
  <input type="number" id="desempenoEntregaMateriales" name="desempenoEntregaMateriales" min="0" max="100" step="0.01" required><br><br>

  <label for="areaCumplimientoEntregaMateriales">Área de cumplimiento (%):</label>
  <input type="number" id="areaCumplimientoEntregaMateriales" name="areaCumplimientoEntregaMateriales" min="0" max="100" step="0.01" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico
  const ctx = document.getElementById('grafico').getContext('2d');

  const dataLabels = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"]
  ;
  let desempenoData = Array(13).fill(100); // Inicializar con 100%
  let areaCumplimientoData = Array(13).fill(100); // Inicializar con 100%

  // Configuración del gráfico
  const grafico = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: dataLabels,
      datasets: [
        {
          label: "Entrega a Tiempo",
          data: desempenoData,
          backgroundColor: 'deepskyblue',
          borderColor: 'deepskyblue',
          borderWidth: 1,
        },
        {
          label: "Línea de 100%",
          data: areaCumplimientoData,
          type: 'line',
          borderColor: 'red',
          borderWidth: 2,
          pointRadius: 0,
        },
      ],
    },
    options: {
      responsive: true,
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
      plugins: {
        legend: {
          display: false, // Ocultar leyenda
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.raw + "%";
            },
          },
        },
      },
    },
  });

  // Generar las opciones de meses en el formulario
  const monthSelectEntregaMateriales = document.getElementById('monthEntregaMateriales');
  dataLabels.forEach((label, index) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectEntregaMateriales.appendChild(option);
  });

  // Validar y actualizar el gráfico
  document.getElementById('dataFormEntregaMateriales').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectEntregaMateriales.value;
    const desempeno = parseFloat(document.getElementById('desempenoEntregaMateriales').value);
    const areaCumplimiento = parseFloat(document.getElementById('areaCumplimientoEntregaMateriales').value);

    // Validar que los valores estén dentro del rango
    if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
      alert('Los valores deben estar entre 0% y 100%.');
      return;
    }

    // Enviar los datos al servidor
    axios.post('/entrega-materiales/store', {
      mes: month,
      desempeno: desempeno,
      area_cumplimiento: areaCumplimiento,
    })
    .then(response => {
      if (response.data.success) {
        // Actualizar los datos del gráfico
        const index = dataLabels.indexOf(month);
        desempenoData[index] = desempeno;
        areaCumplimientoData[index] = areaCumplimiento;

        grafico.update(); // Actualizar el gráfico
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
    axios.get('/entrega-materiales/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabels.indexOf(item.mes);
          if (index !== -1) {
            desempenoData[index] = item.desempeno;
            areaCumplimientoData[index] = item.area_cumplimiento;
          }
        });
        grafico.update(); // Actualizar el gráfico
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchData();
</script>
<br><br>
<h2>Información de Inventarios</h2>
<canvas id="inventarioChart"></canvas>

<!-- Formulario para ingresar datos -->
<h2>Ingresar Datos - Inventarios</h2>
<form id="dataFormInventario">
  <label for="monthInventario">Mes:</label>
  <select id="monthInventario" name="monthInventario">
    <!-- Las opciones se generarán dinámicamente con JavaScript -->
  </select><br><br>

  <label for="desempenoInventario">Desempeño Inventario (%):</label>
  <input type="number" id="desempenoInventario" name="desempenoInventario" min="0" max="100" step="0.01" required><br><br>

  <label for="areaCumplimientoInventario">Área de Cumplimiento (%):</label>
  <input type="number" id="areaCumplimientoInventario" name="areaCumplimientoInventario" min="0" max="100" step="0.01" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Contexto y datos iniciales del gráfico
const ctxInventario = document.getElementById('inventarioChart').getContext('2d');

const dataLabelsInventario = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];
let desempenoDataInventario = Array(13).fill(null);
let areaCumplimientoDataInventario = Array(13).fill(null);

// Configuración del gráfico
const inventarioChart = new Chart(ctxInventario, {
  type: 'line',
  data: {
    labels: dataLabelsInventario, // Cambié 'dataLabels' a 'dataLabelsInventario'
    datasets: [
      {
        label: "Desempeño Inventario",
        data: desempenoData,
        borderColor: 'deepskyblue',
        borderWidth: 2,
        pointBackgroundColor: 'deepskyblue',
        pointRadius: 4,
        fill: false,
        tension: 0.3,
      },
      {
        label: "Área de Cumplimiento",
        data: areaCumplimientoData,
        backgroundColor: 'rgba(255, 0, 0, 0.85)', // Fondo rojo semitransparente
        borderWidth: 0,
        fill: true,
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: false,
        min: 65,
        max: 100,
        ticks: {
          callback: function(value) {
            return value + "%";
          },
        },
      },
    },
    plugins: {
      legend: {
        display: false, // Oculta la leyenda
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            return context.raw + "%";
          },
        },
      },
    },
  },
});


  // Generar las opciones de meses en el formulario
  const monthSelectInventario = document.getElementById('monthInventario');
  dataLabels.forEach((label, index) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectInventario.appendChild(option);
  });

  // Validar y actualizar el gráfico
  document.getElementById('dataFormInventario').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectInventario.value;
    const desempeno = parseFloat(document.getElementById('desempenoInventario').value);
    const areaCumplimiento = parseFloat(document.getElementById('areaCumplimientoInventario').value);

    // Validar que los valores estén dentro del rango
    if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
      alert('Los valores deben estar entre 0% y 100%.');
      return;
    }

    // Enviar los datos al servidor
    axios.post('/inventario/store', {
      mes: month,
      desempeno: desempeno,
      area_cumplimiento: areaCumplimiento,
    })
    .then(response => {
      if (response.data.success) {
        // Actualizar los datos del gráfico
        const index = dataLabels.indexOf(month);
        desempenoData[index] = desempeno;
        areaCumplimientoData[index] = areaCumplimiento;

        inventarioChart.update(); // Actualizar el gráfico
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
    axios.get('/inventario/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabels.indexOf(item.mes);
          if (index !== -1) {
            desempenoData[index] = item.desempeno;
            areaCumplimientoData[index] = item.area_cumplimiento;
          }
        });
        inventarioChart.update(); // Actualizar el gráfico
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchData();
</script>

@endsection