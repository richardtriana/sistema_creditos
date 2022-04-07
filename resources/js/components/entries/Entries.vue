<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Ingresos</h3>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form>
        <div class="form-row">
          <div class="form-group col-md-4 ml-md-auto">
            <label for="search_client">Cliente:</label>
            <input
              type="text"
              id="search_client"
              name="search_client"
              class="form-control"
              placeholder="Nombre cliente"
              v-model="search_client"
            />
          </div>
          <div class="form-group col-md-4 mr-md-auto">
            <label for="search_document">Documento:</label>
            <input
              type="text"
              id="search_document"
              name="search_document"
              class="form-control"
              placeholder="Documento del cliente"
              v-model="search_document"
            />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4 ml-md-auto">
            <label for="search_type_input">Tipo de ingreso:</label>
            <input
              type="text"
              id="search_type_input"
              name="search_type_input"
              class="form-control"
              placeholder="Escribir el tipo de ingreso"
              v-model="search_type_input"
            />
          </div>
          <div class="form-group col-md-4 mr-md-auto">
            <label for="search_description">Descripción:</label>
            <input
              type="text"
              id="search_description"
              name="search_description"
              class="form-control"
              placeholder="Escribir el motivo o descripción"
              v-model="search_description"
            />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4 ml-auto">
            <label for="">Desde:</label>
            <input
              type="date"
              id="search_from"
              name="search_from"
              class="form-control"
              placeholder="Desde"
              v-model="search_from"
              :max="now"
            />
          </div>
          <div class="form-group col-md-4 mr-auto">
            <label for="">Hasta:</label>
            <input
              type="date"
              id="search_to"
              name="search_to"
              class="form-control"
              placeholder="Desde"
              v-model="search_to"
              :max="now"
            />
          </div>
        </div>
        <div class="form-row text-right m-auto">
          <div class="form-group m-md-auto col-md-4">
            <button
              class="btn btn-success w-100"
              type="button"
              @click="listEntries(1)"
            >
              <i class="bi bi-search"></i> Buscar
            </button>
          </div>
        </div>
      </form>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead>
            <tr class="text-center">
              <th>Responsable</th>
              <th>Fecha</th>
              <th>Valor</th>
              <th>Tipo de entrada</th>
              <th>Descripción</th>
              <th>Ver Factura</th>
            </tr>
          </thead>
          <tbody v-if="entryList.data && entryList.data.length > 0">
            <tr v-for="e in entryList.data" :key="e.id">
              <td class="wrap">{{ e.user.name }} {{ e.user.last_name }}</td>
              <td>{{ e.date }}</td>
              <td class="text-right">{{ e.price | currency }}</td>
              <td>{{ e.type_entry }}</td>
              <td class="h6 font-weight-normal">
                <textarea
                  name=""
                  class="form-control-plaintext"
                  readonly
                  id=""
                  cols="10"
                  rows="8"
                  v-model="e.description"
                >
                </textarea>
              </td>

              <td class="text-right">
                <button
                  class="btn btn-info"
                  type="button"
                  @click="printEntryPdf(e.id)"
                >
                  <i class="bi bi-eye"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="entryList"
          :limit="2"
          @pagination-change-page="listEntries"
        >
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"
            ><i class="bi bi-chevron-double-right"></i
          ></span>
        </pagination>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      entryList: {},
      search_client: "",
      search_document: "",
      search_from: "",
      search_to: "",
      search_description: "",
      search_type_input: "",
      now: new Date().toISOString().slice(0, 10),
    };
  },
  mounted() {
    this.listEntries(1);
  },
  methods: {
    listEntries(page = 1) {
      let me = this;
      axios
        .get(
          `api/entries?page=${page}&client=${this.search_client}&document=${this.search_document}&from=${this.search_from}&to=${this.search_to}&type_input=${this.search_type_input}&description=${this.search_description}`,
          me.$root.config
        )
        .then(function (response) {
          me.entryList = response.data;
        });
    },
    printEntryPdf(entry_id) {
      axios
        .get(`api/entries/show-entry/${entry_id}`, this.$root.config)
        .then((response) => {
          const pdf = response.data.pdf;
          var a = document.createElement("a");
          a.href = "data:application/pdf;base64," + pdf;
          a.download = `entrada_${entry_id}-${Date.now()}.pdf`;
          a.click();
        });
    },
    printEntryTicket(id) {
      axios.get(`api/print-entry/${id}`);
    },
  },
};
</script>
