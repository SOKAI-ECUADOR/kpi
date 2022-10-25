<template>
    <vx-card title="Gestión de Bodegas" class="mt-10">
        <vs-divider>Información de Bodega</vs-divider>
        <div class="vx-row">
            <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
                <h5>Código:</h5>
                <p>{{ codigo }}</p>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
                <h5>Nombre de Bodega:</h5>
                <p>{{ nombre }}</p>
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
                <h5>Responsable de Bodega:</h5>
                {{ responsable }}
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
                <h5>Ubicación:</h5>
                {{ ubicacion }}
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
                <h5>Dirección:</h5>
                {{ direccion }}
            </div>
            <div class="vx-col sm:w-1/3 w-full mb-3 mt-2">
                <h5>Teléfono:</h5>
                {{ telefono }}
            </div>
            <vs-divider />
        </div>
        <div class="vx-row sm:w-full w-full mt-5 tabproductos">
            <vs-tabs alignment="fixed">
                <!-- ....................Visualiziacion de productos.................... -->
                <vs-tab
                    class="vx-col "
                    label="Inventario"
                    icon-pack="feather"
                    icon="icon-layers"
                >
                    <div
                        class="flex flex-wrap justify-between items-center mb-3"
                    >
                        <div
                            class="flex flex-wrap justify-between items-center ag-grid-table-actions-left"
                        ></div>
                        <div
                            class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                        >
                            <vs-button
                                color="warning"
                                type="filled"
                                @click="pdfInventario(idrecupera)"
                                >Descargar PDF</vs-button
                            >
                        </div>
                    </div>
                    <!--<vs-divider />
                            <vs-divider position="left">
                            Inventario de Bodega
                            <vs-button color="warning" type="filled" @click="pdfInventario(idrecupera)">Descargar PDF</vs-button>
                        </vs-divider>-->
                    <vs-table
                        stripe
                        max-items="30"
                        pagination
                        class="vx-col sm:w-full w-full"
                        :data="stockarray"
                    >
                        <!-- HEADER -->
                        <template slot="thead">
                            <vs-th>CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>DESCRIPCIÓN</vs-th>
                            <vs-th class="text-center">CANTIDAD</vs-th>
                            <vs-th class="text-center">COSTO UNITARIO</vs-th>
                            <vs-th class="text-center">COSTO TOTAL</vs-th>
                        </template>
                        <!-- DATA -->
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(pr, index) in data" :key="index">
                                <vs-td v-if="pr.cod_alterno">{{
                                    pr.cod_alterno
                                }}</vs-td
                                ><vs-td v-else>{{ pr.cod_principal }}</vs-td>
                                <vs-td v-if="pr.nombrep">{{
                                    pr.nombrep
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="pr.descripcion">{{
                                    pr.descripcion
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td class="text-center" v-if="pr.cantidad">{{
                                    pr.cantidad
                                }}</vs-td>
                                <vs-td class="text-center" v-else>0</vs-td>
                                <vs-td
                                    class="text-center"
                                    v-if="pr.costo_unitario"
                                    >{{ pr.costo_unitario.toFixed(6) }}</vs-td
                                >
                                <vs-td class="text-center" v-else>0</vs-td>
                                <vs-td
                                    class="text-center"
                                    v-if="pr.costo_total"
                                    >{{ pr.costo_total.toFixed(6) }}</vs-td
                                >
                                <vs-td class="text-center" v-else>0</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </vs-tab>
                <!-- ....................Ingreso de bodega.................... -->
                <vs-tab
                    v-if="veringresrol"
                    class="vx-col"
                    label="Ingresos"
                    icon-pack="feather"
                    icon="icon-arrow-down"
                    @click="firstlistingres()"
                >
                    <div
                        class="flex flex-wrap justify-between items-center mb-3"
                    >
                        <div
                            class="flex flex-wrap justify-between items-center ag-grid-table-actions-left"
                        >
                            <template v-if="filterstablei.filtertab">
                                <label
                                    style="position: absolute; top: 0.2%; left: 0.4%;"
                                    ><b>Mostrar:</b></label
                                >
                                <div
                                    class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                >
                                    <label class="vs-input--label mr-1"
                                        >Contabilizados</label
                                    >
                                    <vs-switch
                                        color="success"
                                        @input="filtertablai()"
                                        v-model="filterstablei.filt_asientos"
                                    />
                                </div>
                                <div
                                    class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                >
                                    <label class="vs-input--label mr-1"
                                        >No-contabilizados</label
                                    >
                                    <vs-switch
                                        color="success"
                                        @input="filtertablai()"
                                        v-model="filterstablei.filt_noasientos"
                                    />
                                </div>
                            </template>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                        >
                            <vs-input
                                class="mb-4 md:mb-0 mr-4"
                                v-model="buscaringreso"
                                @keyup="listaringresobodega(buscaringreso)"
                                v-bind:placeholder="i18nbuscar"
                            />
                            <div
                                class="flex flex-wrap justify-between items-center mr-1 ml-1"
                            >
                                <vx-tooltip
                                    text="Filtrar"
                                    position="top"
                                    style="display: inline-flex;"
                                >
                                    <vs-button
                                        v-if="filterstablei.filtertab == false"
                                        color="primary"
                                        type="filled"
                                        icon="filter_list"
                                        @click="filterstablei.filtertab = true"
                                    ></vs-button>
                                    <vs-button
                                        v-else
                                        color="success"
                                        type="filled"
                                        icon="filter_list"
                                        @click="
                                            (filterstablei.filtertab = false),
                                                filtertablai()
                                        "
                                    ></vs-button
                                ></vx-tooltip>
                            </div>
                            <vs-button
                                v-if="crearingresrol"
                                class="btnx"
                                type="filled"
                                @click="nuevoingreso()"
                                >Nuevo Ingreso</vs-button
                            >
                        </div>
                    </div>
                    <vs-table
                        stripe
                        max-items="30"
                        pagination
                        class="vx-col sm:w-full w-full"
                        :data="contenidoingreso"
                    >
                        <!-- HEADER -->
                        <template slot="thead">
                            <vs-th>No.</vs-th>
                            <vs-th class="text-center">Fecha de Ingreso</vs-th>
                            <vs-th class="text-center">Tipo de Ingreso</vs-th>
                            <vs-th class="text-center">Observación</vs-th>
                            <vs-th class="text-center">Opciones</vs-th>
                        </template>
                        <!-- DATA -->
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(pr, index) in data" :key="index">
                                <vs-td v-if="pr.num_ingreso">{{
                                    pr.num_ingreso
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td
                                    v-if="pr.fecha_ingreso"
                                    class="text-center"
                                    >{{ pr.fecha_ingreso }}</vs-td
                                >
                                <vs-td v-else class="text-center">-</vs-td>
                                <vs-td
                                    class="text-center"
                                    v-if="pr.tipo_ingreso"
                                    >{{ pr.tipo_ingreso }}</vs-td
                                >
                                <vs-td class="text-center" v-else>-</vs-td>
                                <vs-td
                                    class="text-center"
                                    v-if="pr.observ_ingreso"
                                    >{{ pr.observ_ingreso }}</vs-td
                                >
                                <vs-td class="text-center" v-else>-</vs-td>
                                <vs-td class="whitespace-no-wrap text-center">
                                    <vx-tooltip
                                        text="Visualizar Ingreso"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            icon="CheckSquareIcon"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            class="pointer"
                                            @click.stop="
                                                veringreso(pr.id_bodega_ingreso)
                                            "
                                        />
                                    </vx-tooltip>
                                    <vx-tooltip
                                        text="Descargar PDF"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            icon="FileTextIcon"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            class="pointer"
                                            @click.stop="
                                                pdfingreso(pr.id_bodega_ingreso)
                                            "
                                        />
                                    </vx-tooltip>
                                    <vx-tooltip
                                        text="Asiento"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            v-if="
                                                pr.contabilidad == null &&
                                                    pr.id_factura == null &&
                                                    pr.id_factura_compra ==
                                                        null &&
                                                    pr.id_importacion == null &&
                                                    pr.id_nota_credito ==
                                                        null &&
                                                    pr.id_nota_credito_compra ==
                                                        null
                                            "
                                            icon="SlidersIcon"
                                            svgClasses="w-5 h-5 fill-current text-primary"
                                            class="cursor-pointer"
                                            @click.stop="
                                                Contabilidad_Ingreso(
                                                    pr.id_bodega_ingreso
                                                )
                                            "
                                        />
                                        <feather-icon
                                            v-else-if="
                                                pr.contabilidad !== null &&
                                                    pr.id_factura == null &&
                                                    pr.id_factura_compra ==
                                                        null &&
                                                    pr.id_importacion == null &&
                                                    pr.id_nota_credito ==
                                                        null &&
                                                    pr.id_nota_credito_compra ==
                                                        null
                                            "
                                            icon="SlidersIcon"
                                            svgClasses="w-5 h-5 fill-current text-success"
                                            class="cursor-pointer"
                                            @click.stop="
                                                Contabilidad_Ingreso(
                                                    pr.id_bodega_ingreso
                                                )
                                            "
                                        />
                                    </vx-tooltip>
                                    <feather-icon
                                        icon="CheckIcon"
                                        v-if="pr.contabilidad !== null"
                                        svgClasses="w-5 h-5"
                                    />
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                    <!--............................ Popup Registro de ingreso de Bodega ....................... -->
                    <vs-popup
                        title="Ingreso de Bodega"
                        :active.sync="popupingreso"
                        class="transpopup"
                    >
                        <div class="vx-row">
                            <vs-divider
                                >Registro de Ingreso a Bodega</vs-divider
                            >
                            <vs-divider position="left"
                                >Información de Ingreso</vs-divider
                            >
                            <div
                                class="vx-col md:w-1/4 sm:w-full w-full ml-auto mb-6"
                            >
                                <vs-input
                                    disabled
                                    class="w-full txt-center"
                                    label="Numero de Ingreso:"
                                    v-model="num_ingreso"
                                    @keypress="solonumeros($event)"
                                />
                                <div v-show="error" v-if="!num_ingreso">
                                    <div
                                        v-for="err in errornum_ingreso"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <div class="vx-col md:w-1/4 sm:w-full w-full  mb-6">
                                <label class="vs-input--label"
                                    >Fecha de Ingreso:</label
                                >
                                <flat-pickr
                                    disabled
                                    class="w-full"
                                    :config="configdateTimePicker"
                                    v-model="fecha_ingreso"
                                    placeholder="Elegir Fecha"
                                />
                            </div>
                            <div
                                class="vx-col md:w-1/4 sm:w-full w-full mr-auto mb-6"
                            >
                                <vs-select
                                    :disabled="recuperaingreso"
                                    placeholder="Seleccione uno"
                                    class="selectExample w-full"
                                    label="Tipo de Ingreso:"
                                    label-placeholder="Seleccione uno"
                                    vs-multiple
                                    v-model="tipo_ingreso"
                                >
                                    <vs-select-item
                                        value="Inventario Inicial"
                                        text="Inventario Inicial"
                                    />
                                    <vs-select-item
                                        value="Fabricación"
                                        text="Fabricación"
                                    />
                                    <vs-select-item
                                        value="Ingreso por Ajuste"
                                        text="Ingreso por Ajuste"
                                    />
                                    <!--  <vs-select-item
                                        value="Devolucion por Venta"
                                        text="Devolucion por Venta"
                                    />-->
                                </vs-select>
                                <div v-show="error" v-if="!tipo_ingreso">
                                    <div
                                        v-for="err in errortipo_ingreso"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <!--div class="vx-col md:w-1/4 sm:w-full w-full mb-6">
                                <vs-select
                                    :disabled="recuperaingreso"
                                    placeholder="Seleccione Proyecto"
                                    class="selectExample w-full"
                                    label="Proyecto:"
                                    label-placeholder="Proyecto:"
                                    vs-multiple
                                    v-model="proyecto_ingreso"
                                >
                                    <vs-select-item
                                        :key="res.id_proyecto"
                                        :value="res.id_proyecto"
                                        :text="res.descripcion"
                                        v-for="res in contproyect"
                                    />
                                </vs-select>
                            </div>-->
                        </div>
                        <div class="vx-col md:w-full sm:w-full w-full mb-6">
                            <label class="vs-input--label"
                                >Observaciones:</label
                            >
                            <vs-textarea
                                :disabled="recuperaingreso"
                                v-model="observ_ingreso"
                                rows="2"
                            />
                        </div>
                        <!-- Inicio Agregar Producto-->
                        <vs-divider />
                        <vs-divider position="left"
                            >Registro de Productos</vs-divider
                        >
                        <!-- INVOICE TASKS TABLE -->
                        <a
                            v-if="!recuperaingreso"
                            class="flex items-center cursor-pointer mb-4"
                            @click="abrirproductos()"
                            >Añadir Productos</a
                        >
                        <vs-table
                            hoverFlat
                            :data="contenidopr"
                            style="font-size: 12px;"
                        >
                            <!-- HEADER -->
                            <template slot="thead">
                                <vs-th>CÓDIGO</vs-th>
                                <vs-th>NOMBRE</vs-th>
                                <vs-th>PROYECTO</vs-th>
                                <vs-th class="text-center">CANTIDAD</vs-th>
                                <vs-th class="text-center"
                                    >COSTO UNITARIO</vs-th
                                >
                                <vs-th class="text-center">COSTO TOTAL</vs-th>
                                <vs-th class="text-center">
                                    <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                                </vs-th>
                            </template>
                            <!-- DATA -->
                            <template slot-scope="{ data }">
                                <vs-tr v-for="(tr, index) in data" :key="index">
                                    <vs-td
                                        style="width:150px!important;"
                                        v-if="tr.cod_alterno"
                                        >{{ tr.cod_alterno }}</vs-td
                                    ><vs-td
                                        style="width:150px!important;"
                                        v-else
                                        >{{ tr.cod_principal }}</vs-td
                                    >
                                    <vs-td :data="tr.nombre">{{
                                        tr.nombre
                                    }}</vs-td>
                                    <vs-td style="width:200px!important;">
                                        <vs-select
                                            :disabled="recuperaingreso"
                                            class="selectExample w-full"
                                            v-model="tr.proyecto"
                                        >
                                            <vs-select-item
                                                :key="res.id_proyecto"
                                                :value="res.id_proyecto"
                                                :text="res.descripcion"
                                                v-for="res in contproyect"
                                            />
                                        </vs-select>
                                        <div v-show="error" v-if="!tr.proyecto">
                                            <div
                                                v-for="err in tr.errorproyecto_ingreso"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td
                                        :data="tr.cant_ingreso"
                                        style="width:200px!important;"
                                    >
                                        <vs-input
                                            :disabled="recuperaingreso"
                                            class="w-full txt-center"
                                            v-model="tr.cant_ingreso"
                                            @keypress="solonumeros($event)"
                                        />
                                        <div
                                            v-show="error"
                                            v-if="!tr.cant_ingreso"
                                        >
                                            <div
                                                v-for="err in tr.errorcant_ingreso"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td
                                        :data="tr.cost_unit_ingreso"
                                        style="width:200px!important;"
                                    >
                                        <vs-input
                                            :disabled="recuperaingreso"
                                            class="w-full txt-center"
                                            v-model="tr.cost_unit_ingreso"
                                            @keypress="solonumeros($event)"
                                        />
                                        <div
                                            v-show="error"
                                            v-if="!tr.cost_unit_ingreso"
                                        >
                                            <div
                                                v-for="err in tr.errorcost_unit_ingreso"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td
                                        :data="tr.cost_tot_ingreso"
                                        style="width:200px!important;"
                                    >
                                        <span style="display:none">{{
                                            (tr.cost_tot_ingreso =
                                                tr.cant_ingreso *
                                                tr.cost_unit_ingreso)
                                        }}</span>
                                        <vs-input
                                            class="w-full txt-center"
                                            v-model="tr.cost_tot_ingreso"
                                            disabled
                                        />
                                    </vs-td>
                                    <vs-td style="width:25px!important;">
                                        <vx-tooltip
                                            text="Eliminar"
                                            position="top"
                                            style="display: inline-flex;"
                                        >
                                            <feather-icon
                                                v-if="!recuperaingreso"
                                                icon="TrashIcon"
                                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                class="pointer"
                                                @click="eliminar(index)"
                                            />
                                        </vx-tooltip>
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                        <vs-divider />
                        <div
                            class="vx-row md:w-full sm:w-full w-full mt-4 mb-2 text-center"
                        >
                            <div class="vx-col md:1/3 sm:1/3 w-1/3 ml-auto">
                                <label class="vs-input--label"
                                    >TOTAL COSTOS UNITARIOS</label
                                >
                                <h1>
                                    $
                                    {{
                                        totales_ingreso.costos_unitarios.toFixed(
                                            2
                                        )
                                    }}
                                </h1>
                            </div>
                            <div class="vx-col md:1/3 sm:1/3 w-1/3 mr-auto">
                                <label class="vs-input--label"
                                    >TOTAL COSTOS TOTALES</label
                                >
                                <h1>
                                    $
                                    {{
                                        totales_ingreso.costos_totales.toFixed(
                                            2
                                        )
                                    }}
                                </h1>
                            </div>
                        </div>
                        <!-- Botones-->
                        <div class="vx-row" v-if="!recuperaingreso">
                            <div class="vx-col w-1/3 ml-auto mt-6 text-center">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    @click="guardarbodegaingreso()"
                                    >Guardar</vs-button
                                >
                            </div>
                            <div class="vx-col w-1/3 mr-auto mt-6 text-center">
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelaringreso()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                        <div class="vx-row" v-else>
                            <div
                                class="vx-col w-1/1 mr-auto ml-auto mt-6 text-center"
                            >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelaringreso()"
                                    >Cerrar</vs-button
                                >
                            </div>
                        </div>
                        <!-- .......................Popup Agregar Producto Ingresos.............................-->
                        <vs-popup
                            classContent="popup-example"
                            title="Seleccione el Producto"
                            :active.sync="popupprod"
                            class="listpopup"
                        >
                            <div class="vx-col w-full">
                                <vs-input
                                    class="mb-4 mr-4 w-full"
                                    v-model="buscarp"
                                    @keyup="listarp(buscarp)"
                                    v-bind:placeholder="i18nbuscar"
                                />
                                <vs-table
                                    stripe
                                    max-items="5"
                                    pagination
                                    v-model="cuentaarrayp"
                                    @selected="handleSelectedp"
                                    :data="contenidop"
                                >
                                    <template slot="thead">
                                        <vs-th>Código</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Descripcion</vs-th>
                                        <vs-th>Marca</vs-th>
                                        <vs-th>Modelo</vs-th>
                                        <vs-th>Costo</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :data="tr"
                                            :key="indextr"
                                            v-for="(tr, indextr) in data"
                                        >
                                            <vs-td v-if="tr.cod_alterno">{{
                                                tr.cod_alterno
                                            }}</vs-td
                                            ><vs-td v-else>{{
                                                tr.cod_principal
                                            }}</vs-td>
                                            <vs-td v-if="tr.nombre">{{
                                                tr.nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="tr.descripcion">{{
                                                tr.descripcion
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="tr.nombremarca">{{
                                                tr.nombremarca
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="tr.nombremodelo">{{
                                                tr.nombremodelo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="tr.costo_total">{{
                                                tr.costo_total
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </vs-popup>
                    </vs-popup>
                </vs-tab>
                <!-- ............................Egreso de bodega.........................................-->
                <vs-tab
                    v-if="veregresrol"
                    class="vx-col"
                    label="Egresos"
                    icon-pack="feather"
                    icon="icon-arrow-up"
                    @click="firstlistegres()"
                >
                    <div
                        class="flex flex-wrap justify-between items-center mb-3"
                    >
                        <div
                            class="flex flex-wrap justify-between items-center ag-grid-table-actions-left"
                        >
                            <template v-if="filterstablee.filtertab">
                                <label
                                    style="position: absolute; top: 0.2%; left: 0.4%;"
                                    ><b>Mostrar:</b></label
                                >
                                <div
                                    class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                >
                                    <label class="vs-input--label mr-1"
                                        >Contabilizados</label
                                    >
                                    <vs-switch
                                        color="success"
                                        @input="filtertablae()"
                                        v-model="filterstablee.filt_asientos"
                                    />
                                </div>
                                <div
                                    class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                >
                                    <label class="vs-input--label mr-1"
                                        >No-contabilizados</label
                                    >
                                    <vs-switch
                                        color="success"
                                        @input="filtertablae()"
                                        v-model="filterstablee.filt_noasientos"
                                    />
                                </div>
                            </template>
                        </div>
                        <div
                            class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                        >
                            <vs-input
                                class="mb-4 md:mb-0 mr-4"
                                v-model="buscaregreso"
                                @keyup="listaregresobodega(buscaregreso)"
                                v-bind:placeholder="i18nbuscar"
                            />
                            <div
                                class="flex flex-wrap justify-between items-center mr-1 ml-1"
                            >
                                <vx-tooltip
                                    text="Filtrar"
                                    position="top"
                                    style="display: inline-flex;"
                                >
                                    <vs-button
                                        v-if="filterstablee.filtertab == false"
                                        color="primary"
                                        type="filled"
                                        icon="filter_list"
                                        @click="filterstablee.filtertab = true"
                                    ></vs-button>
                                    <vs-button
                                        v-else
                                        color="success"
                                        type="filled"
                                        icon="filter_list"
                                        @click="
                                            (filterstablee.filtertab = false),
                                                filtertablae()
                                        "
                                    ></vs-button
                                ></vx-tooltip>
                            </div>
                            <vs-button
                                v-if="crearegresrol"
                                class="btnx"
                                type="filled"
                                @click="nuevoegreso()"
                                >Nuevo Egreso</vs-button
                            >
                        </div>
                    </div>
                    <vs-table
                        stripe
                        max-items="30"
                        pagination
                        class="vx-col sm:w-full w-full"
                        :data="contenidoegreso"
                    >
                        <!-- HEADER -->
                        <template slot="thead">
                            <vs-th>No.</vs-th>
                            <vs-th class="text-center">Fecha de Egreso</vs-th>
                            <vs-th class="text-center">Tipo de Egreso</vs-th>
                            <vs-th class="text-center">Observación</vs-th>
                            <vs-th class="text-center">Opciones</vs-th>
                        </template>
                        <!-- DATA -->
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(pr, index) in data" :key="index">
                                <vs-td v-if="pr.num_egreso">{{
                                    pr.num_egreso
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td
                                    v-if="pr.fecha_egreso"
                                    class="text-center"
                                    >{{ pr.fecha_egreso }}</vs-td
                                >
                                <vs-td v-else class="text-center">-</vs-td>
                                <vs-td
                                    class="text-center"
                                    v-if="pr.tipo_egreso"
                                    >{{ pr.tipo_egreso }}</vs-td
                                >
                                <vs-td class="text-center" v-else>-</vs-td>
                                <vs-td
                                    class="text-center"
                                    v-if="pr.observ_egreso"
                                    >{{ pr.observ_egreso }}</vs-td
                                >
                                <vs-td class="text-center" v-else>-</vs-td>
                                <vs-td class="whitespace-no-wrap text-center">
                                    <vx-tooltip
                                        text="Visualizar Egreso"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            icon="CheckSquareIcon"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            class="pointer"
                                            @click.stop="
                                                veregreso(pr.id_bodega_egreso)
                                            "
                                        />
                                    </vx-tooltip>

                                    <vx-tooltip
                                        text="Descargar PDF"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            icon="FileTextIcon"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            class="pointer"
                                            @click.stop="
                                                pdfegreso(pr.id_bodega_egreso)
                                            "
                                        />
                                    </vx-tooltip>
                                    <vx-tooltip
                                        text="Asiento"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            v-if="
                                                pr.id_factura !== null &&
                                                    pr.contabilidad == null
                                            "
                                            icon="SlidersIcon"
                                            svgClasses="w-5 h-5 fill-current text-primary"
                                            class="cursor-pointer"
                                            @click.stop="
                                                ContabilidadEgresoFactura(
                                                    pr.id_bodega_egreso
                                                )
                                            "
                                        />
                                        <feather-icon
                                            v-else-if="
                                                pr.id_factura !== null &&
                                                    pr.contabilidad !== null
                                            "
                                            icon="SlidersIcon"
                                            svgClasses="w-5 h-5 fill-current text-success"
                                            class="cursor-pointer"
                                            @click.stop="
                                                ContabilidadEgresoFactura(
                                                    pr.id_bodega_egreso
                                                )
                                            "
                                        />
                                    </vx-tooltip>
                                    <vx-tooltip
                                        text="Asiento"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            v-if="
                                                pr.id_factura == null &&
                                                    pr.contabilidad == null
                                            "
                                            icon="SlidersIcon"
                                            svgClasses="w-5 h-5 fill-current text-primary"
                                            class="cursor-pointer"
                                            @click.stop="
                                                Contabilidad_Egreso(
                                                    pr.id_bodega_egreso
                                                )
                                            "
                                        />
                                        <feather-icon
                                            v-else-if="
                                                pr.id_factura == null &&
                                                    pr.contabilidad !== null
                                            "
                                            icon="SlidersIcon"
                                            svgClasses="w-5 h-5 fill-current text-success"
                                            class="cursor-pointer"
                                            @click.stop="
                                                Contabilidad_Egreso(
                                                    pr.id_bodega_egreso
                                                )
                                            "
                                        />
                                    </vx-tooltip>
                                    <feather-icon
                                        icon="CheckIcon"
                                        v-if="pr.contabilidad !== null"
                                        svgClasses="w-5 h-5"
                                    />
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                    <!------------------------------- Popup para Formulario de Egreso de Bodega ------------------------------->
                    <vs-popup
                        title="Egreso de Bodega"
                        :active.sync="popupegreso"
                        class="transpopup"
                    >
                        <div class="vx-row">
                            <vs-divider>Registro de Salida a Bodega</vs-divider>
                            <vs-divider position="left"
                                >Información de Salida</vs-divider
                            >
                            <div
                                class="vx-col md:w-1/4 sm:w-full w-full ml-auto mb-6"
                            >
                                <vs-input
                                    disabled
                                    class="w-full txt-center"
                                    label="Numero de Egreso:"
                                    v-model="num_egreso"
                                    @keypress="solonumeros($event)"
                                />
                                <div v-show="error" v-if="!num_egreso">
                                    <div
                                        v-for="err in errornum_egreso"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <div class="vx-col md:w-1/4 sm:w-full w-full  mb-6">
                                <label class="vs-input--label"
                                    >Fecha de Salida:</label
                                >
                                <flat-pickr
                                    disabled
                                    class="w-full"
                                    :config="configdateTimePicker"
                                    v-model="fecha_egreso"
                                    placeholder="Elegir Fecha"
                                />
                            </div>
                            <div
                                class="vx-col md:w-1/4 sm:w-full w-full mr-auto mb-6"
                            >
                                <vs-select
                                    :disabled="recuperaegreso"
                                    placeholder="Seleccione uno"
                                    class="selectExample w-full"
                                    label="Tipo de Egreso:"
                                    label-placeholder="Seleccione uno"
                                    vs-multiple
                                    v-model="tipo_egreso"
                                >
                                    <vs-select-item
                                        value="Ventas"
                                        text="Ventas"
                                    />
                                    <vs-select-item
                                        value="Auto Consumo"
                                        text="Auto Consumo"
                                    />
                                    <vs-select-item
                                        value="Egreso por Ajuste"
                                        text="Egreso por Ajuste"
                                    />
                                    <vs-select-item
                                        value="Devolucion por Ingreso"
                                        text="Devolucion por Ingreso"
                                    />
                                </vs-select>
                                <div v-show="error" v-if="!tipo_egreso">
                                    <div
                                        v-for="err in errortipo_egreso"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <!--<div class="vx-col md:w-1/4 sm:w-full w-full mb-6">
                                <vs-select
                                    :disabled="recuperaegreso"
                                    placeholder="Seleccione Proyecto"
                                    class="selectExample w-full"
                                    label="Proyecto:"
                                    label-placeholder="Proyecto:"
                                    vs-multiple
                                    v-model="proyecto_egreso"
                                >
                                    <vs-select-item
                                        :key="res.id_proyecto"
                                        :value="res.id_proyecto"
                                        :text="res.descripcion"
                                        v-for="res in contproyect"
                                    />
                                </vs-select>
                            </div>-->
                        </div>
                        <div class="vx-col md:w-full sm:w-full w-full mb-6">
                            <label class="vs-input--label"
                                >Observaciones:</label
                            >
                            <vs-textarea
                                :disabled="recuperaegreso"
                                v-model="observ_egreso"
                                rows="2"
                            />
                        </div>
                        <!-- Inicio Agregar Producto-->
                        <vs-divider />
                        <vs-divider position="left"
                            >Registro de Productos</vs-divider
                        >
                        <!-- INVOICE TASKS TABLE -->
                        <a
                            v-if="!recuperaegreso"
                            class="flex items-center cursor-pointer mb-4"
                            @click="abrirstock()"
                            >Añadir Productos</a
                        >
                        <vs-table
                            hoverFlat
                            :data="contenidostock"
                            style="font-size: 12px;"
                        >
                            <!-- HEADER -->
                            <template slot="thead">
                                <vs-th>CÓDIGO</vs-th>
                                <vs-th>NOMBRE</vs-th>
                                <vs-th>PROYECTO</vs-th>
                                <vs-th
                                    v-if="!recuperaegreso"
                                    class="text-center"
                                    >STOCK DISPONIBLE</vs-th
                                >
                                <vs-th class="text-center"
                                    >CANTIDAD EGRESO</vs-th
                                >
                                <vs-th class="text-center"
                                    >COSTO UNITARIO</vs-th
                                >
                                <vs-th class="text-center">COSTO TOTAL</vs-th>
                                <vs-th class="text-center">
                                    <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                                </vs-th>
                            </template>
                            <!-- DATA -->
                            <template slot-scope="{ data }">
                                <vs-tr v-for="(tr, index) in data" :key="index">
                                    <vs-td
                                        style="width:150px!important;"
                                        v-if="tr.cod_alterno"
                                        >{{ tr.cod_alterno }}</vs-td
                                    ><vs-td
                                        style="width:150px!important;"
                                        v-else
                                        >{{ tr.cod_principal }}</vs-td
                                    >
                                    <vs-td :data="tr.nombre">{{
                                        tr.nombre
                                    }}</vs-td>
                                    <vs-td style="width:200px!important;">
                                        <vs-select
                                            :disabled="recuperaegreso"
                                            class="selectExample w-full"
                                            v-model="tr.proyecto"
                                        >
                                            <vs-select-item
                                                :key="res.id_proyecto"
                                                :value="res.id_proyecto"
                                                :text="res.descripcion"
                                                v-for="res in contproyect"
                                            />
                                        </vs-select>
                                        <div v-show="error" v-if="!tr.proyecto">
                                            <div
                                                v-for="err in tr.errorproyecto_egreso"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td
                                        v-if="!recuperaegreso"
                                        class="w-full text-center"
                                        style="width:200px!important;"
                                        :data="tr.cantidad"
                                        >{{ tr.cantidad }}</vs-td
                                    >
                                    <vs-td
                                        :data="tr.cant_egreso"
                                        style="width:200px!important;"
                                    >
                                        <vs-input
                                            :disabled="recuperaegreso"
                                            class="w-full txt-center"
                                            v-model="tr.cant_egreso"
                                            @keypress="solonumeros($event)"
                                        />
                                        <div
                                            v-show="error"
                                            v-if="!tr.cant_egreso"
                                        >
                                            <div
                                                v-for="err in tr.errorcant_egreso"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td
                                        :data="tr.cost_unit_egreso"
                                        style="width:200px!important;"
                                    >
                                        <vs-input
                                            disabled
                                            class="w-full txt-center"
                                            v-model="tr.cost_unit_egreso"
                                            @keypress="solonumeros($event)"
                                        />
                                        <div
                                            v-show="error"
                                            v-if="!tr.cost_unit_egreso"
                                        >
                                            <div
                                                v-for="err in tr.errorcost_egreso"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td
                                        :data="tr.cost_tot_egreso"
                                        style="width:200px!important;"
                                    >
                                        <span style="display:none">{{
                                            (tr.cost_tot_egreso =
                                                tr.cant_egreso *
                                                tr.cost_unit_egreso)
                                        }}</span>
                                        <vs-input
                                            disabled
                                            class="w-full txt-center"
                                            v-model="tr.cost_tot_egreso"
                                            @keypress="solonumeros($event)"
                                        />
                                        <div
                                            v-show="error"
                                            v-if="!tr.cost_tot_egreso"
                                        >
                                            <div
                                                v-for="err in tr.errorcost_tot_egreso"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td style="width:25px!important;">
                                        <vx-tooltip
                                            text="Eliminar"
                                            position="top"
                                            style="display: inline-flex;"
                                        >
                                            <feather-icon
                                                v-if="!recuperaegreso"
                                                icon="TrashIcon"
                                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                class="pointer"
                                                @click="eliminar(index)"
                                            />
                                        </vx-tooltip>
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                        <vs-divider />
                        <div
                            class="vx-row md:w-full sm:w-full w-full mt-4 mb-2 text-center"
                        >
                            <div class="vx-col md:1/3 sm:1/3 w-1/3 ml-auto">
                                <label class="vs-input--label"
                                    >TOTAL COSTOS UNITARIOS</label
                                >
                                <h1>
                                    $
                                    {{
                                        totales_egreso.costos_unitarios.toFixed(
                                            2
                                        )
                                    }}
                                </h1>
                            </div>
                            <div class="vx-col md:1/3 sm:1/3 w-1/3 mr-auto">
                                <label class="vs-input--label"
                                    >TOTAL COSTOS TOTALES</label
                                >
                                <h1>
                                    $
                                    {{
                                        totales_egreso.costos_totales.toFixed(2)
                                    }}
                                </h1>
                            </div>
                        </div>
                        <!-- Botones-->
                        <div class="vx-row" v-if="!recuperaegreso">
                            <div class="vx-col w-1/3 ml-auto mt-6 text-center">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    @click="guardarbodegaegreso()"
                                    >Guardar</vs-button
                                >
                            </div>
                            <div class="vx-col w-1/3 mr-auto mt-6 text-center">
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelaregreso()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                        <div class="vx-row" v-else>
                            <div
                                class="vx-col w-1/1 mr-auto ml-auto mt-6 text-center"
                            >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelaregreso()"
                                    >Cerrar</vs-button
                                >
                            </div>
                        </div>
                        <!--  ............................Popup Lista Stock  de bodega para egresos..............................-->
                        <vs-popup
                            classContent="popup-example"
                            title="Seleccione el Producto"
                            :active.sync="popupstock"
                            class="listpopup"
                        >
                            <div class="vx-col w-full">
                                <vs-input
                                    class="mb-4 mr-4 w-full"
                                    v-model="buscarstock"
                                    @keyup="listarstockegreso(buscarstock)"
                                    v-bind:placeholder="i18nbuscar"
                                />
                                <vs-table
                                    stripe
                                    max-items="5"
                                    pagination
                                    class="vx-col sm:w-full w-full"
                                    v-model="cuentastock"
                                    @selected="handleSelectedstockegreso"
                                    :data="stockarrayegreso"
                                    style="font-size: 12px;"
                                >
                                    <!-- HEADER -->

                                    <template slot="thead">
                                        <vs-th>CÓDIGO</vs-th>
                                        <vs-th>NOMBRE</vs-th>
                                        <vs-th>DESCRIPCIÓN</vs-th>
                                        <vs-th>CANTIDAD</vs-th>
                                    </template>
                                    <!-- DATA -->
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :data="pr"
                                            v-for="(pr, index) in data"
                                            :key="index"
                                        >
                                            <vs-td v-if="pr.cod_alterno">{{
                                                pr.cod_alterno
                                            }}</vs-td
                                            ><vs-td v-else>{{
                                                pr.cod_principal
                                            }}</vs-td>
                                            <vs-td v-if="pr.nombrep">{{
                                                pr.nombrep
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="pr.descripcion">{{
                                                pr.descripcion
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="pr.cantidad">{{
                                                pr.cantidad
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </vs-popup>
                    </vs-popup>
                </vs-tab>
                <!----------------------------------------------------- Vista General Tranferencias------------------------------------------------------------------------>
                <vs-tab
                    class="vx-col"
                    label="Transferencias"
                    icon-pack="feather"
                    icon="icon-repeat"
                    @click="firstlisttranse()"
                >
                    <vs-divider />
                    <vs-divider>Tipos de Transferencias</vs-divider>
                    <vs-tabs alignment="fixed">
                        <!----------------------------------------------------- Envio de Transferencias------------------------------------------------------------------------>
                        <vs-tab
                            v-if="vertranserol"
                            class="vx-col"
                            label="Envío de Transferencias"
                            @click="firstlisttranse()"
                        >
                            <div
                                class="flex flex-wrap justify-between items-center mb-3"
                            >
                                <div
                                    class="flex flex-wrap justify-between items-center ag-grid-table-actions-left"
                                >
                                    <template
                                        v-if="filterstabletranse.filtertab"
                                    >
                                        <label
                                            style="position: absolute; top: 0.2%; left: 0.4%;"
                                            ><b>Mostrar:</b></label
                                        >
                                        <div
                                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                        >
                                            <label class="vs-input--label mr-1"
                                                >Contabilizados</label
                                            >
                                            <vs-switch
                                                color="success"
                                                @input="filtertablatranse()"
                                                v-model="
                                                    filterstabletranse.filt_asientos
                                                "
                                            />
                                        </div>
                                        <div
                                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                        >
                                            <label class="vs-input--label mr-1"
                                                >No-contabilizados</label
                                            >
                                            <vs-switch
                                                color="success"
                                                @input="filtertablatranse()"
                                                v-model="
                                                    filterstabletranse.filt_noasientos
                                                "
                                            />
                                        </div>
                                    </template>
                                </div>
                                <div
                                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                                >
                                    <vs-input
                                        class="mb-4 md:mb-0 mr-4"
                                        v-model="buscartranse"
                                        @keyup="
                                            listartransebodega(buscartranse)
                                        "
                                        v-bind:placeholder="i18nbuscar"
                                    />
                                    <div
                                        class="flex flex-wrap justify-between items-center mr-1 ml-1"
                                    >
                                        <vx-tooltip
                                            text="Filtrar"
                                            position="top"
                                            style="display: inline-flex;"
                                        >
                                            <vs-button
                                                v-if="
                                                    filterstabletranse.filtertab ==
                                                        false
                                                "
                                                color="primary"
                                                type="filled"
                                                icon="filter_list"
                                                @click="
                                                    filterstabletranse.filtertab = true
                                                "
                                            ></vs-button>
                                            <vs-button
                                                v-else
                                                color="success"
                                                type="filled"
                                                icon="filter_list"
                                                @click="
                                                    (filterstabletranse.filtertab = false),
                                                        filtertablatranse()
                                                "
                                            ></vs-button
                                        ></vx-tooltip>
                                    </div>
                                    <vs-button
                                        v-if="creartranserol"
                                        class="btnx"
                                        type="filled"
                                        @click="nuevotranse()"
                                        >Nuevo Envío</vs-button
                                    >
                                </div>
                            </div>
                            <vs-table
                                stripe
                                max-items="30"
                                pagination
                                class="vx-col sm:w-full w-full"
                                :data="contenidotranse"
                            >
                                <!-- HEADER -->
                                <template slot="thead">
                                    <vs-th>No.</vs-th>
                                    <vs-th class="text-center"
                                        >Fecha Inicio</vs-th
                                    >
                                    <vs-th class="text-center">Fecha Fin</vs-th>
                                    <vs-th class="text-center">Estado</vs-th>
                                    <vs-th class="text-center"
                                        >Motivo Transferencia</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Bodega Receptora</vs-th
                                    >
                                    <vs-th class="text-center">Opciones</vs-th>
                                </template>
                                <!-- DATA -->
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        v-for="(pr, index) in data"
                                        :key="index"
                                    >
                                        <vs-td v-if="pr.num_trans">{{
                                            pr.num_trans
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
                                        <vs-td
                                            v-if="pr.f_iniciacion"
                                            class="text-center"
                                            >{{ pr.f_iniciacion }}</vs-td
                                        >
                                        <vs-td v-else class="text-center"
                                            >-</vs-td
                                        >
                                        <vs-td
                                            v-if="pr.f_finalizacion"
                                            class="text-center"
                                            >{{ pr.f_finalizacion }}</vs-td
                                        >
                                        <vs-td v-else class="text-center"
                                            >-</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            v-if="pr.estado"
                                            >{{ pr.estado }}</vs-td
                                        >
                                        <vs-td class="text-center" v-else
                                            >-</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            v-if="pr.motivo_trans"
                                            >{{ pr.motivo_trans }}</vs-td
                                        >
                                        <vs-td class="text-center" v-else
                                            >-</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            v-if="pr.bodega_receptor"
                                            >{{ pr.bodega_receptor }}</vs-td
                                        >
                                        <vs-td class="text-center" v-else
                                            >-</vs-td
                                        >
                                        <vs-td
                                            class="whitespace-no-wrap text-center"
                                        >
                                            <!--<vx-tooltip
                                                text="Editar Transferencia"
                                                position="top"
                                                style="display: inline-flex;"
                                            >
                                                <feather-icon
                                                    v-if="
                                                        pr.estado == 'Enviada'
                                                    "
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="pointer"
                                                    @click.stop="
                                                        edittransferenciae(
                                                            pr.id_bodega_transferencia
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>-->
                                            <vx-tooltip
                                                text="Visualizar Transferencia"
                                                position="top"
                                                style="display: inline-flex;"
                                            >
                                                <feather-icon
                                                    icon="CheckSquareIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="pointer"
                                                    @click.stop="
                                                        vertransferenciae(
                                                            pr.id_bodega_transferencia
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>
                                            <vx-tooltip
                                                text="Descargar PDF"
                                                position="top"
                                                style="display: inline-flex;"
                                            >
                                                <feather-icon
                                                    icon="FileTextIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="pointer"
                                                    @click.stop="
                                                        pdftransferencia(
                                                            pr.id_bodega_transferencia,
                                                            'emisor'
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>
                                            <vx-tooltip
                                                text="Asiento"
                                                position="top"
                                                style="display: inline-flex;"
                                            >
                                                <feather-icon
                                                    v-if="
                                                        pr.contabilidad == null
                                                    "
                                                    icon="SlidersIcon"
                                                    svgClasses="w-5 h-5 fill-current text-primary"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        Contabilidad_Transf(
                                                            pr.id_bodega_transferencia
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    v-else
                                                    icon="SlidersIcon"
                                                    svgClasses="w-5 h-5 fill-current text-success"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        Contabilidad_Transf(
                                                            pr.id_bodega_transferencia
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>
                                            <feather-icon
                                                icon="CheckIcon"
                                                v-if="pr.contabilidad !== null"
                                                svgClasses="w-5 h-5"
                                            />
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                            <!----------------------------------------------------- Popup agregar Envio de Transferencias------------------------------------------------------------------------>
                            <vs-popup
                                class="transpopup"
                                classContent="popup-example"
                                title="Envio de Transferencias"
                                :active.sync="popuptransenvio"
                            >
                                <div class="vx-row">
                                    <vs-divider position="left"
                                        >Información de
                                        Transferencia</vs-divider
                                    >
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full ml-auto mb-6"
                                    >
                                        <vs-input
                                            disabled
                                            class="w-full txt-center"
                                            label="Número de Transferencia:"
                                            v-model="num_transe"
                                        />
                                        <div v-show="error" v-if="!num_transe">
                                            <div
                                                v-for="err in errornum_transe"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </div>
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full  mb-6"
                                    >
                                        <label class="vs-input--label"
                                            >Fecha de Iniciación:</label
                                        >
                                        <flat-pickr
                                            disabled
                                            class="w-full"
                                            :config="configdateTimePicker"
                                            v-model="f_ini_transe"
                                            placeholder="Elegir Fecha"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full  mb-6"
                                    >
                                        <label class="vs-input--label"
                                            >Fecha de Finalización :</label
                                        >
                                        <flat-pickr
                                            :disabled="recuperatranse"
                                            class="w-full"
                                            :config="configdateTimePicker"
                                            v-model="f_fin_transe"
                                            placeholder="Elegir Fecha"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full mr-auto mb-6"
                                    >
                                        <vs-select
                                            :disabled="recuperatranse"
                                            placeholder="Seleccione uno"
                                            class="selectExample w-full"
                                            label="Motivo de Transferencia:"
                                            label-placeholder="Seleccione uno"
                                            vs-multiple
                                            v-model="motivo_transe"
                                        >
                                            <vs-select-item
                                                value="Abastecimiento"
                                                text="Abastecimiento"
                                            />
                                            <vs-select-item
                                                value="Cambio"
                                                text="Cambio"
                                            />
                                            <vs-select-item
                                                value="Devolución"
                                                text="Devolución"
                                            />
                                            <vs-select-item
                                                value="Traspaso"
                                                text="Traspaso"
                                            />
                                            <vs-select-item
                                                value="Otros"
                                                text="Otros"
                                            />
                                        </vs-select>
                                        <div
                                            v-show="error"
                                            v-if="!motivo_transe"
                                        >
                                            <div
                                                v-for="err in errormotivo_transe"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </div>
                                    <!-- <div
                                        class="vx-col md:w-1/5 sm:w-full w-full mb-6"
                                    >
                                        <vs-select
                                            :disabled="recuperatranse"
                                            placeholder="Seleccione Proyecto"
                                            class="selectExample w-full"
                                            label="Proyecto:"
                                            label-placeholder="Proyecto:"
                                            vs-multiple
                                            v-model="proyecto_transe"
                                        >
                                            <vs-select-item
                                                :key="res.id_proyecto"
                                                :value="res.id_proyecto"
                                                :text="res.descripcion"
                                                v-for="res in contproyect"
                                            />
                                        </vs-select>
                                    </div>-->
                                    <div
                                        class="vx-col md:w-1/2 sm:w-full w-full mb-6"
                                    >
                                        <span style="display:none">{{
                                            (emisor_transe = nombre)
                                        }}</span>
                                        <vs-input
                                            disabled
                                            class="w-full"
                                            label="Emisor de Transferencia:"
                                            v-model="emisor_transe"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-1/2 sm:w-full w-full mb-6"
                                    >
                                        <vs-select
                                            :disabled="recuperatranse"
                                            placeholder="Buscar bodega"
                                            class="selectExample w-full"
                                            label="Receptor de Transferencia:"
                                            label-placeholder="Buscar bodega"
                                            vs-multiple
                                            autocomplete
                                            v-model="receptor_transe"
                                        >
                                            <vs-select-item
                                                :key="res.id_bodega"
                                                :value="res.id_bodega"
                                                :text="res.nombre"
                                                v-for="res in contenidobodegas"
                                            />
                                        </vs-select>
                                        <div
                                            v-show="error"
                                            v-if="!receptor_transe"
                                        >
                                            <div
                                                v-for="err in errorreceptor_transe"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </div>
                                    <div
                                        class="vx-col md:w-full sm:w-full w-full mb-0"
                                    >
                                        <label class="vs-input--label"
                                            >Observaciones:</label
                                        >
                                        <vs-textarea
                                            :disabled="recuperatranse"
                                            v-model="observ_transe"
                                            rows="2"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-full sm:w-full w-full mb-6"
                                        style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                                    >
                                        <vs-checkbox
                                            :disabled="recuperatranse"
                                            icon-pack="feather"
                                            icon="icon-check"
                                            v-model="transport_transe"
                                        >
                                            <template v-if="transport_transe">
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
                                            | Registrar Datos de Guia de
                                            Remisión
                                        </vs-checkbox>
                                    </div>
                                    <template v-if="transport_transe">
                                        <vs-divider position="left"
                                            >Información de
                                            Transporte</vs-divider
                                        >
                                        <div
                                            class="vx-col md:w-1/3 sm:w-full w-full mb-6"
                                        >
                                            <vs-input
                                                :disabled="recuperatranse"
                                                class="w-full"
                                                label="Marca Vehículo:"
                                                v-model="marca_transe"
                                            />
                                            <div
                                                v-show="error"
                                                v-if="!marca_transe"
                                            >
                                                <div
                                                    v-for="err in errormarca_transe"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </div>
                                        <div
                                            class="vx-col md:w-1/3 sm:w-full w-full mb-6"
                                        >
                                            <vs-input
                                                :disabled="recuperatranse"
                                                class="w-full"
                                                label="Placa de Vehículo:"
                                                v-model="placa_transe"
                                            />
                                            <div
                                                v-show="error"
                                                v-if="!placa_transe"
                                            >
                                                <div
                                                    v-for="err in errorplaca_transe"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </div>
                                        <div
                                            class="vx-col md:w-1/3 sm:w-full w-full mb-6"
                                        >
                                            <vs-input
                                                :disabled="recuperatranse"
                                                class="w-full"
                                                label="Color de Vehículo:"
                                                v-model="color_transe"
                                            />
                                            <div
                                                v-show="error"
                                                v-if="!color_transe"
                                            >
                                                <div
                                                    v-for="err in errorcolor_transe"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </div>
                                        <div
                                            class="vx-col md:w-1/2 sm:w-full w-full ml-auto mr-auto mb-6"
                                        >
                                            <vs-input
                                                :disabled="recuperatranse"
                                                class="w-full"
                                                label="Transportado por:"
                                                v-model="sr_transe"
                                            />
                                            <div
                                                v-show="error"
                                                v-if="!sr_transe"
                                            >
                                                <div
                                                    v-for="err in errorsr_transe"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                                <!-- Inicio Agregar Producto-->
                                <vs-divider />
                                <vs-divider position="left"
                                    >Registro de Productos</vs-divider
                                >
                                <!-- INVOICE TASKS TABLE -->
                                <div class="vx-row">
                                    <div
                                        class="vx-col md:w-full sm:w-full w-full mb-6"
                                    >
                                        <a
                                            v-if="!recuperatranse"
                                            class="flex items-center cursor-pointer mb-4"
                                            @click="abrirstocktranse()"
                                            >Añadir Productos</a
                                        >
                                    </div>
                                    <div
                                        class="vx-col md:w-full sm:w-full w-full mb-6"
                                    >
                                        <vs-table
                                            hoverFlat
                                            :data="prodtranse"
                                            style="font-size: 12px;"
                                        >
                                            <!-- HEADER -->
                                            <template slot="thead">
                                                <vs-th>CÓDIGO</vs-th>
                                                <vs-th>NOMBRE</vs-th>
                                                <vs-th>PROYECTO</vs-th>
                                                <vs-th
                                                    class="text-center"
                                                    v-if="!recuperatranse"
                                                    >STOCK DISPONIBLE</vs-th
                                                >
                                                <vs-th class="text-center"
                                                    >CANTIDAD A ENVIAR</vs-th
                                                >
                                                <vs-th class="text-center"
                                                    >COSTO UNITARIO</vs-th
                                                >
                                                <vs-th class="text-center"
                                                    >COSTO TOTAL</vs-th
                                                >
                                                <vs-th class="text-center">
                                                    <!--<feather-icon disabled icon="TrashIcon" svgClasses="w-5 h-5 stroke-current" />-->
                                                </vs-th>
                                            </template>
                                            <!-- DATA -->
                                            <template slot-scope="{ data }">
                                                <vs-tr
                                                    v-for="(tr, index) in data"
                                                    :key="index"
                                                >
                                                    <vs-td
                                                        style="width:100px!important;"
                                                        v-if="tr.cod_alterno"
                                                        >{{
                                                            tr.cod_alterno
                                                        }}</vs-td
                                                    ><vs-td
                                                        style="width:100px!important;"
                                                        v-else
                                                        >{{
                                                            tr.cod_principal
                                                        }}</vs-td
                                                    >
                                                    <vs-td :data="tr.nombre">{{
                                                        tr.nombre
                                                    }}</vs-td>
                                                    <vs-td
                                                        style="width:200px!important;"
                                                    >
                                                        <vs-select
                                                            :disabled="
                                                                recuperatranse
                                                            "
                                                            class="selectExample w-full"
                                                            v-model="
                                                                tr.proyecto
                                                            "
                                                        >
                                                            <vs-select-item
                                                                :key="
                                                                    res.id_proyecto
                                                                "
                                                                :value="
                                                                    res.id_proyecto
                                                                "
                                                                :text="
                                                                    res.descripcion
                                                                "
                                                                v-for="res in contproyect"
                                                            />
                                                        </vs-select>
                                                        <div
                                                            v-show="error"
                                                            v-if="!tr.proyecto"
                                                        >
                                                            <div
                                                                v-for="err in tr.errorproyecto_transe"
                                                                :key="err"
                                                                v-text="err"
                                                                class="text-danger"
                                                            ></div>
                                                        </div>
                                                    </vs-td>
                                                    <vs-td
                                                        v-if="!recuperatranse"
                                                        class="w-full text-center"
                                                        :data="tr.catidad"
                                                        style="width:200px!important;"
                                                        >{{
                                                            tr.cantidad
                                                        }}</vs-td
                                                    >
                                                    <vs-td
                                                        :data="tr.cant_enviadae"
                                                        style="width:200px!important;"
                                                    >
                                                        <vs-input
                                                            :disabled="
                                                                recuperatranse
                                                            "
                                                            class="w-full txt-center"
                                                            v-model="
                                                                tr.cant_enviadae
                                                            "
                                                            @keypress="
                                                                solonumeros(
                                                                    $event
                                                                )
                                                            "
                                                        />
                                                        <div
                                                            v-show="error"
                                                            v-if="
                                                                !tr.cant_enviadae
                                                            "
                                                        >
                                                            <div
                                                                v-for="err in tr.errorcant_enviadae"
                                                                :key="err"
                                                                v-text="err"
                                                                class="text-danger"
                                                            ></div>
                                                        </div>
                                                    </vs-td>
                                                    <vs-td
                                                        :data="
                                                            tr.cost_unitarioe
                                                        "
                                                        style="width:200px!important;"
                                                    >
                                                        <vs-input
                                                            disabled
                                                            class="w-full txt-center"
                                                            v-model="
                                                                tr.cost_unitarioe
                                                            "
                                                        />
                                                    </vs-td>
                                                    <vs-td
                                                        :data="tr.cost_totale"
                                                        style="width:200px!important;"
                                                    >
                                                        <span
                                                            style="display:none"
                                                            >{{
                                                                (tr.cost_totale =
                                                                    tr.cant_enviadae *
                                                                    tr.cost_unitarioe)
                                                            }}</span
                                                        >
                                                        <vs-input
                                                            disabled
                                                            class="w-full txt-center"
                                                            v-model="
                                                                tr.cost_totale
                                                            "
                                                        />
                                                    </vs-td>
                                                    <vs-td
                                                        style="width:25px!important;"
                                                    >
                                                        <vx-tooltip
                                                            text="Eliminar"
                                                            position="top"
                                                            style="display: inline-flex;"
                                                        >
                                                            <feather-icon
                                                                v-if="
                                                                    !recuperatranse
                                                                "
                                                                icon="TrashIcon"
                                                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                                class="pointer"
                                                                @click="
                                                                    eliminar(
                                                                        index
                                                                    )
                                                                "
                                                            />
                                                        </vx-tooltip>
                                                    </vs-td>
                                                    <!--<vs-td :data="tr.cant_recibidae" style="width:250px!important;">
                          <vs-input
                          disabled
                            class="w-full txt-center"
                            v-model="tr.cant_recibidae"
                            @keypress="solonumeros($event)"
                          />
                        </vs-td>-->
                                                </vs-tr>
                                            </template>
                                        </vs-table>
                                    </div>
                                </div>
                                <vs-divider />
                                <div
                                    class="vx-row md:w-full sm:w-full w-full mt-4 mb-2 text-center"
                                >
                                    <div
                                        class="vx-col md:1/3 sm:1/3 w-1/3 ml-auto"
                                    >
                                        <label class="vs-input--label"
                                            >TOTAL COSTOS UNITARIOS</label
                                        >
                                        <h1>
                                            $
                                            {{
                                                totales_transe.costos_unitarios.toFixed(
                                                    2
                                                )
                                            }}
                                        </h1>
                                    </div>
                                    <div
                                        class="vx-col md:1/3 sm:1/3 w-1/3 mr-auto"
                                    >
                                        <label class="vs-input--label"
                                            >TOTAL COSTOS TOTALES</label
                                        >
                                        <h1>
                                            $
                                            {{
                                                totales_transe.costos_totales.toFixed(
                                                    2
                                                )
                                            }}
                                        </h1>
                                    </div>
                                </div>
                                <!-- Botones-->
                                <div class="vx-row" v-if="!recuperatranse">
                                    <div
                                        class="vx-col w-1/3 ml-auto mt-6 text-center"
                                    >
                                        <vs-button
                                            color="success"
                                            type="filled"
                                            @click="guardarbodegatranse()"
                                            >Guardar</vs-button
                                        >
                                    </div>
                                    <div
                                        class="vx-col w-1/3 mr-auto mt-6 text-center"
                                    >
                                        <vs-button
                                            color="danger"
                                            type="filled"
                                            @click="cancelartranse()"
                                            >Cancelar</vs-button
                                        >
                                    </div>
                                </div>
                                <div class="vx-row" v-else>
                                    <div
                                        class="vx-col w-1/1 mr-auto ml-auto mt-6 text-center"
                                    >
                                        <vs-button
                                            color="danger"
                                            type="filled"
                                            @click="cancelartranse()"
                                            >Cerrar</vs-button
                                        >
                                    </div>
                                </div>
                                <!---------------------------------------------------------- Popup Stock para Envio de Trasnferencias--------------------------------------------------->
                                <vs-popup
                                    class="listpopup"
                                    classContent="popup-example"
                                    title="Seleccione el Producto"
                                    :active.sync="popuptransenvioprod"
                                >
                                    <div class="vx-col w-full">
                                        <vs-input
                                            class="mb-4 mr-4 w-full"
                                            v-model="buscarstocktranse"
                                            @keyup="
                                                listarstocktranse(
                                                    buscarstocktranse
                                                )
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <vs-table
                                            stripe
                                            max-items="5"
                                            pagination
                                            class="vx-col sm:w-full w-full"
                                            v-model="cuentastranse"
                                            @selected="handleSelectedtranse"
                                            :data="contenidoprodtranse"
                                            style="font-size: 12px;"
                                        >
                                            <!-- HEADER -->

                                            <template slot="thead">
                                                <vs-th>CÓDIGO</vs-th>
                                                <vs-th>NOMBRE</vs-th>
                                                <vs-th>DESCRIPCIÓN</vs-th>
                                                <vs-th>CANTIDAD</vs-th>
                                            </template>
                                            <!-- DATA -->
                                            <template slot-scope="{ data }">
                                                <vs-tr
                                                    :data="pr"
                                                    v-for="(pr, index) in data"
                                                    :key="index"
                                                >
                                                    <vs-td
                                                        v-if="pr.cod_alterno"
                                                        >{{
                                                            pr.cod_alterno
                                                        }}</vs-td
                                                    ><vs-td v-else>{{
                                                        pr.cod_principal
                                                    }}</vs-td>
                                                    <vs-td v-if="pr.nombrep">{{
                                                        pr.nombrep
                                                    }}</vs-td>
                                                    <vs-td v-else>-</vs-td>
                                                    <vs-td
                                                        v-if="pr.descripcion"
                                                        >{{
                                                            pr.descripcion
                                                        }}</vs-td
                                                    >
                                                    <vs-td v-else>-</vs-td>
                                                    <vs-td v-if="pr.cantidad">{{
                                                        pr.cantidad
                                                    }}</vs-td>
                                                    <vs-td v-else>-</vs-td>
                                                </vs-tr>
                                            </template>
                                        </vs-table>
                                    </div>
                                </vs-popup>
                            </vs-popup>
                        </vs-tab>
                        <!--------------------------------------------------- Recepcion de Transferencias--------------------------------------------------------->
                        <vs-tab
                            v-if="vertransrrol"
                            class="vx-col"
                            label="Recepción de Transferencias "
                            @click="firstlisttransr()"
                        >
                            <div
                                class="flex flex-wrap justify-between items-center mb-3"
                            >
                                <div
                                    class="flex flex-wrap justify-between items-center ag-grid-table-actions-left"
                                >
                                    <template
                                        v-if="filterstabletransr.filtertab"
                                    >
                                        <label
                                            style="position: absolute; top: 0.2%; left: 0.4%;"
                                            ><b>Mostrar:</b></label
                                        >
                                        <div
                                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                        >
                                            <label class="vs-input--label mr-1"
                                                >Contabilizados</label
                                            >
                                            <vs-switch
                                                color="success"
                                                @input="filtertablatransr()"
                                                v-model="
                                                    filterstabletransr.filt_asientos
                                                "
                                            />
                                        </div>
                                        <div
                                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                                        >
                                            <label class="vs-input--label mr-1"
                                                >No-contabilizados</label
                                            >
                                            <vs-switch
                                                color="success"
                                                @input="filtertablatransr()"
                                                v-model="
                                                    filterstabletransr.filt_noasientos
                                                "
                                            />
                                        </div>
                                    </template>
                                </div>
                                <div
                                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                                >
                                    <vs-input
                                        class="mb-4 md:mb-0 mr-4"
                                        v-model="buscartransr"
                                        @keyup="
                                            listartransrbodega(buscartransr)
                                        "
                                        v-bind:placeholder="i18nbuscar"
                                    />
                                    <div
                                        class="flex flex-wrap justify-between items-center mr-1 ml-1"
                                    >
                                        <vx-tooltip
                                            text="Filtrar"
                                            position="top"
                                            style="display: inline-flex;"
                                        >
                                            <vs-button
                                                v-if="
                                                    filterstabletransr.filtertab ==
                                                        false
                                                "
                                                color="primary"
                                                type="filled"
                                                icon="filter_list"
                                                @click="
                                                    filterstabletransr.filtertab = true
                                                "
                                            ></vs-button>
                                            <vs-button
                                                v-else
                                                color="success"
                                                type="filled"
                                                icon="filter_list"
                                                @click="
                                                    (filterstabletransr.filtertab = false),
                                                        filtertablatransr()
                                                "
                                            ></vs-button
                                        ></vx-tooltip>
                                    </div>
                                </div>
                            </div>
                            <vs-table
                                stripe
                                max-items="30"
                                pagination
                                class="vx-col sm:w-full w-full"
                                :data="contenidotransr"
                            >
                                <!-- HEADER -->
                                <template slot="thead">
                                    <vs-th>No.</vs-th>
                                    <vs-th class="text-center"
                                        >Fecha Inicio</vs-th
                                    >
                                    <vs-th class="text-center">Fecha Fin</vs-th>
                                    <vs-th class="text-center">Estado</vs-th>
                                    <vs-th class="text-center"
                                        >Motivo Transferencia</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Bodega Emisora</vs-th
                                    >
                                    <vs-th class="text-center">Opciones</vs-th>
                                </template>
                                <!-- DATA -->
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        v-for="(pr, index) in data"
                                        :key="index"
                                    >
                                        <vs-td v-if="pr.num_trans">{{
                                            pr.num_trans
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
                                        <vs-td
                                            v-if="pr.f_iniciacion"
                                            class="text-center"
                                            >{{ pr.f_iniciacion }}</vs-td
                                        >
                                        <vs-td v-else class="text-center"
                                            >-</vs-td
                                        >
                                        <vs-td
                                            v-if="pr.f_finalizacion"
                                            class="text-center"
                                            >{{ pr.f_finalizacion }}</vs-td
                                        >
                                        <vs-td v-else class="text-center"
                                            >-</vs-td
                                        >
                                        <vs-td
                                            v-if="pr.estado"
                                            class="text-center"
                                            >{{ pr.estado }}</vs-td
                                        >
                                        <vs-td v-else class="text-center"
                                            >-</vs-td
                                        >
                                        <vs-td
                                            v-if="pr.motivo_trans"
                                            class="text-center"
                                            >{{ pr.motivo_trans }}</vs-td
                                        >
                                        <vs-td v-else class="text-center"
                                            >-</vs-td
                                        >
                                        <vs-td
                                            v-if="pr.bodega_emisor"
                                            class="text-center"
                                            >{{ pr.bodega_emisor }}</vs-td
                                        >
                                        <vs-td v-else class="text-center"
                                            >-</vs-td
                                        >
                                        <vs-td
                                            class="whitespace-no-wrap text-center"
                                        >
                                            <vx-tooltip
                                                v-if="creartransrrol"
                                                text="Receptar Transferencia"
                                                position="top"
                                                style="display: inline-flex;"
                                            >
                                                <feather-icon
                                                    icon="ClipboardIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="pointer"
                                                    @click.stop="
                                                        vertransferenciar(
                                                            pr.id_bodega_transferencia
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>

                                            <vx-tooltip
                                                text="Descargar PDF"
                                                position="top"
                                                style="display: inline-flex;"
                                            >
                                                <feather-icon
                                                    icon="FileTextIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="pointer"
                                                    @click.stop="
                                                        pdftransferencia(
                                                            pr.id_bodega_transferencia,
                                                            'receptor'
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>
                                            <vx-tooltip
                                                text="Asiento"
                                                position="top"
                                                style="display: inline-flex;"
                                            >
                                                <feather-icon
                                                    v-if="
                                                        pr.contabilidad == null
                                                    "
                                                    icon="SlidersIcon"
                                                    svgClasses="w-5 h-5 fill-current text-primary"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        Contabilidad_Transf_Rec(
                                                            pr.id_bodega_transferencia
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    v-else
                                                    icon="SlidersIcon"
                                                    svgClasses="w-5 h-5 fill-current text-success"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        Contabilidad_Transf_Rec(
                                                            pr.id_bodega_transferencia
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>
                                            <feather-icon
                                                icon="CheckIcon"
                                                v-if="pr.contabilidad !== null"
                                                svgClasses="w-5 h-5"
                                            />
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                            <!--------------------------------- Popup Visualizar Recepcion de Trasnferencias-------------------------------------->
                            <vs-popup
                                class="transpopup"
                                classContent="popup-example"
                                title="Seleccione el Producto"
                                :active.sync="popuptransrecib"
                            >
                                <div class="vx-row">
                                    <vs-divider position="left"
                                        >Información de
                                        Transferencia</vs-divider
                                    >
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full ml-auto mb-6"
                                    >
                                        <vs-input
                                            disabled
                                            class="w-full txt-center"
                                            label="Número de Transferencia:"
                                            v-model="num_transr"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full mr-auto mb-6"
                                    >
                                        <label class="vs-input--label"
                                            >Fecha de Iniciación:</label
                                        >
                                        <flat-pickr
                                            disabled
                                            class="w-full"
                                            :config="configdateTimePicker"
                                            v-model="f_ini_transr"
                                            placeholder="Elegir Fecha"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full mb-6"
                                    >
                                        <label class="vs-input--label"
                                            >Fecha de Finalización :</label
                                        >
                                        <flat-pickr
                                            disabled
                                            class="w-full"
                                            :config="configdateTimePicker"
                                            v-model="f_fin_transr"
                                            placeholder="Elegir Fecha"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-1/5 sm:w-full w-full mr-auto mb-6"
                                    >
                                        <vs-select
                                            disabled
                                            placeholder="Seleccione uno"
                                            class="selectExample w-full"
                                            label="Motivo de Transferencia:"
                                            label-placeholder="Seleccione uno"
                                            vs-multiple
                                            v-model="motivo_transr"
                                        >
                                            <vs-select-item
                                                value="Ventas"
                                                text="Ventas"
                                            />
                                            <vs-select-item
                                                value="Auto Consumo"
                                                text="Auto Consumo"
                                            />
                                            <vs-select-item
                                                value="Egreso por Ajuste"
                                                text="Egreso por Ajuste"
                                            />
                                            <vs-select-item
                                                value="Devolucion por Ingreso"
                                                text="Devolucion por Ingreso"
                                            />
                                        </vs-select>
                                    </div>
                                    <!--<div
                                        class="vx-col md:w-1/5 sm:w-full w-full mb-6"
                                    >
                                        <vs-select
                                            disabled
                                            placeholder=""
                                            class="selectExample w-full"
                                            label="Proyecto:"
                                            label-placeholder="Proyecto:"
                                            vs-multiple
                                            v-model="proyecto_transr"
                                        >
                                            <vs-select-item
                                                :key="res.id_proyecto"
                                                :value="res.id_proyecto"
                                                :text="res.descripcion"
                                                v-for="res in contproyect"
                                            />
                                        </vs-select>
                                    </div>-->
                                    <div
                                        class="vx-col md:w-1/2 sm:w-full w-full mb-6"
                                    >
                                        <vs-select
                                            disabled
                                            placeholder="Bodega emisora"
                                            class="w-full"
                                            label="Emisor de Transferencia:"
                                            label-placeholder
                                            vs-multiple
                                            autocomplete
                                            v-model="emisor_transr"
                                        >
                                            <vs-select-item
                                                :key="res.id_bodega"
                                                :value="res.id_bodega"
                                                :text="res.nombre"
                                                v-for="res in contenidobodegas"
                                            />
                                        </vs-select>
                                    </div>
                                    <div
                                        class="vx-col md:w-1/2 sm:w-full w-full mb-6"
                                    >
                                        <vs-select
                                            disabled
                                            placeholder="Bodega receptora"
                                            class="w-full"
                                            label="Receptor de Transferencia:"
                                            label-placeholder
                                            vs-multiple
                                            autocomplete
                                            v-model="receptor_transr"
                                        >
                                        </vs-select>
                                    </div>
                                    <div
                                        class="vx-col md:w-full sm:w-full w-full mb-0"
                                    >
                                        <label class="vs-input--label"
                                            >Observaciones:</label
                                        >
                                        <vs-textarea
                                            disabled
                                            v-model="observ_transr"
                                            rows="2"
                                        />
                                    </div>
                                    <div
                                        class="vx-col md:w-full sm:w-full w-full mb-6"
                                        style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                                    >
                                        <vs-checkbox
                                            disabled
                                            icon-pack="feather"
                                            icon="icon-check"
                                            v-model="transport_transr"
                                        >
                                            <template v-if="transport_transr">
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
                                            | Registrar Datos de Transporte
                                        </vs-checkbox>
                                    </div>
                                    <template v-if="transport_transr">
                                        <vs-divider position="left"
                                            >Información de
                                            Transporte</vs-divider
                                        >
                                        <div
                                            class="vx-col md:w-1/3 sm:w-full w-full mb-6"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full"
                                                label="Marca Vehículo:"
                                                v-model="marca_transr"
                                            />
                                        </div>
                                        <div
                                            class="vx-col md:w-1/3 sm:w-full w-full mb-6"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full"
                                                label="Placa de Vehículo:"
                                                v-model="placa_transr"
                                            />
                                        </div>
                                        <div
                                            class="vx-col md:w-1/3 sm:w-full w-full mb-6"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full"
                                                label="Color de Vehículo:"
                                                v-model="color_transr"
                                            />
                                        </div>
                                        <div
                                            class="vx-col md:w-1/2 sm:w-full w-full ml-auto mr-auto mb-6"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full"
                                                label="Transportado por:"
                                                v-model="sr_transr"
                                            />
                                        </div>
                                    </template>
                                </div>
                                <!-- Inicio Agregar Producto-->
                                <vs-divider />
                                <vs-divider position="left"
                                    >Registro de Productos</vs-divider
                                >
                                <div
                                    class="divider md:w-1/2 sm:w-full w-full mb-6"
                                >
                                    Productos Transferidos
                                </div>
                                <vs-table
                                    hoverFlat
                                    :data="contenidoproductostr"
                                    style="font-size: 12px;"
                                >
                                    <!-- HEADER -->
                                    <template slot="thead">
                                        <vs-th>Cod.</vs-th>
                                        <vs-th class="text-center"
                                            >Nombre</vs-th
                                        >
                                        <vs-th class="text-center"
                                            >Proyecto</vs-th
                                        >
                                        <vs-th class="text-center"
                                            >Cant Enviada</vs-th
                                        >
                                        <vs-th class="text-center"
                                            >Cant Recibida</vs-th
                                        >
                                        <vs-th
                                            v-if="editartotales != 0"
                                            class="text-center"
                                            >Cant Pendiente</vs-th
                                        >
                                        <vs-th
                                            v-if="editartotales != 0"
                                            class="text-center"
                                            >Nueva Cant Recibida</vs-th
                                        >
                                        <vs-th class="text-center"
                                            >Cost Unitario</vs-th
                                        >
                                        <vs-th class="text-center"
                                            >Cost Total</vs-th
                                        >
                                    </template>
                                    <!-- DATA -->
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            v-for="(tr, index) in data"
                                            :key="index"
                                        >
                                            <vs-td
                                                style="width:5%;"
                                                v-if="tr.cod_alterno"
                                                >{{ tr.cod_alterno }}</vs-td
                                            ><vs-td style="width:5%;" v-else>{{
                                                tr.cod_principal
                                            }}</vs-td>
                                            <vs-td
                                                class="text-center"
                                                style="width:20%;"
                                                >{{ tr.nombre }}</vs-td
                                            >
                                            <vs-td style="width:15%;">
                                                <vs-select
                                                    disabled
                                                    class="selectExample w-full"
                                                    v-model="tr.proyecto"
                                                >
                                                    <vs-select-item
                                                        :key="res.id_proyecto"
                                                        :value="res.id_proyecto"
                                                        :text="res.descripcion"
                                                        v-for="res in contproyect"
                                                    />
                                                </vs-select>
                                            </vs-td>
                                            <vs-td style="width:10%;">
                                                <vs-input
                                                    disabled
                                                    class="w-full txt-center"
                                                    v-model="tr.cant_env"
                                                />
                                            </vs-td>
                                            <vs-td style="width:10%;">
                                                <vs-input
                                                    disabled
                                                    class="w-full txt-center"
                                                    v-model="tr.cant_recib"
                                                />
                                            </vs-td>
                                            <vs-td
                                                style="width:10%;"
                                                v-if="editartotales != 0"
                                            >
                                                <vs-input
                                                    disabled
                                                    class="w-full txt-center"
                                                    v-model="tr.cant_rest"
                                                />
                                            </vs-td>
                                            <vs-td
                                                style="width:10%;"
                                                v-if="editartotales != 0"
                                            >
                                                <vs-input
                                                    :disabled="
                                                        tr.podereditar == 0
                                                    "
                                                    class="w-full txt-center"
                                                    v-model="tr.cant_new"
                                                    @keypress="
                                                        solonumeros($event)
                                                    "
                                                />
                                                <div
                                                    v-show="error"
                                                    v-if="!tr.cant_new"
                                                >
                                                    <div
                                                        v-for="err in tr.errorcant_recib"
                                                        :key="err"
                                                        v-text="err"
                                                        class="text-danger"
                                                    ></div>
                                                </div>
                                            </vs-td>
                                            <vs-td
                                                :data="tr.cost_unitarior"
                                                style="width:10%;!important;"
                                            >
                                                <vs-input
                                                    disabled
                                                    class="w-full txt-center"
                                                    v-model="tr.cost_unitarior"
                                                />
                                            </vs-td>
                                            <vs-td
                                                v-if="tr.podereditar == 1"
                                                :data="tr.cost_totalr"
                                                style="width:10%!important;"
                                            >
                                                <span style="display:none">{{
                                                    (tr.cost_totalr =
                                                        tr.cant_new *
                                                        tr.cost_unitarior)
                                                }}</span>
                                                <vs-input
                                                    disabled
                                                    class="w-full txt-center"
                                                    v-model="tr.cost_totalr"
                                                />
                                            </vs-td>
                                            <vs-td
                                                v-else
                                                :data="tr.cost_totalr"
                                                style="width:10%!important;"
                                            >
                                                <span style="display:none">{{
                                                    (tr.cost_totalr =
                                                        tr.cant_recib *
                                                        tr.cost_unitarior)
                                                }}</span>
                                                <vs-input
                                                    disabled
                                                    class="w-full txt-center"
                                                    v-model="tr.cost_totalr"
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                                <vs-divider />
                                <div
                                    class="vx-row md:w-full sm:w-full w-full mt-4 mb-2 text-center"
                                >
                                    <div
                                        class="vx-col md:1/3 sm:1/3 w-1/3 ml-auto"
                                    >
                                        <label class="vs-input--label"
                                            >TOTAL COSTOS UNITARIOS</label
                                        >
                                        <h1>
                                            $
                                            {{
                                                totales_transr.costos_unitarios.toFixed(
                                                    2
                                                )
                                            }}
                                        </h1>
                                    </div>
                                    <div
                                        class="vx-col md:1/3 sm:1/3 w-1/3 mr-auto"
                                    >
                                        <label class="vs-input--label"
                                            >TOTAL COSTOS TOTALES</label
                                        >
                                        <h1>
                                            $
                                            {{
                                                totales_transr.costos_totales.toFixed(
                                                    2
                                                )
                                            }}
                                        </h1>
                                    </div>
                                </div>
                                <!-- Botones-->
                                <div class="vx-row" v-if="editartotales != 0">
                                    <div
                                        class="vx-col w-1/3 ml-auto mt-6 text-center"
                                    >
                                        <vs-button
                                            color="success"
                                            type="filled"
                                            @click="guardarbodegatransr()"
                                            >Guardar</vs-button
                                        >
                                    </div>
                                    <div
                                        class="vx-col w-1/3 mr-auto mt-6 text-center"
                                    >
                                        <vs-button
                                            color="danger"
                                            type="filled"
                                            @click="cancelartransr()"
                                            >Cancelar</vs-button
                                        >
                                    </div>
                                </div>
                                <div class="vx-row" v-else>
                                    <div
                                        class="vx-col w-full mr-auto mt-6 text-center"
                                    >
                                        <vs-button
                                            color="danger"
                                            type="filled"
                                            @click="cancelartransr()"
                                            >Cerrar</vs-button
                                        >
                                    </div>
                                </div>
                            </vs-popup>
                        </vs-tab>
                    </vs-tabs>
                </vs-tab>
            </vs-tabs>
        </div>
        <vs-popup
            title="Asiento Contable"
            :class="'peque4'"
            :active.sync="modalAsiento"
        >
            <div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <vs-input
                        class="w-full"
                        label="Número:"
                        :disabled="true"
                        v-model="codigo_asiento_comp"
                    />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                    <label class="vs-input--label">Proyecto:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="nombre_proyecto"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <label class="vs-input--label">Fecha:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="fecha_rol" disabled />
                    </vx-input-group>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label class="vs-input--label">Razon Social:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="razon_social"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Tipo Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="tipo_identificacion"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="ruc_empresa"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-4/11 w-full mb-6">
                    <label class="vs-input--label">Concepto:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="concepto" disabled />
                    </vx-input-group>
                </div>
            </div>
            <h4 style="color: #636363; display:flex; align-items: center;">
                <span>Detalle</span>
            </h4>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Cuenta Contable</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Proyecto</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc">Debe</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Haber</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            {{ cambioDecimales }}
            {{ sumar_iguales }}
            <!--{{igualar}}-->
            <div
                id="one-row"
                class="vx-row"
                v-for="data in bodegas_eg_fact"
                v-bind:key="data.id_bodega_egreso_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta"
                                        disabled
                                    />
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/6 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full valores"
                                    v-model="data.debe"
                                    disabled
                                />
                            </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    v-model="data.haber"
                                    disabled

                                />
                            </div>
                        <!--<div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                                    color="success"
                                    @click="agregarcampoConciliacion(index,'cliente')"
                                    >C</vs-button
                                >
                            </div>-->
                    </div>
                </div>
            </div>

            <div
                id="two-row"
                class="vx-row"
                v-for="data in cuentas_eg_fact"
                :key="data.id_bodega_egreso_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="data.debe > 0"
                        >
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                            class="w-full"
                                            v-model="data.nombre_cuenta"
                                            disabled
                                        />
                            </vx-input-group>
                        </div>
                        <div
                            class="vx-col sm:w-1/6 w-full mb-6"
                            v-if="data.debe > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                                    <vs-input
                                        class="w-full valores"
                                        v-model="data.debe"
                                        disabled
                                    />
                                </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                                    <vs-input
                                        class="w-full"
                                        v-model="data.haber"
                                        disabled

                                    />
                                </div>
                    </div>
                </div>
            </div>

            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        {{ suma_debe }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_debe"
                                disabled
                            />
                        </div>
                        {{ suma_haber }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            {{ Diferencia }}
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_debe"
                                disabled
                            />
                        </div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="contabilizado !== null">
                <h5>Este asiento ya ha sido registrado</h5>
            </div>
            <div v-else>
                <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento_EgFact"
                    @click="crearasiento_EgFact(id_factura)"
                    >GUARDAR</vs-button
                >
            </div>
        </vs-popup>
        <vs-popup
            title="Asiento Contable"
            :class="'peque4'"
            :active.sync="modalAsiento_Eg_FAc"
        >
            <div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <vs-input
                        class="w-full"
                        label="Número:"
                        :disabled="true"
                        v-model="codigo_asiento_comp"
                    />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                    <label class="vs-input--label">Proyecto:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="nombre_proyecto"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <label class="vs-input--label">Fecha:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="fecha_rol" disabled />
                    </vx-input-group>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label class="vs-input--label">Razon Social:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="razon_social"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Tipo Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="tipo_identificacion"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="ruc_empresa"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-4/11 w-full mb-6">
                    <label class="vs-input--label">Concepto:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="concepto" disabled />
                    </vx-input-group>
                </div>
            </div>
            <h4 style="color: #636363; display:flex; align-items: center;">
                <span>Detalle</span>
            </h4>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Cuenta Contable</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Proyecto</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc">Debe</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Haber</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            {{ cambioDecimales }}
            {{ sumar_iguales }}
            <!--{{igualar}}-->
            <div
                id="one-row"
                class="vx-row"
                v-for="data in bodegas_egreso"
                v-bind:key="data.id_bodega_egreso_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta"
                                        disabled
                                    />
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/6 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full valores"
                                    v-model="data.debe"
                                    disabled
                                />
                            </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    v-model="data.haber"
                                    disabled

                                />
                            </div>
                        <!--<div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                                    color="success"
                                    @click="agregarcampoConciliacion(index,'cliente')"
                                    >C</vs-button
                                >
                            </div>-->
                    </div>
                </div>
            </div>

            <div
                id="two-row"
                class="vx-row"
                v-for="data in cuentas_egreso"
                :key="data.id_bodega_egreso_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="data.debe > 0"
                        >
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta"
                                    disabled
                                />
                            </vx-input-group>
                        </div>
                        <div
                            class="vx-col sm:w-1/6 w-full mb-6"
                            v-if="data.debe > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                            <vs-input
                                class="w-full valores"
                                v-model="data.debe"
                                disabled
                            />
                        </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.debe>0">
                            <vs-input
                                class="w-full"
                                v-model="data.haber"
                                disabled

                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        {{ suma_debe }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_debe"
                                disabled
                            />
                        </div>
                        {{ suma_haber }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            {{ Diferencia }}
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_debe"
                                disabled
                            />
                        </div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="contabilizado !== null">
                <h5>Este asiento ya ha sido registrado</h5>
            </div>
            <div v-else>
                <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento_Egreso"
                    @click="crearasiento_Egreso(id_factura)"
                    >GUARDAR</vs-button
                >
            </div>
        </vs-popup>
        <vs-popup
            title="Asiento Contable"
            :class="'peque4'"
            :active.sync="modalAsiento_Ing"
        >
            <div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <vs-input
                        class="w-full"
                        label="Número:"
                        :disabled="true"
                        v-model="codigo_asiento_comp"
                    />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                    <label class="vs-input--label">Proyecto:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="nombre_proyecto"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <label class="vs-input--label">Fecha:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="fecha_rol" disabled />
                    </vx-input-group>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label class="vs-input--label">Razon Social:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="razon_social"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Tipo Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="tipo_identificacion"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="ruc_empresa"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-4/11 w-full mb-6">
                    <label class="vs-input--label">Concepto:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="concepto" disabled />
                    </vx-input-group>
                </div>
            </div>
            <h4 style="color: #636363; display:flex; align-items: center;">
                <span>Detalle</span>
            </h4>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Cuenta Contable</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Proyecto</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc">Debe</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Haber</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            {{ cambioDecimales }}
            {{ sumar_iguales }}
            <!--{{igualar}}-->
            <div
                id="one-row"
                class="vx-row"
                v-for="data in bodegas_ingreso"
                v-bind:key="data.id_bodega_ingreso_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta"
                                        disabled
                                    />
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/6 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full valores"
                                    v-model="data.debe"
                                    disabled
                                />
                            </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    v-model="data.haber"
                                    disabled

                                />
                            </div>
                        <!--<div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                                    color="success"
                                    @click="agregarcampoConciliacion(index,'cliente')"
                                    >C</vs-button
                                >
                            </div>-->
                    </div>
                </div>
            </div>

            <div
                id="two-row"
                class="vx-row"
                v-for="data in cuentas_ingreso"
                :key="data.id_bodega_ingreso_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="data.haber > 0"
                        >
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta"
                                    disabled
                                />
                            </vx-input-group>
                        </div>
                        <div
                            class="vx-col sm:w-1/6 w-full mb-6"
                            v-if="data.haber > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                            <vs-input
                                class="w-full valores"
                                v-model="data.debe"
                                disabled
                            />
                        </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                            <vs-input
                                class="w-full"
                                v-model="data.haber"
                                disabled

                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        {{ suma_debe }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_debe"
                                disabled
                            />
                        </div>
                        {{ suma_haber }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            {{ Diferencia }}
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_debe"
                                disabled
                            />
                        </div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="contabilizado !== null">
                <h5>Este asiento ya ha sido registrado</h5>
            </div>
            <div v-else>
                <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento_Ingreso"
                    @click="crearasiento_Ingreso(id_factura)"
                    >GUARDAR</vs-button
                >
            </div>
        </vs-popup>
        <vs-popup
            title="Asiento Contable"
            :class="'peque4'"
            :active.sync="modalAsiento_Trans"
        >
            <div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <vs-input
                        class="w-full"
                        label="Número:"
                        :disabled="true"
                        v-model="codigo_asiento_comp"
                    />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" hidden>
                    <label class="vs-input--label">Proyecto:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="nombre_proyecto"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <label class="vs-input--label">Fecha:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="fecha_rol" disabled />
                    </vx-input-group>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label class="vs-input--label">Razon Social:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="razon_social"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Tipo Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="tipo_identificacion"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label class="vs-input--label">Identificacion:</label>
                    <vx-input-group>
                        <vs-input
                            class="w-full"
                            v-model="ruc_empresa"
                            disabled
                        />
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-4/11 w-full mb-6">
                    <label class="vs-input--label">Concepto:</label>
                    <vx-input-group>
                        <vs-input class="w-full" v-model="concepto" disabled />
                    </vx-input-group>
                </div>
            </div>
            <h4 style="color: #636363; display:flex; align-items: center;">
                <span>Detalle</span>
            </h4>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Cuenta Contable</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Proyecto</label
                            >
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc">Debe</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label valoresc"
                                >Haber</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            {{ cambioDecimales }}
            {{ sumar_iguales }}
            <!--{{igualar}}-->
            <div
                id="one-row"
                class="vx-row"
                v-for="data in bodegas_trans"
                v-bind:key="data.id_bodega_transferencia_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta"
                                        disabled
                                    />
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/6 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full valores"
                                    v-model="data.debe"
                                    disabled
                                />
                            </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    v-model="data.haber"
                                    disabled

                                />
                            </div>
                        <!--<div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                                    color="success"
                                    @click="agregarcampoConciliacion(index,'cliente')"
                                    >C</vs-button
                                >
                            </div>-->
                    </div>
                </div>
            </div>

            <div
                id="two-row"
                class="vx-row"
                v-for="data in cuentas_trans"
                :key="data.id_bodega_transferencia_detalle"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="data.haber > 0"
                        >
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                    class="w-full"
                                    v-model="data.nombre_cuenta"
                                    disabled
                                />
                            </vx-input-group>
                        </div>
                        <div
                            class="vx-col sm:w-1/6 w-full mb-6"
                            v-if="data.haber > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="data.descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                            <vs-input
                                class="w-full valores"
                                v-model="data.debe"
                                disabled
                            />
                        </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6" v-if="data.haber>0">
                            <vs-input
                                class="w-full"
                                v-model="data.haber"
                                disabled

                            />
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <label class="vs-input--label center">Total</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        {{ suma_debe }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_debe"
                                disabled
                            />
                        </div>
                        {{ suma_haber }}
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                            <vs-input
                                class="w-full"
                                v-model="total_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            {{ Diferencia }}
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <label class="vs-input--label center"
                                >Diferencia</label
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_debe > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_debe"
                                disabled
                            />
                        </div>

                        <div
                            class="vx-col sm:w-2/12 w-full mb-6"
                            v-if="diferencia_haber > 0"
                        >
                            <vs-input
                                class="w-full"
                                v-model="diferencia_haber"
                                disabled
                            />
                        </div>
                    </div>
                </div>
            </div>
            <div v-if="contabilizado !== null">
                <h5>Este asiento ya ha sido registrado</h5>
            </div>
            <div v-else>
                <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento_Trans"
                    @click="crearasiento_Trans(id_factura, tipo_trans)"
                    >GUARDAR</vs-button
                >
            </div>
        </vs-popup>
    </vx-card>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import VueUploadMultipleImage from "vue-upload-multiple-image";
import moment from "moment";
import { AgGridVue } from "ag-grid-vue";
import vSelect from "vue-select";
const axios = require("axios");
const $ = require("jquery");
export default {
    data() {
        return {
            //establece calendario español
            configdateTimePicker: {
                locale: SpanishLocale
            },
            //buscador tiempo
            timeout: null,
            //variables bodega
            id_bodega: null,
            codigo: "",
            nombre: "",
            codigost: "",
            nombrest: "",
            responsable: "",
            ubicacion: "",
            direccion: "",
            telefono: "",

            //variables ingreso de bodega
            num_ingreso: "",
            fecha_ingreso: moment().format("YYYY-M-D"),
            proyecto_ingreso: "",
            tipo_ingreso: "",
            observ_ingreso: "",
            id_producto: "",
            id_bodega: "",
            cant_ingreso: "",
            cost_unit_ingreso: "",
            cost_tot_ingreso: "",

            //variables egreso de bodega
            num_egreso: "",
            fecha_egreso: moment().format("YYYY-M-D"),
            proyecto_egreso: "",
            tipo_egreso: "",
            observ_egreso: "",
            cod_principal: "",
            prod_egreso: "",
            cant_egreso: "",
            cost_unit_egreso: "",
            cost_tot_egreso: "",

            //variables transferenciae
            num_transe: "",
            f_ini_transe: moment().format("YYYY-M-D"),
            f_fin_transe: moment().format("YYYY-M-D"),
            motivo_transe: "",
            proyecto_transe: "",
            emisor_transe: "",
            receptor_transe: "",
            observ_transe: "",
            transport_transe: false,
            marca_transe: "",
            placa_transe: "",
            color_transe: "",
            sr_transe: "",
            cant_enviadae: "",
            cant_recibidae: "",
            //variabes trasferenciar
            id_trasnferenciar: "",
            num_transr: "",
            f_ini_transr: "",
            f_fin_transr: "",
            motivo_transr: "",
            proyecto_transr: "",
            emisor_transr: "",
            receptor_transr: "",
            observ_transr: "",
            transport_transr: "",
            marca_transr: "",
            placa_transr: "",
            color_transr: "",
            sr_transr: "",
            cant_env: "",
            cant_recib: "",
            cant_new: "",
            cost_unitarior: "",
            cost_totalr: "",

            //variables recuperacion
            idrecupera: null,

            //popups
            popupingreso: false,
            popupegreso: false,
            popupprod: false,
            popupstock: false,
            popuptransenvio: false,
            popuptransenvioprod: false,
            popuptransrecib: false,
            /*  Bodegas*/
            contenidobodegas: [],
            /*     arrays   */
            contenidop: [],
            cuentaarrayp: [],
            cuentastock: [],
            contenidoingreso: [],
            contenidoegreso: [],
            contenidotranse: [],
            cuentastranse: [],
            contenidopr: [],
            contenidostock: [],
            prodtranse: [],
            contenidotransr: [],
            contenidoproductostr: [],

            stockarray: [],
            arraynumtrans: [],
            stockarrayegreso: [],
            contenidoprodtranse: [],
            //Proyectos
            contproyect: [],

            buscarp: "",
            buscaringreso: "",
            buscaregreso: "",
            buscartranse: "",
            buscartransr: "",
            buscarstock: "",
            buscarstocktranse: "",
            i18nbuscar: this.$t("i18nbuscar"),
            //tabla producto

            //variables control de vista
            recuperaingreso: false,
            recuperaegreso: false,
            recuperatranse: false,
            recuperatransr: false,
            /*  Variables error validacion*/
            error: 0,
            errornum_ingreso: [],
            errortipo_ingreso: [],
            errorproyecto_ingreso: [],
            errorcant_ingreso: [],
            errorcost_unit_ingreso: [],

            errornum_egreso: [],
            errortipo_egreso: [],
            errorproyecto_egreso: [],
            errorcant_egreso: [],
            errorcost_egreso: [],
            errorcost_tot_egreso: [],

            errornum_transe: [],
            errormotivo_transe: [],
            errorreceptor_transe: [],
            errormarca_transe: [],
            errorplaca_transe: [],
            errorcolor_transe: [],
            errorsr_transe: [],
            errorprodtranse: [],
            errorproyecto_transe: [],
            errorcant_enviadae: [],

            errorcant_recib: [],
            conttranse: 0,
            editartotales: 0,
            //variables contabilidad
            modalAsiento: false,
            disabled_asiento_Egreso: false,
            modalAsiento_Eg_FAc: false,
            disabled_asiento_EgFact: false,
            modalAsiento_Ing: false,
            disabled_asiento_Ingreso: false,
            modalAsiento_Trans: false,
            disabled_asiento_Trans: false,
            tipo_trans: "",
            nombre_proyecto: "",
            fecha_rol: "",
            ruc_empresa: "",
            razon_social: "",
            concepto: "",
            codigo_asiento_comp: "",
            bodegas_ingreso: [],
            cuentas_ingreso: [],
            bodegas_egreso: [],
            cuentas_egreso: [],
            bodegas_eg_fact: [],
            cuentas_eg_fact: [],
            bodegas_trans: [],
            cuentas_trans: [],

            total_debe: "",
            total_haber: "",
            id_factura: "",
            id_proyecto: "",
            tipo_identificacion: "",
            contabilizado: null,
            modal_conciliacion: false,
            indextipoarreglo: "",
            nombre_pago: "",
            id_pago: "",
            fecha_pago: "",
            nro_documento: "",
            diferencia_debe: 0,
            diferencia_haber: 0,
            id_forma_pago: "",
            estado_asiento: "",
            //fitrar tabla ingreso
            filterstablei: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_autoris: true,
                filt_noautoris: true,
                filt_anul: true
            },
            //fitrar tabla egreso
            filterstablee: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_autoris: true,
                filt_noautoris: true,
                filt_anul: true
            },
            //fitrar tabla trans envio
            filterstabletranse: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_autoris: true,
                filt_noautoris: true,
                filt_anul: true
            },
            //fitrar tabla trans recib
            filterstabletransr: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_autoris: true,
                filt_noautoris: true,
                filt_anul: true
            }
        };
    },
    //importa calendario español
    components: {
        flatPickr,
        VueUploadMultipleImage,
        AgGridVue,
        "v-select": vSelect
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        veringresrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[38].ver;
            }
            return res;
        },
        crearingresrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[38].crear;
            }
            return res;
        },
        veregresrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[39].ver;
            }
            return res;
        },
        crearegresrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[39].crear;
            }
            return res;
        },
        vertranserol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[40].ver;
            }
            return res;
        },
        creartranserol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[40].crear;
            }
            return res;
        },
        vertransrrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[41].ver;
            }
            return res;
        },
        creartransrrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[41].crear;
            }
            return res;
        },
        totales_ingreso() {
            var totales_ingreso = { costos_unitarios: 0, costos_totales: 0 };
            for (let i = 0; i < this.contenidopr.length; i++) {
                totales_ingreso.costos_unitarios += parseFloat(
                    this.contenidopr[i].cost_unit_ingreso
                );
                totales_ingreso.costos_totales += parseFloat(
                    this.contenidopr[i].cost_tot_ingreso
                );
            }
            return totales_ingreso;
        },
        totales_egreso() {
            var totales_egreso = { costos_unitarios: 0, costos_totales: 0 };
            for (let i = 0; i < this.contenidostock.length; i++) {
                totales_egreso.costos_unitarios += parseFloat(
                    this.contenidostock[i].cost_unit_egreso
                );
                totales_egreso.costos_totales += parseFloat(
                    this.contenidostock[i].cost_tot_egreso
                );
            }
            return totales_egreso;
        },
        totales_transe() {
            var totales_transe = { costos_unitarios: 0, costos_totales: 0 };
            for (let i = 0; i < this.prodtranse.length; i++) {
                totales_transe.costos_unitarios += parseFloat(
                    this.prodtranse[i].cost_unitarioe
                );
                totales_transe.costos_totales += parseFloat(
                    this.prodtranse[i].cost_totale
                );
            }
            return totales_transe;
        },
        totales_transr() {
            var totales_transr = { costos_unitarios: 0, costos_totales: 0 };
            for (let i = 0; i < this.contenidoproductostr.length; i++) {
                totales_transr.costos_unitarios += parseFloat(
                    this.contenidoproductostr[i].cost_unitarior
                );
                totales_transr.costos_totales += parseFloat(
                    this.contenidoproductostr[i].cost_totalr
                );
            }
            return totales_transr;
        },
        //comp contabilidad
        Diferencia() {
            if (this.total_debe > this.total_haber) {
                this.diferencia_debe = this.total_haber - this.total_debe;
                console.log(this.total_debe);
            }
            if (this.total_debe < this.total_haber) {
                this.diferencia_haber = this.total_debe - this.total_haber;
                console.log(this.total_haber);
            }
            console.log(this.total_debe - this.total_haber);
        },
        suma_debe() {
            var total = 0;
            if (this.bodegas_ingreso.length > 0) {
                this.bodegas_ingreso.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }
            if (this.bodegas_trans.length > 0) {
                this.bodegas_trans.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }
            if (this.cuentas_egreso.length > 0) {
                this.cuentas_egreso.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }
            if (this.cuentas_eg_fact.length > 0) {
                this.cuentas_eg_fact.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }

            this.total_debe = total.toFixed(2);
        },
        suma_haber() {
            var total = 0;
            if (this.bodegas_egreso.length > 0) {
                this.bodegas_egreso.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }
            if (this.bodegas_eg_fact.length > 0) {
                this.bodegas_eg_fact.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }

            if (this.cuentas_ingreso.length > 0) {
                this.cuentas_ingreso.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }
            if (this.cuentas_trans.length > 0) {
                this.cuentas_trans.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }

            this.total_haber = total.toFixed(2);
        },
        sumar_iguales() {
            if (this.cuentas_ingreso.length > 0) {
                this.cuentas_ingreso = this.cuentas_ingreso.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        haber:
                                            parseFloat(elemento.haber) +
                                            parseFloat(valorActual.haber)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
            if (this.cuentas_trans.length > 0) {
                this.cuentas_trans = this.cuentas_trans.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        haber:
                                            parseFloat(elemento.haber) +
                                            parseFloat(valorActual.haber)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
            if (this.cuentas_egreso.length > 0) {
                this.cuentas_egreso = this.cuentas_egreso.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        debe:
                                            parseFloat(elemento.debe) +
                                            parseFloat(valorActual.debe)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
            if (this.cuentas_eg_fact.length > 0) {
                this.cuentas_eg_fact = this.cuentas_eg_fact.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        debe:
                                            parseFloat(elemento.debe) +
                                            parseFloat(valorActual.debe)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
            if (this.bodegas_ingreso.length > 0) {
                this.bodegas_ingreso = this.bodegas_ingreso.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        debe:
                                            parseFloat(elemento.debe) +
                                            parseFloat(valorActual.debe)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
            if (this.bodegas_trans.length > 0) {
                this.bodegas_trans = this.bodegas_trans.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        debe:
                                            parseFloat(elemento.debe) +
                                            parseFloat(valorActual.debe)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
            if (this.bodegas_egreso.length > 0) {
                this.bodegas_egreso = this.bodegas_egreso.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        haber:
                                            parseFloat(elemento.haber) +
                                            parseFloat(valorActual.haber)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
            if (this.bodegas_eg_fact.length > 0) {
                this.bodegas_eg_fact = this.bodegas_eg_fact.reduce(
                    (acumulador, valorActual) => {
                        const elementoYaExiste = acumulador.find(
                            elemento =>
                                elemento.id_proyecto ===
                                    valorActual.id_proyecto &&
                                elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                        );
                        if (elementoYaExiste) {
                            return acumulador.map(elemento => {
                                if (
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas ===
                                        valorActual.id_plan_cuentas
                                ) {
                                    return {
                                        ...elemento,
                                        haber:
                                            parseFloat(elemento.haber) +
                                            parseFloat(valorActual.haber)
                                    };
                                }

                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
            }
        },
        cambioDecimales() {
            if (this.bodegas_ingreso.length > 0) {
                this.bodegas_ingreso.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
            if (this.bodegas_trans.length > 0) {
                this.bodegas_trans.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
            if (this.bodegas_egreso.length > 0) {
                this.bodegas_egreso.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
            if (this.bodegas_eg_fact.length > 0) {
                this.bodegas_eg_fact.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
            if (this.cuentas_ingreso.length > 0) {
                this.cuentas_ingreso.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
            if (this.cuentas_trans.length > 0) {
                this.cuentas_trans.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
            if (this.cuentas_egreso.length > 0) {
                this.cuentas_egreso.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
            if (this.cuentas_eg_fact.length > 0) {
                this.cuentas_eg_fact.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
            //console.log(this.cuentas.length+"xhola");
        }
    },
    methods: {
        /*-------------------------------------Funciones Vista Stock Bodega (Principal)-------------------------------------*/
        //lista encabezado de bodega
        listarbodegavista() {
            this.idrecupera = this.$route.params.id;
            var url = "/api/abrirbodegagestion/" + this.idrecupera;
            axios
                .get(url)
                .then(res => {
                    var data = res.data.bodega;
                    this.codigo = data.codigo;
                    this.nombre = data.nombre;
                    this.ubicacion = data.ubicacion;
                    this.responsable = data.responsable;
                    this.direccion = data.direccion;
                    this.telefono = data.telefono;
                })
                .catch(err => {
                    console.log(err);
                });
            this.listar();
        },
        //Lista stock de inventario
        listar() {
            var url = "/api/abrirproductosbodega/" + this.idrecupera;
            axios
                .get(url)
                .then(res => {
                    this.stockarray = res.data.datos;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        //----------------------------------------------------------------------------------------------------------------------------//
        /*-------------------------------------Funciones Generales  -------------------------------------*/

        //Funcion lista contenido de proyectos para select
        listproyect() {
            if (this.contproyect.length <= 0) {
                var url = "/api/getproyect/" + this.usuario.id_empresa;
                axios.get(url).then(res => {
                    this.contproyect = res.data;
                });
            }
        },
        //----------------------------------------------------------------------------------------------------------------------------//
        /*-------------------------------------Funciones Vista Ingreso Bodega -------------------------------------*/
        //primera carga de vista ingresos bodega
        firstlistingres() {
            if (this.contenidoingreso.length <= 0) {
                this.listaringresobodega(this.buscaringreso);
            }
        },
        //lista tabla ingreso de bodega
        listaringresobodega(buscaringreso) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/ingresoBodega/" +
                    this.$route.params.id +
                    "/" +
                    this.usuario.id_empresa +
                    "?buscar=" +
                    buscaringreso;
                axios.get(url).then(res => {
                    var respuesta = res.data;
                    this.contenidoingreso = respuesta.recupera;
                    this.filterstablei.contrue = respuesta.recupera;
                    this.filtertablai();
                });
            }, 800);
        },
        //filtros tabla ingreso
        filtertablai() {
            var contvar = this.filterstablei.contrue;
            if (this.filterstablei.filtertab == true) {
                if (this.filterstablei.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == null
                    );
                }
                if (this.filterstablei.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == 1
                    );
                }
            } else {
                this.filterstablei.filt_asientos = true;
                this.filterstablei.filt_noasientos = true;
            }
            this.contenidoingreso = contvar;
        },
        //boton para cancelar y limpiar variables
        cancelaringreso() {
            this.popupingreso = false;
            this.num_ingreso = "";
            this.fecha_ingreso = moment().format("YYYY-M-D");
            this.proyecto_ingreso = "";
            this.tipo_ingreso = "";
            this.observ_ingreso = "";
            this.contenidopr = [];
            this.errornum_ingreso = [];
            this.errortipo_ingreso = [];
            this.errorproyecto_ingreso = [];
            this.errorcant_ingreso = [];
            this.errorcost_unit_ingreso = [];
        },
        //boton nuevo ingreso bodega
        nuevoingreso() {
            this.cancelaringreso();
            this.recuperaingreso = false;
            this.popupingreso = true;
            this.listnumingres();
            this.listproyect();
        },
        //Funcion Lista numero ingreso
        listnumingres() {
            var url = "/api/codingres/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.num_ingreso = res.data.num_ingreso;
            });
        },
        //boton abrir popup productos en ingreso bodega
        abrirproductos() {
            this.popupprod = true;
            if (this.contenidop.length <= 0) {
                this.listarp("");
            }
        },
        //funcion lista productos para ingreso de bodega
        listarp(buscarp) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/productoingreso/" +
                    this.usuario.id_empresa +
                    "?buscar=" +
                    buscarp;
                axios.get(url).then(res => {
                    var respuesta = res.data;
                    this.contenidop = respuesta.recupera;
                });
            }, 800);
        },
        //seleccion de producto para ingreso
        handleSelectedp(tr) {
            this.popupprod = false;
            this.contenidopr.push({
                id: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cant_ingreso: null,
                cost_unit_ingreso: null,
                cost_tot_ingreso: null,
                errorproyecto_ingreso: [],
                errorcant_ingreso: [],
                errorcost_unit_ingreso: []
            });
        },
        //funcion para visulizar ingresos anteriores
        veringreso(id_bodega_ingreso) {
            this.cancelaringreso();
            this.listproyect();
            this.recuperaingreso = true;
            axios
                .get("/api/getingresobodega/" + id_bodega_ingreso)
                .then(({ data }) => {
                    this.num_ingreso = data.ingreso.num_ingreso;
                    this.fecha_ingreso = data.ingreso.fecha_ingreso;
                    this.proyecto_ingreso = data.ingreso.id_proyecto;
                    this.tipo_ingreso = data.ingreso.tipo_ingreso;
                    this.observ_ingreso = data.ingreso.observ_ingreso;

                    data.ingreso_detalle.forEach(el => {
                        this.contenidopr.push({
                            id: el.id_producto,
                            cod_alterno: el.cod_alterno,
                            cod_principal: el.cod_principal,
                            nombre: el.nombre,
                            proyecto: el.id_proyecto,
                            cant_ingreso: el.cantidad,
                            cost_unit_ingreso: el.costo_unitario,
                            cost_tot_ingreso: el.costo_total
                        });
                    });
                    this.popupingreso = true;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        //funcion para guardar ingreso de boega
        guardarbodegaingreso() {
            if (this.validaringreso()) {
                return;
            }
            axios
                .post("/api/guardarbodegaingreso", {
                    id_bodega: this.$route.params.id,
                    num_ingreso: this.num_ingreso,
                    fecha_ingreso: this.fecha_ingreso,
                    id_proyecto: this.proyecto_ingreso,
                    tipo_ingreso: this.tipo_ingreso,
                    observ_ingreso: this.observ_ingreso,
                    contenidopr: this.contenidopr,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Ingreso Registrado",
                        text: "Ingreso Registrado con éxito",
                        color: "success"
                    });
                    this.ainicio();
                    this.cancelaringreso();
                    this.listar();
                    this.listaringresobodega("");
                })
                .catch(err => {});
            this.popupingreso = false;
        },
        //funcion para validacion de ingreso de bodega
        validaringreso() {
            this.error = 0;
            this.errornum_ingreso = [];
            this.errortipo_ingreso = [];
            if (!this.num_ingreso) {
                this.errornum_ingreso.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.tipo_ingreso) {
                this.errortipo_ingreso.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            for (var i = 0; i < this.contenidopr.length; i++) {
                this.contenidopr[i].errorproyecto_ingreso = [];
                this.contenidopr[i].errorcant_ingreso = [];
                this.contenidopr[i].errorcost_unit_ingreso = [];
                if (!this.contenidopr[i].proyecto) {
                    this.contenidopr[i].errorproyecto_ingreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.contenidopr[i].cant_ingreso) {
                    this.contenidopr[i].errorcant_ingreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.contenidopr[i].cost_unit_ingreso) {
                    this.contenidopr[i].errorcost_unit_ingreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }

            return this.error;
        },
        //funcion para descargar pdf de ingreso de bodega
        pdfingreso(id_bodega_ingreso, destinatario = null, email = null) {
            axios({
                url: "/api/pdf/ingresobodega",
                method: "GET",
                responseType: "arraybuffer",
                params: {
                    id_bodega_ingreso: id_bodega_ingreso,
                    id_empresa: this.usuario.id_empresa,
                    id_usuario: this.usuario.id
                }
            })
                .then(resp => {
                    console.log("ejecutado empleado");
                    //this.contenidopr=res.data;
                    console.log("resp:" + resp);
                    console.log("resp datas:" + resp.data);

                    // var decodedString = String.fromCharCode.apply(
                    //     null,
                    //     new Uint8Array(resp.data)
                    // );
                    // console.log("respuesta: "+decodedString);
                    // if (decodedString.includes("no-data-report")) {
                    //     this.$vs.notify({
                    //       title: "Sin Registros",
                    //       text: "Los Datos que escogio no tienen registros",
                    //       color: "danger"
                    //     });
                    // }

                    let { headers } = resp;
                    console.log("cabeceras:" + headers);
                    let nameFile = headers["content-disposition"]
                        .split(";")[1]
                        .split("=")[1]
                        .replace(/"/g, "");
                    const url = window.URL.createObjectURL(
                        new Blob([resp.data], { type: "application/pdf" })
                    );
                    console.log("nombre:" + nameFile + "url:" + url);
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
                })
                .catch(err => {
                    console.log("ERROR" + err);
                });
        },
        //----------------------------------------------------------------------------------------------------------------------------//
        /*-------------------------------------Funciones Vista Egreso Bodega -------------------------------------*/
        //primera carga de vista egresos bodega
        firstlistegres() {
            if (this.contenidoegreso.length <= 0) {
                this.listaregresobodega(this.buscaregreso);
            }
        },
        //lista tabla egreso de bodega
        listaregresobodega(buscaregreso) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/egresoBodega/" +
                    this.$route.params.id +
                    "/" +
                    this.usuario.id_empresa +
                    "?buscar=" +
                    buscaregreso;
                axios.get(url).then(res => {
                    var respuesta = res.data;
                    this.contenidoegreso = respuesta.recupera;
                    this.filterstablee.contrue = respuesta.recupera;
                    this.filtertablae();
                });
            }, 800);
        },
        //filtros tabla egreso
        filtertablae() {
            var contvar = this.filterstablee.contrue;
            if (this.filterstablee.filtertab == true) {
                if (this.filterstablee.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == null
                    );
                }
                if (this.filterstablee.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == 1
                    );
                }
            } else {
                this.filterstablee.filt_asientos = true;
                this.filterstablee.filt_noasientos = true;
            }
            this.contenidoegreso = contvar;
        },
        //boton para cancelar y limpiar variables
        cancelaregreso() {
            this.popupegreso = false;
            this.num_egreso = "";
            this.fecha_egreso = moment().format("YYYY-M-D");
            this.proyecto_egreso = "";
            this.tipo_egreso = "";
            this.observ_egreso = "";
            this.contenidostock = [];
            this.errornum_egreso = [];
            this.errortipo_egreso = [];
            this.errorproyecto_egreso = [];
            this.errorcant_egreso = [];
            this.errorcost_egreso = [];
            this.errorcost_tot_egreso = [];
        },
        //boton nuevo egreso bodega
        nuevoegreso() {
            this.cancelaregreso();
            this.recuperaegreso = false;
            this.popupegreso = true;
            this.listnumegres();
            this.listproyect();
        },
        //Funcion Lista numero egreso
        listnumegres() {
            var url = "/api/codegres/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.num_egreso = res.data.num_egreso;
            });
        },
        //boton abrir popup stock de productos en egreso bodega
        abrirstock() {
            this.popupstock = true;
            if (this.contenidop.length <= 0) {
                this.listarstockegreso("");
            }
        },
        //funcion para listar productos de stock de bodega para egreso de bodega
        listarstockegreso(buscarstock) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                this.idrecupera = this.$route.params.id;
                var url =
                    "/api/abrirstockbodegaegreso/" +
                    this.idrecupera +
                    "?buscar=" +
                    buscarstock;
                axios
                    .get(url)
                    .then(res => {
                        this.stockarrayegreso = res.data.datos;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }, 800);
        },
        //seleccion de srock de bodega  para egreso
        handleSelectedstockegreso(pr) {
            this.popupstock = false;
            this.contenidostock.push({
                id: pr.idprod,
                cod_alterno: pr.cod_alterno,
                cod_principal: pr.cod_principal,
                nombre: pr.nombrep,
                cantidad: pr.cantidad,
                cant_egreso: null,
                cost_unit_egreso: pr.costo_unitario,
                cost_tot_egreso: null,
                errorproyecto_egreso: [],
                errorcant_egreso: [],
                errorcost_egreso: [],
                errorcost_tot_egreso: []
            });
        },
        //funcion para visulizar egresos anteriores
        veregreso(id_bodega_ingreso) {
            this.cancelaregreso();
            this.listproyect();
            this.recuperaegreso = true;
            axios
                .get("/api/getegresobodega/" + id_bodega_ingreso)
                .then(({ data }) => {
                    this.num_egreso = data.egreso.num_egreso;
                    this.fecha_egreso = data.egreso.fecha_egreso;
                    this.proyecto_egreso = data.egreso.id_proyecto;
                    this.tipo_egreso = data.egreso.tipo_egreso;
                    this.observ_egreso = data.egreso.observ_egreso;

                    data.egreso_detalle.forEach(el => {
                        this.contenidostock.push({
                            id: el.id_producto,
                            cod_alterno: el.cod_alterno,
                            cod_principal: el.cod_principal,
                            nombre: el.nombre,
                            proyecto: el.id_proyecto,
                            cant_egreso: el.cantidad,
                            cost_unit_egreso: el.costo_unitario,
                            cost_tot_egreso: el.costo_total
                        });
                    });
                    this.popupegreso = true;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        //funcion para guardar egreso de bodega
        guardarbodegaegreso() {
            if (this.validaregreso()) {
                return;
            }
            axios
                .post("/api/guardarbodegaegreso", {
                    id_bodega: this.$route.params.id,
                    num_egreso: this.num_egreso,
                    fecha_egreso: this.fecha_egreso,
                    id_proyecto: this.proyecto_egreso,
                    tipo_egreso: this.tipo_egreso,
                    observ_egreso: this.observ_egreso,
                    contenidostock: this.contenidostock,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Egreso Registrado",
                        text: "Egreso Registrado con éxito",
                        color: "success"
                    });
                    this.ainicio();
                    this.cancelaregreso();
                    this.listar();
                    this.listaregresobodega("");
                })
                .catch(err => {});
            this.popupegreso = false;
        },
        //funcion para validar registro de egreso de bodega
        validaregreso() {
            this.error = 0;
            this.errornum_egreso = [];
            this.errortipo_egreso = [];
            if (!this.num_egreso) {
                this.errornum_egreso.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.tipo_egreso) {
                this.errortipo_egreso.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            for (var i = 0; i < this.contenidostock.length; i++) {
                this.contenidostock[i].errorproyecto_egreso = [];
                this.contenidostock[i].errorcant_egreso = [];
                this.contenidostock[i].errorcost_egreso = [];
                this.contenidostock[i].errorcost_tot_egreso = [];
                if (!this.contenidostock[i].proyecto) {
                    this.contenidostock[i].errorproyecto_egreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.contenidostock[i].cant_egreso) {
                    this.contenidostock[i].errorcant_egreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.contenidostock[i].cost_unit_egreso) {
                    this.contenidostock[i].errorcost_egreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.contenidostock[i].cost_tot_egreso) {
                    this.contenidostock[i].errorcost_tot_egreso.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }
            return this.error;
        },
        //funcion para descargar pdf de egreso de bodega
        pdfegreso(id_bodega_ingreso, destinatario = null, email = null) {
            axios({
                url: "/api/pdf/egresobodega",
                method: "GET",
                responseType: "arraybuffer",
                params: {
                    id_bodega_ingreso: id_bodega_ingreso,
                    id_empresa: this.usuario.id_empresa,
                    id_usuario: this.usuario.id
                }
            })
                .then(resp => {
                    console.log("ejecutado empleado");
                    //this.contenidopr=res.data;
                    console.log("resp:" + resp);
                    console.log("resp datas:" + resp.data);

                    // var decodedString = String.fromCharCode.apply(
                    //     null,
                    //     new Uint8Array(resp.data)
                    // );
                    // console.log("respuesta: "+decodedString);
                    // if (decodedString.includes("no-data-report")) {
                    //     this.$vs.notify({
                    //       title: "Sin Registros",
                    //       text: "Los Datos que escogio no tienen registros",
                    //       color: "danger"
                    //     });
                    // }

                    let { headers } = resp;
                    console.log("cabeceras:" + headers);
                    let nameFile = headers["content-disposition"]
                        .split(";")[1]
                        .split("=")[1]
                        .replace(/"/g, "");
                    const url = window.URL.createObjectURL(
                        new Blob([resp.data], { type: "application/pdf" })
                    );
                    console.log("nombre:" + nameFile + "url:" + url);
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
                })
                .catch(err => {
                    console.log("ERROR" + err);
                });
        },
        //funcion para descargar pdf de transferencia emisor de bodega
        pdftransferencia(
            id_bodega_ingreso,
            tipo,
            destinatario = null,
            email = null
        ) {
            axios({
                url: "/api/pdf/bodega_transf",
                method: "GET",
                responseType: "arraybuffer",
                params: {
                    id_bodega_ingreso: id_bodega_ingreso,
                    id_empresa: this.usuario.id_empresa,
                    id_usuario: this.usuario.id,
                    tipo: tipo
                }
            })
                .then(resp => {
                    console.log("ejecutado empleado");
                    //this.contenidopr=res.data;
                    console.log("resp:" + resp);
                    console.log("resp datas:" + resp.data);

                    // var decodedString = String.fromCharCode.apply(
                    //     null,
                    //     new Uint8Array(resp.data)
                    // );
                    // console.log("respuesta: "+decodedString);
                    // if (decodedString.includes("no-data-report")) {
                    //     this.$vs.notify({
                    //       title: "Sin Registros",
                    //       text: "Los Datos que escogio no tienen registros",
                    //       color: "danger"
                    //     });
                    // }

                    let { headers } = resp;
                    console.log("cabeceras:" + headers);
                    let nameFile = headers["content-disposition"]
                        .split(";")[1]
                        .split("=")[1]
                        .replace(/"/g, "");
                    const url = window.URL.createObjectURL(
                        new Blob([resp.data], { type: "application/pdf" })
                    );
                    console.log("nombre:" + nameFile + "url:" + url);
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
                })
                .catch(err => {
                    console.log("ERROR" + err);
                });
        },
        //funcion para descargar pdf de inventario bodega
        pdfInventario(id_bodega) {
            axios({
                url: "/api/pdf/productosbodega",
                method: "GET",
                responseType: "arraybuffer",
                params: {
                    id_bodega: id_bodega,
                    id_empresa: this.usuario.id_empresa,
                    id_usuario: this.usuario.id
                }
            })
                .then(resp => {
                    console.log("ejecutado empleado");
                    //this.contenidopr=res.data;
                    console.log("resp:" + resp);
                    console.log("resp datas:" + resp.data);

                    // var decodedString = String.fromCharCode.apply(
                    //     null,
                    //     new Uint8Array(resp.data)
                    // );
                    // console.log("respuesta: "+decodedString);
                    // if (decodedString.includes("no-data-report")) {
                    //     this.$vs.notify({
                    //       title: "Sin Registros",
                    //       text: "Los Datos que escogio no tienen registros",
                    //       color: "danger"
                    //     });
                    // }

                    let { headers } = resp;
                    console.log("cabeceras:" + headers);
                    let nameFile = headers["content-disposition"]
                        .split(";")[1]
                        .split("=")[1]
                        .replace(/"/g, "");
                    const url = window.URL.createObjectURL(
                        new Blob([resp.data], { type: "application/pdf" })
                    );
                    console.log("nombre:" + nameFile + "url:" + url);
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
                })
                .catch(err => {
                    console.log("ERROR" + err);
                });
        },
        //----------------------------------------------------------------------------------------------------------------------------//
        /*------------------------------------------------------------------------Funciones Vista Transferencia -------------------------------------------------------------------*/
        /*-------------------------------------Funciones Vista Transferencia Envio-------------------------------------*/
        //primera carga de vista transferencia envio de bodega
        firstlisttranse() {
            if (this.contenidotranse.length <= 0) {
                this.listartransebodega(this.buscartranse);
            }
        },
        //lista tabla transferencias enviadas de bodega
        listartransebodega(buscartranse) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/transeBodega/" +
                    this.$route.params.id +
                    "/" +
                    this.usuario.id_empresa +
                    "?buscar=" +
                    buscartranse;
                axios.get(url).then(res => {
                    var respuesta = res.data;
                    this.contenidotranse = respuesta.recupera;
                    this.filterstabletranse.contrue = respuesta.recupera;
                    this.filtertablatranse();
                });
            }, 800);
        },
        filtertablatranse() {
            var contvar = this.filterstabletranse.contrue;
            if (this.filterstabletranse.filtertab == true) {
                if (this.filterstabletranse.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == null
                    );
                }
                if (this.filterstabletranse.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == 1
                    );
                }
            } else {
                this.filterstabletranse.filt_asientos = true;
                this.filterstabletranse.filt_noasientos = true;
            }
            this.contenidotranse = contvar;
        },
        //boton para cancelar y limpiar variables
        cancelartranse() {
            this.popuptransenvio = false;
            this.num_transe = "";
            this.f_ini_transe = moment().format("YYYY-M-D");
            this.f_fin_transe = moment().format("YYYY-M-D");
            this.motivo_transe = "";
            this.proyecto_transe = "";
            this.emisor_transe = "";
            this.receptor_transe = "";
            this.observ_transe = "";
            this.transport_transe = false;
            this.marca_transe = "";
            this.placa_transe = "";
            this.color_transe = "";
            this.sr_transe = "";
            this.cant_enviadae = "";
            this.cant_recibidae = "";
            this.prodtranse = [];
            this.errornum_transe = [];
            this.errormotivo_transe = [];
            this.errorreceptor_transe = [];
            this.errormarca_transe = [];
            this.errorplaca_transe = [];
            this.errorcolor_transe = [];
            this.errorsr_transe = [];
            this.errorprodtranse = [];
            this.errorproyecto_transe = [];
            this.errorcant_enviadae = [];
        },
        //boton para nuevo envio de trasnferencias
        nuevotranse() {
            this.cancelartranse();
            this.popuptransenvio = true;
            this.recuperatranse = false;
            this.listnumtranse();
            this.listproyect();
            this.listarbodegastranse();
        },
        //lista autoincrementable de envio de trasnferencas
        listnumtranse() {
            var url = "/api/codtrans/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.num_transe = res.data.num_transe;
            });
        },
        //lista las bodegas para realizar el envio de trasnferencia
        listarbodegastranse() {
            if (this.contenidobodegas.length <= 0) {
                var url =
                    "/api/bodegasTranse/" +
                    this.$route.params.id +
                    "/" +
                    this.usuario.id_empresa;
                axios.get(url).then(res => {
                    this.contenidobodegas = res.data;
                });
            }
        },
        //boton agrear productos a trasnferencia envio
        abrirstocktranse() {
            this.popuptransenvioprod = true;
            if (this.contenidoprodtranse.length <= 0) {
                this.listarstocktranse("");
            }
        },
        //funcion para listar productos de stock de bodega para egreso de bodega
        listarstocktranse(buscarstocktranse) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                this.idrecupera = this.$route.params.id;
                var url =
                    "/api/abrirstockbodegaegreso/" +
                    this.idrecupera +
                    "?buscar=" +
                    buscarstocktranse;
                axios
                    .get(url)
                    .then(res => {
                        this.contenidoprodtranse = res.data.datos;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }, 800);
        },
        //seleccion de producto en trasnferencia envio
        handleSelectedtranse(pr) {
            this.popuptransenvioprod = false;
            this.prodtranse.push({
                id: pr.idprod,
                cod_alterno: pr.cod_alterno,
                cod_principal: pr.cod_principal,
                nombre: pr.nombrep,
                proyecto: pr.proyecto,
                cantidad: pr.cantidad,
                cant_enviadae: null,
                cant_recibidae: null,
                cost_unitarioe: pr.costo_unitario,
                cost_totale: null,
                errorproyecto_transe: [],
                errorcant_enviadae: []
            });
        },
        //guarda formulario de trasnferencia envio
        guardarbodegatranse() {
            if (this.validartranse()) {
                return;
            }
            axios
                .post("/api/guardarbodegatranse", {
                    id_bodega: this.$route.params.id,
                    f_ini_transe: this.f_ini_transe,
                    f_fin_transe: this.f_fin_transe,
                    motivo_transe: this.motivo_transe,
                    id_proyecto: this.proyecto_transe,
                    emisor_transe: this.$route.params.id,
                    receptor_transe: this.receptor_transe,
                    observ_transe: this.observ_transe,
                    transport_transe: this.transport_transe,
                    marca_transe: this.marca_transe,
                    placa_transe: this.placa_transe,
                    color_transe: this.color_transe,
                    sr_transe: this.sr_transe,
                    prodtranse: this.prodtranse,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Transferencia Enviada",
                        text: "Transferencia exitosa",
                        color: "success"
                    });
                    this.ainicio();
                    this.cancelartranse();
                    this.listar();
                    this.listaregresobodega();
                    this.listartransebodega("");
                })
                .catch(err => {});
            this.popuptransenvio = false;
        },
        //valida envio de trasnferencia
        validartranse() {
            this.error = 0;
            this.errornum_transe = [];
            this.errormotivo_transe = [];
            this.errorreceptor_transe = [];
            this.errormarca_transe = [];
            this.errorplaca_transe = [];
            this.errorcolor_transe = [];
            this.errorsr_transe = [];
            this.errorprodtranse = [];
            if (!this.num_transe) {
                this.errornum_transe.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.motivo_transe) {
                this.errormotivo_transe.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.receptor_transe) {
                this.errorreceptor_transe.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (this.transport_transe == 1) {
                if (!this.marca_transe) {
                    this.errormarca_transe.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.placa_transe) {
                    this.errorplaca_transe.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.color_transe) {
                    this.errorcolor_transe.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.sr_transe) {
                    this.errorsr_transe.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }
            if (this.prodtranse.length == 0) {
                this.$vs.notify({
                    title: "No hay Productos añadidos",
                    text: "Agregue al menos un producto para transferir",
                    color: "danger"
                });
                this.error = 1;
                window.scrollTo(0, 0);
            }

            for (var i = 0; i < this.prodtranse.length; i++) {
                this.prodtranse[i].errorproyecto_transe = [];
                this.prodtranse[i].errorcant_enviadae = [];
                if (!this.prodtranse[i].proyecto) {
                    this.prodtranse[i].errorproyecto_transe.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.prodtranse[i].cant_enviadae) {
                    this.prodtranse[i].errorcant_enviadae.push(
                        "Campo obligatorio"
                    );
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }

            return this.error;
        },
        //funcion para visulizar trasnferencias enviadas anteriores
        vertransferenciae(id_bodega_transferencia) {
            this.cancelartranse();
            this.listproyect();
            this.listarbodegastranse();
            this.recuperatranse = true;
            axios
                .get("/api/gettransebodega/" + id_bodega_transferencia)
                .then(({ data }) => {
                    this.num_transe = data.transe.num_trans;
                    this.f_ini_transe = data.transe.f_iniciacion;
                    this.f_fin_transe = data.transe.f_finalizacion;
                    this.motivo_transe = data.transe.motivo_trans;
                    this.proyecto_transe = data.transe.id_proyecto;
                    this.emisor_transe = data.transe.emisor_trans;
                    this.receptor_transe = data.transe.receptor_trans;
                    this.observ_transe = data.transe.observ_trans;
                    if (data.transe.transporte == 1) {
                        this.transport_transe = true;
                    } else if (data.transe.transporte == 0) {
                        this.transport_transe = false;
                    }
                    this.marca_transe = data.transe.marcav;
                    this.placa_transe = data.transe.placasv;
                    this.color_transe = data.transe.colorv;
                    this.sr_transe = data.transe.transportista;

                    data.transe_detalle.forEach(el => {
                        this.prodtranse.push({
                            id: el.id_producto,
                            cod_alterno: el.cod_alterno,
                            cod_principal: el.cod_principal,
                            nombre: el.nombre,
                            proyecto: el.id_proyecto,
                            cant_enviadae: el.cant_env,
                            cost_unitarioe: el.costo_unitario,
                            cost_totale: el.costo_total
                        });
                    });
                    this.popuptransenvio = true;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        /* ---------------------------------------------------------------------------------------------------------------------------------------------*/
        /*-------------------------------------Funciones Vista Transferencia Recepcion-------------------------------------*/
        //primera carga de vista transferencia recepcion de bodega
        firstlisttransr() {
            if (this.contenidotransr.length <= 0) {
                this.listartransrbodega(this.buscartransr);
            }
        },
        //lista tabla transferencias receptadas de bodega
        listartransrbodega(buscartransr) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/transrBodega/" +
                    this.$route.params.id +
                    "/" +
                    this.usuario.id_empresa +
                    "?buscar=" +
                    buscartransr;
                axios.get(url).then(res => {
                    var respuesta = res.data;
                    this.contenidotransr = respuesta.recupera;
                    this.filterstabletransr.contrue = respuesta.recupera;
                    this.filtertablatransr();
                });
            }, 800);
        },
        filtertablatransr() {
            var contvar = this.filterstabletransr.contrue;
            if (this.filterstabletransr.filtertab == true) {
                if (this.filterstabletransr.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == null
                    );
                }
                if (this.filterstabletransr.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == 1
                    );
                }
            } else {
                this.filterstabletransr.filt_asientos = true;
                this.filterstabletransr.filt_noasientos = true;
            }
            this.contenidotransr = contvar;
        },
        //boton cancelar para limpiar variables trasnferencia recepcion
        cancelartransr() {
            this.popuptransrecib = false;
            (this.id_trasnferenciar = ""), (this.num_transr = "");
            this.f_ini_transr = "";
            this.f_fin_transr = "";
            this.motivo_transr = "";
            this.proyecto_transr = "";
            this.emisor_transr = "";
            this.receptor_transr = "";
            this.observ_transr = "";
            this.transport_transr = "";
            this.marca_transr = "";
            this.placa_transr = "";
            this.color_transr = "";
            this.sr_transr = "";
            this.cant_env = "";
            this.cant_recib = "";
            this.cant_new = "";
            this.cost_unitarior = "";
            this.cost_totalr = "";
            this.contenidoproductostr = [];
            this.errorcant_recib = [];
            this.conttranse = 0;
        },
        //visualiza contenido de trasnfeerecnia recibida
        vertransferenciar(id_bodega_transferencia) {
            this.cancelartransr();
            this.listproyect();
            this.listarbodegastranse();
            this.recuperatransr = false;
            axios
                .get("/api/gettransrbodega/" + id_bodega_transferencia)
                .then(({ data }) => {
                    this.id_trasnferenciar =
                        data.transr.id_bodega_transferencia;
                    this.num_transr = data.transr.num_trans;
                    this.f_ini_transr = data.transr.f_iniciacion;
                    this.f_fin_transr = data.transr.f_finalizacion;
                    this.motivo_transr = data.transr.motivo_trans;
                    this.proyecto_transr = data.transr.id_proyecto;
                    this.emisor_transr = data.transr.emisor_trans;
                    this.receptor_transr = this.nombre;
                    this.observ_transr = data.transr.observ_trans;
                    if (data.transr.transporte == 1) {
                        this.transport_transr = true;
                    } else if (data.transr.transporte == 0) {
                        this.transport_transr = false;
                    }
                    this.marca_transr = data.transr.marcav;
                    this.placa_transr = data.transr.placasv;
                    this.color_transr = data.transr.colorv;
                    this.sr_transr = data.transr.transportista;

                    data.transr_detalle.forEach(el => {
                        this.contenidoproductostr.push({
                            id_producto: el.id_producto,
                            id_bodega_transferencia_detalle:
                                el.id_bodega_transferencia_detalle,
                            cod_alterno: el.cod_alterno,
                            cod_principal: el.cod_principal,
                            nombre: el.nombre,
                            proyecto: el.id_proyecto,
                            cant_env: el.cant_env,
                            cant_recib: el.cant_recib,
                            cant_rest:
                                parseFloat(el.cant_env) -
                                parseFloat(el.cant_recib),
                            cant_new: "",
                            cost_unitarior: el.costo_unitario,
                            cost_totalr: 0
                        });
                    });
                    this.validarrecibido();
                    this.popuptransrecib = true;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        //valida si producto ya fue trasnferendo en su totalidad o no y modifica visulizacion respecto a eso
        validarrecibido() {
            var total1 = 0;
            this.contenidoproductostr.forEach(el => {
                var total = parseFloat(el.cant_env) - parseFloat(el.cant_recib);
                total1 += parseFloat(el.cant_env) - parseFloat(el.cant_recib);
                if (total == 0) {
                    el.podereditar = 0;
                } else {
                    el.podereditar = 1;
                }
            });
            this.editartotales = total1;
        },
        //realzia el calculo de cantidad total de productos ingresados en trasnferecnia recibida para validacion
        /*  calculotransr(tr) {
            this.contenidoproductostr[tr].cant_tot = (
                parseFloat(this.contenidoproductostr[tr].cant_recib) +
                parseFloat(this.contenidoproductostr[tr].cant_new)
            ).toFixed(2);
        },*/
        //valida formulario de recepcion de trasnferencias
        validartransr() {
            this.error = 0;
            this.errorcant_recib = [];
            this.conttranse = 0;

            for (var i = 0; i < this.contenidoproductostr.length; i++) {
                this.contenidoproductostr[i].errorcant_recib = [];
                this.conttranse += this.contenidoproductostr[i].cant_new;
                if (
                    parseFloat(this.contenidoproductostr[i].cant_recib) +
                        parseFloat(this.contenidoproductostr[i].cant_new) >
                    this.contenidoproductostr[i].cant_env
                ) {
                    this.error = 1;
                    this.$vs.notify({
                        title: "Danger",
                        text:
                            "Cantidad Ingresada no puede ser Mayor a la Cantidad de Envio",
                        color: "danger"
                    });
                }
            }
            if (this.conttranse == 0) {
                this.error = 1;
                this.$vs.notify({
                    title: "Danger",
                    text: "Debe Ingresar al menos un Producto para Guardar ",
                    color: "danger"
                });
            }
            return this.error;
        },
        guardarbodegatransr() {
            if (this.validartransr()) {
                return;
            }
            axios
                .post("/api/guardarbodegatransr", {
                    id_bodega: this.$route.params.id,
                    id_bodega_transferencia: this.id_trasnferenciar,
                    num_trans: this.num_transr,
                    contenidoproductostr: this.contenidoproductostr,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Transferencia Recibida Actualizada",
                        text: "Productos Ingresados a Bodega",
                        color: "success"
                    });
                    this.ainicio();
                    this.cancelartransr();
                    this.listar();
                    this.listaringresobodega();
                    this.listartransrbodega("");
                })
                .catch(err => {});
            this.popuptransrecib = false;
        },
        /*--------------------------------------------------------------------------- Valizaciones extra y otras funciones-----------------------------------------------------------*/
        //valida imputs solo admita numeros
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
        //funcion lleva a vista inicial del formulario
        ainicio() {
            $(".tabproductos")
                .find("li:nth-child(1)>button")
                .click();
            this.motivo_trans = "";
            this.receptor_trans = "";
        },
        //elimina 1 contenido de cualquiera de los arrays
        eliminar(id) {
            this.contenidopr.splice(id, 1);
            this.contenidostock.splice(id, 1);
            this.prodtranse.splice(id, 1);
        },
        //methods asiento egreso factura
        ContabilidadEgresoFactura(id) {
            this.bodegas_ingreso = [];
            this.cuentas_ingreso = [];
            this.bodegas_egreso = [];
            this.cuentas_egreso = [];
            this.bodegas_eg_fact = [];
            this.cuentas_eg_fact = [];
            this.bodegas_trans = [];
            this.cuentas_trans = [];
            axios
                .get(
                    "/api/ver_asiento/bodega_egreso_fact/" +
                        id +
                        "?id_empresa=" +
                        this.usuario.id_empresa
                )
                .then(({ data }) => {
                    //var nros_factura=nombre_provs.join();
                    this.fecha_rol = moment(
                        data.info_bodega.fecha_egreso
                    ).format("Y-MM-DD");
                    var fecha = moment(this.fecha_rol).format("MMMM YYYY");
                    this.razon_social = "Costo Venta";
                    //   this.ruc_empresa=data.proveedores[0].identificacion;
                    //   if(data.proveedores[0].tipo_identificacion=="Cédula de Identidad"){
                    //       this.tipo_identificacion="Cedula";
                    //   }else{
                    //       this.tipo_identificacion=data.proveedores[0].tipo_identificacion;
                    //   }
                    if (data.info_bodega.contabilidad == 1) {
                        this.codigo_asiento_comp =
                            "BCV-" + data.codigo_anterior;
                        this.contabilizado = data.info_bodega.contabilidad;
                    } else {
                        this.codigo_asiento_comp = "BCV-" + data.codigo;
                        this.contabilizado = null;
                    }
                    this.concepto =
                        this.nombre + " " + data.info_bodega.observ_egreso;
                    this.modalAsiento = true;
                    this.bodegas_eg_fact = data.bodega;
                    this.cuentas_eg_fact = data.cuenta;
                    this.id_factura = id;
                    this.id_proyecto = data.proyecto;
                    this.estado_asiento = data.asiento_permitido;
                    // var referencia=data.ctas_pagar.referencia.split(';');
                    // var conteo_ref=referencia.length/4;
                    // var salto = 0;
                    // var factura=[];
                    // for(var f=0; f<conteo_ref; f++){
                    //   factura.push(referencia[0+salto]);
                    //   salto+=4;
                    // }
                    // this.modalAsiento=true;
                    // this.productos_asiento=data.proveedor;
                    // this.iva_asiento=data.forma_pago;
                    // if(this.iva_asiento.length>0){
                    //   this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                    //   this.nombre_pago=this.iva_asiento[0].nombre_pago;
                    //   this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                    //   this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                    //
                    // }

                    //console.log(data.ctas_pagar.contabilidad+"HOLA");
                })
                .catch(error => {
                    console.log(error);
                });
        },
        crearasiento_EgFact(id) {
            this.disabled_asiento_EgFact = true;
            var total = 0;
            total = this.total_debe - this.total_haber;
            console.log(total + ":total diferencia");
            if (this.validacion_asiento("egreso_fact")) {
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento"
                });
                this.disabled_asiento_EgFact = false;
                return;
            }
            var total = 0;
            total = this.total_debe - this.total_haber;
            if (total !== 0) {
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento"
                });
                this.disabled_asiento_EgFact = false;
                return;
            }
            if (this.estado_asiento == "no") {
                this.$vs.notify({
                    color: "danger",
                    title:
                        "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha"
                });
                this.disabled_asiento_EgFact = false;
                return;
            }
            var codigo_asiento = this.codigo_asiento_comp.substr(
                4,
                this.codigo_asiento_comp.length
            );
            var fecha_hoy = new Date();
            axios
                .post("/api/agregar/bodega_egreso_fact", {
                    cod_rol: id,
                    numero: codigo_asiento,
                    codigo: this.codigo_asiento_comp,
                    fecha:
                        this.fecha_rol +
                        " " +
                        fecha_hoy.getHours() +
                        ":" +
                        fecha_hoy.getMinutes() +
                        ":" +
                        fecha_hoy.getSeconds(),
                    razon_social: this.razon_social,
                    tipo_identificacion: this.tipo_identificacion,
                    ruc_ci: this.ruc_empresa,
                    concepto: this.concepto,
                    ucrea: this.usuario.id,
                    id_proyecto: this.id_proyecto
                })
                .then(res => {
                    this.crearasientoDetalle_EgFact(res.data);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_EgFact = false;
                });
        },
        crearasientoDetalle_EgFact(id) {
            axios
                .post("/api/agregar/detalle/bodega_egreso_fact", {
                    bodegas: this.bodegas_eg_fact,
                    cuentas: this.cuentas_eg_fact,
                    // pagos_sin_plc:this.pagos_sin_plc,
                    // pagos_con_plc:this.pagos_con_plc,
                    // pagos_anticipo:this.pagos_anticipo,
                    // creditos:this.creditos,
                    // retencion_iva:this.retencion_iva,
                    // retencion_renta:this.retencion_renta,
                    ucrea: this.usuario.id,
                    id_asientos: id
                })
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Asiento Agregado",
                        text: "Asiento agregado con exito"
                    });

                    this.listaregresobodega(this.buscaregreso);
                    this.modalAsiento = false;
                    this.bodegas_ingreso = [];
                    this.cuentas_ingreso = [];
                    this.bodegas_egreso = [];
                    this.cuentas_egreso = [];
                    this.bodegas_eg_fact = [];
                    this.cuentas_eg_fact = [];
                    this.bodegas_trans = [];
                    this.cuentas_trans = [];
                    this.id_factura = "";
                    this.fecha_rol = "";
                    this.ruc_empresa = "";
                    this.razon_social = "";
                    this.concepto = "";
                    this.codigo = "";
                    this.id_proyecto = "";
                    this.codigo_asiento_comp = "";
                    this.contabilizado = null;
                    this.estado_asiento = "";
                    this.disabled_asiento_EgFact = false;
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_EgFact = false;
                });
        },
        validacion_asiento(tipo) {
            if (tipo == "ingreso") {
                var error = 0;
                if (this.bodegas_ingreso.length <= 0) {
                    error++;
                } else {
                    this.bodegas_ingreso.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                        }

                        if (el.id_proyecto == null) {
                            error++;
                        }
                    });
                }
                if (this.cuentas_ingreso.length <= 0) {
                    error++;
                } else {
                    this.cuentas_ingreso.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                        }
                        if (el.id_proyecto == null) {
                            error++;
                        }
                    });
                }
                return error;
            }
            if (tipo == "egreso") {
                var error = 0;
                if (this.bodegas_egreso.length <= 0) {
                    error++;
                } else {
                    this.bodegas_egreso.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                        }

                        if (el.id_proyecto == null) {
                            error++;
                        }
                    });
                }
                if (this.cuentas_egreso.length <= 0) {
                    error++;
                } else {
                    this.cuentas_egreso.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                        }
                        if (el.id_proyecto == null) {
                            error++;
                        }
                    });
                }
                return error;
            }
            if (tipo == "egreso_fact") {
                var error = 0;
                if (this.bodegas_eg_fact.length <= 0) {
                    error++;
                    console.log("no existe bodega_eg_fact");
                } else {
                    this.bodegas_eg_fact.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                            console.log("no existe plan_cuenta bodega_eg_fact");
                        }

                        if (el.id_proyecto == null) {
                            error++;
                            console.log("no existe proyecto bodega_eg_fact");
                        }
                    });
                }
                if (this.cuentas_eg_fact.length <= 0) {
                    error++;
                    console.log("no existe cuentas_eg_fact");
                } else {
                    this.cuentas_eg_fact.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                            console.log(
                                "no existe plan_cuenta cuentas_eg_fact"
                            );
                        }
                        if (el.id_proyecto == null) {
                            error++;
                            console.log(
                                "no existe plan_cuenta cuentas_eg_fact"
                            );
                        }
                    });
                }
                return error;
            }
            if (tipo == "transf") {
                var error = 0;
                if (this.bodegas_trans.length <= 0) {
                    error++;
                    console.log("no existe bodega_trans");
                } else {
                    this.bodegas_trans.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                            console.log("no existe plan_cuenta bodega_trans");
                        }

                        if (el.id_proyecto == null) {
                            error++;
                            console.log("no existe proyecto bodega_trans");
                        }
                    });
                }
                if (this.cuentas_trans.length <= 0) {
                    error++;
                    console.log("no existe cuentas_trans");
                } else {
                    this.cuentas_trans.forEach(el => {
                        if (el.id_plan_cuentas == null) {
                            error++;
                            console.log("no existe plan_cuenta cuentas_trans");
                        }
                        if (el.id_proyecto == null) {
                            error++;
                            console.log("no existe plan_cuenta cuentas_trans");
                        }
                    });
                }
                return error;
            }
        },
        //methods asiento egresos
        Contabilidad_Egreso(id) {
            this.bodegas_ingreso = [];
            this.cuentas_ingreso = [];
            this.bodegas_egreso = [];
            this.cuentas_egreso = [];
            this.bodegas_eg_fact = [];
            this.cuentas_eg_fact = [];
            this.bodegas_trans = [];
            this.cuentas_trans = [];
            axios
                .get(
                    "/api/ver_asiento/bodega_egreso/" +
                        id +
                        "?id_empresa=" +
                        this.usuario.id_empresa
                )
                .then(({ data }) => {
                    //var nros_factura=nombre_provs.join();
                    this.fecha_rol = moment(
                        data.info_bodega.fecha_egreso
                    ).format("Y-MM-DD");
                    var fecha = moment(this.fecha_rol).format("MMMM YYYY");
                    //  this.razon_social="Costo Venta";
                    //   this.ruc_empresa=data.proveedores[0].identificacion;
                    //   if(data.proveedores[0].tipo_identificacion=="Cédula de Identidad"){
                    //       this.tipo_identificacion="Cedula";
                    //   }else{
                    //       this.tipo_identificacion=data.proveedores[0].tipo_identificacion;
                    //   }
                    if (data.info_bodega.contabilidad == 1) {
                        this.codigo_asiento_comp = "BE-" + data.codigo_anterior;
                        this.contabilizado = data.info_bodega.contabilidad;
                    } else {
                        this.codigo_asiento_comp = "BE-" + data.codigo;
                        this.contabilizado = null;
                    }
                    this.concepto =
                        this.nombre + " " + data.info_bodega.observ_egreso;
                    this.modalAsiento_Eg_FAc = true;
                    this.bodegas_egreso = data.bodega;
                    this.cuentas_egreso = data.cuenta;
                    this.id_factura = id;
                    this.id_proyecto = data.proyecto;
                    this.estado_asiento = data.asiento_permitido;
                    // var referencia=data.ctas_pagar.referencia.split(';');
                    // var conteo_ref=referencia.length/4;
                    // var salto = 0;
                    // var factura=[];
                    // for(var f=0; f<conteo_ref; f++){
                    //   factura.push(referencia[0+salto]);
                    //   salto+=4;
                    // }
                    // this.modalAsiento_Eg_FAc=true;
                    // this.productos_asiento=data.proveedor;
                    // this.iva_asiento=data.forma_pago;
                    // if(this.iva_asiento.length>0){
                    //   this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                    //   this.nombre_pago=this.iva_asiento[0].nombre_pago;
                    //   this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                    //   this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                    //
                    // }

                    //console.log(data.ctas_pagar.contabilidad+"HOLA");
                })
                .catch(error => {
                    console.log(error);
                });
        },
        crearasiento_Egreso(id) {
            this.disabled_asiento_Egreso = true;
            var total = 0;
            total = this.total_debe - this.total_haber;
            console.log(total + ":total diferencia");
            if (this.validacion_asiento("egreso")) {
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento"
                });
                this.disabled_asiento_Egreso = false;
                return;
            }
            var total = 0;
            total = this.total_debe - this.total_haber;
            if (total !== 0) {
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento"
                });
                this.disabled_asiento_Egreso = false;
                return;
            }
            if (this.estado_asiento == "no") {
                this.$vs.notify({
                    color: "danger",
                    title:
                        "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha"
                });
                this.disabled_asiento_Egreso = false;
                return;
            }
            var codigo_asiento = this.codigo_asiento_comp.substr(
                3,
                this.codigo_asiento_comp.length
            );
            var fecha_hoy = new Date();
            axios
                .post("/api/agregar/bodega_egreso", {
                    cod_rol: id,
                    numero: codigo_asiento,
                    codigo: this.codigo_asiento_comp,
                    fecha:
                        this.fecha_rol +
                        " " +
                        fecha_hoy.getHours() +
                        ":" +
                        fecha_hoy.getMinutes() +
                        ":" +
                        fecha_hoy.getSeconds(),
                    razon_social: this.razon_social,
                    tipo_identificacion: this.tipo_identificacion,
                    ruc_ci: this.ruc_empresa,
                    concepto: this.concepto,
                    ucrea: this.usuario.id,
                    id_proyecto: this.id_proyecto
                })
                .then(res => {
                    this.crearasientoDetalle_Egreso(res.data);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_Egreso = false;
                });
        },
        crearasientoDetalle_Egreso(id) {
            axios
                .post("/api/agregar/detalle/bodega_egreso", {
                    bodegas: this.bodegas_egreso,
                    cuentas: this.cuentas_egreso,
                    // pagos_sin_plc:this.pagos_sin_plc,
                    // pagos_con_plc:this.pagos_con_plc,
                    // pagos_anticipo:this.pagos_anticipo,
                    // creditos:this.creditos,
                    // retencion_iva:this.retencion_iva,
                    // retencion_renta:this.retencion_renta,
                    ucrea: this.usuario.id,
                    id_asientos: id
                })
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Asiento Agregado",
                        text: "Asiento agregado con exito"
                    });

                    this.listaregresobodega(this.buscaregreso);
                    this.modalAsiento_Eg_FAc = false;
                    this.bodegas_ingreso = [];
                    this.cuentas_ingreso = [];
                    this.bodegas_egreso = [];
                    this.cuentas_egreso = [];
                    this.bodegas_eg_fact = [];
                    this.cuentas_eg_fact = [];
                    this.bodegas_trans = [];
                    this.cuentas_trans = [];
                    this.id_factura = "";
                    this.fecha_rol = "";
                    this.ruc_empresa = "";
                    this.razon_social = "";
                    this.concepto = "";
                    this.codigo = "";
                    this.id_proyecto = "";
                    this.codigo_asiento_comp = "";
                    this.contabilizado = null;
                    this.estado_asiento = "";
                    this.disabled_asiento_Egreso = false;
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_Egreso = false;
                });
        },
        Contabilidad_Ingreso(id) {
            this.bodegas_ingreso = [];
            this.cuentas_ingreso = [];
            this.bodegas_egreso = [];
            this.cuentas_egreso = [];
            this.bodegas_eg_fact = [];
            this.cuentas_eg_fact = [];
            this.bodegas_trans = [];
            this.cuentas_trans = [];
            axios
                .get(
                    "/api/ver_asiento/bodega_ingreso/" +
                        id +
                        "?id_empresa=" +
                        this.usuario.id_empresa
                )
                .then(({ data }) => {
                    //var nros_factura=nombre_provs.join();
                    this.fecha_rol = moment(
                        data.info_bodega.fecha_ingreso
                    ).format("Y-MM-DD");
                    var fecha = moment(this.fecha_rol).format("MMMM YYYY");
                    //  this.razon_social="Costo Venta";
                    //   this.ruc_empresa=data.proveedores[0].identificacion;
                    //   if(data.proveedores[0].tipo_identificacion=="Cédula de Identidad"){
                    //       this.tipo_identificacion="Cedula";
                    //   }else{
                    //       this.tipo_identificacion=data.proveedores[0].tipo_identificacion;
                    //   }
                    if (data.info_bodega.contabilidad == 1) {
                        this.codigo_asiento_comp = "BI-" + data.codigo_anterior;
                        this.contabilizado = data.info_bodega.contabilidad;
                    } else {
                        this.codigo_asiento_comp = "BI-" + data.codigo;
                        this.contabilizado = null;
                    }
                    this.concepto =
                        this.nombre + " " + data.info_bodega.observ_ingreso;
                    this.modalAsiento_Ing = true;
                    this.bodegas_ingreso = data.bodega;
                    this.cuentas_ingreso = data.cuenta;
                    this.id_factura = id;
                    this.id_proyecto = data.proyecto;
                    this.estado_asiento = data.asiento_permitido;
                    // var referencia=data.ctas_pagar.referencia.split(';');
                    // var conteo_ref=referencia.length/4;
                    // var salto = 0;
                    // var factura=[];
                    // for(var f=0; f<conteo_ref; f++){
                    //   factura.push(referencia[0+salto]);
                    //   salto+=4;
                    // }
                    // this.modalAsiento_Ing=true;
                    // this.productos_asiento=data.proveedor;
                    // this.iva_asiento=data.forma_pago;
                    // if(this.iva_asiento.length>0){
                    //   this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                    //   this.nombre_pago=this.iva_asiento[0].nombre_pago;
                    //   this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                    //   this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                    //
                    // }

                    //console.log(data.ctas_pagar.contabilidad+"HOLA");
                })
                .catch(error => {
                    console.log(error);
                });
        },
        crearasiento_Ingreso(id) {
            this.disabled_asiento_Ingreso = true;
            var total = 0;
            total = this.total_debe - this.total_haber;
            console.log(total + ":total diferencia");
            if (this.validacion_asiento("ingreso")) {
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento"
                });
                this.disabled_asiento_Ingreso = false;
                return;
            }
            var total = 0;
            total = this.total_debe - this.total_haber;
            if (total !== 0) {
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento"
                });
                this.disabled_asiento_Ingreso = false;
                return;
            }
            if (this.estado_asiento == "no") {
                this.$vs.notify({
                    color: "danger",
                    title:
                        "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha"
                });
                this.disabled_asiento_Ingreso = false;
                return;
            }
            var codigo_asiento = this.codigo_asiento_comp.substr(
                3,
                this.codigo_asiento_comp.length
            );
            var fecha_hoy = new Date();
            axios
                .post("/api/agregar/bodega_ingreso", {
                    cod_rol: id,
                    numero: codigo_asiento,
                    codigo: this.codigo_asiento_comp,
                    fecha:
                        this.fecha_rol +
                        " " +
                        fecha_hoy.getHours() +
                        ":" +
                        fecha_hoy.getMinutes() +
                        ":" +
                        fecha_hoy.getSeconds(),
                    razon_social: this.razon_social,
                    tipo_identificacion: this.tipo_identificacion,
                    ruc_ci: this.ruc_empresa,
                    concepto: this.concepto,
                    ucrea: this.usuario.id,
                    id_proyecto: this.id_proyecto
                })
                .then(res => {
                    this.crearasientoDetalle_Ingreso(res.data);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_Ingreso = false;
                });
        },
        crearasientoDetalle_Ingreso(id) {
            axios
                .post("/api/agregar/detalle/bodega_ingreso", {
                    bodegas: this.bodegas_ingreso,
                    cuentas: this.cuentas_ingreso,
                    // pagos_sin_plc:this.pagos_sin_plc,
                    // pagos_con_plc:this.pagos_con_plc,
                    // pagos_anticipo:this.pagos_anticipo,
                    // creditos:this.creditos,
                    // retencion_iva:this.retencion_iva,
                    // retencion_renta:this.retencion_renta,
                    ucrea: this.usuario.id,
                    id_asientos: id
                })
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Asiento Agregado",
                        text: "Asiento agregado con exito"
                    });

                    this.listaringresobodega(this.buscaringreso);
                    this.modalAsiento_Ing = false;
                    this.bodegas_ingreso = [];
                    this.cuentas_ingreso = [];
                    this.bodegas_egreso = [];
                    this.cuentas_egreso = [];
                    this.bodegas_eg_fact = [];
                    this.cuentas_eg_fact = [];
                    this.bodegas_trans = [];
                    this.cuentas_trans = [];
                    this.id_factura = "";
                    this.fecha_rol = "";
                    this.ruc_empresa = "";
                    this.razon_social = "";
                    this.concepto = "";
                    this.codigo = "";
                    this.id_proyecto = "";
                    this.codigo_asiento_comp = "";
                    this.contabilizado = null;
                    this.estado_asiento = "";
                    this.disabled_asiento_Ingreso = false;
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_Ingreso = false;
                });
        },
        Contabilidad_Transf(id) {
            this.bodegas_ingreso = [];
            this.cuentas_ingreso = [];
            this.bodegas_egreso = [];
            this.cuentas_egreso = [];
            this.bodegas_eg_fact = [];
            this.cuentas_eg_fact = [];
            this.bodegas_trans = [];
            this.cuentas_trans = [];
            this.tipo_trans = "";
            axios
                .get(
                    "/api/ver_asiento/bodega_transf/" +
                        id +
                        "?id_empresa=" +
                        this.usuario.id_empresa
                )
                .then(({ data }) => {
                    //var nros_factura=nombre_provs.join();
                    this.fecha_rol = moment(
                        data.info_bodega.f_iniciacion
                    ).format("Y-MM-DD");
                    var fecha = moment(this.fecha_rol).format("MMMM YYYY");
                    //  this.razon_social="Costo Venta";
                    //   this.ruc_empresa=data.proveedores[0].identificacion;
                    //   if(data.proveedores[0].tipo_identificacion=="Cédula de Identidad"){
                    //       this.tipo_identificacion="Cedula";
                    //   }else{
                    //       this.tipo_identificacion=data.proveedores[0].tipo_identificacion;
                    //   }
                    if (data.info_bodega.contabilidad == 1) {
                        this.codigo_asiento_comp = "BT-" + data.codigo_anterior;
                        this.contabilizado = data.info_bodega.contabilidad;
                    } else {
                        this.codigo_asiento_comp = "BT-" + data.codigo;
                        this.contabilizado = null;
                    }
                    this.concepto =
                        this.nombre + " " + data.info_bodega.observ_trans;
                    this.modalAsiento_Trans = true;
                    this.bodegas_trans = data.bodega;
                    this.cuentas_trans = data.cuenta;
                    this.id_factura = id;
                    this.id_proyecto = data.proyecto;
                    this.tipo_trans = "emisor";
                    this.estado_asiento = data.asiento_permitido;
                    // var referencia=data.ctas_pagar.referencia.split(';');
                    // var conteo_ref=referencia.length/4;
                    // var salto = 0;
                    // var factura=[];
                    // for(var f=0; f<conteo_ref; f++){
                    //   factura.push(referencia[0+salto]);
                    //   salto+=4;
                    // }
                    // this.modalAsiento_Trans=true;
                    // this.productos_asiento=data.proveedor;
                    // this.iva_asiento=data.forma_pago;
                    // if(this.iva_asiento.length>0){
                    //   this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                    //   this.nombre_pago=this.iva_asiento[0].nombre_pago;
                    //   this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                    //   this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                    //
                    // }

                    //console.log(data.ctas_pagar.contabilidad+"HOLA");
                })
                .catch(error => {
                    console.log(error);
                });
        },
        Contabilidad_Transf_Rec(id) {
            this.bodegas_ingreso = [];
            this.cuentas_ingreso = [];
            this.bodegas_egreso = [];
            this.cuentas_egreso = [];
            this.bodegas_eg_fact = [];
            this.cuentas_eg_fact = [];
            this.bodegas_trans = [];
            this.cuentas_trans = [];
            this.tipo_trans = "";
            axios
                .get(
                    "/api/ver_asiento/bodega_transf_recep/" +
                        id +
                        "?id_empresa=" +
                        this.usuario.id_empresa
                )
                .then(({ data }) => {
                    //var nros_factura=nombre_provs.join();
                    this.fecha_rol = moment(
                        data.info_bodega.f_iniciacion
                    ).format("Y-MM-DD");
                    var fecha = moment(this.fecha_rol).format("MMMM YYYY");
                    //  this.razon_social="Costo Venta";
                    //   this.ruc_empresa=data.proveedores[0].identificacion;
                    //   if(data.proveedores[0].tipo_identificacion=="Cédula de Identidad"){
                    //       this.tipo_identificacion="Cedula";
                    //   }else{
                    //       this.tipo_identificacion=data.proveedores[0].tipo_identificacion;
                    //   }
                    if (data.info_bodega.contabilidad == 1) {
                        this.codigo_asiento_comp = "BT-" + data.codigo_anterior;
                        this.contabilizado = data.info_bodega.contabilidad;
                    } else {
                        this.codigo_asiento_comp = "BT-" + data.codigo;
                        this.contabilizado = null;
                    }
                    this.concepto =
                        this.nombre + " " + data.info_bodega.observ_trans;
                    this.modalAsiento_Trans = true;
                    this.bodegas_trans = data.bodega;
                    this.cuentas_trans = data.cuenta;
                    this.id_factura = id;
                    this.id_proyecto = data.proyecto;
                    this.tipo_trans = "receptora";
                    this.estado_asiento = data.asiento_permitido;
                    // var referencia=data.ctas_pagar.referencia.split(';');
                    // var conteo_ref=referencia.length/4;
                    // var salto = 0;
                    // var factura=[];
                    // for(var f=0; f<conteo_ref; f++){
                    //   factura.push(referencia[0+salto]);
                    //   salto+=4;
                    // }
                    // this.modalAsiento_Trans=true;
                    // this.productos_asiento=data.proveedor;
                    // this.iva_asiento=data.forma_pago;
                    // if(this.iva_asiento.length>0){
                    //   this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                    //   this.nombre_pago=this.iva_asiento[0].nombre_pago;
                    //   this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                    //   this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                    //
                    // }

                    //console.log(data.ctas_pagar.contabilidad+"HOLA");
                })
                .catch(error => {
                    console.log(error);
                });
        },
        crearasiento_Trans(id, tipo) {
            this.disabled_asiento_Trans = true;
            var total = 0;
            total = this.total_debe - this.total_haber;
            console.log(total + ":total diferencia");
            if (this.validacion_asiento("transf")) {
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento"
                });
                this.disabled_asiento_Trans = false;
                return;
            }
            var total = 0;
            total = this.total_debe - this.total_haber;
            if (total !== 0) {
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento"
                });
                return;
            }
            if (this.estado_asiento == "no") {
                this.$vs.notify({
                    color: "danger",
                    title:
                        "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha"
                });
                this.disabled_asiento_Trans = false;
                return;
            }
            var codigo_asiento = this.codigo_asiento_comp.substr(
                3,
                this.codigo_asiento_comp.length
            );
            var fecha_hoy = new Date();
            axios
                .post("/api/agregar/bodega_trans", {
                    cod_rol: id,
                    numero: codigo_asiento,
                    codigo: this.codigo_asiento_comp,
                    fecha:
                        this.fecha_rol +
                        " " +
                        fecha_hoy.getHours() +
                        ":" +
                        fecha_hoy.getMinutes() +
                        ":" +
                        fecha_hoy.getSeconds(),
                    razon_social: this.razon_social,
                    tipo_identificacion: this.tipo_identificacion,
                    ruc_ci: this.ruc_empresa,
                    concepto: this.concepto,
                    ucrea: this.usuario.id,
                    id_proyecto: this.id_proyecto
                })
                .then(res => {
                    this.crearasientoDetalle_Transf(res.data, tipo);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_Trans = false;
                });
        },
        crearasientoDetalle_Transf(id, tipo) {
            axios
                .post("/api/agregar/detalle/bodega_trans", {
                    bodegas: this.bodegas_trans,
                    cuentas: this.cuentas_trans,
                    // pagos_sin_plc:this.pagos_sin_plc,
                    // pagos_con_plc:this.pagos_con_plc,
                    // pagos_anticipo:this.pagos_anticipo,
                    // creditos:this.creditos,
                    // retencion_iva:this.retencion_iva,
                    // retencion_renta:this.retencion_renta,
                    ucrea: this.usuario.id,
                    id_asientos: id
                })
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Asiento Agregado",
                        text: "Asiento agregado con exito"
                    });

                    if (tipo == "emisor") {
                        this.listartransebodega(this.buscartranse);
                    } else {
                        this.listartransrbodega(this.buscartransr);
                    }

                    this.modalAsiento_Trans = false;
                    this.bodegas_ingreso = [];
                    this.cuentas_ingreso = [];
                    this.bodegas_egreso = [];
                    this.cuentas_egreso = [];
                    this.bodegas_eg_fact = [];
                    this.cuentas_eg_fact = [];
                    this.bodegas_trans = [];
                    this.cuentas_trans = [];
                    this.id_factura = "";
                    this.fecha_rol = "";
                    this.ruc_empresa = "";
                    this.razon_social = "";
                    this.concepto = "";
                    this.codigo = "";
                    this.id_proyecto = "";
                    this.codigo_asiento_comp = "";
                    this.contabilizado = null;
                    this.tipo_trans = "";
                    this.estado_asiento = "";
                    this.disabled_asiento_Trans = false;
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento_Trans = false;
                });
        }
    },
    mounted() {
        this.listarbodegavista();
    }
};
</script>
<style lang="scss">
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
.theme-dark .vx-card select {
    background: #262c49;
}
.vs-tabs--btn {
    display: block !important;
}
.transpopup .vs-popup {
    width: 1400px !important;
}
.listpopup .vs-popup {
    width: 1000px !important;
}
.peque4 .vs-popup {
    width: 1080px !important;
}
</style>
