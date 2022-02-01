<template>
	<div class="page">
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Proveedores</h3>
			<button
				type="button"
				class="btn btn-primary"
				data-toggle="modal"
				data-target="#formProveedorModal"
			>
				Crear proveedor
			</button>
		</div>
		<div class="page-content mt-4">
			<section class="">
				<table class="table table-sm table-bordered table-responsive">
					<thead>
						<tr>
							<th>id</th>
							<th>Nombres</th>
							<th>Apellidos</th>
							<th>Tipo Documento</th>
							<th>Num Documento</th>
							<th>Celular1</th>
							<th>Celular2</th>
							<th>Correo Electronico</th>
							<th>Dirección</th>
							<th>Estado</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="p in providerList.data" :key="p.id">
							<td>{{ p.id }}</td>
							<td>{{ p.name }}</td>
							<td>{{ p.last_name }}</td>
							<td>
								<span v-if="p.type_document == '1'">Cèdula de ciudadania</span>
								<span v-if="p.type_document == '2'">Passaporte</span>
							</td>
							<td>{{ p.document }}</td>
							<td>{{ p.phone_1 }}</td>
							<td>{{ p.phone_2 }}</td>
							<td>{{ p.email }}</td>
							<td>{{ p.address }}</td>
							<td>
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
							<td class="text-center">
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
			@listar-proveedores="listProviders(1)"
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
			axios.get("api/proveedores?page=" + page).then(function (response) {
				me.providerList = response.data;
			});
		},
		showData: function (proveedor) {
			this.$refs.CreateEditProvider.abirEditarProveedor(proveedor);
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
						.post(`api/proveedores/${id}/change-status`, null, me.$root.config)
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
