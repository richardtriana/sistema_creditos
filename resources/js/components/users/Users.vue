<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Usuarios</h3>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#formUserModal"
        v-if="$root.validatePermission('user-store')"
        @click="$refs.CreateEditUser.resetData()"
      >
        Crear usuario
      </button>
    </div>
    <div class="page-search d-flex justify-content-between p-4 border my-2">
      <div class="form-group col-8 m-auto">
        <label for="search_user">Buscar...</label>
        <input
          type="text"
          id="search_user"
          name="search_user"
          class="form-control"
          placeholder="Nombres | Documento"
          @keypress="listUsers(1)"
          v-model="search_user"
        />
      </div>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-sm table-bordered table-hover table-striped">
          <thead>
            <tr>
              <th>id</th>
              <th>Nombre</th>
              <th>Apellidos</th>
              <th>Correo</th>
              <th>Documento</th>
              <th>Celular</th>
              <th>Sede</th>
              <th>Rol</th>
              <th>Estado</th>
              <th v-if="$root.validatePermission('user-status') || $root.validatePermission('user-update')">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in userList.data" :key="user.id">
              <td>{{ user.id }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.last_name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.document }}</td>
              <td>{{ user.phone }}</td>
              <td>{{ user.headquarter.headquarter }}</td>

              <td>
                {{ user.roles[0].name }}
              </td>

              <td>
                <span v-if="user.status == 1">Activo</span>
                <span v-if="user.status == 0">Inactivo</span>
              </td>

              <td class="text-right" v-if="$root.validatePermission('user-status') || $root.validatePermission('user-update')">
                <button
                  v-if="user.status == 1 && $root.validatePermission('user-update')"
                  class="btn btn-primary"
                  @click="showData(user)"
                >
                  <i class="bi bi-pen"></i>
                </button>
                <div class="d-inline">
                  <button
                    v-if="user.status == 1"
                    class="btn btn-danger"
                    @click="changeStatus(user.id)"
                  >
                    <i class="bi bi-trash"></i>
                  </button>
                  <button
                    v-if="user.status == 0"
                    class="btn btn-success"
                    @click="changeStatus(user.id)"
                  >
                    <i class="bi bi-check2-circle"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="userList"
          :limit="8"
          @pagination-change-page="listUsers"
        >
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"
            ><i class="bi bi-chevron-double-right"></i
          ></span>
        </pagination>
      </section>
    </div>
    <create-edit-user ref="CreateEditUser" @list-users="listUsers(1)" />
  </div>
</template>

<script>
import CreateEditUser from "./CreateEditUser.vue";
export default {
  components: { CreateEditUser },
  data() {
    return {
      search_user: "",
      userList: {},
    };
  },
  created() {
    this.listUsers(1);
  },
  methods: {
    listUsers(page = 1) {
      let me = this;
      axios
        .get(`api/users?page=${page}&user=${this.search_user}`, me.$root.config)
        .then(function (response) {
          me.userList = response.data;
        });
    },
    showData: function (ususario) {
      this.$refs.CreateEditUser.showEditarUser(ususario);
    },
    changeStatus: function (id) {
      let me = this;

      Swal.fire({
        title: "¿Quieres cambiar el status del usuario?",
        showDenyButton: true,
        denyButtonText: `Cancelar`,
        confirmButtonText: `Guardar`,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post("api/users/" + id + "/change-status", null, me.$root.config)
            .then(function () {
              me.listUsers(1);
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
