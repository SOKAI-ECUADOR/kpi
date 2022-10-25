<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="left">
                <h3>Factura</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Ambiente:</h6>
                    <span v-if="ambiente==2">Producción</span>
                    <span v-else>Pruebas</span>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto" style="text-align: center;">
                    <h6 class="mb-1">Tipo Emisión:</h6> 
                    <span v-if="tipo_emision==1">Emision Normal</span>
                    <span v-if="tipo_emision==2">Indisponibilidad del SRI</span>
                    <div v-show="error" v-if="!tipo_emision">
                        <div v-for="err in error.tipo_emision" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>

                <div class="vx-col sm:w-1/2 w-full mb-6" style="position: relative;">
                    <h6 class="mb-1">Número Documento</h6>
                    <vs-input class="w-full" maxlength="17" placeholder="000-000-000000000" @keyup="listar_facturas(documento)" :disabled="respuesta" v-model="documento"/>
                    <div class="busqueda_lista busqueda_factura_ls" style="display: none;width: 93%!important;">
                        <div v-if="preloader.facturas">
                            <ul class="ul_busqueda_lista">
                                <li v-for="(tr,index) in facturas" :key="index" @click="seleccionar_factura(tr)">
                                    <span style="font-weight: bold;" v-if="tr.codigo!==null"> Proforma N° {{tr.codigo}}</span>
                                     <span style="font-weight: bold;" v-else> Factura N° {{(tr.clave_acceso).substring(24,27)}}-{{(tr.clave_acceso).substring(27,30)}}-{{(tr.clave_acceso).substring(30,39)}}</span> 
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                    <div v-show="error">
                        <div v-for="err in error.documento" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6">
                    <h6 class="mb-1">Fecha Doc:</h6>
                    <flat-pickr :config="configdateTimePicker" disabled class="w-full" v-model="fecha_doc" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error">
                        <div v-for="err in error.fecha_doc" :key="err" v-text="err" class="text-danger"></div>
                    </div> 
                </div>
            </div>
            <vs-divider position="left">
                <h3>Guia de remisión</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" >
                <div class="vx-col sm:w-full w-full mb-6 text-center">
                    <h6 class="mt-4">Clave de acceso:</h6>
                    <p>{{ transportista.clave_acceso }}</p>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Tipo Identificación</h6>
                    <vs-select class="selectExample w-full" :disabled="respuesta" placeholder="Tipo" v-model="transportista.tipo_identificacion_transporte">
                        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item, index) in tipo_identificacion_menu"/>
                    </vs-select>
                    <div v-show="error" v-if="!transportista.tipo_identificacion_transporte">
                        <div v-for="err in errortipo_identificacion_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col  sm:w-1/4 w-full mb-3 ml-auto" v-if="transportista.tipo_identificacion_transporte=='Ruc'" style="padding-right: inherit;text-align: center;">
                    <h6 class="mb-1">Identificación</h6>
                    <vx-input-group>
                        <vs-input class="w-full"  :disabled="respuesta" v-model="transportista.identificacion_transporte"/>
                        <template slot="append">
                            <div class="append-text btn-addon">
                                  <vs-button color="primary" type="filled" icon="search" @click="seachRuc(transportista.identificacion_transporte)"></vs-button>              
                            </div>
                        </template>
                    </vx-input-group>        
                            <div v-show="error" v-if="!transportista.identificacion_transporte">
                        <div v-for="err in erroridentificacion_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col  sm:w-1/4 w-full mb-3 ml-auto" v-else style="text-align: center;">
                    <h6 class="mb-1">Identificación</h6>
                    <vs-input class="w-full"  :disabled="respuesta" v-model="transportista.identificacion_transporte"/>
                
                            <div v-show="error" v-if="!transportista.identificacion_transporte">
                        <div v-for="err in erroridentificacion_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Razón social del transportista</h6>
                    <vs-input class="w-full" v-model="transportista.nombre_transporte" :disabled="respuesta"/>
                    <div v-show="error" v-if="!transportista.nombre_transporte">
                        <div v-for="err in errornombre_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Fecha de Inicio</h6>
                    <flat-pickr :config="configdateTimePicker" :disabled="respuesta" class="w-full mt-1" v-model="transportista.fecha_inicio_transporte" placeholder="Seleccionar" @on-change="listarclave_guia()"></flat-pickr>
                    <div v-show="error" v-if="!transportista.fecha_inicio_transporte">
                        <div v-for="err in errorfecha_inicio_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Fecha de Finalización</h6>
                    <flat-pickr :config="configdateTimePicker" :disabled="respuesta" class="w-full mt-1" v-model="transportista.fecha_fin_transporte" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!transportista.identificacion_transporte">
                        <div v-for="err in erroridentificacion_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Placa</h6>
                    <vs-input class="w-full" v-model="transportista.placa_transporte" :disabled="respuesta"/>
                    <div v-show="error" v-if="!transportista.placa_transporte">
                        <div v-for="err in errorplaca_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Documento aduanero</h6>
                    <vs-input class="w-full" v-model="transportista.documento_aduanero" :disabled="respuesta"/>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Motivo de translado</h6>
                    <vs-input class="w-full" v-model="transportista.motivo_translado" :disabled="respuesta"/>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-3 ml-auto" >
                    <h6 class="mb-1">Otro Destino
                        
                    </h6>
                    <vs-checkbox v-model="transportista.otro_destino" :disabled="respuesta" vs-value="1"></vs-checkbox>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-3 ml-auto" >
                    <h6 class="mb-1">Otra Dir Partida
                        
                    </h6>
                    <vs-checkbox v-model="transportista.otra_dir_partida" :disabled="respuesta" vs-value="1"></vs-checkbox>
                </div>
                <div class="vx-col sm:w-1/2 w-1/2 mb-3 ml-auto" style="text-align: center;" v-if="transportista.otro_destino && transportista.otra_dir_partida">
                    <h6 class="mb-1">Destino</h6>
                    <vs-input class="w-full" v-model="transportista.destino" :disabled="respuesta"/>
                </div>
                <div class="vx-col sm:w-full w-full mb-3 ml-auto" style="text-align: center;" v-else-if="transportista.otro_destino">
                    <h6 class="mb-1">Destino</h6>
                    <vs-input class="w-full" v-model="transportista.destino" :disabled="respuesta"/>
                </div>
                <div class="vx-col sm:w-1/2 w-1/2 mb-3 ml-auto" style="text-align: center;" v-if="transportista.otra_dir_partida && transportista.otro_destino">
                    <h6 class="mb-1">Direccion Partida</h6>
                    <vs-input class="w-full" v-model="transportista.dir_partida" :disabled="respuesta"/>
                </div>
                <div class="vx-col sm:w-full w-full mb-3 ml-auto" style="text-align: center;" v-else-if="transportista.otra_dir_partida">
                    <h6 class="mb-1">Direccion Partida</h6>
                    <vs-input class="w-full" v-model="transportista.dir_partida" :disabled="respuesta"/>
                </div>
                
            </div>
            <vs-divider position="left">
                <h3>Cliente</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full mb-6 relative" v-if="cliente.tipo">
                    <div class="vx-row">
                        <div v-if="respuesta==false">
                            <a class="flex items-center buscar_otro" @click="cliente.tipo=false" > Agregar otro Cliente </a>
                        </div>
                        
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Nombre:" disabled v-bind:value="cliente.nombre" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Teléfono:" disabled v-bind:value="cliente.telefono" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Email:" disabled v-bind:value="cliente.email" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Tipo de Identificación:" disabled v-bind:value="cliente.tipo_identificacion" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Identificación:" disabled v-bind:value="cliente.identificacion" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Dirección:" disabled v-bind:value="cliente.direccion" />
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative" v-else>
                    <vs-input class="w-full busqueda_cliente" :disabled="respuesta" placeholder="Escoge un cliente Para agregar un Comprobante" v-model="cliente.busqueda" @keyup="listar_cliente(cliente.busqueda)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_cliente_ls" style="display: none;">
                        <div v-if="preloader.cliente">
                            <ul class="ul_busqueda_lista" v-if="cliente.clientes.length">
                                <li v-for="(tr,index) in cliente.clientes" :key="index" @click="seleccionar_cliente(tr)"> {{ tr.nombre }} </li>
                            </ul>
                            <ul class="ul_busqueda_lista" v-else>
                                <li @click="abrir_modal_crear_cliente()"> ESTE CLIENTE NO SE ENCUENTRA REGISTRADO, AGREGAR NUEVO CLIENTE </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div v-show="error" v-if="!cliente.tipo">
                    <div v-for="err in error.cliente.tipo" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full relative" v-if="producto.tipo">
                    <vs-table hoverFlat :data="producto.lista_productos" style="font-size: 12px;">
                        <template slot="thead">
                            <vs-th>CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th>CANTIDAD</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td class="nombrearreglo">
                                    <vs-input class="w-full derecha font-small" v-model="tr.nombre" :disabled="respuesta"/>
                                </vs-td>
                                <vs-td>
                                    <vs-select class="selectExample w-full" :disabled="respuesta" placeholder="Seleccione un proyecto" v-model="tr.proyecto">
                                        <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div v-for="err in tr.errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:70px!important;">
                                    <vs-input class="w-full derecha" :disabled="respuesta" v-model="tr.cantidad"/>
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>

                                
                                <feather-icon icon="TrashIcon" svgClasses="w-6 h-6 hover:text-danger stroke-current cursor-pointer" class="eliminar_producto_icono" @click="eliminar_producto(index)"/>
                                
                            </vs-tr>
                        </template>
                    </vs-table>
                    
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative">
                    <vs-input class="w-full busqueda_cliente focuspr" :disabled="respuesta" placeholder="Agrega productos a esta factura" v-model="producto.busqueda" @keyup="listar_productos(producto.busqueda)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_producto_ls" style="display: none;">
                        <div v-if="preloader.productos">
                            <ul class="ul_busqueda_lista">
                                <li v-for="(tr,index) in producto.productos" :key="index" @click="seleccionar_productos(tr)"> <span v-if="tr.cod_alterno" style="font-weight: bold;" >CodAlt: {{ tr.cod_alterno }} - </span> <span v-else style="font-weight: bold;">CódPrin: {{tr.cod_principal}} - </span><span style="font-weight: bold;">{{ tr.nombre }}</span> <span v-if="tr.presentacion" style="font-weight: bold;" > - Presentación: {{ tr.presentacion }} </span> <span v-if="tr.nombrebodega"> - <span style="font-size: 12px;">Bodega: {{tr.nombrebodega}}</span> - <span style="font-size: 12px;">cantidad: {{tr.cantidad}}</span></span> </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                    <div v-show="error" v-if="!producto.busqueda">
                        <div v-for="err in error.producto.busqueda" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col w-full">
                    <div class="vx-row" v-if="producto.tipo">
                        <div class="vx-col sm:w-1/2 w-full">
                            <h6>Observaciones:</h6>
                            <vs-textarea  class="w-full" :disabled="respuesta" v-model="observacion"  rows="5"/>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>TOTAL CANTIDADES <span>{{ formulas }}</span></div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="vx-col w-full mt-5">
                    <vs-button color="success" type="filled" v-if="$route.params.id" :disabled="disabled_button" @click="editar_guia()">GUARDAR</vs-button>
                    <vs-button color="success" type="filled" v-else :disabled="disabled_button" @click="validar_clave()">GUARDAR</vs-button>
                    <vs-button color="danger" type="filled" to="/facturacion/guia_remision">CANCELAR</vs-button>
                </div>
                <vs-popup :title="modal.titulo" :active.sync="modal.abrir" class="modal-xl">
                    <div class="con-exemple-prompt">
                        <ClienteVue v-if="modal.abrir==true" modalactive=1 @CloseCLient="guardar_cliente"></ClienteVue>
                    </div>
                </vs-popup>
            </div>
        </vx-card>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import $ from "jquery";
