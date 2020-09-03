<!DOCTYPE html class="h-100">
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

</head>
<body class="h-100"> 
    <div id="app" class="h-100">

        <b-navbar toggleable="sm" type="dark" variant="dark">
            <b-navbar-toggle target="nav-text-collapse"></b-navbar-toggle>

            <b-navbar-brand href="{{ route('chat') }}">
                {{ config('app.name', 'Laravel') }}
            </b-navbar-brand>

            <b-collapse id="nav-text-collapse" is-nav>

                <b-navbar-nav class="ml-auto">
                    @guest
                        <b-nav-item href="{{ route('login') }}">Ingresar</b-nav-item>
                        <b-nav-item href="{{ route('register') }}">Registrar</b-nav-item>
                    @else

                        <!-- Navbar dropdowns -->
                        <b-nav-item-dropdown text="{{ auth()->user()->name }}" right>
                            <b-dropdown-item href="{{ url('/profile') }}">
                                Modificar perfil
                            </b-dropdown-item>
                        
                            <b-dropdown-item href="#" @click="logout">
                                Cerrar sesión
                            </b-dropdown-item>
                        </b-nav-item-dropdown>
                        
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            {{ csrf_field() }}
                        </form>

                    @endguest

                </b-navbar-nav>

            </b-collapse>
        </b-navbar>

        @yield('content')
    </div>

    <!-- Scripts -->
    <!-- cambiamos de asset a mix, esto es laravelmix para que 
    siempre se muestre una nueva version del app.js y no 
    tener problemas con el cachè 
    para que funcione, se debe gregar en webpack.mix
    los metodos para produccion o para desarrollo
    
    -->
    <!-- <script src="{{ asset('js/app.js') }}"></script> -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
