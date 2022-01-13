<template>
    <div>
        <div
            class="modal fade"
            id="formCreditModal"
            tabindex="-1"
            aria-labelledby="formCreditModalLabel"
            aria-hidden="true"
        >
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formCreditModalLabel">
                            Credits
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
                                    <label for="client_id">Client</label>
                                    <v-select
                                        :options="listaClients.data"
                                        label="document_number"
                                        aria-logname="{}"
                                        :reduce="name => name.id"
                                        v-model="formCredit.client_id"
                                        placeholder="Buscar por Documento"
                                    >
                                    </v-select>
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="debtor">Deudor</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="debtor"
                                        v-model="formCredit.debtor"
                                    />
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="sede_id">Sede</label>
                                    <v-select
                                        :options="listaSedes.data"
                                        label="sede"
                                        aria-logname="{}"
                                        :reduce="sede => sede.id"
                                        v-model="formCredit.sede_id"
                                    >
                                    </v-select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="credit_value"
                                        >Valor Credit</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="credit_value"
                                        step="any"
                                        v-model="formCredit.credit_value"
                                    />
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="interest">Interes</label>
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="interest"
                                        v-model="formCredit.interest"
                                        step="any"
                                    />
                                </div>

                                <div class="form-group col-md-4">
                                    <label for="number_installments"
                                        >Cantidad Installments</label
                                    >
                                    <input
                                        type="number"
                                        class="form-control"
                                        id="number_installments"
                                        v-model="formCredit.number_installments"
                                    />
                                </div>
                            </div>
                            <simulator
                                :capital="formCredit.credit_value"
                                :interest="formCredit.interest"
                                :number_installments="formCredit.number_installments"
                                ref="Simulator"
                            ></simulator>
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
                                @click="
                                    editar ? editarCredit() : crearCredit()
                                "
                            >
                                Guardar
                            </button>
                        </form>
                    </div>
                    <div class="modal-footer"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Simulator from "./Simulator.vue";
export default {
    components: { Simulator },
    data() {
        return {
            editar: false,
            listaSedes: [],
            listaClients: [],
            formCredit: {
                client_id: "",
                debtor_id: 2,
                sede_id: "",
                number_installments: "",
                number_paid_installments: "",
                number_paid_installments: "",
                day_limit: "",
                debtor: "",
                status: "1",
                fecha_inicio: "",
                interest: "",
                annual_interest_percentage: "",
                usu_crea: 2,
                calor_cuota: "",
                credit_value: "",
                paid_value: "",
                capital_value: "",
                interest_value: ""
            }
        };
    },
    created() {
        this.listarSedes(1);
        this.listarClients(1);
    },
    // Function crearCredits
    methods: {
        listarSedes(page = 1) {
            let me = this;
            axios.get(`api/sedes?page=${page}`).then(function(response) {
                me.listaSedes = response.data;
            });
        },

        listarClients(page = 1) {
            let me = this;
            axios.get(`api/clients?page=${page}`).then(function(response) {
                me.listaClients = response.data;
            });
        },
        crearCredit() {
            let me = this;
            axios.post("api/credits", this.formCredit).then(function() {
                $("#formCreditModal").modal("hide");
                me.resetData();
                this.$emit("listar-credits");
            });
        },
        abirEditarCredit(credit) {
            this.editar = true;
            let me = this;
            $("#formCreditModal").modal("show");
            me.formCredit = credit;
        },
        editarCredit() {
            let me = this;
            axios
                .put("api/credits/" + this.formCredit.id, this.formCredit)
                .then(function() {
                    $("#formCreditModal").modal("hide");
                    me.resetData();
                });
            this.$emit("listar-credits");

            this.editar = false;
        },
        resetData() {
            let me = this;
            Object.keys(this.formCredit).forEach(function(key, index) {
                me.formCredit[key] = "";
            });
        }
    }
};
</script>
