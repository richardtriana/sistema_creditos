<template>
  <div>
    <div class="modal fade" id="formCreditModal" tabindex="-1" aria-labelledby="formCreditModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formCreditModalLabel">Creditos</h5>
            <button type="button" class="close" data-dismiss="modal" @click="(edit = false), resetData()"
              aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <div class="">
                    <label for="client_id" class="col-form-label">Cliente</label>
                    <button class="btn btn-primary mb-2" type="button" data-toggle="modal"
                      data-target="#addClientModal">
                      <i class="bi bi-card-checklist"></i> Añadir cliente
                    </button>
                  </div>
                  <input type="text" class="form-control" disabled readonly v-model="client_name"
                    :class="[formErrors.client_id ? 'is-invalid' : '']" />
                  <small id="client_id_help" class="form-text text-danger">{{
                  formErrors.client_id
                  }}</small>
                </div>

                <div class="
                    form-group
                    col-md-3
                    form-check
                    d-flex
                    align-items-center
                    pl-md-5
                  ">
                  <input type="checkbox" class="form-check-input" id="provider" v-model="formCredit.provider" />
                  <label class="form-check-label" for="provider">¿Usa Proveedor?</label>
                </div>

                <div class="form-group col-md-5" :class="[formCredit.provider ? '' : 'opacity-50']">
                  <div class="">
                    <label for="provider_id" class="col-form-label">Proveedor</label>
                    <button class="btn btn-primary mb-2" type="button" data-toggle="modal"
                      data-target="#addProviderModal" :disabled="!formCredit.provider">
                      <i class="bi bi-card-checklist"></i> Añadir Proveedor
                    </button>
                  </div>
                  <input type="text" class="form-control" disabled readonly v-model="provider_name"
                    :class="[formErrors.provider_id ? 'is-invalid' : '']" />
                  <small id="provider_id_help" class="form-text text-danger">{{
                  formErrors.provider_id
                  }}</small>
                </div>
                <div class="col-md-4"></div>
                <div class="
                    form-group
                    col-md-3
                    form-check
                    d-flex
                    align-items-center
                    pl-md-5
                  ">
                  <input type="checkbox" class="form-check-input" id="debtor" v-model="formCredit.debtor" value="0" />
                  <label class="form-check-label" for="debtor">¿Usa codeudor?</label>
                </div>

                <div class="form-group col-md-5" :class="[formCredit.debtor ? '' : 'opacity-50']">
                  <div class="">
                    <label for="debtors" class="col-form-label">Codeudor</label>
                    <button class="btn btn-primary mb-2" type="button" data-toggle="modal" data-target="#addDebtorModal"
                      :disabled="!formCredit.debtor">
                      <i class="bi bi-card-checklist"></i> Añadir codeudor
                    </button>
                  </div>
                  <ul>
                    <li class="
                        list-group-item
                        d-flex
                        justify-content-between
                        align-items-center
                      " v-for="(debtor, index) in formCredit.debtors" :key="debtor.id">
                      <span>{{ debtor.name }} {{ debtor.last_name }}</span>
                      <button class="btn btn-danger text-white rounded" type="button" @click="removeDebtor(index)">
                        <i class="bi bi-trash"></i>
                      </button>
                    </li>
                  </ul>
                  <small id="debtors_help" class="form-text text-danger">{{
                  formErrors.debtors
                  }}</small>
                </div>

                <div class="form-group col-md-4">
                  <div class="">
                    <label for="client_id" class="col-form-label">Producto</label>
                    <button class="btn btn-primary mb-2" type="button" data-toggle="modal"
                      data-target="#addProductModal">
                      <i class="bi bi-card-checklist"></i> Añadir producto
                    </button>
                  </div>
                  <input type="text" class="form-control" disabled readonly v-model="product_name"
                    :class="[formErrors.product_id ? 'is-invalid' : '']" />
                  <small id="product_id_help" class="form-text text-danger">{{
                  formErrors.product_id
                  }}</small>
                </div>

                <div class="form-group col-md-6">
                  <div class="">
                    <label for="guarantees" class="col-form-label">Garantías</label>
                    <button class="btn btn-primary mb-2" type="button" data-toggle="modal"
                      data-target="#addGuaranteeModal">
                      <i class="bi bi-card-checklist"></i> Añadir Garantías
                    </button>
                  </div>
                  <ul>
                    <li class="
                        list-group-item
                        d-flex
                        justify-content-between
                        align-items-center
                      " v-for="(guarantee, index) in formCredit.guarantees" :key="guarantee.id">
                      <span>{{ guarantee.guarantee }}</span>
                      <button class="btn btn-danger text-white rounded" type="button" @click="removeGuarantee(index)">
                        <i class="bi bi-trash"></i>
                      </button>
                    </li>
                  </ul>
                  <small id="guarantees_help" class="form-text text-danger">{{
                  formErrors.guarantees
                  }}</small>
                </div>

                <div class="form-group col-md-4">
                  <label for="description">Descripción</label>
                  <textarea type="text" class="form-control" id="description" v-model="formCredit.description"
                    :class="[formErrors.description ? 'is-invalid' : '']"
                    placeholder="Ingresar descripción "></textarea>
                  <small id="description_help" class="form-text text-danger">{{
                  formErrors.description
                  }}</small>
                </div>

                <div class="form-group col-md-4">
                  <label for="headquarter_id">Sede</label>
                  <v-select :options="headquarterList" label="headquarter" aria-logname="{}"
                    :reduce="(headquarter) => headquarter.id" v-model="formCredit.headquarter_id"
                    :class="[formErrors.headquarter_id ? 'is-invalid' : '']" placeholder="Seleccionar sede">
                  </v-select>
                  <small id="headquarter_id_help" class="form-text text-danger">{{ formErrors.headquarter_id }}</small>
                </div>
                <div class="form-group col-md-4">
                  <label for="credit_value">Valor Credito:
                    <b>{{ formCredit.credit_value | currency }}</b></label>
                  <input v-if="edit" type="text" class="form-control" id="credit_value" step="any"
                    :value="formCredit.credit_value | currency" :disabled="edit"
                    :class="[formErrors.credit_value ? 'is-invalid' : '']" />

                  <input v-if="!edit" type="number" class="form-control" id="credit_value" step="any"
                    v-model="formCredit.credit_value" :max="max_amount_credit" @keyup="
                      formCredit.credit_value > max_amount_credit
                        ? (formCredit.credit_value = max_amount_credit)
                        : (formCredit.credit_value = formCredit.credit_value)
                    " :class="[formErrors.credit_value ? 'is-invalid' : '']" placeholder="Ingresar valor de crédito" />

                  <small id="addAmountHelpBlock" class="form-text text-muted">
                    Monto máximo
                    {{ max_amount_credit | currency }}
                  </small>
                  <small id="credit_value_help" class="form-text text-danger">{{
                  formErrors.credit_value
                  }}</small>
                </div>
                <div class="form-group col-md-4">
                  <label for="interest">Interés (%)</label>
                  <input type="number" class="form-control" id="interest" v-model="formCredit.interest" step="any"
                    :disabled="edit" :class="[formErrors.interest ? 'is-invalid' : '']" placeholder="Ingresar interés"
                    value="3" />
                  <small id="interest_help" class="form-text text-danger">{{
                  formErrors.interest
                  }}</small>
                </div>

                <div class="form-group col-md-4">
                  <label for="number_installments">Cantidad Cuotas</label>
                  <input type="number" class="form-control" id="number_installments"
                    v-model="formCredit.number_installments" :disabled="edit" :class="[
                      formErrors.number_installments ? 'is-invalid' : '',
                    ]" placeholder="Ingresar cantidad de cuotas" />
                  <small id="number_installments_help" class="form-text text-danger">{{ formErrors.number_installments
                  }}</small>
                </div>

                <div class="form-group col-md-4">
                  <label for="start_date">Fecha inicio</label>
                  <input type="date" class="form-control" id="start_date" v-model="formCredit.start_date"
                    :disabled="edit" :class="[formErrors.start_date ? 'is-invalid' : '']" />
                  <small id="start_date_help" class="form-text text-danger">{{
                  formErrors.start_date
                  }}</small>
                </div>
              </div>
              <simulator :capital="formCredit.credit_value" :interest="formCredit.interest"
                :number_installments="formCredit.number_installments" :start_date="formCredit.start_date"
                ref="Simulator" v-if="!edit"></simulator>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                  @click="(edit = false), resetData()">
                  Cerrar
                </button>
                <button type="button" class="btn btn-success" @click="edit ? editCredit() : createCredit()">
                  Guardar
                </button>
              </div>
            </form>
          </div>
          <div class="modal-footer"></div>
        </div>
      </div>
    </div>
    <add-client @add-client="receiveClient($event)" />
    <add-debtor @add-debtor="receiveDebtor($event)" />
    <add-guarantee @add-guarantee="receiveGuarantee($event)" />
    <add-product @add-product="receiveProduct($event)" />
    <add-provider @add-provider="receiveProvider($event)" />
  </div>
