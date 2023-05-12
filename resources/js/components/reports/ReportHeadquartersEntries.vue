<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3 class="col-6">Reporte de ingresos por sede</h3>
      <ul class="list-group col-6">
        <li class="list-group-item"><h5 class="text-dark font-weight-bold">Total ingresos: {{headquartersEntryTotal.price | currency}}</h5></li>
      </ul>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form>
        <div class="form-row">
          <div class="form-group col-4 ml-auto">
            <label for="search_from">Desde:</label>
            <input
              type="date"
              id="search_from"
              name="search_from"
              class="form-control"
              placeholder="Desde"
              v-model="search_from"
              :max="now"
            />
          </div>
          <div class="form-group col-4 mr-auto">
            <label for="search_to">Hasta:</label>
            <input
              type="date"
              id="search_to"
              name="search_to"
              class="form-control"
              placeholder="Hasta"
              v-model="search_to"
              :max="now"
            />
          </div>
          <div class="form-group col-4 mr-auto">
            <label for="search_type_entry">Tipo de entrada:</label>
            <input
              type="text"
              id="search_type_entry"
              name="search_type_entry"
              class="form-control"
              placeholder="Tipo de salida"
              v-model="search_type_entry"
            />
          </div>
        </div>
        <div class="form-row text-right m-auto">
          <div class="form-group ofsset-md-4 col-md-4">
            <button
              class="btn btn-success w-100"
              type="button"
              @click="listHeadquartersEntries(1)"
            >
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
							<th>Detalle</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody
            v-if="
              headquartersEntryList.data &&
              headquartersEntryList.data.length > 0
            "
          >
            <tr v-for="r in headquartersEntryList.data" :key="r.id">
              <td>{{ r.headquarter }}</td>
							<td>
								<ul>
									<li v-for="item in r.entry" :key="item.id"> {{  item.description }}</li>
								</ul>
							</td>
              <td class="text-right">{{ r.price | currency }}</td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="headquartersEntryList"
          :limit="2"
          @pagination-change-page="listHeadquartersEntries"
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
import {dollarFilter} from '../../filters'
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
          field: 'headquarter',
          callback: (value) => {
            return value;
          }
        },
        'Valor ingresos': {
          field: 'price',
          callback: (value) => {
            return dollarFilter(value);
          }
        }
      }
    };
  },
  mounted() {
    this.listHeadquartersEntries(1);
  },
  methods: {
    listHeadquartersEntries(page = 1) {
      let me = this;

      let data = {
        page: page,
        from: this.search_from,
        to: this.search_to,
        type_entry: this.search_type_entry
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
  },
};
</script>
