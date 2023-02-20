<template>
  <form action="">
    <div class="form-group">
      <label for="description">ó crear tipo de entrada</label>
      <input
        type="text"
        class="form-control truncate"
        id="description"
        placeholder="Descripción del egreso"
        v-model="formTypeEntry.description"
      />
    </div>
    <button
      type="button"
      class="btn btn-primary"
      @click="editar ? editEntry() : createEntry()"
    >
      Guardar
    </button>
  </form>
</template>

<script>
export default {
  data() {
    return {
      editar: false,
      formTypeEntry: {},
    };
  },
  methods: {
    createEntry() {
      let me = this;
      axios.post("api/type-entries", this.formTypeEntry, me.$root.config).then(function () {
        me.resetData();
        me.$emit("list-type-entries");
      });
    },
    resetData() {
      let me = this;
      Object.keys(this.formTypeEntry).forEach(function (key, index) {
        me.formTypeEntry[key] = "";
      });
    },
  },
};
</script>
