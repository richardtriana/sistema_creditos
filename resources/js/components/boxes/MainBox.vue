<template>
	<div class="card">
		<div class="card-header text-center">
			<h5>Caja principal</h5>
		</div>
		<div class="card-body">
			<form>
				<div class="form-group row">
					<label for="saldo_inicial" class="col-sm-4 col-form-label"
						>Saldo inicial</label
					>
					<div class="col-sm-8">
						<input
							type="text"
							readonly
							class="form-control"
							id="saldo_inicial"
							value="$"
						/>
					</div>
				</div>

				<div class="form-group row">
					<label for="saldo_inicial" class="col-sm-4 col-form-label"
						>Saldo actual</label
					>
					<div class="col-sm-8">
						<input
							type="text"
							readonly
							class="form-control"
							id="saldo_inicial"
							value="$"
						/>
					</div>
				</div>

				<div class="form-group row">
					<label for="saldo_inicial" class="col-sm-4 col-form-label"
						>Entradas</label
					>
					<div class="col-sm-8">
						<input
							type="text"
							readonly
							class="form-control"
							id="saldo_inicial"
							value="$"
						/>
					</div>
				</div>
				<div class="form-group row">
					<label for="saldo_inicial" class="col-sm-4 col-form-label"
						>Salidas</label
					>
					<div class="col-sm-8">
						<input
							type="text"
							readonly
							class="form-control"
							id="saldo_inicial"
							value="$"
						/>
					</div>
				</div>
				<div class="form-group row">
					<label for="saldo_inicial" class="col-sm-4 col-form-label"
						>Añadir saldo</label
					>
					<div class="col-sm-8">
						<input
							type="text"
							class="form-control"
							id="saldo_inicial"
							value="$"
						/>
					</div>
				</div>
				<div class="w-100 text-center">
					<button class="btn btn-primary" style="min-width:30%;">Guardar</button>
				</div>
			</form>
		</div>
	</div>
</template>

<script>
export default {};
</script>

<style></style>
