<template>
  <div
    class="modal fade"
    id="creditInformationModal"
    tabindex="-1"
    aria-labelledby="creditInformationModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="creditInformationModalLabel">
            Información general crédito
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
          <section class="table-responsive">
            <table class="table table-bordered table-condensed">
              <tr>
                <td>Cliente</td>
                <td>Identificacion</td>
                <td>Contacto</td>
              </tr>
              <tr>
                <td>#Crédito</td>
                <td>Valor</td>
                <td>#Cuotas</td>
              </tr>
              <tr>
                <td>Codeudor</td>
                <td>Identificacion</td>
                <td>Contacto</td>
              </tr>
              <tr>
                <td>Proveedor</td>
                <td>Identificacion</td>
                <td>Contacto</td>
              </tr>
            </table>
          </section>
          <section>

          </section>
          <section>
            <installment ref="Installment" />
          </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
          </button>
          <button type="button" class="btn btn-success">Aprobar</button>
          <button type="button" class="btn btn-danger">Rechazar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Installment from "../credit_helpers/Installment.vue";
export default {
  components: {
    Installment,
  },
  methods: {
    showInformation(credit_id) {
      this.showInstallments(credit_id)
    },
    showInstallments(credit_id) {
      this.$refs.Installment.listCreditInstallments(credit_id, 0);
    },
  },
};
</script>

<style>
</style>
Installment