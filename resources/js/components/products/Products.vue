<template>
	<div class="page">
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Productos</h3>
			<button
				type="button"
				class="btn btn-primary"
				data-toggle="modal"
				data-target="#formProductModal"
				v-if="$root.validatePermission('product-store')"
			>
				Crear producto
			</button>
		</div>
		<div class="page-content mt-4">
			<section class="table-responsive">
				<table class="table table-sm table-bordered">
					<thead>
						<tr class="text-center">
							<th>ID</th>
							<th>Product</th>
							<th v-if="$root.validatePermission('product-status')">Estado</th>
							<th v-if="$root.validatePermission('product-update')">Opciones</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="p in productList.data" :key="p.id">
							<td>{{ p.id }}</td>
							<td>{{ p.product }}</td>						
							<td class="text-right" v-if="$root.validatePermission('product-status')">
								<button
									class="btn"
									:class="
										p.state == 1 ? 'btn-success' : 'btn-danger'
									"
									@click="changeStatus(p.id)"
								>
									<i class="bi bi-check-circle-fill" v-if="p.state == 1"></i>
									<i class="bi bi-x-circle" v-if="p.state == 0"></i>
								</button>
							</td>
							<td class="text-right" v-if="$root.validatePermission('product-update')">
								<button class="btn btn-primary" @click="showData(p)">
									<i class="bi bi-pen"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
				<pagination
					:align="'center'"
					:data="productList"
					:limit="10"
					@pagination-change-page="listProducts"
				>
					<span slot="prev-nav">&lt; Previous</span>
					<span slot="next-nav">Next &gt;</span>
				</pagination>
			</section>
		</div>
		<create-edit-product
			ref="CreateEditProduct"
			@list-products="listProducts(1)"
		/>
	</div>
</template>
<script>
import CreateEditProduct from "./CreateEditProduct.vue";
export default {
	components: { CreateEditProduct },
	data() {
		return {
			productList: {},
		};
	},
	created() {
		this.listProducts(1);
	},
	methods: {
		listProducts(page = 1) {
			let me = this;
			axios.get("api/products?page=" + page, me.$root.config).then(function (response) {
				me.productList = response.data;
			});
		},
		showData: function (product) {
			this.$refs.CreateEditProduct.showEditProduct(product);
		},
		changeStatus: function (id) {
			let me = this;

			Swal.fire({
				title: "¿Quieres cambiar el status del producto?",
				showDenyButton: true,
				denyButtonText: `Cancelar`,
				confirmButtonText: `Guardar`,
			}).then((result) => {
				if (result.isConfirmed) {
					axios
						.post(`api/products/${id}/change-status`, null, me.$root.config)
						.then(function () {
							me.listProducts(1);
						});
					Swal.fire("Cambios realizados!", "", "success");
				} else if (result.isDenied) {
					Swal.fire("Operación no realizada", "", "info");
				}
			});
		},
	},
};
</script>
