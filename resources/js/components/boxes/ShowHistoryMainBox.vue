<template>
  <div
    class="modal fade"
    id="historyMainBoxModal"
    tabindex="-1"
    aria-labelledby="historyMainBoxModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="historyMainBoxModalLabel">
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
                <td>Responsable: <b>{{ l.user }}</b></td>
                <td>Fecha: {{ l.date }}</td>
                <td>Descripción: {{ l.description }}</td>
                <td>Monto: {{ l.value | currency }}</td>
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
    }
  },
  methods: {
    convertStringToJson(string) {
      $('#historyMainBoxModal').modal('show')
      if (string) {
        const arrHistory = JSON.parse(string);
        this.listItems = arrHistory;
      } else {
        this.listItems = {};
      }
    },
    getHistoryMainBox(mainBoxId){
      axios.get(`api/main-box-history/${mainBoxId}`, this.$root.config)
      .then(response => {        
        console.log(response);
        this.listItems = response.data
        $('#historyMainBoxModal').modal('show')

      }).catch(error =>{
        console.log(error);
      })
    }
  }
};
</script>

<style>
</style>