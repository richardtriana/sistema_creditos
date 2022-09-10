<template>
  <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductModalLabel">Productos</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Documento | Nombre de producte"
              aria-label=" with two button addons" aria-describedby="button-addon4" v-model="filters.product"
              @keyup="listProducts()" />
            <div class="input-group-append" id="button-addon4">
              <button class="btn btn-secondary" type="button" @click="listProducts()">
                Buscar Proveedor
              </button>
            </div>
          </div>
          <table class="table table-bordered table-sm table-responsive">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Product</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="product in ProductList.data" v-bind:key="product.id">
                <th scope="row">{{ product.code }}</th>
                <td>{{ product.product }}</td>
                <td>
                  <button class="btn btn-success" @click="$emit('add-product', product)" data-dismiss="modal">
                    <i class="bi bi-plus-circle"></i>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cerrar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "add-product",
  data() {
    return {
      ProductList: {},
      filters: {
        product: "",
      },
    };
  },
  created() {
    this.listProducts();
  },
  methods: {
    listProducts() {
      let me = this;

      let data = {
        'product': me.filters.product
      }

      var url = `api/products/filter-product-list`;
      axios
        .get(url,
          {
            params: data,
            headers: this.$root.config.headers,
          })
        .then(function (response) {
          me.ProductList = response;
        })
        .catch(function (error) {
          $("#no-results").toast("show");
          console.log(error);
        });

    },
  },
};
</script>
