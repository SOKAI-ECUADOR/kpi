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
                    <!--botón de herramientas-->
                    <div class="dropdown-button-container mr-3">
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="settings"
                                style="border-radius: 5px;"
                            ></vs-button>
                            <vs-dropdown-menu style="width: 13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirlinea()"
                                    >Grupo Cliente</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirtipo()"
                                    >Tipo de Cliente</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <!--Fin de bóton de herramientas-->
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/seguimiento/cliente/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width: 13em;">
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
            <vs-table stripe max-items=25 pagination :data="contenido">
                <template slot="thead">
                    <vs-th class="text-center">Cod.</vs-th>
                    <vs-th class="text-center">Nombre</vs-th>
                    <vs-th class="text-center">Identificación</vs-th>
                    <vs-th class="text-center">Teléfono</vs-th>
                    <vs-th class="text-center">Estado</vs-th>
                    <vs-th class="text-center">Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr
                        :key="datos.id_cliente"
                        v-for="datos in data"
                        class="text-center"
                    >
                        <vs-td v-if="datos.codigo" style="width:5%;">{{
                            datos.codigo
                        }}</vs-td>
                        <vs-td v-else style="width:5%;">-</vs-td>
                        <vs-td v-if="datos.nombre" style="width:50%;">{{
                            datos.nombre
                        }}</vs-td>
                        <vs-td v-else style="width:50%;">-</vs-td>
                        <vs-td v-if="datos.identificacion" style="width:15%;">{{
                            datos.identificacion
                        }}</vs-td>
                        <vs-td v-else style="width:15%;">-</vs-td>
                        <vs-td v-if="datos.telefono" style="width:15%;">{{
                            datos.telefono
                        }}</vs-td>
                        <vs-td v-else style="width:15%;">-</vs-td>
                        <vs-td v-if="datos.estado" style="width:10%;">{{
                            datos.estado
                        }}</vs-td>
                        <vs-td v-else style="width:10%;">-</vs-td>
                        <vs-td class="whitespace-no-wrap" style="width:5%;">
                            <feather-icon
                                v-if="editarrol"
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click.stop="editar(datos.id_cliente)"
                            />
                            <feather-icon
                                v-if="eliminarrol"
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer"
                                @click.stop="eliminarCliente(datos.id_cliente)"
                            />
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <!--========================================================Modales================================================-->
        <vs-popup :title="titulomodal" :active.sync="modal">
            <div class="vx-row">
                <!--===================================Modal principal Grupo cliente=======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 1"
                >
                    <vx-card>
                        <div class="vx-row">
                            <div
                                class="vx-col md:w-full w-full mb-6"
                                id="ag-grid-demo"
                            >
                                <div
                                    class="flex flex-wrap justify-between items-center mb-3"
                                >
                                    <!-- ITEMS PER PAGE -->
                                    <div
                                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                    ></div>
                                    <div
                                        class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                                    >
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup.enter="
                                                listarlinea(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('lineas', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidolinea">
                                    <template slot="thead">
                                        <vs-th>Cod.</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Cuenta</vs-th>
                                        <vs-th>Cuenta Descuento</vs-th>
                                        <vs-th>Cuenta Anticipo</vs-th>
                                        <vs-th>Cuenta Servicio</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_grupo_cliente"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.codigo">{{
                                                datos.codigo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.nombre_grupo">{{
                                                datos.nombre_grupo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.plan_cuentas">{{
                                                datos.plan_cuentas
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                v-if="
                                                    datos.plan_cuentas_descuento
                                                "
                                                >{{
                                                    datos.plan_cuentas_descuento
                                                }}</vs-td
                                            >
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                v-if="
                                                    datos.plan_cuentas_anticipo
                                                "
                                                >{{
                                                    datos.plan_cuentas_anticipo
                                                }}</vs-td
                                            >
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                v-if="
                                                    datos.plan_cuentas_servicio
                                                "
                                                >{{
                                                    datos.plan_cuentas_servicio
                                                }}</vs-td
                                            >
                                            <vs-td v-else>-</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'lineas',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_grupo_cliente;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!-- ====================================Modal principal  tipo cliente====================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 2"
                >
                    <vx-card>
                        <div class="vx-row">
                            <div
                                class="vx-col md:w-full w-full mb-6"
                                id="ag-grid-demo"
                            >
                                <div
                                    class="flex flex-wrap justify-between items-center mb-3"
                                >
                                    <!-- ITEMS PER PAGE -->
                                    <div
                                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                    ></div>
                                    <div
                                        class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                                    >
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar2"
                                            @keyup="listartipo(1, buscar2)"
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('tipos', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidotipo">
                                    <template slot="thead">
                                        <vs-th>Descripción Cliente</vs-th>

                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_tipo_cliente"
                                            v-for="datos in data"
                                        >
                                            <vs-td
                                                v-if="
                                                    datos.descripcion_tipo_cliente
                                                "
                                                >{{
                                                    datos.descripcion_tipo_cliente
                                                }}</vs-td
                                            >
                                            <vs-td v-else>-</vs-td>

                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'tipos',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />

                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_tipo_cliente;
                                                        eliminar = true;
                                                        tipoaccionmodal = 2;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!-- ================================ ====================Fin============================================== -->
            </div>
            <!-- ==================================== Modal Agregar grupo cliente ======================================== -->
            <vs-popup :title="titulo" :active.sync="agregarlinea">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="nombre_grupo"
                            />
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <label class="vs-input--label"
                                >Cuenta Contable</label
                            >
                            <vx-input-group class="mb-base">
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cuenta"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta('Cuenta Contable')
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col w-1/2 mb-6">
                            <label class="vs-input--label"
                                >Cuenta Descuento</label
                            >
                            <vx-input-group class="mb-base">
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cuenta_descuento"
                                    @keypress="solonumeros($event)"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta('Cuenta Descuento')
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col w-1/2 mb-6">
                            <label class="vs-input--label"
                                >Cuenta Anticipo</label
                            >
                            <vx-input-group class="mb-base">
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cuenta_anticipo"
                                    @keypress="solonumeros($event)"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta('Cuenta Anticipo')
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col w-1/2 mb-6">
                            <label class="vs-input--label"
                                >Cuenta Servicio</label
                            >
                            <vx-input-group class="mb-base">
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cuenta_servicio"
                                    @keypress="solonumeros($event)"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta('Cuenta Servicio')
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarlinea()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editargrupo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarlinea = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
                <!-- ========================================Popup cuenta contable ===================================== -->
                <vs-popup
                    :title="titulomodalcuenta"
                    :active.sync="popupActive"
                    class="peque"
                >
                    <div class="con-exemple-prompt">
                        <vs-input
                            class="mb-4 md:mb-0 mr-4 w-full"
                            v-model="buscar"
                            @keyup="listarcuenta(buscar)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <vs-table
                            stripe
                            @selected="selectcta"
                            :data="contenidocuenta"
                        >
                            <template slot="thead">
                                <vs-th>No.Cuenta</vs-th>
                                <vs-th>Tipo Cuenta</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :data="tr"
                                    :key="indextr"
                                    v-for="(tr, indextr) in data"
                                >
                                    <vs-td :data="data[indextr].codcta">{{
                                        data[indextr].codcta
                                    }}</vs-td>

                                    <vs-td :data="data[indextr].nomcta">{{
                                        data[indextr].nomcta
                                    }}</vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </vs-popup>
                <!-- ========================================== Fin popup cuenta contable ================================== -->
            </vs-popup>
            <!-- =================================== Modal Agregar tipo cliente ========================================== -->
            <vs-popup :title="titulo" :active.sync="agregartipo">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripción de Tipo Cliente:"
                                v-model="descripcion_tipo_cliente"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                v-if="tipoaccionmodal == 1"
                                @click="guardartipo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                v-else
                                @click="editartipo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="agregartipo = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <!-- ================================== Modal de eliminar =================================================== -->
            <vs-popup
                title="eliminar registro"
                class="peque"
                :active.sync="eliminar"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div
                                class="vx-col sm:w-full w-full mb-6 text-center"
                            >
                                <label class="text-center">
                                    Esta seguro que desea eliminar este registro
                                    <br />
                                </label>
                            </div>
                            <div class="vx-col sm:w-full w-full text-center">
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="eliminargrupo(ideliminar)"
                                    >Eliminar</vs-button
                                >

                                <vs-button
                                    v-else-if="tipoaccionmodal == 2"
                                    color="danger"
                                    type="filled"
                                    @click="eliminartipo(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    color="primary"
                                    type="filled"
                                    @click="eliminar = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
        </vs-popup>
        <!-- =============================================== Modal para exportar excel ============================================= -->
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
        <!-- ============================================== ===============fin ================================================== -->
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
                                <div style="display: none;">
                                    <input
                                        id="input-upload"
                                        type="file"
                                        class="custom-file-input inputexcel"
                                        @change="subirArchivo($event)"
                                        accept=".XLSX, .CSV"
                                    />
                                </div>
                                <div
                                    class="centimg vx-card input"
                                    @click="importarexcel()"
                                >
                                    <img src="/images/upload.png" />
                                    <div
                                        v-if="file.length === 0"
                                        style="position:absolute;margin-top:60px;color:#000"
                                    >
                                        Click para subir Archivo
                                    </div>
                                    <div
                                        v-else
                                        style="position:absolute;margin-top:60px;color:#000"
                                    >
                                        {{ file[0].name }}
                                    </div>
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
        <!-- ============================================= =================fin ================================================== -->
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import vSelect from "vue-select";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        ImportExcel,
        vSelect
    },
    data() {
        return {
            //cuenta contable listar
            titulomodalcuenta: "",
            contenidocuenta: [],
            popupActive: false,
            codcta: [],
            //variables modal
            titulomodal: "",
            modal: false,
            tipoaccion: 0,
            tipoaccionmodal: 0,
            titulo: "",
            id: null,
            agregarlinea: false,
            agregartipo: false,
            agregarvendedor: false,
            eliminar: false,
            ideliminar: 0,
            tipoeliminar: null,
            //mapeo de datos
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination1: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination2: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination3: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina: 1,
            pagina1: 1,
            pagina2: 1,
            pagina3: 1,
            cantidadp1: 1000000,
            cantidadp2: 100000,
            cantidadp3: 100000,
            offset: 3,
            offset1: 3,
            offset2: 3,
            offset3: 3,
            buscar: "",
            buscar1: "",
            buscar11: "",
            buscar2: "",
            buscar3: "",
            criterio1: "codcta",
            criterio11: "codcta",
            //otros valores
            gridApi: null,
            contenido: [],
            contenidolinea: [],
            contenidotipo: [],
            contenidomarca: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            i18nbuscar1: this.$t("i18nbuscar"),
            nombre_grupo: "",
            cuenta: "",
            cuenta_id: "",
            cuenta_descuento: "",
            cuenta_descuento_id: "",
            cuenta_anticipo: "",
            cuenta_anticipo_id: "",
            cuenta_servicio: "",
            cuenta_servicio_id: "",
            descripcion_tipo_cliente: "",
            codigo_vendedor: "",
            nombre_vendedor: "",
            email_vendedor: "",
            //opciones linea de producto para tipo de producto
            optionlinea: [],
            //buscador
            criterio: "id",
            //otros valoreS
            gridApi: null,
            contenido: [],
            //ERRORES
            error: 0,
            erroremail: [],
            //Datos para la importaciond de archivos
            importar: false,
            nombreimportar: "",
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            timeout: null,
            //campos que existen para exportar
            campos: [
                "id_cliente",
                "codigo",
                "nombre",
                "nombre_adicional",
                "identificacion",
                "direccion",
                "email",
                "telefono",
                "contacto",
                "estado",
                "id_plan_cuentas",
                "comentario",
                "descuento",
                "num_pago",
                "tipo_identificacion",
                "id_codigo_pais",
                "grupo_tributario",
                "id_provincia",
                "id_cuidad",
                "id_parroquia",
                "parte_relacionada",
                "lista_precios",
                "limite_credito",
                "id_forma_pagos",
                "fcrea",
                "fmodifica",
                "umodifica",
                "ucrea",
                "id_grupo_cliente",
                "id_empresa",
                "id_tipo_cliente",
                "id_vendedor"
            ],
            //campos elegidos a exportar
            indexs: [
                "codigo",
                "nombre",
                "nombre_adicional",
                "tipo_identificacion",
                "identificacion",
                "grupo_tributario",
                "id_codigo_pais",
                "id_grupo_cliente",
                "id_tipo_cliente",
                "direccion",
                "id_provincia",
                "id_cuidad",
                "id_parroquia",
                "parte_relacionada",
                "email",
                "telefono",
                "contacto",
                "estado",
                "id_vendedor",
                "descuento",
                "id_plan_cuentas",
                "num_pago",
                "lista_precios",
                "id_forma_pagos",
                "limite_credito",
                "comentario",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "codigo",
                "nombre",
                "nombre_adicional",
                "tipo_identificacion",
                "identificacion",
                "grupo_tributario",
                "id_codigo_pais",
                "id_grupo_cliente",
                "id_tipo_cliente",
                "direccion",
                "id_provincia",
                "id_cuidad",
                "id_parroquia",
                "parte_relacionada",
                "email",
                "telefono",
                "contacto",
                "estado",
                "id_vendedor",
                "descuento",
                "id_plan_cuentas",
                "num_pago",
                "lista_precios",
                "id_forma_pagos",
                "limite_credito",
                "comentario",
                "id_empresa"
            ]
        };
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
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if (el.nombre == "Clientes") {
                        res = el.crear;
                        return res;
                    }
                });
            }
            console.log(res + "Rol");
            return res;
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if (el.nombre == "Clientes") {
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].eliminar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if (el.nombre == "Clientes") {
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        }
    },
    methods: {
        /*
         *importar archivos
         *
         */
        //preload
        readyFile($e) {
            // Este muestra una lista FileList
            console.log($e.target.files);
            // Este muestra el valor actual del input
            console.log($e.target.value);
        },

        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/importarexcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";

                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar = false;
                    this.listar(this.buscar);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado 'Sin Exito'",
                        color: "danger"
                    });
                    this.importar = false;
                    this.listar(this.buscar);
                    this.file = [];
                });
        },
        subirArchivo(e) {
            this.file = [];
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
        /**
         * Cancelar un registro
         */
        cancelar() {
            this.$router.push("/facturacion/clientes");
        },
        cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },
        /**
         * Abren modales
         */
        abrirlinea() {
            this.tipoaccion = 1;
            this.modal = true;
            this.titulomodal = "Grupo Cliente";
        },
        abrirtipo() {
            this.tipoaccion = 2;
            this.modal = true;
            this.titulomodal = "Tipos de Clientes";
        },
        /***
         * Lista los datos de  grupo clientes en una tabla
         ***/
        listarlinea(page1, buscar1) {
            var url =
                "/api/grupocliente/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidolinea = respuesta.recupera;
            });
        },
        /**
         * Lista los datos de clientes en una tabla
         **/
        listar(buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/seguimiento/clientes/" +
                    this.usuario.id_empresa +
                    "?buscar=" +
                    buscar;
                axios.get(url).then(res => {
                    this.contenido = res.data.recupera;
                });
            }, 800);
        },
        /**
         *Lista los datos de tipo de clientes en una tabla
         **/
        listartipo(page2, buscar2) {
            var url =
                "/api/listartipocliente/" +
                this.usuario.id_empresa +
                "?page=" +
                page2 +
                "&buscar=" +
                buscar2;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidotipo = respuesta.recupera;
            });
        },
        /*
         * Guardar los datos del formulario grupo cliente
         */
        guardarlinea() {
            axios
                .post("/api/guardargrupo", {
                    nombre_grupo: this.nombre_grupo,
                    cuenta: this.cuenta_id,
                    cuenta_descuento: this.cuenta_descuento_id,
                    cuenta_anticipo: this.cuenta_anticipo_id,
                    cuenta_servicio: this.cuenta_servicio_id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarlinea = false;
                    this.listarlinea(1, this.buscar1);
                    this.todaslinea();
                })
                .catch(err => {});
        },
        /*
         * Guardar los datos del formulario tipo cliente
         */
        guardartipo() {
            axios
                .post("/api/guardartipocliente", {
                    descripcion_tipo_cliente: this.descripcion_tipo_cliente,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregartipo = false;
                    this.listartipo(1, this.buscar2);
                })
                .catch(err => {});
        },
        /*
         * edita los datos del formulario grupo cliente
         */
        editargrupo() {
            axios
                .post("/api/editargrupo", {
                    id: this.id,
                    nombre_grupo: this.nombre_grupo,
                    cuenta: this.cuenta_id,
                    cuenta_descuento: this.cuenta_descuento_id,
                    cuenta_anticipo: this.cuenta_anticipo_id,
                    cuenta_servicio: this.cuenta_servicio_id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarlinea = false;
                    this.listarlinea(1, this.buscar1);
                });
        },
        /*
         * edita los datos del formulario tipo cliente
         */
        editartipo() {
            axios
                .post("/api/editartipocliente", {
                    id: this.id,
                    descripcion_tipo_cliente: this.descripcion_tipo_cliente,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregartipo = false;
                    this.listartipo(1, this.buscar2);
                });
        },
        /*
         * elimina un registro grupo cliente
         */

        eliminargrupo(id) {
            axios.delete("/api/eliminargrupo/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarlinea(1, this.buscar1);
        },
        /*
         * elimina un registro tipo cliente
         */
        eliminartipo(id) {
            axios.delete("/api/eliminartipocliente/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listartipo(1, this.buscar2);
        },
        todaslinea() {
            var url = "/api/grupoclienteall/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.optionlinea = res.data;
            });
        },
        /*
         * guardar,editar: grupo,tipo de cliente
         */
        agregar(tipo, accion, dato) {
            switch (tipo) {
                case "lineas": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarlinea = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Grupo Cliente";
                            this.nombre_grupo = "";
                            this.cuenta = "";
                            this.cuenta_id = "";
                            this.cuenta_descuento = "";
                            this.cuenta_descuento_id = "";
                            this.cuenta_anticipo = "";
                            this.cuenta_anticipo_id = "";
                            this.cuenta_servicio = "";
                            this.cuenta_servicio_id = "";
                            break;
                        }
                        case "editar": {
                            this.agregarlinea = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Grupo Cliente";
                            this.id = dato.id_grupo_cliente;
                            this.nombre_grupo = dato.nombre_grupo;
                            this.cuenta = dato.plan_cuentas;
                            this.cuenta_id = dato.id_plan_cuentas;
                            this.cuenta_descuento = dato.plan_cuentas_descuento;
                            this.cuenta_descuento_id =
                                dato.id_plan_cuentas_descuento;
                            this.cuenta_anticipo = dato.plan_cuentas_anticipo;
                            this.cuenta_anticipo_id =
                                dato.id_plan_cuentas_anticipo;
                            this.cuenta_servicio = dato.plan_cuentas_servicio;
                            this.cuenta_servicio_id =
                                dato.id_plan_cuentas_servicio;

                            break;
                        }
                    }
                    break;
                }
                case "tipos": {
                    switch (accion) {
                        case "guardar": {
                            this.agregartipo = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar tipo de cliente";

                            this.descripcion_tipo_cliente = "";

                            break;
                        }
                        case "editar": {
                            this.agregartipo = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar tipo de cliente";
                            this.id = dato.id_tipo_cliente;

                            this.descripcion_tipo_cliente =
                                dato.descripcion_tipo_cliente;

                            break;
                        }
                    }
                    break;
                }
                case "vendedores": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarvendedor = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar vendedor";
                            this.codigo_vendedor = "";
                            this.nombre_vendedor = "";
                            this.email_vendedor = "";
                            break;
                        }
                        case "editar": {
                            this.agregarvendedor = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar marca";
                            this.id = dato.id_vendedor;
                            this.codigo_vendedor = dato.codigo_vendedor;
                            this.nombre_vendedor = dato.nombre_vendedor;
                            this.email_vendedor = dato.email_vendedor;
                            break;
                        }
                    }
                    break;
                }
            }
        },
        //lista cotenido de cuenta contable
        listarcuenta(buscar) {
            axios
                .get(
                    "/api/select_plan_cuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscar
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                });
        },
        modalcuenta(titulo) {
            switch (titulo) {
                case "Cuenta Contable": {
                    this.popupActive = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
                case "Cuenta Descuento": {
                    this.popupActive = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
                case "Cuenta Anticipo": {
                    this.popupActive = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
                case "Cuenta Servicio": {
                    this.popupActive = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
            }
        },
        selectcta(tr) {
            if (tr.id_grupo == 2) {
                this.popupActive = false;
                switch (this.titulomodalcuenta) {
                    case "Cuenta Contable": {
                        this.cuenta_id = `${tr.id_plan_cuentas}`;
                        this.cuenta = `${tr.nomcta}`;
                        break;
                    }
                    case "Cuenta Descuento": {
                        this.cuenta_descuento_id = `${tr.id_plan_cuentas}`;
                        this.cuenta_descuento = `${tr.nomcta}`;
                        break;
                    }
                    case "Cuenta Anticipo": {
                        this.cuenta_anticipo_id = `${tr.id_plan_cuentas}`;
                        this.cuenta_anticipo = `${tr.nomcta}`;
                        break;
                    }
                    case "Cuenta Servicio": {
                        this.cuenta_servicio_id = `${tr.id_plan_cuentas}`;
                        this.cuenta_servicio = `${tr.nomcta}`;
                        break;
                    }
                }
                this.buscar = "";
                this.listarcuenta(this.buscar);
            } else {
                this.$vs.notify({
                    title: "Error",
                    text: "La Cuenta seleccionada no es válida",
                    color: "danger"
                });
            }
        },
        /*
         * Limpiar los campos de formulario
         */
        limpiar() {
            this.nombre_grupo = "";
            this.cuenta = "";
            this.cuenta_id = "";
            this.cuenta_descuento = "";
            this.cuenta_descuento_id = "";
            this.cuenta_anticipo = "";
            this.cuenta_anticipo_id = "";
            this.cuenta_servicio = "";
            this.cuenta_servicio_id = "";
            this.descripcion_tipo_cliente = "";
            this.codigo_vendedor = "";
            this.nombre_vendedor = "";
            this.email_vendedor = "";
        },
        /*
         * valida los datos del formulario
         */
        validar() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            this.erroremail = [];

            if (!this.validaremail(this.email_vendedor)) {
                this.erroremail.push("Email no valido");
                this.error = 1;
            }
            return this.error;
        },
        /*
         * valida email
         */
        validaremail(email_vendedor) {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email_vendedor);
        },
        /*
         * exportar archivo
         */
        exportardatos() {
            import("../../../vendor/Export2ClientesExcel").then(excel => {
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
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val);
        },
        /*
         * envia a formulario para crear
         */
        crear() {
            this.$router.push("/seguimiento/cliente/agregar");
        },
        /*
         * envia a formulario para editar
         */
        editar(id) {
            this.$router.push(`/facturacion/cliente/${id}/editar`);
        },
        /*
         * elimina registro de los cliente
         */
        eliminarCliente(id) {
            //metodo eliminar
            // axios
            //     //Envia id
            //     .delete("/api/eliminarCliente/" + id);
            // this.$vs.dialog({
            //     type: "confirm",
            //     color: "danger",
            //     title: `Confirmar`,
            //     text: "¿Desea Elimnar este registro?",
            //     accept: this.acceptAlert
            // });
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
        /**
         * validación de solo números permite solo el
         * ingreso de numeros en los formularios de registro
         */
        solonumeros: function($event) {
            //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
            var num = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
        /**
         * mensaje de alerta
         * recibe id por cd
         * de la function eliminar
         */
        acceptAlert(id) {
            axios
            .delete("/api/eliminarCliente/" + id)
            .then(resp=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Cliente Eliminado  ",
                    text: "El cliente selecionado fue eliminado con exito"
                });
                this.listar(this.buscar);
            })
            .catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "No se pudo eliminar el cliente",
                });
                //this.listar(this.buscar);
            });
            
        }
    },
    mounted() {
        this.listar(this.buscar);
        this.listarlinea(1, this.buscar1);
        this.todaslinea();
        this.listartipo(1, this.buscar2);
        this.listarcuenta(this.buscar);
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
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
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
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
</style>
