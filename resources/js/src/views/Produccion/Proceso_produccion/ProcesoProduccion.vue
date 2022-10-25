<template>
    <vx-card class="mt-10">
        <div class="flex flex-wrap justify-between items-center mb-3">
            <div class="flex flex-wrap justify-between ag-grid-table-actions-left">
                    <template v-if="filterstable.filtertab">
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
                                @input="filtertabla()"
                                v-model="filterstable.filt_asientos"
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
                                @input="filtertabla()"
                                v-model="filterstable.filt_noasientos"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >Orden-Emitida</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_orden"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >Proceso-Emitido</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_proces"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1">Liquidado</label>
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_liquid"
                            />
                        </div>
                    </template>
            </div>
            <div
                class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
            >
                <vs-input
                    class="mb-4 md:mb-0 mr-4"
                    v-model="buscar"
                    @keyup="listarformula(1, buscar)"
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
                                v-if="filterstable.filtertab == false"
                                color="primary"
                                type="filled"
                                icon="filter_list"
                                @click="filterstable.filtertab = true"
                            ></vs-button>
                            <vs-button
                                v-else
                                color="success"
                                type="filled"
                                icon="filter_list"
                                @click="
                                    (filterstable.filtertab = false),
                                        filtertabla()
                                "
                            ></vs-button
                        ></vx-tooltip>
                    </div>
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
                                @click="abrirCtaProduccion()"
                                >Cuenta Produccion</vs-dropdown-item
                            >
                            
                        </vs-dropdown-menu>
                    </vs-dropdown>
                </div>
                <div class="dropdown-button-container" v-if="crearrol">
                    <vs-button
                        class="btnx"
                        type="filled"
                        to="/produccion/proceso-produccion/agregar"
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
        <vs-table stripe max-items="25" pagination :data="contenido">
            <template slot="thead">
                <vs-th>Nº de Orden</vs-th>
                <vs-th class="text-center">Detalle</vs-th>
                <vs-th class="text-center">Estado</vs-th>
                <vs-th class="text-center">Produccion</vs-th>
                <vs-th class="text-center">Opciones</vs-th>
            </template>
            <template slot-scope="{ data }">
                <vs-tr :key="datos.id_proceso_produccion" v-for="datos in data">
                    <vs-td
                        v-if="datos.num_orden"
                        style="width:10%!important;"
                        >{{ datos.num_orden }}</vs-td
                    >
                    <vs-td v-else>-</vs-td>
                    <vs-td
                        class="text-center"
                        v-if="datos.detalle"
                        style="width:40%!important;"
                        >{{ datos.detalle }}</vs-td
                    >
                    <vs-td v-else>-</vs-td>
                    <vs-td
                        class="text-center"
                        style="color:#61B633; width:35%!important;"
                        v-if="datos.estado == 1"
                    >
                        <div>
                            Orden Emitida
                        </div>
                    </vs-td>
                    <vs-td
                        class="text-center"
                        style="color:#1065CB; width:35%!important;"
                        v-if="datos.estado == 2"
                    >
                        <div>
                            Proceso Emitido
                        </div>
                    </vs-td>
                    <vs-td
                        class="text-center"
                        style="color:#f28a2c; width:35%!important;"
                        v-if="datos.estado == 3"
                    >
                        <div>
                            Liquidado
                        </div>
                    </vs-td>
                    <vs-td
                        class="text-center"
                        style="color:#fb0303; width:35%!important;"
                        v-if="datos.estado_produccion == 'Inactivo'"
                    >
                        <div>
                            Anulado
                        </div>
                    </vs-td>
                    <vs-td
                        class="text-center"
                        style="color:#34fb03; width:35%!important;"
                        v-else
                    >
                        <div>
                            ACTIVO
                        </div>
                    </vs-td>

                    <vs-td
                        class="whitespace-no-wrap text-center"
                        style="width:10%!important;"
                    >
                        <feather-icon
                            v-if="datos.estado_produccion == 'Inactivo'"
                            icon="EyeIcon"
                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                            class="pointer"
                            @click.stop="verprod(datos.id_proceso_produccion)"
                        />
                        <feather-icon
                            v-else
                            icon="FileTextIcon"
                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                            class="pointer"
                            @click.stop="abrirprod(datos.id_proceso_produccion)"
                        />
                        <feather-icon
                            icon="DownloadIcon"
                            v-if="datos.estado==3"
                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                            class="pointer"
                            @click.stop="descargar_pdf(datos.id_proceso_produccion)"
                        />
                        <feather-icon
                            v-if="datos.estado_produccion !== 'Inactivo' && datos.contabilidad==null"
                            icon="TrashIcon"
                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                            class="pointer"
                            @click.stop="eliminarprod(datos.id_proceso_produccion,datos.estado)"
                        />
                        <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.contabilidad!==null && datos.estado==3 && datos.estado_produccion !== 'Inactivo'" svgClasses="w-5 h-5 fill-current text-success" @click="Contabilidad(datos.id_proceso_produccion)"/>
                            <feather-icon icon="SlidersIcon" class="cursor-pointer" v-else-if="datos.contabilidad==null && datos.estado==3 && datos.estado_produccion !== 'Inactivo'" svgClasses="w-5 h-5 fill-current text-primary" @click="Contabilidad(datos.id_proceso_produccion)"/>
                            <feather-icon icon="CheckIcon"  v-if="datos.contabilidad!==null" svgClasses="w-5 h-5"/>
                        <!--
            <feather-icon
              icon="TrashIcon"
              svgClasses="w-5 h-5 hover:text-primary stroke-current"
              class="pointer"
            />-->
                    </vs-td>
                </vs-tr>
            </template>
        </vs-table>
        <vs-popup title="Asiento Contable" :class="'peque3'" :active.sync="modalAsiento">
            <div class="vx-row">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Número:"
                                    :disabled="true"
                                    v-model="codigo"

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
                                    <vs-input
                                        class="w-full"
                                        v-model="fecha_rol"
                                        disabled
                                    />

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
                                    <vs-input
                                        class="w-full"
                                        v-model="concepto"
                                        disabled
                                    />

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
                        <label class="vs-input--label valoresc">Cuenta Contable</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label valoresc">Proyecto</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label valoresc">Debe</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label valoresc">Haber</label>
                    </div>
                </div>
            </div>
        </div>
         {{cambioDecimales}}
         {{sumar_iguales}}
        <!--{{sumar_iguales}}
        {{igualar}} -->
        <div
            id="one-row"
            class="vx-row"
            v-for="(data, index1) in productos_asiento"
            v-bind:key="index1"
        >

            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                      <div class="vx-col sm:w-1/3 w-full mb-6" v-if="productos_asiento[index1].sector=='producto' && productos_asiento[index1].iva=='doce'">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta_12"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="productos_asiento[index1].sector=='producto' && productos_asiento[index1].iva=='cero'">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta_0"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="productos_asiento[index1].sector=='servicio'">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta"
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
                        <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                            color="success"
                            @click="agregarcampoConciliacion(index,'cliente')"
                            >C</vs-button
                        >
                    </div>
                    </div>
            </div>

        </div>
        <div
            id="one-row"
            class="vx-row"
            v-for="(data, index1) in ingredientes"
            v-bind:key="index1"
        >

            <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="ingredientes[index1].nombre_cuenta"
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
                        <div class="vx-col sm:w-1/12 w-full mb-2" v-if="data.debe>0 && data.bansel!==null">
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                            color="success"
                            @click="agregarcampoConciliacion(index,'cliente')"
                            >C</vs-button
                        >
                    </div>
                    </div>
            </div>

        </div>

        <div
            id="two-row"
            class="vx-row"
            v-for="(data) in bodega"
            :key="data.id_plan_cuentas"
        >
        <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.debe>0">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta"
                                disabled
                            />

                        </vx-input-group>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.debe>0">
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

                    <div class="vx-col sm:w-1/2 w-full mb-6">

                    </div>

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

                    <div class="vx-col sm:w-1/2 w-full mb-6">

                    </div>

                    {{suma_debe}}
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                                class="w-full"
                                v-model="total_debe"
                                disabled
                            />
                    </div>
                 {{suma_haber}}
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
        <!-- {{Diferencia}} -->
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">

                    <div class="vx-col sm:w-1/2 w-full mb-6">

                    </div>

                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe>0">
                        <label class="vs-input--label center">Diferencia</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber>0">
                        <label class="vs-input--label center">Diferencia</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">

                    <div class="vx-col sm:w-1/2 w-full mb-6">

                    </div>


                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_debe>0">
                        <vs-input
                                class="w-full"
                                v-model="diferencia_debe"
                                disabled
                            />
                    </div>

                    <div class="vx-col sm:w-2/12 w-full mb-6" v-if="diferencia_haber>0">
                        <vs-input
                                class="w-full"
                                v-model="diferencia_haber"
                                disabled
                            />
                    </div>
                </div>
            </div>
        </div>
        <div v-if="contabilizado!==null">
          <h5> Este asiento ya ha sido registrado</h5>
        </div>
        <div v-else>
            <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled_asiento"
                    @click="crearasiento(id_factura)"
                    >GUARDAR</vs-button
                >
        </div>
        <vs-popup title="Conciliacion" :active.sync="modal_conciliacion">
                      <div
                            class="vx-row"

                        >
                            <div class="vx-col sm:w-1/4 w-full mb-6" >
                            <vs-input
                                    label="Fecha Pago"
                                    class="w-full"
                                    v-model="fecha_pago"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <vs-input
                                    label="Forma Pago"
                                    class="w-full"
                                    v-model="nombre_pago"
                                    disabled
                                />
                            </div>
                            <div class="vx-col sm:w-1/4 w-full mb-6">
                                <vs-input
                                    label="No Documento"
                                    class="w-full"
                                    v-model="nro_documento"
                                    disabled
                                />
                            </div>
                        </div>

                </vs-popup>
        </vs-popup>
        <vs-popup
            :class="'peque2a'"
            title="Cuenta Produccion"
            :active.sync="modal_cta"
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
                                    v-model="buscar_cuenta"
                                    @keyup="
                                        listarCtas_Produc(1, buscar_cuenta)
                                    "
                                    v-bind:placeholder="i18nbuscar_cuenta"
                                />
                                <div>
                                    <vs-button
                                        class="btnx"
                                        type="filled"
                                        divider
                                        @click="agregarCta_Producc()"
                                        >Agregar Nuevo</vs-button
                                    >
                                </div>
                            </div>
                        </div>
                        <vs-table stripe :data="ctas">
                            <template slot="thead">
                                <vs-th>Código</vs-th>
                                <vs-th>Nombre</vs-th>
                                <vs-th>Opciones</vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :key="datos.id_cuenta_produccion"
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
                                    <vs-td class="whitespace-no-wrap">
                                        <feather-icon
                                            icon="EditIcon"
                                            class="cursor-pointer"
                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                            @click.stop="
                                                verCta_Producc(
                                                    datos
                                                )
                                            "
                                        />
                                        <feather-icon
                                            icon="TrashIcon"
                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                            class="ml-2 cursor-pointer"
                                            @click.stop="
                                                eliminarCta_Producc(
                                                    datos.id_cuenta_produccion
                                                )
                                            "
                                        />
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                </div>
                <vs-popup :class="'peque3a'" title="Eliminar Cuenta" :active.sync="modal_eliminar_cta_transf">
                    <p>Desea eliminar Este reguistro</p>
                    <div class="vx-col w-full">
                        <br />
                        <vs-button
                            color="warning"
                            type="filled"
                            @click="
                                acceptAlert_Cta_Prod(idrecupera_cta)
                            "
                            >BORRAR</vs-button
                        >
                    </div>
                </vs-popup>
                <vs-popup
                    :class="'peque2a'"
                    :title="titulo_cuenta_produccion"
                    :active.sync="modal_agregar_cta"
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
                                        v-model="cod_cta_contable"
                                        :value="id_cta_contable"
                                        disabled
                                    />
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                            <vs-button
                                                color="primary"
                                                @click="
                                                    popupActive = true,listar3(1,'','','')
                                                "
                                                >Buscar</vs-button
                                            >
                                        </div>
                                    </template>
                                </vx-input-group>
                            </div>
                            <div class="vx-col sm:w-2/3 w-full mb-6">
                                <label for="" class="vs-input--label"
                                    >Nombre Cuenta:</label
                                >
                                <vs-input
                                    class="w-full"
                                    v-model="cta_contable"
                                    :value="id_cta_contable"
                                    disabled
                                />
                            </div>
                        </div>
                        <br />
                        <div class="vx-col w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="guardarCta()"
                                v-if="!idrecupera_cta"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="editarCta()"
                                v-else
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="warning"
                                type="filled"
                                @click="vaciarCta()"
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
                        :active.sync="popupActive"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="listarpc_cta"
                                @keyup="
                                    listar3(
                                        1,
                                        listarpc_cta,
                                        '',
                                        ''
                                    )
                                "
                                v-bind:placeholder="i18nbuscar_cuenta"
                            />
                            <vs-table
                                stripe
                                v-model="cuentaarray3"
                                @selected="handleSelected3"
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
const axios = require("axios");
import moment from "moment";
moment.locale("es");

