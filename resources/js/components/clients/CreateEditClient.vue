<template>
  <div>
    <div
      class="modal fade"
      id="formClientModal"
      tabindex="-1"
      aria-labelledby="formClientModalLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="formClientModalLabel">Clients</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              @click="(editar = false), resetData()"
              aria-label="Close"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form>
              <div class="form-row">
                <div class="form-group col-md-4">
                  <label for="nombres">Nombres</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nombres"
                    v-model="formClient.nombres"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="Apellidos">Apellidos</label>
                  <input
                    type="text"
                    class="form-control"
                    id="Apellidos"
                    v-model="formClient.apellidos"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="fecha_nacimiento">Fecha nacimiento</label>
                  <input
                    type="date"
                    class="form-control"
                    id="fecha_nacimiento"
                    v-model="formClient.fecha_nacimiento"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="tipo_documento">Tipo Documento</label>
                  <select
                    name="tipo_documento"
                    id="tipo_documento"
                    class="custom-select"
                    v-model="formClient.tipo_documento"
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
                    v-model="formClient.nro_documento"
                  />
                </div>

                <div class="form-group col-md-4">
                  <label for="tipo_documento">Estado civil</label>
                  <select
                    name="estado_civil"
                    id="estado_civil"
                    class="custom-select"
                    v-model="formClient.estado_civil"
                  >
                    <option value="0" disabled>--Seleccionar--</option>
                    <option value="Soltero">Soltero</option>
                    <option value="Casado">Casado</option>
                    <option value="Union libre">Union libre</option>
                    <option value="Divorciado">Divorciado</option>
                    <option value="Viudo">Viudo</option>
                  </select>
                </div>

                <div class="form-group">
                  <label for="genero">Género</label>
                  <br />
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="inlineRadioOptions"
                      id="Hombre"
                      value="option1"
                      v-model="formClient.genero"
                    />
                    <label class="form-check-label" for="Hombre">Hombre</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="inlineRadioOptions"
                      id="Mujer"
                      value="option2"
                      v-model="formClient.genero"
                    />
                    <label class="form-check-label" for="Mujer">Mujer</label>
                  </div>
                  <div class="form-check form-check-inline">
                    <input
                      class="form-check-input"
                      type="radio"
                      name="inlineRadioOptions"
                      id="Otro"
                      value="option3"
                      v-model="formClient.genero"
                    />
                    <label class="form-check-label" for="Otro">Otro</label>
                  </div>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-4">
                  <label for="email">Correo Electronico</label>
                  <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="formClient.email"
                  />
                </div>
                <div class="form-group col-4">
                  <label for="direccion">Dirección</label>
                  <input
                    type="text"
                    class="form-control"
                    id="direccion"
                    v-model="formClient.direccion"
                  />
                </div>
                <div class="form-group col-4">
                  <label for="celular1">Celular 1</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="celular1"
                    v-model="formClient.celular1"
                  />
                </div>
                <div class="form-group col-4">
                  <label for="celular2">Celular 2</label>
                  <input
                    type="tel"
                    class="form-control"
                    id="celular2"
                    v-model="formClient.celular2"
                  />
                </div>
                <!-- </div>
              <div class="form-row"> -->
                <div class="form-group col-md-4">
                  <label for="lugar_trabajo">Lugar de trabajo</label>
                  <input
                    type="email"
                    class="form-control"
                    id="lugar_trabajo"
                    v-model="formClient.lugar_trabajo"
                  />
                </div>
                <div class="form-group col-md-4">
                  <label for="cargo">Cargo</label>
                  <input
                    type="text"
                    class="form-control"
                    id="cargo"
                    v-model="formClient.cargo"
                  />
                </div>
                <div class="form-check col-md-4 ml-4">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    value="1"
                    id="independiente"
                    v-model="formClient.independiente"
                  />
                  <label class="form-check-label" for="independiente">
                    Independiente
                  </label>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-secondary"
              data-dismiss="modal"
              @click="(editar = false), resetData()"
            >
              Cerrar
            </button>
            <button
              type="button"
              class="btn btn-primary rounded"
              @click="formClient.id ? editarClient() : crearClient()"
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
      formClient: {
        nombres: "",
        apellidos: "",
        tipo_documento: 0,
        nro_documento: 0,
        fecha_nacimiento: "",
        email: "",
        celular1: "",
        celular2: "",
        genero: "",
        number: "",
        estado_civil: "",
        independiente: 0,
        lugar_trabjo: "",
        cargo: "",
      },
    };
  },
  // Function crearClients
  methods: {
    crearClient() {
      let me = this;
      axios.post("api/clients", this.formClient).then(function () {
        $("#formClientModal").modal("hide");
        me.resetData();
        me.$emit("listar-clients");
      });
    },
    abirEditarClient(client) {
      this.editar = true;
      let me = this;
      $("#formClientModal").modal("show");
      me.formClient = client;
    },
    editarClient() {
      let me = this;
      axios
        .put("api/clients/" + this.formClient.id, this.formClient)
        .then(function () {
          $("#formClientModal").modal("hide");
          me.resetData();
        });
      me.$emit("listar-clients");

      me.editar = false;
    },
    resetData() {
      let me = this;
      Object.keys(this.formClient).forEach(function (key, index) {
        me.formClient[key] = "";
      });
    },
  },
};
</script>