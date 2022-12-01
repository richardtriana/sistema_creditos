<template>
  <div class="modal fade" id="payCreditProviderModal" tabindex="-1" aria-labelledby="payCreditProviderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="payCreditProviderModalLabel">
            Realizar abono a crédito
          </h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="form_payCreditProvider">
            <div class="form-group">
              <label for="credit_value">Valor crédito</label>
              <input type="number" step="any" class="form-control" id="credit_value"
                v-model="formCreditProvider.credit_value" disabled readonly />
            </div>
            <div class="form-group">
              <label for="pending_value">Valor pendiente</label>
              <input type="number" step="any" class="form-control" id="pending_value"
                v-model="formCreditProvider.pending_value" placeholder="$ 0" disabled readonly />
            </div>
            <div class="form-group">
              <label for="add_amount">Abonar: <b>{{ add_amount | currency }} </b></label>
              <input type="number" step="any" class="form-control" id="add_amount" placeholder="$ 0"
                v-model="add_amount" :max="root_data.current_balance_main_box" @keyup="
                  add_amount > formCreditProvider.pending_value &&
                    formCreditProvider.pending_value <
                    root_data.current_balance_main_box
                    ? (add_amount = formCreditProvider.pending_value)
                    : (add_amount = add_amount)
                " />
              <small id="addAmountHelpBlock" class="form-text text-muted">
                Monto máximo
                {{ root_data.current_balance_main_box | currency }}
              </small>
              <small id="amount_help" class="form-text text-danger">{{
                  formErrors.amount
              }}</small>
            </div>
            <div class="form-group">
              <label for="description">Descripción</label>
              <textarea type="text" class="form-control" id="description" v-model="description" name="description"
                placeholder="Ingresar descripción "></textarea>
              <small id="description_help" class="form-text text-danger">{{
                  formErrors.description
              }}</small>
            </div>
            <div class="form-group">
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text" id="inputFile">Subir evidenica</span>
                </div>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" id="evidence" name="evidence"
                    aria-describedby="inputFile" @change="(event) =>{
                      evidence_label = event.target.value
                    }">
                  <label class="custom-file-label" for="evidence">{{ evidence_label }}</label>
                </div>
              </div>
              <small id="evidence_help" class="form-text text-danger">{{
                  formErrors.evidence
              }}</small>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
          </button>
          <button type="button" class="btn btn-success" @click="payCreditProvider()">
            Abonar
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
      formCreditProvider: {},
      add_amount: 0,
      root_data: this.$root.$data,
      description: '',
      evidence: '',
      evidence_label: 'Formatos (pdf,jpeg,jpg,png)',
      formErrors: {
        amount: '',
        description: '',
        evidence: '',
      }
    };
  },
  methods: {
    showCreditProvider(credit_provider) {
      this.formCreditProvider = credit_provider;
      this.resetData();
    },
    payCreditProvider() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);

      let form = new FormData($("#form_payCreditProvider")[0]);
      form.append('amount', me.add_amount);

      axios
        .post(
          `api/credit-providers/pay-credit-provider/${this.formCreditProvider.id}`,
          form,
          this.$root.config
        )
        .then(() => {
          $("#payCreditProviderModal").modal("hide");
          form.reset();
          me.resetData();
        }).catch( response =>{
          me.$root.assignErrors(response, me.formErrors);
        });
      this.$emit('list-providers')
    },
    resetData(){
      this.add_amount = 0;
      this.description ='';
      this.evidence = '';
      this.evidence_label = 'Formatos (pdf,jpeg,jpg,png)';
    }
  },
};
</script>
