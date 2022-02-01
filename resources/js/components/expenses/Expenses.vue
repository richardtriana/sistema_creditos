<template>
	<div>
		<div class="page-header d-flex justify-content-between p-4 border my-2">
			<h3>Egresos</h3>
			<button
				type="button"
				class="btn btn-primary"
				data-toggle="modal"
				data-target="#expenseModal"
			>
				Crear Egreso
			</button>
		</div>
		<div class="page-content">
			<section>
				<table class="table table-sm table-responsicve">
					<thead>
						<tr>
							<th>Motivo</th>
							<th>Responsable</th>
							<th>Fecha</th>
							<th>Tipo de Salida</th>
							<th>Valor</th>
							<th>Opciones</th>
							<th>Estado</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Mark</td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</section>
		</div>
		<modal-expense />
	</div>
</template>

<script>
import ModalExpense from "./ModalExpense.vue";
export default {
	components: { ModalExpense },
};
</script>
