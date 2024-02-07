<template>
    <div>
        <div class="modal fade" id="formDataFileModal" tabindex="-1" aria-labelledby="formDataFileModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formDataFileModalLabel">
                            Cargar Archivo
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" @click="(editar = false)"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formSendFile">
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file"
                                        aria-describedby="inputFile" @change="onFileChange" @input="onFileChange">
                                    <label class="custom-file-label" for="inputFileUpload">{{ formDataFile.title }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="title">Título del archivo</label>
                                <input type="text" v-model="formDataFile.title" class="form-control" id="title" name="title"
                                    placeholder="No se ha seleccionado archivos" readonly />
                            </div>
                            <div class="form-group">
                                <label for="description">Descripción</label>
                                <input type="text" class="form-control" name="description" id="description"
                                    v-model="formDataFile.description" placeholder="Descripción" />
                            </div>
                            <div class="form-group">
                                <label for="category">Categoría</label>
                                <input type="text" class="form-control" id="category" name="category"
                                    v-model="formDataFile.category" placeholder="Categoría" />
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" type="button" @click="submitForm()">Guardar</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="editar = false">
                            Cerrar
                        </button>
                        <button type="button" class="btn btn-primary" @click="editar ? editDataFile() : createDataFile()">
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
            formDataFile: {
                title: "",
                description: "",
                category: "",
                file: ""
            },
            formErrors: {
                title: "",
                description: "",
                category: "",
                file: ""
            },
            type_documents: this.$root.$data.type_documents,
        };
    },

    methods: {
        onFileChange(e) {
            const file = e.target.files[0];
            this.formDataFile.title = file.name;
        },
        createDataFile() {
            let me = this;
            me.$root.assignErrors(false, me.formErrors);

            let config = this.$root.config.headers;

            let headers = {
                'content-type': "multipart/form-data",
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }

            Object.assign(config, headers)

            let fd = new FormData($("#formSendFile")[0]);

            axios
                .post("api/files", fd, { headers: config })
                .then(function () {
                    $("#formDataFileModal").modal("hide");
                    me.formDataFile = {};
                    me.$emit("list-data-files");
                })
                .catch((response) => {
                    me.$root.assignErrors(response, me.formErrors);
                });
        },
        showEditDataFile(provider) {
            this.editar = true;
            let me = this;
            $("#formDataFileModal").modal("show");
            me.formDataFile = provider;
        },
        editDataFile() {
            let me = this;
            me.$root.assignErrors(false, me.formErrors);
            axios
                .put(
                    "api/files/" + this.formDataFile.id,
                    this.formDataFile,
                    me.$root.config
                )
                .then(function () {
                    $("#formDataFileModal").modal("hide");
                    me.formDataFile = {};
                    me.$emit("list-data-files");
                })
                .catch((response) => {
                    me.$root.assignErrors(response, me.formErrors);
                });

            this.editar = false;
        },
    },
};
</script>
  