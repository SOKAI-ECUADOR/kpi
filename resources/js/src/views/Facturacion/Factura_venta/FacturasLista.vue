<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-left"
                >
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
                                >Autorizados</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_autoris"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1"
                                >No-autorizados</label
                            >
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_noautoris"
                            />
                        </div>
                        <div
                            class="flex flex-wrap justify-between items-center mb-1 mr-5"
                        >
                            <label class="vs-input--label mr-1">Anulados</label>
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_anul"
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
                        @keyup="listar(1, buscar)"
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
                                                            @click="abrirmodalinteres"
                                                            >Interes</vs-dropdown-item
                                                        >
                                                        <vs-dropdown-item
                                                            class="text-center"
                                                            divider
                                                            @click="abrirmodalinteres_Anual"
                                                            >Interes Anual</vs-dropdown-item
                                                        >
                                                    </vs-dropdown-menu>
                                                </vs-dropdown>
                    </div>
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
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/facturacion/factura-venta/agregar"
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
                                    @click="generarreporte = true"
                                    >Generar reporte</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="abrirmodalfact_masiva"
                                    >Facturación Masiva</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    v-if="usuario.id_empresa == 50"
                                    class="text-center"
                                    @click="ejemploticket"
                                    >Factura Ticket</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe max-items="25" pagination :data="contenido">
                <template slot="thead">
                    <vs-th style="width: 15em;">No.</vs-th>
                    <vs-th>Cliente</vs-th>
                    <vs-th>Fecha de Emisión</vs-th>
                    <vs-th>Fecha de Autorización</vs-th>
                    <vs-th class="text-center">Valor Total</vs-th>
                    <vs-th class="text-center">Estado</vs-th>
                    <vs-th class="text-center">Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr v-for="(datos, index) in data" :key="index">
                        <vs-td v-if="datos.clave_acceso"
                            >{{ datos.clave_acceso.substring(24, 27) }}-{{
                                datos.clave_acceso.substring(27, 30)
                            }}-{{ datos.clave_acceso.substring(30, 39) }}</vs-td
                        ><vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td
                        ><vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.fecha_emision">{{
                            datos.fecha_emision | fecha
                        }}</vs-td
                        ><vs-td v-else>-</vs-td>
                        <vs-td
                            v-if="
                                datos.fecha_autorizacion &&
                                    datos.respuesta == 'Enviado'
                            "
                            >{{ datos.fecha_autorizacion | fechayhora }}</vs-td
                        >
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.valor_total" class="text-center">{{
                            datos.valor_total | currency
                        }}</vs-td>
                        <vs-td v-else class="text-center">-</vs-td>
                        <vs-td
                            v-if="datos.estadof == 0"
                            style="color: rgb(255, 0, 0);font-weight: bold;"
                            class="text-center"
                            >ANULADO</vs-td
                        >
                        <vs-td
                            v-else-if="datos.respuesta == 'Enviado'"
                            style="color: #28c76f;font-weight: bold;"
                            class="text-center"
                            >AUTORIZADO
                            <template v-if="datos.guia">
                                <span
                                    style="display: block;font-size: 10px;position: relative;color: rgb(40, 199, 111); font-weight: bold;"
                                    class="mt-2"
                                    v-if="datos.respuesta_guia == 'Enviado'"
                                    ><span
                                        style="position: absolute; top: -10px; font-size: 8px; left:25%"
                                        >guia remisión</span
                                    >AUTORIZADO</span
                                >
                                <span
                                    style="display: block;font-size: 10px;position: relative;color: rgb(206, 124, 59); font-weight: bold;"
                                    class="mt-2"
                                    v-if="datos.respuesta_guia != 'Enviado'"
                                    ><span
                                        style="position: absolute; top: -10px; font-size: 8px; left:25%"
                                        >guia remisión</span
                                    >NO AUTORIZADO</span
                                >
                            </template>
                        </vs-td>
                        <vs-td
                            v-else
                            style="color: rgb(206, 124, 59);font-weight: bold;"
                            class="text-center"
                        >
                            <template v-if="datos.mensaje_sri">
                                <vx-tooltip
                                    :title="datos.mensaje_sri"
                                    :text="datos.informacion_sri"
                                    position="left"
                                    class="pointer"
                                >
                                    NO AUTORIZADO
                                </vx-tooltip>
                            </template>
                            <template v-else>
                                <vx-tooltip
                                    title="Error en el servicio del SRI"
                                    text="El SRI se encuentra fuera de servicio, intente mas tarde"
                                    position="left"
                                    class="pointer"
                                >
                                    NO AUTORIZADO
                                </vx-tooltip>
                            </template>
                            <template v-if="datos.guia">
                                <span
                                    style="display: block;font-size: 10px;position: relative;color: rgb(40, 199, 111); font-weight: bold;"
                                    class="mt-2"
                                    v-if="datos.respuesta_guia == 'Enviado'"
                                    ><span
                                        style="position: absolute; top: -10px; font-size: 8px; left:25%"
                                        >guia remisión</span
                                    >AUTORIZADO</span
                                >
                                <span
                                    style="display: block;font-size: 10px;position: relative;color: rgb(206, 124, 59); font-weight: bold;"
                                    class="mt-2"
                                    v-if="datos.respuesta_guia != 'Enviado'"
                                    ><span
                                        style="position: absolute; top: -10px; font-size: 8px; left:25%"
                                        >guia remisión</span
                                    >NO AUTORIZADO</span
                                >
                            </template>
                        </vs-td>
                        <vs-td
                            class="whitespace-no-wrap text-center estilosacciones"
                        >
                            <vs-dropdown vs-custom-content vs-trigger-click>
                                <vs-button
                                    class="btn-drop"
                                    type="filled"
                                    icon="expand_more"
                                    >Acciones</vs-button
                                >
                                <vs-dropdown-menu style="width:13em;">
                                    <vs-dropdown-item
                                        class="text-center"
                                        @click.stop="enviocorreo(datos)"
                                        v-if="
                                            datos.respuesta == 'Enviado' &&
                                                editarrol &&
                                                datos.estadof != 0
                                        "
                                        ><feather-icon
                                            icon="MailIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Enviar al correo</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        class="text-center"
                                        @click.stop="facturaenvio(datos)"
                                        v-if="
                                            datos.respuesta != 'Enviado' &&
                                                editarrol &&
                                                datos.estadof != 0
                                        "
                                        ><feather-icon
                                            icon="SendIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Reenviar</vs-dropdown-item
                                    >

                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click.stop="enviocorreootro(datos)"
                                        ><feather-icon
                                            icon="MailIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Enviar a un correo</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click.stop="descargarpdf(datos)"
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Descargar PDF
                                    </vs-dropdown-item>
                                    <vs-dropdown-item
                                        v-if="usuario.id_empresa == 59"
                                        divider
                                        class="text-center"
                                        @click.stop="verpdf(datos)"
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Ver PDF
                                    </vs-dropdown-item>
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click.stop="descargarxml(datos)"
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Descargar XML</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        v-if="datos.estadof > 0"
                                        divider
                                        class="text-center"
                                        @click.stop="
                                            descargarxmlSRI(
                                                datos,
                                                datos.respuesta
                                            )
                                        "
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        XML SRI</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click.stop="descargarpdfyxml(datos)"
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Desc. PDF y XML</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        v-if="usuario.id_empresa == 71"
                                        @click="descargarfactura_fisica(datos)"
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Descargar Factura Fisica</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        v-if="
                                            usuario.id_empresa == 60 ||
                                                usuario.id_empresa == 55 ||
                                                usuario.id_empresa == 68 ||
                                                (usuario.id_empresa == 50 &&
                                                    usuario.id_punto_emision ==
                                                        43)
                                        "
                                        @click="enviarticket(datos)"
                                        ><feather-icon
                                            icon="PrinterIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Imprimir Ticket</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        v-else-if="usuario.id_rol!==2 && usuario.id_empresa == 50"
                                        @click="enviarticket(datos)"
                                        ><feather-icon
                                            icon="PrinterIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Imprimir Ticket</vs-dropdown-item
                                    >
                                    <!--<vs-dropdown-item divider class="text-center" @click.stop="enviocorreootro(datos)" v-if="datos.respuesta == 'Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Enviar a un correo</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click.stop="descargarpdf(datos)" v-if="datos.respuesta == 'Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click.stop="descargarxml(datos)" v-if="datos.respuesta == 'Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar XML</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click.stop="descargarpdfyxml(datos)" v-if="datos.respuesta == 'Enviado' && editarrol && datos.estadof != 0"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Desc. PDF y XML</vs-dropdown-item>-->

                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click="ver(datos.id_factura)"
                                        v-if="
                                            (datos.respuesta == 'Enviado' &&
                                                datos.estadof != 0) ||
                                                (datos.estadof != 0 &&
                                                    datos.contabilidad != null)
                                        "
                                        ><feather-icon
                                            icon="EyeIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Visualizar</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        class="text-center"
                                        @click="ver(datos.id_factura)"
                                        v-if="datos.estadof == 0"
                                        ><feather-icon
                                            icon="EyeIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Visualizar</vs-dropdown-item
                                    >

                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click="editar(datos.id_factura)"
                                        v-if="
                                            datos.estadof != 0 &&
                                                datos.contabilidad == null
                                        "
                                        ><feather-icon
                                            icon="EditIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Editar</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click="duplicar(datos)"
                                        ><feather-icon
                                            icon="BookIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Duplicar Fatura</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click="eliminar(datos)"
                                        v-if="eliminarrol && datos.estadof != 0"
                                        ><feather-icon
                                            icon="TrashIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Anular</vs-dropdown-item
                                    >

                                    <vs-divider
                                        position="center"
                                        style="margin: 10px 0px;font-size: 16px;"
                                        v-if="datos.guia && datos.estadof != 0"
                                        >Guia
                                    </vs-divider>
                                    <vs-dropdown-item
                                        class="text-center"
                                        @click="guiaenvio(datos.guia)"
                                        v-if="
                                            datos.guia &&
                                                datos.estadof != 0 &&
                                                datos.respuesta_guia !=
                                                    'Enviado'
                                        "
                                        ><feather-icon
                                            icon="MailIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Reenviar Guia
                                    </vs-dropdown-item>
                                    <vs-dropdown-item
                                        class="text-center"
                                        @click.stop="
                                            enviocorreootro_guia(datos),
                                                (tipoenvio = 2)
                                        "
                                        v-if="
                                            datos.guia &&
                                                datos.estadof != 0 &&
                                                datos.respuesta_guia ==
                                                    'Enviado'
                                        "
                                        ><feather-icon
                                            icon="MailIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Enviar a un correo</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click="descargarpdf_guia(datos)"
                                        v-if="
                                            datos.guia &&
                                                datos.estadof != 0 &&
                                                datos.respuesta_guia ==
                                                    'Enviado'
                                        "
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Descargar PDF</vs-dropdown-item
                                    >
                                    
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click.stop="descargarxml_guia(datos)"
                                        v-if="
                                            datos.guia &&
                                                datos.estadof != 0 &&
                                                datos.respuesta_guia ==
                                                    'Enviado'
                                        "
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Descargar XML</vs-dropdown-item
                                    >
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click.stop="
                                            descargarpdfyxml_guia(datos)
                                        "
                                        v-if="
                                            datos.guia &&
                                                datos.estadof != 0 &&
                                                datos.respuesta_guia ==
                                                    'Enviado'
                                        "
                                        ><feather-icon
                                            icon="DownloadIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Desc. PDF y XML</vs-dropdown-item
                                    >
                                </vs-dropdown-menu>
                            </vs-dropdown>
                            <feather-icon
                                icon="SlidersIcon"
                                class="cursor-pointer"
                                v-if="
                                    datos.estadof != 0 &&
                                        datos.contabilidad == null
                                "
                                svgClasses="w-5 h-5 fill-current text-primary"
                                @click="Contabilidad(datos.id_factura)"
                            />
                            <feather-icon
                                icon="SlidersIcon"
                                class="cursor-pointer"
                                v-if="
                                    datos.estadof != 0 &&
                                        datos.contabilidad == 1
                                "
                                svgClasses="w-5 h-5 fill-current text-success"
                                @click="Contabilidad(datos.id_factura)"
                            />
                            <feather-icon
                                icon="CheckIcon"
                                v-if="
                                    datos.estadof != 0 &&
                                        datos.contabilidad !== null
                                "
                                svgClasses="w-5 h-5"
                            />
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <vs-popup title="Enviar a un correo" class="peque" :active.sync="modal">
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full">
                    <vs-input
                        label="Destinatario:"
                        class="mb-4 md:mb-0 mr-4"
                        v-model="destinatario"
                    />
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full">
                    <vs-input
                        label="Email:"
                        class="mb-4 md:mb-0 mr-4"
                        v-model="empleado_email"
                    />
                </div>
            </div>
            <br />
            <div>
                <vs-button color="success" @click="sendData" type="filled"
                    >Generar</vs-button
                >
            </div>
        </vs-popup>
        <vs-popup
            classContent="popup-example"
            title="Generar Reporte"
            :active.sync="generarreporte"
        >
            <div class="vx-row">
                <div class="vx-col sm:w-full mb-6">
                    <label class="vs-input--label"
                        >Defina el tipo de búsqueda</label
                    >
                    <vs-select
                        placeholder="Escoga el tipo de búsqueda"
                        @change="recargar_reporte()"
                        class="selectExample w-full"
                        vs-multiple
                        v-model="tipo_busqueda"
                    >
                        <vs-select-item value="1" text="Fechas" />
                        <vs-select-item value="2" text="Cliente" />
                        <vs-select-item value="3" text="Vendedor" />
                        <vs-select-item value="4" text="Producto" />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-full mb-6" v-if="tipo_busqueda == 1">
                    <label class="vs-input--label"
                        >Escoga el rango de fechas</label
                    >
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 mb-3">
                            <label class="vs-input--label mt-3"
                                >Fecha de inicio</label
                            >
                            <flat-pickr
                                :config="configdateTimePicker"
                                class="w-full mt-1"
                                v-model="dateinicio"
                                placeholder="Seleccionar"
                            ></flat-pickr>
                        </div>
                        <div class="vx-col sm:w-1/2 mb-3">
                            <label class="vs-input--label mt-3"
                                >Fecha final</label
                            >
                            <flat-pickr
                                :config="configdateTimePicker"
                                class="w-full mt-1"
                                v-model="datefin"
                                placeholder="Seleccionar"
                            ></flat-pickr>
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-full mb-6" v-if="tipo_busqueda == 2">
                    <label class="vs-input--label">Escoga el cliente</label>
                    <vs-select
                        placeholder="Escoga el cliente"
                        class="selectExample w-full"
                        vs-multiple
                        v-model="cliente_busqueda"
                    >
                        <vs-select-item
                            v-for="data in clientes2"
                            :key="data.id_cliente"
                            :value="data.id_cliente"
                            :text="data.nombre"
                        />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-full mb-6" v-if="tipo_busqueda == 3">
                    <label class="vs-input--label">Escoga el vendedor</label>
                    <vs-select
                        placeholder="Escoga el vendedor"
                        class="selectExample w-full"
                        vs-multiple
                        v-model="vendedor_busqueda"
                    >
                        <vs-select-item
                            v-for="data in vendedores2"
                            :key="data.id"
                            :value="data.id"
                            :text="data.nombres + ' ' + data.apellidos"
                        />
                    </vs-select>
                </div>
                <div class="vx-col w-full mt-6">
                    <vs-button
                        color="success"
                        type="filled"
                        @click="reportes_factura()"
                        >GENERAR</vs-button
                    >
                    <vs-button
                        color="danger"
                        type="filled"
                        @click="generarreporte = false"
                        >CANCELAR</vs-button
                    >
                </div>
            </div>
        </vs-popup>
        <vs-popup title="Destinatario de Correo" :active.sync="popupcorreo">
            <!--<div class="vx-col sm:w-full w-full mb-6 relative">
                <vs-chips color="rgb(145, 32, 159)" placeholder="Agregue los nombres" v-model="chip_nombre" icon-pack="feather" remove-icon="icon-trash-2">
                    <vs-chip :key="data" @click="remove_chip_nombre(data)" v-for="data in chip_nombre" closable icon-pack="feather" close-icon="icon-trash-2">
                        {{ data }}}
                    </vs-chip>
                </vs-chips>
                <div v-show="errorenvio">
                    <div v-for="err in errornombrecliente" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>-->
            <div class="vx-col sm:full w-full mb-6 relative">
                <vs-chips
                    color="rgb(145, 32, 159)"
                    placeholder="Agregue los correos"
                    v-model="chip_correo"
                    icon-pack="feather"
                    remove-icon="icon-trash-2"
                >
                    <vs-chip
                        :key="data"
                        @click="remove_chip_correo(data)"
                        v-for="data in chip_correo"
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
                        v-for="err in errorchip_correo"
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
                    v-if="estadocorreo == 1"
                    @click="enviocorreootros()"
                    >Enviar</vs-button
                >
                <vs-button
                    color="success"
                    type="border"
                    v-else
                    @click="enviocorreootros_guia()"
                    >Enviar</vs-button
                >
                <vs-button color="danger" type="border" @click="cancelarenvio()"
                    >Cancelar</vs-button
                >
            </div>
        </vs-popup>
        <vs-popup
            title="Asiento Contable"
            class="peque2"
            :active.sync="modalAsiento"
        >
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
            <div
                id="one-row"
                class="vx-row"
                v-for="(add, index1) in productos_asiento"
                v-bind:key="index1"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="
                                productos_asiento[index1].sector ==
                                    'producto' &&
                                    productos_asiento[index1].iva == 'doce'
                            "
                        >
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta_12"
                                disabled
                            />
                            </vx-input-group>
                        </div>
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="
                                productos_asiento[index1].sector ==
                                    'producto' &&
                                    productos_asiento[index1].iva == 'cero'
                            "
                        >
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                class="w-full"
                                v-model="productos_asiento[index1].nombre_cuenta_0"
                                disabled
                            />
                            </vx-input-group>
                        </div>
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="
                                productos_asiento[index1].sector == 'servicio'
                            "
                        >
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
                                v-model="productos_asiento[index1].descripcion"
                                disabled
                            />
                        </div>
                        <!--DEBE-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full valores"
                            v-model="productos_asiento[index1].debe"
                            disabled
                        />
                    </div>
                        <!--HABER-->
                        <!-- prettier-ignore -->
                        <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full"
                            v-model="productos_asiento[index1].haber"
                            disabled

                        />
                    </div>

                        <!--FECHA BANCO-->
                        <!-- prettier-ignore -->
                        <!--<div
                        class="vx-col sm:w-2/12 w-full mb-6"
                        v-if="listaAsientoscontables[index].detalle.fecha && listaAsientoscontables[index].detalle.haber"
                    >
                        <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="listaAsientoscontables[index].detalle.fecha"
                            placeholder="Elegir Fecha de pago"
                        />
                    </div>-->
                    </div>
                </div>
            </div>
            <div
                id="ice-row"
                class="vx-row"
                v-for="data in ice"
                :key="data.id_detalle"
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
            <div
                id="two-row"
                class="vx-row"
                v-for="data in iva_asiento"
                :key="data.id_detalle"
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

            <div
                id="fig-row"
                class="vx-row"
                v-for="(data, index) in pagos_sin_plc"
                :key="data.id_plan_cuentas"
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
                        <div class="vx-col sm:w-2/12 w-full mb-2" v-if="data.debe>0">
                        <vs-input
                            class="w-full"
                            v-model="data.haber"
                            disabled

                        />
                    </div>
                        <div
                            class="vx-col sm:w-1/12 w-full mb-2"
                            v-if="data.debe > 0 && data.bansel !== null"
                        >
                            <vs-button
                                type="filled"
                                style="height: 1.5em;padding: 0px;width: 1.5em; margin:1px 1px;"
                                color="success"
                                @click="
                                    agregarcampoConciliacion(
                                        index,
                                        'forma_pago'
                                    )
                                "
                                >C</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div
                id="fig2-row"
                class="vx-row"
                v-for="(data, index) in pagos_con_plc"
                :key="data.id_plan_cuentas"
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
                        <div
                            class="vx-col sm:w-1/12 w-full mb-6"
                            v-if="data.debe > 0 && data.bansel !== null"
                        >
                            <vs-button
                                type="filled"
                                style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                color="success"
                                @click="agregarcampoConciliacion(index, 'plc')"
                                >C</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div
                id="fig-row"
                class="vx-row"
                v-for="(data, index) in pagos_anticipo"
                :key="data.id_plan_cuentas"
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
                        <div
                            class="vx-col sm:w-1/12 w-full mb-6"
                            v-if="data.debe > 0 && data.bansel !== null"
                        >
                            <vs-button
                                type="filled"
                                style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                color="success"
                                @click="
                                    agregarcampoConciliacion(index, 'anticipo')
                                "
                                >C</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div
                id="tree-row"
                class="vx-row"
                v-for="data in creditos"
                :key="data.id_cliente"
            >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                    <div class="vx-row">
                        <!--CUENTA CONTABLE-->
                        <div
                            class="vx-col sm:w-1/3 w-full mb-6"
                            v-if="data.exist_plc_cl == 'no'"
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
                        <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                            <vx-input-group>
                                <!-- prettier-ignore -->
                                <vs-input
                                class="w-full"
                                v-model="data.nombre_cuenta_cl"
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
                    </div>
                </div>
            </div>
            <div
                id="four-row"
                class="vx-row"
                v-for="data in retencion_iva"
                :key="data.id_plan_cuentas"
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
            <div
                id="five-row"
                class="vx-row"
                v-for="data in retencion_renta"
                :key="data.id_plan_cuentas"
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
            {{Diferencia}}
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
                    :disabled="disabled_asiento"
                    @click="crearasiento(id_factura)"
                    >GUARDAR</vs-button
                >
            </div>
            <vs-popup title="Conciliacion" :active.sync="modal_conciliacion">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/4 w-full mb-6">
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
        <vs-popup title="Escoja la impresora" :active.sync="modal_impresora">
            <vs-select
                placeholder="Seleccione"
                class="selectExample w-full"
                label="Comprobante:"
                vs-multiple
                autocomplete
                v-model="nombre_impresora"
            >
                <vs-select-item
                    :key="index"
                    :value="index"
                    :text="index"
                    v-for="index in impresoras"
                />
            </vs-select>
            <br />
            <div>
                <vs-button
                    color="success"
                    type="filled"
                    @click="imprimir(nombre_impresora)"
                    >Imprimir</vs-button
                >
                <vs-button
                    color="danger"
                    type="filled"
                    @click="cancelar_impresion()"
                    >Cancelar</vs-button
                >
            </div>
        </vs-popup>
        <vs-popup
            class="modalist"
            title="Factuarión Masiva"
            :active.sync="modalfact_masiva"
        >
            <FacturasMasiva
        /></vs-popup>
        <vs-popup  title="Tabla Interes" :active.sync="modalinteres">
            <vx-card>
                    <div class="flex flex-wrap justify-between items-center mb-3">
                        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
                        </div>
                        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar_interes" @keyup="listar_interes(1, buscar_interes)" v-bind:placeholder="i18nbuscar"/>
                                        
                                <div>
                                    <vs-button class="btnx" type="filled" @click="agregarmodal_interes()">Agregar</vs-button>
                                </div>
                        </div>
                    </div>
                    <vs-table stripe  :data="contenido_interes">
                        <template slot="thead">

                            <vs-th>Mes</vs-th>
                            <vs-th style="width:80%">Interes</vs-th>
                            
                            <vs-th>Opciones</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(datos, index) in data" :key="index">
                                <vs-td v-if="datos.codigo_periodo">{{datos.codigo_periodo}}</vs-td><vs-td v-else>-</vs-td>
                                <vs-td v-if="datos.interes">{{ datos.interes }}</vs-td><vs-td v-else>-</vs-td>
                                
                                
                                
                                <vs-td class="whitespace-no-wrap  estilosacciones">
                                    <feather-icon  icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="editarmodal_interes(datos.id_tabla_interes,datos.interes)" />
                                    <!-- <feather-icon  icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="eliminar_interes(datos.id_tabla_interes)"/> -->
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
            </vx-card>
            <vs-popup  title="Eliminar Iteres" :active.sync="modalEliminar_Interes">
                <label for="" class="vs-input--label">Desea Eliminar este Registro</label>
                <div class="vx-col w-full">
                            <vs-button color="success" type="filled" @click="eliminar_interes_accept(id_interesrecupera)">ACEPTAR</vs-button>
                            <vs-button color="danger"  type="filled" @click="cancelar_interes_eliminar()">CANCELAR</vs-button>
                </div>
            </vs-popup>
            <vs-popup  title="Agregar Iteres" :active.sync="modalAgregar_Interes">
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-col sm:w-1/3 w-full mb-6">
                                <label for="" class="vs-input--label">Interes %:</label>
                                <vs-input class="w-full" v-model="interes"/>
                                <div v-show="errorinteres" v-if="!interes">
                                    <span
                                        class="text-danger"
                                        v-for="err in error_interes"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                        </div>
                        <div class="vx-col w-full">
                            <vs-button color="success" type="filled" :disabled="disabled_interes" @click="guardar_interes()" v-if="id_interesrecupera==null">GUARDAR</vs-button>
                            <vs-button color="success" type="filled" @click="editar_interes()" v-else>GUARDAR</vs-button>
                            <vs-button color="warning" type="filled" @click="vaciar_interes()">BORRAR</vs-button>
                            <vs-button color="danger"  type="filled" @click="cancelar_interes()">CANCELAR</vs-button>
                        </div>
                    </div>

            </vs-popup>
        </vs-popup>
        <vs-popup  title="Interes Anual" :active.sync="modalinteres_anual">
            <vx-card>
                    <div class="flex flex-wrap justify-between items-center mb-3">
                        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
                        </div>
                        <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar_interes_anual" @keyup="listar_interes_anual(1, buscar_interes_anual)" v-bind:placeholder="i18nbuscar"/>
                                        
                                <div>
                                    <vs-button class="btnx" type="filled" @click="agregarmodal_interes_anual()">Agregar</vs-button>
                                </div>
                        </div>
                    </div>
                    <vs-table stripe  :data="contenido_interes_anual">
                        <template slot="thead">

                            <vs-th>Interes Anual</vs-th>
                            <vs-th style="width:80%">Primer Periodo de calculo</vs-th>
                            
                            <vs-th>Opciones</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(datos, index) in data" :key="index">
                                <vs-td v-if="datos.interes_anual">{{datos.interes_anual}}</vs-td><vs-td v-else>-</vs-td>
                                <vs-td v-if="datos.tiempo_pago">{{ datos.tiempo_pago }}</vs-td><vs-td v-else>-</vs-td>
                                
                                
                                
                                <vs-td class="whitespace-no-wrap  estilosacciones">
                                    <feather-icon  icon="EditIcon" class="cursor-pointer" svgClasses="w-5 h-5 hover:text-primary stroke-current" @click.stop="editarmodal_interes_anual(datos)" />
                                    <feather-icon  icon="TrashIcon" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer" @click.stop="eliminar_interes_anual(datos.id_tabla_interes_anual)"/>
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
            </vx-card>
            <vs-popup  title="Eliminar Iteres Anual" :active.sync="modalEliminar_Interes_anual">
                <label for="" class="vs-input--label">Desea Eliminar este Registro</label>
                <div class="vx-col w-full">
                            <vs-button color="success" type="filled" @click="eliminar_interes_accept_anual(id_interesrecupera_anual)">ACEPTAR</vs-button>
                            <vs-button color="danger"  type="filled" @click="cancelar_interes_eliminar_anual()">CANCELAR</vs-button>
                </div>
            </vs-popup>
            
            <vs-popup  title="Agregar Interes" class="peque2" :active.sync="modalAgregar_Interes_anual">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/4 w-full mb-6">
                                <label for="" class="vs-input--label">Interes Anual:</label>
                                <vs-input class="w-full" v-model="interes_anual"/>
                                <div v-show="errorinteres_anual" v-if="!interes_anual">
                                    <span
                                        class="text-danger"
                                        v-for="err in error_interes_anual"
                                        :key="err"
                                        v-text="err"
                                    ></span>
                                </div>
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-2 text-center">
                            <label class="vs-input--label">Periodo de Pago</label>
                            <vs-select placeholder="Selecciona el periodo de pago" autocomplete class="selectExample w-full" v-model="periodo_pago">
                                <vs-select-item value text="Selecciona el periodo" />
                                <vs-select-item value="Dias" text="Dias" />
                                <vs-select-item value="Meses" text="Meses" />
                            </vs-select>
                    
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-2 text-center">
                            <vs-input class="w-full text-center" label="Primer Periodo de calculo" v-model="tiempo_pago"/>
                    
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-2">
                                <label class="vs-input--label">Plan Cuenta</label>
                                <vx-input-group>
                                    <vs-input class="w-full" v-model="cta_contable_interes" :value="id_plan_cuenta_interes" disabled/>
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                        <vs-button color="primary"
                                        type="filled"
                                        icon="search" @click="popupActive_Interes_Anual=true,listar3('')"></vs-button>
                                        </div>
                                    </template>
                                </vx-input-group>
                                
                        </div>
                    </div>
                        <div class="vx-col w-full">
                            <vs-button color="success" type="filled" :disabled="disabled_interes_anual" @click="guardar_interes_anual()" v-if="id_interesrecupera_anual==null">GUARDAR</vs-button>
                            <vs-button color="success" type="filled" @click="editar_interes_anual()" v-else>GUARDAR</vs-button>
                            <vs-button color="warning" type="filled" @click="vaciar_interes_anual()">BORRAR</vs-button>
                            <vs-button color="danger"  type="filled" @click="cancelar_interes_anual()">CANCELAR</vs-button>
                        </div>
                    
                <vs-popup title="Plan Cuentas" :active.sync="popupActive_Interes_Anual">
                        <div class="con-exemple-prompt">
                            <vs-input class="mb-4 md:mb-0 mr-4 w-full" v-model="listarpc" @keyup="listar3(listarpc)" v-bind:placeholder="i18nbuscar"/>
                            <vs-table stripe v-model="cuentaarray_plc" @selected="handleSelected_plc" :data="contenido_plc">
                                <template slot="thead">
                                    <vs-th>No.Cuenta</vs-th>
                                    <vs-th>Tipo Cuenta</vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                                        <vs-td :data="data[indextr].codcta">{{data[indextr].codcta}}</vs-td>
                                        <vs-td :data="data[indextr].nomcta">{{data[indextr].nomcta}}</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                </vs-popup>
            </vs-popup>
        </vs-popup>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
