<template>
    <div id="invoice-page">
        <vx-card>
            <div class="vx-row">
                
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <!-- <vs-input
                            class="w-full"
                            label="Número:"
                            :disabled="true"
                            v-model="codigoComprobante"
                    /> -->
                    <vs-input
                            class="w-full"
                            label="Número:"
                            :disabled="true"
                            v-model="codigo"
                        />
                </div>
                <div class="vx-col sm:w-3/12 w-full mb-6">
                     <vs-input
                                class="w-full"
                                label="Comprobante:"
                                :disabled="true"
                                v-model="comprobante"
                                :value="idComprobante"
                        /> 
                        <!-- <vs-select
                            placeholder="Seleccione"
                            class="selectExample w-full"
                            label="Comprobante:"
                            vs-multiple
                            autocomplete
                            disabled
                            v-model="comprobante"
                            @change="seleccionDeComprobante"
                            
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in comprobante_array"
                            />
                        </vs-select> -->
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <label class="vs-input--label">Fecha:</label>

                    <flat-pickr
                        class="w-full"
                        :config="configdateTimePicker"
                        v-model="fecha"
                        :disabled="$route.params.id"
                        placeholder="Elegir Fecha"
                    />
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-6">
                    <vs-select placeholder="Seleccione" class="selectExample w-full" label="Periodo" vs-multiple autocomplete v-model="periodo" :disabled="$route.params.id">
                        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item,index) in periodos"/>
                    </vs-select>
                </div>
            </div>
            {{asignar_cierre}}
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            disabled
                            class="selectExample w-full"
                            label="Razón Social:"
                            vs-multiple
                            autocomplete
                            v-model="razon_social"
                            
                        />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            disabled
                            class="selectExample w-full"
                            label="Tipo Identificacion:"
                            vs-multiple
                            autocomplete
                            v-model="tipo_identificacion"
                            
                        />
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-full w-full mb-6">
                        <vs-input
                            disabled
                            class="selectExample w-full"
                            label="Concepto:"
                            vs-multiple
                            autocomplete
                            v-model="concepto"
                            
                        />
                        <!--@keypress="sololetras($event)"-->
                        
                </div>
            </div>
            <h4 style="color: #636363; display:flex; align-items: center;">
                <span>Agregar detalle</span>
            </h4>
            <vs-divider></vs-divider>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/5 w-full mb-6">
                            <label class="vs-input--label">Proyecto</label>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label">Cuenta Contable</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label">Debe</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label">Haber</label>
                        </div>
                        
                    </div>
                </div>
            </div>
            <div
                id="one-row"
                class="vx-row"
                v-for="(add, index) in detalle"
                v-bind:key="index"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/5 w-full mb-2">
                            <vs-select
                                
                                placeholder="--Seleccione--"
                                autocomplete
                                class="selectExample w-full"
                                v-model="detalle[index].id_proyecto"
                            >
                            <!--canton = '';
                                    parroquia = '';-->
                                <vs-select-item
                                    v-for="data in contenidoproyecto"
                                    :key="data.id_proyecto"
                                    :value="data.id_proyecto"
                                    :text="data.descripcion"
                                />
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    v-model="detalle[index].nombre_cuenta"
                                    :disabled="true"
                                />
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                :disabled="true"
                                class="w-full valores"
                                v-model="detalle[index].debe"
                                maxlength="15"
                            />
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                :disabled="true"
                                class="w-full valores"
                                v-model="detalle[index].haber"
                                maxlength="15"
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div
                id="two-row"
                class="vx-row"
                v-for="(add, index) in resultado"
                v-bind:key="index"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/5 w-full mb-2">
                            <vs-select
                                
                                placeholder="--Seleccione--"
                                autocomplete
                                class="selectExample w-full"
                                v-model="resultado[index].id_proyecto"
                            >
                            <!--canton = '';
                                    parroquia = '';-->
                                <vs-select-item
                                    v-for="data in contenidoproyecto"
                                    :key="data.id_proyecto"
                                    :value="data.id_proyecto"
                                    :text="data.descripcion"
                                />
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    v-model="resultado[index].nombre_cuenta"
                                    :disabled="true"
                                />
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe">
                            <vs-input
                                :disabled="true"
                                class="w-full valores"
                                v-model="resultado[index].saldo"
                                maxlength="15"
                            />
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-else>
                            <vs-input
                                :disabled="true"
                                class="w-full valores"
                                v-model="resultado[index].otro"
                                maxlength="15"
                            />
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber">
                            <vs-input
                                :disabled="true"
                                class="w-full valores"
                                v-model="resultado[index].saldo"
                                maxlength="15"
                            />
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-else>
                            <vs-input
                                :disabled="true"
                                class="w-full valores"
                                v-model="resultado[index].otro"
                                maxlength="15"
                            />
                        </div>
                    </div>
                </div>
            </div>
            {{suma_debe}}
            {{suma_haber}}
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/5 w-full mb-6"></div>
                        <div class="vx-col sm:w-1/3 w-full mb-6"></div>
                        <!--TOTAL DEBE-->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                id="total-debe"
                                label="Total"
                                :disabled="true"
                                class="w-full valores"
                                v-model="debe"
                            />
                        </div>
                        <!--TOTAL HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                id="total-haber"
                                class="w-full valores"
                                label="Total"
                                :disabled="true"
                                v-model="haber"
                            />
                        </div>
                    </div>
                </div>
            </div>
            {{Diferencia}}
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/5 w-full mb-6"></div>
                        <div class="vx-col sm:w-1/3 w-full mb-6"></div>
                        <div class="vx-col sm:w-1/6 w-full mb-6" v-if="diferencia_debe!==0">
                        
                            <!-- prettier-ignore -->
                            <vs-input
                                id="diferencia"
                                class="w-full valores"
                                label="Diferencia al debe"
                                :disabled="true"
                                v-model="diferencia_debe"
                            />
                        </div>
                        <div class="vx-col sm:w-1/6 w-full mb-6" v-else>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber!==0">
                            <!-- prettier-ignore -->
                            <vs-input
                                id="diferencia"
                                class="w-full valores"
                                label="Diferencia al haber"
                                :disabled="true"
                                v-model="diferencia_haber"
                            />
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-else>

                        </div>
                    </div>
                </div>
            </div>
             <div class="flex w-full">
                <vs-button
                    color="success"
                    type="filled"
                    @click="guardarAsientoContable()"
                    v-if="detalle.length > 0"
                    >GUARDAR</vs-button
                >
                <vs-button
                    style="margin: 0px 1em;"
                    class="btnx"
                    color="danger"
                    type="filled"
                    to="/administrar/cierre_contable/ejercicio_contable"
                    >CANCELAR</vs-button
                >
                
            </div> 
        </vx-card>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import vSelect from "vue-select";

