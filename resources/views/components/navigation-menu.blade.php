<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{-- {{ config('app.name', 'Laravel') }} --}}
            <img src="{{ asset('images/logo.jpeg') }}" alt="" srcset="" width="100">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/clients"> Clientes </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/users"> Usuarios </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/credits"> Creditos </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/headquarters"> Sedes </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/providers"> Proveedores </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/expenses"> Egresos </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/boxes"> Cajas </router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link text-white" to="/company"> Empresa </router-link>
                </li>
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </li>
                @endif

                @if (Route::has('register'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
                @endif
                @else
                <li class="nav-item dropdown">
                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name }}
                    </a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                            {{ __('Logout') }}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </div>
                </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>