<template>
    <vx-card>
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
        <vs-divider border-style="solid" color="dark"></vs-divider>
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
                <!--<div v-show="error" v-if="!provider.selectedProvider">
                    <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
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
                <!--<div v-show="error" v-if="!wayToPay.selectedPaymentMethod">
                    <div v-for="err in errorforma_pago" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
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
        <div class="vx-col w-full">
            <vs-button
                class="mt-5"
                color="success"
                @click="sendData"
                type="filled"
                >Generar  </vs-button
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
    name: "reporteria-orden-compra",
    data() {
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
            provider: {
                providersList: [{ id: 0, nombre: "TODOS" }],
                selectedProvider: { id: 0, nombre: "TODOS" }
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
            if (dates.range) {
                let initial = dates.range.initial;
                let final = dates.range.final;
                dates.range.initial = initial > final ? final : initial;
                dates.range.final = final < initial ? initial : final;
            }
            let response = {
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                dates: dates,
                
                provider: this.provider.selectedProvider,
                wayToPay: this.wayToPay.selectedPaymentMethod,
                user: this.user.selectedUser,
                invoice: this.invoice,
                
               
                email:null,
                destinatario:null
            };
            this.$emit("generateReport", response);
        },
    },
    async mounted() {
        try {
            this.user.usersList.push(...(await service.users.getAll({
                idEmpresa: this.idEmpresa
            })));
    
            /*this.pointOfEmission.pointsOfEmissionList = await service.pointsOfEmission.getAll(
                { idEmpresa: this.idEmpresa }
            );*/
            this.provider.providersList.push(
                ...(await service.providers.getAll({
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