<template>
  <div>
    <div
      class="modal fade"
      id="formProviderModal"
      tabindex="-1"
      aria-labelledby="formProviderModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formProviderModalLabel">
              Modal proveedores
            </h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              @click="(editar = false)"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-8">
                  <label for="business_name">Razón social</label>
                  <input
                    type="text"
                    class="form-control"
                    id="business_name"
                    v-model="formProvider.business_name"
                    placeholder="Ingresar razón social"
                  />
                  <small id="business_name_help" class="text-form text-danger">
                    {{ formErrors.business_name }}
                  </small>
                </div>
                <div class="form-group col-md-4">
                  <label for="type_document">Tipo Documento</label>
                  <select
                    name="type_document"
                    id="c"
                    class="custom-select"
                    v-model="formProvider.type_document"
                    placeholder="Ingresar identificación"
                  >
                    <option value="0" disabled>--Seleccionar--</option>
                    <option
                      v-for="(d, key) in type_documents"
                      :key="key"
                      :value="key"
                    >
                      {{ d }}
                    </option>
                  </select>
                  <small id="type_document_help" class="text-form text-danger">
                    {{ formErrors.type_document }}
                  </small>
                </div>
                <div class="form-group col-md-4">
                  <label for="Apellidos">Nro. Documento</label>

                  <input
                    type="number"
                    class="form-control"
                    id="Documento"
                    v-model="formProvider.document"
                  />
                  <small id="document_help" class="text-form text-danger">
                    {{ formErrors.document }}
                  </small>
                </div>

                <div class="form-group col-4">
                  <label for="phone_1">Celular 1</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="phone_1"
                    v-model="formProvider.phone_1"
                    placeholder="Ingresar celular"
                  />
                  <small id="phone_1_help" class="text-form text-danger">
                    {{ formErrors.phone_1 }}
                  </small>
                </div>
                <div class="form-group col-4">
                  <label for="phone_2">Celular 2</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="phone_2"
                    v-model="formProvider.phone_2"
                    placeholder="Ingresar celular"
                  />
                  <small id="phone_2_help" class="text-form text-danger">
                    {{ formErrors.phone_2 }}
                  </small>
                </div>

                <div class="form-group col-4">
                  <label for="email">Correo Electrónico</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="formProvider.email"
                    placeholder="Ingresar correo electrónico"
                  />
                  <small id="email_help" class="text-form text-danger">
                    {{ formErrors.email }}
                  </small>
                </div>
                <div class="form-group col-4">
                  <label for="address">Dirección</label>
                  <input
                    type="text"
                    class="form-control"
                    id="address"
                    v-model="formProvider.address"
                    placeholder="Ingresar dirección"
                  />
                  <small id="address_help" class="text-form text-danger">
                    {{ formErrors.address }}
                  </small>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              @click="editar ? editProvider() : createProvider()"
            >
              Guardar
            </button>
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="editar = false"
            >
              Cerrar
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
      formProvider: {
        business_name: "",
        type_document: "CC",
        document: 0,
        phone_1: "",
        phone_2: "",
        email: "",
      },
      formErrors: {
        business_name: "",
        type_document: "",
        document: "",
        phone_1: "",
        phone_2: "",
        email: "",
      },
      type_documents: this.$root.$data.type_documents,
    };
  },

  methods: {
    createProvider() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);

      axios
        .post("api/providers", this.formProvider, me.$root.config)
        .then(function () {
          $("#formProviderModal").modal("hide");
          me.formProvider = {};
          me.$emit("list-providers");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });
    },
    showEditProvider(provider) {
      this.editar = true;
      let me = this;
      $("#formProviderModal").modal("show");
      me.formProvider = provider;
    },
    editProvider() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);
      axios
        .put(
          "api/providers/" + this.formProvider.id,
          this.formProvider,
          me.$root.config
        )
        .then(function () {
          $("#formProviderModal").modal("hide");
          me.formProvider = {};
          me.$emit("list-providers");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });

      this.editar = false;
    },
  },
};
</script>
