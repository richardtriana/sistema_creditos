<template>
	<div>
		<div
			class="modal fade"
			id="formCreditModal"
			tabindex="-1"
			aria-labelledby="formCreditModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog modal-lg modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="formCreditModalLabel">Creditos</h5>
						<button
							type="button"
							class="close"
							data-dismiss="modal"
							@click="(editar = false), resetData()"
							aria-label="Close"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-row">
								<div class="form-group col-md-4">
									<div class="">
										<label for="client_id" class="col-form-label"
											>Cliente</label
										>
										<button
											class="btn btn-outline-primary mb-2"
											type="button"
											data-toggle="modal"
											data-target="#addClientModal"
										>
											<i class="bi bi-card-checklist"></i> Añadir cliente
										</button>
									</div>
									<input
										type="text"
										class="form-control"
										disabled
										readonly
										v-model="client_name"
									/>
								</div>

								<div class="form-group col-md-4">
									<div class="">
										<label for="debtor_id" class="col-form-label"
											>Codeudor</label
										>
										<button
											class="btn btn-outline-primary mb-2"
											type="button"
											data-toggle="modal"
											data-target="#addDebtorModal"
										>
											<i class="bi bi-card-checklist"></i> Añadir codeudor
										</button>
									</div>
									<input
										type="text"
										class="form-control"
										disabled
										readonly
										v-model="debtor_name"
									/>
								</div>

								<div class="form-group col-md-4">
									<div class="">
										<label for="provider_id" class="col-form-label"
											>Proveedor</label
										>
										<button
											class="btn btn-outline-primary mb-2"
											type="button"
											data-toggle="modal"
											data-target="#addProviderModal"
										>
											<i class="bi bi-card-checklist"></i> Añadir Proveedor
										</button>
									</div>
									<input
										type="text"
										class="form-control"
										disabled
										readonly
										v-model="provider_name"
									/>
								</div>

								<div class="form-group col-md-4">
									<label for="description">Descripción</label>
									<input
										type="text"
										class="form-control"
										id="description"
										v-model="formCredit.description"
									/>
								</div>

								<div class="form-group col-md-4">
									<label for="headquarter_id">Sedes</label>
									<v-select
										:options="headquarterList.data"
										label="headquarter"
										aria-logname="{}"
										:reduce="(headquarter) => headquarter.id"
										v-model="formCredit.headquarter_id"
									>
									</v-select>
								</div>
								<div class="form-group col-md-4">
									<label for="credit_value">Valor Credito</label>
									<input
										type="number"
										class="form-control"
										id="credit_value"
										step="any"
										v-model="formCredit.credit_value"
									/>
								</div>
								<div class="form-group col-md-4">
									<label for="interest">Interes</label>
									<input
										type="number"
										class="form-control"
										id="interest"
										v-model="formCredit.interest"
										step="any"
									/>
								</div>

								<div class="form-group col-md-4">
									<label for="number_installments">Cantidad Cuotas</label>
									<input
										type="number"
										class="form-control"
										id="number_installments"
										v-model="formCredit.number_installments"
									/>
								</div>

								<div class="form-group col-md-4">
									<label for="start_date">Fecha inicio</label>
									<input
										type="date"
										class="form-control"
										id="start_date"
										v-model="formCredit.start_date"
									/>
								</div>
							</div>
							<simulator
								:capital="formCredit.credit_value"
								:interest="formCredit.interest"
								:number_installments="formCredit.number_installments"
								:start_date="formCredit.start_date"
								ref="Simulator"
							></simulator>
							<button
								type="button"
								class="btn btn-secondary"
								data-dismiss="modal"
								@click="editar = false"
							>
								Cerrar
							</button>
							<button
								type="button"
								class="btn btn-primary rounded"
								@click="editar ? editCredit() : createCredit()"
							>
								Guardar
							</button>
						</form>
					</div>
					<div class="modal-footer"></div>
				</div>
			</div>
		</div>
		<add-client @add-client="receiveClient($event)" />
		<add-debtor @add-debtor="receiveDebtor($event)" />
		<add-provider @add-provider="receiveProvider($event)" />
	</div>
</template>

<script>
import AddClient from "../clients/AddClient.vue";
import AddDebtor from "../clients/AddDebtor.vue";
import AddProvider from "../providers/AddProvider.vue";
import Simulator from "./Simulator.vue";
export default {
	components: { Simulator, AddClient, AddDebtor, AddProvider },
	data() {
		return {
			editar: false,
			headquarterList: [],
			formCredit: {
				client_id: "",
				debtor_id: "",
				headquarter_id: "",
				user_id: "",
				provider_id: "",
				number_installments: "",
				number_paid_installments: "",
				number_paid_installments: "",
				day_limit: "",
				debtor: "",
				status: "1",
				start_date: "",
				interest: "",
				annual_interest_percentage: 0,
				installment_value: "",
				credit_value: "",
				paid_value: "",
				capital_value: "",
				interest_value: "",
				description: "",
				disbursement_date: "",
			},
			client_name: "",
			debtor_name: "",
			provider_name: "",
		};
	},
	created() {
		this.listHeadquarters(1);
	},
	methods: {
		listHeadquarters(page = 1) {
			let me = this;
			axios.get(`api/headquarters?page=${page}`).then(function (response) {
				me.headquarterList = response.data;
			});
		},
		createCredit() {
			let me = this;
			axios.post("api/credits", this.formCredit).then(function () {
				$("#formCreditModal").modal("hide");
				me.resetData();
				this.$emit("list-credits");
			});
		},
		showEditCredit(credit) {
			this.editar = true;
			let me = this;
			$("#formCreditModal").modal("show");
			me.formCredit = credit;
		},
		editCredit() {
			let me = this;
			axios
				.put("api/credits/" + this.formCredit.id, this.formCredit)
				.then(function () {
					$("#formCreditModal").modal("hide");
					me.resetData();
				});
			this.$emit("list-credits");
			this.editar = false;
		},
		receiveClient(client) {
			this.formCredit.client_id = client.id;
			this.client_name = `${client.name} ${client.last_name}`;
		},
		receiveDebtor(debtor) {
			this.formCredit.debtor_id = debtor.id;
			this.debtor_name = `${debtor.name} ${debtor.last_name}`;
		},
		receiveProvider(provider) {
			this.formCredit.provider_id = provider.id;
			this.provider_name = `${provider.name} ${provider.last_name}`;
		},
		resetData() {
			let me = this;
			Object.keys(this.formCredit).forEach(function (key, index) {
				me.formCredit[key] = "";
			});
		},
	},
};
</script>
