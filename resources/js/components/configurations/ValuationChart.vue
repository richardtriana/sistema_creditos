<template id="main">
    <div class="page">
        <div class="page-header d-flex justify-content-between p-4 border my-2">
            <h3>Tabla de calificación</h3>

        </div>
        <div class="page-content mt-4" style="width: 100%">
            <section class="table-responsive w-100">

                <table class="table">
                    <thead>
                        <tr>
                            <td></td>
                            <th class="text-center" colspan="2">Días de mora</th>
                            <td></td>
                        </tr>
                        <tr>
                            <th>Valoración</th>
                            <th>Desde</th>
                            <th>Hasta</th>
                            <th>Color</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="valuation in valuationChartList" :key="valuation.index">
                            <td>
                                <input class="form-control" type="text" name="valuation" id="valuation"
                                    v-model="valuation.valuation">
                            </td>
                            <td>
                                <input class="form-control" type="number" name="start" id="start" v-model="valuation.start">
                            </td>
                            <td>
                                <input class="form-control" type="number" name="end" id="end" v-model="valuation.end">
                            </td>
                            <td>
                                <input class="form-control" type="color" name="color" id="color" v-model="valuation.color">
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <button class="btn btn-success" type="button" @click="setValuationChart()">Guardar</button>
                    </tfoot>
                </table>
            </section>
        </div>

    </div>
</template>

<script>
export default {
    data() {
        return {
            valuationChartList: []
        }
    },
    created() {
        this.getValuationChart()
    },

    methods: {
        getValuationChart() {
            axios.get("api/valuation-chart", this.$root.config)
                .then((response) => {
                    if (response.data.valuationChart) {
                        this.valuationChartList = response.data.valuationChart
                    }
                });
        },

        setValuationChart() {
            axios.post("api/valuation-chart", { data: this.valuationChartList }, this.$root.config)
                .then((response) => {
                    if (response.data.valuationChart) {
                        this.valuationChartList = response.data.valuationChart
                    }
                });
        }
    },
}
</script>