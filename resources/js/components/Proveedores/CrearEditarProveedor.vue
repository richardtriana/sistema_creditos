<template>
  <div>
    <div
      class="modal fade"
      id="formProveedorModal"
      tabindex="-1"
      aria-labelledby="formProveedorModalLabel"
      aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formProveedorModalLabel">Modal proveedores</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              @click="editar = false"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="name">Nombres</label>
                  <input
                    type="text"
                    class="form-control"
                    id="name"
                    v-model="formProveedor.name"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="Apellidos">Apellidos</label>
                  <input
                    type="text"
                    class="form-control"
                    id="Apellidos"
                    v-model="formProveedor.last_name"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="type_document">Tipo Documento</label>
                  <select
                    name="type_document"
                    id="type_document"
                    class="custom-select"
                    v-model="formProveedor.type_document"
                  >
                    <option value="0" disabled>--Seleccionar--</option>
                    <option value="1">Cédula de ciudadanía</option>
                    <option value="2">Passaporte</option>
                  </select>
                </div>
                <div class="form-group col-md-4">
                  <label for="Apellidos">Nro. Documento</label>

                  <input
                    type="number"
                    class="form-control"
                    id="Documento"
                    v-model="formProveedor.document_number"
                  />
                </div>

                <div class="form-group col-4">
                  <label for="cell_phone1">Celular 1</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="cell_phone1"
                    v-model="formProveedor.cell_phone1"
                  />
                </div>
                <div class="form-group col-4">
                  <label for="cell_phone2">Celular 2</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="cell_phone2"
                    v-model="formProveedor.cell_phone2"
                  />
                </div>

                <div class="form-group col-4">
                  <label for="email">Correo Electronico</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="formProveedor.email"
                  />
                </div>
                <div class="form-group col-4">
                  <label for="address">Dirección</label>
                  <input
                    type="text"
                    class="form-control"
                    id="address"
                    v-model="formProveedor.address"
                  />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="editar = false"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary rounded"
              @click="editar ? editarProveedor() : crearProveedor()"
            >
              Guardar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      editar: false,
      formProveedor: {
        name: "",
        last_name: "",
        type_document: 0,
        document_number: 0,
        cell_phone1: "",
        cell_phone2: "",
        email: "",
      },
    };
  },

  methods: {
    crearProveedor() {
      let me = this;
      axios.post("api/proveedores", this.formProveedor).then(function () {
        $("#formProveedorModal").modal("hide");
        me.formProveedor = {};
        this.$emit("listar-proveedores");
      });
    },
    abirEditarProveedor(proveedor) {
      this.editar = true;
      let me = this;
      $("#formProveedorModal").modal("show");
      me.formProveedor = proveedor;
    },
    editarProveedor() {
      let me = this;
      axios
        .put("api/proveedores/" + this.formProveedor.id, this.formProveedor)
        .then(function () {
          $("#formProveedorModal").modal("hide");
          me.formProveedor = {};
        });
      this.$emit("listar-proveedores");

      this.editar = false;
    },
  },
};
</script>