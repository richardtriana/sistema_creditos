<template>
  <div class="page">
    <div class="page-header d-flex justify-content-between p-4 border my-2">
      <h3>Creditos con clientes</h3>
      <button
        type="button"
        class="btn btn-primary"
        data-toggle="modal"
        data-target="#formCreditModal"
      >
        Crear Credito
      </button>
    </div>
    <div class="page-search d-flex justify-content-between p-4 border my-2">
      <div class="form-row col-8 m-auto">
        <div class="col">
          <input
            type="text"
            id="search_client"
            name="search_client"
            class="form-control col"
            placeholder="Buscar cliente | Documento"
            @keyup="listCredits()"
            v-model="search_client"
          />
        </div>
        <div class="col">
          <select
            name="status"
            id="status"
            v-model="status"
            class="custom-select col"
            @click="listCredits()"
          >
            <option v-for="(st, index) in creditStatus" :value="index" :key="index"> {{st}}</option>
          </select>
        </div>
      </div>
    </div>

    <div class="page-content mt-4" style="width: 100%">
      <section class="table-responsive">
        <table class="table table-sm table-bordered">
          <thead>
            <tr class="text-center">
              <th>ID</th>
              <th>Cliente</th>
              <th>Nro. Documento</th>
              <th>Valor crédito</th>
              <th>Valor Abonado</th>
              <th>Nro Cuotas</th>
              <th>Estado</th>
              <th>Ver Cuotas</th>
              <th>
                Tabla de <br />
                amortización
              </th>
              <th>Opciones</th>
            </tr>
          </thead>
          <!-- <tbody> -->
          <tbody v-if="creditList.data && creditList.data.length > 0">
            <tr v-for="credit in creditList.data" :key="credit.index">
              <td>{{ credit.id }}</td>
              <td>{{ credit.name }} {{ credit.last_name }}</td>
              <td>{{ credit.document }}</td>
              <td class="text-right">{{ credit.credit_value | currency }}</td>
              <td class="text-right">{{ credit.paid_value | currency }}</td>
              <td>{{ credit.number_installments }}</td>
              <td>
                {{ creditStatus[credit.status] }}
              </td>
              <td class="text-center">
                <button
                  data-toggle="modal"
                  data-target="#cuotasModal"
                  v-if="credit.status == 1"
                  class="btn btn-outline-primary"
                  @click="showInstallment(credit.id)"
                >
                  <i class="bi bi-eye"></i>
                </button>

                <button
                  v-else
                  class="btn disabled btn-outline-secondary"
                  disabled
                >
                  <i class="bi bi-eye-slash"></i>
                </button>
              </td>
              <td class="text-center">
                <button
                  class="btn btn-outline-primary"
                  @click="
                    printTable(credit.id, credit.name + '_' + credit.last_name)
                  "
                >
                  <i class="bi bi-file-pdf"></i>
                </button>
              </td>
              <td class="text-right">
                <button
                  v-if="credit.status == 1"
                  class="btn btn-outline-primary"
                  @click="showData(credit)"
                >
                  <i class="bi bi-pen"></i>
                </button>
                <button v-else class="btn btn-outline-secondary" disabled>
                  <i class="bi bi-pen"></i>
                </button>

                <button
                  v-if="credit.status == 0"
                  class="btn btn-outline-danger"
                  @click="changeStatus(credit.id, 2)"
                >
                  <i class="bi bi-trash"></i>
                </button>
                <button
                  v-if="credit.status == 0"
                  class="btn btn-outline-success"
                  @click="changeStatus(credit.id, 1)"
                >
                  <i class="bi bi-check2-circle"></i>
                </button>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="11">
                <div
                  class="alert alert-danger text-center"
                  style="margin: 2px auto; width: 30%"
                >
                  <p>No se encontraron clientes con creditos.</p>
                  <p>Crear cliente.</p>
                </div>
                <div
                  class="alert alert-info"
                  style="margin: 2px auto; width: 30%"
                >
                  Crear un nuevo Cliente
                  <button
                    type="button"
                    class="btn btn-primary"
                    data-toggle="modal"
                    data-target="#formClientModal"
                  >
                    Crear cliente
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        <pagination
          :align="'center'"
          :data="creditList"
          :limit="2"
          @pagination-change-page="listCredits"
        >
          <span slot="prev-nav"><i class="bi bi-chevron-double-left"></i></span>
          <span slot="next-nav"
            ><i class="bi bi-chevron-double-right"></i
          ></span>
        </pagination>
      </section>
    </div>

    <modal-create-edit-client
      ref="ModalCreateEditClient"
      @list-clients="listCredits(1)"
    />

    <create-edit-credit ref="CreateEditCredit" @list-credits="listCredits(1)" />

    <modal-installment ref="ModalInstallment" />
  </div>
</template>
<script>
import CreateEditCredit from "./CreateEditCredit.vue";

import ModalCreateEditClient from "../../clients/ModalCreateEditClient.vue";
import ModalInstallment from "../credit_helpers/ModalInstallment.vue";

export default {
  components: {
    CreateEditCredit,
    ModalCreateEditClient,
    ModalInstallment,
  },

  data() {
    return {
      search_client: "",
      status: 1,
      creditList: {},
      clientList: {},
      creditStatus: {
        0: "Pendiente",
        1: "Aprobado",
        2: "Rechazado",
        3: "Pendiente pago a proveedor",
      },
    };
  },
  created() {
    this.listCredits(1);
  },
  methods: {
    listCredits(page = 1) {
      let me = this;
      axios
        .get(
          `api/credits?page=${page}&credit=${this.search_client}&status=${this.status}`
        )
        .then(function (response) {
          me.creditList = response.data;
        });
    },
    listClients(page = 1) {
      let me = this;
      axios
        .get(`api/clients?page=${page}&client=${this.search_client}`)
        .then(function (response) {
          me.clientList = response.data;
        });
    },
    showData: function (credit) {
      this.$refs.CreateEditCredit.showEditCredit(credit);
    },

    showInstallment: function (credit) {
      this.$refs.ModalInstallment.listCreditInstallments(credit, 1);
    },
    showDataClient: function (client) {
      this.$refs.ModalCreateEditClient.showEditClient(client);
    },
    changeStatus: function (id, status) {
      let me = this;
      var data = {
        status: status,
      };

      Swal.fire({
        title: "¿Quieres cambiar el status del credito?",
        showDenyButton: true,
        denyButtonText: `Cancelar`,
        confirmButtonText: `Guardar`,
      }).then((result) => {
        if (result.isConfirmed) {
          axios
            .post(`api/credits/${id}/change-status`, data, me.$root.config)
            .then(function () {
              me.listCredits(1);
            });
          Swal.fire("Cambios realizados!", "", "success");
        } else if (result.isDenied) {
          Swal.fire("Operación no realizada", "", "info");
        }
      });
    },
    printTable(credit_id, client) {
      axios
        .get(`api/credits/amortization-table?credit_id=${credit_id}`)
        .then((response) => {
          const pdf = response.data.pdf;
          var a = document.createElement("a");
          a.href = "data:application/pdf;base64," + pdf;
          a.download = `credit_${credit_id}-${client}.pdf`;
          a.click();
        });
    },
  },
};
</script>
