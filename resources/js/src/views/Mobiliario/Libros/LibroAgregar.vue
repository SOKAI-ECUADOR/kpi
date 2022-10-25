<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Agregar Libro">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="tipo_activo"
                            label="Tipo Activo"
                        >
                            <vs-select-item
                                v-for="datos in contenidotipoactivo"
                                :value="datos.id_tipo_activo_mobiliario"
                                :text="datos.descripcion_tipo_activo"
                                :key="datos.id_tipo_activo_mobiliario"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Código Identificacion"
                            v-model="codigo_identificacion"
                        />
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Código Anterior"
                            v-model="codigo_anterior"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Titulo de la Obra Anterior"
                            v-model="titulo_anterior"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Titulo de la Obra Actualizada"
                            v-model="titulo_actualizado"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Editorial"
                            v-model="editorial"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="conservacion"
                            label="Estado de Conservación"
                        >
                            <vs-select-item
                                v-for="datos in contenidoconservacion"
                                :value="datos.id_conservacion"
                                :text="datos.descripcion_conservacion"
                                :key="datos.id_conservacion"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="mantenimiento"
                            label="Mantenimiento"
                        >
                            <vs-select-item
                                v-for="datos in contenidomantenimiento"
                                :value="datos.id_mantenimiento"
                                :text="datos.descripcion_mantenimiento"
                                :key="datos.id_mantenimiento"
                            />
                        </vs-select>
                    </div>
                    <div class="sm:w-full md:w-1/5 w-1/5" >
                        <datepicker
                            v-model="fechacompra"
                            placeholder="Fecha Compra"
                        ></datepicker>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            label="Costo Adquisición:"
                            v-model="costoadquisicion"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="ubicaciongeneral"
                            label="Ubicación General"
                        >
                            <vs-select-item
                                v-for="datos in contenidoubicaciongeneral"
                                :value="datos.id_ubicacion_general"
                                :text="datos.descripcion_ubicacion_general"
                                :key="datos.id_ubicacion_general"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="ubicacionespecifica"
                            label="Ubicación Especifica"
                        >
                            <vs-select-item
                                v-for="datos in contenidoubicacionespecifica"
                                :value="datos.id_ubicacion_especifica"
                                :text="datos.descripcion_ubicacion_especifica"
                                :key="datos.id_ubicacion_especifica"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="custodio"
                            label="Custodio"
                        >
                            <vs-select-item
                                v-for="datos in contenidocustodio"
                                :value="datos.id_custodio"
                                :text="datos.nombre_custodio"
                                :key="datos.id_custodio"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Cuenta Contable"
                            v-model="cuentacontable"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Observaciones"
                            v-model="observaciones"
                        />
                    </div>
                    
                    
                    

                    


                    <div class="vx-col w-full">
                        <vs-button
                            color="success"
                            type="filled"
                            @click="editar()"
                            v-if="$route.params.id"
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="success"
                            type="filled"
                            @click="guardar()"
                            v-else
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            @click="cancelar()"
                            >CANCELAR</vs-button
                        >
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
import { log } from "util";
import { AgGridVue } from "ag-grid-vue";
import Datepicker from "vuejs-datepicker";
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        Datepicker
    },
    data() {
        return {
            /**
             * mapeo de datos
             */
            tipo_activo: "",
            codigo_identificacion: "",
            codigo_anterior: "",
            titulo_anterior: "",
            titulo_actualizado: "",
            editorial: "",
            conservacion: "",
            mantenimiento: "",
            fechacompra: "",
            costoadquisicion: "",
            ubicaciongeneral: "",
            ubicacionespecifica: "",
            custodio: "",
            cuentacontable: "",
            observaciones: "",
            id_libro: '',
            contenidotipoactivo: [],
            contenidoconservacion: [],
            contenidomantenimiento: [],
            contenidoubicaciongeneral: [],
            contenidoubicacionespecifica: [],
            contenidocustodio: [],
            buscar1: ""
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    props: { modalactive: false },
    methods: {
        listartipoactivo(page1, buscar1) {
            var url =
                "/api/tipoactivomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidotipoactivo = respuesta.recupera;
            });
        },
        listarconservacion(page1, buscar1) {
            var url =
                "/api/conservacionmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoconservacion = respuesta.recupera;
            });
        },
        listarmantenimiento(page1, buscar1) {
            var url =
                "/api/mantenimientomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomantenimiento = respuesta.recupera;
            });
        },
        listarubicaciongeneral(page1, buscar1) {
            var url =
                "/api/ubicaciongeneralmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoubicaciongeneral = respuesta.recupera;
            });
        },
        listarubicacionespecifica(page1, buscar1) {
            var url =
                "/api/ubicacionespecificamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoubicacionespecifica = respuesta.recupera;
            });
        },
        listarcustodio(page1, buscar1) {
            var url =
                "/api/custodiomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocustodio = respuesta.recupera;
            });
        },
        buscardatos() {
            var url =
                "/api/buscarlibromobiliario/" +
                this.$route.params.id;
            axios.get(url).then(res => {
                var respuesta = res.data.recupera[0];
                this.tipo_activo=respuesta.id_tipo_activo;
                this.codigo_identificacion=respuesta.codigo_identificacion_libro;
                this.codigo_anterior=respuesta.codigo_anterior_libro;
                this.titulo_anterior=respuesta.titulo_anterior_libro;
                this.titulo_actualizado=respuesta.titulo_actualizado_libro;
                this.editorial=respuesta.editorial_libro;
                this.conservacion=respuesta.id_conservacion;
                this.mantenimiento=respuesta.id_mantenimiento;
                this.fechacompra=respuesta.fechacompra_libro;
                this.costoadquisicion=respuesta.costoadquisicion_libro;
                this.ubicaciongeneral=respuesta.id_ubicacion_general;
                this.ubicacionespecifica=respuesta.id_ubicacion_especifica;
                this.custodio=respuesta.id_custodio;
                this.cuentacontable=respuesta.cuentacontable_libro;
                this.observaciones=respuesta.observaciones_libro;
                this.id_libro=respuesta.id_libro;
            });
        },
        guardar() {
            /*if (this.validar()) {
                return;
            }*/
            axios
                .post("/api/guardarlibromobiliario", {
                    tipo_activo: this.tipo_activo,
                    codigo_identificacion: this.codigo_identificacion,
                    codigo_anterior: this.codigo_anterior,
                    titulo_anterior: this.titulo_anterior,
                    titulo_actualizado: this.titulo_actualizado,
                    editorial: this.editorial,
                    conservacion: this.conservacion,
                    mantenimiento: this.mantenimiento,
                    fechacompra: this.fechacompra,
                    costoadquisicion: this.costoadquisicion,
                    ubicaciongeneral: this.ubicaciongeneral,
                    ubicacionespecifica: this.ubicacionespecifica,
                    custodio: this.custodio,
                    cuentacontable: this.cuentacontable,
                    observaciones: this.observaciones,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Libro Guardado",
                            text: "Registro guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/libro");
                        
                    
                })
                .catch(err => {
                    console.log(err);
                });
        },
        editar() {
            /*if (this.validar()) {
                return;
            }*/
            axios
                .post("/api/editarlibromobiliario", {
                    tipo_activo: this.tipo_activo,
                    codigo_identificacion: this.codigo_identificacion,
                    codigo_anterior: this.codigo_anterior,
                    titulo_anterior: this.titulo_anterior,
                    titulo_actualizado: this.titulo_actualizado,
                    editorial: this.editorial,
                    conservacion: this.conservacion,
                    mantenimiento: this.mantenimiento,
                    fechacompra: this.fechacompra,
                    costoadquisicion: this.costoadquisicion,
                    ubicaciongeneral: this.ubicaciongeneral,
                    ubicacionespecifica: this.ubicacionespecifica,
                    custodio: this.custodio,
                    cuentacontable: this.cuentacontable,
                    observaciones: this.observaciones,
                    id_libro: this.id_libro
                })
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Libro Editado",
                            text: "Registro editado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/libro");
                        
                    
                })
                .catch(err => {
                    console.log(err);
                });
        },
        cancelar() {
            if (this.modalactive == true) {
                this.$emit("CloseCLient", this.clientsend);
            } else {
                this.$router.push("/mobiliario/libro");
            }
        },
        /**
         * validación de solo números, permite solo el
         * ingreso de numeros en los formularios de registro
         */
        solonumeros: function($event) {
            //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
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
    mounted() {
        if(this.$route.params.id){
            this.buscardatos();
        }
        
        this.listartipoactivo(1, "");
        this.listarconservacion(1, "");
        this.listarmantenimiento(1, "");
        this.listarubicaciongeneral(1, "");
        this.listarubicacionespecifica(1, "");
        this.listarcustodio(1, "");
    }
};
</script>
<style lang="scss">
.txt-center > div > input {
    text-align: center;
}
</style>