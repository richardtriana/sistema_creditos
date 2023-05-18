<template>
	<div>
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3 class="col-6">Reporte de ingresos por sede</h3>
			<ul class="list-group col-6">
				<li class="list-group-item">
					<h5 class="text-dark font-weight-bold">Total ingresos: {{ headquartersEntryTotal.price | currency }}</h5>
				</li>
			</ul>
		</div>
		<div class="page-search p-4 border my-2">
			<h6 class="text-primary text-uppercase">Filtrar:</h6>
			<form>
				<div class="form-row">
					<div class="form-group col-12 col-md-2 ml-auto">
						<label for="search_from">Desde:</label>
						<input type="date" id="search_from" name="search_from" class="form-control" placeholder="Desde"
							v-model="search_from" :max="now" />
					</div>
					<div class="form-group col-12 col-md-2 mr-auto">
						<label for="search_to">Hasta:</label>
						<input type="date" id="search_to" name="search_to" class="form-control" placeholder="Hasta"
							v-model="search_to" :max="now" />
					</div>
					<div class="form-group col-12 col-md-3 mr-auto">
						<label for="search_type_entry">Tipo de entrada:</label>
						<input type="text" id="search_type_entry" name="search_type_entry" class="form-control"
							placeholder="Tipo de salida" v-model="search_type_entry" />
					</div>
					<div class="form-group col-12 col-md-3 mr-auto">
						<label for="headquarter_id">Sede</label>
						<select class="form-control custom-selec" id="headquarter_id" v-model="search_headquarter_id">
							<option selected value="">Todas</option>
							<option v-for="headquarter in headquarterList" :key="headquarter.id" :value="headquarter.id">
								{{ headquarter.headquarter }}
							</option>
						</select>
					</div>
					<div class="form-group col-12 col-md-2 mr-auto">
						<label for="">Mostrar {{ search_results }} resultados:</label>
						<input type="number" id="search_results" name="search_results" class="form-control" placeholder="Desde"
							v-model="search_results"/>
					</div>
				</div>
				<div class="form-row text-right m-auto">
					<div class="form-group ofsset-md-4 col-md-4">
						<button class="btn btn-success w-100" type="button" @click="listHeadquartersEntries(1)">
							<i class="bi bi-search"></i> Buscar
						</button>
					</div>
					<div class="form-group col-md-4 ">
						<download-excel class="btn btn-primary w-100" :fields="json_fields" :data="headquartersEntryList.data"
							name="report-headquarters-entries.xls" type="xls">
							<i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
						</download-excel>
					</div>
				</div>
			</form>
		</div>
		<div class="page-content">
			<section class="table-responsive">
				<table class="table table-bordered table-sm">
					<thead>
						<tr class="text-center">
							<th>Sede</th>
							<th>Tipo</th>
							<th>Afectación</th>
							<th>Valor</th>
						</tr>
					</thead>
					<tbody v-if="headquartersEntryList.data &&
						headquartersEntryList.data.length > 0
						">
						<tr v-for="r in headquartersEntryList.data" :key="r.id">
							<td>{{ r.headquarter.headquarter }}</td>
							<td>{{ r.type_entry }}</td>
							<td>{{ r.description | affectation('entry') }}</td>
							<td class="text-right">{{ r.price | currency }}</td>
						</tr>
					</tbody>
				</table>
				<pagination :align="'center'" :data="headquartersEntryList" :limit="2"
					@pagination-change-page="listHeadquartersEntries">
					<span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
					<span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
				</pagination>
			</section>
		</div>
	</div>
</template>

<script>

export default {
	data() {
		return {
			headquartersEntryList: {},
			headquartersEntryTotal: {},
			search_from: "",
			search_to: "",
			search_type_entry: "",
			now: moment().format('YYYY-MM-DD'),
			json_fields: {
        'Sede': {
          field: 'headquarter.headquarter',
          callback: (value) => value
        },
				'Tipo': {
          field: 'type_entry',
          callback: (value) => value
        },
				'Afectación': {
          field: 'description',
          callback: (value) => {
            return this.$options.filters.affectation(value, 'entry');
          }
        },
        'Valor ingreso': {
          field: 'price',
          callback: (value) => {
            return this.$options.filters.currency(value, 'export');
          }
        }
			},
			search_headquarter_id: "",
			search_results: 15
		};
	},
	mounted() {
		this.listHeadquartersEntries(1);
		this.listHeadquarters();
	},
	methods: {
		listHeadquartersEntries(page = 1) {
			let me = this;

			let data = {
				page: page,
				from: this.search_from,
				to: this.search_to,
				type_entry: this.search_type_entry,
				headquarter_id: this.search_headquarter_id,
				results: this.search_results
			}

			axios
				.get(
					`api/reports/headquarters-entries`,
					{
						params: data,
						headers: this.$root.config.headers,
					}
				)
				.then(function (response) {
					me.headquartersEntryList = response.data.entries;
					me.headquartersEntryTotal = response.data.totals;
				});
		},
		listHeadquarters() {
      let me = this;
      axios
        .get(`api/headquarters/list-all-headquarters`, me.$root.config)
        .then(function (response) {
          me.headquarterList = response.data;
        });
    },
	},
};
</script>
