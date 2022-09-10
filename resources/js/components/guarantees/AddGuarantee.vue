<template>
  <div class="modal fade" id="addGuaranteeModal" tabindex="-1" aria-labelledby="addGuaranteeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addGuaranteeModalLabel">Garantías</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <input type="text" class="form-control" placeholder="Garantía" aria-label=" with two button addons"
              aria-describedby="button-addon4" v-model="filters.guarantee" @keyup="listGuarantees()" />
            <div class="input-group-append" id="button-addon4">
              <button class="btn btn-secondary" type="button" @click="listGuarantees()">
                Buscar Proveedor
              </button>
            </div>
          </div>
          <table class="table table-bordered table-sm table-responsive">
            <thead>
              <tr class="text-center">
                <th>#</th>
                <th>Razón social</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="guarantee in GuaranteeList.data" v-bind:key="guarantee.id">
                <th scope="row">{{ guarantee.id }}</th>
                <td>{{ guarantee.guarantee }}</td>
                <td>
                  <button class="btn btn-success" @click="$emit('add-guarantee', guarantee)" data-dismiss="modal">
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
  name: "add-guarantee",
  data() {
    return {
      GuaranteeList: {},
      filters: {
        guarantee: "",
      },
    };
  },
  created() {
    this.listGuarantees();
  },
  methods: {

    listGuarantees() {
      let me = this;

      let data = {
        'guarantee': me.filters.guarantee
      }

      var url = `api/guarantees/filter-guarantee-list`;
      axios
        .get(url, {
          params: data,
          headers: this.$root.config.headers,
        })
        .then(function (response) {
          me.GuaranteeList = response;
        })
        .catch(function (error) {
          $("#no-results").toast("show");
          console.log(error);
        });

    },
  },
};
</script>
