<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/administrar/empresa/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Exportar registros</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Codigo.</vs-th>
                    <vs-th>Nombre de empresa</vs-th>
                    <vs-th>Razón social</vs-th>
                    <vs-th>RUC</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="index" v-for="(datos, index) in data">
                        <vs-td>{{ datos.id_empresa }}</vs-td>
                        <vs-td v-if="datos.nombre_empresa">{{
                            datos.nombre_empresa
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.razon_social">{{
                            datos.razon_social
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.ruc_empresa">{{
                            datos.ruc_empresa
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                                @click="editar(datos.id_empresa)"
                            />
                            <feather-icon
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                                class="ml-2"
                                @click="eliminar(datos.id_empresa)"
                            />
                            <vs-switch
                                color="success"
                                v-model="datos.estado"
                                style="display: inline-flex; align-items: center; margin-bottom: -3px;"
                                @click="openConfirm(index)"
                            >
                                <span slot="on">
                                    <feather-icon
                                        icon="CheckIcon"
                                        svgClasses="w-3 h-5 hover:text-primary stroke-current cursor-pointer"
                                /></span>
                                <span slot="off">
                                    <feather-icon
                                        icon="XIcon"
                                        svgClasses="w-3 h-5 hover:text-primary stroke-current cursor-pointer"
                                /></span>
                            </vs-switch>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
    </div>
</template>
<script>
const axios = require("axios");
export default {
    data() {
        return {
            activeConfirm: false,
            //mapeo de datos
            pagina: 1,
            cantidadp: 10,
            offset: 3,
            //buscador
            buscar: "",
            timeout: null,
            criterio: "codCta",
            //otros valores
            contenido: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            estado_selected: false,
            empresa_selected: null
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    methods: {
        editar(id) {
            this.$router.push(`/administrar/empresa/${id}/editar`);
        },
        eliminar(id) {
            axios.delete("/api/eliminarempresa/" + id);
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                text: "¿Desea Elimnar este registro?",
                accept: this.acceptAlert
            });
        },
        acceptAlert() {
            this.$vs.notify({
                color: "danger",
                title: "Empresa Eliminado  ",
                text: "El producto selecionado fue eliminado con exito"
            });
            this.listar(this.buscar);
        },
        listar(buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url = `/api/empresa?buscar=${buscar}`;
                axios
                    .get(url)
                    .then(res => {
                        this.contenido = res.data.recupera;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }, 800);
        },
        openConfirm(tr) {
            if (
                this.contenido[tr].estado == 1 ||
                this.contenido[tr].estado == true
            ) {
                this.estado_selected = false;
                this.empresa_selected = tr;
                this.$vs.dialog({
                    type: "confirm",
                    color: "danger",
                    title: `Deshabilitar Empresa`,
                    text: `Esta seguro que desea deshabilitar ${this.contenido[tr].nombre_empresa}`,
                    accept: this.acceptAlert,
                    cancel: this.cancelAlert
                });
            } else if (
                this.contenido[tr].estado == 0 ||
                this.contenido[tr].estado == false
            ) {
                this.estado_selected = true;
                this.empresa_selected = tr;
                this.$vs.dialog({
                    type: "confirm",
                    color: "success",
                    title: `Habilitar Empresa`,
                    text: `Esta seguro que desea habilitar ${this.contenido[tr].nombre_empresa}`,
                    accept: this.acceptAlert,
                    cancel: this.cancelAlert
                });
            }
        },
        acceptAlert() {
            axios
                .post(`/api/estado/change_state_empresa`, {
                    id: this.contenido[this.empresa_selected].id_empresa,
                    estado: this.estado_selected
                })
                .then(resp => {
                    if (this.estado_selected == true) {
                        var messagealtert = "Empresa Habilitada";
                    } else {
                        var messagealtert = "Empresa Deshabilitada";
                    }
                    this.$vs.notify({
                        color: "success",
                        title: "Aceptado",
                        text: messagealtert
                    });
                })
                .catch(err => {
                    if (this.estado_selected == true) {
                        this.contenido[this.empresa_selected].estado = false;
                        var messagealtert = "No se ha podido Habilitar";
                    } else if (this.estado_selected == false) {
                        this.contenido[this.empresa_selected].estado = true;
                        var messagealtert = "No se ha podido Deshabilitar";
                    }
                    this.$vs.notify({
                        color: "danger",
                        title: "Error",
                        text: messagealtert
                    });
                });
        },
        cancelAlert() {
            if (this.estado_selected == true) {
                this.contenido[this.empresa_selected].estado = false;
                var messagealtert = "No se ha Habilitado";
            } else if (this.estado_selected == false) {
                this.contenido[this.empresa_selected].estado = true;
                var messagealtert = "No se ha Deshabilitado";
            }
            this.$vs.notify({
                color: "danger",
                title: "Cancelado",
                text: messagealtert
            });
        }
    },
    mounted() {
        this.listar(this.buscar);
    }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.dropdown-button-container {
    display: flex;
    align-items: center;

    .btnx {
        border-radius: 5px 0px 0px 5px;
    }

    .btn-drop {
        border-radius: 0px 5px 5px 0px;
        border-left: 1px solid rgba(255, 255, 255, 0.2);
    }
    .peque .vs-popup {
        width: 700px !important;
    }
}
</style>
