<template>
    <div class="page">
        <div class="page-header">
            <h3>Reporte calificativo de Cliente</h3>
        </div>
        <div class="page-search border my-2">
            <form>
                <div class="form-row">
                    <div class="form-group col-md-9 col-sm-6 col-xs-6 ">
                        <label for="search_client">Cliente: </label>
                        <input type="text" id="search_client_document" name="search_client_document" class="form-control"
                            placeholder="Buscar cliente | Documento" v-model="search_client_document" />
                    </div>

                    <div class="form-group col-md-3 col-sm-6 col-xs-6">
                        <button class="btn btn-success w-100 mt-5" type="button" @click="listReportGeneralClient()">
                            Buscar
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="card w-100 text-center" v-if="ReportRatingClientList.length > 0">
            <h4 class="text-primary"> {{ client.name }} {{ client.last_name }} - {{ client.document }} </h4>
            <h6>CALIFICACIÓN GENERAL:</h6>
            <h5 class="p-3 rounded rounded-pill" :style="{ backgroundColor: reportValuationGeneral.color }">{{
                reportValuationGeneral.valuation }}</h5>
        </div>
        <div class="page-content">
            <section class="table-responsive">
                <table class="table table-sm table-bordered">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th>ID crédito</th>
                            <th>Monto Crédito</th>
                            <th>Mora registrada</th>
                            <th>Descripción</th>
                            <th>Calificación</th>
                        </tr>
                    </thead>
                    <tbody v-if="ReportRatingClientList.length > 0">
                        <tr v-for="(report, index) in ReportRatingClientList" :key="index">
                            <span class="hidden" v-show="false"> {{ report.valuation= getValuation(report.days_past_due_avg)
                            }}</span>
                            <td>{{ index + 1 }}</td>
                            <td>{{ report.id }}</td>
                            <td class="text-right">{{ report.credit_value | currency }}</td>
                            <td class="text-right">{{ report.days_past_due }} días</td>
                            <td class="text-right">{{ report.description }}</td>

                            <td class="text-center" :style="{ backgroundColor: report.valuation.color }">{{
                                report.valuation.valuation }} </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr class="text-center">
                            <td colspan="12" class="alert alert-warning text-center">
                                Buscar cliente
                            </td>
                        </tr>
                    </tbody>
                </table>
            </section>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            ReportRatingClientList: [],
            GeneralRatingClient: 0,
            search_client_document: "",
            search_results: 15,
            valuationChart: [],
            client: [],
            reportValuationGeneral: []
        };
    },
    created() {
        this.getValuationChart()
    },
    methods: {
        listReportGeneralClient() {
            let data = {
                document: this.search_client_document,
                results: this.search_results
            }
            axios
                .get(
                    `api/reports/rating-client`,
                    {
                        params: data,
                        headers: this.$root.config.headers,
                    }
                )
                .then((response) => {
                    if (response.data) {
                        this.ReportRatingClientList = response.data.credits;
                        this.GeneralRatingClient = response.data.total_credits,
                        this.client = response.data.client
                        this.reportValuationGeneral = this.getValuation(response.data.total_credits)
                    }
                });
        },
        getValuationChart() {
            axios.get("api/valuation-chart", this.$root.config)
                .then((response) => {
                    if (response.data) {
                        this.valuationChart = response.data.valuationChart
                    }
                });
        },
        getValuation(daysPastDue) {

            for (const valuation of this.valuationChart) {
                if (daysPastDue >= valuation.start && daysPastDue <= valuation.end) {
                    return valuation;
                }
            }
            return this.valuationChart[3];
        }
    },
    mounted() {
        // this.listReportGeneralClient();
        this.getValuation()
    },
};
</script>
