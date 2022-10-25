<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap">
                <div class="vx-col sm:w-1/6 mb-3 pr-3">
                    <label for="sections">Año de consulta:</label> <br> 
                    <vs-select placeholder="Año"  class="selectExample w-full mt-5" vs-multiple autocomplete v-model="anio">
                        <vs-select-item v-for="tr in anios" :key="tr" :value="tr" :text="tr" />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/6 mb-3 pr-3">
                    <label for="sections">Periodo:</label> <br>
                    <vs-select placeholder="Periodo" @change="cambiarPeriodo()"  class="selectExample w-full mt-5" vs-multiple autocomplete v-model="periodo">
                        <vs-select-item value="1" text="Mensual" />
                        <vs-select-item value="2" text="Semestral" />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/6 mb-3 pr-3" v-if="esMensual">
                    <label for="sections">Mes de consulta:</label> <br>
                    <vs-select placeholder="Mes"  class="selectExample w-full mt-5" vs-multiple autocomplete v-model="mes">
                        <vs-select-item v-for="tr in 12" :key="tr" :value="tr" :text="tr" />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/6 mb-3 pr-3" v-else>
                    <label for="sections">Semestre:</label> <br>
                    <vs-select placeholder="Periodo"  class="selectExample w-full mt-5" vs-multiple autocomplete v-model="semestre">
                        <vs-select-item value="1" text="Primer Semestre" />
                        <vs-select-item value="2" text="Segundo Semestre" />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/3 mb-3">
                    <label for="sections">Descargar reporte:</label> <br>
                    <vs-button class="btnx w-100 mt-5" @click="descargar()">Descargar Anexo Transaccional</vs-button>
                </div>
            </div>
        </vx-card>
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");

const axios = require("axios");
export default {
    data(){
        return {
            anio:"",
            mes:"",
            periodo: 1,
            semestre: 1,
            esMensual: true,
            anios:[]
        }
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
    },
    methods:{
        descargar(){
            let mesesConsulta = 0;

            if(this.periodo == 1){
                mesesConsulta = this.mes + "-" + this.mes;
            }

            else{
                if(this.semestre == 1){
                    mesesConsulta = "1-6";
                }

                else{
                    mesesConsulta = "7-12";
                }
            }

            this.$vs.notify({
                    time: 8000,
                    color: "warning",
                    title: "Generando informe",
                    text: "El anexo transaccional se esta generando, esto puede tardar"
                });
            axios.get("/api/transaccional/", {params:{
                empresa: this.usuario.id_empresa,
                anio: this.anio,
                mes: mesesConsulta
            }}).then(({data}) => {
                console.log(data);
                this.$vs.notify({
                    time: 8000,
                    color: "success",
                    title: "Descarga realizada",
                    text: "El anexo transaccional se descargo exitosamente"
                });
                window.open('/'+this.usuario.id_empresa+'/anexto_transaccional/decarga/'+this.anio+'/'+mesesConsulta, '_top');
            }).catch( error => {
                console.log(error);
            });
        },
        cambiarPeriodo(){
            if(this.periodo == 1){
                this.esMensual = true;
            }
            else{
                this.esMensual = false;
            }
        }
    },
    mounted(){
        for(var i=moment().format('Y'); i>=2000; i--){
            this.anios.push(i);
        }
        this.anio = moment().format('Y');
        this.mes = moment().format('MM');
    }
}
</script>