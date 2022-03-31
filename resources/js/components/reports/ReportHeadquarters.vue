<template>
	<div class="page">
		<div class="page-header">
			<h3>Reporte de Sedes</h3>
		</div>
		<div class="page-content">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>#</th>
							<th>Sede</th>
							<th>Nro. Creditos</th>
							<th>Pendientes</th>
							<th>Pendientes con proveedor</th>
							<th>Aprobados</th>
							<th>Rechazados</th>
							<th>Completado</th>
							<th>Monto Crédito</th>
							<th>Capital Abonado</th>
							<th>Interés abonado</th>
						</tr>
					</thead>
					<tbody v-if="ReportHeaquartersList.data">
						<tr
							v-for="(report, index) in ReportHeaquartersList.data"
							:key="index"
						>
							<td>{{ index + 1 }}</td>
							<td>{{ report.headquarter }}</td>
							<td>{{ report.number_of_credits }}</td>
							<td>{{ report.pending }}</td>
							<td>{{ report.pending_provider }}</td>
							<td>{{ report.approved }}</td>
							<td>{{ report.rejected }}</td>
							<td>{{ report.completed }}</td>
							<td class="text-right">{{ report.credit_value | currency }}</td>
							<td class="text-right">{{ report.capital_value | currency }}</td>
							<td class="text-right">{{ report.interest_value | currency }}</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="ReportHeaquartersList"
					:limit="2"
					@pagination-change-page="listReportHeadquarters"
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
			ReportHeaquartersList: {},
		};
	},
	methods: {
		listReportHeadquarters(page = 1) {
			axios
				.get(`api/reports/headquarters?page=${page}`, this.$root.config)
				.then((response) => {
					this.ReportHeaquartersList = response.data;
				});
		},
	},
	mounted() {
		this.listReportHeadquarters(1);
	},
};
</script>
