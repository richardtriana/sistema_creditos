<template>
  <div class="modal fade" id="historyBoxModal" tabindex="-1" aria-labelledby="historyBoxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="historyBoxModalLabel">
            Historial del crédito
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div v-if="listItems.data" class="table-responsive">
            <pagination :align="'center'" :data="listItems" :limit="4" @pagination-change-page="getHistoryBox">
              <span slot="prev-nav">&lt; Anterior</span>
              <span slot="next-nav">Siguiente &gt;</span>
            </pagination>
            <table class="table table-sm table-bordered">
              <thead>
                <th>Responsable</th>
                <th>Fecha</th>
                <th>Descripción</th>
                <th>Monto</th>
                <th>Efectivo</th>
                <th>Consignación cliente</th>
                <th>Pago a proveedor</th>
                <th>Estado</th>
                <th>Observaciones</th>
              </thead>
              <tbody>
                <tr v-for="(l, key) in listItems.data" :key="key">
                  <td> <b>{{ l.user }}</b></td>
                  <td>{{ l.date }}</td>
                  <td>{{ l.description }}</td>
                  <td> {{ l.value | currency }}</td>
                  <td>{{ l.cash | currency }}</td>
                  <td>{{ l.consignment_to_client | currency }}</td>
                  <td>{{ l.payment_to_provider | currency }}</td>
                  <td>
                    <span v-if="l.status == 'Correct'" class="text-success font-weight-bold uppercase">Correcto</span>
                    <span v-if="l.status == 'Incorrect'"
                      class="text-danger font-weight-bold text-uppercase">Incorrecto</span>
                  </td>
                  <td>
                    {{ l.observations }}
                  </td>
                </tr>
              </tbody>
            </table>
            <pagination :align="'center'" :data="listItems" :limit="4" @pagination-change-page="getHistoryBox">
              <span slot="prev-nav">&lt; Anterior</span>
              <span slot="next-nav">Siguiente &gt;</span>
            </pagination>
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
      id: 0
    }
  },
  methods: {
    convertStringToJson(string) {
      $('#historyBoxModal').modal('show')
      if (string) {
        const arrHistory = JSON.parse(string);
        this.listItems = arrHistory;
      } else {
        this.listItems = {};
      }
    },

    handleIdBox(boxId) {
      this.id = boxId
      this.getHistoryBox()
    },

    getHistoryBox(page = 1) {
      let data = {
        page: page
      }

      axios.get(`api/box-history/${this.id}`, {
        params: data,
        headers: this.$root.config.headers,
      })
        .then(response => {

          this.listItems = response.data.boxHistory
          console.log(this.listItems);

          $('#historyBoxModal').modal('show')

        }).catch(error => {
          console.log(error);
        })
    }
  },
};
</script>

<style></style>