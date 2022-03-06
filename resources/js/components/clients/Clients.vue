<template>
  <div class="">
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Clientes</h3>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#formClientModal"
        v-if="$root.validatePermission('client-store')"
      >
        Crear cliente
      </button>
    </div>
    <div class="page-search d-flex justify-content-between p-4 border my-2">
      <div class="form-group col-8 m-auto">
        <label for="search_client">Buscar...</label>
        <input
          type="text"
          id="search_client"
          name="search_client"
          class="form-control"
          placeholder="Nombres | Documento"
          @keypress="listClients(1)"
          v-model="search_client"
        />
      </div>
    </div>
    <div class="page-content mt-4">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th>id</th>
              <th>Nombres</th>
              <th>Documento</th>
              <th>Celular</th>
              <th>Correo Electronico</th>
              <th>Dirección</th>
              <th  v-if="$root.validatePermission('client-status')">Estado</th>
              <th  v-if="$root.validatePermission('client-update')">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="c in clientList.data" :key="c.id">
              <td>{{ c.id }}</td>
              <td>{{ c.name }} {{ c.last_name }}</td>
              <td>{{ c.document }}</td>
              <td>
                {{ c.phone_1 }} <br />
                {{ c.phone_2 }}
              </td>
              <td>{{ c.email }}</td>
              <td>{{ c.address }}</td>
              <td v-if="$root.validatePermission('client-status')">
                <button
                  class="btn"
                  :class="
                    c.status == 1 ? 'btn-outline-success' : 'btn-outline-danger'
                  "
                  @click="changeStatus(c.id)"
                >
                  <i class="bi bi-check-circle-fill" v-if="c.status == 1"></i>
                  <i class="bi bi-x-circle" v-if="c.status == 0"></i>
                </button>
              </td>
              <td v-if="c.status == 1 && $root.validatePermission('client-update')" class="text-center">
                <button class="btn btn-outline-primary" @click="showData(c)">
                  <i class="bi bi-pen"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="clientList"
          :limit="8"
          @pagination-change-page="listClients"
        >
          <span slot="prev-nav">&lt; Anterior</span>
          <span slot="next-nav">Siguiente &gt;</span>
        </pagination>
      </section>
    </div>
    <modal-create-edit-client
      ref="ModalCreateEditClient"
      @list-clients="listClients(1)"
    />
  </div>
</template>
<script>
import ModalCreateEditClient from "./ModalCreateEditClient.vue";
export default {
  components: { ModalCreateEditClient },
  data() {
    return {
      search_client: "",
      clientList: {},
    };
  },
  created() {
    this.listClients(1);
  },
  methods: {
    listClients(page = 1) {
      let me = this;
      axios
        .get(`api/clients?page=${page}&client=${this.search_client}`, me.$root.config)
        .then(function (response) {
          me.clientList = response.data;
        });
    },
    showData: function (client) {
      this.$refs.ModalCreateEditClient.showEditClient(client);
    },
    changeStatus: function (id) {
      let me = this;

      Swal.fire({
        title: "¿Quieres cambiar el status del client?",
        showDenyButton: true,
        denyButtonText: `Cancelar`,
        confirmButtonText: `Guardar`,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post(`api/clients/${id}/change-status`, null, me.$root.config)
            .then(function () {
              me.listClients(1);
            });
          Swal.fire("Cambios realizados!", "", "success");
        } else if (result.isDenied) {
          Swal.fire("Operación no realizada", "", "info");
        }
      });
    },
  },
};
</script>
