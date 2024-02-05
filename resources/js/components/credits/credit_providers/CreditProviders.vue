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

          <div class="form-group col-md-4">
            <label for="search_provider_id" class=" w-100 form-label">Sede
            </label>
            <v-select label="business_name" class="w-100" v-model="search_provider_id" :reduce="(option) => option.id"
              :filterable="false" :options="listProviders" @search="onSearchProvider">
              <template slot="no-options">
                Escribe para iniciar la búsqueda
              </template>
              <template slot="option" slot-scope="option">
                <div class="d-center">
                  {{ option.business_name }}
                </div>
              </template>
              <template slot="selected-option" slot-scope="option">
                <div class="selected d-center">
                  {{ option.business_name }}
                </div>
              </template>
            </v-select>
          </div>

          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <label for="">Mostrar {{ search_results }} resultados por página:</label>
            <input type="number" id="search_results" name="search_results" class="form-control" placeholder="Desde"
              v-model="search_results" max="1000" />
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
        <div class="form-row text-center m-auto">
          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <download-excel class="btn btn-primary w-100 mt-5" :fields="json_fields" :data="creditProvidersList.data"
              name="credits-providers.xls" type="xls">
              <i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
            </download-excel>
          </div>
          <div class="form-group col-md-4">
            <button class="btn btn-success w-100  mt-5" type="button" @click="listCreditProviders()">
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
              <th>Cliente</th>
              <th>Proveedor</th>
              <th>Fecha inicio crédito</th>
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
                {{ c.credit.client.name }} {{ c.credit.client.last_name }} <br>
                <small><b> {{ c.credit.client.document }}</b></small>
              </td>
              <td>
                {{ c.provider.business_name }}
              </td>
              <td> {{ c.credit.start_date }}</td>
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
                  @click="showHistoryCredit(c.history, c.credit_provider_payments)">
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
      listProviders: [],
      search_status: "-1",
      search_from: "",
      search_to: "",
      search_provider_id: "",
      search_results: 10,
      now: new Date().toISOString().slice(0, 10),

      json_fields:{
        '# Crédito': {
          field: 'credit_id',
          callback: (value) => {
            return value;
          }
        },
        'Cliente': {
          callback: (value) => {
            let name = value.credit.client.name;
            let last_name = value.credit.client.last_name
            return `${last_name} ${name}`;
          }
        },

        'Identificación': {
          callback: (value) => {
            let type = value.credit.client.type_document;
            let doc = value.credit.client.document
            return `${type} ${doc}`;
          }
        },
        'Proveedor': {
          field: 'provider.business_name',
          callback: (value) => {
            return value;
          }
        },
        'Fecha inicio crédito': {
          field: 'credit.start_date',
          callback: (value) => {
            return value;
          }
        },
        'Valor crédito': {
          field: 'credit_value',
          callback: (value) => {
            return value;
          }
        },
        'Saldo Abonado': {
          field: 'paid_value',
          callback: (value) => {
            return value;
          }
        },
        'Saldo pendiente': {
          field: 'pending_value',
          callback: (value) => {
            return value;
          }
        },
        'Historial de cambios': {
          field: 'history',
          callback: (value) => {
            return value;
          }
        },
      }
    };
  },
  methods: {
    listCreditProviders(page = 1) {

      let data = {
        'page': page,
        'search_status': this.search_status,
        'search_from': this.search_from,
        'search_to': this.search_to,
        'search_provider_id': this.search_provider_id,
        results: this.search_results,
      }

      axios.get(`api/credit-providers`, {
        params: data,
        headers: this.$root.config.headers,
      })
        .then((response) => {
          this.creditProvidersList = response.data;
        });
    },
    showHistoryCredit(history, payments) {
      this.$refs.ShowHistoryCreditProvider.listPayments(payments);
      this.$refs.ShowHistoryCreditProvider.convertStringToJson(history);
    },
    payCreditProvider(credit_provider) {
      this.$refs.PayCreditProvider.showCreditProvider(credit_provider);
    },
    onSearchProvider(search, loading) {
      if (search.length) {
        loading(true);
        axios.get(`api/providers?provider=${search}&page=1`, this.$root.config)
          .then((response) => {
            this.listProviders = (response.data.data);
            loading(false)
          })
          .catch(e => console.log(e))
      }
    },
  },
  mounted() {
    this.listCreditProviders();
  },
};
</script>