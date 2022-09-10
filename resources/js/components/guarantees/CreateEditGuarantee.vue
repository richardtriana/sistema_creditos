<template>
  <div>
    <div
      class="modal fade"
      id="formGuaranteeModal"
      tabindex="-1"
      aria-labelledby="formGuaranteeModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formGuaranteeModalLabel">
              Modal garantías
            </h5>
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
                <div class="form-group col-md-8">
                  <label for="guarantee">Garantías</label>
                  <input
                    type="text"
                    class="form-control"
                    id="guarantee"
                    v-model="formGuarantee.guarantee"
                    placeholder="Garantía"
                  />
                  <small id="guarantee_help" class="text-form text-danger">
                    {{ formErrors.guarantee }}
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
              @click="editar ? editGuarantee() : createGuarantee()"
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
      formGuarantee: {
        guarantee: "",
        state: 1,
      },
      formErrors: {
        guarantee: "",
        state: "",
      },
    };
  },

  methods: {
    createGuarantee() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);

      axios
        .post("api/guarantees", this.formGuarantee, me.$root.config)
        .then(function () {
          $("#formGuaranteeModal").modal("hide");
          me.formGuarantee = {};
          me.$emit("list-guarantees");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });
    },
    showEditGuarantee(provider) {
      this.editar = true;
      let me = this;
      $("#formGuaranteeModal").modal("show");
      me.formGuarantee = provider;
    },
    editGuarantee() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);
      axios
        .put(
          `api/guarantees/${this.formGuarantee.id}`,
          this.formGuarantee,
          me.$root.config
          
        )
        .then(function () {
          $("#formGuaranteeModal").modal("hide");
          me.formGuarantee = {};
          me.$emit("list-guarantees");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });

      this.editar = false;
    },
  },
};
</script>
