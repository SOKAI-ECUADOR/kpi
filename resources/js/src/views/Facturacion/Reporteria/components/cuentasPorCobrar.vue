<template>
    <vx-card>
        
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Tipo Reporte:</label>
                <v-select
                    id="project"
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
            
            <div class="w-1/5 flex">
                <label for="sections" v-if="dates.currentDate.active"
                    >Fecha de emision actual</label
                >
                <label for="sections" v-else>Fecha de emision en rango</label>
                <vs-switch class="ml-5" v-model="dates.currentDate.active" />
            </div>
            <div class="w-1/5 flex" v-if="tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id==3">
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
            
                <!-- <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                    <datepicker
                        id="dt-final"
                        :minimumView="'day'"
                        :maximumView="'year'"
                        placeholder="Fecha final"
                        :language="es"
                        v-model="dates.currentDate.dateRange.finalDate"
                    ></datepicker>
                </div> -->
                <div class="sm:w-full md:w-6/12 w-6/12" v-if="!dates.currentDate.active &&  tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id==5">
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
                    <div class="sm:w-full md:w-6/12 w-6/12 md:ml-auto" v-if="!dates.currentDate.active &&  tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id==5">
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
                    <div class="sm:w-full md:w-full w-full md:ml-auto" v-else-if="!dates.currentDate.active &&  tipo_reporte.selectedReporte && tipo_reporte.selectedReporte.id!==5">
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
        <vs-divider border-style="solid" color="dark"></vs-divider>
        <div class="vx-row">
            <div class="vx-col sm:w-1/4 w-full mb-6">
                
                    <label for="clients">Cliente</label>
                    <v-select
                        id="clients"
                        :options="clients.clientsList"
                        label="nombre"
                        v-model="clients.selectedClient"
                        placeholder="Seleccione el cliente"
                    ></v-select>
                
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6">
                <label for="project">Forma de pago:</label>
                <v-select
                    id="project"
                    :options="wayToPay.listOfPaymentMethods"
                    label="nombre"
                    v-model="wayToPay.selectedPaymentMethod"
                    placeholder="Seleccione la forma de pago"
                ></v-select>
                <!--<div v-show="error" v-if="!wayToPay.selectedPaymentMethod">
                    <div v-for="err in errorforma_pago" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6" v-if="usuario.id_rol!=2">
                <label for="sellers">Usuario:</label>
                <v-select
                    id="sellers"
                    :options="user.usersList"
                    label="fullname"
                    v-model="user.selectedUser"
                    placeholder="Seleccione el vendedor"
                ></v-select>
            </div>
            <div class="vx-col sm:w-1/4 w-full mb-6" >
                <label for="sellers">Vendedor:</label>
                <v-select
                    id="sellers"
                    :options="Vendedor.VendedorList"
                        label="fullname"
                        v-model="Vendedor.selectedVendedor"
                        placeholder="Seleccione el Vendedor"
                    >
                </v-select>
            </div>
        </div>
        <div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
            <label class="mr-2 ml-2">Con valor en cuotas</label>
            <vs-radio
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="1"
                >mayor</vs-radio
            >
            <vs-radio
                class="mr-2 ml-2"
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="0"
                >igual</vs-radio
            >
            <vs-radio
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="-1"
                >menor</vs-radio
            >
            <vs-radio
                class="ml-2"
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="2"
                >todas</vs-radio
            >
            <label
                v-if="invoice.typeSearchSales != 2 && invoice.typeSearchSales"
                class="ml-2"
                >a:</label
            >
            <vs-input
                class="ml-4"
                maxlength="7"
                v-if="invoice.typeSearchSales != 2 && invoice.typeSearchSales"
                v-model="invoice.totalCountSales"
                @keypress="onlyNumbers($event)"
                @blur="changeToDecimal"
                placeholder=" $ "
            />
        </div>
        
        <!--<div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
            <label class="mr-2 ml-2">Con valor en deudas</label>
            <vs-radio
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="1"
                >mayor</vs-radio
            >
            <vs-radio
                class="mr-2 ml-2"
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="0"
                >igual</vs-radio
            >
            <vs-radio
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="-1"
                >menor</vs-radio
            >
            <vs-radio
                class="ml-2"
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="2"
                >todas</vs-radio
            >
            <label
                v-if="invoice.typeSearchDebts != 2 && invoice.typeSearchDebts"
                class="ml-2"
                >a:</label
            >
            <vs-input
                class="ml-4"
                maxlength="7"
                v-if="invoice.typeSearchDebts != 2 && invoice.typeSearchDebts"
                v-model="invoice.totalCountDebts"
                @keypress="onlyNumbers($event)"
                @blur="changeToDecimal"
                placeholder=" $ "
            />
        </div>-->
        <br>
        <!--<div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="establishment">Establecimiento:</label>
                <v-select
                    id="establishment"
                    :options="invoice.establishment.establishmentsList"
                    label="name"
                    v-model="invoice.establishment.selectedEstablishment"
                    @input="searchByEstablishment"
                    placeholder="Seleccione el establecimiento"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pr-3 pl-3">
                <label for="pointOfEmission">Punto de emision:</label>
                
                <v-select
                    id="pointOfEmission"
                    :options="
                        invoice.pointOfEmission
                            .pointsOfEmissionListByEstablishment
                    "
                    label="name"
                    v-model="invoice.pointOfEmission.selectedPointOfEmission"
                    placeholder="Seleccione el punto de emision"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Proyecto:</label>
                <v-select
                    id="project"
                    :options="invoice.project.projectsList"
                    label="name"
                    v-model="invoice.project.selectedProject"
                    placeholder="Seleccione el proyecto"
                ></v-select>
            </div>
        </div>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-6/12 w-6/12 mb-6">
                <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                    <label for="clients">Cliente</label>
                    <v-select
                        id="clients"
                        :options="clients.clientsList"
                        label="nombre"
                        v-model="clients.selectedClient"
                        placeholder="Seleccione el cliente"
                    ></v-select>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
            <label class="mr-2 ml-2">Con valor en ventas</label>
            <vs-radio
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="1"
                >mayor</vs-radio
            >
            <vs-radio
                class="mr-2 ml-2"
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="0"
                >igual</vs-radio
            >
            <vs-radio
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="-1"
                >menor</vs-radio
            >
            <vs-radio
                class="ml-2"
                v-model="invoice.typeSearchSales"
                vs-name="invoice"
                vs-value="2"
                >todas</vs-radio
            >
            <label
                v-if="invoice.typeSearchSales != 2 && invoice.typeSearchSales"
                class="ml-2"
                >a:</label
            >
            <vs-input
                class="ml-4"
                maxlength="7"
                v-if="invoice.typeSearchSales != 2 && invoice.typeSearchSales"
                v-model="invoice.totalCountSales"
                @keypress="onlyNumbers($event)"
                @blur="changeToDecimal"
                placeholder=" $ "
            />
        </div>
        <div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
            <label class="mr-2 ml-2">Con valor en deudas</label>
            <vs-radio
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="1"
                >mayor</vs-radio
            >
            <vs-radio
                class="mr-2 ml-2"
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="0"
                >igual</vs-radio
            >
            <vs-radio
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="-1"
                >menor</vs-radio
            >
            <vs-radio
                class="ml-2"
                v-model="invoice.typeSearchDebts"
                vs-name="typeSearchDebts"
                vs-value="2"
                >todas</vs-radio
            >
            <label
                v-if="invoice.typeSearchDebts != 2 && invoice.typeSearchDebts"
                class="ml-2"
                >a:</label
            >
            <vs-input
                class="ml-4"
                maxlength="7"
                v-if="invoice.typeSearchDebts != 2 && invoice.typeSearchDebts"
                v-model="invoice.totalCountDebts"
                @keypress="onlyNumbers($event)"
                @blur="changeToDecimal"
                placeholder=" $ "
            />
        </div>
        <div class="flex flex-wrap">
            <vs-button color="success" @click="sendData" type="filled"
                >Generar</vs-button
            >
        </div>-->
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
    </vx-card>
