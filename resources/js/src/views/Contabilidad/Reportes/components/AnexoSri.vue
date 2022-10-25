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

        <div class="flex flex-wrap mt-5 mb-5" v-if="tipo_reporte.selectedReporte && (tipo_reporte.selectedReporte.id==1 || tipo_reporte.selectedReporte.id==2)">
            <div class="vx-col sm:w-1/6 mb-3 pr-3">
                    <label for="sections">Año de consulta:</label> <br> 
                    <vs-select placeholder="Año"  class="selectExample w-full mt-5" vs-multiple autocomplete v-model="anio">
                        <vs-select-item v-for="tr in anios" :key="tr" :value="tr" :text="tr" />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/6 mb-3 pr-3">
                    <label for="sections">Mes de consultas:</label> <br>
                    <vs-select placeholder="Mes"  class="selectExample w-full mt-5" vs-multiple autocomplete v-model="mes">
                        <vs-select-item v-for="tr in 12" :key="tr" :value="tr" :text="tr" />
                    </vs-select>
                </div>
        </div>
        

            

            <div class="vx-col w-full">
                <vs-button
                    class="mt-5"
                    color="success"
                    @click="sendData"
                    type="filled"
                    >Generar  </vs-button
                >

                <!-- <vs-button
                    class="mt-5"
                    color="warning"
                    @click="abrirmodal()"
                    type="filled"
                    >Enviar</vs-button
                > -->
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
                    
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
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
                    {id:1,nombre:"ATS"},
                    {id:2,nombre:"Detalle ATS"},
                    // {id:2,nombre:"Mayor General"},
                    // {id:3,nombre:"Balance de Comprobacion"},
                    // {id:4,nombre:"Estado Situacion Financiera"},
                    // {id:5,nombre:"Estado de Resultado Integral"}
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
                },
                
            },
            anio:"",
            mes:"",
            anios:[],
            meses:[
                    {
                        id: '01',
                        name: "Enero"
                    },
                    {
                        id: '02',
                        name: "Febrero"
                    },
                    {
                        id: '03',
                        name: "Marzo"
                    },
                    {
                        id: '04',
                        name: "Abril"
                    },
                    {
                        id: '05',
                        name: "Mayo"
                    },
                    {
                        id: '06',
                        name: "Junio"
                    },
                    {
                        id: '07',
                        name: "Julio"
                    },
                    {
                        id: '08',
                        name: "Agosto"
                    },
                    {
                        id: '09',
                        name: "Septiembre"
                    },
                    {
                        id: '10',
                        name: "Octubre"
                    },
                    {
                        id: '11',
                        name: "Noviembre"
                    },
                    {
                        id: '12',
                        name: "Diciembre"
                    },
                    ]
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
            this.cta_contable = `${tr.name}`,
            this.id_cta_contable = `${tr.id}`,
            this.activePrompt7 = false
        },
        handleSelected4(tr){
            this.cta_contable_2 = `${tr.name}`,
            this.id_cta_contable_2 = `${tr.id}`,
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
                reporte:this.tipo_reporte.selectedReporte,
                anio:this.anio,
                mes:this.mes,
                email:null,
                destinatario:null
            };
            this.$emit("generateReport", response);
        },
    },
    async mounted(){
        try {
            
            for(var i=moment().format('Y'); i>=2000; i--){
                this.anios.push(i);
            }
            this.anio = moment().format('Y');
            this.mes = moment().format('MM');
        } catch (error) {
            console.log("cambios ats error:"+error);
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