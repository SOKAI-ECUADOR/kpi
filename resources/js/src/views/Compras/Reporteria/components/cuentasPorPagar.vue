<template>
    <vx-card>
        <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Tipo Reporte:</label>
                <v-select
                    
                    :options="tipo_reporte.reporteList"
                    label="nombre"
                    v-model="tipo_reporte.selectedReporte"
                    placeholder="Seleccione el Reporte"
                ></v-select>
                <div v-show="error" v-if="!tipo_reporte.selectedReporte">
                    <div v-for="err in errorreporte" :key="err" v-text="err" class="text-danger"></div>
                </div>
        </div>
        <div class="flex flex-wrap mt-5 mb-5">
            
            <div class="w-1/4 flex">
                <label for="sections" v-if="dates.currentDate.active"
                    >Fecha de emision actual</label
                >
                <label for="sections" v-else>Fecha de emision en rango</label>
                <vs-switch class="ml-5" v-model="dates.currentDate.active" />
            </div>
            <div class="w-1/5 flex" v-if="tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id==3 && name_establecimiento=='CONSPECCIME CIA. LTDA.'">
                    <label for="project">Vencido:</label>
                    <vs-checkbox
                            v-model="vencido_reporte"
                            vs-value="1"
                    ></vs-checkbox>
                    <!--<div v-show="error" v-if="!tipo_reporte.selectedReporte">
                        <div v-for="err in errorreporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>-->
            </div>
        </div>
        <div class="flex flex-wrap mt-5 mb-5" v-if="!dates.currentDate.active">
            <!--<div class="sm:w-full md:w-6/12 w-6/12">
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
            </div>-->
            <!--<div class="sm:w-full md:w-6/12 w-6/12 md:ml-auto">-->
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
           <!-- </div>>-->
        </div>
        <vs-divider border-style="solid" color="dark"></vs-divider>
        <!--<div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="establishment">Establecimiento:</label>
                <v-select
                    id="establishment"
                    :options="establishment.establishmentsList"
                    label="name"
                    v-model="establishment.selectedEstablishment"
                    @input="searchByEstablishment"
                    placeholder="Seleccione el establecimiento"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pr-3 pl-3">
                <label for="pointOfEmission">Punto de emision:</label>
                
                <v-select
                    id="pointOfEmission"
                    :options="
                        pointOfEmission.pointsOfEmissionListByEstablishment"
                    label="name"
                    v-model="pointOfEmission.selectedPointOfEmission"
                    placeholder="Seleccione el punto de emision"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Proyecto:</label>
                <v-select
                    id="project"
                    :options="project.projectsList"
                    label="name"
                    v-model="project.selectedProject"
                    placeholder="Seleccione el proyecto"
                ></v-select>
            </div>
        </div>-->
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Proveedor:</label>
                <v-select
                    id="project"
                    :options="provider.providersList"
                    label="nombre"
                    v-model="provider.selectedProvider"
                    placeholder="Seleccione el proveedor"
                ></v-select>
                <div v-show="error" v-if="!provider.selectedProvider">
                    <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>
            
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pl-3 pr-3">
                <label for="project">Forma de pago:</label>
                <v-select
                    id="project"
                    :options="wayToPay.listOfPaymentMethods"
                    label="nombre"
                    v-model="wayToPay.selectedPaymentMethod"
                    placeholder="Seleccione la forma de pago"
                ></v-select>
                <div v-show="error" v-if="!wayToPay.selectedPaymentMethod">
                    <div v-for="err in errorforma_pago" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="sellers">Usuario:</label>
                <v-select
                    id="sellers"
                    :options="user.usersList"
                    label="fullname"
                    v-model="user.selectedUser"
                    placeholder="Seleccione el vendedor"
                ></v-select>
            </div>
        </div>
        <!--<div class="flex flex-wrap">
            <div class="w-1/4 mb-6" v-if="!invoice.allType">
                <label for="sections" class="flex mb-2"
                    >Gastos de importacion
                    <vs-switch class="ml-5" v-model="invoice.importCosts" />
                </label>
            </div>
            <div class="w-1/4 mb-6" v-if="!invoice.allType">
                <label for="sections" class="flex mb-2"
                    >Documento tributario
                    <vs-switch class="ml-5" v-model="invoice.taxDocument" />
                </label>
            </div>
            <div class="w-1/4 mb-6">
                <label for="sections" class="flex mb-2"
                    >Todas
                    <vs-switch class="ml-5" v-model="invoice.allType" />
                </label>
            </div>
        </div>-->
        <div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
            <!--<label class="mr-2">Facturas</label>
            <vs-switch
                v-if="!invoice.all"
                v-model="invoice.credit"
                class="mr-2"
            >
                <span slot="on">con credito</span>
                <span slot="off">sin credito</span>
            </vs-switch>
            <vs-switch
                v-if="!invoice.all"
                v-model="invoice.retention"
                class="mr-2"
            >
                <span slot="on">con retenciones</span>
                <span slot="off">sin retenciones</span>
            </vs-switch>
            <vs-switch v-model="invoice.all" class="mr-2">
                <span slot="on">Todas</span>
            </vs-switch>-->
            <label class="mr-2 ml-2">con valor cuota</label>
            <vs-radio
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="1"
                >mayor</vs-radio
            >
            <vs-radio
                class="mr-2 ml-2"
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="0"
                >igual</vs-radio
            >
            <vs-radio
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="-1"
                >menor </vs-radio
            >
            <vs-radio
                class="ml-2"
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="2"
                >todas</vs-radio
            >
            <label
                v-if="invoice.typeSearch != 2 && invoice.typeSearch != -3 && invoice.typeSearch != -2 && invoice.typeSearch"
                class="ml-2"
                >a:</label
            >
            <vs-input
                class="ml-4"
                maxlength="7"
                v-if="invoice.typeSearch != 2 && invoice.typeSearch != -3 && invoice.typeSearch != -2 && invoice.typeSearch"
                v-model="invoice.totalCount"
                @keypress="onlyNumbers($event)"
                @blur="changeToDecimal"
                placeholder=" $ "
            />
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
    </vx-card>
