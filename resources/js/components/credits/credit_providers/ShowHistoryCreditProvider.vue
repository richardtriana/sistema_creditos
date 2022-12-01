<template>
  <div class="modal fade" id="historyCreditProviderModal" tabindex="-1"
    aria-labelledby="historyCreditProviderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="historyCreditProviderModalLabel">
            Historial del crédito
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="summary-tab" data-toggle="tab" href="#summary" role="tab" aria-controls="summary"
                aria-selected="true">Resumen</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="details-tab" data-toggle="tab" href="#details" role="tab" aria-controls="details"
                aria-selected="false">Detallado</a>
            </li>
          </ul>
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="summary" role="tabpanel" aria-labelledby="summary-tab">
              <div v-if="listItems.length > 0">
                <table class="table table-sm table-bordered">
                  <tr v-for="(l, key) in listItems" :key="key">
                    <td>Asesor : {{ l.Asesor }}</td>
                    <td>Fecha : {{ l.Fecha }}</td>
                    <td>Monto : {{ l.Monto | currency }}</td>
                  </tr>
                </table>
              </div>
              <div class="card" v-else>
                <div class="card-body">No hay cambios recientes</div>
              </div>
            </div>
            <div class="tab-pane fade" id="details" role="tabpanel" aria-labelledby="details-tab">
              <div v-if="listItems.length > 0">
                <table class="table table-sm table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Asesor</th>
                      <th>Fecha</th>
                      <th>Monto</th>
                      <th>Descripción</th>
                      <th>Evidencia</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, key) in list" :key="key">
                      <td> {{( key + 1)}}</td>
                      <td>{{ item.adviser }}</td>
                      <td>{{ item.created_at }}</td>
                      <td>{{ item.paid_value | currency }}</td>
                      <td>{{ item.description }}</td>
                      <td v-if="(item.evidence != null)"><a :href="item.evidence" target="_blank">Ver <i class="bi bi-eye"></i></a></td>
                      <td v-else>Sin evidencia</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="card" v-else>
                <div class="card-body">No hay cambios recientes</div>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      listItems: {},
      list: []
    };
  },
  methods: {
    convertStringToJson(string) {
      if (string) {
        const arrHistory = JSON.parse(string);
        this.listItems = arrHistory;
      } else {
        this.listItems = {};
      }
    },
    listPayments(data) {
      this.list = data
    }
  },
};
</script>

<style>

</style>