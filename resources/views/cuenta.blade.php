@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'cuenta' @endphp
@endsection

@section('content')

 <style>
    
    .container {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    h2 {
        font-size: 2rem;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

    .card-header {
        background-color: #062fe6;
        color: #fff;
        padding: 15px;
        font-size: 1.25rem;
        text-align: center;
        font-weight: 600;
    }

    .card-body {
        padding: 20px;
        background-color: #fff;
    }

    .card-body p {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .card-body strong {
        font-weight: 600;
        color: #062fe6;
    }

    h4 {
        font-size: 1.5rem;
        margin-top: 30px;
        color: #062fe6;
        font-weight: 600;
    }

    p {
        font-size: 1rem;
        color: #666;
    }

   
    .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 50px;
        padding: 10px 20px;
        font-size: 1rem;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    @media (max-width: 768px) {
        h2 {
            font-size: 1.5rem;
        }

        h4 {
            font-size: 1.25rem;
        }

        .card-body p {
            font-size: 1rem;
        }
    }
</style>


<div class="container">
    <h2>Mi Cuenta</h2>

    <!-- Información del usuario -->
    <div class="card">
        <div class="card-header">
            Detalles de la cuenta
        </div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</p>
            <p><strong>Correo Electrónico:</strong> {{ Auth::user()->email }}</p>
            <p><strong>Número de Teléfono:</strong> {{ Auth::user()->phone_number }}</p>
            <p><strong>Rol:</strong> {{ Auth::user()->getRoleNames()->first() }}</p>
            <p><strong>Fecha de Creación:</strong> {{ Auth::user()->created_at->format('d/m/Y') }}</p>
            <p><strong>Último Inicio de Sesión:</strong> {{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('d/m/Y H:i') : 'Nunca' }}</p>
        </div>
    </div>

    
    <div class="mt-4">
        <h4>Actividad Reciente</h4>
        <p>No hay actividad reciente para mostrar.</p>
    </div>

   

@endsection
