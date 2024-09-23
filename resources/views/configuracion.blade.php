@extends('layouts.dashboard')

@section('page')
    @php $currentPage = 'configuracion' @endphp
@endsection

@section('content')

<div class="container">
    <h2>Configuraciones de la Cuenta</h2>

    <!-- Mostrar preferencias del usuario -->
    <div class="card">
        <div class="card-header">
            Preferencias de Notificaciones
        </div>
        <div class="card-body">
            <p><strong>Tema de la Cuenta:</strong> {{ Auth::user()->theme == 'light' ? 'Oscuro' : 'Claro' }}</p>
        </div>
    </div>

    <!-- Políticas de cuenta o privacidad -->
    <div class="mt-4">
        <h4>Políticas de Privacidad y Uso</h4>
        <p>Propósito: La aplicación es para que coordinadores y el CEO accedan a indicadores clave de desempeño.</p>

         <p> Acceso: Solo los administradores de la app y el CEO tienen acceso autorizado a toda información.</p>
            
            <p>Privacidad y Seguridad: Los datos son confidenciales.Los usuarios deben proteger sus credenciales y no compartir la información con terceros.</p>
            
          <p>Uso Permitido: La aplicación solo debe utilizarse para propósitos relacionados con el seguimiento de indicadores de la empresa.</p>
    </div>

    <!-- Contacto para modificaciones -->
    <div class="mt-4">
        <h4>¿Necesitas hacer cambios en tu cuenta?</h4>
        <p>Si necesitas realizar cambios en tu cuenta, por favor contacta al administrador del sistema 
            <a href="mailto:admin@tuempresa.com?subject=Solicitud%20de%20cambio%20en%20cuenta&body=Hola%20Admin,%20me%20gustaría%20solicitar%20algunos%20cambios%20en%20mi%20cuenta.">aquí</a>.
        </p>
    </div>
    


@endsection
