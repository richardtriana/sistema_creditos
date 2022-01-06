<template>
    <div class="page">
        <div class="page-header text-center">
            <h2>SImulador de Crédito</h2>
            <p>Puedes hacer una simulacion de tu crédito</p>
        </div>
        <div class="page-content">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="monto">Monto</label>
                    <input
                        required
                        type="number"
                        class="form-control"
                        id="monto"
                        v-model="capital"
                        step="any"
                        placeholder="Ingresar monto"
                    />
                </div>
                <div class="form-group col-md-4">
                    <label for="tiempo">Tiempo en Meses</label>
                    <input
                        required
                        type="number"
                        class="form-control"
                        id="tiempo"
                        v-model="cant_cuotas"
                        placeholder="Ingresar cantidad de meses"
                    />
                </div>
                <div class="form-group col-md-4">
                    <label for="interes">Interés Mensual</label>
                    <input
                        required
                        type="number"
                        class="form-control"
                        id="interes"
                        v-model="interes"
                        step="any"
                        placeholder="Ingresar tasa de interés mensual"
                    />
                </div>
            </div>

            <simulator
                :capital="capital"
                :interes="interes"
                :cant_cuotas="cant_cuotas"
                ref="Simulator"
            ></simulator>
        </div>
    </div>
</template>

<script>
import Simulator from "../credits/Simulator.vue";
export default {
    components: { Simulator },
    data() {
        return {
            interes: 3,
            capital: 500000,
            cant_cuotas: 12
        };
    },
    methods: {
        simularCredit: function() {
            this.$refs.Simulator.abrirSimulator();
        }
    },
    mounted() {
        console.log("Component mounted.");
    }
};
</script>
