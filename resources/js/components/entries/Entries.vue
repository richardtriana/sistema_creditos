<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3 class="col-4">Ingresos</h3>
      <ul class="list-group col-4">
        <li class="list-group-item"><h5 class="text-dark font-weight-bold">Total ingresos: {{entriesTotal.price | currency}}</h5></li>
      </ul>
      <button type="button" class="btn btn-primary col-4" data-toggle="modal" data-target="#entryModal"
        v-if="$root.validatePermission('entry-store')">
        Crear Ingreso
      </button>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form>
        <div class="form-row">
          <div class="form-group col-md-4">
            <label for="search_id">Nro. de ingreso:</label>
            <input type="text" id="search_id" name="search_id" class="form-control" placeholder="Nro de ingreso"
              v-model="search_id" />
          </div>
          <div class="form-group col-md-4">
            <label for="search_client">Cliente:</label>
            <input type="text" id="search_client" name="search_client" class="form-control" placeholder="Nombre cliente"
              v-model="search_client" />
          </div>
          <div class="form-group col-md-4">
            <label for="search_document">Documento:</label>
            <input type="text" id="search_document" name="search_document" class="form-control"
              placeholder="Documento del cliente" v-model="search_document" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4 ml-md-auto">
            <label for="search_type_input">Tipo de ingreso:</label>
            <input type="text" id="search_type_input" name="search_type_input" class="form-control"
              placeholder="Escribir el tipo de ingreso" v-model="search_type_input" />
          </div>
          <div class="form-group col-md-4 mr-md-auto">
            <label for="search_description">Descripción:</label>
            <input type="text" id="search_description" name="search_description" class="form-control"
              placeholder="Escribir el motivo o descripción" v-model="search_description" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group col-md-4 ml-auto">
            <label for="">Desde:</label>
            <input type="date" id="search_from" name="search_from" class="form-control" placeholder="Desde"
              v-model="search_from" :max="now" />
          </div>
          <div class="form-group col-md-4 mr-auto">
            <label for="">Hasta:</label>
            <input type="date" id="search_to" name="search_to" class="form-control" placeholder="Desde"
              v-model="search_to" :max="now" />
          </div>
        </div>
        <div class="form-row text-right m-auto">
          <div class="form-group m-md-auto col-md-4">
            <button class="btn btn-success w-100" type="button" @click="listEntries(1)">
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
              <th>Sede</th>
              <th>Responsable</th>
              <th>Fecha</th>
              <th>Tipo de entrada</th>
              <th>Descripción</th>
              <th>Valor</th>
              <th>Ver Factura</th>
              <th v-if="$root.validatePermission('entry-update')">
                Opciones
              </th>
            </tr>
          </thead>
          <tbody v-if="entryList.data && entryList.data.length > 0">
            <tr v-for="e in entryList.data" :key="e.id">
              <td>{{ e.headquarter.headquarter }}</td>
              <td class="wrap">{{ e.user.name }} {{ e.user.last_name }}</td>
              <td>{{ e.date }}</td>

              <td>{{ e.type_entry }}</td>
              <td class="h6 font-weight-normal">
                <textarea name="" class="form-control-plaintext" readonly id="" cols="10" rows="8"
                  v-model="e.description">
                  </textarea>
              </td>
              <td class="text-right">{{ e.price | currency }}</td>
              <td class="text-right">
                <button class="btn btn-danger btn-block" type="button" @click="printEntryPdf(e.id)">
                  <i class="bi bi-file-pdf-fill"></i> PDF
                </button>
                <br>
                <button class="btn btn-success btn-block" type="button" @click="printEntryTicket(e.id)">
                  <i class="bi bi-receipt-cutoff"></i> Ticket
                </button>
              </td>
              <td class="text-right" v-if="$root.validatePermission('entry-update')">
                <button v-if="!e.credit_id" class="btn btn-success" @click="showData(e)">
                  <i class="bi bi-pen"></i>
                </button>
                <button v-else class="btn btn-secondary" data-toggle="tooltip" data-placement="top" 
                  title="No se pueden editar las entradas asociadas a créditos" disabled>
                  <i class="bi bi-pen"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination :align="'center'" :data="entryList" :limit="2" @pagination-change-page="listEntries">
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"><i class="bi bi-chevron-double-right"></i></span>
        </pagination>
      </section>
    </div>
    <modal-entry ref="ModalEntry" @list-entries="listEntries(1)" />

  </div>
</template>

<script>
import ModalEntry from './ModalEntry.vue';
export default {
  components: { ModalEntry },
  data() {
    return {
      entryList: {},
      entriesTotal: {},
      search_id: "",
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

      let data = {
        page: page,
        client: this.search_client,
        document: this.search_document,
        from: this.search_from,
        to: this.search_to,
        type_input: this.search_type_input,
        description: this.search_description,
        id: this.search_id,
      }

      let me = this;
      axios
        .get(
          `api/entries`, {
          params: data,
          headers: me.$root.config.headers,
        })
        .then(function (response) {
          me.entryList = response.data.entries;
          me.entriesTotal = response.data.totals;
        });
    },
    showData: function (entry) {
      this.$refs.ModalEntry.showEditEntry(entry);
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
      axios.get(`api/print-entry/${id}`, this.$root.config);
    },
  },
};
</script>
