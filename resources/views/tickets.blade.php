@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'users' @endphp
@endsection

@section('content')
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Tickets</title>
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
            width: calc(100% - 16px); /* Ajuste para padding */
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
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
</head>
<body>
    <div class="container">
        <h1>Tickets</h1>

        <form id="ticketForm">
            <label for="usuario">Usuario:</label>
            <input type="text" id="usuario" name="usuario" required>
            
            <label for="area">Área:</label>
            <input type="text" id="area" name="area" required>
            
            <label for="correo">Correo:</label>
            <input type="email" id="correo" name="correo" required>
            
            <label for="descripcion">Descripción del problema:</label>
            <textarea id="descripcion" name="descripcion" rows="4" required></textarea>
        
            <button type="submit">Crear Ticket</button>
        </form>

        <table id="ticketTable">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Área</th>
                    <th>Correo</th>
                    <th>Descripción</th>
                    <th>Estado</th>{}
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se insertarán los tickets -->
            </tbody>
        </table>
    </div>

    <script>
        const tickets = [];

        document.getElementById('ticketForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const usuario = document.getElementById('usuario').value;
            const area = document.getElementById('area').value;
            const correo = document.getElementById('correo').value;
            const descripcion = document.getElementById('descripcion').value;
            
            const ticket = {
                id: tickets.length + 1,
                usuario: usuario,
                area: area,
                correo: correo,
                descripcion: descripcion,
                estado: 'Pendiente'
            };
            
            tickets.push(ticket);
            renderTickets();
            
            this.reset();
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
                    <td class="status ${ticket.estado.toLowerCase()}" data-label="Estado">${ticket.estado}</td>
                    <td class="action-buttons" data-label="Acciones">
                        <button onclick="changeStatus(${ticket.id})">Listo</button>
                        <button class="delete" onclick="deleteTicket(${ticket.id})">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(row);
            });
        }

        function changeStatus(id) {
            const ticket = tickets.find(t => t.id === id);
            if (ticket) {
                ticket.estado = 'Resuelto';
                renderTickets();
            }
        }

        function deleteTicket(id) {
            const index = tickets.findIndex(t => t.id === id);
            if (index > -1) {
                tickets.splice(index, 1);
                renderTickets();
            }
        }
    </script>
</body>
</html>
@endsection