<template>
  <div>
    <div class="card mt-4">
      <div class="card-header">
        <h5>Cajas relacionadas</h5>
      </div>
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead class="thead-light">
            <tr class="text-center">
              <th scope="col">#</th>
              <th scope="col">Sede</th>
              <th scope="col">Saldo disponible</th>
              <th scope="col">Última modificación</th>
              <th>ültimo editor</th>
              <th>Historial</th>
              <th v-if="$root.validatePermission('box-update')">Opciones</th>
              <!-- <th>Eliminar</th> -->
            </tr>
          </thead>
          <tbody v-if="boxList.length > 0">
            <tr v-for="(box, index) in boxList" :key="index">
              <th scope="row">{{ index + 1 }}</th>
              <td>{{ box.headquarter.headquarter }}</td>
              <td class="text-right">{{ box.current_balance | currency }}</td>
              <td>{{ box.last_update }}</td>
              <td>
                <span v-if="box.last_editor">
                  {{ box.last_editor.name }} {{ box.last_editor.last_name }}
                </span>
              </td>
              <td class="text-right">
                <!-- data-toggle="modal" data-target="#historyBoxModal" -->
                <button class="btn btn-primary" 
                  @click="showHistoryBox(box.history)">
                  <i class="bi bi-clock-history"></i>
                </button>
              </td>
              <td v-if="$root.validatePermission('box-update')" class="text-right">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#boxModal"
                  @click="showEditBox(box)">
                  <i class="bi bi-pencil"></i>
                </button>
              </td>
              <!-- <td>
							<button class="btn btn-outline-danger">
								<i class="bi bi-trash2"></i>
							</button>
						</td> -->
            </tr>
          </tbody>
        </table>
      </section>
    </div>
    <create-edit-box ref="CreateEditBox" @list-boxes="listBoxes()" />
  </div>
</template>

<script>
import CreateEditBox from "./CreateEditBox.vue";
export default {
  components: { CreateEditBox },
  data() {
    return {
      boxList: {},
    };
  },
  created() {
    this.listBoxes();
  },
  methods: {
    listBoxes() {
      axios.get("api/boxes", this.$root.config).then((response) => {
        this.boxList = response.data.boxes;
      });
      this.$root.getCurrentBalanceMainBox();
    },

    showEditBox(box) {
      this.$refs.CreateEditBox.showEditBox(box);
    },
    showHistoryBox(history) {
			this.$emit('show-history', history)
    },
  },
};
</script>
