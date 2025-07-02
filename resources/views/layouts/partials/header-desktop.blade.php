<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="header-button" style="justify-content: space-between;">
            
            <div class="search-bar">
                <input type="text" placeholder="Buscar..." />
                <button  style="max-height: 30px;"><i class="zmdi zmdi-search"></i></button>
            </div>
            
            <!-- Perfil de usuario -->
            <div class="account-wrap">
                <div class="account-item clearfix js-item-menu">
                    <div class="image">
                        <img src="{!! asset('theme/images/icon/logo2.png') !!}" alt="John Doe" />
                    </div>
                    <div class="content">
                        <a class="js-acc-btn" href="#">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
                    </div>
                    <div class="account-dropdown js-dropdown">
                        <div class="info clearfix">
                            <div class="image">
                                <a href="#">
                                    <img src="{!! asset('theme/images/icon/logo2.png') !!}" alt="John Doe" />
                                </a>
                            </div>
                            <div class="content">
                                <h5 class="name">
                                    <a href="#">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
                                </h5>
                                <span class="email">{{ Auth::user()->email }}</span>
                            </div>
                        </div>
                        <div class="account-dropdown__body">
                            <div class="account-dropdown__item">
                                <a href="{{ route('cuenta') }}">
                                    <i class="zmdi zmdi-account-box"></i>Cuenta
                                </a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="{{ route('configuracion') }}">
                                    <i class="zmdi zmdi-settings"></i>Configuracion
                                </a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="{{ route('notificaciones') }}">
                                    <i class="zmdi zmdi-notifications-active"></i>Notificaciones
                                </a>
                            </div>
                            <div class="account-dropdown__item">
                                <a href="{{ route('tickets') }}">
                                    <i class="zmdi zmdi-help"></i>Ayuda
                                </a>
                            </div>
                        </div>
                        <div class="account-dropdown__footer">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button style="color:red;padding-top:10px;padding-bottom:10px;padding-left:20px;">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

 <style>
 .header-desktop {
    background-color: #f5f7fa;
    padding: 10px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

/* Icono de menú hamburguesa */
.menu-icon { 
    font-size: 24px;
    cursor: pointer;
    display: flex;
    align-items: center;
}

/* Barra de búsqueda */
.search-bar {
    display: flex;
    align-items: center;
    margin-right: auto;
}

.search-bar input {
    border: 1px solid #ddd;
    padding: 5px;
    border-radius: 20px;
    margin-right: 5px;
}

.search-bar button {
    background: none;
    border: none;
    cursor: pointer;
}

/* Menú principal */
.main-nav {
    display: flex;
    align-items: center;
    gap: 20px;
}

.main-nav .menu-item {
    text-decoration: none;
    color: #333;
    font-size: 14px;
}

.main-nav .menu-item:hover {
    text-decoration: underline;
}

/* Perfil de usuario */
.header-button .account-wrap {
    display: flex;
    align-items: center;
}

.header-button .account-item {
    margin: 0 10px;
    font-size: 14px;
}

/* Evitar subrayado */
.header-button .account-item a {
    text-decoration: none;
    color: inherit;
}

/* Media query para pantallas más pequeñas */
@media (max-width: 768px) {
    .header-desktop {
        flex-direction: column;
        align-items: flex-start;
    }

    .main-nav {
        flex-direction: column;
        width: 100%;
    }
}
</style>
