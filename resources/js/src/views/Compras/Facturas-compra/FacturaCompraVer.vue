<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3>Factura compra</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6>Nº de Factura:</h6>
                    <vs-input class="w-full" v-model="factura.nfactura" @keydown="solonumeros"/>
                    <div v-show="error" v-if="error.error">
                        <div v-for="err in error.factura.numero_factura" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Fecha Emision:</h6>
                    <flat-pickr :config="configdateTimePicker" disabled class="w-full" v-model="factura.fecha_emision" @on-change="listarclave()" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha_emision">
                        <div v-for="err in error.factura.fecha_emision" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Fecha Validez:</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full" v-model="factura.fecha_validez" placeholder="Seleccionar"></flat-pickr>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <h6 class="mb-1"> Número de Autorizacion:</h6>
                    <vs-input class="w-full" v-model="factura.autorizacion" disabled/>
                    <div v-show="error" v-if="!factura.autorizacion">
                        <div v-for="err in error.factura.numero_autorizacion" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-1/6 w-full mb-6"
                    v-if="
                        empresa_info.xml_factura_compra === 1 ||
                            usuario.root === true
                    "
                >
                    <div class="vx-row">
                        <h6
                            class="flex flex-wrap items-center justify-between mb-1"
                        >
                            Descargar Archivos:
                        </h6>
                        <div class="vx-col sm:w-1/2 w-full ">
                            <vx-tooltip
                                color="#0758ba"
                                text="XML"
                                position="top"
                                style="display: inline-flex"
                            >
                                <vs-button
                                    color="#0758ba"
                                    type="border"
                                    icon="upload_file"
                                    @click="dowloadxmlfile"
                                ></vs-button
                            ></vx-tooltip>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full ">
                            <vx-tooltip
                                color="danger"
                                text="PDF"
                                position="top"
                                style="display: inline-flex"
                            >
                                <vs-button
                                    color="danger"
                                    type="border"
                                    icon="picture_as_pdf"
                                    @click="dowloadpdffile"
                                ></vs-button
                            ></vx-tooltip>
                        </div>
                    </div>
                </div>
                <!--<div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Proyectos:</h6>
                    <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="factura.proyectos">
                        <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                    </vs-select>
                </div>-->
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <h6 class="mb-1">Tipo Sustento:</h6>
                    <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="factura.tipo_sustento" disabled>
                        <vs-select-item v-for="(tr,index) in sustentos" :key="index" :value="tr.id_sustento" :text="tr.descrip_sustento"/>
                    </vs-select>
                    <div v-show="error" v-if="!factura.tipo_sustento">
                        <div
                            v-for="err in error.factura.tipo_sustento"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-6">
                    <h6 class="mb-1">Destino del Pago:</h6>
                    <vs-select placeholder="buscar" autocomplete class="selectExample w-full" disabled v-model="factura.destino_pago">
                        <vs-select-item value="Pago a residentes" text="Pago a residentes" />
                        <vs-select-item value="Pago a no residentes" text="Pago a no residentes" />
                    </vs-select>
                    <div v-show="error" v-if="!factura.destino_pago">
                        <div
                            v-for="err in error.factura.destino_pago"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-6">
                    <h6 class="mb-1">Tipo Comprobante:</h6>
                    <vs-select
                        disabled
                        placeholder="buscar"
                        autocomplete
                        class="selectExample w-full"
                        v-model="factura.tipo_comprobante"
                    >
                        <vs-select-item
                            v-for="(tr, index) in tipo_comprobantes"
                            :key="index"
                            :value="tr.id_tipcomprobante"
                            :text="tr.descrip_tipcomprob"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!factura.tipo_comprobante">
                        <div
                            v-for="err in error.factura.tipo_comprobante"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Gastos Importacion:</h6>
                    <ul class="demo-alignment">
                      <li style="margin: 13px 1.5rem;">
                        <vs-radio disabled v-model="factura.gastos" vs-value="1g">Si</vs-radio>
                      </li>
                      <li style="margin: 13px 1.5rem;">
                        <vs-radio disabled v-model="factura.gastos" vs-value="0g">No</vs-radio>
                      </li>
                    </ul>
                </div>
                <div class="vx-col sm:w-2/5 w-full mb-6" v-if="parseInt(factura.gastos)==1">
                    <h6 class="mb-1">Nro Importacion:</h6>
                    <vs-select disabled class="selectExample w-full" placeholder="Seleccione una importacion" v-model="factura.importacion">
                        <vs-select-item :key="index" :value="item.id_importacion" :text="item.cod_importacion" v-for="(item, index) in imports"/>
                    </vs-select>
                </div>
                <div class="vx-col sm:w-2/5 w-full mb-6 ml-auto">
                    <h6 class="mb-1">Orden de compra:</h6>
                    <vs-input disabled class="w-full" v-model="factura.orden_compra"/>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6 mr-auto">
                    <h6 class="mb-1">Documento Tributario:</h6>
                    <ul class="demo-alignment">
                      <li style="margin: 13px 1.5rem;">
                        <vs-radio disabled v-model="factura.docutributario" vs-value="1">Si</vs-radio>
                      </li>
                      <li style="margin: 13px 1.5rem;">
                        <vs-radio disabled v-model="factura.docutributario" vs-value="0">No</vs-radio>
                      </li>
                    </ul>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Proveedor</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full mb-6 relative" v-if="cliente.tipo">
                    <div class="vx-row">
                        <a class="flex items-center buscar_otro" @click="cliente.tipo=false"> Agregar otro Proveedor </a>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Nombre:" disabled v-bind:value="cliente.nombre" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Teléfono:" disabled v-bind:value="cliente.telefono" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Email:" disabled v-bind:value="cliente.email" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Tipo de Identificación:" disabled v-bind:value="cliente.tipo_identificacion" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Identificación:" disabled v-bind:value="cliente.identificacion" />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input class="w-full" label="Dirección:" disabled v-bind:value="cliente.direccion" />
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative" v-else>
                    <vs-input disabled class="w-full busqueda_cliente" placeholder="Escoge un cliente Para agregar un Comprobante" v-model="cliente.busqueda" @keyup="listar_cliente(cliente.busqueda)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista">
                        <ul class="ul_busqueda_lista" v-if="cliente.clientes.length">
                            <li v-for="(tr,index) in cliente.clientes" :key="index" @click="seleccionar_cliente(tr)"> {{ tr.nombre_proveedor }} </li>
                        </ul>
                    </div>
                    <div v-show="error" v-if="!cliente.tipo">
                        <div v-for="err in error.cliente.tipo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full relative" v-if="producto.tipo">
                    <vs-table hoverFlat :data="producto.lista_productos" style="font-size: 12px;">
                        <template slot="thead">
                            <vs-th>CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th>CANTIDAD</vs-th>
                            <vs-th>PRECIO</vs-th>
                            <vs-th>DESCUENTO</vs-th>
                            <vs-th>BODEGA</vs-th>
                            <vs-th>SUBTOTAL</vs-th>
                            <vs-th class="text-center">IVA</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td>
                                    <vx-tooltip v-if="tr.nomb" :text="tr.nomb" position="top" style="display: inline-flex">{{ tr.nombre }}</vx-tooltip>
                                    <vx-tooltip v-else :text="tr.nombre" position="center" style="display: inline-flex">{{ tr.nombre }}</vx-tooltip>
                                </vs-td>
                                    
                                <vs-td>
                                    <vs-select disabled class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="tr.proyecto">
                                        <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div v-for="err in tr.errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <!-- <vs-input class="w-full derecha" :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" v-model="tr.cantidad"/> -->
                                    <vs-input  class="w-full derecha" disabled  v-model="tr.cantidad"/>
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <!-- <vs-input class="w-full derecha" :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" placeholder="$0.00" v-model="tr.precio"/> -->
                                    <vs-input class="w-full derecha" disabled v-model="tr.precio"/>
                                    <div v-show="error" v-if="!tr.precio">
                                        <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:180px!important;">
                                    <vx-input-group>
                                        <!-- <vs-input class="w-full derecha" :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" placeholder="$0.00" v-model="tr.descuento"/> -->
                                        <vs-input class="w-full derecha" disabled placeholder="$0.00" v-model="tr.descuento"/>
                                        <template slot="append">
                                            <div class="append-text btn-addon">
                                                <!-- <button class="botonstl" :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" :class="{'elejido':tr.p_descuento==1}" @click="tr.p_descuento=1">
                                                    $
                                                </button> -->
                                                <button class="botonstl" disabled :class="{'elejido':tr.p_descuento==1}" @click="tr.p_descuento=1">
                                                    $
                                                </button>
                                                <!-- <button class="botonstl" :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" :class="{'elejido':tr.p_descuento==0}" @click="tr.p_descuento=0">
                                                    %
                                                </button> -->
                                                <button class="botonstl" disabled :class="{'elejido':tr.p_descuento==0}" @click="tr.p_descuento=0">
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td style="width:175px!important;" v-if="tr.id_producto_bodega">
                                    {{tr.nombrebodega}}
                                </vs-td>
                                <vs-td style="width:175px!important;" v-else-if="tr.sector == 2">
                                    SERVICIO
                                </vs-td>
                                <vs-td style="width:175px!important;" v-else>
                                    <vs-select placeholder="buscar" autocomplete class="selectExample w-full" v-model="tr.id_bodega">
                                        <vs-select-item v-for="(tr,index) in listarbodegas" :key="index" :value="tr.id_bodega" :text="tr.nombre"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.id_bodega">
                                        <div v-for="err in tr.errorid_bodega" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - tr.descuento).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - ((tr.cantidad * tr.precio * tr.descuento)/100)).toFixed(2)}}
                                    </template>
                                </vs-td>
                                <vs-td>
                                    <vs-switch v-if="tr.iva2==1" :disabled="true" v-model="tr.siiva"/>
                                    <!-- <vs-switch :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" v-else v-model="tr.siiva" @click="cambiarivas(index)"/> -->
                                    <vs-switch :disabled="true" v-else v-model="tr.siiva" @click="cambiarivas(index)"/>
                                </vs-td>
                                <!-- <feather-icon icon="TrashIcon" disabled svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" style="vertical-align: middle;display: table-cell;" class="eliminar_producto_icono" @click="eliminar_producto(index)"/> -->
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative" v-if="factura.respuesta != 'Enviado' && factura.cuentas<1">
                    <vs-input class="w-full busqueda_cliente focuspr" placeholder="Agrega productos a esta factura" v-model="producto.busqueda" disabled @keyup="listar_productos(producto.busqueda)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_producto_ls" style="display:none;">
                        <div v-if="preloader.productos">
                            <ul class="ul_busqueda_lista">
                                <li v-for="(tr,index) in producto.productos" :key="index" @click="seleccionar_productos(tr)"> <span style="font-weight: bold;">{{ tr.nombre }}</span> <span v-if="tr.nombrebodega">- <span style="font-size: 12px;">Bodega: {{tr.nombrebodega}}</span></span> <span v-if="!tr.nombrebodega && tr.sector==1">- <span style="font-size: 12px;">Producto sin Bodega</span></span> </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="vx-col w-full">
                    <div class="vx-row" v-if="producto.tipo">
                        <div class="vx-col sm:w-1/2 w-full">
                            <h6>Observaciones:</h6>
                            <vs-textarea disabled class="w-full"  v-model="factura.observacion"  rows="5"/>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>SUBTOTAL FINAL <span>$ {{ formulas.subtotal }}</span></div>
                                <div v-if="formulas.subtotal12>0">SUBTOTAL IVA 12% <span>$ {{ formulas.subtotal12 }}</span></div>
                                <div v-if="formulas.valor12>0">Valor IVA 12% <span>$ {{ formulas.valor12 }}</span></div>
                                <div v-if="formulas.subtotal0>0">SUBTOTAL IVA 0% <span>$ {{ formulas.subtotal0 }}</span></div>
                                <div v-if="formulas.no_impuesto>0">NO OBJETO DE IMPUESTO <span>$ {{ formulas.no_impuesto }}</span></div>
                                <div v-if="formulas.exento>0">EXENTO DE IVA <span>$ {{ formulas.exento }}</span></div>
                                <div>TOTAL DESCUENTO <span>$ {{ formulas.descuento }}</span></div>
                                <div>PROPINA
                                    <span>
                                        <vx-input-group>
                                            <vs-input class="w-full" :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" placeholder="$0.00" v-model="propinapr"/>
                                            <template slot="append">
                                                <div class="append-text btn-addon">
                                                    <button :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" class="botonstl" :class="{'botonstl elejido':pp_descuento == 1,botonstl:pp_descuento != 1 }" @click="pp_descuento = 1">
                                                        $
                                                    </button>
                                                    <button :disabled="factura.respuesta == 'Enviado' || factura.cuentas>=1" class="botonstl" :class="{ 'botonstl elejido': pp_descuento == 0, botonstl: pp_descuento != 0 }" @click="pp_descuento = 0">
                                                        %
                                                    </button>
                                                </div>
                                            </template>
                                        </vx-input-group>
                                    </span>
                                </div>
                                <div>VALOR TOTAL <span>$ {{ formulas.total }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Total Facturas</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALDO TOTAL</label>
                    <h1>$ {{ formulas.total }}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALDO PENDIENTE</label>
                    <h1>$ {{ total_pendiente }}</h1>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                    <label class="vs-input--label">SALGO PAGADO</label>
                    <h1>$ {{ total_pagado }}</h1>
                </div>
            </div>
            <vs-divider position="left" class="flexy">
                <h3>Créditos</h3>
                <vs-switch disabled vs-icon-on="check" color="success" v-model="creditos.estado" class="ml-2" @click="cambioscreditos()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-if="creditos.estado">
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                        <label class="vs-input--label">Periodo de pago</label>
                        <vs-select disabled placeholder="Selecciona el periodo de pago" autocomplete class="selectExample w-full" v-model="creditos.periodo">
                            <vs-select-item value text="Slecciona el periodo" />
                            <vs-select-item value="Dias" text="Dias" />
                            <vs-select-item value="Semanas" text="Semanas" />
                            <vs-select-item value="Meses" text="Meses" />
                            <vs-select-item value="Años" text="Años" />
                        </vs-select>
                        <div v-show="error" v-if="!creditos.periodo">
                            <div v-for="err in error.creditos.periodo" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input disabled class="w-full text-center" label="Tiempos Pago" v-model="creditos.tiempo"/>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Plazos de pago</label>
                        <vs-select disabled placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="creditos.plazos">
                            <vs-select-item v-for="(v, index) in 24" :key="index" :value="v" :text="v + ' Periodos'"/>
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input disabled class="w-full text-center" label="Monto de pago" v-model="creditos.monto"/>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Pago por letra</label>
                        <div class="mt-2">$ {{pagoletra}}</div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy">
                <h3>Retenciones</h3>
                <vs-switch disabled vs-icon-on="check"  color="success" class="ml-2" v-model="retenciones.estado" @click="cambiosretenciones()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-show="retenciones.estado">
                    <div class="w-full">
                        <p style="text-align: center;font-weight: bold;font-size: 18px;margin-bottom: 8px;" v-if="factura.clave_acceso">Retención N° {{(factura.clave_acceso).substring(24,27)}}-{{(factura.clave_acceso).substring(27,30)}}-{{(factura.clave_acceso).substring(30,39)}}</p>
                        <p style="text-align: center;font-weight: bold;font-size: 18px;margin-bottom: 8px;" v-else>No existe retencion</p>
                        <div class="vx-row hovertrash" v-for="(tr, index) in valorretenciones" :key="index">
                            <div class="w-2/3 ml-auto mr-auto">
                                <div class="vx-row">
                                    <div class="vx-col md:w-2/3 text-center">
                                        <label class="vs-input--label">Valores por IVA</label>
                                        <vs-select disabled @change="recuperacion=true, agregarretencioniva(index)" placeholder="Selecciona la retención" autocomplete class="selectExample w-full" v-model="tr.iva">
                                            <vs-select-item v-for="(tr, index) in listretenciones" :key="index" :value="tr" :text="tr.descrip_retencion" v-if="tr.tipo_retencion =='Retencion IVA Compras'"/>
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input label="Base" class="w-full derecha" disabled v-model="tr.baseiva" @click="recuperacion=true" @keyup="agregarretencionivavalor(index, tr.baseiva)"/>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input label="Porcentaje" class="w-full derecha" disabled v-model="tr.porcentajeiva"/>
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <vs-input label="Val. Ret." class="w-full derecha" disabled v-model="tr.cantidadiva"/>
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div class="vx-col md:w-2/3 w-full mb-2 mr-auto text-center">
                                        <label class="vs-input--label">Valores por RENTA</label>
                                        <vs-select disabled @change="recuperacion=true, agregarretencionrenta(index)" placeholder="Selecciona la retención" autocomplete class="selectExample w-full" v-model="tr.renta">
                                            <vs-select-item v-for="(tr, index) in listretenciones" :key="index" :value="tr" :text="tr.descrip_retencion" v-if="tr.tipo_retencion == 'Retencion Fuente Compras' "/>
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input  label="Base" class="w-full derecha" disabled v-model="tr.baserenta" @click="recuperacion=true" @keyup="agregarretencionrentavalor(index, tr.baserenta)"/>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input label="Porcentaje" class="w-full derecha" disabled v-model="tr.porcentajerenta"/>
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <vs-input label="Val. Ret." class="w-full derecha" disabled v-model="tr.cantidadrenta"/>
                                    </div>
                                    <vs-divider position="left"></vs-divider>
                                </div>
                            </div>
                            <feather-icon v-if="factura.respuesta != 'Enviado' && factura.cuentas<1" disabled icon="TrashIcon" style="position: absolute !important;right: 125px;margin-top: 80px;display: none;" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="eliminararrayretencion(index)"/>
                            <feather-icon v-if="factura.respuesta != 'Enviado' && factura.cuentas<1" disabled @click="addretenciones()" icon="PlusIcon" style="position: absolute !important;right: 125px;margin-top: 55px;display: none;" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher"/>
                            
                        </div>
                        <div class="vx-col sm:w-full w-full mb-2 text-center">
                              <h6 class="mt-4">Clave de acceso:</h6>
                              <p>{{ factura.clave_acceso }}</p>
                            </div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy">
                <h3>Pagos</h3>
                <vs-switch disabled vs-icon-on="check" color="success" class="ml-2" v-model="pagos.estado" @click="cambiospagosrec()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-show="pagos.estado">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr,index) in pagos.datos" :key="index">
                            <div class="vx-col w-full mb-2 text-center ml-auto sm:w-1/6" :class="{'ml-auto': tr.metodo_pago=='Anticipo'}">
                                <label class="vs-input--label">Método de pago</label>
                                <vs-select disabled placeholder="Selecciona el método de pago" autocomplete class="selectExample w-full" v-model="tr.metodo_pago">
                                    <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
                                </vs-select>
                                <div v-show="error.error" v-if="!tr.metodo_pago">
                                    <div v-for="err in tr.errormetodo" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center" v-if="tr.metodo_pago!='Anticipo'">
                                <vs-select disabled class="selectExample w-full" label="Banco" vs-multiple autocomplete v-model="tr.banco_pago">
                                    <vs-select-item v-for="data in bancos" :key="data.id_banco" :value="data.id_banco" :text="data.nombre_banco" />
                                </vs-select>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-input disabled class="w-full text-center" label="Valor" v-model="tr.cantidad_pago"/>
                                <div v-show="error.error" v-if="parseFloat(tr.cantidad_pago)<=0">
                                    <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center mr-auto" v-if="tr.metodo_pago=='Anticipo'">
                                <vs-input disabled class="w-full text-center" label="Anticipo Total" :value="parseFloat(anticipoexistente) + (anticipo_creado - tr.cantidad_pago)"/>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center" v-if="tr.metodo_pago!='Anticipo'">
                                <vs-input disabled class="w-full text-center" label="Nro de transacción" v-model="tr.nro_trans"/>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2" v-if="tr.metodo_pago!='Anticipo'">
                                <label class="vs-input--label">Fecha de pago</label>
                                <flat-pickr disabled :config="configdateTimePicker" class="w-full" v-model="tr.fecha_pago" placeholder="Seleccionar"></flat-pickr>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2" v-if="tr.metodo_pago!='Anticipo'">
                                <label class="vs-input--label">Plan Cuenta</label>
                                <vx-input-group>
                                    <vs-input class="w-full" v-model="tr.cuenta"/>
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                        <vs-button disabled color="primary" @click="abrir_plan_cuentas_pagos1(index)">Buscar</vs-button>
                                        <vs-button disabled color="danger" type="flat" @click="eliminarplc(index)">X</vs-button>
                                        </div>
                                    </template>
                                </vx-input-group>
                            </div>
                            <feather-icon icon="TrashIcon" style="position: absolute!important;right: 15px;margin-top: 44px;display:none" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="eliminararraypagos(index)" />
                            <feather-icon icon="PlusIcon" style="position: absolute!important;right: 15px;margin-top: 26px;display:none" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="addpagos()" />
                        </div>
                    </div>
                </div>
            </transition>
            <div class="vx-col w-full mt-5">
                
                <vs-button color="danger" type="filled" to="/compras/factura-compra">CANCELAR</vs-button>
            </div>
            <!-- Crear Cliente -->
            <vs-popup :title="modal.titulo" :active.sync="modal.abrir" class="modal-xl">
                <div class="con-exemple-prompt">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Nombre Completo" v-model="crear_cliente.nombre"/>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Tipo de Identificación</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el tipo" v-model="crear_cliente.tipo_identificacion">
                                <vs-select-item :key="index" :value="item.value" :text="item.label" v-for="(item, index) in tipo_identificacion_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Identificación" v-model="crear_cliente.identificacion"/>
                        </div>

                        <div class="vx-col sm:w-1/5 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Grupo Cliente</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el grupo cliente" v-model="crear_cliente.grupo_cliente">
                                <vs-select-item :key="index" :value="item.id_grupo_cliente" :text="item.nombre_grupo" v-for="(item, index) in grupo_cliente_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/5 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Tipo Cliente</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el tipo cliente" v-model="crear_cliente.tipo_cliente">
                                <vs-select-item :key="index" :value="item.id_tipo_cliente" :text="item.descripcion_tipo_cliente" v-for="(item, index) in tipo_cliente_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/5 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Grupo Tributario</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el grupo tributario" v-model="crear_cliente.grupo_tributario">
                                <vs-select-item :key="index" :value="item.value" :text="item.label" v-for="(item, index) in grupo_tributario_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-2/5 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Dirección" v-model="crear_cliente.direccion"/>
                        </div>

                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Provincia</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione la provincia" v-model="crear_cliente.provincia" @change="listarcanton(crear_cliente.provincia)">
                                <vs-select-item :key="index" :value="item.id_provincia" :text="item.nombre" v-for="(item, index) in provincia_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Cantón</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el cantón" v-model="crear_cliente.canton" @change="listarparroquia(crear_cliente.canton)">
                                <vs-select-item :key="index" :value="item.id_ciudad" :text="item.nombre" v-for="(item, index) in canton_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Parroquia</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione la parroquia" v-model="crear_cliente.parroquia">
                                <vs-select-item :key="index" :value="item.id_parroquia" :text="item.nombre_parroquia" v-for="(item, index) in parroquia_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-checkbox icon-pack="feather" icon="icon-check" class="mt-6 pt-2" v-model="crear_cliente.parte_relacionada">
                                <template v-if="crear_cliente.parte_relacionada">
                                    <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
                                </template>
                                <template v-else>
                                    <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
                                </template>
                                | Parte relacionada
                            </vs-checkbox>
                        </div>

                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="E-mail" v-model="crear_cliente.e_mail"/>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Teléfono" v-model="crear_cliente.telefono"/>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Contacto" v-model="crear_cliente.contacto"/>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Vendedor</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el vendedor" v-model="crear_cliente.vendedor">
                                <vs-select-item :key="index" :value="item.id_vendedor" :text="item.nombre_vendedor" v-for="(item, index) in vendedor_menu"/>
                            </vs-select>
                        </div>

                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Estado</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el estado" v-model="crear_cliente.estado">
                                <vs-select-item :key="index" :value="item.value" :text="item.label" v-for="(item, index) in estado_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Descuento %" v-model="crear_cliente.descuento"/>
                        </div>
                        <div class="vx-col sm:w-1/2 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Cuenta Contable</label>
                            <vx-input-group>
                                <vs-input class="w-full" v-model="crear_cliente.cuenta_contable"/>
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                    <vs-button color="primary" @click="abrir_plan_cuentas()">Buscar</vs-button>
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>

                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Número Pagos" v-model="crear_cliente.numero_pagos"/>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Lista de Precios" v-model="crear_cliente.lista_precios"/>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Forma de Pago</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione forma de pago" v-model="crear_cliente.forma_pago">
                                <vs-select-item :key="index" :value="item.id_forma_pagos" :text="item.descripcion" v-for="(item, index) in forma_pago_menu"/>
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Límite de Credito" v-model="crear_cliente.limite_credito"/>
                        </div>

                        <div class="vx-col w-full relative mb-6">
                            <label class="vs-input--label">Comentario</label>
                            <vs-textarea v-model="crear_cliente.comentario" rows="3" />
                        </div>
                        <div class="vx-col w-full mb-4">
                            <vs-button color="success" type="filled" @click="guardar_cliente()">GUARDAR</vs-button>
                            <vs-button color="danger" type="filled" @click="popupActive4=false">CANCELAR</vs-button>
                        </div>
                    </div>
                </div>
            </vs-popup>
            <!-- Cuentas Contables -->
            <vs-popup :title="modalcontable.titulo" :active.sync="modalcontable.abrir" style="z-index:99999999999">
                <div class="con-exemple-prompt">
                    <vs-input class="mb-4 md:mb-0 mr-4 w-full" v-model="plan_cuenta.buscar" placeholder="buscar" @keyup="listar_cuenta_contable(plan_cuenta.buscar)"/>
                    <table class="vs-table mt-3" style="wudth:100%;">
                        <thead class="vs-table--thead">
                            <tr>
                                <th>No.Cuenta</th>
                                <th>Tipo Cuenta</th>
                            </tr>
                        </thead>
                        <tbody v-if="modalcontable.tipo!=3">
                            <tr v-for="(tr,index) in plan_cuenta.lista" :key="index" @click="escoger_plan_cuenta(tr)" class="tablavista">
                                <td>{{ tr.codcta }}</td>
                                <td>{{ tr.nomcta }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr v-for="(tr,index) in plan_cuenta.lista" :key="index" @click="escoger_plan_cuenta1(tr)" class="tablavista">
                                <td>{{ tr.codcta }}</td>
                                <td>{{ tr.nomcta }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </vs-popup>
        </vx-card>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
import $ from "jquery";
import { log } from "util";
const axios = require("axios");
const {rutasEmpresa:{DATA_EMPRESA}} = require("../../../../../../config-routes/config");
import script_comprobantes from '../../../../factura.js';

export default {
    components: {
        flatPickr,
        "v-select": vSelect
    },
    data() {
        return {
            configdateTimePicker: {
                locale: SpanishLocale
            },
            modal:{
                abrir:false,
                titulo:'',
                tipo:0
            },
            modalcontable:{
                abrir:false,
                titulo:'',
                tipo:0
            },
            factura:{
                nfactura:'',
                fecha_emision:moment().format('YYYY-MM-DD'),
                fecha_validez:moment().format('YYYY-MM-DD'),
                autorizacion:'',
                proyectos:null,
                tipo_sustento:null,
                destino_pago:null,
                gastos:1,
                importacion:null,
                orden_compra:'',
                docutributario:1,
                clave_acceso:"Generando la clave de acceso",
                observacion:'',
                id_retfactcompra:null,
                respuesta: '',
                cuentas: 0,
                tipo_comprobante:null
            },
            cliente:{
                tipo:false,
                busqueda:'',
                clientes:[],
                id_cliente:'',
                nombre:'',
                telefono:'',
                email:'',
                tipo_identificacion:'',
                identificacion:'',
                direccion:'',
            },
            disbled_editar:false,
            crear_cliente:{
                codigo:'',
                nombre:'',
                tipo_identificacion:'',
                identificacion:'',
                grupo_cliente:'',
                tipo_cliente:'',
                grupo_tributario:'',
                direccion:'',
                provincia:null,
                canton:null,
                parroquia:null,
                parte_relacionada:'',
                e_mail:'',
                telefono:'',
                contacto:'',
                vendedor:null,
                estado:null,
                descuento:'',
                cuenta_contable:'',
                id_cuenta_contable:null,
                numero_pagos:'',
                lista_precios:'',
                forma_pago:null,
                limite_credito:'',
                comentario:'',
            },
            producto:{
                tipo:false,
                busqueda:'',
                productos:[],
                lista_productos:[],
                id_producto:null,
                codigo:'',
                nombre:'',
                cantidad:'',
                precio:0,
                descuento:0,
                subtotal:0,
            },
            tipo_identificacion_menu: [
                { label: "Cédula Identidad", value: 1 },
                { label: "Ruc", value: 2 },
                { label: "Pasaporte", value: 3 },
                { label: "Consumidor Final", value: 4 }
            ],
            grupo_cliente_menu: [],
            tipo_cliente_menu: [],
            grupo_tributario_menu: [
                { label: "Persona Natural", value: "Persona Natural" },
                { label: "Persona Jurídica", value: "Persona Jurídica" }
            ],
            provincia_menu: [],
            canton_menu: [],
            parroquia_menu: [],
            vendedor_menu: [],
            estado_menu: [
                { label: "Activo", value: "Activo" },
                { label: "Inactivo", value: "Inactivo" }
            ],
            forma_pago_menu: [],
            cuenta_contable_menu:[],
            plan_cuenta: {
                buscar:'',
                lista:[]
            },
            proyectos_menu:[],
            empresa:[],
            formapagos:[],
            retenciones:{
                estado:false,
                listaretenciones:[],
                data:[
                    {
                        iva:{
                            lista:null,
                            valor:0,
                            porcentaje:0,
                            cantidad:0,
                        },
                        renta:{
                            lista:null,
                            base:0,
                            porcentaje:0,
                            cantidad:0,
                        }
                    }
                ]
            },
            valorretenciones: [
                {
                    baseiva: null,
                    iva: null,
                    porcentajeiva: null,
                    cantidadiva: null,
                    renta: null,
                    baserenta: null,
                    porcentajerenta: null,
                    cantidadrenta: null
                }
            ],
            listretenciones: [],
            creditos:{
                estado:false,
                periodo:'',
                tiempo:1,
                plazos:3,
                monto:0,
                pago:0,
            },
            pagos:{
                estado:false,
                datos:[
                    {
                        metodo_pago:'',
                        banco_pago:'',
                        cantidad_pago:0,
                        nro_trans:'',
                        fecha_pago:'',
                        cuenta:'',
                        plan_cuenta:null,
                    }
                ]
            },
            pago_hecho:"",
            bancos: [],
            totalef:0,
            propinapr:null,
            pp_descuento:1,
            sustentos:[],
            anticipo_creado: 0,
            anticipoexistente:0,
            //tipo comprobantes
            tipo_comprobantes:[],
            formapagos:[],
            imports:[],
            error: {
                error: 0,
                factura: {
                    fecha_emision: [],
                    numero_factura: [],
                    numero_autorizacion: [],
                    tipo_sustento:[],
                    destino_pago:[],
                    tipo_comprobante:[]
                },
                cliente: {
                    tipo: []
                },
                producto: {
                    busqueda: []
                },
                creditos: {
                    periodo: [],
                    tiempo: [],
                    plazos: [],
                    monto: []
                }
            },
            preloader:{
                cliente:false,
                productos:false,
            },
            recuperacion:false,
            listarbodegas: [],
            empresa_info: {}
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        formulas(){
            var subtotal = 0;
            var subtotal12 = 0;
            var valor12 = 0;
            var subtotal0 = 0;
            var valor0 = 0;
            var no_impuesto = 0;
            var exento = 0;
            var descuento = 0;
            var total = 0;
            var propina = 0;

            this.producto.lista_productos.forEach(el => {
                if (el.p_descuento == 1) {
                    subtotal += el.precio * el.cantidad - el.descuento;
                    if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - el.descuento;}
                    if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - el.descuento;}
                    if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - el.descuento;}
                    if (el.iva == 4) {exento += el.precio * el.cantidad - el.descuento;}
                    if(isNaN(parseFloat(el.descuento))){
                        descuento += 0;
                    }else{
                        descuento += parseFloat(el.descuento);
                    }
                } else {
                    subtotal += el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100;
                    if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100;}
                    if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100;}
                    if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100;}
                    if (el.iva == 4) {exento += el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100;}
                    if(isNaN((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100)){
                        descuento += 0;
                    }else{
                        descuento += (parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100;
                    }
                }
                valor12 = subtotal12 * 0.12;
            });
            total += subtotal + valor12;

            if(this.pp_descuento==1){
                if(parseFloat(this.propinapr)>=0){
                    propina = parseFloat(this.propinapr)
                }
            }else{
                if(parseFloat(this.propinapr)>=0){
                    propina = (parseFloat(total) * parseFloat(this.propinapr))/100;
                }
            }
            total = total - propina;
            if(this.recuperacion){
                if(this.valorretenciones.length==1){
                    this.valorretenciones[0].baserenta=parseFloat(subtotal ).toFixed(2);
                    this.valorretenciones[0].baseiva=parseFloat(valor12).toFixed(2);
                }else{
                    if(this.valorretenciones.length==2){
                        this.valorretenciones[1].baserenta=parseFloat(subtotal - this.valorretenciones[0].baserenta).toFixed(2);
                        this.valorretenciones[1].baseiva=parseFloat(valor12 - this.valorretenciones[0].baseiva).toFixed(2);
                        console.log(this.valorretenciones[1].baserenta);
                    }else{
                        if(this.valorretenciones.length==3){
                            this.valorretenciones[2].baserenta=parseFloat(subtotal - this.valorretenciones[0].baserenta - this.valorretenciones[1].baserenta).toFixed(2);
                            this.valorretenciones[2].baseiva=parseFloat(valor12 - this.valorretenciones[0].baseiva - this.valorretenciones[1].baseiva).toFixed(2);
                        }
                    }
                }
            }

            return {
                'subtotal': subtotal.toFixed(2),
                'subtotal12': subtotal12.toFixed(2),
                'valor12': valor12.toFixed(2),
                'subtotal0': subtotal0.toFixed(2),
                'valor0': valor0.toFixed(2),
                'no_impuesto': no_impuesto.toFixed(2),
                'exento': exento.toFixed(2),
                'descuento': descuento.toFixed(2),
                'total': total.toFixed(2)
            };
        },
        pagoletra(){
            var res = 0;
            var res = this.creditos.monto / this.creditos.plazos;
            return res.toFixed(2);
        },
        total_pendiente(){
            var total = 0;
            var retencion = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;

            this.valorretenciones.forEach(el => {
                iva = 0;
                renta = 0;
                if(parseFloat(el.cantidadiva)>=0 && el.iva!=null){
                    iva = parseFloat(el.cantidadiva)
                }
                if(parseFloat(el.cantidadrenta)>=0  && el.renta!=null){
                    renta = parseFloat(el.cantidadrenta)
                }
                retencion +=  iva + renta;
            });
            this.pagos.datos.forEach(el => {
                if(parseFloat(el.cantidad_pago)>=0 ){
                    paga = parseFloat(el.cantidad_pago)
                }
                pagas +=  paga;
            });
            if(this.creditos.monto<=0){
                creditos = 0;
            }else{
                creditos = this.creditos.monto;
            }

            total = parseFloat(this.formulas.total) - parseFloat(retencion) - parseFloat(creditos) - parseFloat(pagas);
            if(parseFloat(total)<0.01 && parseFloat(total)>=-0.02){
                total = 0;
            }
            return total.toFixed(2);
        },
        total_pagado(){
            var total = 0;
            var retencion = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;
            this.valorretenciones.forEach(el => {
                iva = 0;
                renta = 0;
                if(parseFloat(el.cantidadiva)>=0 ){
                    iva = parseFloat(el.cantidadiva)
                }
                if(parseFloat(el.cantidadrenta)>=0 ){
                    renta = parseFloat(el.cantidadrenta)
                }
                retencion +=  iva + renta;
            });
            this.pagos.datos.forEach(el => {
                if(parseFloat(el.cantidad_pago)>=0 ){
                    paga = parseFloat(el.cantidad_pago)
                }
                pagas +=  paga;
            });
            if(this.creditos.estado){
                if(this.creditos.monto<=0){
                    creditos = 0;
                }else{
                    creditos = parseFloat(this.creditos.monto);
                }
            }
            total = parseFloat(retencion) + parseFloat(creditos) + parseFloat(pagas);
            if(parseFloat(total)<0.01){
                total = 0;
            }
            return total.toFixed(2);
        }
    },
    methods: {
        listar_cliente(buscar){
            axios.get('/api/factura_compra/listar_proveedor?buscar=' + buscar + "&usuario=" + this.usuario.id_empresa).then( ({data}) => {
                $(".busqueda_lista_proveedor").show();
                this.cliente.clientes = data;
            }).catch( error => {
                console.log(error);
            });
        },
        listar_productos(buscar){
            this.preloader.productos=false;
            $(".busqueda_producto_ls").show();
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                axios.post('/api/notacredito/listar_productos1',{
                        buscar: buscar,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                        cliente: this.cliente.id_cliente,
                }).then( ({data}) => {
                    this.preloader.productos=true;
                    if(data.length>=1){
                        if(data[0].codigo_barras == buscar && data[0].codigo_barras.length>=1){
                            this.seleccionar_productos(data[0]);
                            this.producto.busqueda = '';
                            return;
                        }else{
                            this.producto.productos = data;
                            return;
                        }
                    }else{
                        this.producto.productos = [];
                        return;
                    }
                }).catch( error => {
                    console.log(error);
                    this.preloader.productos=true;
                });
            }, 800);
        },
        listar_creacion_cliente(){
            axios.get('/api/notacredito/listar_creacion_cliente/'+this.usuario.id_empresa).then( ({data}) => {
                this.grupo_cliente_menu = data.grupo_cliente;
                this.tipo_cliente_menu = data.tipo_cliente;
                this.provincia_menu = data.provincia;
                this.vendedor_menu = data.vendedor;
                this.forma_pago_menu = data.forma_pago;
                this.proyectos_menu = data.proyectos;
                this.empresa = data.empresa;
                this.factura.ambiente = data.empresa.ambiente;
            }).catch( error => {
                console.log(error);
            });
        },
        listarcanton(id){
            axios.get('/api/notacredito/listar_canton/'+id).then( ({data}) => {
                this.canton_menu = data;
            }).catch( error => {
                console.log(error);
            });
        },
        listarparroquia(id){
            axios.get('/api/notacredito/listar_parroquia/'+id).then( ({data}) => {
                this.parroquia_menu = data;
            }).catch( error => {
                console.log(error);
            });
        },
        listar_cuenta_contable(buscar){
            axios.get('/api/notacredito/listar_cuenta_contable?empresa='+this.usuario.id_empresa+'&buscar='+buscar).then( ({data}) => {
                this.plan_cuenta.lista = data;
            }).catch( error => {
                console.log(error);
            });
        },
        seleccionar_cliente(tr){
            this.cliente.clientes = [];
            this.cliente.busqueda = '';
            this.cliente.tipo = true;
            this.cliente.id_cliente = tr.id_proveedor;
            this.cliente.nombre = tr.nombre_proveedor;
            this.cliente.telefono = tr.contacto;
            this.cliente.email = '';
            this.cliente.tipo_identificacion = tr.tipo_identificacion;
            this.cliente.identificacion = tr.identif_proveedor;
            this.cliente.direccion = tr.direccion_prov;
            this.anticipover(tr.id_proveedor);
        },
        anticipover(id){
            axios.get("/api/anticipototalcompra?id="+id).then( ({data}) => {
                if(data){
                    this.anticipoexistente = parseFloat(data).toFixed(2);
                }else{
                    this.anticipoexistente = parseFloat(0).toFixed(2);
                }
                
            }).catch( err => {
                console.log(err);
            });
        },
        seleccionar_productos(tr){
            this.producto.productos = [];
            this.producto.busqueda = '';
            this.producto.tipo = true;
            var subtotal =  (tr.precio - tr.descuento).toFixed(2);
            var cantidad = 1;

            if(isNaN(parseInt(tr.existencia_total))){
                tr.existencia_total='';
            }
            if(isNaN(parseFloat(tr.precio))){
               tr.precio='';
            }
            if(isNaN(parseFloat(tr.descuento))){
                tr.descuento ='';
            }
            if(tr.sector==1 && (tr.id_producto_bodega==="undefined" || tr.id_producto_bodega == null)){
                cantidad = 0;
                tr.cantidad = 0;
                tr.id_producto_bodega = null;
                tr.nombrebodega = null;
            }
            var siiva = false;
            if(tr.iva==1){
                siiva = false;
            }else{
                siiva = true;
            }

            this.producto.lista_productos.push({
                id_producto_bodega:tr.id_producto_bodega,
                nombrebodega:tr.nombrebodega,
                id_producto: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cantidad: cantidad,
                cantidadreal: tr.cantidad,
                precio: tr.precio,
                descuento: tr.descuento,
                p_descuento: 1,
                subtotal: subtotal,
                iva: tr.iva,
                ice: tr.ice,
                sector: tr.sector,
                iva2: tr.iva,
                siiva: siiva,
                id_bodega: null
            });
            console.log(this.producto.lista_productos);
            $(".focuspr").focus();
        },
        cambiarivas(index){
            if(this.producto.lista_productos[index].siiva){
                this.producto.lista_productos[index].iva = 1;
            }else{
                this.producto.lista_productos[index].iva = this.producto.lista_productos[index].iva2;
            }
        },
        escoger_plan_cuenta(tr){
           this.crear_cliente.cuenta_contable = tr.codcta;
           this.crear_cliente.id_cuenta_contable = tr.id_plan_cuentas;
           this.modalcontable.abrir = false;
        },
        escoger_plan_cuenta1(tr){
           this.pagos.datos[this.pagos.index].cuenta = tr.codcta;
           this.pagos.datos[this.pagos.index].plan_cuenta = tr.id_plan_cuentas;
           this.modalcontable.abrir = false;
        },
        abrir_modal_crear_cliente(){
            this.modal = {
                abrir: true,
                titulo: "Crear Cliente",
                tipo: 1,
            }
            this.crear_cliente = {
                codigo:'',
                nombre:'',
                tipo_identificacion:{ label: "Seleccione", value: 0 },
                identificacion:'',
                grupo_cliente:'',
                tipo_cliente:'',
                grupo_tributario:'',
                direccion:'',
                provincia:null,
                canton:null,
                parroquia:null,
                parte_relacionada:'',
                e_mail:'',
                telefono:'',
                contacto:'',
                vendedor:null,
                estado:null,
                descuento:'',
                cuenta_contable:'',
                id_cuenta_contable:null,
                numero_pagos:'',
                lista_precios:'',
                forma_pago:null,
                limite_credito:'',
                comentario:'',
            }
        },
        abrir_plan_cuentas(){
            this.modalcontable = {
                abrir: true,
                titulo: "Crear Cliente",
                tipo: 1,
            }
        },
        eliminar_producto(id){
            this.producto.lista_productos.splice(id,1);
            if(!this.producto.lista_productos.length){
               this.producto.tipo = false;
            }
        },
        verificarcliente(){
            axios.get('/api/notacredito/verificarcliente/'+this.usuario.id_empresa).then( ({data}) => {
                if(data == 'vacio'){
                    this.crear_cliente.codigo = '';
                }else{
                    this.crear_cliente.codigo = data;
                }
            }).catch( error => {
                console.log(error);
            });
        },
        guardar_cliente(){
            axios.post('/api/notacredito/guardar_cliente', {
                cliente:this.crear_cliente,
                empresa: this.usuario.id_empresa
            }).then( ({data}) => {
                this.$vs.notify({
                    time: 8000,
                    title: "Cliente guardado",
                    text: "El cliente se guardo exitosamente",
                    color: "success"
                });
                this.modal.abrir = false;
                this.cliente.busqueda = '';
            }).catch( error => {
                console.log(error);
            });
        },
        guardar_factura(){
            this.disbled_editar=true;
            if(this.validar()){this.disbled_editar=false;return;}

            if(this.total_pendiente>0){
                this.$vs.notify({
                    time: 8000,
                    title: "No se puede Guardar la factura",
                    text: "Todavía existe un saldo pendiente de $ "+this.total_pendiente,
                    color: "danger"
                });
                this.disbled_editar=false;
                return;
            }
            if(this.total_pendiente<0){
                this.$vs.notify({
                    time: 8000,
                    title: "No se puede Guardar la factura",
                    text: "No se puede guardar un saldo en negativo",
                    color: "danger"
                });
                this.disbled_editar=false;
                return;
            }
            
            if(this.pago_hecho=='si'){
                    this.$vs.notify({
                        title: "No se puede Editar el credito de la factura",
                        text: "Ya se ha hecho un pago correspondiente a esta factura",
                        color: "danger"
                    });
                    this.disbled_editar=false;
                    return;
            }
            var alerta = false;
            var valoranticipo = 0;
            this.pagos.datos.forEach(el => {
                if(el.metodo_pago=='Anticipo'){
                    alerta = true;
                    valoranticipo += el.cantidad_pago;
                }
            });
            if(alerta){
                if(parseFloat(this.anticipoexistente) + parseFloat(this.anticipo_creado) - parseFloat(valoranticipo) < 0){
                    this.$vs.notify({
                        time: 8000,
                        title: "El valor a Pagar excede el limite",
                        text: "El valor a pagar excede el anticipo maximo del cliente",
                        color: "danger"
                    });
                    this.disbled_editar=false;
                    return;
                }
            }
            this.validar_clave_retencion().then(val=>{
                this.editar_factura();
            }).catch(error=>{
                console.log(error+"Validar clave retencion");
            });

            
        },
        validar_clave_retencion(){
            return new Promise((resolve,reject) => {
                axios.put('/api/factura_compra/validar',{
                    usuario:this.usuario,
                    factura:this.factura,
                    retencion_estado: this.retenciones.estado,
                }).then(res=>{
                    console.log(res.data+" ");
                    if (res.data == "repetido") {
                        console.log(res.data+"entro validacion ");
                                var url = "/api/listarclaveretencion/" + this.usuario.id;
                                axios.get(url).then(res => {
                                    var fecha = moment(this.factura.fecha_emision).format("DDMMYYYY");
                                    var rec = res.data.recupera[0];
                                    var secuencial = this.zeroFill(res.data.secuencial, 9);
                                    var establecimiento = this.zeroFill(rec.establecimiento, 3);
                                    var punto_emision = this.zeroFill(rec.punto_emision, 3);
                                    var codigoacc = fecha+"07"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                                    var acceso = this.Modulo11(codigoacc);
                                    this.factura.clave_acceso = codigoacc + acceso;
                                    resolve(this.factura.clave_acceso);
                                }).catch(err=>{
                                    this.$vs.notify({
                                        time: 8000,
                                        text:
                                            "Error en clave retencion",
                                        color: "danger"
                                    });
                                    this.disbled_editar=false;
                                    return;

                                });

                                    
                    }else{
                        resolve(this.factura.clave_acceso);
                    }
                }).catch(err=>{
                    console.log(err);
                    reject(err);
                });
            });
            
        },
        editar_factura(){
                axios.put('/api/factura_compra/editar_factura', {
                    factura:this.factura,
                    productos: this.producto.lista_productos,
                    empresa: this.empresa,
                    usuario:this.usuario,
                    cliente: this.cliente.id_cliente,
                    subtotal: this.formulas.subtotal,
                    subtotal12: this.formulas.subtotal12,
                    valor12: this.formulas.valor12,
                    subtotal0: this.formulas.subtotal0,
                    valor0: this.formulas.valor0,
                    no_impuesto: this.formulas.no_impuesto,
                    exento: this.formulas.exento,
                    descuento: this.formulas.descuento,
                    total: this.formulas.total,
                    total_pendiente:this.total_pendiente,
                    total_pagado:this.total_pagado,
                    propinapr: this.propinapr,
                    pp_descuento: this.pp_descuento,
                    creditos: this.creditos,
                    retencion_estado: this.retenciones.estado,
                    valorretenciones:this.valorretenciones,
                    pagos:this.pagos,
                    anticipo_creado:this.anticipo_creado,
                }).then( ({data}) => {
                    if (data == "error numero") {
                            this.$vs.notify({
                                time: 8000,
                                title: "Error de registro",
                                text:
                                    "El número de factura ya existe, verifique nuevamente",
                                color: "danger"
                            });
                            this.disbled_editar=false;
                            return;
                    }
                    
                    if(data=='Enviado'){
                        this.$vs.notify({
                            time: 8000,
                            title: "Registro Actualizado",
                            text: "Este registro se actualizo exitosamente",
                            color: "success"
                        });
                        this.enviado();
                        return;
                    }
                    this.$vs.notify({
                        time: 8000,
                        title: "Registro Guardado",
                        text: "Este registro se guardo exitosamente",
                        color: "success"
                    });
                    if(this.retenciones.estado){
                            if(this.valorretenciones[0].iva!=null || this.valorretenciones[0].renta!=null){
                                this.$vs.notify({time: 8000,title: "Enviando Retencion de Compra al SRI",text:"La Retencion de Compra esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",color: "primary"});
                                var dataf = data[0];
                                this.recueidfact = data[0].id_factcompra;
                                axios.post('/api/factura/xml_compro_retenc',dataf).then(res => {
                                    var password = res.data.recupera.pass_firma;
                                    var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + res.data.recupera.firma;
                                    var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/retencioncompra/" + this.factura.autorizacion +".xml";
                                    var tipo = "retencion_compra";
                                    var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/retencioncompra/";
                                    var fecha_actual = moment(dataf.fech_validez).format('LL');
                                    this.crearfacturacion(firma, password, factura, tipo, this.usuario, this.recueidfact, carpeta, fecha_actual, dataf.total_factura, dataf.logo, dataf.nombre_empresa);
                                    this.enviado();
                                });
                            }else{
                                this.enviado();
                            }
                    }else{
                        this.enviado();
                    }
                }).catch( error => {
                    console.log(error);
                });
        },
        pago_efectuado(){
            this.pago_hecho="";
            axios.get("/api/factura_compra/ctaxpagar/verficacion",{
                params:{
                    id:this.$route.params.id,
                    monto:this.creditos.monto
                }
            }).then(resp=>{
                this.pago_hecho=resp.data;
                this.guardar_factura();
            }).catch(err=>{
                this.guardar_factura();
            });
        },
        listarformapagos(){
            axios.get("/api/facturaformapagos/" + this.usuario.id_empresa).then( res => {
                this.formapagos = res.data;
                this.formapagos.push({id_forma_pagos:"Anticipo",descripcion:"ANTICIPO"});
            }).catch( err => {
                console.log(err);
            });
        },
        cambioscreditos(){
            this.creditos.monto = this.total_pendiente;
            if(this.creditos.estado){
                this.creditos.monto = (0).toFixed(2);
            }
        },
        cambiosretenciones(){
            if(this.retenciones.estado){
                this.valorretenciones = [
                    {
                        iva: null,
                        porcentajeiva: null,
                        cantidadiva: null,
                        renta: null,
                        baserenta: null,
                        porcentajerenta: null,
                        cantidadrenta: null
                    }
                ];
            }
            this.valorretenciones.forEach(el => {
                el.baserenta = parseFloat(this.formulas.subtotal / this.valorretenciones.length).toFixed(2);
            });
        },
        listarretenciones() {
            var url = "/api/listarretenciones?empresa="+this.usuario.id_empresa;
            axios.get(url).then(({data}) => {
                this.listretenciones = data;
            });
        },

        //retenciones iva y renta
        valorescalculodiv(){
            if(this.recuperacion){
                if(this.monto_credito){
                    var menor = this.monto_credito;
                }else{
                    var menor = 0;
                }
                if(this.formulas.total>0){
                    for(var i=0; i<this.valorretenciones.length; i++){
                        this.valorretenciones[i].baserenta = (this.formulas.subtotal/this.valorretenciones.length).toFixed(2);
                        this.valorretenciones[i].baseiva = (this.formulas.valor12/this.valorretenciones.length).toFixed(2);
                        if(this.valorretenciones[i].iva != null){
                            this.valorretenciones[i].porcentajeiva = this.valorretenciones[i].iva.porcen_retencion + "%";
                            this.valorretenciones[i].cantidadiva = ((this.valorretenciones[i].baseiva * this.valorretenciones[i].iva.porcen_retencion) / 100).toFixed(2);
                        }
                        if(this.valorretenciones[i].renta != null){
                            this.valorretenciones[i].porcentajerenta = this.valorretenciones[i].renta.porcen_retencion + "%";
                            this.valorretenciones[i].cantidadrenta = ((this.valorretenciones[i].baserenta * this.valorretenciones[i].renta.porcen_retencion) / 100).toFixed(2);
                        }
                    }
                }
            }
        },
        addretenciones(){
            if(this.recuperacion){
                this.valorretenciones.push({
                    iva: null,
                    porcentajeiva: null,
                    cantidadiva: null,
                    renta: null,
                    baserenta: null,
                    porcentajerenta: null,
                    cantidadrenta: null,
                    errorbase:[],
                });
                //this.valorescalculodiv();
            }
        },
        agregarretencioniva(index){
            if(this.recuperacion){
                if (this.valorretenciones[index].iva != null) {
                    this.valorretenciones[index].porcentajeiva = this.valorretenciones[index].iva.porcen_retencion + "%";
                    this.valorretenciones[index].cantidadiva = ((this.valorretenciones[index].baseiva * this.valorretenciones[index].iva.porcen_retencion) / 100).toFixed(2);
                }else{
                    this.valorretenciones[index].porcentajeiva = null;
                    this.valorretenciones[index].cantidadiva = null;
                }
            }
        },
        agregarretencionrenta(index){
            if(this.recuperacion){
                if (this.valorretenciones[index].renta != null) {
                    this.valorretenciones[index].porcentajerenta = this.valorretenciones[index].renta.porcen_retencion + "%";
                    this.valorretenciones[index].cantidadrenta = ((this.valorretenciones[index].baserenta * this.valorretenciones[index].renta.porcen_retencion) / 100).toFixed(2);
                }else{
                    this.valorretenciones[index].porcentajerenta = null;
                    this.valorretenciones[index].cantidadrenta = null;
                }
            }
        },
        agregarretencionivavalor(index, valor){
            if(this.recuperacion){
                var total = parseFloat(this.formulas.valor12) - parseFloat(valor);
                var num = parseInt(this.valorretenciones.length) - 1;
                for(var i=0; i < this.valorretenciones.length; i++){
                    if(i != index){
                        this.valorretenciones[i].baseiva = (total/num).toFixed(2);
                    }
                }
                this.agregarretencionivayrenta(index);
            }
        },
        agregarretencionrentavalor(index, valor){
            if(this.recuperacion){
                var total = parseFloat(this.formulas.subtotal) - parseFloat(valor);
                var num = parseInt(this.valorretenciones.length) - 1;
                for(var i=0; i < this.valorretenciones.length; i++){
                    if(i != index){
                        this.valorretenciones[i].baserenta = (total/num).toFixed(2);
                    }
                }
                this.agregarretencionivayrenta(index);
            }
        },
        agregarretencionivayrenta(){
            this.valorretenciones.forEach((el,index) => {
                if (this.valorretenciones[index].iva != null) {
                    this.valorretenciones[index].porcentajeiva = this.valorretenciones[index].iva.porcen_retencion + "%";
                    this.valorretenciones[index].cantidadiva = ((this.valorretenciones[index].baseiva * this.valorretenciones[index].iva.porcen_retencion) / 100).toFixed(2);
                }else{
                    this.valorretenciones[index].porcentajeiva = null;
                    this.valorretenciones[index].cantidadiva = null;
                }
                if (this.valorretenciones[index].renta != null) {
                    this.valorretenciones[index].porcentajerenta = this.valorretenciones[index].renta.porcen_retencion + "%";
                    this.valorretenciones[index].cantidadrenta = ((this.valorretenciones[index].baserenta * this.valorretenciones[index].renta.porcen_retencion) / 100).toFixed(2);
                }else{
                    this.valorretenciones[index].porcentajerenta = null;
                    this.valorretenciones[index].cantidadrenta = null;
                }
            });
        },
        eliminararrayretencion(id) {
            this.valorretenciones.splice(id, 1);
            this.valorescalculodiv();
        },
        //listar clave acceso
        listarclave() {
            // var url = "/api/listarclaveretencion/" + this.usuario.id;
            // axios.get(url).then(res => {
            //     var fecha = moment(this.factura.fecha_emision).format("DDMMYYYY");
            //     var rec = res.data.recupera[0];
            //     var secuencial = this.zeroFill(res.data.secuencial, 9);
            //     var establecimiento = this.zeroFill(rec.establecimiento, 3);
            //     var punto_emision = this.zeroFill(rec.punto_emision, 3);
            //     var codigoacc = fecha+"07"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
            //     var acceso = this.Modulo11(codigoacc);
            //     console.log("listarclave"+this.factura.respuesta);
            //     if(this.factura.respuesta == 'Enviado' ){
            //         console.log("Enviado clave retencion");
            //     }else{
            //         this.factura.clave_acceso = codigoacc + acceso;
            //     }
                
            // });
            // return false;
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return (new Array(width + (/\./.test(number) ? 2 : 1)).join("0") +number);
            }
            return number + "";
        },
        Modulo11(claveAcceso) {
            var multiplos = [2, 3, 4, 5, 6, 7];
            var i = 0;
            var cantidad = claveAcceso.length;
            var total = 0;
            while (cantidad > 0) {
                total += parseInt(claveAcceso.substring(cantidad - 1, cantidad)) * multiplos[i];
                //console.log(total + " - " + (claveAcceso.substring(cantidad - 1, cantidad) *multiplos[i]) + " - " + claveAcceso.substring(cantidad - 1, cantidad) + " - " + multiplos[i]);
                i++;
                i = i % 6;
                cantidad--;
            }
            var modulo11 = 11 - (total % 11);
            if (modulo11 == 11) {
                modulo11 = 0;
            } else if (modulo11 == 10) {
                modulo11 = 1;
            }
            return modulo11;
        },

        listarbanco() {
            axios.get("/api/traerbancofactcomp").then(({data}) => {
                this.bancos = data;
            });
        },
        cambiospagos(){
            setTimeout(() => {
                var total = 0;
                this.pagos.datos.forEach(el => {
                    total = this.total_pendiente / this.pagos.datos.length;
                    el.cantidad_pago = total;
                });
            }, 50);
        },
        cambiospagosrec(){
            this.pagos.datos.forEach(el => {
                el.cantidad_pago = (0).toFixed(2);
            });
            this.pagos.datos = [
                {
                    metodo_pago:'',
                    banco_pago:null,
                    cantidad_pago:0,
                    nro_trans:'',
                    fecha_pago:'',
                    cuenta:'',
                    plan_cuenta:null,
                }
            ];
            if(!this.pagos.estado){
                this.pagos.datos[0].cantidad_pago = this.total_pendiente;
            }
        },
        eliminararraypagos(id) {
            this.pagos.datos.splice(id, 1);
        },
        addpagos(){
            this.pagos.datos.push({
                metodo_pago:'',
                banco_pago:'',
                cantidad_pago:0,
                nro_trans:'',
                fecha_pago:'',
                cuenta:'',
                plan_cuenta:null,
            });
        },
        listarsustento() {
          axios.get("/api/traersustento?empresa="+ this.usuario.id_empresa).then( ({data}) => {
              this.sustentos = data;
          });
        },
        listarTipoComprobante() {
            axios
                .get("/api/tipcomprob?id_empresa=" + this.usuario.id_empresa)
                .then(({ data }) => {
                    console.log("tipo comprobante"+data.recupera);
                    this.tipo_comprobantes = data.recupera;
                });
        },
        listarimportaciones() {
          axios.get("/api/traerimport?empresa=" + this.usuario.id_empresa).then( response => {
            this.imports = response.data;
          });
        },
        recuperardatos(){
            axios.get("/api/factura_compra/recuperar/"+ this.$route.params.id).then( ({data}) => {
                console.log(data);
                if(data.factura.gasto_import == 1 || data.factura.gasto_import == '1g'){
                    data.factura.gasto_import = '1g';
                }else{
                    data.factura.gasto_import = '0g';
                }
                this.factura = {
                    id_factcompra:data.factura.id_factcompra,
                    nfactura:data.factura.descripcion,
                    fecha_emision:data.factura.fech_emision,
                    fecha_validez:data.factura.fech_validez,
                    autorizacion:data.factura.nro_autorizacion,
                    proyectos:data.factura.id_proyecto,
                    tipo_sustento:data.factura.id_sustento,
                    destino_pago:data.factura.destino_pago,
                    gastos:data.factura.gasto_import,
                    importacion:data.factura.id_importacion,
                    orden_compra:data.factura.orden_compra,
                    docutributario:parseInt(data.factura.documento_tributario),
                    clave_acceso:data.factura.observacion,
                    observacion:data.factura.descripcion,
                    id_retfactcompra:data.factura.id_retfactcompra,
                    respuesta: data.factura.respuesta,
                    cuentas: data.cuentas,
                    clave_acceso:data.factura.observacion,
                    tipo_comprobante:data.factura.id_tipo_comprobante
                };
                console.log(data.factura.observacion);
                this.cliente = {
                    tipo:true,
                    busqueda:'',
                    clientes:[],
                    id_cliente:data.proveedor.id_proveedor,
                    nombre:data.proveedor.nombre_proveedor,
                    telefono:data.proveedor.telefono_prov,
                    email:data.proveedor.email,
                    tipo_identificacion:data.proveedor.tipo_identificacion,
                    identificacion:data.proveedor.identif_proveedor,
                    direccion:data.proveedor.direccion_prov,
                };
                this.anticipover(this.cliente.id_cliente);
                this.producto.tipo = true;
                data.detalle_factura.forEach(el => {
                    this.producto.lista_productos.push({
                        id_producto_bodega:el.id_producto_bodega,
                        id_detalle_factura_compra:el.id_detalle_factura_compra,
                        id_producto: el.id_producto,
                        cod_alterno: el.cod_alterno,
                        cod_principal: el.cod_principal,
                        nombre: el.nombre,
                        nomb: el.nomb,
                        sector:el.sector,
                        cantidad: el.cantidad,
                        cantidadreal: el.cantidad,
                        precio: el.precio,
                        descuento: el.descuento,
                        p_descuento: el.p_descuento,
                        proyecto: el.id_proyecto,
                        subtotal: (el.precio * el.cantidad) - el.descuento,
                        iva: el.id_iva,
                        ice: el.id_ice,
                        id_bodega:null,
                        nombrebodega: el.nombrebodega
                    });
                });

                if(data.pagos.length>=1){
                    this.pagos.estado=true;
                    data.pagos.forEach(el => {
                        if(el.anticipo==1){
                            el.metodo_pago = "Anticipo";
                            this.anticipo_creado = this.anticipo_creado + parseFloat(el.cantidad_pago);
                        }
                    });
                    this.pagos.datos = data.pagos;
                }
                if(data.creditos){
                    this.creditos.estado=true;
                    this.creditos = data.creditos;
                }
                if(data.iva.length>=1 || data.renta.length>=1){
                    this.retenciones.estado=true;
                }
                data.iva.forEach((el,index) => {
                    if(this.valorretenciones.length<index+1){ this.valorretenciones.push({baseiva: null,iva: null,porcentajeiva: null,cantidadiva: null,renta: null,baserenta: null,porcentajerenta: null,cantidadrenta: null,errorbase:[]}); }
                    const valor = this.listretenciones.reduce((i, item, index) => item.id_retencion === el.id_retencion ? index : i, -1);
                    this.valorretenciones[index].iva = this.listretenciones[valor];
                    this.valorretenciones[index].baseiva = parseFloat(el.baseiva).toFixed(2);
                    this.valorretenciones[index].cantidadiva = parseFloat(el.cantidadiva).toFixed(2);
                    this.valorretenciones[index].porcentajeiva = parseFloat(el.porcentajeiva).toFixed(2);
                    
                });
                data.renta.forEach((el,index) => {
                    if(this.valorretenciones.length<index+1){ this.valorretenciones.push({baseiva: null,iva: null,porcentajeiva: null,cantidadiva: null,renta: null,baserenta: null,porcentajerenta: null,cantidadrenta: null,errorbase:[]}); }
                    const valor = this.listretenciones.reduce((i, item, index) => item.id_retencion === el.id_retencion ? index : i, -1);
                    this.valorretenciones[index].renta = this.listretenciones[valor];
                    this.valorretenciones[index].baserenta = parseFloat(el.baserenta).toFixed(2);;
                    this.valorretenciones[index].porcentajerenta  = parseFloat(el.porcentajerenta).toFixed(2);;
                    this.valorretenciones[index].cantidadrenta = parseFloat((el.baserenta*parseFloat(el.porcentajerenta))/100).toFixed(2);;
                });
                /*if(data.iva.length>=1 || data.renta.length>=1){
                    this.retenciones.estado=true;
                    data.iva.forEach((el,index) => {
                        this.valorretenciones[index].iva = el;
                    });
                    data.renta.forEach((el,index) => {
                        this.valorretenciones[index].renta = el;
                    });
                } */
                if(!this.factura.id_retfactcompra){
                    console.log("ingreso");
                    this.listarclave();
                }
            }).catch( error => {
                console.log(error);
            })
        },
        validar(){
            this.error = {
                error:0,
                factura:{
                    fecha_emision:[],
                    numero_factura:[],
                    numero_autorizacion:[],
                    tipo_sustento:[],
                    destino_pago:[],
                    tipo_comprobante:[]
                },
                cliente:{
                    tipo:[]
                },
                producto:{
                    busqueda:[]
                },
                creditos:{
                    periodo:[],
                    tiempo:[],
                    plazos:[],
                    monto:[],
                }
            }

            if(!this.factura.fecha_emision){
                this.error.factura.fecha_emision.push("Debe agregar la fecha de emisión");
                this.error.error=1;
                console.log(1);
            }
            if(this.factura.nfactura.length!=15){
                this.error.factura.numero_factura.push("Debe ingresar 15 números");
                this.error.error=1;
                console.log(2);
            }
            if(!this.factura.autorizacion){
                this.error.factura.numero_autorizacion.push("Debe agregar N° autorización");
                this.error.error=1;
                console.log(3);
            }
            if (!this.factura.tipo_sustento) {
                this.error.factura.tipo_sustento.push(
                    "Obligatorio"
                );
                this.error.error = 1;
                console.log("1.0.1");
            }
            if (!this.factura.destino_pago) {
                this.error.factura.destino_pago.push(
                    "Obligatorio"
                );
                this.error.error = 1;
                console.log("1.0.2");
            }
            if (!this.factura.tipo_comprobante) {
                this.error.factura.tipo_comprobante.push(
                    "Obligatorio"
                );
                this.error.error = 1;
                console.log("1.0.3");
            }
            if(!this.cliente.tipo){
                this.error.cliente.tipo.push("Debe agregar un proveedor al comprobante");
                this.error.error=1;
                console.log(4);
            }
            if(!this.producto.tipo){
                this.error.producto.busqueda.push("Debe agregar un producto al comprobante");
                this.error.error=1;
                console.log(5);
            }

            for (var i = 0; i < this.producto.lista_productos.length; i++) {
                this.producto.lista_productos[i].errorcantidad = [];
                this.producto.lista_productos[i].errorprecio = [];
                this.producto.lista_productos[i].errorproyecto = [];
                this.producto.lista_productos[i].errorid_bodega = [];
                if (!this.producto.lista_productos[i].cantidad) {
                    this.producto.lista_productos[i].errorcantidad.push("Obligatorio");
                    this.error.error = 1;
                }
                if (!this.producto.lista_productos[i].precio) {
                    this.producto.lista_productos[i].errorprecio.push("Obligatorio");
                    this.error.error = 1;
                }
                if (!this.producto.lista_productos[i].proyecto) {
                    this.producto.lista_productos[i].errorproyecto.push("Obligatorio");
                    this.error.error = 1;
                }
            }
            if(this.creditos.estado){
                if(!this.creditos.periodo){
                    this.error.creditos.periodo.push("Ingrese Periodo");
                    this.error.error=1;
                    console.log(8);
                }
                if(!this.creditos.tiempo){
                    this.error.creditos.tiempo.push("Obligatorio");
                    this.error.error=1;
                    console.log(9);
                }
                if(!this.creditos.plazos){
                    this.error.creditos.plazos.push("Obligatorio");
                    this.error.error=1;
                    console.log(10);
                }
                if(parseFloat(this.creditos.monto)<=0){
                    this.error.creditos.monto.push("Obligatorio");
                    this.error.error=1;
                    console.log(11);
                }
            }
            if(this.retenciones.estado){
                for (var i = 0; i < this.valorretenciones.length; i++) {
                    this.valorretenciones[i].errorbase = [];
                    if (this.valorretenciones[i].renta) {
                        if (parseFloat(this.valorretenciones[i].baserenta)<=0 || !this.valorretenciones[i].baserenta) {
                            this.valorretenciones[i].errorbase.push("Obligatorio");
                            this.error.error = 1;
                            console.log(12);
                        }
                    }
                }
            }
            if(this.pagos.estado){
                for (var i = 0; i < this.pagos.datos.length; i++) {
                    this.pagos.datos[i].errormetodo = [];
                    this.pagos.datos[i].errorcantidad = [];
                    if (!this.pagos.datos[i].metodo_pago) {
                        this.pagos.datos[i].errormetodo.push("Obligatorio");
                        this.error.error = 1;
                        console.log(13);
                    }
                    if (parseFloat(this.pagos.datos[i].cantidad_pago)<=0) {
                        this.pagos.datos[i].errorcantidad.push("Obligatorio");
                        this.error.error = 1;
                        console.log(14);
                    }
                }
            }

            if(this.error.error){
                setTimeout(() => {
                    var valor = $(".text-danger:first-child").offset().top - 300;
                    $("html, body").animate({
                        scrollTop: valor,
                    }, 500);
                }, 50);
            }
            return this.error.error;
        },
        abrir_plan_cuentas_pagos(index){
            this.modalcontable = {
                abrir: true,
                titulo: "Escoger plan de cuenta",
                tipo: 2,
            }
            this.pagos.index = index;
        },
        abrir_plan_cuentas_pagos1(index){
            this.modalcontable = {
                abrir: true,
                titulo: "Escoger plan de cuenta",
                tipo: 3,
            }
            this.pagos.index = index;
        },
        eliminarplc(index){
            this.pagos.index = index;
            this.pagos.datos[this.pagos.index].cuenta = "";
            this.pagos.datos[this.pagos.index].plan_cuenta = null;
        },
        //Facturación
        enviado(){
            this.$router.push("/compras/factura-compra");
        },
        async crearfacturacion(firma, password, factura, tipo, usuario, id_factura, carpeta, fecha, valor, logo, nombre_empresa){
            try {
                let {data:comprobante} = await script_comprobantes.obtener_comprobante_firmado.getAll({ factura:factura, id_factura:id_factura, tipo:tipo });
                let {resultado:contenido} = await script_comprobantes.lectura_firma.getAll({ firma:firma, id_factura:   id_factura, tipo:tipo });
                let {data:certificado} = await script_comprobantes.firmar_comprobante.getAll({ contenido:contenido[0], password:password, comprobante:comprobante, id_factura:id_factura, tipo:tipo });
                let {data:quefirma} = await script_comprobantes.verificar_firma.getAll({ comprobante:comprobante, mensaje:certificado, tipo:tipo, id_factura:id_factura, carpeta:carpeta });
                let {data:validado} = await script_comprobantes.validar_comprobante.getAll({ comprobante:comprobante, tipo:tipo, id_factura:id_factura, carpeta:carpeta, id_empresa:usuario.id_empresa });
                let {data:recibida} = await script_comprobantes.autorizar_comprobante.getAll({ comprobante:comprobante, validado:validado, usuario:usuario, tipo:tipo, id_factura:id_factura, carpeta:carpeta, fecha:fecha, valor:valor, logo:logo, nombre_empresa:nombre_empresa });
                let {data:registrado} = await script_comprobantes.autorizado_comprobante.getAll({ recibida:recibida, tipo:tipo, id_factura:id_factura });
                this.$vs.notify({
                    time: 8000,
                    title: "Retención Enviada",
                    text:"La Retención se generó exitosamente",
                    color: "success"
                });
                this.enviado();
            } catch(error) {
                this.$vs.notify({
                    time: 20000,
                    title: error.mensaje,
                    text: error.informacion,
                    color: "danger"
                });
                this.enviado();
            }
        },
        intlRound(numero, decimales = 2, usarComa = false) {
            var opciones = {
                maximumFractionDigits: decimales,
                useGrouping: false
            };
            usarComa = usarComa ? "es" : "en";
            return new Intl.NumberFormat(usarComa, opciones).format(numero);
        },
        bodegas(){
            axios.get("/api/factura_compra/traerbodegas?empresa=" + this.usuario.id_empresa + "&establecimiento=" + this.usuario.id_establecimiento).then( ({data}) => {
                this.listarbodegas = data;
            });
        },
        solonumeros(e) {
            var key = e.charCode || e.keyCode || 0;
            if(key == 8 || key == 9 || key == 46 || key == 116 || (key >= 37 && key <= 40) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105)) {
                if(this.factura.nfactura.length>=15 && key != 8 && key != 9 && key != 46 && key != 116)
                    e.preventDefault();
            }else{
                e.preventDefault();
            }
        },
        list_info_empresa() {
            axios
                .get(`/api/verempresa/${this.usuario.id_empresa}`)
                .then(({ data }) => {
                    this.empresa_info = data[0];
                })
                .catch(error => {
                    console.log(error);
                });
        },
        dowloadxmlfile() {
            window.open(
                "/api/dowloadxmlfactcompra?id_empresa=" + this.usuario.id_empresa + "&id_factcomp=" + this.$route.params.id
            );
        },
        dowloadpdffile() {
            window.open(
                "/api/dowloadpdffactcompra?id_empresa=" + this.usuario.id_empresa + "&id_factcomp=" + this.$route.params.id
            );
        }
    },
    mounted() {
        this.listar_creacion_cliente();
        this.listar_cuenta_contable(this.plan_cuenta.buscar);
        this.listarformapagos();
        this.listarretenciones();
        this.listarbanco();
        this.listarsustento();
        this.listarTipoComprobante()
        this.listarimportaciones();
        this.recuperardatos();
        this.bodegas();
        $(document).on("click",function(e) {
         var container = $(".busqueda_lista");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
               $(".busqueda_lista").hide();
            }
        });
        $(".focuspr").focus();
        this.list_info_empresa();
    }
};
</script>
<style lang="scss">
    @import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
    .sindis .vs-input--input:focus {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .sindis .vs-input--input {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .nover > .icon-select {
        display: none;
    }
    .hovertrash:hover > .trasher {
        display: block !important;
    }
    .botonstl {
        height: 100%;
        width: 38px;
        border: 1px solid #635ace;
        background: transparent;
        color: #635ace;
        font-size: 16px;
        cursor: pointer;
    }
    .elejido {
        background: #635ace !important;
        color: #fff !important;
    }
    //Busqueda de comprobantes
    .busqueda_cliente input{
        height: 50px;
        padding-left: 45px!important;
    }
    .busqueda_cliente_icono{
        position: absolute!important;
        top: 11px;
        left: 25px;
    }
    .busqueda_lista{
        position: absolute;
        width: 97%;
        z-index: 9;
    }
    .ul_busqueda_lista{
        min-width: 160px;
        margin: -2px 0 0;
        list-style: none;
        font-size: 13.5px;
        text-align: left;
        background-color: #fff;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.15);
        border-radius: 2px;
        -webkit-box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        box-shadow: 0 6px 12px rgba(0,0,0,0.175);
        background-clip: padding-box;
    }
    .ul_busqueda_lista li{
        padding: 10px 16px;
        text-overflow: ellipsis;
        overflow: hidden;
        font-weight: 300;
        display: block;
        clear: both;
        line-height: 1.3;
        color: #333;
        white-space: nowrap;
        font-family: sans-serif;
    }
    .ul_busqueda_lista li:hover{
        background: rgba(16, 22, 58, 0.38);
        cursor: pointer;
        color:#fff;
    }
    .busqueda_cliente .input-span-placeholder{
        padding-left: 50px;
        margin-top: 3px;
    }
    .buscar_otro{
        position: absolute;
        margin-top: -35px;
        margin-left: 14px;
        cursor: pointer;
    }
    .eliminar_producto_icono{
        display:none;
    }
    .eliminar_producto_icono svg{
        margin-top:8px;
    }
    .fila_lista:hover .eliminar_producto_icono{
        display:block;
    }
    .cabezera_total span{
        float: right;
        margin-right: 25px;
    }
    .cabezera_total>div{
        margin-left: 20px;
        padding: 9px 3px;
    }
    .cabezera_total{
        margin-top:15px;
    }
    .vs-input--placeholder {
        top: 0px;
    }
    .modal-xl .vs-popup{
        width: 1250px;
    }
    .tablavista td{
        padding: 10px 15px;
    }
    .tablavista:hover{
        cursor:pointer;
        background: rgba(0,0,0,.2);
    }


    .vs-popup {
        width: 1060px !important;
    }
    .peque .vs-popup {
        width: 600px !important;
    }
    .sindis .vs-input--input:focus {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .sindis .vs-input--input {
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }
    .nover > .icon-select {
        display: none;
    }
    .hovertrash:hover > .trasher {
        display: block !important;
    }
    .botonstl {
        height: 100%;
        width: 38px;
        border: 1px solid #635ace;
        background: transparent;
        color: #635ace;
        font-size: 16px;
        cursor: pointer;
    }
    .elejido {
        background: #635ace !important;
        color: #fff !important;
    }
    .flexy > .vs-divider--text {
        display: flex;
    }
    .slide-fade-enter-active {
        transition: all 0.5s ease;
    }
    .slide-fade-leave-active {
        transition: all 0.5s cubic-bezier(1, 0.5, 0.8, 1);
    }
    .slide-fade-enter, .slide-fade-leave-to
    /* .slide-fade-leave-active for <2.1.8 */ {
        transform: translateX(10px);
        opacity: 0;
    }
    .btnmoremore{
        position: absolute;
        z-index: 9;
        right: 18px;
        margin-top: -45px;
        font-size: 31px;
        background: #fff;
        cursor: pointer;
    }
    .derecha input{
        text-align: end;
    }
    .derecha .vs-input--placeholder{
        text-align: end;
    }
    .preloader {
        width: 50px;
        height: 50px;
        border: 10px solid #eee;
        border-top: 10px solid #666;
        border-radius: 50%;
        animation-name: girar;
        animation-duration: 2s;
        animation-iteration-count: infinite;
        animation-timing-function: linear;
    }
    @keyframes girar {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
    .lista_preloader{
        padding: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .derecha input{
        text-align: end;
    }
    .derecha .vs-input--placeholder{
        text-align: end;
    }
    .nombrearreglo span{
        font-size: 11px;
        letter-spacing: -0.5px;
        line-height: 15px;
        display: block;
    }
</style>
