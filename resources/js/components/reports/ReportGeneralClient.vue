<template>
	<div class="page">
		<div class="page-header">
			<h3>Reporte de Cliente</h3>
		</div>
		<div class="page-search border my-2">
			<form>
				<div class="form-row">
					<div class="form-group col-md-3 col-sm-6 col-xs-6 ">
						<label for="search_client">Cliente: </label>
						<input type="text" id="search_client_document" name="search_client_document" class="form-control"
							placeholder="Buscar cliente | Documento" v-model="search_client_document" />
					</div>
					<div class="form-group col-md-3 col-sm-6 col-xs-6 ">
						<label for="">Mostrar {{ search_results }} resultados:</label>
						<input type="number" id="search_results" name="search_results" class="form-control" placeholder="Desde"
							v-model="search_results" max="1000" />
					</div>
					<div class="form-group col-md-3 col-sm-6 col-xs-6">
						<button class="btn btn-success w-100 mt-5" type="button" @click="listReportGeneralClient()">
							Buscar
						</button>
					</div>
					<div class="form-group col-md-3 col-sm-6 col-xs-6">
							<download-excel class="btn btn-primary w-100 mt-5" :fields="json_fields" :data="ReportGeneralClientList"
								name="report-general-clients.xls" type="xls">
								<i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
							</download-excel>
						</div>
				</div>
			</form>
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
							<td>
								{{ report.name }} {{ report.last_name }} <br>
								{{ report.document }}
							</td>
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
						<tr class="text-center">
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
			ReportGeneralClientList: [],
			search_client_document: "",
			search_results: 15,
			json_fields: {
				'Cliente': {
					callback: (value) => {
						let name = value.name;
						let last_name = value.last_name
						return `${last_name} ${name}`;
					}
				},
				'Sede': {
					field: 'headquarter',
					callback: (value) => {
						return value;
					}
				},
				'# de créditos': {
					field: 'number_of_credits',
					callback: (value) => {
						return value;
					}
				},
				'Pendientes': {
					field: 'pending',
					callback: (value) => {
						return value;
					}
				},
				'Pendientes con proveedor': {
					field: 'pending_provider',
					callback: (value) => {
						return value;
					}
				},
				'Aprobados': {
					field: 'approved',
					callback: (value) => {
						return value;
					}
				},
				'Rechazados': {
					field: 'rejected',
					callback: (value) => {
						return value;
					}
				},
				'Completados': {
					field: 'completed',
					callback: (value) => {
						return value;
					}
				},
				'Monto Crédito': {
					field: 'credit_value',
					callback: (value) => {
						return this.$options.filters.currency(value, 'export');
					}
				},
				'Capital Abonado': {
					field: 'capital_value',
					callback: (value) => {
						return this.$options.filters.currency(value, 'export');
					}
				},
				'Interés abonado': {
					field: 'interest_value',
					callback: (value) => {
						return this.$options.filters.currency(value, 'export');
					}
				},
			}
		};
	},
	methods: {
		listReportGeneralClient() {
			let data = {
				document: this.search_client_document,
				results: this.search_results
			}
			axios
				.get(
					`api/reports/general-client`,
					{
						params: data,
						headers: this.$root.config.headers,
					}
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
