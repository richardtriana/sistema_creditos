<template>
  <div
    class="modal fade"
    id="historyCreditProviderModal"
    tabindex="-1"
    aria-labelledby="historyCreditProviderModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="historyCreditProviderModalLabel">
            Historial del crédito
          </h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
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
  },
};
</script>

<style>
</style>