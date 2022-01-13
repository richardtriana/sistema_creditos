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
                        <h5 class="modal-title" id="formHeadquarterModalLabel">
                            Sedes
                        </h5>
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
                                    />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="address">Dirección</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="address"
                                        v-model="formHeadquarter.address"
                                    />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="nit">NIT</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nit"
                                        v-model="formHeadquarter.nit"
                                    />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="email"
                                        >Correo Contacto</label
                                    >
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="email"
                                        v-model="formHeadquarter.email"
                                    />
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="legal_representative"
                                        >Representante</label
                                    >

                                    <input
                                        type="text"
                                        class="form-control"
                                        id="legal_representative"
                                        v-model="formHeadquarter.legal_representative"
                                    />
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="cell_phone"
                                        >Celular Contacto</label
                                    >

                                    <input
                                        type="number"
                                        class="form-control"
                                        id="cell_phone"
                                        v-model="formHeadquarter.cell_phone"
                                    />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="pos_printer"
                                        >Impresora POS</label
                                    >
                                    <input
                                        type="email"
                                        class="form-control"
                                        id="pos_printer"
                                        v-model="formHeadquarter.pos_printer"
                                    />
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
                name: "",
                email: "",
                password: "",
                cell_phone: "",
                address: "",
                type_document: 0,
                document: 0,
                photo: "",
                status: "1",
                id_rol: "",
                headquarter_id: "",
                pos_printer: ""
            }
        };
    },
    // Function createHeadquarters
    methods: {
        createHeadquarter() {
            let me = this;
            axios.post("api/headquarters", this.formHeadquarter).then(function() {
                $("#formHeadquarterModal").modal("hide");
                me.resetData();
                me.$emit("list-headquarters");
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
                .put(`api/headquarters/${this.formHeadquarter.id}`, this.formHeadquarter)
                .then(function() {
                    $("#formHeadquarterModal").modal("hide");
                    me.resetData();
                });
            me.$emit("list-headquarters");

            me.editar = false;
        },
        resetData() {
            let me = this;
            Object.keys(this.formHeadquarter).forEach(function(key, index) {
                me.formHeadquarter[key] = "";
            });
        }
    }
};
</script>
