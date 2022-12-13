<template>
  <div class="page">
    <div class="page-header">
      <h3>Reporte de flujo de caja</h3>
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
        <div class="form-group col-md-4 text-sm-center">
          <button class="btn btn-success mt-5 w-50" type="button" @click="listReportPortCashFlow()">
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
              <th>Totalizado abono</th>
              <th>Total egresos</th>
              <th>Totalizado bruto</th>
            </tr>
          </thead>
          <tbody v-if="ReportCashFlowList">
            <tr class="text-right">
              <td>{{ ReportCashFlowList.total_payment | currency }}</td>
              <td>{{ ReportCashFlowList.total_expense | currency }}</td>
              <td>{{ ReportCashFlowList.total_cash_flow | currency }}</td>
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
      ReportCashFlowList: {},
      now: moment().format('YYYY-MM-DD'),
      search_from: "",
      search_to: ""
    };
  },
  methods: {
    listReportPortCashFlow() {
      let data = {
        from: this.search_from,
        to: this.search_to
      };

      axios
        .get(`api/reports/cash-flow`, {
          params: data,
          headers: this.$root.config.headers,
        })
        .then((response) => {
          this.ReportCashFlowList = response.data;
        });
    },
  },

  mounted() {
    this.listReportPortCashFlow();
  },
};
</script>