</template>

<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
import { es } from 'vuejs-datepicker/dist/locale';
const axios = require("axios");
export default {
    name: "reporteria-cuentas-por-pagar",
    data() {
        return {
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
            activectn2:false,
            email:"",
            destinatario:"",
            establishment: {
                establishmentsList: [{ id: 0, name: "TODOS" }],
                selectedEstablishment: null
            },
            pointOfEmission: {
                pointsOfEmissionList: [],
                pointsOfEmissionListByEstablishment: [],
                selectedPointOfEmission: null
            },
            project: {
                projectsList: [{ id: 0, name: "TODOS" }],
                selectedProject: null
            },
            provider: {
                providersList: [{ id: 0, nombre: "TODOS" }],
                selectedProvider: { id: 0, nombre: "TODOS" }
            },
            tipo_reporte: {
                reporteList: [{ id: 1, nombre: "Proveedor" },
                { id: 2, nombre: "Detallado por Factura" },
                { id: 3, nombre: "Resumido por Factura" },
                { id: 4, nombre: "Dias Vencimiento" },
                { id: 5, nombre: "Anticipo" }],
                selectedReporte: null
            },
            wayToPay: {
                listOfPaymentMethods: [{ id: 0, nombre: "TODOS" }],
                selectedPaymentMethod: { id: 0, nombre: "TODOS" }
            },
            user: {
                usersList: [{ id: 0, fullname: "TODOS" }],
                selectedUser: { id: 0, fullname: "TODOS" }
            },
            invoice: {
                retention: false,
                credit: false,
                all: true,
                typeSearch: 2,
                totalCount: null,
                importCosts: false,
                taxDocument: false,
                allType: true
            },
            //nombre establecimiento
            name_establecimiento:"",
            vencido_reporte:null,
            error:0,
            errorproveedor:[],
            errorforma_pago:[],
            errorusuario:[],
            errorreporte:[]
        };
    },
    computed: {
        idEmpresa() {
            let user = this.$store.state.AppActiveUser;
            return user.id_empresa;
        },
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
    },
    methods: {
        changeToDecimal() {
            if (this.invoice.totalCount != null) {
                this.invoice.totalCount = parseFloat(
                    this.invoice.totalCount
                ).toFixed(2);
            }
        },
        abrirmodal(){
            this.activectn2=true;
        },
        enviaremail(){
            if(this.validar()){
                return;
            }
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
            /*var url="/api/reporte/cuenta_pagar?company="+this.idEmpresa+
            "&currentDate="+this.dates.currentDate.active+
            "&dates="+dates+
            "&establishment="+this.establishment.selectedEstablishment+
            "&pointOfEmission="+this.pointOfEmission.selectedPointOfEmission+
            "&project="+this.project.selectedProject+
            "&provider="+this.provider.selectedProvider+
            "&wayToPay="+this.wayToPay.selectedPaymentMethod+
            "&user="+this.user.selectedUser+
            "&invoice="+this.invoice+
            "&reporte="+this.tipo_reporte.selectedReporte+
            "&email="+this.email+
            "&destinatario="+this.destinatario;*/
            let response={
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                dates: dates,
                establishment: this.establishment.selectedEstablishment,
                pointOfEmission: this.pointOfEmission.selectedPointOfEmission,
                project: this.project.selectedProject,
                provider: this.provider.selectedProvider,
                wayToPay: this.wayToPay.selectedPaymentMethod,
                user: this.user.selectedUser,
                invoice: this.invoice,
                
                reporte:this.tipo_reporte.selectedReporte,
                email:this.email,
                destinatario:this.destinatario
            }
            axios({
                url: "/api/reporte/cuenta_pagar",
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
                    /*let { headers } = resp;
                    let nameFile = headers["content-disposition"]
                        .split(";")[1]
                        .split("=")[1]
                        .replace(/"/g, "");
                    const url = window.URL.createObjectURL(
                        new Blob([resp.data], { type: "application/pdf" })
                    );
                    const link = document.createElement("a");
                    link.href = url;
                    link.setAttribute("download", nameFile);
                    document.body.appendChild(link);
                    link.click();*/
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
            
            //resolve({ url: url, nameFile: nameFile });
            /*let response={
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                dates: dates,
                establishment: this.establishment.selectedEstablishment,
                pointOfEmission: this.pointOfEmission.selectedPointOfEmission,
                project: this.project.selectedProject,
                provider: this.provider.selectedProvider,
                wayToPay: this.wayToPay.selectedPaymentMethod,
                user: this.user.selectedUser,
                invoice: this.invoice,
                
                reporte:this.tipo_reporte.selectedReporte,
                email:this.email,
                destinatario:this.destinatario
            }*/
            //this.$emit("Mail", response);
        },
        sendData() {
            if(this.validar()){
                return;
            }
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
            //console.log(JSON.stringify(dates)+"proyecto");
            //var fecha= Date.parse(this.dates.currentDate.dateRange.finalDate);
            console.log(JSON.stringify(dates)+"proyecto");
            
            let response = {
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                dates: dates,
                establishment: this.establishment.selectedEstablishment,
                pointOfEmission: this.pointOfEmission.selectedPointOfEmission,
                project: this.project.selectedProject,
                provider: this.provider.selectedProvider,
                wayToPay: this.wayToPay.selectedPaymentMethod,
                user: this.user.selectedUser,
                invoice: this.invoice,
                
                reporte:this.tipo_reporte.selectedReporte,
                email:null,
                destinatario:null,
                vencido_reporte:this.vencido_reporte
            };
            this.$emit("generateReport", response);
        },
        validar(){
            this.error=0;
            this.errorproveedor=[];
            this.errorforma_pago=[];
            this.errorusuario=[];
            this.errorreporte=[];
            if(!this.provider.selectedProvider){
                this.errorproveedor.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.wayToPay.selectedPaymentMethod){
                this.errorforma_pago.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.tipo_reporte.selectedReporte){
                this.errorreporte.push("Campo Obligatorio");
                this.error=1;
            }
            return this.error;
        },
        searchByEstablishment(establishment) {
            if (establishment !== null) {
                this.pointOfEmission.pointsOfEmissionListByEstablishment = [
                    { id: 0, name: "TODOS" }
                ];
                console.log(
                    ">>",
                    this.pointOfEmission.pointsOfEmissionListByEstablishment
                );
                let lista =
                    establishment.id == 0
                        ? this.pointOfEmission.pointsOfEmissionList
                        : this.pointOfEmission.pointsOfEmissionList.filter(
                              point => point.establishment === establishment.id
                          );
                console.log("<<", lista);
                lista.length != 1
                    ? this.pointOfEmission.pointsOfEmissionListByEstablishment.push(
                          ...lista
                      )
                    : (this.pointOfEmission.pointsOfEmissionListByEstablishment = lista);
            }
        },
        onlyNumbers(event) {
            this.$emit("onlyNumbers", event);
        }
    },
    components: {
        Datepicker
    },
    async mounted() {
        try {
            this.user.usersList.push(...(await service.users.getAll({
                idEmpresa: this.idEmpresa
            })));
            this.establishment.establishmentsList.push(
                ...(await service.establishments.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            if(this.establishment.establishmentsList.length>1){
                this.name_establecimiento=this.establishment.establishmentsList[1].name;
            }
            
            this.pointOfEmission.pointsOfEmissionList = await service.pointsOfEmission.getAll(
                { idEmpresa: this.idEmpresa }
            );
            this.provider.providersList.push(
                ...(await service.providers.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.project.projectsList.push(
                ...(await service.projects.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.wayToPay.listOfPaymentMethods.push(
                ...(await service.paymentMethods.getAll({
                     idEmpresa: this.idEmpresa
                }))
            );
            await console.log("ejecutado");
        } catch (error) {
            console.error(error.message);
        }
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
