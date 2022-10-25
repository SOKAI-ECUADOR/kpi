<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Asignar">
                <!---==============================Button Listar empleados====================================--->

                <div class="vx-row">
                    <!--==================================fin=========================================-->
                    <!---====================Button Listar Ingresos egreso===========================--->
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Ingreso Egresos</label>
                        <vx-input-group class>
                            <vs-input
                                class="w-full"
                                v-model="ingreso_egreso"
                                :value="idtipoie"
                                disabled
                            />
                            
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="activePromptipo = true"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                        <div v-show="error" v-if="!idtipoie">
                            <div v-for="err in erroringreso" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="active_departamento_2">
                        <label class="vs-input--label">Departamento</label>
                        <vx-input-group class>
                            <vs-input
                                class="w-full"
                                v-model="departamento"
                                :value="iddepart"
                                disabled
                            />
                            
                            <template slot="append" v-if="!$route.params.id">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="activePrompt4 = true"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                        <div v-show="error" v-if="!iddepart">
                            <div v-for="err in errordepartamento" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>

                    
                    
                                    
                    <!---==============================Button Listar Ingresos egreso====================================--->
                    <!--   <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Departamento</label>
                        <vx-input-group class>
                            <vs-input
                                class="w-full"
                                :value="iddepart"
                                disabled
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">

                                    <vs-button color="primary"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
          </div>-->
          {{asignar_fecha}}
                <div class="vx-col sm:w-1/4 w-full mb-6">
                    <label class="vs-input--label">Fecha</label>
                    <flat-pickr :config="configdateTimePicker" v-model="fecha_asignar" />
                    <!--<vs-select autocomplete class="selectExample w-full mt-3" label="Fecha" v-model="fecha_asignar">
                        <vs-select-item
                        
                        :key="index"
                        :value="item.value"
                        :text="item.text"
                        v-for="(item,index) in forma_pago_array"
                        />
                    </vs-select>-->
                    <div v-show="error" v-if="!fecha_asignar">
                        <div v-for="err in errorfecha" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
            </div>

                <!--==================DEPARTAMENTO POPUP======================-->
                
                <!--================FIN DEPARTAMENTO POPUP======================-->
                <!--=============LISTAR TIPO INGRESO EGRESO POPUP================-->
                <vs-popup
                    title="Tipo Ingreso Egresos"
                    class="depa"
                    :active.sync="activePromptipo"
                >
                    <div class="con-exemple-prompt">
                        <vs-table
                            stripe
                            
                            :data="contenidoinregos"
                        >
                            <template slot="thead">
                                <vs-th>Codigo</vs-th>
                                <vs-th>Nombre Ingresos-egresos</vs-th>
                                <vs-th>Tipo</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :data="tr"
                                    :key="indextr"
                                    v-for="(tr, indextr) in data"
                                >
                                    <vs-td :data="data[indextr].id_ineg">
                                        {{ data[indextr].id_ineg }}
                                    </vs-td>
                                    <vs-td :data="data[indextr].decripcion">
                                        {{ data[indextr].decripcion }}
                                    </vs-td>
                                    <vs-td :data="data[indextr].tipo">
                                        {{ data[indextr].tipo }}
                                    </vs-td>
                                    <vs-td>
                                        <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="data[indextr].contador" @click="handleSelectedtipo(data[indextr])" vs-value="Si" style="margin-top: 4px;">
                                            <span slot="off">No</span>
                                        </vs-switch>
                                    </vs-td>
                                </vs-tr>
                                <tr></tr>
                            </template>
                        </vs-table>
                        <vs-button color="success" type="filled"  @click="activePromptipo=false">GUARDAR</vs-button>
                    </div>
                </vs-popup>
                <!--==============FIN POPUP TIPO INGRESO EGRESO==================-->
                <!--     <vs-divider border-style="solid" color="dark">
                    <vs-button
                        v-if="usuario.id_rol == 1"
                        color="primary"
                        style="margin-left: 9px;padding: 8px 20px;"
                        type="border"
                        @click="agregar()"
                        >agregar</vs-button
                    >
        </vs-divider>-->
                <!--=========================Table================================-->
                <!--===============================DEPARTAMENTO POPUP============================================-->
                <vs-popup
                    title="Departamento"
                    class="depa"
                    :active.sync="activePrompt4"
                >
                    <div class="con-exemple-prompt">
                        <vs-table
                            stripe
                            :data="contenidodepartamento"
                            @selected="handleSelected4"
                        >
                            <template slot="thead">
                                <vs-th>Código</vs-th>
                                <vs-th>Nombre Departamento</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :data="tr"
                                    :key="indextr"
                                    v-for="(tr, indextr) in data"
                                >
                                    <vs-td
                                        :data="data[indextr].id_departamento"
                                        >{{
                                            data[indextr].id_departamento
                                        }}</vs-td
                                    >
                                    <vs-td :data="data[indextr].dep_nombre">{{
                                        data[indextr].dep_nombre
                                    }}</vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </vs-popup>
                <!--======================================FIN=====================================================-->
                <!--====================POPUP CHECK===========================--->
                <vs-popup
                    title="Tipo Ingreso Egresos"
                    class="depa"
                    :active.sync="activetipo"
                >
                    <div class="con-exemple-prompt">
                        <vs-table stripe :data="contenidoinregos">
                            <template slot="thead">
                                <vs-th>Codigo</vs-th>
                                <vs-th>Nombre Ingresos-egresos</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    v-for="(tipos, index) in data"
                                    :key="index"
                                >
                                    <vs-td>
                                        <vs-checkbox
                                            v-model="form.tipo"
                                            :vs-value="tipos"
                                        ></vs-checkbox>
                                    </vs-td>
                                    <vs-td>{{ tipos.decripcion }}</vs-td>
                                    <vs-td>{{ tipos.tipo }}</vs-td>
                                </vs-tr>
                                <!--{{ form.tipo }}-->
                            </template>
                        </vs-table>
                    </div>
                </vs-popup>
                <!--------=================table 3=========================---------->
                <!---->
                <div
                    class="vx-row p-base"
                    v-for="(tr, index) in valorin"
                    :key="index"
                >
                    <vs-table stripe :data="contenidoempleado">
                        <template slot="thead">
                            <vs-th>Codigo</vs-th>
                            <vs-th>Nombre Empleado</vs-th>
                            <vs-th>Ingreso egreso</vs-th>
                            <vs-th>Valor</vs-th>
                        </template>

                        <template slot-scope="{ data }">
                            <vs-tr
                                :data="tr"
                                :key="indextr"
                                v-for="(tr, indextr) in data"
                            >
                                <vs-td :data="data[indextr].id_empleado">
                                    {{ data[indextr].id_empleado }}
                                </vs-td>
                                <vs-td :data="data[indextr].primer_nombre">
                                    {{ data[indextr].primer_nombre }}
                                </vs-td>
                                <vs-td>
                                    <vs-input
                                        class="w-full"
                                        v-model="tr.decripcion"
                                        disabled
                                    />
                                </vs-td>
                                <vs-td>
                                    <vs-input
                                        class="w-full"
                                        style="text-align:right;!important"
                                        v-model="tr.valor"
                                    />
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
                <!---->
                <vs-divider position="left">
                    <h3>Empleados</h3>
                </vs-divider>
                <div class="p-base">
                    <vs-table
                        hoverFlat
                        :data="contenidopr"
                        style="font-size: 13px;"
                        id="asignar"
                    >
                        <template slot="thead" id="head">
                            <!--<vs-th class="table-header">Nº</vs-th>-->
                            <vs-th class="table-header">Listado Personal</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>0 && array_ineg[0].descripcion">{{array_ineg[0].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>1 && array_ineg[1].descripcion">{{array_ineg[1].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>2 && array_ineg[2].descripcion">{{array_ineg[2].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>3 && array_ineg[3].descripcion">{{array_ineg[3].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>4 && array_ineg[4].descripcion">{{array_ineg[4].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>5 && array_ineg[5].descripcion">{{array_ineg[5].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>6 && array_ineg[6].descripcion">{{array_ineg[6].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>7 && array_ineg[7].descripcion">{{array_ineg[7].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>8 && array_ineg[8].descripcion">{{array_ineg[8].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>9 && array_ineg[9].descripcion">{{array_ineg[9].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>10 && array_ineg[10].descripcion">{{array_ineg[10].descripcion}}</vs-th>
                            <vs-th class="table-header" v-if="active_departamento && array_ineg.length>11 && array_ineg[11].descripcion">{{array_ineg[11].descripcion}}</vs-th>
                            <!-- <vs-th class="table-header">Ingresos-Egresos</vs-th>
                            <vs-th class="table-header">Valor</vs-th> -->
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" id="body_table">
                                <!--<vs-td
                                    :data="tr.id_empleado"
                                    :value="idemple"
                                    >{{ tr.id_empleado }}</vs-td
                                >-->
                                <vs-td :data="tr.primer_nombre"
                                    >{{ tr.primer_nombre }}
                                    {{ tr.apellido_paterno }}</vs-td
                                >
                                
                                <vs-td v-if="tr.valor_ineg_0">
                                    
                                        <vs-input class="w-full valores" v-model="tr.valor_ineg_0"/>
                                        
                                    
                                
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_1">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_1"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_2">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_2"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_3">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_3"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_4">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_4"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_5">
                                    
                                        <vs-input class="w-full valores" v-model="tr.valor_ineg_5"/>
                                        
                                    
                                
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_6">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_6"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_7">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_7"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_8">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_8"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_9">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_9"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_10">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_10"
                                    />
                                </vs-td>
                                <vs-td v-if="tr.valor_ineg_11">
                                    <vs-input
                                            class="w-full valores"
                                            v-model="tr.valor_ineg_11"
                                    />
                                </vs-td>
                                
                            </vs-tr>
                            <vs-tr style="border-top: 1px solid #ddd;font-size: 15px;">
                
                                <th><h5>Total</h5></th>

                                <th v-if="active_departamento && array_ineg.length>0 && array_ineg[0].descripcion" style="text-align:right;"><h5>{{totalvalorcolum0 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>1 && array_ineg[1].descripcion" style="text-align:right;"><h5>{{totalvalorcolum1 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>2 && array_ineg[2].descripcion" style="text-align:right;"><h5>{{totalvalorcolum2 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>3 && array_ineg[3].descripcion" style="text-align:right;"><h5>{{totalvalorcolum3 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>4 && array_ineg[4].descripcion" style="text-align:right;"><h5>{{totalvalorcolum4 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>5 && array_ineg[5].descripcion" style="text-align:right;"><h5>{{totalvalorcolum5 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>6 && array_ineg[6].descripcion" style="text-align:right;"><h5>{{totalvalorcolum6 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>7 && array_ineg[7].descripcion" style="text-align:right;"><h5>{{totalvalorcolum7 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>8 && array_ineg[8].descripcion" style="text-align:right;"><h5>{{totalvalorcolum8 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>9 && array_ineg[9].descripcion" style="text-align:right;"><h5>{{totalvalorcolum9 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>10 && array_ineg[10].descripcion" style="text-align:right;"><h5>{{totalvalorcolum10 | currency}}</h5></th>
                                <th v-if="active_departamento && array_ineg.length>11 && array_ineg[11].descripcion" style="text-align:right;"><h5>{{totalvalorcolum11 | currency}}</h5></th>
                            </vs-tr>
                        </template>
                    </vs-table>
                    

                    <!--<div class="vx-col w-full" hidden>
                        <vs-button
                            color="success"
                            type="filled"
                            
                            v-if="$route.params.id"
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="success"
                            type="filled"
                            
                            v-else
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            to="/nomina/rol-pagos"
                            >CANCELAR</vs-button
                        >
                    </div>-->

                    <!--dividir-->
                </div>
                
                <div class="vx-col w-full">
                    <vs-button
                        color="success"
                        type="filled"
                        @click="editar()"
                        v-if="$route.params.id"
                        >GUARDAR</vs-button
                    >
                    <vs-button
                        color="success"
                        type="filled"
                        @click="guardar()"
                        v-else
                        >GUARDAR</vs-button
                    >
                    <vs-button color="warning" type="filled" @click="borrar()"
                        >BORRAR</vs-button
                    >
                    <vs-button
                        color="danger"
                        type="filled"
                        to="/nomina/asignar-ingreso"
                        >CANCELAR</vs-button
                    >
                </div>
            </vx-card>
            
        </div>
    </div>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import $ from 'jquery';
import { log } from "util";
const axios = require("axios");
export default {
    components: {
        flatPickr
    },
    data() {
        return {
            /** borrar */

            activePrompt4: false,
            contenidodepartamento: [],
            /** */
            form: {
                tipo: []
            },
            active_departamento:false,
            active_departamento_2:false,
            contador_ineg:-1,
            array_ineg:[],
            array_ineg_2:[],
            valorin: [],
            tipo3: "",
            ingresosin: "",
            valor: "",
            ingreso_egreso: "",
            nombre2: "",
            tipoie: "",
            activetipo: false,
            activectn2: false,
            activePrompt3: false,
            contenidopr: [],
            cuentaarray: [],
            cuentaarray3: [],
            cuentaarray4: [],
            i18nbuscar: this.$t("i18nbuscar"),
            buscar: "",
            criterio: "codcta",
            descripcion: "",
            ctacontable: "",
            idtipoie: "",
            iddepart: "",
            idContable: "",
            idContable2: "",
            idemple: "",
            //ctacontable2: "",
            //
            valorproveedores: [],
            departamento: "",
            activePromptipo: false,
            activePrompt5: false,
            //listar depart
            contenidoempleado: [],
            contenidoinregos: [],
            valoresingresos: [],
            contenido: [],
            contenidocamposadicionales: [],
            nombrec: "",
            nombre: "",
            contenido1: [],
            fecha_asignar:"",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            forma_pago_array: [
              { text: "Seleccione", value: 0 },
              { text: "Enero", value: "Enero" },
              { text: "Febrero", value: "Febrero" },
              { text: "Marzo", value: "Marzo" },
              { text: "Abril", value: "Abril" },
              { text: "Mayo", value: "Mayo" },
              { text: "Junio", value: "Junio" },
              { text: "Julio", value: "Julio" },
              { text: "Agosto", value: "Agosto" },
              { text: "Septiembre", value: "Septiembre" },
              { text: "Octubre", value: "Octubre" },
              { text: "Noviembre", value: "Noviembre" },
              { text: "Diciembre", value: "Diciembre" },
            ],
            //ERRORES
            error: 0,
            errorfecha:[],
            errordepartamento: [],
            erroringreso: [],
            errorsecuencial_factura: [],
            errorsecuencial_nota_credito: [],
            errorsecuencial_nota_debito: [],
            errorsecuencial_guia: [],
            errorsecuencial_retencion: [],
            errorestablecimiento: [],
            errorsecuencial_l: [],
            asignar: [],
            //arrays
            tipo: [
                //{ text: "Seleccioné", value: 0 },
                { text: "Ingreso", value: 1 },
                { text: "Egreso", value: 2 }
            ]
        };
    },
    
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        asignar_fecha(){
            if(this.fecha_asignar){
                this.active_departamento_2=true;
            }
        },
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[18].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[18].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[18].eliminar;
            }
            return res;
        },
        totalvalorcolum0(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_0){
                        total+=parseFloat(el.valor_ineg_0);
                    }
                });
            }
            return total;
        },
        totalvalorcolum1(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_1){
                        total+=parseFloat(el.valor_ineg_1);
                    }
                });
            }
            return total;
        },
        totalvalorcolum2(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_2){
                        total+=parseFloat(el.valor_ineg_2);
                    }
                });
            }
            return total;
        },
        totalvalorcolum3(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_3){
                        total+=parseFloat(el.valor_ineg_3);
                    }
                });
            }
            return total;
        },
        totalvalorcolum4(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_4){
                        total+=parseFloat(el.valor_ineg_4);
                    }
                });
            }
            return total;
        },
        totalvalorcolum5(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_5){
                        total+=parseFloat(el.valor_ineg_5);
                    }
                });
            }
            return total;
        },
        totalvalorcolum6(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_6){
                        total+=parseFloat(el.valor_ineg_6);
                    }
                });
            }
            return total;
        },
        totalvalorcolum7(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_7){
                        total+=parseFloat(el.valor_ineg_7);
                    }
                });
            }
            return total;
        },  
        totalvalorcolum8(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_8){
                        total+=parseFloat(el.valor_ineg_8);
                    }
                });
            }
            return total;
        },
        totalvalorcolum9(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_9){
                        total+=parseFloat(el.valor_ineg_9);
                    }
                });
            }
            return total;
        },
        totalvalorcolum10(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_10){
                        total+=parseFloat(el.valor_ineg_10);
                    }
                });
            }
            return total;
        },
        totalvalorcolum11(){
            var total=0
            if(this.contenidopr.length>0){
                this.contenidopr.forEach(el=>{
                    if(el.valor_ineg_11){
                        total+=parseFloat(el.valor_ineg_11);
                    }
                });
            }
            return total;
        },
    },
    methods: {
        listarDepartamento(page1, buscar1) {
            var url = "/api/departamento/listar/" + this.usuario.id_empresa;
            axios;
            axios
                .get(url)
                .then(res => {
                    this.contenidodepartamento = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        abrirdepart() {
            this.activePrompt4 = true;
            this.tipomodal = 2;
            this.listarDepartamento(1, this.buscarp, this.cantidadpp);
        },
        handvalor1(tr) {
            if (this.valorin.length < 30) {
                this.valorin.push({});
            }
        },
        agregar() {
            if (this.valorproveedores.length < 30) {
                this.valorproveedores.push({});
            }
        },
        handleSelectedtipo(tr) {
            

            //console.log("RECUÈRA",tr.decripcion);
            //this.activePromptipo = false;
          /*  this.ingresosin = `${tr.decripcion}`;
            this.tipo3 = `${tr.decripcion}`;*/
            this.ingreso_egreso =tr.decripcion;
            this.idtipoie=`${tr.id_ineg}`;
            this.tipo3= tr.decripcion;
            //$('#asignar thead tr').append('<th colspan="1" rowspan="1" class="col-0 col table-header"><div class="vs-table-text">'+tr.decripcion+'</div></th>');
            //$('#body_table').append('<vs-td><vs-input class="w-full" v-model="tr.ineg.valor_ineg" /></vs-td>');
            
            
                var a=0;
                var existeElemento=this.array_ineg.find(el=>el.id_ineg==tr.id_ineg);
                console.log(existeElemento+" typeof="+typeof(existeElemento));
                if(typeof(existeElemento)=='undefined'){
                    //for(a=0;a<this.contenidopr.length;a++){
                        this.array_ineg.push({
                            id_ineg:tr.id_ineg,
                            descripcion:tr.decripcion
                        });
                        this.array_ineg_2.push(tr.id_ineg);
                        // this.contador_ineg++;
                        // this.contenidopr.forEach(el => {
                            
                        //     el["id_ineg_"+this.contador_ineg]=tr.id_ineg,
                        //     el["valor_ineg_"+this.contador_ineg]="0.00"
                        //     // el["ineg"].push({
                                
                        //     //     valor_ineg:"",
                        //     //     id_ineg:tr.id_ineg
                        //     // })
                        // });
                   //console.log(JSON.stringify(this.contenidopr[0].ineg));
                }else{
                        this.$vs.notify({
                            text: "Ya se agrego este Ingreso-Egreso",
                            color: "danger"
                        });

                }
                console.log(JSON.stringify(this.array_ineg));
                
            console.log(this.array_ineg_2);
            
            
        },
        handleall(tr) {
            console.log("res", tr);
        },
        handleSelected4(tr) {
            var url = "/api/asignaringresos/listarempleado";
            axios
                .get(url,{
                    params:{
                        id:tr.id_departamento,
                        ingresos:this.array_ineg_2,
                        fecha:this.fecha_asignar
                    }
                    
                })
                .then(res => {

                    this.contenidopr = res.data;
                    this.active_departamento=true;
                })
                .catch(function(error) {
                    this.active_departamento=false;
                    console.log(error);
                });
            this.departamento = `${tr.dep_nombre}`;
            this.iddepart = `${tr.id_departamento}`;
            this.activePrompt4 = false;
        },

        handleSelected5(tr) {
            //console.log("recupera", tr);
        },
        listarempleado(page, buscar) {
            var url =
                "/api/nomina/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios;
            axios
                .get(url)
                .then(res => {
                    this.contenidoempleado = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },

        /**
         * Listar Ingresos egresos
         * @var listaringresos
         */
        listaringresos(page1, buscar1) {
            var url = "/api/ingresoegreso/listarin/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.contenidoinregos = res.data;
            });
        },
        guardar() {
           if (this.validar()) {
                return;
            }
            axios
                .post("/api/asignaringresos/agregar", {
                    idtipoie:this.idtipoie,
                    id_empresa: this.usuario.id_empresa,
                    contenidopr: this.contenidopr,
                    id_departamento:this.iddepart,
                    fecha_asignar:this.fecha_asignar,
                    usuario:this.usuario.id
                })
                .then(res => {
                    if(res.data ==="existe"){
                        this.$vs.notify({
                            title: "Error al Guardar",
                            text: "Este ingreso ya existe en el Departamento",
                            color: "danger"
                        });
                    }else{
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/nomina/asignar-ingreso");
                    }
                    
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Guardar",
                        text: "Verifique los campos",
                        color: "danger"
                    });
                });
        },
        editar() {
            if (this.validar()) {
                return;
            }
            axios
                .put("/api/asignaringresos/editar/", {
                    idtipoie: this.idtipoie,
                    contenidopr: this.contenidopr,
                    fecha_asignar:this.fecha_asignar,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Actualizado",
                        text: "Registro Actualizado exitosamente",
                        color: "success"
                    });
                    this.$router.push("/nomina/asignar-ingreso");
                })
                .catch(err => {
                    //console.log(err);
                    this.$vs.notify({
                        title: "Error al Actualizar",
                        text: "Revise bien sus campos antes de guardar",
                        color: "danger"
                    });
                });
        },
        solonumeros: function($event) {
            //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
            var num = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
        validar(){
            this.error= 0;
            this.errordepartamento= [];
            this.erroringreso= [];
            this.errorfecha=[];
            if(!this.iddepart){
                this.errordepartamento.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.idtipoie){
                this.erroringreso.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.fecha_asignar){
                this.errorfecha.push("Campo Obligatorio");
                this.error=1;
            }
            for (var i = 0; i < this.contenidopr.length; i++) {
                this.contenidopr[i].errorvalor = [];
                if(!this.contenidopr[i].valor){
                    this.contenidopr[i].errorvalor.push("Campo Obligatorio");
                    this.error = 1;
                }
            }
            return this.error;
        },
        listarAsignarIngreso(){
            if(this.$route.params.id){
                axios.get("/api/asignarinregos/ver/"+this.$route.params.id)
                    .then(res =>{
                        this.contenidopr=res.data;
                        this.iddepart=res.data[0].id_departamento;
                        this.departamento=res.data[0].dep_nombre;
                        this.ingreso_egreso=res.data[0].tipo3;
                        this.idtipoie=res.data[0].id_ineg;
                        this.tipo3=res.data[0].tipo3;
                        this.fecha_asignar=res.data[0].fecha_asignar;
                        //console.log("datos"+res.data[0].dep_nombre);
                    })
            }
        }
    },
    mounted() {
        this.listarempleado(1, this.buscar);
        this.listaringresos(1, this.buscar1);
        this.listarDepartamento(1, this.buscar1);
        this.listarAsignarIngreso();
    }
};
</script>
<style lang="scss">
.vs-popup {
    width: 900px !important;
}
.peque .vs-popup {
    width: 1060px !important;
}
.depa .vs-popup {
    width: 650px !important;
}
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
}
.centerx li {
    padding: 2px;
    margin-left: 2px;
    justify-content: center;
    text-align: center;
}
</style>
