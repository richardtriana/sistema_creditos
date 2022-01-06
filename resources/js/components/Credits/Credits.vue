<template>
    <div class="page">
        <div class="page-header d-flex justify-content-between p-4 border my-2">
            <h3>Credits</h3>
            <button
                type="button"
                class="btn btn-primary"
                data-toggle="modal"
                data-target="#formCreditModal"
            >
                Crear Credit
            </button>
        </div>
        <div class="page-search d-flex justify-content-between p-4 border my-2">
            <div class="form-group col-8 m-auto">
                <label for="buscar_cliente">Buscar Cliente...</label>
                <input
                    type="text"
                    id="buscar_cliente"
                    name="buscar_cliente"
                    class="form-control"
                    placeholder="Nombres | Documento"
                    @keypress="listarCredits()"
                    v-model="buscar_cliente"
                />
            </div>
        </div>

        <div class="page-content mt-4" style="width: 100%">
            <section class="">
                <table class="table table-md table-bordered table-responsive">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Nro. Documento</th>
                            <th>Valor crédito</th>
                            <th>Valor Abonado</th>
                            <th>Nro Cuotas</th>
                            <th>Cuotas</th>
                            <th>Estado</th>
                            <th>Ver Cuotas</th>
                            <th>Tabla de <br> amortización</th>
                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <!-- <tbody> -->
                    <tbody v-if="creditList.data && creditList.data.length > 0">
                        <tr
                            v-for="credit in creditList.data"
                            :key="credit.index"
                        >
                            <td>{{ credit.id }}</td>
                            <td>{{ credit.nombres }} {{ credit.apellidos }}</td>
                            <td>{{ credit.nro_documento }}</td>
                            <td>{{ credit.valor_credit }}</td>
                            <td>{{ credit.valor_abonado }}</td>
                            <td>{{ credit.cant_cuotas }}</td>
                            <td>{{ credit.cant_cuotas_pagadas }}</td>
                            <td>
                                <span v-if="credit.estado == 1">Activo</span>
                                <span v-if="credit.estado == 0">Inactivo</span>
                            </td>
                            <td class="text-center">
                                <button
                                    data-toggle="modal"
                                    data-target="#cuotasModal"
                                    v-if="credit.estado == 1"
                                    class="btn btn-outline-primary"
                                    @click="mostrarFees(credit.id)"
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
                            <td>
                                <button class="btn btn-outline-primary">
                                    <i class="bi bi-file-pdf"></i>
                                </button>
                            </td>
                            <td class="text-left">
                                <button
                                    v-if="credit.estado == 1"
                                    class="btn btn-outline-primary"
                                    @click="mostrarDatos(credit)"
                                >
                                    <i class="bi bi-pen"></i>
                                </button>
                                <button
                                    v-else
                                    class="btn btn-outline-secondary"
                                    disabled
                                >
                                    <i class="bi bi-pen"></i>
                                </button>

                                <button
                                    v-if="credit.estado == 1"
                                    class="btn btn-outline-danger"
                                    @click="CambiarEstado(credit.id)"
                                >
                                    <i class="bi bi-trash"></i>
                                </button>
                                <button
                                    v-if="credit.estado == 0"
                                    class="btn btn-outline-success"
                                    @click="CambiarEstado(credit.id)"
                                >
                                    <i class="bi bi-check2-circle"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <div v-else>
                        <div
                            class="alert alert-danger"
                            style="margin: 2px auto; width: 30%"
                        >
                            <p>No se encontraron resultados.</p>
                            <p>Crear usuario.</p>
                        </div>
                        <div
                            class="alert alert-info"
                            style="margin: 2px auto; width: 30%"
                        >
                            Crear un nuevo Cliente
                            <button
                                type="button"
                                class="btn btn-success"
                                data-toggle="modal"
                                data-target="#formClienteModal"
                            >
                                Crear Cliente
                            </button>
                        </div>
                    </div>
                </table>
                <pagination
                    :align="'center'"
                    :data="creditList"
                    :limit="2"
                    @pagination-change-page="listarCredits"
                >
                    <span slot="prev-nav"
                        ><i class="bi bi-chevron-double-left"></i
                    ></span>
                    <span slot="next-nav"
                        ><i class="bi bi-chevron-double-right"></i
                    ></span>
                </pagination>
            </section>
        </div>

        <crear-editar-cliente
            ref="CrearEditarCliente"
            @listar-clientes="listarCredits(1)"
        />
        <create-edit-credit
            ref="CreateEditCredit"
            @listar-credits="listarCredits(1)"
        />

        <fees ref="Fees" />
    </div>
</template>
<script>
import CreateEditCredit from "./CreateEditCredit.vue";
import Simulator from "./Simulator.vue";
import CrearEditarCliente from "./../Clientes/CrearEditarCliente.vue";
import Fees from "./Fees.vue";

export default {
    components: { CreateEditCredit, Simulator, CrearEditarCliente, Fees },

    props: {
        fees: {
            type: Object
        }
    },
    data() {
        return {
            buscar_cliente: "",
            creditList: {},
            listaClientes: {}
        };
    },
    created() {
        this.listarCredits(1);
    },
    methods: {
        listarCredits(page = 1) {
            let me = this;
            axios
                .get(`api/credits?page=${page}&credit=${this.buscar_cliente}`)
                .then(function(response) {
                    console.log(response);
                    me.creditList = response.data;
                });
        },
        listarClientes(page = 1) {
            let me = this;
            axios
                .get(`api/clientes?page=${page}&cliente=${this.buscar_cliente}`)
                .then(function(response) {
                    me.listaClientes = response.data;
                });
        },
        mostrarDatos: function(credit) {
            this.$refs.CreateEditCredit.abirEditarCredit(credit);
        },
        simularCredit: function() {
            this.$refs.Simulator.abrirSimulator();
        },
        mostrarFees: function(credit) {
            this.$refs.Fees.listarFeesCredit(credit);
        },
        mostrarDatosCliente: function(cliente) {
            this.$refs.CrearEditarCliente.abirEditarCliente(cliente);
        },
        CambiarEstado: function(id) {
            let me = this;

            Swal.fire({
                title: "¿Quieres cambiar el estado del credit?",
                showDenyButton: true,
                denyButtonText: `Cancelar`,
                confirmButtonText: `Guardar`
            }).then(result => {
                if (result.isConfirmed) {
                    axios
                        .post(
                            "api/credits/" + id + "/cambiar-estado",
                            null,
                            me.$root.config
                        )
                        .then(function() {
                            me.listarCredits(1);
                        });
                    Swal.fire("Cambios realizados!", "", "success");
                } else if (result.isDenied) {
                    Swal.fire("Operación no realizada", "", "info");
                }
            });
        }
    } //End of methods
};
</script>
