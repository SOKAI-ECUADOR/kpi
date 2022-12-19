<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">



            <vx-card title="Creación de Acta - Ingreso de Activo">
                <div class="vx-row">

                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="tipo_activo"
                            label="Tipo Activo"
                            v-on:change="cambiarcuentacontable()"
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
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="identificador"
                            label="Identificador"
                        >
                            <vs-select-item
                                v-for="datos in contenidoidentificador"
                                :value="datos.id_identificador"
                                :text="datos.descripcion_identificador"
                                :key="datos.id_identificador"
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
                            label="Nombre del Bien"
                            v-model="nombre_bien"
                        />
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Descripción"
                            v-model="descripcion"
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
                            v-model="modelo"
                            label="Modelo"
                        >
                            <vs-select-item
                                v-for="datos in contenidomodelo"
                                :value="datos.id_modelo"
                                :text="datos.nombre_modelo"
                                :key="datos.id_modelo"
                            />
                        </vs-select>
                    </div>
                    <!--<div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Serie"
                            v-model="serie"
                        />
                    </div>-->
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
                            v-model="color_secundario"
                            label="Color Secundario"
                        >
                            <vs-select-item
                                v-for="datos in contenidocolor"
                                :value="datos.id_color"
                                :text="datos.nombre_color"
                                :key="datos.id_color"
                            />
                        </vs-select>
                    </div>
                    <!--<div class="vx-col sm:w-1/5 w-full mb-6">
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
                    </div>-->
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="tipo"
                            label="Tipo"
                        >
                            <vs-select-item
                                v-for="datos in contenidotipo"
                                :value="datos.id_tipo"
                                :text="datos.descripcion_tipo"
                                :key="datos.id_tipo"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="ano_fabricacion"
                            label="Año Fabricación"
                        >
                            <vs-select-item
                                v-for="datos in contenidoano"
                                :value="datos"
                                :text="datos"
                                :key="datos"
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
                        <!--<vs-input
                            class="w-full txt-center"
                            label="Fecha de Compra"
                            v-model="fechacompra"
                        />-->
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
                        <vs-input
                            class="w-full txt-center"
                            label="Combustible"
                            v-model="combustible"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="No. Motor"
                            v-model="motor"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Placa"
                            v-model="placa"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="No. Chasis"
                            v-model="chasis"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Kilometraje"
                            v-model="kilometraje"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6" style="display: flex; align-items: center;">
                        <vs-input
                            class="w-full txt-center"
                            label="No. Vehiculo"
                            v-model="vehiculo"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="ubicaciongeneral"
                            label="Ubicación General"
                            v-on:change="cambiarubicacionespecifica()"
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
                            readonly
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Observaciones"
                            v-model="observaciones"
                        />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Imagen Principal</label>
                        <div class="vx-col md:w-full w-full mb-6">
                            <div style="display:none">
                                <input
                                    type="file"
                                    class="filepre seleccionarimagen"
                                    accept="image/*"
                                    @change="seleccionarimagen"
                                />
                            </div>
                            <div class="verimagen" v-if="!imagen">
                                <img
                                    src="/images/upload.png"
                                    @click="agregarimagen"
                                    class="imagenpre w-full"
                                    style="padding: 20px;"
                                />
                            </div>
                            <div
                                class="verimagen"
                                v-else
                                style="position: relative;"
                            >
                                <template v-if="recuperaimagen">
                                    <img
                                        :src="
                                            '/' +
                                                usuario.id_empresa +
                                                '/imagen/' +
                                                imagen
                                        "
                                        @click="agregarimagen"
                                        class="imagenpre w-full"
                                    />
                                    <div class="alerta estiloborrar">
                                        <div class="text-center">
                                            <span
                                                class="spanborrar"
                                                @click="eliminarimagen"
                                                >X</span
                                            >
                                        </div>
                                    </div>
                                </template>
                                <template v-else>
                                    <img
                                        :src="imagenprevisualizar"
                                        @click="agregarimagen"
                                        class="imagenpre w-full"
                                    />
                                    <div class="alerta estiloborrar">
                                        <div class="text-center">
                                            <span
                                                class="spanborrar"
                                                @click="eliminarimagen"
                                                >X</span
                                            >
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
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
            identificador: "",
            codigo_identificacion: "",
            codigo_anterior: "",
            nombre_bien: "",
            descripcion: "",
            marca: "",
            modelo: "",
            //serie: "",
            color: "",
            color_secundario: "",
            tipo: "",
            //material: "",
            ano_fabricacion: "",
            conservacion: "",
            mantenimiento: "",
            fechacompra: "",
            costoadquisicion: "",
            combustible: "",
            motor: "",
            placa: "",
            chasis: "",
            kilometraje: "",
            vehiculo: "",
            ubicaciongeneral: "",
            ubicacionespecifica: "",
            custodio: "",
            cuentacontable: "",
            observaciones: "",
            id_vehiculo: '',
            contenidotipoactivo: [],
            contenidomarca: [],
            contenidomodelo: [],
            contenidocolor: [],
            //contenidomaterial: [],
            contenidoano: ['2022', '2021', '2020', '2019', '2018', '2017', '2016', '2015', '2014', '2013', '2012', '2011', '2010', '2009', '2008', '2007', '2006', '2005', '2004', '2003', '2002', '2001', '2000'],
            contenidoconservacion: [],
            contenidomantenimiento: [],
            contenidoubicaciongeneral: [],
            contenidoubicacionespecifica: [],
            contenidocustodio: [],
            contenidoidentificador: [],
            contenidotipo: [],
            buscar1: "",
            recuperaimagen: "",
            imagen: "",
            imagenprevisualizar:[],
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
        listarmodelo(page1, buscar1) {
            var url =
                "/api/modelomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomodelo = respuesta.recupera;
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
        /*listarmaterial(page1, buscar1) {
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
        },*/
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
        cambiarubicacionespecifica(){
            this.ubicacionespecifica = "";
            this.listarubicacionespecifica(1, "");
        },
        listarubicacionespecifica(page1, buscar1) {
            var url =
                "/api/listarubicacionespecificamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1 +
                "&ubicaciongeneral=" +
                this.ubicaciongeneral;
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
        listaridentificador(page1, buscar1) {
            var url =
                "/api/identificadormobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoidentificador = respuesta.recupera;
            });
        },
        listartipo(page1, buscar1) {
            var url =
                "/api/tipomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidotipo = respuesta.recupera;
            });
        },
        cambiarcuentacontable(){
            let tipoact = this.contenidotipoactivo.find(element => element.id_tipo_activo_mobiliario == this.tipo_activo);
            this.cuentacontable = tipoact.cuenta_contable_tipo_activo;
        },
        buscardatos() {
            var url =
                "/api/buscarvehiculomobiliario/" +
                this.$route.params.id;
            axios.get(url).then(res => {
                var respuesta = res.data.recupera[0];
                this.tipo_activo=respuesta.id_tipo_activo;
                this.identificador=respuesta.id_identificador;
                this.codigo_identificacion=respuesta.codigo_identificacion_vehiculo;
                this.codigo_anterior=respuesta.codigo_anterior_vehiculo;
                this.nombre_bien=respuesta.nombre_bien_vehiculo;
                this.descripcion=respuesta.descripcion_vehiculo;
                this.marca=respuesta.id_marca;
                this.modelo=respuesta.id_modelo;
                //this.serie=respuesta.serie_vehiculo;
                this.color=respuesta.id_color;
                this.color_secundario=respuesta.id_color_secundario;
                this.tipo=respuesta.id_tipo;
                //this.material=respuesta.id_material;
                this.ano_fabricacion=respuesta.ano_fabricacion_vehiculo;
                this.conservacion=respuesta.id_conservacion;
                this.mantenimiento=respuesta.id_mantenimiento;
                this.fechacompra=respuesta.fechacompra_vehiculo;
                this.costoadquisicion=respuesta.costoadquisicion_vehiculo;
                this.combustible=respuesta.combustible_vehiculo;
                this.motor=respuesta.motor_vehiculo
                this.placa=respuesta.placa_vehiculo;
                this.chasis=respuesta.chasis_vehiculo;
                this.kilometraje=respuesta.kilometraje_vehiculo;
                this.vehiculo=respuesta.vehiculo_vehiculo;
                this.ubicaciongeneral=respuesta.id_ubicacion_general;
                this.ubicacionespecifica=respuesta.id_ubicacion_especifica;
                this.custodio=respuesta.id_custodio;
                this.cuentacontable=respuesta.cuentacontable_vehiculo;
                this.observaciones=respuesta.observaciones_vehiculo;
                this.id_vehiculo=respuesta.id_vehiculo;
                this.imagen = respuesta.imagen1;
                if(this.imagen){
                  this.recuperaimagen = 1;
                }
            });
        },
        /*
         * Guardar los datos del formulario
         */
        guardar() {
            /*if (this.validar()) {
                return;
            }*/
            let formData = new FormData();
            formData.append("tipo_activo", this.tipo_activo);
            formData.append("identificador", this.identificador);
            formData.append("codigo_identificacion", this.codigo_identificacion);
            formData.append("codigo_anterior", this.codigo_anterior);
            formData.append("nombre_bien", this.nombre_bien);
            formData.append("descripcion", this.descripcion);
            formData.append("marca", this.marca);
            formData.append("modelo", this.modelo);
            formData.append("color", this.color);
            formData.append("color_secundario", this.color_secundario);
            formData.append("tipo", this.tipo);
            formData.append("ano_fabricacion", this.ano_fabricacion);
            formData.append("conservacion", this.conservacion);
            formData.append("mantenimiento", this.mantenimiento);
            formData.append("fechacompra", this.fechacompra);
            formData.append("costoadquisicion", this.costoadquisicion);
            formData.append("combustible", this.combustible);
            formData.append("motor", this.motor);
            formData.append("placa", this.placa);
            formData.append("chasis", this.chasis);
            formData.append("kilometraje", this.kilometraje);
            formData.append("vehiculo", this.vehiculo);
            formData.append("ubicaciongeneral", this.ubicaciongeneral);
            formData.append("ubicacionespecifica", this.ubicacionespecifica);
            formData.append("custodio", this.custodio);
            formData.append("cuentacontable", this.cuentacontable);
            formData.append("observaciones", this.observaciones);
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file_imagen", this.imagen);
            axios
                .post("/api/guardarvehiculomobiliario", formData)
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Vehiculo Guardado",
                            text: "Registro guardado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/vehiculo");
                        
                    
                })
                .catch(err => {
                    console.log(err);
                });
        },
        editar() {
            /*if (this.validar()) {
                return;
            }*/
            let formData = new FormData();
            formData.append("tipo_activo", this.tipo_activo);
            formData.append("identificador", this.identificador);
            formData.append("codigo_identificacion", this.codigo_identificacion);
            formData.append("codigo_anterior", this.codigo_anterior);
            formData.append("nombre_bien", this.nombre_bien);
            formData.append("descripcion", this.descripcion);
            formData.append("marca", this.marca);
            formData.append("modelo", this.modelo);
            formData.append("color", this.color);
            formData.append("color_secundario", this.color_secundario);
            formData.append("tipo", this.tipo);
            formData.append("ano_fabricacion", this.ano_fabricacion);
            formData.append("conservacion", this.conservacion);
            formData.append("mantenimiento", this.mantenimiento);
            formData.append("fechacompra", this.fechacompra);
            formData.append("costoadquisicion", this.costoadquisicion);
            formData.append("combustible", this.combustible);
            formData.append("motor", this.motor);
            formData.append("placa", this.placa);
            formData.append("chasis", this.chasis);
            formData.append("kilometraje", this.kilometraje);
            formData.append("vehiculo", this.vehiculo);
            formData.append("ubicaciongeneral", this.ubicaciongeneral);
            formData.append("ubicacionespecifica", this.ubicacionespecifica);
            formData.append("custodio", this.custodio);
            formData.append("cuentacontable", this.cuentacontable);
            formData.append("observaciones", this.observaciones);
            formData.append("id_vehiculo", this.id_vehiculo);
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file_imagen", this.imagen);
            axios
                .post("/api/editarvehiculomobiliario", {
                    tipo_activo: this.tipo_activo,
                    identificador: this.identificador,
                    codigo_identificacion: this.codigo_identificacion,
                    codigo_anterior: this.codigo_anterior,
                    nombre_bien: this.nombre_bien,
                    descripcion: this.descripcion,
                    marca: this.marca,
                    modelo: this.modelo,
                    //serie: this.serie,
                    color: this.color,
                    color_secundario: this.color_secundario,
                    tipo: this.tipo,
                    //material: this.material,
                    ano_fabricacion: this.ano_fabricacion,
                    conservacion: this.conservacion,
                    mantenimiento: this.mantenimiento,
                    fechacompra: this.fechacompra,
                    costoadquisicion: this.costoadquisicion,
                    combustible: this.combustible,
                    motor: this.motor,
                    placa: this.placa,
                    chasis: this.chasis,
                    kilometraje: this.kilometraje,
                    vehiculo: this.vehiculo,
                    ubicaciongeneral: this.ubicaciongeneral,
                    ubicacionespecifica: this.ubicacionespecifica,
                    custodio: this.custodio,
                    cuentacontable: this.cuentacontable,
                    observaciones: this.observaciones,
                    id_vehiculo: this.id_vehiculo
                })
                .then(res => {
                    
                        this.$vs.notify({
                            title: "Vehiculo Editado",
                            text: "Registro editado exitosamente",
                            color: "success"
                        });
                        this.$router.push("/mobiliario/vehiculo");
                        
                    
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         * elimina los campos de formulario
         */
        cancelar() {
            if (this.modalactive == true) {
                this.$emit("CloseCLient", this.clientsend);
            } else {
                this.$router.push("/mobiliario/vehiculo");
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
        },
        //Archivo de imagen de empresa
        agregarimagen() {
            this.recuperaimagen = 0;
            $(".seleccionarimagen").click();
        },
        seleccionarimagen(event) {
            this.recuperaimagen = 0;
            var allowedExtensions = /(.jpg|.jpeg|.png|.gif|.webp)$/i;
            if (!allowedExtensions.exec($(".seleccionarimagen").val())) {
                this.imagen = "";
                $(".seleccionarimagen").val("");
                this.$vs.notify({
                    time: 8000,
                    color: "danger",
                    title: "Formato inválido ",
                    text: "Solo se acepta archivos imagen"
                });
            } else {
                var tam = parseInt(event.target.files[0].size / 1024);
                if (tam > 1024) {
                    this.imagen = "";
                    $(".seleccionarimagen").val("");
                    this.$vs.notify({
                        time: 8000,
                        color: "danger",
                        title: "Formato inválido",
                        text: "El tamaño de la imagen no puede ser mayor a 1 MB"
                    });
                    return;
                }
                this.imagen = event.target.files[0];
                this.previsualizarimagen(this.imagen);
            }
        },
        eliminarimagen(event) {
            this.recuperaimagen = 0;
            this.imagen = "";
            $(".seleccionarimagen").val("");
        },
        previsualizarimagen(file) {
            this.recuperaimagen = 0;
            let reader = new FileReader();
            reader.onload = e => {
                this.imagenprevisualizar = e.target.result;
            };
            reader.readAsDataURL(file);
        },
    },
    mounted() {

        if(this.$route.params.id){
            this.buscardatos();
        }
        
        this.listartipoactivo(1, "");
        this.listarmarca(1, "");
        this.listarmodelo(1, "");
        this.listarcolor(1, "");
        //this.listarmaterial(1, "");
        this.listarconservacion(1, "");
        this.listarmantenimiento(1, "");
        this.listarubicaciongeneral(1, "");
        this.listarcustodio(1, "");
        this.listaridentificador(1, "");
        this.listartipo(1, "");
    }
};
</script>
<style lang="scss">
.txt-center > div > input {
    text-align: center;
}
.verimagen{
      overflow: hidden;
      padding: 0px;
      display: flex;
      justify-content: center;
      align-items: center;
      width: 100%;
      border-radius: 20px;
      background: rgba(255,255,255,.8)!important;
      -webkit-transition: all .3s ease;
    -moz-transition: all .3s ease;
    -ms-transition: all .3s ease;
    -o-transition: all .3s ease;
    transition: all .3s ease;
    border: 5px solid rgba(0,0,0,.3);
  }

  .imagenpre:hover{
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
  }
</style>