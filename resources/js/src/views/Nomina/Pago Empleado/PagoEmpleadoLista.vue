<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1, buscar, '', '')"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container mr-3" v-if="crearrol">
                        <!--@click="abrirModal()"-->
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/nomina/pago-empleado/agregar"
                            >Agregar</vs-button
                        >
                    </div>
                </div>
            </div>
            <br>
        </vx-card>
    </div>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        flatPickr,
        FormWizard,
        TabContent
    },
    data(){
        return{
            buscar:"",
            criterio:"",
            cantidadp:"",
            i18nbuscar: this.$t("i18nbuscar"),
        };
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        crearrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Activos fijos"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            //console.log(res+"Rol");
            return res;
            
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Activos fijos"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarroles() {
        var res = 0;
                // if (this.usuario.id_rol == 1) {
                //     res = 1;
                // } else {
                //     res = this.$store.state.Roles[15].eliminar;
                // }
                if (this.usuario.id_rol == 1) {
                    res = 1;
                } else {
                    this.$store.state.Roles.forEach(el => {
                        if(el.nombre == "Activos fijos"){
                            res = el.eliminar;
                            return res;
                        }
                    });
                }
                return res;
        },
    },
    methods:{
        listar(page,buscar,cantidad,otro){
            // let me = this;
            // var url =
            //     "/api/activo_fijo/" +
            //     this.usuario.id_empresa +
            //     "?page=" +
            //     page +
            //     "&buscar=" +
            //     buscar;
            // axios
            //     .get(url)
            //     .then(function(response) {
            //         var respuesta = response.data;
            //         me.contenido = respuesta.recupera;
            //     })
            //     .catch(function(error) {
            //         console.log(error);
            //     });
        },
    }
    
}
</script>