<template>
	<div>
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Ingresos</h3>
		</div>
		<div class="page-content">
			<section class="table-responsive">
				<table class="table table-bordered table-sm">
					<thead>
						<tr class="text-center">
							<th>Responsable</th>
							<th>Fecha</th>
							<th>Valor</th>
							<th>Tipo de Salida</th>
							<th>Descripción</th>
							<th>Reimprimir Ticket</th>
							<th>Ver Factura</th>
						</tr>
					</thead>
					<tbody v-if="entryList.data && entryList.data.length > 0">
						<tr v-for="e in entryList.data" :key="e.id">
							<td class="wrap">{{ e.user.name }} {{ e.user.last_name }}</td>
							<td>{{ e.date }}</td>
							<td class="text-right">{{ e.price | currency }}</td>
							<td>{{ e.type_entry }}</td>
							<td>{{ e.description }}</td>
							<td>
								<button
									class="btn btn-outline-success"
									@click="printEntryTicket(e.id)"
									type="button"
								>
									<i class="bi bi-receipt-cutoff"></i>
								</button>
							</td>
							<td>
								<button
									class="btn btn-success"
									type="button"
									@click="
										printEntryPdf(e.id, e.user.name + '_' + e.user.last_name)
									"
								>
									<i class="bi bi-eye"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="entryList"
					:limit="2"
					@pagination-change-page="listEntries"
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
			search_entry: "",
			entryList: {},
		};
	},
	mounted() {
		this.listEntries(1);
	},
	methods: {
		listEntries(page = 1) {
			let me = this;
			axios
				.get(`api/entries?page=${page}&entry=${this.search_entry}`)
				.then(function (response) {
					me.entryList = response.data;
				});
		},
		printEntryPdf(entry_id, client) {
			axios.get(`api/entries/show-entry/${entry_id}`).then((response) => {
				const pdf = response.data.pdf;
				var a = document.createElement("a");
				a.href = "data:application/pdf;base64," + pdf;
				a.download = `credit_${entry_id}-${client}.pdf`;
				a.click();
			});
		},
		printEntryTicket(id) {
			axios.get(`api/print-entry/${id}`);
		},
	},
};
</script>
