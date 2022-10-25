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
            <div class="flex flex-wrap mb-2" v-if="sections.selectedSection && sections.selectedSection.id !== 7 && sections.selectedSection.id !== 8">
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
                v-if="!searchParametersByDate.currentDate && sections.selectedSection && sections.selectedSection.id !== 7 && sections.selectedSection.id !== 8"
            >
                <div class="sm:w-full md:w-6/12 w-6/12" >
                    <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                        <!-- prettier-ignore -->
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
            </div>
        </vx-card>
        <ReporteriaFacturaVenta
            v-if="sections.selectedSection && sections.selectedSection.id === 4"
            @generateReport="generateReport"
            @onlyNumbers="onlyNumbers"
            
        />
        <!-- @generateExport="generateExportFactura" -->
        <Proforma
            v-if="sections.selectedSection && sections.selectedSection.id === 3"
            @generateReport="generateReportProforma"
            @onlyNumbers="onlyNumbers"
        />
        <Vendedor
            v-if="sections.selectedSection && sections.selectedSection.id === 2"
            @generateReport="generateReportVendedor"
        />
        <CuentasPorCobrar
            v-if="sections.selectedSection && sections.selectedSection.id === 8"
            @generateReport="generateReportAccountsReceivable"
            @onlyNumbers="onlyNumbers"
        />
        <ListaPrecio
            v-if="sections.selectedSection && sections.selectedSection.id === 10"
            @generateReport="generateReportListaPrecio"
        />
        <NotaCredito
            v-if="sections.selectedSection && sections.selectedSection.id === 6"
            @generateReport="generateReportNotaCredito"
        />
        <NotaVenta
            v-if="sections.selectedSection && sections.selectedSection.id === 5"
            @generateReport="generateReportNotaVenta"
        />
        <CierreCaja
            v-if="sections.selectedSection && sections.selectedSection.id === 11"
            @generateReport="generateReportCierreCaja"
        />
        <CheckList
            v-if="sections.selectedSection && sections.selectedSection.id === 12"
            @generateReport="generateReportCheckList"
        />
        <GuiaRemision
            v-if="sections.selectedSection && sections.selectedSection.id === 9"
            @generateReport="generateReportGuia"
        />
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import Datepicker from "vuejs-datepicker";
import service from "./service";
import ReporteriaFacturaVenta from "./components/facturaVenta";
import Proforma from "./components/proforma";
import CuentasPorCobrar from "./components/cuentasPorCobrar";
import ListaPrecio from "./components/ListaPrecio";
import NotaCredito from "./components/NotaCredito";
import NotaVenta from "./components/NotaVenta";
import CierreCaja from "./components/CierreCaja";
import CheckList from "./components/CheckList";
import GuiaRemision from "./components/GuiaRemision";
import Vendedor from './components/vendedor.vue';
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
                console.log("fecha inicial: "+initial+" fecha final: "+final);
                let initialTemp = initial;
                let finalTemp = final;
                //initial = initialTemp > finalTemp ? finalTemp : initialTemp;
                //final = finalTemp < initialTemp ? initialTemp : finalTemp;
                console.log("fecha inicial despues: "+initialTemp+" fecha final despues: "+finalTemp);
                dataForReport.date = {
                    initialDate: service.generateDate({ date: initialTemp }),
                    finalDate: service.generateDate({ date: finalTemp })
                };
                console.log("fechas"+JSON.stringify(dataForReport.date));
                dataForReport.currentDate = false;
            }
        },
        downloadFile(url, nameFile) {
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
        async generateReport(e) {
            let URL_FACTURAS;
            if(e.ProductoVSCostos){
                URL_FACTURAS = "/api/reportes/productovscostos";
            }
            else if(e.FacturaVSCostos){
                URL_FACTURAS = "/api/reportes/facturavscostos";
            }
            else{
                URL_FACTURAS = "/api/reportes/factura";
            }
            
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportGuia(e) {
            const URL_FACTURAS = "/api/reportes/guia_remision";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportCierreCaja(e) {
            const URL_FACTURAS = "/api/reportes/cierre_caja";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportCheckList(e) {
            const URL_FACTURAS = "/api/reportes/check_list";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateExportFactura(e) {
            const URL_FACTURAS = "/api/excel/exportar/factura";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.ExcelFactura.ExcelFacturaVenta({
                    urlResource: URL_FACTURAS,
                    datos: e
                });
                //this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportNotaVenta(e) {
            const URL_FACTURAS = "/api/reportes/nota_venta";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportNotaCredito(e) {
            const URL_FACTURAS = "/api/reportes/nota_credito";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportProforma(e) {
            const URL_PROFORMA = "/api/reportes/factura?modo=0";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.proformaReport({
                    urlResource: URL_PROFORMA,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportVendedor(e) {
            const URL_VENDEDOR = "/api/reportes/vendedor";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_VENDEDOR,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportAccountsReceivable(e) {
            const URL_CUENTAS_POR_COBRAR = "/api/reportes/cuentas-por-cobrar";
            try {
                //this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.accountsReceivableReport({
                    urlResource: URL_CUENTAS_POR_COBRAR,
                    data: e
                });
                this.downloadFile(url, nameFile);
                
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    ...error
                });
            }
        },
        async generateReportListaPrecio(e) {
            const URL_FACTURAS = "/api/reportes/listaprecio";
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL_FACTURAS,
                    data: e
                });
                this.downloadFile(url, nameFile);
            } catch (error) {
                console.error("ERROR::", error);
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
        ReporteriaFacturaVenta,
        Proforma,
        CuentasPorCobrar,
        ListaPrecio,
        NotaCredito,
        NotaVenta,
        CierreCaja,
        CheckList,
        GuiaRemision,
        Vendedor
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
