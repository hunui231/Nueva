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
                @can('administracion.index')
                <li @if($currentPage == 'Administracion') class="active" @endif>
                    <a href="{{ route('administracion.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-solid fa-chart-line mr-2"></i>Administracion
                 </a>
                </li>
                @endcan
                @can('Ventas.index')
                <li @if($currentPage == 'Ventas') class="active" @endif>
                    <a href="{{ route('Ventas.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class=" fas fa-solid fa-handshake mr-2"></i>Ventas
                 </a>
                </li>
                @endcan
                @can('produccion.index')
                <li @if($currentPage == 'produccion') class="active" @endif>
                    <a href="{{ route('produccion.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class=" fas fa-solid fa-chart-bar mr-2"></i>Produccion
                 </a>
                </li>
                @endcan
                @can('rh.index')
                <li @if($currentPage == 'rh') class="active" @endif>
                    <a href="{{ route('rh.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class=" fas fa-solid fa-user mr-2"></i>Recursos Humanos
                 </a>
                </li>
                @endcan
                @can('taller.index')
                <li @if($currentPage == 'taller') class="active" @endif>
                    <a href="{{ route('taller.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fa fa-cogs" aria-hidden="true"></i>Operacion MM
                    </a>
                </li>
                @endcan
                @can('administraciongic.index')
                <li @if($currentPage == 'administraciongic') class="active" @endif>
                    <a href="{{ route('administraciongic.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fa fa-solid fa-chart-line mr-2" aria-hidden="true"></i>Administracion GIC
                    </a>
                </li>
                @endcan

                @can('logistica.index')
                <li @if($currentPage == 'logistica') class="active" @endif>
                    <a href="{{ route('logistica.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-truck mr-2"></i>Produccion (Log)
                    </a>
                </li>
                @endcan
                @can('mtto.index')
                <li @if($currentPage == 'Manteimiento') class="active" @endif>
                    <a href="{{ route('mtto.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-solid fa-briefcase"></i>MTTO
                    </a>
                </li>
                @endcan

                @can('calidad.index')
                <li @if($currentPage == 'calidad') class="active" @endif>
                    <a href="{{ route('calidad.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-chart-bar mr-2"></i>Metrología
                    </a>
                </li>
                @endcan
                @can('sistemas.index')
                 <li @if($currentPage == 'sistemas') class="active" @endif>
                    <a href="{{ route('sistemas.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-desktop mr-2"></i>Sistemas
                    </a>
                </li>
                @endcan
              @can('proyectos.index')
                <li @if($currentPage == 'proyectos') class="active" @endif>
                     <a href="{{ route('proyectos.index') }}" class="flex items-center p-2 hover:bg-gray-200">
                        <i class="fas fa-tasks mr-2"></i>Proyectos
                    </a>
                </li>
              @endcan
            </ul>
        </nav>
    </div>
    <h6></h6>
</aside>

 <button id="sidebarToggle" class="lg:hidden fixed top-0 left-0 p-4 z-50 bg-gray-800 text-white">
    <i class="fas fa-bars"></i>
 </button>
 <style>
    .menu-sidebar {
        background-color: white; 
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); 
        z-index: 50; 
    }

    @media (max-width: 1024px) {
        .menu-sidebar {
            position: fixed;
            inset: 0;
            transition: transform 0.3s;
            transform: translateX(-100%); 
        }

        .menu-sidebar.open {
            transform: translateX(0); 
        }

        #sidebarToggle {
            position: fixed;
            top: 0;
            left: 0;
            padding: 1rem;
            background-color: #2d3748; 
            color: white;
            z-index: 50;
        }
    }

    .navbar-sidebar {
        margin-top: 1rem;
    }

    .navbar__list li {
        border-bottom: 1px solid #e2e8f0; 
    }

    .navbar__list li a {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #4a5568;
        text-decoration: none;
    }

    .navbar__list li a:hover {
        background-color: #edf2f7; 
    }

    .navbar__list li.active a {
        background-color: #edf2f7;
        color: #2b6cb0; 
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
