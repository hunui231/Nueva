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
<style>
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


<h2 class="box-title">Entrega de Materiales a Tiempo</h2>
<canvas id="grafico"></canvas>
<canvas id="grafico2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartEntregaMateriales" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartEntregaMateriales" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<!-- Formulario para ingresar datos -->
<h2>Ingresar Datos - Entrega de Materiales</h2>
@can('logistica.update')
<form id="dataFormEntregaMateriales">
  <label for="monthEntregaMateriales">Mes:</label>
  <select id="monthEntregaMateriales" name="monthEntregaMateriales">
  </select><br><br>
  @can('admin.update')
  <label for="desempenoEntregaMateriales">Desempeño (%):</label>
  <input type="number" id="desempenoEntregaMateriales" name="desempenoEntregaMateriales" min="0" max="100" step="0.01" required><br><br>
 @endcan
  <label for="areaCumplimientoEntregaMateriales">Área de cumplimiento (%):</label>
  <input type="number" id="areaCumplimientoEntregaMateriales" name="areaCumplimientoEntregaMateriales" min="0" max="100" step="0.01" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico 1
  const ctx = document.getElementById('grafico').getContext('2d');
  const ctx2 = document.getElementById('grafico2').getContext('2d');

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
  const dataLabels = generateMonthOptions(23, 24); // Genera desde 2023 hasta 2024
  const dataLabels2 = generateMonthOptions(25, 25); // Genera solo 2025

  let desempenoData = Array(24).fill(100); // Datos iniciales para el gráfico 1
  let areaCumplimientoData = Array(24).fill(100); // Datos iniciales para el gráfico 1

  let desempenoData2 = Array(12).fill(100); // Datos iniciales para el gráfico 2
  let areaCumplimientoData2 = Array(12).fill(100); // Datos iniciales para el gráfico 2

  // Configuración del gráfico 1
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

  // Configuración del gráfico 2
  const grafico2 = new Chart(ctx2, {
    type: 'bar',
    data: {
      labels: dataLabels2,
      datasets: [
        {
          label: "Entrega a Tiempo",
          data: desempenoData2,
          backgroundColor: 'deepskyblue',
          borderColor: 'deepskyblue',
          borderWidth: 1,
        },
        {
          label: "Línea de 100%",
          data: areaCumplimientoData2,
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
  dataLabels.concat(dataLabels2).forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectEntregaMateriales.appendChild(option);
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
  monthSelectEntregaMateriales.value = currentMonthYear;

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
        if (index !== -1) {
          desempenoData[index] = desempeno;
          areaCumplimientoData[index] = areaCumplimiento;
          grafico.update(); // Actualizar el gráfico 1
        } else {
          const index2 = dataLabels2.indexOf(month);
          if (index2 !== -1) {
            desempenoData2[index2] = desempeno;
            areaCumplimientoData2[index2] = areaCumplimiento;
            grafico2.update(); // Actualizar el gráfico 2
          }
        }
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
          } else {
            const index2 = dataLabels2.indexOf(item.mes);
            if (index2 !== -1) {
              desempenoData2[index2] = item.desempeno;
              areaCumplimientoData2[index2] = item.area_cumplimiento;
            }
          }
        });
        grafico.update(); // Actualizar el gráfico 1
        grafico2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchData();

  // Alternar entre gráficos
  let currentChartEntregaMateriales = 1;
  document.getElementById('nextChartEntregaMateriales').addEventListener('click', () => {
    if (currentChartEntregaMateriales === 1) {
      document.getElementById('grafico').style.display = 'none';
      document.getElementById('grafico2').style.display = 'block';
      currentChartEntregaMateriales = 2;
    } else {
      document.getElementById('grafico').style.display = 'block';
      document.getElementById('grafico2').style.display = 'none';
      currentChartEntregaMateriales = 1;
    }
  });

  document.getElementById('prevChartEntregaMateriales').addEventListener('click', () => {
    if (currentChartEntregaMateriales === 1) {
      document.getElementById('grafico').style.display = 'none';
      document.getElementById('grafico2').style.display = 'block';
      currentChartEntregaMateriales = 2;
    } else {
      document.getElementById('grafico').style.display = 'block';
      document.getElementById('grafico2').style.display = 'none';
      currentChartEntregaMateriales = 1;
    }
  });
</script>
<br><br>
<h2>Información de Inventarios</h2>
<canvas id="inventarioChart"></canvas>
<canvas id="inventarioChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartInventario" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartInventario" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2 class="box-title">Ingresar Datos - Inventarios</h2>
@can('logistica.update')
<form id="dataFormInventario">
  <label for="monthInventario">Mes:</label>
  <select id="monthInventario" name="monthInventario"></select><br><br>
  @can('admin.update')
  <label for="desempenoInventario">Desempeño Inventario (%):</label>
  <input type="number" id="desempenoInventario" name="desempenoInventario" min="0" max="100" step="0.01" required><br><br>
  @endcan
  <label for="areaCumplimientoInventario">Área de Cumplimiento (%):</label>
  <input type="number" id="areaCumplimientoInventario" name="areaCumplimientoInventario" min="0" max="100" step="0.01" required><br><br>

  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto de los gráficos
  const ctxInventario = document.getElementById('inventarioChart').getContext('2d');
  const ctxInventario2 = document.getElementById('inventarioChart2').getContext('2d');

  // Función para generar opciones de meses y años
  function generarOpcionesMesesInventario(anioInicio, anioFin) {
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
  const anioActualInventario = new Date().getFullYear();
  const dataLabelsInventario = generarOpcionesMesesInventario(23, 24); // Genera desde 2023 hasta 2024
  const dataLabelsInventario2 = generarOpcionesMesesInventario(25, 25); // Genera solo 2025

  // Datos iniciales para los gráficos
  let desempenoDataInventario = Array(24).fill(0); 
  let areaCumplimientoDataInventario = Array(24).fill(0); 

  let desempenoDataInventario2 = Array(12).fill(0); 
  let areaCumplimientoDataInventario2 = Array(12).fill(0);

  // Configuración del gráfico 1
  const inventarioChart = new Chart(ctxInventario, {
    type: 'line',
    data: {
      labels: dataLabelsInventario,
      datasets: [
        {
          label: "Desempeño Inventario",
          data: desempenoDataInventario,
          borderColor: 'deepskyblue',
          borderWidth: 2,
          pointBackgroundColor: 'deepskyblue',
          pointRadius: 4,
          fill: false,
          tension: 0.3,
          spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
        },
        {
          label: "Área de Cumplimiento",
          data: areaCumplimientoDataInventario,
          backgroundColor: 'rgba(255, 0, 0, 0.85)', 
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
          min: 0,
          max: 100,
          ticks: {
            callback: function(valor) {
              return valor + "%";
            },
          },
        },
      },
      plugins: {
        legend: {
          display: false, 
        },
        tooltip: {
          callbacks: {
            label: function(contexto) {
              return contexto.raw + "%";
            },
          },
        },
      },
    },
  });

  // Configuración del gráfico 2
  const inventarioChart2 = new Chart(ctxInventario2, {
    type: 'line',
    data: {
      labels: dataLabelsInventario2,
      datasets: [
        {
          label: "Desempeño Inventario",
          data: desempenoDataInventario2,
          borderColor: 'deepskyblue',
          borderWidth: 2,
          pointBackgroundColor: 'deepskyblue',
          pointRadius: 4,
          fill: false,
          tension: 0.3,
          spanGaps: true, // Permitir dibujar líneas incluso si hay gaps
        },
        {
          label: "Área de Cumplimiento",
          data: areaCumplimientoDataInventario2,
          backgroundColor: 'rgba(255, 0, 0, 0.85)', 
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
            callback: function(valor) {
              return valor + "%";
            },
          },
        },
      },
      plugins: {
        legend: {
          display: false, 
        },
        tooltip: {
          callbacks: {
            label: function(contexto) {
              return contexto.raw + "%";
            },
          },
        },
      },
    },
  });

  // Generar las opciones de meses en el formulario
  const selectorMesInventario = document.getElementById('monthInventario');
  dataLabelsInventario.concat(dataLabelsInventario2).forEach((etiqueta) => {
    const opcion = document.createElement('option');
    opcion.value = etiqueta;
    opcion.textContent = etiqueta;
    selectorMesInventario.appendChild(opcion);
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
  selectorMesInventario.value = mesAnioActual;

  // Validar y actualizar el gráfico
  document.getElementById('dataFormInventario').addEventListener('submit', (evento) => {
    evento.preventDefault();

    const mesSeleccionado = selectorMesInventario.value;
    const desempeno = parseFloat(document.getElementById('desempenoInventario').value);
    const areaCumplimiento = parseFloat(document.getElementById('areaCumplimientoInventario').value);

    // Validar que los valores estén dentro del rango
    if (desempeno < 0 || desempeno > 100 || areaCumplimiento < 0 || areaCumplimiento > 100) {
      alert('Los valores deben estar entre 0% y 100%.');
      return;
    }

    // Enviar los datos al servidor
    axios.post('/inventario/store', {
      mes: mesSeleccionado,
      desempeno: desempeno,
      area_cumplimiento: areaCumplimiento,
    })
    .then(respuesta => {
      if (respuesta.data.success) {
        // Actualizar los datos del gráfico
        const indice = dataLabelsInventario.indexOf(mesSeleccionado);
        if (indice !== -1) {
          desempenoDataInventario[indice] = desempeno;
          areaCumplimientoDataInventario[indice] = areaCumplimiento;
          inventarioChart.update(); // Actualizar el gráfico 1
        } else {
          const indice2 = dataLabelsInventario2.indexOf(mesSeleccionado);
          if (indice2 !== -1) {
            desempenoDataInventario2[indice2] = desempeno;
            areaCumplimientoDataInventario2[indice2] = areaCumplimiento;
            inventarioChart2.update(); // Actualizar el gráfico 2
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

  // Obtener los datos actualizados del servidor
  function obtenerDatosInventario() {
    axios.get('/inventario/get-data')
      .then(respuesta => {
        const datos = respuesta.data;
        datos.forEach(item => {
          const indice = dataLabelsInventario.indexOf(item.mes);
          if (indice !== -1) {
            desempenoDataInventario[indice] = item.desempeno;
            areaCumplimientoDataInventario[indice] = item.area_cumplimiento;
          } else {
            const indice2 = dataLabelsInventario2.indexOf(item.mes);
            if (indice2 !== -1) {
              desempenoDataInventario2[indice2] = item.desempeno;
              areaCumplimientoDataInventario2[indice2] = item.area_cumplimiento;
            }
          }
        });
        inventarioChart.update(); // Actualizar el gráfico 1
        inventarioChart2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  obtenerDatosInventario();

  // Alternar entre gráficos
  let graficoActualInventario = 1;
  document.getElementById('nextChartInventario').addEventListener('click', () => {
    if (graficoActualInventario === 1) {
      document.getElementById('inventarioChart').style.display = 'none';
      document.getElementById('inventarioChart2').style.display = 'block';
      graficoActualInventario = 2;
    } else {
      document.getElementById('inventarioChart').style.display = 'block';
      document.getElementById('inventarioChart2').style.display = 'none';
      graficoActualInventario = 1;
    }
  });

  document.getElementById('prevChartInventario').addEventListener('click', () => {
    if (graficoActualInventario === 1) {
      document.getElementById('inventarioChart').style.display = 'none';
      document.getElementById('inventarioChart2').style.display = 'block';
      graficoActualInventario = 2;
    } else {
      document.getElementById('inventarioChart').style.display = 'block';
      document.getElementById('inventarioChart2').style.display = 'none';
      graficoActualInventario = 1;
    }
  });
</script>

@endsection