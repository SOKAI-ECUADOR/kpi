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
        <ReporteriaAsientos 
        v-if="sections.selectedSection && sections.selectedSection.id === 2"
            @generateReport="generateAsientoDiarioReport"
        />
        <ReporteriaAnexo 
        v-if="sections.selectedSection && sections.selectedSection.id === 4"
            @generateReport="generateAnexoReport"
        />
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import Datepicker from "vuejs-datepicker";
import service from "./service";
import ReporteriaAsientos from "./components/AsientosContables";
import ReporteriaAnexo from "./components/AnexoSri";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        Datepicker,
        ReporteriaAsientos,
        ReporteriaAnexo
    },
    data() {
        return{
            sections: {
                sectionsList: [],
                selectedSection: null
            },
            /*sections: {
                sectionsList: [
                    {id:1,name:"Diario General"},
                    {id:2,name:"Mayor General"},
                    {id:3,name:"Balance de Comprobacion"},
                    {id:4,name:"Estado Situacion Financiera"},
                    {id:5,name:"Estado de Resultado Integral"}
                    ],
                selectedSection: null
            },*/
        };
    },
    methods:{
        async generateAsientoDiarioReport(e){
            const URL_FACTURA_DE_COMPRA = "/api/asientos-contables/reporte/diario_general";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.AsientoReport({
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
        async generateAnexoReport(e){
            const URL_FACTURA_DE_COMPRA = "/api/anexo_sri/ats/reporte";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.AsientoReport({
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
    },
    async mounted() {
        try {
            this.sections.sectionsList = await service.sections;
        } catch (error) {
            console.error(error.message);
        }
    }
}
</script>