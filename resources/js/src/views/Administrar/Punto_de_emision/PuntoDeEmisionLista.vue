<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="mb-4 flex flex-wrap justify-between items-center">
                <div class="mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup.enter="listar(1, buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button class="btnx" type="filled" @click="crear()"
                            >Agregar</vs-button
                        >
                         <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="importar = true"
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="exportar = true"
                                    >Exportar registros</vs-dropdown-item
                                >
                                
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>

            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <vs-th>Nombre</vs-th>
                    <vs-th>Cod.</vs-th>
                    <vs-th>S. F</vs-th>
                    <vs-th>S. NC</vs-th>
                    <vs-th>S. ND</vs-th>
                    <vs-th>S. GR</vs-th>
                    <vs-th>S. R</vs-th>
                    <vs-th>S. LC</vs-th>
                    <vs-th>S. RB</vs-th>
                    <vs-th>Estado</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id" v-for="datos in data">
                        <vs-td>{{ datos.nombre }}</vs-td>
                        <vs-td v-if="datos.codigo">{{ datos.codigo }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.secuencial_factura">{{
                            datos.secuencial_factura
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.secuencial_nota_credito">{{
                            datos.secuencial_nota_credito
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.secuencial_nota_debito">{{
                            datos.secuencial_nota_debito
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.secuencial_guia_remision">{{
                            datos.secuencial_guia_remision
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.secuencial_retencion">{{
                            datos.secuencial_retencion
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.secuencial_liquidacion_compra">{{
                            datos.secuencial_liquidacion_compra
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.secuencial_recibo">{{
                            datos.secuencial_recibo
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.activo == 1">
                            <div style="color:green;">Activo</div>
                        </vs-td>
                        <vs-td v-else>
                            <div style="color:red;">Inactivo</div>
                        </vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                v-if="editarrol"
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="edit(datos.id_punto_emision)"
                            />
                            <feather-icon
                                v-if="eliminarrol"
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                @click.stop="eliminar(datos.id_punto_emision)"
                            />
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
         <!-- =============================================Modal para exportar excel=======================================-->
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
        <!--===================================================fin modal de exportar=================================================== -->

        <!-- ================================================== Modal para importar excel ============================================== -->
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

                            <div class="vx-col md:w-full w-full mb-6"  >
                                <div style="display:none" >
                                    <input
                                        id="input-upload"
                                        type="file"
                                        class="custom-file-input inputexcel"
                                        @change="subirArchivo($event)"
                                        accept=".XLSX, .CSV"
                                         
                                    />
                                </div>

                                <div class="centimg vx-card input" @click="importarexcel()">
                                    <img src="/images/upload.png" />
                                    <div v-if="file.length === 0" style="position:absolute;margin-top:60px;color:#000" >Click para subir Archivo</div>
                                    <div v-else  style="position:absolute;margin-top:60px;color:#000">{{file[0].name}}</div>   
                                </div>
                            </div>
                        </div>
                      
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="importardatos()"
                                >Subir Archivo</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="cancelar1()"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!-- =============================================== fin modal de importar ===================================================== -->
   
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import moment from "moment";
import $ from "jquery";
moment.locale("es");
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        ImportExcel
    },
    filters: {
        fecha(dato) {
            return moment(dato).format("LLL A");
        }
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        // crearrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[2].crear;
        //     }
        //     return res;
        // },
        // editarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[2].editar;
        //     }
        //     return res;
        // },
        // eliminarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[2].eliminar;
        //     }
        //     return res;
        // }
        crearrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Punto Emision"){
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
                    if(el.nombre == "Punto Emision"){
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
                    if(el.nombre == "Punto Emision"){
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
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
          /**
           * mapeo de datos
           */          
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
            offset: 0,
            /**
             *buscador
             */
            buscar: "",
            criterio: "codCta",
            //otros valores
            gridApi: null,
            contenido: [],
            activePrompt: false,
            val: "",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //Datos para la importaciond de archivos
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
                "nombre",
                "codigo",
                "secuencial_factura",
                "secuencial_nota_credito",
                "secuencial_nota_debito",
                "secuencial_guia_remision",
                "secuencial_retencion",
                "secuencial_liquidacion_compra",
                "activo",
                "id_establecimiento",
                "id_empresa"
            ],
            //campos elegidos a exportar
            indexs: [
                "nombre",
                "codigo",
                "secuencial_factura",
                "secuencial_nota_credito",
                "secuencial_nota_debito",
                "secuencial_guia_remision",
                "secuencial_retencion",
                "secuencial_liquidacion_compra",
                "activo",
                "id_establecimiento",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "nombre",
                "punto_de_emision",
                "secuencial_factura",
                "secuencial_nota_credito",
                "secuencial_nota_debito",
                "secuencial_guia_remision",
                "secuencial_retencion",
                "secuencial_liquidacion_compra",
                "activo",
                "id_establecimiento",
                "id_empresa"
            ]
        };
    },
    methods: {
         /*
         * exportar archivo
         */
        exportardatos() {
            import("../../../vendor/Export2PuntoEmisionExcel").then(excel => {
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
            });
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
        //preload
        readyFile ($e){
            // Este muestra una lista FileList
            console.log($e.target.files)
            // Este muestra el valor actual del input
            console.log($e.target.value)
        },
        cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = ""
        },
         subirArchivo(e) {
            let tempFile = e.target.files[0];
            var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
            if (!allowedExtensions.exec(tempFile.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .csv, .xls, .xlsx",
                    color: "danger"
                });
                return;
            }
            this.file.push(tempFile);
        },
        importarexcel() {
            $(".inputexcel").click();
            
        },
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/ImportarPuntosEmisionExcel", formData, {})
                .then((res) => {
                     document.getElementById("input-upload").value = ""
                    this.$vs.notify({
                                text: "Archivo Importado con exito",
                                color: "success",
                            });
                            this.importar = false;
                            this.listar(1, this.buscar);
                            this.file = [];   
                })
                .catch((err) => {
                    this.$vs.notify({
                        text: "Archivo Importado 'Sin prueba Exito'",
                        color: "danger",
                    });
                   this.importar = false;
                   this.listar(1, this.buscar);
                   this.file = []
                });
        },
        edit(id) {
            this.$router.push(`/administrar/punto-de-emision/${id}/editar`);
        },
        crear() {
            this.$router.push("/administrar/punto-de-emision/agregar");
        },
        eliminar(cd) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Elimnar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: cd
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/eliminarpt/" + parameters);
            this.$vs.notify({
                color: "success",
                title: "Empresa Eliminada  ",
                text: "Registro eliminado con exito"
            });
            this.listar(1, this.buscar);
        },
        listar(page, buscar) {
            let me = this;
            var url =
                "/api/ptoemision/" +
                this.usuario.id_empresa +
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
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val);
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
