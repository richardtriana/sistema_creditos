<template>
  <div class="">
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Clientes</h3>
      <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formClientModal"
        v-if="$root.validatePermission('client-store')">
        Crear cliente
      </button>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form>
        <div class="form-row">
          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <label for="search_client">Buscar...</label>
            <input type="text" id="search_client" name="search_client" class="form-control"
              placeholder="Nombres | Documento" v-model="search_client" />
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6">
            <label for="">Mostrar {{ search_results }} resultados por página:</label>
            <input type="number" id="search_results" name="search_results" class="form-control" placeholder="Desde"
              v-model="search_results" max="1000" />
          </div>
          <div class="form-group col-md-4 col-sm-6 col-xs-6 ">
            <button class="btn btn-success w-100 mt-5" type="button" @click="listClients(1)">
              <i class="bi bi-search"></i> Buscar
            </button>
          </div>

        </div>
        <div class="form-row">
          <div class="form-group offset-md-8  col-md-4 col-sm-6 col-xs-6">
            <download-excel class="btn btn-primary w-100" :fields="json_fields" :data="clientList.data"
              name="list-clients.xls" type="xls">
              <i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
            </download-excel>
          </div>
        </div>
      </form>
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
              <th v-if="$root.validatePermission('client-status')">Estado</th>
              <th v-if="$root.validatePermission('client-update')">Opciones</th>
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
              <td v-if="$root.validatePermission('client-status')" class="text-right">
                <button class="btn" :class="c.status == 1 ? 'btn-success' : 'btn-danger'
                  " @click="changeStatus(c.id)">
                  <i class="bi bi-check-circle-fill" v-if="c.status == 1"></i>
                  <i class="bi bi-x-circle" v-if="c.status == 0"></i>
                </button>
              </td>
              <td class="text-right" v-if="$root.validatePermission('client-update')">
                <button class="btn btn-primary" @click="showData(c)" v-if="c.status == 1">
                  <i class="bi bi-pen"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="clientList" :limit="8" @pagination-change-page="listClients">
          <span slot="prev-nav">&lt; Anterior</span>
          <span slot="next-nav">Siguiente &gt;</span>
        </pagination>
      </section>
    </div>
    <modal-create-edit-client ref="ModalCreateEditClient" @list-clients="listClients(1)" />
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
      search_results: 10,
      json_fields: {
        'ID': {
          field: 'id',
          callback: (value) => {
            return value;
          }
        },
        'Nombres': {
          callback: (value) => {
            let name = value.name;
            let last_name = value.last_name
            return `${last_name} ${name}`;
          }
        },
        'Tipo documento': {
          field: 'type_document',
          callback: (value) => {
            return value;
          }
        },
        'Documento': {
          field: 'document',
          callback: (value) => {
            return value;
          }
        },
        'Celular 1': {
          field: 'phone_1',
          callback: (value) => {
            return value;
          }
        },
        'Celular 2': {
          field: 'phone_2',
          callback: (value) => {
            return value;
          }
        },
        'Correo electronico': {
          field: 'email',
          callback: (value) => {
            return value;
          }
        },
        'Dirección': {
          field: 'address',
          callback: (value) => {
            return value;
          }
        },
        'Cupo crédito': {
          field: 'maximum_credit_allowed',
          callback: (value) => {
            return value;
          }
        },
        'Fecha de nacimiento': {
          field: 'birth_date',
          callback: (value) => {
            return value;
          }
        },
        'Profesion': {
          field: 'profession',
          callback: (value) => {
            return value;
          }
        },
        'Cargo': {
          field: 'occupation',
          callback: (value) => {
            return value;
          }
        },
        'Estado civil': {
          field: 'civil_status',
          callback: (value) => {
            return value;
          }
        },
        'Lugar de trabajo': {
          field: 'workplace',
          callback: (value) => {
            return value;
          }
        },
        'Sede': {
          field: 'headquarter',
          callback: (value) => {
            return value.headquarter;
          }
        }
      }
    };
  },
  created() {
    this.listClients(1);
  },
  methods: {
    listClients(page = 1) {
      let me = this;

      let data = {
        page: page,
        results: this.search_results,
        client: this.search_client
      }

      axios
        .get(`api/clients`, {
          params: data,
          headers: me.$root.config.headers,
        })
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
