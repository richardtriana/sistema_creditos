<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Reporte de egresos por sede</h3>
    </div>
    <div class="page-search p-4 border my-2">
      <h6 class="text-primary text-uppercase">Filtrar:</h6>
      <form>
        <div class="form-row">
          <div class="form-group col-4 ml-auto">
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
          <div class="form-group col-4 mr-auto">
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
              @click="listHeadquartersExpenses(1)"
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
              <th>Sede</th>
              <th>Valor</th>
            </tr>
          </thead>
          <tbody
            v-if="
              headquartersExpenseList.data &&
              headquartersExpenseList.data.length > 0
            "
          >
            <tr v-for="r in headquartersExpenseList.data" :key="r.id">
              <td>{{ r.headquarter }}</td>
              <td class="text-right">{{ r.price | currency }}</td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="headquartersExpenseList"
          :limit="2"
          @pagination-change-page="listHeadquartersExpenses"
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
      headquartersExpenseList: {},
      search_from: "",
      search_to: "",
      now: moment().format('YYYY-MM-DD'),
    };
  },
  mounted() {
    this.listHeadquartersExpenses(1);
  },
  methods: {
    listHeadquartersExpenses(page = 1) {
      let me = this;
      axios
        .get(
          `api/reports/headquarters-expenses?page=${page}&from=${this.search_from}&to=${this.search_to}`,
          this.$root.config
        )
        .then(function (response) {
          me.headquartersExpenseList = response.data;
        });
    },
  },
};
</script>
