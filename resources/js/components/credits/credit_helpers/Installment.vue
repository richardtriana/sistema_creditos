<template>
  <section class="table-responsive">
    <table class="table table-sm table-bordered">
      <thead>
        <tr>
          <th>Fecha de vencimiento</th>
          <th>Nro. Cuota</th>
          <th>Valor</th>
          <th>Abono Capital</th>
          <th>Abono Interés</th>
          <th>Saldo capital</th>
          <th v-if="allow_payment">Mora</th>
          <th v-if="allow_payment">Dias de mora</th>
          <th v-if="allow_payment">Valor abonado</th>
          <th v-if="allow_payment">Estado</th>
          <th v-if="allow_payment"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="quote in listInstallments" :key="quote.id">
          <th>{{ quote.payment_date }}</th>
          <td>{{ quote.installment_number }}</td>
          <td class="text-right">{{ quote.value | currency }}</td>
          <td class="text-right">
            {{ quote.capital_value | currency }}
          </td>
          <td class="text-right">
            {{ quote.interest_value | currency }}
          </td>
          <td class="text-right">
            {{ quote.capital_balance | currency }}
          </td>
          <td v-if="allow_payment" class="text-right">
            {{ quote.late_interests_value | currency }}
          </td>
          <td v-if="allow_payment">{{ quote.days_past_due }}</td>
          <td v-if="allow_payment" class="text-right">
            {{ quote.paid_balance | currency }}
          </td>
          <td v-if="allow_payment">
            <span v-if="quote.status == 0" class="badge badge-secondary"
              >Pendiente</span
            >
            <span v-if="quote.status == 1" class="badge badge-success"
              >Pagado</span
            >
          </td>
          <td v-if="allow_payment">
            <button
              @click="payInstallment(quote)"
              type="button"
              class="btn btn-sm btn-success"
              v-if="quote.status == 0"
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
</template>
<script>
export default {
  data() {
    return {
      id_credit: 0,
      listInstallments: [],
      listInstallmentsPaid: [],
      amount_value: 0,
      allow_payment: 1,
    };
  },
  methods: {
    listCreditInstallments(credit_id, allow_payment) {
      this.id_credit = credit_id;
      this.allow_payment = allow_payment;

      let me = this;
      axios
        .get(`api/credits/${credit_id}/installments`, me.$root.config)
        .then(function (response) {
          me.listInstallments = response.data;
        });
    },

    payInstallment(quote) {
      let me = this;
      var data = {
        amount: quote.value,
      };

      if (quote.value > 0) {
        axios
          .post(
            `api/installment/${quote.id}/pay-installment`,
            data,
            me.$root.config
          )
          .then(function (response) {
            me.listCreditInstallments(me.id_credit, 1);
            var entry = {
              data: quote,
              value: response.data.balance,
            };

            axios.post(`api/entries`, entry, me.$root.config);
          });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "El valor debe ser mayor a 0 ",
        });
      }
    },
  },
};
</script>
