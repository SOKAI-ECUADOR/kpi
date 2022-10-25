<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                    <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(buscar)" placeholder="Buscar..."/>
                    <div class="dropdown-button-container">
                        <vs-button class="btnx" type="filled" @click="abrirmodal('agregar')">Agregar</vs-button>
                        <vs-dropdown>
                            <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center">Importar registros</vs-dropdown-item>
                                <vs-dropdown-item class="text-center">Exportar registros</vs-dropdown-item>
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Código</vs-th>
                    <vs-th>Descripción</vs-th>
                    <vs-th>Descripción SRI</vs-th>
                    <vs-th>Plan de cuentas</vs-th>
                    <vs-th>Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                        <vs-td v-if="tr.codigofps">{{ tr.codigofps }}</vs-td>
                        <vs-td v-if="tr.descripcion">{{ tr.descripcion }}</vs-td>
                        <vs-td v-if="tr.descripcionfps">{{ tr.descripcionfps }}</vs-td>
                        <vs-td v-if="tr.codcta">{{ tr.codcta }}</vs-td> <vs-td v-else> - </vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <vx-tooltip text="Editar Proyecto" style="display: inline-flex;">
                                <feather-icon icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click="abrirmodal('editar', tr)" />
                            </vx-tooltip>
                            <vx-tooltip text="Eliminar Proyecto" style="display: inline-flex;">
                                <feather-icon icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click="eliminar(tr.id_forma_pagos)" />
                            </vx-tooltip>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>

            <vs-popup :title="modal.titulo" :active.sync="modal.abrir">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label for="" class="vs-input--label">Descripción:</label>
                            <vs-textarea class="selectExample w-full" v-model="tabla.descripcion" rows="3" />
                            <div v-show="validacion.estado" v-if="!tabla.descripcion">
                                <span class="text-danger" v-for="err in validacion.valores.descripcion" :key="err" v-text="err"></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label for="" class="vs-input--label">Forma de pagos del SRI:</label>
                            <vs-select placeholder="Selecciona el método de pago" autocomplete class="selectExample w-full" v-model="tabla.forma_pagos_sri">
                                <vs-select-item v-for="(tr,index) in formapagossri" :key="index" :value="tr.id_forma_pagos_sri" :text="tr.codigo+' - '+tr.descripcion"/>
                            </vs-select>
                            <div v-show="validacion.estado" v-if="!tabla.forma_pagos_sri">
                                <span class="text-danger" v-for="err in validacion.valores.forma_pagos_sri" :key="err" v-text="err"></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label for="" class="vs-input--label">Tipo Forma de Pago:</label>
                            <vs-select placeholder="Selecciona el tipo forma de pago" autocomplete class="selectExample w-full" v-model="tipo_forma_pago">
                                <vs-select-item value="Cheque" text="Cheque" />
                                <vs-select-item value="Transferencia" text="Transferencia" />
                                <vs-select-item value="Deposito" text="Deposito" />
                                <vs-select-item value="Nota de Credito" text="Nota de Credito" />
                                <vs-select-item value="Nota de Debito" text="Nota de Debito" />
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label for="" class="vs-input--label">Cuenta contable:</label>
                            <vx-input-group>
                                <vs-input class="w-full" :value="plan_cuenta.nom_cta" disabled/>
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button color="primary" @click="popupActive = true">Buscar</vs-button>
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                    </div>
                    <vs-button color="success" v-if="modal.tipo==1" @click="guardar()">GUARDAR</vs-button>
                    <vs-button color="success" v-if="modal.tipo==2" @click="editar()">GUARDAR</vs-button>
                    <vs-button color="danger" @click="modal.abrir = false">CANCELAR</vs-button>
                </div>
            </vs-popup>
            <vs-popup title="Seleccione una Cuenta Contable" :active.sync="popupActive" :class="'peque'" style="z-index:99999999">
                <div class="con-exemple-prompt">
                    <vs-input class="mb-4 md:mb-0 mr-4 w-full" v-model="listarpc" @keyup="listar_cuenta_contable(listarpc)" v-bind:placeholder="i18nbuscar"/>
                    <vs-table stripe @selected="selectpc" :data="plan_cuenta.lista">
                        <template slot="thead">
                            <vs-th>No.Cuenta</vs-th>
                            <vs-th>Tipo Cuenta</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                                <vs-td :data="data[indextr].codcta">{{ data[indextr].codcta }}</vs-td>
                                <vs-td :data="data[indextr].nomcta">{{ data[indextr].nomcta }}</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
            </vs-popup>
        </vx-card>
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import vSelect from "vue-select";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
const axios = require("axios");

