<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Sedes</h3>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#formHeadquarterModal"
        v-if="$root.validatePermission('headquarter-store')"
      >
        Crear Sede
      </button>
    </div>
    <div class="page-search d-flex justify-content-between p-4 border my-2">
      <div class="form-group col-8 m-auto">
        <!-- <label for="search_headquarter">Buscar...</label> -->
        <input
          type="text"
          id="search_headquarter"
          name="search_headquarter"
          class="form-control"
          placeholder="Buscar sede"
          @keypress="listHeadquarters(1)"
          v-model="search_headquarter"
        />
      </div>
    </div>
    <div class="page-content mt-4">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th>id</th>
              <th>Sede</th>
              <th>NIT</th>
              <th>Correo Contacto</th>
              <th>Representante</th>
              <th>Celular Contacto</th>
              <th v-if="$root.validatePermission('headquarter-status')">Estado</th>
              <th v-if="$root.validatePermission('headquarter-update')">Opciones</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="headquarter in headquarterList.data"
              :key="headquarter.id"
            >
              <td>{{ headquarter.id }}</td>
              <td>{{ headquarter.headquarter }}</td>
              <td>{{ headquarter.nit }}</td>
              <td>{{ headquarter.email }}</td>
              <td>{{ headquarter.legal_representative }}</td>
              <td>{{ headquarter.phone }}</td>
              <td class="text-right" v-if="$root.validatePermission('headquarter-status')">
                <button
                  v-if="headquarter.status"
                  class="btn btn-danger"
                  @click="changeStatus(headquarter.id)"
                >
                  <i class="bi bi-trash"></i>
                </button>
                <button
                  v-else
                  class="btn btn-success"
                  @click="changeStatus(headquarter.id)"
                >
                  <i class="bi bi-check2-circle"></i>
                </button>
              </td>
              <td class="text-right" v-if="$root.validatePermission('headquarter-update')">
                <button
                  v-if="headquarter.status"
                  class="btn btn-primary"
                  @click="showData(headquarter)"
                >
                  <i class="bi bi-pen"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="headquarterList"
          :limit="8"
          @pagination-change-page="listHeadquarters"
        >
          <span slot="prev-nav">&lt; Previous</span>
          <span slot="next-nav">Next &gt;</span>
        </pagination>
      </section>
    </div>
    <create-edit-headquarter
      ref="CreateEditHeadquarter"
      @list-headquarters="listHeadquarters(1)"
    />
  </div>
</template>
<script>
import CreateEditHeadquarter from "./CreateEditHeadquarter.vue";
export default {
  components: { CreateEditHeadquarter },
  data() {
    return {
      search_headquarter: "",
      headquarterList: {},
    };
  },
  created() {
    this.listHeadquarters(1);
  },
  methods: {
    listHeadquarters(page = 1) {
      let me = this;
      axios
        .get(
          `api/headquarters?page=${page}&headquarter=${this.search_headquarter}`,
          me.$root.config
        )
        .then(function (response) {
          me.headquarterList = response.data;
        });
    },
    showData: function (headquarter) {
      this.$refs.CreateEditHeadquarter.showEditHeadquarter(headquarter);
    },
    changeStatus: function (id) {
      let me = this;

      Swal.fire({
        title: "¿Quieres cambiar el estado de la sede?",
        showDenyButton: true,
        denyButtonText: `Cancelar`,
        confirmButtonText: `Guardar`,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post(`api/headquarters/${id}/change-status`, null, me.$root.config)
            .then(function () {
              me.listHeadquarters(1);
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
