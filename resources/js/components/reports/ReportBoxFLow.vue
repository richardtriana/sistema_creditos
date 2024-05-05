<template>
  <div class="page">
    <div class="page-header">
      <h3>Reporte de arqueo</h3>
    </div>
    <div class="page-search p-4 border my-2">
      <form class="form-row">
        <div class="form-group col-md-4 ml-auto">
          <label for="">Desde:</label>
          <input type="date" id="search_from" name="search_from" class="form-control" placeholder="Desde"
            v-model="search_from" :max="now" />
        </div>
        <div class="form-group col-md-4 mr-auto">
          <label for="">Hasta:</label>
          <input type="date" id="search_to" name="search_to" class="form-control" placeholder="Desde"
            v-model="search_to" :max="now" />
        </div>
        <div class="form-group col-md-4 mr-auto">
          <label for="">Estado:</label>
          <select class="form-control" id="search_status" v-model="search_status">
            <option value="">--Seleccionar--</option>
            <option value="Correct">Correcto</option>
            <option value="Incorrect">Incorrecto</option>
          </select>
        </div>
        <div class="form-group col-md-4 mr-auto">
          <label for="">Descripción:</label>
          <input type="text" id="search_description" name="search_description" class="form-control"
            placeholder="Descripción" v-model="search_description" />
        </div>
        <div class="form-group col-md-4">
          <label for="search_headquarter_id" class=" w-100 form-label">Sede
          </label>
          <v-select label="headquarter" class="w-100" v-model="search_headquarter_id" :reduce="(option) => option.id"
            :filterable="false" :options="listHeadquarters" @search="onSearchHeadquarter">
            <template slot="no-options">
              Escribe para iniciar la búsqueda
            </template>
            <template slot="option" slot-scope="option">
              <div class="d-center">
                {{ option.headquarter }}
              </div>
            </template>
            <template slot="selected-option" slot-scope="option">
              <div class="selected d-center">
                {{ option.headquarter }}
              </div>
            </template>
          </v-select>
        </div>
        <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
          <label for="">Mostrar {{ search_results }} resultados por página:</label>
          <input type="number" id="search_results" name="search_results" class="form-control"
            placeholder="Resultados por página" v-model="search_results" max="1000" />
        </div>
        <div class="form-group  offset-md-8  col-lg-2  col-md-4">
          <download-excel class="btn btn-primary w-100" :fields="json_fields" :data="ReportBoxFlowList.data"
            name="report-portfolio.xls" type="xls">
            <i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
          </download-excel>
        </div>
        <div class="form-group col-lg-2  col-md-4 text-sm-right">
          <button class="btn btn-success w-100 " type="button" @click="listReportBoxFlow()">
            <i class="bi bi-search"></i> Buscar
          </button>
        </div>

      </form>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th>ID</th>
              <th>Fecha</th>
              <th>Sede</th>
              <th>Descripción</th>
              <th>Valor</th>
              <th>Efectivo</th>
              <th>Consignacion cliente</th>
              <th>Pago a proveedores</th>
              <th>Estado</th>
              <th>Observaciones</th>
            </tr>
          </thead>
          <tbody v-if="ReportBoxFlowList.data">
            <tr v-for="(report) in ReportBoxFlowList.data" :key="report.id">
              <td>{{ report.id }}</td>
              <td>{{ report.date }}</td>
              <td class="text-left">{{ report.headquarter }}</td>
              <td class="text-left">{{ report.description }}</td>
              <td class="text-right">{{ report.value | currency }}</td>
              <td class="text-right">{{ report.cash | currency }}</td>
              <td class="text-right">{{ report.consignment_to_client | currency }}</td>
              <td class="text-right">{{ report.payment_to_provider | currency }}</td>
              <td class="text-right">{{ report.status }}</td>
              <td class="text-justify">{{ report.observations }}</td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="ReportBoxFlowList" :limit="2" @pagination-change-page="listReportBoxFlow">
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
      ReportBoxFlowList: {},
      listHeadquarters: [],
      now: moment().format('YYYY-MM-DD'),
      search_from: "",
      search_to: "",
      search_status: "",
      search_results: "15",
      search_headquarter_id: "",
      search_description: "",
      json_fields: {
        'ID': {
          field: 'id',
          callback: (value) => {
            return value;
          }
        },
        'Fecha': {
          field: 'date',
          callback: (value) => {
            return value;
          }
        },
        'Sede': {
          field: 'headquarter',
          callback: (value) => {
            return value;
          }
        },
        'Descripción': {
          field: 'description',
          callback: (value) => value
        },
        'Valor': {
          field: 'value',
          callback: (value) => {
            return this.$options.filters.currency(value, 'export');
          }
        },
        'Efectivo': {
          field: 'cash',
          callback: (value) => {
            return this.$options.filters.currency(value, 'export');
          }
        },
        'Consignacion cliente': {
          field: 'consignment_to_client',
          callback: (value) => {
            return this.$options.filters.currency(value, 'export');
          }
        },
        'Pago a proveedores': {
          field: 'payment_to_provider',
          callback: (value) => {
            return this.$options.filters.currency(value, 'export');
          }
        },
        'Estado': {
          field: 'status',
          callback: (value) => {
            return value;
          }
        },
        'Observaciones': {
          field: 'observations',
          callback: (value) => {
            return value;
          }
        },

      }
    };
  },
  methods: {
    listReportBoxFlow(page = 1) {
      let data = {
        page: page,
        from: this.search_from,
        to: this.search_to,
        status: this.search_status,
        headquarter_id: this.search_headquarter_id,
        results: this.search_results,
        description: this.search_description,
      };

      axios
        .get(`api/reports/box-flow`, {
          params: data,
          headers: this.$root.config.headers,
        })
        .then((response) => {
          this.ReportBoxFlowList = response.data.boxes;
        });
    },
    onSearchHeadquarter(search, loading) {
      if (search.length) {
        loading(true);
        axios.get(`api/headquarters/list-headquarter?headquarter=${search}&page=1`, this.$root.config)
          .then((response) => {
            this.listHeadquarters = (response.data);
            loading(false)
          })
          .catch(e => console.log(e))
      }
    },
  },

  mounted() {
    this.listReportBoxFlow();
  },
};
</script>
