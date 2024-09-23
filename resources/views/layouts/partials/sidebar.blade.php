<aside class="menu-sidebar fixed inset-0 lg:relative lg:w-64 bg-white shadow-md z-50 lg:z-auto lg:shadow-none lg:static transition-transform duration-300 transform lg:translate-x-0 -translate-x-full">
    <div class="logo p-4">
        <a href="#">
            <img src="{!! asset('theme/images/icon/esc.png') !!}" alt="Cool Admin" class="w-32" />
        </a>
    </div>

    <div class="menu-sidebar__content overflow-y-auto">
        <nav class="navbar-sidebar">
            <ul class="list-unstyled navbar__list">
                @can('dashboard')
                <li @if($currentPage == 'dashboard') class="active" @endif>
                    <a href="{{ route('dashboard') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-home mr-2"></i>Inicio
                    </a>
                </li>
                @endcan
                @can('users.index')
                <li @if($currentPage == 'users') class="active" @endif>
                    <a href="{{ route('users.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-address-card mr-2"></i>Usuarios
                    </a>
                </li>
                @endcan

                @can('logistica.index')
                <li @if($currentPage == 'logistica') class="active" @endif>
                    <a href="{{ route('logistica.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-truck mr-2"></i>Logística
                    </a>
                </li>
                @endcan

                @can('calidad.index')
                <li @if($currentPage == 'calidad') class="active" @endif>
                    <a href="{{ route('calidad.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-chart-bar mr-2"></i>Calidad
                    </a>
                </li>
                @endcan

                @can('cnc.index')
                <li @if($currentPage == 'cnc') class="active" @endif>
                    <a href="{{ route('cnc.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-chart-line mr-2"></i>CNC
                    </a>
                </li>
                @endcan
            </ul>
        </nav>
    </div>
</aside>

<!-- Button to toggle sidebar visibility on mobile -->
<button id="sidebarToggle" class="lg:hidden fixed top-0 left-0 p-4 z-50 bg-gray-800 text-white">
    <i class="fas fa-bars"></i>
</button>

<!-- Inline CSS -->
 <style>
    /* Estilos generales */
    .menu-sidebar {
        background-color: white; /* Puedes ajustar según sea necesario */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Ajusta el estilo del shadow */
        z-index: 50; /* Asegura que esté por encima del contenido */
    }

    /* Ajustes para dispositivos móviles */
    @media (max-width: 1024px) {
        .menu-sidebar {
            position: fixed;
            inset: 0;
            transition: transform 0.3s;
            transform: translateX(-100%); /* Ocultar la barra lateral por defecto en móviles */
        }

        .menu-sidebar.open {
            transform: translateX(0); /* Mostrar la barra lateral cuando está abierta */
        }

        #sidebarToggle {
            position: fixed;
            top: 0;
            left: 0;
            padding: 1rem;
            background-color: #2d3748; /* Ajusta el color del botón según sea necesario */
            color: white;
            z-index: 50; /* Asegura que el botón esté por encima del contenido */
        }
    }

    /* Estilos para el menú */
    .navbar-sidebar {
        margin-top: 1rem;
    }

    .navbar__list li {
        border-bottom: 1px solid #e2e8f0; /* Ajusta el color del border según sea necesario */
    }

    .navbar__list li a {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #4a5568; /* Ajusta el color del texto según sea necesario */
        text-decoration: none;
    }

    .navbar__list li a:hover {
        background-color: #edf2f7; /* Ajusta el color de fondo al pasar el ratón */
    }

    .navbar__list li.active a {
        background-color: #edf2f7; /* Color de fondo para el elemento activo */
        color: #2b6cb0; /* Ajusta el color del texto para el elemento activo */
    }
</style>

 <script>
    document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.querySelector('.menu-sidebar');
        const toggleButton = document.getElementById('sidebarToggle');

        toggleButton.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });
    });
</script>
