<template>
	<div class="page">
		<div class="page-header">
			<h3>Reporte de cajas de Sedes</h3>
		</div>
		<div
			class="page-search form-inline justify-content-center row p-4 border my-2"
		>
			<input
				type="text"
				id="search_client_document"
				name="search_client_document"
				class="form-control w-50"
				placeholder="Documento"
				v-model="search_client_document"
			/>
			<div class="p-2">
				<button
					class="btn btn-primary"
					type="button"
					@click="listReportGeneralClient()"
				>
					Buscar
				</button>
			</div>
		</div>
		<div class="page-content">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>#</th>
							<th>Cliente</th>
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
					<tbody v-if="ReportGeneralClientList.length > 0">
						<tr v-for="(report, index) in ReportGeneralClientList" :key="index">
							<td>{{ index + 1 }}</td>
							<td>{{ report.name }} {{ report.last_name }}</td>
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
					<tbody v-else>
						<tr>
							<td colspan="12" class="alert alert-warning text-center">
								Buscar cliente
							</td>
						</tr>
					</tbody>
				</table>
			</section>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			ReportGeneralClientList: {},
			search_client_document: "",
		};
	},
	methods: {
		listReportGeneralClient() {
			axios
				.get(
					`api/reports/general-client?document=${this.search_client_document}`,
					this.$root.config
				)
				.then((response) => {
					this.ReportGeneralClientList = response.data;
				});
		},
	},
	mounted() {
		this.listReportGeneralClient();
	},
};
</script>
