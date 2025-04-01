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
  @can('admin.update')
  <label for="areaClientes">Área de cumplimiento (%): </label>
  <input type="number" id="areaClientes" name="areaClientes" min="0" max="100" step="0.01" ><br><br>
  @endcan
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  
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
<h2 class="box-title">Porcentaje de Ventas</h2>
<canvas id="salesChart"></canvas>
<canvas id="salesChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartVentas" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartVentas" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

<h2>Actualizar Datos de Ventas</h2>
@can('ventas.update')
<form id="dataFormVentas">
  <label for="monthVentas">Mes:</label>
  <select id="monthVentas" name="monthVentas"></select><br><br>
  <label for="performanceVentas">Desempeño (%):</label>
  <input type="number" id="performanceVentas" name="performanceVentas" min="0" step="0.01" ><br><br>
  @can('admin.update')
  <label for="areaVentas">Área de cumplimiento (%):</label>
  <input type="number" id="areaVentas" name="areaVentas" min="0" step="0.01" ><br><br>
  @endcan
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  const ctxVentas = document.getElementById('salesChart').getContext('2d');
  const ctxVentas2 = document.getElementById('salesChart2').getContext('2d');

  const months = ['ene-23', 'mar-23', 'may-23', 'jul-23', 'sep-23', 'nov-23', 'ene-24', 'mar-24', 'may-24', 'jul-24', 'sep-24', 'nov-24', 'dic-24'];
  const months2 = ['ene-25', 'feb-25', 'mar-25', 'abr-25', 'may-25', 'jun-25', 'jul-25', 'ago-25', 'sep-25', 'oct-25', 'nov-25', 'dic-25'];

  let salesData = [110, 105, 95, 102, 90, 100, 85, 98, 110, 99, 60, 88, 87.33];
  let referenceData = Array(13).fill(100);

  let salesData2 = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
  let referenceData2 = Array(12).fill(0);

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
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
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
              return tooltipItem.dataset.label + ': ' + tooltipItem.raw;
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

  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Obtener el mes actual
  const currentDate2 = new Date();
  const currentMonth2 = currentDate2.toLocaleString('default', { month: 'short' }).toLowerCase();
  const currentYear2 = currentDate2.getFullYear().toString().slice(-2);
  const currentMonthLabel2 = `${currentMonth2}-${currentYear2}`;

  // Generar las opciones de meses en el formulario
  const monthSelectVentas = document.getElementById('monthVentas');
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

        monthSelectVentas.appendChild(option);
      }
    }
  };

  generateMonthOptions();

  // Establecer el mes actual como seleccionado por defecto
  monthSelectVentas.value = currentMonthLabel2;

  // Validar y actualizar el gráfico
  document.getElementById('dataFormVentas').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectVentas.value;
    const performance = document.getElementById('performanceVentas').value;
    
    // Manejo seguro del campo restringido
    const areaInput = document.getElementById('areaVentas');
    const area = areaInput ? areaInput.value : null;

    // Convertir a número (permite null si está vacío o no existe)
    const performanceNum = performance === "" ? null : parseFloat(performance);
    const areaNum = area === null || area === "" ? null : parseFloat(area);

    // Validación mínima - solo requiere performance
    if (performanceNum === null) {
        alert('Debe ingresar al menos el valor de Área de cumplimiento');
        return;
    }

    // Validar que los valores no sean negativos
    if (performanceNum !== null && performanceNum < 0) {
        alert('El valor de Área de cumplimiento no puede ser negativo');
        return;
    }
    if (areaNum !== null && areaNum < 0) {
        alert('El valor de Desempeño no puede ser negativo');
        return;
    }

    axios.post('/ventas/store', {
        mes: month,
        desempeno: performanceNum,
        area_cumplimiento: areaNum,
    })
    .then(response => {
        if (response.data.success) {
            // Actualizar ambos gráficos según corresponda
            const index1 = months.indexOf(month);
            const index2 = months2.indexOf(month);
            
            // Actualizar gráfico 1 (2023-2024)
            if (index1 !== -1) {
                if (performanceNum !== null) salesChart.data.datasets[0].data[index1] = performanceNum;
                if (areaNum !== null) salesChart.data.datasets[1].data[index1] = areaNum;
                salesChart.update();
            }
            
            // Actualizar gráfico 2 (2025)
            if (index2 !== -1) {
                if (performanceNum !== null) salesChart2.data.datasets[0].data[index2] = performanceNum;
                if (areaNum !== null) salesChart2.data.datasets[1].data[index2] = areaNum;
                salesChart2.update();
            }
            
            // Feedback visual discreto
            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = '✓ Actualizado';
            setTimeout(() => { btn.textContent = originalText; }, 1000);
        } else {
            console.error('Error en la respuesta:', response.data);
        }
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
    });
});

axios.get('/ventas/get-data')
    .then(response => {
      const data = response.data;
      data.forEach(item => {
        const index = months.indexOf(item.mes);
        if (index !== -1) {
          salesChart.data.datasets[0].data[index] = item.desempeno;
        }
      });
      salesChart.update();
    })
    .catch(error => {
      console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
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
