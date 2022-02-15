<template>
  <div
    class="modal fade"
    id="addClientModal"
    tabindex="-1"
    aria-labelledby="addClientModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addClientModalLabel">Clientes</h5>
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
              placeholder="Documento | Nombre de cliente"
              aria-label=" with two button addons"
              aria-describedby="button-addon4"
              v-model="filters.client"
              @keyup="searchClient()"
            />
            <div class="input-group-append" id="button-addon4">
              <button
                class="btn btn-outline-secondary"
                type="button"
                @click="searchClient()"
              >
                Buscar Cliente
              </button>
            </div>
          </div>
          <table class="table table-bordered table-sm table-responsive">
            <thead>
              <tr>
                <th>#</th>
                <th>Nombres</th>
                <th>Documento</th>
                <th>Direccion</th>
                <th>Correo</th>
                <th>Contacto</th>
                <th>Opciones</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="client in ClientList.data" v-bind:key="client.id">
                <th scope="row">{{ client.code }}</th>
                <td>{{ client.name }}</td>
                <td>{{ client.document }}</td>
                <td>{{ client.address }}</td>
                <td>{{ client.email }}</td>
                <td>
                  {{ client.phone_1 }} <br />
                  {{ client.phone_2 }}
                </td>
                <td>
                  <button
                    class="btn btn-outline-secondary"
                    @click="$emit('add-client', client)"
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
  name: "add-client",
  data() {
    return {
      ClientList: {},
      filters: {
        client: "",
      },
    };
  },
  created() {
    this.listClients();
  },
  methods: {
    listClients() {
      let me = this;
      axios
        .post("api/clients/filter-client-list", null)
        .then(function (response) {
          me.ClientList = response;
        });
    },
    searchClient() {
      let me = this;
      if (me.filters.client == "") {
        return false;
      }
      var url = `api/clients/filter-client-list?client=${me.filters.client}`;
      if (me.filters.client.length >= 3) {
        axios
          .post(url, null)
          .then(function (response) {
            me.ClientList = response;
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