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
        background-color:rgb(255, 0, 0); 
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
    background:rgb(255, 0, 0);
    color: white;
    padding: 10px 20px;
    border-radius: 10px;
    display: inline-block;
  }
    
    </style>

<h2 class="maspudo">Operacion MM</h2>

<br>
<h2 class="box-title">SCRAP Donaldson</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chart-container">
    <canvas id="scrapChart1"></canvas>
    <canvas id="scrapChart2" style="display: none;"></canvas> <!-- Segundo gráfico oculto -->
</div>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChart" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>

@can('taller.update')
<form id="formScrap1">
    <label for="monthScrap1">Mes:</label>
    <select id="monthScrap1" name="monthScrap1"></select><br><br>
    
    <label for="desempeno1">Desempeño (%):</label>
    <input type="number" id="desempeno1" name="desempeno1" min="0" max="100" step="0.01" required><br><br>
    
    @can('admin.update')
    <label for="areaCumplimiento1">Área de Cumplimiento (%):</label>
    <input type="number" id="areaCumplimiento1" name="areaCumplimiento1" min="0" max="100" step="0.01"><br><br>
    @endcan
    
    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Datos iniciales del gráfico
    const ctx1 = document.getElementById('scrapChart1').getContext('2d');
    const ctx2 = document.getElementById('scrapChart2').getContext('2d');

    // Fechas para el primer gráfico (ene-23 a dic-24)
    const monthsScrap1 = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];

    // Fechas para el segundo gráfico (ene-25 a dic-25)
    const monthsScrap2 = ["ene-25", "feb-25", "mar-25", "abr-25", "may-25", "jun-25", "jul-25", "ago-25", "sep-25", "oct-25", "nov-25", "dic-25"];

    // Datos iniciales para ambos gráficos
    let scrapData1 = Array(24).fill(null); // Datos para el gráfico 1
    let scrapData2 = Array(12).fill(0); // Datos para el gráfico 2 (12 meses en 2025)

    // Configuración del gráfico 1
    const scrapChart1 = new Chart(ctx1, {
        type: 'line',
        data: {
            labels: monthsScrap1,
            datasets: [
                {
                    label: "Scrap %",
                    data: scrapData1,
                    borderColor: "#007bff",
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Límite",
                    data: Array(24).fill(2.0), // Línea roja fija en 2.0%
                    borderColor: "red",
                    borderWidth: 2,
                    pointRadius: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 3,
                    ticks: {
                        callback: value => value + "%" // Agrega % a los valores del eje Y
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Configuración del gráfico 2
    const scrapChart2 = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: monthsScrap2,
            datasets: [
                {
                    label: "Scrap %",
                    data: scrapData2,
                    borderColor: "#007bff",
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Límite",
                    data: Array(12).fill(2.0), // Línea roja fija en 2.0%
                    borderColor: "red",
                    borderWidth: 2,
                    pointRadius: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 3,
                    ticks: {
                        callback: value => value + "%" // Agrega % a los valores del eje Y
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Función para obtener el mes actual en formato "ene-23"
    function getCurrentMonth() {
        const date = new Date();
        const month = date.toLocaleString('default', { month: 'short' }).toLowerCase();
        const year = date.getFullYear().toString().slice(-2); // Obtiene los últimos dos dígitos del año
        return `${month}-${year}`;
    }

    // Generar las opciones de meses en el formulario
    const monthSelectScrap1 = document.getElementById('monthScrap1');
    const currentMonth = getCurrentMonth(); // Obtener el mes actual

    // Generar opciones de meses para el formulario (ene-23 a dic-25)
    const allMonths = [...monthsScrap1, ...monthsScrap2];
    allMonths.forEach((month) => {
        const option = document.createElement('option');
        option.value = month;
        option.textContent = month;
        monthSelectScrap1.appendChild(option);

        // Seleccionar el mes actual en el formulario
        if (month === currentMonth) {
            option.selected = true;
        }
    });

    document.getElementById('formScrap1').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = document.getElementById('monthScrap1').value;
    const desempeno = document.getElementById('desempeno1').value;
    
    // Manejo seguro del campo restringido
    const areaCumplimientoInput = document.getElementById('areaCumplimiento1');
    const areaCumplimiento = areaCumplimientoInput ? areaCumplimientoInput.value : null;

    // Convertir a número (permite null si está vacío o no existe)
    const desempenoNum = desempeno === "" ? null : parseFloat(desempeno);
    const areaCumplimientoNum = areaCumplimiento === null || areaCumplimiento === "" ? null : parseFloat(areaCumplimiento);

    // Validación mínima - solo requiere desempeño
    if (desempenoNum === null) {
        alert('Debe ingresar al menos el valor de Desempeño');
        return;
    }

    // Validar rangos
    if (desempenoNum !== null && (desempenoNum < 0 || desempenoNum > 100)) {
        alert('El valor de Desempeño debe estar entre 0% y 100%');
        return;
    }
    if (areaCumplimientoNum !== null && (areaCumplimientoNum < 0 || areaCumplimientoNum > 100)) {
        alert('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
        return;
    }

    axios.post('/scrap-donaldson/store', {
        mes: month,
        desempeno: desempenoNum,
        area_cumplimiento: areaCumplimientoNum,
    })
    .then(response => {
        if (response.data.success) {
            // Actualizar ambos gráficos según corresponda
            const index = allMonths.indexOf(month);
            
            // Actualizar gráfico 1 (2023-2024)
            if (index < 24) {
                scrapChart1.data.datasets[0].data[index] = desempenoNum;
                if (areaCumplimientoNum !== null) {
                    scrapChart1.data.datasets[1].data[index] = areaCumplimientoNum;
                }
                scrapChart1.update();
            }
            
            // Actualizar gráfico 2 (2025)
            if (index >= 24) {
                scrapChart2.data.datasets[0].data[index - 24] = desempenoNum;
                if (areaCumplimientoNum !== null) {
                    scrapChart2.data.datasets[1].data[index - 24] = areaCumplimientoNum;
                }
                scrapChart2.update();
            }
        } else {
            console.error('Error en la respuesta:', response.data);
            alert('Error al actualizar los datos');
        }
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
        alert('Error al enviar los datos al servidor');
    });
});
    // Cargar los datos iniciales al cargar la página
    axios.get('/scrap-donaldson/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index = allMonths.indexOf(item.mes);
                if (index !== -1) {
                    if (index < 24) {
                        scrapChart1.data.datasets[0].data[index] = item.desempeno;
                        scrapChart1.data.datasets[1].data[index] = item.area_cumplimiento;
                    } else {
                        scrapChart2.data.datasets[0].data[index - 24] = item.desempeno;
                        scrapChart2.data.datasets[1].data[index - 24] = item.area_cumplimiento;
                    }
                }
            });
            scrapChart1.update(); // Actualizar el gráfico 1
            scrapChart2.update(); // Actualizar el gráfico 2
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });

    // Alternar entre gráficos
    let currentChart = 1;
    document.getElementById('nextChart').addEventListener('click', () => {
        if (currentChart === 1) {
            document.getElementById('scrapChart1').style.display = 'none';
            document.getElementById('scrapChart2').style.display = 'block';
            currentChart = 2;
        } else {
            document.getElementById('scrapChart1').style.display = 'block';
            document.getElementById('scrapChart2').style.display = 'none';
            currentChart = 1;
        }
    });

    document.getElementById('prevChart').addEventListener('click', () => {
        if (currentChart === 1) {
            document.getElementById('scrapChart1').style.display = 'none';
            document.getElementById('scrapChart2').style.display = 'block';
            currentChart = 2;
        } else {
            document.getElementById('scrapChart1').style.display = 'block';
            document.getElementById('scrapChart2').style.display = 'none';
            currentChart = 1;
        }
    });
</script>

<br><br>
<h2 class="box-title">SCRAP Taller</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chart-container">
    <canvas id="scrapChartTaller1"></canvas>
    <canvas id="scrapChartTaller2" style="display: none;"></canvas> <!-- Segundo gráfico oculto -->
</div>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartTaller" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChartTaller" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>

@can('taller.update')
<form id="formScrapTaller">
    <label for="monthScrapTaller">Mes:</label>
    <select id="monthScrapTaller" name="monthScrapTaller"></select><br><br>
    <label for="desempenoTaller">Desempeño (%):</label>
    <input type="number" id="desempenoTaller" name="desempenoTaller" min="0" max="100" step="0.01"><br><br>
    @can('admin.update')
    <label for="areaCumplimientoTaller">Área de Cumplimiento (%):</label>
    <input type="number" id="areaCumplimientoTaller" name="areaCumplimientoTaller" min="0" max="100" step="0.01"><br><br>
     @endcan
    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script>
    // Configurar el token CSRF en Axios
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Función para generar opciones de meses dinámicamente
    function generateMonthOptions() {
        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const currentMonth2 = currentDate.getMonth();

        const monthSelect = document.getElementById('monthScrapTaller');
        monthSelect.innerHTML = ''; // Limpiar opciones anteriores

        for (let month = 0; month < 12; month++) {
            const option = document.createElement('option');
            option.value = `${new Date(currentYear, month).toLocaleString('default', { month: 'short' }).toLowerCase()}-${currentYear.toString().slice(-2)}`;
            option.textContent = `${new Date(currentYear, month).toLocaleString('default', { month: 'short' }).toLowerCase()}-${currentYear.toString().slice(-2)}`;
            monthSelect.appendChild(option);
        }
    }

    // Validación de años futuros
    function validateYear(year) {
        const currentYear = new Date().getFullYear();
        return year >= currentYear;
    }

    // Datos iniciales del gráfico
    const ctxTaller1 = document.getElementById('scrapChartTaller1').getContext('2d');
    const ctxTaller2 = document.getElementById('scrapChartTaller2').getContext('2d');

    // Array original para el primer gráfico
    const monthsScrapTaller1 = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];

    // Array nuevo para el segundo gráfico
    const monthsScrapTaller2 = ["ene-25", "feb-25", "mar-25", "abr-25", "may-25", "jun-25", "jul-25", "ago-25", "sep-25", "oct-25", "nov-25", "dic-25"];

    // Datos iniciales para ambos gráficos
    let scrapDataTaller1 = Array(24).fill(null); // Datos para el gráfico 1
    let scrapDataTaller2 = Array(12).fill(0); // Datos para el gráfico 2

    // Configuración del gráfico 1
    const scrapChartTaller1 = new Chart(ctxTaller1, {
        type: 'line',
        data: {
            labels: monthsScrapTaller1,
            datasets: [
                {
                    label: "Scrap %",
                    data: scrapDataTaller1,
                    borderColor: "#007bff",
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Límite",
                    data: Array(24).fill(3.0), // Línea roja fija en 3.0%
                    borderColor: "red",
                    borderWidth: 2,
                    pointRadius: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 12, // Ajusta el límite máximo del eje Y
                    ticks: {
                        callback: value => value + "%" // Agrega % a los valores del eje Y
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Configuración del gráfico 2
    const scrapChartTaller2 = new Chart(ctxTaller2, {
        type: 'line',
        data: {
            labels: monthsScrapTaller2,
            datasets: [
                {
                    label: "Scrap %",
                    data: scrapDataTaller2,
                    borderColor: "#007bff",
                    tension: 0.3,
                    pointRadius: 5,
                    pointBackgroundColor: "#007bff"
                },
                {
                    label: "Límite",
                    data: Array(12).fill(3.0), // Línea roja fija en 3.0%
                    borderColor: "red",
                    borderWidth: 2,
                    pointRadius: 0.1
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 5, // Ajusta el límite máximo del eje Y
                    ticks: {
                        callback: value => value + "%" // Agrega % a los valores del eje Y
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });

    // Generar las opciones de meses en el formulario
    generateMonthOptions();

    // Función para obtener el mes actual en formato "ene-23"
    function getCurrentMonth() {
        const date = new Date();
        const month = date.toLocaleString('default', { month: 'short' }).toLowerCase();
        const year = date.getFullYear().toString().slice(-2); // Obtiene los últimos dos dígitos del año
        return `${month}-${year}`;
    }

    // Seleccionar el mes actual en el formulario
    const currentMonth2 = getCurrentMonth();
    document.getElementById('monthScrapTaller').value = currentMonth2;

    document.getElementById('formScrapTaller').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = document.getElementById('monthScrapTaller').value;
    const desempeno = document.getElementById('desempenoTaller').value;
    
    // Manejo seguro del campo restringido
    const areaCumplimientoInput = document.getElementById('areaCumplimientoTaller');
    const areaCumplimiento = areaCumplimientoInput ? areaCumplimientoInput.value : null;

    // Convertir a número (permite null si está vacío o no existe)
    const desempenoNum = desempeno === "" ? null : parseFloat(desempeno);
    const areaCumplimientoNum = areaCumplimiento === null || areaCumplimiento === "" ? null : parseFloat(areaCumplimiento);

    // Validación mínima - solo requiere desempeño
    if (desempenoNum === null) {
        alert('Debe ingresar al menos el valor de Desempeño');
        return;
    }

    // Validar rangos
    if (desempenoNum !== null && (desempenoNum < 0 || desempenoNum > 100)) {
        alert('El valor de Desempeño debe estar entre 0% y 100%');
        return;
    }
    if (areaCumplimientoNum !== null && (areaCumplimientoNum < 0 || areaCumplimientoNum > 100)) {
        alert('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
        return;
    }

    axios.post('/scrap-taller/store', {
        mes: month,
        desempeno: desempenoNum,
        area_cumplimiento: areaCumplimientoNum,
    })
    .then(response => {
        if (response.data.success) {
            // Actualizar ambos gráficos según corresponda
            const index1 = monthsScrapTaller1.indexOf(month);
            const index2 = monthsScrapTaller2.indexOf(month);
            
            // Actualizar gráfico 1 (2023-2024)
            if (index1 !== -1) {
                scrapChartTaller1.data.datasets[0].data[index1] = desempenoNum;
                if (areaCumplimientoNum !== null) {
                    scrapChartTaller1.data.datasets[1].data[index1] = areaCumplimientoNum;
                }
                scrapChartTaller1.update();
            }
            
            // Actualizar gráfico 2 (2025)
            if (index2 !== -1) {
                scrapChartTaller2.data.datasets[0].data[index2] = desempenoNum;
                if (areaCumplimientoNum !== null) {
                    scrapChartTaller2.data.datasets[1].data[index2] = areaCumplimientoNum;
                }
                scrapChartTaller2.update();
            }
            
            // Feedback visual opcional (sin alert)
            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = '✓';
            setTimeout(() => { btn.textContent = originalText; }, 1000);
        } else {
            console.error('Error en la respuesta:', response.data);
        }
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
    });
});
    // Cargar los datos iniciales al cargar la página
    axios.get('/scrap-taller/get-data')
        .then(response => {
            const data = response.data;
            data.forEach(item => {
                const index1 = monthsScrapTaller1.indexOf(item.mes);
                const index2 = monthsScrapTaller2.indexOf(item.mes);
                if (index1 !== -1) {
                    scrapChartTaller1.data.datasets[0].data[index1] = item.desempeno;
                }
                if (index2 !== -1) {
                    scrapChartTaller2.data.datasets[0].data[index2] = item.desempeno;
                }
            });
            scrapChartTaller1.update(); // Actualizar el gráfico 1
            scrapChartTaller2.update(); // Actualizar el gráfico 2
        })
        .catch(error => {
            console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
        });

    // Alternar entre gráficos
    let currentChartTaller = 1;
    document.getElementById('nextChartTaller').addEventListener('click', () => {
        if (currentChartTaller === 1) {
            document.getElementById('scrapChartTaller1').style.display = 'none';
            document.getElementById('scrapChartTaller2').style.display = 'block';
            currentChartTaller = 2;
        } else {
            document.getElementById('scrapChartTaller1').style.display = 'block';
            document.getElementById('scrapChartTaller2').style.display = 'none';
            currentChartTaller = 1;
        }
    });

    document.getElementById('prevChartTaller').addEventListener('click', () => {
        if (currentChartTaller === 1) {
            document.getElementById('scrapChartTaller1').style.display = 'none';
            document.getElementById('scrapChartTaller2').style.display = 'block';
            currentChartTaller = 2;
        } else {
            document.getElementById('scrapChartTaller1').style.display = 'block';
            document.getElementById('scrapChartTaller2').style.display = 'none';
            currentChartTaller = 1;
        }
    });
</script>

<p></p>

<br><br>
<h2 class="box-title">SCRAP Forjas</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
<div class="chart-container">
    <canvas id="scrapChartForjas1"></canvas>
    <canvas id="scrapChartForjas2" style="display: none;"></canvas> <!-- Segundo gráfico oculto -->
</div>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartForjas" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ 2024</button>
  <button id="nextChartForjas" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">2025 ▶</button>
</div>

@can('taller.update')
<form id="formScrapForjas">
    <label for="monthScrapForjas">Mes:</label>
    <select id="monthScrapForjas" name="monthScrapForjas"></select><br><br>
    
    <label for="desempenoForjas">Desempeño (%):</label>
    <input type="number" id="desempenoForjas" name="desempenoForjas" min="0" max="100" step="0.01" ><br><br>
    @can('admin.update')
    <label for="areaCumplimientoForjas">Área de Cumplimiento (%):</label>
    <input type="number" id="areaCumplimientoForjas" name="areaCumplimientoForjas" min="0" max="100" step="0.01" ><br><br>
    @endcan
    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script>
    // Encapsulación en una función para evitar conflictos
    (function () {
        // Configurar el token CSRF en Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Datos iniciales del gráfico
        const ctxForjas1 = document.getElementById('scrapChartForjas1').getContext('2d');
        const ctxForjas2 = document.getElementById('scrapChartForjas2').getContext('2d');

        // Fechas para el primer gráfico (ene-23 a dic-24)
        const monthsForjas1 = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23", "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];

        // Fechas para el segundo gráfico (ene-25 a dic-25)
        const monthsForjas2 = ["ene-25", "feb-25", "mar-25", "abr-25", "may-25", "jun-25", "jul-25", "ago-25", "sep-25", "oct-25", "nov-25", "dic-25"];

        // Datos iniciales para ambos gráficos
        let scrapDataForjas1 = Array(24).fill(null); // Datos para el gráfico 1
        let scrapDataForjas2 = Array(12).fill(0); // Datos para el gráfico 2 (12 meses en 2025)

        // Configuración del gráfico 1
        const scrapChartForjas1 = new Chart(ctxForjas1, {
            type: 'line',
            data: {
                labels: monthsForjas1,
                datasets: [
                    {
                        label: "Scrap %",
                        data: scrapDataForjas1,
                        borderColor: "#007bff",
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: "#007bff"
                    },
                    {
                        label: "Límite",
                        data: Array(24).fill(7.5), // Línea roja fija en 7.5%
                        borderColor: "red",
                        borderWidth: 2,
                        pointRadius: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10, // Ajusta el límite máximo del eje Y
                        ticks: {
                            callback: value => value + "%" // Agrega % a los valores del eje Y
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Configuración del gráfico 2
        const scrapChartForjas2 = new Chart(ctxForjas2, {
            type: 'line',
            data: {
                labels: monthsForjas2,
                datasets: [
                    {
                        label: "Scrap %",
                        data: scrapDataForjas2,
                        borderColor: "#007bff",
                        tension: 0.3,
                        pointRadius: 5,
                        pointBackgroundColor: "#007bff"
                    },
                    {
                        label: "Límite",
                        data: Array(12).fill(7.5), // Línea roja fija en 7.5%
                        borderColor: "red",
                        borderWidth: 2,
                        pointRadius: 0.1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 10, // Ajusta el límite máximo del eje Y
                        ticks: {
                            callback: value => value + "%" // Agrega % a los valores del eje Y
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + "; " + tooltipItem.raw.toFixed(2) + "%";
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Función para obtener el mes actual en formato "ene-23"
        function getCurrentMonth() {
            const date = new Date();
            const month = date.toLocaleString('default', { month: 'short' }).toLowerCase();
            const year = date.getFullYear().toString().slice(-2); // Obtiene los últimos dos dígitos del año
            return `${month}-${year}`;
        }

        // Generar las opciones de meses en el formulario
        const monthSelectForjas = document.getElementById('monthScrapForjas');
        const currentMonth = getCurrentMonth(); // Obtener el mes actual

        // Generar opciones de meses para el formulario (ene-23 a dic-25)
        const allMonths = [...monthsForjas1, ...monthsForjas2];
        allMonths.forEach((month) => {
            const option = document.createElement('option');
            option.value = month;
            option.textContent = month;
            monthSelectForjas.appendChild(option);

            // Seleccionar el mes actual en el formulario
            if (month === currentMonth) {
                option.selected = true;
            }
        });

document.getElementById('formScrapForjas').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = monthSelectForjas.value;
    const desempeno = document.getElementById('desempenoForjas').value;
    
    // Manejo seguro del campo restringido
    const areaCumplimientoInput = document.getElementById('areaCumplimientoForjas');
    const areaCumplimiento = areaCumplimientoInput ? areaCumplimientoInput.value : null;

    // Convertir a número (permite null si está vacío o no existe)
    const desempenoNum = desempeno === "" ? null : parseFloat(desempeno);
    const areaCumplimientoNum = areaCumplimiento === null || areaCumplimiento === "" ? null : parseFloat(areaCumplimiento);

    // Validación mínima - solo requiere desempeño
    if (desempenoNum === null) {
        alert('Debe ingresar al menos el valor de Desempeño');
        return;
    }

    // Validar rangos
    if (desempenoNum !== null && (desempenoNum < 0 || desempenoNum > 100)) {
        alert('El valor de Desempeño debe estar entre 0% y 100%');
        return;
    }
    if (areaCumplimientoNum !== null && (areaCumplimientoNum < 0 || areaCumplimientoNum > 100)) {
        alert('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
        return;
    }

    axios.post('/scrap-forjas/store', {
        mes: month,
        desempeno: desempenoNum,
        area_cumplimiento: areaCumplimientoNum,
    })
    .then(response => {
        if (response.data.success) {
            // Actualizar ambos gráficos según corresponda
            const index1 = monthsForjas1.indexOf(month);
            const index2 = monthsForjas2.indexOf(month);
            
            // Actualizar gráfico 1 (2023-2024)
            if (index1 !== -1) {
                scrapChartForjas1.data.datasets[0].data[index1] = desempenoNum;
                if (areaCumplimientoNum !== null) {
                    scrapChartForjas1.data.datasets[1].data[index1] = areaCumplimientoNum;
                }
                scrapChartForjas1.update();
            }
            
            // Actualizar gráfico 2 (2025)
            if (index2 !== -1) {
                scrapChartForjas2.data.datasets[0].data[index2] = desempenoNum;
                if (areaCumplimientoNum !== null) {
                    scrapChartForjas2.data.datasets[1].data[index2] = areaCumplimientoNum;
                }
                scrapChartForjas2.update();
            }
            
            // Feedback visual opcional (sin alert)
            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = '✓';
            setTimeout(() => { btn.textContent = originalText; }, 1000);
        } else {
            console.error('Error en la respuesta:', response.data);
        }
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
    });
});
        // Cargar los datos iniciales al cargar la página
        axios.get('/scrap-forjas/get-data')
            .then(response => {
                const data = response.data;
                data.forEach(item => {
                    const index = allMonths.indexOf(item.mes);
                    if (index !== -1) {
                        if (index < 24) {
                            scrapChartForjas1.data.datasets[0].data[index] = item.desempeno;
                            scrapChartForjas1.data.datasets[1].data[index] = item.area_cumplimiento;
                        } else {
                            scrapChartForjas2.data.datasets[0].data[index - 24] = item.desempeno;
                            scrapChartForjas2.data.datasets[1].data[index - 24] = item.area_cumplimiento;
                        }
                    }
                });
                scrapChartForjas1.update(); // Actualizar el gráfico 1
                scrapChartForjas2.update(); // Actualizar el gráfico 2
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
            });

        // Alternar entre gráficos
        let currentChartForjas = 1;
        document.getElementById('nextChartForjas').addEventListener('click', () => {
            if (currentChartForjas === 1) {
                document.getElementById('scrapChartForjas1').style.display = 'none';
                document.getElementById('scrapChartForjas2').style.display = 'block';
                currentChartForjas = 2;
            } else {
                document.getElementById('scrapChartForjas1').style.display = 'block';
                document.getElementById('scrapChartForjas2').style.display = 'none';
                currentChartForjas = 1;
            }
        });

        document.getElementById('prevChartForjas').addEventListener('click', () => {
            if (currentChartForjas === 1) {
                document.getElementById('scrapChartForjas1').style.display = 'none';
                document.getElementById('scrapChartForjas2').style.display = 'block';
                currentChartForjas = 2;
            } else {
                document.getElementById('scrapChartForjas1').style.display = 'block';
                document.getElementById('scrapChartForjas2').style.display = 'none';
                currentChartForjas = 1;
            }
        });
    })(); // Fin de la encapsulación
</script>
<br><br>
<h2 class="box-title">Cumplimiento Plan de Producción Taller</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="chart-container">
    <canvas id="chart1"></canvas>
    <canvas id="chart2"></canvas> <!-- Ahora no está oculto inicialmente -->
</div>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartBtn" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartBtn" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

@can('taller.update')
<form id="dataForm">
    <label for="monthSelect">Mes:</label>
    <select id="monthSelect" name="monthSelect"></select><br><br>
    <label for="performanceInput">Desempeño (%):</label>
    <input type="number" id="performanceInput" name="performanceInput" min="0" max="100" step="0.01" ><br><br>
    @can('admin.update')
    <label for="complianceInput">Área de Cumplimiento (%):</label>
    <input type="number" id="complianceInput" name="complianceInput" min="0" max="100" step="0.01" ><br><br>
    @endcan
    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script>
    (function () {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const chart1Canvas = document.getElementById('chart1');
        const chart2Canvas = document.getElementById('chart2');
        const chart1Ctx = chart1Canvas.getContext('2d');
        const chart2Ctx = chart2Canvas.getContext('2d');

        // Fechas diferentes para cada gráfico
        const monthsChart1 = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23",
                              "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];

        const monthsChart2 = ["ene-25", "feb-25", "mar-25", "abr-25", "may-25", "jun-25", "jul-25", "ago-25", "sep-25", "oct-25", "nov-25", "dic-25"];

        let chart1Data = Array(24).fill(null);
        let chart2Data = Array(12).fill(null);

        function createChartConfig(labels, data) {
            return {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: "Cumplimiento %",
                            data: data,
                            borderColor: "#007bff",
                            backgroundColor: "rgba(0, 123, 255, 0.1)",
                            tension: 0.3,
                            pointRadius: 5,
                            pointBackgroundColor: "#007bff",
                            fill: false
                        },
                        {
                            label: "Banda roja",
                            data: Array(labels.length).fill(95),
                            borderColor: "red",
                            backgroundColor: "red",
                            borderWidth: 1,
                            fill: true,
                            pointRadius: 0,
                            tension: 0
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 0,
                            max: 100,
                            ticks: {
                                callback: value => value + "%"
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.y !== null) {
                                        label += context.parsed.y.toFixed(2) + '%';
                                    }
                                    return label;
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    }
                }
            };
        }

        const chart1 = new Chart(chart1Ctx, createChartConfig(monthsChart1, chart1Data));
        const chart2 = new Chart(chart2Ctx, createChartConfig(monthsChart2, chart2Data));

   function generateMonthOptions() {
    const monthSelect = document.getElementById('monthSelect');
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    
    // Obtener el mes actual en formato "ene-23"
    const currentMonthFormatted = getMonthFormatted(currentDate);
    
    monthSelect.innerHTML = ''; // Limpiar opciones anteriores

    // Generar meses desde el año actual hasta dos años en el futuro
    for (let year = currentYear; year <= currentYear + 2; year++) {
        for (let month = 0; month < 12; month++) {
            const date = new Date(year, month);
            const monthFormatted = getMonthFormatted(date);
            
            const option = document.createElement('option');
            option.value = monthFormatted;
            option.textContent = monthFormatted;
            monthSelect.appendChild(option);

            // Seleccionar automáticamente el mes actual
            if (monthFormatted === currentMonthFormatted) {
                option.selected = true;
            }
        }
    }
}

// Función auxiliar para formatear mes-año (ej: "ene-23")
function getMonthFormatted(date) {
    const monthNames = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 
                        'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    const month = monthNames[date.getMonth()];
    const year = date.getFullYear().toString().slice(-2);
    return `${month}-${year}`;
}

// Llamar a la función
generateMonthOptions();

        document.getElementById('dataForm').addEventListener('submit', (event) => {
            event.preventDefault();

            const month = document.getElementById('monthSelect').value;
            const performance = document.getElementById('performanceInput').value;
            
            // Manejo seguro del campo restringido
            const complianceInput = document.getElementById('complianceInput');
            const compliance = complianceInput ? complianceInput.value : null;

            // Convertir a número (permite null si está vacío o no existe)
            const performanceNum = performance === "" ? null : parseFloat(performance);
            const complianceNum = compliance === null || compliance === "" ? null : parseFloat(compliance);

            // Validación mínima - solo requiere performance
            if (performanceNum === null) {
                alert('Debe ingresar al menos el valor de Desempeño');
                return;
            }

            // Validar rangos
            if (performanceNum !== null && (performanceNum < 0 || performanceNum > 100)) {
                alert('El valor de Desempeño debe estar entre 0% y 100%');
                return;
            }
            if (complianceNum !== null && (complianceNum < 0 || complianceNum > 100)) {
                alert('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
                return;
            }

            axios.post('/cumplimiento-taller/store', {
                mes: month,
                desempeno: performanceNum,
                area_cumplimiento: complianceNum,
            })
            .then(response => {
                if (response.data.success) {
                    // Actualizar ambos gráficos según corresponda
                    const index1 = monthsChart1.indexOf(month);
                    const index2 = monthsChart2.indexOf(month);
                    
                    // Actualizar gráfico 1 (2023-2024)
                    if (index1 !== -1) {
                        chart1.data.datasets[0].data[index1] = performanceNum;
                        chart1.update();
                    }
                    
                    // Actualizar gráfico 2 (2025)
                    if (index2 !== -1) {
                        chart2.data.datasets[0].data[index2] = performanceNum;
                        chart2.update();
                    }
                    
                    // Feedback visual discreto
                    const btn = event.target.querySelector('button[type="submit"]');
                    const originalText = btn.textContent;
                    btn.textContent = '✓';
                    setTimeout(() => { btn.textContent = originalText; }, 1000);
                } else {
                    console.error('Error en la respuesta:', response.data);
                }
            })
            .catch(error => {
                console.error('Error:', error.response ? error.response.data : error.message);
                alert('Error al guardar los datos. Por favor, inténtelo de nuevo.');
            });
        });

        // Cargar datos iniciales
        function loadInitialData() {
            axios.get('/cumplimiento-taller/get-data')
                .then(response => {
                    const data = response.data;
                    data.forEach(item => {
                        const index1 = monthsChart1.indexOf(item.mes);
                        const index2 = monthsChart2.indexOf(item.mes);
                        
                        if (index1 !== -1) {
                            chart1.data.datasets[0].data[index1] = item.desempeno;
                        }
                        if (index2 !== -1) {
                            chart2.data.datasets[0].data[index2] = item.desempeno;
                        }
                    });
                    chart1.update();
                    chart2.update();
                })
                .catch(error => {
                    console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
                });
        }

        loadInitialData();

        // Manejo de navegación entre gráficos
        let activeChart = 1;
        chart1Canvas.style.display = 'block';
        chart2Canvas.style.display = 'none';

        function toggleCharts() {
            if (activeChart === 1) {
                chart1Canvas.style.display = 'none';
                chart2Canvas.style.display = 'block';
                activeChart = 2;
            } else {
                chart1Canvas.style.display = 'block';
                chart2Canvas.style.display = 'none';
                activeChart = 1;
            }
        }

        document.getElementById('nextChartBtn').addEventListener('click', toggleCharts);
        document.getElementById('prevChartBtn').addEventListener('click', toggleCharts);
    })();
</script>
<br>
<h2 class="box-title">Cumplimiento al Plan de Producción<br>Maquinados Forjas</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="chart-container">
    <canvas id="forjasProduccionChart1"></canvas>
    <canvas id="forjasProduccionChart2" style="display: none;"></canvas> <!-- Segundo gráfico oculto -->
</div>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartForjasProduccion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartForjasProduccion" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

@can('taller.update')
<form id="formForjasProduccion">
    <label for="monthForjasProduccion">Mes:</label>
    <select id="monthForjasProduccion" name="monthForjasProduccion"></select><br><br>
 
    <label for="forjasProduccion">Desempeño (%):</label>
    <input type="number" id="forjasProduccion" name="forjasProduccion" min="0" max="100" step="0.01" ><br><br>
    @can('admin.update')
    <label for="forjasCumplimiento">Área de Cumplimiento (%):</label>
    <input type="number" id="forjasCumplimiento" name="forjasCumplimiento" min="0" max="100" step="0.01" ><br><br>
    @endcan
    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan

<script>
    // Encapsulación en una función para evitar conflictos
    (function () {
        // Configurar el token CSRF en Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Variables únicas para gráficos y datos
        const ctxForjasProduccion1 = document.getElementById('forjasProduccionChart1').getContext('2d');
        const ctxForjasProduccion2 = document.getElementById('forjasProduccionChart2').getContext('2d');

        // Fechas para el primer gráfico (ene-23 a dic-24)
        const monthsForjasProduccion1 = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23",
                                       "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];

        // Fechas para el segundo gráfico (ene-25 a dic-25)
        const monthsForjasProduccion2 = ["ene-25", "feb-25", "mar-25", "abr-25", "may-25", "jun-25", "jul-25", "ago-25", "sep-25", "oct-25", "nov-25", "dic-25"];

        // Datos iniciales para ambos gráficos
        let forjasProduccionData1 = Array(24).fill(null); // Datos para el gráfico 1 (2023-2024)
        let forjasProduccionData2 = Array(12).fill(0);   // Datos para el gráfico 2 (2025)

        // Configuración del gráfico 1 (2023-2024)
        const forjasProduccionChart1 = new Chart(ctxForjasProduccion1, {
            type: 'line',
            data: {
                labels: monthsForjasProduccion1,
                datasets: [
                    {
                        label: "Cumplimiento %",
                        data: forjasProduccionData1,
                        borderColor: "#0095ff",
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: "#0095ff",
                    },
                    {
                        label: "Banda roja",
                        data: Array(24).fill(90), // Línea fija en 90%
                        backgroundColor: "rgba(255, 0, 0, 0.97)",
                        borderWidth: 0,
                        fill: true,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 40,
                        max: 110,
                        ticks: {
                            callback: value => value + "%"
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ": " + tooltipItem.raw.toFixed(2) + "%";
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                elements: {
                    line: {
                        borderWidth: 2
                    }
                }
            }
        });

        // Configuración del gráfico 2 (2025)
        const forjasProduccionChart2 = new Chart(ctxForjasProduccion2, {
            type: 'line',
            data: {
                labels: monthsForjasProduccion2,
                datasets: [
                    {
                        label: "Cumplimiento %",
                        data: forjasProduccionData2,
                        borderColor: "#0095ff",
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: "#0095ff",
                    },
                    {
                        label: "Banda roja",
                        data: Array(12).fill(90), // Línea fija en 90%
                        backgroundColor: "rgba(255, 0, 0, 0.97)",
                        borderWidth: 0,
                        fill: true,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 40,
                        max: 110,
                        ticks: {
                            callback: value => value + "%"
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ": " + tooltipItem.raw.toFixed(2) + "%";
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                elements: {
                    line: {
                        borderWidth: 2
                    }
                }
            }
        });

    
function generateMonthOptions(selectElementId) {
    const monthSelect = document.getElementById(selectElementId);
    if (!monthSelect) return; // Si no existe el elemento, salir
    
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();
    
    // Array de abreviaturas de meses en minúsculas
    const monthAbbr = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 
                       'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

    monthSelect.innerHTML = ''; // Limpiar opciones anteriores

    // Generar meses desde el año actual hasta 2 años en el futuro
    for (let year = currentYear; year <= currentYear + 2; year++) {
        for (let month = 0; month < 12; month++) {
            const monthName = monthAbbr[month];
            const yearShort = year.toString().slice(-2);
            const monthYear = `${monthName}-${yearShort}`;
            
            const option = document.createElement('option');
            option.value = monthYear;
            option.textContent = monthYear;
            monthSelect.appendChild(option);

            // Seleccionar automáticamente el mes actual
            if (year === currentYear && month === currentMonth) {
                option.selected = true;
            }
        }
    }
}
generateMonthOptions('monthForjasProduccion');

    

        // Validar y actualizar el gráfico
        document.getElementById('formForjasProduccion').addEventListener('submit', (event) => {
    event.preventDefault();

    const month = document.getElementById('monthForjasProduccion').value;
    const desempeno = document.getElementById('forjasProduccion').value;
    
    // Manejo seguro del campo restringido
    const areaCumplimientoInput = document.getElementById('forjasCumplimiento');
    const areaCumplimiento = areaCumplimientoInput ? areaCumplimientoInput.value : null;

    // Convertir a número (permite null si está vacío o no existe)
    const desempenoNum = desempeno === "" ? null : parseFloat(desempeno);
    const areaCumplimientoNum = areaCumplimiento === null || areaCumplimiento === "" ? null : parseFloat(areaCumplimiento);

    // Validación mínima - solo requiere desempeño
    if (desempenoNum === null) {
        alert('Debe ingresar al menos el valor de Desempeño');
        return;
    }

    // Validar rangos
    if (desempenoNum !== null && (desempenoNum < 0 || desempenoNum > 100)) {
        alert('El valor de Desempeño debe estar entre 0% y 100%');
        return;
    }
    if (areaCumplimientoNum !== null && (areaCumplimientoNum < 0 || areaCumplimientoNum > 100)) {
        alert('El valor de Área de Cumplimiento debe estar entre 0% y 100%');
        return;
    }

    axios.post('/forjas-produccion/store', {
        mes: month,
        desempeno: desempenoNum,
        area_cumplimiento: areaCumplimientoNum,
    })
    .then(response => {
        if (response.data.success) {
            const index1 = monthsForjasProduccion1.indexOf(month);
            const index2 = monthsForjasProduccion2.indexOf(month);
            
            if (index1 !== -1) {
                if (desempenoNum !== null) forjasProduccionChart1.data.datasets[0].data[index1] = desempenoNum;
                if (areaCumplimientoNum !== null) forjasProduccionChart1.data.datasets[1].data[index1] = areaCumplimientoNum;
                forjasProduccionChart1.update();
            }
            
            // Actualizar gráfico 2 (2025)
            if (index2 !== -1) {
                if (desempenoNum !== null) forjasProduccionChart2.data.datasets[0].data[index2] = desempenoNum;
                if (areaCumplimientoNum !== null) forjasProduccionChart2.data.datasets[1].data[index2] = areaCumplimientoNum;
                forjasProduccionChart2.update();
            }
            
            // Feedback visual discreto
            const btn = event.target.querySelector('button[type="submit"]');
            const originalText = btn.textContent;
            btn.textContent = '✓';
            setTimeout(() => { btn.textContent = originalText; }, 1000);
        } else {
            console.error('Error en la respuesta:', response.data);
        }
    })
    .catch(error => {
        console.error('Error:', error.response ? error.response.data : error.message);
    });
});
        // Cargar los datos iniciales al cargar la página
        axios.get('/forjas-produccion/get-data')
            .then(response => {
                const data = response.data;
                data.forEach(item => {
                    const index1 = monthsForjasProduccion1.indexOf(item.mes);
                    const index2 = monthsForjasProduccion2.indexOf(item.mes);
                    if (index1 !== -1) {
                        forjasProduccionChart1.data.datasets[0].data[index1] = item.desempeno;
                    }
                    if (index2 !== -1) {
                        forjasProduccionChart2.data.datasets[0].data[index2] = item.desempeno;
                    }
                });
                forjasProduccionChart1.update(); // Actualizar ambos gráficos
                forjasProduccionChart2.update();
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
            });

        // Alternar entre gráficos
        let currentChartForjasProduccion = 1;
        document.getElementById('nextChartForjasProduccion').addEventListener('click', () => {
            if (currentChartForjasProduccion === 1) {
                document.getElementById('forjasProduccionChart1').style.display = 'none';
                document.getElementById('forjasProduccionChart2').style.display = 'block';
                currentChartForjasProduccion = 2;
            } else {
                document.getElementById('forjasProduccionChart1').style.display = 'block';
                document.getElementById('forjasProduccionChart2').style.display = 'none';
                currentChartForjasProduccion = 1;
            }
        });

        document.getElementById('prevChartForjasProduccion').addEventListener('click', () => {
            if (currentChartForjasProduccion === 1) {
                document.getElementById('forjasProduccionChart1').style.display = 'none';
                document.getElementById('forjasProduccionChart2').style.display = 'block';
                currentChartForjasProduccion = 2;
            } else {
                document.getElementById('forjasProduccionChart1').style.display = 'block';
                document.getElementById('forjasProduccionChart2').style.display = 'none';
                currentChartForjasProduccion = 1;
            }
        });
    })(); // Fin de la encapsulación
</script>
<br><br>
<h2 class="box-title">Cumplimiento al Plan de Producción<br>Maquinados Serie Donaldson</h2>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="chart-container">
    <canvas id="donaldsonChart1"></canvas>
    <canvas id="donaldsonChart2" style="display: none;"></canvas> <!-- Segundo gráfico oculto -->
</div>

<div style="text-align: center; margin-top: 10px;">
  <button id="prevChartDonaldson" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">◀ Anterior</button>
  <button id="nextChartDonaldson" style="background-color: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-size: 16px;">Siguiente ▶</button>
</div>

@can('taller.update')
<form id="formDonaldson">
    <label for="monthDonaldson">Mes:</label>
    <select id="monthDonaldson" name="monthDonaldson"></select><br><br>
 
    <label for="donaldsonProduccion">Desempeño (%):</label>
    <input type="number" id="donaldsonProduccion" name="donaldsonProduccion" min="0" max="100" step="0.01" ><br><br>
    @can('admin.update')
    <label for="donaldsonCumplimiento">Área de Cumplimiento (%):</label>
    <input type="number" id="donaldsonCumplimiento" name="donaldsonCumplimiento" min="0" max="100" step="0.01" ><br><br>
    @endcan
    <button type="submit" class="button">Actualizar Gráfico</button>
</form>
@endcan
<script>
    (function () {
        // Configurar el token CSRF en Axios
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Variables únicas para gráficos y datos
        const ctxDonaldson1 = document.getElementById('donaldsonChart1').getContext('2d');
        const ctxDonaldson2 = document.getElementById('donaldsonChart2').getContext('2d');

        // Fechas para el primer gráfico (ene-23 a dic-24)
        const monthsDonaldson1 = ["ene-23", "feb-23", "mar-23", "abr-23", "may-23", "jun-23", "jul-23", "ago-23", "sep-23", "oct-23", "nov-23", "dic-23",
                               "ene-24", "feb-24", "mar-24", "abr-24", "may-24", "jun-24", "jul-24", "ago-24", "sep-24", "oct-24", "nov-24", "dic-24"];

        // Fechas para el segundo gráfico (ene-25 a dic-25)
        const monthsDonaldson2 = ["ene-25", "feb-25", "mar-25", "abr-25", "may-25", "jun-25", "jul-25", "ago-25", "sep-25", "oct-25", "nov-25", "dic-25"];

        // Datos iniciales para ambos gráficos
        let donaldsonData1 = Array(24).fill(null);
        let donaldsonData2 = Array(12).fill(0);

        // Configuración del gráfico 1 (2023-2024)
        const donaldsonChart1 = new Chart(ctxDonaldson1, {
            type: 'line',
            data: {
                labels: monthsDonaldson1,
                datasets: [
                    {
                        label: "Cumplimiento %",
                        data: donaldsonData1,
                        borderColor: "#2196F3",  // Cambio a verde para diferenciar
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: "#2196F3",
                    },
                    {
                        label: "Banda roja",
                        data: Array(24).fill(90),
                        backgroundColor: "rgba(255, 0, 0, 0.97)",
                        borderWidth: 0,
                        fill: true,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 110,
                        ticks: {
                            callback: value => value + "%"
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ": " + tooltipItem.raw.toFixed(2) + "%";
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Configuración del gráfico 2 (2025)
        const donaldsonChart2 = new Chart(ctxDonaldson2, {
            type: 'line',
            data: {
                labels: monthsDonaldson2,
                datasets: [
                    {
                        label: "Cumplimiento %",
                        data: donaldsonData2,
                        borderColor: "#2196F3",
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4,
                        pointBackgroundColor: "#2196F3",
                    },
                    {
                        label: "Banda roja",
                        data: Array(12).fill(90),
                        backgroundColor: "rgba(255, 0, 0, 0.97)",
                        borderWidth: 0,
                        fill: true,
                        pointRadius: 0
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 110,
                        ticks: {
                            callback: value => value + "%"
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.label + ": " + tooltipItem.raw.toFixed(2) + "%";
                            }
                        }
                    },
                    legend: {
                        display: false
                    }
                }
            }
        });

       // Función para generar opciones de meses en formato "ene-23" (minúsculas)
function generateMonthOptions(selectElementId) {
    const monthSelect = document.getElementById(selectElementId);
    if (!monthSelect) return; // Si no existe el elemento, salir
    
    const currentDate = new Date();
    const currentYear = currentDate.getFullYear();
    const currentMonth = currentDate.getMonth();
    
    // Array de abreviaturas de meses en minúsculas
    const monthAbbr = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 
                       'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];

    monthSelect.innerHTML = ''; // Limpiar opciones anteriores

    // Generar meses desde el año actual hasta 2 años en el futuro
    for (let year = currentYear; year <= currentYear + 2; year++) {
        for (let month = 0; month < 12; month++) {
            const monthName = monthAbbr[month];
            const yearShort = year.toString().slice(-2);
            const monthYear = `${monthName}-${yearShort}`;
            
            const option = document.createElement('option');
            option.value = monthYear;
            option.textContent = monthYear;
            monthSelect.appendChild(option);

            // Seleccionar automáticamente el mes actual
            if (year === currentYear && month === currentMonth) {
                option.selected = true;
            }
        }
    }
}

generateMonthOptions('monthDonaldson');


        document.getElementById('formDonaldson').addEventListener('submit', (event) => {
            event.preventDefault();

            const month = document.getElementById('monthDonaldson').value;
            const desempeno = document.getElementById('donaldsonProduccion').value;
            const areaCumplimientoInput = document.getElementById('donaldsonCumplimiento');
            const areaCumplimiento = areaCumplimientoInput ? areaCumplimientoInput.value : null;

            const desempenoNum = desempeno === "" ? null : parseFloat(desempeno);
            const areaCumplimientoNum = areaCumplimiento === null || areaCumplimiento === "" ? null : parseFloat(areaCumplimiento);

            if (desempenoNum === null) {
                alert('Debe ingresar al menos el valor de Desempeño');
                return;
            }

            axios.post('/donaldson/store', {
                mes: month,
                desempeno: desempenoNum,
                area_cumplimiento: areaCumplimientoNum,
            })
            .then(response => {
                if (response.data.success) {
                    const index1 = monthsDonaldson1.indexOf(month);
                    const index2 = monthsDonaldson2.indexOf(month);
                    
                    if (index1 !== -1) {
                        donaldsonChart1.data.datasets[0].data[index1] = desempenoNum;
                        donaldsonChart1.update();
                    }
                    
                    if (index2 !== -1) {
                        donaldsonChart2.data.datasets[0].data[index2] = desempenoNum;
                        donaldsonChart2.update();
                    }
                    
                    const btn = event.target.querySelector('button[type="submit"]');
                    const originalText = btn.textContent;
                    btn.textContent = '✓';
                    setTimeout(() => { btn.textContent = originalText; }, 1000);
                }
            })
            .catch(error => {
                console.error('Error:', error.response ? error.response.data : error.message);
            });
        });

        // Cargar datos iniciales
        axios.get('/donaldson/get-data')
            .then(response => {
                const data = response.data;
                data.forEach(item => {
                    const index1 = monthsDonaldson1.indexOf(item.mes);
                    const index2 = monthsDonaldson2.indexOf(item.mes);
                    if (index1 !== -1) {
                        donaldsonChart1.data.datasets[0].data[index1] = item.desempeno;
                    }
                    if (index2 !== -1) {
                        donaldsonChart2.data.datasets[0].data[index2] = item.desempeno;
                    }
                });
                donaldsonChart1.update();
                donaldsonChart2.update();
            })
            .catch(error => {
                console.error('Error al obtener los datos:', error.response ? error.response.data : error.message);
            });

        // Alternar entre gráficos
        let currentChartDonaldson = 1;
        document.getElementById('nextChartDonaldson').addEventListener('click', () => {
            if (currentChartDonaldson === 1) {
                document.getElementById('donaldsonChart1').style.display = 'none';
                document.getElementById('donaldsonChart2').style.display = 'block';
                currentChartDonaldson = 2;
            } else {
                document.getElementById('donaldsonChart1').style.display = 'block';
                document.getElementById('donaldsonChart2').style.display = 'none';
                currentChartDonaldson = 1;
            }
        });

        document.getElementById('prevChartDonaldson').addEventListener('click', () => {
            if (currentChartDonaldson === 1) {
                document.getElementById('donaldsonChart1').style.display = 'none';
                document.getElementById('donaldsonChart2').style.display = 'block';
                currentChartDonaldson = 2;
            } else {
                document.getElementById('donaldsonChart1').style.display = 'block';
                document.getElementById('donaldsonChart2').style.display = 'none';
                currentChartDonaldson = 1;
            }
        });
    })();
</script>

@endsection
