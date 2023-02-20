<template>
  <div
    class="modal fade"
    id="entryModal"
    tabindex="-1"
    aria-labelledby="entryModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog">
      <create-edit-entry
        ref="CreateEditEntry"
        @list-entries="listEntries()"
      />
    </div>
  </div>
</template>

<script>
import CreateEditEntry from "./CreateEditEntry.vue";
export default {
  components: { CreateEditEntry },
  methods: {
    listEntries() {
      this.$emit("list-entries");
    },

    showEditEntry(entry) {
      this.$refs.CreateEditEntry.showEditEntry(entry);
    },
  },
};
</script>
