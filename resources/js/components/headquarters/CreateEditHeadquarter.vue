<template>
  <div>
    <div
      class="modal fade"
      id="formHeadquarterModal"
      tabindex="-1"
      aria-labelledby="formHeadquarterModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formHeadquarterModalLabel">Sedes</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              @click="(editar = false), resetData()"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="headquarter">Sede</label>
                  <input
                    type="text"
                    class="form-control"
                    id="headquarter"
                    v-model="formHeadquarter.headquarter"
                    required
                    :class="[formErrors.headquarter ? 'is-invalid' : '']"
                    placeholder="Ingresar sede"
                  />
                  <small id="headquarterHelp" class="form-text text-danger">{{
                    formErrors.headquarter
                  }}</small>
                </div>
                <div class="form-group col-md-4">
                  <label for="address">Dirección</label>
                  <input
                    type="text"
                    class="form-control"
                    id="address"
                    v-model="formHeadquarter.address"
                    required
                    placeholder="Ingresar dirección"
                    :class="[formErrors.address ? 'is-invalid' : '']"
                  />
                  <small id="addressHelp" class="form-text text-danger">
                    {{ formErrors.address }}
                  </small>
                </div>
                <div class="form-group col-md-4">
                  <label for="nit">NIT</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nit"
                    v-model="formHeadquarter.nit"
                    placeholder="Ingresar nit"
                    :class="[formErrors.nit ? 'is-invalid' : '']"
                  />
                  <small id="nitHelp" class="form-text text-danger">
                    {{ formErrors.nit }}
                  </small>
                </div>
                <div class="form-group col-md-4">
                  <label for="email">Correo Contacto</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="formHeadquarter.email"
                    :class="[formErrors.email ? 'is-invalid' : '']"
                    placeholder="Ingresar email"
                  />
                  <small id="email_help" class="form-text text-danger">
                    {{ formErrors.email }}
                  </small>
                </div>

                <div class="form-group col-md-4">
                  <label for="legal_representative">Representante</label>

                  <input
                    type="text"
                    class="form-control"
                    id="legal_representative"
                    v-model="formHeadquarter.legal_representative"
                    placeholder="Ingresar representante"
					          :class="[formErrors.headquarter ? 'is-invalid' : '']"
                  />
                  <small
                    id="legal_representative_help"
                    class="form-text text-danger"
                  >
                    {{ formErrors.legal_representative }}
                  </small>
                </div>

                <div class="form-group col-md-4">
                  <label for="phone">Celular Contacto</label>

                  <input
                    type="number"
                    class="form-control"
                    id="phone"
                    v-model="formHeadquarter.phone"
                    placeholder="Ingresar numero de celular"
                    :class="[formErrors.phone ? 'is-invalid' : '']"
                  />
                  <small
                    id="phone_help"
                    class="form-text text-danger"
                  >
                    {{ formErrors.phone }}
                  </small>
                </div>
                <div class="form-group col-md-4">
                  <label for="pos_printer">Impresora POS</label>
                  <input
                    type="email"
                    class="form-control"
                    id="pos_printer"
                    v-model="formHeadquarter.pos_printer"
                    :class="[formErrors.pos_printer ? 'is-invalid' : '']"
                    placeholder="Ingresar impresora"
                  />
                  <small id="posPrinterHelp" class="form-text text-danger">
                    {{ formErrors.pos_printer }}
                  </small>
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
              class="btn btn-primary"
              @click="editar ? editHeadquarter() : createHeadquarter()"
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
      formHeadquarter: {
        headquarter: "",
        legal_representative: "",
        pos_printer: "",
        phone: "",
        address: "",
        email: "",
        nit: "",
      },
      formErrors: {
        headquarter: "",
        legal_representative: "",
        pos_printer: "",
        phone: "",
        address: "",
        email: "",
        nit: "",
      },
    };
  },
  // Function createHeadquarters
  methods: {
    createHeadquarter() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);
      axios
        .post("api/headquarters", this.formHeadquarter, me.$root.config)
        .then(function () {
          $("#formHeadquarterModal").modal("hide");
          me.resetData();
          me.$emit("list-headquarters");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });
    },
    showEditHeadquarter(headquarter) {
      this.editar = true;
      let me = this;
      $("#formHeadquarterModal").modal("show");
      me.formHeadquarter = headquarter;
    },
    editHeadquarter() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);
      axios
        .put(
          `api/headquarters/${this.formHeadquarter.id}`,
          this.formHeadquarter,
          me.$root.config
        )
        .then(function () {
          $("#formHeadquarterModal").modal("hide");
          me.resetData();
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });
      me.$emit("list-headquarters");
      me.editar = false;
    },
    resetData() {
      let me = this;
      Object.keys(this.formHeadquarter).forEach(function (key, index) {
        me.formHeadquarter[key] = "";
      });
      me.$emit("list-headquarters");
    },
  },
};
</script>
