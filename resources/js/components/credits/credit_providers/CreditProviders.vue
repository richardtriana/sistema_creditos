<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Pago a proveedores</h3>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form>
        <div class="form-row">

          <div class="form-group col-md-4 mr-md-auto">
            <label for="search_document">Estado:</label>
            <select name="search_status" id="search_status" v-model="search_status" class="custom-select">
              <option value="-1">Todos</option>
              <option value="0">Pendientes</option>
              <option value="1">Pagados</option>
            </select>
          </div>
      
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
        </div>
        <div class="form-row text-right m-auto">
          <div class="form-group m-md-auto col-md-4">
            <button class="btn btn-success w-100" type="button" @click="listCreditProviders()">
              <i class="bi bi-search"></i> Buscar
            </button>
          </div>
        </div>
      </form>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-sm table-bordered" v-if="$root.validatePermission('provider-index')">
          <thead>
            <tr class="text-center">
              <th># Crédito</th>
              <th>Proveedor</th>
              <th>Valor crédito</th>
              <th>Saldo abonado</th>
              <th>Saldo pendiente</th>
              <th>Historial de cambios</th>
              <th v-if="$root.validatePermission('provider-update')">Abonar</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in creditProvidersList.data" :key="c.id">
              <td># {{ c.credit_id }}</td>
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
                <button class="btn btn-primary" data-toggle="modal" data-target="#historyCreditProviderModal"
                  @click="showHistoryCredit(c.history)">
                  <i class="bi bi-clock-history"></i>
                </button>
              </td>
              <td class="text-right" v-if="$root.validatePermission('provider-update')">
                <button class="btn btn-success" data-toggle="modal" data-target="#payCreditProviderModal"
                  @click="payCreditProvider(c)">
                  <i class="bi bi-currency-dollar"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="creditProvidersList" :limit="2"
          @pagination-change-page="listCreditProviders">
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
        </pagination>
      </section>
    </div>
    <show-history-credit-provider ref="ShowHistoryCreditProvider" />
    <pay-credit-provider ref="PayCreditProvider" @list-providers="listCreditProviders()" />
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
      search_status: "-1",
      search_from: "",
      search_to: "",
      now: new Date().toISOString().slice(0, 10),
    };
  },
  methods: {
    listCreditProviders() {

      let data = {
        'search_status': this.search_status,
        'search_from': this.search_from,
        'search_to': this.search_to
      }

      axios.get(`api/credit-providers`, {
        params: data,
        headers: this.$root.config.headers,
      })
        .then((response) => {
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