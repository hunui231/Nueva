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


<h2 class="box-title">Entrega de Materiales a Tiempo</h2>
<canvas id="grafico"></canvas>
<canvas id="grafico2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartEntregaMateriales" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChartEntregaMateriales" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>

<!-- Formulario para ingresar datos -->
<h2>Ingresar Datos - Entrega de Materiales</h2>
@can('logistica.update')
<form id="dataFormEntregaMateriales">
  <label for="monthEntregaMateriales">Mes:</label>
  <select id="monthEntregaMateriales" name="monthEntregaMateriales">
  </select><br><br>
  <label for="desempenoEntregaMateriales">Área de cumplimiento (%):</label>
  <input type="number" id="desempenoEntregaMateriales" name="desempenoEntregaMateriales" min="0" max="100" step="0.01"><br><br>
  @can('admin.update')
  <label for="areaCumplimientoEntregaMateriales">Desempeño (%):</label>
  <input type="number" id="areaCumplimientoEntregaMateriales" name="areaCumplimientoEntregaMateriales" min="0" max="100" step="0.01" ><br><br>
  @endcan
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

  let desempenoData2 = Array(12).fill(0); // Datos iniciales para el gráfico 2
  let areaCumplimientoData2 = Array(12).fill(0); // Datos iniciales para el gráfico 2

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
   document.getElementById('dataFormEntregaMateriales').addEventListener('submit', async (evento) => {
    evento.preventDefault();

    try {
        // 1. Obtener elementos del formulario de manera segura
        const mesSeleccionado = document.getElementById('monthEntregaMateriales')?.value;
        const desempenoInput = document.getElementById('desempenoEntregaMateriales');
        const areaCumplimientoInput = document.getElementById('areaCumplimientoEntregaMateriales'); // Campo condicional
        
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
            throw new Error('El campo Área de cumplimiento es obligatorio');
        }

        if (desempenoNum < 0 || desempenoNum > 100) {
            throw new Error('El valor de Área de cumplimiento debe estar entre 0% y 100%');
        }

        // Validaciones para el área de cumplimiento (solo si existe el campo)
        if (areaCumplimientoInput) {
            if (areaCumplimientoNum === null || isNaN(areaCumplimientoNum)) {
                throw new Error('El campo Desempeño es obligatorio');
            }
            if (areaCumplimientoNum < 0 || areaCumplimientoNum > 100) {
                throw new Error('El valor de Desempeño debe estar entre 0% y 100%');
            }
        }

        // 4. Preparar datos para enviar al servidor
        const datos = {
            mes: mesSeleccionado,
            desempeno: desempenoNum
        };

        // Solo agregar area_cumplimiento si existe el campo
        if (areaCumplimientoInput) {
            datos.area_cumplimiento = areaCumplimientoNum;
        }

        // 5. Enviar datos al servidor
        const respuesta = await axios.post('/entrega-materiales/store', datos);

        if (!respuesta.data.success) {
            throw new Error('Error al guardar los datos');
        }

        // 6. Actualizar ambos gráficos donde corresponda
        const indice = dataLabels.indexOf(mesSeleccionado);
        const indice2 = dataLabels2.indexOf(mesSeleccionado);
        
        if (indice !== -1) {
            desempenoData[indice] = desempenoNum;
            if (areaCumplimientoNum !== null) areaCumplimientoData[indice] = areaCumplimientoNum;
            grafico.update();
        }
        
        if (indice2 !== -1) {
            desempenoData2[indice2] = desempenoNum;
            if (areaCumplimientoNum !== null) areaCumplimientoData2[indice2] = areaCumplimientoNum;
            grafico2.update();
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
<h2 class="box-title">Información de Inventarios</h2>
<canvas id="inventarioChart"></canvas>
<canvas id="inventarioChart2" style="display: none;"></canvas> <!-- Nuevo gráfico oculto inicialmente -->

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartInventario" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChartInventario" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>
<div id="ultimaActualizacionInventario" style="margin-top: 5px; font-size: 12px;"></div>
</div>

<h2 >Ingresar Datos - Inventarios</h2>
@can('logistica.update')
<form id="dataFormInventario">
  <label for="monthInventario">Mes:</label>
  <select id="monthInventario" name="monthInventario"></select><br><br>
  <label for="desempenoInventario"> Desempeño Inventario (%):</label>
  <input type="number" id="desempenoInventario" name="desempenoInventario" min="0" max="100" step="0.01"><br><br>
  @can('admin.update')
  <label for="areaCumplimientoInventario">Área de Cumplimiento (%):</label>
  <input type="number" id="areaCumplimientoInventario" name="areaCumplimientoInventario" min="0" max="100" step="0.01" ><br><br>
  @endcan
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
          spanGaps: true, 
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
           borderColor: 'rgba(255, 0, 0, 0.85)',
          borderWidth: 2,
          pointBackgroundColor: 'rgba(255, 0, 0, 0.85)',
          pointRadius: 4,
          fill: false,
            tension: 0.3,
          spanGaps: true,
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

  const anioActual = fechaActual.getFullYear().toString().slice(-2);

  const mesAnioActual = `${mesActual}-${anioActual}`;

  selectorMesInventario.value = mesAnioActual;

 // Función para mostrar última actualización
function mostrarUltimaActualizacion() {
  const ahora = new Date();
  const fechaHora = ahora.toLocaleString('es-ES', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
  document.getElementById('ultimaActualizacionInventario').textContent = `Última actualización gráfico: ${fechaHora}`;
}

// Función para actualizar el gráfico y registrar el momento
function actualizarGraficoConRegistro(grafico, datosDesempeno, datosArea, mes, desempeno, area) {
  const indice = grafico.data.labels.indexOf(mes);
  if (indice !== -1) {
    // Solo actualizar si los datos son diferentes
    if (datosDesempeno[indice] !== desempeno || (area !== null && datosArea[indice] !== area)) {
      datosDesempeno[indice] = desempeno;
      if (area !== null) {
        datosArea[indice] = area;
      }
      grafico.update();
      // Registrar la actualización del gráfico
      mostrarUltimaActualizacion();
      return true; // Indica que hubo actualización
    }
  }
  return false; // Indica que no hubo cambios
}

document.getElementById('dataFormInventario').addEventListener('submit', async (evento) => {
  evento.preventDefault();

  try {
    // 1. Obtener elementos del formulario de manera segura
    const form = document.getElementById('dataFormInventario');
    const mesSeleccionado = form.querySelector('#monthInventario')?.value;
    const desempenoInput = form.querySelector('#desempenoInventario');
    
    // Verificar existencia de elementos críticos
    if (!mesSeleccionado || !desempenoInput) {
      throw new Error('Elementos básicos del formulario no encontrados');
    }

    // 2. Obtener valores con comprobación de nulidad
    const desempeno = desempenoInput.value;
    const areaCumplimientoInput = form.querySelector('#areaCumplimientoInventario');
    const areaCumplimiento = areaCumplimientoInput ? areaCumplimientoInput.value : null;

    // 3. Convertir y validar datos
    const desempenoNum = desempeno !== "" ? parseFloat(desempeno) : null;
    const areaCumplimientoNum = areaCumplimiento !== null && areaCumplimiento !== "" ? parseFloat(areaCumplimiento) : null;

    // Validaciones básicas
    if (desempenoNum === null || isNaN(desempenoNum)) {
      throw new Error('El campo Área de Cumplimiento es obligatorio');
    }

    if (desempenoNum < 0 || desempenoNum > 100) {
      throw new Error('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
    }

    if (areaCumplimientoInput) {
      if (areaCumplimientoNum === null || isNaN(areaCumplimientoNum)) {
        throw new Error('El campo Desempeño Inventario es obligatorio');
      }
      if (areaCumplimientoNum < 0 || areaCumplimientoNum > 100) {
        throw new Error('El valor de Desempeño Inventario debe estar entre 0% y 100%');
      }
    }

    // 4. Preparar datos para enviar al servidor
    const datos = {
      mes: mesSeleccionado,
      area_cumplimiento: desempenoNum
    };

    if (areaCumplimientoInput && areaCumplimientoNum !== null) {
      datos.desempeno = areaCumplimientoNum;
    }

    // 5. Enviar datos al servidor
    const respuesta = await axios.post('/inventario/store', datos);

    if (!respuesta.data.success) {
      throw new Error(respuesta.data.message || 'Error al guardar los datos');
    }

    // 6. Actualizar ambos gráficos donde corresponda
    const actualizoGrafico1 = actualizarGraficoConRegistro(
      inventarioChart, 
      desempenoDataInventario, 
      areaCumplimientoDataInventario,
      mesSeleccionado,
      desempenoNum,
      areaCumplimientoNum
    );

    const actualizoGrafico2 = actualizarGraficoConRegistro(
      inventarioChart2, 
      desempenoDataInventario2, 
      areaCumplimientoDataInventario2,
      mesSeleccionado,
      desempenoNum,
      areaCumplimientoNum
    );

    // 7. Feedback visual
    const btn = form.querySelector('button[type="submit"]');
    if (btn) {
      const originalText = btn.textContent;
      btn.textContent = actualizoGrafico1 || actualizoGrafico2 ? '✓ Actualizado' : 'Sin cambios';
      btn.disabled = true;
      
      setTimeout(() => {
        btn.textContent = originalText;
        btn.disabled = false;
      }, 2000);
    }

  } catch (error) {
    console.error('Error en el formulario:', error);
    alert(error.message || 'Ocurrió un error al procesar el formulario');
  }
});

// Obtener los datos actualizados del servidor
function obtenerDatosInventario() {
  axios.get('/inventario/get-data')
    .then(respuesta => {
      const datos = respuesta.data;
      let huboCambios = false;
      
      datos.forEach(item => {
        const indice = dataLabelsInventario.indexOf(item.mes);
        if (indice !== -1) {
          if (desempenoDataInventario[indice] !== item.desempeno || 
              areaCumplimientoDataInventario[indice] !== item.area_cumplimiento) {
            desempenoDataInventario[indice] = item.desempeno;
            areaCumplimientoDataInventario[indice] = item.area_cumplimiento;
            huboCambios = true;
          }
        } else {
          const indice2 = dataLabelsInventario2.indexOf(item.mes);
          if (indice2 !== -1) {
            if (desempenoDataInventario2[indice2] !== item.desempeno || 
                areaCumplimientoDataInventario2[indice2] !== item.area_cumplimiento) {
              desempenoDataInventario2[indice2] = item.desempeno;
              areaCumplimientoDataInventario2[indice2] = item.area_cumplimiento;
              huboCambios = true;
            }
          }
        }
      });
      
      if (huboCambios) {
        inventarioChart.update();
        inventarioChart2.update();
        mostrarUltimaActualizacion();
      }
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
<br><br>

<meta name="csrf-token" content="{{ csrf_token() }}">

<h2 class="box-title">Inventario JLG - Porcentaje de Existencia</h2>
<canvas id="graficoJLG"></canvas>

<!-- Formulario para ingresar datos -->
<h2>Ingresar Datos - Inventario JLG</h2>
@can('logistica.update')
<form id="dataFormInventarioJLG">
  <label for="monthInventarioJLG">Mes:</label>
  <select id="monthInventarioJLG" name="monthInventarioJLG">
  </select><br><br>
  <label for="desempenoInventarioJLG">Desempeño (%):</label>
  <input type="number" id="desempenoInventarioJLG" name="desempenoInventarioJLG" min="0" max="150" step="0.01"><br><br>
  @can('admin.update')
  <label for="areaCumplimientoInventarioJLG">Área de cumplimiento (%):</label>
  <input type="number" id="areaCumplimientoInventarioJLG" name="areaCumplimientoInventarioJLG" min="0" max="150" step="0.01"><br><br>
  @endcan
  <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Configurar el token CSRF en Axios
  axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // Contexto del gráfico
  const ctxJLG = document.getElementById('graficoJLG').getContext('2d');

  // Función para generar opciones de meses
  function generateMonthOptionsJLG(year) {
    const months = ["ene", "feb", "mar", "abr", "may", "jun", "jul", "ago", "sep", "oct", "nov", "dic"];
    return months.map(month => `${month}-${year.toString().slice(-2)}`);
  }

  // Solo datos para 2025
  const dataLabelsJLG = generateMonthOptionsJLG(25); // 2025

  // Datos iniciales
  let desempenoDataJLG = Array(12).fill(0);
  let areaCumplimientoDataJLG = Array(12).fill(100); // Línea de 100%

  // Configuración del gráfico
  const graficoJLG = new Chart(ctxJLG, {
    type: 'bar',
    data: {
      labels: dataLabelsJLG,
      datasets: [
        {
          label: "Porcentaje de Existencia",
          data: desempenoDataJLG,
          backgroundColor: 'rgba(54, 162, 235, 0.7)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        },
        {
          label: "Límite Inferior (95%)",
          data: Array(12).fill(95),
          type: 'line',
          borderColor: 'orange',
          borderWidth: 2,
          pointRadius: 0,
          borderDash: [5, 5]
        },
        {
          label: "Ideal (100%)",
          data: areaCumplimientoDataJLG,
          type: 'line',
          borderColor: 'green',
          borderWidth: 2,
          pointRadius: 0
        },
        {
          label: "Límite Superior (110%)",
          data: Array(12).fill(110),
          type: 'line',
          borderColor: 'red',
          borderWidth: 2,
          pointRadius: 0,
          borderDash: [5, 5]
        }
      ]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: false,
          min: 90,
          max: 120,
          ticks: {
            callback: function(value) {
              return value + "%";
            }
          }
        }
      },
      plugins: {
        legend: {
          position: 'top',
          labels: {
            boxWidth: 12
          }
        },
        tooltip: {
          callbacks: {
            label: function(context) {
              return context.dataset.label + ": " + context.raw + "%";
            }
          }
        }
      }
    }
  });

  // Generar opciones de meses en el formulario (solo 2025)
  const monthSelectJLG = document.getElementById('monthInventarioJLG');
  dataLabelsJLG.forEach((label) => {
    const option = document.createElement('option');
    option.value = label;
    option.textContent = label;
    monthSelectJLG.appendChild(option);
  });

  // Establecer mes actual como valor predeterminado
  const currentDate = new Date();
  const month = currentDate.toLocaleString('default', { month: 'short' }).toLowerCase();
  const year = currentDate.getFullYear().toString().slice(-2);
  monthSelectJLG.value = `${month}-${year}`;

  // Manejar envío del formulario
  document.getElementById('dataFormInventarioJLG').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const mes = document.getElementById('monthInventarioJLG').value;
    const desempeno = parseFloat(document.getElementById('desempenoInventarioJLG').value);
    const areaCumplimiento = parseFloat(document.getElementById('areaCumplimientoInventarioJLG').value) || 100;

    if (isNaN(desempeno)) {
      alert('Por favor ingrese un valor de desempeño válido');
      return;
    }

    try {
      const response = await axios.post('/inventario-jlg/store', {
        mes: mes,
        desempeno: desempeno,
        area_cumplimiento: areaCumplimiento
      });

      // Actualizar gráfico
      const index = dataLabelsJLG.indexOf(mes);
      if (index !== -1) {
        desempenoDataJLG[index] = desempeno;
        areaCumplimientoDataJLG[index] = areaCumplimiento;
        graficoJLG.update();
      }

      // Feedback visual
      const btn = e.target.querySelector('button[type="submit"]');
      const originalText = btn.textContent;
      btn.textContent = '✓ Actualizado';
      btn.disabled = true;
      setTimeout(() => {
        btn.textContent = originalText;
        btn.disabled = false;
      }, 2000);
      
    } catch (error) {
      console.error('Error:', error);
      alert('Error al guardar los datos: ' + (error.response?.data?.message || error.message));
    }
  });

  // Cargar datos iniciales (solo para 2025)
  async function fetchDataJLG() {
    try {
      const response = await axios.get('/inventario-jlg/get-data');
      response.data.forEach(item => {
        const index = dataLabelsJLG.indexOf(item.mes);
        if (index !== -1) {
          desempenoDataJLG[index] = item.desempeno;
          areaCumplimientoDataJLG[index] = item.area_cumplimiento;
        }
      });
      graficoJLG.update();
    } catch (error) {
      console.error('Error al cargar datos:', error);
    }
  }

  fetchDataJLG();
});
</script>
@endsection