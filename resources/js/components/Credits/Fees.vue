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
								<label for="amount">Monto a pay</label>
							</div>
							<div class="form-group col-4">
								<input
									type="number"
									step="any"
									class="form-control"
									id="amount"
									placeholder="$"
								/>
							</div>
							<div class="form-group col-3">
								<button class="btn btn-outline-primary my-auto">
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
									<th scope="col">Nro. Fee</th>
									<th scope="col">Valor</th>
									<th scope="col">Capital</th>
									<th>Interés</th>
									<th>Mora</th>
									<th>Dias de mora</th>
									<th>Estado</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="f in listFees" :key="f.id">
									<th>{{ f.fecha_pago }}</th>
									<td>{{ f.nro_cuota }}</td>
									<td>{{ f.valor }}</td>
									<td>{{ f.valor_pago_capital }}</td>
									<td>{{ f.valor_pago_interes }}</td>
									<td>{{ f.valor_interes_mora }}</td>
									<td>{{ f.dias_mora }}</td>
									<td>
										<span v-if="f.estado == 0" class=" badge badge-secondary"
											>Pendiente</span
										>
										<span v-if="f.estado == 1" class="badge badge-success"
											>Pagado</span
										>
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
			listFees: []
		};
	},
	methods: {
		listarFeesCredit(credit_id) {
			this.id_credit = credit_id;
			let me = this;
			axios.get(`api/credits/${credit_id}/fees`).then(function(response) {
				me.listFees = response.data;
			});
		},

		payFee(id) {
			let me = this;
			axios.post(`api/fee/${id}/pay-fee`).then(function(response) {
				me.listarFeesCredit(me.id_credit);
			});
		},

		printTable() {
			axios
				.get(`api/credits/amortization-table?credit_id=${this.id_credit}`)
				.then(response => {
					console.log(response);

					const pdf = response.data;
					var a = document.createElement("a");
					a.href = "data:application/pdf;base64," + pdf;
					a.download = `Order-5.pdf`;
					a.click();
				});
		}
	}
};
</script>
