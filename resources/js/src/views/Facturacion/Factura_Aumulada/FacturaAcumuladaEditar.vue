<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3 v-if="factura.clave_acceso">Nota Venta N° {{(factura.clave_acceso).substring(24,27)}}-{{(factura.clave_acceso).substring(27,30)}}-{{(factura.clave_acceso).substring(30,39)}}</h3>
                <h3 v-else>Cargando Nota</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Ambiente:</h6>
                    <span v-if="factura.ambiente==2">Producción</span>
                    <span v-else>Pruebas</span>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto" style="text-align: center;">
                    <h6 class="mb-1">Tipo Emisión:</h6> Emision Normal
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" v-if="factura.respuesta!='Enviado'">
                    <h6 v-if="empresa.compra">{{empresa.compra}}</h6>
                    <h6 v-else>Orden de compra:</h6>
                    <vs-input class="w-full" v-model="factura.orden_compra"/>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                    <h6 v-if="empresa.compra">{{empresa.compra}}</h6>
                    <h6 v-else>Orden de compra:</h6>
                    <vs-input class="w-full" v-model="factura.orden_compra" disabled/>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" v-if="factura.respuesta!='Enviado'">
                    <h6 class="mb-1" v-if="empresa.migo">{{empresa.migo}}</h6>
                    <h6 class="mb-1" v-else>Migo:</h6>
                    <vs-input class="w-full" v-model="factura.migo"/>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                    <h6 class="mb-1" v-if="empresa.migo">{{empresa.migo}}</h6>
                    <h6 class="mb-1" v-else>Migo:</h6>
                    <vs-input class="w-full" v-model="factura.migo" disabled/>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <h6 class="mb-1">Fecha Emisión:</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full" disabled v-model="factura.fecha_emision" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha_emision">
                        <div v-for="err in error.factura.fecha_emision" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6">
                    <h6 class="mb-1">Vendedor:</h6>
                    <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="factura.vendedor">
                        <vs-select-item :key="index" :value="item.id_vendedor" :text="item.nombre_vendedor" v-for="(item, index) in listarvendedores"/>
                    </vs-select>
                    <div v-show="error" v-if="!factura.vendedor">
                        <div v-for="err in error.factura.vendedor" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <!-- <div class="vx-col sm:w-full w-full mb-6 text-center">
                    <h6 class="mt-4">Clave de acceso:</h6>
                    <p>{{ factura.clave_acceso }}</p>
                </div> -->
                <div class="vx-col sm:w-1/3 w-full mb-6" style="margin-top: 20px; margin-bottom: 0.2rem !important;">
                    <vs-checkbox icon-pack="feather" icon="icon-check" v-model="guia">
                        <template v-if="guia">
                            <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">Si</label>
                        </template>
                        <template v-else>
                            <label class="vs-input--label" style="font-size: 14px;font-weight: bold;">No</label>
                        </template>
                        | Guia de Remisión
                    </vs-checkbox>
                </div>
            </div>
            <vs-divider position="left" v-if="guia">
                <h3>Guia de remisión</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="guia">
                <div class="vx-col sm:w-full w-full mb-6 text-center">
                    <h6 class="mt-4">Clave de acceso:</h6>
                    <p>{{ transportista.clave_acceso }}</p>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Razón social del transportista</h6>
                    <vs-input class="w-full" v-model="transportista.nombre_transporte"/>
                    <div v-show="error" v-if="!transportista.nombre_transporte">
                        <div v-for="err in errornombre_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Tipo Identificación</h6>
                    <vs-select class="selectExample w-full" placeholder="Tipo" v-model="transportista.tipo_identificacion_transporte" @change="cambio_guia(transportista.tipo_identificacion_transporte)">
                        <vs-select-item :key="index" :value="item.value" :text="item.text" v-for="(item, index) in tipo_identificacion_menu"/>
                    </vs-select>
                    <div v-show="error" v-if="!transportista.tipo_identificacion_transporte">
                        <div v-for="err in errortipo_identificacion_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Identificación</h6>
                    <vs-input class="w-full" v-model="transportista.identificacion_transporte"/>
                    <div v-show="error" v-if="!transportista.identificacion_transporte">
                        <div v-for="err in erroridentificacion_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">fecha de Inicio</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full mt-1" disabled v-bind:value="transportista.fecha_inicio_transporte" placeholder="Seleccionar" @on-change="listarclave_guia()"></flat-pickr>
                    <div v-show="error" v-if="!transportista.fecha_inicio_transporte">
                        <div v-for="err in errorfecha_inicio_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">fecha de Finalización</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full mt-1" v-model="transportista.fecha_fin_transporte" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!transportista.identificacion_transporte">
                        <div v-for="err in erroridentificacion_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Placa</h6>
                    <vs-input class="w-full" v-model="transportista.placa_transporte"/>
                    <div v-show="error" v-if="!transportista.placa_transporte">
                        <div v-for="err in errorplaca_transporte" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Documento aduanero</h6>
                    <vs-input class="w-full" v-model="transportista.documento_aduanero"/>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Motivo de translado</h6>
                    <vs-input class="w-full" v-model="transportista.motivo_translado"/>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Cliente</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full mb-6 relative" v-if="cliente.tipo">
                    <div class="vx-row">
                        <!--<a class="flex items-center buscar_otro" v-if="factura.respuesta!='Enviado'" @click="cliente.tipo=false"> Agregar otro Cliente </a>-->
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
                    <vs-input class="w-full busqueda_cliente" placeholder="Escoge un cliente Para agregar un Comprobante" v-model="cliente.busqueda" @keyup="listar_cliente(cliente.busqueda)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_cliente_ls" style="display: none;">
                        <div v-if="preloader.cliente">
                            <ul class="ul_busqueda_lista" v-if="cliente.clientes.length">
                                <li v-for="(tr,index) in cliente.clientes" :key="index" @click="seleccionar_cliente(tr)"> {{ tr.nombre }} </li>
                            </ul>
                            <ul class="ul_busqueda_lista" v-else>
                                <li @click="abrir_modal_crear_cliente()"> ESTE CLIENTE NO SE ENCUENTRA REGISTRADO, AGREGAR NUEVO CLIENTE </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                </div>
                <div v-show="error" v-if="!cliente.tipo">
                    <div v-for="err in error.cliente.tipo" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-full w-full relative" v-if="producto.tipo">
                    <vs-table hoverFlat :data="producto.lista_productos" style="font-size: 12px;overflow-y:hidden;">
                        <template slot="thead">
                            <vs-th>CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th>CANTIDAD</vs-th>
                            <vs-th>PRECIO</vs-th>
                            <vs-th style="width: 110px;">ICE</vs-th>
                            <vs-th>DESCUENTO</vs-th>
                            <vs-th>VALOR</vs-th>
                            <vs-th>SUBTOTAL</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td class="nombrearreglo">
                                    <vs-input class="w-full derecha font-small" v-model="tr.nombre"/>
                                </vs-td>
                                <vs-td>
                                    <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="tr.proyecto">
                                        <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div v-for="err in tr.errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:70px!important;">
                                    <vs-input class="w-full derecha" v-model="tr.cantidad" @keyup="validarcantidad(tr,index)"/>
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <!-- <vs-td style="width:70px!important;">
                                    <vs-input class="w-full derecha" v-model="tr.precio"/>
                                    <div v-show="error" v-if="!tr.precio">
                                        <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td> -->
                                <!-- <vs-td style="width:70px!important;" v-if="typeof tr.precio_sin_iva=='undefined' || tr.precio_sin_iva=='' || tr.precio_sin_iva==null">
                                    <vs-input class="w-full derecha" v-model="tr.precio" />
                                    <div v-show="error" v-if="!tr.precio">
                                        <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:70px!important;" v-else>
                                    {{tr.precio |currency}}
                                    <div v-show="error" v-if="!tr.precio">
                                        <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td> -->
                                    <vs-td style="width:70px!important;" v-if="usuario.id_empresa==55">
                                        <template v-if="typeof tr.precio_sin_iva=='undefined' || tr.precio_sin_iva=='' || tr.precio_sin_iva==null">
                                            <vs-input class="w-full derecha" v-model="tr.precio" />
                                            <div v-show="error" v-if="!tr.precio">
                                                <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                            </div>
                                        </template>
                                        <template v-else>
                                            ${{parseFloat(tr.precio).toFixed(4)}}
                                            <div v-show="error" v-if="!tr.precio">
                                                <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                            </div>
                                        </template>
                                    </vs-td>
                                    <vs-td style="width:70px!important;" v-else>
                                        <template v-if="typeof tr.precio_sin_iva=='undefined' || tr.precio_sin_iva=='' || tr.precio_sin_iva==null">
                                            <vs-input class="w-full derecha" v-model="tr.precio"/>
                                            <div v-show="error" v-if="!tr.precio">
                                                <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                            </div>
                                        </template>
                                        <template v-else>
                                            ${{tr.precio}}
                                            <div v-show="error" v-if="!tr.precio">
                                                <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                            </div>
                                        </template>
                                    </vs-td>
                                <vs-td style="width:70px!important;"><span class="vs-inputx vs-input--input normal hasValue" style="font-size: 13.5px;font-family: inherit;">${{(tr.total_ice*tr.cantidad).toFixed(2)}}</span></vs-td>
                                <vs-td style="width:170px!important;">
                                    <vx-input-group>
                                        <vs-input class="w-full derecha" placeholder="$0.00" v-model="tr.descuento"/>
                                        <template slot="append">
                                            <div class="append-text btn-addon">
                                                <button class="botonstl" :class="{'elejido':tr.p_descuento==1}" @click="tr.p_descuento=1">
                                                    $
                                                </button>
                                                <button class="botonstl" :class="{'elejido':tr.p_descuento==0}" @click="tr.p_descuento=0">
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td style="width:70px!important;">
                                    <vs-input class="w-full derecha" v-if="tr.iva!==2" :disabled="true" v-model="tr.precio_sin_iva" @keyup="cambiarivas(index,tr.precio_sin_iva)"/>
                                    <vs-input class="w-full derecha" v-else v-model="tr.precio_sin_iva" @keyup="cambiarivas(index,tr.precio_sin_iva)"/>

                                </vs-td>
                                <vs-td style="width:70px!important;" v-if="usuario.id_empresa==55">
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * parseFloat(tr.precio).toFixed(4)) - tr.descuento).toFixed(4)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * parseFloat(tr.precio).toFixed(4)) - ((tr.cantidad * parseFloat(tr.precio).toFixed(4) * tr.descuento)/100)).toFixed(4)}}
                                    </template>
                                </vs-td>
                                <vs-td style="width:70px!important;" v-else>
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - tr.descuento).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - ((tr.cantidad * tr.precio * tr.descuento)/100)).toFixed(2)}}
                                    </template>
                                </vs-td>
                                <feather-icon icon="TrashIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" class="eliminar_producto_icono" @click="eliminar_producto(index)"/>
                            </vs-tr>
                        </template>
                    </vs-table>
                    
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative" v-if="factura.respuesta!='Enviado'">
                    <vs-input class="w-full busqueda_cliente focuspr" placeholder="Agrega productos a esta factura" v-model="producto.busqueda" @keyup="listar_productos(producto.busqueda)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_producto_ls" style="display: none;">
                        <div v-if="preloader.productos">
                            <ul class="ul_busqueda_lista">
                                <li v-for="(tr,index) in producto.productos" :key="index" @click="seleccionar_productos(tr)"> <span v-if="tr.cod_alterno" style="font-weight: bold;" >CodAlt: {{ tr.cod_alterno }} - </span> <span v-else style="font-weight: bold;">CódPrin: {{tr.cod_principal}} - </span><span style="font-weight: bold;">{{ tr.nombre }}</span> <span v-if="tr.presentacion" style="font-weight: bold;" > - Presentación: {{ tr.presentacion }} </span> <span v-if="tr.nombrebodega"> - <span style="font-size: 12px;">Bodega: {{tr.nombrebodega}}</span> - <span style="font-size: 12px;">cantidad: {{tr.cantidad}}</span></span> <span v-if="tr.nombre_marca && usuario.id_empresa==68"><span style="font-size: 12px;">Marca: {{tr.nombre_marca}}</span></span></li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                    <div v-show="error" v-if="!producto.busqueda">
                        <div v-for="err in error.producto.busqueda" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col w-full">
                    <div class="vx-row" v-if="producto.tipo">
                        <div class="vx-col sm:w-1/2 w-full" v-if="factura.respuesta!='Enviado'">
                            <h6>Observaciones:</h6>
                            <vs-textarea  class="w-full"  v-model="factura.observacion"  rows="5"/>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full" v-else>
                            <h6>Observaciones:</h6>
                            <span>{{factura.observacion}}</span>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>SUBTOTAL FINAL <span>$ {{ formulas.subtotal }}</span></div>
                                <!--<div v-if="formulas.subtotalice>0">SUBTOTAL ICE <span>$ {{ formulas.subtotalice }}</span></div>-->
                                <div v-if="formulas.valorice>0">Valor ICE <span>$ {{ formulas.valorice }}</span></div>
                                <div v-if="formulas.subtotal12>0">SUBTOTAL IVA 12% <span>$ {{ formulas.subtotal12 }}</span></div>
                                <div v-if="formulas.valor12>0">Valor IVA 12% <span>$ {{ formulas.valor12 }}</span></div>
                                <div v-if="formulas.subtotal0>0">SUBTOTAL IVA 0% <span>$ {{ formulas.subtotal0 }}</span></div>
                                <div v-if="formulas.no_impuesto>0">NO OBJETO DE IMPUESTO <span>$ {{ formulas.no_impuesto }}</span></div>
                                <div v-if="formulas.exento>0">EXENTO DE IVA <span>$ {{ formulas.exento }}</span></div>
                                <div>TOTAL DESCUENTO <span>$ {{ formulas.descuento }}</span></div>
                                <div v-if="interes_monto>0">FINANCIAMIENTO<span>$ {{ interes_monto }}</span></div>
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
            <vs-divider position="left" class="flexy" v-if="usuario.id_empresa==34">
                <h3>Cuotas Extras</h3>
                <vs-switch vs-icon-on="check" color="success" v-model="cuotas_extras.estado" class="ml-2" @click="cambioscuotas_extra()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade" v-if="usuario.id_empresa==34">
                <div class="vx-row leading-loose p-base" v-show="cuotas_extras.estado">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr,index) in cuotas_extras.datos" :key="index">
                            
                            <div class="w-1/3 ml-auto mr-auto">
                                <vs-input class="w-full text-center" label="Valor" v-model="tr.valor_pago"/>
                                <!-- <div v-show="error.error" v-if="parseFloat(tr.cantidad_pago)<=0">
                                    <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                </div> -->
                            </div>

                            <div class="w-1/3 ml-auto mr-auto">
                                <label class="vs-input--label">Fecha de pago</label>
                                <flat-pickr :config="configdateTimePicker" class="w-full" v-model="tr.fecha_pago" placeholder="Seleccionar"></flat-pickr>
                            </div>

                            <feather-icon icon="TrashIcon" style="position: absolute!important;right: 15px;margin-top: 44px;display:none" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="eliminararraycuota(index)" />
                            <feather-icon icon="PlusIcon" style="position: absolute!important;right: 15px;margin-top: 26px;display:none" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="addcuotas()" />
                        </div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy">
                <h3>Créditos</h3>
                <vs-switch vs-icon-on="check" color="success" v-model="creditos.estado" class="ml-2" @click="cambioscreditos()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-if="creditos.estado">
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                        <label class="vs-input--label">Periodo de pago</label>
                        <vs-select placeholder="Selecciona el periodo de pago" autocomplete class="selectExample w-full" v-model="creditos.periodo">
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
                        <vs-input class="w-full text-center" label="Tiempos Pago" v-model="creditos.tiempo"/>
                        <div v-show="error" v-if="!creditos.tiempo">
                            <div v-for="err in error.creditos.tiempo" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Plazos de pago</label>
                        <vs-select placeholder="Seleccione" autocomplete class="selectExample w-full" v-model="creditos.plazos">
                            <vs-select-item v-for="(v, index) in 36" :key="index" :value="v" :text="v + ' Periodos'"/>
                        </vs-select>
                        <div v-show="error" v-if="!creditos.plazos">
                            <div v-for="err in error.creditos.plazos" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input class="w-full text-center" label="Monto de pago" v-model="creditos.monto"/>
                        <div v-show="error" v-if="parseFloat(creditos.monto)<=0">
                            <div v-for="err in error.creditos.monto" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Pago por letra</label>
                        <div class="mt-2">$ {{pagoletra}}</div>
                    </div>
                </div>
            </transition>
            <div class="vx-row" v-if="creditos.estado && exist_interes==1 && interes_monto>0">
                    
                   <div class="vx-col sm:w-1/4 w-full mb-2 text-center">
                        <label class="vs-input--label">Capital</label>
                        <div class="mt-2">$ {{capital_monto}}</div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2 text-center">
                        <label class="vs-input--label">Interes</label>
                        <div class="mt-2">$ {{interes_monto}}</div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2 text-center">
                        <label class="vs-input--label">Total Pagar</label>
                        <div class="mt-2">$ {{total_interes_saldo}}</div>
                    </div>
            </div>
            <vs-divider position="left" class="flexy">
                <h3>Retenciones</h3>
                <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="retenciones.estado" @click="cambiosretenciones()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-show="retenciones.estado">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr, index) in valorretenciones" :key="index">
                            <div class="w-2/3 ml-auto mr-auto">
                                <div class="vx-row">
                                    <div class="vx-col md:w-2/3 w-full mb-2 ml-auto text-center">
                                        <label class="vs-input--label derecha">Valores por IVA</label>
                                        <vs-select @change="recuperacion=true, agregarretencioniva(index)" placeholder="Selecciona la retención" autocomplete class="selectExample w-full" v-model="tr.iva">
                                            <vs-select-item v-for="(tr, index) in listretenciones" :key="index" :value="tr" :text="tr.descrip_retencion" v-if="tr.tipo_retencion =='Retencion IVA Ventas'"/>
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input label="Base" class="w-full derecha" @click="recuperacion=true" @change="agregarretencionivavalor(index, tr.baseiva)" v-model="tr.baseiva"/>
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
                                        <vs-select @change="recuperacion=true, agregarretencionrenta(index)" placeholder="Selecciona la retención" autocomplete class="selectExample w-full" v-model="tr.renta">
                                            <vs-select-item v-for="(tr, index) in listretenciones" :key="index" :value="tr" :text="tr.descrip_retencion" v-if="tr.tipo_retencion == 'Retencion Fuente Ventas' "/>
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input label="Base" @click="recuperacion=true" @change="agregarretencionrentavalor(index, tr.baserenta)" placeholder="0.00" class="w-full derecha" v-model="tr.baserenta"/>
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
                            <feather-icon icon="TrashIcon" style="position: absolute !important;right: 125px;margin-top: 80px;display: none;" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher" @click="eliminararrayretencion(index)"/>
                            <feather-icon @click="addretenciones()" icon="PlusIcon" style="position: absolute !important;right: 125px;margin-top: 55px;display: none;" svgClasses="w-5 h-5 hover:text-danger stroke-current" class="ml-2 cursor-pointer trasher"/>
                        </div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy">
                <h3>Pagos</h3>
                <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="pagos.estado" @click="cambiospagosrec()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-show="pagos.estado">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr,index) in pagos.datos" :key="index">
                            <div class="vx-col w-full mb-2 text-center ml-auto sm:w-1/6" :class="{'ml-auto': tr.metodo_pago=='Anticipo'}">
                                <label class="vs-input--label">Método de pago</label>
                                <vs-select placeholder="Selecciona el método de pago" autocomplete class="selectExample w-full" v-model="tr.metodo_pago">
                                    <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
                                </vs-select>
                                <div v-show="error.error" v-if="!tr.metodo_pago">
                                    <div v-for="err in tr.errormetodo" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center" v-if="tr.metodo_pago!='Anticipo'">
                                <vs-select class="selectExample w-full" label="Banco" vs-multiple autocomplete v-model="tr.banco_pago">
                                    <vs-select-item v-for="data in bancos" :key="data.id_banco" :value="data.id_banco" :text="data.nombre_banco" />
                                </vs-select>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-input class="w-full text-center" label="Valor" v-model="tr.cantidad_pago"/>
                                <div v-show="error.error" v-if="parseFloat(tr.cantidad_pago)<=0">
                                    <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center mr-auto" v-if="tr.metodo_pago=='Anticipo'">
                                <vs-input class="w-full text-center" label="Anticipo Total" disabled :value="parseFloat(anticipoexistente) + (anticipo_creado - tr.cantidad_pago)"/>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center" v-if="tr.metodo_pago!='Anticipo'">
                                <vs-input class="w-full text-center" label="Nro de transacción" v-model="tr.nro_trans"/>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2" v-if="tr.metodo_pago!='Anticipo'">
                                <label class="vs-input--label">Fecha de pago</label>
                                <flat-pickr :config="configdateTimePicker" class="w-full" v-model="tr.fecha_pago" placeholder="Seleccionar"></flat-pickr>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2" v-if="tr.metodo_pago!='Anticipo'">
                                <label class="vs-input--label">Plan Cuenta</label>
                                <vx-input-group>
                                    <vs-input class="w-full" v-model="tr.cuenta"/>
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                        <vs-button color="primary" @click="abrir_plan_cuentas_pagos(index)">Buscar</vs-button>
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
                <vs-button color="success" type="filled" :disabled="disabled_button" @click="guardar_factura()">GUARDAR</vs-button>
                <vs-button color="danger" type="filled" to="/facturacion/factura-venta">CANCELAR</vs-button>
            </div>
            <!-- Crear Cliente -->
            <vs-popup :title="modal.titulo" :active.sync="modal.abrir" class="modal-xl">
                <div class="con-exemple-prompt">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full sm:w-full w-full relative mb-6" v-if="codigocliente">
                            <vs-input class="w-full" label="Código de cliente" v-model="codigocliente"/>
                            <div v-show="error" v-if="!codigocliente">
                                <div v-for="err in errorcodigocliente" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Nombre Completo" v-model="crear_cliente.nombre"/>
                            <div v-show="error" v-if="!crear_cliente.nombre">
                                <div v-for="err in error_cliente.crear_cliente.nombre" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Tipo de Identificación</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el tipo" v-model="crear_cliente.tipo_identificacion">
                                <vs-select-item :key="index" :value="item.value" :text="item.label" v-for="(item, index) in tipo_identificacion_menu"/>
                            </vs-select>
                            <div v-show="error" v-if="!crear_cliente.tipo_identificacion">
                                <div v-for="err in error_cliente.crear_cliente.tipo_identificacion" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Identificación" v-model="crear_cliente.identificacion"/>
                            <div v-show="error" v-if="crear_cliente.identificacion.length!=13 && crear_cliente.identificacion.length!=10">
                                <div v-for="err in error_cliente.crear_cliente.identificacion" :key="err" v-text="err" class="text-danger"></div>
                            </div>
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
                            <div v-show="error" v-if="!crear_cliente.grupo_tributario">
                                <div v-for="err in error_cliente.crear_cliente.grupo_tributario" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-2/5 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Dirección" v-model="crear_cliente.direccion"/>
                            <div v-show="error" v-if="!crear_cliente.direccion">
                                <div v-for="err in error_cliente.crear_cliente.direccion" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Provincia</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione la provincia" v-model="crear_cliente.provincia" @change="listarcanton(crear_cliente.provincia)">
                                <vs-select-item :key="index" :value="item.id_provincia" :text="item.nombre" v-for="(item, index) in provincia_menu"/>
                            </vs-select>
                            <div v-show="error" v-if="!crear_cliente.provincia">
                                <div v-for="err in error_cliente.crear_cliente.provincia" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Cantón</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione el cantón" v-model="crear_cliente.canton" @change="listarparroquia(crear_cliente.canton)">
                                <vs-select-item :key="index" :value="item.id_ciudad" :text="item.nombre" v-for="(item, index) in canton_menu"/>
                            </vs-select>
                            <div v-show="error" v-if="!crear_cliente.canton">
                                <div v-for="err in error_cliente.crear_cliente.canton" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <label class="vs-input--label">Parroquia</label>
                            <vs-select class="selectExample w-full" placeholder="Seleccione la parroquia" v-model="crear_cliente.parroquia">
                                <vs-select-item :key="index" :value="item.id_parroquia" :text="item.nombre_parroquia" v-for="(item, index) in parroquia_menu"/>
                            </vs-select>
                            <div v-show="error" v-if="!crear_cliente.parroquia">
                                <div v-for="err in error_cliente.crear_cliente.parroquia" :key="err" v-text="err" class="text-danger"></div>
                            </div>
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
                            <div v-show="error" v-if="!crear_cliente.e_mail">
                                <div v-for="err in error_cliente.crear_cliente.e_mail" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Teléfono" v-model="crear_cliente.telefono"/>
                            <div v-show="error" v-if="!crear_cliente.telefono">
                                <div v-for="err in error_cliente.crear_cliente.telefono" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 sm:w-1/2 w-full relative mb-6">
                            <vs-input class="w-full" label="Contacto" v-model="crear_cliente.contacto"/>
                            <div v-show="error" v-if="!crear_cliente.contacto">
                                <div v-for="err in error_cliente.crear_cliente.contacto" :key="err" v-text="err" class="text-danger"></div>
                            </div>
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
                            <div v-show="error" v-if="!crear_cliente.estado">
                                <div v-for="err in error_cliente.crear_cliente.estado" :key="err" v-text="err" class="text-danger"></div>
                            </div>
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
                            <vs-button color="danger" type="filled" @click="modal.abrir=false">CANCELAR</vs-button>
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
                        <tbody v-if="modalcontable.tipo==1">
                            <tr v-for="(tr,index) in plan_cuenta.lista" :key="index" @click="escoger_plan_cuenta(tr)" class="tablavista">
                                <td>{{ tr.codcta }}</td>
                                <td>{{ tr.nomcta }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else-if="modalcontable.tipo==2">
                            <tr v-for="(tr,index) in plan_cuenta.lista" :key="index" @click="escoger_plan_cuenta_pagos(tr)" class="tablavista">
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
                id_factura:'',
                respuesta:'',
                orden_compra:'',
                migo:'',
                fecha_emision:moment().format('YYYY-MM-DD'),
                ambiente:'',
                tipo_emision:'Emision Normal',
                clave_acceso:'Generando Clave de acceso',
                observacion:'',
                proyectos:null,
                forma_pago:'',
                vendedor:null,
            },
            cliente:{
                tipo:false,
                busqueda:'',
                clientes:[],
                id_cliente:null,
                nombre:'',
                telefono:'',
                email:'',
                tipo_identificacion:'',
                identificacion:'',
                direccion:'',
                id_plan_seguro:null,
            },
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
                { text: "Cédula de Identidad", value: "Cédula de Identidad" },
                { text: "Ruc", value: "Ruc" },
                { text: "Pasaporte", value: "Pasaporte" },
                { text: "Consumidor Final", value: "Consumidor Final" }
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
            disabled_button:false,
            // variables cuotas extras
            cuotas_extras:{
                estado:false,
                index:null,
                datos:[
                    {
                        fecha_pago:"",
                        valor_pago:"",
                        error_fecha:[],
                        error_valor:[]
                    }
                ]
            },
            ////////////////////
            // variables interes
                exist_interes:0,
                valor_interes:0,
                interes_anual:0,
                periodo_pago_anual:"",
                tiempo_pago_anual:0,
                valida_interes:0,
            //
            proyectos_menu:[],
            empresa:[],
            formapagos:[],
            creditos:{
                estado:false,
                periodo:'',
                tiempo:1,
                plazos:3,
                monto:0,
                pago:0,
            },
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
                    cantidadrenta: null,
                    errorbase:[],
                }
            ],
            listretenciones: [],
            pagos:{
                estado:false,
                index:null,
                datos:[
                    {
                        metodo_pago:'',
                        banco_pago:null,
                        cantidad_pago:0,
                        nro_trans:'',
                        fecha_pago:'',
                        cuenta:'',
                        plan_cuenta:null,
                    }
                ]
            },
            bancos: [],
            totalef:0,
            propinapr:null,
            pp_descuento:1,
            error:{
                error:0,
                factura:{
                    fecha_emision:[],
                    vendedor:[],
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
            },
            error_cliente:{
                error:0,
                crear_cliente:{
                    nombre:[],
                    tipo_identificacion:[],
                    identificacion:[],
                    grupo_tributario:[],
                    direccion:[],
                    provincia:[],
                    canton:[],
                    parroquia:[],
                    e_mail:[],
                    telefono:[],
                    contacto:[],
                    estado:[],
                }
            },
            preloader:{
                cliente:false,
                productos:false,
            },
            listarvendedores:[],
            codigocliente:null,
            errorcodigocliente:[],
            anticipoexistente:0,
            timeout:null,
            recuperacion:false,

            //transportista
            guia:false,
            transportista: {
                id:null,
                nombre_transporte: "",
                tipo_identificacion_transporte: null,
                identificacion_transporte: "",
                fecha_inicio_transporte: moment().format('YYYY-MM-DD'),
                fecha_fin_transporte: "",
                placa_transporte: "",
                documento_aduanero: "",
                motivo_translado: "",
                clave_acceso:"",
            },
            errornombre_transporte: [],
            errortipo_identificacion_transporte: [],
            erroridentificacion_transporte: [],
            errorfecha_inicio_transporte: [],
            errorfecha_fin_transporte: [],
            errorplaca_transporte: [],
            modofactura: 0,
            anticipo_creado: 0,
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
            var subtotalice = 0;
            var valorice = 0;
            var subtotal12 = 0;
            var valor12 = 0;
            var subtotal0 = 0;
            var valor0 = 0;
            var no_impuesto = 0;
            var exento = 0;
            var descuento = 0;
            var total = 0;
            var propina = 0;
            if(this.usuario.id_empresa==55){
                this.producto.lista_productos.forEach(el => {
                    if (el.p_descuento == 1) {
                        subtotal += parseFloat(el.precio).toFixed(4) * el.cantidad - el.descuento;

                        if (el.total_ice) {subtotalice += parseFloat(el.precio).toFixed(4) * el.cantidad - el.descuento;}
                        if (el.total_ice) {valorice += el.total_ice * el.cantidad;}

                        if (el.iva == 2) {subtotal12 += (parseFloat(el.precio).toFixed(4) * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if (el.iva == 1) {subtotal0 += (parseFloat(el.precio).toFixed(4) * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if (el.iva == 3) {no_impuesto += (parseFloat(el.precio).toFixed(4) * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if (el.iva == 4) {exento += (parseFloat(el.precio).toFixed(4) * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if(isNaN(parseFloat(el.descuento))){
                            descuento += 0;
                        }else{
                            descuento += parseFloat(el.descuento);
                        }
                    } else {
                        subtotal += parseFloat(el.precio).toFixed(4) * el.cantidad - (el.cantidad * parseFloat(el.precio).toFixed(4) * el.descuento)/100;

                        if (el.total_ice) {subtotalice += parseFloat(el.precio).toFixed(4) * el.cantidad - (el.cantidad * parseFloat(el.precio).toFixed(4) * el.descuento)/100;}
                        if (el.total_ice) {valorice += el.total_ice * el.cantidad;}

                        if (el.iva == 2) {subtotal12 += (parseFloat(el.precio).toFixed(4) * el.cantidad - (el.cantidad * parseFloat(el.precio).toFixed(4) * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if (el.iva == 1) {subtotal0 += (parseFloat(el.precio).toFixed(4) * el.cantidad - (el.cantidad * parseFloat(el.precio).toFixed(4) * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if (el.iva == 3) {no_impuesto += (parseFloat(el.precio).toFixed(4) * el.cantidad - (el.cantidad * parseFloat(el.precio).toFixed(4) * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if (el.iva == 4) {exento += (parseFloat(el.precio).toFixed(4) * el.cantidad - (el.cantidad * parseFloat(el.precio).toFixed(4) * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if(isNaN((parseFloat(parseFloat(el.precio).toFixed(4)) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100)){
                            descuento += 0;
                        }else{
                            descuento += (parseFloat(parseFloat(el.precio).toFixed(4)) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100;
                        }
                    }
                    valor12 = subtotal12 * 0.12;
                });
                if(this.interes_monto>0){
                    total += subtotal + valor12 + valorice+parseFloat(this.interes_monto);
                }else{
                    total += subtotal + valor12 + valorice;
                }
                

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

                this.valorretenciones.forEach(el => {
                    el.baserenta = parseFloat(subtotal / this.valorretenciones.length).toFixed(4);
                    el.baseiva = parseFloat(valor12 / this.valorretenciones.length).toFixed(4);
                });

                return {
                    'subtotal': subtotal.toFixed(4),
                    'subtotalice': subtotalice.toFixed(4),
                    'valorice': valorice.toFixed(4),
                    'subtotal12': subtotal12.toFixed(4),
                    'valor12': valor12.toFixed(4),
                    'subtotal0': subtotal0.toFixed(4),
                    'valor0': valor0.toFixed(4),
                    'no_impuesto': no_impuesto.toFixed(4),
                    'exento': exento.toFixed(4),
                    'descuento': descuento.toFixed(4),
                    'total': total.toFixed(4)
                };
            }else{
                this.producto.lista_productos.forEach(el => {
                    if (el.p_descuento == 1) {
                        subtotal += el.precio * el.cantidad - el.descuento;

                        if (el.total_ice) {subtotalice += el.precio * el.cantidad - el.descuento;}
                        if (el.total_ice) {valorice += el.total_ice * el.cantidad;}

                        if (el.iva == 2) {subtotal12 += (el.precio * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if (el.iva == 1) {subtotal0 += (el.precio * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if (el.iva == 3) {no_impuesto += (el.precio * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if (el.iva == 4) {exento += (el.precio * el.cantidad - el.descuento) + (el.total_ice*el.cantidad);}
                        if(isNaN(parseFloat(el.descuento))){
                            descuento += 0;
                        }else{
                            descuento += parseFloat(el.descuento);
                        }
                    } else {
                        subtotal += el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100;

                        if (el.total_ice) {subtotalice += el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100;}
                        if (el.total_ice) {valorice += el.total_ice * el.cantidad;}

                        if (el.iva == 2) {subtotal12 += (el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if (el.iva == 1) {subtotal0 += (el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if (el.iva == 3) {no_impuesto += (el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if (el.iva == 4) {exento += (el.precio * el.cantidad - (el.cantidad * el.precio * el.descuento)/100) + (el.total_ice*el.cantidad);}
                        if(isNaN((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100)){
                            descuento += 0;
                        }else{
                            descuento += (parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100;
                        }
                    }
                    valor12 = subtotal12 * 0.12;
                });
                if(this.interes_monto>0){
                    total += subtotal + valor12 + valorice +parseFloat(this.interes_monto);
                }else{
                    total += subtotal + valor12 + valorice ;
                }

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

                this.valorretenciones.forEach(el => {
                    el.baserenta = parseFloat(subtotal / this.valorretenciones.length).toFixed(2);
                    el.baseiva = parseFloat(valor12 / this.valorretenciones.length).toFixed(2);
                });

                return {
                    'subtotal': subtotal.toFixed(2),
                    'subtotalice': subtotalice.toFixed(2),
                    'valorice': valorice.toFixed(2),
                    'subtotal12': subtotal12.toFixed(2),
                    'valor12': valor12.toFixed(2),
                    'subtotal0': subtotal0.toFixed(2),
                    'valor0': valor0.toFixed(2),
                    'no_impuesto': no_impuesto.toFixed(2),
                    'exento': exento.toFixed(2),
                    'descuento': descuento.toFixed(2),
                    'total': total.toFixed(2)
                };
            }
            
        },
        pagoletra(){
            var res = 0;
            
            if((this.exist_interes==1 || this.exist_interes=="1") && this.interes_monto>0){
                var res = Number(this.interes_pago_letra); 
            }else{
                var res = this.creditos.monto / this.creditos.plazos;
            }
            if(this.usuario.id_empresa==59){
                return res.toFixed(4);
            }else{
                return res.toFixed(2);
            }
            
        },
        capital_monto(){
            var total=0;
            console.log("Interes Monto");
            console.log(this.interes_monto);
            if(this.interes_monto>0){
                total=Number(this.creditos.monto);
            }
            return total.toFixed(2);
        },
        interes_monto(){
            var total=0;
            if(this.exist_interes==1 || this.exist_interes=="1"){
                if(this.creditos.periodo){
                    
                    var interes_a=0;
                    var porcentaje_anual=0;
                    if(this.interes_anual>0){
                        interes_a=Number((this.interes_anual/360).toFixed(2));
                        porcentaje_anual=Number(this.tiempo_pago_anual)*Number(interes_a);
                    }
                    if(this.periodo_pago_anual=='Dias'){
                        if(this.creditos.periodo=='Dias'){
                            //var n1_t=Number(this.creditos.tiempo);
                            var n1_t=Number(this.creditos.plazos);
                            var n2_t=Number(this.tiempo_pago_anual);
                            if(n1_t>=n2_t){
                                var tiempo_dias=this.creditos.plazos;
                                //var tiempo_dias=this.creditos.tiempo;
                                // if(tiempo_dias>=1 && tiempo_dias<=30){
                                //     tiempo_dias=1;
                                // }
                                // if(tiempo_dias>=31 && tiempo_dias<=60){
                                //     tiempo_dias=2;
                                // }
                                // if(tiempo_dias>=61 && tiempo_dias<=90){
                                //     tiempo_dias=3;
                                // }
                                // if(tiempo_dias>=91 && tiempo_dias<=120){
                                //     tiempo_dias=4;
                                // }
                                // if(tiempo_dias>=121 && tiempo_dias<=150){
                                //     tiempo_dias=5;
                                // }
                                // if(tiempo_dias>=151 && tiempo_dias<=180){
                                //     tiempo_dias=6;
                                // }
                                // if(tiempo_dias>=181 && tiempo_dias<=210){
                                //     tiempo_dias=7;
                                // }
                                // if(tiempo_dias>=211 && tiempo_dias<=240){
                                //     tiempo_dias=8;
                                // }
                                // if(tiempo_dias>=241 && tiempo_dias<=270){
                                //     tiempo_dias=9;
                                // }
                                this.listar_interes(1,tiempo_dias);
                                console.log("Interes Saldo Dia:"+this.creditos.monto);
                                console.log("Interes Plazo Dia:"+this.creditos.plazos);
                                if(this.valor_interes>0){
                                    //total=this.creditos.monto*((this.creditos.tiempo*tiempo_dias/this.valor_interes)/100);
                                    total=this.creditos.monto*((this.creditos.plazos*tiempo_dias/this.valor_interes)/100);
                                    //total=this.creditos.monto*(porcentaje_anual/100);
                                }
                            }else{
                                this.$vs.notify({
                                    text: "El Tiempo Pago del Interes debe ser mayor",
                                    color: "danger"
                                });
                            }  
                        }
                        if(this.creditos.periodo=='Semanas'){
                            var tiempo_semana=0;
                            //tiempo_semana=this.creditos.tiempo*7;
                            tiempo_semana=this.creditos.plazos*7;
                            var n1_t=Number(tiempo_semana);
                            var n2_t=Number(this.tiempo_pago_anual);
                            if(tiempo_semana>=this.tiempo_pago_anual){
                                var tiempo_semana_2=tiempo_semana;
                                // if(tiempo_semana_2>=1 && tiempo_semana_2<=30){
                                //     tiempo_semana_2=1;
                                // }
                                // if(tiempo_semana_2>=31 && tiempo_semana_2<=60){
                                //     tiempo_semana_2=2;
                                // }
                                // if(tiempo_semana_2>=61 && tiempo_semana_2<=90){
                                //     tiempo_semana_2=3;
                                // }
                                // if(tiempo_semana_2>=91 && tiempo_semana_2<=120){
                                //     tiempo_semana_2=4;
                                // }
                                // if(tiempo_semana_2>=121 && tiempo_semana_2<=150){
                                //     tiempo_semana_2=5;
                                // }
                                // if(tiempo_semana_2>=151 && tiempo_semana_2<=180){
                                //     tiempo_semana_2=6;
                                // }
                                // if(tiempo_semana_2>=181 && tiempo_semana_2<=210){
                                //     tiempo_semana_2=7;
                                // }
                                // if(tiempo_semana_2>=211 && tiempo_semana_2<=240){
                                //     tiempo_semana_2=8;
                                // }
                                // if(tiempo_semana_2>=241 && tiempo_semana_2<=270){
                                //     tiempo_semana_2=9;
                                // }
                                this.listar_interes(1,tiempo_semana_2);
                                console.log("Interes Saldo Semana:"+this.creditos.monto);
                                console.log("Interes Plazo Semana:"+this.creditos.plazos);
                                if(this.valor_interes>0){
                                    total=this.creditos.monto*((this.creditos.plazos*7*tiempo_semana_2/this.valor_interes)/100);
                                    //total=this.creditos.monto*((this.creditos.tiempo*7*tiempo_semana_2/this.valor_interes)/100);
                                    //total=this.creditos.monto*(porcentaje_anual/100);
                                }else{
                                    this.$vs.notify({
                                        text: "El Tiempo Pago del Interes debe ser mayor",
                                        color: "danger"
                                    });
                                }
                            }

                        }
                        if(this.creditos.periodo=='Meses'){
                            var tiempo_mes=0;
                            //tiempo_mes=this.creditos.tiempo*30;
                            tiempo_mes=this.creditos.plazos*30;
                            console.log("Entra Meses Anual Dias");
                            var n1_t=Number(tiempo_mes);
                            var n2_t=Number(this.tiempo_pago_anual);
                            if(tiempo_mes>=this.tiempo_pago_anual){
                                //this.listar_interes(1,this.creditos.tiempo);
                                this.listar_interes(1,this.creditos.plazos);
                                console.log("Interes Saldo Semana:"+this.creditos.monto);
                                console.log("Interes Plazo Semana:"+this.creditos.plazos);
                                if(this.valor_interes>0){
                                    total=this.creditos.monto*((this.valor_interes)/100);
                                    
                                }else{
                                    this.$vs.notify({
                                        text: "El Tiempo Pago del Interes debe ser mayor",
                                        color: "danger"
                                    });
                                }
                            }
                        }
                    }
                    if(this.periodo_pago_anual=='Meses'){
                        if(this.creditos.periodo=='Dias'){
                            //var tiempo_dias0=(this.creditos.tiempo*this.tiempo_pago_anual)/(this.tiempo_pago_anual*30);
                            var tiempo_dias0=(this.creditos.plazos*this.tiempo_pago_anual)/(this.tiempo_pago_anual*30);
                            var n1_t=Number(tiempo_dias0);
                            var n2_t=Number(this.tiempo_pago_anual);
                            if(n1_t>=n2_t){
                                var tiempo_dias1=Math.trunc(tiempo_dias0); 
                                this.listar_interes(1,tiempo_dias1);
                                console.log("Interes Saldo Dia:"+this.creditos.monto);
                                console.log("Interes Plazo Dia:"+this.creditos.plazos);
                                if(this.valor_interes>0){
                                    total=this.creditos.monto*((this.creditos.plazos*tiempo_dias1/this.valor_interes)/100);
                                    //total=this.creditos.monto*((this.creditos.tiempo*tiempo_dias1/this.valor_interes)/100);
                                    
                                }
                            }else{
                                this.$vs.notify({
                                    text: "El Tiempo Pago del Interes debe ser mayor",
                                    color: "danger"
                                });
                            }  
                        }
                        if(this.creditos.periodo=='Semanas'){
                            //var tiempo_semana0=(this.creditos.tiempo*7*this.tiempo_pago_anual)/(this.tiempo_pago_anual*30);
                            var tiempo_semana0=(this.creditos.plazos*7*this.tiempo_pago_anual)/(this.tiempo_pago_anual*30);
                            var n1_t=Number(tiempo_semana0);
                            var n2_t=Number(this.tiempo_pago_anual);
                            if(n1_t>=n2_t){
                                var tiempo_semana1=Math.trunc(tiempo_semana0);
                                this.listar_interes(1,tiempo_semana1);
                                console.log("Interes Saldo Dia:"+this.creditos.monto);
                                console.log("Interes Plazo Dia:"+this.creditos.plazos);
                                if(this.valor_interes>0){
                                    total=this.creditos.monto*((this.creditos.plazos*7*tiempo_semana1/this.valor_interes)/100);
                                    //total=this.creditos.monto*((this.creditos.tiempo*7*tiempo_semana1/this.valor_interes)/100);
                                }
                            }else{
                                this.$vs.notify({
                                    text: "El Tiempo Pago del Interes debe ser mayor",
                                    color: "danger"
                                });
                            }  
                        }
						if(this.creditos.periodo=='Meses'){
                            console.log("Entra Meses Anual Meses:Mes_Credito:"+this.creditos.tiempo+" Mes_Anual:"+this.tiempo_pago_anual);
                            //var n1_t=Number(this.creditos.tiempo);
                            var n1_t=Number(this.creditos.plazos);
                            var n2_t=Number(this.tiempo_pago_anual);

                            if(n1_t>=n2_t){
                                //this.listar_interes(1,this.creditos.tiempo);
                                this.listar_interes(1,this.creditos.plazos);
                                console.log("Interes Saldo Dia:"+this.creditos.monto);
                                console.log("Interes Plazo Dia:"+this.creditos.plazos);
                                if(this.valor_interes>0){
                                    total=this.creditos.monto*(this.valor_interes/100);
                                    //total=this.creditos.monto*(this.valor_interes/100);
                                }
                            }else{
                                this.$vs.notify({
                                    text: "El Tiempo Pago del Interes debe ser mayor",
                                    color: "danger"
                                });
                            }  
                        }
                    }
                    
                }
                
                
            }
            return total.toFixed(2);
        },
        total_interes_monto(){
            var total=0;
            if(this.interes_monto>0){
                total=Number(this.interes_monto)+Number(this.capital_monto);
            }
            return total.toFixed(2);
        },
        total_interes_saldo(){
            var total=0;
            var total2=0;
            if(this.total_interes_monto>0){
                total=Number(this.total_interes_monto);
            }
            //total2=Number(total)-Number(this.pagoletra);
            total2=total;
            return total2.toFixed(2);
        },
        interes_pago_letra(){
            var total=0;
            var total2=0;
            if(this.total_interes_monto>0){
                total=Number(this.total_interes_monto)/ Number(this.creditos.plazos);
            }
            //total2=Number(total)-Number(this.pagoletra);
            total2=total;
            return total2.toFixed(2);
        },
        total_pendiente(){
            var total = 0;
            var retencion = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;
            var cuotas = 0;

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
            this.cuotas_extras.datos.forEach(el => {
                if(parseFloat(el.valor_pago)>0){
                    cuotas+=parseFloat(el.valor_pago)
                }
            });
            if(this.creditos.monto<=0){
                creditos = 0;
            }else{
                creditos = this.creditos.monto;
            }
            if(isNaN(retencion)){
                retencion = 0;
            }
            if(isNaN(creditos)){
                creditos = 0;
            }
            if(isNaN(pagas)){
                pagas = 0;
            }
            if(this.interes_monto>0){
                total = parseFloat(this.formulas.total) - parseFloat(retencion) - parseFloat(creditos) - parseFloat(pagas)-parseFloat(cuotas)-parseFloat(this.interes_monto);
            }else{
                total = parseFloat(this.formulas.total) - parseFloat(retencion) - parseFloat(creditos) - parseFloat(pagas)-parseFloat(cuotas);
            }
            
            if(parseFloat(total)<0.01 && parseFloat(total)>=-0.02){
                total = 0;
            }
            if(this.usuario.id_empresa==55){
                return total.toFixed(4);
            }else{
                return total.toFixed(2);
            }
        },
        total_pagado(){
            var total = 0;
            var retencion = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;
            var cuotas = 0;
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
            this.cuotas_extras.datos.forEach(el =>{
                if(parseFloat(el.valor_pago)>0){
                    cuotas+=parseFloat(el.valor_pago);
                }
            });
            if(this.creditos.estado){
                if(this.creditos.monto<=0){
                    creditos = 0;
                }else{
                    creditos = parseFloat(this.creditos.monto);
                }
            }
            if(this.interes_monto>0){
                total = parseFloat(retencion) + parseFloat(creditos) + parseFloat(pagas)+ parseFloat(cuotas)+parseFloat(this.interes_monto);
            }else{
                total = parseFloat(retencion) + parseFloat(creditos) + parseFloat(pagas)+ parseFloat(cuotas);
            }
            
            if(parseFloat(total)<0.01){
                total = 0;
            }
            if(this.usuario.id_empresa==59){
                return parseFloat(total).toFixed(4);
            }else{
                return parseFloat(total).toFixed(2);
            }
        }
        
    },
    methods: {
        listar_cliente(buscar){
            this.preloader.cliente=false;
            axios.get('/api/notacredito/listar_cliente?buscar=' + buscar + '&empresa=' + this.usuario.id_empresa).then( ({data}) => {
                this.cliente.clientes = data;
                $(".busqueda_cliente_ls").show();
                setTimeout(() => {
                    this.preloader.cliente=true;
                }, 100);
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
                axios.post('/api/factura_venta/listar_productos',{
                    buscar: buscar,
                    id_empresa: this.usuario.id_empresa,
                    id_establecimiento: this.usuario.id_establecimiento,
                    cliente: this.cliente.id_cliente,
                    id_pto_emision:this.usuario.id_punto_emision 
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
            }, 1000);
        },
        validarcantidad(tr, index){
            //console.log("validar cantidad");
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                if(tr.cantidadreal == 0 && tr.sector==1){
                    this.producto.lista_productos[index].cantidad = 0;
                    this.$vs.notify({
                        time: 5000,
                        title: "Error en cantidad",
                        text: "el producto " + tr.nombre + " no tiene unidades existentes en bodega",
                        color: "danger"
                    });
                }else if(this.empresa.negativo==0 && tr.sector==1){
                    console.log("validar cantidad");
                    if(parseFloat(tr.cantidad)>parseFloat(tr.cantidadreal)){
                        if(tr.cantidadreal>=1){
                            this.producto.lista_productos[index].cantidad = this.producto.lista_productos[index].cantidadreal;
                            this.$vs.notify({
                                time: 5000,
                                title: "Error en cantidad",
                                text: "La cantidad ingresada excede la cantidad existente del producto " + tr.nombre + ". <br> Cantidad máxima " + tr.cantidadreal,
                                color: "warning"
                            });
                        }else{
                            this.producto.lista_productos[index].cantidad = 0;
                            this.$vs.notify({
                                time: 5000,
                                title: "Error en cantidad",
                                text: "el producto " + tr.nombre + " no tiene unidades existentes en bodega",
                                color: "warning"
                            });
                        }
                    }
                }
            }, 300);
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
            this.cliente.id_plan_seguro = null;
            this.cliente.id_cliente = tr.id_cliente;
            this.cliente.nombre = tr.nombre;
            this.cliente.telefono = tr.telefono;
            this.cliente.email = tr.email;
            this.cliente.tipo_identificacion = tr.tipo_identificacion;
            this.cliente.identificacion = tr.identificacion;
            this.cliente.direccion = tr.direccion;
            this.cliente.id_plan_seguro = tr.id_plan_seguro;
            this.anticipover(tr.id_cliente);
        },
        anticipover(id){
            axios.get("/api/anticipototal?id="+id).then( ({data}) => {
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
                nombreice: tr.nombreice,
                total_ice: tr.total_ice,
                proyecto: this.proyectos_menu[0].id_proyecto,
                id_bodega_prod:tr.id_bodega,
                id_plan_seguro:tr.id_plan_seguro,
                descuento_seguro:tr.descuento_seguro
            });
            $(".focuspr").focus();
        },
        escoger_plan_cuenta(tr){
           this.crear_cliente.cuenta_contable = tr.codcta;
           this.crear_cliente.id_cuenta_contable = tr.id_plan_cuentas;
           this.modalcontable.abrir = false;
        },
        escoger_plan_cuenta_pagos(tr){
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
            }
        },
        abrir_plan_cuentas(){
            this.modalcontable = {
                abrir: true,
                titulo: "Crear Cliente",
                tipo: 1,
            }
        },
        abrir_plan_cuentas_pagos(index){
            this.modalcontable = {
                abrir: true,
                titulo: "Escoger plan de cuenta",
                tipo: 2,
            }
            this.pagos.index = index;
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
            if(this.validar_crear_cliente()){return;}
            axios.post('/api/notacredito/guardar_cliente', {
                codigocliente:this.codigocliente,
                cliente:this.crear_cliente,
                empresa: this.usuario.id_empresa
            }).then( ({data}) => {
                if(data!="error_identificacion"){
                    this.$vs.notify({
                        time: 8000,
                        title: "Cliente guardado",
                        text: "El cliente se guardo exitosamente",
                        color: "success"
                    });
                    this.modal.abrir = false;
                    this.cliente.busqueda = '';
                }else{
                    this.$vs.notify({
                        time: 8000,
                        title: "Cliente existente",
                        text: "El cliente ya existe",
                        color: "success"
                    });
                }
            }).catch( error => {
                console.log(error);
            });
        },
        leercodigo() {
            axios.get("/api/verificarcliente/" + this.usuario.id_empresa).then(({data}) => {
                if (data == "vacio") {
                    this.codigocliente = null;
                } else {
                    this.codigocliente = data;
                }
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
            if(!this.creditos.estado){
                this.creditos = {
                    estado:false,
                    periodo:'',
                    tiempo:1,
                    plazos:3,
                    monto:0,
                    pago:0,
                };
            }
            this.creditos.monto = parseFloat(0);
            if(this.total_pendiente<=0 || this.total_pendiente === undefined){
                this.creditos.monto = 0;
            }else{
                this.creditos.monto = parseFloat(this.total_pendiente).toFixed(2);
            }
            if(this.creditos.estado){
                this.creditos.monto = 0;
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
                el.baseiva = parseFloat(this.formulas.valor12 / this.valorretenciones.length).toFixed(2);
            });
        },
        listarretenciones() {
            var url = "/api/listarretenciones?empresa="+this.usuario.id_empresa;
            axios.get(url).then(({data}) => {
                this.listretenciones = data;
            });
        },
        listarvendedor(){
            axios.get("/api/factura/vervendedor?empresa="+this.usuario.id).then(({data}) => {
                this.listarvendedores = data;
            });
        },

        //retenciones iva y renta
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
                this.agregarretencioniva(index);
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
                this.agregarretencionrenta(index);
            }
        },
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
                this.valorescalculodiv();
            }
        },
        eliminararrayretencion(id) {
            this.valorretenciones.splice(id, 1);
            this.valorescalculodiv();
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
                el.cantidad_pago = 0;
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
                this.pagos.datos[0].cantidad_pago = parseFloat(this.total_pendiente).toFixed(2);
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
                errormetodo:[],
                errorcantidad:[],
            });
        },
        validar(){
            this.error = {
                error:0,
                factura:{
                    fecha_emision:[],
                    vendedor:[]
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
            }
            if(!this.factura.vendedor){
                this.error.factura.vendedor.push("Debe agregar el vendedor obligatorio");
                this.error.error=1;
            }
            if(!this.cliente.tipo){
                this.error.cliente.tipo.push("Debe agregar un cliente al comprobante");
                this.error.error=1;

            }
            if(!this.producto.tipo){
                this.error.producto.busqueda.push("Debe agregar un producto al comprobante");
                this.error.error=1;

            }

            for (var i = 0; i < this.producto.lista_productos.length; i++) {
                this.producto.lista_productos[i].errorcantidad = [];
                this.producto.lista_productos[i].errorprecio = [];
                this.producto.lista_productos[i].errorproyecto = [];
                if(this.cliente.id_plan_seguro!==null && this.cliente.id_plan_seguro>0){
                    
                    if(this.producto.lista_productos[i].descuento_seguro==null){
                        this.$vs.notify({
                            title:"Error Plan Seguro",
                            text: "Hay productos que no pertecen al plan seguro",
                            color: "danger"
                        });
                        console.log("Hay productos que no pertecen al plan seguro "+this.producto.lista_productos[i].cod_principal);
                        this.error.error = 1;
                    }
                    if(parseFloat(this.producto.lista_productos[i].descuento)>0){
                        if(parseFloat(this.producto.lista_productos[i].descuento_seguro)>0){
                             var porc_desc_pln=parseFloat(Number(this.producto.lista_productos[i].precio*(this.producto.lista_productos[i].descuento_seguro/100)).toFixed(2));
                             console.log(porc_desc_pln);
                            if(this.producto.lista_productos[i].p_descuento==1){
                                if(parseFloat(this.producto.lista_productos[i].descuento)>porc_desc_pln){
                                    this.$vs.notify({
                                        title:"Error Descuento Plan Seguro",
                                        text: "Hay productos que sobre pasan el descuento",
                                        color: "danger"
                                    });
                                    console.log("Hay productos que sobre pasan el descuento"+this.producto.lista_productos[i].cod_principal);
                                    this.error.error = 1;
                                }
                            }else{
                                if(parseFloat(this.producto.lista_productos[i].descuento)>parseFloat(this.producto.lista_productos[i].descuento_seguro)){
                                    this.$vs.notify({
                                        title:"Error Descuento Plan Seguro",
                                        text: "Hay productos que sobrepasan el descuento",
                                        color: "danger"
                                    });
                                    console.log("Hay productos que sobrepasan el descuento"+this.producto.lista_productos[i].cod_principal);
                                    this.error.error = 1;
                                }
                            }
                        }
                    }
                    
                }

                if (!this.producto.lista_productos[i].cantidad ) {
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

                }
                if(!this.creditos.tiempo){
                    this.error.creditos.tiempo.push("Obligatorio");
                    this.error.error=1;

                }
                if(!this.creditos.plazos){
                    this.error.creditos.plazos.push("Obligatorio");
                    this.error.error=1;

                }
                if(parseFloat(this.creditos.monto)<=0){
                    this.error.creditos.monto.push("Obligatorio");
                    this.error.error=1;

                }
            }
            if(this.retenciones.estado){
                for (var i = 0; i < this.valorretenciones.length; i++) {
                    this.valorretenciones[i].errorbase = [];
                    if (this.valorretenciones[i].renta) {
                        if (parseFloat(this.valorretenciones[i].baserenta)<=0 || !this.valorretenciones[i].baserenta) {
                            this.valorretenciones[i].errorbase.push("Obligatorio");
                            this.error.error = 1;

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

                    }
                    if (parseFloat(this.pagos.datos[i].cantidad_pago)<=0) {
                        this.pagos.datos[i].errorcantidad.push("Obligatorio");
                        this.error.error = 1;

                    }
                }
            }

            if (this.guia) {
                if (!this.transportista.nombre_transporte) {
                    this.errornombre_transporte.push("Campo obligatorio");
                    this.error.error = 1;
                }
                if (!this.transportista.tipo_identificacion_transporte) {
                    this.errortipo_identificacion_transporte.push("Campo obligatorio");
                    this.error.error = 1;
                }
                if (!this.transportista.identificacion_transporte) {
                    this.erroridentificacion_transporte.push("Campo obligatorio");
                    this.error.error = 1;
                }
                if (!this.transportista.fecha_inicio_transporte) {
                    this.errorfecha_inicio_transporte.push("Campo obligatorio");
                    this.error.error = 1;
                }
                if (!this.transportista.fecha_fin_transporte) {
                    this.errorfecha_fin_transporte.push("Campo obligatorio");
                    this.error.error = 1;
                }
                if (!this.transportista.placa_transporte) {
                    this.errorplaca_transporte.push("Campo obligatorio");
                    this.error.error = 1;
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
        validar_crear_cliente(){
            this.error_cliente = {
                error:0,
                crear_cliente:{
                    nombre:[],
                    tipo_identificacion:[],
                    identificacion:[],
                    grupo_tributario:[],
                    direccion:[],
                    provincia:[],
                    canton:[],
                    parroquia:[],
                    e_mail:[],
                    telefono:[],
                    contacto:[],
                    estado:[],
                }
            }
            this.errorcodigocliente = [];

            if(!this.crear_cliente.nombre){
                this.error_cliente.crear_cliente.nombre.push("Debe ingresar nombres");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.tipo_identificacion){
                this.error_cliente.crear_cliente.tipo_identificacion.push("Ingrese tipo de identificación");
                this.error_cliente.error = 1;
            }

            if(this.crear_cliente.tipo_identificacion==1){
                if(this.crear_cliente.identificacion.length!=10){
                    this.error_cliente.crear_cliente.identificacion.push("Cédula Inválida");
                    this.error_cliente.error = 1;
                }
            }else if(this.crear_cliente.tipo_identificacion==2){
                if(this.crear_cliente.identificacion.length!=13){
                    this.error_cliente.crear_cliente.identificacion.push("Ruc Inválido");
                    this.error_cliente.error = 1;
                }
            } else{
                if(this.crear_cliente.identificacion.length<10){
                    this.error_cliente.crear_cliente.identificacion.push("Identificación Inválido");
                    this.error_cliente.error = 1;
                }
            }

            if(!this.crear_cliente.grupo_tributario){
                this.error_cliente.crear_cliente.grupo_tributario.push("Ingrese grupo trubutario");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.direccion){
                this.error_cliente.crear_cliente.direccion.push("Ingrese dirección");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.provincia){
                this.error_cliente.crear_cliente.provincia.push("Ingrese Provincia");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.canton){
                this.error_cliente.crear_cliente.canton.push("Ingrese Canton");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.parroquia){
                this.error_cliente.crear_cliente.parroquia.push("Ingrese Parroquia");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.e_mail){
                this.error_cliente.crear_cliente.e_mail.push("Ingrese el email");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.telefono){
                this.error_cliente.crear_cliente.telefono.push("Ingrese teléfono");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.contacto){
                this.error_cliente.crear_cliente.contacto.push("Ingrese contacto");
                this.error_cliente.error = 1;
            }
            if(!this.crear_cliente.estado){
                this.error_cliente.crear_cliente.estado.push("Ingrese estado");
                this.error_cliente.error = 1;
            }

            if(!this.crear_cliente.estado){
                this.error_cliente.crear_cliente.estado.push("Ingrese estado");
                this.error_cliente.error = 1;
            }
            if(!this.codigocliente){
                this.errorcodigocliente.push("Ingrese Código");
                this.error_cliente.error = 1;
            }

            return this.error_cliente.error;
        },
        //recuperar
        guardar_factura(){
            this.disabled_button=true;
            if(this.validar()){this.disabled_button=false;return;}
            var n1=Number(this.formulas.total);
            var n2=Number(this.total_pagado);
            console.log(this.total_pendiente+" total_pendiente"+parseFloat(n1-n2).toFixed(2));
            
            if(parseFloat(n1-n2).toFixed(2)>0 || parseFloat(n1-n2).toFixed(2)<0){
                this.$vs.notify({
                    time: 8000,
                    title: "No se puede Guardar la factura",
                    text: "Todavía esiste un saldo pendiente de $ "+parseFloat(n1-n2).toFixed(2),
                    color: "danger"
                });
                this.disabled_button=false;
                return;
                
            }
            if(this.total_pendiente>0){
                this.$vs.notify({
                    time: 8000,
                    title: "No se puede Guardar la factura",
                    text: "Todavía esiste un saldo pendiente de $ "+this.total_pendiente,
                    color: "danger"
                });
                 this.disabled_button=false;
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
                    this.disabled_button=false;
                    return;
                }
            }
            if(this.modofactura == 0){
                axios.post('/api/nota_venta/guardar_nota_venta_clave', {factura:this.factura, guia: this.guia,transportista:this.transportista}).then(({data}) => {
                    if(data.factura == "repetido"){
                        var url = "/api/listarclave_nota_venta/" + this.usuario.id;
                        axios.get(url).then(res => {
                            var fecha = moment(this.factura.fecha_emision).format("DDMMYYYY");
                            var rec = res.data.recupera[0];
                            var secuencial = this.zeroFill(res.data.secuencial, 9);
                            var establecimiento = this.zeroFill(rec.establecimiento, 3);
                            var punto_emision = this.zeroFill(rec.punto_emision, 3);
                            var codigoacc = fecha+"01"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                            var acceso = this.Modulo11(codigoacc);
                            this.factura.clave_acceso = codigoacc + acceso;
                        });
                    }
                    if(this.guia){
                        if(data.guia == "repetido"){
                            var url = "/api/listarclave_guia_nota_venta/" + this.usuario.id;
                            axios.get(url).then(res => {
                                var fecha = moment(this.transportista.fecha_inicio_transporte).format("DDMMYYYY");
                                var rec = res.data.recupera[0];
                                var secuencial = this.zeroFill(res.data.secuencial, 9);
                                var establecimiento = this.zeroFill(rec.establecimiento, 3);
                                var punto_emision = this.zeroFill(rec.punto_emision, 3);
                                var codigoacc = fecha+"06"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                                var acceso = this.Modulo11(codigoacc);
                                this.transportista.clave_acceso = codigoacc + acceso;
                            });
                        }
                    }
                    axios.put('/api/nota_venta/editar_nota_venta', {
                        tipo_editar:"proforma",
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
                        modo:this.modofactura ,
                        // transportista
                        guia: this.guia,
                        transportista: this.transportista,
                        // tabla interes
                        interes_monto:this.interes_monto,
                        total_interes_saldo:this.total_interes_saldo,
                        exist_interes:this.exist_interes,
                        pagoletra:this.pagoletra,
                        capital_monto:this.capital_monto,
                        //cuotas extras
                        cuota_extra:this.cuotas_extras,
                    }).then( ({data}) => {
                        if(data=='existe clave factura'){
                                this.$vs.notify({
                                    text: "Ya existe esta clave de acceso en Nota Venta",
                                    color: "danger"
                                });
                                return;
                        }
                        console.log("Exito");
                        //     this.$vs.notify({
                        //         title: "Registro Actualizado",
                        //         text: "Este registro se actualizo exitosamente",
                        //         color: "success"
                        //     });
                        //     this.enviado();
                        //     return;
                        // if(data.guia=='Enviado'){
                        //     this.$vs.notify({
                        //         time: 8000,
                        //         title: "Registro Actualizado",
                        //         text: "Este registro se actualizo exitosamente",
                        //         color: "success"
                        //     });
                        //     this.enviado();
                        //     return;
                        // }
                        // if(data.factura=='Enviado'){
                        //     this.$vs.notify({
                        //         time: 8000,
                        //         title: "Registro Actualizado",
                        //         text: "Este registro se actualizo exitosamente",
                        //         color: "success"
                        //     });
                            if(this.guia){
                                if(data.guia!='Enviado'){
                                    this.$vs.notify({
                                        time: 8000,
                                        title: "Enviando Guia de Remisión",
                                        text: "La Guia de Remisión esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                                        color: "warning"
                                    });
                                    this.enviarguia(data);
                                }else{
                                    this.enviado();
                                }
                            }else{
                                this.enviado();
                            }
                             //return;
                        // }else{
                        //     if(this.factura.respuesta!='Enviado'){
                        //         this.$vs.notify({
                        //             time: 8000,
                        //             title: "Enviando Factura",
                        //             text: "La factura esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                        //             color: "warning"
                        //         });
                        //         var recupera_factura = data.factura[0];
                        //         axios.post('/api/factura/xml_factura', recupera_factura).then(res => {
                        //             var password = recupera_factura.pass_firma;
                        //             var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + recupera_factura.firma;
                        //             var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/" + this.factura.clave_acceso +".xml";
                        //             var tipo = "factura_venta";
                        //             var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/";
                        //             var fecha_actual = moment(recupera_factura.fecha_autorizacion).format('LL');
                        //             this.crearfacturacion(firma, password, factura, tipo, this.usuario, recupera_factura.id_factura, carpeta, fecha_actual, recupera_factura.valor_total, recupera_factura.logo, recupera_factura.nombre_empresa);
                        //         });
                        //     }else{
                        //         this.$vs.notify({
                        //             title: "Factura actualizada",
                        //             text: "La factura fue actualizada exitosamente",
                        //             color: "success"
                        //         });
                        //         this.enviado();
                        //     }
                        // }
                        // if(this.guia){
                        //     if(data.guia!='Enviado'){
                        //         this.$vs.notify({
                        //             time: 8000,
                        //             title: "Enviando Guia de Remisión",
                        //             text: "La Guia de Remisión esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                        //             color: "warning"
                        //         });
                        //         this.enviarguia(data);
                        //     }else{
                        //         this.enviado();
                        //     }
                        // }
                    }).catch( error => {
                        this.enviado();
                        this.$vs.notify({
                            time: 8000,
                            title: "Error en el envio al SRI",
                            text: 'La Guia no pudo ser enviada, intente mas tarde',
                            color: "danger"
                        });
                    });
                }).catch( error => {
                    this.enviado();
                    this.$vs.notify({
                        time: 8000,
                        title: "Error en el registro",
                        text: 'El sistema tiene problemas de iniciar, intente mas tarde',
                        color: "danger"
                    });
                });
            }else{
                axios.put('/api/nota_venta/editar_nota_venta', {
                    tipo_editar:"proforma",
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
                    modo:this.modofactura,
                    anticipo_creado:this.anticipo_creado,

                    // transportista
                    guia: this.guia,
                    transportista: this.transportista,
                    // tabla interes
                    interes_monto:this.interes_monto,
                    total_interes_saldo:this.total_interes_saldo,
                    exist_interes:this.exist_interes,
                    pagoletra:this.pagoletra,
                    capital_monto:this.capital_monto,
                    //cuotas extras
                    cuota_extra:this.cuotas_extras,
                }).then( ({data}) => {
                    if(data=='existe clave factura'){
                        this.$vs.notify({
                            text: "Ya existe esta clave de acceso en Nota Venta",
                            color: "danger"
                        });
                        return;
                    }
                    console.log("Exito 2");
                            
                            //return;
                    // if(data.factura=='Enviado' && data.guia=='Enviado'){
                    //     this.$vs.notify({
                    //         time: 8000,
                    //         title: "Registro Actualizado",
                    //         text: "Este registro se actualizo exitosamente",
                    //         color: "success"
                    //     });
                    //     this.enviado();
                    //     return;
                    // }
                    // if(data.factura=='Enviado'){
                    //     this.$vs.notify({
                    //         time: 8000,
                    //         title: "Registro Actualizado",
                    //         text: "Este registro se actualizo exitosamente",
                    //         color: "success"
                    //     });
                        if(this.guia){
                            if(data.guia!='Enviado'){
                                this.$vs.notify({
                                    time: 8000,
                                    title: "Enviando Guia de Remisión",
                                    text: "La Guia de Remisión esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                                    color: "warning"
                                });
                                this.enviarguia(data);
                            }else{
                                this.$vs.notify({
                                    title: "Registro Actualizado",
                                    text: "Este registro se actualizo exitosamente",
                                    color: "success"
                                });
                                this.enviado();
                            }
                        }else{
                            this.$vs.notify({
                                title: "Registro Actualizado",
                                text: "Este registro se actualizo exitosamente",
                                color: "success"
                            });
                            this.enviado();
                        }
                        return;
                    // }else{
                    //     if(this.factura.respuesta!='Enviado'){
                    //         this.$vs.notify({
                    //             time: 8000,
                    //             title: "Enviando Factura",
                    //             text: "La factura esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                    //             color: "warning"
                    //         });
                    //         var recupera_factura = data.factura[0];
                    //         axios.post('/api/factura/xml_factura', recupera_factura).then(res => {
                    //             var password = recupera_factura.pass_firma;
                    //             var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + recupera_factura.firma;
                    //             var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/" + this.factura.clave_acceso +".xml";
                    //             var tipo = "factura_venta";
                    //             var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/factura/";
                    //             var fecha_actual = moment(recupera_factura.fecha_autorizacion).format('LL');
                    //             this.crearfacturacion(firma, password, factura, tipo, this.usuario, recupera_factura.id_factura, carpeta, fecha_actual, recupera_factura.valor_total, recupera_factura.logo, recupera_factura.nombre_empresa);
                    //         });
                    //     }else{
                    //         this.$vs.notify({
                    //             title: "Factura actualizada",
                    //             text: "La factura fue actualizada exitosamente",
                    //             color: "success"
                    //         });
                    //         this.enviado();
                    //     }
                    // }
                    // if(this.guia){
                    //     if(data.guia!='Enviado'){
                    //         this.$vs.notify({
                    //             time: 8000,
                    //             title: "Enviando Guia de Remisión",
                    //             text: "La Guia de Remisión esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                    //             color: "warning"
                    //         });
                    //         this.enviarguia(data);
                    //     }else{
                    //         this.enviado();
                    //     }
                    // }
                }).catch( error => {
                    this.enviado();
                    this.$vs.notify({
                        time: 8000,
                        title: "Error en el envio al SRI",
                        text: 'La Guia no pudo ser enviada, intente mas tarde',
                        color: "danger"
                    });
                });
            }
        },
        enviarguia(data){
            var datag = data.guia[0];
            var tipo_guia = "guia_remision_nota_venta";
            var urlxmlg = "/api/factura/xml_guia";
            axios.post(urlxmlg, datag).then(res => {
                var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + datag.firma;
                var password = datag.pass_firma;
                var ruta_factura_guia = DATA_EMPRESA +this.usuario.id_empresa +"/comprobantes/guia/" + datag.clave_acceso +".xml";
                var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/guia/";
                var fecha_actual = moment(datag.fecha_inicio_tr).format('LL');
                this.crearfacturacion_guia(firma, password, ruta_factura_guia, tipo_guia, this.usuario, datag.id_guia, carpeta, fecha_actual, '0.00', datag.logo, datag.nombre_empresa);
            });
        },
        recuperar(){
            axios.get('/api/nota_venta/recuperar/' + this.$route.params.id).then( ({data}) => {
                if(data.factura.modo == 1){
                    this.modofactura = 1;
                    if(data.pagos.length>=1){
                        this.pagos.estado=true;
                    }
                    if(data.creditos){
                        this.creditos.estado=true;
                        this.exist_interes=data.creditos.existe_interes;
                        console.log("EXIST INTERES:"+typeof this.exist_interes);

                    }
                    if((data.cuota_extra).length>0){
                        this.cuotas_extras.estado=true;
                        this.cuotas_extras.datos=[];
                        data.cuota_extra.forEach(el=>{
                            this.cuotas_extras.datos.push({
                                fecha_pago:el.fecha_pago,
                                valor_pago:el.valor_cuota,
                                id_cuota_extra:el.id_cuota_extra_nota_venta
                            });
                        });
                    }
                    if(data.iva.length>=1 || data.renta.length>=1){
                        this.retenciones.estado=true;
                    }
                    this.factura = {
                        id_factura:data.factura.id_nota_venta,
                        respuesta: data.factura.respuesta,
                        orden_compra:data.factura.orden_compra,
                        migo:data.factura.migo,
                        fecha_emision:data.factura.fecha_emision,
                        ambiente:data.factura.ambiente,
                        tipo_emision:data.factura.tipo_emision,
                        clave_acceso:data.factura.clave_acceso,
                        observacion:data.factura.observacion,
                        proyectos:data.factura.id_proyecto,
                        forma_pago:data.factura.id_forma_pagos,
                        vendedor:data.factura.id_vendedor,
                        respuesta_guia:data.factura.respuesta_guia
                    },
                    this.cliente = {
                        tipo:true,
                        busqueda:'',
                        clientes:[],
                        id_cliente:data.cliente.id_cliente,
                        nombre:data.cliente.nombre,
                        telefono:data.cliente.telefono,
                        email:data.cliente.email,
                        tipo_identificacion:data.cliente.tipo_identificacion,
                        identificacion:data.cliente.identificacion,
                        direccion:data.cliente.direccion,
                        id_plan_seguro:data.cliente.id_plan_seguro
                    }
                    this.anticipover(this.cliente.id_cliente);
                    if(this.usuario.id_empresa==55){
                        data.productos.forEach(el => {
                            this.producto.lista_productos.push({
                                id_producto_bodega:el.id_producto_bodega,
                                id_detalle: el.id_detalle,
                                id_producto: el.id_producto,
                                cod_alterno: el.cod_alterno,
                                cod_principal: el.cod_principal,
                                nombre: el.nombre,
                                cantidad: el.cantidad,
                                cantidadreal:el.cantidadreal,
                                precio: el.precio,
                                descuento: el.descuento,
                                p_descuento: el.p_descuento,
                                proyecto: el.id_proyecto,
                                subtotal: null,
                                iva: el.id_iva,
                                ice: el.id_ice,
                                sector: el.sector,
                                nombreice: el.nombreice,
                                total_ice: el.total_ice,
                                precio_sin_iva:el.valor_sin_iva,
                                id_bodega_prod:el.id_bodega,
                                id_plan_seguro:el.id_plan_seguro,
                                descuento_seguro:el.descuento_seguro
                            });
                        });
                    }else{
                        data.productos.forEach(el => {
                            this.producto.lista_productos.push({
                                id_producto_bodega:el.id_producto_bodega,
                                id_detalle: el.id_detalle,
                                id_producto: el.id_producto,
                                cod_alterno: el.cod_alterno,
                                cod_principal: el.cod_principal,
                                nombre: el.nombre,
                                cantidad: el.cantidad,
                                cantidadreal:el.cantidadreal,
                                precio: el.precio,
                                descuento: el.descuento,
                                p_descuento: el.p_descuento,
                                proyecto: el.id_proyecto,
                                subtotal: null,
                                iva: el.id_iva,
                                ice: el.id_ice,
                                sector: el.sector,
                                nombreice: el.nombreice,
                                total_ice: el.total_ice,
                                precio_sin_iva:el.valor_sin_iva,
                                id_bodega_prod:el.id_bodega,
                                id_plan_seguro:el.id_plan_seguro,
                                descuento_seguro:el.descuento_seguro

                            });
                        });
                    }
                    this.producto.tipo = true;
                    data.pagos.forEach(el => {
                        if(el.anticipo==1){
                            el.metodo_pago = "Anticipo";
                            this.anticipo_creado = this.anticipo_creado + parseFloat(el.cantidad_pago);
                        }
                    });
                    this.creditos = data.creditos;
                    this.pagos.datos = data.pagos;

                    data.iva.forEach((el,index) => {
                        if(this.valorretenciones.length<index+1){ this.valorretenciones.push({baseiva: null,iva: null,porcentajeiva: null,cantidadiva: null,renta: null,baserenta: null,porcentajerenta: null,cantidadrenta: null,errorbase:[]}); }
                        const valor = this.listretenciones.reduce((i, item, index) => item.id_retencion === el.id_retencion ? index : i, -1);
                        this.valorretenciones[index].iva = this.listretenciones[valor];
                        this.valorretenciones[index].cantidadiva = parseFloat(el.cantidadiva).toFixed(2);
                        this.valorretenciones[index].porcentajeiva = parseFloat(el.porcentajeiva).toFixed(2);
                        this.valorretenciones[index].baseiva = parseFloat((parseFloat(el.cantidadiva)*100)/parseFloat(el.porcentajeiva)).toFixed(2);
                    });
                    data.renta.forEach((el,index) => {
                        if(this.valorretenciones.length<index+1){ this.valorretenciones.push({baseiva: null,iva: null,porcentajeiva: null,cantidadiva: null,renta: null,baserenta: null,porcentajerenta: null,cantidadrenta: null,errorbase:[]}); }
                        const valor = this.listretenciones.reduce((i, item, index) => item.id_retencion === el.id_retencion ? index : i, -1);
                        this.valorretenciones[index].renta = this.listretenciones[valor];
                        this.valorretenciones[index].baserenta = parseFloat(el.baserenta).toFixed(2);;
                        this.valorretenciones[index].porcentajerenta  = parseFloat(el.porcentajerenta).toFixed(2);;
                        this.valorretenciones[index].cantidadrenta = parseFloat((el.baserenta*parseFloat(el.porcentajerenta))/100).toFixed(2);;
                    });
                    if(data.guia){
                        this.guia = true;
                        this.transportista = {
                            id: data.guia.id_guia,
                            nombre_transporte: data.guia.razon_social_tr,
                            tipo_identificacion_transporte: data.guia.tipo_identificacion_tr,
                            identificacion_transporte: data.guia.identificacion_tr,
                            fecha_inicio_transporte: data.guia.fecha_inicio_tr,
                            fecha_fin_transporte: data.guia.fecha_fin_tr,
                            placa_transporte: data.guia.placa_tr,
                            documento_aduanero: data.guia.doc_aduanero_tr,
                            motivo_translado: data.guia.motivo_translado_tr,
                            clave_acceso:data.guia.clave_acceso
                        };
                    }else{
                        this.listarclave_guia();
                    }
                }else if (data.factura.modo == 0){
                    this.modofactura = 0;
                    this.verificaproducto(data.productos);
                    // if(data.factura.clave_acceso == null){
                    //     this.listarclave();
                    // }
                    this.factura = {
                        id_factura:data.factura.id_factura,
                        respuesta: data.factura.respuesta,
                        orden_compra:data.factura.orden_compra,
                        migo:data.factura.migo,
                        fecha_emision:data.factura.fecha_emision,
                        ambiente:data.factura.ambiente,
                        tipo_emision:data.factura.tipo_emision,
                        clave_acceso:data.factura.clave_acceso,
                        observacion:data.factura.observacion,
                        proyectos:data.factura.id_proyecto,
                        forma_pago:data.factura.id_forma_pagos,
                        vendedor:data.factura.id_vendedor,
                    },
                    this.cliente = {
                        tipo:true,
                        busqueda:'',
                        clientes:[],
                        id_cliente:data.cliente.id_cliente,
                        nombre:data.cliente.nombre,
                        telefono:data.cliente.telefono,
                        email:data.cliente.email,
                        tipo_identificacion:data.cliente.tipo_identificacion,
                        identificacion:data.cliente.identificacion,
                        direccion:data.cliente.direccion,
                    }
                    this.anticipover(this.cliente.id_cliente);
                    this.producto.tipo = true;
                }
            }).catch( error => {
                console.log(error);
            });
        },
        verificaproducto(rec){
            axios.post('/api/factura_venta/verificaproducto', {
                usuario: this.usuario,
                productos: rec,
            }).then( ({data}) => {
                console.log(data);
                data.forEach(el => {
                    var subtotal = 0;
                    var descuento = 0;
                    if(el.sector==1 && (el.id_producto_bodega==="undefined" || el.id_producto_bodega == null)){
                        el.cantidad = 0;
                        el.id_producto_bodega = null;
                        el.nombrebodega = null;
                        el.cantidadreal = 0;
                    }
                    if(el.p_descuento == 1){
                        descuento = parseFloat(el.descuento);
                    }else{
                        descuento = (parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100;
                    }
                    if(isNaN(el.total_ice_f)){
                        el.total_ice_f = 0;
                    }
                    subtotal = (parseFloat(el.precio) * parseFloat(el.cantidad)) - descuento;
                    this.producto.lista_productos.push({
                        id_detalle: el.id_detalle,
                        id_producto_bodega: el.id_producto_bodega,
                        nombrebodega: el.nombrebodega,
                        id_producto: el.id_producto,
                        cod_alterno: el.cod_alterno,
                        cod_principal: el.cod_principal,
                        nombre: el.nombre,
                        cantidad: el.cantidad,
                        cantidadreal: el.cantidadreal,
                        precio: el.precio,
                        descuento: el.descuento_f,
                        p_descuento: el.p_descuento,
                        subtotal: subtotal,
                        iva: el.iva,
                        ice: el.ice,
                        sector: el.sector,
                        nombreice: el.nombreice,
                        total_ice: el.total_ice_f,
                        id_bodega_prod:el.id_bodega,
                        id_plan_seguro:el.id_plan_seguro,
                        descuento_seguro:el.descuento_seguro

                    });
                });
                this.producto.lista_productos.forEach(el => {
                    el.proyecto = this.proyectos_menu[0].id_proyecto;
                });
            });
        },
        //Facturación
        enviado(){
            this.$router.push("/facturacion/factura_acumulada");
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
                    time: 7000,
                    title: "Factura Enviada",
                    text:"La factura se generó exitosamente",
                    color: "success"
                });
                setTimeout(() => {
                    this.enviado();
                }, 7000);
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
        async crearfacturacion_guia(firma, password, factura, tipo, usuario, id_factura, carpeta, fecha, valor, logo, nombre_empresa){
            try {
                let {data:comprobante} = await script_comprobantes.obtener_comprobante_firmado.getAll({ factura:factura, id_factura:id_factura, tipo:tipo });
                let {resultado:contenido} = await script_comprobantes.lectura_firma.getAll({ firma:firma, id_factura:   id_factura, tipo:tipo });
                let {data:certificado} = await script_comprobantes.firmar_comprobante.getAll({ contenido:contenido[0], password:password, comprobante:comprobante, id_factura:id_factura, tipo:tipo });
                let {data:quefirma} = await script_comprobantes.verificar_firma.getAll({ comprobante:comprobante, mensaje:certificado, tipo:tipo, id_factura:id_factura, carpeta:carpeta });
                let {data:validado} = await script_comprobantes.validar_comprobante.getAll({ comprobante:comprobante, tipo:tipo, id_factura:id_factura, carpeta:carpeta, id_empresa:usuario.id_empresa });
                let {data:recibida} = await script_comprobantes.autorizar_comprobante.getAll({ comprobante:comprobante, validado:validado, usuario:usuario, tipo:tipo, id_factura:id_factura, carpeta:carpeta, fecha:fecha, valor:valor, logo:logo, nombre_empresa:nombre_empresa });
                let {data:registrado} = await script_comprobantes.autorizado_comprobante.getAll({ recibida:recibida, tipo:tipo, id_factura:id_factura });
                this.$vs.notify({
                    time: 7000,
                    title: "Guia de remisión Enviada",
                    text:"La Guia de remisión se generó exitosamente",
                    color: "success"
                });
                setTimeout(() => {
                    this.enviado();
                }, 7000);
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
        listarclave_guia(){
            var url = "/api/listarclave_guia/" + this.usuario.id;
            axios.get(url).then(res => {
                var fecha = moment(this.transportista.fecha_inicio_transporte).format("DDMMYYYY");
                var rec = res.data.recupera[0];
                var secuencial = this.zeroFill(res.data.secuencial, 9);
                var establecimiento = this.zeroFill(rec.establecimiento, 3);
                var punto_emision = this.zeroFill(rec.punto_emision, 3);
                var codigoacc = fecha+"06"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                var acceso = this.Modulo11(codigoacc);
                this.transportista.clave_acceso = codigoacc + acceso;
            });
            return false;
        },
        cambio_guia(c){
            if (c === "Consumidor Final") {
                this.transportista.identificacion_transporte = "9999999999999";
            }else{
                if(this.transportista.identificacion_transporte == "9999999999999"){
                    this.transportista.identificacion_transporte = "";
                }
            }
        },
        listarclave() {
            var url = "/api/listarclave/" + this.usuario.id;
            axios.get(url).then(res => {
                var fecha = moment(this.factura.fecha_emision).format("DDMMYYYY");
                var rec = res.data.recupera[0];
                var secuencial = this.zeroFill(res.data.secuencial, 9);
                var establecimiento = this.zeroFill(rec.establecimiento, 3);
                var punto_emision = this.zeroFill(rec.punto_emision, 3);
                var codigoacc = fecha+"01"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                var acceso = this.Modulo11(codigoacc);
                this.factura.clave_acceso = codigoacc + acceso;
            });
            return false;
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
        //methodos para cambiar iva
        cambiarivas(id,valor){
            if(valor!==0){
                // console.log(id+":index");
                // console.log(valor+":valor");
                // console.log(this.producto.lista_productos[id].precio);
                this.producto.lista_productos[id].precio=valor/1.12;
            }
        },
        // funciones interes
        listar_interes(page1,buscar1){
            
                var url = "/api/tabla_interes/"+this.usuario.id_empresa;
                axios.get(url, {
                    params:{
                         page: page1,
                         buscar: buscar1,
                         id_user: this.usuario.id
                    }
                }).then(res => {
                    //this.contenido_interes=res.data.recupera;
                    this.valor_interes=res.data.recupera[0].interes;
                    console.log("Interes:"+this.valor_interes);
                });
        },
        listar_interes_anual(page1,buscar1){
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                //this.contenido_interes_anual = [];
                var url = "/api/tabla_interes_anual/"+this.usuario.id_empresa;
                axios.get(url, {
                    params:{
                         page: page1,
                         buscar: buscar1,
                         id_user: this.usuario.id
                    }
                }).then(res => {

                    this.interes_anual=res.data.recupera[0].interes_anual;
                    this.periodo_pago_anual=res.data.recupera[0].periodo_pago;
                    this.tiempo_pago_anual=res.data.recupera[0].tiempo_pago;
                    //this.contenido_interes_anual=res.data.recupera;
                });
            }, 800);

        },
        ///////////////////////////// funciones cuota extra
        cambioscuotas_extra(){
            // estado:false,
            //     index:null,
            //     datos:[
            //         {
            //             fecha_pago:"",
            //             valor_pago:"",
            //             error_fecha:[],
            //             error_valor:[]
            //         }
            //     ]
            this.cuotas_extras.datos.forEach(el => {
                el.valor_pago = 0;
            });
            this.cuotas_extras.datos = [
                {
                    fecha_pago:this.factura.fecha_emision,
                    valor_pago:0
                }
            ];
            if(!this.cuotas_extras.estado){
                
                this.cuotas_extras.datos[0].valor_pago = parseFloat(this.total_pendiente).toFixed(2);
            }
        },
        addcuotas(){
            this.cuotas_extras.datos.push({
                fecha_pago:this.factura.fecha_emision,
                valor_pago:0
            });
        },
        eliminararraycuota(id) {
            this.cuotas_extras.datos.splice(id, 1);
        },
        /////////////////////////////
    },
    mounted() {
        this.listar_interes_anual(1,'');
        this.listar_creacion_cliente();
        this.listar_cuenta_contable(this.plan_cuenta.buscar);
        //this.listarclave();
        this.listarformapagos();
        this.listarretenciones();
        this.listarbanco();
        this.recuperar();
        this.listarvendedor();
        this.leercodigo();
        $(document).on("click",function(e) {
         var container = $(".busqueda_lista");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
               $(".busqueda_lista").hide();
            }
        });
        $(".focuspr").focus();
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
        display: table-cell!important;
        height: 100%;
        vertical-align: middle;
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
    .font-small input{
        font-size: 11px;
    }
    .vs-table--tbody{
        overflow-y: hidden!important;
    }
</style>
