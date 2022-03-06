<template>
  <div
    class="modal fade"
    id="addProviderModal"
    tabindex="-1"
    aria-labelledby="addProviderModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProviderModalLabel">Proveedores</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Cerrar"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="input-group">
            <input
              type="text"
              class="form-control"
              placeholder="Documento | Nombre de providere"
              aria-label=" with two button addons"
              aria-describedby="button-addon4"
              v-model="filters.provider"
              @keyup="searchProvider()"
            />
            <div class="input-group-append" id="button-addon4">
              <button
                class="btn btn-outline-secondary"
                type="button"
                @click="searchProvider()"
              >
                Buscar Proveedor
              </button>
            </div>
          </div>
          <table class="table table-bordered table-sm table-responsive">
            <thead>
              <tr>
                <th>#</th>
                <th>Razón social</th>
                <th>Documento</th>
                <th>Direccion</th>

                <th>Correo</th>
                <th>Contacto</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="provider in ProviderList.data"
                v-bind:key="provider.id"
              >
                <th scope="row">{{ provider.code }}</th>
                <td>{{ provider.business_name }}</td>
                <td>{{ provider.document }}</td>
                <td>{{ provider.address }}</td>

                <td>{{ provider.email }}</td>
                <td>
                  {{ provider.phone_1 }} <br />
                  {{ provider.phone_2 }}
                </td>

                <td>
                  <button
                    class="btn btn-outline-secondary"
                    @click="$emit('add-provider', provider)"
                    data-dismiss="modal"
                  >
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
  name: "add-provider",
  data() {
    return {
      ProviderList: {},
      filters: {
        provider: "",
      },
    };
  },
  created() {
    this.listProviders();
  },
  methods: {
    listProviders() {
      let me = this;
      axios
        .post("api/providers/filter-provider-list", null, me.$root.config)
        .then(function (response) {
          me.ProviderList = response;
        });
    },
    searchProvider() {
      let me = this;
      if (me.filters.provider == "") {
        return false;
      }
      var url = `api/providers/filter-provider-list?provider=${me.filters.provider}`;
      if (me.filters.provider.length >= 3) {
        axios
          .post(url, null, me.$root.config)
          .then(function (response) {
            me.ProviderList = response;
          })
          .catch(function (error) {
            $("#no-results").toast("show");
            console.log(error);
          });
      }
    },
  },
};
</script>