moment.locale("es");
import $ from "jquery";
import FacturasMasiva from "./FacturasMasiva.vue";
const axios = require("axios");
const {
    rutasEmpresa: { DATA_EMPRESA }
} = require("../../../../../../config-routes/config.js");
import script_comprobantes from "../../../../factura.js";
import cont from "../../../layouts/components/vertical-nav-menu/navMenuItems";

export default {
    components: {
        AgGridVue,
        flatPickr,
        FacturasMasiva
    },
    filters: {
        fecha(data) {
            return moment(data).format("LL");
        },
        fechayhora(data) {
            return moment(data).format("LLL");
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
                    if (el.nombre == "Factura Venta") {
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
                    if (el.nombre == "Factura Venta") {
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
                    if (el.nombre == "Factura Venta") {
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        },

        suma_debe() {
            var total = 0;
            if (this.creditos.length > 0) {
                this.creditos.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    total += parseFloat(el.debe);
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    total += parseFloat(el.debe);
                });
            }
            if (this.pagos_sin_plc.length > 0) {
                this.pagos_sin_plc.forEach(el => {
                    total += parseFloat(el.debe);
                });
            }
            if (this.pagos_con_plc.length > 0) {
                this.pagos_con_plc.forEach(el => {
                    total += parseFloat(el.debe);
                });
            }
            if (this.pagos_anticipo.length > 0) {
                this.pagos_anticipo.forEach(el => {
                    total += parseFloat(el.debe);
                });
            }

            this.total_debe = total.toFixed(2);
        },
        suma_haber() {
            var total = 0;
            if (this.productos_asiento.length > 0) {
                this.productos_asiento.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }
            if (this.iva_asiento.length > 0) {
                this.iva_asiento.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }
            if (this.ice.length > 0) {
                this.ice.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }

            this.total_haber = total.toFixed(2);
        },
        cambioDecimales() {
            if (this.creditos.length > 0) {
                this.creditos.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    el.debe = parseFloat(el.debe).toFixed(2);
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    el.debe = parseFloat(el.debe).toFixed(2);
                });
            }
            if (this.pagos_sin_plc.length > 0) {
                this.pagos_sin_plc.forEach(el => {
                    el.debe = parseFloat(el.debe).toFixed(2);
                });
            }
            if (this.pagos_con_plc.length > 0) {
                this.pagos_con_plc.forEach(el => {
                    el.debe = parseFloat(el.debe).toFixed(2);
                });
            }
            if (this.pagos_anticipo.length > 0) {
                this.pagos_anticipo.forEach(el => {
                    el.debe = parseFloat(el.debe).toFixed(2);
                });
            }
            if (this.productos_asiento.length > 0) {
                this.productos_asiento.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
            if (this.iva_asiento.length > 0) {
                this.iva_asiento.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
            if (this.ice.length > 0) {
                this.ice.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
        },
        igualar() {
            var cambio_iva = 0;
            var total_iva = 0;
            var cambio_renta = 0;
            var total_renta = 0;
            var cambio_pag = 0;
            var num_may_iva = 0;
            var otro_iva = -1;
            var num_may_renta = 0;
            var otro_renta = -1;
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    total_iva += parseFloat(el.haber);
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    total_renta += parseFloat(el.haber);
                });
            }
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    if (el.haber > num_may_iva) {
                        otro_iva++;
                        num_may_iva = el.haber;
                    }
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    if (el.haber > num_may_renta) {
                        otro_renta++;
                        num_may_renta = el.haber;
                    }
                });
            }

            var index_renta = this.retencion_renta.find(
                fruta => fruta.haber === num_may_renta
            );
            console.log("num renta:" + num_may_renta + " total:" + otro_renta);
            console.log("num iva:" + num_may_iva + " total iva:" + otro_iva);
            if (this.retencion_iva.length > 0) {
                //if(this.retencion_iva.length>0){
                //     var elementoYaExiste2=0;
                // elementoYaExiste2 = this.retencion_iva.find(elemento => elemento.haber === num_may_iva);
                // var miCarritoSinDuplicados = this.retencion_iva.reduce((acumulador, valorActual) => {
                // var elementoYaExiste = acumulador.find(elemento => elemento.haber === valorActual.haber);
                // if (elementoYaExiste) {
                //     return acumulador.map((elemento) => {
                //     if ( elemento.haber === valorActual.haber) {
                //         return {
                //         ...elemento,
                //         acumula: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                //         }
                //         //elemento.acumula=parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                //     }

                //     return elemento;
                //     });
                // }

                // return [...acumulador, valorActual];
                // }, []);
                if (this.retencion_iva[0].total_iva < total_iva) {
                    cambio_iva = total_iva - this.retencion_iva[0].total_iva;
                    this.retencion_iva[otro_iva].haber =
                        this.retencion_iva[otro_iva].haber - cambio_iva;
                }
                //console.log(JSON.stringify(elementoYaExiste2)+"exist");
                //}
            }
            if (this.retencion_renta.length > 0) {
                if (this.retencion_renta[0].total_renta < total_renta) {
                    cambio_renta =
                        total_renta - this.retencion_renta[0].total_renta;
                    this.retencion_renta[otro_renta].haber =
                        this.retencion_renta[otro_renta].haber - cambio_renta;
                }
            }

            console.log(
                "total con cambio renta:" +
                    cambio_renta +
                    " total renta:" +
                    total_renta
            );
            console.log(
                "total con cambio iva:" + cambio_iva + " total iva:" + total_iva
            );
            //console.log(JSON.stringify(miCarritoSinDuplicados)+"proyecto");
            //console.log(JSON.stringify(array));
            // if(this.retencion_iva.length>0){
            //     if(this.retencion_iva[this.retencion_iva.length-1].total_iva!==total_iva){
            //         cambio_iva=total_iva-this.retencion_iva[this.retencion_iva.length-1].total_iva;
            //         this.retencion_iva[otro_iva].haber=this.retencion_iva[otro_iva].haber-cambio_iva;
            //     }
            // }
            // if(this.retencion_renta.length>0){
            //     if(this.retencion_renta[this.retencion_renta.length-1].total_renta!==total_renta){
            //         cambio_renta=total_renta-this.retencion_renta[this.retencion_renta.length-1].total_renta;
            //         this.retencion_renta[otro_renta].haber=this.retencion_renta[otro_renta].haber-cambio_renta;
            //     }
            // }
        },
        Diferencia() {
            if (this.iva_asiento.length > 0) {
                if (this.iva_asiento.length == 1) {
                    if (
                        parseFloat(this.iva_asiento[0].haber) !==
                        parseFloat(this.total_doce_iva)
                    ) {
                        var diferencia_iva_asiento =
                            parseFloat(this.total_doce_iva) -
                            parseFloat(this.iva_asiento[0].haber);
                        var total_iva_asiento =
                            parseFloat(this.iva_asiento[0].haber) +
                            diferencia_iva_asiento;
                        this.iva_asiento[0].haber = total_iva_asiento;
                        //this.iva_asiento[0].haber=diferencia_iva_asiento;
                        console.log(
                            "Diferebcia en el debe: iva:" + total_iva_asiento
                        );
                    }
                }
            }
            if (this.creditos.length > 0) {
                if (this.creditos.length == 1) {
                    if (
                        parseFloat(this.creditos[0].debe) !==
                        parseFloat(this.creditos[0].total)
                    ) {
                        var diferencia_creditos =
                            parseFloat(this.creditos[0].total) -
                            parseFloat(this.creditos[0].debe);
                        var total_creditos =
                            parseFloat(this.creditos[0].debe) +
                            diferencia_creditos;
                        this.creditos[0].debe = total_creditos;
                        //this.creditos[0].debe=diferencia_creditos;
                        console.log(
                            "Diferebcia en el debe: creditos:" + total_creditos
                        );
                    }
                }
            }
            if (this.pagos_sin_plc.length > 0) {
                if (this.pagos_sin_plc.length == 1) {
                    if (
                        parseFloat(this.pagos_sin_plc[0].debe) !==
                        parseFloat(this.pagos_sin_plc[0].total)
                    ) {
                        var diferencia_pagos_sin_plc =
                            parseFloat(this.pagos_sin_plc[0].total) -
                            parseFloat(this.pagos_sin_plc[0].debe);
                        var total_pagos_sin_plc =
                            parseFloat(this.pagos_sin_plc[0].debe) +
                            diferencia_pagos_sin_plc;
                        this.pagos_sin_plc[0].debe = total_pagos_sin_plc;
                        //this.pagos_sin_plc[0].debe=diferencia_pagos_sin_plc;
                        console.log(
                            "Diferebcia en el debe: pagos_sin_plc" +
                                total_pagos_sin_plc
                        );
                    }
                }
            }
            if (this.pagos_con_plc.length > 0) {
                if (this.pagos_con_plc.length == 1) {
                    if (
                        this.pagos_con_plc[0].debe !==
                        this.pagos_con_plc[0].total
                    ) {
                        var diferencia_pagos_con_plc =
                            parseFloat(this.pagos_con_plc[0].total) -
                            parseFloat(this.pagos_con_plc[0].debe);
                        var total_pagos_con_plc =
                            parseFloat(this.pagos_con_plc[0].debe) +
                            diferencia_pagos_con_plc;
                        this.pagos_con_plc[0].debe = total_pagos_con_plc;
                        console.log(
                            "Diferebcia en el debe: pagos_con_plc" +
                                total_pagos_con_plc
                        );
                    }
                }
            }
            if (this.pagos_anticipo.length > 0) {
                if (this.pagos_anticipo.length == 1) {
                    if (
                        this.pagos_anticipo[0].debe !==
                        this.pagos_anticipo[0].total
                    ) {
                        var diferencia_pagos_anticipo =
                            parseFloat(this.pagos_anticipo[0].total) -
                            parseFloat(this.pagos_anticipo[0].debe);
                        var total_pagos_anticipo =
                            parseFloat(this.pagos_anticipo[0].debe) +
                            diferencia_pagos_anticipo;
                        this.pagos_anticipo[0].debe = total_pagos_anticipo;
                        //this.pagos_anticipo[0].debe=diferencia_pagos_anticipo;
                        console.log(
                            "Diferebcia en el debe: pagos_anticipo" +
                                total_pagos_anticipo
                        );
                    }
                }
            }

            // if (this.retencion_iva.length > 0) {
            //     if (this.retencion_iva.length == 1) {
            //         if (
            //             this.retencion_iva[0].debe !==
            //             this.retencion_iva[0].cantidadiva
            //         ) {
            //             var diferencia_retencion_iva =
            //                 parseFloat(this.retencion_iva[0].cantidadiva) -
            //                 parseFloat(this.retencion_iva[0].debe);
            //             var total_retencion_iva =
            //                 parseFloat(this.retencion_iva[0].debe) +
            //                 diferencia_retencion_iva;
            //             this.retencion_iva[0].debe = total_retencion_iva;
            //             //this.retencion_iva[0].debe=diferencia_retencion_iva;
            //             console.log(
            //                 "Diferebcia en el debe: retencion_iva" +
            //                     total_retencion_iva
            //             );
            //         }
            //     }
            // }
            // if (this.retencion_renta.length > 0) {
            //     if (this.retencion_renta.length == 1) {
            //         if (
            //             this.retencion_renta[0].debe !==
            //             this.retencion_renta[0].cantidadrenta
            //         ) {
            //             var diferencia_retencion_renta =
            //                 parseFloat(this.retencion_renta[0].cantidadrenta) -
            //                 parseFloat(this.retencion_renta[0].debe);
            //             var total_retencion_renta =
            //                 parseFloat(this.retencion_renta[0].debe) +
            //                 diferencia_retencion_renta;
            //             this.retencion_renta[0].debe = total_retencion_renta;
            //             //this.retencion_renta[0].debe=diferencia_retencion_renta;
            //             console.log(
            //                 "Diferebcia en el debe: retencion_renta" +
            //                     total_retencion_renta
            //             );
            //         }
            //     }
            // }
            
            

            if (this.total_debe > this.total_haber) {
                this.diferencia_debe = this.total_haber - this.total_debe;
                console.log("Diferebcia en el debe:" + this.diferencia_debe);
            }
            if (this.total_debe < this.total_haber) {
                this.diferencia_haber = this.total_debe - this.total_haber;
                console.log("Diferebcia en el haber:" + this.diferencia_haber);
            }

            var diferencia = this.total_debe - this.total_haber;
            // if (this.productos_asiento.length > 0) {
            //     if (diferencia !== 0) {
            //         var total_dif =
            //             parseFloat(this.productos_asiento[0].haber) +
            //             parseFloat(diferencia);
            //         this.productos_asiento[0].haber = total_dif;
            //         console.log("Producto1: " + total_dif);
            //     }
            // }
        },
        sumar_iguales() {
            var array = {};
            var hash = {};
            var hash2 = {};
            if (this.productos_asiento.length > 0) {
                this.productos_asiento = this.productos_asiento.reduce(
                    (acumulador, valorActual) => {
                        if (
                            valorActual.sector === "producto" &&
                            valorActual.iva === "doce"
                        ) {
                            const elementoYaExiste = acumulador.find(
                                elemento =>
                                    elemento.id_proyecto ===
                                        valorActual.id_proyecto &&
                                    elemento.id_plan_cuentas_iva_12 ===
                                        valorActual.id_plan_cuentas_iva_12
                            );
                            if (elementoYaExiste) {
                                return acumulador.map(elemento => {
                                    if (
                                        elemento.id_proyecto ===
                                            valorActual.id_proyecto &&
                                        elemento.id_plan_cuentas_iva_12 ===
                                            valorActual.id_plan_cuentas_iva_12
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
                        } else {
                            if (
                                valorActual.sector === "producto" &&
                                valorActual.iva === "cero"
                            ) {
                                const elementoYaExiste = acumulador.find(
                                    elemento =>
                                        elemento.id_proyecto ===
                                            valorActual.id_proyecto &&
                                        elemento.id_plan_cuentas_iva_0 ===
                                            valorActual.id_plan_cuentas_iva_0
                                );
                                if (elementoYaExiste) {
                                    return acumulador.map(elemento => {
                                        if (
                                            elemento.id_proyecto ===
                                                valorActual.id_proyecto &&
                                            elemento.id_plan_cuentas_iva_0 ===
                                                valorActual.id_plan_cuentas_iva_0
                                        ) {
                                            return {
                                                ...elemento,
                                                haber:
                                                    parseFloat(elemento.haber) +
                                                    parseFloat(
                                                        valorActual.haber
                                                    )
                                            };
                                        }

                                        return elemento;
                                    });
                                }

                                return [...acumulador, valorActual];
                            } else {
                                const elementoYaExiste = acumulador.find(
                                    elemento =>
                                        elemento.id_proyecto ===
                                            valorActual.id_proyecto &&
                                        elemento.id_plan_cuentas_servicio ===
                                            valorActual.id_plan_cuentas_servicio
                                );
                                if (elementoYaExiste) {
                                    return acumulador.map(elemento => {
                                        if (
                                            elemento.id_proyecto ===
                                                valorActual.id_proyecto &&
                                            elemento.id_plan_cuentas_servicio ===
                                                valorActual.id_plan_cuentas_servicio
                                        ) {
                                            return {
                                                ...elemento,
                                                haber:
                                                    parseFloat(elemento.haber) +
                                                    parseFloat(
                                                        valorActual.haber
                                                    )
                                            };
                                        }

                                        return elemento;
                                    });
                                }

                                return [...acumulador, valorActual];
                            }
                        }
                    },
                    []
                );
            }
            /*array = this.iva_asiento.filter(function(current) {
            var exists = !hash[current.id_proyecto] || !hash2[current.id_plan_cuentas];
            hash[current.id_proyecto] = true;
            hash2[current.id_plan_cuentas]=true;
              return exists
            });*/
            /*if(this.productos_asiento.length>0){
                this.productos_asiento = this.productos_asiento.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }*/
            if (this.ice.length > 0) {
                this.ice = this.ice.reduce((acumulador, valorActual) => {
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
            if (this.iva_asiento.length > 0) {
                this.iva_asiento = this.iva_asiento.reduce(
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
                                    //console.log(elemento.haber+" iva  "+valorActual.haber+"="+parseFloat(elemento.haber) +parseFloat(valorActual.haber));
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
            if (this.pagos_sin_plc.length > 0) {
                this.pagos_sin_plc = this.pagos_sin_plc.reduce(
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
            if (this.pagos_con_plc.length > 0) {
                this.pagos_con_plc = this.pagos_con_plc.reduce(
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
            if (this.pagos_anticipo.length > 0) {
                this.pagos_anticipo = this.pagos_anticipo.reduce(
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
            if (this.retencion_iva.length > 0) {
                this.retencion_iva = this.retencion_iva.reduce(
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
                                    //console.log(elemento.debe+" iva retencion "+valorActual.debe+"="+parseFloat(elemento.debe) +parseFloat(valorActual.debe));
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
            if (this.retencion_renta.length > 0) {
                this.retencion_renta = this.retencion_renta.reduce(
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
        }
    },
    data() {
        return {
            timeout: null,
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
            modal: false,
            datos: [],
            destinatario: "",
            empleado_email: "",
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
            claveacceso: null,
            tipofactura: "factura",
            generarreporte: false,
            tipo_busqueda: "",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            dateinicio: "",
            datefin: "",
            cliente_busqueda: null,
            vendedor_busqueda: null,
            vendedores2: [],
            clientes2: [],
            datosg: {},
            popupcorreo: false,
            nombrecliente: "",
            correocliente: "",
            errorenvio: 0,
            errornombrecliente: [],
            errorchip_correo: [],
            total_doce_iva:0,
            total_retencion_iva:0,
            total_retencion_renta:0,
            lista: {
                factura: {},
                cliente: [],
                productos: [],
                pagos: [],
                creditos: {},
                iva: [],
                renta: []
            },
            //variables tabla interes
            modalinteres:false,
            buscar_interes:"",
            contenido_interes:[],
            modalAgregar_Interes:false,
            modalEliminar_Interes:false,
            interes:"",
            id_interesrecupera:null,
            errorinteres:0,
            error_interes:[],
            disabled_interes:false,
            //variables tabla interes anual
            modalinteres_anual:false,
            buscar_interes_anual:"",
            contenido_interes_anual:[],
            modalAgregar_Interes_anual:false,
            modalEliminar_Interes_anual:false,
            interes_anual:"",
            periodo_pago:"",
            tiempo_pago:"",
            cuentaarray_plc:[],
            id_plan_cuenta_interes:"",
            cta_contable_interes:"",
            nombre_cuenta:"",
            contenido_plc:[],
            listarpc:"",
            popupActive_Interes_Anual:false,
            id_interesrecupera_anual:null,
            errorinteres_anual:0,
            error_interes_anual:[],
            disabled_interes_anual:false,
            //variables Contabilizar
            modalAsiento: false,
            disabled_asiento:false,
            nombre_proyecto: "",
            fecha_rol: "",
            ruc_empresa: "",
            razon_social: "",
            concepto: "",
            codigo: "",
            productos_asiento: [],
            iva_asiento: [],
            pagos_sin_plc: [],
            pagos_con_plc: [],
            pagos_anticipo: [],
            creditos: [],
            retencion_iva: [],
            retencion_renta: [],
            pagos: [],
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
            ice: [],
            estadocorreo: 1,
            subtotal_factura:0,
            estado_asiento: "",
            //
            chip_nombre: [],
            chip_correo: [],
            //variables impresora
            impresoras: [],
            nombre_impresora: "",
            modal_impresora: false,
            id_factura_imp: "",

            //variables facturacion masiva
            modalfact_masiva: false,
            //fitrar tabla
            filterstable: {
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
                if (this.filterstable.filt_autoris == true) {
                    filt =
                        filt +
                        '(contvar.respuesta == "Enviado" && contvar.estadof == 1)';
                }
                if (this.filterstable.filt_noautoris == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt =
                        filt +
                        '(contvar.respuesta == "Error" && contvar.estadof == 1)';
                }
                if (this.filterstable.filt_anul == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt = filt + "contvar.estadof == 0";
                }
                contvar = contvar.filter(contvar => eval(filt));
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
                this.filterstable.filt_autoris = true;
                this.filterstable.filt_noautoris = true;
                this.filterstable.filt_anul = true;
            }
            this.contenido = contvar;
        },
        sendData() {
            console.log(this.datos);
        },
        listar(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                this.contenido = [];
                var url = "/api/facturas";
                var datos = {
                    page: page,
                    buscar: buscar,
                    datos: this.usuario
                };
                axios.post(url, datos).then(res => {
                    this.contenido = res.data;
                    this.filterstable.contrue = res.data;
                    this.filtertabla();
                });
            }, 800);
            
        },
        getVendedores: function() {
            axios.get("/api/vendedores").then(
                function(response) {
                    this.vendedores2 = response.data;
                }.bind(this)
            );
        },
        getClientes: function() {
            axios.get("/api/clientes").then(
                function(response) {
                    this.clientes2 = response.data;
                }.bind(this)
            );
        },
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val);
        },
        editar(id) {
            this.$router.push(`/facturacion/factura-venta/${id}/editar`);
        },
        ver(id) {
            this.$router.push(`/facturacion/factura-venta/${id}/ver`);
        },
        facturaenvio(dat) {
            this.$vs.notify({
                time: 8000,
                title: "Validando factura al SRI",
                text: "Por favor Espere...",
                color: "primary"
            });
            this.claveacceso = dat.clave_acceso;
            var url = "/api/factura/xml_factura";
            axios.post(url, dat).then(({ data }) => {
                var password = data.recupera.pass_firma;
                var firma =
                    DATA_EMPRESA +
                    this.usuario.id_empresa +
                    "/firma/" +
                    data.recupera.firma;
                var factura =
                    DATA_EMPRESA +
                    this.usuario.id_empresa +
                    "/comprobantes/factura/" +
                    this.claveacceso +
                    ".xml";
                var tipo = "factura_venta";
                var carpeta =
                    DATA_EMPRESA +
                    this.usuario.id_empresa +
                    "/comprobantes/factura/";
                var fecha_actual = moment(dat.fecha_autorizacion).format("LL");
                this.crearfacturacion(
                    firma,
                    password,
                    factura,
                    tipo,
                    this.usuario,
                    dat.id_factura,
                    carpeta,
                    fecha_actual,
                    dat.valor_total,
                    dat.logo,
                    dat.nombre_empresa
                );
            });
        },
        guiaenvio(id) {
            axios.post("/api/factura/recuperar_guia/" + id).then(({ data }) => {
                var res = data[0];
                this.$vs.notify({
                    time: 8000,
                    title: "Validando factura al SRI",
                    text: "Por favor Espere...",
                    color: "primary"
                });
                this.claveacceso = res.clave_acceso;
                var url = "/api/factura/xml_guia";
                axios.post(url, res).then(({ data }) => {
                    var password = res.pass_firma;
                    var firma =
                        DATA_EMPRESA +
                        this.usuario.id_empresa +
                        "/firma/" +
                        res.firma;
                    var factura =
                        DATA_EMPRESA +
                        this.usuario.id_empresa +
                        "/comprobantes/guia/" +
                        res.clave_acceso +
                        ".xml";
                    var tipo = "guia_remision_venta";
                    var carpeta =
                        DATA_EMPRESA +
                        this.usuario.id_empresa +
                        "/comprobantes/guia/";
                    var fecha_actual = moment(res.fecha_inicio_tr).format("LL");
                    this.crearfacturacion(
                        firma,
                        password,
                        factura,
                        tipo,
                        this.usuario,
                        res.id_guia,
                        carpeta,
                        fecha_actual,
                        "0.00",
                        res.logo,
                        res.nombre_empresa
                    );
                });
            });
        },
        reportes_factura() {
            axios({
                url:
                    "/api/reportes/factura?tipo_busqueda=" +
                    this.tipo_busqueda +
                    "&dateinicio=" +
                    this.dateinicio +
                    "&datefin=" +
                    this.datefin +
                    "&cliente_busqueda=" +
                    this.cliente_busqueda +
                    "&vendedor_busqueda=" +
                    this.vendedor_busqueda,
                method: "GET",
                responseType: "arraybuffer"
            })
                .then(resp => {
                    var decodedString = String.fromCharCode.apply(
                        null,
                        new Uint8Array(resp.data)
                    );
                    if (decodedString.includes("no-data-report")) {
                        this.$vs.notify({
                            time: 5000,
                            title: "Sin registros",
                            text:
                                "No se encontraron registros con los datos proporcionados",
                            color: "warning"
                        });
                        return;
                    }
                    let { headers } = resp;
                    let nameFile = headers["content-disposition"]
                        .split(";")[1]
                        .split("=")[1]
                        .replace(/"/g, "");
                    console.log(resp.data);
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
                        text: "Su reporte esta siendo descargado exitosamente!",
                        color: "success"
                    });
                })
                .catch(console.error);
        },
        duplicar(data) {
            localStorage.removeItem("duplicar");
            localStorage.setItem("duplicar", data.id_factura);
            this.$router.push("/facturacion/factura-venta/duplicar");
        },
        //Facturación
        enviado() {
            this.listar(1, this.buscar);
        },
        async crearfacturacion(
            firma,
            password,
            factura,
            tipo,
            usuario,
            id_factura,
            carpeta,
            fecha,
            valor,
            logo,
            nombre_empresa
        ) {
            try {
                let {
                    data: comprobante
                } = await script_comprobantes.obtener_comprobante_firmado.getAll(
                    { factura: factura, id_factura: id_factura, tipo: tipo }
                );
                let {
                    resultado: contenido
                } = await script_comprobantes.lectura_firma.getAll({
                    firma: firma,
                    id_factura: id_factura,
                    tipo: tipo
                });
                let {
                    data: certificado
                } = await script_comprobantes.firmar_comprobante.getAll({
                    contenido: contenido[0],
                    password: password,
                    comprobante: comprobante,
                    id_factura: id_factura,
                    tipo: tipo
                });
                let {
                    data: quefirma
                } = await script_comprobantes.verificar_firma.getAll({
                    comprobante: comprobante,
                    mensaje: certificado,
                    tipo: tipo,
                    id_factura: id_factura,
                    carpeta: carpeta
                });
                let {
                    data: validado
                } = await script_comprobantes.validar_comprobante.getAll({
                    comprobante: comprobante,
                    tipo: tipo,
                    id_factura: id_factura,
                    carpeta: carpeta,
                    id_empresa: usuario.id_empresa
                });
                let {
                    data: recibida
                } = await script_comprobantes.autorizar_comprobante.getAll({
                    comprobante: comprobante,
                    validado: validado,
                    usuario: usuario,
                    tipo: tipo,
                    id_factura: id_factura,
                    carpeta: carpeta,
                    fecha: fecha,
                    valor: valor,
                    logo: logo,
                    nombre_empresa: nombre_empresa
                });
                let {
                    data: registrado
                } = await script_comprobantes.autorizado_comprobante.getAll({
                    recibida: recibida,
                    tipo: tipo,
                    id_factura: id_factura
                });
                this.$vs.notify({
                    time: 8000,
                    title: "Factura Enviada",
                    text: "La factura se generó exitosamente",
                    color: "success"
                });
                this.enviado();
            } catch (error) {
                this.$vs.notify({
                    time: 20000,
                    title: error.mensaje,
                    text: error.informacion,
                    color: "danger"
                });
                this.enviado();
            }
        },
        eliminar(id) {
            //console.log("1", id);
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `¿Desea Cancelar este comprobante?`,
                text: `Este comprobante sera cancelada del sistema`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.aceptarborrado,
                parameters: id
            });
        },
        aceptarborrado(parameters) {
            axios
                .post("/api/eliminarfactura", {
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
                    this.listar(1, this.buscar);
                });
        },
        recargar_reporte() {
            this.dateinicio = "";
            this.datefin = "";
            this.cliente_busqueda = null;
            this.vendedor_busqueda = null;
        },
        descargarpdf(datos) {
            window.open(
                "/api/creacion_factura_venta_pdf/" + datos.id_factura + "/d",
                "_top"
            );
            // axios
            //     .get(
            //         "/api/creacion_factura_venta_pdf/" + datos.id_factura + "/v"
            //     )
            //     .then(resp => {
            //         window.open(
            //             "/api/creacion_factura_venta_pdf/" +
            //                 datos.id_factura +
            //                 "/d",
            //             "_top"
            //         );
            //         this.$vs.notify({
            //             color: "success",
            //             text: "Pdf Generado exitosamente"
            //         });
            //     })
            //     .catch(err => {
            //         this.$vs.notify({
            //             color: "danger",
            //             title: "Error al descargar PDF",
            //             text: err
            //         });
            //         console.log(err);
            //     });
        },
        verpdf(datos) {
            axios
                .get(
                    "/api/creacion_factura_venta_pdf/" + datos.id_factura + "/v"
                )
                .then(resp => {
                    window.open(
                        "/api/creacion_factura_venta_pdf/" +
                            datos.id_factura +
                            "/v",
                        "_blank"
                    );
                    this.$vs.notify({
                        color: "success",
                        text: "Pdf Generado exitosamente"
                    });
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Error al descargar PDF",
                        text: err
                    });
                    console.log(err);
                });
        },
        descargarxml(datos) {
            //window.open('/'+datos.id_empresa+'/vistaxml/factura_venta/'+datos.clave_acceso, '_top');
            this.claveacceso = datos.clave_acceso;
            var url = "/api/factura/xml_factura";
            axios.post(url, datos).then(({ data }) => {
                window.open(
                    "/" +
                        datos.id_empresa +
                        "/vistaxml/factura_venta/" +
                        datos.clave_acceso,
                    "_top"
                );
            });
        },
        descargarxmlSRI(datos, tipo) {
            // try {
            //     window.open('/'+datos.id_empresa+'/vistaxml/factura_venta/respuesta_sri/'+datos.clave_acceso, '_top');
            // } catch (error) {
            //         this.$vs.notify({
            //             color: "danger",
            //             title: "No existe el archivo",

            //         });
            // }
            var url =
                "/" +
                datos.id_empresa +
                "/vistaxml/factura_venta/respuesta_sri/" +
                datos.clave_acceso;
            try {
                axios
                    .get(url)
                    .then(resp => {
                        window.open(
                            "/" +
                                datos.id_empresa +
                                "/vistaxml/factura_venta/respuesta_sri/" +
                                datos.clave_acceso,
                            "_top"
                        );
                    })
                    .catch(err => {
                        this.$vs.notify({
                            title: "Error de envio",
                            text: "No existe el XML",
                            color: "danger"
                        });
                    });
                // .done(function() {
                //     window.open(
                //         "/" +
                //             datos.id_empresa +
                //             "/vistaxml/factura_venta/respuesta_sri/" +
                //             datos.clave_acceso,
                //         "_top"
                //     );
                // }).fail(function() {
                //         this.$vs.notify({
                //             title: "Error de envio",
                //             text:
                //                 "No existe el XML",
                //             color: "danger"
                //         });
                // });
            } catch (error) {
                this.$vs.notify({
                    title: "Error de envio",
                    text: "No existe el XML",
                    color: "danger"
                });
            }
        },
        descargarpdfyxml(datos) {
            window.open(
                "/" +
                    datos.id_empresa +
                    "/vistapdfyxml/factura_venta/" +
                    datos.clave_acceso,
                "_top"
            );
        },
        //-----------------------metodos para Contabilizar Facturas
        Contabilidad(id) {
            axios
                .get(
                    "/api/facturavercontabilidad/" +
                        id +
                        "?id_empresa=" +
                        this.usuario.id_empresa
                )
                .then(({ data }) => {
                    this.lista.factura = data.factura;
                    this.lista.cliente = data.cliente;
                    //{{(datos.clave_acceso).substring(24,27)}}-{{(datos.clave_acceso).substring(27,30)}}-{{(datos.clave_acceso).substring(30,39)
                    var serie1 = data.factura.clave_acceso.substring(24, 27);
                    var serie2 = data.factura.clave_acceso.substring(27, 30);
                    var documento = data.factura.clave_acceso.substring(30, 39);
                    //this.lista.productos = data.productos;
                    this.lista.creditos = data.creditos;
                    this.lista.iva = data.iva;
                    this.lista.renta = data.renta;
                    this.fecha_rol = data.factura.fecha_emision;
                    var fecha = moment(this.fecha_rol).format("MMMM YYYY");
                    this.razon_social = data.empresa.nombre;
                    this.ruc_empresa = data.empresa.identificacion;
                    if (
                        data.empresa.tipo_identificacion ==
                        "Cédula de Identidad"
                    ) {
                        this.tipo_identificacion = "Cedula";
                    } else {
                        this.tipo_identificacion =
                            data.empresa.tipo_identificacion;
                    }
                    if (data.factura.contabilidad !== null) {
                        this.codigo = "FV-" + data.codigo_anterior;
                        this.contabilizado = data.factura.contabilidad;
                    } else {
                        this.codigo = "FV-" + data.codigo;
                        this.contabilizado = null;
                    }
                    this.concepto =
                        "Venta " +
                        serie1 +
                        "-" +
                        serie2 +
                        "-" +
                        documento +
                        " Cliente: " +
                        data.empresa.nombre;
                    this.productos_asiento = data.producto_asientos;
                    this.subtotal_factura=data.factura.subtotal_sin_impuesto;
                    this.iva_asiento = data.doce_iva_asiento;
                    this.pagos_sin_plc = data.pagos_asientos_sin_plc;
                    this.pagos_con_plc = data.pagos_asientos_con_plc;
                    this.pagos_anticipo = data.pagos_asientos_anticipo;
                    this.creditos = data.cliente;
                    this.retencion_iva = data.iva_retencion_asiento;
                    this.retencion_renta = data.retencion_asiento;
                    this.ice = data.ice;
                    //this.modalAsiento = true;
                    this.id_factura = id;
                    this.id_proyecto = data.id_proyecto;
                    this.estado_asiento = data.asiento_permitido;
                    this.total_doce_iva = data.factura.iva_12;
                    this.total_retencion_iva = data.total_retencion_iva;
                    this.total_retencion_renta = data.total_retencion_renta;
                    this.cuadrarAsiento();
                })
                .catch(error => {
                    console.log(error);
                });
        },
        cuadrarAsiento() {
            // this.$vs.notify({
            //         title: "Cargando este Registro",
            //         text: "Este proceso puede demorar por favor espere..",
            //         color: "warning"
            //     });
            this.IgualarIva()
                .then(value => {
                    return this.IgualarCredito();
                })
                .then(value => {
                    return this.IgualarPagosAnt();
                })
                .then(value => {
                    return this.IgualarPagosPlc();
                })
                .then(value => {
                    return this.IgualarPagosSinPlc();
                })
                .then(value => {
                    return this.IgualarRetencionIva();
                })
                .then(value => {
                    return this.IgualarRetencionRenta();
                })
                .then(value => {
                    return this.Decimales();
                })
                .then(value => {
                    this.modalAsiento = true;
                })
                .catch(error => {
                    console.error("[ERROR::]", error);
                    this.$vs.notify({
                        text: error,
                        color: "danger"
                    });
                });
        },
        IgualarIva() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro Iva12");
                var total_diferencia_pago = 0;
                //this.pagos_anticipo.length
                if (this.iva_asiento.length > 1) {
                    this.iva_asiento.forEach(el => {
                        pagos += parseFloat(el.haber);
                    });
                    //console.log("cantidad pagos:"+this.iva_asiento.length+" diferencia pago: "+pagos+" total pago:"+this.iva_asiento[0].total);
                    var n1 = Number(this.total_doce_iva);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    //console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)

                    for (var x = 1; x < this.iva_asiento.length; x++) {
                        if (
                            this.iva_asiento[x].haber >
                            this.iva_asiento[indiceDelMayor].haber
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.iva_asiento[indiceDelMayor].haber);
                    total_diferencia_pago = n3 + res;
                    this.iva_asiento[
                        indiceDelMayor
                    ].haber = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.iva_asiento[indiceDelMayor].haber +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
        },
        IgualarCredito() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro Credito");
                var total_diferencia_pago = 0;
                //this.pagos_anticipo.length
                if (this.creditos.length > 1) {
                    this.creditos.forEach(el => {
                        pagos += parseFloat(el.debe);
                    });
                    //console.log("cantidad pagos:"+this.creditos.length+" diferencia pago: "+pagos+" total pago:"+this.creditos[0].total);
                    var n1 = Number(this.creditos[0].total);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    //console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)

                    for (var x = 1; x < this.creditos.length; x++) {
                        if (
                            this.creditos[x].debe >
                            this.creditos[indiceDelMayor].debe
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.creditos[indiceDelMayor].debe);
                    total_diferencia_pago = n3 + res;
                    this.creditos[indiceDelMayor].debe = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.creditos[indiceDelMayor].debe +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
        },
        IgualarPagosAnt() {
            return new Promise(resolve => {
                var pagos = 0;
                var pagos_total = 0;
                console.log("Entro IgualarPagosANt");
                var total_diferencia_pago = 0;
                //this.pagos_anticipo.length
                if (this.pagos_anticipo.length > 1) {
                    this.pagos_anticipo.forEach(el => {
                        pagos += parseFloat(el.debe);
                    });
                    //console.log("cantidad pagos:"+this.pagos_anticipo.length+" diferencia pago: "+pagos+" total pago:"+this.pagos_anticipo[0].total);
                    var n1 = Number(this.total_pagos_anticipo);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    //console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)

                    for (var x = 1; x < this.pagos_anticipo.length; x++) {
                        if (
                            this.pagos_anticipo[x].debe >
                            this.pagos_anticipo[indiceDelMayor].debe
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.pagos_anticipo[indiceDelMayor].debe);
                    total_diferencia_pago = n3 + res;
                    this.pagos_anticipo[
                        indiceDelMayor
                    ].debe = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.pagos_anticipo[indiceDelMayor].debe +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
        },
        IgualarPagosPlc() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro IgualarPagosPLc");
                var total_diferencia_pago = 0;
                if (this.pagos_con_plc.length > 1) {
                    this.pagos_con_plc.forEach(el => {
                        pagos += parseFloat(el.debe);
                    });
                    //console.log("cantidad pagos:"+this.pagos_con_plc.length+" diferencia pago: "+pagos+" total pago:"+this.pagos_con_plc[0].total);
                    var n1 = Number(this.total_pagos_con_plc);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    //console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)

                    for (var x = 1; x < this.pagos_con_plc.length; x++) {
                        if (
                            this.pagos_con_plc[x].debe >
                            this.pagos_con_plc[indiceDelMayor].debe
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.pagos_con_plc[indiceDelMayor].debe);
                    total_diferencia_pago = n3 + res;
                    this.pagos_con_plc[
                        indiceDelMayor
                    ].debe = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.pagos_con_plc[indiceDelMayor].debe +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
        },
        IgualarPagosSinPlc() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro IgualarPagos");
                var total_diferencia_pago = 0;
                if (this.pagos_sin_plc.length > 1) {
                    this.pagos_sin_plc.forEach(el => {
                        pagos += parseFloat(el.debe);
                    });
                    //console.log("cantidad pagos:"+this.pagos_sin_plc.length+" diferencia pago: "+pagos+" total pago:"+this.pagos_sin_plc[0].total);
                    var n1 = Number(this.total_pagos_sin_plc);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    //console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)

                    for (var x = 1; x < this.pagos_sin_plc.length; x++) {
                        if (
                            this.pagos_sin_plc[x].debe >
                            this.pagos_sin_plc[indiceDelMayor].debe
                        ) {
                            indiceDelMayor = x;
                        }
                    }
                    //}

                    var n3 = Number(this.pagos_sin_plc[indiceDelMayor].debe);
                    total_diferencia_pago = n3 + res;
                    this.pagos_sin_plc[
                        indiceDelMayor
                    ].debe = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.pagos_sin_plc[indiceDelMayor].debe +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
        },
        IgualarRetencionIva() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro RetencionIva");
                
                //console.log("cantidad pagos:"+this.retencion_iva.length+" diferencia pago: "+pagos+" total pago:"+this.retencion_iva[0].total);
                var total_diferencia_pago = 0;
                if (this.retencion_iva.length > 0) {
                    this.retencion_iva.forEach(el => {
                        pagos += parseFloat(el.debe);
                    });

                    console.log("cantidad pagos:"+this.retencion_iva.length+" diferencia pago: "+pagos+" total pago:"+this.total_retencion_iva);
                    var n1 = Number(this.total_retencion_iva);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)
                    if(this.retencion_iva.length > 1){
                        for (var x = 1; x < this.retencion_iva.length; x++) {
                            if (
                                this.retencion_iva[x].debe >
                                this.retencion_iva[indiceDelMayor].debe
                            ) {
                                indiceDelMayor = x;
                            }
                        }
                    }
                    
                    //}
                    //if(this.retencion_iva.length>1){
                        var n3 = Number(this.retencion_iva[indiceDelMayor].debe);
                        total_diferencia_pago = n3 + res;
                        this.retencion_iva[
                            indiceDelMayor
                        ].debe = total_diferencia_pago;
                        console.log(
                            "pago:" +
                                this.retencion_iva[indiceDelMayor].debe +
                                " diferencia pago: " +
                                res +
                                " total pago:" +
                                total_diferencia_pago
                        );
                    // }else{
                        
                    // }
                    
                }
                resolve(total_diferencia_pago);
            });
        },
        IgualarRetencionRenta() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro RetencionRenta");
                var total_diferencia_pago = 0;
                if (this.retencion_renta.length > 0) {
                    this.retencion_renta.forEach(el => {
                        pagos += parseFloat(el.debe);
                    });

                    console.log("cantidad pagos:"+this.retencion_renta.length+" diferencia pago: "+pagos+" total pago:"+this.total_retencion_renta);
                    var n1 = Number(this.total_retencion_renta);
                    var n2 = Number(pagos);
                    var div = 0;
                    var res = 0;
                    if (n1 !== n2) {
                        res = n1 - n2;
                        div = Number(res);
                    }

                    //console.log(div+" :diferencia pago");
                    var indiceDelMayor = 0;
                    // Recorrer arreglo y ver si no es así
                    // (comenzar desde el 1 porque el 0 ya lo tenemos contemplado arriba)
                    if(this.retencion_renta.length>1){
                        for (var x = 1; x < this.retencion_renta.length; x++) {
                                            if (
                                                this.retencion_renta[x].debe >
                                                this.retencion_renta[indiceDelMayor].debe
                                            ) {
                                                indiceDelMayor = x;
                                            }
                                        }
                    }
                    
                    //}

                    var n3 = Number(this.retencion_renta[indiceDelMayor].debe);
                    total_diferencia_pago = n3 + res;
                    this.retencion_renta[
                        indiceDelMayor
                    ].debe = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.retencion_renta[indiceDelMayor].debe +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
        },
        Decimales() {
            return new Promise(resolve => {
                var diferencia = 0;
                var total_factura=0;
                console.log("Entro Decimales");
                var total_diferencia = 0;
                if (this.productos_asiento.length > 0) {
                    this.productos_asiento.forEach(el=>{
                        total_factura+=parseFloat(el.haber);
                    });
                    // if(this.total_debe<this.total_haber){
                    if(total_factura!==parseFloat(this.subtotal_factura)){
                        diferencia = parseFloat(
                            this.total_debe - this.total_haber
                        ).toFixed(2);
                    }else{
                        diferencia = parseFloat(
                            this.total_haber-this.total_debe
                        ).toFixed(2);
                    }
                    
                    diferencia = parseFloat(
                        this.total_debe - this.total_haber
                    ).toFixed(2);
                    // }
                    if (diferencia != 0) {
                        var debe_producto = Number(
                            this.productos_asiento[0].haber
                        );
                        var df = Number(diferencia);
                        total_diferencia = debe_producto + df;
                        this.productos_asiento[0].haber = total_diferencia;
                        console.log(
                            "diferencia total producto:" +
                                diferencia +
                                " " +
                                total_diferencia+
                                "r:"+
                                this.productos_asiento[0].haber

                        );
                    }
                }
                resolve(total_diferencia);
            });
        },
        validacion_asiento() {
            var error = 0;
            console.log(this.productos_asiento.length);
            if (this.productos_asiento.length > 0) {
                this.productos_asiento.forEach(el => {
                    // if(el.sector=="producto" && el.iva=="cero"){
                    //     if(el.id_plan_cuentas_iva_0==null){
                    //         error++;
                    //         console.log("producto asiento producto cero");
                    //     }
                    // }
                    // if(el.sector=="producto" && el.iva=="doce"){
                    //     if(el.id_plan_cuentas_iva_12==null){
                    //         error++;
                    //         console.log("producto asiento producto doce");
                    //     }
                    // }
                    // if(el.sector=="servicio"){
                    //     if(el.id_plan_cuentas_servicio==null){
                    //         error++;
                    //         console.log("producto asiento servicio");
                    //     }
                    // }
                    if (el.sector == "producto" && el.iva == "cero") {
                        if (el.id_plan_cuentas_iva_0 == null) {
                            error++;
                            console.log("producto asiento producto cero");
                        } else {
                            if (el.grupo_cuenta_0 == 1) {
                                error++;
                                this.$vs.notify({
                                    color: "danger",
                                    title:
                                        "Solo se puede guardar cuentas contables de MOVIMIENTO"
                                });
                                console.log(
                                    "producto asiento producto cero grupo"
                                );
                            }
                        }
                    }
                    if (el.sector == "producto" && el.iva == "doce") {
                        if (el.id_plan_cuentas_iva_12 == null) {
                            error++;

                            console.log("producto asiento producto doce");
                        } else {
                            if (el.grupo_cuenta_12 == 1) {
                                error++;
                                this.$vs.notify({
                                    color: "danger",
                                    title:
                                        "Solo se puede guardar cuentas contables de MOVIMIENTO"
                                });
                                console.log(
                                    "producto asiento producto doce grupo"
                                );
                            }
                        }
                    }
                    if (el.sector == "servicio") {
                        if (el.id_plan_cuentas_servicio == null) {
                            error++;
                            console.log("producto asiento servicio");
                        } else {
                            if (el.grupo_cuenta_servicio == 1) {
                                error++;
                                this.$vs.notify({
                                    color: "danger",
                                    title:
                                        "Solo se puede guardar cuentas contables de MOVIMIENTO"
                                });
                                console.log("producto asiento servicio grupo");
                            }
                        }
                    }
                    if (el.id_proyecto == null) {
                        error++;
                        console.log("producto asiento proyecto");
                    }
                });
            }
            if (this.iva_asiento.length > 0) {
                this.iva_asiento.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                        console.log("iva_asiento plan_cuentas");
                    }
                    if (el.id_proyecto == null) {
                        error++;
                        console.log("iva_asiento proyecto");
                    }
                });
            }
            if (this.pagos_sin_plc.length > 0) {
                this.pagos_sin_plc.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                        console.log("pagos_sin_plc plan_cuentas");
                    }
                    if (el.id_proyecto == null) {
                        error++;
                        console.log("pagos_sin_plc proyecto");
                    }
                });
            }
            if (this.pagos_con_plc.length > 0) {
                this.pagos_con_plc.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                        console.log("pagos_con_plc plan_cuentas");
                    }
                    if (el.id_proyecto == null) {
                        error++;
                        console.log("pagos_con_plc proyecto");
                    }
                });
            }
            if (this.pagos_anticipo.length > 0) {
                this.pagos_anticipo.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                        console.log("pagos_anticipo plan_cuentas");
                    }
                    if (el.id_proyecto == null) {
                        error++;
                        console.log("pagos_anticipo proyecto");
                    }
                });
            }
            if (this.creditos.length > 0) {
                this.creditos.forEach(el => {
                    // if(el.id_plan_cuentas==null){
                    //     error++;
                    //     console.log("creditos plan_cuenta_prov");
                    // }
                    if (el.exist_plc_cl == "no") {
                        if (el.id_plan_cuentas == null) {
                            error++;
                            console.log("creditos plan_cuenta_grupo");
                        }
                    } else {
                        if (el.id_plan_cuentas_cl == null) {
                            error++;
                            console.log("creditos plan_cuenta_cliente");
                        }
                    }
                    if (el.id_proyecto == null) {
                        error++;
                    }
                });
            }
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                    }
                    if (el.id_proyecto == null) {
                        error++;
                    }
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                    }
                    if (el.id_proyecto == null) {
                        error++;
                    }
                });
            }
            return error;
        },
        agregarcampoConciliacion(index, tipo) {
            this.modal_conciliacion = true;
            this.indextipoarreglo = index;
            if (tipo == "anticipo") {
                this.fecha_pago = this.pagos_anticipo[index].fecha_pago;
                this.nombre_pago = this.pagos_anticipo[index].nombre_pago;
                this.nro_documento = this.pagos_anticipo[
                    index
                ].numero_transaccion;
            } else {
                if (tipo == "forma_pago") {
                    this.fecha_pago = this.pagos_sin_plc[index].fecha_pago;
                    this.nombre_pago = this.pagos_sin_plc[index].nombre_pago;
                    this.nro_documento = this.pagos_sin_plc[
                        index
                    ].numero_transaccion;
                } else {
                    this.fecha_pago = this.pagos_con_plc[index].fecha_pago;
                    this.nombre_pago = this.pagos_con_plc[index].nombre_pago;
                    this.nro_documento = this.pagos_con_plc[
                        index
                    ].numero_transaccion;
                }
            }
        },
        crearasiento(id) {
            this.disabled_asiento=true;
            if (this.validacion_asiento()) {
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento"
                });
                this.disabled_asiento=false;
                return;
            }
            var total = 0;
            total = this.total_debe - this.total_haber;
            console.log("Debe Asiento");
            console.log(this.total_debe);
            console.log("Haber Asiento");
            console.log(this.total_haber);
            console.log("Diferencia Asiento");
            console.log(total);
            if (total !== 0) {
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento"
                });
                this.disabled_asiento=false;
                return;
            }
            if (this.estado_asiento == "no") {
                this.$vs.notify({
                    color: "danger",
                    title:
                        "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha"
                });
                this.disabled_asiento=false;
                return;
            }
            var codigo_asiento = this.codigo.substr(3, this.codigo.length);
            var fecha_hoy = new Date();
            axios
                .post("/api/factura_venta/agregar/asiento", {
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
                .post("/api/factura_venta/agregar/asiento_detalle", {
                    proyecto: this.nombre_proyecto,
                    productos: this.productos_asiento,
                    ice: this.ice,
                    iva_12: this.iva_asiento,
                    pagos_sin_plc: this.pagos_sin_plc,
                    pagos_con_plc: this.pagos_con_plc,
                    pagos_anticipo: this.pagos_anticipo,
                    creditos: this.creditos,
                    retencion_iva: this.retencion_iva,
                    retencion_renta: this.retencion_renta,
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
                    this.listar(1, this.buscar);
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
        enviocorreo(datos) {
            console.log(datos);
            this.$vs.notify({
                time: 3000,
                title: "Enviando comprobantes",
                text: "Espere por favor, enviando comprobantes",
                color: "warning"
            });
            var fecha_actual = moment(datos.fecha_autorizacion).format("LL");
            axios
                .get(
                    "/api/creacion_factura_venta_pdf/" + datos.id_factura + "/v"
                )
                .then(() => {
                    axios
                        .post("/api/factura_venta/enviarcorreo", {
                            tipo: "Factura",
                            nombre: datos.nombre,
                            claveAcceso: datos.clave_acceso,
                            email: datos.email,
                            id_empresa: datos.id_empresa,
                            empresas: datos,
                            fecha_autorizacion: fecha_actual,
                            valor_total: datos.valor_total,
                            logo: datos.logo,
                            nombre_empresa: datos.nombre_empresa
                        })
                        .then(({ data }) => {
                            this.$vs.notify({
                                time: 5000,
                                title: "Documentos enviados",
                                text: "Se ha enviado al correo exitosamente",
                                color: "success"
                            });
                        });
                });
        },
        enviocorreootro(datos) {
            this.popupcorreo = true;
            this.nombrecliente = "";
            this.correocliente = "";
            this.estadocorreo = 1;
            this.datosg = datos;
        },
        cancelarenvio() {
            this.popupcorreo = false;
            this.nombrecliente = "";
            this.correocliente = "";
            this.datosg = {};
        },
        enviocorreootros() {
            if (this.validarcorreo()) {
                return;
            }
            this.popupcorreo = false;
            var fecha_actual = moment(this.datosg.fecha_autorizacion).format(
                "LL"
            );
            this.$vs.notify({
                time: 3000,
                title: "Enviando comprobantes",
                text: "Espere por favor, enviando comprobantes",
                color: "warning"
            });
            axios
                .get(
                    "/api/creacion_factura_venta_pdf/" +
                        this.datosg.id_factura +
                        "/v"
                )
                .then(() => {
                    axios
                        .post("/api/factura_venta/enviarcorreo_masivo", {
                            tipo: "Factura",
                            claveAcceso: this.datosg.clave_acceso,
                            email: this.chip_correo,
                            id_empresa: this.datosg.id_empresa,
                            empresas: this.datosg,
                            fecha_autorizacion: fecha_actual,
                            valor_total: this.datosg.valor_total,
                            logo: this.datosg.logo,
                            nombre_empresa: this.datosg.nombre_empresa,
                            id_factura: this.datosg.id_factura
                        })
                        .then(({ data }) => {
                            this.$vs.notify({
                                time: 5000,
                                title: "Documentos enviados",
                                text: "Se ha enviado al correo exitosamente",
                                color: "success"
                            });
                        })
                        .catch(err => {
                            this.$vs.notify({
                                time: 5000,
                                title: "Error de envio",
                                text:
                                    "Intente nuevamente o comuniquese con el administrador",
                                color: "warning"
                            });
                        });
                })
                .catch(err => {
                    this.$vs.notify({
                        time: 5000,
                        title: "Error de envio",
                        text:
                            "Intente nuevamente o comuniquese con el administrador",
                        color: "warning"
                    });
                });
        },
        validarcorreo() {
            this.errorenvio = 0;
            this.errornombrecliente = [];
            this.errorchip_correo = [];
            if (!this.chip_correo) {
                this.errorchip_correo.push("Ingrese un correo");
                this.errorenvio = 1;
            }

            return this.errorenvio;
        },
        enviocorreootro_guia(datos) {
            this.popupcorreo = true;
            this.nombrecliente = "";
            this.correocliente = "";
            this.estadocorreo = 2;
            this.datosg = datos;
        },
        enviocorreootros_guia() {
            if (this.validarcorreo()) {
                return;
            }
            this.popupcorreo = false;
            var fecha_actual = moment(this.datosg.fecha_inicio_tr).format("LL");
            this.$vs.notify({
                time: 3000,
                title: "Enviando comprobantes",
                text: "Espere por favor, enviando comprobantes",
                color: "warning"
            });
            axios
                .post("/api/guia/enviarcorreo_masivo", {
                    tipo: "Factura",
                    claveAcceso: this.datosg.clave_acceso_guia,
                    email: this.chip_correo,
                    id_empresa: this.datosg.id_empresa,
                    empresas: this.datosg,
                    fecha_autorizacion: fecha_actual,
                    valor_total: "0.00",
                    logo: this.datosg.logo,
                    nombre_empresa: this.datosg.nombre_empresa
                })
                .then(({ data }) => {
                    this.$vs.notify({
                        time: 5000,
                        title: "Documentos enviados",
                        text: "Se ha enviado al correo exitosamente",
                        color: "success"
                    });
                })
                .catch(err => {
                    this.$vs.notify({
                        time: 5000,
                        title: "Error de envio",
                        text:
                            "Intente nuevamente o comuniquese con el administrador",
                        color: "success"
                    });
                });
        },
        descargarpdf_guia(datos) {
            window.open(
                "/api/creacion_guia_remision_pdf/" + datos.guia + "/d/fv",
                "_top"
            );
        },
        descargarfactura_fisica(datos){
            axios({
                    url: "/api/factura/fisica",
                    method: "GET",
                    responseType: "arraybuffer",
                    params:{
                      id:datos.id_factura
                    }
                
                }).then(resp=>{
                  console.log("ejecutado empleado");
                //this.contenidopr=res.data;
                console.log("resp:"+resp);
                  console.log("resp data:"+resp.data);
                console.log(JSON.stringify(resp.data)+"proyecto");
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
                    title: "PDF Generado",
                    text: "Su PDF esta siendo descargado exitosamente!",
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
        descargarxml_guia(datos) {
            window.open(
                "/" +
                    datos.id_empresa +
                    "/vistaxml/guia/" +
                    datos.clave_acceso_guia,
                "_top"
            );
        },
        descargarpdfyxml_guia(datos) {
            window.open(
                "/" +
                    datos.id_empresa +
                    "/vistapdfyxml/guia/" +
                    datos.clave_acceso_guia,
                "_top"
            );
        },
        remove_chip_nombre(item) {
            this.chips.splice(this.chips.indexOf(item), 1);
        },
        remove_chip_correo(item) {
            this.chip_correo.splice(this.chip_correo.indexOf(item), 1);
        },
        //metodos de impresora
        enviarticket(datos) {
            //this.imprimir("POS Impresora");
            //this.id_factura_imp=id;
            axios
                .get("/api/imprimir/ticket/" + datos.id_factura + "/d")
                .then(resp => {
                    window.open(
                        "/api/imprimir/ticket/" + datos.id_factura + "/d",
                        "_blank"
                    );
                })
                .catch(err => {});
        },
        imprimir(nombre) {
            // axios.get("/api/imprimir/ticket",{
            //     params:{
            //         id_factura:this.id_factura_imp,
            //         id_empresa:this.usuario.id_empresa,
            //         nombre_impresora:nombre
            //     }
            // }).then(resp=>{
            //     // this.principal_factura().then(value=>{
            //     //     this.$vs.notify({
            //     //         title: "Impreso con exito",
            //     //         text: "La factura se imprimio con exito",
            //     //         color: "success"
            //     //     });
            //     //     //this.$router.push("/facturacion/factura-venta");
            //     // }).catch(err=>{
            //     //     console.log("ERROR::[imprimir_factura]"+err);
            //     // });
            //     //this.modal_impresora=false;
            //     //return resp.data+"hhooooola";
            // }).catch(err=>{
            //         console.log("ERROR::[imprimir_factura]"+err);
            //     //this.modal_impresora=false;
            // });
        },
        principal_factura() {
            return new Promise(resolve => {
                this.modal_impresora = false;
                this.nombre_impresora = "";
                resolve((this.modal_impresora = false));
            });
        },
        cancelar_impresion() {
            this.modal_impresora = false;
            this.$router.push("/facturacion/factura-venta");
        },
        //----------------------------------------------------- FUNCIONES FACUTACION MASIVA --------------------------------------------------//
        abrirmodalfact_masiva() {
            this.modalfact_masiva = true;
        },
        
        ejemploticket(){
            var url="/api/imprimir/ejemplo_ticket/6130"
            axios.get(url).then(resp=>{
                this.$vs.notify({

                        text: "Se IMPRIMIO",
                        color: "success"
                    });

            }).catch(err=>{
                    this.$vs.notify({

                        text: "No se imprimio",
                        color: "danger"
                    });
                    
            });
        },
        //funciones tabla interes
        abrirmodalinteres(){
            this.modalinteres=true;
            this.listar_interes(1,"");
        },
        agregarmodal_interes(){
            this.modalAgregar_Interes=true;
            this.interes="";
            this.id_interesrecupera=null;
        },
        listar_interes(page1,buscar1){
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.contenido_interes = [];
                var url = "/api/tabla_interes/"+this.usuario.id_empresa;
                axios.get(url, {
                    params:{
                         page: page1,
                         buscar: buscar1,
                         id_user: this.usuario.id
                    }
                }).then(res => {
                    this.contenido_interes=res.data.recupera;
                });
            }, 800);

        },
        editarmodal_interes(id,intres){
            this.id_interesrecupera=id;
            this.interes=intres;
            this.modalAgregar_Interes=true;
            
        },
        eliminar_interes(cd){
            // this.$vs.dialog({
            //     type: "confirm",
            //     color: "danger",
            //     title: `Confirmar`,
            //     text: `¿Desea Eliminar este registro?`,
            //     acceptText: "Aceptar",
            //     cancelText: "Cancelar",
            //     accept: this.acceptAlert_Interes,
            //     parameters: cd
            // });
            this.id_interesrecupera=cd;
            this.modalEliminar_Interes=true;
            

        },
        eliminar_interes_accept(parameters) {
            axios.get("/api/tabla_interes/eliminar/" + parameters+"/"+this.usuario.id)
            .then(resp=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.id_interesrecupera=null;
                this.interes="";
                this.listar_interes(1,"");
            })
            .catch(err=>{
                this.$vs.notify({

                    text: "No se puedo Eliminar este registro",
                    color: "danger"
                });
            });
            
        },
        cancelar_interes_eliminar(){
            this.id_interesrecupera=null;
            this.modalEliminar_Interes=false;
        },
        guardar_interes(){
            var url_interes="/api/tabla_interes/guardar";
            axios.post(url_interes,{
                interes:this.interes,
                id_empresa:this.usuario.id_empresa,
                ucrea:this.usuario.id
            })
            .then(resp=>{
                    this.$vs.notify({
                        title:"Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.modalEliminar_Interes=false;
                    this.modalAgregar_Interes=false;
                    this.id_interesrecupera=null;
                    this.interes="";
                    this.listar_interes(1,"");
            })
            .catch(err=>{
                this.$vs.notify({

                        text: "Error al Eliminar",
                        color: "danger"
                    });
            });
        },
        editar_interes(){
            var url_interes="/api/tabla_interes/editar";
            axios.put(url_interes,{
                id:this.id_interesrecupera,
                interes:this.interes,
                id_empresa:this.usuario.id_empresa,
                umodifica:this.usuario.id
            })
            .then(resp=>{
                    this.$vs.notify({
                        title:"Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.modalAgregar_Interes=false;
                    this.id_interesrecupera=null;
                    this.interes="";
                    this.listar_interes(1,"");
            })
            .catch(err=>{
                this.$vs.notify({

                        text: "Error al Guardar",
                        color: "danger"
                    });
            });
        },
        vaciar_interes(){
            this.interes="";
        },
        cancelar_interes(){
            this.interes="";
            this.id_interesrecupera=null;
            this.listar_interes(1,"");
            this.modalAgregar_Interes=false;
        },
        // funciones tabla interes anual
        abrirmodalinteres_Anual(){
            this.modalinteres_anual=true;
            this.listar_interes_anual(1,"");
        },
        agregarmodal_interes_anual(){
            this.modalAgregar_Interes_anual=true;
            this.interes_anual="";
            this.id_plan_cuenta_interes=null;
            this.cta_contable_interes="",
            this.periodo_pago="";
            this.tiempo_pago="";
            this.id_interesrecupera_anual=null;
        },
        listar_interes_anual(page1,buscar1){
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.contenido_interes_anual = [];
                var url = "/api/tabla_interes_anual/"+this.usuario.id_empresa;
                axios.get(url, {
                    params:{
                         page: page1,
                         buscar: buscar1,
                         id_user: this.usuario.id
                    }
                }).then(res => {
                    this.contenido_interes_anual=res.data.recupera;
                });
            }, 800);

        },
        editarmodal_interes_anual(datos){
            this.id_interesrecupera_anual=datos.id_tabla_interes_anual;
            this.interes_anual=datos.interes_anual;
            this.periodo_pago=datos.periodo_pago;
            this.tiempo_pago=datos.tiempo_pago;
            this.id_plan_cuenta_interes=datos.id_plan_cuentas;
            this.cta_contable_interes=datos.nombre_cuenta;
            this.modalAgregar_Interes_anual=true;
            
        },
        eliminar_interes_anual(cd){
            // this.$vs.dialog({
            //     type: "confirm",
            //     color: "danger",
            //     title: `Confirmar`,
            //     text: `¿Desea Eliminar este registro?`,
            //     acceptText: "Aceptar",
            //     cancelText: "Cancelar",
            //     accept: this.acceptAlert_Interes,
            //     parameters: cd
            // });
            this.id_interesrecupera_anual=cd;
            this.modalEliminar_Interes_anual=true;
            

        },
        eliminar_interes_accept_anual(parameters) {
            axios.get("/api/tabla_interes_anual/eliminar/" + parameters+"/"+this.usuario.id)
            .then(resp=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.id_interesrecupera_anual=null;
                this.interes_anual="";
                this.id_plan_cuenta_interes=null;
                this.cta_contable_interes="",
                this.periodo_pago="";
                this.tiempo_pago="";
                this.modalEliminar_Interes_anual=false;
                this.listar_interes_anual(1,"");
            })
            .catch(err=>{
                this.$vs.notify({

                    text: "No se puedo Eliminar este registro",
                    color: "danger"
                });
            });
            
        },
        cancelar_interes_eliminar_anual(){
            this.id_interesrecupera_anual=null;
            this.modalEliminar_Interes_anual=false;
        },
        guardar_interes_anual(){
            var url_interes="/api/tabla_interes_anual/guardar";
            axios.post(url_interes,{
                interes_anual:this.interes_anual,
                periodo_pago:this.periodo_pago,
                tiempo_pago:this.tiempo_pago,
                id_plan_cuentas:this.id_plan_cuenta_interes,
                id_empresa:this.usuario.id_empresa,
                ucrea:this.usuario.id
            })
            .then(resp=>{
                    this.$vs.notify({
                        title:"Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.modalEliminar_Interes_anual=false;
                    this.modalAgregar_Interes_anual=false;
                    this.id_interesrecupera_anual=null;
                    this.interes_anual="";
                    this.id_plan_cuenta_interes=null;
                    this.cta_contable_interes="",
                    this.periodo_pago="";
                    this.tiempo_pago="";
                    this.listar_interes_anual(1,"");
            })
            .catch(err=>{
                this.$vs.notify({

                        text: "Error al Eliminar",
                        color: "danger"
                    });
            });
        },
        editar_interes_anual(){
            var url_interes="/api/tabla_interes_anual/editar";
            axios.put(url_interes,{
                id:this.id_interesrecupera_anual,
                interes_anual:this.interes_anual,
                periodo_pago:this.periodo_pago,
                tiempo_pago:this.tiempo_pago,
                id_plan_cuentas:this.id_plan_cuenta_interes,
                id_empresa:this.usuario.id_empresa,
                umodifica:this.usuario.id
            })
            .then(resp=>{
                    this.$vs.notify({
                        title:"Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.modalAgregar_Interes_anual=false;
                    this.id_interesrecupera_anual=null;
                    this.interes_anual="";
                    this.id_plan_cuenta_interes=null;
                    this.cta_contable_interes="",
                    this.periodo_pago="";
                    this.tiempo_pago="";
                    this.listar_interes_anual(1,"");
            })
            .catch(err=>{
                this.$vs.notify({

                        text: "Error al Guardar",
                        color: "danger"
                    });
            });
        },
        vaciar_interes_anual(){
            this.interes_anual="";
            this.id_plan_cuenta_interes=null;
            this.cta_contable_interes="",
            this.periodo_pago="";
            this.tiempo_pago="";
        },
        cancelar_interes_anual(){
            this.interes_anual="";
            this.id_plan_cuenta_interes=null;
            this.cta_contable_interes="",
            this.periodo_pago="";
            this.tiempo_pago="";
            this.id_interesrecupera_anual=null;
            this.listar_interes_anual(1,"");
            this.modalAgregar_Interes_anual=false;
        },
        handleSelected_plc(tr){

            (this.id_plan_cuenta_interes =`${tr.id_plan_cuentas}`),
            (this.cta_contable_interes =`${tr.codcta}`+`${tr.nomcta}`),
            (this.popupActive_Interes_Anual = false);
        },
        listar3(buscar3) {
            let me = this;
            var url =
                "/api/notacredito/listar_cuenta_contable" 
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
                ;
            axios.get(url,{
                params:{
                    empresa:this.usuario.id_empresa,
                    buscar:buscar3
                }
            })
                .then(({data})=> {
                    var respuesta = data;
                    me.contenido_plc = respuesta;
                })
                .catch(function(error) {
                });
        },

    },
    mounted() {
        this.listar(1, this.buscar);
        this.getVendedores();
        this.getClientes();
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
.text-center .vs-table-text {
    text-align: center;
}
.vs-table-text {
    display: block !important;
}
.estilosacciones .vs-button-primary {
    padding: 0.5rem 1rem !important;
    cursor: pointer;
}
.vs-con-dropdown {
    cursor: pointer;
}
.feather-icon {
    vertical-align: sub;
}
.modalist .vs-popup {
    width: 1320px !important;
}
.peque2 .vs-popup {
    width: 1080px !important;
}
.vs-popup {
    width: 600px !important;
}
.valoresc input {
    text-align: center;
}
.valoresc .vs-input--label {
    text-align: center;
}
</style>
