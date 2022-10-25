<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Asignar">
                <!---==============================Button Listar empleados====================================--->

                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6" >
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
                            @selected="handleSelectedtipo"
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
                                </vs-tr>
                                <tr></tr>
                            </template>
                        </vs-table>
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
                    >
                        <template slot="thead">
                            <!--<vs-th class="table-header">Nº</vs-th>-->
                            <vs-th class="table-header">Listado Personal</vs-th>
                            <vs-th class="table-header">Ingresos-Egresos</vs-th>
                            <vs-th class="table-header">Valor</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index">
                                <!--<vs-td
                                    :data="tr.id_empleado"
                                    :value="idemple"
                                    >{{ tr.id_empleado }}</vs-td
                                >-->
                                <vs-td :data="tr.primer_nombre"
                                    >{{ tr.primer_nombre }}
                                    {{ tr.apellido_paterno }}</vs-td
                                >
                                <vs-td :data="tr.tipo3">
                                    <vs-input
                                        class="w-full"
                                        v-model="tipo3"
                                        :value="idtipoie"
                                         disabled
                                    />
                                </vs-td>
                                <vs-td :data="tr.valor">
                                    <vs-input
                                        class="w-full"
                                        style="text-align:right;!important"
                                        v-model="tr.valor"
                                        :value="valor"
                                    />
                                    <div v-show="error" v-if="!tr.valor">
                                        <div v-for="err in tr.errorvalor" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
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
        }
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
            this.activePromptipo = false;
          /*  this.ingresosin = `${tr.decripcion}`;
            this.tipo3 = `${tr.decripcion}`;*/
            this.ingreso_egreso =tr.decripcion;
            this.idtipoie=`${tr.id_ineg}`;
            this.tipo3= tr.decripcion
        },
        handleall(tr) {
            console.log("res", tr);
        },
        handleSelected4(tr) {
            var url = "/api/asignaringresos/listarempleado/" + tr.id_departamento;
            axios
                .get(url)
                .then(res => {
                    this.contenidopr = res.data;
                    
                })
                .catch(function(error) {
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
                    fecha_asignar:this.fecha_asignar
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
.centerx li {
    padding: 2px;
    margin-left: 2px;
    justify-content: center;
    text-align: center;
}
</style>
