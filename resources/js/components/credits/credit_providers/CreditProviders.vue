<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Créditos con proveedores</h3>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th># Crédito</th>
              <!-- <th>Cliente</th> -->
              <th>Proveedor</th>
              <th>Valor crédito</th>
              <th>Saldo abonado</th>
              <th>Saldo pendiente</th>
              <th>Historial de cambios</th>
              <th>Abonar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in creditProvidersList.data" :key="c.id">
              <td># {{ c.credit_id }}</td>
              <!-- <td>
                {{ c.client_id }}
              </td> -->
              <td>
                {{ c.provider.business_name }}  
              </td>
              <td class="text-right">
                {{ c.credit_value | currency }}
              </td>
              <td class="text-right">
                {{ c.paid_value | currency }}
              </td>
              <td class="text-right">
                {{ c.pending_value | currency }}
              </td>
              <td class="text-right">
                <button
                  class="btn btn-outline-primary"
                  data-toggle="modal"
                  data-target="#historyCreditProviderModal"
                  @click="showHistoryCredit(c.history)"
                >
                  <i class="bi bi-clock-history"></i>
                </button>
              </td>
              <td class="text-right">
                <button
                  class="btn btn-outline-success"
                  data-toggle="modal"
                  data-target="#payCreditProviderModal"
                  @click="payCreditProvider(c)"
                >
                  <i class="bi bi-currency-dollar"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </section>
    </div>
    <show-history-credit-provider ref="ShowHistoryCreditProvider" />
    <pay-credit-provider ref="PayCreditProvider" />
  </div>
</template>

<script>
import PayCreditProvider from "./PayCreditProvider.vue";
import ShowHistoryCreditProvider from "./ShowHistoryCreditProvider.vue";
export default {
  components: { ShowHistoryCreditProvider, PayCreditProvider },
  name: 'credit-providers',
  data() {
    return {
      creditProvidersList: {},
    };
  },
  methods: {
    listCreditProviders() {
      axios.get(`api/credit-providers`).then((response) => {
        this.creditProvidersList = response.data;
      });
    },
    showHistoryCredit(history) {
      this.$refs.ShowHistoryCreditProvider.convertStringToJson(history);
    },
    payCreditProvider(credit_provider) {
      this.$refs.PayCreditProvider.showCreditProvider(credit_provider);
    },
  },
  mounted() {
    this.listCreditProviders();
  },
};
</script>