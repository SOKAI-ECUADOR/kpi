<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3 v-if="factura.clave_acceso">Nota de Crédito N° {{(factura.clave_acceso).substring(24,27)}}-{{(factura.clave_acceso).substring(27,30)}}-{{(factura.clave_acceso).substring(30,39)}}</h3>
                <h3 v-else>Generando factura</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto" style="text-align: center;">
                    <h6 class="mb-1">Ambiente:</h6>
                    <span v-if="factura.ambiente==2">Producción</span>
                    <span v-else>Pruebas</span>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6 ml-auto mr-auto" style="text-align: center;">
                    <h6 class="mb-1">Tipo Emisión:</h6> {{ factura.tipo_emision }}
                    <div v-show="error" v-if="!factura.tipo_emision">
                        <div v-for="err in error.factura.tipo_emision" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6>Fecha:</h6>
                    <flat-pickr :config="configdateTimePicker" class="w-full mt-1" v-model="factura.fecha" placeholder="Seleccionar" @on-change="listarclave()"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha">
                        <div v-for="err in error.factura.fecha" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6" style="position: relative;">
                    <h6 class="mb-1">Número Documento</h6>
                    <vs-input class="w-full" maxlength="15" placeholder="000-000-000000000" @keyup="documentos(), listar_facturas(factura.documento)" v-model="factura.documento"/>
                    <div class="busqueda_lista busqueda_factura_ls" style="display: none;width: 93%!important;">
                        <div v-if="preloader.facturas">
                            <ul class="ul_busqueda_lista">
                                <li v-for="(tr,index) in facturas" :key="index" @click="seleccionar_factura(tr)"><span style="font-weight: bold;"> Factura N° {{(tr.clave_acceso).substring(24,27)}}-{{(tr.clave_acceso).substring(27,30)}}-{{(tr.clave_acceso).substring(30,39)}}</span> </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                    <div v-show="error" v-if="factura.documento.length!=15">
                        <div v-for="err in error.factura.documento" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Fecha Doc:</h6>
                    <flat-pickr :config="configdateTimePicker" disabled class="w-full" v-model="factura.fecha_doc" placeholder="Seleccionar"></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha_doc">
                        <div v-for="err in error.factura.fecha_doc" :key="err" v-text="err" class="text-danger"></div>
                    </div> 
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <h6 class="mb-1">Motivo:</h6>
                    <vs-input class="w-full" v-model="factura.motivo" placeholder="Escriba el motivo del comprobante"/>
                    <div v-show="error" v-if="!factura.motivo">
                        <div v-for="err in error.factura.motivo" :key="err" v-text="err" class="text-danger"></div>
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
                            <vs-th>CÓDIGO</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th>CANTIDAD</vs-th>
                            <vs-th>PRECIO</vs-th>
                            <vs-th>DESCUENTO</vs-th>
                            <vs-th>SUBTOTAL</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr v-for="(tr, index) in data" :key="index" class="fila_lista">
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td class="w-full derecha font-small">{{ tr.nombre }}</vs-td>
                                <vs-td>
                                    <vs-select class="selectExample w-full" placeholder="Seleccione un proyecto" v-model="tr.proyecto">
                                        <vs-select-item :key="index" :value="item.id_proyecto" :text="item.descripcion" v-for="(item, index) in proyectos_menu"/>
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div v-for="err in tr.errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:150px!important;">
                                    <vs-input class="w-full" placeholder="$0.00" v-model="tr.cantidad"/>
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div v-for="err in tr.errorcantidad" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:150px!important;">
                                    <!-- <vs-input class="w-full" placeholder="$0.00" v-model="tr.precio"/> -->
                                    {{tr.precio | currency}}
                                    <div v-show="error" v-if="!tr.precio">
                                        <div v-for="err in tr.errorprecio" :key="err" v-text="err" class="text-danger"></div>
                                    </div>
                                </vs-td>
                                <vs-td class="estilodes">
                                    <!-- <vx-input-group>
                                        <vs-input class="w-full" placeholder="$0.00" v-model="tr.descuento"/>
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
                                    </vx-input-group> -->
                                    <template v-if="tr.p_descuento==1"></template> 
                                    <span v-if="tr.descuento">{{ tr.descuento/tr.cantidad_dsc*tr.cantidad | currency}}</span><span v-else>0.00</span> 
                                    <template v-if="tr.p_descuento==0">%</template>
                                </vs-td>
                                <vs-td style="width:130px!important;" v-if="tr.descuento">
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - (tr.descuento/tr.cantidad_dsc*tr.cantidad)).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio) - ((tr.cantidad * tr.precio * tr.descuento)/100/tr.cantidad_dsc*tr.cantidad)).toFixed(2)}}
                                    </template>
                                </vs-td>
                                <vs-td style="width:130px!important;" v-else>
                                    <template v-if="tr.p_descuento==1">
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio)).toFixed(2)}}
                                    </template>
                                    <template v-else>
                                         $ {{ tr.subtotal =  ((tr.cantidad * tr.precio)).toFixed(2)}}
                                    </template>
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
                                <div>TOTAL DESCUENTO <span>$ {{ formulas.descuento }}</span></div>
                                <div>VALOR TOTAL <span>{{ formulas.total |currency}}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vx-col w-full">
                <vs-button color="success" type="filled" @click="guardar_factura()" :disabled="disabled_button">GUARDAR</vs-button>
                <vs-button color="danger" type="filled" to="/facturacion/nota-credito">CANCELAR</vs-button>
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
            disabled_button:false,
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
                facturas:false,
            },
            id_factura:null,
            facturas: []
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

            this.producto.lista_productos.forEach(el => {
                if (el.p_descuento == 1) {
                    if(el.descuento>0 && el.descuento!==null){
                        subtotal += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);
                    }else{
                        subtotal += el.precio * el.cantidad ;
                    }
                    
                    
                    if(isNaN(parseFloat(el.descuento))){
                        if (el.iva == 2) {subtotal12 += el.precio * el.cantidad ;}
                        if (el.iva == 1) {subtotal0 += el.precio * el.cantidad ;}
                        if (el.iva == 3) {no_impuesto += el.precio * el.cantidad ;}
                        if (el.iva == 4) {exento += el.precio * el.cantidad ;}
                        descuento += 0;
                    }else{
                        if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        if (el.iva == 4) {exento += el.precio * el.cantidad - (el.descuento/el.cantidad_dsc*el.cantidad);}
                        descuento += parseFloat(el.descuento/el.cantidad_dsc*el.cantidad);
                    }
                } else {
                    if(el.descuento>0 && el.descuento!==null){
                        subtotal += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);
                    }else{
                        subtotal += el.precio * el.cantidad;
                    }
                    
                    
                    if(isNaN((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100)){
                        if (el.iva == 2) {subtotal12 += el.precio * el.cantidad ;}
                        if (el.iva == 1) {subtotal0 += el.precio * el.cantidad ;}
                        if (el.iva == 3) {no_impuesto += el.precio * el.cantidad ;}
                        if (el.iva == 4) {exento += el.precio * el.cantidad ;}
                        descuento += 0;
                    }else{
                        if (el.iva == 2) {subtotal12 += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                        if (el.iva == 1) {subtotal0 += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                        if (el.iva == 3) {no_impuesto += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                        if (el.iva == 4) {exento += el.precio * el.cantidad - ((el.cantidad * el.precio * el.descuento)/100/el.cantidad_dsc*el.cantidad);}
                        descuento += parseFloat((parseFloat(el.precio) * parseFloat(el.cantidad) * parseFloat(el.descuento))/100/el.descuento/el.cantidad_dsc*el.cantidad);
                    }
                }
            });
            console.log("Valor Subt12:"+parseFloat(subtotal12).toFixed(2));
            valor12 = parseFloat(subtotal12).toFixed(2) * 0.12;
            console.log("Valor 12:"+parseFloat(valor12).toFixed(2));
            console.log("Valor Subt:"+subtotal);
            total = Number(subtotal.toFixed(2)) + Number(valor12.toFixed(2));
            console.log("Valor Total:"+total);
            return {
                'subtotal': subtotal.toFixed(2),
                'subtotal12': subtotal12.toFixed(2),
                'valor12': valor12.toFixed(2),
                'subtotal0': subtotal0.toFixed(2),
                'valor0': valor0.toFixed(2),
                'no_impuesto': no_impuesto.toFixed(2),
                'exento': exento.toFixed(2),
                'descuento': descuento.toFixed(2),
                'total': total
            };
        }
    },
    methods: {
        documentos(){
            var bs = this.factura.documento.replace(/-/g,"");
            if(this.factura.documento.length==15){
                axios.post('/api/notacredito/buscarfactura', {
                    factura:bs,
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
                                id_producto_bodega:el.id_producto_bodega,
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
                                sector: el.sector,
                                proyecto:el.id_proyecto,
                                cantidad_dsc:el.cantidad
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
                        this.id_factura = data.factura.id_factura;
                        $(".busqueda_factura_ls").hide();
                    }
                }); 
            }
        },
        listar_facturas(buscar){
            this.preloader.facturas=false;
            $(".busqueda_factura_ls").show();
            if (this.timeout) {  
                clearTimeout(this.timeout);
            }
            this.timeout =  setTimeout(() => {
                var bs = buscar.replace(/-/g,"");
                axios.post('/api/notacredito/listar_facturas',{
                        buscar: bs,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                }).then( ({data}) => {
                    this.facturas = data;
                    this.preloader.facturas=true;
                    return;
                }).catch(() =>{
                    this.preloader.facturas=true;
                });
            }, 800);
        },
        seleccionar_factura(tr){
            this.factura.documento = tr.clave_acceso.substring(24,39);
            this.documentos();
            $(".busqueda_factura_ls").hide();
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
                    console.log('hi');
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
            var subtotal =  (tr.pvp_precio1 - tr.descuento).toFixed(2);

            if(isNaN(parseInt(tr.existencia_total))){
                tr.existencia_total='';
            }
            if(isNaN(parseFloat(tr.pvp_precio1))){
               tr.pvp_precio1=''; 
            }
            if(isNaN(parseFloat(tr.descuento))){
                tr.pvp_precio1='';
            }
            this.producto.lista_productos.push({
                id_producto_bodega:tr.id_producto_bodega,
                nombrebodega:tr.nombrebodega,
                id_producto: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cantidad: tr.cantidad,
                precio: tr.pvp_precio1,
                descuento: tr.descuento,
                p_descuento: 1,
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
        guardar_factura(){
            this.disabled_button=true;
            if(this.validar()){this.disabled_button=false;return;}
            if(this.factura.documento.length<15 || this.id_factura == null){
               this.$vs.notify({
                    time: 5000,
                    title: "Error de número de documento",
                    text: "Debe escoger el número de documento de una factura existente en el sistema",
                    color: "danger"
                });  
                this.disabled_button=false;
                return;
            }
            if(this.formulas.total<0){
                this.$vs.notify({
                    time: 5000,
                    text: "No se puede guardar un comprobante con valores negativos",
                    color: "danger"
                }); 
                this.disabled_button=false; 
                return;
            }
            axios.post('/api/notacredito/guardar_factura', {
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
                id_factura: this.id_factura,
            }).then( ({data}) => {
                this.$vs.notify({
                    time: 8000,
                    title: "Enviando Nota de Crédito",
                    text: "La factura esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                    color: "warning"
                }); 
                var recupera_factura = data;
                axios.post('/api/factura/xml_nota_credito', data).then(res => {
                    var password = res.data.recupera.pass_firma;
                    var firma = DATA_EMPRESA + this.usuario.id_empresa + "/firma/" + res.data.recupera.firma;
                    var factura = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notacredito/" + this.factura.clave_acceso +".xml";
                    var tipo = "nota_credito_venta";
                    var carpeta = DATA_EMPRESA + this.usuario.id_empresa + "/comprobantes/notacredito/";
                    var fecha_actual = moment(recupera_factura.fecha_autorizacion).format('LL'); 
                    this.crearfacturacion(firma, password, factura, tipo, this.usuario, recupera_factura.id_nota_credito, carpeta, fecha_actual, recupera_factura.valor_total, recupera_factura.logo, recupera_factura.nombre_empresa);
                });
            }).catch( error => {
                console.log(error);
            }); 
        }, 
        listarclave() {
            if (!this.$route.params.id) {
                var url = "/api/listarclavecredito/" + this.usuario.id;
                axios.get(url).then(res => {
                    var fecha = moment(this.factura.fecha).format("DDMMYYYY");
                    var rec = res.data.recupera[0];
                    var secuencial = this.zeroFill(res.data.secuencial, 9);
                    var establecimiento = this.zeroFill(rec.establecimiento, 3); 
                    var punto_emision = this.zeroFill(rec.punto_emision, 3);
                    var codigoacc = fecha+"04"+rec.ruc_empresa+rec.ambiente+establecimiento+punto_emision+secuencial+"12345678"+1;
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
                    motivo:[],
                },
                cliente:{
                    tipo:[]
                },
                producto:{
                    busqueda:[]
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
            if(!this.factura.motivo){
                this.error.factura.motivo.push("Debe agregar el motivo");
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
        //Facturación
        enviado(){
            this.$router.push("/facturacion/nota-credito/");
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
                    title: "Nota de Crédito Enviada",
                    text:"La factura se generó exitosamente",
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
    .font-small input{
        font-size: 11px;
    }
    .font-small span{
        font-size: 11px;
    }
    .selectExample input{
        width: 150px !important;
    }
    .estilodes .vx-input-group {
        width: 125px;
    }
</style>
