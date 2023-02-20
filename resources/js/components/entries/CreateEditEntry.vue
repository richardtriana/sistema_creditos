<template>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title" id="entryModalLabel">Egresos</h5>
      <button
        type="button"
        class="close"
        data-dismiss="modal"
        aria-label="Close"
      >
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <form class="">
      <div class="modal-body">
        <div class="form-group col-12">
          <label for="description">Descripción</label>
          <input
            type="text"
            class="form-control truncate"
            id="description"
            placeholder="Descripción del gasto a realizar"
            v-model="formEntry.description"
          />
          <small id="description_help" class="form-text text-danger">{{
            formErrors.description
          }}</small>
        </div>
        <div class="form-group col-12">
          <label for="date">Fecha</label>
          <input
            type="date"
            class="form-control"
            id="date"
            v-model="formEntry.date"
          />
          <small id="date_help" class="form-text text-danger">{{
            formErrors.date
          }}</small>
        </div>
        <div class="form-row col-12">
          <div class="form-group col-11">
            <label for="type_entry">Seleccionar tipo de entrada</label>
            <v-select
              :options="entryTypeList"
              label="description"
              aria-logname="{}"
              :reduce="(entryType) => entryType.description"
              v-model="formEntry.type_entry"
              placeholder="--Seleccionar--"
            >
            </v-select>
            <small id="type_entry_help" class="form-text text-danger">{{
              formErrors.type_entry
            }}</small>
          </div>
          <div class="form-group col-1">
            <button
              type="button"
              class="btn btn-success border-success mt-4"
              v-if="!show_type_entry"
              @click="show_type_entry = true"
            >
              <i class="bi bi-plus-circle"></i>
            </button>
            <button
              type="button"
              class="btn btn-danger border-danger mt-4"
              v-if="show_type_entry"
              @click="show_type_entry = false"
            >
              <i class="bi bi-x-circle"></i>
            </button>
          </div>
        </div>
        <div class="form-group col-12">
          <create-edit-type-entry
            class="border p-1 bg-light"
            v-if="show_type_entry"
            @list-type-entries="listTypeEntries(), (show_type_entry = false)"
          />
        </div>
        <div class="form-group col-12">
          <label for="price">Precio: <b>{{ formEntry.price | currency}} </b></label>
          <input
            type="number"
            step="any"
            class="form-control"
            id="price"
            placeholder="$"
            v-model="formEntry.price"
          />
          <small id="price_help" class="form-text text-danger">{{
            formErrors.price
          }}</small>
        </div>
      </div>
      <div class="modal-footer">
        <button
          type="button"
          class="btn btn-secondary"
          data-dismiss="modal"
          @click="editar = false"
        >
          Cerrar
        </button>
        <button
          type="button"
          class="btn btn-primary"
          @click="editar ? editEntry() : createEntry()"
        >
          Guardar
        </button>
      </div>
    </form>
  </div>
</template>

<script>
import CreateEditTypeEntry from "./type-entries/CreateEditTypeEntry.vue";
export default {
  components: { CreateEditTypeEntry },
  data() {
    return {
      editar: false,
      show_type_entry: false,
      formEntry: {},
      entryTypeList: [],
      formErrors: {
        description: "",
        date: "",
        type_entry: "",
        price: "",
      },
    };
  },
  methods: {
    createEntry() {
      let me = this;
       me.$root.assignErrors(false, me.formErrors);

      axios
        .post("api/entries", this.formEntry, me.$root.config)
        .then(function () {
          $("#entryModal").modal("hide");
          me.resetData();
          me.$emit("list-entries");
        }).catch(response =>{
              me.$root.assignErrors(response, me.formErrors);
        });
    },
    showEditEntry(entry) {
      this.editar = true;
      let me = this;
      $("#entryModal").modal("show");
      me.formEntry = entry;
    },
    editEntry() {
      let me = this;
       me.$root.assignErrors(false, me.formErrors);

      axios
        .put(
          `api/entries/${this.formEntry.id}`,
          this.formEntry,
          me.$root.config
        )
        .then(function () {
          $("#entryModal").modal("hide");
          me.resetData();
        }).catch(response =>{
              me.$root.assignErrors(response, me.formErrors);
        });
      this.$emit("list-entries");

      me.editar = false;
    },
    resetData() {
      let me = this;
      Object.keys(this.formEntry).forEach(function (key, index) {
        me.formEntry[key] = "";
      });
      me.$root.assignErrors(false, me.formErrors);
    },
    listTypeEntries() {
      let me = this;
      axios.get(`api/type-entries`, me.$root.config).then(function (response) {
        me.entryTypeList = response.data;
      });
    },
  },
  mounted() {
    this.listTypeEntries();
  },
};
</script>
