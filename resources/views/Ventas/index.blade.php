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
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<h2 class="box-title">Clientes Nuevos por Mes</h2>
<canvas id="clientesChart"></canvas>
<canvas id="clientesChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartClientes" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartClientes" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Ingresar Datos - Clientes Nuevos</h2>
@can('ventas.update')
<form id="dataFormClientes">
  <label for="monthClientes">Mes:</label>
  <select id="monthClientes" name="monthClientes"></select><br><br>
  <label for="performanceClientes">Desempeño (%): </label>
  <input type="number" id="performanceClientes" name="performanceClientes" min="0" max="100" step="0.01" ><br><br>
  <label for="areaClientes">Área de cumplimiento (%): </label>
  <input type="number" id="areaClientes" name="areaClientes" min="0" max="100" step="0.01" ><br><br>
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  //
  const ctxClientes = document.getElementById('clientesChart').getContext('2d');
  const ctxClientes2 = document.getElementById('clientesChart2').getContext('2d');

  const mesesClientes1 = [
    "ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23",
    "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"
  ];

  const mesesClientes2 = [
    "ene-25", "feb-25", "mar-25", "abr-25", "may-25", "jun-25", "jul-25", "ago-25", "sep-25", "oct-25", "nov-25", "dic-25"
  ];

  let datosClientes1 = [10, 12, 9, 11, 8, 14, 13, 17, 12, 15, 6, 3, 10, 14, 7, 9, 6, 10, 7, 9, 8, 7, 10, 18];
  let datosMeta1 = [4, 4, 4, 4, 4, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7];

  let datosClientes2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
  let datosMeta2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

  const graficoClientes1 = new Chart(ctxClientes, {
    type: 'line',
    data: {
      labels: mesesClientes1,
      datasets: [
        {
          label: "Clientes Nuevos",
          data: datosClientes1,
          borderColor: "#009FE3",
          backgroundColor: "rgb(0, 159, 227)",
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: "#009FE3"
        },
        {
          label: "Meta",
          data: datosMeta1,
          backgroundColor: "rgb(255, 0, 0)",
          borderColor: "rgb(255, 0, 0)",
          fill: true,
          tension: 0.1,
          pointRadius: 0
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          suggestedMax: 20
        }
      }
    }
  });

  // Configuración del gráfico 2
  const graficoClientes2 = new Chart(ctxClientes2, {
    type: 'line',
    data: {
      labels: mesesClientes2,
      datasets: [
        {
          label: "Clientes Nuevos",
          data: datosClientes2,
          borderColor: "#009FE3",
          backgroundColor: "rgb(0, 159, 227)",
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: "#009FE3"
        },
        {
          label: "Meta",
          data: datosMeta2,
          backgroundColor: "rgb(255, 0, 0)",
          borderColor: "rgb(255, 0, 0)",
          fill: true,
          tension: 0.1,
          pointRadius: 0
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
            }
          }
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          suggestedMax: 20
        }
      }
    }
  });

  // Obtener la fecha actual
  const fechaActual = new Date();
  const mesActual = fechaActual.toLocaleString('default', { month: 'short' }).toLowerCase();
  const anioActual = fechaActual.getFullYear().toString().slice(-2);
  const mesAnioActual = `${mesActual}-${anioActual}`;

  // Generar las opciones de meses en el formulario
  const selectorMes = document.getElementById('monthClientes');
  mesesClientes1.concat(mesesClientes2).forEach((mes) => {
    const opcion = document.createElement('option');
    opcion.value = mes;
    opcion.textContent = mes;
    if (mes !== mesAnioActual) {
      opcion.disabled = false; // Deshabilitar meses que no sean el actual
    }
    selectorMes.appendChild(opcion);
  });

  // Establecer el mes actual como seleccionado por defecto
  selectorMes.value = mesAnioActual;

  document.getElementById('dataFormClientes').addEventListener('submit', (evento) => {
    evento.preventDefault();

    const mesSeleccionado = selectorMes.value;
    const desempeno = document.getElementById('performanceClientes').value;
    
    // Manejo seguro del campo restringido
    const areaInput = document.getElementById('areaClientes');
    const area = areaInput ? areaInput.value : null;

    // Convertir a número (permite null si está vacío o no existe)
    const desempenoNum = desempeno === "" ? null : parseFloat(desempeno);
    const areaNum = area === null || area === "" ? null : parseFloat(area);

    // Validación mínima - solo requiere desempeño
    if (desempenoNum === null) {
        alert('Debe ingresar al menos el valor de Área de cumplimiento');
        return;
    }

    // Validar rangos
    axios.post('/clientes-nuevos/store', {
        mes: mesSeleccionado,
        desempeno: desempenoNum,
        area_cumplimiento: areaNum,
    })
    .then(respuesta => {
        if (respuesta.data.success) {
            // Actualizar ambos gráficos según corresponda
            const indice1 = mesesClientes1.indexOf(mesSeleccionado);
            const indice2 = mesesClientes2.indexOf(mesSeleccionado);
            
            // Actualizar gráfico 1 (2023-2024)
            if (indice1 !== -1) {
                if (desempenoNum !== null) datosClientes1[indice1] = desempenoNum;
                if (areaNum !== null) datosMeta1[indice1] = areaNum;
                graficoClientes1.update();
            }
            
            // Actualizar gráfico 2 (2025)
            if (indice2 !== -1) {
                if (desempenoNum !== null) datosClientes2[indice2] = desempenoNum;
                if (areaNum !== null) datosMeta2[indice2] = areaNum;
                graficoClientes2.update();
            }
            
            // Feedback visual discreto
            const btn = evento.target.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = '✓ ';
            setTimeout(() => { btn.textContent = originalText; }, 1000);
        } else {
            console.error('Error en la respuesta:', respuesta.data);
        }
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
    });
});
  // Cargar los datos iniciales al cargar la página
  function cargarDatos() {
    axios.get('/clientes-nuevos/get-data')
      .then(respuesta => {
        const datos = respuesta.data;
        datos.forEach(item => {
          const indice = mesesClientes1.indexOf(item.mes);
          if (indice !== -1) {
            datosClientes1[indice] = item.desempeno;
            datosMeta1[indice] = item.area_cumplimiento;
          } else {
            const indice2 = mesesClientes2.indexOf(item.mes);
            if (indice2 !== -1) {
              datosClientes2[indice2] = item.desempeno;
              datosMeta2[indice2] = item.area_cumplimiento;
            }
          }
        });
        graficoClientes1.update(); // Actualizar el gráfico 1
        graficoClientes2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  cargarDatos();

  // Alternar entre gráficos
  let graficoActual = 1;
  document.getElementById('nextChartClientes').addEventListener('click', () => {
    if (graficoActual === 1) {
      document.getElementById('clientesChart').style.display = 'none';
      document.getElementById('clientesChart2').style.display = 'block';
      graficoActual = 2;
    } else {
      document.getElementById('clientesChart').style.display = 'block';
      document.getElementById('clientesChart2').style.display = 'none';
      graficoActual = 1;
    }
  });

  document.getElementById('prevChartClientes').addEventListener('click', () => {
    if (graficoActual === 1) {
      document.getElementById('clientesChart').style.display = 'none';
      document.getElementById('clientesChart2').style.display = 'block';
      graficoActual = 2;
    } else {
      document.getElementById('clientesChart').style.display = 'block';
      document.getElementById('clientesChart2').style.display = 'none';
      graficoActual = 1;
    }
  });
</script>
<br><br>
<meta name="csrf-token" content="{{ csrf_token() }}">
<h2 class="box-title">Cumplimiento de las Metas Establecidas de Ventas</h2>
<canvas id="salesChart"></canvas>
<canvas id="salesChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartVentas" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChartVentas" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>

<h2>Actualizar Datos de Ventas</h2>
@can('ventas.update')
<form id="dataFormVentas">
  <label for="monthVentas">Mes:</label>
  <select id="monthVentas" name="monthVentas"></select><br><br>
  <label for="performanceVentas">Desempeño (%):</label>
  <input type="number" id="performanceVentas" name="performanceVentas" min="0" step="0.01"><br><br>
  <label for="areaVentas">Área de cumplimiento (%):</label>
  <input type="number" id="areaVentas" name="areaVentas" min="0" step="0.01"><br><br>
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  const ctxVentas = document.getElementById('salesChart').getContext('2d');
  const ctxVentas2 = document.getElementById('salesChart2').getContext('2d');

  const months = ['ene-23', 'feb-23', 'mar-23', 'abr-23', 'may-23', 'jun-23', 'jul-23', 'ago-23', 'sep-23', 'oct-23', 'nov-23', 'dic-23', 'ene-24', 'feb-24', 'mar-24', 'abr-24', 'may-24', 'jun-24', 'jul-24', 'ago-24', 'sep-24', 'oct-24', 'nov-24', 'dic-24'];
  const months2 = ['ene-25', 'feb-25', 'mar-25', 'abr-25', 'may-25', 'jun-25', 'jul-25', 'ago-25', 'sep-25', 'oct-25', 'nov-25', 'dic-25'];

  // Inicializar datos con ceros o valores por defecto
  let salesData = Array(months.length).fill(0);
  let referenceData = Array(months.length).fill(100);

  let salesData2 = Array(months2.length).fill(0);
  let referenceData2 = Array(months2.length).fill(100);

  // Configuración del gráfico 1
  const salesChart = new Chart(ctxVentas, {
    type: 'line',
    data: {
      labels: months,
      datasets: [
        {
          label: 'Volumen de Ventas',
          data: salesData,
          borderColor: '#009FE3',
          backgroundColor: 'transparent',
          borderWidth: 2,
          pointBackgroundColor: '#009FE3',
          tension: 0.3
        },
        {
          label: 'Referencia 100%',
          data: referenceData,
          borderColor: 'rgb(255, 0, 0)',
          borderWidth: 2,
          pointRadius: 0
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw + '%';
            }
          }
        }
      },
      scales: {
        y: {
          min: 50,
          max: 120,
          ticks: {
            callback: (value) => `${value}%`
          }
        }
      }
    }
  });

  // Configuración del gráfico 2
  const salesChart2 = new Chart(ctxVentas2, {
    type: 'line',
    data: {
      labels: months2,
      datasets: [
        {
          label: 'Volumen de Ventas',
          data: salesData2,
          borderColor: '#009FE3',
          backgroundColor: 'transparent',
          borderWidth: 2,
          pointBackgroundColor: '#009FE3',
          tension: 0.3
        },
        {
          label: 'Referencia 100%',
          data: referenceData2,
          borderColor: 'rgb(255, 0, 0)',
          borderWidth: 2,
          pointRadius: 0
        }
      ]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        tooltip: {
          callbacks: {
            label: function(tooltipItem) {
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw + '%';
            }
          }
        }
      },
      scales: {
        y: {
          min: 50,
          max: 120,
          ticks: {
            callback: (value) => `${value}%`
          }
        }
      }
    }
  });

  // Configurar el token CSRF para Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Obtener la fecha actual para seleccionar el mes por defecto
  const currentDatet = new Date();
  const currentMonthe = currentDatet.toLocaleString('default', { month: 'short' }).toLowerCase();
  const currentYearo = currentDatet.getFullYear().toString().slice(-2);
  const currentMonthLabel = `${currentMonthe}-${currentYearo}`;

  // Generar las opciones de meses en el formulario
  const monthSelectVentas = document.getElementById('monthVentas');
  const generateMonthOptions = () => {
    monthSelectVentas.innerHTML = ''; // Limpiar opciones existentes
    
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();

    // Años a mostrar (desde 2023 hasta 2025)
    for (let year = 2023; year <= 2025; year++) {
      for (let month = 0; month < 12; month++) {
        // Solo agregar meses pasados o el mes actual
        if (year < currentYear || (year === currentYear && month <= currentMonth)) {
          const monthLabel = new Date(year, month).toLocaleString('default', { month: 'short' }).toLowerCase();
          const yearLabel = year.toString().slice(-2);
          const fullLabel = `${monthLabel}-${yearLabel}`;

          const option = document.createElement('option');
          option.value = fullLabel;
          option.textContent = fullLabel;
          monthSelectVentas.appendChild(option);
        }
      }
    }
  };

  generateMonthOptions();

  // Establecer el mes actual como seleccionado por defecto
  if (months.includes(currentMonthLabel) || months2.includes(currentMonthLabel)) {
    monthSelectVentas.value = currentMonthLabel;
  }

  // Cargar datos iniciales del servidor
  function loadInitialData() {
    axios.get('/ventas/get-data')
      .then(response => {
        const data = response.data;
        
        // Actualizar datos del primer gráfico
        data.forEach(item => {
          const index = months.indexOf(item.mes);
          if (index !== -1) {
            salesChart.data.datasets[0].data[index] = item.desempeno || 0;
            salesChart.data.datasets[1].data[index] = item.area_cumplimiento || 100;
          }
        });
        salesChart.update();
        
        // Actualizar datos del segundo gráfico
        data.forEach(item => {
          const index = months2.indexOf(item.mes);
          if (index !== -1) {
            salesChart2.data.datasets[0].data[index] = item.desempeno || 0;
            salesChart2.data.datasets[1].data[index] = item.area_cumplimiento || 100;
          }
        });
        salesChart2.update();
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error);
      });
  }

  // Cargar datos al iniciar la página
  loadInitialData();

  // Manejar el envío del formulario
  document.getElementById('dataFormVentas').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectVentas.value;
    const performance = parseFloat(document.getElementById('performanceVentas').value);
    const area = parseFloat(document.getElementById('areaVentas').value);

    // Validaciones
    if (isNaN(performance) || isNaN(area)) {
      alert('Por favor ingrese valores numéricos válidos');
      return;
    }

    if (performance < 0 || area < 0) {
      alert('Los valores no pueden ser negativos');
      return;
    }

    // Enviar datos al servidor
    axios.post('/ventas/store', {
      mes: month,
      desempeno: performance,
      area_cumplimiento: area,
    })
    .then(response => {
      if (response.data.success) {
        // Actualizar ambos gráficos según corresponda
        const index1 = months.indexOf(month);
        const index2 = months2.indexOf(month);
        
        if (index1 !== -1) {
          salesChart.data.datasets[0].data[index1] = performance;
          salesChart.data.datasets[1].data[index1] = area;
          salesChart.update();
        }
        
        if (index2 !== -1) {
          salesChart2.data.datasets[0].data[index2] = performance;
          salesChart2.data.datasets[1].data[index2] = area;
          salesChart2.update();
        }
        
        // Feedback visual
        const btn = event.target.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.textContent = '✓ Actualizado';
        setTimeout(() => { btn.textContent = originalText; }, 2000);
        
        // Limpiar campos del formulario
        document.getElementById('performanceVentas').value = '';
        document.getElementById('areaVentas').value = '';
      } else {
        alert('Error al guardar los datos: ' + (response.data.message || 'Error desconocido'));
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Error al guardar los datos. Por favor intente nuevamente.');
    });
  });

  // Alternar entre gráficos
  let currentChartVentas = 1;
  
  document.getElementById('nextChartVentas').addEventListener('click', () => {
    if (currentChartVentas === 1) {
      document.getElementById('salesChart').style.display = 'none';
      document.getElementById('salesChart2').style.display = 'block';
      currentChartVentas = 2;
    } else {
      document.getElementById('salesChart').style.display = 'block';
      document.getElementById('salesChart2').style.display = 'none';
      currentChartVentas = 1;
    }
  });

  document.getElementById('prevChartVentas').addEventListener('click', () => {
    if (currentChartVentas === 1) {
      document.getElementById('salesChart').style.display = 'none';
      document.getElementById('salesChart2').style.display = 'block';
      currentChartVentas = 2;
    } else {
      document.getElementById('salesChart').style.display = 'block';
      document.getElementById('salesChart2').style.display = 'none';
      currentChartVentas = 1;
    }
  });
