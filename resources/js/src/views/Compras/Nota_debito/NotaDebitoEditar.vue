<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3>Nota de Débito Compra</h3>    
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <h6>Fecha:</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full mt-1" v-model="factura.fecha" placeholder="Seleccionar" @on-change="listarclave()"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha">
                        <div v-for="err in error.factura.fecha" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-3/5 w-full mb-6">
                    <h6 class="mb-1">Número Documento</h6>
                    <vs-input class="w-full" maxlength="15" placeholder="000-000-000000000" @keyup="documentos()" v-model="factura.documento"/>
                    <div v-show="error" v-if="factura.documento.length!=15">
                        <div v-for="err in error.factura.documento" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div> 
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <h6 class="mb-1">Fecha Doc:</h6>
                    <flat-pickr :config="configdateTimePicker" disabled class="w-full" v-model="factura.fecha_doc" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha_doc">
                        <div v-for="err in error.factura.fecha_doc" :key="err" v-text="err" class="text-danger"></div>
                    </div> 
                </div>
                <div class="vx-col sm:w-full w-full mb-6 text-center">
                    <h6 class="mt-4">Clave de acceso:</h6>
                    <p>{{ factura.clave_acceso }}</p>
                </div>
            </div>
            <vs-divider position="left" v-if="cliente.tipo">
                <h3>Cliente</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="cliente.tipo">
                <div class="vx-col sm:w-full w-full mb-6 relative">
                    <div class="vx-row">
                        <!--<a class="flex items-center buscar_otro" @click="cliente.tipo=false"> Agregar otro Cliente </a>-->
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
                <div v-show="error" v-if="!cliente.tipo">
                    <div v-for="err in error.cliente.tipo" :key="err" v-text="err" class="text-danger"></div>
                </div>
            </div>
            <vs-divider position="left" v-if="producto.tipo">
                <h3>Productos</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="producto.tipo">
                <div class="vx-col sm:w-full w-full relative">
                    <vs-table hoverFlat :data="producto.lista_productos" style="font-size: 12px;">
                        <template slot="thead">
                            <vs-th class="text-center">CÓDIGO</vs-th>
                            <vs-th>MOTIVO</vs-th>
                             <vs-th>PROYECTO</vs-th>
                            <vs-th class="text-center">CANTIDAD</vs-th>
                            <vs-th class="text-center">PRECIO</vs-th>
                            <vs-th class="text-center">SUBTOTAL</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td>{{ tr.nombre }}</vs-td>
                                <vs-td>
                                    <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="tr.proyecto">
                                        <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div v-for="err in tr.errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:120px!important;">
                                    <vs-input class="w-full text-center" placeholder="$0.00" v-model="tr.cantidad"/>
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                 <vs-td style="width:120px!important;">
                                    <vs-input class="w-full text-center" placeholder="$0.00" v-model="tr.precio"/>
                                    <div v-show="error" v-if="!tr.precio">
                                        <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td class="text-center" style="width:120px!important;">
                                         $ {{ tr.subtotal =  (tr.cantidad * tr.precio).toFixed(2) }}
                                </vs-td>
                                <feather-icon icon="TrashIcon" svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer" class="eliminar_producto_icono" @click="eliminar_producto(index)"/>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
                <div class="vx-col w-full">
                    <div class="vx-row" v-if="producto.tipo">
                        <div class="vx-col sm:w-1/2 w-full">
                            <h6>Observaciones:</h6>
                            <vs-textarea  class="w-full"  v-model="factura.observacion"  rows="5"/>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>SUBTOTAL FINAL <span>$ {{ formulas.subtotal }}</span></div>
                                <div v-if="formulas.subtotal12>0">SUBTOTAL IVA 12% <span>$ {{ formulas.subtotal12 }}</span></div>
                                <div v-if="formulas.valor12>0">Valor IVA 12% <span>$ {{ formulas.valor12 }}</span></div>
                                <div v-if="formulas.subtotal0>0">SUBTOTAL IVA 0% <span>$ {{ formulas.subtotal0 }}</span></div>
                                <div v-if="formulas.no_impuesto>0">NO OBJETO DE IMPUESTO <span>$ {{ formulas.no_impuesto }}</span></div>
                                <div v-if="formulas.exento>0">EXENTO DE IVA <span>$ {{ formulas.exento }}</span></div>
                                <div>VALOR TOTAL <span>$ {{ formulas.total }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <vs-divider position="left" v-if="cliente.tipo">
                <h3>Total Facturas</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base" v-if="cliente.tipo">
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
            <vs-divider position="left" class="flexy" v-if="cliente.tipo">
                <h3>Créditos</h3>
                <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="creditos.estado" @click="cambioscreditos()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade" v-if="cliente.tipo">
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
            <vs-divider position="left" class="flexy" v-if="cliente.tipo">
                <h3>Pagos</h3>
                <vs-switch vs-icon-on="check" color="success" class="ml-2" v-model="pagos.estado" @click="cambiospagosrec()" vs-value="Si" style="margin-top: 4px;">
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade" v-if="cliente.tipo">
                <div class="vx-row leading-loose p-base" v-show="pagos.estado">
                    <div class="w-full">
                        <div class="vx-row hovertrash" v-for="(tr,index) in pagos.datos" :key="index">
                            <div class="vx-col w-full mb-2 text-center ml-auto sm:w-1/6">
                                <label class="vs-input--label">Método de pago</label>
                                <vs-select placeholder="Selecciona el método de pago" autocomplete class="selectExample w-full" v-model="tr.metodo_pago">
                                    <vs-select-item v-for="(tr,index) in formapagos" :key="index" :value="tr.id_forma_pagos" :text="tr.descripcion"/>
                                </vs-select>
                                <div v-show="error.error" v-if="!tr.metodo_pago">
                                    <div v-for="err in tr.errormetodo" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-select class="selectExample w-full" label="Banco" vs-multiple autocomplete v-model="tr.banco_pago">
                                    <vs-select-item v-for="data in bancos" :key="data.id_banco" :value="data.id_banco" :text="data.nombre_banco" />
                                </vs-select>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-input class="w-full text-center" label="Cantidad" v-model="tr.cantidad_pago"/>
                                <div v-show="error.error" v-if="parseFloat(tr.cantidad_pago)<=0">
                                    <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                                <vs-input class="w-full text-center" label="Nro de transacción" v-model="tr.nro_trans"/>
                            </div>
                            <div class="vx-col sm:w-1/6 w-full mb-2">
                                <label class="vs-input--label">Fecha de pago</label>
                                <flat-pickr :config="configdateTimePicker" class="w-full" v-model="tr.fecha_pago" placeholder="Seleccionar"></flat-pickr>
                            </div>
                             <div class="vx-col sm:w-1/6 w-full mb-2">
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
            <div class="vx-col w-full" v-if="cliente.tipo">
                <vs-button color="success" type="filled" @click="guardar_factura()">GUARDAR</vs-button>
                <vs-button color="danger" type="filled" to="/facturacion/nota-debito">CANCELAR</vs-button>
            </div>
            <!-- Crear Cliente -->
            <vs-popup :title="modal.titulo" :active.sync="modal.abrir" class="modal-xl">
                <div class="con-exemple-prompt">
                    <div class="vx-row">
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
                        <tbody>
                            <tr v-for="(tr,index) in plan_cuenta.lista" :key="index" @click="escoger_plan_cuenta(tr)" class="tablavista">
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
                fecha:moment().format('YYYY-MM-DD'),
                documento:'',
                fecha_doc:'',
                motivo:'',
                ambiente:'',
                tipo_emision:'Emision Normal',
                clave_acceso:'Generando Clave de acceso',
                observacion:'',
                proyectos:null,
                forma_pago:'',
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

            error:{
                error:0,
                factura:{
                    tipo_emision:[],
                    fecha:[],
                    documento:[],
                    fecha_doc:[],
                    motivo:[]
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
            creditos:{
                estado: false,
                periodo:'',
                tiempo:1,
                plazos:3,
                monto:0,
                pago:0
            },
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
                        errormetodo:[],
                        errorcantidad:[],
                    }
                ]
            },
            formapagos:[],
            bancos: [],
            formapagos:[],
            creditosestado:false,
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
            var total = 0;

            this.producto.lista_productos.forEach(el => {
                subtotal += el.precio * el.cantidad;
                if (el.iva == 2) {subtotal12 += el.precio * el.cantidad;}
                if (el.iva == 1) {subtotal0 += el.precio * el.cantidad;}
                if (el.iva == 3) {no_impuesto += el.precio * el.cantidad;}
                if (el.iva == 4) {exento += el.precio * el.cantidad;}
            });
            valor12 = subtotal12 * 0.12;
            total = subtotal + valor12;

            return {
                'subtotal': subtotal.toFixed(2),
                'subtotal12': subtotal12.toFixed(2),
                'valor12': valor12.toFixed(2),
                'subtotal0': subtotal0.toFixed(2),
                'valor0': valor0.toFixed(2),
                'no_impuesto': no_impuesto.toFixed(2),
                'exento': exento.toFixed(2),
                'total': total.toFixed(2)
            };
        },
        pagoletra(){
            var res = 0;
            if(this.creditos.monto<=0 || this.creditos.monto === undefined){
                var res = 0;
            }else{
                var res = parseFloat(this.creditos.monto) / parseFloat(this.creditos.plazos);
            }
            return res.toFixed(2);
        },
        total_pendiente(){
            var total = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;

            this.pagos.datos.forEach(el => {
                if(parseFloat(el.cantidad_pago)>=0 ){
                    paga = parseFloat(el.cantidad_pago)
                }
                pagas +=  paga;
            });
            if(this.creditos.monto<=0 || this.creditos.monto === undefined){
                creditos = 0;
            }else{
                creditos = parseFloat(this.creditos.monto);
            }

            total = parseFloat(this.formulas.total) - parseFloat(creditos) - parseFloat(pagas); 
            if(parseFloat(total)<0.01 && parseFloat(total)>=-0.02){
                total = 0;
            }
            return total.toFixed(2);
        },
        total_pagado(){
            var total = 0;
            var iva = 0;
            var renta = 0;
            var paga = 0;
            var pagas = 0;
            var creditos = 0;
            this.pagos.datos.forEach(el => {
                if(parseFloat(el.cantidad_pago)>=0 ){
                    paga = parseFloat(el.cantidad_pago)
                }
                pagas +=  paga;
            });
            if(this.creditos.monto<=0  || this.creditos.monto === undefined){
                creditos = 0;
            }else{
                creditos = this.creditos.monto;
            }
            total = parseFloat(creditos) + parseFloat(pagas); 
            if(parseFloat(total)<0.01){
                total = 0;
            }
            return parseFloat(total).toFixed(2);
        }
    },
    methods: {
        documentos(){
            if(this.factura.documento.length==15){
                axios.post('/api/notacredito/buscarfactura', {
                    factura:this.factura.documento,
                    id_empresa: this.usuario.id_empresa,
                }).then( ({data}) => {
                    if(data=='error'){
                        this.$vs.notify({
                            title: "Factura erronea",
                            text: "Esta factura no consta en nuestro sistema",
                            color: "danger"
                        }); 
                    }else{
                        this.factura.fecha_doc = data.factura.fecha_emision;
                        this.factura.fecha = moment().format();
                        this.factura.proyectos = data.factura.id_proyecto;
                        this.producto.tipo = 1;
                        this.producto.lista_productos=[];
                        data.detalle.forEach((el,index) => {
                            this.producto.lista_productos.push({
                                id_producto: el.id_producto,
                                cod_alterno: el.cod_alterno,
                                cod_principal: el.cod_principal,
                                nombre: el.nombre,
                                cantidad: el.cantidad,
                                precio: el.precio,
                                descuento: el.descuento,
                                p_descuento: el.p_descuento,
                                subtotal: el.cantidad*el.precio,
                                iva: el.id_iva,
                                ice: el.id_ice,
                                proyecto: el.id_proyecto
                            });
                        }); 
                        this.cliente.tipo = true;
                        this.cliente.id_cliente = data.cliente.id_cliente;
                        this.cliente.nombre = data.cliente.nombre;
                        this.cliente.telefono = data.cliente.telefono;
                        this.cliente.email = data.cliente.email;
                        this.cliente.tipo_identificacion = data.cliente.tipo_identificacion;
                        this.cliente.identificacion = data.cliente.identificacion;
                        this.cliente.direccion = data.cliente.direccion;
                    }
                }); 
            }
        },
        listar_cliente(buscar){
            this.preloader.cliente=false;
            if(this.cliente.busqueda.length > 2){
                axios.get('/api/notacredito/listar_cliente?buscar=' + buscar + '&empresa=' + this.usuario.id_empresa).then( ({data}) => {
                    this.cliente.clientes = data;
                    $(".busqueda_cliente_ls").show();
                    setTimeout(() => {
                        this.preloader.cliente=true;
                    }, 100);
                }).catch( error => {
                    console.log(error);
                });
            }else{
                this.cliente.clientes = [];
            }
        },
        listar_productos(buscar){
            this.preloader.productos=false;
            if(this.producto.busqueda.length > 2){
                axios.get('/api/notacredito/listar_productos?buscar=' + buscar + '&empresa=' + this.usuario.id_empresa).then( ({data}) => {
                    this.producto.productos = data;
                    $(".busqueda_producto_ls").show();
                    setTimeout(() => {
                        this.preloader.productos=true;
                    }, 100);
                }).catch( error => {
                    console.log(error);
                });
            }else{
                this.producto.productos = [];
            }
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
            this.cliente.id_cliente = tr.id_cliente;
            this.cliente.nombre = tr.nombre;
            this.cliente.telefono = tr.identificacion;
            this.cliente.email = tr.email;
            this.cliente.tipo_identificacion = tr.tipo_identificacion;
            this.cliente.identificacion = tr.identificacion;
            this.cliente.direccion = tr.direccion;
        },
        seleccionar_productos(tr){
            this.producto.productos = [];
            this.producto.busqueda = '';
            this.producto.tipo = true;
            var subtotal =  (tr.pvp_precio1).toFixed(2);
            if(isNaN(parseInt(tr.existencia_total))){
                tr.existencia_total='';
            }
            if(isNaN(parseFloat(tr.pvp_precio1))){
               tr.pvp_precio1=''; 
            }
            this.producto.lista_productos.push({
                id_producto: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cantidad: tr.existencia_total,
                precio: tr.pvp_precio1,
                subtotal: subtotal,
                iva: tr.iva,
                ice: tr.ice
            });
        },
        escoger_plan_cuenta(tr){
           this.crear_cliente.cuenta_contable = tr.codcta;
           this.crear_cliente.id_cuenta_contable = tr.id_plan_cuentas;
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
                tipo_identificacion:null,
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
            if(this.validar_crear_cliente()){return;}
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
        listarclave() {
            if (!this.$route.params.id) {
                var url = "/api/listarclavedebito/" + this.usuario.id;
                axios.get(url).then(res => {
                    var fecha = moment(this.factura.fecha).format("DDMMYYYY");
                    var rec = res.data.recupera[0];
                    var secuencial = this.zeroFill(res.data.secuencial, 9);
                    var establecimiento = this.zeroFill(rec.establecimiento, 3); 
                    var punto_emision = this.zeroFill(rec.punto_emision, 3);
                    var codigoacc = fecha+"05"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
                    var acceso = this.Modulo11(codigoacc);
                    this.factura.clave_acceso = codigoacc + acceso;
                });
                return false;
            }
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
        validar(){
            this.error = {
                error:0,
                factura:{
                    tipo_emision:[],
                    fecha:[],
                    documento:[],
                    fecha_doc:[],
                    motivo:[]
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

            if(!this.factura.tipo_emision){
                this.error.factura.tipo_emision.push("Debe agregar el tipo de emisión");
                this.error.error=1; 
            }
            if(!this.factura.fecha){
                this.error.factura.fecha.push("Debe agregar la fecha de la factura");
                this.error.error=1;
            }
            if(this.factura.documento.length!=15){
                this.error.factura.documento.push("Debe agregar el número de documento válido");
                this.error.error=1;
            }
            if(!this.factura.fecha_doc){
                this.error.factura.fecha_doc.push("Debe agregar la fecha del documento");
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

            return this.error_cliente.error;
        },
        listarformapagos(){
            axios.get("/api/facturaformapagos").then( res => {
                this.formapagos = res.data;
            }).catch( err => {
                console.log(err);
            });
        },
        listarbanco() {
            axios.get("/api/traerbancofactcomp").then(({data}) => {
                this.bancos = data;
            });
        }, 
        listarformapagos(){
            axios.get("/api/facturaformapagos").then( res => {
                this.formapagos = res.data;
            }).catch( err => {
                console.log(err);
            });
        },
        cambioscreditos(){
            if(this.total_pendiente<=0 || this.total_pendiente === undefined){
                this.creditos.monto = 0;
            }else{
                this.creditos.monto = parseFloat(this.total_pendiente);
            }
            
            if(this.creditos.estado){
                this.creditos.monto = 0;
            }
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
                this.pagos.datos[0].cantidad_pago = this.total_pendiente; 
            }
        },
        //valores de edición
        recuperar(){
            axios.get('/api/notadebito/recuperar/' + this.$route.params.id).then( ({data}) => {
                this.factura = {
                    fecha:moment(data.factura.fecha_emision).format('YYYY-MM-DD'),
                    documento:data.factura.autorizacionfactura,
                    fecha_doc:data.factura.fechaAutorizacion,
                    motivo:data.factura.motivo,
                    ambiente:data.factura.ambiente,
                    tipo_emision:'Emision Normal',
                    clave_acceso:data.factura.clave_acceso,
                    observacion:data.factura.observacion,
                    proyectos:data.factura.id_proyecto,
                    forma_pago:data.factura.forma_pago,
                }
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
                data.productos.forEach(el => {
                    this.producto.lista_productos.push({
                        id_detalle_nota_credito: el.id_detalle_nota_credito,
                        id_producto: el.id_producto,
                        cod_alterno: el.cod_alterno,
                        cod_principal: el.cod_principal,
                        nombre: el.nombre,
                        cantidad: el.cantidad,
                        precio: el.precio,
                        descuento: el.descuento,
                        p_descuento: el.p_descuento,
                        subtotal: null,
                        iva: el.id_iva,
                        ice: el.id_ice,
                        proyecto: el.id_proyecto
                    });
                });
                this.producto.tipo = true;
                this.creditos = data.creditos;
                this.pagos.datos = data.pagos;
                if(data.pagos.length>=1){
                    this.pagos.estado=true;
                }else{
                    this.pagos = {
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
                                errormetodo:[],
                                errorcantidad:[],
                            }
                        ]
                    }
                }

                if(data.creditos){
                    this.creditos.estado=true;
                }else{
                    this.creditos = {
                        estado: false,
                        periodo:'',
                        tiempo:1,
                        plazos:3,
                        monto:0,
                        pago:0
                    }
                }
            }).catch( error => {
                console.log(error);
            });
        },
        guardar_factura(){
            if(this.validar()){return;}
            axios.put('/api/notadebito/editar_factura', {
                id: this.$route.params.id,
                factura: this.factura,
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
                creditos: this.creditos,
                pagos:this.pagos,
            }).then( ({data}) => {
                this.$vs.notify({
                    time: 8000,
                    title: "Enviando Nota de Débito",
                    text: "La factura esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                    color: "warning"
                }); 
                var recupera_factura = data;
                axios.post('/api/factura/xml_nota_debito', data).then(res => {
                    var password = res.data.recupera.pass_firma;
                    var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + res.data.recupera.firma;
                    var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notadebito/" + this.factura.clave_acceso +".xml";
                    var tipo = "nota_debito_venta";
                    var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notadebito/";
                    var fecha_actual = moment(recupera_factura.fecha_autorizacion).format('LL');  
                    this.crearfacturacion(firma, password, factura, tipo, this.usuario, recupera_factura.id_nota_debito, carpeta, fecha_actual, recupera_factura.valor_total, recupera_factura.logo, recupera_factura.nombre_empresa);
                });
            }).catch( error => {
                console.log(error);
            }); 
        },
        //Facturación
        enviado(){
            this.$router.push("/facturacion/nota-debito/");
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
                if(registrado=='enviado'){
                    this.$vs.notify({
                        time: 8000,
                        title: "Nota de Débito Enviada",
                        text:"La Nota de Débito se generó exitosamente",
                        color: "success"
                    }); 
                    this.enviado();
                }else{
                    this.$vs.notify({
                        time: 8000,
                        title: "Error en el envio al SRI",
                        text: 'La Nota de Débito no pudo ser enviada, intente mas tarde',
                        color: "danger"
                    }); 
                    this.enviado();
                }
            } catch(error) {
                if(error.message = 'Request failed with status code 500'){
                    error.message = 'La Nota de Débito no pudo ser enviada, intente mas tarde';
                }
                this.$vs.notify({
                    time: 8000,
                    title: "Error en el envio al SRI",
                    text: error.message,
                    color: "danger"
                }); 
                this.enviado();
            }
        },
    }, 
    mounted() {
        this.listar_creacion_cliente(); 
        this.listar_cuenta_contable(this.plan_cuenta.buscar);
        this.listarclave();
        $(document).on("click",function(e) {      
         var container = $(".busqueda_lista");              
            if (!container.is(e.target) && container.has(e.target).length === 0) { 
               $(".busqueda_lista").hide();          
            }
        });
        this.listarformapagos();
        this.listarbanco();
        this.listarformapagos();
        this.recuperar();
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
        position: absolute!important;
        right: 0px;
        margin-top: 18px;
        display:none;
    }
    .fila_lista:hover .eliminar_producto_icono{
        display:block;
    }
    .cabezera_total span{
        float: right;
        margin-right: 25px;
    }
    .cabezera_total div{
        margin-left: 20px;
        padding: 6px 3px;
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
    .text-center .vs-table-text {
        text-align: center!important;
        display: inline-block;
    }
    .text-center input{
        text-align: center!important;
    }
    .flexy > .vs-divider--text {
        display: flex;
    }
</style>