<template>
  <div class="page">
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Roles</h3>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#rolModal"
        @click="$refs.CreateEditRol.ResetData()"
        v-if="$root.validatePermission('rol-store')"
      >
        Crear Rol
      </button>
    </div>
    <div class="page-content mt-4" style="width: 100%">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead class="thead-primary">
            <tr>
              <th scope="col">#</th>
              <th scope="col">Rol</th>
              <th v-if="$root.validatePermission('rol-update')">
                Opciones
              </th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="(rol, index) in roleList.data"
              :key="rol.id"
            >
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ rol.name }}</td>
              <td v-if="$root.validatePermission('rol-update')">
                <button
                  class="btn btn-outline-primary"
                  @click="ShowData(rol)"
                >
                  <i class="bi bi-pen"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="roleList"
          @pagination-change-page="listRoles"
        >
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav">
            <i class="bi bi-chevron-double-right"></i>
          </span>
        </pagination>
      </section>
    </div>

    <create-edit-rol ref="CreateEditRol" @list-roles="listRoles(1)" />
  </div>
</template>

<script>

  import CreateEditRol from "./CreateEditRol.vue";

  export default {
    name: "Roles",
    components: { CreateEditRol },
    data() {
      return {
        roleList: {},
      };
    },
    created() {
      this.listRoles(1);
    },
    methods: {
      listRoles(page = 1) {
        let me = this;
        axios
          .get("api/roles?page=" + page, this.$root.config)
          .then(function (response) {
            me.roleList = response.data.roles;
          })
      },
    
      ShowData: function (rol) {
        this.$refs.CreateEditRol.OpenEditRol(rol);
      }
    }
  };
</script>
