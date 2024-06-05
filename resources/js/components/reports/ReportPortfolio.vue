<template>
  <div class="page">
    <div class="page-header row">
      <div class=" col-6 col-sm-12 col-md-6 col-lg-6">
        <h3 class=" col-6 col-sm-12 col-md-6 col-lg-6">Reporte de cartera</h3>
        <div class=" col-6 col-sm-12 col-md-6 col-lg-6">
          <h1 class="h1 text-success">
            N° Creditos {{ ReportPortfolioTotal.nro_credits }} 
          </h1>
        </div>
      </div>
      <ul class="list-group  col-6 col-sm-12 col-md-6 col-lg-6">
        <li class="list-group-item">
          <h5 class="text-dark font-weight-bold">Total cuotas: {{ ReportPortfolioTotal.value | currency }}</h5>
        </li>
        <li class="list-group-item">
          <h5 class="text-dark font-weight-bold">Total interés: {{ ReportPortfolioTotal.interest_value | currency }}</h5>
        </li>
        <li class="list-group-item">
          <h5 class="text-dark font-weight-bold">Total capital: {{ ReportPortfolioTotal.capital_value | currency }}</h5>
        </li>
      </ul>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form>
        <div class="form-row">
          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <label for="search_client">Buscar...</label>
            <input type="text" id="search_client" name="search_client" class="form-control"
              placeholder="Nombres | Documento" @keypress="listReportPortfolio(1)" v-model="search_client" />
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <label for="headquarter_id">Sede</label>
            <v-select :options="headquarterList" label="headquarter" aria-logname="{}"
              :reduce="(headquarter) => headquarter.id" v-model="search_headquarter_id" placeholder="Seleccionar sede">
            </v-select>
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
            <label for="search_status">Estado vencimiento:</label>
            <select name="search_status" id="search_status" v-model="search_status" class="custom-select">
              <option value="all">Todos</option>
              <option value="expired">Vencido</option>
              <option value="now">Vence hoy</option>
              <option value="dueSoon">Próximos a vencer</option>
            </select>
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
            <label for="">Desde:</label>
            <input type="date" id="search_from" name="search_from" class="form-control" placeholder="Desde"
              v-model="search_from" :max="now" />
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
            <label for="">Hasta:</label>
            <input type="date" id="search_to" name="search_to" class="form-control" placeholder="Desde"
              v-model="search_to" :max="now" />
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
            <label for="">Mostrar {{ search_results }} resultados por página:</label>
            <input type="number" id="search_results" name="search_results" class="form-control" placeholder="Desde"
              v-model="search_results" max="1000" />
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
            <button class="btn btn-success w-100 mt-5" type="button" @click="listReportPortfolio(1)">
              <i class="bi bi-search"></i> Buscar
            </button>
          </div>
        </div>
        <div class="form-row text-right m-auto">
          <div class="form-group offset-md-8 col-md-4">
            <download-excel class="btn btn-primary w-100" :fields="json_fields" :data="ReportPortfolioList.data"
              name="report-portfolio.xls" type="xls">
              <i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
            </download-excel>
          </div>
        </div>
      </form>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th>#</th>
              <th>Cliente</th>
              <th>Sede</th>
              <th>Contacto <i class="bi bi-telephone"></i></th>
              <th>Descripción</th>
              <th>Valor cuota</th>
              <th>Fecha de pago cuota</th>
              <th>Estado</th>
              <th>Nro. Cuota</th>
              <th>Compromiso de pago</th>
            </tr>
          </thead>
          <tbody v-if="ReportPortfolioList.data">
            <tr v-for="report in ReportPortfolioList.data" :key="report.id">
              <td>{{ report.credit_id }}</td>
              <td>{{ report.name }} {{ report.last_name }}</td>
              <td class="text-uppercase">{{ report.headquarter }}</td>
              <td>
                <a v-if="report.phone_1 != null" target="_blank"
                  :href="`https://wa.me/57${report.phone_1}?text=${infoCompany.whatsapp_msg}`"><i
                    class="bi bi-whatsapp"></i> {{ report.phone_1 }}</a>
                <br />
                <a v-if="report.phone_2 != null" target="_blank"
                  :href="`https://wa.me/57${report.phone_2}?text=${infoCompany.whatsapp_msg}`"><i
                    class="bi bi-whatsapp"></i> {{ report.phone_2 }}</a>
              </td>
              <td>{{ report.credit.description }}</td>
              <td class="text-right">{{ report.value | currency }}</td>
              <td class="text-center font-weight-bold">
                <span class="badge badge-md badge-pill badge-success" v-if="report.payment_date > now">{{
                  report.payment_date
                }}</span>
                <span class="badge badge-md badge-pill badge-warning" v-if="report.payment_date == now">{{
                  report.payment_date
                }}</span>
                <span class="badge badge-md badge-pill badge-danger" v-if="report.payment_date < now">{{
                  report.payment_date
                }}</span>
              </td>
              <td class="text-center">
                <span class="
                    badge badge-md
                    font-weight-bold
                    badge-pill badge-success
                  " v-if="report.payment_date > now">Próximo a vencer</span>
                <span class="
                    badge badge-md
                    font-weight-bold
                    badge-pill badge-warning
                  " v-if="report.payment_date == now">Vence hoy</span>
                <span class="
                    badge badge-md
                    font-weight-bold
                    badge-pill badge-danger
                  " v-if="report.payment_date < now">En mora</span>
              </td>
              <td>
                {{ report.installment_number }}
              </td>
              <td>
                <template>
                  <div>
                    <textarea class="form-control mb-1" v-model="report.payment_commitment"
                      placeholder="Anotaciones de promesa de pago"></textarea> <br>
                    <button type="button" v-if="report.payment_commitment" class="btn btn-success"
                      @click="sendPaymentCommitment(report)">Enviar</button>
                  </div>
                </template>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="ReportPortfolioList" :limit="2"
          @pagination-change-page="listReportPortfolio">
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
      ReportPortfolioList: {},
      ReportPortfolioTotal: {},
      headquarterList: [],
      now: moment().format('YYYY-MM-DD'),
      infoCompany: {},
      search_from: "",
      search_to: "",
      search_status: "all",
      search_headquarter_id: 'all',
      search_results: 15,
      search_client: "",

      json_fields: {
        'ID crédito': {
          field: 'credit_id',
          callback: (value) => {
            return value;
          }
        },
        'Cliente': {
          callback: (value) => {
            let name = value.name;
            let last_name = value.last_name
            return `${last_name} ${name}`;
          }
        },
        'Sede': {
          field: 'headquarter',
          callback: (value) => {
            return value;
          }
        },
        'Descripción': {
          field: 'credit.description',
          callback: (value) => value
        },
        'Contacto 1': {
          field: 'phone_1',
          callback: (value) => {
            return value;
          }
        },
        'Contacto 2': {
          field: 'phone_2',
          callback: (value) => {
            return value;
          }
        },
        'Valor cuota': {
          field: 'value',
          callback: (value) => {
            return this.$options.filters.currency(value, 'export');
          }
        },
        'Nro. Cuota': {
          field: 'installment_number',
          callback: (value) => {
            return value;
          }
        },
        'Fecha de pago': {
          field: 'payment_date',
          callback: (value) => {
            return value;
          }
        },
        'Compromiso de pago': {
          field: 'payment_commitment',
          callback: (value) => {
            return value;
          }
        },
        'Estado': {
          field: 'payment_date',
          callback: (value) => {
            let now = moment().format('YYYY-MM-DD')

            if (value > now) {
              return 'Próximo a vencer'
            }
            if (value == now) {
              return 'Vence hoy'
            }
            if (value < now) {
              return 'En mora'
            }
          }
        },
      }
    };
  },
  methods: {
    listReportPortfolio(page = 1) {
      let data = {
        page: page,
        from: this.search_from,
        to: this.search_to,
        status: this.search_status,
        headquarter_id: this.search_headquarter_id,
        results: this.search_results,
        search_client: this.search_client

      };

      axios
        .get(`api/reports/portfolio`, {
          params: data,
          headers: this.$root.config.headers,
        })
        .then((response) => {
          this.ReportPortfolioList = response.data.installments;
          this.ReportPortfolioTotal = response.data.totals;
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

    getCompanyInformation() {
      axios.get("api/configurations", this.$root.config).then((response) => {
        if (response.data.company) {
          this.infoCompany = response.data.company;
        }
      });
    },
    sendPaymentCommitment(quote) {
      console.log(quote)
      let data = {
        'payment_commitment': quote.payment_commitment
      }
      axios
        .post(
          `api/installment/send-payment-commitment/${quote.id}`,
          data,
          this.$root.config
        )
        .then(function (response) {
          // handle success
          Swal.fire({
            icon: "success",
            title: ".",
            text: "La operación se ha realizado correctamente",
          });
        })
        .catch(function (error) {
          // handle error
          if (error) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "No se pudo realizar esta operación",
            });
          }
        })
        .finally(this.listReportPortfolio());
    }
  },

  mounted() {
    this.listReportPortfolio();
    this.listHeadquarters();
    this.getCompanyInformation();
  },
};
</script>
