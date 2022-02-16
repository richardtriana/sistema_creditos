<nav class="navbar navbar-expand-md navbar-dark bg-primary shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      {{-- {{ config('app.name', 'Laravel') }} --}}
      <img src="{{ asset('logo.jpeg') }}" alt="" srcset="" width="100">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
      aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav mr-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownConfigurations" role="button"
            data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-cash-coin"></i>Creditos
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownConfigurations">
            <router-link class="dropdown-item" :to="{ name: 'outsanding-credits' }"> Creditos pendientes </router-link>
            <router-link class="dropdown-item" :to="{ name: 'credit-clients' }"> Clientes </router-link>
            <router-link class="dropdown-item" :to="{ name: 'credit-providers' }"> Proveedores </router-link>
          </div>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/entries"><i class="bi bi-bag-dash"></i> Ingresos </router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/expenses"><i class="bi bi-bag-dash"></i> Egresos </router-link>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownConfigurations" role="button"
            data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-cash-coin"></i>Reportes
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownConfigurations">
            <router-link class="dropdown-item"  :to="{ name: 'report-portfolio' }"> Cartera </router-link>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPeople" role="button" data-toggle="dropdown"
            aria-expanded="false">
            <i class="bi bi-people"></i> Personas
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownPeople">
            <router-link class="dropdown-item" to="/providers"> Proveedores </router-link>
            <router-link class="dropdown-item" to="/clients"> Clientes </router-link>
          </div>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/boxes"> <i class="bi bi-box"></i>Cajas </router-link>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/headquarters"><i class="bi bi-house-door"></i> Sedes </router-link>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownConfigurations" role="button"
            data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-gear"></i> Configuraciones
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownConfigurations">
            <router-link class="dropdown-item" to="/company"> Empresa </router-link>
            <router-link class="dropdown-item" to="/users"> Usuarios </router-link>
          </div>
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
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }}
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault();document.getElementById('logout-form').submit();">
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