</script>
<br><br>
<meta name="csrf-token" content="{{ csrf_token() }}">

<h2 class="box-title">Satisfacción al Cliente</h2>
<canvas id="graficoSatisfaccion"></canvas>
<canvas id="graficoSatisfaccion2" style="display: none;"></canvas> 

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartSatisfaccion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartSatisfaccion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar satisfacción al cliente</h2>
@can('ventas.update')
<form id="dataFormSatisfaccion">
  <label for="monthSatisfaccion">Mes:</label>
  <select id="monthSatisfaccion" name="monthSatisfaccion">
  </select><br><br>
  <label for="desempenoSatisfaccion">Nivel de Satisfacción (%):</label>
  <input type="number" id="desempenoSatisfaccion" name="desempenoSatisfaccion" min="0" max="100" step="0.01"><br><br>
  <label for="areaCumplimientoSatisfaccion">Meta (%):</label>
  <input type="number" id="areaCumplimientoSatisfaccion" name="areaCumplimientoSatisfaccion" min="0" max="100" step="0.01"><br><br>
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script>
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto y datos iniciales del gráfico 1
  const ctxSatisfaccion = document.getElementById('graficoSatisfaccion').getContext('2d');
  const ctxSatisfaccion2 = document.getElementById('graficoSatisfaccion2').getContext('2d');

  // Función para generar opciones de meses y años
  function generateMonthOptionss(startYear, endYear) {
    const months = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    let options = [];

    for (let year = startYear; year <= endYear; year++) {
      months.forEach((month) => {
        options.push(`${month}-${year.toString().slice(-2)}`);
      });
    }

    return options;
  }