export default {
    components: {
        AgGridVue
    },
    data() {
        return {
            //buscador
            buscar: "",
            i18nbuscar: this.$t("i18nbuscar"),

            eliminar: false,
            //listar
            contenido: [],
            //variables Contabilizar
            modalAsiento:false,
            disabled_asiento:false,
            nombre_proyecto:"",
            fecha_rol:"",
            ruc_empresa:"",
            razon_social:"",
            concepto:"",
            codigo:"",
            productos_asiento:[],
            iva_asiento:[],
            pagos_sin_plc:[],
            pagos_con_plc:[],
            pagos_anticipo:[],
            creditos:[],
            retencion_iva:[],
            retencion_renta:[],
            pagos:[],
            total_debe:"",
            total_haber:"",
            id_factura:"",
            id_proyecto:"",
            tipo_identificacion:"",
            contabilizado:null,
            modal_conciliacion:false,
            indextipoarreglo:"",
            anticipo_asiento:0,
            nombre_pago:"",
            id_pago:"",
            fecha_pago:"",
            nro_documento:"",
            diferencia_debe:0,
            diferencia_haber:0,
            id_forma_pago:"",
            estado_asiento:"",
            ingredientes:"",
            bodega:[],
            //variables cuenta produccion
            modal_cta:false,
            ctas:[],
            buscar_cuenta:"",
            i18nbuscar_cuenta: this.$t("i18nbuscar"),
            modal_agregar_cta:false,
            buscar2_cta:"",
            cod_cta_contable:"",
            id_cta_contable:"",
            cta_contable:"",
            popupActive:false,
            listarpc_cta:"",
            contenido3:[],
            cuentaarray3:"",
            idrecupera_cta:"",
            titulo_cuenta_produccion:"Agregar Cuenta",
            modal_eliminar_cta_transf:false,
            //fitrar tabla
            filterstable: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_orden: true,
                filt_proces: true,
                filt_liquid: true
            }
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
                res = this.$store.state.Roles[23].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[23].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[23].eliminar;
            }
            return res;
        },
        cambioDecimales(){
            if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el => {
                    
                        el.haber=parseFloat(el.haber).toFixed(2);
                    
                });
                console.log("Cambio Decimales producto");
            }
            if(this.ingredientes.length>0){
                this.ingredientes.forEach(el => {
                    
                        el.haber=parseFloat(el.haber).toFixed(2);
                    
                });
                console.log("Cambio Decimales ingredientes");
            }
        },
        sumar_iguales(){
            if(this.ingredientes.length>0){
                this.ingredientes = this.ingredientes.reduce((acumulador, valorActual) => {
                    const elementoYaExiste = acumulador.find(
                        elemento =>
                            elemento.id_proyecto === valorActual.id_proyecto &&
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
                }, []);
            }
        },
        suma_debe(){
            var total=0;
            if(this.bodega.length>0){
                this.bodega.forEach(el=>{
                    total+=Number(el.debe);
                }); 
            }
            this.total_debe=parseFloat(total).toFixed(2);
        },
        suma_haber(){
            var total=0;
            if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el=>{
                    total+=parseFloat(el.haber);
                })
            }
            if(this.ingredientes.length>0){
                this.ingredientes.forEach(el=>{
                    total+=parseFloat(el.haber);
                })
            }
            this.total_haber=parseFloat(total).toFixed(2);
            // if(total!==0){
            //     console.log("Bodega 1");
            //     if(this.bodega.length>0){
            //         console.log("Bodega");
            //         console.log(this.bodega[0]);
            //         this.bodega[0].debe=parseFloat(total);
            //         this.total_debe=parseFloat(total);
            //     }

            // }
        }
    },
    methods: {
        filtertabla() {
            var contvar = this.filterstable.contrue;
            var filt = "";
            if (this.filterstable.filtertab == true) {
                if (this.filterstable.filt_asientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == null
                    );
                }
                if (this.filterstable.filt_noasientos == false) {
                    contvar = contvar.filter(
                        contvar => contvar.contabilidad == 1
                    );
                }
                if (this.filterstable.filt_orden == true) {
                    filt =
                        filt +
                        'contvar.estado == 1';
                }
                if (this.filterstable.filt_proces == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt =
                        filt +
                        'contvar.estado == 2';
                }
                if (this.filterstable.filt_liquid == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt = filt + 'contvar.estado == 3';
                }
                contvar = contvar.filter(contvar => eval(filt));
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
                this.filterstable.filt_orden = true;
                this.filterstable.filt_proces = true;
                this.filterstable.filt_liquid = true;
            }
            this.contenido = contvar;
        },
        listarformula(page, buscar) {
            var url =
                "/api/traerprocesprod/" +
                this.usuario.id_empresa +
                "/" +
                this.usuario.id_establecimiento +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenido = respuesta.recupera;
                this.filterstable.contrue = respuesta.recupera;
                this.filtertabla();

            });
        },
        abrirprod(id) {
            this.$router.push(`/produccion/proceso-produccion/${id}/gestion`);
        },
        //ver produccion cuando la produccion esta anulada
        verprod(id){
            this.$router.push(`/produccion/proceso-produccion/${id}/ver`);
        },
        // funciones asientos
        Contabilidad(id){
            axios.get('/api/produccionvercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                        
                           this.fecha_rol=moment(data.produccion.fecha_fin).format("Y-MM-DD");
                        

                           var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                           this.razon_social=data.empresa.nombre_empresa;
                            this.ruc_empresa=data.empresa.identificaion_rep;
                          if(data.produccion.empresa=="Cédula de Identidad"){
                              this.tipo_identificacion="Cedula";
                          }else{
                              this.tipo_identificacion=data.empresa.tipo_identidicacion_empresa;
                          }
                          if(data.produccion.contabilidad==1){
                              this.codigo="PD-"+data.codigo_anterior;
                              this.contabilizado=data.produccion.contabilidad;
                          }else{
                              this.codigo="PD-"+data.codigo;
                              this.contabilizado=null;
                          }
                           this.concepto="Produccion "+data.produccion.detalle+" "+fecha;
                           //this.modalAsiento=true;
                           this.productos_asiento=data.productos;
                           this.ingredientes=data.ingredientes;
                           this.bodega=data.bodega;
                           this.id_factura=id;
                           if(this.bodega.length>0){
                               this.id_proyecto=this.bodega[0].id_proyecto;
                           }
                           this.Igualar();

                           console.log(JSON.stringify(data.bodega));
                        //   
                        //   this.iva_asiento=data.forma_pago;
                        //   if(this.iva_asiento.length>0){
                        //     if(this.iva_asiento[0].fecha_registro){

                        //       this.fecha_pago=moment(this.iva_asiento[0].fecha_registro).format("Y-MM-DD");
                        //     }else{

                        //       this.fecha_pago=moment(this.iva_asiento[0].fecha_pago).format("Y-MM-DD");
                        //     }
                        //     this.nombre_pago=this.iva_asiento[0].nombre_pago;
                        //     this.nro_documento=this.iva_asiento[0].nro_tarjeta;
                        //     this.id_forma_pago=this.iva_asiento[0].id_forma_pagos;
                        //     this.id_proyecto=this.iva_asiento[0].id_proyecto;
                        //   }
                        //   this.estado_asiento=data.asiento_permitido;
                        //   console.log(data.ctas_cobrar.contabilidad+"HOLA");
                        //console.log(data);
            }).catch( error => {
                console.log(error);
            });
            
        },
        Igualar(){
            this.IgualarHaber().then(val=>{
                this.modalAsiento=true;
            });
        },
        IgualarHaber(){
             return new Promise(resolve => {
                 var total0=0;
                if(this.productos_asiento.length>0){
                    this.productos_asiento.forEach(el=>{
                        total0+=Number(parseFloat(el.haber).toFixed(2));
                    })
                }
                if(this.ingredientes.length>0){
                    this.ingredientes.forEach(el=>{
                        total0+=Number(parseFloat(el.haber).toFixed(2));
                    })
                }
                console.log("Bodega Igual Haber 0");
                
                    console.log("Bodega Igual Haber 3");
                    this.bodega[0].debe=parseFloat(total0).toFixed(2);
                    console.log(this.bodega[0].debe);
                
                resolve(parseFloat(total0).toFixed(2));
             });
        },
        crearasiento(id){
            this.disabled_asiento=true;
            var total = 0;
            total = this.total_debe - this.total_haber;
            console.log("total diferencia:" + total);
            if (this.validacion_asiento()) {
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento"
                });
                this.disabled_asiento=false;
                return;
            }

            if (total !== 0) {
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento"
                });
                this.disabled_asiento=false;
                return;
            }
            var codigo_asiento = this.codigo.substr(3, this.codigo.length);
            var fecha_hoy = new Date();
            axios
                .post("/api/produccion/agregar/asiento", {
                    cod_rol: id,
                    numero: codigo_asiento,
                    codigo: this.codigo,
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
                    this.crearasientoDetalle(res.data);
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento=false;
                });
        },
        crearasientoDetalle(id) {
            axios
                .post("/api/produccion/agregar/asiento_detalle", {
                    proyecto: this.nombre_proyecto,
                    productos: this.productos_asiento,
                    ingredientes:this.ingredientes,
                    bodega:this.bodega,
                    ucrea: this.usuario.id,
                    id_asientos: id
                })
                .then(res => {
                    this.$vs.notify({
                        color: "success",
                        title: "Asiento Agregado",
                        text: "Asiento agregado con exito"
                    });
                    this.modalAsiento = false;
                    this.estado_asiento = "";
                    this.listarformula(1, "");
                    this.disabled_asiento=false;
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    this.disabled_asiento=false;
                });
        },
        validacion_asiento(){
            var error=0;
            if(this.ingredientes.length>0){
                this.ingredientes.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                        this.$vs.notify({
                            color: "danger",
                            title:
                                "No existe Plan Cuentas"
                        });
                        console.log("No existe Plan Cuentas Cuenta Bodega");
                    }
                    if(el.id_proyecto==null){
                        error++;
                        this.$vs.notify({
                            color: "danger",
                            title:
                                "No existe Proyecto"
                        });
                        console.log("No existe Plan Cuentas Cuenta Bodega");
                    }
                });
                    
            }else{
                error++;
                this.$vs.notify({
                    color: "danger",
                    title:
                        "No existe Cuenta Bodega"
                });
            }
            if(this.bodega.length>0){
                this.bodega.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                        this.$vs.notify({
                            color: "danger",
                            title:
                                "No existe Plan Cuentas"
                        });
                        console.log("No existe Plan Cuentas Cuenta Produccion");
                    }
                    if(el.id_proyecto==null){
                        error++;
                        this.$vs.notify({
                            color: "danger",
                            title:
                                "No existe Proyecto"
                        });
                        console.log("No existe Plan Cuentas Cuenta Produccion");
                    }
                });
            }
            return error;
        },
        ////////////
        // funciones ctas produccion
        abrirCtaProduccion(){
            this.modal_cta=true;
            this.listarCtas_Produc(1, "");

        },
        listarCtas_Produc(page, buscar2) {
            let me = this;
            var url =
                "/api/cuenta_produccion/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.ctas = respuesta;
                    console.log(respuesta);
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        agregarCta_Producc(){
            this.cod_cta_contable="";
            this.id_cta_contable="";
            this.cta_contable="";
            this.idrecupera_cta=null;
            this.modal_agregar_cta=true;
            this.listar3(1,'','','');
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
        handleSelected3(tr){
                (this.cta_contable = `${tr.nomcta}`),
                (this.id_cta_contable = `${tr.id_plan_cuentas}`),
                (this.cod_cta_contable = `${tr.codcta}`),
                (this.popupActive = false);
        },
        guardarCta(){
            axios
                .post("/api/agregarcuenta_produccion", {
                    cod_cuenta: this.cod_cta_contable,
                    nombre_cuenta: this.cta_contable,
                    id_plan_cuentas: this.id_cta_contable,
                    ucrea: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive = false;
                    this.modal_agregar_cta= false;
                    this.$vs.notify({
                        title: "Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.cod_cta_contable="";
                    this.id_cta_contable="";
                    this.cta_contable="";
                    this.idrecupera_cta=null;
                    this.listarCtas_Produc(1, "");
                    this.vaciarCta();
                })
                .catch(err => {});
        },
        listarCtas(){

        },
        vaciarCta(page,buscar){
            this.cod_cta_contable="";
                    this.id_cta_contable="";
                    this.cta_contable="";
                    this.idrecupera_cta=null;
        },
        editarCta(){
            axios
                .put("/api/editarcuenta_produccion", {
                    id:this.idrecupera_cta,
                    cod_cuenta: this.cod_cta_contable,
                    nombre_cuenta: this.cta_contable,
                    id_plan_cuentas: this.id_cta_contable,
                    umodifica: this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.popupActive = false;
                    this.modal_agregar_cta= false;
                    this.$vs.notify({
                        title: "Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.cod_cta_contable="";
                    this.id_cta_contable="";
                    this.cta_contable="";
                    this.idrecupera_cta=null;
                    this.listarCtas_Produc(1, "");
                    this.vaciarCta();
                })
                .catch(err => {});
        },
        verCta_Producc(datos){
            this.cod_cta_contable=datos.cod_cuenta;
            this.id_cta_contable=datos.id_cuenta_produccion;
            this.cta_contable=datos.nombre_cuenta;
            this.idrecupera_cta=datos.id_cuenta_produccion;
            this.titulo_cuenta_produccion="Editar Cuenta";
            this.modal_agregar_cta=true;

        },
        eliminarCta_Producc(cd){
            this.modal_eliminar_cta_transf=true;
            this.idrecupera_cta=cd;
        },
        acceptAlert_Cta_Prod(parameters) {
            axios
                .post("/api/eliminarcuenta_produccion", { datos: parameters })
                .then(() => {
                    this.modal_eliminar_cta_transf=false;
                    this.$vs.notify({
                        color: "danger",
                        title: "Registro Eliminado",
                        text: "La Registro selecionado fue eliminado con exito"
                    });

                    this.idrecupera_cta=null;
                    this.listarCtas_Produc(1, "");
                });
        },
        //
        //funcion descargar pdf
        async descargar_pdf(id){
            try {
                let resp = await axios({
                    url: "/api/pdf_produccion/" + id,
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
        //eliminar proceso produccion
        eliminarprod(id) {
            //console.log("1", id);
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `¿Desea Anular este Proceso Produccion?`,
                text: `Este Proceso Produccion sera Anulado del sistema`,
                acceptText: "Aceptar",
                cancelText: "Anular",
                accept: this.aceptarborrado,
                parameters: id
            });
        },
        aceptarborrado(parameters) {
            axios
                .post("/api/eliminarproceso_produccion", {
                    datos: parameters,
                    id_pto: this.usuario.id_punto_emision
                })
                .then(() => {
                    this.$vs.notify({
                        color: "warning",
                        title: "Comprobante Cancelado",
                        text:
                            "El comprobante selecionado fue cancelado con exito"
                    });
                    this.listarformula(1, this.buscar);
                });
        },
    },

    mounted() {
        this.listarformula(1, this.buscar);
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
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.full .vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
</style>
