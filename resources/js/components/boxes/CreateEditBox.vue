<template>
	<div>
		<div
			class="modal fade"
			id="boxModal"
			tabindex="-1"
			aria-labelledby="boxModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="boxModalLabel">Sede</h5>
						<button
							type="button"
							class="close"
							data-dismiss="modal"
							aria-label="Close"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<form>
						<div class="modal-body">
							<div class="form-group">
								<label for="current_balance">Saldo disponible</label>
								<input
									type="number"
									step="any"
									class="form-control"
									id="current_balance"
									aria-describedby="emailHelp"
									v-model="formBox.current_balance"
									disabled
									readonly
								/>
							</div>
							<div class="form-group">
								<label for="headquarter_id">Sede</label>
								<select
									class="form-control"
									id="headquarter_id"
									v-model="formBox.headquarter_id"
									disabled
								>
									<option
										v-for="headquarter in headquarterList"
										:key="headquarter.id"
										:value="headquarter.id"
									>
										{{ headquarter.headquarter }}
									</option>
								</select>
							</div>
							<div class="form-group">
								<label for="add_amount">Añadir saldo</label>
								<input
									type="number"
									step="any"
									class="form-control"
									id="add_amount"
									v-model="add_amount"
								/>
							</div>
						</div>
						<div class="modal-footer">
							<button
								type="button"
								class="btn btn-secondary"
								data-dismiss="modal"
							>
								Cerrar
							</button>
							<button
								type="button"
								class="btn btn-primary"
								@click="updateBox()"
							>
								Guardar
							</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			box_id: 0,
			editar: false,
			formBox: {},
			add_amount: 0,
			headquarterList: {},
		};
	},
	created() {
		this.listHeadquarters(1);
	},
	methods: {
		showEditBox(box) {
			this.editar = true;
			this.box_id = box.id;
			let me = this;
			$("#boxModal").modal("show");
			me.formBox = box;
			console.log(box);
		},
		listHeadquarters() {
			let me = this;
			axios.get(`api/headquarters/list-headquarter`).then(function (response) {
				me.headquarterList = response.data;
			});
		},

		updateBox() {
			let me = this;
			if (this.box_id != 0) {
				axios
					.put(`api/boxes/${this.box_id}`, { amount: this.add_amount })
					.then(function () {
						$("#boxModal").modal("hide");
						me.$emit("list-boxes");
					});
			}
		},
		resetData() {
			let me = this;
			Object.keys(this.formBox).forEach(function (key, index) {
				me.formBox[key] = "";
			});
		},
	},
};
</script>