import { log } from "util";
const axios = require("axios");
const {rutasEmpresa:{DATA_EMPRESA}} = require("../../../../../../config-routes/config");
import script_comprobantes from '../../../../factura.js';
import ClienteVue from "../Clientes/ClientesAgregar.vue";

export default {
    components: {
        flatPickr,
        "v-select": vSelect,
        ClienteVue
    },
    data(){
        return{
            error:{
                error:0,
                cliente:{
                    tipo:[],

                },
                producto:{
                    busqueda:[]
                },
                tipo_emision:[],
                documento:[],
                fecha_doc:[]
            },
            disabled_button:false,
            configdateTimePicker: {
                locale: SpanishLocale
            },
            preloader:{
                cliente:false,
                productos:false,
                facturas:false
            },
            respuesta:false,
            recupera_documento:false,
            proforma:0,
            fecha_doc:"",
            id_documento:null,
            documento:"",
            ambiente:"",
            facturas:[],
            tipo_emision:"",
            empresa:[],
            modal:{
                abrir:false,
                titulo:'',
                tipo:0
            },
            observacion:"",
            tipo_identificacion_menu: [
                { text: "Cédula de Identidad", value: "Cédula de Identidad" },
                { text: "Ruc", value: "Ruc" },
                { text: "Pasaporte", value: "Pasaporte" }
            ],
            transportista: {
                nombre_transporte: "",
                tipo_identificacion_transporte: null,
                identificacion_transporte: "",
                fecha_inicio_transporte: moment().format('YYYY-MM-DD'),
                fecha_fin_transporte: "",
                placa_transporte: "",
                documento_aduanero: "",
                motivo_translado: "",
                clave_acceso:"",
                otro_destino:null,
                otra_dir_partida:null,
                destino:"",
                dir_partida:""
            },
            cliente:{
                tipo:false,
                busqueda:'',
                clientes:[],
                id_cliente:null,
                nombre:'',
                telefono:'',
                email:'',
                tipo_identificacion:'',
                identificacion:'',
                direccion:'',
            },
            crear_cliente:{
                codigo:'',
                nombre:'',
                tipo_identificacion:'',
                identificacion:'',
                grupo_cliente:'',
                tipo_cliente:'',
                grupo_tributario:'',
                direccion:'',
                provincia:null,
                canton:null,
                parroquia:null,
                parte_relacionada:'',
                e_mail:[],
                telefono:'',
                contacto:'',
                vendedor:null,
                estado:null,
                descuento:'',
                cuenta_contable:'',
                id_cuenta_contable:null,
                numero_pagos:'',
                lista_precios:'',
                forma_pago:null,
                limite_credito:'',
                comentario:'',
            },
            producto:{
                tipo:false,
                busqueda:'',
                productos:[],
                lista_productos:[],
                id_producto:null,
                codigo:'',
                nombre:'',
                cantidad:'',
                precio:0,
                descuento:0,
                subtotal:0,
            },
            proyectos_menu:[],
            errornombre_transporte: [],
            errortipo_identificacion_transporte: [],
            erroridentificacion_transporte: [],
            errorfecha_inicio_transporte: [],
            errorfecha_fin_transporte: [],
            errorplaca_transporte: [],

        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        formulas(){
            var total=0;
            this.producto.lista_productos.forEach(el => {
                total+=parseFloat(el.cantidad);
            });

            return total;
        },
    },
    methods:{
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return (new Array(width + (/\./.test(number) ? 2 : 1)).join("0") +number);
            }
            return number + "";
        },
        Modulo11(claveAcceso) {
            //El código de control es un mecanismo de detección de errores utilizado para verificar la corrección de un dato, generalmente en soporte informático. Los dígitos de control se usan principalmente para detectar errores en el tecleo o transmisión de los datos.
            //Generalmente consisten en uno o más caracteres numéricos o alfabéticos añadidos al dato original y calculados a partir de este mediante un determinado algoritmo. Algunos de los ejemplos de uso frecuentes son los números de identificación personal, códigos de barras, tarjetas de crédito y códigos bancarios.
            /*Pasos 1 y 2
                    +---+---+---+---+---+---+---+---+   +---+
                    | 4 | 1 | 2 | 6 | 1 | 5 | 3 | 3 | - | ? |
                    +---+---+---+---+---+---+---+---+   +---+
                    |   |   |   |   |   |   |   |
                    x3  x2  x7  x6  x5  x4  x3  x2
                    |   |   |   |   |   |   |   |
                    =12  =2 =14 =36  =5 =20  =9  =6

            Paso 3   12  +2 +14 +36  +5 +20  +9  +6 = 104

            Paso 4   104 mod 11 = 5     (ya que 104 = 11 x 9 + 5)

            Paso 5   11 - 5 = 6

            Resultado = 41261533-6*/


            //mas info a https://es.wikipedia.org/wiki/C%C3%B3digo_de_control#:~:text=Cada%20d%C3%ADgito%20del%20n%C3%BAmero%20base%20se%20multiplica%20por%20el%20factor,la%20divisi%C3%B3n%20entera%20entre%2011.

            var multiplos = [2, 3, 4, 5, 6, 7];
            var i = 0;
            var cantidad = claveAcceso.length;
            var total = 0;
            while (cantidad > 0) {
                total += parseInt(claveAcceso.substring(cantidad - 1, cantidad)) * multiplos[i];
                //console.log(total + " - " + (claveAcceso.substring(cantidad - 1, cantidad) *multiplos[i]) + " - " + claveAcceso.substring(cantidad - 1, cantidad) + " - " + multiplos[i]);
                i++;
                i = i % 6;
                cantidad--;
            }
            var modulo11 = 11 - (total % 11);
            if (modulo11 == 11) {
                modulo11 = 0;
            } else if (modulo11 == 10) {
                modulo11 = 1;
            }
            return modulo11;
        },
        listarclave_guia(){
            if (!this.$route.params.id) {
                var url = "/api/listarclave_guia/" + this.usuario.id;
                axios.get(url).then(res => {
                    var fecha = moment(this.transportista.fecha_inicio_transporte).format("DDMMYYYY");
                    var rec = res.data.recupera[0];
                    var secuencial = this.zeroFill(res.data.secuencial, 9);
                    var establecimiento = this.zeroFill(rec.establecimiento, 3);
                    var punto_emision = this.zeroFill(rec.punto_emision, 3);
                    var codigoacc = fecha+"06"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                    var acceso = this.Modulo11(codigoacc);
                    this.transportista.clave_acceso = codigoacc + acceso;
                });
                return false;
            }
        },
        abrir_modal_crear_cliente(){
            this.modal = {
                abrir: true,
                titulo: "Crear Cliente",
                tipo: 1,
            }
            this.crear_cliente = {
                codigo:'',
                nombre:'',
                tipo_identificacion:'',
                identificacion:'',
                grupo_cliente:'',
                tipo_cliente:'',
                grupo_tributario:'',
                direccion:'',
                provincia:null,
                canton:null,
                parroquia:null,
                parte_relacionada:'',
                e_mail:'',
                telefono:'',
                contacto:'',
                vendedor:null,
                estado:null,
                descuento:'',
                cuenta_contable:'',
                id_cuenta_contable:null,
                numero_pagos:'',
                lista_precios:'',
                forma_pago:null,
                limite_credito:'',
                comentario:'',
            }
        },
        seleccionar_cliente(tr){
            this.cliente.clientes = [];
            this.cliente.busqueda = '';
            this.cliente.tipo = true;
            this.cliente.id_cliente = tr.id_cliente;
            this.cliente.nombre = tr.nombre;
            this.cliente.telefono = tr.telefono;
            this.cliente.email = tr.email;
            this.cliente.tipo_identificacion = tr.tipo_identificacion;
            this.cliente.identificacion = tr.identificacion;
            this.cliente.direccion = tr.direccion;
            //this.anticipover(tr.id_cliente);
        },
        listar_cliente(buscar){
            this.preloader.productos=false;
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.preloader.cliente=false;
                axios.get('/api/notacredito/listar_cliente?buscar=' + buscar + '&empresa=' + this.usuario.id_empresa).then( ({data}) => {
                    this.cliente.clientes = data;
                    $(".busqueda_cliente_ls").show();
                    setTimeout(() => {
                        this.preloader.cliente=true;
                    }, 100);
                }).catch( error => {
                    console.log(error);
                });
            }, 800);
        },
        listar_productos(buscar){
            this.preloader.productos=false;
            $(".busqueda_producto_ls").show();
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                axios.post('/api/factura_venta/listar_productos',{
                        buscar: buscar,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                        cliente: this.cliente.id_cliente,
                        id_pto_emision:this.usuario.id_punto_emision    
                }).then( ({data}) => {
                    this.preloader.productos=true;
                    if(data.length>=1){
                        if(data[0].codigo_barras == buscar && data[0].codigo_barras.length>=1){
                            this.seleccionar_productos(data[0]);
                            this.producto.busqueda = '';
                            return;
                        }else{
                            this.producto.productos = data;
                            return;
                        }
                    }else{
                        this.producto.productos = [];
                        return;
                    }
                }).catch( error => {
                    console.log(error);
                    this.preloader.productos=true;
                });
            }, 800);
        },
        seleccionar_productos(tr){
            this.producto.productos = [];
            this.producto.busqueda = '';
            this.producto.tipo = true;
            var subtotal =  (0).toFixed(2);
            var cantidad = 1;

            if(isNaN(parseInt(tr.existencia_total))){
                tr.existencia_total='';
            }
            if(isNaN(parseFloat(tr.precio))){
               tr.precio='';
            }
            if(isNaN(parseFloat(tr.descuento))){
                tr.descuento ='';
            }
            if(tr.sector==1 && (tr.id_producto_bodega==="undefined" || tr.id_producto_bodega == null)){
                cantidad = 1;
                tr.cantidad = 1;
                tr.id_producto_bodega = null;
                tr.nombrebodega = null;
            }
            if(tr.cantidad<=1){
                cantidad = 0;
            }
            this.producto.lista_productos.push({
                id_producto_bodega:tr.id_producto_bodega,
                nombrebodega:tr.nombrebodega,
                id_producto: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cantidad: cantidad,
                cantidadreal: tr.cantidad,
                precio: tr.precio,
                descuento: tr.descuento,
                p_descuento: 1,
                subtotal: subtotal,
                iva: tr.iva,
                ice: tr.ice,
                sector: tr.sector,
                nombreice: tr.nombreice,
                total_ice: tr.total_ice,
                proyecto: this.proyectos_menu[0].id_proyecto,
                id_bodega_prod:tr.id_bodega
            });
            $(".focuspr").focus();
        },
        listar_creacion_cliente(){
            axios.get('/api/notacredito/listar_creacion_cliente/'+this.usuario.id_empresa).then( ({data}) => {
                // this.grupo_cliente_menu = data.grupo_cliente;
                // this.tipo_cliente_menu = data.tipo_cliente;
                // this.provincia_menu = data.provincia;
                // this.crear_cliente.provincia=data.provincia[16].id_provincia;
                // this.vendedor_menu = data.vendedor;
                // this.forma_pago_menu = data.forma_pago;
                this.proyectos_menu = data.proyectos;
                this.empresa = data.empresa;
                this.ambiente = data.empresa.ambiente;
                this.tipo_emision=data.empresa.tipo_emision;
                //console.log("id_provincia:"+this.crear_cliente.provincia);
            }).catch( error => {
                console.log(error);
            });
        },
        validarcantidad(tr, index){
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                if(tr.cantidadreal == 0 && tr.sector==1){
                    this.producto.lista_productos[index].cantidad = 0;
                    this.$vs.notify({
                        time: 5000,
                        title: "Error en cantidad",
                        text: "el producto " + tr.nombre + " no tiene unidades existentes en bodega 1",
                        color: "danger"
                    });
                }else if(this.empresa.negativo==0 && tr.sector==1){
                    if(parseFloat(tr.cantidad)>parseFloat(tr.cantidadreal)){
                        if(tr.cantidadreal>=1){
                            this.producto.lista_productos[index].cantidad = this.producto.lista_productos[index].cantidadreal;
                            this.$vs.notify({
                                time: 5000,
                                title: "Error en cantidad",
                                text: "La cantidad ingresada excede la cantidad existente del producto " + tr.nombre + ". <br> Cantidad máxima " + tr.cantidadreal,
                                color: "warning"
                            });
                        }else{
                            this.producto.lista_productos[index].cantidad = 0;
                            this.$vs.notify({
                                time: 5000,
                                title: "Error en cantidad",
                                text: "el producto " + tr.nombre + " no tiene unidades existentes en bodega 2",
                                color: "warning"
                            });
                        }
                    }
                }
            }, 300);
        },
        documentos(){
            var bs="";
            if(this.proforma==1){
                bs = this.documento;
            }else{
                bs = this.documento.replace(/-/g,"");
            }
            
            if(this.documento.length==15 || this.documento.length==17 || this.proforma==1){
                axios.post('/api/guia_remision/buscardocumento', {
                    factura:bs,
                    id_empresa: this.usuario.id_empresa,
                    proforma:this.proforma
                }).then( ({data}) => {
                    if(data=='error'){
                        this.$vs.notify({
                            title: "Factura erronea",
                            text: "Esta factura no consta en nuestro sistema",
                            color: "danger"
                        }); 
                    }else{
                        this.fecha_doc = data.factura.fecha_emision;
                        //this.factura.fecha = moment().format();
                        this.proyectos = data.factura.id_proyecto;
                        this.producto.tipo = 1;
                        this.producto.lista_productos=[];
                        data.detalle.forEach((el,index) => {
                            this.producto.lista_productos.push({
                                id_producto_bodega:el.id_producto_bodega,
                                id_producto: el.id_producto,
                                cod_alterno: el.cod_alterno,
                                cod_principal: el.cod_principal,
                                nombre: el.nombre,
                                cantidad: el.cantidad,
                                precio: el.precio,
                                descuento: el.descuento,
                                p_descuento: el.p_descuento,
                                subtotal: el.cantidad*el.precio,
                                iva: el.id_iva,
                                ice: el.id_ice,
                                sector: el.sector,
                                proyecto:el.id_proyecto
                            });
                        }); 
                        this.cliente.tipo = true;
                        this.cliente.id_cliente = data.cliente.id_cliente;
                        this.cliente.nombre = data.cliente.nombre;
                        this.cliente.telefono = data.cliente.telefono;
                        this.cliente.email = data.cliente.email;
                        this.cliente.tipo_identificacion = data.cliente.tipo_identificacion;
                        this.cliente.identificacion = data.cliente.identificacion;
                        this.cliente.direccion = data.cliente.direccion;
                        this.id_factura = data.factura.id_factura;
                        $(".busqueda_factura_ls").hide();
                    }
                }); 
            }
        },
        listar_facturas(buscar){
            this.preloader.facturas=false;
            $(".busqueda_factura_ls").show();
            if (this.timeout) {  
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                var bs = buscar.replace(/-/g,"");
                axios.post('/api/guia_remision/listar_documentos',{
                        buscar: bs,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                }).then( ({data}) => {
                    this.facturas = data;
                    this.preloader.facturas=true;
                    return;
                }).catch(() =>{
                    this.preloader.facturas=true;
                });
            }, 800);
        },
        seleccionar_factura(tr){
            this.id_documento=tr.id_factura;
            if(tr.codigo!==null){
                this.documento = tr.codigo;
                this.proforma=1;
            }else{
                this.documento = tr.clave_acceso.substring(24,39);
                this.proforma=0;
            }
            
            this.documentos();
            $(".busqueda_factura_ls").hide();
        },
        guardar_cliente(value){
            console.log("entro guardar cliente");
            if(value!==null){
                console.log("entro guardar cliente 2");
                // this.cliente.id_cliente=value.id_cliente;
                // this.cliente.nombre=value.nombre;
                // this.cliente.telefono=value.telefono;
                // this.cliente.email=value.email.toString();
                // this.cliente.tipo_identificacion=value.tipo_identificacion;
                // this.cliente.identificacion=value.ruc_ci;
                // this.cliente.direccion=value.direccion;
                // this.cliente.tipo=true;
                // this.cliente.busqueda="";
                this.seleccionar_cliente(value);
                this.modal.abrir=false;
            }else{
                this.modal.abrir=false;
            }

        },
        validar_clave_guia(){
            return new Promise((resolve,reject)=>{
                axios
                .post("/api/guia_remision/guia_remision_clave", {
                    transportista: this.transportista,
                    id_empresa: this.usuario.id_empresa
                })
                .then(({ data }) => {
                    if (data.guia == "repetido") {
                        //genera la url del guardado de la guia
                        var url = "/api/listarclave_guia/" + this.usuario.id;
                        axios.get(url).then(res => {
                            var fecha = moment(this.transportista.fecha_inicio_transporte).format("DDMMYYYY");
                            var rec = res.data.recupera[0];
                            var secuencial = this.zeroFill(res.data.secuencial, 9);
                            var establecimiento = this.zeroFill(rec.establecimiento, 3);
                            var punto_emision = this.zeroFill(rec.punto_emision, 3);
                            var codigoacc = fecha+"06"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                            var acceso = this.Modulo11(codigoacc);
                            this.transportista.clave_acceso = codigoacc + acceso;
                            resolve(this.transportista.clave_acceso);
                        });
                    }else{
                        resolve(this.transportista.clave_acceso);
                    }

                })
                .catch(error => {
                    this.disabled_button=false;
                    reject(error);
                    this.$vs.notify({
                        time: 8000,
                        title: "Error en el registro",
                        text:
                            "El sistema tiene problemas de iniciar, intente mas tarde",
                        color: "danger"
                    });
                });
            });
            
        },
        validar_clave(){
            this.validar_clave_guia()
            .then(value=>{
                this.guardar_guia();
            }).catch(error=>{
                console.log("[ERROR validar_clave_guia]"+error);
            });
        },
        guardar_guia(){
            this.disabled_button=true;
            if(this.validacion()){
                this.disabled_button=false;
                return;
            }
            axios.post("/api/guia_remision/guardar_guia",{
                transportista:this.transportista,
                cliente:this.cliente.id_cliente,
                productos:this.producto.lista_productos,
                usuario:this.usuario.id,
                id_empresa:this.usuario.id_empresa,
                id_punto_emision:this.usuario.id_punto_emision,
                id_establecimiento:this.usuario.id_establecimiento,
                id_documento:this.id_documento,
                observacion:this.observacion
            })
            .then(({data})=>{
                if(data=="existe guia factura"){
                        this.$vs.notify({
                            time: 8000,
                            text: "Ya existe Una Guia Remision con esta fatura",
                            color: "danger"
                        });
                        this.disabled_button=false;
                        return;
                }
                    var recupera_guia = data.guia[0];
                    this.$vs.notify({
                            time: 8000,
                            title: "Enviando Guia de remisión",
                            text: "La Guia de remisión esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                            color: "warning"
                        });
                    axios.post('/api/factura/xml_guia', recupera_guia).then(res => {
                        var password = recupera_guia.pass_firma;
                        var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + recupera_guia.firma;
                        console.log("Entro xml");
                        this.enviarguia(firma, password, recupera_guia);
                        // var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/" + this.factura.clave_acceso +".xml";
                        // var tipo = "factura_venta";
                        // var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/";
                        // var fecha_actual = moment(recupera_guia.fecha_inicio_tr).format('LL');
                        
                    });
                    
                    //this.$router.push(`/facturacion/guia_remision`);
            })
            .catch(error=>{
                this.$vs.notify({
                    text: error,
                    color: "danger"
                }); 
            });
        },
        validacion(){
            this.error={
                error:0,
                cliente:{
                    tipo:[],
                    
                },
                producto:{
                    busqueda:[]
                },
                tipo_emision:[],
                documento:[],
                fecha_doc:[]
            };
            if(!this.tipo_emision){
                this.error.factura.fecha_emision.push("Campo Obligatorio");
                this.error.error = 1;
            }
            if (!this.transportista.nombre_transporte) {
                    this.errornombre_transporte.push("Campo obligatorio");
                    this.error.error = 1;
            }
            if (!this.transportista.tipo_identificacion_transporte) {
                this.errortipo_identificacion_transporte.push(
                    "Campo obligatorio"
                );
                this.error.error = 1;
            }
            if (!this.transportista.identificacion_transporte) {
                this.erroridentificacion_transporte.push(
                    "Campo obligatorio"
                );
                this.error.error = 1;
            }
            if (!this.transportista.fecha_inicio_transporte) {
                this.errorfecha_inicio_transporte.push("Campo obligatorio");
                this.error.error = 1;
            }
            if (!this.transportista.fecha_fin_transporte) {
                this.errorfecha_fin_transporte.push("Campo obligatorio");
                this.error.error = 1;
            }
            if (!this.transportista.placa_transporte) {
                this.errorplaca_transporte.push("Campo obligatorio");
                this.error.error = 1;
            }
            if (!this.cliente.tipo) {
                this.error.cliente.tipo.push(
                    "Debe agregar un cliente al comprobante"
                );
                this.error.error = 1;
            }
            if (!this.producto.tipo) {
                this.error.producto.busqueda.push(
                    "Debe agregar un producto al comprobante"
                );
                this.error.error = 1;
            }
            for (var i = 0; i < this.producto.lista_productos.length; i++) {
                this.producto.lista_productos[i].errorcantidad = [];
                this.producto.lista_productos[i].errorprecio = [];
                this.producto.lista_productos[i].errorproyecto = [];
                if (!this.producto.lista_productos[i].cantidad) {
                    this.producto.lista_productos[i].errorcantidad.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                } else {
                    if (
                        parseFloat(this.producto.lista_productos[i].cantidad) <=
                        0
                    ) {
                        this.producto.lista_productos[i].errorcantidad.push(
                            "Obligatorio"
                        );
                        this.error.error = 1;
                    }
                }
                
                if (!this.producto.lista_productos[i].proyecto) {
                    this.producto.lista_productos[i].errorproyecto.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                }
            }
            if (this.error.error) {
                setTimeout(() => {
                    var valor =
                        $(".text-danger:first-child").offset().top - 300;
                    $("html, body").animate(
                        {
                            scrollTop: valor
                        },
                        500
                    );
                }, 50);
            }
            return this.error.error;
        },
        enviarguia(firma, password, datag){
            var tipo_guia = "guia_remision_venta";
            var urlxmlg = "/api/factura/xml_guia";
            axios.post(urlxmlg, datag).then(res => {
                var ruta_factura_guia = DATA_EMPRESA +this.usuario.id_empresa +"/comprobantes/guia/" + datag.clave_acceso +".xml";
                var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/guia/";
                var fecha_actual = moment(datag.fecha_inicio_tr).format('LL');
                console.log("Entro enviarguia");
                this.crearfacturacion_guia(firma, password, ruta_factura_guia, tipo_guia, this.usuario, datag.id_guia, carpeta, fecha_actual, '0.00', datag.logo, datag.nombre_empresa);
            }).catch( error => {
                this.disabled_button=false;
                this.enviado();
                this.$vs.notify({
                    time: 8000,
                    title: "Error en el envio al SRI",
                    text: 'La Guia no pudo ser enviada, intente mas tarde',
                    color: "danger"
                });
            });
        },
        async crearfacturacion_guia(firma, password, factura, tipo, usuario, id_factura, carpeta, fecha, valor, logo, nombre_empresa){
            try {
                console.log("Entro crearfacturacion_guia");
                let {data:comprobante} = await script_comprobantes.obtener_comprobante_firmado.getAll({ factura:factura, id_factura:id_factura, tipo:tipo });
                let {resultado:contenido} = await script_comprobantes.lectura_firma.getAll({ firma:firma, id_factura:   id_factura, tipo:tipo });
                let {data:certificado} = await script_comprobantes.firmar_comprobante.getAll({ contenido:contenido[0], password:password, comprobante:comprobante, id_factura:id_factura, tipo:tipo });
                let {data:quefirma} = await script_comprobantes.verificar_firma.getAll({ comprobante:comprobante, mensaje:certificado, tipo:tipo, id_factura:id_factura, carpeta:carpeta });
                let {data:validado} = await script_comprobantes.validar_comprobante.getAll({ comprobante:comprobante, tipo:tipo, id_factura:id_factura, carpeta:carpeta, id_empresa:usuario.id_empresa });
                let {data:recibida} = await script_comprobantes.autorizar_comprobante.getAll({ comprobante:comprobante, validado:validado, usuario:usuario, tipo:tipo, id_factura:id_factura, carpeta:carpeta, fecha:fecha, valor:valor, logo:logo, nombre_empresa:nombre_empresa });
                let {data:registrado} = await script_comprobantes.autorizado_comprobante.getAll({ recibida:recibida, tipo:tipo, id_factura:id_factura });
                this.$vs.notify({
                    time: 8000,
                    title: "Guia de remisión Enviada",
                    text:"La Guia de remisión se generó exitosamente",
                    color: "success"
                });
                
                this.enviado();
            } catch(error) {
                this.disabled_button=false;
                this.$vs.notify({
                    time: 20000,
                    title: error.mensaje,
                    text: error.informacion,
                    color: "danger"
                });
                this.enviado();
            }
        },
        enviado(){
            this.$router.push(`/facturacion/guia_remision`);
        },
        seachRuc(ruc) {
            axios
                .get(`/auth_ruc_sri/${ruc}`)
                .then(res => {
                    if (res.data == "Ruc no existe") {
                        this.$vs.notify({
                            text: "No existe Este RUC",
                            color: "danger"
                        });
                        return;
                    } else {
                        // this.contenidocuenta = res.data.recupera;
                        this.transportista.nombre_transporte = res.data.razon_social;
                        
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        recuperar_guia(){
            if (this.$route.params.id) {
                axios.get("/api/guia_remision/recuperar/"+this.$route.params.id).then(({data})=>{
                    // cliente:{
                    //     tipo:false,
                    //     busqueda:'',
                    //     clientes:[],
                    //     id_cliente:null,
                    //     nombre:'',
                    //     telefono:'',
                    //     email:'',
                    //     tipo_identificacion:'',
                    //     identificacion:'',
                    //     direccion:'',
                    // },

                    // transportista: {
                    //     nombre_transporte: "",
                    //     tipo_identificacion_transporte: null,
                    //     identificacion_transporte: "",
                    //     fecha_inicio_transporte: moment().format('YYYY-MM-DD'),
                    //     fecha_fin_transporte: "",
                    //     placa_transporte: "",
                    //     documento_aduanero: "",
                    //     motivo_translado: "",
                    //     clave_acceso:"",
                    //     otro_destino:null,
                    //     destino:""
                    // },
                    this.transportista.nombre_transporte=data.guia[0].razon_social_tr;
                    this.transportista.tipo_identificacion_transporte=data.guia[0].tipo_identificacion_tr;
                    this.transportista.identificacion_transporte=data.guia[0].identificacion_tr;
                    this.transportista.fecha_inicio_transporte=data.guia[0].fecha_inicio_tr;
                    this.transportista.fecha_fin_transporte=data.guia[0].fecha_fin_tr;
                    this.transportista.placa_transporte=data.guia[0].placa_tr;
                    this.transportista.documento_aduanero=data.guia[0].doc_aduanero_tr;
                    this.transportista.motivo_translado=data.guia[0].motivo_translado_tr;
                    this.transportista.clave_acceso=data.guia[0].clave_acceso;
                    this.transportista.otro_destino=data.guia[0].otro_destino_tr;
                    this.transportista.destino=data.guia[0].destino_tr;
                    this.transportista.otra_dir_partida=data.guia[0].otra_dir_partida_tr;
                    this.transportista.dir_partida=data.guia[0].dir_partida_tr;
                    this.observacion=data.guia[0].observacion_tr;

                    this.cliente.tipo=true;
                    this.cliente.id_cliente=data.cliente[0].id_cliente;
                    this.cliente.nombre=data.cliente[0].nombre;
                    this.cliente.telefono=data.cliente[0].telefono;
                    this.cliente.email=data.cliente[0].email;
                    this.cliente.tipo_identificacion=data.cliente[0].tipo_identificacion;
                    this.cliente.identificacion=data.cliente[0].identificacion;
                    this.cliente.direccion=data.cliente[0].direccion;
                    this.producto.tipo=true;
                    // data.producto.forEach(el=>{
                    //     this.producto.lista_productos.id_producto=el.id_producto;
                    //     this.producto.lista_productos.cod_principal=el.cod_principal;
                    //     this.producto.lista_productos.nombre=el.nombre;
                    //     this.producto.lista_productos.proyecto=el.id_proyecto;
                    //     this.producto.lista_productos.cantidad=el.cantidad;
                    //     this.producto.lista_productos.id_detalle_guia_remision=el.id_detalle_guia_remision;
                    // });
                    this.producto.lista_productos=data.producto;
                    this.documento=data.guia[0].clave_acceso_doc;
                    this.fecha_doc=data.guia[0].fecha_emision;
                    this.id_documento=data.guia[0].id_factura;
                    if(data.guia[0].respuesta!=="Enviado" && (data.guia[0].estado==null || data.guia[0].estado==1) ){
                        this.respuesta=false;
                    }else{
                        this.respuesta=true;
                    }
                    // producto:{
                    //     tipo:false,
                    //     busqueda:'',
                    //     productos:[],
                    //     lista_productos:[],
                    //     id_producto:null,
                    //     codigo:'',
                    //     nombre:'',
                    //     cantidad:'',
                    //     precio:0,
                    //     descuento:0,
                    //     subtotal:0,
                    // },
                }).catch(err=>{
                    
                });
            }
        },
        editar_guia(){
            this.disabled_button=true;
            if(this.validacion()){
                this.disabled_button=false;
                return;
            }
            axios.put("/api/guia_remision/editar_guia",{
                transportista:this.transportista,
                cliente:this.cliente.id_cliente,
                productos:this.producto.lista_productos,
                usuario:this.usuario.id,
                id_empresa:this.usuario.id_empresa,
                id_punto_emision:this.usuario.id_punto_emision,
                id_establecimiento:this.usuario.id_establecimiento,
                id_documento:this.id_documento,
                id_guia:this.$route.params.id,
                observacion:this.observacion
            })
            .then(({data})=>{
                    this.$vs.notify({
                        title: "Guia Remision Guardada",
                        text: "La Guia Remision ha sido guardada con exito",
                        color: "success"
                    }); 
                    var recupera_guia = data.guia[0];
                    if(data.respuesta!=="Enviado"){
                        this.$vs.notify({
                            time: 8000,
                            title: "Enviando Guia de remisión",
                            text: "La Guia de remisión esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                            color: "warning"
                        });
                        axios.post('/api/factura/xml_guia', recupera_guia).then(res => {
                            var password = recupera_guia.pass_firma;
                            var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + recupera_guia.firma;
                            console.log("Entro xml");
                            this.enviarguia(firma, password, recupera_guia);
                            var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/" + this.factura.clave_acceso +".xml";
                            var tipo = "factura_venta";
                            var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/";
                            var fecha_actual = moment(recupera_guia.fecha_inicio_tr).format('LL');
                            
                        });
                    }
                    
                //this.$router.push(`/facturacion/guia_remision`);
            })
            .catch(error=>{
                this.$vs.notify({
                    text: error,
                    color: "danger"
                }); 
            });
        },
    },
    mounted(){
        this.listarclave_guia();
        this.listar_creacion_cliente();
        this.recuperar_guia();
    }
};
</script>
<style lang="scss">
    @import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
    .sindis .vs-input--input:focus {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .sindis .vs-input--input {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .nover > .icon-select {
        display: none;
    }
    .hovertrash:hover > .trasher {
        display: block !important;
    }
    .botonstl {
        height: 100%;
        width: 38px;
        border: 1px solid #635ace;
        background: transparent;
        color: #635ace;
        font-size: 16px;
        cursor: pointer;
    }
    .elejido {
        background: #635ace !important;
        color: #fff !important;
    }
    //Busqueda de comprobantes
    .busqueda_cliente input{
        height: 50px;
        padding-left: 45px!important;
    }
    .busqueda_cliente_icono{
        position: absolute!important;
        top: 11px;
        left: 25px;
    }
    .busqueda_lista{
        position: absolute;
        width: 97%;
        z-index: 9;
    }
    .ul_busqueda_lista{
        min-width: 160px;
        margin: -2px 0 0;
        list-style: none;
        font-size: 13.5px;
        text-align: left;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.15);
        border-radius: 2px;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        background-clip: padding-box;
    }
    .ul_busqueda_lista li{
        padding: 10px 16px;
        text-overflow: ellipsis;
        overflow: hidden;
        font-weight: 300;
        display: block;
        clear: both;
        line-height: 1.3;
        color: #333;
        white-space: nowrap;
        font-family: sans-serif;
    }
    .ul_busqueda_lista li:hover{
        background: rgba(16, 22, 58, 0.38);
        cursor: pointer;
        color:#fff;
    }
    .busqueda_cliente .input-span-placeholder{
        padding-left: 50px;
        margin-top: 3px;
    }
    .buscar_otro{
        position: absolute;
        margin-top: -35px;
        margin-left: 14px;
        cursor: pointer;
    }
    .eliminar_producto_icono{
        display: table-cell!important;
        height: 100%;
        vertical-align: middle;
        display:none;
    }
    .eliminar_producto_icono svg{
        margin-top:8px;
    }
    .fila_lista:hover .eliminar_producto_icono{
        display:block;
    }
    .cabezera_total span{
        float: right;
        margin-right: 25px;
    }
    .cabezera_total>div{
        margin-left: 20px;
        padding: 9px 3px;
    }
    .cabezera_total{
        margin-top:15px;
    }
    .vs-input--placeholder {
        top: 0px;
    }
    .modal-xl .vs-popup{
        width: 1250px;
    }
    .tablavista td{
        padding: 10px 15px;
    }
    .tablavista:hover{
        cursor:pointer;
        background: rgba(0,0,0,.2);
    }


    .vs-popup {
        width: 1060px !important;
    }
    .peque .vs-popup {
        width: 600px !important;
    }
    .sindis .vs-input--input:focus {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .sindis .vs-input--input {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .nover > .icon-select {
        display: none;
    }
    .hovertrash:hover > .trasher {
        display: block !important;
    }
    .botonstl {
        height: 100%;
        width: 38px;
        border: 1px solid #635ace;
        background: transparent;
        color: #635ace;
        font-size: 16px;
        cursor: pointer;
    }
    .elejido {
        background: #635ace !important;
        color: #fff !important;
    }
    .flexy > .vs-divider--text {
        display: flex;
    }
    .slide-fade-enter-active {
        transition: all 0.5s ease;
    }
    .slide-fade-leave-active {
        transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active for <2.1.8 */ {
        transform: translateX(10px);
        opacity: 0;
    }
    .btnmoremore{
        position: absolute;
        z-index: 9;
        right: 18px;
        margin-top: -45px;
        font-size: 31px;
        background: #fff;
        cursor: pointer;
    }

    .preloader {
        width: 50px;
        height: 50px;
        border: 10px solid #eee;
        border-top: 10px solid #666;
        border-radius: 50%;
        animation-name: girar;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }
    @keyframes girar {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    .lista_preloader{
        padding: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .derecha input{
        text-align: end;
    }
    .derecha .vs-input--placeholder{
        text-align: end;
    }
    .nombrearreglo span{
        font-size: 11px;
        letter-spacing: -0.5px;
        line-height: 15px;
        display: block;
    }
    .font-small input{
        font-size: 11px;
    }
    .font-small span{
        font-size: 11px;
    }
</style>