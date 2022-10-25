<template>
    <div class="vx-row">
        <div class="vx-col w-full ">
            <vx-card>
                <h4 class="mb-3">Agregar Empresa</h4>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Periodo</label>
                        <div class="estilo-vista ml-3">
                            Periodo del año {{ datos.periodo_empresa }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <label class="vs-input--label">Ruc</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.ruc_empresa }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label">Nombre Comercial</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.nombre_empresa }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <label class="vs-input--label">Razón social</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.razon_social }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Telefono</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.telefono }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <label class="vs-input--label">Direccion</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.direccion_empresa }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Seleccione Provincia</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.nombre_provincia }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Seleccione Ciudad</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.nombre_ciudad }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Ruc Contador</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.ruc_contador }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label">Nombre Contador</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.nombre_contador }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label"
                            >Nombre Representante</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.nomb_representante }}
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/3 w-full mb-6"
                        :class="{
                            'sm:w-1/4': datos.tipo_identidicacion_empresa
                        }"
                    >
                        <label class="vs-input--label"
                            >Tipo Identificacion RL</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.tipo_identidicacion_empresa }}
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/3 w-full mb-6"
                        :class="{
                            'sm:w-1/4': datos.tipo_identidicacion_empresa
                        }"
                        v-if="datos.tipo_identidicacion_empresa"
                    >
                        <label class="vs-input--label"
                            >Identificación del representante</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.identificaion_rep }}
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/3 w-full mb-6"
                        :class="{
                            'sm:w-1/4': datos.tipo_identidicacion_empresa
                        }"
                    >
                        <label class="vs-input--label">Periodo Inicio</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.periodo_inicio | fecha }}
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/3 w-full mb-6"
                        :class="{
                            'sm:w-1/4': datos.tipo_identidicacion_empresa
                        }"
                    >
                        <label class="vs-input--label">Periodo Fin</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.periodo_fin | fecha }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Seleccione su Moneda</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.nombre_moneda }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Fecha de duración</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.clave_duracion | fecha }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Obligado llevar contabilidad</label
                        >
                        <div
                            class="estilo-vista ml-3"
                            v-if="
                                datos.obligado_contabilidad > 0 &&
                                    datos.obligado_contabilidad !== null
                            "
                        >
                            Si
                        </div>
                        <div class="estilo-vista ml-3" v-else>No</div>
                    </div>
                    <div
                        class="vx-col sm:w-1/3 w-full mb-6"
                        :class="{ 'sm:w-1/4': datos.balance == 'Si' }"
                    >
                        <label class="vs-input--label"
                            >Fecha de último cierre</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.fcierre | fecha }}
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/3 w-full mb-6"
                        :class="{ 'sm:w-1/4': datos.balance == 'Si' }"
                    >
                        <label class="vs-input--label"
                            >Balance consolidado</label
                        >
                        <div class="estilo-vista ml-3">{{ datos.balance }}</div>
                    </div>
                    <div
                        class="vx-col sm:w-1/4 w-full mb-6"
                        v-if="datos.balance == 'Si'"
                    >
                        <label class="vs-input--label">Empresa asociada</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.nombre_empresa_asociada }}
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/3 w-full mb-6"
                        :class="{ 'sm:w-1/4': datos.balance == 'Si' }"
                    >
                        <label class="vs-input--label">Ultimo Recalculo</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.recalculo | fecha }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label">Codigo Entidad</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.codigo_entidad }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label"
                            >Contribuyente Especial</label
                        >
                        <div
                            class="estilo-vista ml-3"
                            v-if="datos.contribuyente"
                        >
                            Si
                        </div>
                        <div class="estilo-vista ml-3" v-else>No</div>
                    </div>
                    
                </div>
                <div class="vx-row chtm" v-if="datos.contribuyente == 1">
                    <vs-divider border-style="solid" color="dark"
                        >Contribuyente especial</vs-divider
                    >
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Fecha Resolucion</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.fresolucion | fecha }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">No. Resolucion</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.noresolucion }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">No. Contribuyente</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.nocontribuyente }}
                        </div>
                    </div>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos adicionales</vs-divider
                >
                <div class="vx-row">
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <label class="vs-input--label">Página web</label>
                        <div class="estilo-vista ml-3">{{ datos.urlweb }}</div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <label class="vs-input--label"
                            >Facturación en negativo</label
                        >
                        <div class="estilo-vista ml-3" v-if="datos.negativo">
                            Si
                        </div>
                        <div class="estilo-vista ml-3" v-else>No</div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Leyenda</label>
                        <div
                            class="estilo-vista ml-3"
                            v-if="datos.leyenda == 2"
                        >
                            AGENTE DE RETENCION RESOLUCION NAC No
                            DNCRASC20-00000001 Y REGIMEN MICROEMPRESA
                        </div>
                        <div class="estilo-vista ml-3" v-else-if="datos.leyenda == 3">
                            AGENTE DE RETENCION RESOLUCION NAC No
                            DNCRASC20-00000001 Y REGIMEN RIMPE
                        </div>
                        <div class="estilo-vista ml-3">
                            {{ datos.leyenda }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Email Facturación</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.email_facturacion }}
                        </div>
                    </div>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Punto de emisión</vs-divider
                >
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Secuencial de Nota de crédito</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.secuencial_factura }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Secuencial de Nota de débito</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.secuencial_nota_credito }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Secuencial de Guia de remisión</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.secuencial_guia_remision }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Secuencial de Retención</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.secuencial_retencion }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Secuencial liquidación de compra</label
                        >
                        <div class="estilo-vista ml-3">
                            {{ datos.secuencial_liquidacion_compra }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6 imagencudro">
                        <label class="vs-input--label"
                            >Estado de punto de emisión:</label
                        >
                        <div class="estilo-vista ml-3" v-if="datos.estado">
                            Activo
                        </div>
                        <div class="estilo-vista ml-3" v-else>Inactivo</div>
                    </div>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Configuración de facturación electrónica</vs-divider
                >
                <div class="vx-row">
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label">Email</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.email_empresa }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label">Contraseña</label>
                        <div class="estilo-vista ml-3" v-if="datos.password">
                            Contraseña agregado
                        </div>
                        <div class="estilo-vista ml-3" v-else>
                            Contraseña no agregado
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Servidor Correo</label>
                        <div class="estilo-vista ml-3">
                            {{ datos.servidor_correo }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Puerto</label>
                        <div class="estilo-vista ml-3">
                            Puerto {{ datos.puerto_correo }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label"
                            >Capa de puerto de correo</label
                        >
                        <div class="estilo-vista ml-3">
                            Capa de puerto {{ datos.seguridad_correo }}
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label">Tipo de emisión</label>
                        <div
                            class="estilo-vista ml-3"
                            v-if="datos.tipo_emision == 1"
                        >
                            Emisor Normal
                        </div>
                        <div
                            class="estilo-vista ml-3"
                            v-if="datos.tipo_emision == 2"
                        >
                            Indisponibilidad del SRI
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <label class="vs-input--label">Ambiente</label>
                        <div
                            class="estilo-vista ml-3"
                            v-if="datos.ambiente == 1"
                        >
                            Pruebas
                        </div>
                        <div
                            class="estilo-vista ml-3"
                            v-if="datos.ambiente == 2"
                        >
                            Producción
                        </div>
                    </div>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Imagen y Firma P12</vs-divider
                >
                <div class="vx-row">
                    <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto">
                        <label class="vs-input--label"
                            >Logotipo de la empresa</label
                        >
                        <div class="vx-col md:w-full w-full mb-6">
                            <div class="verimagen" style="position: relative;">
                                <img
                                    :src="
                                        '/' +
                                            usuario.id_empresa +
                                            '/imagen/' +
                                            datos.logo
                                    "
                                    class="imagenpre"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto">
                        <label class="vs-input--label">Firma de tipo p12</label>
                        <div class="form-group">
                            <label class="btn btn-primary">
                                <vs-button
                                    v-if="
                                        datos.firma &&
                                            datos.fecha_expiracion_firma
                                    "
                                    color="primary"
                                    type="filled"
                                    >Firma válida hasta el
                                    <b>{{
                                        datos.fecha_expiracion_firma | fecha
                                    }}</b>
                                </vs-button>
                                <vs-button
                                    v-else-if="
                                        datos.firma &&
                                            !datos.fecha_expiracion_firma
                                    "
                                    color="primary"
                                    type="filled"
                                    >Sin fecha de expiración de la firma <b></b>
                                </vs-button>
                                <vs-button v-else color="primary" type="filled"
                                    >Firma no agregada a la empresa</vs-button
                                >
                            </label>
                        </div>
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        AgGridVue
    },
    filters: {
        fecha(data) {
            return moment(data).format("LL");
        }
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    data() {
        return {
            datos: []
        };
    },
    methods: {
        visualizar() {
            axios
                .get("/api/verempresa/" + this.usuario.id_empresa)
                .then(res => {
                    this.datos = res.data[0];
                });
        }
    },
    mounted() {
        this.visualizar();
    }
};
</script>
<style>
input[type="”file”"]#nuestroinput {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
label[for=" nuestroinput"] {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background-color: #106ba0;
    display: inline-block;
    transition: all 0.5s;
    cursor: pointer;
    padding: 15px 40px !important;
    text-transform: uppercase;
    width: fit-content;
    text-align: center;
}
.imagenpre {
    max-height: 100%;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    max-width: 100%;
}
.centimg {
    height: 225px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.verimagen {
    overflow: hidden;
    padding: 0px;
    height: 300px;
    height: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    border: 5px solid rgba(0, 0, 0, 0.3);
}

.imagenpre:hover {
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
}

.centimg:hover {
    background: rgba(255, 255, 255, 0.6) !important;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.centimg img {
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.demo-alignment > * {
    margin-right: 1.5rem;
    margin-top: 0.8rem;
}
.text-danger {
    font-size: 13px;
}
.vs-dialog {
    max-width: 900px !important;
}
.estiloborrar {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.estiloborrar:hover {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    box-shadow: 0px -20px 20px 20px red;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.verimagen:hover .spanborrar {
    margin-bottom: 0px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.spanborrar {
    margin-bottom: -999px;
    position: absolute;
    bottom: 29px;
    color: red;
    font-size: 44px;
    font-weight: bold;
    margin-left: -10px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.estilo-vista {
    color: inherit;
    position: relative;
    padding: 0.4rem;
    border-radius: 5px;
    border: 1px solid rgba(0, 0, 0, 0.2);
    -webkit-box-sizing: border-box;
    box-sizing: border-box;
    -webkit-box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.15);
    box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.15);
    -webkit-transition: all 0.3s ease;
    transition: all 0.3s ease;
    width: 100%;
    min-height: 34px;
}
.text-center {
    justify-content: center;
}
</style>
