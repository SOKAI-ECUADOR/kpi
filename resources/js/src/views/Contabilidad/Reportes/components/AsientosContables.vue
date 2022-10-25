<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Reporte Contable:</label>
                <v-select
                    
                    :options="tipo_reporte.reporteList"
                    label="nombre"
                    v-model="tipo_reporte.selectedReporte"
                    placeholder="Seleccione el Reporte"
                ></v-select>
                <!--<div v-show="error" v-if="!tipo_reporte.selectedReporte">
                    <div v-for="err in errorreporte" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
            </div>

            <div class="flex flex-wrap mt-5 mb-5">
            
            <div class="w-1/4 flex">
                <label for="sections" v-if="dates.currentDate.active"
                    >Fecha de emision actual</label
                >
                <label for="sections" v-else>Fecha de emision en rango</label>
                <vs-switch class="ml-5" v-model="dates.currentDate.active" />
            </div>
        </div>
        <div class="flex flex-wrap mt-5 mb-5" v-if="!dates.currentDate.active">
            <div class="sm:w-full md:w-6/12 w-6/12">
                <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                    <datepicker
                        id="dt-initial"
                        :minimumView="'day'"
                        :maximumView="'year'"
                        placeholder="Fecha de inicio"
                        :language="es"
                        v-model="dates.currentDate.dateRange.initialDate"
                    ></datepicker>
                </div>
            </div>
            <div class="sm:w-full md:w-6/12 w-6/12 md:ml-auto">
                <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                    <datepicker
                        id="dt-final"
                        :minimumView="'day'"
                        :maximumView="'year'"
                        placeholder="Fecha final"
                        :language="es"
                        v-model="dates.currentDate.dateRange.finalDate"
                    ></datepicker>
                </div>
           </div>
        </div>
        <vs-divider border-style="solid" color="dark" v-if="tipo_reporte.selectedReporte "></vs-divider>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/2 w-1/3 mb-6 pl-3 pr-3" v-if=" tipo_reporte.selectedReporte">
                <label for="project">Proyecto:</label>
                <v-select
                    id="project"
                    :options="provider.providersList"
                    label="name"
                    v-model="provider.selectedProvider"
                    placeholder="Seleccione el proyecto"
                ></v-select>
                <!--<div v-show="error" v-if="!provider.selectedProvider">
                    <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
            </div>
            <!--<div class="sm:w-full md:w-1/2 w-1/3 mb-6 pl-3 pr-3" v-if=" tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id !==3 && tipo_reporte.selectedReporte.id !==4  && tipo_reporte.selectedReporte.id !==3 && tipo_reporte.selectedReporte.id !==5">
          

            <vs-select
                class="selectExample"
                
                vs-multiple
                autocomplete
                v-model="provider.selectedProvider"
            >
              <vs-select-item
                v-for="(data,index) in provider.providersList"
                :key="index"
                :value="data.id"
                :text="data.name"
              />
            </vs-select>
        </div>-->
            <div class="sm:w-full md:w-1/2 w-1/3 mb-6 pl-3 pr-3" v-if="tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id !==3 && tipo_reporte.selectedReporte.id !==4   && tipo_reporte.selectedReporte.id !==5">
                <label for="project">Comprobante:</label>
                <v-select
                    id="project"
                    :options="comprobante.comprobanteList"
                    label="name"
                    v-model="comprobante.selectedComprobante"
                    placeholder="Seleccione el comprobante"
                ></v-select>
                <!--<div v-show="error" v-if="!provider.selectedProvider">
                    <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
            </div>
            <!--<div class="sm:w-full md:w-full w-full mb-6 pl-3 pr-3" v-if="tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id===2">
                <label for="project">Cuenta Contable:</label>
                <v-select
                    id="project"
                    :options="planctas.planctasList"
                    label="name"
                    v-model="planctas.selectedplanctas"
                    placeholder="Seleccione la cuenta contable"
                ></v-select>
                <div v-show="error" v-if="!provider.selectedProvider">
                    <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>-->
                <div class="sm:w-full md:w-1/2 w-1/3 mb-6 pl-3 pr-3" v-if="tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id===2">
                    <label class="vs-input--label">Cuenta Contable Desde:</label>
                    <vx-input-group class>
                    <vs-input class="w-full" v-model="cta_contable" :value="id_cta_contable" disabled/>
                        <template slot="append">
                            <div class="append-text btn-addon">
                                
                                <vs-button color="primary" type="filled" @click="listar3(1,'',1)">Buscar</vs-button>
                                <vs-button color="danger" type="flat" @click="eliminarplc()">X</vs-button>
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="sm:w-full md:w-1/2 w-1/3 mb-6 pl-3 pr-3" v-if="tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id===2">
                    <label class="vs-input--label">Cuenta Contable Hasta:</label>
                    <vx-input-group class>
                    <vs-input class="w-full" v-model="cta_contable_2" :value="id_cta_contable_2" disabled/>
                        <template slot="append">
                            <div class="append-text btn-addon">
                                
                                <vs-button color="primary" type="filled" @click="listar3(1,'',2)">Buscar</vs-button>
                                <vs-button color="danger" type="flat" @click="eliminarplc_2()">X</vs-button>
                            </div>
                        </template>
                    </vx-input-group>
                </div>

            

            <div class="vx-col w-full">
            <vs-button
                class="mt-5"
                color="success"
                @click="sendData"
                type="filled"
                >Generar  </vs-button
            >

            <vs-button
                class="mt-5"
                color="warning"
                @click="abrirmodal()"
                type="filled"
                >Enviar</vs-button
            >
        </div>
        </div>
        </vx-card>
        <vs-popup
                        title="Enviar al Correo"
                        class="peque"
                        :active.sync="activectn2"
                    >
                    <div class="con-exemple-prompt">
                        <label>Destinatario:</label>
                        <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="destinatario"
                            
                            />
                            <label>Correo:</label>
                        <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="email"
                            
                            />
                            <vs-button
                                class="mt-5"
                                color="warning"
                                @click="enviaremail()"
                                type="filled"
                                >Enviar</vs-button
                            >
                    </div>
                     </vs-popup>
                     <vs-popup title="Plan Cuentas" :active.sync="activePrompt7">
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="buscar3"
                                @keyup="listar3(1,buscar3,1)"
                                v-bind:placeholder="i18nbuscar3"
                            />
                            <vs-table stripe v-model="planctas.selectedplanctas" @selected="handleSelected3" :data="planctas.planctasList">
                                <template slot="thead">
                                    <vs-th>No.Cuenta</vs-th>
                                    <vs-th>Tipo Cuenta</vs-th>
                                </template>
                                <template slot-scope="{data}">
                                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                                    <vs-td :data="data[indextr].codcta">{{ data[indextr].codcta }}</vs-td>

                                    <vs-td :data="data[indextr].nomcta">{{ data[indextr].nomcta }}</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                    </vs-popup>
                    <vs-popup title="Plan Cuentas" :active.sync="activePrompt8">
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="buscar4"
                                @keyup="listar3(1,buscar4,2)"
                                v-bind:placeholder="i18nbuscar3"
                            />
                            <vs-table stripe v-model="planctas.selectedplanctas" @selected="handleSelected4" :data="planctas.planctasList">
                                <template slot="thead">
                                    <vs-th>No.Cuenta</vs-th>
                                    <vs-th>Tipo Cuenta</vs-th>
                                </template>
                                <template slot-scope="{data}">
                                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                                    <vs-td :data="data[indextr].codcta">{{ data[indextr].codcta }}</vs-td>

                                    <vs-td :data="data[indextr].nomcta">{{ data[indextr].nomcta }}</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                    </vs-popup>
    </div>