export default {
    components: {
        AgGridVue
    },
    // computed: {
    //     usuario() {
    //         return this.$store.state.AppActiveUser;
    //     },
    //     token() {
    //         return this.$store.state.Token;
    //     },
    // },
    data() {
        return {
            contenido:[],
            buscar:'',
            modal:{
                abrir:false,
                tipo:0,
                titulo:'',
            },
            tabla:{
                id:null,
                codigo:'',
                descripcion:'',
                forma_pagos_sri:null,
            },
            validacion:{
                estado:0,
                valores:{
                    codigo:[],
                    descripcion:[],
                    forma_pagos_sri:[]
                }
            },
            formapagossri:[],
            plan_cuenta:{
                lista:[],
                nom_cta:'',
                cod_cta:'',
                cta:null,
            },
            tipo_forma_pago:"",
            popupActive:false,
            listarpc:'',
            i18nbuscar: this.$t("i18nbuscar"),
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
                    if(el.nombre == "Forma de pagos"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            //console.log(res+"Rol");
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
                    if(el.nombre == "Forma de pagos"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarrol() {
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
                    if(el.nombre == "Forma de pagos"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        }
    },
    methods: {
        listar(buscar){
            axios.get("/api/administrar/forma_pagos/listar?buscar=" + buscar + "&empresa=" + this.usuario.id_empresa).then( ({data}) => {
                this.contenido = data;
            }).catch( err => {
                console.log(err);
            });
        },
        abrirmodal(tipo, data){
            console.log(data);
            switch (tipo) {
                case "agregar": {
                    this.modal = {
                        abrir:true,
                        tipo:1,
                        titulo:'Agregar Forma de pago',
                    },
                    this.tabla = {
                        id:null,
                        descripcion:'',
                        forma_pagos_sri:null,
                    } 
                    this.plan_cuenta.cta = null;
                    this.plan_cuenta.cod_cta = null;
                    this.plan_cuenta.nom_cta = null;
                    this.tipo_forma_pago = "";
                    break;
                }
                case "editar": {
                    this.modal = {
                        abrir:true,
                        tipo:2,
                        titulo:'Editar Forma de pago',
                    },
                    this.tabla = {
                        id:data.id_forma_pagos,
                        descripcion:data.descripcion,
                        forma_pagos_sri:data.id_forma_pagos_sri,
                    }
                    this.plan_cuenta.nom_cta = data.nom_cta;
                    this.plan_cuenta.cod_cta = data.codcta;
                    this.plan_cuenta.cta = data.id_plan_cuentas;
                    this.tipo_forma_pago = data.tipo_forma_pago;
                    break;
                }
            }
        },
        guardar(){
            if(this.validar()){return;}
            axios.post("/api/administrar/forma_pagos/guardar", {tabla:this.tabla,empresa:this.usuario.id_empresa, cta:this.plan_cuenta.cta, nom_cta:this.plan_cuenta.nom_cta, cod_cta:this.plan_cuenta.cod_cta,tipo_forma_pago:this.tipo_forma_pago}).then( ({data}) => {
                if(data == 'errorcuenta'){
                    this.$vs.notify({
                        title: "Error en plan de cuenta",
                        text: "Este plan de cuenta no existe en el sistema",
                        color: "danger"
                    });
                    return;
                }else{
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.modal.abrir = false;
                    this.listar(this.buscar);
                }
            }).catch( err => {
                console.log(err);
            });
        },
        editar(){
            if(this.validar()){return;}
            axios.put("/api/administrar/forma_pagos/editar", {tabla:this.tabla, empresa:this.usuario.id_empresa , cta:this.plan_cuenta.cta,nom_cta:this.plan_cuenta.nom_cta, cod_cta:this.plan_cuenta.cod_cta,tipo_forma_pago:this.tipo_forma_pago}).then( ({data}) => {
                if(data == 'errorcuenta'){
                    this.$vs.notify({
                        title: "Error en plan de cuenta",
                        text: "Este plan de cuenta no existe en el sistema",
                        color: "danger"
                    });
                    return;
                }else{
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro actualizado con éxito",
                        color: "success"
                    });
                    this.modal.abrir = false;
                    this.listar(this.buscar);
                }
            }).catch( err => {
                console.log(err);
            });
        },
        eliminar(id){
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/administrar/forma_pagos/eliminar/" + parameters);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
             this.listar(this.buscar);
        },
        validar(){
            this.validacion = {
                estado:0,
                valores:{
                    codigo:[],
                    descripcion:[],
                    forma_pagos_sri:[]
                }
            }
            if(!this.tabla.descripcion){
                this.validacion.valores.descripcion.push("Debe Ingresar una Descripcion");
                this.validacion.estado = 1;
            }
            if(!this.tabla.forma_pagos_sri){
                this.validacion.valores.forma_pagos_sri.push("Debe Esocger un forma de pago");
                this.validacion.estado = 1;
            }

            return this.validacion.estado;
        },
        pagos_sri(){
            axios.get("/api/forma_pagos_sri/listar?empresa=" + this.usuario.id_empresa).then( ({data}) => {
                this.formapagossri = data;
            }).catch( err => {
                console.log(err);
            });
        },
        listar_cuenta_contable(listarpc){
            axios.get('/api/administrar/forma_pagos/cuenta_contable?empresa='+this.usuario.id_empresa+'&buscar='+listarpc).then( ({data}) => {
                this.plan_cuenta.lista = data;
            }).catch( error => {
                console.log(error);
            });
        },
        selectpc(tr) {
            var punto = tr.codcta.charAt(tr.codcta.length-1)
            if(punto=='.'){
                this.$vs.notify({
                    title: "Cuenta contable erroneo",
                    text: "Esta cuenta contable no es válida",
                    color: "danger"
                });
            }else{
                this.plan_cuenta.cta = `${tr.id_plan_cuentas}`;
                this.plan_cuenta.nom_cta = `${tr.nomcta}`;
                this.plan_cuenta.cod_cta = `${tr.cod_cta}`;
                this.popupActive = false;
            }
        },
    },
    mounted() {
        this.listar(this.buscar);
        this.pagos_sri();
        this.listar_cuenta_contable(this.listarpc);
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
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.peque1 .vs-popup {
    width: 500px !important;
}
.peque2 .vs-popup {
    width: 500px !important;
}
input[type="”file”"]#nuestroinput {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
label[for=" nuestroinput"] {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background-color: #106ba0;
    display: inline-block;
    transition: all 0.5s;
    cursor: pointer;
    padding: 15px 40px !important;
    text-transform: uppercase;
    width: fit-content;
    text-align: center;
}
.imagenpre {
    height: 100%;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.centimg {
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.verimagen {
    overflow: hidden;
    padding: 0px;
    height: 300px;
    height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    border: 5px solid rgba(0, 0, 0, 0.3);
}
.centimg {
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.centimg:hover {
    background: rgba(255, 255, 255, 0.6) !important;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.centimg img {
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.demo-alignment > * {
    margin-right: 1.5rem;
    margin-top: 0.8rem;
}
.activeNoti{
    z-index: 999999999!important;
}
</style>
