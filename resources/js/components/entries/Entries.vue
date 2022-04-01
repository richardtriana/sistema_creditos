<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Ingresos</h3>
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
              <td>
                <pre
                  class="h6 font-weight-normal"
                > <p>{{ e.description }}</p></pre>
              </td>

              <td class="text-right">
                <button
                  class="btn btn-info"
                  type="button"
                  @click="
                    printEntryPdf(e.id)
                  "
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
      search_entry: "",
      entryList: {},
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
          `api/entries?page=${page}&entry=${this.search_entry}`,
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
