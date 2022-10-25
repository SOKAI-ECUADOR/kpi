<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Generar Proforma">
                <div class="vx-row">
                    
                    
                    <div class="vx-col w-full">
                        <vs-button
                            color="success"
                            type="filled"
                            @click="generateReport({})"
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            >CANCELAR</vs-button
                        >
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
import service from "./service";
export default {
    data() {
        return {
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
    },
    props: {  },
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
            let URL = "/api/reportes/proformasistema";
            e.id_empresa = JSON.parse(localStorage.getItem("userInfo")).id_empresa;
            try {
                this.getDate(e);
                let {
                    url,
                    nameFile
                } = await service.generateReport.invoiceReport({
                    urlResource: URL,
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
        }
    },
    mounted() {

    }
};
</script>
<style lang="scss">

</style>