//
  const currentYear = new Date().getFullYear();
  const dataLabelsSatisfaccion = generateMonthOptionss(23, 24); 
  const dataLabelsSatisfaccion2 = generateMonthOptionss(25, 25); 

  let desempenoDataSatisfaccion = Array(24).fill(0); // Datos iniciales para el gráfico 1
  let areaCumplimientoDataSatisfaccion = Array(24).fill(90); // Meta inicial para el gráfico 1

  let desempenoDataSatisfaccion2 = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let areaCumplimientoDataSatisfaccion2 = Array(12).fill(90); // Meta inicial para el gráfico 2

  // Configuración del gráfico 1
  const graficoSatisfaccion = new Chart(ctxSatisfaccion, {
    type: 'line', 
    data: {
      labels: dataLabelsSatisfaccion,
      datasets: [
        {
          label: "Satisfacción del Cliente",
          data: desempenoDataSatisfaccion,
          backgroundColor: 'rgb(61, 149, 221)',
          borderColor: 'rgb(31, 173, 255)',
          borderWidth: 1,
        },
        {
          label: "Meta (90%)",
          data: areaCumplimientoDataSatisfaccion,
          type: 'line',
          borderColor: 'red',   
          borderWidth: 2,
          pointRadius: 0,
          spanGaps: true,
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
          display: false, 
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

  const graficoSatisfaccion2 = new Chart(ctxSatisfaccion2, {
    type: 'line',
    data: {
      labels: dataLabelsSatisfaccion2,
      datasets: [
        {
          label: "Satisfacción del Cliente",
          data: desempenoDataSatisfaccion2,
          backgroundColor: 'rgba(75, 192, 192, 0.7)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1,
        },
        {
          label: "Meta (90%)",
          data: areaCumplimientoDataSatisfaccion2,
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
  const monthSelectSatisfaccion = document.getElementById('monthSatisfaccion');
  dataLabelsSatisfaccion.concat(dataLabelsSatisfaccion2).forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectSatisfaccion.appendChild(option);
  });

  
  const currentDate = new Date();

  
  const month = currentDate.toLocaleString('default', { month: 'short' }).toLowerCase();

  const year = currentDate.getFullYear().toString().slice(-2);

  const currentMonthYear = `${month}-${year}`;

  monthSelectSatisfaccion.value = currentMonthYear;

  document.getElementById('dataFormSatisfaccion').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    try {
        // 1. Obtener elementos del formulario de manera segura
        const mesSeleccionado = document.getElementById('monthSatisfaccion')?.value;
        const desempenoInput = document.getElementById('desempenoSatisfaccion');
        const areaCumplimientoInput = document.getElementById('areaCumplimientoSatisfaccion'); 
        
        // Verificar existencia de elementos críticos
        if (!mesSeleccionado || !desempenoInput) {
            throw new Error('Elementos del formulario no encontrados');
        }

        // 2. Obtener valores con comprobación de nulidad
        const desempeno = desempenoInput.value;
        const areaCumplimiento = areaCumplimientoInput ? areaCumplimientoInput.value : null;

        // 3. Convertir y validar datos
        const desempenoNum = desempeno !== "" ? parseFloat(desempeno) : null;
        const areaCumplimientoNum = areaCumplimiento !== null && areaCumplimiento !== "" ? parseFloat(areaCumplimiento) : null;

        // Validaciones básicas
        if (desempenoNum === null || isNaN(desempenoNum)) {
            throw new Error('El campo Nivel de Satisfacción es obligatorio');
        }

        if (desempenoNum < 0 || desempenoNum > 100) {
            throw new Error('El valor de Nivel de Satisfacción debe estar entre 0% y 100%');
        }

        if (areaCumplimientoInput) {
            if (areaCumplimientoNum === null || isNaN(areaCumplimientoNum)) {
                throw new Error('El campo Meta es obligatorio');
            }
            if (areaCumplimientoNum < 0 || areaCumplimientoNum > 100) {
                throw new Error('El valor de Meta debe estar entre 0% y 100%');
            }
        }

        const datos = {
            mes: mesSeleccionado,
            desempeno: desempenoNum
        };

        // Solo agregar area_cumplimiento si existe el campo
        if (areaCumplimientoInput) {
            datos.area_cumplimiento = areaCumplimientoNum;
        }

        // 5. Enviar datos al servidor
        const respuesta = await axios.post('/satisfaccion-cliente/store', datos);

        if (!respuesta.data.success) {
            throw new Error('Error al guardar los datos');
        }

        // 6. Actualizar ambos gráficos donde corresponda
        const indice = dataLabelsSatisfaccion.indexOf(mesSeleccionado);
        const indice2 = dataLabelsSatisfaccion2.indexOf(mesSeleccionado);
        
        if (indice !== -1) {
            desempenoDataSatisfaccion[indice] = desempenoNum;
            if (areaCumplimientoNum !== null) areaCumplimientoDataSatisfaccion[indice] = areaCumplimientoNum;
            graficoSatisfaccion.update();
        }
        
        if (indice2 !== -1) {
            desempenoDataSatisfaccion2[indice2] = desempenoNum;
            if (areaCumplimientoNum !== null) areaCumplimientoDataSatisfaccion2[indice2] = areaCumplimientoNum;
            graficoSatisfaccion2.update();
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

  function fetchDataSatisfaccion() {
    axios.get('/satisfaccion-cliente/get-data')
      .then(response => {
        const data = response.data;
        data.forEach(item => {
          const index = dataLabelsSatisfaccion.indexOf(item.mes);
          if (index !== -1) {
            desempenoDataSatisfaccion[index] = item.desempeno;
            areaCumplimientoDataSatisfaccion[index] = item.area_cumplimiento;
          } else {
            const index2 = dataLabelsSatisfaccion2.indexOf(item.mes);
            if (index2 !== -1) {
              desempenoDataSatisfaccion2[index2] = item.desempeno;
              areaCumplimientoDataSatisfaccion2[index2] = item.area_cumplimiento;
            }
          }
        });
        graficoSatisfaccion.update(); // Actualizar el gráfico 1
        graficoSatisfaccion2.update(); // Actualizar el gráfico 2
      })
      .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchDataSatisfaccion();

  // Alternar entre gráficos
  let currentChartSatisfaccion = 1;
  document.getElementById('nextChartSatisfaccion').addEventListener('click', () => {
    if (currentChartSatisfaccion === 1) {
      document.getElementById('graficoSatisfaccion').style.display = 'none';
      document.getElementById('graficoSatisfaccion2').style.display = 'block';
      currentChartSatisfaccion = 2;
    } else {
      document.getElementById('graficoSatisfaccion').style.display = 'block';
      document.getElementById('graficoSatisfaccion2').style.display = 'none';
      currentChartSatisfaccion = 1;
    }
  });

  document.getElementById('prevChartSatisfaccion').addEventListener('click', () => {
    if (currentChartSatisfaccion === 1) {
      document.getElementById('graficoSatisfaccion').style.display = 'none';
      document.getElementById('graficoSatisfaccion2').style.display = 'block';
      currentChartSatisfaccion = 2;
    } else {
      document.getElementById('graficoSatisfaccion').style.display = 'block';
      document.getElementById('graficoSatisfaccion2').style.display = 'none';
      currentChartSatisfaccion = 1;
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
