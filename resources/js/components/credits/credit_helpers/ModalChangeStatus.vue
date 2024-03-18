<template>

    <div class="modal fade" id="modalChangeStatus" tabindex="-1" role="dialog" aria-labelledby="modalChangeStatusLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalChangeStatusLabel">Cambiar estado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a @click="status = 2" class="list-group-item list-group-item-action"
                            :class="{ 'active bg-success': status == 2 }"><i
                                class="bi bi-exclamation-triangle-fill"></i>&nbsp;Rechazar</a>
                        <a @click="status = 7" class="list-group-item list-group-item-action"
                            :class="{ 'active  bg-success': status == 7 }" aria-current="true"> <i
                                class="bi bi-clipboard-x"></i>  &nbsp; Perdida</a>
                        <template v-if="currentStatus == 1">
                            <a @click="status = 5" class="list-group-item list-group-item-action"
                                :class="{ 'active  bg-success': status == 5 }"> <i class="bi bi-x-circle"></i>&nbsp; Pasar a
                                cobro jurídico</a>
                        </template>

                        <template v-if="currentStatus == 5">
                            <a @click="status = 1" class="list-group-item list-group-item-action"
                                :class="{ 'active  bg-success': status == 1 }"><i class="bi bi-check"></i>&nbsp; Deshacer
                                cobro juridico</a>
                        </template>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" @click="changeStatus(id, status)">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    data() {
        return {
            id: "",
            status: "",
            currentStatus: ""
        }
    },
    methods: {

        openModal(credit) {
            this.id = credit.id
            this.currentStatus = credit.status
            console.log(this.id)
            console.log(this.currentStatus)

            $('#modalChangeStatus').on('shown.bs.modal', function () {
                $(document).off('focusin.modal');
            });
        },

        changeStatus: function (id, status) {
            var data = {
                status: status,
            };
            Swal.fire({
                title: "¿Quieres cambiar el estado del credito?",
                showCancelButton: true,
                cancelButtonText: "Cancelar",
                confirmButtonText: `Aceptar`,
            }).then((result) => {
                if (result.isConfirmed) {
                    if (status != 2 && status != 7) {
                        this.sendData(id, data);
                        Swal.fire("Cambios realizados!", "", "success");
                    } else {
                        this.msgRejectd(id, status);
                    }
                } else {
                    Swal.fire("Operación no realizada", "", "info");
                }
            });
        },
        msgRejectd: async function (id, status) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    title: "text-primary",
                    cancelButton: "btn btn-secondary",
                    confirmButton: "btn btn-danger",
                },
                buttonsStyling: false,
            });

            await swalWithBootstrapButtons
                .fire({
                    title: "Motivo de cambio de estado a $status",
                    reverseButtons: true,
                    input: "text",
                    inputLabel: "Realice una descripción del motivo",
                    inputPlaceholder: "",
                    showCancelButton: true,
                    cancelButtonText: "Cancelar",
                    confirmButtonText: "Aceptar",
                    inputValidator: (value) => {
                        if (!value) {
                            return "Debes completar este campo!";
                        }
                    },
                })
                .then((response) => {
                    console.log(response.isConfirmed);
                    if (response.isConfirmed) {
                        var data = {
                            status: status,
                            description: response.value,
                        };
                        this.sendData(id, data);
                        Swal.fire("Cambios realizados!", "", "success");

                    } else {
                        Swal.fire("Operación no realizada", "", "info");
                    }
                });
        },
        sendData(id, data) {
            let me = this;
            axios
                .post(`api/credits/${id}/change-status`, data, this.$root.config)
                .then(function () {
                    me.$emit('list-credits')
                });
            $('#modalChangeStatus').modal('hide');
        },

    }
}
</script>
<style>
    .list-group-item {
        cursor:pointer
    }
</style>
