<template>
    
         <vx-card>
        <!--<div class="flex flex-wrap mt-5 mb-5">
            <div class="w-1/4 flex">
                <label for="sections" v-if="dates.currentDate.active"
                    >Fecha de emision actual</label
                >
                <label for="sections" v-else>Fecha de emision en rango</label>
                <vs-switch class="ml-5" v-model="dates.currentDate.active" />
            </div>
        </div>-->
        <div class="flex flex-wrap mt-5 mb-5">
            <div class="sm:w-full md:w-6/12 w-6/12">
                <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                    <datepicker
                        id="dt-initial"
                        :minimumView="'day'"
                        :maximumView="'year'"
                        placeholder="Fecha de Entrada"
                        :language="es"
                        v-model="dates.currentDate.dateRange.initialDate"
                    ></datepicker>
                </div>
            </div>
            <!--<div class="sm:w-full md:w-6/12 w-6/12 md:ml-auto">
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
            </div>-->
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
                <label for="department">Departamento:</label>
                <v-select
                    id="department"
                    :options="department.departmentList"
                    label="nombre"
                    v-model="department.selectedDepartment"
                    placeholder="Seleccione el departamento"
                ></v-select>
                <!--<div v-show="error" v-if="!provider.selectedProvider">
                    <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pl-3 pr-3">
                <label for="user">Area Trabajo:</label>
                <v-select
                    id="user"
                    :options="user.usersList"
                    label="nombre"
                    v-model="user.selectedUser"
                    placeholder="Seleccione el vendedor"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pl-3 pr-3">
                <label for="cargo">Cargo:</label>
                <v-select
                    id="cargo"
                    :options="cargo.cargoList"
                    label="nombre"
                    v-model="cargo.selectedCargo"
                    placeholder="Seleccione la forma de pago"
                ></v-select>
                <!--<div v-show="error" v-if="!wayToPay.selectedPaymentMethod">
                    <div v-for="err in errorforma_pago" :key="err" v-text="err" class="text-danger"></div>
                </div>-->
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
            
        </div>
        <div class="flex flex-wrap">
            <div class="flex flex-wrap">
            <vs-button color="success" @click="sendData" type="filled"
                >Generar</vs-button
            >
        </div>
        </div>
    </vx-card>
</template>
<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
import { es } from 'vuejs-datepicker/dist/locale'
export default {
    data(){
        return{
            invoice: {
                all:true
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
            },
            department: {
                departmentList: [{ id: 0, nombre: "TODOS" }],
                selectedDepartment: { id: 0, nombre: "TODOS" }
            },
            cargo: {
                cargoList: [{ id: 0, nombre: "TODOS" }],
                selectedCargo: { id: 0, nombre: "TODOS" }
            },
            user: {
                usersList: [{ id: 0, nombre: "TODOS" }],
                selectedUser: { id: 0, nombre: "TODOS" }
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
    computed: {
        idEmpresa() {
            let user = this.$store.state.AppActiveUser;
            return user.id_empresa;
        }
    },
    components: {
        Datepicker
    },
    methods:{
        changeToDecimal() {
            if (this.invoice.totalCount != null) {
                this.invoice.totalCount = parseFloat(
                    this.invoice.totalCount
                ).toFixed(2);
            }
        },
        sendData() {
            let dates;
            if(this.dates.currentDate.dateRange.initialDate){
                dates= service.generateDate({ date: this.dates.currentDate.dateRange.initialDate });
            }else{
                dates= null;
            }
            let response = {
                all: this.invoice.all,
                dates:dates,
                company: this.idEmpresa,
                department: this.department.selectedDepartment,
                cargo:this.cargo.selectedCargo,
                area:this.user.selectedUser
            };
            this.$emit("generateReport", response);
        },
    },
    async mounted() {
        try {
            this.department.departmentList.push(...(await service.department.getAll({
                idEmpresa: this.idEmpresa
            })));
            this.user.usersList.push(...(await service.area.getAll({
                idEmpresa: this.idEmpresa
            })));
            this.cargo.cargoList.push(...(await service.cargo.getAll({
                idEmpresa: this.idEmpresa
            })));
            
            console.log("ejecutado");
            
        } catch (error) {
            console.error(error.message);
        }
    }
}
</script>