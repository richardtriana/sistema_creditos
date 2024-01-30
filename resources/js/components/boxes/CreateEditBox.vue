<template>
	<div>
		<div class="modal fade" id="boxModal" tabindex="-1" aria-labelledby="boxModalLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="boxModalLabel">Sede</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
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
							<ul class="nav nav-tabs nav-pills">
								<li class="nav-item w-50 text-center border text-uppercase font-weight-bold">
									<a class="btn-operations  nav-link active" id="btnAddCash" role="button"
										@click="changeOperation('btnAddCash', 1)" type="button">Añadir saldo</a>
								</li>
								<li class="nav-item w-50 text-center border text-uppercase font-weight-bold">
									<a class="btn-operations nav-link" id="btnUpdateBox" role="button"
										@click="changeOperation('btnUpdateBox', 2)" type="button">Realizar arqueo</a>
								</li>
							</ul>
							<div class="text-center">
								<p class="text-danger"> {{ errorCashRegister }}</p>
							</div>
							<div class="form-group">
								<label for="current_balance">Saldo disponible</label>
								<input type="number" step="any" class="form-control" id="current_balance"
									aria-describedby="current_balanceHelp" v-model="formBox.current_balance" disabled
									readonly />
							</div>
							<div class="form-group">
								<label for="input">Entradas</label>
								<input type="number" step="any" class="form-control" id="input"
									aria-describedby="inputlHelp" v-model="formBox.input" disabled readonly />
							</div>
							<div class="form-group">
								<label for="output">Salidas</label>
								<input type="number" step="any" class="form-control" id="output"
									aria-describedby="outputlHelp" v-model="formBox.output" disabled readonly />
							</div>
							<div class="form-group">
								<label for="headquarter_id">Sede</label>
								<select class="form-control" id="headquarter_id" v-model="formBox.headquarter_id" disabled>
									<option v-for="headquarter in headquarterList" :key="headquarter.id"
										:value="headquarter.id">
										{{ headquarter.headquarter }}
									</option>
								</select>
							</div>
							<div class="form-group">
								<label for="add_amount">Cantidad: <b>{{ add_amount | currency }}</b></label>
								<input type="number" step="any" class="form-control" id="add_amount" v-model="add_amount"
									:max="root_data.current_balance_main_box" @keyup="
										add_amount > root_data.current_balance_main_box
											? (add_amount = root_data.current_balance_main_box)
											: (add_amount = add_amount)
										" />
								<small id="addAmountHelpBlock" class="form-text text-muted">
									Monto máximo
									{{ root_data.current_balance_main_box | currency }}
								</small>
								<small class="form-text text-danger">
									{{ formErrors.amount }}
								</small>
							</div>
							<template v-if="operationId == 2">
								<div class="form-group">
									<label for="cash">Efectivo</label>
									<input type="number" step="any" class="form-control" id="cash"
										aria-describedby="cashlHelp" v-model="formBox.cash" @keyup="calculateBalance()" />
								</div>
								<div class="form-group">
									<label for="consignment_to_client">Consignación Cliente</label>
									<input type="number" step="any" class="form-control" id="consignment_to_client"
										aria-describedby="consignment_to_clientlHelp"
										v-model="formBox.consignment_to_client" @keyup="calculateBalance()" />
								</div>
								<div class="form-group">
									<label for="payment_to_provider">Pago a proveedor</label>
									<input type="number" step="any" class="form-control" id="payment_to_provider"
										aria-describedby="payment_to_providerlHelp" v-model="formBox.payment_to_provider"
										@keyup="calculateBalance()" />
								</div>
								<div class="form-group">
									<label for="">Diferencia Arqueo</label>
									<input type="text" class="form-control" :class="difference < 0 ? ' text-danger' : ' text-info'" readonly :value="difference">
								</div>
								<div class="form-group">
									<label for="status">Estado</label>
									<input type="text" step="any" class="form-control" id="status"
										aria-describedby="statuslHelp" v-model="formBox.status" readonly disabled />
								</div>
								<div class="form-group">
									<label for="observations">Observaciones</label>
									<textarea name="observations" id="observations" class="form-control" cols="30" rows="3"
										aria-describedby="observationslHelp" v-model="formBox.observations"></textarea>
								</div>
							</template>
						</div>
						<div class="modal-footer">
							<template v-if="operationId == 1">
								<button type="button" :disabled="add_amount <= 0" class="btn btn-success"
									@click="updateBox()">
									Guardar
								</button>
							</template>
							<template v-if="operationId == 2">
								<button type="button" class="btn btn-success"
									@click="add_amount > 0 ? collectAmount(formBox) : cashRegister(formBox)">
									Realizar Arqueo de caja
								</button>
							</template>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">
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
			difference: 0,
			formBox: {
				cash: {
					type: Number,
					default: 0
				},
				payment_to_provider: {
					type: Number,
					default: 0
				},
				consignment_to_client: {
					type: Number,
					default: 0
				}
			},
			add_amount: 0,
			headquarterList: {},
			root_data: this.$root.$data,
			formErrors: {
				amount: ""
			},
			errorCashRegister: '',
			operationId: 1
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
			me.errorCashRegister = ""
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
				me.$root.assignErrors(false, me.formErrors);
				axios
					.put(
						`api/boxes/${this.box_id}`,
						{ amount: this.add_amount },
						me.$root.config
					)
					.then(function () {
						$("#boxModal").modal("hide");
						me.$emit("list-boxes");
						Swal.fire("Cambios realizados!", "", "success");
					}).catch(response => {
						me.$root.assignErrors(response, me.formErrors);
						Swal.fire("Operación no realizada", "", "info");
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
			this.errorCashRegister = "";
			box.add_amount = this.add_amount;
			axios
				.post(`api/main-box/cash-register/${box.id}`, this.formBox, this.$root.config)
				.then(() => {
					this.$emit("list-boxes");
					$("#boxModal").modal("hide");
					Swal.fire("Cambios realizados!", "", "success");
				})
				.catch(response => {
					this.errorCashRegister = `Error al realizar arqueo de caja \n ${response}`
					Swal.fire("Operación no realizada", "", "info");
				})
		},
		collectAmount(box) {
			this.errorCashRegister = "";
			box.add_amount = this.add_amount;
			axios
				.post(`api/main-box/collect-amount/${box.id}`, this.formBox, this.$root.config)
				.then(() => {
					this.$emit("list-boxes");
					$("#boxModal").modal("hide");
					Swal.fire("Cambios realizados!", "", "success");

				})
				.catch(response => {
					this.errorCashRegister = `Error al realizar arqueo de caja \n ${response}`
					Swal.fire("Operación no realizada", "", "info");
				})
		},
		changeOperation(buttonId, operationId) {
			$('.btn-operations').removeClass('active')
			$(`#${buttonId}`).addClass('active')
			this.operationId = operationId
		},
		calculateBalance() {
			let total = Number(this.formBox.cash) + Number(this.formBox.consignment_to_client) + Number(this.formBox.payment_to_provider);

			console.log(this.formBox.current_balance);
			console.log((total));

			if ((this.formBox.current_balance).toFixed() == (total.toFixed())) {
				this.formBox.status = 'Correct'
			} else {
				this.formBox.status = 'Incorrect'
			}

			this.difference = (this.formBox.current_balance - total).toFixed(2);
		}
	},
};
</script>
