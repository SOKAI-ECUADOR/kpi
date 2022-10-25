<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1,buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
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
                                    @click="abrirCierrePeriodo()"
                                    >Cierre Periodo</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <div class="dropdown-button-container">
                        <vs-button class="btnx" type="filled" to="/administrar/cierre_contable/ejercicio_contable/agregar">Agregar</vs-button>
                
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <!--<vs-th>#</vs-th>-->
                    <vs-th>Código</vs-th>
                    <!--<vs-th>Proyecto</vs-th>-->
                    <vs-th>Concepto</vs-th>
                    <vs-th>Comprobante</vs-th>
                    <vs-th>Razon social</vs-th>
                    <vs-th>Periodo</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id_asientos" v-for="(datos) in data">
                        <!--<vs-td >{{index + 1}}</vs-td>-->
                        <vs-td v-if="datos.numero">{{datos.codigo_comprobante.toUpperCase()}}-{{ datos.numero }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <!--<vs-td v-if="datos.descripcion">
                            {{ datos.descripcion }}
                        </vs-td>
                        <vs-td v-else>-</vs-td>-->
                        <vs-td v-if="datos.concepto">{{
                            datos.concepto
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.tipo">{{
                            datos.tipo.toUpperCase()
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.razon_social">{{
                            datos.razon_social
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.periodo">{{ datos.periodo }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                icon="EyeIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="editarAsientoContable(datos.id_asientos)"
                            />
                            <feather-icon
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos)"
                            />
                            <!-- <feather-icon
                                icon="EditIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="editarAsientoContable(datos.id_asientos)"
                            />

                            <feather-icon
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos,datos.id_asientos_comprobante,datos.id_proyecto,datos.codigo_rol)"
                            />
                            <feather-icon
                                icon="PrinterIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarPdf(datos.id_asientos)"
                            />
                            <feather-icon
                                v-if="datos.existe_cheque>0"
                                hidden
                                icon="CreditCardIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarCheques(datos.id_asientos,datos.fecha)"
                            /> -->
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <vs-popup
            :class="'peque2'"
            title="Cierre Periodo"
            :active.sync="modal_mes"
        >
        <vx-card>
            <div class="vx-row">
                 <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                     <div class="flex flex-wrap justify-between items-center mb-3">
                                    <!-- ITEMS PER PAGE -->
                                    <div
                                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                    ></div>
                                    <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar2"
                                            @keyup="
                                                listarCtas(1, buscar2)
                                            "
                                            v-bind:placeholder="i18nbuscar2"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="agregarCierre()"
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                    
                    </div>
                    <vs-table stripe :data="contenido_mes">
                                        <template slot="thead">
                                            <vs-th>Mes</vs-th>
                                            <vs-th>Opciones</vs-th>
                                        </template>
                                        <template slot-scope="{ data }">
                                            <vs-tr
                                            :key="datos.id_asientos"
                                            v-for="datos in data"
                                            >
                  
                                                <vs-td
                                                    v-if="datos.fecha"
                                                    >{{
                                                        datos.fecha |fecha |upper
                                                    }}</vs-td
                                                >
                                                <vs-td v-else>-</vs-td>
                                                <vs-td class="whitespace-no-wrap">
                                                    <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="verCierre(datos.id_asientos)"
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="eliminarCierre(datos.id_asientos)"
                                                />
                                                </vs-td>
                                            </vs-tr>
                                        </template>
                                    </vs-table>
                 </div>
             </div>
        </vx-card>
        <vs-popup
                    :class="'peque3'"
                    title="Eliminar"
                    :active.sync="modal_eliminar_mes"
                >
                 <p>Desea eliminar Este reguistro</p>
                 <div class="vx-col w-full">
                   <br>
                 <vs-button color="warning" type="filled" @click="acceptAlertCierre(idrecupera_cierre)">BORRAR</vs-button>
                 </div>
        </vs-popup>
        <vs-popup
                    :class="'peque2'"
                    title="Agregar"
                    :active.sync="modal_agregar_mes"
                >
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label class="vs-input--label">Fecha:</label>

                                <flat-pickr
                                    class="w-full"
                                    :config="configdateTimePicker"
                                    v-model="fecha_mes"
                                    placeholder="Elegir Fecha"
                                />

                        </div>
                        <br>
                        <div class="vx-col w-full">
                            <vs-button color="success" type="filled" @click="guardarCierre()" v-if="!idrecupera_cierre">GUARDAR</vs-button>
                            <vs-button color="success" type="filled" @click="editarCierre()" v-else>GUARDAR</vs-button>
                            <vs-button color="warning" type="filled" @click="vaciarCierre()">BORRAR</vs-button>
                            <vs-button color="danger"  type="filled" @click="cancelarCierre()">CANCELAR</vs-button>
                        </div>
                    </div>
                </div>
        </vs-popup>
        </vs-popup>
    </div> 
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
moment.locale("es");
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        flatPickr
    },
    filters:{
        fecha(data){
            return moment(data).format("MMMM YYYY");
        },
        upper: function (value) {
            return value.toUpperCase();
        }
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        crearrol() {
            var res = 0;
            if(this.usuario.id_rol==1){
                res=1
            }else{
                res = this.$store.state.Roles[68].crear;
            }
            return res;
        },
        editarrol(){
            var res = 0;
            if(this.usuario.id_rol==1){
                res=1
            }else{
                res = this.$store.state.Roles[68].editar;
            }
            return res;
        },
        eliminarrol(){
            var res = 0;
            if(this.usuario.id_rol==1){
                res=1
            }else{
                res = this.$store.state.Roles[68].eliminar;
            }
            return res;
        }
    },
    data() {
        return {
            //buscador
            buscar: "",
            criterio: "secuencial",
            //otros valores
            gridApi: null,
            contenido: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //valores cierre mes
            modal_mes:false,
            contenido_mes:[],
            buscar2:"",
            i18nbuscar2: this.$t("i18nbuscar"),
            modal_agregar_mes:false,
            fecha_mes:"",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            idrecupera_cierre:null,
            modal_eliminar_cta:false,
        };
    },
    methods: {
        listar(page, buscar) {
            var url =
                "/api/ejercicio_contable/listar" ;
            axios.get(url,{params:{
                id_empresa:this.usuario.id_empresa
            }}).then(res => {
                var respuesta = res.data;
                this.contenido = respuesta.recupera;
            });
        },
        editarAsientoContable(id){
            this.$router.push(`/administrar/cierre_contable/ejercicio_contable/${id}/editar`);
        },
        eliminarAsientoContable(id) {
            //metodo eliminar
            var url="/api/ejercicio_contable/eliminar/"+id;
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: "¿Desea Elimnar este registro?",
                
                accept: () => {
                    axios
                        .get(url)
                        .then(respuesta => {
                            this.listar(1,"");
                            this.$vs.notify({
                                color: "success",
                                title: "Asiento Eliminado  ",
                                text: "El asiento selecionado fue eliminado con exito"
                            });
                        }).catch(err=>{
                            this.$vs.notify({
                                color: "danger",
                                title: "Error al Eliminar",
                                text: "Este registro se esta utilizando en otra seccion"
                            });
                        });
                }
            });
        },
        // metodos cierre periodo
        abrirCierrePeriodo(){
            this.modal_mes=true;
            this.listarCierreMes(1,"");
        },
        listarCierreMes(page,buscar){
            var url =
                "/api/cierre_mes/listar" ;
            axios.get(url,{params:{
                id_empresa:this.usuario.id_empresa
            }}).then(res => {
                var respuesta = res.data;
                this.contenido_mes = respuesta.recupera;
            });
        },
        agregarCierre(){
            this.modal_agregar_mes=true
        },
        guardarCierre(){
            if(!this.fecha_mes){
                    this.$vs.notify({
                        text: "La fecha Obligatorio",
                        color: "danger"
                    });
                    return;
            }
            axios.post("/api/cierre_mes/guardar/asiento", {
                    fecha_mes:this.fecha_mes,
                    ucrea:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                        this.modal_agregar_mes=false;
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.listarCierreMes(1,this.buscar2);
                        this.vaciarCierre();
                    
                })
                .catch(err => {});
        },
        editarCierre(){
            if(!this.fecha_mes){
                    this.$vs.notify({
                        text: "La fecha Obligatorio",
                        color: "danger"
                    });
                    return;
            }
            axios.put("/api/actualizar/cierre_mes", {
                    id: this.idrecupera_cierre,
                    fecha_mes:this.fecha_mes,
                    umodifica:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                    
                    this.modal_agregar_mes=false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarCierreMes(1,"");
                    this.vaciarCierre();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarCierre(){
            this.fecha_mes="";
            this.idrecupera_cierre=null;
        },
        cancelarCierre(){
            this.fecha_mes="";
            this.idrecupera_cierre=null;
            this.modal_agregar_mes=false;
        },
        verCierre($id){
            let me = this;
            var url =
                "/api/cierre_mes/abrir/" +
                $id;
                
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    
                    me.idrecupera_cierre=respuesta.recupera[0].id_asientos;
                    me.fecha_mes=respuesta.recupera[0].fecha;
                    console.log(respuesta.recupera[0].id_asientos+":id_plan_ctas");
                    me.modal_agregar_mes=true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarCierre(cd){
            this.modal_eliminar_mes=true;
            this.idrecupera_cierre=cd;
        },
        acceptAlertCierre(parameters) {
            var url="/api/ejercicio_contable/eliminar/" + parameters;
            axios.get(url)
            .then(res =>{
                this.$vs.notify({
                color: "success",
                title: "Reguistro Eliminado  ",
                text: "El reguistro selecionado fue eliminado con exito"
                });
                this.modal_eliminar_mes=false;
                this.idrecupera_cierre=null;
                this.listarCierreMes(1,"");
            }).catch(err => {
                this.$vs.notify({
                color: "danger",
                title: "Error al eliminar",
                text: "Ha ocurrido un error al momento de eliminar reguistro"
                });
            });
            
        },
    },
    mounted(){
        this.listar(1,"");
    }
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