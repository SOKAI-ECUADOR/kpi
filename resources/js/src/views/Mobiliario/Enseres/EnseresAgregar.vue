<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Agregar Mobiliario Enseres">
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
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Descripción Anterior"
                            v-model="descripcion_anterior"
                        />
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Descripción Actualizada"
                            v-model="descripcion_actualizada"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="marca"
                            label="Marca"
                        >
                            <vs-select-item
                                v-for="datos in contenidomarca"
                                :value="datos.id_marca"
                                :text="datos.nombre_marca"
                                :key="datos.id_marca"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="dimension"
                            label="Dimensiones"
                        >
                            <vs-select-item
                                v-for="datos in contenidodimension"
                                :value="datos.id_dimension"
                                :text="datos.descripcion_dimension"
                                :key="datos.id_dimension"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="color"
                            label="Color"
                        >
                            <vs-select-item
                                v-for="datos in contenidocolor"
                                :value="datos.id_color"
                                :text="datos.nombre_color"
                                :key="datos.id_color"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="material"
                            label="Material"
                        >
                            <vs-select-item
                                v-for="datos in contenidomaterial"
                                :value="datos.id_material"
                                :text="datos.descripcion_material"
                                :key="datos.id_material"
                            />
                        </vs-select>
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
            descripcion_anterior: "",
            descripcion_actualizada: "",
            marca: "",
            dimension: "",
            color: "",
            material: "",
            conservacion: "",
            mantenimiento: "",
            fechacompra: "",
            costoadquisicion: "",
            ubicaciongeneral: "",
            ubicacionespecifica: "",
            custodio: "",
            cuentacontable: "",
            observaciones: "",
            id_enseres: '',
            contenidotipoactivo: [],
            contenidomarca: [],
            contenidodimension: [],
            contenidocolor: [],
            contenidomaterial: [],
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
        listarmarca(page1, buscar1) {
            var url =
                "/api/marcamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomarca = respuesta.recupera;
            });
        },
        listardimension(page1, buscar1) {
            var url =
                "/api/dimensionmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidodimension = respuesta.recupera;
            });
        },
        listarcolor(page1, buscar1) {
            var url =
                "/api/colormobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocolor = respuesta.recupera;
            });
        },
        listarmaterial(page1, buscar1) {
            var url =
                "/api/materialmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomaterial = respuesta.recupera;
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
                "/api/buscarenseresmobiliario/" +
                this.$route.params.id;
            axios.get(url).then(res => {
                var respuesta = res.data.recupera[0];
                this.tipo_activo=respuesta.id_tipo_activo;
                this.codigo_identificacion=respuesta.codigo_identificacion_enseres;
                this.codigo_anterior=respuesta.codigo_anterior_enseres;
                this.descripcion_anterior=respuesta.descripcion_anterior_enseres;
                this.descripcion_actualizada=respuesta.descripcion_actualizada_enseres;
                this.marca=respuesta.id_marca;
                this.dimension=respuesta.id_dimension;
                this.color=respuesta.id_color;
                this.material=respuesta.id_material;
                this.conservacion=respuesta.id_conservacion;
                this.mantenimiento=respuesta.id_mantenimiento;
                this.fechacompra=respuesta.fechacompra_enseres;
                this.costoadquisicion=respuesta.costoadquisicion_enseres;
                this.ubicaciongeneral=respuesta.id_ubicacion_general;
                this.ubicacionespecifica=respuesta.id_ubicacion_especifica;
                this.custodio=respuesta.id_custodio;
                this.cuentacontable=respuesta.cuentacontable_enseres;
                this.observaciones=respuesta.observaciones_enseres;
                this.id_enseres=respuesta.id_enseres;
            });
        },
        guardar() {
            /*if (this.validar()) {
                return;
            }*/
            axios
                .post("/api/guardarenseresmobiliario", {
                    tipo_activo: this.tipo_activo,
                    codigo_identificacion: this.codigo_identificacion,
                    codigo_anterior: this.codigo_anterior,
                    descripcion_anterior: this.descripcion_anterior,
                    descripcion_actualizada: this.descripcion_actualizada,
                    marca: this.marca,
                    dimension: this.dimension,
                    color: this.color,
                    material: this.material,
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
                            title: "Enseres Guardado",
                            text: "Registro guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/enseres");
                        
                    
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
                .post("/api/editarenseresmobiliario", {
                    tipo_activo: this.tipo_activo,
                    codigo_identificacion: this.codigo_identificacion,
                    codigo_anterior: this.codigo_anterior,
                    descripcion_anterior: this.descripcion_anterior,
                    descripcion_actualizada: this.descripcion_actualizada,
                    marca: this.marca,
                    dimension: this.dimension,
                    color: this.color,
                    material: this.material,
                    conservacion: this.conservacion,
                    mantenimiento: this.mantenimiento,
                    fechacompra: this.fechacompra,
                    costoadquisicion: this.costoadquisicion,
                    ubicaciongeneral: this.ubicaciongeneral,
                    ubicacionespecifica: this.ubicacionespecifica,
                    custodio: this.custodio,
                    cuentacontable: this.cuentacontable,
                    observaciones: this.observaciones,
                    id_enseres: this.id_enseres
                })
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Enseres Editado",
                            text: "Registro editado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/enseres");
                        
                    
                })
                .catch(err => {
                    console.log(err);
                });
        },
        cancelar() {
            if (this.modalactive == true) {
                this.$emit("CloseCLient", this.clientsend);
            } else {
                this.$router.push("/mobiliario/enseres");
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
        this.listarmarca(1, "");
        this.listardimension(1, "");
        this.listarcolor(1, "");
        this.listarmaterial(1, "");
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