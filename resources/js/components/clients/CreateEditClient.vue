<template>
	<div class="modal-content">
		<div class="modal-header">
			<h5 class="modal-title" id="formClientModalLabel">Clientes</h5>
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
						<label for="name">Nombres</label>
						<input
							type="text"
							class="form-control"
							id="name"
							v-model="formClient.name"
						/>
					</div>
					<div class="form-group col-md-4">
						<label for="Apellidos">Apellidos</label>
						<input
							type="text"
							class="form-control"
							id="Apellidos"
							v-model="formClient.last_name"
						/>
					</div>
					<div class="form-group col-md-4">
						<label for="birth_date">Fecha nacimiento</label>
						<input
							type="date"
							class="form-control"
							id="birth_date"
							v-model="formClient.birth_date"
						/>
					</div>
					<div class="form-group col-md-4">
						<label for="type_document">Tipo Documento</label>
						<select
							name="type_document"
							id="type_document"
							class="custom-select"
							v-model="formClient.type_document"
						>
							<option value="0" disabled>--Seleccionar--</option>
							<option value="1">Cédula de ciudadanía</option>
							<option value="2">Passaporte</option>
						</select>
					</div>
					<div class="form-group col-md-4">
						<label for="Apellidos">Nro. Documento</label>

						<input
							type="number"
							class="form-control"
							id="Documento"
							v-model="formClient.document"
						/>
					</div>

					<div class="form-group col-md-4">
						<label for="type_document">Estado civil</label>
						<select
							name="civil_status"
							id="civil_status"
							class="custom-select"
							v-model="formClient.civil_status"
						>
							<option value="0" disabled>--Seleccionar--</option>
							<option value="Soltero">Soltero</option>
							<option value="Casado">Casado</option>
							<option value="Union libre">Union libre</option>
							<option value="Divorciado">Divorciado</option>
							<option value="Viudo">Viudo</option>
						</select>
					</div>

					<div class="form-group">
						<label for="gender">Género</label>
						<br />
						<div class="form-check form-check-inline">
							<input
								class="form-check-input"
								type="radio"
								name="inlineRadioOptions"
								id="Hombre"
								value="M"
								v-model="formClient.gender"
							/>
							<label class="form-check-label" for="Hombre">Hombre</label>
						</div>
						<div class="form-check form-check-inline">
							<input
								class="form-check-input"
								type="radio"
								name="inlineRadioOptions"
								id="Mujer"
								value="F"
								v-model="formClient.gender"
							/>
							<label class="form-check-label" for="Mujer">Mujer</label>
						</div>
						<div class="form-check form-check-inline">
							<input
								class="form-check-input"
								type="radio"
								name="inlineRadioOptions"
								id="Otro"
								value="O"
								v-model="formClient.gender"
							/>
							<label class="form-check-label" for="Otro">Otro</label>
						</div>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-4">
						<label for="email">Correo Electronico</label>
						<input
							type="email"
							class="form-control"
							id="email"
							v-model="formClient.email"
						/>
					</div>
					<div class="form-group col-4">
						<label for="address">Dirección</label>
						<input
							type="text"
							class="form-control"
							id="address"
							v-model="formClient.address"
						/>
					</div>
					<div class="form-group col-4">
						<label for="phone_1">Celular 1</label>
						<input
							type="tel"
							class="form-control"
							id="phone_1"
							v-model="formClient.phone_1"
						/>
					</div>
					<div class="form-group col-4">
						<label for="phone_2">Celular 2</label>
						<input
							type="tel"
							class="form-control"
							id="phone_2"
							v-model="formClient.phone_2"
						/>
					</div>

					<div class="form-group col-md-4">
						<label for="workplace">Lugar de trabajo</label>
						<input
							type="email"
							class="form-control"
							id="workplace"
							v-model="formClient.workplace"
						/>
					</div>
					<div class="form-group col-md-4">
						<label for="occupation">Cargo</label>
						<input
							type="text"
							class="form-control"
							id="occupation"
							v-model="formClient.occupation"
						/>
					</div>
					<div class="form-check col-md-4 ml-4">
						<input
							class="form-check-input"
							type="checkbox"
							value="1"
							id="independent"
							v-model="formClient.independent"
						/>
						<label class="form-check-label" for="independent">
							independent
						</label>
					</div>
				</div>
			</form>
		</div>
		<div class="modal-footer">
			<button
				type="button"
				class="btn btn-secondary"
				data-dismiss="modal"
				@click="(editar = false), resetData()"
			>
				Cerrar
			</button>
			<button
				type="button"
				class="btn btn-primary rounded"
				@click="formClient.id ? editClient() : createClient()"
			>
				Guardar
			</button>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			editar: false,
			formClient: {
				name: "",
				last_name: "",
				type_document: 0,
				document: 0,
				birth_date: "",
				email: "",
				phone_1: "",
				phone_2: "",
				gender: "",
				civil_status: "",
				independent: 0,
				workplace: "",
				occupation: "",
				address: "",
			},
		};
	},
	methods: {
		createClient() {
			let me = this;
			axios.post("api/clients", this.formClient).then(function () {
				$("#formClientModal").modal("hide");
				me.resetData();
				me.$emit("list-clients");
			});
		},
		showEditClient(client) {
			this.editar = true;
			let me = this;
			$("#formClientModal").modal("show");
			me.formClient = client;
		},
		editClient() {
			let me = this;
			axios
				.put(`api/clients/${this.formClient.id}`, this.formClient)
				.then(function () {
					$("#formClientModal").modal("hide");
					me.resetData();
				});
			me.$emit("list-clients");

			me.editar = false;
		},
		resetData() {
			let me = this;
			Object.keys(this.formClient).forEach(function (key, index) {
				me.formClient[key] = "";
			});
		},
	},
};
</script>
