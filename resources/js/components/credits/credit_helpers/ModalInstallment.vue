<template>
  <div
    class="modal fade"
    id="cuotasModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="cuotasModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="cuotasModalLabel">Listado de Cuotas</h5>
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
          <div
            class="page-header d-flex justify-content-between p-4 border my-2"
          >
            <div class="form-row w-100">
              <!-- <div class="form-group col-2">
                <label for="amount">Monto a pagar</label>
              </div>
              <div class="form-group col-4">
                <input
                  type="number"
                  step="any"
                  class="form-control"
                  id="amount"
                  placeholder="$"
                  v-model="amount_value"
                />
              </div>
              <div class="form-group col-3">
                <button
                  class="btn btn-outline-primary my-auto"
                  @click="payCredit()"
                  v-if="amount_value > 0"
                >
                  <i class="bi bi-currency-dollar"></i> Abonar a crédito
                </button>
                <button
                  v-else
                  class="btn btn-outline-secondary my-auto"
                  disabled
                >
                  <i class="bi bi-currency-dollar"></i> Abonar a crédito
                </button>
              </div> -->
              <div class="form-group col-3">
                <button class="btn btn-outline-primary" @click="printTable()">
                  <i class="bi bi-file-pdf"></i> Tabla de amortización
                </button>
              </div>
            </div>
          </div>
          <installment ref="Installment" />
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
import Installment from "./Installment.vue";
export default {
  components: { Installment },
  data() {
    return {
      id_credit: 0,
      allow_payment: 0,
      listInstallments: [],
      listInstallmentsPaid: [],
      amount_value: 0,
      allow_payment: 1,
    };
  },
  methods: {
    listCreditInstallments: function (credit, allow_payment) {
      this.id_credit = credit;
      this.allow_payment = allow_payment;
      this.$refs.Installment.listCreditInstallments(credit, allow_payment);
    },

    payCredit() {
      var data = {
        amount: this.amount_value,
      };
      if (this.amount_value > 0) {
        axios
          .post(
            `api/credits/pay-credit-installments/${this.id_credit}`,
            data,
            this.$root.config
          )
          .then(this.listCreditInstallments(this.id_credit))
          .finally(
            this.$refs.Installment.listCreditInstallments(
              this.id_credit,
              this.allow_payment
            )
          );
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "El valor debe ser mayor a 0 ",
        });
      }
    },

    printTable() {
      axios
        .get(
          `api/credits/amortization-table?credit_id=${this.id_credit}`,
          this.$root.config
        )
        .then((response) => {
          const pdf = response.data.pdf;
          var a = document.createElement("a");
          a.href = "data:application/pdf;base64," + pdf;
          a.download = `credit_${this.id_credit}-${Date.now()}.pdf`;
          a.click();
        });
    },
  },
};
</script>
