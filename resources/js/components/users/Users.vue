<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Users</h3>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#formUserModal"
      >
        Crear User
      </button>
    </div>
    <div class="page-search d-flex justify-content-between p-4 border my-2">
      <div class="form-group col-8 m-auto">
        <label for="buscar_user">Buscar...</label>
        <input
          type="text"
          id="buscar_user"
          name="buscar_user"
          class="form-control"
          placeholder="Nombres | Documento"
          @keypress="listarUsers(1)"
          v-model="buscar_user"
        />
      </div>
    </div>
    <div class="page-content">
      <section>
        <table
          class="
            table
            table-sm
            table-bordered
            table-responsive
            table-hover
            table-striped
          "
        >
          <thead>
            <tr>
              <th>id</th>
              <th>Nombre</th>
              <th>Apellidos</th>
              <th>Correo</th>
              <th>Documento</th>
              <th>Celular</th>
              <th>Headquarter</th>
              <th>Rol</th>
              <th>Estado</th>
              <th>Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in listaUsers.data" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.last_name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.document }}</td>
              <td>{{ user.phone }}</td>
              <td>{{ user.headquarter_id }}</td>
              <td v-if="user.rol_id == 1">Administrador</td>
              <td v-if="user.rol_id == 2">Operario</td>
              <td v-if="user.status == 1">Activo</td>
              <td v-if="user.status == 0">Inactivo</td>

              <td class="text-right">
                <button
                  v-if="user.status == 1"
                  class="btn btn-outline-primary"
                  @click="showData(user)"
                >
                  <i class="bi bi-pen"></i>
                </button>
                <button
                  v-if="user.status == 1"
                  class="btn btn-outline-danger"
                  @click="changeStatus(user.id)"
                >
                  <i class="bi bi-trash"></i>
                </button>
                <button
                  v-if="user.status == 0"
                  class="btn btn-outline-success"
                  @click="changeStatus(user.id)"
                >
                  <i class="bi bi-check2-circle"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="listaUsers"
          :limit="8"
          @pagination-change-page="listarUsers"
        >
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"
            ><i class="bi bi-chevron-double-right"></i
          ></span>
        </pagination>
      </section>
    </div>
    <create-edit-user ref="CreateEditUser" />
  </div>
</template>

<script>
import CreateEditUser from "./CreateEditUser.vue";
export default {
  components: { CreateEditUser },
  data() {
    return {
      buscar_user: "",
      listaUsers: {},
    };
  },
  created() {
    this.listarUsers(1);
  },
  methods: {
    listarUsers(page = 1) {
      let me = this;
      axios
        .get(`api/users?page=${page}&user=${this.buscar_user}`)
        .then(function (response) {
          me.listaUsers = response.data;
        });
    },
    showData: function (ususario) {
      this.$refs.CreateEditUser.abirEditarUser(ususario);
    },
    changeStatus: function (id) {
      let me = this;

      Swal.fire({
        title: "¿Quieres cambiar el status del user?",
        showDenyButton: true,
        denyButtonText: `Cancelar`,
        confirmButtonText: `Guardar`,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post(
              "api/users/" + id + "/change-status",
              null,
              me.$root.config
            )
            .then(function () {
              me.listarUsers(1);
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
