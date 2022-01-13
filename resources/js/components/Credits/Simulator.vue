<template>
    <div class="">
        <div class="card-body">
            <div>
                <div class="">
                    <button
                        type="button"
                        class="btn btn-primary col-md-3 offset-9 mb-5"
                        id="btnCalcular"
                        @click="simularInstallments()"
                    >
                        Calcular
                    </button>

                    <table id="lista-tabla" class="table">
                        <thead>
                            <tr>
                                <th>Nro.Installment</th>
                                <th>Fecha</th>
                                <th>Installment</th>
                                <th>Capital</th>
                                <th>Interés</th>
                                <th>Saldo Capital</th>
                            </tr>
                        </thead>
                        <tbody v-if="listInstallments.length">
                            <tr
                                v-for="installment in listInstallments"
                                :key="installment.nro_cuota"
                            >
                                <td>
                                    No. {{ installment.nro_cuota }}
                                </td>
                                <td>
                                   $ {{ installment.payment_date }}
                                </td>
                                <td>{{ installment.valor_cuota }}</td>
                                <td>
                                   $ {{ installment.pagoCapital }}
                                </td>
                                <td>
                                    ${{ installment.pagoInteres }}
                                </td>
                                <td>
                                    $ {{ installment.saldo_capital }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <!-- <button
                type="button"
                class="btn btn-primary rounded"
                @click="editar = crearSimulator()"
            >
                Guardar
            </button> -->
        </div>
    </div>
</template>

<script>
import moment from "moment";

export default {
    props: ["number_installments", "interest", "capital"],
    // capital value total del prestamo
    // tasa value de tasa de interest que se compraria
    // plazos numero de pagos
    data() {
        return {
            editar: false,
            listInstallments: [],
            formInstallments: {},
            valor_cuota: 0
        };
    },
    methods: {
        crearSimulator() {
            let me = this;
            axios.post("api/installments", this.installments).then(function() {
                $("#formSimulatorModal").modal("hide");
                me.resetData();
                this.$emit("list-credits");
            });
        },
        abrirSimulator(credit) {
            this.editar = true;
            let me = this;
            $("#formSimulatorModal").modal("show");
            me.formSimulator = credit;
        },
        editarSimulator() {
            let me = this;
            axios.put("api/credits/" + 4, this.installments).then(function() {
                $("#formSimulatorModal").modal("hide");
                me.resetData();
            });
            this.$emit("list-credits");

            this.editar = false;
        },
        simular: function(id) {
            let me = this;
            axios
                .post("api/credits/" + id + "/installments", null, me.$root.config)
                .then(function() {
                    me.listarCredits(1);
                });
        },
        resetData() {
            let me = this;
            Object.keys(this.installments).forEach(function(key, index) {
                me.installments[key] = "";
            });
        },
        agregar() {
            this.formInstallments.push(newInstallment);
            console.log(agregar);
        },
        simularInstallments() {
            let me = this;
            axios
                .get(
                    `api/installments/calculate-installments?credit_value=${this.capital}&interest=${this.interest}&number_installments=${this.number_installments}`
                )
                .then(
                    response => (
                        (me.listInstallments = response.data.listInstallments),
                        (me.valor_cuota = response.data.installment)
                        
                    )
                );
        }
    },
    mounted() {
        // this.simularInstallments();
    }
};
</script>
