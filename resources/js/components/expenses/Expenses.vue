<template>
  <div>
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Egresos</h3>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#expenseModal"
        v-if="$root.validatePermission('expense-store')"
      >
        Crear Egreso
      </button>
    </div>
    <div class="page-content">
      <section class="table-responsive">
        <table class="table table-bordered table-sm">
          <thead>
            <tr class="text-center">
              <th>Motivo</th>
              <th>Responsable</th>
              <th>Fecha</th>
              <th>Tipo de Salida</th>
              <th>Valor</th>
              <th v-if="$root.validatePermission('expense-status')">Estado</th>
              <th v-if="$root.validatePermission('expense-update')">Opciones</th>
            </tr>
          </thead>
          <tbody v-if="expenseList.data && expenseList.data.length > 0">
            <tr v-for="e in expenseList.data" :key="e.id">
              <td class="wrap">{{ e.description }}</td>
              <td>{{ e.user_id }}</td>
              <td>{{ e.date }}</td>
              <td>{{ e.type_output }}</td>
              <td class="text-right">{{ e.price | currency }}</td>
              <td class="text-right" v-if="$root.validatePermission('expense-status')">
                <button
                  v-if="e.status == 0"
                  class="btn btn-danger"
                  @click="changeStatus(e.id)"
                >
                  <i class="bi bi-x-circle"></i>
                </button>
                <button
                  v-if="e.status == 1"
                  class="btn btn-success"
                  @click="changeStatus(e.id)"
                >
                  <i class="bi bi-check2-circle"></i>
                </button>
              </td>
              <td class="text-right" v-if="$root.validatePermission('expense-update')">
                <button
                  v-if="e.status == 1"
                  class="btn btn-success"
                  @click="showData(e)"
                >
                  <i class="bi bi-pen"></i>
                </button>
                <button v-else class="btn btn-secondary" disabled>
                  <i class="bi bi-pen"></i>
                </button>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="expenseList"
          :limit="2"
          @pagination-change-page="listExpenses"
        >
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"
            ><i class="bi bi-chevron-double-right"></i
          ></span>
        </pagination>
      </section>
    </div>
    <modal-expense ref="ModalExpense" @list-expenses="listExpenses(1)" />
  </div>
</template>

<script>
import ModalExpense from "./ModalExpense.vue";
export default {
  components: { ModalExpense },
  data() {
    return {
      search_expense: "",
      expenseList: {},
    };
  },
  mounted() {
    this.listExpenses(1);
  },
  methods: {
    listExpenses(page = 1) {
      let me = this;
      axios
        .get(`api/expenses?page=${page}&expense=${this.search_expense}`, me.$root.config)
        .then(function (response) {
          me.expenseList = response.data;
        });
    },
    showData: function (expense) {
      this.$refs.ModalExpense.showEditExpense(expense);
    },
    changeStatus: function (id) {
      let me = this;

      Swal.fire({
        title: "¿Quieres cambiar el estado de este egreso?",
        showDenyButton: true,
        denyButtonText: `Cancelar`,
        confirmButtonText: `Guardar`,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post(`api/expenses/${id}/change-status`, null, me.$root.config)
            .then(function () {
              me.listExpenses(1);
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
