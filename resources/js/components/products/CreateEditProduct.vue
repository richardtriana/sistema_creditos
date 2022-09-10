<template>
  <div>
    <div
      class="modal fade"
      id="formProductModal"
      tabindex="-1"
      aria-labelledby="formProductModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formProductModalLabel">
              Modal productos
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
                  <label for="product">Producto</label>
                  <input
                    type="text"
                    class="form-control"
                    id="product"
                    v-model="formProduct.product"
                    placeholder="Ingresar producto"
                  />
                  <small id="product_help" class="text-form text-danger">
                    {{ formErrors.product }}
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
              @click="editar ? editProduct() : createProduct()"
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
      formProduct: {
        product: "",
        state: 1,
      },
      formErrors: {
        product: "",
        state: "",
      }
    };
  },

  methods: {
    createProduct() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);

      axios
        .post("api/products", this.formProduct, me.$root.config)
        .then(function () {
          $("#formProductModal").modal("hide");
          me.formProduct = {};
          me.$emit("list-products");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });
    },
    showEditProduct(provider) {
      this.editar = true;
      let me = this;
      $("#formProductModal").modal("show");
      me.formProduct = provider;
    },
    editProduct() {
      let me = this;
      me.$root.assignErrors(false, me.formErrors);
      axios
        .put(
          "api/products/" + this.formProduct.id,
          this.formProduct,
          me.$root.config
        )
        .then(function () {
          $("#formProductModal").modal("hide");
          me.formProduct = {};
          me.$emit("list-products");
        })
        .catch((response) => {
          me.$root.assignErrors(response, me.formErrors);
        });

      this.editar = false;
    },
  },
};
</script>
