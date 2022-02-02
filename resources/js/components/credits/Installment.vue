<template>
  <div
    class="modal fade"
    id="cuotasModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="cuotasModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg" role="document">
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
              <div class="form-group col-2">
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
              </div>
              <div class="form-group col-3">
                <button class="btn btn-outline-primary" @click="printTable()">
                  <i class="bi bi-file-pdf"></i> Tabla de amortización
                </button>
              </div>
            </div>
          </div>
          <section>
            <table class="table table-sm">
              <thead>
                <tr>
                  <th>Fecha de vencimiento</th>
                  <th scope="col">Nro. Cuota</th>
                  <th scope="col">Valor</th>
                  <th scope="col">Capital</th>
                  <th>Interés</th>
                  <th>Mora</th>
                  <th>Dias de mora</th>
                  <th>Valor abonado</th>
                  <th>Estado</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="f in listInstallments" :key="f.id">
                  <th>{{ f.payment_date }}</th>
                  <td>{{ f.installment_number }}</td>
                  <td class="text-right">{{ f.value | dollar }}</td>
                  <td class="text-right">{{ f.capital_value | dollar }}</td>
                  <td class="text-right">{{ f.interest_value | dollar }}</td>
                  <td class="text-right">
                    {{ f.late_interests_value | dollar }}
                  </td>
                  <td>{{ f.days_past_due }}</td>
                  <td class="text-right">{{ f.paid_balance | dollar }}</td>
                  <td>
                    <span v-if="f.status == 0" class="badge badge-secondary"
                      >Pendiente</span
                    >
                    <span v-if="f.status == 1" class="badge badge-success"
                      >Pagado</span
                    >
                  </td>
                  <td>
                    <button
                      @click="payInstallment(f.id)"
                      type="button"
                      class="btn btn-sm btn-success"
                      v-if="f.status == 0"
                    >
                      Pagar
                    </button>

                    <button v-else class="btn btn-sm btn-secondary" disabled>
                      Pagar
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </section>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Close
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
      id_credit: 0,
      listInstallments: [],
      listInstallmentsPaid: [],
      amount_value: 0,
    };
  },
  methods: {
    listCreditInstallments(credit_id) {
      this.id_credit = credit_id;
      let me = this;
      axios
        .get(`api/credits/${credit_id}/installments`)
        .then(function (response) {
          me.listInstallments = response.data;
        });
    },

    payInstallment(id) {
      let me = this;
      axios.post(`api/installment/${id}/pay-installment`).then(function () {
        me.listCreditInstallments(me.id_credit);
      });
    },

    payCredit() {
      var data = {
        amount: this.amount_value,
      };
      axios
        .post(`api/credits/pay-credit-installments/${this.id_credit}`, data)
        .then(this.listCreditInstallments(this.id_credit));
    },

    printTable() {
      axios
        .get(`api/credits/amortization-table?credit_id=${this.id_credit}`)
        .then((response) => {
          const pdf = response.data.pdf;
          var a = document.createElement("a");
          a.href = "data:application/pdf;base64," + pdf;
          a.download = `credit_${this.id_credit}.pdf`;
          a.click();
        });
    },
  },
};
</script>
