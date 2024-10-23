@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users'; @endphp
@endsection

@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            text-align: center;
        }
        form {
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        label {
            display: block;
            margin-bottom: 8px;
        }
        input, textarea {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
       
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .status {
            color: green;
            font-weight: bold;
        }
        .status.pending {
            color: red;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        .action-buttons button {
            padding: 5px 10px;
            background-color: #28a745;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .action-buttons button.delete {
            background-color: #dc3545;
        }
        .action-buttons button:hover {
            opacity: 0.8;
        }
        @media (max-width: 768px) {
            table {
                font-size: 14px;
                display: block;
                overflow-x: auto;
            }
            thead {
                display: none;
            }
            tr {
                display: block;
                margin-bottom: 10px;
            }
            td {
                display: block;
                text-align: right;
                position: relative;
                padding-left: 50%;
                white-space: nowrap;
            }
            td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: bold;
                text-align: left;
            }
        }
    </style>

     <div class="container">
        <center>
        <h1>Tickets</h1>
        <h5>Esta Seccion Esta Unicamente Destinada Para Problemas con la Aplicacion</h5>
        </center>
        
        <br>
        <form id="ticketForm" action="{{ route('tickets.create') }}" method="POST">
            @csrf 
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            
            <label for="area">Área:</label>
            <input type="text" id="area" name="area" required>
            
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="descripcion">Descripción del problema:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
            
            <button type="submit" style="background-color:#007bff">Crear Ticket</button>
        </form>
        @can('calidad.update')

        <table id="ticketTable">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Área</th>
                    <th>Correo</th>
                    <th>Descripción</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->usuario }}</td>
                        <td>{{ $ticket->area }}</td>
                        <td>{{ $ticket->correo }}</td>
                        <td>{{ $ticket->descripcion }}</td>
                        <td class="status {{ strtolower($ticket->estado) }}">{{ $ticket->estado }}</td>
                        <td class="action-buttons">
                            <button onclick="changeStatus({{ $ticket->id }})">Listo</button>
                            <button class="delete" onclick="deleteTicket({{ $ticket->id }})">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @endcan
    </div>
    <script>
        let tickets = [];

     document.getElementById('ticketForm').addEventListener('submit', function(e) {
                e.preventDefault(); // Prevenir el envío normal del formulario
                
                const formData = new FormData(this); // Recoger los datos del formulario
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.id) {
                        // Si el ticket fue creado exitosamente, agregarlo a la tabla
                        const ticket = {
                            id: data.id,
                            usuario: data.usuario,
                            area: data.area,
                            correo: data.correo,
                            descripcion: data.descripcion,
                            estado: 'Pendiente'
                        };
                        tickets.push(ticket);
                        renderTickets(); // Llamar a la función para renderizar los tickets en la tabla
                        this.reset(); // Reiniciar el formulario
                    }
                })
                .catch(error => console.error('Error:', error));
            });
        
            function renderTickets() {
                const tbody = document.querySelector('#ticketTable tbody');
                tbody.innerHTML = '';
                
                tickets.forEach(ticket => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${ticket.usuario}</td>
                        <td>${ticket.area}</td>
                        <td>${ticket.correo}</td>
                        <td>${ticket.descripcion}</td>
                        <td class="status ${ticket.estado.toLowerCase()}">${ticket.estado}</td>
                        <td class="action-buttons">
                            <button onclick="changeStatus(${ticket.id})">Listo</button>
                            <button class="delete" onclick="deleteTicket(${ticket.id})">Eliminar</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
            renderTickets();w
    </script>
@endsection