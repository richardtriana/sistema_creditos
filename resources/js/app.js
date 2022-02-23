/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

// window.Vue = require('vue').default;
import Vue from "vue";
import VueRouter from "vue-router";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import { dollarFilter } from "./filters";

import "vue-select/dist/vue-select.css";

import CKEditor from "@ckeditor/ckeditor5-vue2";
import axios from "axios";

Vue.filter("currency", dollarFilter);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component("pagination", require("laravel-vue-pagination"));
Vue.component("v-select", vSelect);

Vue.use(VueRouter);
Vue.use(CKEditor);

// Vue.use(Swal);
window.Swal = Swal;

const routes = [
	{ path: "", component: require("./components/Front/Home.vue").default },
	{
		path: "/clients",
		component: require("./components/clients/Clients.vue").default,
	},
	{
		path: "/users",
		component: require("./components/users/Users.vue").default,
	},
	{
		path: "/providers",
		component: require("./components/providers/Providers.vue").default,
	},
	{
		path: "/credits",
		component: require("./components/credits/AllCredits.vue").default,
		name: "credits",
		children: [
			{
				path: "credit-clients",
				component: require("./components/credits/credit_clients/Credits.vue")
					.default,
				name: "credit-clients",
			},
			{
				path: "credit_clients/:credit_id/installments",
				component:
					require("./components/credits/credit_helpers/Installment.vue")
						.default,
				props: true,
				name: "installments",
			},
			{
				path: "credit-providers",
				component:
					require("./components/credits/credit_providers/CreditProviders.vue")
						.default,
				name: "credit-providers",
			},
			{
				path: "outsanding-credits",
				component:
					require("./components/credits/outstanding_credits/Disbursements.vue")
						.default,
				name: "outsanding-credits",
			},
		],
	},

	{
		path: "/headquarters",
		component: require("./components/headquarters/Headquarters.vue").default,
	},
	{
		path: "/company",
		component: require("./components/configurations/Company.vue").default,
	},
	{
		path: "/boxes",
		component: require("./components/boxes/Boxes.vue").default,
	},
	{
		path: "/expenses",
		component: require("./components/expenses/Expenses.vue").default,
	},
	{
		path: "/entries",
		component: require("./components/entries/Entries.vue").default,
	},
	{
		path: "/reports",
		component: require("./components/reports/ReportsDashboard.vue").default,
		name: "reports",
		children: [
			{
				path: "portfolio",
				component: require("./components/reports/ReportPortfolio.vue").default,
				name: "report-portfolio",
			},
			{
				path: "credit",
				component: require("./components/reports/ReportCreditsGeneral.vue")
					.default,
				name: "report-general-credits",
			},
		],
	},
];

const router = new VueRouter({
	routes, // short for `routes: routes`
	linkActiveClass: "active",
});
export default router;

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
	el: "#app",
	router,
	data: {
		current_balance_main_box: 0,
		type_documents: {
			CC: "Cédula de ciudadania",
			NIT: "NIT",
			TI: "Tarjeta de identidad",
			PP: "Pasaporte",
			CE: "Cédula de extranjería",
		},
	},
	methods: {
		getCurrentBalanceMainBox() {
			axios.get(`api/main-box/current-balance`).then((response) => {
				return (this.current_balance_main_box = response.data);
			});
		},
	},
	mounted() {
		this.getCurrentBalanceMainBox();
	},
});
