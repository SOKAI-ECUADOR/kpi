<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3 v-if="lista.factura.clave_acceso">Nota Venta N° {{(lista.factura.clave_acceso).substring(24,27)}}-{{(lista.factura.clave_acceso).substring(27,30)}}-{{(lista.factura.clave_acceso).substring(30,39)}}</h3>
                <h3 v-else>Generando nota</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto" style="text-align: center;">
                    <h6 class="mb-3">Ambiente:</h6>
                    <span v-if="lista.factura.ambiente==2">Producción</span>
                    <span v-else>Pruebas</span>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto" style="text-align: center;">
                    <h6 class="mb-3">Tipo Emisión:</h6> Emision Normal
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                    <h6>Orden de compra:</h6>
                    <span v-if="lista.factura.orden_compra">{{ lista.factura.orden_compra }}</span>
                    <span v-else>Sin orden de compra</span>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                    <h6 class="mb-3">Migo:</h6>
                    <span v-if="lista.factura.migo">{{ lista.factura.migo }}</span>
                    <span v-else>Sin migo</span>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                    <h6 class="mb-3">Fecha Emisión:</h6>
                    {{ lista.factura.fecha_emision | fecha }}
                </div>
                <!-- <div class="vx-col sm:w-full w-full mb-6 text-center">
                    <h6 class="mb-3">Proyecto:</h6>
                    <span v-if="lista.factura.des_proyecto">{{ lista.factura.des_proyecto }}</span>
                    <span v-else>Sin Proyecto</span>
                </div> -->
                <!-- <div class="vx-col sm:w-full w-full mb-6 text-center">
                    <h6 class="mt-4">Clave de acceso:</h6>
                    {{ lista.factura.clave_acceso }}
                </div> -->
            </div>
            <vs-divider position="left">
                <h3>Cliente</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full mb-6 relative">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                            <label for="" class="vs-input--label">Nombre:</label>
                            <div>{{ lista.cliente.nombre }}</div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                            <label for="" class="vs-input--label">Teléfono:</label>
                            <div>{{ lista.cliente.telefono }}</div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                            <label for="" class="vs-input--label">Email:</label>
                            <div>{{ lista.cliente.email }}</div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                            <label for="" class="vs-input--label">Tipo de Identificación:</label>
                            <div>{{ lista.cliente.tipo_identificacion }}</div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                            <label for="" class="vs-input--label">Identificación:</label>
                            <div>{{ lista.cliente.identificacion }}</div>
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 text-center">
                            <label for="" class="vs-input--label">Dirección:</label>
                            <div>{{ lista.cliente.direccion }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base mb-6">
                <div class="vx-col sm:w-full w-full relative">
                    <vs-table hoverFlat :data="lista.productos" style="font-size: 12px;">
                        <template slot="thead">
                            <vs-th>CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th>CANTIDAD</vs-th>
                            <vs-th>PRECIO</vs-th>
                            <vs-th style="width: 110px;">ICE</vs-th>
                            <vs-th>DESCUENTO</vs-th>
                            <vs-th>VALOR</vs-th>
                            <vs-th>SUBTOTAL</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td>{{ tr.nombre }}</vs-td>
                                <vs-td>{{ tr.descripcion_proyecto    }}</vs-td>
                                <vs-td>$ {{ tr.cantidad }}</vs-td>
                                <vs-td>$ {{ tr.precio }}</vs-td>
                                <vs-td v-if="tr.total_ice">$ {{ (tr.total_ice * tr.cantidad) | decimal }}</vs-td>
                                <vs-td v-else> $ 0.00</vs-td>
                                <vs-td> $ {{ (tr.total_ice*tr.cantidad) | decimal }}</vs-td>
                                <vs-td v-if="tr.valor_sin_iva">$ {{ tr.valor_sin_iva | decimal }}</vs-td>
                                <vs-td v-else>$ 0.00</vs-td>
                                <vs-td v-if="tr.descuento">{{ (tr.cantidad * tr.precio)/tr.descuento | decimal }}</vs-td>
                                
                                <vs-td v-else>$ {{ tr.cantidad * tr.precio | decimal }}</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
                <div class="vx-col w-full">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mt-6">
                            <h6>Observaciones:</h6>
                            <div>{{ lista.factura.observacion }}</div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>SUBTOTAL FINAL <span>$ {{ lista.factura.subtotal_sin_impuesto }}</span></div>
                                <div v-if="lista.factura.valorice>0">Valor ICE <span>$ {{ lista.factura.valorice | decimal }}</span></div>
                                <div v-if="lista.factura.subtotal_12>0">SUBTOTAL IVA 12% <span>$ {{ lista.factura.subtotal_12 }}</span></div>
                                <div v-if="lista.factura.iva_12>0">Valor IVA 12% <span>$ {{ lista.factura.iva_12 }}</span></div>
                                <div v-if="lista.factura.subtotal_0>0">SUBTOTAL IVA 0% <span>$ {{ lista.factura.subtotal_0 }}</span></div>
                                <div v-if="lista.factura.subtotal_no_obj_iva>0">NO OBJETO DE IMPUESTO <span>$ {{ lista.factura.subtotal_no_obj_iva }}</span></div>
                                <div>TOTAL DESCUENTO <span>$ {{ lista.factura.descuento }}</span></div>
                                <div>VALOR TOTAL <span>$ {{ lista.factura.valor_total }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Total Facturas</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full mb-2 text-center">
                    <label class="vs-input--label">SALDO TOTAL</label>
                    <h1>$ {{ lista.factura.valor_total }}</h1>
                </div>
            </div>
            <vs-divider position="left" class="flexy" v-if="lista.creditos.periodo">
                <h3>Créditos</h3>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base">
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                        <label class="vs-input--label">Periodo de pago</label>
                        <div class="mt-2">{{lista.creditos.periodo}}</div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Tiempos Pago</label>
                        <div class="mt-2">{{lista.creditos.tiempo}}</div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Plazos de pago</label>
                        <div class="mt-2">{{lista.creditos.plazos}}</div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Monto de pago</label>
                        <div class="mt-2">$ {{lista.creditos.monto}}</div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Pago por letra</label>
                        <div class="mt-2">$ {{(lista.creditos.monto * lista.creditos.plazos) | decimal}}</div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy" v-if="lista.iva.length">
                <h3>Retenciones</h3>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr, index) in lista.iva" :key="index">
                            <div class="w-2/3 ml-auto mr-auto">
                                <div class="vx-row">
                                    <div class="vx-col md:w-3/5 w-full mb-2 ml-auto text-center">
                                        <label class="vs-input--label">Valores por IVA</label>
                                        <div class="mt-2">$ {{lista.iva[index].descrip_retencion}}</div>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <label class="vs-input--label">Base</label>
                                        <div class="mt-2">$ {{lista.iva[index].cantidadiva}}</div>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <label class="vs-input--label">Porcentaje</label>
                                        <div class="mt-2">{{lista.iva[index].porcentajeiva}}</div>
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <label class="vs-input--label">Val. Ret.</label>
                                        <div class="mt-2">$ {{lista.iva[index].cantidadiva}}</div>
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div class="vx-col md:w-2/3 w-full mb-2 mr-auto text-center">
                                        <label class="vs-input--label">Valores por RENTA</label>
                                        <div class="mt-2">$ {{lista.renta[index].descrip_retencion}}</div>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <label class="vs-input--label">Base</label>
                                        <div class="mt-2">$ {{lista.renta[index].baserenta}}</div>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <label class="vs-input--label">Porcentaje</label>
                                       <div class="mt-2">{{lista.renta[index].porcentajerenta}}</div>
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <label class="vs-input--label">Val. Ret.</label>
                                        <div class="mt-2">$ {{lista.renta[index].cantidadrenta}}</div>
                                    </div>
                                    <vs-divider position="left"></vs-divider>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy" v-if="lista.pagos.length">
                <h3>Pagos</h3>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr,index) in lista.pagos" :key="index">
                            <div class="vx-col w-full mb-2 text-center ml-auto sm:w-1/6 text-center">
                                <label class="vs-input--label">Método de pago</label>
                                <div class="mt-2">{{tr.descripcionpagos}}</div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <label class="vs-input--label">Banco</label>
                                <div class="mt-2">{{tr.nombre_banco}}</div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <label class="vs-input--label">Cantidad</label>
                                <div class="mt-2">{{tr.cantidad_pago}}</div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <label class="vs-input--label">Nro de transacción</label>
                                <div class="mt-2">{{tr.numero_transaccion}}</div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <label class="vs-input--label">Fecha de pago</label>
                                <div class="mt-2">{{tr.fecha_pago | fecha}}</div>
                            </div>
                             <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <label class="vs-input--label">Plan Cuenta</label>
                                <div class="mt-2 ml-3">{{tr.cuenta}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
            <div class="vx-col w-full mt-5">
                <vs-button color="danger" type="filled" to="/facturacion/factura_acumulada">ATRAS</vs-button>
            </div>
        </vx-card>
    </div>
</template>
<script>
import moment from "moment";
moment.locale('es');
import $ from "jquery";
import { log } from "util";
const axios = require("axios");
const {rutasEmpresa:{DATA_EMPRESA}} = require("../../../../../../config-routes/config");

export default {
    data() {
        return {
            lista:{
                factura:{},
                cliente:{},
                productos:[],
                pagos:[],
                creditos:{},
                iva:[],
                renta:[]
            },
            ice:0,
        };
    },
    filters:{
        fecha(data){
            return moment(data).format("LL");
        },
        decimal(data){
            return parseFloat(data).toFixed(2);
        }
    },
    computed: {
        decimal(data) {
            return data.toFixed(2);
        },
    },
    methods: {
        listar(){
            axios.get('/api/nota_ventaver/' + this.$route.params.id).then( ({data}) => {
                this.lista.factura = data.factura;
                this.lista.cliente = data.cliente;
                this.lista.productos = data.productos;
                this.lista.pagos = data.pagos;
                this.lista.creditos = data.creditos;
                this.lista.iva = data.iva;
                this.lista.renta = data.renta;
                let ice = 0;
                this.lista.productos.forEach(el => {
                    if(el.total_ice){
                        ice += parseFloat(el.total_ice) * parseFloat(el.cantidad);
                    }
                });
                this.lista.factura.valorice = ice;
            }).catch( error => {
                console.log(error);
            });
        }
    },
    mounted() {
        this.listar();
    }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
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
</style>
