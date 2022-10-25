<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3 v-if="factura.clave_acceso">Nota de Crédito Compra</h3>
                <h3 v-else>Generando factura</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6>Fecha:</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full mt-1" v-model="factura.fecha" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha">
                        <div v-for="err in error.factura.fecha" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <h6 class="mb-1">Número de Factura</h6>
                    <vs-input class="w-full" maxlength="15" placeholder="000-000-000000000" disabled v-model="factura.documento"/>
                    <div v-show="error" v-if="factura.documento.length!=15">
                        <div v-for="err in error.factura.documento" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Fecha Doc:</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full" v-model="factura.fecha_doc" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha_doc">
                        <div v-for="err in error.factura.fecha_doc" :key="err" v-text="err" class="text-danger"></div>
                    </div> 
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <h6 class="mb-1">Motivo:</h6>
                    <vs-input class="w-full" v-model="factura.motivo" placeholder="Escriba el motivo del comprobante"/>
                    <div v-show="error" v-if="!factura.motivo">
                        <div v-for="err in error.factura.motivo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-1/2 mb-6">
                    <h6 class="mb-1">Número de Autorización:</h6>
                    <vs-input class="w-full" v-model="factura.autorizacion" placeholder="Escriba la autorización del comprobante"/>
                    <div v-show="error" v-if="!factura.autorizacion">
                        <div v-for="err in error.factura.autorizacion" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-1/2 mb-6">
                    <h6 class="mb-1">Número Nota Credito:</h6>
                    <vs-input class="w-full" maxlength="15" v-model="factura.nro_nota_credito" placeholder="Escriba el Nro de la Nota de Credito"/>
                    <div v-show="error" v-if="factura.nro_nota_credito.length!=15">
                        <div v-for="err in error.factura.nro_nota_credito" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
            </div>
            <vs-divider position="left" v-if="producto.tipo">
                <h3>Proveedor</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="producto.tipo">
                <div class="vx-col sm:w-full w-full mb-6 relative" v-if="proveedor.tipo">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Nombre:" disabled v-bind:value="proveedor.nombre_proveedor" />
                        </div> 
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Teléfono:" disabled v-bind:value="proveedor.telefono_prov" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Contacto:" disabled v-bind:value="proveedor.contacto" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Tipo de Identificación:" disabled v-bind:value="proveedor.tipo_identificacion" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Identificación:" disabled v-bind:value="proveedor.identif_proveedor" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Dirección:" disabled v-bind:value="proveedor.direccion_prov" />
                        </div>
                    </div>
                </div>
            </div>
            <vs-divider position="left" v-if="producto.tipo">
                <h3>Productos</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="producto.tipo">
                <div class="vx-col sm:w-full w-full relative">
                    <vs-table hoverFlat :data="producto.lista_productos" style="font-size: 12px;">
                        <template slot="thead">
                            <vs-th class="text-center">CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th class="text-center">CANTIDAD</vs-th>
                            <vs-th class="text-center">PRECIO</vs-th>
                            <vs-th class="text-center">DESCUENTO</vs-th>
                            <vs-th class="text-center">SUBTOTAL</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td class="text-center" style="width:100px!important;" v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td class="text-center" style="width:100px!important;" v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td>{{ tr.nombre }}</vs-td>
                                <vs-td>
                                    <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="tr.proyecto">
                                        <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div v-for="err in tr.errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:120px!important;">
                                    <vs-input class="w-full text-center" placeholder="$0.00" v-model="tr.cantidad"/>
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td class="text-center" style="width:120px!important;" v-if="tr.sector==1">
                                    $ {{ tr.precio }}
                                </vs-td>
                                <vs-td class="text-center" style="width:120px!important;" v-else>
                                    <vs-input class="w-full text-center" placeholder="$0.00" v-model="tr.precio"/>
                                </vs-td>
                                <vs-td class="text-center" style="width:100px!important;" v-if="tr.sector==1">
                                    <template v-if="tr.p_descuento==1"></template> 
                                    <span v-if="tr.descuento">{{ tr.descuento/tr.cantidad_dsc*tr.cantidad | currency}}</span><span v-else>0.00</span> 
                                    <template v-if="tr.p_descuento==0">%</template>
                                </vs-td>
                                <vs-td class="text-center" style="width:180px!important;" v-else>
                                    <vx-input-group>
                                        <vs-input
                                            class="w-full"
                                            placeholder="$0.00"
                                            v-model="tr.descuento"
                                            
                                        />
                                        <template slot="append">
                                            <div class="append-text btn-addon">
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 1
                                                    }"
                                                    @click="tr.p_descuento = 1"
                                                >
                                                    $
                                                </button>
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 0
                                                    }"
                                                    @click="tr.p_descuento = 0"
                                                >
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td class="text-center" style="width:120px!important;" v-if="tr.descuento_comp && tr.sector==1">
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - (tr.descuento_comp/tr.cantidad_dsc*tr.cantidad)).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - ((tr.cantidad * tr.precio * tr.descuento_comp)/100/tr.cantidad_dsc*tr.cantidad)).toFixed(2)}}
                                    </template>
                                </vs-td>
                                <vs-td class="text-center" style="width:120px!important;" v-else-if="tr.descuento_comp && tr.sector==2">
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - tr.descuento).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - ((tr.cantidad * tr.precio * tr.descuento)/100)).toFixed(2)}}
                                    </template>
                                </vs-td>
                                <vs-td class="text-center" style="width:120px!important;" v-else>
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) ).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio)).toFixed(2)}}
                                    </template>
                                </vs-td>
                                <feather-icon icon="TrashIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" class="eliminar_producto_icono" @click="eliminar_producto(index)"/>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative">
                    <vs-input
                        class="w-full busqueda_cliente focuspr"
                        placeholder="Agrega productos a esta factura"
                        v-model="producto.busqueda"
                        @keyup="listar_productos(producto.busqueda)"
                    />
                    <feather-icon
                        icon="SearchIcon"
                        svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer"
                        class="busqueda_cliente_icono"
                    />
                    <div
                        class="busqueda_lista busqueda_producto_ls"
                        style="display:none;"
                    >
                        <div v-if="preloader.productos">
                            <ul class="ul_busqueda_lista">
                                <li
                                    v-for="(tr, index) in producto.productos"
                                    :key="index"
                                    @click="seleccionar_productos(tr)"
                                >
                                    <span
                                        v-if="tr.cod_alterno"
                                        style="font-weight: bold;"
                                        >CodAlt: {{ tr.cod_alterno }} -
                                    </span>
                                    <span v-else style="font-weight: bold;"
                                        >CódPrin:
                                        {{ tr.cod_principal }} - </span
                                    ><span style="font-weight: bold;">{{
                                        tr.nombre
                                    }}</span>
                                    <span
                                        v-if="tr.presentacion"
                                        style="font-weight: bold;"
                                    >
                                        - Presentación: {{ tr.presentacion }}
                                    </span>
                                    <span
                                        v-if="tr.presentacion"
                                        style="font-weight: bold;"
                                    >
                                        - Presentación: {{ tr.presentacion }}
                                    </span>
                                    <span v-if="tr.nombrebodega"
                                        >-
                                        <span style="font-size: 12px;"
                                            >Bodega: {{ tr.nombrebodega }}</span
                                        ></span
                                    >
                                    <span
                                        v-if="
                                            !tr.nombrebodega && tr.sector == 1
                                        "
                                        >-
                                        <span style="font-size: 12px;"
                                            >Producto sin Bodega</span
                                        ></span
                                    >
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="vx-col w-full">
                    <div class="vx-row" v-if="producto.tipo">
                        <div class="vx-col sm:w-1/2 w-full">
                            <h6>Observaciones:</h6>
                            <vs-textarea  class="w-full"  v-model="factura.observacion"  rows="5"/>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>SUBTOTAL FINAL <span>$ {{ formulas.subtotal }}</span></div>
                                <div v-if="formulas.subtotal12>0">SUBTOTAL IVA 12% <span>$ {{ formulas.subtotal12 }}</span></div>
                                <div v-if="formulas.valor12>0">Valor IVA 12% <span>$ {{ formulas.valor12 }}</span></div>
                                <div v-if="formulas.subtotal0>0">SUBTOTAL IVA 0% <span>$ {{ formulas.subtotal0 }}</span></div>
                                <div v-if="formulas.no_impuesto>0">NO OBJETO DE IMPUESTO <span>$ {{ formulas.no_impuesto }}</span></div>
                                <div v-if="formulas.exento>0">EXENTO DE IVA <span>$ {{ formulas.exento }}</span></div>
                                <div>TOTAL DESCUENTO <span>$ {{ formulas.descuento }}</span></div>
                                <div>VALOR TOTAL <span>{{ formulas.total | currency}}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-col w-full">
                <vs-button color="success" type="filled" @click="guardar_factura()">GUARDAR</vs-button>
                <vs-button color="danger" type="filled" to="/compras/nota-credito">CANCELAR</vs-button>
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

