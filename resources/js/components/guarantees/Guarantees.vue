<template>
	<div class="page">
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Garantías</h3>
			<button
				type="button"
				class="btn btn-primary"
				data-toggle="modal"
				data-target="#formGuaranteeModal"
				v-if="$root.validatePermission('guarantee-store')"
			>
				Crear garantía
			</button>
		</div>
		<div class="page-content mt-4">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>ID</th>
							<th>Garantía </th>
							
							<th v-if="$root.validatePermission('guarantee-status')">Estado</th>
							<th v-if="$root.validatePermission('guarantee-update')">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="guarantee in guaranteeList.data" :key="guarantee.id">
							<td>{{ guarantee.id }}</td>
							<td>{{ guarantee.guarantee }}</td>
							
							<td class="text-right" v-if="$root.validatePermission('guarantee-status')">
								<button
									class="btn"
									:class="
										guarantee.state == 1 ? 'btn-success' : 'btn-danger'
									"
									@click="changeStatus(guarantee.id)"
								>
									<i class="bi bi-check-circle-fill" v-if="guarantee.state == 1"></i>
									<i class="bi bi-x-circle" v-if="guarantee.state == 0"></i>
								</button>
							</td>
							<td class="text-right" v-if="$root.validatePermission('guarantee-update')">
								<button class="btn btn-primary" @click="showData(guarantee)">
									<i class="bi bi-pen"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="guaranteeList"
					:limit="10"
					@pagination-change-page="listGuarantees"
				>
					<span slot="prev-nav">&lt; Previous</span>
					<span slot="next-nav">Next &gt;</span>
				</pagination>
			</section>
		</div>
		<create-edit-guarantee
			ref="CreateEditGuarantee"
			@list-guarantees="listGuarantees(1)"
		/>
	</div>
</template>
<script>
import CreateEditGuarantee from "./CreateEditGuarantee.vue";
export default {
	components: { CreateEditGuarantee },
	data() {
		return {
			guaranteeList: {},
		};
	},
	created() {
		this.listGuarantees(1);
	},
	methods: {
		listGuarantees(page = 1) {
			let me = this;
			axios.get("api/guarantees?page=" + page, me.$root.config).then(function (response) {
				me.guaranteeList = response.data;
			});
		},
		showData: function (guarantee) {
			this.$refs.CreateEditGuarantee.showEditGuarantee(guarantee);
		},
		changeStatus: function (id) {
			let me = this;

			Swal.fire({
				title: "¿Quieres cambiar el status de la garantía?",
				showDenyButton: true,
				denyButtonText: `Cancelar`,
				confirmButtonText: `Guardar`,
			}).then((result) => {
				if (result.isConfirmed) {
					axios
						.post(`api/guarantees/${id}/change-status`, null, me.$root.config)
						.then(function () {
							me.listGuarantees(1);
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