</template>

<script>
import AddClient from "../../clients/AddClient.vue";
import AddDebtor from "../../clients/AddDebtor.vue";
import AddGuarantee from "../../guarantees/AddGuarantee.vue";
import AddProduct from "../../products/AddProduct.vue";
import AddProvider from "../../providers/AddProvider.vue";
import Simulator from "../credit_helpers/Simulator.vue";

export default {
  components: {
    Simulator,
    AddClient,
    AddDebtor,
    AddProvider,
    AddProduct,
    AddGuarantee,
  },
  data() {
    return {
      edit: false,
      headquarterList: [],
      formCredit: {
        client_id: "",
        product_id: "",
        debtors: [],
        headquarter_id: "",
        user_id: "",
        provider_id: "",
        number_installments: "",
        number_paid_installments: "",
        number_paid_installments: "",
        day_limit: "",
        debtor: 0,
        provider: 0,
        status: "1",
        start_date: "",
        interest: "",
        annual_interest_percentage: 0,
        installment_value: "",
        credit_value: "",
        paid_value: "",
        capital_value: "",
        interest_value: "",
        description: "",
        disbursement_date: "",
        guarantees: [],
      },
      client_name: "Agregar con el botón",
      debtor_name: "Agregar con el botón",
      provider_name: "Agregar con el botón",
      product_name: "Agregar con el botón",
      maximum_credit_allowed: -1,
      root_data: this.$root.$data,
      formErrors: {
        client_id: "",
        product_id: "",
        debtors: "",
        headquarter_id: "",
        user_id: "",
        provider_id: "",
        number_installments: "",
        number_paid_installments: "",
        number_paid_installments: "",
        day_limit: "",
        debtor: "",
        provider: "",
        status: "",
        start_date: "",
        interest: "",
        annual_interest_percentage: "",
        installment_value: "",
        credit_value: "",
        paid_value: "",
        capital_value: "",
        interest_value: "",
        description: "",
        disbursement_date: "",
      },
    };
  },
  computed: {
    max_amount_credit() {
      var value = this.root_data.current_balance_main_box;

      if (this.maximum_credit_allowed != -1) {
        if (
          this.root_data.current_balance_main_box < this.maximum_credit_allowed
        ) {
          return this.root_data.current_balance_main_box;
        } else {
          return this.maximum_credit_allowed;
        }
      }

      return value;
    },
  },
  created() {
    this.listHeadquarters(1);
  },
  methods: {
    listHeadquarters() {
      let me = this;
      axios
        .get(`api/headquarters/list-all-headquarters`, me.$root.config)
        .then(function (response) {
          me.headquarterList = response.data;
        });
    },
    createCredit() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);
      axios
        .post("api/credits", this.formCredit, me.$root.config)
        .then(function () {
          $("#formCreditModal").modal("hide");
          me.resetData();
          me.$emit("list-credits");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });
    },
    showEditCredit(credit) {
      this.edit = true;
      let me = this;
      $("#formCreditModal").modal("show");
      me.formCredit = Object.assign({}, credit);
    },
    editCredit() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);
      axios
        .put(
          "api/credits/" + this.formCredit.id,
          this.formCredit,
          me.$root.config
        )
        .then(function () {
          $("#formCreditModal").modal("hide");
          me.resetData();
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });

      this.$emit("list-credits");
      this.edit = false;
    },
    receiveClient(client) {
      this.formCredit.client_id = client.id;
      this.client_name = `${client.name} ${client.last_name}`;
      this.maximum_credit_allowed = client.maximum_credit_allowed;
    },
    receiveProduct(product) {
      this.formCredit.product_id = product.id;
      this.product_name = `${product.product}`;
    },
    receiveDebtor(debtor) {
      let findId = this.formCredit.debtors.find(
        (element) => element.id == debtor.id
      );
      if (!findId) {
        this.formCredit.debtors.push(debtor);
      }
    },
    removeDebtor(index) {
      this.formCredit.debtors.splice(index, 1);
    },
    receiveGuarantee(guarantee) {
      let findId = this.formCredit.guarantees.find(
        (element) => element.id == guarantee.id
      );
      if (!findId) {
        this.formCredit.guarantees.push(guarantee);
      }
    },
    removeGuarantee(index) {
      this.formCredit.guarantees.splice(index, 1);
    },
    receiveProvider(provider) {
      this.formCredit.provider_id = provider.id;
      this.provider_name = `${provider.business_name}`;
    },
    resetData() {
      let me = this;
      Object.keys(this.formCredit).forEach(function (key, index) {
        me.formCredit[key] = "";
      });
      me.$root.assignErrors(false, me.formErrors);
    },
  },
};
</script>
