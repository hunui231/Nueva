@extends('layouts.dashboard') 

@section('page') 
    @php $currentPage = 'users' @endphp 
@endsection

@section('content') 
<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<h2>Clientes Nuevos por Mes</h2>
    <canvas id="clientesChart"></canvas>
    
<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

    <h2>Ingresar Datos - Clientes Nuevos</h2>
    <form id="dataFormClientes">
        <label for="monthClientes">Mes:</label>
        <select id="monthClientes" name="monthClientes"></select><br><br>

        <label for="performanceClientes">Desempeño (%):</label>
        <input type="number" id="performanceClientes" name="performanceClientes" min="0" max="100" step="0.01" required><br><br>

        <label for="areaClientes">Área de cumplimiento (%):</label>
        <input type="number" id="areaClientes" name="areaClientes" min="0" max="100" step="0.01" required><br><br>

        <button type="submit" class="button">Actualizar Gráfico</button>
    </form>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
  const ctxClientes = document.getElementById('clientesChart').getContext('2d');

  const dataLabelsClientes = [
    "ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23",
    "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"
  ];

  let clientesData = [10, 12, 9, 11, 8, 14, 13, 17, 12, 15, 6, 3, 10, 14, 7, 9, 6, 10, 7, 9, 8, 7, 10, 18];
  let metaData = [4, 4, 4, 4, 4, 6, 6, 6, 6, 6, 6, 6, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7, 7];

  // Configuración del gráfico
  const clientesChart = new Chart(ctxClientes, {
    type: 'line',
    data: {
      labels: dataLabelsClientes,
      datasets: [
        {
          label: "Clientes Nuevos",
          data: clientesData,
          borderColor: "#009FE3",
          backgroundColor: "rgb(0, 159, 227)",
          fill: false,
          tension: 0.3,
          pointRadius: 5,
          pointBackgroundColor: "#009FE3"
        },
        {
          label: "Meta",
          data: metaData,
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

  // Generar las opciones de meses en el formulario
  const monthSelectClientes = document.getElementById('monthClientes');
  dataLabelsClientes.forEach((label, index) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectClientes.appendChild(option);
  });

  // Validar y actualizar el gráfico
  document.getElementById('dataFormClientes').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectClientes.value;
    const performance = parseFloat(document.getElementById('performanceClientes').value);
    const area = parseFloat(document.getElementById('areaClientes').value);

    // Validar que los valores estén dentro del rango
    if (performance < 0 || performance > 100 || area < 0 || area > 100) {
      alert('Los valores deben estar entre 0% y 100%.');
      return;
    }

    // Enviar los datos al servidor
    axios.post('/clientes-nuevos/store', {
      mes: month,
      desempeno: performance,
      area_cumplimiento: area,
    })
    .then(response => {
      // Actualizar los datos del gráfico
      const index = dataLabelsClientes.indexOf(month);
      clientesData[index] = performance;
      metaData[index] = area;

      clientesChart.update(); // Actualizar el gráfico
    })
    .catch(error => {
      console.error('Error al guardar los datos:', error);
    });
  });

  function fetchData() {
    axios.get('/clientes-nuevos/get-data')
      .then(response => {
          const data = response.data;
          data.forEach(item => {
              const index = dataLabelsClientes.indexOf(item.mes);
              if (index !== -1) {
                  clientesData[index] = item.desempeno;
                  metaData[index] = item.area_cumplimiento;
              } else {
                  console.error('Mes no encontrado:', item.mes);
              }
          });
          clientesChart.update(); // Actualizar el gráfico
      })
      .catch(error => {
          console.error('Error al obtener los datos:', error);
      });
  }

  // Cargar los datos iniciales al cargar la página
  fetchData();
</script>


    <br><br>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <h2>Porcentaje de Ventas</h2>
    <canvas id="salesChart"></canvas>

    
<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

    <h2>Actualizar Datos de Ventas</h2>
    <form id="dataFormVentas">
        <label for="monthVentas">Mes:</label>
        <select id="monthVentas" name="monthVentas"></select><br><br>

        <label for="performanceVentas">Desempeño (%):</label>
        <input type="number" id="performanceVentas" name="performanceVentas" min="0" max="100" step="0.01" required><br><br>

        <label for="areaVentas">Área de cumplimiento (%):</label>
        <input type="number" id="areaVentas" name="areaVentas" min="0" max="100" step="0.01" required><br><br>

        <button type="submit"  class="button">Actualizar Gráfico</button>
    </form>

    <script>
      
        const ctxVentas = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctxVentas, {
            type: 'line',
            data: {
                labels: ['ene-23', 'mar-23', 'may-23', 'jul-23', 'sep-23', 'nov-23', 'ene-24', 'mar-24', 'may-24', 'jul-24', 'sep-24', 'nov-24', 'dic-24'],
                datasets: [
                    {
                        label: 'Volumen de Ventas',
                        data: [110, 105, 95, 102, 90, 100, 85, 98, 110, 99, 60, 88, 87.33],
                        borderColor: '#009FE3',
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        pointBackgroundColor: '#009FE3',
                        tension: 0.3
                    },
                    {
                        label: 'Referencia 100%',
                        data: Array(13).fill(100),
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

// Generar las opciones de meses en el formulario
const monthSelectVentas = document.getElementById('monthVentas');
const months = ['ene-23', 'mar-23', 'may-23', 'jul-23', 'sep-23', 'nov-23', 'ene-24', 'mar-24', 'may-24', 'jul-24', 'sep-24', 'nov-24', 'dic-24'];
months.forEach((month) => {
    const option = document.createElement('option');
    option.value = month;
    option.textContent = month;
    monthSelectVentas.appendChild(option);
});

// Validar y actualizar el gráfico
document.getElementById('dataFormVentas').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectVentas.value;
    const performance = parseFloat(document.getElementById('performanceVentas').value);
    const area = parseFloat(document.getElementById('areaVentas').value);

    // Validar que los valores estén dentro del rango
    if (performance < 0 || performance > 100 || area < 0 || area > 100) {
        alert('Los valores deben estar entre 0% y 100%.');
        return;
    }

    // Enviar los datos al servidor
    axios.post('/ventas/store', {
        mes: month,
        desempeno: performance,
        area_cumplimiento: area,
    })
    .then(response => {
        if (response.data.success) {
            // Actualizar los datos del gráfico
            const index = months.indexOf(month);
            salesChart.data.datasets[0].data[index] = performance;
            salesChart.update(); // Actualizar el gráfico
        } else {
            console.error('Error en la respuesta del servidor:', response.data);
        }
    })
    .catch(error => {
        console.error('Error al guardar los datos:', error.response ? error.response.data : error.message);
    });
});

// Cargar los datos iniciales al cargar la página
axios.get('/ventas/get-data')
    .then(response => {
        const data = response.data;
        data.forEach(item => {
            const index = months.indexOf(item.mes);
            if (index !== -1) {
                salesChart.data.datasets[0].data[index] = item.desempeno;
            }
        });
        salesChart.update(); // Actualizar el gráfico
    })
    .catch(error => {
        console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
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
