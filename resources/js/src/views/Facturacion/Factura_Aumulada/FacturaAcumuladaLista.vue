<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-left">
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
                            <label class="vs-input--label mr-1">Activos</label>
                            <vs-switch
                                color="success"
                                @input="filtertabla()"
                                v-model="filterstable.filt_activ"
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
                    </template>
                </div>
                <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                    <vs-input class="mb-4 md:mb-0 mr-4" v-model="buscar" @keyup="listar(1, buscar)" v-bind:placeholder="i18nbuscar"/>
                    <div
                        class="flex flex-wrap justify-between items-center mr-1 ml-1"
                        v-if="crearrol"
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
                    <div class="dropdown-button-container">
                        <vs-button class="btnx" type="filled" to="/facturacion/factura_acumulada/agregar">Agregar</vs-button>
                        <vs-dropdown>
                            <vs-button class="btn-drop" type="filled" icon="expand_more"></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" @click="generarreporte = true">Generar reporte</vs-dropdown-item>
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe max-items="25" pagination :data="contenido">
                <template slot="thead">
                    <vs-th style="width: 15em;">No.</vs-th>
                    <vs-th>Cliente</vs-th>
                    <vs-th>Fecha Emisión</vs-th>
                    <vs-th >Valor Total</vs-th>
                    <vs-th >Estado</vs-th>
                    <vs-th>Opciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr v-for="(datos, index) in data" :key="index">
                        <vs-td v-if="datos.clave_acceso">{{(datos.clave_acceso).substring(24,27)}}-{{(datos.clave_acceso).substring(27,30)}}-{{(datos.clave_acceso).substring(30,39)}}</vs-td><vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td><vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.fecha_emision">{{datos.fecha_emision | fecha}}</vs-td><vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.valor_total">{{datos.valor_total | currency}}</vs-td>
                        <vs-td v-else >-</vs-td>
                        <vs-td v-if="datos.estadof == 0" style="color: rgb(255, 0, 0);font-weight: bold;">
                            ANULADO
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
                        <vs-td v-else style="color: #28c76f;font-weight: bold;">
                            ACTIVO
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
                        <vs-td class="whitespace-no-wrap  estilosacciones">
                            <vs-dropdown vs-custom-content vs-trigger-click>
                                <vs-button class="btn-drop" type="filled" icon="expand_more">Acciones</vs-button>
                                 <vs-dropdown-menu style="width:13em;">
                                      <vs-dropdown-item divider class="text-center" @click.stop="descargarpdf(datos)"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>
                                      <vs-dropdown-item divider class="text-center" @click.stop="enviocorreocliente(datos.id_nota_venta)"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Enviar al correo del Cliente</vs-dropdown-item>
                                      <vs-dropdown-item divider class="text-center" @click.stop="enviocorreootro(datos.id_nota_venta)"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Enviar a un correo</vs-dropdown-item>
                                        <vs-dropdown-item
                                            divider
                                            class="text-center"

                                            @click="enviarticket(datos)"
                                            ><feather-icon
                                                icon="PrinterIcon"
                                                svgClasses="w-5 h-5"
                                            ></feather-icon>
                                            Imprimir Ticket</vs-dropdown-item
                                        >

                                    <vs-dropdown-item divider class="text-center" @click="ver(datos.id_nota_venta)" v-if="(datos.respuesta == 'Enviado' && datos.estadof != 0) || (datos.estadof != 0 && datos.contabilidad!=null)"><feather-icon icon="EyeIcon" svgClasses="w-5 h-5"></feather-icon> Visualizar</vs-dropdown-item>
                                    <vs-dropdown-item class="text-center" @click="ver(datos.id_nota_venta)" v-if="datos.estadof == 0"><feather-icon icon="EyeIcon" svgClasses="w-5 h-5"></feather-icon> Visualizar</vs-dropdown-item>

                                    <vs-dropdown-item divider class="text-center" @click="editar(datos.id_nota_venta)" v-if="datos.estadof != 0 && datos.contabilidad==null"><feather-icon icon="EditIcon" svgClasses="w-5 h-5"></feather-icon> Editar</vs-dropdown-item>
                                    <vs-dropdown-item
                                        divider
                                        class="text-center"
                                        @click="duplicar(datos)"
                                        ><feather-icon
                                            icon="BookIcon"
                                            svgClasses="w-5 h-5"
                                        ></feather-icon>
                                        Duplicar Nota Venta</vs-dropdown-item
                                    >
                                    <!-- <vs-dropdown-item divider class="text-center" @click="duplicar(datos)"><feather-icon icon="BookIcon" svgClasses="w-5 h-5"></feather-icon> Duplicar Fatura</vs-dropdown-item> -->
                                    <vs-dropdown-item divider class="text-center" @click="eliminar(datos)" v-if="datos.estadof != 0"><feather-icon icon="TrashIcon" svgClasses="w-5 h-5"></feather-icon> Cancelar</vs-dropdown-item>

                                    <vs-divider position="center" style="margin: 10px 0px;font-size: 16px;" v-if="datos.guia && datos.estadof != 0">Guia</vs-divider>
                                    <vs-dropdown-item class="text-center" @click="guiaenvio(datos.guia)" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia != 'Enviado'"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Reenviar Guia </vs-dropdown-item>
                                    <vs-dropdown-item class="text-center" @click.stop="enviocorreootro_guia(datos),tipoenvio=2" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia == 'Enviado'"><feather-icon icon="MailIcon" svgClasses="w-5 h-5"></feather-icon> Enviar a un correo</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click="descargarpdf_guia(datos)" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia == 'Enviado'"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click.stop="descargarxml_guia(datos)" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia == 'Enviado'"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar XML</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click.stop="descargarpdfyxml_guia(datos)" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia == 'Enviado'"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Desc. PDF y XML</vs-dropdown-item>

                                    <!--<vs-dropdown-item divider class="text-center" @click="descargarpdf_guia(datos)" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia == 'Enviado'"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar PDF</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click.stop="descargarxml_guia(datos)" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia == 'Enviado'"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Descargar XML</vs-dropdown-item>
                                    <vs-dropdown-item divider class="text-center" @click.stop="descargarpdfyxml_guia(datos)" v-if="datos.guia && datos.estadof != 0 && datos.respuesta_guia == 'Enviado'"><feather-icon icon="DownloadIcon" svgClasses="w-5 h-5"></feather-icon> Desc. PDF y XML</vs-dropdown-item> -->
                                 </vs-dropdown-menu>
                            </vs-dropdown>
                            <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.estadof != 0 && datos.contabilidad==null" svgClasses="w-5 h-5 fill-current text-primary" @click="Contabilidad(datos.id_nota_venta)" />
                            <feather-icon icon="SlidersIcon" class="cursor-pointer" v-if="datos.estadof != 0 && datos.contabilidad==1" svgClasses="w-5 h-5 fill-current text-success" @click="Contabilidad(datos.id_nota_venta)" />
                            <feather-icon icon="CheckIcon"  v-if="datos.estadof != 0 && datos.contabilidad!==null" svgClasses="w-5 h-5"/>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
            <vs-popup title="Destinatario de Correo" :active.sync="popupcorreo">
                <div class="vx-col sm:w-full w-full mb-6 relative">
                    <vs-input label="Destinatario:" class="mb-4 md:mb-0 mr-4" v-model="nombrecliente"/>
                    <div v-show="errorenvio">
                        <div v-for="err in errornombrecliente" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:full w-full mb-6 relative">
                    <vs-chips color="rgb(145, 32, 159)" placeholder="Agregue los correos" v-model="chip_correo" icon-pack="feather" remove-icon="icon-trash-2">
                        <vs-chip :key="data" @click="remove_chip_correo(data)" v-for="data in chip_correo" closable icon-pack="feather" close-icon="icon-trash-2">
                            {{ data }}
                        </vs-chip>
                    </vs-chips>
                    <span style="font-size: 11px;margin-left: 10px;">despues de agregar un correo pulse la tecla enter</span>
                    <div v-show="errorenvio">
                        <div v-for="err in errorchip_correo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col w-full mt-5">
                    <vs-button color="success" type="border" v-if="estadocorreo==1" @click="enviocorreootros()">Enviar</vs-button>
                    <vs-button color="success" type="border" v-else @click="enviocorreootros_guia()">Enviar</vs-button>
                    <vs-button color="danger" type="border" @click="cancelarenvio()">Cancelar</vs-button>
                </div>
            </vs-popup>
            <vs-popup title="Asiento Contable" class="peque2" :active.sync="modalAsiento">
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
                <div
                    id="one-row"
                    class="vx-row"
                    v-for="(add, index1) in productos_asiento"
                    v-bind:key="index1"
                >
                <div class="vx-col xs:w-10/12 sm:w-11/12">
                        <div class="vx-row">
                            <!--CUENTA CONTABLE-->
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
                            <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.haber>0">
                                <vx-input-group>
                                    <!-- prettier-ignore -->
                                    <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta"
                                        disabled
                                    />

                                </vx-input-group>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.haber>0">
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
                            <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.haber>0">
                                <vx-input-group>
                                    <!-- prettier-ignore -->
                                    <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta"
                                        disabled
                                    />

                                </vx-input-group>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-6" v-if="data.haber>0">
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
                    v-for="(data,index) in pagos_sin_plc"
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
                            <div class="vx-col sm:w-2/12 w-full mb-2" v-if="data.debe>0">
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
                                    @click="agregarcampoConciliacion(index,'forma_pago')"
                                    >C</vs-button
                                >
                            </div>


                        </div>
                    </div>
                </div>
                <div
                    id="fig2-row"
                    class="vx-row"
                    v-for="(data,index) in pagos_con_plc"
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
                            <div class="vx-col sm:w-1/12 w-full mb-6" v-if="data.debe>0 && data.bansel!==null">
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                    color="success"
                                    @click="agregarcampoConciliacion(index,'plc')"
                                    >C</vs-button
                                >
                            </div>


                        </div>
                    </div>
                </div>
                <div
                    id="fig-row"
                    class="vx-row"
                    v-for="(data,index) in pagos_anticipo"
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
                            <div class="vx-col sm:w-1/12 w-full mb-6" v-if="data.debe>0 && data.bansel!==null">
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                    color="success"
                                    @click="agregarcampoConciliacion(index,'anticipo')"
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
                            <div class="vx-col sm:w-1/3 w-full mb-6" v-if="data.exist_plc_cl=='no'">
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
                <div
                    id="five-row"
                    class="vx-row"
                    v-for="data in retencion_renta"
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
                {{Diferencia}}
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
        </vx-card>
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
const axios = require("axios");
const {rutasEmpresa: { DATA_EMPRESA }} = require("../../../../../../config-routes/config.js");
import script_comprobantes from '../../../../factura.js';
export default {
    components: {
        AgGridVue,
        flatPickr
    },
    filters: {
        fecha(data) {
            return moment(data).format("LL");
        },
        fechayhora(data) {
            return moment(data).format("LLL");
        }
    },
    data(){
        return{
            i18nbuscar: this.$t("i18nbuscar"),
            buscar:"",
            contenido:[],
            id_nota:"",
            //variables email
            popupcorreo:false,
            nombrecliente: "",
            correocliente: "",
            errorenvio:0,
            errornombrecliente:[],
            errorchip_correo:[],
            chip_nombre:[],
            chip_correo: [],
            estadocorreo: 1,
            //variables Contabilizar
            modalAsiento:false,
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
            nombre_pago:"",
            id_pago:"",
            fecha_pago:"",
            nro_documento:"",
            diferencia_debe:0,
            diferencia_haber:0,
            ice:[],
            estadocorreo: 1,
            estado_asiento:"",
            lista:{
                factura:{},
                cliente:[],
                productos:[],
                pagos:[],
                creditos:{},
                iva:[],
                renta:[]
            },
            datosg: {},
            //fitrar tabla
            filterstable: {
                contrue: [],
                filtertab: false,
                filt_asientos: true,
                filt_noasientos: true,
                filt_anul: true,
                filt_activ:true,
                filt_autoris: true,
                filt_noautoris: true,

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
            var res = 0
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Nota Venta"){
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
                    if(el.nombre == "Nota Venta"){
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
                    if(el.nombre == "Nota Venta"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        },
        //comp asientos
        suma_debe(){
            var total=0;
            if(this.creditos.length>0){
                this.creditos.forEach(el => {
                    if(el.debe !==null){
                        total+=parseFloat(el.debe);
                    }

                });
            }
            if(this.retencion_iva.length>0){
                this.retencion_iva.forEach(el=>{
                    total+=parseFloat(el.debe);
                });
            }
            if(this.retencion_renta.length>0){
                this.retencion_renta.forEach(el=>{
                    total+=parseFloat(el.debe);
                });
            }
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc.forEach(el=>{
                    total+=parseFloat(el.debe);
                });
            }
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc.forEach(el=>{
                    total+=parseFloat(el.debe);
                });
            }
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo.forEach(el=>{
                    total+=parseFloat(el.debe);
                });
            }

            this.total_debe=total.toFixed(2);
        },
        suma_haber(){
            var total=0;
            if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el => {
                    if(el.haber !==null){
                        total+=parseFloat(el.haber);
                    }

                });
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el => {
                    if(el.haber !==null){
                        total+=parseFloat(el.haber);
                    }

                });
            }
            if(this.ice.length>0){
                this.ice.forEach(el => {
                    if(el.haber !==null){
                        total+=parseFloat(el.haber);
                    }

                });
            }

            this.total_haber=total.toFixed(2);
        },
        cambioDecimales(){
            if(this.creditos.length>0){
                this.creditos.forEach(el => {
                    if(el.debe !==null){
                        el.debe=parseFloat(el.debe).toFixed(2);
                    }

                });
            }
            if(this.retencion_iva.length>0){
                this.retencion_iva.forEach(el=>{
                    el.debe=parseFloat(el.debe).toFixed(2);
                });
            }
            if(this.retencion_renta.length>0){
                this.retencion_renta.forEach(el=>{
                    el.debe=parseFloat(el.debe).toFixed(2);
                });
            }
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc.forEach(el=>{
                    el.debe=parseFloat(el.debe).toFixed(2);
                });
            }
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc.forEach(el=>{
                    el.debe=parseFloat(el.debe).toFixed(2);
                });
            }
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo.forEach(el=>{
                    el.debe=parseFloat(el.debe).toFixed(2);
                });
            }
             if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }

                });
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }

                });
            }
            if(this.ice.length>0){
                this.ice.forEach(el => {
                    if(el.haber !==null){
                        el.haber=parseFloat(el.haber).toFixed(2);
                    }

                });
            }
        },
        igualar(){
            var cambio_iva=0;
            var total_iva=0;
            var cambio_renta=0;
            var total_renta=0;
            var cambio_pag=0;
            var num_may_iva=0;
            var otro_iva=-1;
            var num_may_renta=0;
            var otro_renta=-1;
            if(this.retencion_iva.length>0){
                this.retencion_iva.forEach(el=>{
                    total_iva+=parseFloat(el.haber);
                });
            }
            if(this.retencion_renta.length>0){
                this.retencion_renta.forEach(el=>{
                    total_renta+=parseFloat(el.haber);
                });
            }
            if(this.retencion_iva.length>0){
                this.retencion_iva.forEach(el=>{
                    if(el.haber>num_may_iva){
                        otro_iva++;
                        num_may_iva=el.haber;
                    }
                });
            }
            if(this.retencion_renta.length>0){
                this.retencion_renta.forEach(el=>{
                    if(el.haber>num_may_renta){
                        otro_renta++;
                        num_may_renta=el.haber;
                    }
                });
            }

            var index_renta=this.retencion_renta.find( fruta => fruta.haber === num_may_renta );
            console.log("num renta:"+num_may_renta+" total:"+otro_renta);
            console.log("num iva:"+num_may_iva+" total iva:"+otro_iva);
            if(this.retencion_iva.length>0){


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
                    if(this.retencion_iva[0].total_iva<total_iva){
                         cambio_iva=total_iva-this.retencion_iva[0].total_iva;
                         this.retencion_iva[otro_iva].haber=this.retencion_iva[otro_iva].haber-cambio_iva;
                     }
                     //console.log(JSON.stringify(elementoYaExiste2)+"exist");
            //}
            }
            if(this.retencion_renta.length>0){
                    if(this.retencion_renta[0].total_renta<total_renta){
                        cambio_renta=total_renta-this.retencion_renta[0].total_renta;
                        this.retencion_renta[otro_renta].haber=this.retencion_renta[otro_renta].haber-cambio_renta;
                    }
            }

            console.log("total con cambio renta:"+cambio_renta+" total renta:"+total_renta);
            console.log("total con cambio iva:"+cambio_iva+" total iva:"+total_iva);
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
        Diferencia(){

                if(this.pagos_sin_plc.length>0){
                    if(this.pagos_sin_plc.length==1){
                        if(parseFloat(this.pagos_sin_plc[0].debe)!==parseFloat(this.pagos_sin_plc[0].total)){
                            var diferencia_pagos_sin_plc=parseFloat(this.pagos_sin_plc[0].total)-parseFloat(this.pagos_sin_plc[0].debe);
                            var total_pagos_sin_plc=parseFloat(this.pagos_sin_plc[0].debe)+diferencia_pagos_sin_plc;
                            this.pagos_sin_plc[0].debe=total_pagos_sin_plc;
                            //this.pagos_sin_plc[0].debe=diferencia_pagos_sin_plc;
                            console.log("Diferebcia en el debe: pagos_sin_plc"+total_pagos_sin_plc);
                        }
                    }
                }
                if(this.pagos_con_plc.length>0){
                    if(this.pagos_con_plc.length==1){
                        if(this.pagos_con_plc[0].debe!==this.pagos_con_plc[0].total){
                            var diferencia_pagos_con_plc=parseFloat(this.pagos_con_plc[0].total)-parseFloat(this.pagos_con_plc[0].debe);
                            var total_pagos_con_plc=parseFloat(this.pagos_con_plc[0].debe)+diferencia_pagos_con_plc;
                            this.pagos_con_plc[0].debe=total_pagos_con_plc;
                            console.log("Diferebcia en el debe: pagos_con_plc"+total_pagos_con_plc);
                        }
                    }
                }
                if(this.pagos_anticipo.length>0){
                    if(this.pagos_anticipo.length==1){
                        if(this.pagos_anticipo[0].debe!==this.pagos_anticipo[0].total){
                            var diferencia_pagos_anticipo=parseFloat(this.pagos_anticipo[0].total)-parseFloat(this.pagos_anticipo[0].debe);
                            var total_pagos_anticipo=parseFloat(this.pagos_anticipo[0].debe)+diferencia_pagos_anticipo;
                            this.pagos_anticipo[0].debe=total_pagos_anticipo;
                            //this.pagos_anticipo[0].debe=diferencia_pagos_anticipo;
                            console.log("Diferebcia en el debe: pagos_anticipo"+total_pagos_anticipo);
                        }
                    }
                }


            if(this.retencion_iva.length>0){
                if(this.retencion_iva.length==1){
                    if(this.retencion_iva[0].debe!==this.retencion_iva[0].cantidadiva){
                        var diferencia_retencion_iva=parseFloat(this.retencion_iva[0].cantidadiva)-parseFloat(this.retencion_iva[0].debe);
                        var total_retencion_iva=parseFloat(this.retencion_iva[0].debe)+diferencia_retencion_iva;
                        this.retencion_iva[0].debe=total_retencion_iva;
                        //this.retencion_iva[0].debe=diferencia_retencion_iva;
                        console.log("Diferebcia en el debe: retencion_iva"+total_retencion_iva);
                    }
                }
            }
            if(this.retencion_renta.length>0){
                if(this.retencion_renta.length==1){
                    if(this.retencion_renta[0].debe!==this.retencion_renta[0].cantidadrenta){
                        var diferencia_retencion_renta=parseFloat(this.retencion_renta[0].cantidadrenta)-parseFloat(this.retencion_renta[0].debe);
                        var total_retencion_renta=parseFloat(this.retencion_renta[0].debe)+diferencia_retencion_renta;
                        this.retencion_renta[0].debe=total_retencion_renta;
                        //this.retencion_renta[0].debe=diferencia_retencion_renta;
                        console.log("Diferebcia en el debe: retencion_renta"+total_retencion_renta);
                    }
                }
            }

                if(this.total_debe>this.total_haber){
                this.diferencia_debe=this.total_haber-this.total_debe;
                console.log("Diferebcia en el debe:"+this.diferencia_debe);
                }
                if(this.total_debe<this.total_haber){
                    this.diferencia_haber=this.total_debe-this.total_haber;
                    console.log("Diferebcia en el haber:"+this.diferencia_haber);
                }

                var diferencia=this.total_debe-this.total_haber;
                if(this.productos_asiento.length>0){
                    if(diferencia!==0){
                        var total_dif=parseFloat(this.productos_asiento[0].haber)+parseFloat(diferencia);
                        this.productos_asiento[0].haber=total_dif;
                        console.log("Producto1: "+total_dif);
                    }
                }



        },
        sumar_iguales(){
            var array={};
            var hash = {};
            var hash2 = {};
            if(this.productos_asiento.length>0){
                this.productos_asiento = this.productos_asiento.reduce((acumulador, valorActual) => {
                    if(valorActual.sector==="producto" && valorActual.iva==="doce"){
                        const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_iva_12 === valorActual.id_plan_cuentas_iva_12 );
                        if (elementoYaExiste) {
                            return acumulador.map((elemento) => {
                            if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_iva_12 === valorActual.id_plan_cuentas_iva_12) {
                                return {
                                ...elemento,
                                haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                                }
                            }

                            return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    }else{
                        if(valorActual.sector==="producto" && valorActual.iva==="cero"){
                            const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_iva_0 === valorActual.id_plan_cuentas_iva_0 );
                            if (elementoYaExiste) {
                                return acumulador.map((elemento) => {
                                if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_iva_0 === valorActual.id_plan_cuentas_iva_0) {
                                    return {
                                    ...elemento,
                                    haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                                    }
                                }

                                return elemento;
                                });
                            }

                            return [...acumulador, valorActual];
                        }else{
                            const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas_servicio === valorActual.id_plan_cuentas_servicio );
                            if (elementoYaExiste) {
                                return acumulador.map((elemento) => {
                                if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas_servicio === valorActual.id_plan_cuentas_servicio) {
                                    return {
                                    ...elemento,
                                    haber: parseFloat(elemento.haber) + parseFloat(valorActual.haber)
                                    }
                                }

                                return elemento;
                                });
                            }

                            return [...acumulador, valorActual];
                        }
                    }
                }, []);
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
            if(this.ice.length>0){
                this.ice = this.ice.reduce((acumulador, valorActual) => {
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
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento = this.iva_asiento.reduce((acumulador, valorActual) => {
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
            }
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc = this.pagos_sin_plc.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc = this.pagos_con_plc.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo = this.pagos_anticipo.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.retencion_iva.length>0){
                this.retencion_iva = this.retencion_iva.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }
            if(this.retencion_renta.length>0){
                this.retencion_renta = this.retencion_renta.reduce((acumulador, valorActual) => {
                const elementoYaExiste = acumulador.find(elemento => elemento.id_proyecto === valorActual.id_proyecto  && elemento.id_plan_cuentas === valorActual.id_plan_cuentas );
                if (elementoYaExiste) {
                    return acumulador.map((elemento) => {
                    if (elemento.id_proyecto === valorActual.id_proyecto && elemento.id_plan_cuentas === valorActual.id_plan_cuentas) {
                        return {
                        ...elemento,
                        debe: parseFloat(elemento.debe) + parseFloat(valorActual.debe)
                        }
                    }

                    return elemento;
                    });
                }

                return [...acumulador, valorActual];
                }, []);
            }

        }
    },
    methods:{
        filtertabla() {
            var contvar = this.filterstable.contrue;
            var filt = "";
            var filt2 = "";
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
                if (this.filterstable.filt_activ == true) {
                    filt = filt + "contvar.estadof == 1";
                }
                if (this.filterstable.filt_anul == true) {
                    if (filt.length > 0) {
                        filt = filt + " || ";
                    }
                    filt = filt + "contvar.estadof == 0";
                }
                if (this.filterstable.filt_autoris == true) {
                    filt2 =
                        filt2 +
                        '(contvar.respuesta_guia == "Enviado" )';
                }
                if (this.filterstable.filt_noautoris == true) {
                    if (filt2.length > 0) {
                        filt2 = filt2 + " || ";
                    }
                    filt2 =
                        filt2 +
                        '(contvar.respuesta_guia != "Enviado" )';
                }
                contvar = contvar.filter(contvar => eval(filt));
                contvar = contvar.filter(contvar => eval(filt2));
            } else {
                this.filterstable.filt_asientos = true;
                this.filterstable.filt_noasientos = true;
                this.filterstable.filt_anul = true;
                this.filterstable.filt_activ = true;
                this.filterstable.filt_autoris = true;
                this.filterstable.filt_noautoris = true;
            }
            this.contenido = contvar;
        },
        enviarticket(datos) {
            //this.imprimir("POS Impresora");
            //this.id_factura_imp=id;
            axios
                .get("/api/imprimir/ticket/nota_venta/" + datos.id_nota_venta + "/d")
                .then(resp => {
                    window.open(
                        "/api/imprimir/ticket/nota_venta/" + datos.id_nota_venta + "/d",
                        "_top"
                    );
                })
                .catch(err => {});
        },
        listar(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                this.contenido = [];
                var url = "/api/facturas_acumulada";
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
        editar(id) {
            this.$router.push(`/facturacion/factura_acumulada/${id}/editar`);
        },
        duplicar(data) {
            localStorage.removeItem("duplicar");
            localStorage.setItem("duplicar", data.id_nota_venta);
            this.$router.push("/facturacion/factura_acumulada/duplicar");
        },
        ver(id) {
            this.$router.push(`/facturacion/factura_acumulada/${id}/ver`);
        },
        descargarpdf(datos){
            //window.open('/api/creacion_factura_venta_pdf/'+datos.id_factura+'/d', '_top');
            axios.get('/api/creacion_nota_venta_pdf/'+datos.id_nota_venta+'/v').then( resp => {
                window.open('/api/creacion_nota_venta_pdf/'+datos.id_nota_venta+'/d', '_top');
                this.$vs.notify({
                    color: "success",
                    text: "Pdf Generado exitosamente"
                });
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al descargar PDF",
                    text: err
                });
                console.log(err);
            });
        },
        eliminar(id) {
            //console.log("1", id);
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `¿Desea Eliminar este comprobante?`,
                text: `Este comprobante sera eliminar del sistema`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.aceptarborrado,
                parameters: id
            });
        },
        aceptarborrado(parameters) {
            axios.post("/api/eliminarnota_venta",{datos:parameters,id_pto:this.usuario.id_punto_emision}).then(() => {
                this.$vs.notify({
                    color: "warning",
                    title: "Comprobante Eliminado",
                    text: "El comprobante selecionado fue eliminado con exito"
                });
                this.listar(1, this.buscar);
            });
        },
        //metodos envio correo cliente
        enviocorreocliente(id){
            axios.get('/api/creacion_nota_venta_pdf/'+id+'/v').then( resp => {
                    // this.$vs.notify({
                    //     title: "Registrado con Exito",
                    //     text: "Se ha registrado la factura con exito",
                    //     color: "success"
                    // });
                    // this.enviado();
                    this.enviar_correo(id,null,null,0);
                }).catch(err=>{
                    this.$vs.notify({
                        color: "danger",
                        title: "Error al descargar PDF",
                        text: err
                    });
                    console.log(err);
                });

        },
        enviocorreootro(id){
            this.id_nota=id;
            this.popupcorreo=true;
            this.popupcorreo = true;
            this.nombrecliente = "";
            this.correocliente = "";
            this.estadocorreo = 1;
        },
        enviocorreootros(){
            if(this.validarcorreo()){
                return;
            }
            axios.get('/api/creacion_nota_venta_pdf/'+this.id_nota+'/v').then( resp => {
                    // this.$vs.notify({
                    //     title: "Registrado con Exito",
                    //     text: "Se ha registrado la factura con exito",
                    //     color: "success"
                    // });
                    // this.enviado();
                    this.enviar_correo(this.id_nota,this.chip_correo,this.nombrecliente,1);

                }).catch(err=>{
                    this.$vs.notify({
                        color: "danger",
                        title: "Error al descargar PDF",
                        text: err
                    });
                    console.log(err);
                });

        },
        enviocorreootro_guia(datos) {
            this.popupcorreo = true;
            this.nombrecliente = "";
            this.correocliente = "";
            this.estadocorreo = 2;
            this.datosg = datos;
        },

        validarcorreo(){
            this.errorenvio = 0;
            this.errornombrecliente = [];
            this.errorchip_correo = [];
            if(!this.chip_correo){
                this.errorchip_correo.push("Ingrese un correo");
                this.errorenvio = 1;
            }
            if(!this.nombrecliente){
                this.errornombrecliente.push("Ingrese un correo");
                this.errorenvio = 1;
            }
            return this.errorenvio;
        },
        enviar_correo(id,email,destinatario,tipo){
            axios.post('/api/nota_venta/enviarcorreo', {
                id_nota_venta:id,
                id_user:this.usuario.id,
                email:email,
                destinatario:destinatario,
                id_empresa:this.usuario.id_empresa
            }).then(resp=>{
                    this.$vs.notify({
                        title: "Registrado con Exito",
                        text: "Se ha registrado la factura con exito",
                        color: "success"
                    });
                    if(tipo==1){
                        this.nombrecliente="";
                        this.chip_correo=[];
                        this.id_nota="";
                        this.popupcorreo=false;
                    }
                    //this.enviado();
            }).catch(err=>{
                    this.$vs.notify({
                        title: "Error al enviar a Correo",
                        text: "Se ha producido un error al enviar al correo",
                        color: "danger"
                    });
            });
        },
        remove_chip_nombre (item) {
            this.chips.splice(this.chips.indexOf(item), 1)
        },
        remove_chip_correo (item) {
            this.chip_correo.splice(this.chip_correo.indexOf(item), 1)
        },
        //-----------------------metodos para Contabilizar Facturas
        Contabilidad(id){
            axios.get('/api/notaVentavercontabilidad/' + id+
                        "?id_empresa=" +
                        this.usuario.id_empresa).then( ({data}) => {
                        this.lista.factura = data.nota_venta;
                        this.lista.cliente = data.cliente;
                        //{{(datos.clave_acceso).substring(24,27)}}-{{(datos.clave_acceso).substring(27,30)}}-{{(datos.clave_acceso).substring(30,39)
                        var serie1=data.nota_venta.clave_acceso.substring(24,27);
                        var serie2=data.nota_venta.clave_acceso.substring(27,30);
                        var documento=data.nota_venta.clave_acceso.substring(30,39);
                        //this.lista.productos = data.productos;
                        this.lista.creditos = data.creditos;
                        this.lista.iva = data.iva;
                        this.lista.renta = data.renta;
                        this.fecha_rol=data.nota_venta.fecha_emision;
                        var fecha=moment(this.fecha_rol).format("MMMM YYYY");
                        this.razon_social=data.empresa.nombre;
                        this.ruc_empresa=data.empresa.identificacion;
                        if(data.empresa.tipo_identificacion=="Cédula de Identidad"){
                            this.tipo_identificacion="Cedula";
                        }else{
                            this.tipo_identificacion=data.empresa.tipo_identificacion;
                        }
                        if(data.nota_venta.contabilidad!==null){
                            this.codigo="NV-"+data.codigo_anterior;
                            this.contabilizado=data.nota_venta.contabilidad;
                        }else{
                            this.codigo="NV-"+data.codigo;
                            this.contabilizado=null;
                        }
                        this.concepto="Nota Venta "+serie1+"-"+serie2+"-"+documento+" Cliente: "+data.empresa.nombre;
                        this.productos_asiento=data.producto_asientos;
                        this.iva_asiento=data.doce_iva_asiento;
                        this.pagos_sin_plc=data.pagos_asientos_sin_plc;
                        this.pagos_con_plc=data.pagos_asientos_con_plc;
                        this.pagos_anticipo=data.pagos_asientos_anticipo;
                        this.creditos=data.cliente;
                        this.retencion_iva=data.iva_retencion_asiento;
                        this.retencion_renta=data.retencion_asiento;
                        this.ice=data.ice
                        this.modalAsiento=true;
                        this.id_factura=id;
                        this.id_proyecto=data.id_proyecto;
                        this.estado_asiento=data.asiento_permitido;
                    }).catch( error => {
                        console.log(error);
                    });
        },
        validacion_asiento(){
            var error=0;
            console.log(this.productos_asiento.length);
            if(this.productos_asiento.length>0){
                this.productos_asiento.forEach(el=>{
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
                    if(el.sector=="producto" && el.iva=="cero"){
                        if(el.id_plan_cuentas_iva_0==null){
                            error++;
                            console.log("producto asiento producto cero");
                        }else{
                            if(el.grupo_cuenta_0==1){
                                error++;
                                this.$vs.notify({
                                    color: "danger",
                                    title: "Solo se puede guardar cuentas contables de MOVIMIENTO",
                                });
                                console.log("producto asiento producto cero grupo");
                            }
                        }
                    }
                    if(el.sector=="producto" && el.iva=="doce"){
                        if(el.id_plan_cuentas_iva_12==null){
                            error++;

                            console.log("producto asiento producto doce");

                        }else{
                            if(el.grupo_cuenta_12==1){
                                error++;
                                this.$vs.notify({
                                    color: "danger",
                                    title: "Solo se puede guardar cuentas contables de MOVIMIENTO",
                                });
                                console.log("producto asiento producto doce grupo");
                            }
                        }
                    }
                    if(el.sector=="servicio"){
                        if(el.id_plan_cuentas_servicio==null){
                            error++;
                            console.log("producto asiento servicio");
                        }else{
                            if(el.grupo_cuenta_servicio==1){
                                error++;
                                this.$vs.notify({
                                    color: "danger",
                                    title: "Solo se puede guardar cuentas contables de MOVIMIENTO",
                                });
                                console.log("producto asiento servicio grupo");
                            }
                        }
                    }
                    if(el.id_proyecto==null){
                            error++;
                            console.log("producto asiento proyecto");
                        }
                });
            }
            if(this.iva_asiento.length>0){
                this.iva_asiento.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                        console.log("iva_asiento plan_cuentas");
                    }
                    if(el.id_proyecto==null){
                            error++;
                            console.log("iva_asiento proyecto");
                        }
                });
            }
            if(this.pagos_sin_plc.length>0){
                this.pagos_sin_plc.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                        console.log("pagos_sin_plc plan_cuentas");
                    }
                    if(el.id_proyecto==null){
                            error++;
                            console.log("pagos_sin_plc proyecto");
                        }
                });
            }
            if(this.pagos_con_plc.length>0){
                this.pagos_con_plc.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                        console.log("pagos_con_plc plan_cuentas");
                    }
                    if(el.id_proyecto==null){
                            error++;
                            console.log("pagos_con_plc proyecto");
                        }
                });
            }
            if(this.pagos_anticipo.length>0){
                this.pagos_anticipo.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                        console.log("pagos_anticipo plan_cuentas");
                    }
                    if(el.id_proyecto==null){
                            error++;
                            console.log("pagos_anticipo proyecto");
                        }
                });
            }
            if(this.creditos.length>0){
                this.creditos.forEach(el=>{
                    // if(el.id_plan_cuentas==null){
                    //     error++;
                    //     console.log("creditos plan_cuenta_prov");
                    // }
                    if(el.exist_plc_cl=='no'){
                        if(el.id_plan_cuentas==null){
                            error++;
                            console.log("creditos plan_cuenta_grupo");
                        }
                    }else{
                        if(el.id_plan_cuentas_cl==null){
                            error++;
                            console.log("creditos plan_cuenta_cliente");
                        }
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            if(this.retencion_iva.length>0){
                this.retencion_iva.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            if(this.retencion_renta.length>0){
                this.retencion_renta.forEach(el=>{
                    if(el.id_plan_cuentas==null){
                        error++;
                    }
                    if(el.id_proyecto==null){
                            error++;
                        }
                });
            }
            return error;
        },
        agregarcampoConciliacion(index,tipo){
            this.modal_conciliacion=true;
            this.indextipoarreglo = index;
            if(tipo=="anticipo"){
                this.fecha_pago=this.pagos_anticipo[index].fecha_pago;
                this.nombre_pago=this.pagos_anticipo[index].nombre_pago;
                this.nro_documento=this.pagos_anticipo[index].numero_transaccion;
            }else{
                if(tipo=="forma_pago"){
                    this.fecha_pago=this.pagos_sin_plc[index].fecha_pago;
                    this.nombre_pago=this.pagos_sin_plc[index].nombre_pago;
                    this.nro_documento=this.pagos_sin_plc[index].numero_transaccion;
                }else{
                    this.fecha_pago=this.pagos_con_plc[index].fecha_pago;
                    this.nombre_pago=this.pagos_con_plc[index].nombre_pago;
                    this.nro_documento=this.pagos_con_plc[index].numero_transaccion;
                }
            }

        },
        crearasiento(id){
            if(this.validacion_asiento()){
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento",
                });
                return;
            }
            var total=0;
            total=this.total_debe-this.total_haber;
            if(total!==0){
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento",
                });
                return;
            }
            if(this.estado_asiento=='no'){
                this.$vs.notify({
                    color: "danger",
                    title: "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha",
                });
                return;
            }
            var codigo_asiento = this.codigo.substr(3,this.codigo.length);
            var fecha_hoy=new Date();
            axios.post("/api/nota_venta/agregar/asiento",{
                cod_rol:id,
                numero:codigo_asiento,
                codigo:this.codigo,
                fecha:this.fecha_rol+" "+fecha_hoy.getHours()+":"+fecha_hoy.getMinutes()+":"+fecha_hoy.getSeconds(),
                razon_social:this.razon_social,
                tipo_identificacion:this.tipo_identificacion,
                ruc_ci:this.ruc_empresa,
                concepto:this.concepto,
                ucrea:this.usuario.id,
                id_proyecto:this.id_proyecto
            }).then(res=>{
                this.crearasientoDetalle(res.data);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Asiento No Agregado",
                    text: err
                });
            });

        },
        crearasientoDetalle(id){
            axios.post("/api/nota_venta/agregar/asiento_detalle",{
                proyecto:this.nombre_proyecto,
                productos:this.productos_asiento,
                ice:this.ice,
                iva_12:this.iva_asiento,
                pagos_sin_plc:this.pagos_sin_plc,
                pagos_con_plc:this.pagos_con_plc,
                pagos_anticipo:this.pagos_anticipo,
                creditos:this.creditos,
                retencion_iva:this.retencion_iva,
                retencion_renta:this.retencion_renta,
                ucrea:this.usuario.id,
                id_asientos:id,
            }).then(res=>{
                this.$vs.notify({
                color: "success",
                title: "Asiento Agregado",
                text: "Asiento agregado con exito"
                });
                this.modalAsiento=false;
                this.estado_asiento="";
                this.listar(1, this.buscar);
            }).catch(err=>{
                this.$vs.notify({
                color: "danger",
                title: "Asiento No Agregado",
                text: err
                });
            });
        },
        //metodos guia remision
        guiaenvio(id) {
            axios.post("/api/factura/recuperar_guia/" + id).then(({ data }) => {
                var res = data[0];
                this.$vs.notify({
                    time: 8000,
                    title: "Validando Guia al SRI",
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
                    var tipo = "guia_remision_nota_venta";
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
                    title: "Guia Enviada",
                    text: "La guia se generó exitosamente",
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
        enviado(){
            this.listar(1, this.buscar);
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
            console.log(JSON.stringify(this.datosg));
            axios
                .post("/api/guia/enviarcorreo_masivo", {
                    tipo: "guia_nota_venta",
                    claveAcceso: this.datosg.clave_acceso_guia,
                    email: this.chip_correo,
                    id_empresa: this.datosg.id_empresa,
                    empresas: this.datosg,
                    fecha_autorizacion: fecha_actual,
                    valor_total: this.datosg.valor_total,
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
                "/api/creacion_guia_remision_pdf/" + datos.guia + "/d/nv",
                "_top"
            );
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
    },
    mounted(){
        this.listar(1, this.buscar);
    },

}
</script>
<style lang="scss">
    @import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
    .peque2 .vs-popup {
        width: 1080px !important;
    }
</style>
