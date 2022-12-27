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
          <th v-if="allow_payment">Pago a cuota</th>
          <th v-if="allow_payment">Abono a cuota</th>
          <th v-if="$root.validatePermission('installment-reverse')">Reversar <br /> Pago</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="quote in listInstallments" :key="quote.id">
          <th>{{ quote.payment_date }}</th>
          <td>{{ quote.installment_number }}</td>
          <th class="text-right font-weight-bold">
            <span class="text-danger">{{ quote.value_pending | currency }}</span> <br>
            <span class="text-dark small">{{ quote.value | currency }}</span>
          </th>
          <td class="text-right">
            <span class="text-danger">{{ quote.capital_value_pending | currency }}</span> <br>
            <span class="text-dark small">{{ quote.capital_value | currency }}</span>
          </td>
          <td class="text-right">
            <span class="text-danger">{{ quote.interest_value_pending | currency }}</span> <br>
            <span class="text-dark small">{{ quote.interest_value | currency }}</span>
          </td>
          <td class="text-right">
            {{ quote.capital_balance | currency }}
          </td>
          <td v-if="allow_payment" class="text-right">
            <span class="text-danger" >{{ quote.late_interests_value_pending | currency }}<br></span> 
            <span class="text-dark small"  v-if="quote.days_past_due">{{ quote.late_interests_value | currency }}</span>
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
            <span v-if="quote.status == 0" class="badge badge-pill badge-warning">Pendiente</span>
            <span v-if="quote.status == 1" class="badge badge-pill badge-success">Pagado</span>
          </td>
          <td v-if="allow_payment">
            <button @click="payInstallment(quote)" type="button" class="btn btn-sm btn-success"
              v-if="quote.status == 0">
              Pagar
            </button>
            <button v-else class="btn btn-sm btn-secondary" disabled>
              Pagar
            </button>
          </td>
          <td>
            <div class="input-group mb-3" v-if="quote.status == 0">
              <input type="number" :min="0" v-model="quote.add_payment" :max="quote.value" step="any"
                class="form-control form-control-sm" placeholder="Valor" aria-label="Valor"
                aria-describedby="pay-button">
              <div class="input-group-append">
                <button class="btn btn-outline-success btn-sm" @click="payInstallment(quote, quote.add_payment)"
                  type="button" id="pay-button">Abonar</button>
              </div>
            </div>
          </td>
          <td v-if="$root.validatePermission('installment-reverse')">
            <button class="btn btn-warning" @click="reversePayment(quote)">
              <i class="bi bi-arrow-counterclockwise"></i>
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

    payInstallment(quote, amount = null) {

      let late_interests_value = this.listInstallments.map(x => { if (x.late_interests_value > 0) { return x } })

      let me = this;
      var data = {
        amount: amount ? amount : quote.value_pending,
        quote_id: quote.id,
        late_interest_pending: late_interests_value[0]['late_interests_value_pending']
      }

      if (quote.value > 0) {
        axios
          .post(
            `api/installment/${quote.credit_id}/pay-installment`,
            data,
            me.$root.config
          )
          .then(function (response) {
            //me.printEntryPdf(response.data.entry_id);
            Swal.fire({
              icon: "success",
              title: ".",
              text: "La operación se ha realizado correctamente",
            });
          })
          .catch(function (error) {
            console.log('error', error)
            // handle error
            if (error) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No se pudo realizar esta operación",
              });
            }
          })
          .finally(
            me.listCreditInstallments(me.id_credit, 1)
          )
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "El valor debe ser mayor a 0 ",
        });
      }
    },
    printEntryPdf: async function (entry_id) {
      try {
        const resp = await axios
          .get(`api/entries/show-entry/${entry_id}`, this.$root.config)
          .then((response) => {
            const pdf = response.data.pdf;
            var a = document.createElement("a");
            a.href = "data:application/pdf;base64," + pdf;
            a.download = `entrada_${entry_id}-${Date.now()}.pdf`;
            a.target = "_blank";
            a.click();
          });
      } catch (error) {
        console.log(error)
      }
    },
    reversePayment(quote) {
      axios.post(`api/installment/reverse-payment/${quote.id}`, null, this.$root.config)
        .then(function (response) {
          // handle success
          Swal.fire({
            icon: "success",
            title: ".",
            text: "La operación se ha realizado correctamente",
          });

        })
        .catch(function (error) {
          // handle error
          if (error) {
            Swal.fire({
              icon: "error",
              title: "Oops...",
              text: "No se pudo realizar esta operación",
            });
          }
        })
        .finally(
          this.listCreditInstallments(this.id_credit, 1)
        )
    }
  },
};
</script>
