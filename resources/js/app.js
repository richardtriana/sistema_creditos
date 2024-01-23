/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

//Services
import utils from './services/utils.js';
import moment from "moment";

// window.Vue = require('vue').default;
import Vue from "vue";
import VueRouter from "vue-router";
import Swal from "sweetalert2";
import vSelect from "vue-select";
import { dollarFilter, affectation} from "./filters";
import JsonExcel from "vue-json-excel";

import "vue-select/dist/vue-select.css";

import CKEditor from "@ckeditor/ckeditor5-vue2";
import axios from "axios";

Vue.filter("currency", dollarFilter);
Vue.filter("affectation", affectation);

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
Vue.component("downloadExcel", JsonExcel);

Vue.use(VueRouter);
Vue.use(CKEditor);


// Vue.use(Swal);
window.Swal = Swal;
window.moment = moment;

const routes = [

	{
		path: "",
		component: require("./components/Front/Home.vue").default,
		name: "Home"
	},
	{
		path: "/login",
		component: require("./components/auth/Login.vue").default,
		name: "Login"
	},
	{
		path: "/clients",
		component: require("./components/clients/Clients.vue").default,
		alias: "client-index"
	},
	{
		path: "/guarantees",
		component: require("./components/guarantees/Guarantees.vue").default,
		alias: "guarantee-index"
	},
	{
		path: "/products",
		component: require("./components/products/Products.vue").default,
		alias: "product-index"
	},
	{
		path: '/roles',
		name: 'Roles',
		component: require("./components/roles/Roles.vue").default,
		alias: "rol-index"
	},
	{
		path: "/users",
		component: require("./components/users/Users.vue").default,
		alias: "user-index"
	},
	{
		path: "/providers",
		component: require("./components/providers/Providers.vue").default,
		alias: "provider-index"
	},
	{
		path: "/credits",
		component: require("./components/credits/AllCredits.vue").default,
		name: "credits",
		alias: "credit-index",
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
			}
		],
	},
	{
		path: "/headquarters",
		component: require("./components/headquarters/Headquarters.vue").default,
		alias: "headquarter-index"
	},
	{
		path: "/company",
		component: require("./components/configurations/Company.vue").default,
	},
	{
		path: "/valuation-chart",
		component: require("./components/configurations/ValuationChart.vue").default,
	},
	{
		path: "/boxes",
		component: require("./components/boxes/Boxes.vue").default,
		alias: "box-index"
	},
	{
		path: "/expenses",
		component: require("./components/expenses/Expenses.vue").default,
		alias: "expense-index"
	},
	{
		path: "/entries",
		component: require("./components/entries/Entries.vue").default,
	},
	{
		path: "/reports",
		component: require("./components/reports/ReportsDashboard.vue").default,
		name: "reports",
		alias: "report",
		children: [
			{
				path: "portfolio",
				component: require("./components/reports/ReportPortfolio.vue").default,
				name: "report-portfolio",
			},
			{
				path: "general-credits",
				component: require("./components/reports/ReportCreditsGeneral.vue")
					.default,
				name: "report-general-credits",
			},
			{
				path: "headquarters",
				component: require("./components/reports/ReportHeadquarters.vue")
					.default,
				name: "report-headquarters",
			},
			{
				path: "general-client",
				component: require("./components/reports/ReportGeneralClient.vue")
					.default,
				name: "report-general-client",
			},
			{
				path: "headquarters-expenses",
				component: require("./components/reports/ReportHeadquartersExpenses.vue")
					.default,
				name: "report-headquarters-expenses",
			},
			{
				path: "headquarters-entries",
				component: require("./components/reports/ReportHeadquartersEntries.vue")
					.default,
				name: "report-headquarters-entries",
			},
			{
				path: "profitability",
				component: require("./components/reports/ReportProfitability.vue").default,
				name: "report-profitability",
			},
			{
				path: "cash-flow",
				component: require("./components/reports/ReportCashFlow.vue").default,
				name: "report-cash-flow",
			},
			{
				path: "rating-client",
				component: require("./components/reports/ReportRatingClient.vue").default,
				name: "report-rating-client",
			}
		],
	},
	{
		path: "**",
		component: require("./components/utils/NotFound.vue").default,
		name: "NotFound"
	}
];

const router = new VueRouter({
	routes, // short for `routes: routes`
	// linkActiveClass: "active",
	//mode: 'history'
});
export default router;


router.beforeEach(async (to, from, next) => {
	// redirect to login if not authenticated in and trying to access a restricted route
	const publicRoutes = ["Login", "Home"];
	const authRequired = !publicRoutes.includes(to.name);
	let isAuthenticated = false;
	try {
		isAuthenticated =
			localStorage.getItem("token") &&
				localStorage.getItem("user") &&
				JSON.parse(localStorage.getItem("user"))
				? true
				: false;
	} catch (e) {
		isAuthenticated
	}
	if (authRequired && !isAuthenticated) {
		return next({ name: "Login", query: { redirect: to.fullPath } });
	}

	if (isAuthenticated) {

		let alias = to.matched[0].alias[0];
		if (alias) {
			if (!utils.validatePermission(undefined, alias)) {
				return next({ name: "NotFound" });
			}
		}
	}
	next();

});

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
		user: Object,
		token: "",
		config: Object({
			headers: {
				Authorization: "",
			},
		}),
		configuration: Object,
		notifications: []

	},
	watch: {
		$route(to, from) {
			this.authUser();
		},
	},
	computed: {
		validateAuth() {
			return !$.isEmptyObject(this.user) && this.token;
		}
	},
	methods: {
		getCurrentBalanceMainBox() {
			axios.get(`api/main-box/current-balance`, this.config).then((response) => {
				return (this.current_balance_main_box = response.data);
			});
		},
		authUser() {
			this.user = JSON.parse(localStorage.getItem("user"));
			this.token = localStorage.getItem("token");

			if (this.user) {
				this.permissions = this.user.permissions;
			}

			this.config.headers.Authorization = "Bearer " + this.token;
		},
		validatePermission(permission) {
			return utils.validatePermission(this.permissions, permission);
		},
		logout() {
			this.user = {};
			this.token = "";
			this.permissions = [];
			this.config.headers.Authorization = "";
			localStorage.clear();
			this.$router.push('/');
		},
		assignErrors(response, formErrors) {
			if (response.response) {
				let errors = response.response.data.errors;

				Object.keys(formErrors).forEach(function (key, index) {
					if (errors[key] != undefined) {
						formErrors[key] = errors[key][0];
					}
				});
			} else {
				Object.keys(formErrors).forEach(function (key, index) {
					formErrors[key] = "";
				});
			}
		},
		getConfiguration()
		{
			axios.get("api/configurations", this.$root.config).then((response) => {
				if (response.data.company) {
				  this.configuration = response.data.company;
				}
			  });
		},
		getNotifications()
		{
			axios.get("api/notifications", this.$root.config).then( response => {
					this.notifications = response.data.data;
			});
		}
	},
	created() {
		this.authUser();
		this.getConfiguration();
	},
	mounted() {
		this.getCurrentBalanceMainBox();
		this.getNotifications();
	},
});
