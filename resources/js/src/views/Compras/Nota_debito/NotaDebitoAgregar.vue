<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3>Nota de Débito Compra</h3>    
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <h6>Fecha:</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full mt-1" v-model="factura.fecha" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha">
                        <div v-for="err in error.factura.fecha" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-3/5 w-full mb-6">
                    <h6 class="mb-1">Número Documento</h6>
                    <vs-input class="w-full" maxlength="15" placeholder="000-000-000000000" @keyup="documentos()" v-model="factura.documento"/>
                    <div v-show="error" v-if="factura.documento.length!=15">
                        <div v-for="err in error.factura.documento" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div> 
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <h6 class="mb-1">Fecha Doc:</h6>
                    <flat-pickr :config="configdateTimePicker" disabled class="w-full" v-model="factura.fecha_doc" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha_doc">
                        <div v-for="err in error.factura.fecha_doc" :key="err" v-text="err" class="text-danger"></div>
                    </div> 
                </div>
                <div class="vx-col sm:w-full mb-6">
                    <h6 class="mb-1">Número de Autorización:</h6>
                    <vs-input class="w-full" v-model="factura.autorizacion" placeholder="Escriba la autorización del comprobante"/>
                    <div v-show="error" v-if="!factura.autorizacion">
                        <div v-for="err in error.factura.autorizacion" :key="err" v-text="err" class="text-danger"></div>
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
                            <vs-th>MOTIVO</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th class="text-center">CANTIDAD</vs-th>
                            <vs-th class="text-center">PRECIO</vs-th>
                            <vs-th class="text-center">DESCUENTO</vs-th>
                            <vs-th class="text-center">SUBTOTAL</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
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
                                <vs-td class="text-center" style="width:120px!important;">$ {{ tr.precio }}</vs-td>
                                <vs-td class="text-center" style="width:120px!important;">
                                    <template v-if="tr.p_descuento==1">$</template> 
                                    <span v-if="tr.descuento">{{ tr.descuento }}</span><span v-else>0.00</span> 
                                    <template v-if="tr.p_descuento==0">%</template>
                                </vs-td>
                                <vs-td class="text-center" style="width:120px!important;">
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - tr.descuento).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - ((tr.cantidad * tr.precio * tr.descuento)/100)).toFixed(2)}}
                                    </template>
                                </vs-td>
                                <feather-icon icon="TrashIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" class="eliminar_producto_icono" @click="eliminar_producto(index)"/>
                            </vs-tr>
                        </template>
                    </vs-table>
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
                                <div>VALOR TOTAL <span>$ {{ formulas.total }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <vs-divider position="left" v-if="producto.tipo">
                <h3>Total Facturas</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="producto.tipo">
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALDO TOTAL</label>
                    <h1>$ {{ formulas.total }}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALDO PENDIENTE</label>
                    <h1>$ {{ total_pendiente }}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALGO PAGADO</label>
                    <h1>$ {{ total_pagado }}</h1>
                </div>
            </div>
            <vs-divider position="left" class="flexy" v-if="producto.tipo">
                <h3>Créditos</h3>
                <vs-switch vs-icon-on="check" color="success" v-model="creditos.estado" class="ml-2" @click="cambioscreditos()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade" v-if="producto.tipo">
                <div class="vx-row leading-loose p-base" v-if="creditos.estado">
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                        <label class="vs-input--label">Periodo de pago</label>
                        <vs-select placeholder="Selecciona el periodo de pago" autocomplete class="selectExample w-full" v-model="creditos.periodo">
                            <vs-select-item value text="Slecciona el periodo" />
                            <vs-select-item value="Dias" text="Dias" />
                            <vs-select-item value="Semanas" text="Semanas" />
                            <vs-select-item value="Meses" text="Meses" />
                            <vs-select-item value="Años" text="Años" />
                        </vs-select>
                        <div v-show="error" v-if="!creditos.periodo">
                            <div v-for="err in error.creditos.periodo" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input class="w-full text-center" label="Tiempos Pago" v-model="creditos.tiempo"/>
                        <div v-show="error" v-if="!creditos.tiempo">
                            <div v-for="err in error.creditos.tiempo" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Plazos de pago</label>
                        <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="creditos.plazos">
                            <vs-select-item v-for="(v, index) in 36" :key="index" :value="v" :text="v + ' Periodos'"/>
                        </vs-select>
                        <div v-show="error" v-if="!creditos.plazos">
                            <div v-for="err in error.creditos.plazos" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input class="w-full text-center" label="Monto de pago" v-model="creditos.monto"/>
                        <div v-show="error" v-if="parseFloat(creditos.monto)<=0">
                            <div v-for="err in error.creditos.monto" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Pago por letra</label>
                        <div class="mt-2">$ {{pagoletra}}</div>
                    </div> 
                </div>
            </transition>
            <vs-divider position="left" class="flexy" v-if="producto.tipo">
                <h3>Pagos</h3>
                <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="pagos.estado" @click="cambiospagosrec()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade" v-if="producto.tipo">
                <div class="vx-row leading-loose p-base" v-show="pagos.estado">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr,index) in pagos.datos" :key="index">
                            <div class="vx-col w-full mb-2 text-center ml-auto sm:w-1/6">
                                <label class="vs-input--label">Método de pago</label>
                                <vs-select placeholder="Selecciona el método de pago" autocomplete class="selectExample w-full" v-model="tr.metodo_pago">
                                    <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
                                </vs-select>
                                <div v-show="error.error" v-if="!tr.metodo_pago">
                                    <div v-for="err in tr.errormetodo" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-select class="selectExample w-full" label="Banco" vs-multiple autocomplete v-model="tr.banco_pago">
                                    <vs-select-item v-for="data in bancos" :key="data.id_banco" :value="data.id_banco" :text="data.nombre_banco" />
                                </vs-select>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-input class="w-full text-center" label="Cantidad" v-model="tr.cantidad_pago"/>
                                <div v-show="error.error" v-if="parseFloat(tr.cantidad_pago)<=0">
                                    <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-input class="w-full text-center" label="Nro de transacción" v-model="tr.nro_trans"/>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2">
                                <label class="vs-input--label">Fecha de pago</label>
                                <flat-pickr :config="configdateTimePicker" class="w-full" v-model="tr.fecha_pago" placeholder="Seleccionar"></flat-pickr>
                            </div>
                             <div class="vx-col sm:w-1/6 w-full mb-2">
                                <label class="vs-input--label">Plan Cuenta</label>
                                <vx-input-group>
                                    <vs-input class="w-full" v-model="tr.cuenta"/>
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                        <vs-button color="primary" @click="abrir_plan_cuentas_pagos(index)">Buscar</vs-button>
                                        </div>
                                    </template>
                                </vx-input-group>
                            </div>
                            <feather-icon icon="TrashIcon" style="position: absolute!important;right: 15px;margin-top: 44px;display:none" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="eliminararraypagos(index)" />
                            <feather-icon icon="PlusIcon" style="position: absolute!important;right: 15px;margin-top: 26px;display:none" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="addpagos()" />
                        </div>
                    </div>
                </div>
            </transition>
            <div class="vx-col w-full" v-if="producto.tipo">
                <vs-button color="success" type="filled" @click="guardar_factura()">GUARDAR</vs-button>
                <vs-button color="danger" type="filled" to="/facturacion/nota-debito">CANCELAR</vs-button>
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
                },
                producto:{
                    busqueda:[]
                },
                creditos:{
                    periodo:[],
                    tiempo:[],
                    plazos:[],
                    monto:[],
                }
            },
            id_factcompra:null,
            creditos:{
                estado:false,
                periodo:'',
                tiempo:1,
                plazos:3,
                monto:0,
                pago:0,
            },
            pagos:{
                estado:false,
                index:null,
                datos:[
                    {
                        metodo_pago:'',
                        banco_pago:null,
                        cantidad_pago:0,
                        nro_trans:'',
                        fecha_pago:'',
                        cuenta:'',
                        plan_cuenta:null,
                        errormetodo:[],
                        errorcantidad:[],
                    }
                ]
            },
            formapagos:[],
            bancos: [],
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
            var total = 0;

            this.producto.lista_productos.forEach(el => {
                subtotal += el.precio * el.cantidad;
                if (el.iva == 2) {subtotal12 += el.precio * el.cantidad;}
                if (el.iva == 1) {subtotal0 += el.precio * el.cantidad;}
                if (el.iva == 3) {no_impuesto += el.precio * el.cantidad;}
                if (el.iva == 4) {exento += el.precio * el.cantidad;}
            });
            valor12 = subtotal12 * 0.12;
            total = subtotal + valor12;

            return {
                'subtotal': subtotal.toFixed(2),
                'subtotal12': subtotal12.toFixed(2),
                'valor12': valor12.toFixed(2),
                'subtotal0': subtotal0.toFixed(2),
                'valor0': valor0.toFixed(2),
                'no_impuesto': no_impuesto.toFixed(2),
                'exento': exento.toFixed(2),
                'total': total.toFixed(2)
            };
        },
        pagoletra(){
            var res = 0;
            var res = this.creditos.monto / this.creditos.plazos;
            return res.toFixed(2);
        },
        total_pendiente(){
            var total = 0;
            var retencion = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;
            
            this.pagos.datos.forEach(el => {
                if(parseFloat(el.cantidad_pago)>=0 ){
                    paga = parseFloat(el.cantidad_pago)
                }
                pagas +=  paga;
            });
            if(this.creditos.monto<=0){
                creditos = 0;
            }else{
                creditos = this.creditos.monto;
            }
            
            total = parseFloat(this.formulas.total) - parseFloat(retencion) - parseFloat(creditos) - parseFloat(pagas); 
            if(parseFloat(total)<0.01 && parseFloat(total)>=-0.02){
                total = 0;
            }
            return total.toFixed(2);
        },
        total_pagado(){
            var total = 0;
            var retencion = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;
            this.pagos.datos.forEach(el => {
                if(parseFloat(el.cantidad_pago)>=0 ){
                    paga = parseFloat(el.cantidad_pago)
                }
                pagas +=  paga;
            });
            if(this.creditos.monto<=0){
                creditos = 0;
            }else{
                creditos = this.creditos.monto;
            }
            total = parseFloat(retencion) + parseFloat(creditos) + parseFloat(pagas); 
            if(parseFloat(total)<0.01){
                total = 0;
            }
            return total.toFixed(2);
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
                        this.empresa = data.empresa;
                        this.factura.fecha_doc = data.factura.fech_emision;
                        this.factura.fecha = moment().format();
                        this.factura.proyectos = data.factura.id_proyecto;
                        this.id_factcompra = data.factura.id_factcompra;
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
                                proyecto: el.id_proyecto
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
        guardar_factura(){
            if(this.validar()){return;}
            if(this.factura.documento.length<15 || this.id_factcompra == null){
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
            axios.post('/api/notadebitocompra/guardar_factura', {
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
                creditos: this.creditos,
                pagos: this.pagos
            }).then( () => {
                this.$vs.notify({
                    time: 8000,
                    title: "Nota de Crédito Guardado",
                    text: "La nota de crédito se guardo exitosamente",
                    color: "success"
                }); 
                this.$router.push("/compras/nota-debito");
            }).catch( error => {
                console.log(error);
            }); 
        },
        validar(){
            this.error = {
                error:0,
                factura:{
                    fecha:[],
                    documento:[],
                    fecha_doc:[],
                    autorizacion:[],
                },
                producto:{
                    busqueda:[]
                },
            }
            if(!this.factura.fecha){
                this.error.factura.fecha.push("Debe agregar la fecha de la factura");
                this.error.error=1;
            }
            if(this.factura.documento.length!=15){
                this.error.factura.documento.push("Debe agregar el número de documento válido");
                this.error.error=1;
            }
            if(!this.factura.fecha_doc){
                this.error.factura.fecha_doc.push("Debe agregar la fecha del documento");
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
        listarbanco() {
            axios.get("/api/traerbancofactcomp").then(({data}) => {
                this.bancos = data;
            });
        },
        listarformapagos(){
            axios.get("/api/facturaformapagos/" + this.usuario.id_empresa).then( res => {
                this.formapagos = res.data;
            }).catch( err => {
                console.log(err);
            });
        }, 
        cambioscreditos(){
            this.creditos.monto = this.total_pendiente;
            if(this.creditos.estado){
                this.creditos.monto = 0;
            }
        },
        cambiospagosrec(){
            this.pagos.datos.forEach(el => {
                el.cantidad_pago = 0;
            });
            this.pagos.datos = [
                {
                    metodo_pago:'',
                    banco_pago:null,
                    cantidad_pago:0,
                    nro_trans:'',
                    fecha_pago:'',
                    cuenta:'',
                    plan_cuenta:null,
                }
            ];
            if(!this.pagos.estado){
                this.pagos.datos[0].cantidad_pago = this.total_pendiente; 
            }
        },
    },
    mounted() {
        this.listarformapagos();
        this.listarbanco();
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
    .flexy > .vs-divider--text {
        display: flex;
    }
</style>
