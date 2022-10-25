<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="mb-4 flex flex-wrap justify-between items-center">
                <div class="mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup.enter="listar(1, buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <!--<vs-button class="mb-4 md:mb-0" @click="gridApi.exportDataAsCsv()">Export as CSV</vs-button>-->
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/nomina/parametrizacion/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    to="/app/agregarEjemplo"
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Exportar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center" divider
                                    >Generar PDF</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Generar XML</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>

            <vs-table stripe :data="contenidolistar">
                <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Departamento</vs-th>
                    <!--<vs-th>Descripción</vs-th>
                    <vs-th>Cuenta Contable 1</vs-th>
                    <vs-th>Cuenta Contable 2</vs-th>-->
                    <vs-th>Acciones</vs-th>
                </template>
             
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id_departamento" v-for="datos in data">
                        <vs-td>{{ datos.id_departamento }}</vs-td>
                        <vs-td v-if="datos.dep_nombre">{{ datos.dep_nombre }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <!--<vs-td v-if="datos.descripcion">{{
                            datos.descripcion
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.id_plan_cuentas1">{{
                            datos.id_plan_cuentas1
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                     <vs-td v-if="datos.id_plan_cuentas2">{{
                            datos.id_plan_cuentas2
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>-->
                        <vs-td>
                            <feather-icon
                                v-if="editarrol"
                                icon="EditIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="edit(datos.id_departamento)"
                            />
                            <feather-icon
                                v-if="eliminarrol"
                                icon="TrashIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                @click.stop="eliminar(datos.id_departamento)"
                            />
                            <!--@click.stop="eliminar(datos.id_empresa)"-->
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>

            
        </vx-card>
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
const axios = require("axios");
export default {
    components: {
        AgGridVue
    },
    data() {
        return {
            //mapeo de datos
            //paginacion
            contenidolistar:[],
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina: 1,
            cantidadp: 10,
            offset: 0,
            //buscador
            buscar: "",
            criterio: "codCta",
            //otros valores
            gridApi: null,
            contenido: [],
            activePrompt: false,
            val: "",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar")
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        // crearrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[64].crear;
        //     }
        //     return res;
        // },
        // editarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[64].editar;
        //     }
        //     return res;
        // },
        // eliminarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[64].eliminar;
        //     }
        //     return res;
        // }
        crearrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Rol de Provisiones"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            console.log(res+"Rol de Provisiones");
            return res;
            
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Rol de Provisiones"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarroles() {
            var res = 0;
                    // if (this.usuario.id_rol == 1) {
                    //     res = 1;
                    // } else {
                    //     res = this.$store.state.Roles[15].eliminar;
                    // }
                    if (this.usuario.id_rol == 1) {
                        res = 1;
                    } else {
                        this.$store.state.Roles.forEach(el => {
                            if(el.nombre == "Parametrizacion"){
                                res = el.eliminar;
                                return res;
                            }
                        });
                    }
                    return res;
        },
    },
    methods: {
        edit(id) {
 
        this.$router.push(`/nomina/parametrizacion/${id}/editar`);
        },
        crear() {},
        eliminar(cd) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar todos los registros de este Departamento?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: cd
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/parametrizacion/eliminartodo/" + parameters);
            this.$vs.notify({
                color: "success",
                title: "Registros Eliminado  ",
                text: "Registro eliminado con exito"
            });
            this.listar(1, this.buscar);
        },
        listar(page, buscar) {
            var url = "/api/parametrizacion/listar/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(res => {
                    this.contenidolistar = res.data.recupera;
                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

    },
    mounted() {
          this.listar(1, this.buscar);
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
    .vs-switch--input {
        width: 25%;
        height: 10px;
    }
}
</style>
