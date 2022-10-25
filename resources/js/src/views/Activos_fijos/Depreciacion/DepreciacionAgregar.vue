<template>
    <div id="invoice-page">
        <vx-card>
            <div class="vx-row">
                <div class="vx-col sm:w-1/4 w-full mb-6" hidden>
                        <label for="" class="vs-input--label">Fecha Inicio:</label>
                        <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="fecha_inicio"
                            placeholder="Elegir Fecha"
                        />
                        <div v-show="error" v-if="!fecha_inicio">
                            <div v-for="err in errorfecha_inicio" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" v-if="!existe_fecha_ant">
                        <label for="" class="vs-input--label">Fecha Fin:</label>
                        <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="fecha_fin"
                            placeholder="Elegir Fecha"
                        />
                        <div v-show="error" v-if="!fecha_fin">
                            <div v-for="err in errorfecha_fin" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                        <label for="" class="vs-input--label">Fecha Fin:</label>
                        <flat-pickr
                            disabled
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="fecha_fin"
                            placeholder="Elegir Fecha"
                        />
                        <div v-show="error" v-if="!fecha_fin">
                            <div v-for="err in errorfecha_fin" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label for="" class="vs-input--label">Tipo Activo Fijo:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="tipo_activo"
                    @change="activo_fijo_esp(tipo_activo,grupo_activo)"
                    >
                    <vs-select-item
                        v-for="data in activo_tipo"
                        :key="data.id_activo_fijo_tipo"
                        :value="data.id_activo_fijo_tipo"
                        :text="data.nombre"
                    />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label for="" class="vs-input--label">Grupo Activo Fijo:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="grupo_activo"
                    @change="activo_fijo_esp(tipo_activo,grupo_activo)"
                    >
                    <!--@change="grupo_valor(grupo_activo)"-->
                    <vs-select-item
                        v-for="data in activo_grupo"
                        :key="data.id_activo_fijo_grupo"
                        :value="data.id_activo_fijo_grupo"
                        :text="data.nombre"
                    />
                    </vs-select>
                </div>
            </div>
            <vs-divider position="left" class="flexy" >
                <h3>Activos Fijos</h3>
                <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="estado_activos" @change="activo_fijo_todos(estado_activos)" vs-value="Todos" style="margin-top: 4px;" v-if="!tipo_activo && !grupo_activo">
                    <span slot="on">Todos</span>
                    <span slot="off"></span>
                </vs-switch>
            </vs-divider>
            <!-- <div class="vx-col sm:w-full w-full mb-6 relative">
                <div class="vx-row" v-for="(tr,index) in valoractivos" :key="index">
                    <div class="vx-col sm:w-1/4 w-full mb-2">
                        <h6>Nombre Activo Fijo:</h6>        
                        <vs-input disabled class="w-full" v-model="tr.nombre_activo_fijo" />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2">
                        <h6>Tipo Activo Fijo:</h6>        
                        <vs-input disabled class="w-full" v-model="tr.tipo_activo" />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2">
                        <h6>Grupo Activo Fijo:</h6>        
                        <vs-input disabled class="w-full" v-model="tr.grupo_activo" />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-2">
                        <h6>Valor Depreciacion:</h6>        
                        <vs-input disabled class="w-full" v-model="tr.valor_depreciacion" />
                    </div>
                    <feather-icon
                              
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer"
                                @click="borrarprov(index)"
                              />
                             
                </div>
                <div class="vx-row" v-if="valoractivos.length>0">
                    <div class="vx-col sm:w-3/4 w-full mb-2">
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-2">
                        <h6>TOTAL: {{total_bien |currency}}</h6>
                    </div>
                </div>
            </div> -->
            <div class="vx-col sm:w-full w-full mb-6 relative">
                <!--<a class="flex items-center cursor-pointer mb-4" @click="abrirproductos()">Añadir Productos</a>-->
                <div class="vx-col sm:w-full w-full relative" v-if="valoractivos.length>0">
                <vs-table hoverFlat :data="valoractivos" style="font-size: 12px;">
                    <template slot="thead">
                        <vs-th>Nombre</vs-th>
                        <vs-th>Fecha</vs-th>
                        <vs-th>Dias</vs-th>
                        <vs-th>Tipo Activo Fijo</vs-th>
                        <vs-th>Grupo Activo Fijo</vs-th>
                        <vs-th style="text-align:right!important">Valor Depreciacion</vs-th>
                        <vs-th>Eliminar</vs-th>
                    </template>
                    <template slot-scope="{data}">
                        <vs-tr v-for="(tr, index) in data" :key="index">
                            <vs-td :data="tr.nombre_activo_fijo">{{ tr.nombre_activo_fijo }}</vs-td>
                            <vs-td :data="tr.fecha_activo" v-if="tr.fecha_activo">{{tr.fecha_activo}}</vs-td>
                            <vs-td :data="tr.fecha_activo" v-else>-</vs-td>
                            <vs-td :data="tr.dias_transcurridos" v-if="tr.numero_meses_depreciacion || tr.numero_meses_depreciacion!=null">{{tr.dias_transcurridos}}</vs-td>
                            <vs-td :data="tr.dias_transcurridos" v-else>30</vs-td>
                            <vs-td :data="tr.tipo_activo">{{tr.tipo_activo}}</vs-td>
                            <vs-td :data="tr.grupo_activo">{{tr.grupo_activo}}</vs-td>
                            <vs-td style="text-align:right!important" v-if="tr.numero_meses_depreciacion || tr.numero_meses_depreciacion!=null">{{(tr.valor_depreciacion/30)*tr.dias_transcurridos | currency}}</vs-td>
                            <vs-td style="text-align:right!important" v-else>{{(tr.valor_depreciacion/30)*30 | currency}}</vs-td>
                            <vs-td style="text-align:center!important;width:80px!important;">
                                <feather-icon
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer"
                                @click="borrarprov(index)"
                                />
                            </vs-td>
                        </vs-tr>
                    </template>
                    </vs-table>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative"  v-if="proveedor_tipo==true">
                    <vs-input class="w-full busqueda_cliente" placeholder="Busca Activo Fijo" v-model="busqueda_cliente" @keyup="documentos()"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-7 h-7 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_lista_proveedor">
                            <ul class="ul_busqueda_lista" v-if="contenidoprov.length">
                                <li v-for="(tr,index) in contenidoprov" :key="index" @click="seleccionar_cliente(tr,'fact')"> {{ tr.nombre_activo }} </li>
                            </ul>
                            <ul class="ul_busqueda_lista" v-else-if="preloader_prov == true && contenidoprov.length<1">
                                <li>
                                        ESTE ACTIVO FIJO NO SE ENCUENTRA REGISTRADO
                                </li>
                            </ul>
                    </div>
                </div>
                <div v-show="error" v-if="valoractivos.length<=0">
                            <div v-for="err in errorvaloractivos" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>
            <div v-if="valoractivos.length>0">
                <h6>Valor Total {{total_bien |currency}}</h6>
            </div>
            <br>
           
            <div class="vx-col w-full">
                        <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id">GUARDAR</vs-button>
                        <vs-button color="success" type="filled" @click="editar()" v-else>GUARDAR</vs-button>
                        <vs-button color="warning" type="filled" @click="vaciar()">BORRAR</vs-button>
                        <vs-button color="danger"  type="filled" @click="cancelar()">CANCELAR</vs-button>
            </div>
            <!-- <vs-divider>
            </vs-divider>
            <div class="vx-row" >
                <div class="vx-col sm:w-3/4 w-full mb-2">
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-2">
                    <h6>Total:</h6> 
                    <vs-input disabled class="w-full" v-model="total_depreciacion" />
                </div>
            </div>-->
        </vx-card>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        flatPickr,
        "v-select": vSelect
    },
    data(){
        return{
            configdateTimePicker: {
                locale: SpanishLocale
            },
            fecha_inicio:"",
            fecha_fin:"",
            activo_grupo:[],
            activo_tipo:[],
            tipo_activo:"",
            grupo_activo:"",
            estado_activos:false,
            total_depreciacion:"",
            //valor individaual activo
            valoractivos:[],
            proveedor_tipo:true,
            factura_tipo:true,
            busqueda_cliente:"",
            contenidoprov:[],
            preloader_prov:false,
            tipo_identificacion_menu: [
                { text: "Seleccione", value: 0 },
                { text: "Cédula de Identidad", value: "Cedula" },
                { text: "Ruc", value: "Ruc" },
                { text: "Pasaporte", value: "Pasaporte" },
                { text: "Extranjero", value: "Extranjero" },
                { text: "Consumidor Final", value: 4 }
            ],
            existe_fecha_ant:"",
            //errores
            error:0,
            errorfecha_inicio:[],
            errorfecha_fin:[],
            errorvaloractivos:[],
        };
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        total_bien(){
            if(this.valoractivos.length>0){
                var total=0;
                this.valoractivos.forEach(el => {
                    if(el.numero_meses_depreciacion || el.numero_meses_depreciacion!==null){
                        total += parseFloat((el.valor_depreciacion/30)*el.dias_transcurridos);
                    }else{
                        total += parseFloat((el.valor_depreciacion/30)*30);
                    }
                    //if(el.numero_meses_depreciacion){
                        
                    //}
                });
                return total;
            }
        }
    },
    methods:{
        fechaMax(){
            let me = this;
            var buscar3="";
            var url =
                "/api/depreciacion/fecha_maximo/" +
                this.usuario.id_empresa +
                "?page=" +
                1 +
                "&buscar=" +
                buscar3;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    console.log(JSON.stringify(response.data[0].max_fecha));
                    me.fecha_fin= response.data[0].max_fecha;
                    if(response.data[0].max_fecha==null){
                        me.existe_fecha_ant=null;
                    }else{
                        me.existe_fecha_ant=1;
                    }
                    console.log(me.existe_fecha_ant);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarGrupo(page3,buscar3){
            let me = this;
            var url =
                "/api/grupo_activo/" +
                this.usuario.id_empresa +
                "?page=" +
                page3 +
                "&buscar=" +
                buscar3;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.activo_grupo = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        activo_fijo_todos(estado,page='',buscar=''){
            let me = this;
            if(estado==true){
                this.proveedor_tipo=false;
                this.valoractivos =[];
                var url =
                "/api/depreciacion/activos-fijos/todos/" +
                this.usuario.id_empresa +
                "?page=" +
                1 +
                "&buscar=" +
                buscar;
                axios
                    .get(url)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.valoractivos = respuesta.recupera;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }else{
                me.proveedor_tipo=true;
                me.busqueda_cliente="";
                me.valoractivos =[];
                
            }
            
        },
        activo_fijo_esp(tipo,grupo){
            let me = this;
            this.estado_activos=false;
            this.proveedor_tipo=false;
            this.busqueda_cliente="";
            this.valoractivos =[];
            var buscar="";
            var url =
                "/api/depreciacion/activos-fijos/" +
                this.usuario.id_empresa +
                "?page=" +
                1 +
                "&buscar=" +
                buscar+
                "&tipo="+
                tipo+
                "&grupo="+
                grupo;
                axios
                    .get(url)
                    .then(function(response) {
                        var respuesta = response.data;
                        me.valoractivos = respuesta.recupera;
                        if(me.valoractivos.length<1){
                            me.$vs.notify({
                                text: "No se a encontrado ningun Activo Fijo",
                                color: "danger"
                            });
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
        },
        listarTipo(page,buscar2){
            let me = this;
            var url =
                "/api/tipo_activo/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.activo_tipo = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        guardar(){
            if(this.validar()){
                return;
            }
            axios.post('/api/guardar/depreciacion', {
                        fecha_inicio:this.fecha_inicio,
                        fecha_fin:this.fecha_fin,
                        todos_activos:this.estado_activos,
                        ucrea:this.usuario.id,
                        id_grupo_activo_fijo:this.grupo_activo,
                        id_tipo_activo_fijo:this.tipo_activo,
                        id_empresa:this.usuario.id_empresa
                    }).then( response => {
                        this.guardarDetalle(response.data);
                    }).catch(err=>{
                        console.log("ERROR :: error al guardar:"+err);
                    });
        },
        validar(){
            this.error=0;
            this.errorfecha_inicio=[];
            this.errorfecha_fin=[];
            this.errorvaloractivos=[];
            // if(!this.fecha_inicio){
            //     this.errorfecha_inicio.push("Campo Obligatorio");
            //     this.error=1
            // }
            if(!this.fecha_fin){
                this.errorfecha_fin.push("Campo Obligatorio");
                this.error=1
            }
            if(this.valoractivos.length<=0){
                this.errorvaloractivos.push("Campo Obligatorio");
                this.error=1
            }
            return this.error;
        },
        guardarDetalle(id){
            axios.post('/api/guardar/depreciacion/detalle', {
                        id_depreciacion:id,
                        fecha_fin:this.fecha_fin,
                        activos_fijos:this.valoractivos
                    }).then( response => {
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/activos-fijos/depreciacion");
                    }).catch(err=>{
                        console.log("ERROR :: error al guardar:"+err);
                    });
        },
        vaciar(){
            this.fecha_inicio="";
            this.fecha_fin="";
            this.tipo_activo="";
            this.grupo_activo="";
            this.estado_activos=false;
            this.valoractivos=[];
            this.proveedor_tipo=true;
            this.busqueda_cliente="";  
        },
        cancelar(){
            this.$router.push("/activos-fijos/depreciacion");
        },
        //methodos busqueda individual activo fijo
        documentos(){
                    axios.post('/api/depreciacion/activos-fijos/individual', {
                        factura:this.busqueda_cliente,
                        id_empresa: this.usuario.id_empresa,
                    }).then( ({data}) => {
                        if(data=='error'){
                            this.$vs.notify({
                                title: "Activo Fijo erronea",
                                text: "Este activo fijo no consta en nuestro sistema",
                                color: "danger"
                            }); 
                            this.contenidoprov=[];
                            if(this.contenidoprov.length>0){
                                this.preloader_prov =false;
                            }else{
                                this.preloader_prov =true;
                            }
                            
                        }else{
                            this.contenidoprov = data.activo;
                            
                            if(this.contenidoprov.length>0){
                                this.preloader_prov =false;
                            }else{
                                this.preloader_prov =true;
                            }
                            
                        }
                    }).catch(err=>{
                        console.log("ERROR:: traer activos :"+err);
                    });
        },
        seleccionar_cliente(tr,tipo){
                        this.contenidoprov=[];
                        this.busqueda_cliente="";
                        this.valoractivos.push(
                            {
                                id_activos_fijos:tr.id_activos_fijos,
                                nombre_activo_fijo:tr.nombre_activo_fijo,
                                fecha_activo:tr.fecha_activo,
                                valor_depreciacion:tr.valor_depreciacion,
                                grupo_activo:tr.grupo_activo,
                                tipo_activo:tr.tipo_activo,
                            },
                        );
                        this.proveedor_tipo=true;
                        this.factura_tipo=true;
                        this.busqueda_cliente="";
                        //this.listar_cliente("");
                        $(".busqueda_lista_proveedor").show();
        },
        borrarprov(id) {
            this.valoractivos.splice(id, 1);
        },
    },
    mounted(){
        this.fechaMax();
        this.listarTipo(1,"");
        this.listarGrupo(1,"");
    }
}
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.vs-popup {
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
.peque2 .vs-popup {
  width: 900px !important;
}
.sindis .vs-input--input:focus {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
}
.menuescoger {
    position: absolute;
    margin-top: -11px;
    width: 100%;
    background: #fff;
    z-index: 999;
    border: 1px solid #dfdfdf;
    border-radius: 0 0 8px 8px;
}

.menuescoger ul {
    list-style: none;
    padding: 8px 15px 25px 15px;
    margin: 0;
    cursor: pointer;
    color: #848484;
    font-weight: 600;
    font: 14px arial, sans-serif;
    position: relative;
    border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover {
    background: rgba(0, 0, 0, 0.1);
}

.menuescoger span {
    font-size: 12px;
}
.posicion {
    bottom: 5px;
    position: absolute;
    left: 15px;
}
.posicion span {
    font-size: 10px;
}
.sindis .vs-input--input {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.nover > .icon-select {
  display: none;
}
.hovertrash:hover > .trasher {
  display: block !important;
}
.botonstl{
  height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido{
  background: #635ace!important;
  color:#fff!important;
}
.busqueda_cliente input{
        height: 50px;
        padding-left: 45px!important;
    }
    .busqueda_cliente_icono{
        position: absolute!important;
        top: 11px;
        left: 15px;
    }
    .busqueda_lista{
        position: absolute;
        width: 97%;
        z-index: 9;
    }
    .cabezera_total span{
        float: right;
        margin-right: 25px;
    }
    .cabezera_total>div{
        margin-left: 20px;
        padding: 9px 3px;
    }
    .cabezera_total{
        margin-top:15px;
    }
    .ul_busqueda_lista{
        min-width: 160px;
        margin: -2px 0 0;
        list-style: none;
        font-size: 13.5px;
        text-align: left;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.15);
        border-radius: 2px;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        background-clip: padding-box;
    }
    .ul_busqueda_lista li{
        padding: 10px 16px;
        text-overflow: ellipsis;
        overflow: hidden;
        font-weight: 300;
        display: block;
        clear: both;
        line-height: 1.3;
        color: #333;
        white-space: nowrap;
        font-family: sans-serif;
    }
    .ul_busqueda_lista li:hover{
        background: rgba(16, 22, 58, 0.38);
        cursor: pointer;
        color:#fff;
    }
    .busqueda_cliente .input-span-placeholder{
        padding-left: 50px;
        margin-top: 3px;
    }
    .buscar_otro{
        position: absolute;
        margin-top: -35px;
        margin-left: 14px;
        cursor: pointer;
    }
    .lista_preloader{
        padding: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .preloader {
        width: 50px;
        height: 50px;
        border: 10px solid #eee;
        border-top: 10px solid #666;
        border-radius: 50%;
        animation-name: girar;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }
</style>