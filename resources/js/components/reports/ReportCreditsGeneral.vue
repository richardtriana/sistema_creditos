<template>
  <div class="page">
    <div class="page-header">
      <h3>Reporte general de créditos</h3>
    </div>
    <div class="page-search border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form class="">
        <div class="form-row">
          <div class="form-group col-4 ">
            <label for="search_client">Cliente: </label>
            <input type="text" id="search_client" name="search_client" class="form-control"
              placeholder="Nombres | Documento" v-model="search_client" />
          </div>
          <div class="form-group col-4 ">
            <label for="">Fecha de inicio:</label>
            <input type="date" id="search_start_date" name="search_start_date" class="form-control" placeholder="Desde"
              v-model="search_start_date" />
          </div>
          <div class="form-group col-4">
            <label for="">Fecha de finalización:</label>
            <input type="date" id="search_end_date" name="search_end_date" class="form-control" placeholder="Desde"
              v-model="search_end_date" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-4 ml-auto">
            <label for="search_status">Estado:</label>
            <select name="search_status" id="search_status" v-model="search_status" class="custom-select">
              <option value="all">Todos</option>
              <option :value="key" v-for="(status, key) in creditStatus" :key="status">
                {{ status }}
              </option>
            </select>
          </div>
          <div class="form-group col-4">
            <label for="">Desde:</label>
            <input type="date" id="search_from" name="search_from" class="form-control" placeholder="Desde"
              v-model="search_from" :max="now" />
          </div>
          <div class="form-group col-4 mr-auto">
            <label for="">Hasta:</label>
            <input type="date" id="search_to" name="search_to" class="form-control" placeholder="Desde"
              v-model="search_to" :max="now" />
          </div>
        </div>
        <div class="form-row m-auto">
          <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
            <label for="">Mostrar {{ search_results }} resultados por página:</label>
            <input type="number" id="search_results" name="search_results" class="form-control" placeholder="Desde"
              v-model="search_results" max="1000" />
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <button class="btn btn-success w-100 mt-5" type="button" @click="listReportGeneralCredits(1)">
              <i class="bi bi-search"></i> Buscar
            </button>
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <download-excel class="btn btn-primary w-100 mt-5" :fields="json_fields" :data="ReportGeneralCreditsList.data"
              name="report-general-credits.xls" type="xls">
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
              <th>Contacto <i class="bi bi-telephone"></i></th>
              <th>Sede</th>
              <th>Valor crédito</th>
              <th>Número de cuotas</th>
              <th>Fecha de inicio</th>
              <th>Fecha finalización</th>
              <th>Estado</th>
              <th>Total abonado</th>
              <th>Abono a capital</th>
              <th>Abono a intereses</th>
            </tr>
          </thead>
          <tbody v-if="ReportGeneralCreditsList.data">
            <tr v-for="report in ReportGeneralCreditsList.data" :key="report.id">
              <td class="text-right">{{ report.id }}</td>
              <td>{{ report.client.name }} {{ report.client.last_name }}</td>
              <td>
                <a v-if="report.client.phone_1 != null" target="_blank"
                  :href="`https://wa.me/57${report.client.phone_1}?text=${infoCompany.whatsapp_msg}`"><i
                    class="bi bi-whatsapp"></i> {{ report.client.phone_1 }}</a>
                <br />
                <a v-if="report.client.phone_2 != null" target="_blank"
                  :href="`https://wa.me/57${report.client.phone_2}?text=${infoCompany.whatsapp_msg}`"><i
                    class="bi bi-whatsapp"></i> {{ report.client.phone_2 }}</a>
              </td>
              <td>
                {{ report.headquarter.headquarter }}
              </td>
              <td class="text-right">{{ report.credit_value | currency }}</td>
              <td class="text-center">{{ report.number_installments }}</td>
              <td> {{ report.start_date }}</td>
              <td> {{ report.end_date }}</td>
              <td>{{ creditStatus[report.status] }}</td>
              <td class="text-right">{{ report.paid_value | currency }}</td>
              <td class="text-right">{{ report.capital_value | currency }}</td>
              <td class="text-right">{{ report.interest_value | currency }}</td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="ReportGeneralCreditsList" :limit="2"
          @pagination-change-page="listReportGeneralCredits">
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
        </pagination>
      </section>
      <section class="table-responsive">
        <h5>Totalizado:</h5>
        <table class="table table-sm table-bordered">
          <tr class="text-right">
            <th>Total Valor créditos</th>
            <td>{{ ReportTotalValues.credit_value | currency }}</td>
          </tr>
          <tr class="text-right">
            <th>Total abonado</th>
            <td>{{ ReportTotalValues.paid_value | currency }}</td>
          </tr>
          <tr class="text-right">
            <th>Total abono a capital</th>
            <td>{{ ReportTotalValues.capital_value | currency }}</td>
          </tr>
          <tr class="text-right">
            <th>Total abonado a intereses</th>
            <td>{{ ReportTotalValues.interest_value | currency }}</td>
          </tr>
          <tr class="text-right">
            <th class="font-weight-bold h4">Total saldo actual</th>
            <td class="h4">{{ (ReportTotalValues.credit_value - ReportTotalValues.paid_value) | currency }}</td>
          </tr>
        </table>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      ReportGeneralCreditsList: {},
      ReportTotalValues: {},
      infoCompany: {},
      creditStatus: {
        0: "Pendiente",
        1: "Aprobado",
        2: "Rechazado",
        3: "Pendiente pago a proveedor",
        4: "Completado",
        5: "Cobro jurídico",
      },
      now: new Date().toISOString().slice(0, 10),
      search_from: "",
      search_to: "",
      search_status: "all",
      search_start_date: '',
      search_end_date: '',
      search_client: '',
      search_results: 15,

      json_fields: {
        'Cliente': {
          callback: (value) => {
            let name = value.client.name;
            let last_name = value.client.last_name
            return `${last_name} ${name}`;
          }
        },

        'Sede': {
          field: 'headquarter.headquarter',
          callback: (value) => {
            return value;
          }
        },
        'Contacto 1': {
          field: 'client.phone_1',
          callback: (value) => {
            return value;
          }
        },
        'Contacto 2': {
          field: 'client.phone_2',
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
        'Nro. Cuotas': {
          field: 'number_installments',
          callback: (value) => {
            return value;
          }
        },
        'Fecha de inicio': {
          field: 'start_date',
          callback: (value) => {
            return value;
          }
        },
        'Fecha de finalizacion': {
          field: 'end_date',
          callback: (value) => {
            return value;
          }
        },
        'Estado': {
          field: 'status',
          callback: (value) => {
            if (value == 0) {
              return 'Pendiente'
            }
            if (value == 1) {
              return 'Aprobado'
            }
            if (value == 2) {
              return 'Rechazado'
            }
            if (value == 3) {
              return 'Pendiente pago a proveedor'
            }
            if (value == 4) {
              return 'Completado'
            }
            if (value == 5) {
              return 'Cobro jurídico'
            }
          }
        },
        'Total abonado': {
          field: 'paid_value',
          callback: (value) => {
            return value;
          }
        },
        'Abono a capital': {
          field: 'capital_value',
          callback: (value) => {
            return value;
          }
        },
        'Abono a intereses': {
          field: 'interest_value',
          callback: (value) => {
            return value;
          }
        },
      }
    };
  },
  methods: {
    listReportGeneralCredits(page = 1) {
      let data = {
        page: page,
        from: this.search_from,
        to: this.search_to,
        status: this.search_status,
        start_date: this.search_start_date,
        end_date: this.search_end_date,
        search_client: this.search_client,
        results: this.search_results
      }
      axios
        .get(
          `api/reports/general-credits`,
          {
            params: data,
            headers: this.$root.config.headers,
          }
        )
        .then((response) => {
          this.ReportGeneralCreditsList = response.data.credits;
          this.ReportTotalValues = response.data.total_credits;
        });
    },
    getCompanyInformation() {
      axios.get("api/configurations", this.$root.config).then((response) => {
        if (response.data.company) {
          this.infoCompany = response.data.company;
        }
      });
    },
  },
  mounted() {
    this.listReportGeneralCredits();
    this.getCompanyInformation()
  },
};
</script>

<style>

</style>
