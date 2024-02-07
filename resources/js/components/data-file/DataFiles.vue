<template>
	<div class="page">
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Biblioteca de archivos</h3>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#formDataFileModal">
				Crear archivo
			</button>
		</div>

		<div class="page-search p-4 border my-2">
			<h6 class="text-primary text-uppercase">Filtrar:</h6>
			<form>
				<div class="form-row">
					<div class="form-group col-md-4 col-sm-6 col-xs-6">
						<label for="search_file">Buscar archivo...</label>
						<input type="text" id="search_file" name="search_file" class="form-control"
							placeholder="Nombre o descripción del archivo" v-model="search_file" />
					</div>
					<div class="form-group col-md-4 col-sm-6 col-xs-6">
						<label for="">Mostrar {{ search_results }} resultados por página:</label>
						<input type="number" id="search_results" name="search_results" class="form-control"
							placeholder="Desde" v-model="search_results" max="1000" />
					</div>
					<div class="form-group col-md-4 col-sm-6 col-xs-6 ">
						<button class="btn btn-success w-100 mt-5" type="button" @click="listDataFiles(1)">
							<i class="bi bi-search"></i> Buscar
						</button>
					</div>
					<div class="form-group offset-md-8 col-md-4 col-sm-6 col-xs-6">
						<download-excel class="btn btn-primary w-100" :fields="json_fields" :data="providerList.data"
							name="list-data-files.xls" type="xls">
							<i class="bi bi-file-earmark-arrow-down-fill"></i> Descargar .xls
						</download-excel>
					</div>
				</div>

			</form>
		</div>
		<div class="page-content mt-4">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>ID</th>
							<th>Título</th>
							<th>Archivo Original</th>
							<th>Descripción</th>
							<th>Categoria</th>
							<th>Ruta</th>
							<th>Fecha de creación</th>
							<th>Metadata</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="p in providerList.data" :key="p.id">
							<td>{{ p.id }}</td>
							<td>{{ p.title }}</td>
							<td>
								<a :href="p.url_path + p.file" target="_blank">Descargar Archivo
									<i class="bi bi-eye"></i>
								</a>
							</td>
							<td>{{ p.description }}</td>
							<td>{{ p.category }}</td>
							<td>{{ p.url_path }}</td>
							<td>{{ p.created_at }}</td>
							<td style="max-width: 300px;">
								<pre>{{ JSON.parse(p.metadata) }}</pre>
							</td>
						</tr>
					</tbody>
				</table>
				<pagination :align="'center'" :data="providerList" :limit="10" @pagination-change-page="listDataFiles">
					<span slot="prev-nav">&lt; Anterior</span>
					<span slot="next-nav">Siguiente &gt;</span>
				</pagination>
			</section>
		</div>
		<create-edit-data-file ref="CreateEditDataFile" @list-data-files="listDataFiles(1)" />
	</div>
</template>
<script>
import CreateEditDataFile from "./CreateEditDataFile.vue";
export default {
	components: { CreateEditDataFile },
	data() {
		return {
			providerList: {},
			search_file: "",
			search_results: 10,
			json_fields: {
				'Archivo': {
					field: 'file',
					callback: (value) => {
						return value;
					}
				},
				'Nombre': {
					field: 'title',
					callback: (value) => {
						return value;
					}
				},
				'Categoría': {
					field: 'category',
					callback: (value) => {
						return value;
					}
				},
				'Descripción': {
					field: 'description',
					callback: (value) => {
						return value;
					}
				},
				'Ruta': {
					field: 'url_path',
					callback: (value) => {
						return value;
					}
				},
				'fecha de creación': {
					field: 'created_at',
					callback: (value) => {
						return value;
					}
				},
				'Metadata': {
					field: 'metadata',
					callback: (value) => {
						return value;
					}
				}
			}

		};
	},
	created() {
		this.listDataFiles(1);
	},
	methods: {
		listDataFiles(page = 1) {
			let me = this;

			let data = {
				page: page,
				search_file: this.search_file,
				results: this.search_results
			}

			axios.get(`api/files`, {
				params: data,
				headers: me.$root.config.headers,
			}).then(function (response) {
				me.providerList = response.data.dataFiles;
			});
		},
		showData: function (provider) {
			this.$refs.CreateEditDataFile.showEditDataFile(provider);
		},
		changeStatus: function (id) {
			let me = this;

			Swal.fire({
				title: "¿Quieres cambiar el status del archivo?",
				showDenyButton: true,
				denyButtonText: `Cancelar`,
				confirmButtonText: `Guardar`,
			}).then((result) => {
				if (result.isConfirmed) {
					axios
						.post(`api/providers/${id}/change-status`, null, me.$root.config)
						.then(function () {
							me.listDataFiles(1);
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
