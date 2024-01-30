<nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
  <div class="container">
    <a class="navbar-brand" href="{{ url('/') }}">
      {{-- {{ config('app.name', 'Laravel') }} --}}
      <img src="{{ asset('logo.jpeg') }}" alt="" srcset="" width="100">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-between" id="navbarSupportedContent">
      <!-- Left Side Of Navbar -->
      <ul class="navbar-nav m-auto" id="main-menu" v-if="validateAuth">
        <li class="nav-item dropdown" v-if="validatePermission('credit-index')">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownConfigurations" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-cash-coin"></i>Creditos
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownConfigurations">
            <router-link class="dropdown-item" :to="{ name: 'outsanding-credits' }"> Creditos pendientes </router-link>
            <router-link class="dropdown-item" :to="{ name: 'credit-clients' }"> Clientes </router-link>
            <router-link class="dropdown-item" :to="{ name: 'credit-providers' }"> Pago a proveedores </router-link>
          </div>
        </li>
        <li class="nav-item">
          <router-link class="nav-link" to="/entries"><i class="bi bi-bag-dash"></i> Ingresos </router-link>
        </li>
        <li class="nav-item" v-if="validatePermission('expense-index')">
          <router-link class="nav-link" to="/expenses"><i class="bi bi-bag-dash"></i> Egresos </router-link>
        </li>
        <li class="nav-item dropdown" v-if="validatePermission('report')">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownConfigurations" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-cash-coin"></i>Reportes
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownConfigurations">
            <router-link class="dropdown-item" :to="{ name: 'report-portfolio' }"> Cartera </router-link>
            <router-link class="dropdown-item" :to="{ name: 'report-general-credits' }"> Reporte general de créditos
            </router-link>
            <router-link class="dropdown-item" :to="{ name: 'report-headquarters' }"> Reporte general de Sedes
            </router-link>
            <router-link class="dropdown-item" :to="{ name: 'report-general-client' }"> Reporte general cliente
            </router-link>
            <router-link class="dropdown-item" :to="{ name: 'report-headquarters-expenses' }"> Reporte egresos sedes
            </router-link>
              <router-link class="dropdown-item" :to="{ name: 'report-headquarters-entries' }"> Reporte ingresos sedes
            </router-link>
            <router-link class="dropdown-item" :to="{ name: 'report-profitability' }"> Reporte de Rentabilidad
            </router-link>
            <router-link class="dropdown-item" :to="{ name: 'report-cash-flow' }"> Reporte de flujo de caja
            </router-link>
             <router-link class="dropdown-item" :to="{ name: 'report-rating-client' }"> Reporte calificativo de cliente
            </router-link>
          </div>
        </li>
        <li class="nav-item dropdown" v-if="validatePermission('provider-index') || validatePermission('client-index')">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownPeople" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-people"></i> Personas
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownPeople">
            <router-link class="dropdown-item" to="/providers" v-if="validatePermission('provider-index')"> Proveedores
            </router-link>
            <router-link class="dropdown-item" to="/clients" v-if="validatePermission('client-index')"> Clientes
            </router-link>
          </div>
        </li>
        <li class="nav-item" v-if="validatePermission('box-index')">
          <router-link class="nav-link" to="/boxes"> <i class="bi bi-box"></i>Cajas </router-link>
        </li>
        <li class="nav-item" v-if="validatePermission('headquarter-index')">
          <router-link class="nav-link" to="/headquarters"><i class="bi bi-house-door"></i> Sedes </router-link>
        </li>
        <li class="nav-item dropdown" v-if="validatePermission('user-index') || validatePermission('configuration') || validatePermission('rol-index')">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownConfigurations" role="button" data-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-gear"></i> Configuraciones
          </a>
          <div class="dropdown-menu" aria-labelledby="navbarDropdownConfigurations">
            <router-link class="dropdown-item" to="/company" v-if="validatePermission('configuration')"> Empresa
            </router-link>
            <router-link class="dropdown-item" to="/valuation-chart" v-if="validatePermission('configuration')"> Tabla de valoración clientes
            </router-link>
            <router-link class="dropdown-item" to="/roles" v-if="validatePermission('rol-index')"> Roles </router-link>
            <router-link class="dropdown-item" to="/users" v-if="validatePermission('user-index')"> Usuarios
            </router-link>
            <router-link class="dropdown-item" to="/products" v-if="validatePermission('product-index')"> Productos
            </router-link>
            <router-link class="dropdown-item" to="/guarantees" v-if="validatePermission('guarantee-index')"> Garantias
            </router-link>
          </div>
        </li>
				<li class="nav-item dropdown notifications" >
					<a id="navbarDropdownNotifications" href="#" class="nav-link text-white " :class="{ 'dropdown-toggle' : notifications.length != 0}" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<i v-if="notifications.length == 0" class="bi bi-bell"></i>
						<i v-else class="bi bi-bell-fill"></i>
						Cumpleaños
					</a>
					<div class="dropdown-menu dropdown-menu-right px-2 birth-date" aria-labelledby="navbarDropdownNotifications">
						<div v-for="item in notifications" :key="item.id" class="card my-1" style="width: 20rem;">
							<div class="card-body">
								<h6 class="card-title"><i class="bi bi-person-circle rounded me-2"></i> @{{ `${item.name} ${item.last_name}`  }}</h6>
								<h6 class="card-subtitle mb-2 text-body-secondary"><strong>@{{ item.type_document }}: </strong>@{{ item.document }}</h6>
								<h6 v-if="item.phone_1 !== '' && item.phone_1 != NULL" class="card-subtitle mb-2 text-body-secondary"><strong>Cel 1: </strong>@{{ item.phone_1 }}</h6>
								<h6 v-if="item.phone_2 != '' && item.phone_2 != NULL" class="card-subtitle mb-2 text-body-secondary"><strong>Cel 2: </strong>@{{ item.phone_2 }}</h6>
							</div>
						</div>
					</div>
				</li>
      </ul>

      <!-- Right Side Of Navbar -->
      <ul class="navbar-nav" v-if="validateAuth">
        <!-- Authentication Links -->
        <li class="nav-item dropdown">
          <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            @{{ user.name }}
          </a>

          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="javascript:void(0)" @click="logout">
              {{ __('Logout') }}
            </a>
          </div>
        </li>
      </ul>

      <ul class="navbar-nav justify-content-end ml-auto" v-else>
        <li class="nav-item">
          <router-link class="nav-link" to="/login">{{ __('Login') }}</router-link>
        </li>
      </ul>
    </div>
  </div>
</nav>
