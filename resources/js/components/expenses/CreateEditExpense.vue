<template>
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="expenseModalLabel">Egresos</h5>
			<button
				type="button"
				class="close"
				data-dismiss="modal"
				aria-label="Close"
			>
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
		<form>
			<div class="modal-body">
				<div class="form-group">
					<label for="description">Descripción</label>
					<input
						type="text"
						class="form-control"
						id="description"
						placeholder="Example input placeholder"
					/>
				</div>
				<div class="form-group">
					<label for="date">Fecha</label>
					<input
						type="date"
						class="form-control"
						id="date"
						placeholder="Another input placeholder"
					/>
				</div>
				<div class="form-group">
					<label for="type_output"></label>
					<select class="custom-select" id="type_output">
						<option selected>Open this select menu</option>
						<option value="1">One</option>
						<option value="2">Two</option>
						<option value="3">Three</option>
					</select>
				</div>
				<div class="form-group">
					<div class="form-group">
						<label for="price">Precio</label>
						<input
							type="number"
							step="any"
							class="form-control"
							id="price"
							placeholder="$"
						/>
					</div>
				</div>
				<!-- 	<v-select
										:options="headquarterList.data"
										label="headquarter"
										aria-logname="{}"
										:reduce="(headquarter) => headquarter.id"
										v-model="formCredit.headquarter_id"
									>
									</v-select> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">
					Close
				</button>
				<button type="button" class="btn btn-primary">Save changes</button>
			</div>
		</form>
	</div>
</template>

<script>
export default {};
</script>
