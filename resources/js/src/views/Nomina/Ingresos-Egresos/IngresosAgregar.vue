<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Agregar">
                <div class="vx-col sm:w-1/3 w-full mb-6">
                
                    <label class="vs-input--label" >Departamento</label>
                    <div >
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
                                        @click="activePrompt5 = true"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                    </div>
                    
                </div>
                <!--===============================DEPARTAMENTO POPUP============================================-->
                <vs-popup
                    title="Departamento"
                    class="depa"
                    :active.sync="activePrompt5"
                >
                    <div class="con-exemple-prompt">
                        <vs-table
                            stripe
                            @selected="handleSelected4"
                            :data="contenidodepartamento"
                        >
                            <template slot="thead">
                                <vs-th>Codigo</vs-th>
                                <vs-th>Nombre Departamentos</vs-th>
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
        
                
                
                    <div
                        class="vx-row p-base"
                        v-for="(tr, index) in valorproveedores"
                        :key="index"
                    >
                          <div  class="vx-col sm:w-1/2 w-full mb-2" >
                              <h6>Descripción:</h6>
                              <vs-input class="w-full" v-model="tr.decripcion" />
                              <div v-show="error" v-if="!tr.decripcion">
                                  <span
                                      class="text-danger"
                                      v-for="err in tr.errordes"
                                      :key="err"
                                      v-text="err"
                                  ></span>
                              </div>
                          </div>   
                          <div v-if="!$route.params.id" class="vx-col sm:w-1/3 w-full mb-2">
                              <h6>Tipo:</h6>
                              <vs-select
                                  placeholder="--Seleccione--"
                                  class="selectExample w-full"
                                  vs-multiple
                                  v-model="tr.tipo"
                              >
                                  <vs-select-item
                                      :key="index"
                                      :value="item.text"
                                      :text="item.text"
                                      v-for="(item, index) in tipo"
                                  />
                              </vs-select>
                              <div v-show="error" v-if="!tr.tipo">
                                  <span
                                      class="text-danger"
                                      v-for="err in tr.errortipo"
                                      :key="err"
                                      v-text="err"
                                  ></span>
                              </div>
                          </div>
                          <div v-else class="vx-col sm:w-1/6 w-full mb-2">
                              <h6>Tipo:</h6>
                              <vs-select
                                  placeholder="--Seleccione--"
                                  class="selectExample w-full"
                                  vs-multiple
                                  v-model="tr.tipo"
                              >
                                  <vs-select-item
                                      :key="index"
                                      :value="item.text"
                                      :text="item.text"
                                      v-for="(item, index) in tipo"
                                  />
                              </vs-select>
                              <div v-show="error" v-if="!tr.tipo">
                                  <span
                                      class="text-danger"
                                      v-for="err in tr.errortipo"
                                      :key="err"
                                      v-text="err"
                                  ></span>
                              </div>
                          </div>
                          <div class="vx-col sm:w-1/6 w-full mb-2" v-if="tr.tipo=='Ingreso'">
                                <label class="vs-input--label"
                                    >Iess</label
                                >
                                <vs-checkbox
                                    v-model="tr.iess"
                                    vs-value="1"
                                ></vs-checkbox>
                        
                          </div>
                          <div class="vx-col sm:w-1/2 w-full mb-2">
                              <h6>Debe:</h6>
                              <vx-input-group class>
                                  <vs-input
                                      class="w-full"
                                      v-model="tr.id_plan_cuentas_1"
                                      :value="tr.idContable"
                                      disabled
                                  />
                                  <template slot="append">
                                      <div class="append-text btn-addon">
                                          
                                          <vs-button
                                              color="primary"
                                              @click="activePrompt3 = true,index_plan_cuenta(index)"
                                              >Buscar</vs-button
                                          >
                                      </div>
                                  </template>
                              </vx-input-group>
                             <!-- <div class="row mb-2">
                            <div
                                class="col-xl-6 col-lg-12 justify-content-end clicker"
                                style="position: relative;"
                            >
                                <div
                                    class="input-group mb-3"
                                    style="width: 100%;"
                                >
                                    <vs-input
                                        label="Cuenta Contable 1"
                                        type="text"
                                        class="w-full"
                                        placeholder="Buscar Cuenta Contable"
                                        v-model="tr.id_plan_cuentas_1"
                                        @keyup="
                                            listarcuenta(tr.id_plan_cuentas_1),
                                                abrirlista(index)
                                                
                                        "
                                    />
                                </div>
                                <div
                                    class="menuescoger"
                                    style="display:none"
                                    id="menuescoger"
                                    v-if="tr.id_plan_cuentas_1"
                                >
                                    <template v-if="contenidocuenta.length">
                                        <ul
                                            v-for="(tr,
                                            index) in contenidocuenta"
                                            :key="index"
                                            @click="handleSelectedCuenta(tr)"
                                        >
                                            <li>
                                                {{ tr.nomcta }}
                                                <span class="posicion">
                                                    <template v-if="tr.codcta"
                                                        ><span
                                                            >Codigo:
                                                            {{ tr.codcta }}
                                                        </span>
                                                    </template>
                                                </span>
                                            </li>
                                        </ul>
                                    </template>
                                    <template v-else>
                                        <ul
                                            style="padding: 7px;text-align: center;"
                                        >
                                            <li>
                                                Cuenta Contable no existe
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
                        </div>-->
                        
                              <div v-show="error" v-if="!tr.id_plan_cuentas_1">
                                  <span
                                      class="text-danger"
                                      v-for="err in errorid_plan_cuentas_1"
                                      :key="err"
                                      v-text="err"
                                  ></span>
                              </div>
                          </div>
                          <div class="vx-col sm:w-1/2 w-full mb-2">
                              <h6>Haber:</h6>
                              <vx-input-group class>
                                  <vs-input
                                      class="w-full"
                                      v-model="tr.id_plan_cuentas_2"
                                      :value="tr.cod_cuenta_2"
                                      disabled
                                  />
                                  <template slot="append">
                                      <div class="append-text btn-addon">
                                          <vs-button
                                              color="primary"
                                              @click="activectn2 = true,index_plan_cuenta(index)"
                                              >Buscar</vs-button
                                          >
                                      </div>
                                  </template>
                              </vx-input-group>
                              <div v-show="error" v-if="!tr.id_plan_cuentas_2">
                                  <span
                                      class="text-danger"
                                      v-for="err in errorid_plan_cuentas_2"
                                      :key="err"
                                      v-text="err"
                                  ></span>
                              </div>
                          </div>
                        
                        <vs-button
                        
                            v-if="usuario.id_rol == 1 && tr.id_ineg==null"
                            color="danger"
                            type="gradient"
                            class="iconelim"
                            @click="quitarcampo(index)"
                            >x</vs-button
                        >
                        <vs-button
                        
                            v-else-if="usuario.id_rol == 1 && tr.id_ineg!=null"
                            color="danger"
                            type="gradient"
                            class="iconelim"
                            @click="eliminarcampo(tr.id_ineg)"
                            >d</vs-button
                        >
                    <!-- CUENTA CONTABLE=====1-->
                        
                    <vs-popup
                        title="Plan Cuentas"
                        class="peque"
                        :active.sync="activePrompt3"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="buscar"
                                @keyup="listar(1, buscar)"
                                v-bind:placeholder="i18nbuscar"
                            />
                            <vs-table
                                stripe
                                @selected="handleSelected3"
                                
                                :data="contenido"
                            >
                                <template slot="thead">
                                    <vs-th>No.Cuenta</vs-th>
                                    <vs-th>Tipo Cuenta</vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :data="tr"
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td :data="data[indextr].codcta">{{
                                            data[indextr].codcta
                                        }}</vs-td>
                                        <vs-td :data="data[indextr].nomcta">{{
                                            data[indextr].nomcta
                                        }}</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                    </vs-popup>
                    <!--Fin cuenta contable1-->
                    
                    <!-- CUENTA CONTABLE=====2-->
                    <vs-popup
                        title="Plan Cuentas"
                        class="peque"
                        :active.sync="activectn2"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="buscar2"
                                @keyup="listar2(1, buscar2)"
                                v-bind:placeholder="i18nbuscar2"
                            />
                            <vs-table
                                stripe
                                v-model="cuentaarray4"
                                @selected="handleSelectedctn"
                                :data="contenido"
                            >
                                <template slot="thead">
                                    <vs-th>No.Cuenta</vs-th>
                                    <vs-th>Tipo Cuenta</vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :data="tr"
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td :data="data[indextr].codcta">{{
                                            data[indextr].codcta
                                        }}</vs-td>
                                        <vs-td :data="data[indextr].nomcta">{{
                                            data[indextr].nomcta
                                        }}</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                    </vs-popup>
                    <!--Fin cuenta contable2-->
                    
                </div>
                <vs-divider border-style="solid" color="dark">
                    <vs-button
                        v-if="usuario.id_rol == 1"
                        color="primary"
                        style="margin-left: 9px;padding: 8px 20px;"
                        type="border"
                        @click="handleSelected5()"
                        >+</vs-button
                    >
                </vs-divider>
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
                        <vs-button
                            color="warning"
                            type="filled"
                            @click="borrar()"
                            >BORRAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            to="/nomina/ingreso-egreso"
                            >CANCELAR</vs-button
                        >
                    </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
