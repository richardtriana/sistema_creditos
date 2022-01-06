<template>
    <div class="">
        <div class="card-body">
            <div>
                <div class="">
                    <button
                        type="button"
                        class="btn btn-primary col-md-3 offset-9 mb-5"
                        id="btnCalcular"
                        @click="simularFees()"
                    >
                        Calcular
                    </button>

                    <table id="lista-tabla" class="table">
                        <thead>
                            <tr>
                                <th>Nro.Fee</th>
                                <th>Fecha</th>
                                <th>Fee</th>
                                <th>Capital</th>
                                <th>Interés</th>
                                <th>Saldo Capital</th>
                            </tr>
                        </thead>
                        <tbody v-if="listFees.length">
                            <tr
                                v-for="fee in listFees"
                                :key="fee.nro_cuota"
                            >
                                <td>
                                    No. {{ fee.nro_cuota }}
                                </td>
                                <td>
                                   $ {{ fee.fecha_pago }}
                                </td>
                                <td>{{ fee.valor_cuota }}</td>
                                <td>
                                   $ {{ fee.pagoCapital }}
                                </td>
                                <td>
                                    ${{ fee.pagoInteres }}
                                </td>
                                <td>
                                    $ {{ fee.saldo_capital }}
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
    props: ["cant_cuotas", "interes", "capital"],
    // capital valor total del prestamo
    // tasa valor de tasa de interes que se compraria
    // plazos numero de pagos
    data() {
        return {
            editar: false,
            listFees: [],
            formFees: {},
            valor_cuota: 0
        };
    },
    methods: {
        crearSimulator() {
            let me = this;
            axios.post("api/fees", this.fees).then(function() {
                $("#formSimulatorModal").modal("hide");
                me.resetData();
                this.$emit("listar-credits");
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
            axios.put("api/credits/" + 4, this.fees).then(function() {
                $("#formSimulatorModal").modal("hide");
                me.resetData();
            });
            this.$emit("listar-credits");

            this.editar = false;
        },
        simular: function(id) {
            let me = this;
            axios
                .post("api/credits/" + id + "/fees", null, me.$root.config)
                .then(function() {
                    me.listarCredits(1);
                });
        },
        resetData() {
            let me = this;
            Object.keys(this.fees).forEach(function(key, index) {
                me.fees[key] = "";
            });
        },
        agregar() {
            this.formFees.push(newFee);
            console.log(agregar);
        },
        simularFees() {
            let me = this;
            axios
                .get(
                    `api/fees/calcular-fees?valor_credit=${this.capital}&interes=${this.interes}&cant_cuotas=${this.cant_cuotas}`
                )
                .then(
                    response => (
                        (me.listFees = response.data.listFees),
                        (me.valor_cuota = response.data.fee)
                        
                    )
                );
        }
    },
    mounted() {
        // this.simularFees();
    }
};
</script>
