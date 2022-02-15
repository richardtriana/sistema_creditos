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
				<create-edit-client ref="CreateEditClient" @list-clients="listClients()" />
			</div>
		</div>
	</div>
</template>

<script>
import CreateEditClient from "./CreateEditClient.vue";
export default {
	components: { CreateEditClient },
	data() {
		return {};
	},
	methods: {
		listClients() {
			this.$emit("list-clients");
		},

		showEditClient(client) {
			this.$refs.CreateEditClient.showEditClient(client);
		},
	},
};
</script>
