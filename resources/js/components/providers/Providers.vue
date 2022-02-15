<template>
	<div class="page">
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Proveedores</h3>
			<button
				type="button"
				class="btn btn-primary"
				data-toggle="modal"
				data-target="#formProviderModal"
			>
				Crear proveedor
			</button>
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
							<th>Estado</th>
							<th>Opciones</th>
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
							<td class="text-right">
								<button
									class="btn"
									:class="
										p.status == 1 ? 'btn-outline-success' : 'btn-outline-danger'
									"
									@click="changeStatus(p.id)"
								>
									<i class="bi bi-check-circle-fill" v-if="p.status == 1"></i>
									<i class="bi bi-x-circle" v-if="p.status == 0"></i>
								</button>
							</td>
							<td class="text-right">
								<button class="btn btn-outline-primary" @click="showData(p)">
									<i class="bi bi-pen"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="providerList"
					:limit="10"
					@pagination-change-page="listProviders"
				>
					<span slot="prev-nav">&lt; Previous</span>
					<span slot="next-nav">Next &gt;</span>
				</pagination>
			</section>
		</div>
		<create-edit-provider
			ref="CreateEditProvider"
			@list-providers="listProviders(1)"
		/>
	</div>
</template>
<script>
import CreateEditProvider from "./CreateEditProvider.vue";
export default {
	components: { CreateEditProvider },
	data() {
		return {
			providerList: {},
		};
	},
	created() {
		this.listProviders(1);
	},
	methods: {
		listProviders(page = 1) {
			let me = this;
			axios.get("api/providers?page=" + page).then(function (response) {
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
