<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1, buscar, criterio, cantidadp)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <!-- <div class="dropdown-button-container mr-3">
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="settings"
                                style="border-radius: 5px;"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirCtaImport()"
                                    >Cuenta de Importacion</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div> -->
                    <div class="dropdown-button-container mr-3" v-if="crearrol">
                        <!--@click="abrirModal()"-->
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/importacion/registro-importacion/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu>
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="importar = true"
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="exportar = true"
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
            <br />
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>No.Import</vs-th>
                    <vs-th>Periodo Inicio</vs-th>
                    <vs-th>Periodo Fin</vs-th>
                    <vs-th>Estado</vs-th>
                    <vs-th>Total</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr v-for="datos in data" :key="datos.id_importacion">
                        <vs-td v-if="datos.cod_importacion">{{
                            datos.cod_importacion
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.periodo_inicio">{{
                            datos.periodo_inicio
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.periodo_fin">{{
                            datos.periodo_fin
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.estado">{{ datos.estado }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.total_liquidacion">{{
                            datos.total_liquidacion
                        }}</vs-td>
                        <vs-td v-else-if="datos.total_importacion">{{datos.total_importacion}}</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <vx-tooltip
                                text="Editar Importacion"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                    v-if="editarrol && datos.contabilidad==null"
                                    icon="EditIcon"
                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                    @click="editar(datos.id_importacion)"
                                />
                            </vx-tooltip>
                            <!--<vx-tooltip
                                text="Ver Liquidacion"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                    v-if="editarrol"
                                    icon="EyeIcon"
                                    svgClasses="w-5 h-5 hover:text-dark stroke-current"
                                    @click="verliquid(datos.id_importacion)"
                                />
                            </vx-tooltip>-->
                            <!--
              <vx-tooltip text="Liquidar" position="top" style="display: inline-flex;">
              <feather-icon
                v-if="editarrol && datos.estado=='Inicial'"
                icon="SendIcon"
                svgClasses="w-5 h-5 hover:text-success stroke-current"
                @click="liquidar(datos.id_importacion)"
              />
              </vx-tooltip>
              -->
                            <vx-tooltip
                                text="Eliminar Importacion"
                                style="display: inline-flex;"
                            >
                                <feather-icon
                                    v-if="eliminarrol && datos.contabilidad==null"
                                    icon="TrashIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                    @click="eliminar(datos.id_importacion)"
                                />
                            </vx-tooltip>
                            <vx-tooltip text="Generar PDF" style="display: inline-flex;">
                                <feather-icon
                                                icon="PrinterIcon"
                                                svgClasses="w-5 h-5 hover:text-primary stroke-current cursor-pointer"
                                                class="ml-2"
                                                @click="generarPdf(datos.id_importacion,1)"
                                            />
                            </vx-tooltip>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
            <!--<vs-pagination :total="pagination.count"
        v-model="pagina"
        @change="listar(pagina,buscar,criterio,cantidadp)"
        prev-icon="arrow_back"
        next-icon="arrow_forward"
      ></vs-pagination>-->
        </vx-card>
        <!-------------------------------------------Modal para exportar excel-------------------------------------->
        <vs-popup
            :class="'peque1'"
            title="Exportar Excel"
            :active.sync="exportar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-input
                                v-model="nombreexportar"
                                placeholder="Nombre del archivo..."
                                class="w-full"
                            />

                            <div class="flex mb-4">
                                <span class="mr-4"
                                    >Celda con ancho predefinido:</span
                                >
                                <vs-switch v-model="cellancho"
                                    >Ancho de los campos del archivo</vs-switch
                                >
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="exportardatos"
                                >Descargar Excel</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="exportar = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!--fin modal de exportar-->

        <!-- ============================================== Modal para importar excel ============================================== -->
        <vs-popup
            :class="'peque2'"
            title="Importar Excel"
            :active.sync="importar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label class="vs-input--label">Subir Archivo</label>
                            <div class="vx-col md:w-full w-full mb-6">
                                <div style="display:none">
                                    <input
                                        :onSuccess="loadDataInTable"
                                        type="file"
                                        class="custom-file-input inputexcel"
                                        @change="obtenerimagen"
                                        accept=".XLSX, .CSV"
                                    />
                                </div>
                                <div
                                    class="centimg vx-card input"
                                    @click="importarexcel()"
                                >
                                    <img src="/images/upload.png" />
                                    <div
                                        style="position:absolute;margin-top:60px;color:#000"
                                    >
                                        Click para subir Archivo
                                    </div>
                                </div>
                            </div>
                        </div>
                        <vx-card v-if="tableData.length && header.length">
                            <vs-table
                                stripe
                                pagination
                                :max-items="10"
                                search
                                :data="tableData"
                            >
                                <template slot="header">
                                    <h4>{{ sheetName }}</h4>
                                </template>

                                <template slot="thead">
                                    <vs-th
                                        :sort-key="heading"
                                        v-for="heading in header"
                                        :key="heading"
                                        >{{ heading }}</vs-th
                                    >
                                </template>

                                <template
                                    slot-scope="{ data }"
                                    @change="obtenerimagen"
                                >
                                    <vs-tr
                                        :data="tr"
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td
                                            :data="col"
                                            v-for="col in data[indextr]"
                                            :key="col"
                                            >{{ col }}</vs-td
                                        >
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </vx-card>

                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="importardatos()"
                                >Subir Archivo</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="importar = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!-- ============================================= =================fin ================================================== -->
        
    </div>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";

import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";

const axios = require("axios");
export default {
    components: {
        AgGridVue,
        ImportExcel
    },
    data() {
        return {
            //mapeo de datos
            //paginacion
            /*pagination: {
        total: 0, 
        current_page: 0,
        per_page: 0,
        last_page: 0,
        from: 0,
        to: 0,
        count:0,
      },*/
            pagina: 1,
            cantidadp: 50,
            offset: 3,
            //buscador
            buscar: "",
            criterio: "codcta",
            //otros valores
            gridApi: null,
            contenido: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //Modal
            popupActive3: false,
            //campos

            cod_importacion: "",
            nro_orden: "",
            estado: "",
            fech_inicio: "",
            fech_fin: "",
            fech_embarque: "",
            fech_arribo: "",
            liquidar: "Si",
            cantidad: "",
            costo_unit: "",
            costo_total: "",
            id_proveedor: "",
            id_producto: "",
            //id_user:"",
            //fecult:"",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            //tabla empresa
            empresas: [],
            monedas: [],
            idrecupera: null,
            //modal
            titulomodal: "",
            modal: false,
            //traer proveedor
            proveedors: [],
            //traer productos
            productos: [],
            //Datos para la importaciond de archivos
            //excel import
            file: "",
            tableData: [],
            header: [],
            sheetName: "",
            importar: false,
            nombreimportar: "",
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            //campos que existen para exportar
            campos: [
                "id_importacion",
                "cod_importacion",
                "estado",
                "periodo_inicio",
                "periodo_fin",
                "fech_embarque",
                "fech_arribo",
                "forma_liquidacion",
                "fech_importacion",
                "total_facturas",
                "total_liquidacion",
                "total_importacion",
                "fcrea",
                "fmodifica",
                "id_proveedor",
                "id_orden",
                "id_user",
                "id_empresa",
                "id_punto_emision",
                "id_bodega"
            ],
            //campos elegidos a exportar
            indexs: [
                "cod_importacion",
                "estado",
                "periodo_inicio",
                "periodo_fin",
                "fech_embarque",
                "fech_arribo",
                "forma_liquidacion",
                "fech_importacion",
                "total_facturas",
                "total_liquidacion",
                "total_importacion",
                "id_proveedor",
                "id_orden",
                "id_user",
                "id_empresa",
                "id_punto_emision",
                "id_bodega"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "cod_importacion",
                "estado",
                "periodo_inicio",
                "periodo_fin",
                "fech_embarque",
                "fech_arribo",
                "forma_liquidacion",
                "fech_importacion",
                "total_facturas",
                "total_liquidacion",
                "total_importacion",
                "id_proveedor",
                "id_orden",
                "id_user",
                "id_empresa",
                "id_punto_emision",
                "id_bodega"
            ],

        };
    },
    components: {
        flatPickr,
        FormWizard,
        TabContent
    },
    filters: {
        fechasimple(val) {
            return moment(String(val))
                .locale("es")
                .format("LL");
        }
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        saldot() {
            this.costo_total =
                parseFloat(this.cantidad) * parseFloat(this.costo_unit);
        },
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[21].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[21].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[21].eliminar;
            }
            return res;
        }
    },
    methods: {
        //exportar archivos
        exportardatos() {
            import("../../../vendor/Export2RegistroImportacionExcel").then(
                excel => {
                    const list = this.contenido;
                    const data = this.formatJson(this.indexs, list);
                    excel.export_json_to_excel({
                        header: this.cabezera,
                        data,
                        filename: this.nombreexportar,
                        autoWidth: this.cellancho,
                        bookType: this.tipoformatoexportar
                    });
                    this.nombreexportar = "";
                    this.exportar = false;
                }
            );
        },
        formatJson(filterVal, jsonData) {
            return jsonData.map(v =>
                filterVal.map(j => {
                    return v[j];
                })
            );
        },
           /*
         *importar archivos
         *
         */
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file);
            axios
                .post("/api/ImportarRegistroImportacionExcel", formData, {})
                .then(res => {
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar = false;
                    this.listar(1, this.buscar, this.criterio, this.cantidadp);
                })
                .catch(err => {
                     this.$vs.notify({
                        text: "Archivo Importado no tuvo exito",
                        color: "danger"
                    });
                });
        },
        obtenerimagen(e) {
            let file = e.target.files[0];
            var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
            if (!allowedExtensions.exec(file.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .xlsx, .csv",
                    color: "danger"
                });
                return;
            }
            this.file = file;
        },
        loadDataInTable({ results, header, meta }) {
            this.header = header;
            this.tableData = results;
            this.sheetName = meta.sheetName;
        },
        importarexcel() {
            $(".inputexcel").click();
        },

        editar(id) {
            this.$router.push(`/importacion/registro-importacion/${id}/editar`);
        },
        eliminar(id) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            //console.log(parameters);
            axios.delete("/api/eliminarimportacion/" + parameters).then(res=>{
                this.$vs.notify({
                    color: "success",
                    title: "Reguistro Eliminado  ",
                    text: "El Reguistro selecionado fue eliminado con exito"
                });
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al eliminar reguistro",
                    text: "Este reguistro esta siendo utilizado en otro modulo"
                });
            });
            
            this.listar(1, this.buscar, this.cantidadp);
        },
        generarPdf(cod_rol,tipo,destinatario=null,email=null){
            //this.id_orden=cod_rol;
            axios({
                            url: "/api/pdf/importacion",
                            method: "GET",
                            responseType: "arraybuffer",
                            params:{
                            id_orden:cod_rol,
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
                        if(destinatario==null && email==null){
                        this.$vs.notify({
                            title: "Pdf Generado",
                            text: "Su pdf ha sido descargado exitosamente!",
                            color: "success"
                        });
                        }else{
                        this.enviarorden(cod_rol,tipo,destinatario,email);
                        }
                        

                        /*this.modal_proyecto=false;
                        this.dep_rol="";
                        this.fechrolprov="";
                        this.datos_proyecto.selectedproyecto=[];*/
                        }).catch(err=>{
                        console.log("ERROR generar pdf"+err);
                            if(destinatario==null && email==null){
                            this.$vs.notify({
                                title: "Pdf Generado",
                                text: "Su pdf ha sido descargado exitosamente!",
                                color: "success"
                            });
                            }else{
                            this.enviarorden(cod_rol,tipo,destinatario,email);
                            }
                        });
        },
        listar(page, buscar) {
            let me = this;
            var url =
                "/api/importacion/" +
                this.usuario.id_punto_emision +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.contenido = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        verliquid(id) {
            this.$router.push(`/importacion/liquidacion/${id}/editar`);
        },
        
    },
    mounted() {
        this.listar(1, this.buscar, this.criterio, this.cantidadp);
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
.vs-popup {
    width: 800px !important;
}
.peque1 .vs-popup {
    width: 500px !important;
}
.peque2 .vs-popup {
    width: 500px !important;
}
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
        height: 100%;
        cursor: pointer;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -ms-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        transition: all 0.3s ease;
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
</style>
