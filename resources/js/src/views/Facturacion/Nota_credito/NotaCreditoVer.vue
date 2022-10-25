<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3 v-if="cuerpo.clave_acceso">Nota de Crédito N° {{(cuerpo.clave_acceso).substring(24,27)}}-{{(cuerpo.clave_acceso).substring(27,30)}}-{{(cuerpo.clave_acceso).substring(30,39)}}</h3>
                <h3 v-else>Generando factura</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Ambiente:</h6>
                    <span v-if="empresa.ambiente==2">Producción</span>
                    <span v-else>Pruebas</span>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto mr-auto" style="text-align: center;">
                    <h6 class="mb-1">Tipo de emisión:</h6>
                    <span v-if="empresa.tipo_emision==2">Emisión Normal</span>
                    <span v-else>Emisión Normal</span>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                    <h6>Fecha:</h6>
                    {{ cuerpo.fecha_emision | fecha }}
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center" v-if="cuerpo.autorizacionfactura">
                    <h6>Número Documento</h6>
                    {{(cuerpo.autorizacionfactura).substring(0,3)}} - {{(cuerpo.autorizacionfactura).substring(3,6)}} - {{(cuerpo.autorizacionfactura).substring(6,15)}}
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                    <h6>Fecha Doc:</h6>
                    {{ cuerpo.fechaAutorizacion | fecha }}
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <h6>Motivo:</h6>
                    {{ cuerpo.motivo }}
                </div>
                <div class="vx-col sm:w-full w-full mb-2 text-center text-center">
                    <h6 class="mt-4">Clave de acceso:</h6>
                    <p>{{ cuerpo.clave_acceso }}</p>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Cliente</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/3 w-full mb-3 text-center">
                    <h6>Nombre:</h6>
                    {{ cuerpo.nombre }}
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 text-center">
                    <h6>Teléfono:</h6>
                    {{ cuerpo.telefono }}
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 text-center">
                    <h6>Email:</h6>
                    {{ cuerpo.email }}
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 text-center">
                    <h6>Tipo de Identificación:</h6>
                    {{ cuerpo.tipo_identificacion }}
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 text-center">
                    <h6>Identificación:</h6>
                    {{ cuerpo.identificacion }}
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 text-center">
                    <h6>Dirección:</h6>
                    {{ cuerpo.direccion }}
                </div>
            </div>
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            <div class="p-base">
                <vs-table hoverFlat :data="productos" class="mb-5" style="font-size: 12px;">
                    <template slot="thead">
                        <vs-th>CÓDIGO</vs-th>
                        <vs-th>NOMBRE</vs-th>
                        <vs-th>CANTIDAD</vs-th>
                        <vs-th>PRECIO</vs-th>
                        <vs-th>DESCUENTO</vs-th>
                        <vs-th>SUBTOTAL</vs-th>
                    </template>
                    <template slot-scope="{ data }">
                        <vs-tr v-for="(tr, index) in data" :key="index">
                            <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                            <vs-td>{{ tr.nombre }}</vs-td>
                            <vs-td style="width:150px!important;">
                                {{ tr.cantidad }}
                            </vs-td>
                            <vs-td style="width:150px!important;">
                                $ {{ tr.precio }}
                            </vs-td>
                            <vs-td style="width:150px!important;" v-if="tr.descuento">
                                $ {{ tr.descuento }}
                            </vs-td>
                            <vs-td style="width:150px!important;" v-else>
                                $ 0.00
                            </vs-td>
                            <vs-td>
                                $ {{ (tr.cantidad * tr.precio - tr.descuento).toFixed(2) }}
                            </vs-td>
                        </vs-tr>
                    </template>
                </vs-table>
                <div class="vx-row mt-6">
                    <div class="vx-col sm:w-1/2 w-full">
                        <h6 class="mb-3">Observaciones:</h6>
                        {{ cuerpo.observacion }}
                    </div>
                    <div class="vx-col sm:w-1/2 w-full">
                        <vs-table hoverFlat class="w-full" :data="productos">
                                <vs-tr>
                                    <vs-th>SUBTOTAL FINAL</vs-th>
                                    <vs-td>$ {{ cuerpo.subtotal_sin_impuesto }}</vs-td>
                                </vs-tr>
                                <vs-tr v-if="cuerpo.subtotal_12 > 0">
                                    <vs-th>SUBTOTAL IVA 12%</vs-th>
                                    <vs-td>$ {{ cuerpo.subtotal_12 }}</vs-td>
                                </vs-tr>
                                <vs-tr v-if="cuerpo.iva_12 > 0">
                                    <vs-th>Valor IVA 12%</vs-th>
                                    <vs-td>$ {{ cuerpo.iva_12 }}</vs-td>
                                </vs-tr>
                                <vs-tr v-if="cuerpo.subtotal_0 > 0">
                                    <vs-th>SUBTOTAL IVA 0%</vs-th>
                                    <vs-td>$ {{ cuerpo.subtotal_0 }}</vs-td>
                                </vs-tr>
                                <vs-tr v-if="cuerpo.subtotal_0 > 0">
                                    <vs-th>Valor IVA 0%</vs-th>
                                    <vs-td> $ 0.00 </vs-td>
                                </vs-tr>
                                <vs-tr v-if="cuerpo.subtotal_no_obj_iva > 0">
                                    <vs-th>NO OBJETO DE IMPUESTO</vs-th>
                                    <vs-td>$ {{ cuerpo.subtotal_no_obj_iva }}</vs-td>
                                </vs-tr>
                                <vs-tr v-if="cuerpo.subtotal_no_obj_iva > 0">
                                    <vs-th>VALOR NO OBJETO DE IMPUESTO</vs-th>
                                    <vs-td> $ 0.00 </vs-td>
                                </vs-tr>
                                <vs-tr>
                                    <vs-th>TOTAL DESCUENTO</vs-th>
                                    <vs-td>$ {{ cuerpo.descuento }}</vs-td>
                                </vs-tr>
                                <vs-tr>
                                    <vs-th>VALOR TOTAL</vs-th>
                                    <vs-td>$ {{ cuerpo.valor_total }}</vs-td>
                                </vs-tr>
                        </vs-table>
                    </div>
                </div>
                <div class="vx-col w-full">
                    <vs-button color="danger" type="filled" to="/facturacion/nota-credito">Regresar</vs-button>
                </div>
            </div>
        </vx-card>
    </div>
</template>
<script>
import moment from "moment";
moment.locale("es");
import $ from "jquery";
const axios = require("axios");
export default {
    data() {
        return {
            cuerpo: [],
            productos: [],
            empresa: [],
        };
    },
    filters: {
        fecha(data) {
        return moment(data).format("LL");
        },
        fechayhora(data) {
        return moment(data).format("LLL");
        }
    },
    methods: {
        listar() {
            var url = "/api/vernotacredito/" + this.$route.params.id;
            axios.get(url).then(res => {
                this.cuerpo = res.data.cuerpo;
                this.productos = res.data.productos;
                this.empresa = res.data.empresa;
            });
        }
    }, 
    mounted() {
        this.listar();
    },
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
</style>
