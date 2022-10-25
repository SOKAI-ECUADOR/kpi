<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
                </div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                        <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1, buscar)" v-bind:placeholder="i18nbuscar"/>
                        <div  v-if="crearrol">
                            <vs-button class="btnx" type="filled" to="/salud/plan_seguro/agregar">Agregar</vs-button>
                        </div>
                </div>
            </div>
            <vs-table stripe  :data="contenido">
                <template slot="thead">

                    <vs-th>Plan</vs-th>
                    <vs-th>Seguro</vs-th>
                    <vs-th>Descuento</vs-th>
                    <vs-th>Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr v-for="(datos, index) in data" :key="index">
                        <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td><vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nombre_seguro">{{datos.nombre_seguro}}</vs-td><vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.descuento">{{datos.descuento}}</vs-td>
                        <vs-td v-else >-</vs-td>
                        
                        <vs-td class="whitespace-no-wrap  estilosacciones">
                            <feather-icon v-if="editarrol" icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="editar(datos.id_plan_seguro)" />
                            <feather-icon v-if="eliminarrol" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="eliminar(datos.id_plan_seguro)"/>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
import $ from "jquery";
const axios = require("axios");
const {rutasEmpresa: { DATA_EMPRESA }} = require("../../../../../../config-routes/config.js");
import script_comprobantes from '../../../../factura.js';
export default {
    components: {
        AgGridVue,
        flatPickr
    },
    filters: {
        fecha(data) {
            return moment(data).format("LL");
        },
        fechayhora(data) {
            return moment(data).format("LLL");
        }
    },
    data(){
        return{
            i18nbuscar: this.$t("i18nbuscar"),
            buscar:"",
            contenido:[]
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        crearrol() {
            var res = 0
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Plan Seguro"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Plan Seguro"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Plan Seguro"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        },

    },
    methods:{

        listar(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.contenido = [];
                var url = "/api/planes_seguro/"+this.usuario.id_empresa;
                var datos = {
                    page: page,
                    buscar: buscar,
                    datos: this.usuario
                };
                axios.post(url, datos).then(res => {
                    this.contenido=res.data.recupera;
                });
            }, 800);
        },
        editar(id){

        },
        eliminar(cd){
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: cd
            });
        },
        acceptAlert(parameters) {
            axios.get("/api/eliminar_plan_seguro/" + parameters)
            .then(resp=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.listar(1, this.buscar);
            })
            .catch(err=>{
                this.$vs.notify({

                    text: "No se puedo Eliminar este registro",
                    color: "danger"
                });
            });
            
        },

    },
    mounted(){
        this.listar(1, "");
    },
    
}
</script>
