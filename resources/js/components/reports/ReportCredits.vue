<template>
  <div class="page">
    <div class="page-header">
      <h3>Reporte de clientes</h3>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Cliente</th>
              <th>Valor crédito</th>
              <th>Fecha de pago cuota</th>
              <th>Estado</th>
              <th>Nro. Cuota</th>
            </tr>
          </thead>
          <tbody v-if="reportCreditsList.data">
            <tr v-for="report in reportCreditsList.data" :key="report.id">
              <td>{{ report.credit_id }}</td>
              <td>{{ report.name }} {{ report.last_name }}</td>
              <td class="text-right">{{ report.credit_value | currency }}</td>
              <td class="text-center">{{ report.payment_date }}</td>
              <td>
                <span class="text-success" v-if="report.payment_date > now"
                  >A tiempo</span
                >
                <span class="text-danger" v-if="report.payment_date <= now"
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
          :data="reportCreditsList"
          :limit="2"
          @pagination-change-page="listReportCredits"
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
      reportCreditsList: {},
      now: new Date().toISOString().slice(0, 10),
    };
  },
  methods: {
    listReportCredits(page=1) {
      axios.get(`api/reports/credits?page=${page}`).then((response) => {
        this.reportCreditsList = response.data;
      });
    },
  },
  mounted() {
    this.listReportCredits();
  },
};
</script>
