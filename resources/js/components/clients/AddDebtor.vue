<template>
  <div
    class="modal fade"
    id="addDebtorModal"
    tabindex="-1"
    aria-labelledby="addDebtorModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addDebtorModalLabel">Clientes</h5>
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
                class="btn btn-secondary"
                type="button"
                @click="searchClient()"
              >
                Buscar codeudor
              </button>
            </div>
          </div>
          <section class="table-responsive">
            <table class="table table-bordered table-sm">
              <thead>
                <tr class="text-center">
                  <th scope="col">#</th>
                  <th scope="col">Nombres</th>
                  <th>Documento</th>
                  <th scope="col">Direccion</th>
                  <th>Correo</th>
                  <th>Contacto</th>
                  <th>Opciones</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="debtor in ClientList.data" v-bind:key="debtor.id">
                  <th scope="row">{{ debtor.id }}</th>
                  <td>{{ debtor.name }}</td>
                  <td>{{ debtor.document }}</td>
                  <td>{{ debtor.address }}</td>
                  <td>{{ debtor.email }}</td>
                  <td>
                    {{ debtor.phone_1 }} <br />
                    {{ debtor.phone_2 }}
                  </td>

                  <td>
                    <button
                      class="btn btn-primary"
                      @click="$emit('add-debtor', debtor)"
                      data-dismiss="modal"
                    >
                      <i class="bi bi-plus-circle"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </section>
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
  name: "add-debtor",
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
        .post("api/clients/filter-client-list", null, me.$root.config)
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
          .post(url, null, me.$root.config)
          .then(function (response) {
            me.ClientList = response;
          })
          .catch(function (error) {
            $("#no-results").toast("show");

            console.log(error);
          });
      }
    },
    sendClient() {},
  },
};
</script>
