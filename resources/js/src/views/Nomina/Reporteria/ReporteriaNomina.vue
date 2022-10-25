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
                <!--<div class="sm:w-full md:w-6/12 w-6/12" hidden>
                    <div class="sm:pr-0 md:pr-3 sm:w-full pr-3" v-if="sections.selectedSection && sections.selectedSection.id === 1">
                        
                        <datepicker
                            id="dt-initial"
                            :minimumView="'day'"
                            :maximumView="'year'"
                            v-model="searchParametersByDate.dateRange.initialDate"
                            placeholder="Fecha de Entrada"
                        ></datepicker>
                    </div>
                </div>-->
                <ReporteriaEmpleado
                    v-if="sections.selectedSection && sections.selectedSection.id === 1"
                    @generateReport="generateReport"
                />
            </div>
        </vx-card>  
    </div>  
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import Datepicker from "vuejs-datepicker";
import service from "./service";
import ReporteriaEmpleado from "./components/ReporteEmpleado";
export default {
   data() {
        return {
            sections: {
                sectionsList: [
                    {id:1 , name:"Empleado"},
                    {id:2 , name:"Rol de Pago General"},
                    {id:3 , name:"Rol de Pago Individual"},
                    {id:4 , name:"Rol de Provisiones"},
                    ],
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
        },
        usuario() {
            return this.$store.state.AppActiveUser;
        },
    },
    components: {
        AgGridVue,
        Datepicker,
        ReporteriaEmpleado
    },
    methods:{
        async generateReport(e) {
            const URL_EMPLEADOS = "/api/reporteempleado";
            try {
                let {
                    url,
                    nameFile
                } = await service.generateReport.purchaseInvoiceReport({
                    urlResource: URL_EMPLEADOS,
                    data: e
                });
                //console.log("URL:"+url);
                //console.log("Nombre:"+nameFile);
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
            //this.sections.sectionsList = await service.sections;
        } catch (error) {
            console.error(error.message);
        }
    }
}

</script>