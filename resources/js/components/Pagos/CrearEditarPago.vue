<template>
  <div>
    <div
      class="modal fade"
      id="formPagoModal"
      tabindex="-1"
      aria-labelledby="formPagoModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formPagoModalLabel">Modal pagos</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              @click="editar = false"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="tipo_deuda">Tipo Deuda</label>
                  <input
                    type="number"
                    class="form-control"
                    id="tipo_deuda"
                    v-model="formPago.tipo_deuda"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="id_deuda">Id deuda</label>
                  <input
                    type="number"
                    class="form-control"
                    id="id_deuda"
                    v-model="formPago.id_deuda"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="valor_pago">Valor pago</label>
                  <input
                    type="number"
                    class="form-control"
                    id="valor_pago"
                    v-model="formPago.valor_pago"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="nro_cuota">Nro installment</label>
                  <input
                    type="number"
                    class="form-control"
                    id="nro_cuota"
                    v-model="formPago.nro_cuota"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="interest_value">Valor interest</label>
                  <input
                    type="number"
                    class="form-control"
                    id="interest_value"
                    v-model="formPago.interest_value"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="capital_value">Valor capital</label>
                  <input
                    type="number"
                    class="form-control"
                    id="capital_value"
                    v-model="formPago.capital_value"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="id_tercero">Id tercero</label>
                  <input
                    type="number"
                    class="form-control"
                    id="id_tercero"
                    v-model="formPago.id_tercero"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="payment_date">Fecha pago</label>
                  <input
                    type="number"
                    class="form-control"
                    id="payment_date"
                    v-model="formPago.payment_date"
                  />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="editar = false"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary rounded"
              @click="editar ? editarPago() : crearPago()"
            >
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editar: false,
      formPago: {
        //
      },
    };
  },
  // Function crearPagos
  methods: {
    crearPago() {
      let me = this;
      axios.post("api/pagos", this.formPago).then(function () {
        $("#formPagoModal").modal("hide");
        me.formPago = {};
        this.$emit("listar-pagos");
      });
    },
    abirEditarPago(pago) {
      this.editar = true;
      let me = this;
      $("#formPagoModal").modal("show");
      me.formPago = pago;
    },
    editarPago() {
      let me = this;
      axios
        .put("api/pagos/" + this.formPago.id, this.formPago)
        .then(function () {
          $("#formPagoModal").modal("hide");
          me.formPago = {};
        });
      this.$emit("listar-pagos");

      this.editar = false;
    },
  },
};
</script>