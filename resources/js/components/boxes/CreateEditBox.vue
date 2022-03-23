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
							<div class="card text-primary mb-3">
								<div class="card-header">
									Saldo máximo permitido:
									<b>
										{{ root_data.current_balance_main_box | currency }}
									</b>
								</div>
							</div>
							<div class="form-group">
								<label for="current_balance">Saldo disponible</label>
								<input
									type="number"
									step="any"
									class="form-control"
									id="current_balance"
									aria-describedby="current_balanceHelp"
									v-model="formBox.current_balance"
									disabled
									readonly
								/>
							</div>
							<div class="form-group">
								<label for="input">Entradas</label>
								<input
									type="number"
									step="any"
									class="form-control"
									id="input"
									aria-describedby="inputlHelp"
									v-model="formBox.input"
									disabled
									readonly
								/>
							</div>
							<div class="form-group">
								<label for="output">Salidas</label>
								<input
									type="number"
									step="any"
									class="form-control"
									id="output"
									aria-describedby="outputlHelp"
									v-model="formBox.output"
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
									:max="root_data.current_balance_main_box"
									@keyup="
										add_amount > root_data.current_balance_main_box
											? (add_amount = root_data.current_balance_main_box)
											: (add_amount = add_amount)
									"
								/>
								<small id="addAmountHelpBlock" class="form-text text-muted">
									Monto máximo
									{{ root_data.current_balance_main_box | currency }}
								</small>
							</div>
						</div>
						<div class="modal-footer">
							<button
								type="button"
								class="btn btn-success"
								@click="cashRegister(formBox)"
							>
								Realizar Arqueo de caja
							</button>
							<button
								type="button"
								class="btn btn-success"
								@click="updateBox()"
							>
								Guardar
							</button>
							<button
								type="button"
								class="btn btn-secondary"
								data-dismiss="modal"
							>
								Cerrar
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
			root_data: this.$root.$data,
		};
	},
	mounted() {
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
			axios
				.get(`api/headquarters/list-all-headquarters`, me.$root.config)
				.then(function (response) {
					me.headquarterList = response.data;
				});
		},

		updateBox() {
			let me = this;
			if (this.box_id != 0) {
				axios
					.put(
						`api/boxes/${this.box_id}`,
						{ amount: this.add_amount },
						me.$root.config
					)
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

		cashRegister(box) {
			box.add_amount = this.add_amount;
			axios
				.post(`api/main-box/cash-register/${box.id}`, box, this.$root.config)
				.then(() => this.$emit("list-boxes"))
				.finally($("#boxModal").modal("hide"));
		},
	},
};
</script>
