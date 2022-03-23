<template>
  <div class="page">
    <div class="page-header">
      <h3>Cajas</h3>
    </div>
    <div class="page-content">
      <main-box />
      <box v-if="root_data.current_balance_main_box > 0" />
      <div
        v-if="root_data.current_balance_main_box <= 0"
        class="card w-100 mt-5 bg-warning text-white"
      >
        <div class="card-body">
          <i class="bi bi-exclamation-diamond-fill"></i>
          <div class="h5">
            Debe añadir saldo a la caja principal para visualizar las otras cajas
          </div>
          <span>Por favor, recargue la página al finalizar</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import Box from "./Box.vue";
import MainBox from "./MainBox.vue";
export default {
  data() {
    return {
      root_data: this.$root.$data,
    };
  },
  components: { MainBox, Box },
};
</script>
