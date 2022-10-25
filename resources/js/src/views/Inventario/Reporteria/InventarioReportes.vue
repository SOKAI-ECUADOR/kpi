<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap">
                <div class="w-full mb-6">
                    <label for="sections">Reporte de:</label>
                    <v-select
                        id="sections"
                        :options="sections.sectionsList"
                        label="name"
                        v-model="sections.selectedSection"
                        placeholder="Seleccione la sección"
                    ></v-select>
                </div>
            </div>
            <!-- <div class="flex flex-wrap mb-2" v-if="sections.selectedSection && sections.selectedSection.id !== 7 && sections.selectedSection.id !== 8">
                <label for="sections" v-if="searchParametersByDate.currentDate"
                    >Fecha actual</label
                >
                <label for="sections" v-else>En rango</label>
                <vs-switch
                    v-model="searchParametersByDate.currentDate"
                    class="ml-5"
                />
            </div>
            <div
                class="flex flex-wrap"
                v-if="!searchParametersByDate.currentDate "
            >
                <div class="sm:w-full md:w-6/12 w-6/12" >
                    <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                        <datepicker
                            id="dt-initial"
                            :minimumView="'day'"
                            :maximumView="'year'"
                            v-model="searchParametersByDate.dateRange.initialDate"
                            placeholder="Fecha de inicio"
                        ></datepicker>
                    </div>
                </div>
                <div class="sm:w-full md:w-1/3 w-1/3 md:ml-auto">
                    <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                        <datepicker
                            id="dt-final"
                            :minimumView="'day'"
                            :maximumView="'year'"
                            v-model="searchParametersByDate.dateRange.finalDate"
                            placeholder="Fecha final"
                        ></datepicker>
                    </div>
                </div>
            </div>-->
        </vx-card>

        <Kardex
            v-if="sections.selectedSection && sections.selectedSection.id === 3"
            @generateReport="generateKardex"
        />
        <Inventario
            v-if="sections.selectedSection && sections.selectedSection.id === 4"
            @generateReport="generateInventario"
        />
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import Datepicker from "vuejs-datepicker";
import service from "./service";
const axios = require("axios");
import Kardex from "./components/Kardex";
import Inventario from "./components/Inventario";
export default {
    data() {
        return {
            sections: {
                sectionsList: [],
                selectedSection: null
            },
            searchParametersByDate: {
                dateRange: {
                    initialDate: null,
                    finalDate: null
                },
                currentDate: true
            }
        };
    },
    computed: {
        idEmpresa() {
            let user = this.$store.state.AppActiveUser;
            return user.id_empresa;
        }
    },
    methods: {
        getDate(dataForReport) {
            if (this.searchParametersByDate.currentDate) {
                let date = new Date();
                dataForReport.date = service.generateDate({ date: date });
                dataForReport.currentDate = true;
            } else {
                let initial = new Date(
                    this.searchParametersByDate.dateRange.initialDate ||
                        new Date()
                );
                let final = new Date(
                    this.searchParametersByDate.dateRange.finalDate ||
                        new Date()
                );
                console.log(
                    "fecha inicial: " + initial + " fecha final: " + final
                );
                let initialTemp = initial;
                let finalTemp = final;
                initial = initialTemp > finalTemp ? finalTemp : initialTemp;
                final = finalTemp < initialTemp ? initialTemp : finalTemp;
                console.log(
                    "fecha inicial despues: " +
                        initial +
                        " fecha final despues: " +
                        final
                );
                dataForReport.date = {
                    initialDate: service.generateDate({ date: initial }),
                    finalDate: service.generateDate({ date: final })
                };
                dataForReport.currentDate = false;
            }
        },
        downloadFile(url, nameFile) {
            console.log("llego a descargar");
            const link = document.createElement("a");
            link.href = url;
            link.setAttribute("download", nameFile);
            document.body.appendChild(link);
            link.click();
            this.$vs.notify({
                title: "Reporte Generado",
                text: "Su reporte esta siendo descargado exitosamente!",
                color: "success"
            });
        },
        async generateKardex(e) {
            const URL_KARDEX = "/api/reportes/kardex";
            window.open("/api/reportes/kardex?data="+JSON.stringify(e));
        },
        async generateInventario(e){
            console.log("0 parte llega aqui el pdf");
            const URL_FACTURAS = "/api/reporte/inventario_bodega";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                console.log("1 parte llega aqui el pdf");
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::Inventario", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        onlyNumbers($event) {
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
    components: {
        AgGridVue,
        Datepicker,
        Kardex,
        Inventario
    },
    async mounted() {
        try {
            this.sections.sectionsList = await service.sections;
        } catch (error) {
            console.error(error.message);
        }
    }
};
</script>
