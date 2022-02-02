<template>
  <div
    class="modal fade"
    id="expenseModal"
    tabindex="-1"
    aria-labelledby="expenseModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <create-edit-expense
        ref="CreateEditExpense"
        @list-expenses="listExpenses()"
      />
    </div>
  </div>
</template>

<script>
import CreateEditExpense from "./CreateEditExpense.vue";
export default {
  components: { CreateEditExpense },
  methods: {
    listExpenses() {
      this.$emit("list-expenses");
    },

    showEditExpense(expense) {
      this.$refs.CreateEditExpense.showEditExpense(expense);
    },
  },
};
</script>
