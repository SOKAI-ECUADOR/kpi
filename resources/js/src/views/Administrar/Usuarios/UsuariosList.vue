<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="mb-4 flex flex-wrap justify-between items-center">
                <div class="mr-4 ag-grid-table-actions-left"></div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                    <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup.enter="listar(1, buscar)" v-bind:placeholder="i18nbuscar"/>
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button class="btnx" type="filled" to="/administrar/usuarios/agregar">Agregar</vs-button>
                        <vs-dropdown>
                            <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider>Generar PDF</vs-dropdown-item>
                                <vs-dropdown-item class="text-center">Generar XML</vs-dropdown-item>
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Tipo de Rol</vs-th>
                    <vs-th>Nombres</vs-th>
                    <vs-th>Email</vs-th>
                    <vs-th>Fecha de creación</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id" v-for="datos in data">
                        <vs-td v-if="datos.id_rol">{{ datos.nombre_rol }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nombres">{{ datos.nombres }} {{ datos.apellidos }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.email">{{ datos.email }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.fcrea">{{ datos.fcrea | fecha }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon v-if="editarrol" class="cursor-pointer" icon="EditIcon" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="editar(datos.id)"/>
                            <feather-icon v-if="eliminarrol" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="idusuario=datos.id;eliminar=true;"/>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <!-- Eliminar popup-->
        <vs-popup title="eliminar registro" :class="'peque'" :active.sync="eliminar">
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6 text-center">
                            <label class="text-center">
                                Esta seguro que desea eliminar este registro
                                <br/>¡Se eliminará de forma permanente!
                            </label>
                        </div>
                        <div class="vx-col sm:w-full w-full text-center">
                            <vs-button color="danger" type="filled" @click="eliminarusuario(idusuario)">Eliminar Usuario</vs-button>
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
    </div>
</template>
<script>

import $ from "jquery";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
const axios = require("axios");
export default {
    components: {
        AgGridVue
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[3].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[3].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[3].eliminar;
            }
            return res;
        }
    },
    data() {
        return {
            //mapeo de datos
            //paginacion
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
            offset: 3,
            //buscador
            buscar: "",
            criterio: "nombres",
            //otros valores
            gridApi: null,
            contenido: [],
            addNewDataSidebar: false,
            sidebarData: {},
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            activePrompt2: false,
            valMultipe: {
                value1: "",
                value2: ""
            },
            eliminar: false,
            idusuario: null,
            
          
            
        };
    },
    filters: {
        fecha(dato) {
            return moment(dato).format("LLL A");
        }
    },
    methods: {    
        editar(id) {
            this.$router.push(`/administrar/usuarios/${id}/editar`);
        },
        listar(page, buscar) {
            var url = "/api/usuarios/" + this.usuario.id_empresa + "?page=" + page + "&buscar=" + buscar;
            axios.get(url).then(res => {this.contenido = res.data.recupera;});
        },
        eliminarusuario(id) {
            axios.delete("/api/eliminarusuario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listar(1, this.buscar); 
        }
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
}

.vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
}

</style>
