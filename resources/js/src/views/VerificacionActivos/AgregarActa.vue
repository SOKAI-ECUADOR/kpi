<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card>
                <h2><b>ACTA # {{ acta_id }}</b></h2>
                <br />
                <div class="vx-row">

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="acta_agencia"
                            label="Agencia Custodio" v-on:change="cambiar_acta_agencia($event)">
                            <vs-select-item
                                v-for="datos in acta_agencias" :value="datos.id"
                                :text="datos.nombre" :key="datos.id"
                            />
                        </vs-select>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input class="w-full" label="Provincia" v-model="acta_agencia_provincia" disabled />
                    </div>    
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input class="w-full" label="Cantón" v-model="acta_agencia_canton"  disabled/>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input class="w-full" label="Parroquia" v-model="acta_agencia_parroquia" disabled />
                    </div>

                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input class="w-full" label="Descripción Ubicación" v-model="acta_agencia_descripcion"  disabled/>
                    </div>

                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="acta_responsable"
                            label="Responsable del Acta" v-on:change="cambiar_acta_responsable($event)">
                            <vs-select-item
                                v-for="datos in acta_responsables" :value="datos.id"
                                :text="datos.nombres" :key="datos.id"
                            />
                        </vs-select>
                    </div>

                    <div class="vx-col sm:w-1/2 w-full mb-6">
                    </div>

                    <div class="vx-col w-full">
                        <center>
                            <vs-button color="success" type="filled" @click="editar()" v-if="$route.params.id">EDITAR</vs-button>
                            <vs-button color="success" type="filled" @click="guardar()" v-else>GUARDAR</vs-button>
                            <vs-button color="danger" type="filled" @click="cancelar()" >CANCELAR</vs-button>
                            <vs-button color="warning" type="filled" @click="abrirModalAgregarActivo()" v-if="$route.params.id">AGREGAR ACTIVO</vs-button>
                        </center>
                    </div>
                </div>
            </vx-card>
            <!--=================================== Inicio Modal Agregar Activo =======================================-->

              <vs-popup fullscreen  :title="titulomodal" :active.sync="modal">

                <vs-tabs :color="colorx">
            
                    <vs-tab label="Detalle del Activo" icon="account_balance" @click="colorx = '#FFA500'">
                    <!--=================================== Inicio Agregar Activo =======================================-->

                    <div class="vx-col sm:w-full w-full mb-12">
                        <vx-card>

                            <vs-collapse accordion >
                                <vs-collapse-item>
                                    <div slot="header">
                                        INFORMACIÓN GENERAL DEL ACTIVO
                                    </div>
                                    <div class="vx-row">
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_tipo"
                                                label="TIPO DE ACTIVO" v-on:change="cambiar_activo_tipo($event)">
                                                <vs-select-item
                                                    v-for="datos in activo_tipos" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_estado"
                                                label="PROCESO" v-on:change="cambiar_activo_estado($event)">
                                                <vs-select-item
                                                    v-for="datos in activo_estados" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="CÓDIGO DE EMPRESA" v-model="activo_codigo_empresa"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="ETIQUETA ANTERIOR" v-model="activo_codigo_anterior"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input type="number" class="w-full" label="INGRESE CANTIDAD" v-model="activo_cantidad"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_unidad"
                                                label="UNIDAD" v-on:change="cambiar_activo_unidad($event)">
                                                <vs-select-item
                                                    v-for="datos in activo_unidades" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/2 w-full mb-6">
                                                <vs-textarea label="DESCRIPCIÓN DEL ACTIVO" v-model="activo_descripcion" />
                                        </div>
                                    </div>
                                </vs-collapse-item>
                                <vs-collapse-item>
                                    <div slot="header">
                                        DETALLE DEL ACTIVO
                                    </div>
                                    <div class="vx-row">
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_nombre_nivel_2"
                                                label="NOMBRE NIVEL 2" v-on:change="cambiar_activo_nombre_nivel_2($event)">
                                                <vs-select-item
                                                    v-for="datos in activos_nombre_nivel_2" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_nombre_nivel_3"
                                                label="NOMBRE NIVEL 3" v-on:change="cambiar_activo_nombre_nivel_3($event)">
                                                <vs-select-item
                                                    v-for="datos in activos_nombre_nivel_3" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_nombre_nivel_4"
                                                label="NOMBRE NIVEL 4" v-on:change="cambiar_activo_nombre_nivel_4($event)">
                                                <vs-select-item
                                                    v-for="datos in activos_nombre_nivel_4" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="CUENTA CONTABLE" v-model="activo_cuenta_contable" disabled />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_marca"
                                                label="MARCA" >
                                                <vs-select-item
                                                    v-for="datos in activo_marcas" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="MODELO" v-model="activo_modelo"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="NÚMERO DE SERIE" v-model="activo_numero_serie"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="DIMENSIONES" v-model="activo_dimensiones"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="MATERIAL" v-model="activo_material"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="COLOR" v-model="activo_color"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_estado_conservacion"
                                                label="ESTADO DE CONSERVACIÓN" >
                                                <vs-select-item
                                                    v-for="datos in activos_estado_conservacion" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>    

                                    </div>
                                </vs-collapse-item>
                                <vs-collapse-item>
                                    <div slot="header">
                                        DETALLE DE LA UBICACIÓN
                                    </div>
                                    <div class="vx-row">

                                       
                                       <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_ubicacion_piso"
                                                label="PISO" >
                                                <vs-select-item
                                                    v-for="datos in activos_ubicacion_piso" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_ubicacion_departamento"
                                                label="DEPARTAMENTO" >
                                                <vs-select-item
                                                    v-for="datos in activos_ubicacion_departamento" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_ubicacion_area_especifica"
                                                label="ÁREA ESPECÍFICA" >
                                                <vs-select-item
                                                    v-for="datos in activos_ubicacion_area_especifica" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input class="w-full" label="OBSERVACIONES" v-model="activo_ubicacion_observaciones"  />
                                        </div>
                                    </div>
                                </vs-collapse-item>
                                <vs-collapse-item>
                                    <div slot="header">
                                        CUSTODIO
                                    </div>
                                    <div class="vx-row">

                                       <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="activo_custodio"
                                                label="CUSTODIO" >
                                                <vs-select-item
                                                    v-for="datos in activo_custodios" :value="datos.id"
                                                    :text="datos.nombre" :key="datos.id"
                                                />
                                            </vs-select>
                                        </div>
        
                                    </div>
                                </vs-collapse-item>


                                <vs-collapse-item>
                                    <div slot="header">
                                        DETALLE DE COMPRA
                                    </div>
                                    <div class="vx-row">

                                       <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input type="date" class="w-full" label="FECHA DE COMPRA" v-model="activo_fecha_compra"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input  class="w-full" label="COSTO DE COMPRA" v-model="activo_costo_compra"  />
                                        </div>
                                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                            <vs-input  class="w-full" label="DESCRIPCIÓN MEJORADA" v-model="activo_compra_descripcion_mejorada"  />
                                        </div>

                                    </div>
                                </vs-collapse-item>

                                <vs-collapse-item>
                                    <div slot="header">
                                        OBSERVACIONES
                                    </div>
                                    <div class="vx-row">

                                       <div class="vx-col sm:w-1 w-full mb-6">
                                            <vs-textarea label="OBSERVACIONES GENERALES" v-model="activo_observaciones" />
                                        </div>
                                       
                                        
                                    </div>
                                </vs-collapse-item>


                                </vs-collapse>
                                <div class="vx-col w-full">
                                    <center>
                                        <vs-button color="success" type="filled" >GUARDAR</vs-button>
                                        <vs-button color="danger" type="filled"  >CANCELAR</vs-button>
                                    </center>
                                </div>

                        </vx-card>            
                    </div>


                    <!--=================================== Fin Agregar Activo =======================================-->

                    </vs-tab>
                    <vs-tab label="Imágenes" icon="dashboard" @click="colorx = '#551A8B'">
                        <vs-upload multiple text="Subir múltiples imágenes" action="https://jsonplaceholder.typicode.com/posts/" @on-success="successUpload" />

                    </vs-tab>
                    <vs-tab label="Componentes" icon="account_circle" @click="colorx = '#0000FF'">
                    </vs-tab>
                </vs-tabs>

              </vs-popup>


            <!--=================================== Fin Modal Agregar Activo =======================================-->
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
            acta_id: "",
            acta_agencia: "",    
            acta_agencias: [],
            acta_responsable: "",
            acta_responsables: [],
            acta_agencia_provincia: "",
            acta_agencia_canton: "",
            acta_agencia_parroquia: "",
            acta_agencia_descripcion: "",
            buscar1: "",
            titulomodal: "",    
            modal: false,
            activo_tipo: "",
            activo_tipos: [],
            activo_estado: "",
            activo_estados: [],


			activo_codigo_empresa: "",
			activo_codigo_anterior: "",
			activo_cantidad: "",
            activo_descripcion: "",
            activo_unidad: "",
            activo_unidades: [],

            activo_nombre_nivel_2: "",
            activos_nombre_nivel_2: [],
            activo_nombre_nivel_3: "",
            activos_nombre_nivel_3: [],
            activo_nombre_nivel_4: "",
            activos_nombre_nivel_4: [],
            activo_cuenta_contable: "",

            activo_marca: "",
            activo_marcas: [],
            activo_modelo: "",
            activo_numero_serie: "",
            activo_dimensiones: "",
            activo_material: "",
            activo_color: "",
            activo_estado_conservacion: "",
            activos_estado_conservacion: [],     


            activo_ubicacion_piso: "",
            activos_ubicacion_piso: [],
            activo_ubicacion_departamento: "",
            activos_ubicacion_departamento: [],
            activo_ubicacion_area_especifica: "",
            activos_ubicacion_area_especifica: [],
            activo_ubicacion_observaciones: "",
            activo_custodio: "",
            activo_custodios: [],

            activo_fecha_compra: "",
            activo_costo_compra: "",
            activo_compra_descripcion_mejorada: "",
            activo_observaciones: "",

            tipo_activo: "",
            identificador: "",
            recuperaimagen: "",
            imagen: "",
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

        cambiar_acta_agencia($event){
            let agencia = this.acta_agencias.find(element => element.id == $event);
            this.acta_agencia = agencia.id;
            this.acta_agencia_provincia = agencia.provincia_nombre;
            this.acta_agencia_canton = agencia.canton_nombre;
            this.acta_agencia_parroquia = agencia.nombre_parroquia;
            this.acta_agencia_descripcion = agencia.descripcion;
        },

        listar_acta_agencia(page1, buscar1) {
            var url = "/api/actaagencia/" +this.usuario.id_empresa + "?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.acta_agencias = respuesta.recupera;
            });
        },

        cambiar_acta_responsable($event){
            let responsable = this.acta_responsables.find(element => element.id == $event);
            this.acta_responsable = responsable.id;
        },

        listar_acta_responsable(page1, buscar1) {
            var url = "/api/actaresponsables/" + this.usuario.id_empresa + "?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.acta_responsables = respuesta.recupera;
            });
        },
        cambiar_activo_tipo($event){
            let tipo = this.activo_tipos.find(element => element.id == $event);
            this.activo_tipo = tipo.id;
            this.listar_activo_nombre_nivel_2(1, "");
        },
        listar_activo_tipo(page1, buscar1) {
            var url = "/api/actaactivotipo/" + this.usuario.id_empresa + "?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.activo_tipos = respuesta.recupera;
            });
        },
        cambiar_activo_estado($event){
            let tipo = this.activo_estados.find(element => element.id == $event);
            this.activo_estado = tipo.id;
        },
        listar_activo_estado(page1, buscar1) {
            var url = "/api/actaestado/activo?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.activo_estados = respuesta.recupera;
            });
        },

        cambiar_activo_unidad($event){
            let unidad = this.activo_unidades.find(element => element.id == $event);
            this.activo_unidad = unidad.id;
        },
        listar_activo_unidad(page1, buscar1) {
            var url = "/api/actaactivounidad?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.activo_unidades = respuesta.recupera;
            });
        },            
        cambiar_activo_nombre_nivel_2($event){
            let nivel = this.activos_nombre_nivel_2.find(element => element.id == $event);
            this.activo_nombre_nivel_2 = nivel.id;
            this.listar_activo_nombre_nivel_3(1, "")

        },
        listar_activo_nombre_nivel_2(page1, buscar1) {
            var url = "/api/actaactivonombrenivel2/" + this.activo_tipo + "?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.activos_nombre_nivel_2 = respuesta.recupera;
            });
        },

        cambiar_activo_nombre_nivel_3($event){
            let nivel = this.activos_nombre_nivel_3.find(element => element.id == $event);
            this.activo_nombre_nivel_3 = nivel.id;
            this.listar_activo_nombre_nivel_4(1, "");
        },
        listar_activo_nombre_nivel_3(page1, buscar1) {
            var url = "/api/actaactivonombrenivel3/" + this.activo_nombre_nivel_2 + "?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.activos_nombre_nivel_3 = respuesta.recupera;
            });
        },
        cambiar_activo_nombre_nivel_4($event){
            let nivel = this.activos_nombre_nivel_4.find(element => element.id == $event);
            this.activo_nombre_nivel_4 = nivel.id;
        },
        listar_activo_nombre_nivel_4(page1, buscar1) {
            var url = "/api/actaactivonombrenivel4/" + this.activo_nombre_nivel_3 + "?page=" + page1 + "&buscar=" + buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.activos_nombre_nivel_4 = respuesta.recupera;
            });
        },






        buscardatos() {
            var url = "/api/buscaractaasignacionactivo/" +this.$route.params.id;

            axios.get(url).then(res => {
                var respuesta = res.data.recupera;
                 this.acta_id=respuesta.id;
                this.acta_agencia=respuesta.acta_agencia_id;
                this.acta_responsable=respuesta.acta_responsable_id;
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

            formData.append("user_id", this.usuario.id);
            formData.append("empresa_id", this.usuario.id_empresa);
            formData.append("agencia_id", this.acta_agencia);
            formData.append("responsable_acta_id", this.acta_responsable);

            axios.post("/api/guardaractaasignacionactivo", formData).then(res => {
                this.acta_id = res.data.acta_id;   
                this.$vs.notify({ title: "Acta Guardada", text: "Registro guardado exitosamente", color: "success" });
                this.$router.push(`/verificacion_activos/agregar_acta/${this.acta_id}/editar`);                         
            }).catch(err => {
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
         * cancelar los campos de formulario
         */
        cancelar() {
            if (this.modalactive == true) {
                this.$emit("CloseCLient", this.clientsend);
            } else {
                this.$router.push("/verificacion_activos/actas");
            }
        },
        abrirModalAgregarActivo() {
            this.modal = true;
            this.titulomodal = "Ingresar Activo";
        },
        
    },
    mounted() {

        this.listar_acta_agencia(1, "");
        this.listar_acta_responsable(1, "");
        this.listar_activo_tipo(1, "");
        this.listar_activo_estado(1, "");
        this.listar_activo_unidad(1, "");

        if(this.$route.params.id){
            this.buscardatos();
        }

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