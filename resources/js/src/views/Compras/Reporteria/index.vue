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
        </vx-card>
        <ReporteriaFacturaVenta
            v-if="sections.selectedSection && sections.selectedSection.id === 3"
            @generateReport="generatePurchaseInvoiceReport"
            @onlyNumbers="onlyNumbers"
        />
            <!-- v-if="sections.selectedSection && sections.selectedSection.id === 7" -->
        <ReporteriaCuentasPorPagar
        v-if="sections.selectedSection && sections.selectedSection.id === 7"
            @generateReport="generateCuentasPagarReport"
            @onlyNumbers="onlyNumbers"
            
        />
        <ReporteriaProveedor
        v-if="sections.selectedSection && sections.selectedSection.id === 1"
            @generateReport="generateProveedorReport"
            @onlyNumbers="onlyNumbers"
            
        />
        <ReporteriaOrdenCompra
        v-if="sections.selectedSection && sections.selectedSection.id === 2"
           @generateReport="generateOrdencompraReport" 
            
        />
        <ReporteriaNotaCreditoCompra
        v-if="sections.selectedSection && sections.selectedSection.id === 5"
           @generateReport="generateNotaCreditoCompraReport" 
            
        />
        <ReporteriaLiquidacionCompra
        v-if="sections.selectedSection && sections.selectedSection.id === 4"
           @generateReport="generateLiquidacionCompraReport" 
            
        />
        
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import Datepicker from "vuejs-datepicker";
import service from "./service";
import ReporteriaFacturaVenta from "./components/facturaCompra";
import ReporteriaCuentasPorPagar from "./components/cuentasPorPagar";
import ReporteriaProveedor from "./components/Proveedor";
import ReporteriaOrdenCompra from "./components/Ordencompra";
import ReporteriaLiquidacionCompra from "./components/LiquidacionCompra";
import ReporteriaNotaCreditoCompra from "./components/NotaCreditoCompra";
const axios = require("axios");
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
        /*async enviaremail(e){
            
            const URL_FACTURA_DE_COMPRA = "/api/reporte/cuenta_pagar";
            try {
                await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
                    data: e
                });
                //this.downloadFile(url, nameFile);
                e.email="";
            } catch (error) {
                console.error("ERROR::", error);
                this.$vs.notify({
                    time: 5000,
                    text:error
                });
            }
        },*/
        async generatePurchaseInvoiceReport(e) {
            const URL_FACTURA_DE_COMPRA = "/api/reportes/compra";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
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
        async generateNotaCreditoCompraReport(e) {
            const URL_FACTURA_DE_COMPRA = "/api/reportes/nota_credito_compra";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
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
        async generateLiquidacionCompraReport(e) {
            const URL_FACTURA_DE_COMPRA = "/api/reportes/liquidacion_compra";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
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
         async generateCuentasPagarReport(e) {
            const URL_FACTURA_DE_COMPRA = "/api/reporte/cuenta_pagar";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
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
        async generateProveedorReport(e) {
            const URL_FACTURA_DE_COMPRA = "/api/reporte/proveedor";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
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
        async generateOrdencompraReport(e) {
            const URL_FACTURA_DE_COMPRA = "/api/reporte/orden_compra";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
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
        
        async generateAccountsPayableReport(e) {
            const URL_FACTURA_DE_COMPRA = "/api/reportes/compra";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_FACTURA_DE_COMPRA,
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
        ReporteriaCuentasPorPagar,
        ReporteriaProveedor,
        ReporteriaOrdenCompra,
        ReporteriaLiquidacionCompra,
        ReporteriaNotaCreditoCompra
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
