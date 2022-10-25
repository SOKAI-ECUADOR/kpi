<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1, buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/facturacion/proforma/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Exportar registros</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe max-items="25" pagination :data="contenido">
                <template slot="thead">
                    <vs-th class="text-center">No.</vs-th>
                    <vs-th class="text-center">Cliente</vs-th>
                    <vs-th class="text-center">Fecha de Emisión</vs-th>
                    <vs-th class="text-center">Estatus</vs-th>
                    <vs-th class="text-center">Valor Total</vs-th>
                    <vs-th class="text-center">Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr
                        class="text-center"
                        :key="datos.id_factura"
                        v-for="datos in data"
                    >
                        <vs-td v-if="datos.codigo_proforma" style="width:5%;">{{
                            datos.codigo_proforma
                        }}</vs-td>
                        <vs-td v-else style="width:5%;">-</vs-td>
                        <vs-td v-if="datos.nombre" style="width:30%;">{{
                            datos.nombre
                        }}</vs-td>
                        <vs-td v-else style="width:30%;">-</vs-td>
                        <vs-td
                            v-if="datos.fecha_emision"
                            style="width:16.6%;"
                            >{{ datos.fecha_emision | fecha }}</vs-td
                        >
                        <vs-td v-else style="width:16.6%;">-</vs-td>
                        <vs-td v-if="datos.modo == 1" style="width:16.6%;">
                            <div style="color:#61B633;">Facturado</div>
                        </vs-td>
                        <vs-td v-else-if="datos.modo == 2" style="width:16.6%;">
                            <div style="color:#61B633;">Nota de Venta</div>
                        </vs-td>
                        <vs-td v-else style="width:16.6%;">
                            <div style="color:#CE7C3B;">Sin Facturar</div>
                        </vs-td>
                        <vs-td v-if="datos.valor_total" style="width:16.6%;">{{
                            datos.valor_total | currency
                        }}</vs-td>
                        <vs-td v-else style="width:16.6%;">-</vs-td>
                        <vs-td class="whitespace-no-wrap" style="width:15%;">
                            <vx-tooltip
                                text="Editar Proforma"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                    v-if="editarrol"
                                    icon="EditIcon"
                                    svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                                    @click.stop="editar(datos.id_factura)"
                            /></vx-tooltip>
                            <vs-dropdown>
                                <feather-icon
                                    icon="MailIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                                    class="ml-2"
                                />
                                <vs-dropdown-menu>
                                    <!-- prettier-ignore -->
                                    <vs-dropdown-item
                                        @click.stop="
                                            enviarproforma(datos.id_factura)"
                                    >
                                        Enviar a Cliente
                                    </vs-dropdown-item>
                                    <!-- prettier-ignore -->
                                    <vs-dropdown-item
                                        @click.stop="
                                            enviarotrocorreo(datos.id_factura)"
                                    >
                                        Enviar a otro correo
                                    </vs-dropdown-item>
                                </vs-dropdown-menu>
                            </vs-dropdown>
                            <vx-tooltip
                                text="Descargar PDF"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                    icon="DownloadIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                                    class="ml-2"
                                    @click="imprimepdf(datos.id_factura)"
                            /></vx-tooltip>

                            <vs-dropdown v-if="datos.modo == 0 && editarrol">
                                <feather-icon
                                    icon="ShoppingCartIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                                    class="ml-2"
                                />
                                <vs-dropdown-menu>
                                    <!-- prettier-ignore -->
                                    <vs-dropdown-item
                                    @click.stop="crearfactura(datos.id_factura)"
                                    >
                                        Generar Factura
                                    </vs-dropdown-item>
                                    <!-- prettier-ignore -->
                                    <vs-dropdown-item
                                    @click.stop="crearnotaventa(datos.id_factura)"
                                    >
                                        Generar Nota de Venta
                                    </vs-dropdown-item>
                                </vs-dropdown-menu>
                            </vs-dropdown>

                            <!-- <vx-tooltip
                                text="Generar Factura"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                    v-if="datos.modo != 1 && editarrol"
                                    icon="ShoppingCartIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                    class="ml-2 pointer"
                                    @click.stop="crearfactura(datos.id_factura)"
                            /></vx-tooltip> -->
                            <!-- <vx-tooltip
                                text="Borrar Proforma"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                    v-if="eliminarrol"
                                    icon="TrashIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current cursor-pointer"
                                    class="ml-2"
                                    @click.stop="eliminarprof(datos.id_factura)"
                            /></vx-tooltip> -->
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <vs-popup title="Destinatario de Correo" :active.sync="popupcorreo">
            <div class="vx-col sm:w-full w-full mb-6 relative">
                <vs-input
                    class="w-full"
                    label="Nombre:"
                    v-model="nombrecliente"
                />
            </div>
            <div class="vx-col sm:full w-full mb-6 relative">
                <!--<vs-input
                    class="w-full"
                    label="Dirección de Correo Electrónico:"
                    v-model="correocliente"
                />-->
                <vs-chips
                    color="rgb(145, 32, 159)"
                    label="E-mail"
                    placeholder="Agregue los correos"
                    v-model="correocliente"
                    icon-pack="feather"
                    remove-icon="icon-trash-2"
                >
                    <vs-chip
                        :key="data"
                        @click="remove_chip_correo(data)"
                        v-for="data in correocliente"
                        closable
                        icon-pack="feather"
                        close-icon="icon-trash-2"
                    >
                        {{ data }}
                    </vs-chip>
                </vs-chips>
                <span style="font-size: 11px;margin-left: 10px;"
                    >despues de agregar un correo pulse la tecla enter</span
                >
                <div v-show="errorenvio">
                    <div
                        v-for="err in errorcorreo"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                    ></div>
                </div>
            </div>
            <div class="vx-col w-full mt-5">
                <vs-button
                    color="success"
                    type="border"
                    @click="enviarotroproforma()"
                    >Enviar</vs-button
                >
                <vs-button color="danger" type="border" @click="cancelarenvio()"
                    >Cancelar</vs-button
                >
            </div>
        </vs-popup>
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
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
        },
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if (el.nombre == "Proforma") {
                        res = el.crear;
                        return res;
                    }
                });
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if (el.nombre == "Proforma") {
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if (el.nombre == "Proforma") {
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        }
    },
    data() {
        return {
            //mapeo de datos
            //paginacion
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina: 1,
            cantidadp: 10,
            offset: 3,
            //buscador
            buscar: "",
            criterio: "secuencial",
            //otros valores
            gridApi: null,
            contenido: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),

            //envio correos
            popupcorreo: false,
            id_prof_correo: null,
            nombrecliente: "",
            correocliente: [],
            //validar correo
            errorenvio: 0,
            errorcorreo: [],
            errorcorreomal: [],
            timeout: null
        };
    },
    methods: {
        listar(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/proforma/" +
                    this.usuario.id_empresa +
                    "?page=" +
                    page +
                    "&buscar=" +
                    buscar +
                    "&id_user=" +
                    this.usuario.id;
                axios.get(url).then(res => {
                    var respuesta = res.data;
                    this.contenido = respuesta.recupera;
                });
            }, 800);
        },
        eliminarprof(id) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Elimnar este registro?`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/eliminarproforma/" + parameters);
            this.$vs.notify({
                color: "success",
                title: "Proforma Eliminada  ",
                text: "Proforma eliminada con exito"
            });
            this.listar(1, this.buscar);
        },
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val);
        },
        editar(id) {
            this.$router.push(`/facturacion/proforma/${id}/editar`);
        },
        crearfactura(id) {
            this.$router.push(`/facturacion/factura-venta/${id}/editar`);
        },
        crearnotaventa(id) {
            this.$router.push(`/facturacion/factura_acumuladaproforma/${id}`);
        },
        async imprimepdf(id) {
            try {
                let resp = await axios({
                    url: "/api/reportes/proforma/?id_factura=" + id,
                    method: "GET",
                    responseType: "arraybuffer"
                });
                var decodedString = new TextDecoder("utf-8").decode(
                    new Uint8Array(resp.data)
                );
                if (decodedString.includes("no-data-report")) {
                    return this.$vs.notify({
                        title: "Sin registros",
                        text:
                            "No se encontraron registros con los datos proporcionados",
                        color: "warning"
                    });
                }
                let { headers } = resp;
                let nameFile = headers["content-disposition"]
                    .split(";")[1]
                    .split("=")[1]
                    .replace(/"/g, "");
                const url = window.URL.createObjectURL(
                    new Blob([resp.data], { type: "application/pdf" })
                );

                const link = document.createElement("a");
                link.href = url;
                link.download = "Reporte.pdf";
                link.setAttribute("download", nameFile);
                document.body.appendChild(link);
                link.click();

                this.$vs.notify({
                    title: "Reporte Generado",
                    text: " Proforma descargada exitosamente!",
                    color: "success"
                });
            } catch (error) {
                console.error("ERROR::DESCARGAR PDF:"+error);
            }
        },
        enviarproforma(id) {
            this.$vs.notify({
                time: 3000,
                title: "Enviando proforma",
                text: "Por favor espere...",
                color: "warning"
            });
            axios
                .post("/api/proforma/enviarcorreo", { id: id, tipo: 1 })
                .then(() => {
                    this.$vs.notify({
                        title: "Proforma enviada",
                        text: "Proforma enviada exitosamente",
                        color: "success"
                    });
                });
        },
        enviarotrocorreo(id) {
            this.popupcorreo = true;
            this.id_prof_correo = id;
            this.nombrecliente = "";
            this.correocliente = [];
        },
        enviarotroproforma() {
            if (this.validarcorreo()) {
                return;
            }
            this.$vs.notify({
                time: 3000,
                title: "Enviando proforma",
                text: "Por favor espere...",
                color: "warning"
            });
            axios
                .post("/api/proforma/enviarcorreo", {
                    id: this.id_prof_correo,
                    tipo: 2,
                    correocliente: this.correocliente,
                    nombrecliente: this.nombrecliente
                })
                .then(() => {
                    this.$vs.notify({
                        title: "Proforma enviada",
                        text:
                            "Proforma enviada exitosamente a: " +
                            this.correocliente,
                        color: "success"
                    });
                    this.popupcorreo = false;
                });
        },
        cancelarenvio() {
            this.popupcorreo = false;
            this.id_prof_correo = null;
            this.nombrecliente = "";
            this.correocliente = [];
            this.errorenvio = 0;
            this.errorcorreo = [];
        },
        validarcorreo() {
            this.errorenvio = 0;
            this.errorcorreo = [];

            if (this.correocliente.length <= 0) {
                this.errorcorreo.push("Ingrese una dirección email");
                this.errorenvio = 1;
                window.scrollTo(0, 0);
            }
            if (this.correocliente) {
                for (var i = 0; i < this.correocliente.length; i++) {
                    if (this.validaremail(this.correocliente[i]) == false) {
                        this.errorcorreo.push("Ingrese un email válido");
                        this.errorenvio = 1;
                        window.scrollTo(0, 0);
                    }
                }
            }
            return this.errorenvio;
        },
        validaremail(correocliente) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(correocliente);
        },
        //correos
        remove_chip_correo(item) {
            this.correocliente.splice(this.correocliente.indexOf(item), 1);
        }
    },
    mounted() {
        this.listar(1, this.buscar);
    }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.dropdown-button-container {
    display: flex;
    align-items: center;

    .btnx {
        border-radius: 5px 0px 0px 5px;
    }
    .btn-drop {
        border-radius: 0px 5px 5px 0px;
        border-left: 1px solid rgba(255, 255, 255, 0.2);
    }
}
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
</style>