export default {
    components: {
        flatPickr,
        "v-select": vSelect
    },
    data() {
        return {
            configdateTimePicker: {
                locale: SpanishLocale
            },
            factura:{
                fecha:moment().format('YYYY-MM-DD'),
                documento:'',
                fecha_doc:'',
                motivo:'',
                ambiente:'',
                tipo_emision:'Emision Normal',
                observacion:'',
                proyectos:null,
                autorizacion:'',
                nro_nota_credito:''
            },
            proveedor:{
                tipo:false,
                id_proveedor:'',
                nombre_proveedor:'',
                telefono_prov:'',
                contacto:'',
                tipo_identificacion:'',
                identif_proveedor:'',
                direccion_prov:'',
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
            empresa:[],
            error:{
                error:0,
                factura:{
                    tipo_emision:[],
                    fecha:[],
                    documento:[],
                    fecha_doc:[],
                    motivo:[],
                    autorizacion:[],
                    nro_nota_credito:[]
                },
                producto:{
                    busqueda:[]
                },
            },
            id_factcompra:null,
            //variables listar servicios
            preloader: {
                cliente: false,
                productos: false
            },
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
            var subtotal = 0;
            var subtotal12 = 0;
            var valor12 = 0;
            var subtotal0 = 0;
            var valor0 = 0;
            var no_impuesto = 0;
            var exento = 0;
            var descuento = 0;
            var total = 0;

            this.producto.lista_productos.forEach(el => {
                if(el.sector==2){
                    if (el.p_descuento == 1) {
                        console.log("Descuento_comp:"+el.descuento);
                        if(el.descuento>0 && el.descuento!==null){
                            console.log("Descuento_comp 3:"+el.descuento);
                            subtotal += el.precio * el.cantidad - el.descuento;
                        }else{
                            console.log("Descuento_comp 4:"+el.descuento);
                            subtotal += el.precio * el.cantidad;
                        }
                        //subtotal += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);
                        // if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        // if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        // if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        // if (el.iva == 4) {exento += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        if(isNaN(parseFloat(el.descuento))){
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad ;}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad ;}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad ;}
                            if (el.iva == 4) {exento += el.precio * el.cantidad ;}
                            descuento += 0;
                        }else{
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - el.descuento;}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - el.descuento;}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - el.descuento;}
                            if (el.iva == 4) {exento += el.precio * el.cantidad - el.descuento;}
                            descuento += parseFloat(el.descuento);
                        }
                    } else {
                        console.log("Descuento_comp 2:"+el.descuento);
                        if(el.descuento>0 && el.descuento!==null){
                            subtotal += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento));
                        }else{
                            subtotal += el.precio * el.cantidad;
                        }
                        
                        
                        if(isNaN((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100)){
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad ;}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad ;}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad ;}
                            if (el.iva == 4) {exento += el.precio * el.cantidad ;}
                            descuento += 0;
                        }else{
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento));}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento));}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento));}
                            if (el.iva == 4) {exento += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento));}
                            descuento += parseFloat((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento)));
                        }
                    }
                }else{
                    if (el.p_descuento == 1) {
                        console.log("Descuento_comp:"+el.descuento);
                        if(el.descuento>0 && el.descuento!==null){
                            console.log("Descuento_comp 3:"+el.descuento);
                            subtotal += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);
                        }else{
                            console.log("Descuento_comp 4:"+el.descuento);
                            subtotal += el.precio * el.cantidad;
                        }
                        //subtotal += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);
                        // if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        // if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        // if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        // if (el.iva == 4) {exento += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        if(isNaN(parseFloat(el.descuento))){
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad ;}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad ;}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad ;}
                            if (el.iva == 4) {exento += el.precio * el.cantidad ;}
                            descuento += 0;
                        }else{
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                            if (el.iva == 4) {exento += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                            descuento += parseFloat(el.descuento/el.cantidad_dsc*el.cantidad);
                        }
                    } else {
                        console.log("Descuento_comp 2:"+el.descuento);
                        if(el.descuento>0 && el.descuento!==null){
                            subtotal += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);
                        }else{
                            subtotal += el.precio * el.cantidad;
                        }
                        
                        
                        if(isNaN((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100)){
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad ;}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad ;}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad ;}
                            if (el.iva == 4) {exento += el.precio * el.cantidad ;}
                            descuento += 0;
                        }else{
                            if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                            if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                            if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                            if (el.iva == 4) {exento += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                            descuento += parseFloat((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100/el.cantidad_dsc*el.cantidad);
                        }
                    }
                }
                
            });
            console.log("Valor Subt12:"+parseFloat(subtotal12).toFixed(2));
            valor12 = parseFloat(subtotal12).toFixed(2) * 0.12;
            console.log("Valor 12:"+parseFloat(valor12).toFixed(2));
            console.log("Valor Subt:"+subtotal);
            total = Number(subtotal.toFixed(2)) + Number(valor12.toFixed(2));
            console.log("Valor Total:"+total);
            return {
                'subtotal': subtotal.toFixed(2),
                'subtotal12': subtotal12.toFixed(2),
                'valor12': valor12.toFixed(2),
                'subtotal0': subtotal0.toFixed(2),
                'valor0': valor0.toFixed(2),
                'no_impuesto': no_impuesto.toFixed(2),
                'exento': exento.toFixed(2),
                'descuento': descuento.toFixed(2),
                'total': total
            };
        }
    },
    methods: {
        listar_creacion_cliente(){
            axios.get('/api/notacredito/listar_creacion_cliente/'+this.usuario.id_empresa).then( ({data}) => {
                this.grupo_cliente_menu = data.grupo_cliente;
                this.tipo_cliente_menu = data.tipo_cliente;
                this.provincia_menu = data.provincia;
                this.vendedor_menu = data.vendedor;
                this.forma_pago_menu = data.forma_pago;
                this.proyectos_menu = data.proyectos;
                this.empresa = data.empresa;
                this.factura.ambiente = data.empresa.ambiente;
            }).catch( error => {
                console.log(error);
            });
        },
        documentos(){
            if(this.factura.documento.length==15){
                axios.post('/api/notacreditocompra/buscarfactura', {
                    factura:this.factura.documento,
                    id_empresa: this.usuario.id_empresa,
                }).then( ({data}) => {
                    if(data=='error'){
                        this.$vs.notify({
                            title: "Factura erronea",
                            text: "Esta factura no consta en nuestro sistema",
                            color: "danger"
                        }); 
                    }else{
                        this.factura.fecha_doc = data.factura.fecha_emision;
                        this.factura.fecha = moment().format();
                        this.factura.proyectos = data.factura.id_proyecto;
                        this.id_factcompra = data.factura.id_factcompra;
                        this.producto.tipo = 1;
                        this.producto.lista_productos=[];
                        data.detalle.forEach((el,index) => {
                            this.producto.lista_productos.push({
                                id_producto: el.id_producto,
                                cod_alterno: el.cod_alterno,
                                cod_principal: el.cod_principal,
                                nombre: el.nombre,
                                sector:el.sector,
                                cantidad: el.cantidad,
                                precio: el.precio,
                                descuento: el.descuento,
                                p_descuento: el.p_descuento,
                                subtotal: el.cantidad*el.precio,
                                iva: el.id_iva,
                                ice: el.id_ice,
                                proyecto: el.id_proyecto,
                                cantidad_dsc:el.cantidad,
                                prod_factura:0
                            });
                        }); 
                        this.proveedor = {
                            tipo:true,
                            id_proveedor:data.proveedor.id_proveedor,
                            nombre_proveedor:data.proveedor.nombre_proveedor,
                            telefono_prov:data.proveedor.telefono_prov,
                            contacto:data.proveedor.contacto,
                            tipo_identificacion:data.proveedor.tipo_identificacion,
                            identif_proveedor:data.proveedor.identif_proveedor,
                            direccion_prov:data.proveedor.direccion_prov,
                        };
                    }
                }); 
            }
        },
        eliminar_producto(id){
            this.producto.lista_productos.splice(id,1);
            if(!this.producto.lista_productos.length){
               this.producto.tipo = false; 
            }
        },
        validar(){
            this.error = {
                error:0,
                factura:{
                    tipo_emision:[],
                    fecha:[],
                    documento:[],
                    fecha_doc:[],
                    motivo:[],
                    autorizacion:[],
                    nro_nota_credito:[]
                },
                producto:{
                    busqueda:[]
                },
            }

            if(!this.factura.tipo_emision){
                this.error.factura.tipo_emision.push("Debe agregar el tipo de emisión");
                this.error.error=1; 
            }
            if(!this.factura.fecha){
                this.error.factura.fecha.push("Debe agregar la fecha de la factura");
                this.error.error=1;
            }
            if(this.factura.documento.length!=15){
                this.error.factura.documento.push("Debe agregar el número de la factura válido");
                this.error.error=1;
            }
            if(this.factura.nro_nota_credito.length!=15){
                this.error.factura.nro_nota_credito.push("Debe agregar el número de nota de credito válido");
                this.error.error=1;
            }
            if(!this.factura.fecha_doc){
                this.error.factura.fecha_doc.push("Debe agregar la fecha del documento");
                this.error.error=1;
            }
            if(!this.factura.motivo){
                this.error.factura.motivo.push("Debe agregar el motivo");
                this.error.error=1;
            }
            if(!this.factura.autorizacion){
                this.error.factura.autorizacion.push("Debe agregar el número autorizacion");
                this.error.error=1;
            }
            if(!this.producto.tipo){
                this.error.producto.busqueda.push("Debe agregar un producto al comprobante");
                this.error.error=1;
            }

            for (var i = 0; i < this.producto.lista_productos.length; i++) {
                this.producto.lista_productos[i].errorcantidad = [];
                this.producto.lista_productos[i].errorprecio = [];
                this.producto.lista_productos[i].errorproyecto = [];

                if (!this.producto.lista_productos[i].cantidad) {
                    this.producto.lista_productos[i].errorcantidad.push("Obligatorio");
                    this.error.error = 1;
                }
                if (!this.producto.lista_productos[i].precio) {
                    this.producto.lista_productos[i].errorprecio.push("Obligatorio");
                    this.error.error = 1;
                }
                if (!this.producto.lista_productos[i].proyecto) {
                    this.producto.lista_productos[i].errorproyecto.push("Obligatorio");
                    this.error.error = 1;
                }
            }

            if(this.error.error){
                setTimeout(() => {
                    var valor = $(".text-danger:first-child").offset().top - 300;
                    $("html, body").animate({
                        scrollTop: valor,
                    }, 500);
                }, 50);
            }
            return this.error.error;
        },
        recuperar(){
            axios.get('/api/notacreditocompra/recuperar/' + this.$route.params.id + "/"+this.usuario.id_empresa).then( ({data}) => {
                this.empresa = data.empresa;
                this.factura = {
                    fecha:moment(data.factura.fecha_emision).format('YYYY-MM-DD'),
                    documento:data.factura.autorizacionfactura,
                    fecha_doc:data.factura.fechaAutorizacion,
                    motivo:data.factura.motivo,
                    ambiente:data.factura.ambiente,
                    tipo_emision:'Emision Normal',
                    observacion:data.factura.observacion,
                    proyectos:data.factura.id_proyecto,
                    forma_pago:data.factura.forma_pago,
                    autorizacion:data.factura.clave_acceso,
                    nro_nota_credito:data.factura.nro_nota_credito
                }
                this.proveedor = {
                    tipo:true,
                    id_proveedor:data.proveedor.id_proveedor,
                    nombre_proveedor:data.proveedor.nombre_proveedor,
                    telefono_prov:data.proveedor.telefono_prov,
                    contacto:data.proveedor.contacto,
                    tipo_identificacion:data.proveedor.tipo_identificacion,
                    identif_proveedor:data.proveedor.identif_proveedor,
                    direccion_prov:data.proveedor.direccion_prov,
                },
                data.productos.forEach(el => {
                    this.producto.lista_productos.push({
                        id_producto_bodega:el.id_producto_bodega,
                        id_detalle_nota_credito_compra: el.id_detalle_nota_credito_compra,
                        id_producto: el.id_producto,
                        cod_alterno: el.cod_alterno,
                        cod_principal: el.cod_principal,
                        nombre: el.nombre,
                        sector:el.sector,
                        cantidad: el.cantidad,
                        precio: el.precio,
                        descuento: el.descuento,
                        p_descuento: el.p_descuento,
                        subtotal: null,
                        iva: el.id_iva,
                        ice: el.id_ice,
                        proyecto: el.id_proyecto,
                        cantidad_dsc:el.cantidad_dsc,
                        descuento_comp:el.descuento_comp,
                        prod_factura:el.prod_factura
                    });
                });
                this.producto.tipo = true;
                this.id_factcompra=data.factura.id_factura_compra;
            }).catch( error => {
                console.log(error);
            });
        },
        guardar_factura(){
            if(this.validar()){return;}
            if(this.factura.documento.length<15){
               this.$vs.notify({
                    time: 5000,
                    title: "Error de número de documento",
                    text: "Debe escoger el número de documento de una factura existente en el sistema",
                    color: "danger"
                });  
                return;
            }
            if(this.factura.autorizacion.length<=1){
               this.$vs.notify({
                    time: 5000,
                    title: "Error de número de autorización",
                    text: "Debe agregar el número de autorización de la factura",
                    color: "danger"
                });  
                return;
            }
            axios.post('/api/notacreditocompra/editar_factura', {
                id: this.$route.params.id,
                factura:this.factura,
                productos: this.producto.lista_productos,
                empresa: this.empresa,
                usuario:this.usuario,
                proveedorc: this.proveedor,
                proveedor: this.proveedor.id_proveedor,
                subtotal: this.formulas.subtotal,
                subtotal12: this.formulas.subtotal12,
                valor12: this.formulas.valor12,
                subtotal0: this.formulas.subtotal0,
                valor0: this.formulas.valor0,
                no_impuesto: this.formulas.no_impuesto,
                exento: this.formulas.exento,
                descuento: this.formulas.descuento,
                total: this.formulas.total,
                id_factcompra: this.id_factcompra,
            }).then( () => {
                this.$vs.notify({
                    time: 8000,
                    title: "Nota de Crédito Guardado",
                    text: "La nota de crédito se guardo exitosamente",
                    color: "success"
                }); 
                this.$router.push("/compras/nota-credito");
            }).catch( error => {
                console.log(error);
            }); 
        },
        // listar servicios nota credito
        listar_productos(buscar) {
            this.preloader.productos = false;
            $(".busqueda_producto_ls").show();
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                axios
                    .post("/api/notacredito_compra/listar_servicios", {
                        buscar: buscar,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                        cliente: this.proveedor.id_proveedor
                    })
                    .then(({ data }) => {
                        this.preloader.productos = true;
                        if (data.length >= 1) {
                            if (
                                data[0].codigo_barras == buscar &&
                                data[0].codigo_barras.length >= 1
                            ) {
                                this.seleccionar_productos(data[0]);
                                this.producto.busqueda = "";
                                return;
                            } else {
                                this.producto.productos = data;
                                return;
                            }
                        } else {
                            this.producto.productos = [];
                            return;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        this.preloader.productos = true;
                    });
            }, 800);
        },
        seleccionar_productos(tr) {
            this.producto.productos = [];
            this.producto.busqueda = "";
            this.producto.tipo = true;
            var subtotal = (tr.precio - tr.descuento).toFixed(2);
            var cantidad = 1;

            if (isNaN(parseInt(tr.existencia_total))) {
                tr.existencia_total = "";
            }
            if (isNaN(parseFloat(tr.precio))) {
                tr.precio = "";
            }
            if (isNaN(parseFloat(tr.descuento))) {
                tr.descuento = 0;
            }
            if (
                tr.sector == 1 &&
                (tr.id_producto_bodega === "undefined" ||
                    tr.id_producto_bodega == null)
            ) {
                cantidad = 0;
                tr.cantidad = 0;
                tr.id_producto_bodega = null;
                tr.nombrebodega = null;
            }
            var siiva = false;
            if (tr.iva == 1) {
                siiva = false;
            } else {
                siiva = true;
            }
            
                this.producto.lista_productos.push({
        
                    id_producto_bodega:null,
                    id_producto: tr.id_producto,
                    cod_alterno: tr.cod_alterno,
                    cod_principal: tr.cod_principal,
                    sector:tr.sector,
                    nombre: tr.nombre,
                    cantidad: "",
                    precio: "",
                    descuento: "",
                    p_descuento: 1,
                    subtotal: "",
                    iva: tr.iva,
                    ice: tr.ice,
                    proyecto: this.proyectos_menu[0].id_proyecto,
                    cantidad_dsc:0,
                    prod_factura:0
                });
            

            $(".focuspr").focus();
        },
    },
    mounted(){
        this.recuperar();
        this.listar_creacion_cliente();
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
        position: absolute!important;
        right: 0px;
        margin-top: 18px;
        display:none;
    }
    .fila_lista:hover .eliminar_producto_icono{
        display:block;
    }
    .cabezera_total span{
        float: right;
        margin-right: 25px;
    }
    .cabezera_total div{
        margin-left: 20px;
        padding: 6px 3px;
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
    .text-center .vs-table-text {
        text-align: center!important;
        display: inline-block;
    }
    .text-center input{
        text-align: center!important;
    }
</style>