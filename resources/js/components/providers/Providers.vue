<template>
	<div class="page">
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Proveedores</h3>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formProviderModal"
				v-if="$root.validatePermission('provider-store')">
				Crear proveedor
			</button>
		</div>

		<div class="page-search p-4 border my-2">
			<h6 class="text-primary text-uppercase">Filtrar:</h6>
			<form>
				<div class="form-row">
					<div class="form-group col-md-4 col-sm-6 col-xs-6">
						<label for="search_provider">Buscar proveedor...</label>
						<input type="text" id="search_provider" name="search_provider" class="form-control"
						  placeholder="Razon Social | Documento" v-model="search_provider" />
					  </div>
					<div class="form-group col-md-4 col-sm-6 col-xs-6">
						<label for="">Mostrar {{ search_results }} resultados por página:</label>
						<input type="number" id="search_results" name="search_results" class="form-control"
							placeholder="Desde" v-model="search_results" max="1000" />
					</div>
					<div class="form-group col-md-4 col-sm-6 col-xs-6 ">
						<button class="btn btn-success w-100 mt-5" type="button" @click="listProviders(1)">
							<i class="bi bi-search"></i> Buscar
						</button>
					</div>
					<div class="form-group offset-md-8 col-md-4 col-sm-6 col-xs-6">
						<download-excel class="btn btn-primary w-100" :fields="json_fields" :data="providerList.data"
							name="list-providers.xls" type="xls">
							<i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
						</download-excel>
					</div>
				</div>

			</form>
		</div>
		<div class="page-content mt-4">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>ID</th>
							<th>Razón social</th>
							<th>Tipo Documento</th>
							<th>Documento</th>
							<th>Celular1</th>
							<th>Celular2</th>
							<th>Correo Electrónico</th>
							<th>Dirección</th>
							<th v-if="$root.validatePermission('provider-status')">Estado</th>
							<th v-if="$root.validatePermission('provider-update')">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="p in providerList.data" :key="p.id">
							<td>{{ p.id }}</td>
							<td>{{ p.business_name }}</td>
							<td>
								<span v-if="p.type_document == 'CC'">Cédula de ciudadania</span>
								<span v-if="p.type_document == 'PP'">Pasaporte</span>
								<span v-if="p.type_document == 'NIT'">NIT</span>
							</td>
							<td>{{ p.document }}</td>
							<td>{{ p.phone_1 }}</td>
							<td>{{ p.phone_2 }}</td>
							<td>{{ p.email }}</td>
							<td>{{ p.address }}</td>
							<td class="text-right" v-if="$root.validatePermission('provider-status')">
								<button class="btn" :class="p.status == 1 ? 'btn-success' : 'btn-danger'
									" @click="changeStatus(p.id)">
									<i class="bi bi-check-circle-fill" v-if="p.status == 1"></i>
									<i class="bi bi-x-circle" v-if="p.status == 0"></i>
								</button>
							</td>
							<td class="text-right" v-if="$root.validatePermission('provider-update')">
								<button class="btn btn-primary" @click="showData(p)">
									<i class="bi bi-pen"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<pagination :align="'center'" :data="providerList" :limit="10" @pagination-change-page="listProviders">
					<span slot="prev-nav">&lt; Anterior</span>
					<span slot="next-nav">Siguiente &gt;</span>
				</pagination>
			</section>
		</div>
		<create-edit-provider ref="CreateEditProvider" @list-providers="listProviders(1)" />
	</div>
</template>
<script>
import CreateEditProvider from "./CreateEditProvider.vue";
export default {
	components: { CreateEditProvider },
	data() {
		return {
			providerList: {},
			search_provider:"",
			search_results: 10,
			json_fields: {
				'ID': {
					field: 'id',
					callback: (value) => {
						return value;
					}
				},
				'Razón social': {
					field: 'business_name',
					callback: (value) => {
						return value;
					}
				},
				'Tipo documento': {
					field: 'type_document',
					callback: (value) => {
						return value;
					}
				},
				'Documento': {
					field: 'document',
					callback: (value) => {
						return value;
					}
				},
				'Celular 1': {
					field: 'phone_1',
					callback: (value) => {
						return value;
					}
				},
				'Celular 2': {
					field: 'phone_2',
					callback: (value) => {
						return value;
					}
				},
				'Correo electronico': {
					field: 'email',
					callback: (value) => {
						return value;
					}
				},
				'Dirección': {
					field: 'address',
					callback: (value) => {
						return value;
					}
				},
			}
		};
	},
	created() {
		this.listProviders(1);
	},
	methods: {
		listProviders(page = 1) {
			let me = this;

			let data = {
				page: page,
				provider:this.search_provider,
				results: this.search_results
			}

			axios.get(`api/providers`, {
				params: data,
				headers: me.$root.config.headers,
			}).then(function (response) {
				me.providerList = response.data;
			});
		},
		showData: function (provider) {
			this.$refs.CreateEditProvider.showEditProvider(provider);
		},
		changeStatus: function (id) {
			let me = this;

			Swal.fire({
				title: "¿Quieres cambiar el status del proveedor?",
				showDenyButton: true,
				denyButtonText: `Cancelar`,
				confirmButtonText: `Guardar`,
			}).then((result) => {
				if (result.isConfirmed) {
					axios
						.post(`api/providers/${id}/change-status`, null, me.$root.config)
						.then(function () {
							me.listProviders(1);
						});
					Swal.fire("Cambios realizados!", "", "success");
				} else if (result.isDenied) {
					Swal.fire("Operación no realizada", "", "info");
				}
			});
		},
	},
};
</script>
