<template>
	<div class="page">
		<div class="page-header">
			<h3>Reporte de cartera</h3>
		</div>
		<div class="page-content">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>#</th>
							<th>Cliente</th>
							<th>Contacto <i class="bi bi-telephone"></i></th>
							<th>Valor cuota</th>
							<th>Fecha de pago cuota</th>
							<th>Estado</th>
							<th>Nro. Cuota</th>
						</tr>
					</thead>
					<tbody v-if="ReportPortfolioList.data">
						<tr v-for="report in ReportPortfolioList.data" :key="report.id">
							<td>{{ report.credit_id }}</td>
							<td>{{ report.name }} {{ report.last_name }}</td>
							<td>
								<a
									v-if="report.phone_1 != null"
									target="_blank"
									:href="`https://wa.me/57${report.phone_1}?text=Le%20escribimos%20desde%20Grucosur%20con%20el%20fin%20de%20informarle%20el%20estado%20de%20su%20credito`"
									><i class="bi bi-whatsapp"></i> {{ report.phone_1 }}</a
								>
								<br />
								<a
									v-if="report.phone_2 != null"
									target="_blank"
									:href="`https://wa.me/57${report.phone_2}?text=Le%20escribimos%20desde%20Grucosur%20con%20el%20fin%20de%20informarle%20el%20estado%20de%20su%20credito`"
									><i class="bi bi-whatsapp"></i> {{ report.phone_2 }}</a
								>
							</td>
							<td class="text-right">{{ report.value | currency }}</td>
							<td class="text-center">{{ report.payment_date }}</td>
							<td class="text-center">
								<span class="text-success" v-if="report.payment_date > now"
									>Próximo a vencer</span
								>
								<span class="text-warning" v-if="report.payment_date == now"
									>Vence hoy</span
								>
								<span class="text-danger" v-if="report.payment_date < now"
									>En mora</span
								>
							</td>
							<td>
								{{ report.installment_number }}
							</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="ReportPortfolioList"
					:limit="2"
					@pagination-change-page="listReportPortfolio"
				>
					<span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
					<span slot="next-nav"
						><i class="bi bi-chevron-double-right"></i
					></span>
				</pagination>
			</section>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			ReportPortfolioList: {},
			now: new Date().toISOString().slice(0, 10),
		};
	},
	methods: {
		listReportPortfolio(page = 1) {
			axios.get(`api/reports/portfolio?page=${page}`).then((response) => {
				this.ReportPortfolioList = response.data;
			});
		},
	},
	mounted() {
		this.listReportPortfolio();
	},
};
</script>
