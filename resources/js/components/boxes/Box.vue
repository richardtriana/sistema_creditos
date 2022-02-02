<template>
	<div>
		<div class="card mt-4">
			<div class="card-header">
				<h5>Cajas relacionadas</h5>
			</div>
			<table class="table table-sm table-responsive table-bordered">
				<thead class="thead-light">
					<tr>
						<th scope="col">#</th>
						<th scope="col">Sede</th>
						<th scope="col">Saldo disponible</th>
						<th scope="col">Última modificación</th>
						<th>ültimo editor</th>
						<th>Opciones</th>
						<!-- <th>Eliminar</th> -->
					</tr>
				</thead>
				<tbody v-if="boxList.length > 0">
					<tr v-for="(box, index) in boxList" :key="index">
						<th scope="row">{{ index + 1 }}</th>
						<td>{{ box.headquarter.headquarter }}</td>
						<td class="text-right">{{ box.current_balance | dollar }}</td>
						<td>{{ box.last_update }}</td>
						<td>{{ box.last_editor.name }} {{ box.last_editor.last_name }}</td>
						<td>
							<button
								type="button"
								class="btn btn-outline-primary"
								data-toggle="modal"
								data-target="#boxModal"
								@click="showEditBox(box)"
							>
								<i class="bi bi-pencil"></i>
							</button>
						</td>
						<!-- <td>
							<button class="btn btn-outline-danger">
								<i class="bi bi-trash2"></i>
							</button>
						</td> -->
					</tr>
				</tbody>
			</table>
		</div>
		<create-edit-box ref="CreateEditBox" @list-boxes="listBoxes()" />
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

		showEditBox(box) {
			this.$refs.CreateEditBox.showEditBox(box);
		},
	},
};
</script>
