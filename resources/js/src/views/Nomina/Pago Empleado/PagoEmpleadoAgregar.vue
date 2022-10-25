<template>
    <div id="invoice-page">
        <vx-card>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label class="vs-input--label">Departamento</label>
                    <vx-input-group class>
                    <vs-input class="w-full" v-model="departamento" :value="iddepart" disabled />

                    <template slot="append">
                        <div class="append-text btn-addon">
                        <!-- -->
                        <vs-button color="primary" @click="abrirdepart()">Buscar</vs-button>
                        </div>
                    </template>
                    
                    <!-- <div v-show="error" v-if="!iddepart">
                        <div v-for="err in errordepartamento" :key="err" v-text="err" class="text-danger"></div>
                    </div> -->
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label for="" class="vs-input--label">Fecha:</label>
                    <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="fecha"
                            placeholder="Elegir Fecha"
                        />
                    {{fecha_rol_pago}}
                </div>
            </div>
            {{pagos}}
            <vs-divider position="left">

                <h3>Pago Empleado</h3>
            </vs-divider>
            <div class="p-base">
                <vs-table  hoverFlat :data="pagos" style="font-size: 12px;width: 100%;" >
                    <template slot="thead">
                        <vs-th class="table-header" >NOMBRE</vs-th>
                        <vs-th class="table-header" >PROYECTO</vs-th>
                        <vs-th class="table-header" >VALOR RECIBIR</vs-th>
                        <vs-th class="table-header" >VALOR TOTAL A PAGAR</vs-th>
                        <vs-th class="table-header" >SALDO</vs-th>
                    </template>
                    <template slot-scope="{data}">
                        <vs-tr v-for="(tr, index) in data" :key="index">
                            <vs-td :data="tr.primer_nombre" style="width:150px!important;">{{ tr.primer_nombre }}</vs-td>
                            <vs-td :data="tr.descripcion" style="width:130px!important;">{{ tr.descripcion }}</vs-td>
                            <vs-td :data="tr.valor_recibir">{{tr.valor_recibir | currency}}</vs-td>
                            <vs-td :data="tr.valor_pagar">
                                {{tr.valor_pagar}}
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                    color="primary"
                                    @click="agregarcampoConciliacion(index,tr.id_empleado)"
                                    >
                                    +
                                </vs-button>
                            </vs-td>
                            <vs-td></vs-td>
                        </vs-tr>
                            <vs-popup title="Conciliacion" :active.sync="modal_conciliacion">
                                <div class="vx-col sm:w-full w-full mb-6 relative">
                                    <div class="vx-row p-base" v-for="(tr,index) in pagos" :key="index">
                                        <!-- <div class="vx-col sm:w-1/3 w-full mb-6">
                                            <vs-select
                                                
                                                class="selectExample w-full"    
                                                label="Forma Pago"
                                                vs-multiple
                                                autocomplete
                                                v-model="pagos[indextipoarreglo].id_forma_pago"
                                                >
                                                <vs-select-item
                                                    v-for="data in formas_pagos_array"
                                                    :key="data.id_forma_pagos"
                                                    :text="data.descripcion"
                                                    :value="data"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6" v-if="forma_pagos[indextipoarreglo].id_forma_pago && forma_pagos[indextipoarreglo].id_forma_pago.descripcionfps!=='TARJETA PREPAGO' && forma_pagos[indextipoarreglo].id_forma_pago.descripcionfps!=='COMPENSACIÓN DE DEUDAS' && forma_pagos[indextipoarreglo].id_forma_pago.descripcionfps!=='ENDOSO DE TÍTULOS' && forma_pagos[indextipoarreglo].id_forma_pago.descripcionfps!=='SIN UTILIZACION DEL SISTEMA FINANCIERO' ">
                                            <vs-input
                                                label="No Documento"
                                                class="w-full"
                                                v-model="forma_pagos[indextipoarreglo].no_documento"
                                                maxlength="30"
                                               
                                            />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6" >
                                            <vs-input
                                                label="Valor a Pagar"
                                                class="w-full"
                                                v-model="forma_pagos[indextipoarreglo].valor_pago"
                                                maxlength="30"
                                                
                                            />
                                        </div> -->
                                        <feather-icon
                                                
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    
                                                />
                                                <!-- @click="borrarprov(index)" -->
                                    </div>
                                </div>
                                
                            </vs-popup> 
                    </template>
                </vs-table>
            </div>
        </vx-card>
        <vs-popup title="Departamento" class="depa" :active.sync="activePrompt3">
            <div class="con-exemple-prompt">
              <vs-table stripe :data="departamentos" @selected="handleSelected4">
                <template slot="thead">
                  <vs-th>Código</vs-th>
                  <vs-th>Nombre Departamento</vs-th>
                </template>
                <template slot-scope="{data}">
                  <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                    <vs-td :data="data[indextr].id_departamento">{{ data[indextr].id_departamento }}</vs-td>
                    <vs-td :data="data[indextr].dep_nombre">{{ data[indextr].dep_nombre}}</vs-td>
                  </vs-tr>
                </template>
              </vs-table>
            </div>
        </vs-popup>
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
            fecha:"",
            departamento:"",
            iddepart:"",
            departamentos:[],
            id_proyecto:"",
            proyectos:[],
            activePrompt3:false,
            tipomodal:"",
            pagos:[],
            forma_pagos:[],
            formas_pagos_array:[],
            //pagos
            indextipoarreglo:0,
            modal_conciliacion:false,
        };
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        fecha_rol_pago(){
            if(this.fecha && this.departamento && !this.$route.params.id){
                this.handleEmpleados();
            }
        },
    },
    methods:{
        abrirdepart() {
            this.activePrompt3 = true;
            this.tipomodal = 2;
            this.listarDepartamento(1, this.buscarp, this.cantidadpp);
        },
        listarDepartamento(page1, buscar1) {
            var url = "/api/departamento/listar/"+this.usuario.id_empresa;
            axios;
            axios
                .get(url)
                .then(res => {
                    this.departamentos = res.data.recupera;
                })
                .catch(function(error) {
                console.log(error);
                });
        },
        handleSelected4(tr) {
      
            this.departamento=`${tr.dep_nombre}`;
            this.iddepart = `${tr.id_departamento}`;
            this.activePrompt3 = false;
            
        },
        handleEmpleados(){
            var url="/api/rolpago/empleados";
            axios.get(url,{params:{
                id_departamento:this.iddepart,
                fecha:this.fecha
            }}).then(resp=>{
                if(resp.data=="vacio"){
                    this.$vs.notify({
                        title: "No se a encontrado Pagos",
                        text: "Revise bien los datos y que el Rol de Pagos este contabilizado",
                        color: "warning"
                    });
                }else{
                    this.pagos=resp.data;
                }
            }).catch(err=>{
                    console.log(err);
            });
        },
        agregarcampoConciliacion(index,empleado){
            this.modal_conciliacion=true;
            this.indextipoarreglo = index;
            //console.log(this.pagos[index].valor_pagar);
             this.pagos.push({forma_pagos:{
                index_fp:index,
                id_forma_pago:null,
                id_pago_sri:"",
                no_documento:null,
                valor_pago:null,
                id_empleado:empleado,
                id_proyecto:null
            }});
        },
        listarfomaspagos(){
           var url =
                "/api/administrar/forma_pagos/listar/asientos"
            axios.get(url,{
                params:{
                    id_empresa:this.usuario.id_empresa
                }
            }).then(res => {
                var respuesta = res.data;
                this.formas_pagos_array = res.data;
                console.log(res.data);
            }); 
        },
    },
    mounted(){
        this.listarfomaspagos();
    }
}
</script>