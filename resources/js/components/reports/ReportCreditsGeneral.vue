<template>
	<div class="page">
		<div class="page-header">
			<h3>Reporte general de créditos</h3>
		</div>
		<div class="page-content">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>#</th>
							<th>Cliente</th>
							<th>Valor crédito</th>
							<th>Número de cuotas</th>
							<th>Estado</th>
							<th>Total abonado</th>
							<th>Abono a capital</th>
							<th>Abono a intereses</th>
						</tr>
					</thead>
					<tbody v-if="ReportGeneralCreditsList.data">
						<tr
							v-for="report in ReportGeneralCreditsList.data"
							:key="report.id"
						>
							<td class="text-right">{{ report.id }}</td>
							<td>{{ report.client.name }} {{ report.client.last_name }}</td>
							<td class="text-right">{{ report.credit_value | currency }}</td>
							<td class="text-center">{{ report.number_installments }}</td>
							<td>{{ creditStatus[report.status] }}</td>
							<td class="text-right">{{ report.paid_value | currency }}</td>
							<td class="text-right">{{ report.capital_value | currency }}</td>
							<td class="text-right">{{ report.interest_value | currency }}</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="ReportGeneralCreditsList"
					:limit="2"
					@pagination-change-page="listReportGeneralCredits"
				>
					<span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
					<span slot="next-nav"
						><i class="bi bi-chevron-double-right"></i
					></span>
				</pagination>
			</section>
			<section class="table-responsive">
				<h5>Totalizado:</h5>
				<table class="table table-sm table-bordered">
					<tr>
						<th>Total Valor créditos</th>
						<td>{{ ReportTotalValues.credit_value | currency }}</td>
					</tr>
					<tr>
						<th>Total abonado</th>
						<td>{{ ReportTotalValues.paid_value | currency }}</td>
					</tr>
					<tr>
						<th>Total abono a capital</th>
						<td>{{ ReportTotalValues.capital_value | currency }}</td>
					</tr>
					<tr>
						<th>Total abonado a intereses</th>
						<td>{{ ReportTotalValues.interest_value | currency }}</td>
					</tr>
				</table>
			</section>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			ReportGeneralCreditsList: {},
			ReportTotalValues: {},
			creditStatus: {
				0: "Pendiente",
				1: "Aprobado",
				2: "Rechazado",
				3: "Pendiente pago a proveedor",
			},
		};
	},
	methods: {
		listReportGeneralCredits(page = 1) {
			axios.get(`api/reports/general-credits?page=${page}`).then((response) => {
				this.ReportGeneralCreditsList = response.data.credits;
				this.ReportTotalValues = response.data.total_credits;
			});
		},
	},
	mounted() {
		this.listReportGeneralCredits();
	},
};
</script>

<style></style>
