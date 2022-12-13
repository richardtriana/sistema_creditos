<template>
  <div class="page">
    <div class="page-header">
      <h3>Reporte de rentabilidad</h3>
    </div>
    <div class="page-search p-4 border my-2">
      <form class="form-row">
        <div class="form-group col-md-4">
          <label for="">Desde:</label>
          <input type="date" id="search_from" name="search_from" class="form-control" placeholder="Desde"
            v-model="search_from" :max="now" />
        </div>
        <div class="form-group col-md-4">
          <label for="">Hasta:</label>
          <input type="date" id="search_to" name="search_to" class="form-control" placeholder="Desde"
            v-model="search_to" :max="now" />
        </div>
        <div class="form-group col-md-4 text-sm-center">
          <button class="btn btn-success mt-5 w-50" type="button" @click="listReportProfitability()">
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
              <th>Totalizado abonos de interés</th>
              <th>Total egresos</th>
              <th>Totalizado bruto</th>
            </tr>
          </thead>
          <tbody v-if="ReportProfitabilityList">
            <tr class="text-right">
              <td>{{ ReportProfitabilityList.total_credit_interest | currency }}</td>
              <td>{{ ReportProfitabilityList.total_expense | currency }}</td>
              <td>{{ ReportProfitabilityList.total_gross | currency }}</td>
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
      ReportProfitabilityList: {},
      now: moment().format('YYYY-MM-DD'),
      search_from: "",
      search_to: ""
    };
  },
  methods: {
    listReportProfitability() {
      let data = {
        from: this.search_from,
        to: this.search_to
      };

      axios
        .get(`api/reports/profitability`, {
          params: data,
          headers: this.$root.config.headers,
        })
        .then((response) => {
          this.ReportProfitabilityList = response.data;
        });
    },
  },

  mounted() {
    this.listReportProfitability();
  },
};
</script>
