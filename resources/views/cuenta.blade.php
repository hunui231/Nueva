@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'cuenta' @endphp
@endsection

@section('content')

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
