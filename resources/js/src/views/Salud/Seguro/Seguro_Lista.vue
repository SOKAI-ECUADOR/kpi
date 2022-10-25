<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
                </div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar_plan" @keyup="listar(1, buscar_plan)" v-bind:placeholder="i18nbuscar"/>
                                <div class="dropdown-button-container mr-3">
                                <vs-dropdown>
                                    <vs-button
                                        class="btn-drop"
                                        type="filled"
                                        icon="settings"
                                        style="border-radius: 5px;"
                                    ></vs-button>
                                    <vs-dropdown-menu style="width:13em;">
                                        <vs-dropdown-item
                                            class="text-center"
                                            divider
                                            @click="abrirSeguro()"
                                            >Seguro</vs-dropdown-item
                                        >
                                    </vs-dropdown-menu>
                                </vs-dropdown>
                </div>
                        <div  v-if="crearrol">
                            <vs-button class="btnx" type="filled" to="/salud/plan_seguro/agregar">Agregar</vs-button>
                        </div>
                </div>
            </div>
            <vs-table stripe  :data="contenido_plan">
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
                            <feather-icon v-if="editarrol" icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="editar_pln_seguro(datos.id_plan_seguro)" />
                            <feather-icon v-if="eliminarrol" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="eliminar_pln_seguro(datos.id_plan_seguro)"/>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
            <vs-popup  title="Seguro" :active.sync="modalSeguro">
                <vx-card>
                    <div class="flex flex-wrap justify-between items-center mb-3">
                        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
                        </div>
                        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1, buscar)" v-bind:placeholder="i18nbuscar"/>
                                        
                                <div  v-if="crearrol">
                                    <vs-button class="btnx" type="filled" @click="agregarmodal()">Agregar</vs-button>
                                </div>
                        </div>
                    </div>
                    <vs-table stripe  :data="contenido">
                        <template slot="thead">

                            <vs-th>Nro</vs-th>
                            <vs-th style="width:80%">Seguro</vs-th>
                            
                            <vs-th>Opciones</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(datos, index) in data" :key="index">
                                <vs-td v-if="datos.numero">{{datos.numero}}</vs-td><vs-td v-else>-</vs-td>
                                <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td><vs-td v-else>-</vs-td>
                                
                                
                                
                                <vs-td class="whitespace-no-wrap  estilosacciones">
                                    <feather-icon v-if="editarrol" icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="editarmodal(datos.id_seguro,datos.nombre)" />
                                    <feather-icon v-if="eliminarrol" icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="eliminar(datos.id_seguro)"/>
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </vx-card>
                <vs-popup  title="Agregar Seguro" :active.sync="modalAgregar">
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label for="" class="vs-input--label">Nombre:</label>
                                <vs-input class="w-full" v-model="nombre_seguro"/>
                                <div v-show="error" v-if="!nombre_seguro">
                                    <span
                                        class="text-danger"
                                        v-for="err in errorseguro"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                        </div>
                        <div class="vx-col w-full">
                            <vs-button color="success" type="filled" :disabled="disabled_seguro" @click="guardar()" v-if="idrecupera==null">GUARDAR</vs-button>
                            <vs-button color="success" type="filled" @click="editar()" v-else>GUARDAR</vs-button>
                            <vs-button color="warning" type="filled" @click="vaciar()">BORRAR</vs-button>
                            <vs-button color="danger"  type="filled" @click="cancelar()">CANCELAR</vs-button>
                        </div>
                    </div>

                </vs-popup>
            </vs-popup>
            
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
            contenido:[],
            modalSeguro:false,
            modalAgregar:false,
            nombre_seguro:"",
            idrecupera:null,
            disabled_seguro:false,
            //errores seguro
            error:0,
            errorseguro:[],
            //variables plan seguro
            buscar_plan:"",
            contenido_plan:[]
            
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
                    if(el.nombre == "Seguro"){
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
                    if(el.nombre == "Seguro"){
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
                    if(el.nombre == "Seguro"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        },
    },
    methods:{
        ////////////////////////////// modulo seguro
        listar(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.contenido = [];
                var url = "/api/seguros/"+this.usuario.id_empresa;
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
        abrirSeguro(){
            this.listar(1, "");
            this.modalSeguro=true;
        },
        agregarmodal(){
            this.nombre_seguro="";
            this.idrecupera=null;
            this.modalAgregar=true;
            
        },
        editarmodal(id,nombre){
            this.idrecupera=id;
            this.nombre_seguro=nombre;
            this.modalAgregar=true;
        },
        guardar(){
            this.disabled_seguro=true;
            if(this.validarseguro()){
                this.disabled_seguro=false;
                return;
            }
            var url="/api/guardar_seguro";
            axios.post(url,{
                nombre:this.nombre_seguro,
                id_empresa:this.usuario.id_empresa,
                ucrea:this.usuario.id
            })
            .then(resp=>{
                this.modalAgregar=false;
                this.disabled_seguro=false;
                this.listar(1,"");
            })
            .catch(err=>{
                console.log(err);
            });
        },
        editar(){
            if(this.validarseguro()){
                return;
            }
            var url="/api/editar_seguro";
            axios.put(url,{
                id_seguro:this.idrecupera,
                nombre:this.nombre_seguro,
                id_empresa:this.usuario.id_empresa,
                umodifica:this.usuario.id
            })
            .then(resp=>{
                this.idrecupera=null;
                this.modalAgregar=false;
                
                this.listar(1,"");
            })
            .catch(err=>{
                console.log(err);
            });
        },
        validarseguro(){
            this.error=0;
            this.errorseguro=[];
            if(!this.nombre_seguro){
                this.errorseguro.push("Campo Obligatorio");
                this.error=1
            }
            return this.error;
        },
        vaciar(){
            this.nombre_seguro="";
        },
        cancelar(){
            this.modalAgregar=false;
            this.listar(1, "");
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
            axios.get("/api/eliminar_seguro/" + parameters)
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
        /////////////////////////////////////////////////////////
        listar_plan(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.contenido_plan = [];
                var url = "/api/planes_seguro/"+this.usuario.id_empresa;
                var datos = {
                    page: page,
                    buscar: buscar,
                    datos: this.usuario
                };
                axios.post(url, datos).then(res => {
                    this.contenido_plan=res.data.recupera;
                });
            }, 800);
        },
        editar_pln_seguro(id){
            this.$router.push(`/salud/plan_seguro/${id}/editar`);
        },
        eliminar_pln_seguro(cd){
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert_pln_seguro,
                parameters: cd
            });
        },
        acceptAlert_pln_seguro(parameters) {
            axios.get("/api/eliminar_plan_seguro/" + parameters)
            .then(resp=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.listar_plan(1, this.buscar_plan);
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
        
        this.listar_plan(1,"");
    },
    
}
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
  width: 800px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.peque2 .vs-popup {
    width: 900px !important;
}
.peque3 .vs-popup {
    width: 400px !important;
}
.peque4 .vs-popup {
    width: 1080px !important;
}
</style>