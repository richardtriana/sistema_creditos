<template>
	<div class="page">
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Creditos</h3>
			<button
				type="button"
				class="btn btn-primary"
				data-toggle="modal"
				data-target="#formCreditModal"
			>
				Crear Credito
			</button>
		</div>
		<div class="page-search d-flex justify-content-between p-4 border my-2">
			<div class="form-group col-8 m-auto">
				<label for="search_client">Buscar Cliente...</label>
				<input
					type="text"
					id="search_client"
					name="search_client"
					class="form-control"
					placeholder="Nombres | Documento"
					@keypress="listCredits()"
					v-model="search_client"
				/>
			</div>
		</div>

		<div class="page-content mt-4" style="width: 100%">
			<section class="">
				<table class="table table-sm table-bordered table-responsive">
					<thead>
						<tr>
							<th>ID</th>
							<th>Cliente</th>
							<th>Nro. Documento</th>
							<th>Valor crédito</th>
							<th>Valor Abonado</th>
							<th>Nro Cuotas</th>
							<th>Cuotas</th>
							<th>Estado</th>
							<th>Ver Cuotas</th>
							<th>
								Tabla de <br />
								amortización
							</th>
							<th>Opciones</th>
						</tr>
					</thead>
					<!-- <tbody> -->
					<tbody v-if="creditList.data && creditList.data.length > 0">
						<tr v-for="credit in creditList.data" :key="credit.index">
							<td>{{ credit.id }}</td>
							<td>{{ credit.name }} {{ credit.last_name }}</td>
							<td>{{ credit.document }}</td>
							<td class="text-right">$ {{ credit.credit_value }}</td>
							<td class="text-right">$ {{ credit.paid_value }}</td>
							<td>{{ credit.number_installments }}</td>
							<td>{{ credit.number_paid_installments }}</td>
							<td>
								<span v-if="credit.status == 1">Activo</span>
								<span v-if="credit.status == 0">Inactivo</span>
							</td>
							<td class="text-center">
								<button
									data-toggle="modal"
									data-target="#cuotasModal"
									v-if="credit.status == 1"
									class="btn btn-outline-primary"
									@click="showInstallment(credit.id)"
								>
									<i class="bi bi-eye"></i>
								</button>

								<button
									v-else
									class="btn disabled btn-outline-secondary"
									disabled
								>
									<i class="bi bi-eye-slash"></i>
								</button>
							</td>
							<td>
								<button
									class="btn btn-outline-primary"
									@click="
										printTable(credit.id, credit.name + '_' + credit.last_name)
									"
								>
									<i class="bi bi-file-pdf"></i>
								</button>
							</td>
							<td class="text-left">
								<button
									v-if="credit.status == 1"
									class="btn btn-outline-primary"
									@click="showData(credit)"
								>
									<i class="bi bi-pen"></i>
								</button>
								<button v-else class="btn btn-outline-secondary" disabled>
									<i class="bi bi-pen"></i>
								</button>

								<button
									v-if="credit.status == 1"
									class="btn btn-outline-danger"
									@click="changeStatus(credit.id)"
								>
									<i class="bi bi-trash"></i>
								</button>
								<button
									v-if="credit.status == 0"
									class="btn btn-outline-success"
									@click="changeStatus(credit.id)"
								>
									<i class="bi bi-check2-circle"></i>
								</button>
							</td>
						</tr>
					</tbody>
					<div v-else>
						<div
							class="alert alert-danger"
							style="margin: 2px auto; width: 30%"
						>
							<p>No se encontraron resultados.</p>
							<p>Crear user.</p>
						</div>
						<div class="alert alert-info" style="margin: 2px auto; width: 30%">
							Crear un nuevo Client
							<button
								type="button"
								class="btn btn-success"
								data-toggle="modal"
								data-target="#formClientModal"
							>
								Crear Client
							</button>
						</div>
					</div>
				</table>
				<pagination
					:align="'center'"
					:data="creditList"
					:limit="2"
					@pagination-change-page="listCredits"
				>
					<span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
					<span slot="next-nav"
						><i class="bi bi-chevron-double-right"></i
					></span>
				</pagination>
			</section>
		</div>

		<create-edit-client ref="CreateEditClient" @list-clients="listCredits(1)" />
		<create-edit-credit ref="CreateEditCredit" @list-credits="listCredits(1)" />

		<installment ref="Installment" />
	</div>
</template>
<script>
import CreateEditCredit from "./CreateEditCredit.vue";
import Simulator from "./Simulator.vue";
import CreateEditClient from "./../clients/CreateEditClient.vue";
import Installment from "./Installment.vue";

export default {
	components: { CreateEditCredit, Simulator, CreateEditClient, Installment },

	props: {
		installment: {
			type: Object,
		},
	},
	data() {
		return {
			search_client: "",
			creditList: {},
			clientList: {},
		};
	},
	created() {
		this.listCredits(1);
	},
	methods: {
		listCredits(page = 1) {
			let me = this;
			axios
				.get(`api/credits?page=${page}&credit=${this.search_client}`)
				.then(function (response) {
					me.creditList = response.data;
				});
		},
		listClients(page = 1) {
			let me = this;
			axios
				.get(`api/clients?page=${page}&client=${this.search_client}`)
				.then(function (response) {
					me.clientList = response.data;
				});
		},
		showData: function (credit) {
			this.$refs.CreateEditCredit.showEditCredit(credit);
		},
		simularCredit: function () {
			this.$refs.Simulator.openSimulator();
		},
		showInstallment: function (credit) {
			this.$refs.Installment.listCreditInstallments(credit);
		},
		showDataClient: function (client) {
			this.$refs.CreateEditClient.showEditClient(client);
		},
		changeStatus: function (id) {
			let me = this;

			Swal.fire({
				title: "¿Quieres cambiar el status del credito?",
				showDenyButton: true,
				denyButtonText: `Cancelar`,
				confirmButtonText: `Guardar`,
			}).then((result) => {
				if (result.isConfirmed) {
					axios
						.post("api/credits/" + id + "/change-status", null, me.$root.config)
						.then(function () {
							me.listCredits(1);
						});
					Swal.fire("Cambios realizados!", "", "success");
				} else if (result.isDenied) {
					Swal.fire("Operación no realizada", "", "info");
				}
			});
		},
		printTable(credit_id, client) {
			axios
				.get(`api/credits/amortization-table?credit_id=${credit_id}`)
				.then((response) => {
					const pdf = response.data.pdf;
					var a = document.createElement("a");
					a.href = "data:application/pdf;base64," + pdf;
					a.download = `credit_${credit_id}-${client}.pdf`;
					a.click();
				});
		},
	},
};
</script>
