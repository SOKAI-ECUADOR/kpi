<template>
    <vx-card class="mt-10">
        <div class="flex flex-wrap justify-between items-center mb-3">
            <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
            <div
                class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
            >
                <vs-input
                    class="mb-4 md:mb-0 mr-4"
                    v-model="buscar"
                    @keyup="listarbodega(buscar)"
                    v-bind:placeholder="i18nbuscar"
                />
                <div class="dropdown-button-container mr-3">
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
                                @click="abrirCtaIngreso()"
                                >Cuenta Ingreso Bodega</vs-dropdown-item
                            >
                            <vs-dropdown-item
                                class="text-center"
                                divider
                                @click="abrirCtaEgreso()"
                                >Cuenta Egreso Bodega</vs-dropdown-item
                            >
                            <vs-dropdown-item
                                class="text-center"
                                divider
                                @click="abrirCtaTransf()"
                                >Cuenta Transaccion Bodega</vs-dropdown-item
                            >
                        </vs-dropdown-menu>
                    </vs-dropdown>
                </div>
                <div class="dropdown-button-container" v-if="crearrol">
                    <vs-button
                        class="btnx"
                        type="filled"
                        @click="abrir('agregar'), listcodbodega()"
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
                                divider
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
                <vs-th>Código</vs-th>
                <vs-th>Nombre</vs-th>
                <vs-th>Responsable de Bodega</vs-th>
                <vs-th>Ubicación</vs-th>
                <vs-th>Dirección</vs-th>
                <vs-th>Telefono</vs-th>
                <vs-th>Opciones</vs-th>
            </template>
            <template slot-scope="{ data }">
                <vs-tr :key="datos.codigo" v-for="datos in data">
                    <vs-td v-if="datos.codigo">
                        {{ datos.codigo }}
                    </vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.responsable">{{
                        datos.responsable
                    }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.ubicacion">{{ datos.ubicacion }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.direccion">{{ datos.direccion }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td v-if="datos.telefono">{{ datos.telefono }}</vs-td>
                    <vs-td v-else>-</vs-td>
                    <vs-td class="whitespace-no-wrap">
                        <vx-tooltip
                            text="Editar Bodega"
                            position="top"
                            style="display: inline-flex;"
                        >
                            <feather-icon
                                v-if="editarrol"
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="pointer"
                                @click.stop="abrir('editar', datos)"
                            />
                        </vx-tooltip>
                        <vx-tooltip
                            text="Borrar Bodega"
                            position="top"
                            style="display: inline-flex;"
                        >
                            <feather-icon
                                v-if="eliminarrol && datos.exist_ingresos==0"
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 pointer"
                                @click.stop="
                                    (ideliminar = datos.id_bodega),
                                        (eliminar = true)
                                "
                            />
                        </vx-tooltip>
                        <vx-tooltip
                            text="Gestionar Bodega"
                            position="top"
                            style="display: inline-flex;"
                        >
                            <feather-icon
                                icon="PackageIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 pointer"
                                @click="gestionbodega(datos.id_bodega)"
                            />
                        </vx-tooltip>
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table>
        <!--Popup Agregar Bodega-->
        <vs-popup title="Agregar Bodega" :active.sync="popupActive">
            <div class="vx-col sm:w-full w-full mb-6">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            disabled
                            class="w-full txt-center"
                            label="Código:"
                            v-model="codigo"
                        />
                        <div v-show="error" v-if="!codigo">
                            <div
                                v-for="err in errorcodigo"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-2/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Nombre:"
                            v-model="nombre"
                        />
                        <div v-show="error" v-if="!nombre">
                            <div
                                v-for="err in errornombre"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Responsable de Bodega:"
                            v-model="responsable"
                        />
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Dirección:"
                            v-model="direccion"
                        />
                        <div v-show="error" v-if="!direccion">
                            <div
                                v-for="err in errordireccion"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Ubicación:"
                            v-model="ubicacion"
                        />
                        <div v-show="error" v-if="!ubicacion">
                            <div
                                v-for="err in errorubicacion"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>

                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Teléfono:"
                            v-model="telefono"
                        />
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Cuenta Contable</label>
                        <vx-input-group>
                            <vs-input
                                disabled
                                class="w-full"
                                v-model="cuenta_contable"
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="
                                            popupcuentacontable = true;
                                            listarcuenta(buscarccontable);
                                        "
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                    </div>
                    <div
                        class="vx-col sm:w-full w-full mb-6"
                        style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                    >
                        <vs-checkbox
                            icon-pack="feather"
                            icon="icon-check"
                            v-model="visible"
                        >
                            <template v-if="visible">
                                <label
                                    class="vs-input--label"
                                    style="font-size: 14px;font-weight: bold;"
                                    >Si</label
                                >
                            </template>
                            <template v-else>
                                <label
                                    class="vs-input--label"
                                    style="font-size: 14px;font-weight: bold;"
                                    >No</label
                                >
                            </template>
                            | Ocultar Inventario
                        </vs-checkbox>
                    </div>
                    <div class="vx-row sm:w-full w-full">
                        <div
                            class="vx-col w-1/3 ml-auto mt-6 text-center"
                            v-if="id_bodega == null"
                        >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="guardarbodega()"
                                >Guardar</vs-button
                            >
                        </div>
                        <div
                            class="vx-col w-1/3 ml-auto mt-6 text-center"
                            v-else
                        >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="editarbodega()"
                                >Guardar</vs-button
                            >
                        </div>
                        <div class="vx-col w-1/3 mr-auto mt-6 text-center">
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="(popupActive = false), cancelar()"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-- Popup cuenta contable -->
            <vs-popup
                title="Plan de Cuentas"
                class="peque"
                :active.sync="popupcuentacontable"
            >
                <div class="con-exemple-prompt">
                    <vs-input
                        class="mb-4 md:mb-0 mr-4 w-full"
                        v-model="buscarccontable"
                        @keyup="listarcuenta(buscarccontable)"
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
        </vs-popup>

        <!-- Eliminar popup-->
        <vs-popup
            title="eliminar registro"
            :class="'peque'"
            :active.sync="eliminar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6 text-center">
                            <label class="text-center">
                                Esta seguro que desea eliminar este registro
                                <br />¡Se eliminará de forma permanente!
                            </label>
                        </div>
                        <div class="vx-col sm:w-full w-full text-center">
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="eliminarbodega(ideliminar)"
                                >Eliminar bodega</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!--Modal para exportar excel-->
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
        <!--Modal para importar excel-->
        <vs-popup
            :class="'peque2'"
            title="Importar Excel"
            :active.sync="importar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <!---->
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label class="vs-input--label">Subir Archivo</label>
                            <div class="vx-col md:w-full w-full mb-6">
                                <div style="display:none">
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
        <!--fin modal de importar-->
        <!--inicio modal de cta ingreso-->
        <vs-popup
            :class="'peque2a'"
            title="Cuenta Ingreso Bodega"
            :active.sync="modal_cta_ingreso"
        >
            <vx-card>
                <div class="vx-row">
                    <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
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
                                    v-model="buscar2_cta_ingreso"
                                    @keyup="
                                        listarCtas_Ingreso(
                                            1,
                                            buscar2_cta_ingreso
                                        )
                                    "
                                    v-bind:placeholder="i18nbuscar2_cta_ingreso"
                                />
                                <div>
                                    <vs-button
                                        class="btnx"
                                        type="filled"
                                        divider
                                        @click="agregarCta_Ingreso()"
                                        >Agregar Nuevo</vs-button
                                    >
                                </div>
                            </div>
                        </div>
                        <vs-table stripe :data="ctas_ingreso">
                            <template slot="thead">
                                <vs-th>Código</vs-th>
                                <vs-th>Nombre</vs-th>
                                <vs-th>Bodega</vs-th>
                                <vs-th>Opciones</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :key="datos.id_cuenta_ingreso_bodega"
                                    v-for="datos in data"
                                >
                                    <vs-td v-if="datos.cod_cuenta">{{
                                        datos.cod_cuenta
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.nombre_cuenta">{{
                                        datos.nombre_cuenta
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.nombre_bodega">{{
                                        datos.nombre_bodega
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td class="whitespace-no-wrap">
                                        <feather-icon
                                            icon="EditIcon"
                                            class="cursor-pointer"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            @click.stop="
                                                verCta_Ingreso(
                                                    datos.id_cuenta_ingreso_bodega
                                                )
                                            "
                                        />
                                        <feather-icon
                                            icon="TrashIcon"
                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                            class="ml-2 cursor-pointer"
                                            @click.stop="
                                                eliminarCta_Ingreso(
                                                    datos.id_cuenta_ingreso_bodega
                                                )
                                            "
                                        />
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </div>
                <vs-popup
                    :class="'peque3a'"
                    title="Eliminar Cuenta"
                    :active.sync="modal_eliminar_cta_ingreso"
                >
                    <p>Desea eliminar Este reguistro</p>
                    <div class="vx-col w-full">
                        <br />
                        <vs-button
                            color="warning"
                            type="filled"
                            @click="
                                acceptAlertCta_Ingreso(idrecupera_cta_ingreso)
                            "
                            >BORRAR</vs-button
                        >
                    </div>
                </vs-popup>
                <vs-popup
                    :class="'peque2a'"
                    title="Agregar Cuenta"
                    :active.sync="modal_agregar_cta_ingreso"
                >
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label for="" class="vs-input--label"
                                    >Cuenta contable:</label
                                >
                                <vx-input-group>
                                    <vs-input
                                        class="w-full"
                                        v-model="cod_cta_contable_cta_ingreso"
                                        :value="id_cta_contable_cta_ingreso"
                                        disabled
                                    />
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                            <vs-button
                                                color="primary"
                                                @click="
                                                    popupActive_cta_ingreso = true
                                                "
                                                >Buscar</vs-button
                                            >
                                        </div>
                                    </template>
                                </vx-input-group>
                            </div>
                            
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                    <label for="" class="vs-input--label"
                                            >Nombre Cuenta:</label
                                        >
                                        <vs-input
                                            class="w-full"
                                            v-model="cta_contable_cta_ingreso"
                                            :value="id_cta_contable_cta_ingreso"
                                            disabled
                                        />
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                
                                <label for="" class="vs-input--label"
                                    >Bodega:</label
                                >
                                <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="id_bodega_ingreso">
                                    <vs-select-item :key="index" :value="item.id_bodega" :text="item.nombre" v-for="(item, index) in bodega_ingreso"/>
                                </vs-select>
                                
                            </div>
                        </div>
                        <br />
                        <div class="vx-col w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="guardarCta_Ingreso()"
                                v-if="!idrecupera_cta_ingreso"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="editarCta_Ingreso()"
                                v-else
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="warning"
                                type="filled"
                                @click="vaciarCta_Ingreso()"
                                >BORRAR</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="cancelarCta_Ingreso()"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <vs-popup
                        title="Plan Cuentas"
                        :active.sync="popupActive_cta_ingreso"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="listarpc_cta_ingreso"
                                @keyup="
                                    listar3(
                                        1,
                                        listarpc_cta_ingreso,
                                        criterio3_cta_ingreso,
                                        cantidadp3_cta_ingreso
                                    )
                                "
                                v-bind:placeholder="i18nbuscar3_cta_ingreso"
                            />
                            <vs-table
                                stripe
                                v-model="cuentaarray3_cta_ingreso"
                                @selected="handleSelected3_Ingreso"
                                :data="contenido3"
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
                </vs-popup>
            </vx-card>
        </vs-popup>
        <vs-popup
            :class="'peque2a'"
            title="Cuenta Egreso Bodega"
            :active.sync="modal_cta_egreso"
        >
            <vx-card>
                <div class="vx-row">
                    <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
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
                                    v-model="buscar2_cta_egreso"
                                    @keyup="
                                        listarCtas_Egreso(1, buscar2_cta_egreso)
                                    "
                                    v-bind:placeholder="i18nbuscar2_cta_egreso"
                                />
                                <div>
                                    <vs-button
                                        class="btnx"
                                        type="filled"
                                        divider
                                        @click="agregarCta_Egreso()"
                                        >Agregar Nuevo</vs-button
                                    >
                                </div>
                            </div>
                        </div>
                        <vs-table stripe :data="ctas_egreso">
                            <template slot="thead">
                                <vs-th>Código</vs-th>
                                <vs-th>Nombre</vs-th>
                                <vs-th>Bodega</vs-th>
                                <vs-th>Opciones</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :key="datos.id_cuenta_egreso_bodega"
                                    v-for="datos in data"
                                >
                                    <vs-td v-if="datos.cod_cuenta">{{
                                        datos.cod_cuenta
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.nombre_cuenta">{{
                                        datos.nombre_cuenta
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.nombre_bodega">{{
                                        datos.nombre_bodega
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td class="whitespace-no-wrap">
                                        <feather-icon
                                            icon="EditIcon"
                                            class="cursor-pointer"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            @click.stop="
                                                verCta_Egreso(
                                                    datos.id_cuenta_egreso_bodega
                                                )
                                            "
                                        />
                                        <feather-icon
                                            icon="TrashIcon"
                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                            class="ml-2 cursor-pointer"
                                            @click.stop="
                                                eliminarCta_Egreso(
                                                    datos.id_cuenta_egreso_bodega
                                                )
                                            "
                                        />
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </div>
                <vs-popup
                    :class="'peque3a'"
                    title="Eliminar Cuenta"
                    :active.sync="modal_eliminar_cta_egreso"
                >
                    <p>Desea eliminar Este reguistro</p>
                    <div class="vx-col w-full">
                        <br />
                        <vs-button
                            color="warning"
                            type="filled"
                            @click="
                                acceptAlertCta_Egreso(idrecupera_cta_egreso)
                            "
                            >BORRAR</vs-button
                        >
                    </div>
                </vs-popup>
                <vs-popup
                    :class="'peque2a'"
                    title="Agregar Cuenta"
                    :active.sync="modal_agregar_cta_egreso"
                >
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label for="" class="vs-input--label"
                                    >Cuenta contable:</label
                                >
                                <vx-input-group>
                                    <vs-input
                                        class="w-full"
                                        v-model="cod_cta_contable_cta_egreso"
                                        :value="id_cta_contable_cta_egreso"
                                        disabled
                                    />
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                            <vs-button
                                                color="primary"
                                                @click="
                                                    popupActive_cta_egreso = true
                                                "
                                                >Buscar</vs-button
                                            >
                                        </div>
                                    </template>
                                </vx-input-group>
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label for="" class="vs-input--label"
                                    >Nombre Cuenta:</label
                                >
                                <vs-input
                                    class="w-full"
                                    v-model="cta_contable_cta_egreso"
                                    :value="id_cta_contable_cta_egreso"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                
                                <label for="" class="vs-input--label"
                                    >Bodega:</label
                                >
                                <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="id_bodega_egreso">
                                    <vs-select-item :key="index" :value="item.id_bodega" :text="item.nombre" v-for="(item, index) in bodega_egreso"/>
                                </vs-select>
                                
                            </div>
                        </div>
                        <br />
                        <div class="vx-col w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="guardarCta_Egreso()"
                                v-if="!idrecupera_cta_egreso"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="editarCta_Egreso()"
                                v-else
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="warning"
                                type="filled"
                                @click="vaciarCta_Egreso()"
                                >BORRAR</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="cancelarCta_Egreso()"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <vs-popup
                        title="Plan Cuentas"
                        :active.sync="popupActive_cta_egreso"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="listarpc_cta_egreso"
                                @keyup="
                                    listar3(
                                        1,
                                        listarpc_cta_egreso,
                                        criterio3_cta_egreso,
                                        cantidadp3_cta_egreso
                                    )
                                "
                                v-bind:placeholder="i18nbuscar3_cta_egreso"
                            />
                            <vs-table
                                stripe
                                v-model="cuentaarray3_cta_egreso"
                                @selected="handleSelected3_Egreso"
                                :data="contenido3"
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
                </vs-popup>
            </vx-card>
        </vs-popup>
        <vs-popup
            :class="'peque2a'"
            title="Cuenta Transaccion Bodega"
            :active.sync="modal_cta_transf"
        >
            <vx-card>
                <div class="vx-row">
                    <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
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
                                    v-model="buscar2_cta_transf"
                                    @keyup="
                                        listarCtas_Transf(1, buscar2_cta_transf)
                                    "
                                    v-bind:placeholder="i18nbuscar2_cta_transf"
                                />
                                <div>
                                    <vs-button
                                        class="btnx"
                                        type="filled"
                                        divider
                                        @click="agregarCta_Transf()"
                                        >Agregar Nuevo</vs-button
                                    >
                                </div>
                            </div>
                        </div>
                        <vs-table stripe :data="ctas_transf">
                            <template slot="thead">
                                <vs-th>Código</vs-th>
                                <vs-th>Nombre</vs-th>
                                <vs-th>Bodega</vs-th>
                                <vs-th>Opciones</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :key="datos.id_cuenta_transf_bodega"
                                    v-for="datos in data"
                                >
                                    <vs-td v-if="datos.cod_cuenta">{{
                                        datos.cod_cuenta
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.nombre_cuenta">{{
                                        datos.nombre_cuenta
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td v-if="datos.nombre_bodega">{{
                                        datos.nombre_bodega
                                    }}</vs-td>
                                    <vs-td v-else>-</vs-td>
                                    <vs-td class="whitespace-no-wrap">
                                        <feather-icon
                                            icon="EditIcon"
                                            class="cursor-pointer"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            @click.stop="
                                                verCta_Transf(
                                                    datos.id_cuenta_transf_bodega
                                                )
                                            "
                                        />
                                        <feather-icon
                                            icon="TrashIcon"
                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                            class="ml-2 cursor-pointer"
                                            @click.stop="
                                                eliminarCta_Transf(
                                                    datos.id_cuenta_transf_bodega
                                                )
                                            "
                                        />
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </div>
                <vs-popup
                    :class="'peque3a'"
                    title="Eliminar Cuenta"
                    :active.sync="modal_eliminar_cta_transf"
                >
                    <p>Desea eliminar Este reguistro</p>
                    <div class="vx-col w-full">
                        <br />
                        <vs-button
                            color="warning"
                            type="filled"
                            @click="
                                acceptAlertCta_Transf(idrecupera_cta_transf)
                            "
                            >BORRAR</vs-button
                        >
                    </div>
                </vs-popup>
                <vs-popup
                    :class="'peque2a'"
                    title="Agregar Cuenta"
                    :active.sync="modal_agregar_cta_transf"
                >
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label for="" class="vs-input--label"
                                    >Cuenta contable:</label
                                >
                                <vx-input-group>
                                    <vs-input
                                        class="w-full"
                                        v-model="cod_cta_contable_cta_transf"
                                        :value="id_cta_contable_cta_transf"
                                        disabled
                                    />
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                            <vs-button
                                                color="primary"
                                                @click="
                                                    popupActive_cta_transf = true
                                                "
                                                >Buscar</vs-button
                                            >
                                        </div>
                                    </template>
                                </vx-input-group>
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label for="" class="vs-input--label"
                                    >Nombre Cuenta:</label
                                >
                                <vs-input
                                    class="w-full"
                                    v-model="cta_contable_cta_transf"
                                    :value="id_cta_contable_cta_transf"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                
                                <label for="" class="vs-input--label"
                                    >Bodega:</label
                                >
                                <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="id_bodega_transf">
                                    <vs-select-item :key="index" :value="item.id_bodega" :text="item.nombre" v-for="(item, index) in bodega_transf"/>
                                </vs-select>
                                
                            </div>
                        </div>
                        <br />
                        <div class="vx-col w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="guardarCta_Transf()"
                                v-if="!idrecupera_cta_transf"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="editarCta_Transf()"
                                v-else
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="warning"
                                type="filled"
                                @click="vaciarCta_Transf()"
                                >BORRAR</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="cancelarCta_Transf()"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <vs-popup
                        title="Plan Cuentas"
                        :active.sync="popupActive_cta_transf"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="listarpc_cta_transf"
                                @keyup="
                                    listar3(
                                        1,
                                        listarpc_cta_transf,
                                        criterio3_cta_transf,
                                        cantidadp3_cta_transf
                                    )
                                "
                                v-bind:placeholder="i18nbuscar3_cta_transf"
                            />
                            <vs-table
                                stripe
                                v-model="cuentaarray3_cta_transf"
                                @selected="handleSelected3_Transf"
                                :data="contenido3"
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
                </vs-popup>
            </vx-card>
        </vs-popup>
    </vx-card>
</template>

<script>
import { AgGridVue } from "ag-grid-vue";
import $ from "jquery";
import ImportExcel from "@/components/excel/ImportExcel.vue";
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
            cantidadp: 100000,
            offset: 3,
            //buscador
            buscar: "",
            //buscador cuenta contable
            buscarccontable: "",
            //otros valores
            gridApi: null,
            contenido: [],
            contenidocuenta: [],
            eliminar: false,
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //popup agregar bodega
            popupActive: false,
            //popup gestion bodega
            popupBodega: false,
            //popup listar plan de cuentas
            popupcuentacontable: false,
            //variables bodega
            id_bodega: null,
            codigo: "",
            nombre: "",
            responsable: "",
            ubicacion: "",
            direccion: "",
            telefono: "",
            cuenta_contable: "",
            id_plan_cuentas: "",
            visible: false,
            //variables validacion bodegaç
            error: 0,
            errorcodigo: [],
            errornombre: [],
            errorubicacion: [],
            errordireccion: [],
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
            //Datos para la importaciond de archivos
            importar: false,
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            //campos que existen para exportar
            campos: [
                "id_bodega",
                "codigo",
                "nombre",
                "responsable",
                "ubicacion",
                "direccion",
                "telefono",
                "fcrea",
                "fmodifica",
                "ucrea",
                "umodifica",
                "id_establecimiento",
                "id_empresa"
            ],
            //campos elegidos a exportar
            indexs: [
                "codigo",
                "nombre",
                "responsable",
                "ubicacion",
                "direccion",
                "telefono",
                "id_establecimiento",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "codigo",
                "nombre",
                "responsable_de_bodega",
                "ubicacion",
                "direccion",
                "telefono",
                "id_establecimiento",
                "id_empresa"
            ],
            //campos cuenta_importacion
            modal_cta_ingreso: false,
            buscar2_cta_ingreso: "",
            ctas_ingreso: [],
            i18nbuscar2_cta_ingreso: this.$t("i18nbuscar"),
            i18nbuscar3_cta_ingreso: this.$t("i18nbuscar"),
            modal_agregar_cta_ingreso: false,
            popupActive_cta_ingreso: false,
            listarpc_cta_ingreso: "",
            cantidadp3_cta_ingreso: 50,
            criterio3_cta_ingreso: "codcta",
            contenido3: [],
            cuentaarray3_cta_ingreso: [],
            id_cta_contable_cta_ingreso: "",
            cta_contable_cta_ingreso: "",
            cod_cta_contable_cta_ingreso: "",
            idrecupera_cta_ingreso: null,
            errorctas_cta_ingreso: 0,
            errorcta_contable_cta_ingreso: [],
            modal_eliminar_cta_ingreso: false,
            modal_cta_egreso: false,
            buscar2_cta_egreso: "",
            ctas_egreso: [],
            i18nbuscar2_cta_egreso: this.$t("i18nbuscar"),
            i18nbuscar3_cta_egreso: this.$t("i18nbuscar"),
            modal_agregar_cta_egreso: false,
            popupActive_cta_egreso: false,
            listarpc_cta_egreso: "",
            cantidadp3_cta_egreso: 50,
            criterio3_cta_egreso: "codcta",
            id_bodega_ingreso:"",
            bodega_ingreso:[],
            id_bodega_egreso:"",
            bodega_egreso:[],
            id_bodega_transf:"",
            bodega_transf:[],
            cuentaarray3_cta_egreso: [],
            id_cta_contable_cta_egreso: "",
            cta_contable_cta_egreso: "",
            cod_cta_contable_cta_egreso: "",
            idrecupera_cta_egreso: null,
            errorctas_cta_egreso: 0,
            errorcta_contable_cta_egreso: [],
            modal_eliminar_cta_egreso: false,
            modal_cta_transf: false,
            buscar2_cta_transf: "",
            ctas_transf: [],
            i18nbuscar2_cta_transf: this.$t("i18nbuscar"),
            i18nbuscar3_cta_transf: this.$t("i18nbuscar"),
            modal_agregar_cta_transf: false,
            popupActive_cta_transf: false,
            listarpc_cta_transf: "",
            cantidadp3_cta_transf: 50,
            criterio3_cta_transf: "codcta",

            cuentaarray3_cta_transf: [],
            id_cta_contable_cta_transf: "",
            cta_contable_cta_transf: "",
            cod_cta_contable_cta_transf: "",
            idrecupera_cta_transf: null,
            errorctas_cta_transf: 0,
            errorcta_contable_cta_transf: [],
            modal_eliminar_cta_transf: false
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
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[16].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[16].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[16].eliminar;
            }
            return res;
        }
    },
    methods: {
        //importar archivos
        //preload
        readyFile($e) {
            // Este muestra una lista FileList
            console.log($e.target.files);
            // Este muestra el valor actual del input
            console.log($e.target.value);
        },
        cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },
        importarexcel() {
            $(".inputexcel").click();
        },
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/ImportarBodegaExcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar = false;
                    this.listarbodega(this.buscar);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo no pudo subir",
                        color: "danger"
                    });
                    this.importar = false;
                    this.listarbodega(this.buscar);
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
        //exportar archivos
        exportardatos() {
            import("../../vendor/Export2BodegaExcel").then(excel => {
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
        //Funcion Lista codigo de bodega autoincrementable
        listcodbodega() {
            var url = "/api/codbodega/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.codigo = res.data.codigo;
            });
        },
        listarbodega(buscar) {
            var url = `/api/bodega/${this.usuario.id_empresa}/`;
            if (this.usuario.id_rol != 1) {
                url += this.usuario.id_establecimiento;
            } else {
                url += `any`;
            }
            url += `?buscar=${buscar}`;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenido = respuesta.recupera;
                this.bodega_ingreso=this.contenido;
                this.bodega_egreso=this.contenido;
                this.bodega_transf=this.contenido;
            });
        },
        //lista cotenido de cuenta contable
        listarcuenta(buscarccontable) {
            axios
                .get(
                    "/api/select_plan_cuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscarccontable
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                });
        },
        selectcta(tr) {
            if (tr.id_grupo == 2) {
                this.id_plan_cuentas = `${tr.id_plan_cuentas}`;
                this.cuenta_contable = `${tr.nomcta}`;
                this.popupcuentacontable = false;
            } else {
                this.$vs.notify({
                    title: "Error",
                    text: "La Cuenta seleccionada no es válida",
                    color: "danger"
                });
            }
        },
        guardarbodega() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/guardarbodega", {
                    codigo: this.codigo,
                    nombre: this.nombre,
                    responsable: this.responsable,
                    ubicacion: this.ubicacion,
                    direccion: this.direccion,
                    telefono: this.telefono,
                    id_plan_cuentas: this.id_plan_cuentas,
                    visible: this.visible,
                    id_empresa: this.usuario.id_empresa,
                    id_establecimiento: this.usuario.id_establecimiento
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    (this.popupActive = false), this.listarbodega(this.buscar);
                })
                .catch(err => {});
        },
        abrir(tipo, datos) {
            this.popupActive = true;
            switch (tipo) {
                case "agregar": {
                    this.id_bodega = null;
                    this.codigo = "";
                    this.nombre = "";
                    this.responsable = "";
                    this.ubicacion = "";
                    this.direccion = "";
                    this.telefono = "";
                    this.cuenta_contable = "";
                    this.id_plan_cuentas = "";
                    this.visible = false;
                    break;
                }
                case "editar": {
                    this.id_bodega = datos.id_bodega;
                    this.codigo = datos.codigo;
                    this.nombre = datos.nombre;
                    this.responsable = datos.responsable;
                    this.telefono = datos.telefono;
                    this.ubicacion = datos.ubicacion;
                    this.direccion = datos.direccion;
                    this.cuenta_contable = datos.nomcta;
                    this.id_plan_cuentas = datos.id_plan_cuentas;
                    this.visible = datos.visible;
                    break;
                }
            }
        },
        editarbodega() {
            if (this.validar()) {
                return;
            }
            axios
                .post("/api/editarbodega", {
                    id: this.id_bodega,
                    codigo: this.codigo,
                    nombre: this.nombre,
                    responsable: this.responsable,
                    ubicacion: this.ubicacion,
                    direccion: this.direccion,
                    telefono: this.telefono,
                    id_plan_cuentas: this.id_plan_cuentas,
                    visible: this.visible,
                    id_empresa: this.usuario.id_empresa,
                    id_establecimiento: this.usuario.id_establecimiento
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Edición Exitosa",
                        text: "Registro editado con éxito",
                        color: "success"
                    });
                    this.popupActive = false;
                    this.listarbodega(this.buscar);
                    this.id_bodega = null;
                })
                .catch(err => {});
        },
        eliminarbodega(id) {
            axios.delete("/api/eliminarbodega/" + id)
            .then(resp=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.eliminar = false;
                this.listarbodega(this.buscar);
            })
            .catch(err=>{
                this.$vs.notify({
                    title: "Registro No pudo ser eliminado",
                    text: "Este registro ya tiene registros",
                    color: "danger"
                });
                this.eliminar = false;
                this.listarbodega(this.buscar);
            });
            
        },
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val);
        },
        gestionbodega(id) {
            this.$router.push(`/inventario/bodega/${id}/gestionar`);
        },
        //validacion guardar bodega
        validar() {
            this.error = 0;
            this.errorcodigo = [];
            this.errornombre = [];
            this.errorubicacion = [];
            this.errordireccion = [];

            if (!this.codigo) {
                this.errorcodigo.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.nombre) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.ubicacion) {
                this.errorubicacion.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.direccion) {
                this.errordireccion.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            return this.error;
        },
        cancelar() {
            this.buscar = "";
            this.buscarccontable = "";
            this.codigo = "";
            this.nombre = "";
            this.responsable = "";
            this.ubicacion = "";
            this.direccion = "";
            this.telefono = "";
            this.cuenta_contable = "";
            this.id_plan_cuentas = "";
            this.visible = false;
            this.error = 0;
            this.errorcodigo = [];
            this.errornombre = [];
            this.errorubicacion = [];
            this.errordireccion = [];
        },
        //metodos cuenta_importacion
        abrirCtaIngreso() {
            this.modal_cta_ingreso = true;
            this.listarCtas_Ingreso(1, this.buscar2_cta_ingreso);
            this.listar3(
                1,
                this.listarpc_cta_ingreso,
                this.criterio3_cta_ingreso,
                this.cantidadp3_cta_ingreso
            );
            this.id_bodega_ingreso="";
        },
        abrirCtaEgreso() {
            this.modal_cta_egreso = true;
            this.listarCtas_Egreso(1, this.buscar2_cta_egreso);
            this.listar3(
                1,
                this.listarpc_cta_egreso,
                this.criterio3_cta_egreso,
                this.cantidadp3_cta_egreso
            );
            this.id_bodega_egreso="";
        },
        abrirCtaTransf() {
            this.modal_cta_transf = true;
            this.listarCtas_Transf(1, this.buscar2_cta_transf);
            this.listar3(
                1,
                this.listarpc_cta_transf,
                this.criterio3_cta_transf,
                this.cantidadp3_cta_transf
            );
            this.id_bodega_transf="";
        },
        listarCtas_Ingreso(page, buscar2) {
            let me = this;
            var url =
                "/api/cuenta_ingreso_bodega/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.ctas_ingreso = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarCtas_Egreso(page, buscar2) {
            let me = this;
            var url =
                "/api/cuenta_egreso_bodega/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.ctas_egreso = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarCtas_Transf(page, buscar2) {
            let me = this;
            var url =
                "/api/cuenta_transf_bodega/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.ctas_transf = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        agregarCta_Ingreso() {
            this.vaciarCta_Ingreso();
            this.modal_agregar_cta_ingreso = true;
        },
        agregarCta_Egreso() {
            this.vaciarCta_Egreso();
            this.modal_agregar_cta_egreso = true;
            
        },
        agregarCta_Transf() {
            this.vaciarCta_Transf();
            this.modal_agregar_cta_transf = true;
        },
        listar3(page3, buscar3, criterio3, cantidadp3) {
            let me = this;
            var url = "/api/notacredito/listar_cuenta_contable";
            // +
            // this.usuario.id_empresa +
            // "?page=" +
            // page3 +
            // "&buscar=" +
            // buscar3 +
            // "&criterio=" +
            // criterio3 +
            // "&cantidadp=" +
            // cantidadp3
            axios
                .get(url, {
                    params: {
                        empresa: this.usuario.id_empresa,
                        buscar: buscar3
                    }
                })
                .then(({ data }) => {
                    var respuesta = data;
                    me.contenido3 = respuesta;
                })
                .catch(function(error) {});
        },
        handleSelected3_Ingreso(tr) {
            (this.cta_contable_cta_ingreso = `${tr.nomcta}`),
                (this.id_cta_contable_cta_ingreso = `${tr.id_plan_cuentas}`),
                (this.cod_cta_contable_cta_ingreso = `${tr.codcta}`),
                (this.popupActive_cta_ingreso = false);
        },
        handleSelected3_Egreso(tr) {
            (this.cta_contable_cta_egreso = `${tr.nomcta}`),
                (this.id_cta_contable_cta_egreso = `${tr.id_plan_cuentas}`),
                (this.cod_cta_contable_cta_egreso = `${tr.codcta}`),
                (this.popupActive_cta_egreso = false);
        },
        handleSelected3_Transf(tr) {
            (this.cta_contable_cta_transf = `${tr.nomcta}`),
                (this.id_cta_contable_cta_transf = `${tr.id_plan_cuentas}`),
                (this.cod_cta_contable_cta_transf = `${tr.codcta}`),
                (this.popupActive_cta_transf = false);
        },
        verCta_Ingreso($id) {
            let me = this;
            var url = "/api/abrircuenta_ingreso_bodega/" + $id;

            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.id_cta_contable_cta_ingreso =
                        respuesta.recupera[0].id_plan_cuentas;
                    me.cta_contable_cta_ingreso =
                        respuesta.recupera[0].nombre_cuenta;
                    me.cod_cta_contable_cta_ingreso =
                        respuesta.recupera[0].cod_cuenta;
                    me.idrecupera_cta_ingreso =
                        respuesta.recupera[0].id_cuenta_ingreso_bodega;
                    me.id_bodega_ingreso=respuesta.recupera[0].id_bodega;
                    console.log(
                        respuesta.recupera[0].id_plan_cuentas + ":id_plan_ctas"
                    );
                    me.modal_agregar_cta_ingreso = true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarCta_Ingreso(cd) {
            this.modal_eliminar_cta_ingreso = true;
            this.idrecupera_cta_ingreso = cd;
        },
        //eliminar un reguistro
        acceptAlertCta_Ingreso(parameters) {
            axios
                .delete("/api/eliminarcuenta_ingreso_bodega/" + parameters)
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Reguistro Eliminado  ",
                        text: "El reguistro selecionado fue eliminado con exito"
                    });
                    this.modal_eliminar_cta_ingreso = false;
                    this.idrecupera_cta_ingreso = null;
                    this.listarCtas_Ingreso(1, this.buscar2_cta_ingreso);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Error al eliminar",
                        text:
                            "Ha ocurrido un error al momento de eliminar reguistro"
                    });
                });
        },
        verCta_Egreso($id) {
            let me = this;
            var url = "/api/abrircuenta_egreso_bodega/" + $id;

            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.id_cta_contable_cta_egreso =
                        respuesta.recupera[0].id_plan_cuentas;
                    me.cta_contable_cta_egreso =
                        respuesta.recupera[0].nombre_cuenta;
                    me.cod_cta_contable_cta_egreso =
                        respuesta.recupera[0].cod_cuenta;
                    me.idrecupera_cta_egreso =
                        respuesta.recupera[0].id_cuenta_egreso_bodega;
                    me.id_bodega_egreso=respuesta.recupera[0].id_bodega;
                    console.log(
                        respuesta.recupera[0].id_plan_cuentas + ":id_plan_ctas"
                    );
                    me.modal_agregar_cta_egreso = true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarCta_Egreso(cd) {
            this.modal_eliminar_cta_egreso = true;
            this.idrecupera_cta_egreso = cd;
        },
        //eliminar un reguistro
        acceptAlertCta_Egreso(parameters) {
            axios
                .delete("/api/eliminarcuenta_egreso_bodega/" + parameters)
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Reguistro Eliminado  ",
                        text: "El reguistro selecionado fue eliminado con exito"
                    });
                    this.modal_eliminar_cta_egreso = false;
                    this.idrecupera_cta_egreso = null;
                    this.listarCtas_Egreso(1, this.buscar2_cta_egreso);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Error al eliminar",
                        text:
                            "Ha ocurrido un error al momento de eliminar reguistro"
                    });
                });
        },
        verCta_Transf($id) {
            let me = this;
            var url = "/api/abrircuenta_transf_bodega/" + $id;

            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.id_cta_contable_cta_transf =
                        respuesta.recupera[0].id_plan_cuentas;
                    me.cta_contable_cta_transf =
                        respuesta.recupera[0].nombre_cuenta;
                    me.cod_cta_contable_cta_transf =
                        respuesta.recupera[0].cod_cuenta;
                    me.idrecupera_cta_transf =
                        respuesta.recupera[0].id_cuenta_transf_bodega;
                    me.id_bodega_transf=respuesta.recupera[0].id_bodega;
                    console.log(
                        respuesta.recupera[0].id_plan_cuentas + ":id_plan_ctas"
                    );
                    me.modal_agregar_cta_transf = true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarCta_Transf(cd) {
            this.modal_eliminar_cta_transf = true;
            this.idrecupera_cta_transf = cd;
        },
        //eliminar un reguistro
        acceptAlertCta_Transf(parameters) {
            axios
                .delete("/api/eliminarcuenta_transf_bodega/" + parameters)
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Reguistro Eliminado  ",
                        text: "El reguistro selecionado fue eliminado con exito"
                    });
                    this.modal_eliminar_cta_transf = false;
                    this.idrecupera_cta_transf = null;
                    this.listarCtas_Transf(1, this.buscar2_cta_transf);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Error al eliminar",
                        text:
                            "Ha ocurrido un error al momento de eliminar reguistro"
                    });
                });
        },
        guardarCta_Ingreso() {
            if (this.validar_Ingreso()) {
                return;
            }
            axios
                .post("/api/agregarcuenta_ingreso_bodega", {
                    cod_cuenta: this.cod_cta_contable_cta_ingreso,
                    nombre_cuenta: this.cta_contable_cta_ingreso,
                    id_plan_cuentas: this.id_cta_contable_cta_ingreso,
                    id_bodega:this.id_bodega_ingreso,
                    ucrea: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive_cta_ingreso = false;
                    this.modal_agregar_cta_ingreso = false;
                    this.$vs.notify({
                        title: "Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.listarCtas_Ingreso(1, this.buscar2_cta_ingreso);
                    this.vaciarCta_Ingreso();
                })
                .catch(err => {});
        },
        editarCta_Ingreso() {
            if (this.validar_Ingreso()) {
                return;
            }
            axios
                .put("/api/actualizarcuenta_ingreso_bodega", {
                    id: this.idrecupera_cta_ingreso,
                    cod_cuenta: this.cod_cta_contable_cta_ingreso,
                    nombre_cuenta: this.cta_contable_cta_ingreso,
                    id_plan_cuentas: this.id_cta_contable_cta_ingreso,
                    id_bodega:this.id_bodega_ingreso,
                    umodifica: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive_cta_ingreso = false;
                    this.modal_agregar_cta_ingreso = false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarCtas_Ingreso(1, this.buscar2_cta_ingreso);
                    this.vaciarCta_Ingreso();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarCta_Ingreso() {
            (this.cod_cta_contable_cta_ingreso = ""),
                (this.cta_contable_cta_ingreso = ""),
                (this.id_cta_contable_cta_ingreso = ""),
                (this.idrecupera_cta_ingreso = null);
                (this.id_bodega_ingreso="");
        },
        //cancela lo que se esta hacien en impuestos
        cancelarCta_Ingreso() {
            (this.popupActive_cta_ingreso = false),
                (this.cod_cta_contable_cta_ingreso = ""),
                (this.cta_contable_cta_ingreso = ""),
                (this.id_cta_contable_cta_ingreso = ""),
                (this.idrecupera_cta_ingreso = null);
                (this.id_bodega_ingreso="");
        },
        validar_Ingreso() {
            this.errorctas_cta_ingreso = 0;
            this.errorcta_contable_cta_ingreso = [];
            if (!this.id_cta_contable_cta_ingreso) {
                this.errorcta_contable_cta_ingreso.push("Campo Obligatorio");
                this.errorctas_cta_ingreso = 1;
                console.log("descrip_retencion ingreso");
            }
            return this.errorctas_cta_ingreso;
        },
        guardarCta_Egreso() {
            if (this.validar_Egreso()) {
                return;
            }
            axios
                .post("/api/agregarcuenta_egreso_bodega", {
                    cod_cuenta: this.cod_cta_contable_cta_egreso,
                    nombre_cuenta: this.cta_contable_cta_egreso,
                    id_plan_cuentas: this.id_cta_contable_cta_egreso,
                    id_bodega:this.id_bodega_egreso,
                    ucrea: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive_cta_egreso = false;
                    this.modal_agregar_cta_egreso = false;
                    this.$vs.notify({
                        title: "Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.listarCtas_Egreso(1, this.buscar2_cta_egreso);
                    this.vaciarCta_Egreso();
                })
                .catch(err => {});
        },
        editarCta_Egreso() {
            if (this.validar_Egreso()) {
                return;
            }
            axios
                .put("/api/actualizarcuenta_egreso_bodega", {
                    id: this.idrecupera_cta_egreso,
                    cod_cuenta: this.cod_cta_contable_cta_egreso,
                    nombre_cuenta: this.cta_contable_cta_egreso,
                    id_plan_cuentas: this.id_cta_contable_cta_egreso,
                    id_bodega:this.id_bodega_egreso,
                    umodifica: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive_cta_egreso = false;
                    this.modal_agregar_cta_egreso = false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarCtas_Egreso(1, this.buscar2_cta_egreso);
                    this.vaciarCta_Egreso();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarCta_Egreso() {
            (this.cod_cta_contable_cta_egreso = ""),
                (this.cta_contable_cta_egreso = ""),
                (this.id_cta_contable_cta_egreso = ""),
                (this.idrecupera_cta_egreso = null);
                (this.id_bodega_egreso="");
        },
        //cancela lo que se esta hacien en impuestos
        cancelarCta_Egreso() {
            (this.popupActive_cta_egreso = false),
                (this.cod_cta_contable_cta_egreso = ""),
                (this.cta_contable_cta_egreso = ""),
                (this.id_cta_contable_cta_egreso = ""),
                (this.idrecupera_cta_egreso = null);
                (this.id_bodega_egreso="");
        },
        validar_Egreso() {
            this.errorctas_cta_egreso = 0;
            this.errorcta_contable_cta_egreso = [];
            if (!this.id_cta_contable_cta_egreso) {
                this.errorcta_contable_cta_egreso.push("Campo Obligatorio");
                this.errorctas_cta_egreso = 1;
                console.log("descrip_retencion egreso");
            }
            return this.errorctas_cta_egreso;
        },
        guardarCta_Transf() {
            if (this.validar_Transf()) {
                return;
            }
            axios
                .post("/api/agregarcuenta_transf_bodega", {
                    cod_cuenta: this.cod_cta_contable_cta_transf,
                    nombre_cuenta: this.cta_contable_cta_transf,
                    id_plan_cuentas: this.id_cta_contable_cta_transf,
                    id_bodega:this.id_bodega_transf,
                    ucrea: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive_cta_transf = false;
                    this.modal_agregar_cta_transf = false;
                    this.$vs.notify({
                        title: "Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.listarCtas_Transf(1, this.buscar2_cta_transf);
                    this.vaciarCta_Transf();
                })
                .catch(err => {});
        },
        editarCta_Transf() {
            if (this.validar_Transf()) {
                return;
            }
            axios
                .put("/api/actualizarcuenta_transf_bodega", {
                    id: this.idrecupera_cta_transf,
                    cod_cuenta: this.cod_cta_contable_cta_transf,
                    nombre_cuenta: this.cta_contable_cta_transf,
                    id_plan_cuentas: this.id_cta_contable_cta_transf,
                    id_bodega:this.id_bodega_transf,
                    umodifica: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive_cta_transf = false;
                    this.modal_agregar_cta_transf = false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarCtas_Transf(1, this.buscar2_cta_transf);
                    this.vaciarCta_Transf();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarCta_Transf() {
            (this.cod_cta_contable_cta_transf = ""),
                (this.cta_contable_cta_transf = ""),
                (this.id_cta_contable_cta_transf = ""),
                (this.idrecupera_cta_transf = null);
                (this.id_bodega_transf="");
        },
        //cancela lo que se esta hacien en impuestos
        cancelarCta_Transf() {
            (this.popupActive_cta_transf = false),
                (this.cod_cta_contable_cta_transf = ""),
                (this.cta_contable_cta_transf = ""),
                (this.id_cta_contable_cta_transf = ""),
                (this.idrecupera_cta_transf = null);
                (this.id_bodega_transf="");
        },
        validar_Transf() {
            this.errorctas_cta_transf = 0;
            this.errorcta_contable_cta_transf = [];
            if (!this.id_cta_contable_cta_transf) {
                this.errorcta_contable_cta_transf.push("Campo Obligatorio");
                this.errorctas_cta_transf = 1;
                console.log("descrip_retencion transf");
            }
            return this.errorctas_cta_transf;
        }
    },
    mounted() {
        this.listarbodega(this.buscar);
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
.vs-popup {
    width: 1060px !important;
}
.mediano .vs-popup {
    width: 800px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.peque1 .vs-popup {
    width: 500px !important;
}
.peque2 .vs-popup {
    width: 400px !important;
}
.peque2a .vs-popup {
    width: 900px !important;
}
.peque3a .vs-popup {
    width: 400px !important;
}

.full .vs-popup {
    width: 1060px !important;
}
.demo-alignment > * {
    margin-right: 1.5rem;
    margin-top: 0.8rem;
}
</style>
