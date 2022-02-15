<template>
	<div>
		<div
			class="modal fade"
			id="formHeadquarterModal"
			tabindex="-1"
			aria-labelledby="formHeadquarterModalLabel"
			aria-hidden="true"
		>
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="formHeadquarterModalLabel">Sedes</h5>
						<button
							type="button"
							class="close"
							data-dismiss="modal"
							@click="(editar = false), resetData()"
							aria-label="Close"
						>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form>
							<div class="form-row">
								<div class="form-group col-md-4">
									<label for="headquarter">Sede</label>
									<input
										type="text"
										class="form-control"
										id="headquarter"
										v-model="formHeadquarter.headquarter"
										required
										:class="[formErrors.headquarter ? 'is-invalid' : '']"
									/>
									<small id="headquarterHelp" class="form-text text-danger">{{
										formErrors.headquarter
									}}</small>
								</div>
								<div class="form-group col-md-4">
									<label for="address">Dirección</label>
									<input
										type="text"
										class="form-control"
										id="address"
										v-model="formHeadquarter.address"
										required
									/>
									<small id="addressHelp" class="form-text text-danger">
										{{ formErrors.address }}
									</small>
								</div>
								<div class="form-group col-md-4">
									<label for="nit">NIT</label>
									<input
										type="text"
										class="form-control"
										id="nit"
										v-model="formHeadquarter.nit"
									/>
									<small id="nitHelp" class="form-text text-danger">
										{{ formErrors.nit }}
									</small>
								</div>
								<div class="form-group col-md-4">
									<label for="email">Correo Contacto</label>
									<input
										type="email"
										class="form-control"
										id="email"
										v-model="formHeadquarter.email"
										:class="[formErrors.email ? 'is-invalid' : '']"
									/>
									<small id="emailHelp" class="form-text text-danger">
										{{ formErrors.email }}
									</small>
								</div>

								<div class="form-group col-md-4">
									<label for="legal_representative">Representante</label>

									<input
										type="text"
										class="form-control"
										id="legal_representative"
										v-model="formHeadquarter.legal_representative"
									/>
								</div>

								<div class="form-group col-md-4">
									<label for="phone">Celular Contacto</label>

									<input
										type="number"
										class="form-control"
										id="phone"
										v-model="formHeadquarter.phone"
									/>
								</div>
								<div class="form-group col-md-4">
									<label for="pos_printer">Impresora POS</label>
									<input
										type="email"
										class="form-control"
										id="pos_printer"
										v-model="formHeadquarter.pos_printer"
										:class="[formErrors.pos_printer ? 'is-invalid' : '']"
									/>
									<small id="posPrinterHelp" class="form-text text-danger">
										{{ formErrors.pos_printer }}
									</small>
								</div>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button
							type="button"
							class="btn btn-secondary"
							data-dismiss="modal"
							@click="editar = false"
						>
							Cerrar
						</button>
						<button
							type="button"
							class="btn btn-primary rounded"
							@click="editar ? editHeadquarter() : createHeadquarter()"
						>
							Guardar
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			editar: false,
			formHeadquarter: {
				headquarter: "",
				legal_representative: "",
				pos_printer: "",
				phone: "",
				address: "",
				email: "",
				nit: "",
				status: "1",
			},
			formErrors: {
				headquarter: "",
				legal_representative: "",
				pos_printer: "",
				phone: "",
				address: "",
				email: "",
				nit: "",
				status: "1",
			},
		};
	},
	// Function createHeadquarters
	methods: {
		createHeadquarter() {
			let me = this;
			me.assignErrors(false);
			axios
				.post("api/headquarters", this.formHeadquarter)
				.then(function () {
					$("#formHeadquarterModal").modal("hide");
					me.resetData();
					me.$emit("list-headquarters");
				})
				.catch((response) => {
					me.assignErrors(response);
				});
		},
		showEditHeadquarter(headquarter) {
			this.editar = true;
			let me = this;
			$("#formHeadquarterModal").modal("show");
			me.formHeadquarter = headquarter;
		},
		editHeadquarter() {
			let me = this;
			axios
				.put(
					`api/headquarters/${this.formHeadquarter.id}`,
					this.formHeadquarter
				)
				.then(function () {
					$("#formHeadquarterModal").modal("hide");
					me.resetData();
				})
				.catch((response) => {
					me.assignErrors(response);
				});
			me.$emit("list-headquarters");
			me.editar = false;
		},
		resetData() {
			let me = this;
			Object.keys(this.formHeadquarter).forEach(function (key, index) {
				me.formHeadquarter[key] = "";
			});
			me.$emit("list-headquarters");
		},
		assignErrors(response) {
			const fillable = [
				"headquarter",
				"status",
				"address",
				"nit",
				"email",
				"legal_representative",
				"phone",
				"pos_printer",
			];

			if (response) {
				var errors = response.response.data.errors;
				fillable.forEach((index) => {
					if (errors[index] != undefined) {
						this.formErrors[index] = errors[index][0];
					}
				});
			} else {
				fillable.forEach((index) => {
					this.formErrors[index] = "";
				});
			}
		},
	},
};
</script>
