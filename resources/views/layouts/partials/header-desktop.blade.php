<header class="header-desktop">
    <div class="section__content section__content--p30">
        <div class="header-button" style="justify-content: flex-end;">
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

.header-button {
    display: flex;
    justify-content: space-between;
    align-items:flex-end;
    padding: 10px 20px;
}

/* Estilo para los elementos dentro del header */
.header-button .account-wrap {
    display: flex;
    align-items:flex-end;
}

.header-button .account-item {
    margin: 0 10px; /* Espaciado entre los items */
    font-size: 14px; /* Tamaño de fuente para una mejor legibilidad */
}

 /* Media query para pantallas más pequeñas */
  @media (max-width: 768px) {
    .header-button {
        flex-direction: column; /* Cambia la disposición a columna en pantallas pequeñas */
        align-items: flex-start; /* Alinea los elementos a la izquierda */
    }

    .header-button .account-wrap {
        flex-direction: column; /* Cambia la disposición interna a columna */
        width: 100%; /* Asegura que ocupe el ancho completo */
        margin-top: 10px; /* Espacio superior adicional */
    }

    .header-button .account-item {
        margin: 5px 0; /* Ajusta el espaciado vertical en pantallas pequeñas */
    }
}

/* Media query para pantallas muy pequeñas (móviles) */
@media (max-width: 480px) {
    .header-button {
        padding: 5px 10px; /* Reduce el padding en pantallas muy pequeñas */
    }

    .header-button .account-item {
        font-size: 12px; /* Ajusta el tamaño de la fuente */
    }
}

   </style>