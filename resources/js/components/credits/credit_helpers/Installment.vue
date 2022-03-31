<template>
  <section class="table-responsive">
    <table class="table table-sm table-bordered">
      <thead>
        <tr class="text-center">
          <th>Fecha de vencimiento</th>
          <th>Nro. Cuota</th>
          <th>Valor</th>
          <th>Valor Capital</th>
          <th>Valor Interés</th>
          <th>Saldo capital</th>
          <th v-if="allow_payment">Mora</th>
          <th v-if="allow_payment">Dias de mora</th>
          <th>Valor abonado</th>
          <th>Capital abonado</th>
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
          <td v-if="allow_payment" class="text-danger">
            {{ quote.days_past_due }}
          </td>
          <td class="text-right">
            {{ quote.paid_balance | currency }}
          </td>
          <td class="text-right">
            {{ quote.paid_capital | currency }}
          </td>
          <td v-if="allow_payment">
            <span
              v-if="quote.status == 0"
              class="badge badge-pill badge-warning"
              >Pendiente</span
            >
            <span
              v-if="quote.status == 1"
              class="badge badge-pill badge-success"
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
      allow_payment: 0,
      now: new Date().getTime(),
    };
  },
  computed: {},
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

      console.log(quote.value);

      if (quote.value > 0) {
        axios
          .post(
            `api/installment/${quote.credit_id}/pay-installment`,
            data,
            me.$root.config
          )
          .then(function (response) {
            me.listCreditInstallments(me.id_credit, 1);
          });
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "El valor debe ser mayor a 0 ",
        });
      }
    },
    calculateLate(quote) {
      var payment_date = new Date(quote.payment_date).getTime();
      if (payment_date < this.now) {
        var time = this.now - payment_date;
        return Math.round(time / (1000 * 60 * 60 * 24));
      }
    },
  },
};
</script>
