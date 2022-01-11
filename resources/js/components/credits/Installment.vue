<template>
	<div
		class="modal fade"
		id="cuotasModal"
		tabindex="-1"
		role="dialog"
		aria-labelledby="cuotasModalLabel"
		aria-hidden="true"
	>
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="cuotasModalLabel">
						Listado de Cuotas
					</h5>
					<button
						type="button"
						class="close"
						data-dismiss="modal"
						aria-label="Close"
					>
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div
						class="page-header d-flex justify-content-between p-4 border my-2"
					>
						<div class="form-row w-100">
							<div class="form-group col-2">
								<label for="amount">Monto a pagar</label>
							</div>
							<div class="form-group col-4">
								<input
									type="number"
									step="any"
									class="form-control"
									id="amount"
									placeholder="$"
									v-model="amount_value"
								/>
							</div>
							<div class="form-group col-3">
								<button
									class="btn btn-outline-primary my-auto"
									@click="payCredit()"
								>
									<i class="bi bi-currency-dollar"></i> Abonar a crédito
								</button>
							</div>
							<div class="form-group col-3">
								<button class="btn btn-outline-primary" @click="printTable()">
									<i class="bi bi-file-pdf"></i> Tabla de amortización
								</button>
							</div>
						</div>
					</div>
					<section>
						<table class="table">
							<thead>
								<tr>
									<th>Fecha de vencimiento</th>
									<th scope="col">Nro. Installment</th>
									<th scope="col">Valor</th>
									<th scope="col">Capital</th>
									<th>Interés</th>
									<th>Mora</th>
									<th>Dias de mora</th>
									<th>Estado</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="f in listInstallments" :key="f.id">
									<th>{{ f.payment_date }}</th>
									<td>{{ f.nro_cuota }}</td>
									<td>{{ f.valor }}</td>
									<td>{{ f.valor_pago_capital }}</td>
									<td>{{ f.valor_pago_interes }}</td>
									<td>{{ f.late_interests_value }}</td>
									<td>{{ f.days_past_due }}</td>
									<td>
										<span v-if="f.status == 0" class=" badge badge-secondary"
											>Pendiente</span
										>
										<span v-if="f.status == 1" class="badge badge-success"
											>Pagado</span
										>
									</td>
									<td>
										<button
											@click="payInstallment(f.id)"
											type="button"
											class="btn btn-outline-success"
											v-if="f.status == 0"
										></button>
									</td>
								</tr>
							</tbody>
						</table>
					</section>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">
						Close
					</button>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {
	data() {
		return {
			id_credit: 0,
			listInstallments: [],
			listInstallmentsPaid: [],
			amount_value: 0
		};
	},
	methods: {
		listCreditInstallments(credit_id) {
			this.id_credit = credit_id;
			let me = this;
			axios.get(`api/credits/${credit_id}/installments`).then(function(response) {
				me.listInstallments = response.data;
			});
		},

		payInstallment(id) {
			let me = this;
			axios.post(`api/installment/${id}/pay-installment`).then(function() {
				me.listCreditInstallments(me.id_credit);
			});
		},

		payCredit() {
			// var amount_x = this.amount_value;
			// for (let i = 0; i < this.listInstallments.length && amount_x > 0; i++) {
			// 	// if (this.listInstallments[i]["status"] == 0) {
			// 	amount_x = amount_x - this.listInstallments[i]["valor"];
			// 	console.log(amount_x, this.listInstallments[i]["valor"]);
			// 	if (amount_x > 0) {
			// 		this.listInstallmentsPaid.push(this.listInstallments[i]);
			// 		if (amount_x < this.listInstallments[i]["valor"]) {
			// 			console.log("holi");
			// 		}
			// 	}
			// 	// console.log("amount1", amount_x);
			// }

			var data = {
				amount: this.amount_value
			};
			axios.post(`api/credits/pay-credit-installments/${this.id_credit}`, data);
		},

		printTable() {
			axios
				.get(`api/credits/amortization-table?credit_id=${this.id_credit}`)
				.then(response => {
					const pdf = response.data.pdf;
					var a = document.createElement("a");
					a.href = "data:application/pdf;base64," + pdf;
					a.download = `credit_${this.id_credit}.pdf`;
					a.click();
				});
		}
	}
};
</script>