import { log } from "util";
const axios = require("axios");
const $ = require("jquery");
export default {
    data() {
        return {
            nombre2: "",
            tipo: "",
            activectn2: false,
            activePrompt3: false,
            cuentaarray3: [],
            cuentaarray4: [],
            i18nbuscar: this.$t("i18nbuscar"),
            buscar: "",
            i18nbuscar2: this.$t("i18nbuscar"),
            buscar2: "",
            criterio: "codcta",
            decripcion: "",
            id_plan_cuentas_1: [],
            iddepart: "",
            idContable: "",
            idContable2: "",
            id_plan_cuentas_2: [],
            valorproveedores: [
                {
                   id_ineg:null,
                   decripcion:"",
                   tipo:"",
                   id_plan_cuentas_1:"",
                   id_plan_cuentas_2:"" ,
                   cod_cuenta_1:"",
                   cod_cuenta_2:""
                }
            ],
            departamento_valorproveedor:"",

            departamento: "",
            contenidocuenta:[],
            activePrompt5: false,
            //listar depart
            contenidodepartamento: [],
            contenido: [],
            contenidocamposadicionales: [],
            nombrec: "",
            nombre: "",
            contenido1: [],
            //ERRORES
            error: 0,
            errordepartamento: [],
            erroring:[],
            errordes: [],
            errortipo: [],
            errorid_plan_cuentas_1: [],
            errorid_plan_cuentas_2: [],

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
        
        departamento_ingreso(){
            var total="";
            if(this.$route.params.id){
                this.valorproveedores.forEach(el => {
                    //if(this.$route.params.id){
                        
                            el.dep_nombre=this.departamento_valorproveedor;
                        
                        
                    
                });
            }
        }
    },
    methods: {
        /***
         * @function quitarcampos
         * borrar campos
         *
         */
        quitarcampo(x) {
            this.valorproveedores.splice(x, 1);
        },
        eliminarcampo(id){
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/ingresoegreso/eliminaruno/" + parameters);
            this.$vs.notify({
                color: "success",
                title: "Reguistro Eliminado  ",
                text: "Registro eliminado con exito"
            });
        },
        index_plan_cuenta(index){
            this.idContable=index;
        },
        /**
         *
         * @function handleSelected3
         * Seleciona una cuanta contable de cuenta contable 1
         */
        handleSelected3(tr) {
           
           
           this.activePrompt3 = false
           
           var s=this.idContable;
            this.valorproveedores[s].cod_cuenta_1=tr.id_plan_cuentas;
            this.valorproveedores[s].id_plan_cuentas_1=tr.nomcta;
            //console.log("hola"+this.idContable);
            
        },

        
        /**
         * cuenta contable dos
         * @function handleSelectedctn
         */
         handleSelectedctn(tr) {
            
            this.activectn2 = false;
            var s=this.idContable;
             this.valorproveedores[s].cod_cuenta_2=tr.id_plan_cuentas;
            this.valorproveedores[s].id_plan_cuentas_2=tr.nomcta;
        },

        
        handleSelected4(tr) {
            console.log("hol"+tr);
            (this.departamento = `${tr.dep_nombre}`),
                (this.iddepart = `${tr.id_departamento}`),
                (this.activePrompt5 = false);
        },
        handleSelected5(tr) {

            if (this.valorproveedores.length < 30) {
                this.valorproveedores.push({
                   id_ineg:null,
                   decripcion:"",
                   tipo:"",
                   id_plan_cuentas_1:"",
                   id_plan_cuentas_2:"" ,
                   iess:null,
                   cod_cuenta_1:"",
                   cod_cuenta_2:""
                });
            }
        },
        listar(page, buscar) {
            let me = this;
            var url =
                "/api/notacredito/listar_cuenta_contable" 
                // +
                // this.usuario.id_empresa +
                // "?page=" +
                // page +
                // "&buscar=" +
                // buscar
                ;
            axios
                .get(url,{
                    params:{
                        empresa:this.usuario.id_empresa,
                        buscar:buscar
                    }
                })
                .then(({data})=> {
                    var respuesta = data;
                    me.contenido = respuesta;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listar2(page2,buscar2){
            let me = this;
            var url =
                "/api/notacredito/listar_cuenta_contable"
                // +
                // this.usuario.id_empresa +
                // "?page=" +
                // page2 +
                // "&buscar=" +
                // buscar2
                ;
            axios
                .get(url,{
                    params:{
                        empresa:this.usuario.id_empresa,
                        buscar:buscar2
                    }
                })
                .then(({data})=> {
                    var respuesta = data;
                    me.contenido = respuesta;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
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
        cancelar() {
            this.$router.push("/nomina/ingresos");
        },
        listarcuenta(buscar1){
            axios.get("/api/selcuentas/" +
                this.usuario.id_empresa +
                "?buscar=" +
                buscar1
            )
            .then(res => {
                this.contenidocuenta = res.data.recupera;
                //this.contenidocuentadesc = res.data.recupera;
                //this.contenidocuentaantcp = res.data.recupera;
            });
        },
        abrirlista(index) {
            $(".menuescoger").show();
        },
        handleSelectedCuenta(tr){
            
            var s=this.valorproveedores.length-1;

            this.valorproveedores[s].id_plan_cuentas_1=`${tr.codcta}`
            
            //this.idContable = `${tr.id_plan_cuentas}`;
            //this.ctacontable = `${tr.codcta}`;
        },
        guardar() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/ingresoegreso/agregar", {
                    departamento: this.iddepart,
                    id_empresa: this.usuario.id_empresa,
                    id_user: this.usuario.id,
                    ingresose: this.valorproveedores
                })
                .then(res => {
                    if(res.data=="existe" ){
                        this.$vs.notify({
                            title: "Error en guardar",
                            text: "Ya existe un ingreso o egreso",
                            color: "danger"
                        });
                    }else{
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/nomina/ingreso-egreso/");
                    }
                    
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Guardar",
                        text: "Revise sus campos antes de guardar",
                        color: "danger"
                    });
                });
        },
        editar() {
            if (this.validar()) {
                return;
            }
            
            axios
                .put("/api/ingresoegreso/editar", {

                    id: this.$route.params.id,
                    departamento: this.iddepart,
                    id_empresa: this.usuario.id_empresa,
                    id_user: this.usuario.id,
                    ingresose: this.valorproveedores
                })
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Actualizado exitosamente",
                            text: "Registro actualizado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/nomina/ingreso-egreso");
                    
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Actualizar",
                        text: "Revise sus campos antes de guardar",
                        color: "danger"
                    });
                });
        },
        veringresos() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
            } else {
                this.idrecupera = 0;
            }
            var url = "/api/veringresoegreso/listar/" + this.idrecupera;
            axios
                .get(url)
                .then(res => {
                    this.valorproveedores = res.data;
                    
                    //console.log("l"+res.data[0].dep_nombre);
                            this.departamento=res.data[0].dep_nombre;
                            this.departamento_valorproveedor=res.data[0].dep_nombre;
                            this.iddepart=res.data[0].id_departamento;
                        
                   
                })
                .catch(err => {
                    //console.log(err);
                });
        },
        borrar() {
            this.departamento="",
            this.iddepart="",
            this.valorproveedores= [
                {
                   //id_ineg:null,
                   decripcion:"",
                   tipo:"",
                   id_plan_cuentas_1:"",
                   id_plan_cuentas_2:"" ,
                   cod_cuenta_1:"",
                   cod_cuenta_2:""
                }
            ]

        },
        validar() {
            this.error = 0;
            this.errordepartamento = [];
            this.erroring=[];
            this.errordes = [];
            this.errortipo = [];
            this.errorid_plan_cuentas_1= [];
            this.errorid_plan_cuentas_2 = [];
           
            if (!this.valorproveedores.length) {
                this.erroring.push("Campo obligatorio");
                this.error = 1;
            }
            for (var i = 0; i < this.valorproveedores.length; i++) {
                this.valorproveedores[i].errordes = [];
                this.valorproveedores[i].errortipo = [];
                this.valorproveedores[i].errorid_plan_cuentas_1 = [];
                this.valorproveedores[i].errorid_plan_cuentas_2 = [];
                if (!this.valorproveedores[i].decripcion) {
                    this.valorproveedores[i].errordes.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.valorproveedores[i].tipo) {
                    this.valorproveedores[i].errortipo.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }

            /*if (!this.id_plan_cuentas_1) {
                this.errorid_plan_cuentas_1.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.cuenta2) {
                this.errorcuenta2.push("Campo obligatorio");
                this.error = 1;
            }*/
            
                return this.error;
        },
        solonumeros: function($event) {
            var num = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        }
    },
    mounted() {
        this.listarDepartamento(1, this.buscar1);
        this.listar(1, this.buscar);
        //this.listar2(1, this.buscar2);
        if (this.$route.params.id) {
            var id = this.$route.params.id;
            this.veringresos();
        }
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
</style>