</template>

<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
import { es } from 'vuejs-datepicker/dist/locale';
const axios = require("axios");
export default {
    name: "cuentas-por-cobrar",
    data() {
        return {
            clients: {
                clientsList: [{ id: 0, nombre: "TODOS" }],
                selectedClient: { id: 0, nombre: "TODOS" }
            },
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
            tipo_reporte: {
                reporteList: [{ id: 1, nombre: "Cliente" },
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
            Vendedor: {
                VendedorList: [{ id: 0, fullname: "TODOS" }],
                selectedVendedor: { id: 0, fullname: "TODOS" }
            },
            invoice: {
                totalCountSales: null,
                typeSearchSales: 2,
                totalCountDebts: null,
                typeSearchDebts: 2,
                establishment: {
                    establishmentsList: [{ id: 0, name: "TODOS" }],
                    selectedEstablishment: { id: 0, name: "TODOS" }
                },
                pointOfEmission: {
                    pointsOfEmissionList: [],
                    pointsOfEmissionListByEstablishment: [],
                    selectedPointOfEmission: null
                },
                project: {
                    projectsList: [{ id: 0, name: "TODOS" }],
                    selectedProject: { id: 0, name: "TODOS" }
                }
            },
            activectn2:false,
            destinatario:"",
            email:"",
            vencido_reporte:null
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
            if (
                this.invoice.totalCountSales != null &&
                this.invoice.totalCountSales != NaN
            ) {
                this.invoice.totalCountSales = parseFloat(
                    this.invoice.totalCountSales
                ).toFixed(2);
            }
            if (
                this.invoice.totalCountDebts != null &&
                this.invoice.totalCountDebts != NaN
            ) {
                this.invoice.totalCountDebts = parseFloat(
                    this.invoice.totalCountDebts
                ).toFixed(2);
            }
        },
        abrirmodal(){
            this.activectn2=true;
        },
        enviaremail(){
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
            if (dates.range) {
                let initial = dates.range.initial;
                let final = dates.range.final;
                dates.range.initial = initial > final ? final : initial;
                dates.range.final = final < initial ? initial : final;
            }
            
            let response={
                reporte:this.tipo_reporte.selectedReporte,
                wayToPay:this.wayToPay.selectedPaymentMethod,
                user:this.user.selectedUser,
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                date:dates,
                client:
                    this.clients.selectedClient &&
                    this.clients.selectedClient.id == 0
                        ? null
                        : this.clients.selectedClient,
                totalCountSales:
                    this.invoice.typeSearchSales != 2
                        ? this.invoice.totalCountSales
                        : "ALL",
                typeSearchSalesTotalCount: this.invoice.typeSearchSales,
                totalCountDebts:
                    this.invoice.typeSearchDebts != 2
                        ? this.invoice.totalCountDebts
                        : "ALL",
                typeSearchDebtsTotalCount: this.invoice.typeSearchDebts,
                selectedEstablishment:
                    this.invoice.establishment.selectedEstablishment &&
                    this.invoice.establishment.selectedEstablishment.id == 0
                        ? null
                        : this.invoice.establishment.selectedEstablishment,
                selectedPointOfEmission:
                    this.invoice.pointOfEmission.selectedPointOfEmission &&
                    this.invoice.pointOfEmission.selectedPointOfEmission.id == 0
                        ? null
                        : this.invoice.pointOfEmission.selectedPointOfEmission,
                selectedProject:
                    this.invoice.project.selectedProject &&
                    this.invoice.project.selectedProject.id == 0
                        ? null
                        : this.invoice.project.selectedProject,
                email:this.email,
                destinatario:this.destinatario
            }
            axios({
                url: "/api/reportes/cuentas-por-cobrar",
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
            console.log("P_S::", this.invoice.project.selectedProject);
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
                reporte:this.tipo_reporte.selectedReporte,
                wayToPay:this.wayToPay.selectedPaymentMethod,
                user:this.user.selectedUser,
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                date:dates,
                client:
                    this.clients.selectedClient &&
                    this.clients.selectedClient.id == 0
                        ? null
                        : this.clients.selectedClient,
                totalCountSales:
                    this.invoice.typeSearchSales != 2
                        ? this.invoice.totalCountSales
                        : "ALL",
                typeSearchSalesTotalCount: this.invoice.typeSearchSales,
                totalCountDebts:
                    this.invoice.typeSearchDebts != 2
                        ? this.invoice.totalCountDebts
                        : "ALL",
                typeSearchDebtsTotalCount: this.invoice.typeSearchDebts,
                selectedEstablishment:
                    this.invoice.establishment.selectedEstablishment &&
                    this.invoice.establishment.selectedEstablishment.id == 0
                        ? null
                        : this.invoice.establishment.selectedEstablishment,
                selectedPointOfEmission:
                    this.invoice.pointOfEmission.selectedPointOfEmission &&
                    this.invoice.pointOfEmission.selectedPointOfEmission.id == 0
                        ? null
                        : this.invoice.pointOfEmission.selectedPointOfEmission,
                selectedProject:
                    this.invoice.project.selectedProject &&
                    this.invoice.project.selectedProject.id == 0
                        ? null
                        : this.invoice.project.selectedProject,
                rol_user:this.usuario.id_rol,
                usuario:this.usuario.id,
                user_name:this.usuario.nombres,
                vendedor:this.Vendedor.selectedVendedor,
                vencido_reporte:this.vencido_reporte
            };
            console.log("R_P::", response);
            this.$emit("generateReport", response);
        },
        searchByEstablishment(establishment) {
            if (establishment !== null) {
                this.invoice.pointOfEmission.pointsOfEmissionListByEstablishment = [
                    { id: 0, name: "TODOS" }
                ];
                let lista =
                    establishment.id == 0
                        ? this.invoice.pointOfEmission.pointsOfEmissionList
                        : this.invoice.pointOfEmission.pointsOfEmissionList.filter(
                              point => point.establishment === establishment.id
                          );
                lista.length != 1
                    ? this.invoice.pointOfEmission.pointsOfEmissionListByEstablishment.push(
                          ...lista
                      )
                    : (this.invoice.pointOfEmission.pointsOfEmissionListByEstablishment = lista);
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
            this.clients.clientsList.push(
                ...(await service.clients.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            
            // this.user.usersList.push(
            //     ...(await service.users.getAll({
            //         idEmpresa: this.idEmpresa
            //     }))
            // );
            if(this.usuario.id_rol!==2){
                this.user.usersList.push(
                    ...(await service.users.getAll({
                        idEmpresa: this.idEmpresa
                    }))
                );
            }else{
                this.user.usersList.push(
                    ...(await service.sellers_usu.getAll({
                        idEmpresa: this.usuario.id
                    }))
                );
            }
            if(this.usuario.id_rol!==2){
                this.Vendedor.VendedorList.push(
                    ...(await service.sellers.getAll({
                        idEmpresa: this.idEmpresa
                    }))
                );
            }else{
                this.Vendedor.VendedorList.push(
                    ...(await service.sellers_usu.getAll({
                        idEmpresa: this.usuario.id
                    }))
                );
            }
            this.wayToPay.listOfPaymentMethods.push(
                ...(await service.paymentMethods.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.invoice.project.projectsList.push(
                ...(await service.projects.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
        } catch (error) {
            console.error(error.message);
        }
    }
};
</script>
