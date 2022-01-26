<template>
	<div>
		<div
			class="modal fade"
			id="boxModal"
			tabindex="-1"
			aria-labelledby="boxModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="boxModalLabel">Sede</h5>
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
								<label for="exampleInputEmail1">Saldo disponible</label>
								<input
									type="number"
									step="any"
									class="form-control"
									id="exampleInputEmail1"
									aria-describedby="emailHelp"
								/>
							</div>
							<div class="form-group">
								<label for="exampleFormControlSelect1">Sede</label>
								<select class="form-control" id="exampleFormControlSelect1">
									<option>1</option>
									<option>2</option>
									<option>3</option>
									<option>4</option>
									<option>5</option>
								</select>
							</div>
							<div class="form-group">
								<label for="anadir_saldo">Añadir saldo</label>
								<input
									type="number"
									step="any"
									class="form-control"
									id="anadir_saldo"
								/>
							</div>
						</div>
						<div class="modal-footer">
							<button
								type="button"
								class="btn btn-secondary"
								data-dismiss="modal"
							>
								Close
							</button>
							<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
export default {};
</script>