import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { concat } from "bytebuffer";
import { Script } from "vm";
import $ from "jquery";
const axios = require("axios");

export default {
    components: {
        AgGridVue,
        flatPickr
    },
    data(){
        return{
            
            comprobante:"DIARIOS",
            codigo:"",
            idComprobante:3,
            configdateTimePicker: {
                locale: SpanishLocale
            },
            fecha:"",
            razon_social:"",
            tipo_identificacion:"",
            concepto:"",
            debe:"",
            haber:"",
            detalle:[],
            contenidoproyecto:[],
            numero:"",
            comprobante_array: [{ text: "DIARIOS", value: 3 }],
            diferencia_debe:0,
            diferencia_haber:0,
            periodo:"",
            resultado:[],
            id_proyecto:"",
            periodos: [{ text: "2021", value: "2021" },{ text: "2020", value: "2020" },{ text: "2019", value: "2019" }, { text: "2018", value: "2018" },{ text: "2017", value: "2017" }, { text: "2016", value: "2018" },{ text: "2015", value: "2015" }, { text: "2014", value: "2014" },{ text: "2013", value: "2013" }, { text: "2012", value: "2012" },{ text: "2011", value: "2011" }, { text: "2010", value: "2010" },{ text: "2009", value: "2009" }, { text: "2008", value: "2008" },{ text: "2007", value: "2007" }, { text: "2006", value: "2006" },{ text: "2005", value: "2005" }, { text: "2004", value: "2004" },{ text: "2003", value: "2003" }, { text: "2002", value: "2002" },{ text: "2001", value: "2001" }, { text: "2000", value: "2000" },{ text: "1999", value: "1999" }, { text: "1998", value: "1998" }],
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        suma_debe(){
             var total=0;
            if(this.detalle.length>0){
               
                this.detalle.forEach(el => {
                    if (el.debe > 0) {
                        total += parseFloat(el.debe);
                    }
                });
                
                //this.debe=parseFloat(total).toFixed(2);
                //console.log("debe total:".total);
            }
            if(this.resultado.length>0){
                this.resultado.forEach(el => {
                    if(this.diferencia_debe>0){
                        total+=parseFloat(el.saldo);
                    }
                });
            }
            this.debe=parseFloat(total).toFixed(2);
            //console.log("debe total:0 s");
        },
        suma_haber(){
            var total=0;
            if(this.detalle.length>0){
                
                this.detalle.forEach(el => {
                    if (el.haber > 0) {
                        total += parseFloat(el.haber);
                    }
                });
                
                //console.log("haber total:".total);
            }
            if(this.resultado.length>0){
                this.resultado.forEach(el => {
                    if(this.diferencia_haber>0){
                        total+=parseFloat(el.saldo);
                    }
                });
            }
            this.haber=parseFloat(total).toFixed(2);
            //console.log("haber total:0 s");
        },
        asignar_cierre(){
            if(this.fecha!==""){
                this.listar();
                var anio=this.fecha.substr(0, 4);
                this.concepto="Balance Inicial "+anio;
            }
        },
        Diferencia(){
            if(this.debe>this.haber){
                var num=parseFloat(this.haber-this.debe).toFixed(2);
                if(num<0){
                    this.diferencia_haber=num*-1;
                }else{
                    this.diferencia_haber=num;
                }
                console.log("el mayor debe es:"+this.debe);
            }
            if(this.debe<this.haber){
                var num2=parseFloat(this.debe-this.haber).toFixed(2);
                if(num2<0){
                    this.diferencia_debe=num2*-1;
                }else{
                    this.diferencia_debe=num2;
                }
                console.log("el mayor haber es:"+this.haber);
                
            }
            console.log("el debe es:"+this.debe+" el haber es:"+this.haber+" el total es:"+this.debe-this.haber);
            // if(this.diferencia_debe || this.diferencia_haber){
            //     this.listaCtaResultado();
            // }
        }
        // codigoComprobante() {
        //     let codigo = this.comprobante_array.find(
        //         data => data.value == this.comprobante
        //     );
        //     codigo = codigo.value
        //         ? `${codigo.text[0].toUpperCase()}-${
        //               this.numero
        //           }`
        //         : "";
        //     return codigo;
        // }
    },
    methods:{
        listarproyecto(page2, buscar2) {
            var url =
                "/api/listarproyecto/asientos/" +
                this.usuario.id_empresa +
                "?page=" +
                page2 +
                "&buscar=" +
                buscar2;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoproyecto = respuesta.recupera;
                
            });
        },
        listar(){
            var url="/api/balance_inicial";
            axios.get(url,{
                params:{
                    id_empresa:this.usuario.id_empresa,
                    fecha:this.fecha
                }
            })
            .then(({data})=>{
                this.detalle=data.asientos_detalle;
                this.razon_social=data.nombre_empresa;
                this.numero=data.numero;
                this.codigo="D-"+data.numero;
                this.id_proyecto=data.proyecto_id;
            })
            .catch(err=>{
                console.log("Error al traer cierre:"+err);
            });
        },
        listaCtaResultado(){
            var url="/api/ejercicio_contable/cta_resultado";
            axios.get(url,{
                params:{
                    id_empresa:this.usuario.id_empresa,
                    fecha:this.fecha
                }
            })
            .then(({data})=>{
                this.resultado=data.asiento_resultado;
            })
            .catch(err=>{
                console.log("Error al traer cta:"+err);
            });
        },
        seleccionDeComprobante() {
            let comprobante = this.comprobante_array.find(
                data => data.value === this.comprobante
            );
            this.obtenerUltimoNumeroDeAsientosContables(
                this.comprobante
            );
        },
        obtenerComprobantes() {
            const url = `/api/asientos-contables/manuales/comprobantes/`;
            axios.get(url).then(({ data: listaDeComprobantes }) => {
                for (const comprobantes of listaDeComprobantes) {
                    if(comprobantes.id_asientos_comprobante<4){
                        this.comprobante_array.push({
                            text: comprobantes.tipo.toUpperCase(),
                            value: comprobantes.id_asientos_comprobante
                        });
                    }
                    
                }
            });
        },
        obtenerUltimoNumeroDeAsientosContables(comprobante) {
            if (comprobante !== "Seleccione") {
                var url=`/api/asientos-contables/manuales/ultimo-numero/${comprobante}`;
                axios
                    .get(
                        url,
                        {
                            params:{
                                id_empresa:this.usuario.id_empresa
                                }
                        }
                    )
                    .then(({ data: ultimoNumero }) => {
                        this.numero = "D-"+ultimoNumero;
                    });
            }
        },
        guardarAsientoContable(){
                    var total=0;
                    total=this.debe-this.haber;
                    if(total!==0){
                            this.$vs.notify({
                                text: "Los Asientos no cuadran",
                                color: "danger"
                            });
                            return;
                    }
                    if(this.validar()){
                        return;
                    }
                    var fecha_hoy=new Date();
                    let url = `/api/balance_inicial/guardar/asiento`;
                    axios
                        .post(url, {
                            numero:this.numero,
                            codigo:this.codigo,
                            fecha:this.fecha+" "+fecha_hoy.getHours()+":"+fecha_hoy.getMinutes()+":"+fecha_hoy.getSeconds(),
                            razon_social:this.razon_social,
                            concepto:this.concepto,
                            ucrea:this.usuario.id,
                            id_proyecto:this.id_proyecto,
                            id_empresa:this.usuario.id_empresa,
                            periodo:this.periodo
                        })
                        .then(response => {
                            this.agregarDetalle(response.data);
                            // this.listarproyecto(1, this.buscar2);
                            // this.$vs.notify({
                            //     title: "Asientos Guardados",
                            //     text: "Los Asientos se han guardado correctamente",
                            //     color: "success"
                            // });
                            // this.$router.push("/contabilidad/asientos-contables");
                        })
                        .catch(err => {
                            this.$vs.notify({
                                title: "Error al Guardar",
                                text: "Error al guardar reguistros",
                                color: "danger"
                            });
                        });
                        
        },
        validar(){
            var error=0;
            if(!this.fecha){
                error++;
                this.$vs.notify({
                    text: "El Asiento no tiene fecha",
                    color: "danger"
                });
            }
            if(!this.periodo){
                error++;
                this.$vs.notify({
                    text: "El Asiento no tiene periodo",
                    color: "danger"
                });
            }
            if(this.detalle.length<=0){
                error++;
                console.log("No se encuentra el detalle");
            }else{
                this.detalle.forEach(el=>{
                    if(el.id_proyecto==null || el.id_proyecto==""){
                        error++;
                        this.$vs.notify({
                                text: "El Asiento no tiene proyecto",
                                color: "danger"
                            });
                        console.log("No se encuentra el proyecto  detalle");
                    }
                    if(el.id_plan_cuentas==null || el.id_plan_cuentas==""){
                        error++;
                        this.$vs.notify({
                                text: "El Asiento no tiene cuenta contable",
                                color: "danger"
                            });
                        console.log("No se encuentra el plan_cuentas  detalle");  
                    }
                });
            }
            return error;
        },
        agregarDetalle(id){
                    let url = `/api/balance_inicial/guardar/detalle`;
                    axios
                        .post(url, {
                            id_asientos:id,
                            detalle:this.detalle,
                            resultado:this.resultado,
                            diferencia_debe:this.diferencia_debe,
                            diferencia_haber:this.diferencia_haber,
                            ucrea:this.usuario.id
                        })
                        .then(response => {
                            this.$vs.notify({
                                title: "Asientos Guardados",
                                text: "Los Asientos se han guardado correctamente",
                                color: "success"
                            });
                            this.$router.push("/administrar/cierre_contable/balance_inicial");
                        })
                        .catch(err => {
                            this.$vs.notify({
                                title: "Error al Guardar",
                                text: "Error al guardar reguistros",
                                color: "danger"
                            });
                        });
        },
        
    },
    mounted(){
        this.listarproyecto(1,"");
        this.obtenerComprobantes();
        
    }
}
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.vs-popup {
    width: 600px !important;
}
.peque .vs-popup {
    width: 1060px !important;
}
.depa .vs-popup {
    width: 650px !important;
}
.iconelim {
    float: none;
    position: absolute;
    right: 16px;
    padding: 1px !important;
    margin-top: -4px;
    width: 23px !important;
    height: 23px !important;
    cursor: pointer;
    z-index: 9;
}
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
}
#diferencia_debe.vs-input--input:disabled {
    background-color: rgb(253, 121, 121) !important;
    color: black !important;
    opacity: 0.7;
    cursor: default;
    pointer-events: none;
}
#diferencia_haber.vs-input--input:disabled {
    background-color: rgb(253, 121, 121) !important;
    color: black !important;
    opacity: 0.7;
    cursor: default;
    pointer-events: none;
}
#total-debe.vs-input--input:disabled {
    background-color: white !important;
    color: black !important;
    opacity: 0.7;
    cursor: default;
    pointer-events: none;
}
#total-haber.vs-input--input:disabled {
    background-color: white !important;
    color: black !important;
    opacity: 0.7;
    cursor: default;
    pointer-events: none;
}
</style>