</template>
<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
import { es } from 'vuejs-datepicker/dist/locale';
const axios = require("axios");
export default {
    data(){
        return{
            es:es,
            dates: {
                optionSelected: 3,
                optionSelectedValue: null,
                currentDate: {
                    active: true,
                    value: null,
                    dateRange: {
                        initialDate: null,
                        finalDate: null
                    }
                }
            },
            id_cta_contable:"",
            cta_contable:"",
            id_cta_contable_2:"",
            cta_contable_2:"",
            activePrompt7:false,
            activePrompt8:false,
            provider: {
                providersList: [{ id: 0, name: "TODOS" }],
                selectedProvider: { id: 0, name: "TODOS" }
            },
            tipo_reporte: {
                reporteList: [
                    {id:1,nombre:"Diario General"},
                    {id:2,nombre:"Mayor General"},
                    {id:3,nombre:"Balance de Comprobacion"},
                    {id:4,nombre:"Estado Situacion Financiera"},
                    {id:5,nombre:"Estado de Resultado Integral"}
                ],
                selectedReporte: null
            },
            planctas: {
                planctasList: [
                    {id:0,name:"TODOS"},
                ],
                selectedplanctas: {id:0,name:"TODOS"}
            },
            activectn2:false,
            destinatario:"",
            email:"",
            i18nbuscar3: this.$t("i18nbuscar"),
            buscar3: "",
            buscar4: "",
            comprobante: {
                comprobanteList: [
                    {
                        id: 0,
                        name: "Todos"
                    },
                    {
                        id: 1,
                        name: "Ingresos"
                    },
                    {
                        id: 2,
                        name: "Egresos"
                    },
                    {
                        id: 3,
                        name: "Diarios"
                    },
                    {
                        id: 4,
                        name: "Rol Pago"
                    },
                    {
                        id: 5,
                        name: "Rol Provision"
                    },
                    {
                        id: 6,
                        name: "Factura Venta"
                    },
                    {
                        id: 7,
                        name: "Factura Compra"
                    },
                    {
                        id: 8,
                        name: "Cobro Clientes"
                    },
                    {
                        id: 9,
                        name: "Cobro Proveedores"
                    },
                    // {
                    //     id: 10,
                    //     name: "Importacion"
                    // },
                    // {
                    //     id: 11,
                    //     name: "Nota Credito Facturacion"
                    // },
                    // {
                    //     id: 12,
                    //     name: "Nota Debito Facturacion"
                    // },
                    // {
                    //     id: 13,
                    //     name: "Nota Credito Compras"
                    // },
                    // {
                    //     id: 14,
                    //     name: "Nota Debito Compras"
                    // }

                ],
                selectedComprobante: {
                        id: 0,
                        name: "Todos"
                    }
            },
        };
    },
    components: {
        Datepicker
    },
    computed: {
        idEmpresa() {
            let user = this.$store.state.AppActiveUser;
            return user.id_empresa;
        }
    },
    methods:{
        abrirmodal(){
            this.activectn2=true;   
        },
        handleSelected3(tr) {
            this.cta_contable = `${tr.nomcta}`,
            this.id_cta_contable = `${tr.id_plan_cuentas}`,
            this.activePrompt7 = false
        },
        handleSelected4(tr){
            this.cta_contable_2 = `${tr.nomcta}`,
            this.id_cta_contable_2 = `${tr.id_plan_cuentas}`,
            this.activePrompt8 = false
        },
        eliminarplc(){
            this.cta_contable="",
            this.id_cta_contable=""
        },
        eliminarplc_2(){
            this.cta_contable_2="",
            this.id_cta_contable_2=""
        },
        enviaremail(){
            let dates = this.dates.currentDate.active
                ? {
                      option: this.dates.optionSelected,
                      value: service.generateDate({ date: new Date() })
                  }
                : {
                      option: this.dates.optionSelected,
                      range: {
                          initial: this.dates.currentDate.dateRange.initialDate
                              ? service.generateDate({
                                    date: new Date(
                                        this.dates.currentDate.dateRange.initialDate
                                    )
                                })
                              : service.generateDate({ date: new Date() }),
                          final: this.dates.currentDate.dateRange.finalDate
                              ? service.generateDate({
                                    date: new Date(
                                        this.dates.currentDate.dateRange.finalDate
                                    )
                                })
                              : service.generateDate({ date: new Date() })
                      }
                  };
            if (dates.range) {
                let initial = dates.range.initial;
                let final = dates.range.final;
                dates.range.initial = initial > final ? final : initial;
                dates.range.final = final < initial ? initial : final;
            }
            let response={
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                dates: dates,
                //establishment: this.establishment.selectedEstablishment,
                //pointOfEmission: this.pointOfEmission.selectedPointOfEmission,
                //project: this.project.selectedProject,
                project: this.provider.selectedProvider,
                //wayToPay: this.wayToPay.selectedPaymentMethod,
                //user: this.user.selectedUser,
                //invoice: this.invoice,
                reporte:this.tipo_reporte.selectedReporte,
                comprobante:this.comprobante.selectedComprobante,
                plan_cuenta:this.planctas.selectedplanctas,
                email:this.email,
                destinatario:this.destinatario
            }
            axios({
                url: "/api/asientos-contables/reporte/diario_general",
                method: "GET",
                responseType: "arraybuffer",
                params: response
                }).then(resp=>{
                    var decodedString = String.fromCharCode.apply(
                        null,
                        new Uint8Array(resp.data)
                    );
                    if (decodedString.includes("no-data-report")) {
                        this.$vs.notify({
                        title: "Error al Generar Reporte",
                        text: "No se encontraron registros con los datos proporcionados",
                        color: "warning"
                    });
                    return;
                    }
                    this.$vs.notify({
                        title: "Reporte Enviado",
                        text: "Su reporte esta siendo enviado exitosamente!",
                        color: "success"
                    });
                    this.email="";
                    this.destinatario="";
                    this.activectn2=false;
                }).catch(err=>{
                    
                    this.$vs.notify({
                        title: "Reporte NO Enviado",
                        text: "Error al Enviar Reporte",
                        color: "danger"
                    });
                    
                });
        },
        sendData() {
            /*if(this.validar()){
                return;
            }*/
            let dates = this.dates.currentDate.active
                ? {
                      option: this.dates.optionSelected,
                      value: service.generateDate({ date: new Date() })
                  }
                : {
                      option: this.dates.optionSelected,
                      range: {
                          initial: this.dates.currentDate.dateRange.initialDate
                              ? service.generateDate({
                                    date: new Date(
                                        this.dates.currentDate.dateRange.initialDate
                                    )
                                })
                              : service.generateDate({ date: new Date() }),
                          final: this.dates.currentDate.dateRange.finalDate
                              ? service.generateDate({
                                    date: new Date(
                                        this.dates.currentDate.dateRange.finalDate
                                    )
                                })
                              : service.generateDate({ date: new Date() })
                      }
                  };
            // if (dates.range) {
            //     let initial = dates.range.initial;
            //     let final = dates.range.final;
            //     dates.range.initial = initial > final ? final : initial;
            //     dates.range.final = final < initial ? initial : final;
            // }
            let response = {
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                dates: dates,
                //establishment: this.establishment.selectedEstablishment,
                //pointOfEmission: this.pointOfEmission.selectedPointOfEmission,
                //project: this.project.selectedProject,
                project: this.provider.selectedProvider,
                //wayToPay: this.wayToPay.selectedPaymentMethod,
                //user: this.user.selectedUser,
                //invoice: this.invoice,
                reporte:this.tipo_reporte.selectedReporte,
                comprobante:this.comprobante.selectedComprobante,
                plan_cuenta:this.id_cta_contable,
                plan_cuenta_2:this.id_cta_contable_2,
                email:null,
                destinatario:null
            };
            this.$emit("generateReport", response);
        },
        listar3(page,buscar,tipo){
            axios.get("/api/listarplan_cuenta",
            {
                params:{
                    id:this.idEmpresa,
                    buscar:buscar
                }
            })
            .then(resp=>{
                this.planctas.planctasList=resp.data.recupera;
                if(tipo==1){
                    this.activePrompt7=true;
                }else{
                    this.activePrompt8=true;
                }
                
            }).catch(err=>{
                console.log("[ERROR plc]:"+err);
            });
        },
    },
    async mounted(){
        try {
            this.provider.providersList.push(
                ...(await service.projects.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            // this.planctas.planctasList.push(
            //     ...(await service.plan_cta.getAll({
            //         idEmpresa: this.idEmpresa
            //     }))
            // );
        } catch (error) {
            
        }
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
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
  width: 800px !important;
}
.peque .vs-popup {
  width: 500px !important;
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
    font: 13.3333px Arial, sans-serif;
    position: relative;
    border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover {
    background: rgba(0, 0, 0, 0.1);
}

.menuescoger span {
    font-size: 12px;
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
  max-width: 50%;
  max-height: 50px;
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
</style>