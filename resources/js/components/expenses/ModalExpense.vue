<template>
	<div
		class="modal fade"
		id="expenseModal"
		tabindex="-1"
		aria-labelledby="expenseModalLabel"
		aria-hidden="true"
	>
		<div class="modal-dialog">
			<create-edit-expense />
		</div>
	</div>
</template>

<script>
import CreateEditExpense from "./CreateEditExpense.vue";
export default {
	components: { CreateEditExpense },
};
</script>
