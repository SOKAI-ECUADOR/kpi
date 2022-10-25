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
                        @keyup="listar(buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/contabilidad/asientos-contables/agregar"
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
                                <vs-dropdown-item class="text-center" divider
                                    >Generar PDF</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Generar XML</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe  max-items="25" pagination :data="listaDeAsientosContables">
                <template slot="thead">
                    <!--<vs-th>#</vs-th>-->
                    <vs-th>Código</vs-th>
                    <!--<vs-th>Proyecto</vs-th>-->
                    <vs-th>Concepto</vs-th>
                    <vs-th>Comprobante</vs-th>
                    <vs-th>Razon social</vs-th>
                    <vs-th>Fecha</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id_asientos" v-for="(datos) in data">
                        <!--<vs-td >{{index + 1}}</vs-td>-->
                        <vs-td v-if="datos.numero">{{datos.codigo.toUpperCase()}}-{{ datos.numero }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <!--<vs-td v-if="datos.descripcion">
                            {{ datos.descripcion }}
                        </vs-td>
                        <vs-td v-else>-</vs-td>-->
                        <vs-td v-if="datos.concepto">{{
                            datos.concepto
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.tipo">{{
                            datos.tipo.toUpperCase()
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.razon_social">{{
                            datos.razon_social
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.fecha">{{ datos.fecha |fecha}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <!-- prettier-ignore -->
                            <feather-icon
                                v-if="datos.cierre_anio<=0 && datos.cierre_mes<=0"
                                icon="EditIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="editarAsientoContable(datos.id_asientos)"
                            />
                            <feather-icon
                                v-else
                                icon="EyeIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="verAsientoContable(datos.id_asientos)"
                            />
                            <!-- prettier-ignore -->
                            <feather-icon
                                v-if="datos.cierre_anio<=0 && datos.cierre_mes<=0"
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos,datos.id_asientos_comprobante,datos.id_proyecto,datos.codigo_rol)"
                            />
                            <feather-icon
                                icon="PrinterIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarPdf(datos.id_asientos)"
                            />
                            <feather-icon
                                v-if="datos.existe_cheque>0"
                                hidden
                                icon="CreditCardIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="generarCheques(datos.id_asientos,datos.fecha)"
                            />
                            <!--<feather-icon
                                icon="MailIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos,datos.id_asientos_comprobante,datos.id_proyecto,datos.codigo_rol)"
                            />
                            <feather-icon
                                icon="ClipboardIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos,datos.id_asientos_comprobante,datos.id_proyecto,datos.codigo_rol)"
                            />
                            <feather-icon
                                icon="PrinterIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                @click.stop="eliminarAsientoContable(datos.id_asientos,datos.id_asientos_comprobante,datos.id_proyecto,datos.codigo_rol)"
                            />-->
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { concat } from "bytebuffer";
import Datepicker from "vuejs-datepicker";
const axios = require("axios");
import moment from "moment";
moment.locale("es");
export default {
    components: {
        AgGridVue,
        Datepicker,
        flatPickr
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
            //buscador
            buscar: "",
            //otros valores
            gridApi: null,
            contenido: [],
            //otros valores
            gridApi: null,
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            configdateTimePicker: {
                locale: SpanishLocale
            },
            listaDeAsientosContables: []
        };
    },
    filters:{
        fecha(data){
            
            return moment(data).format("Y-MM-DD");
        },
        upper: function (value) {
            return value.toUpperCase();
        }
    },
    methods: {
        listarAsientosContables() {
            var url="/api/asientos-contables/manuales/listar/"+this.usuario.id_empresa;
            axios
                .get(url)
                .then(({ data: listaDeAsientosContables }) => {
                    console.log('LL',listaDeAsientosContables)
                    this.listaDeAsientosContables = [];
                    this.listaDeAsientosContables = listaDeAsientosContables;
                });
        },
        editarAsientoContable(idAsientoContable) {
            this.$router.push(
                `/contabilidad/asientos-contables/${idAsientoContable}/editar`
            );
        },
        verAsientoContable(idAsientoContable) {
            this.$router.push(
                `/contabilidad/asientos-contables/${idAsientoContable}/ver`
            );
        },
        eliminarAsientoContable(id,comprobante,proyecto,cod_rol) {
            //metodo eliminar
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: "¿Desea Elimnar este registro?",
                accept: () => {
                    this.$vs.notify({
                        color: "warning",
                        text: "Se esta eliminando este registro espere"
                    });
                    axios
                        .post(`/api/asientos-contables/manuales/eliminar`, {
                            id: id,
                            id_comprobante:comprobante,
                            id_proyecto:proyecto,
                            cod_rol:cod_rol,
                            id_user:this.usuario.id
                        })
                        .then(respuesta => {
                            this.listarAsientosContables();
                            this.$vs.notify({
                                color: "success",
                                title: "Asiento Eliminado  ",
                                text: "El asiento selecionado fue eliminado con exito"
                            });
                        }).catch(err=>{
                            this.$vs.notify({
                                color: "danger",
                                title: "Error al Eliminar",
                                text: "Este registro se esta utilizando en otra seccion"
                            });
                        });
                }
            });
        },
        generarPdf(cod_rol,destinatario=null,email=null){
            axios({
                    url: "/api/pdf/asientos",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      id_asientos:cod_rol,
                      id_empresa:this.usuario.id_empresa,
                      destinatario:destinatario,
                      email:email
                    }
                
                }).then(resp=>{
                  console.log("ejecutado empleado");
                //this.contenidopr=res.data;
                console.log("resp:"+resp);
                  console.log("resp data:"+resp.data);

                //   var decodedString = String.fromCharCode.apply(
                //     null,
                //     new Uint8Array(resp.data)
                // );
                // if (decodedString.includes("no-data-report")) {
                //     this.$vs.notify({
                //       title: "Sin Registros",
                //       text: "Los Datos que escogio no tienen registros",
                //       color: "danger"
                //     });
                // }
                
                let { headers } = resp;
                let nameFile = headers["content-disposition"]
                    .split(";")[1]
                    .split("=")[1]
                    .replace(/"/g, "");
                const url = window.URL.createObjectURL(
                    new Blob([resp.data], { type: "application/pdf" })
                );
                console.log("nombre:"+nameFile+"url:"+url);
                //return({ url: url, nameFile: nameFile });
                console.log("URL_NAME::", url, nameFile);
                const link = document.createElement("a");
                link.href = url;
                link.download = "Reporte.pdf";
                link.setAttribute("download", nameFile);
                document.body.appendChild(link);
                link.click();
                this.$vs.notify({
                    title: "Reporte Generado",
                    text: "Su reporte esta siendo descargado exitosamente!",
                    color: "success"
                });
                /*this.modal_proyecto=false;
                this.dep_rol="";
                this.fechrolprov="";
                this.datos_proyecto.selectedproyecto=[];*/
                }).catch(err=>{
                  console.log("ERROR"+err);
                });
        },
        generarCheques(id,fecha,destinatario=null,email=null){
            axios({
                    url: "/api/cheques/asientos",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      id_asientos:id,
                      fecha:fecha,
                      id_empresa:this.usuario.id_empresa,
                      destinatario:destinatario,
                      email:email
                    }
                
                }).then(resp=>{
                    console.log("ejecutado cheque");
                //this.contenidopr=res.data;
                console.log("resp:"+resp);
                  console.log("resp data:"+resp.data);

                //   var decodedString = String.fromCharCode.apply(
                //     null,
                //     new Uint8Array(resp.data)
                // );
                // if (decodedString.includes("no-data-report")) {
                //     this.$vs.notify({
                //       title: "Sin Registros",
                //       text: "Los Datos que escogio no tienen registros",
                //       color: "danger"
                //     });
                // }
                
                let { headers } = resp;
                let nameFile = headers["content-disposition"]
                    .split(";")[1]
                    .split("=")[1]
                    .replace(/"/g, "");
                const url = window.URL.createObjectURL(
                    new Blob([resp.data], { type: "application/pdf" })
                );
                console.log("nombre:"+nameFile+"url:"+url);
                //return({ url: url, nameFile: nameFile });
                console.log("URL_NAME::", url, nameFile);
                const link = document.createElement("a");
                link.href = url;
                link.download = "Reporte.pdf";
                link.setAttribute("download", nameFile);
                document.body.appendChild(link);
                link.click();
                this.$vs.notify({
                    title: "Reporte Generado",
                    text: "Su reporte esta siendo descargado exitosamente!",
                    color: "success"
                });
                }).catch(err=>{
                  console.log("ERROR"+err);
                });
        },
        listar(busqueda) {
            axios
                .post(`/api/asientos-contables/manuales/buscar`, {
                    query: busqueda,
                    id_empresa:this.usuario.id_empresa
                })
                .then(({ data: listaDeAsientosContables }) => {
                    this.listaDeAsientosContables = [];
                    this.listaDeAsientosContables = listaDeAsientosContables;
                });
        }
    },
    mounted() {
        this.listarAsientosContables();
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
</style>
