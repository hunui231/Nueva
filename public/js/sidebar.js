sdocument.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const toggleSidebarButton = document.getElementById('toggleSidebar');

    // Función para ocultar el sidebar
    function hideSidebar() {
        sidebar.classList.add('hidden');
    }

    // Función para mostrar el sidebar
    function showSidebar() {
        sidebar.classList.remove('hidden');
    }

    // Manejar el botón de alternar en pantallas pequeñas
    toggleSidebarButton.addEventListener('click', function () {
        if (sidebar.classList.contains('hidden')) {
            showSidebar();
        } else {
            hideSidebar();
        }
    });

    // Opcional: ocultar el sidebar al hacer clic en un enlace si la pantalla es pequeña
    document.querySelectorAll('.navbar-sidebar a').forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth < 1024) { // lg breakpoint
                hideSidebar();
            }
        });
    });
});