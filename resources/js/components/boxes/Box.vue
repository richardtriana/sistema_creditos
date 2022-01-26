<template>
	<div>
		<div class="card mt-4">
			<div class="card-header">
				<h5>Cajas relacionadas</h5>
			</div>
			<table class="table table-sm table-responsive">
				<thead class="thead-light">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Sede</th>
						<th scope="col">Saldo disponible</th>
						<th scope="col">Deuda</th>
						<th>Estado</th>
						<th>Opciones</th>
					</tr>
				</thead>
				<tbody v-if="boxList.length > 0">
					<tr v-for="(l, index) in boxList" :key="index">
						<th scope="row">{{ index + 1 }}</th>
						<td>x</td>
						<td>Otto</td>
						<td>@mdo</td>
						<td>
							<button class="btn btn-outline-success">
								<i class="bi bi-check-circle"></i>
							</button>
							<button class="btn btn-outline-danger">
								<i class="bi bi-x-circle"></i>
							</button>
						</td>
						<td>
							<button
								type="button"
								class="btn btn-outline-primary"
								data-toggle="modal"
								data-target="#boxModal"
							>
								<i class="bi bi-pencil"></i>
							</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
		<create-edit-box />
	</div>
</template>

<script>
import CreateEditBox from "./CreateEditBox.vue";
export default {
	components: { CreateEditBox },
	data() {
		return {
			boxList: {},
		};
	},
	created() {
		this.listBoxes();
	},
	methods: {
		listBoxes() {
			axios.get("api/boxes").then((reponse) => {
				this.boxList = reponse.data.boxes;
			});
		},
	},
};
</script>
