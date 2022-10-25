<template>
    <div id="invoice-page">
        <vx-card>
            <vs-divider position="center">
                <h3>Factura compra</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6>Nº de Factura:</h6>
                    <vs-input
                        class="w-full"
                        v-model="factura.nfactura"
                        v-on:keypress="NumbersOnly"
                        maxlength="15"
                    />
                    <div v-show="error" v-if="error.error">
                        <div
                            v-for="err in error.factura.numero_factura"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Fecha Emision:</h6>
                    <flat-pickr
                        :config="configdateTimePicker"
                        class="w-full"
                        v-model="factura.fecha_emision"
                        @on-change="listarclave()"
                        placeholder="Seleccionar"
                    ></flat-pickr>
                    <div v-show="error" v-if="!factura.fecha_emision">
                        <div
                            v-for="err in error.factura.fecha_emision"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Fecha Validez:</h6>
                    <flat-pickr
                        :config="configdateTimePicker"
                        class="w-full"
                        v-model="factura.fecha_validez"
                        placeholder="Seleccionar"
                    ></flat-pickr>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <h6 class="mb-1">Número de Autorizacion:</h6>
                    <vs-input class="w-full" v-model="factura.autorizacion" />
                    <div v-show="error" v-if="!factura.autorizacion">
                        <div
                            v-for="err in error.factura.numero_autorizacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
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
                            Ingresar Archivos:
                        </h6>
                        <div class="vx-col sm:w-1/2 w-full ">
                            <div style="display:none">
                                <input
                                    class="button_upload selectbuttomxml"
                                    type="file"
                                    ref="file"
                                    accept=".xml"
                                    @change="uploadxml"
                                />
                            </div>
                            <vx-tooltip
                                color="#0758ba"
                                text="Subir XML"
                                position="top"
                                style="display: inline-flex"
                            >
                                <vs-button
                                    color="#0758ba"
                                    type="border"
                                    icon="upload_file"
                                    @click="selectxmlfile"
                                ></vs-button
                            ></vx-tooltip>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full ">
                            <div style="display:none">
                                <input
                                    class="button_upload selectbuttompdf"
                                    type="file"
                                    ref="file"
                                    accept=".pdf"
                                    @change="uploadpdf"
                                />
                            </div>
                            <vx-tooltip
                                color="danger"
                                text="Subir PDF"
                                position="top"
                                style="display: inline-flex"
                            >
                                <vs-button
                                    color="danger"
                                    type="border"
                                    icon="picture_as_pdf"
                                    @click="selectpdffile"
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
                    <vs-select
                        placeholder="buscar"
                        autocomplete
                        class="selectExample w-full"
                        v-model="factura.tipo_sustento"
                    >
                        <vs-select-item
                            v-for="(tr, index) in sustentos"
                            :key="index"
                            :value="tr.id_sustento"
                            :text="tr.descrip_sustento"
                        />
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
                    <vs-select
                        placeholder="buscar"
                        autocomplete
                        class="selectExample w-full"
                        v-model="factura.destino_pago"
                    >
                        <vs-select-item
                            value="Pago a residentes"
                            text="Pago a residentes"
                        />
                        <vs-select-item
                            value="Pago a no residentes"
                            text="Pago a no residentes"
                        />
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
                {{exist_import}}
                <div class="vx-col sm:w-1/6 w-full mb-6">
                    <h6 class="mb-1">Gastos Importacion:</h6>
                    <ul class="demo-alignment">
                        <li style="margin: 13px 1.5rem;">
                            <vs-radio
                                v-model="factura.gastos"
                                :disabled="imports.length < 1"
                                name="importacion"
                                vs-value="1g"
                                >Si</vs-radio
                            >
                        </li>
                        <li style="margin: 13px 1.5rem;">
                            <vs-radio
                                v-model="factura.gastos"
                                :disabled="imports.length < 1"
                                name="importacion"
                                vs-value="0g"
                                >No</vs-radio
                            >
                        </li>
                    </ul>
                </div>
                <div
                    class="vx-col sm:w-2/5 w-full mb-6"
                    v-if="parseInt(factura.gastos) == 1"
                >
                    <h6 class="mb-1">Nro Importacion:</h6>
                    <vs-select
                        class="selectExample w-full"
                        placeholder="Seleccione una importacion"
                        v-model="factura.importacion"
                    >
                        <vs-select-item
                            :key="index"
                            :value="item.id_importacion"
                            :text="item.nombre_proveedor"
                            v-for="(item, index) in imports"
                        />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-2/5 w-full mb-6 ml-auto">
                    <h6 class="mb-1">Orden de compra:</h6>
                    <vs-input class="w-full" v-model="factura.orden_compra" />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6 mr-auto">
                    <h6 class="mb-1">Documento Tributario:</h6>
                    <ul class="demo-alignment">
                        <li style="margin: 13px 1.5rem;">
                            <vs-radio
                                v-model="factura.docutributario"
                                name="tributoario"
                                vs-value="1t"
                                >Si</vs-radio
                            >
                        </li>
                        <li style="margin: 13px 1.5rem;">
                            <vs-radio
                                v-model="factura.docutributario"
                                name="tributoario"
                                vs-value="0t"
                                >No</vs-radio
                            >
                        </li>
                    </ul>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Proveedor</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div
                    class="vx-col sm:w-full w-full mb-6 relative"
                    v-if="cliente.tipo"
                >
                    <div class="vx-row">
                        <a
                            class="flex items-center buscar_otro"
                            @click="cliente.tipo = false"
                        >
                            Agregar otro Proveedor
                        </a>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                disabled
                                v-bind:value="cliente.nombre"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Teléfono:"
                                disabled
                                v-bind:value="cliente.telefono"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Email:"
                                disabled
                                v-bind:value="cliente.email"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Tipo de Identificación:"
                                disabled
                                v-bind:value="cliente.tipo_identificacion"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Identificación:"
                                disabled
                                v-bind:value="cliente.identificacion"
                            />
                        </div>
                        <div class="vx-col sm:w-1/3 w-full mb-6 relative">
                            <vs-input
                                class="w-full"
                                label="Dirección:"
                                disabled
                                v-bind:value="cliente.direccion"
                            />
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-full w-full mb-6 relative" v-else>
                    <vs-input
                        class="w-full busqueda_cliente"
                        placeholder="Escoge un cliente Para agregar un Comprobante"
                        v-model="cliente.busqueda"
                        @keyup="listar_cliente(cliente.busqueda)"
                    />
                    <feather-icon
                        icon="SearchIcon"
                        svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer"
                        class="busqueda_cliente_icono"
                    />
                    <div class="busqueda_lista busqueda_lista_proveedor">
                        <div v-if="preloader.cliente">
                            <ul
                                class="ul_busqueda_lista"
                                v-if="cliente.clientes.length"
                            >
                                <li
                                    v-for="(tr, index) in cliente.clientes"
                                    :key="index"
                                    @click="seleccionar_cliente(tr)"
                                >
                                    {{ tr.nombre_proveedor }}
                                </li>
                            </ul>
                            <ul class="ul_busqueda_lista" v-else>
                                <li @click="abrir_modal_crear_cliente()">
                                    ESTE PROVEEDOR NO EXISTE, AGREGAR NUEVO
                                    PROVEEDOR
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul>
                                <div></div>
                            </ul>
                        </div>
                    </div>
                    <div v-show="error" v-if="!cliente.tipo">
                        <div
                            v-for="err in error.cliente.tipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
            </div>
            <vs-divider position="left">
                <h3>Productos</h3>
            </vs-divider>
            <div class="vx-row leading-loose p-base">
                <div
                    class="vx-col sm:w-full w-full relative"
                    v-if="producto.tipo"
                >
                    <!--<vs-table
                        hoverFlat
                        :data="producto.lista_productos"
                        style="font-size: 12px;"
                        v-if="xmlmode == false"
                    >
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
                            <vs-th class="text-center" v-if="factura.gastos=='1g'">IMP</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                v-for="(tr, index) in data"
                                :key="index"
                                class="fila_lista"
                            >
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td class="nombrearreglo">
                                    <vx-tooltip
                                        :text="tr.nomb"
                                        position="top"
                                        style="display: inline-flex"
                                        >{{ tr.nombre }}</vx-tooltip
                                    ></vs-td
                                >
                                <vs-td>
                                    <vs-select
                                        class="selectExample w-full"
                                        placeholder="Seleccione un proyecto"
                                        v-model="tr.proyecto"
                                    >
                                        <vs-select-item
                                            :key="index"
                                            :value="item.id_proyecto"
                                            :text="item.descripcion"
                                            v-for="(item,
                                            index) in proyectos_menu"
                                        />
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div
                                            v-for="err in tr.errorproyecto"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <vs-input
                                        class="w-full derecha"
                                        v-model="tr.cantidad"
                                        @keyup="dividir_retenciones()"
                                        @input="pushlotes(index)"
                                    />
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div
                                            v-for="err in tr.errorcantidad_prod"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <vs-input
                                        class="w-full derecha"
                                        placeholder="$0.00"
                                        v-model="tr.precio"
                                        @keyup="dividir_retenciones()"
                                    />
                                    <div v-show="error" v-if="!tr.precio">
                                        <div
                                            v-for="err in tr.errorprecio"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:180px!important;">
                                    <vx-input-group>
                                        <vs-input
                                            class="w-full derecha"
                                            placeholder="$0.00"
                                            v-model="tr.descuento"
                                            @keyup="dividir_retenciones()"
                                        />
                                        <template slot="append">
                                            <div class="append-text btn-addon">
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 1
                                                    }"
                                                    @click="tr.p_descuento = 1"
                                                >
                                                    $
                                                </button>
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 0
                                                    }"
                                                    @click="tr.p_descuento = 0"
                                                >
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td
                                    style="width:175px!important;"
                                    v-if="tr.sector == 2"
                                >
                                    SERVICIO
                                </vs-td>
                                <vs-td style="width:175px!important;" v-else>
                                    <vs-select
                                        placeholder="buscar"
                                        autocomplete
                                        class="selectExample w-full"
                                        v-model="tr.id_bodega"
                                    >
                                        <vs-select-item
                                            v-for="(tr, index) in listarbodegas"
                                            :key="index"
                                            :value="tr.id_bodega"
                                            :text="tr.nombre"
                                        />
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.id_bodega">
                                        <div
                                            v-for="err in tr.errorid_bodega"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <template v-if="tr.p_descuento == 1">
                                        $
                                        {{
                                            (tr.subtotal = (
                                                tr.cantidad * tr.precio -
                                                tr.descuento
                                            ).toFixed(2))
                                        }}
                                    </template>
                                    <template v-else>
                                        $
                                        {{
                                            (tr.subtotal = (
                                                tr.cantidad * tr.precio -
                                                (tr.cantidad *
                                                    tr.precio *
                                                    tr.descuento) /
                                                    100
                                            ).toFixed(2))
                                        }}
                                    </template>
                                </vs-td>
                                <vs-td>
                                    <vs-switch
                                        v-if="tr.iva2 == 1"
                                        :disabled="true"
                                        v-model="tr.siiva"
                                    />
                                    <vs-switch
                                        v-else
                                        v-model="tr.siiva"
                                        @click="cambiarivas(index)"
                                    />
                                </vs-td>
                                <vs-td v-if="factura.gastos=='1g'">
                                    <vs-switch
                                        
                                        
                                        v-model="tr.importacion"
                                    />
                                </vs-td>
                                <vs-td>
                                    <feather-icon
                                     v-if="tr.sector == 1"
                                                    icon="ClipboardIcon"
                                                    svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                                    style="vertical-align: middle;display: table-cell;"
                                                    class="pointer"
                                                    @click="abrirlotes(index)"
                                                />
                                    <feather-icon
                                        icon="TrashIcon"
                                        svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                        style="vertical-align: middle;display: table-cell;"
                                        class="eliminar_producto_icono"
                                        @click="eliminar_producto(index)"
                                    />
                                </vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
    
                    <vs-table
                        hoverFlat
                        :data="producto.lista_productos"
                        style="font-size: 12px;"
                        v-if="xmlmode == true"
                    >
                        <template slot="thead">
                            <vs-th>CÓD</vs-th>
                            <vs-th>NOMBRE</vs-th>
                            <vs-th>PROYECTO</vs-th>
                            <vs-th>CANTIDAD</vs-th>
                            <vs-th>PRECIO</vs-th>
                            <vs-th>DESCUENTO</vs-th>
                            <vs-th>BODEGA</vs-th>
                            <vs-th>SUBTOTAL</vs-th>
                            <vs-th class="text-center">IVA</vs-th>
                            <vs-th class="text-center" v-if="factura.gastos=='1g'">IMP</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                v-for="(tr, index) in data"
                                :key="index"
                                class="fila_lista"
                            >
                                <vs-td style="width:5%!important;">{{
                                    tr.cod_principal
                                }}</vs-td>
                                <vs-td style="width:25%!important;">
                                    <vx-tooltip
                                        class="w-full"
                                        v-if="tr.nomb"
                                        :text="tr.nomb"
                                        position="top"
                                        style="display: inline-flex"
                                    >
                                        <vs-select
                                            class="selectExample w-full"
                                            placeholder="Seleccione un producto"
                                            style=""
                                            autocomplete
                                            v-model="tr.nombre"
                                            @input="
                                                select_producto_xml(
                                                    index,
                                                    tr.nombre
                                                )
                                            "
                                        >
                                            <vs-select-item
                                                :key="index"
                                                :value="index"
                                                :text="item.nombre"
                                                v-for="(item,
                                                index) in producto_xml"
                                            /> </vs-select></vx-tooltip
                                ></vs-td>
                                <vs-td style="width:15%!important;">
                                    <vs-select
                                        class="selectExample w-full"
                                        placeholder="Seleccione un proyecto"
                                        v-model="tr.proyecto"
                                    >
                                        <vs-select-item
                                            :key="index"
                                            :value="item.id_proyecto"
                                            :text="item.descripcion"
                                            v-for="(item,
                                            index) in proyectos_menu"
                                        />
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div
                                            v-for="err in tr.errorproyecto"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <vs-input
                                        class="w-full derecha"
                                        v-model="tr.cantidad"
                                        @keyup="dividir_retenciones()"
                                        @input="pushlotes(index)"
                                    />
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div
                                            v-for="err in tr.errorcantidad_prod"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <vs-input
                                        class="w-full derecha"
                                        placeholder="$0.00"
                                        v-model="tr.precio"
                                        @keyup="dividir_retenciones()"
                                    />
                                    <div v-show="error" v-if="!tr.precio">
                                        <div
                                            v-for="err in tr.errorprecio"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:180px!important;">
                                    <vx-input-group>
                                        <vs-input
                                            class="w-full derecha"
                                            placeholder="$0.00"
                                            v-model="tr.descuento"
                                            @keyup="dividir_retenciones()"
                                        />
                                        <template slot="append">
                                            <div class="append-text btn-addon">
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 1
                                                    }"
                                                    @click="tr.p_descuento = 1"
                                                >
                                                    $
                                                </button>
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 0
                                                    }"
                                                    @click="tr.p_descuento = 0"
                                                >
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td
                                    style="width:175px!important;"
                                    v-if="tr.sector == 2"
                                >
                                    SERVICIO
                                </vs-td>
                                <vs-td style="width:175px!important;" v-else>
                                    <vs-select
                                        placeholder="buscar"
                                        autocomplete
                                        class="selectExample w-full"
                                        v-model="tr.id_bodega"
                                    >
                                        <vs-select-item
                                            v-for="(tr, index) in listarbodegas"
                                            :key="index"
                                            :value="tr.id_bodega"
                                            :text="tr.nombre"
                                        />
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.id_bodega">
                                        <div
                                            v-for="err in tr.errorid_bodega"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <template v-if="tr.p_descuento == 1">
                                        $
                                        {{
                                            (tr.subtotal = (
                                                tr.cantidad * tr.precio -
                                                tr.descuento
                                            ).toFixed(2))
                                        }}
                                    </template>
                                    <template v-else>
                                        $
                                        {{
                                            (tr.subtotal = (
                                                tr.cantidad * tr.precio -
                                                (tr.cantidad *
                                                    tr.precio *
                                                    tr.descuento) /
                                                    100
                                            ).toFixed(2))
                                        }}
                                    </template>
                                </vs-td>
                                <vs-td>
                                    <vs-switch
                                        v-if="tr.iva2 == 1"
                                        v-model="tr.siiva"
                                    />
                                    <vs-switch
                                        v-else
                                        v-model="tr.siiva"
                                        @click="cambiarivas(index)"
                                    />
                                </vs-td>
                                <vs-td v-if="factura.gastos=='1g'">
                                    <vs-switch
                                        
                                        
                                        v-model="tr.importacion"
                                    />
                                </vs-td>
                                 <feather-icon
                                v-if="tr.sector == 1"
                                                    icon="ClipboardIcon"
                                                    svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                                    style="vertical-align: middle;display: table-cell;"
                                                    class="pointer"
                                                    @click="abrirlotes(index)"
                                                /> 
                                <feather-icon
                                    icon="TrashIcon"
                                    svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                    style="vertical-align: middle;display: table-cell;"
                                    class="eliminar_producto_icono"
                                    @click="eliminar_producto(index)"
                                />
                            </vs-tr>
                        </template>
                    </vs-table>-->

                    <vs-table
                        hoverFlat
                        :data="producto.lista_productos"
                        style="font-size: 12px;"
                    >
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
                            <vs-th class="text-center" v-if="factura.gastos=='1g'">IMP</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                v-for="(tr, index) in data"
                                :key="index"
                                class="fila_lista"
                            >
                                <vs-td v-if="tr.cod_alterno">{{tr.cod_alterno}}</vs-td><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td class="nombrearreglo" v-if="tr.xmlmode == false">
                                    <vx-tooltip
                                        :text="tr.nomb"
                                        position="top"
                                        style="display: inline-flex"
                                        >{{ tr.nombre }}
                                    </vx-tooltip>
                                </vs-td>

                                <vs-td style="width:25%!important;" v-if="tr.xmlmode == true">
                                    <vx-tooltip
                                        class="w-full"
                                        v-if="tr.nomb"
                                        :text="tr.nomb"
                                        position="top"
                                        style="display: inline-flex"
                                    >
                                        <vs-select
                                            class="selectExample w-full"
                                            placeholder="Seleccione un producto"
                                            style=""
                                            autocomplete
                                            v-model="tr.nombre"
                                            @input="
                                                select_producto_xml(
                                                    index,
                                                    tr.nombre
                                                )
                                            "
                                        >
                                            <vs-select-item
                                                :key="index"
                                                :value="index"
                                                :text="item.nombre"
                                                v-for="(item,
                                                index) in producto_xml"
                                            /> </vs-select></vx-tooltip
                                ></vs-td>

                                <vs-td>
                                    <vs-select
                                        class="selectExample w-full"
                                        placeholder="Seleccione un proyecto"
                                        v-model="tr.proyecto"
                                    >
                                        <vs-select-item
                                            :key="index"
                                            :value="item.id_proyecto"
                                            :text="item.descripcion"
                                            v-for="(item,
                                            index) in proyectos_menu"
                                        />
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.proyecto">
                                        <div
                                            v-for="err in tr.errorproyecto"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <vs-input
                                        class="w-full derecha"
                                        v-model="tr.cantidad"
                                        @keyup="dividir_retenciones()"
                                        @input="pushlotes(index)"
                                    />
                                    <div v-show="error" v-if="!tr.cantidad">
                                        <div
                                            v-for="err in tr.errorcantidad_prod"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <vs-input
                                        class="w-full derecha"
                                        placeholder="$0.00"
                                        v-model="tr.precio"
                                        @keyup="dividir_retenciones()"
                                    />
                                    <div v-show="error" v-if="!tr.precio">
                                        <div
                                            v-for="err in tr.errorprecio"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:180px!important;">
                                    <vx-input-group>
                                        <vs-input
                                            class="w-full derecha"
                                            placeholder="$0.00"
                                            v-model="tr.descuento"
                                            @keyup="dividir_retenciones()"
                                        />
                                        <template slot="append">
                                            <div class="append-text btn-addon">
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 1
                                                    }"
                                                    @click="tr.p_descuento = 1"
                                                >
                                                    $
                                                </button>
                                                <button
                                                    class="botonstl"
                                                    :class="{
                                                        elejido:
                                                            tr.p_descuento == 0
                                                    }"
                                                    @click="tr.p_descuento = 0"
                                                >
                                                    %
                                                </button>
                                            </div>
                                        </template>
                                    </vx-input-group>
                                </vs-td>
                                <vs-td
                                    style="width:175px!important;"
                                    v-if="tr.sector == 2"
                                >
                                    SERVICIO
                                </vs-td>
                                <vs-td style="width:175px!important;" v-else>
                                    <vs-select
                                        placeholder="buscar"
                                        autocomplete
                                        class="selectExample w-full"
                                        v-model="tr.id_bodega"
                                    >
                                        <vs-select-item
                                            v-for="(tr, index) in listarbodegas"
                                            :key="index"
                                            :value="tr.id_bodega"
                                            :text="tr.nombre"
                                        />
                                    </vs-select>
                                    <div v-show="error" v-if="!tr.id_bodega">
                                        <div
                                            v-for="err in tr.errorid_bodega"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                                </vs-td>
                                <vs-td style="width:100px!important;">
                                    <template v-if="tr.p_descuento == 1">
                                        $
                                        {{
                                            (tr.subtotal = (
                                                tr.cantidad * tr.precio -
                                                tr.descuento
                                            ).toFixed(2))
                                        }}
                                    </template>
                                    <template v-else>
                                        $
                                        {{
                                            (tr.subtotal = (
                                                tr.cantidad * tr.precio -
                                                (tr.cantidad *
                                                    tr.precio *
                                                    tr.descuento) /
                                                    100
                                            ).toFixed(2))
                                        }}
                                    </template>
                                </vs-td>
                                <vs-td>
                                    <vs-switch
                                        v-if="tr.iva2 == 1"
                                        :disabled="true"
                                        v-model="tr.siiva"
                                    />
                                    <vs-switch
                                        v-else
                                        v-model="tr.siiva"
                                        @click="cambiarivas(index)"
                                    />
                                </vs-td>
                                <vs-td v-if="factura.gastos=='1g'">
                                    <vs-switch
                                        
                                        
                                        v-model="tr.importacion"
                                    />
                                </vs-td>
                                <vs-td>
                                    <feather-icon
                                     v-if="tr.sector == 1"
                                                    icon="ClipboardIcon"
                                                    svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                                    style="vertical-align: middle;display: table-cell;"
                                                    class="pointer"
                                                    @click="abrirlotes(index)"
                                                />
                                    <feather-icon
                                        icon="TrashIcon"
                                        svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                        style="vertical-align: middle;display: table-cell;"
                                        class="eliminar_producto_icono"
                                        @click="eliminar_producto(index)"
                                    />
                                </vs-td>
                            </vs-tr>
                        </template>

                    </vs-table>
    
                        
                </div>

                <div class="vx-col sm:w-full w-full mb-6 relative">
                    <vs-input
                        class="w-full busqueda_cliente focuspr"
                        placeholder="Agrega productos a esta factura"
                        v-model="producto.busqueda"
                        @keyup="listar_productos(producto.busqueda)"
                    />
                    <feather-icon
                        icon="SearchIcon"
                        svgClasses="w-8 h-8 hover:text-primary stroke-current cursor-pointer"
                        class="busqueda_cliente_icono"
                    />
                    <div
                        class="busqueda_lista busqueda_producto_ls"
                        style="display:none;"
                    >
                        <div v-if="preloader.productos">
                            <ul class="ul_busqueda_lista">
                                <li
                                    v-for="(tr, index) in producto.productos"
                                    :key="index"
                                    @click="seleccionar_productos(tr)"
                                >
                                    <span
                                        v-if="tr.cod_alterno"
                                        style="font-weight: bold;"
                                        >CodAlt: {{ tr.cod_alterno }} -
                                    </span>
                                    <span v-else style="font-weight: bold;"
                                        >CódPrin:
                                        {{ tr.cod_principal }} - </span
                                    ><span style="font-weight: bold;">{{
                                        tr.nombre
                                    }}</span>
                                    <span
                                        v-if="tr.presentacion"
                                        style="font-weight: bold;"
                                    >
                                        - Presentación: {{ tr.presentacion }}
                                    </span>
                                    <span
                                        v-if="tr.presentacion"
                                        style="font-weight: bold;"
                                    >
                                        - Presentación: {{ tr.presentacion }}
                                    </span>
                                    <span v-if="tr.nombrebodega"
                                        >-
                                        <span style="font-size: 12px;"
                                            >Bodega: {{ tr.nombrebodega }}</span
                                        ></span
                                    >
                                    <span
                                        v-if="
                                            !tr.nombrebodega && tr.sector == 1
                                        "
                                        >-
                                        <span style="font-size: 12px;"
                                            >Producto sin Bodega</span
                                        ></span
                                    >
                                </li>
                            </ul>
                        </div>
                        <div v-else>
                            <ul class="ul_busqueda_lista lista_preloader">
                                <div class="preloader"></div>
                            </ul>
                        </div>
                    </div>
                    <div v-show="error" v-if="!producto.busqueda">
                        <div
                            v-for="err in error.producto.busqueda"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col w-full">
                    <div class="vx-row" v-if="producto.tipo">
                        <div class="vx-col sm:w-1/2 w-full">
                            <h6>Observaciones:</h6>
                            <vs-textarea
                                class="w-full"
                                v-model="factura.observacion"
                                rows="5"
                            />
                        </div>
                        <div class="vx-col sm:w-1/2 w-full">
                            <div class="cabezera_total">
                                <div>
                                    SUBTOTAL FINAL
                                    <span>$ {{ formulas.subtotal }}</span>
                                </div>
                                <div v-if="formulas.subtotal12 > 0">
                                    SUBTOTAL IVA 12%
                                    <span>$ {{ formulas.subtotal12 }}</span>
                                </div>
                                <div v-if="formulas.valor12 > 0">
                                    Valor IVA 12%
                                    <span>$ {{ formulas.valor12 }}</span>
                                </div>
                                <div v-if="formulas.subtotal0 > 0">
                                    SUBTOTAL IVA 0%
                                    <span>$ {{ formulas.subtotal0 }}</span>
                                </div>
                                <div v-if="formulas.no_impuesto > 0">
                                    NO OBJETO DE IMPUESTO
                                    <span>$ {{ formulas.no_impuesto }}</span>
                                </div>
                                <div v-if="formulas.exento > 0">
                                    EXENTO DE IVA
                                    <span>$ {{ formulas.exento }}</span>
                                </div>
                                <div>
                                    TOTAL DESCUENTO
                                    <span>$ {{ formulas.descuento }}</span>
                                </div>
                                <div>
                                    PROPINA
                                    <span>
                                        <vx-input-group>
                                            <vs-input
                                                class="w-full"
                                                placeholder="$0.00"
                                                v-model="propinapr"
                                            />
                                            <template slot="append">
                                                <div
                                                    class="append-text btn-addon"
                                                >
                                                    <button
                                                        class="botonstl"
                                                        :class="{
                                                            'botonstl elejido':
                                                                pp_descuento ==
                                                                1,
                                                            botonstl:
                                                                pp_descuento !=
                                                                1
                                                        }"
                                                        @click="
                                                            pp_descuento = 1
                                                        "
                                                    >
                                                        $
                                                    </button>
                                                    <button
                                                        class="botonstl"
                                                        :class="{
                                                            'botonstl elejido':
                                                                pp_descuento ==
                                                                0,
                                                            botonstl:
                                                                pp_descuento !=
                                                                0
                                                        }"
                                                        @click="
                                                            pp_descuento = 0
                                                        "
                                                    >
                                                        %
                                                    </button>
                                                </div>
                                            </template>
                                        </vx-input-group>
                                    </span>
                                </div>
                                <div>
                                    VALOR TOTAL
                                    <span>$ {{ formulas.total }}</span>
                                </div>
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
                <vs-switch
                    vs-icon-on="check"
                    color="success"
                    v-model="creditos.estado"
                    class="ml-2"
                    @click="cambioscreditos()"
                    vs-value="Si"
                    style="margin-top: 4px;"
                >
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-if="creditos.estado">
                    <div class="vx-col sm:w-1/3 w-full mb-2 text-center">
                        <label class="vs-input--label">Periodo de pago</label>
                        <vs-select
                            placeholder="Selecciona el periodo de pago"
                            autocomplete
                            class="selectExample w-full"
                            v-model="creditos.periodo"
                        >
                            <vs-select-item value text="Slecciona el periodo" />
                            <vs-select-item value="Dias" text="Dias" />
                            <vs-select-item value="Semanas" text="Semanas" />
                            <vs-select-item value="Meses" text="Meses" />
                            <vs-select-item value="Años" text="Años" />
                        </vs-select>
                        <div v-show="error" v-if="!creditos.periodo">
                            <div
                                v-for="err in error.creditos.periodo"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input
                            class="w-full text-center"
                            label="Tiempos Pago"
                            v-model="creditos.tiempo"
                        />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Plazos de pago</label>
                        <vs-select
                            placeholder="Seleccione"
                            autocomplete
                            class="selectExample w-full"
                            v-model="creditos.plazos"
                        >
                            <vs-select-item
                                v-for="(v, index) in 24"
                                :key="index"
                                :value="v"
                                :text="v + ' Periodos'"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <vs-input
                            class="w-full text-center"
                            label="Monto de pago"
                            v-model="creditos.monto"
                        />
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-2 text-center">
                        <label class="vs-input--label">Pago por letra</label>
                        <div class="mt-2">$ {{ pagoletra }}</div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy">
                <h3>Retenciones</h3>
                <vs-switch
                    vs-icon-on="check"
                    color="success"
                    class="ml-2"
                    v-model="retenciones.estado"
                    @click="cambiosretenciones()"
                    vs-value="Si"
                    style="margin-top: 4px;"
                >
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div
                    class="vx-row leading-loose p-base"
                    v-show="retenciones.estado"
                >
                    <div class="w-full">
                        <p
                            style="text-align: center;font-weight: bold;font-size: 18px;margin-bottom: 8px;"
                        >
                            Retención N°
                            {{ factura.clave_acceso.substring(24, 27) }}-{{
                                factura.clave_acceso.substring(27, 30)
                            }}-{{ factura.clave_acceso.substring(30, 39) }}
                        </p>
                        <div
                            class="vx-row hovertrash"
                            v-for="(tr, index) in valorretenciones"
                            :key="index"
                        >
                            <div class="w-2/3 ml-auto mr-auto">
                                <div class="vx-row">
                                    <div class="vx-col md:w-2/3 text-center">
                                        <label class="vs-input--label"
                                            >Valores por IVA</label
                                        >
                                        <vs-select
                                            @change="agregarretencioniva(index)"
                                            placeholder="Selecciona la retención"
                                            autocomplete
                                            class="selectExample w-full"
                                            v-model="tr.iva"
                                        >
                                            <vs-select-item
                                                v-for="(tr,
                                                index) in listretenciones"
                                                :key="index"
                                                :value="tr"
                                                :text="tr.descrip_retencion"
                                                v-if="
                                                    tr.tipo_retencion ==
                                                        'Retencion IVA Compras'
                                                "
                                            />
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Base"
                                            class="w-full derecha"
                                            v-model="tr.baseiva"
                                            @keyup="
                                                agregarretencionivavalor(
                                                    index,
                                                    tr.baseiva
                                                )
                                            "
                                        />
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Porcentaje"
                                            class="w-full derecha"
                                            disabled
                                            v-model="tr.porcentajeiva"
                                        />
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <vs-input
                                            label="Val. Ret."
                                            class="w-full derecha"
                                            disabled
                                            v-model="tr.cantidadiva"
                                        />
                                    </div>
                                </div>
                                <div class="vx-row">
                                    <div
                                        class="vx-col md:w-2/3 w-full mb-2 mr-auto text-center"
                                    >
                                        <label class="vs-input--label"
                                            >Valores por RENTA</label
                                        >
                                        <vs-select
                                            @change="
                                                agregarretencionrenta(index)
                                            "
                                            placeholder="Selecciona la retención"
                                            autocomplete
                                            class="selectExample w-full"
                                            v-model="tr.renta"
                                        >
                                            <vs-select-item
                                                v-for="(tr,
                                                index) in listretenciones"
                                                :key="index"
                                                :value="tr"
                                                :text="tr.descrip_retencion"
                                                v-if="
                                                    tr.tipo_retencion ==
                                                        'Retencion Fuente Compras'
                                                "
                                            />
                                        </vs-select>
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Base"
                                            class="w-full derecha"
                                            v-model="tr.baserenta"
                                            @keyup="
                                                agregarretencionrentavalor(
                                                    index,
                                                    tr.baserenta
                                                )
                                            "
                                        />
                                    </div>
                                    <div class="flex-1 mb-2 mr-3 text-center">
                                        <vs-input
                                            label="Porcentaje"
                                            class="w-full derecha"
                                            disabled
                                            v-model="tr.porcentajerenta"
                                        />
                                    </div>
                                    <div class="flex-1 mb-2 text-center">
                                        <vs-input
                                            label="Val. Ret."
                                            class="w-full derecha"
                                            disabled
                                            v-model="tr.cantidadrenta"
                                        />
                                    </div>
                                    <vs-divider position="left"></vs-divider>
                                </div>
                            </div>
                            <feather-icon
                                icon="TrashIcon"
                                style="position: absolute !important;right: 125px;margin-top: 80px;display: none;"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer trasher"
                                @click="eliminararrayretencion(index)"
                            />
                            <feather-icon
                                @click="addretenciones()"
                                icon="PlusIcon"
                                style="position: absolute !important;right: 125px;margin-top: 55px;display: none;"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer trasher"
                            />
                            <div
                                class="vx-col sm:w-full w-full mb-2 text-center"
                            >
                                <h6 class="mt-4">Clave de acceso:</h6>
                                <p>{{ factura.clave_acceso }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
            <vs-divider position="left" class="flexy">
                <h3>Pagos</h3>
                <vs-switch
                    vs-icon-on="check"
                    color="success"
                    class="ml-2"
                    v-model="pagos.estado"
                    @click="cambiospagosrec()"
                    vs-value="Si"
                    style="margin-top: 4px;"
                >
                    <span slot="off">No</span>
                </vs-switch>
            </vs-divider>
            <transition name="slide-fade">
                <div class="vx-row leading-loose p-base" v-show="pagos.estado">
                    <div class="w-full">
                        <div
                            class="vx-row hovertrash"
                            v-for="(tr, index) in pagos.datos"
                            :key="index"
                        >
                            <div
                                class="vx-col w-full mb-2 text-center ml-auto sm:w-1/6"
                                :class="{
                                    'ml-auto': tr.metodo_pago == 'Anticipo'
                                }"
                            >
                                <label class="vs-input--label"
                                    >Método de pago</label
                                >
                                <vs-select
                                    placeholder="Selecciona el método de pago"
                                    autocomplete
                                    class="selectExample w-full"
                                    v-model="tr.metodo_pago"
                                >
                                    <vs-select-item
                                        v-for="(tr, index) in formapagos"
                                        :key="index"
                                        :value="tr.id_forma_pagos"
                                        :text="tr.descripcion"
                                    />
                                </vs-select>
                                <div
                                    v-show="error.error"
                                    v-if="!tr.metodo_pago"
                                >
                                    <div
                                        v-for="err in tr.errormetodo"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2 text-center"
                                v-if="tr.metodo_pago != 'Anticipo'"
                            >
                                <vs-select
                                    class="selectExample w-full"
                                    label="Banco"
                                    vs-multiple
                                    autocomplete
                                    v-model="tr.banco_pago"
                                >
                                    <vs-select-item
                                        v-for="data in bancos"
                                        :key="data.id_banco"
                                        :value="data.id_banco"
                                        :text="data.nombre_banco"
                                    />
                                </vs-select>
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2 text-center"
                            >
                                <vs-input
                                    class="w-full text-center"
                                    label="Valor"
                                    v-model="tr.cantidad_pago"
                                />
                                <div
                                    v-show="error.error"
                                    v-if="parseFloat(tr.cantidad_pago) <= 0"
                                >
                                    <div
                                        v-for="err in tr.errorcantidad"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2 text-center mr-auto"
                                v-if="tr.metodo_pago == 'Anticipo'"
                            >
                                <vs-input
                                    class="w-full text-center"
                                    label="Anticipo Total"
                                    disabled
                                    :value="anticipoexistente"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2 text-center"
                                v-if="tr.metodo_pago != 'Anticipo'"
                            >
                                <vs-input
                                    class="w-full text-center"
                                    label="Nro de transacción"
                                    v-model="tr.nro_trans"
                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2"
                                v-if="tr.metodo_pago != 'Anticipo'"
                            >
                                <label class="vs-input--label"
                                    >Fecha de pago</label
                                >
                                <flat-pickr
                                    :config="configdateTimePicker"
                                    class="w-full"
                                    v-model="tr.fecha_pago"
                                    placeholder="Seleccionar"
                                ></flat-pickr>
                            </div>
                            <div
                                class="vx-col sm:w-1/6 w-full mb-2"
                                v-if="tr.metodo_pago != 'Anticipo'"
                            >
                                <label class="vs-input--label"
                                    >Plan Cuenta</label
                                >
                                <vx-input-group>
                                    <vs-input
                                        class="w-full"
                                        v-model="tr.cuenta"
                                    />
                                    <template slot="append">
                                        <div class="append-text btn-addon">
                                            <vs-button
                                                color="primary"
                                                @click="
                                                    abrir_plan_cuentas_pagos1(
                                                        index
                                                    )
                                                "
                                                >Buscar</vs-button
                                            >
                                            <vs-button
                                                color="danger"
                                                type="flat"
                                                @click="eliminarplc(index)"
                                                >X</vs-button
                                            >
                                        </div>
                                    </template>
                                </vx-input-group>
                            </div>
                            <feather-icon
                                icon="TrashIcon"
                                style="position: absolute!important;right: 15px;margin-top: 44px;display:none"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer trasher"
                                @click="eliminararraypagos(index)"
                            />
                            <feather-icon
                                icon="PlusIcon"
                                style="position: absolute!important;right: 15px;margin-top: 26px;display:none"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer trasher"
                                @click="addpagos()"
                            />
                        </div>
                    </div>
                </div>
            </transition>
            <div class="vx-col w-full mt-5">
                <vs-button
                    color="success"
                    type="filled"
                    :disabled="disabled"
                    @click="guardar_factura()"
                    >GUARDAR</vs-button
                >
                <vs-button color="danger" type="filled" @click="enviado()"
                    >CANCELAR</vs-button
                >
            </div>
            <!-- Crear Cliente -->
            <vs-popup
                :title="modal.titulo"
                :active.sync="modal.abrir"
                class="modal-xl"
            >
                <div class="con-exemple-prompt">
                    <ProveedorVue
                        v-if="modal.abrir == true || exist_data_prov_xml > 0"
                        factura="1"
                        :valores="datos_xml_prov"
                        @CreateProveedor="guardar_proveedor"
                        @CancelarCreate="cancelar_proveedor"
                    ></ProveedorVue>
                </div>
            </vs-popup>
            <!-- Cuentas Contables -->
            <vs-popup
                :title="modalcontable.titulo"
                :active.sync="modalcontable.abrir"
                style="z-index:99999999999"
            >
                <div class="con-exemple-prompt">
                    <vs-input
                        class="mb-4 md:mb-0 mr-4 w-full"
                        v-model="plan_cuenta.buscar"
                        placeholder="buscar"
                        @keyup="listar_cuenta_contable(plan_cuenta.buscar)"
                    />
                    <table class="vs-table mt-3" style="wudth:100%;">
                        <thead class="vs-table--thead">
                            <tr>
                                <th>No.Cuenta</th>
                                <th>Tipo Cuenta</th>
                            </tr>
                        </thead>
                        <tbody v-if="modalcontable.tipo != 3">
                            <tr
                                v-for="(tr, index) in plan_cuenta.lista"
                                :key="index"
                                @click="escoger_plan_cuenta(tr)"
                                class="tablavista"
                            >
                                <td>{{ tr.codcta }}</td>
                                <td>{{ tr.nomcta }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr
                                v-for="(tr, index) in plan_cuenta.lista"
                                :key="index"
                                @click="escoger_plan_cuenta1(tr)"
                                class="tablavista"
                            >
                                <td>{{ tr.codcta }}</td>
                                <td>{{ tr.nomcta }}</td>
                            </tr>
                        </tbody>
                    </table>
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
                        <label class="vs-input--label"
                            >Tipo Identificacion:</label
                        >
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
                                <label class="vs-input--label valoresc"
                                    >Debe</label
                                >
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
                                    productos_asiento[index1].sector ==
                                        'servicio'
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
                                    v-model="
                                        productos_asiento[index1].descripcion
                                    "
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
                    id="two-row"
                    class="vx-row"
                    v-for="data in ice"
                    :key="data.id_detalle"
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
                <!-- {{igualar}} -->
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
                            <div class="vx-col sm:w-2/12 w-full mb-2" v-if="data.haber>0">
                                <vs-input
                                    class="w-full"
                                    v-model="data.haber"
                                    disabled

                                />
                            </div>
                            <div
                                class="vx-col sm:w-1/12 w-full mb-2"
                                v-if="data.haber > 0 && data.bansel !== null"
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
                            <div
                                class="vx-col sm:w-1/12 w-full mb-6"
                                v-if="data.haber > 0 && data.bansel !== null"
                            >
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                    color="success"
                                    @click="
                                        agregarcampoConciliacion(index, 'plc')
                                    "
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
                            <div
                                class="vx-col sm:w-1/12 w-full mb-6"
                                v-if="data.haber > 0 && data.bansel !== null"
                            >
                                <vs-button
                                    type="filled"
                                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                                    color="success"
                                    @click="
                                        agregarcampoConciliacion(
                                            index,
                                            'anticipo'
                                        )
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
                    v-for="data in creditos_asiento"
                    :key="data.id_proveedor"
                >
                    <div class="vx-col xs:w-10/12 sm:w-11/12">
                        <div class="vx-row">
                            <!--CUENTA CONTABLE-->
                            <div
                                class="vx-col sm:w-1/3 w-full mb-6"
                                v-if="data.exist_plan_cuenta_prov == 'si'"
                            >
                                <vx-input-group>
                                    <!-- prettier-ignore -->
                                    <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta_prov"
                                        disabled
                                    />
                                </vx-input-group>
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                                <vx-input-group>
                                    <!-- prettier-ignore -->
                                    <vs-input
                                        class="w-full"
                                        v-model="data.nombre_cuenta_grupo_prov"
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
                    id="five-row"
                    class="vx-row"
                    v-for="data in retencion_renta"
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
                <div class="vx-row">
                    <div class="vx-col xs:w-10/12 sm:w-11/12">
                        <div class="vx-row">
                            <div class="vx-col sm:w-1/2 w-full mb-6"></div>

                            <div class="vx-col sm:w-2/12 w-full mb-6">
                                <label class="vs-input--label center"
                                    >Total</label
                                >
                            </div>
                            <div class="vx-col sm:w-2/12 w-full mb-6">
                                <label class="vs-input--label center"
                                    >Total</label
                                >
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
                <!-- {{Decimales}} -->
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
                <vs-popup
                    title="Conciliacion"
                    :active.sync="modal_conciliacion"
                >
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
            <!-- .......................Popup Agregar Informacion Lotes Producto Ingresos.............................-->
            <vs-popup
                classContent="popup-example"
                class="listpopup"
                title="Información Lotes de Producto"
                :active.sync="popuplotes"
            >
                <div class="vx-col w-full">
                    <vs-table
                        stripe
                        v-if="producto.lista_productos[lugar]"
                        :data="producto.lista_productos[lugar].lotes"
                    >
                        <template slot="thead">
                            <vs-th>Código</vs-th>
                            <vs-th>Nombre</vs-th>
                            <vs-th class="text-center">Cantidad</vs-th>
                            <vs-th class="text-center">Lote</vs-th>
                            <vs-th class="text-center">Fecha Fabricación</vs-th>
                            <vs-th class="text-center">Fecha Vencimiento</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                                <vs-td v-if="tr.cod_principal">{{
                                    tr.cod_principal
                                }}</vs-td>
                                <vs-td v-if="tr.nombre">{{ tr.nombre }}</vs-td>
                                <vs-td :data="tr.cantidad"
                                    ><vs-input
                                        class="w-full txt-center"
                                        v-model="tr.cantidad"
                                        @input="calculo_lotes()"
                                /></vs-td>
                                <vs-td :data="tr.lote"
                                    ><vs-input
                                        class="w-full txt-center"
                                        v-model="tr.lote"
                                /></vs-td>
                                <vs-td :data="tr.fecha_fabricacion">
                                    <flat-pickr
                                        class="w-full"
                                        :config="configdateTimePicker"
                                        v-model="tr.fecha_fabricacion"
                                    />
                                </vs-td>
                                <vs-td :data="tr.fecha_vencimiento">
                                    <flat-pickr
                                        class="w-full"
                                        :config="configdateTimePicker"
                                        v-model="tr.fecha_vencimiento"
                                    />
                                </vs-td>
                                <vs-td>
                                    <vx-tooltip
                                        text="Eliminar"
                                        position="top"
                                        style="display: inline-flex;"
                                    >
                                        <feather-icon
                                            icon="TrashIcon"
                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                            class="pointer"
                                            @click="eliminar_lote(indextr)"
                                        /> </vx-tooltip
                                ></vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                    <vs-button
                        color="primary"
                        type="filled"
                        icon="add_circle"
                        style="width: 25px !important; height: 25px !important;"
                        @click="add_lote()"
                    >
                    </vs-button>
                </div>
                <vs-divider />
                <div
                    class="vx-row md:w-full sm:w-full w-full mt-4 mb-2 text-center"
                >
                    <div class="vx-col md:1/3 sm:1/3 w-1/3 ml-auto">
                        <label class="vs-input--label">TOTAL INGRESO</label>
                        <h1>
                            {{ totales_lotes.cantidad_ingreso }}
                        </h1>
                    </div>
                    <div class="vx-col md:1/3 sm:1/3 w-1/3 mr-auto">
                        <label class="vs-input--label">TOTAL LOTES</label>
                        <h1>
                            {{ totales_lotes.total_lote }}
                        </h1>
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col w-1/3 ml-auto mr-auto mt-6 text-center">
                        <vs-button
                            color="success"
                            type="filled"
                            @click="guardar_lotes()"
                            >Guardar</vs-button
                        >
                    </div>
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
const {
    rutasEmpresa: { DATA_EMPRESA }
} = require("../../../../../../config-routes/config");
import script_comprobantes from "../../../../factura.js";
import ProveedorVue from "../Proveedor/ProveedorAgregar.vue";

export default {
    components: {
        flatPickr,
        "v-select": vSelect,
        ProveedorVue
    },
    data() {
        return {
            disabled: false,
            configdateTimePicker: {
                locale: SpanishLocale
            },
            modal: {
                abrir: false,
                titulo: "",
                tipo: 0
            },
            modalcontable: {
                abrir: false,
                titulo: "",
                tipo: 0
            },
            factura: {
                nfactura: "",
                fecha_emision: moment().format("YYYY-MM-DD"),
                fecha_validez: moment().format("YYYY-MM-DD"),
                autorizacion: "",
                proyectos: null,
                tipo_sustento: null,
                destino_pago: null,
                gastos: "0g",
                importacion:false,
                orden_compra: "",
                docutributario: "1t",
                clave_acceso: "Generando la clave de acceso",
                observacion: "",
                id_orden: "",
                tipo_comprobante: null
            },
            exist_data_prov_xml: 0,
            datos_xml_prov: null,
            nombre_prov: "",
            cliente: {
                tipo: false,
                busqueda: "",
                clientes: [],
                id_cliente: "",
                nombre: "",
                telefono: "",
                email: "",
                tipo_identificacion: "",
                identificacion: "",
                direccion: ""
            },
            crear_cliente: {
                codigo: "",
                nombre: "",
                tipo_identificacion: "",
                identificacion: "",
                grupo_cliente: "",
                tipo_cliente: "",
                grupo_tributario: "",
                direccion: "",
                provincia: null,
                canton: null,
                parroquia: null,
                parte_relacionada: "",
                e_mail: "",
                telefono: "",
                contacto: "",
                vendedor: null,
                estado: null,
                descuento: "",
                cuenta_contable: "",
                id_cuenta_contable: null,
                numero_pagos: "",
                lista_precios: "",
                forma_pago: null,
                limite_credito: "",
                comentario: ""
            },
            producto: {
                tipo: false,
                busqueda: "",
                productos: [],
                lista_productos: [],
                id_producto: null,
                codigo: "",
                nombre: "",
                cantidad: "",
                precio: 0,
                descuento: 0,
                subtotal: 0
            },
            //variables para lotes
            popuplotes: false,
            lugar: null,
            totales_lotes: { cantidad_ingreso: 0, total_lote: 0 },
            sololotes: [],
            ////////////
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
            cuenta_contable_menu: [],
            plan_cuenta: {
                buscar: "",
                lista: []
            },
            proyectos_menu: [],
            empresa: [],
            formapagos: [],
            creditos: {
                estado: false,
                periodo: "",
                tiempo: 1,
                plazos: 3,
                monto: 0,
                pago: 0
            },
            retenciones: {
                estado: false,
                listaretenciones: [],
                data: [
                    {
                        iva: {
                            lista: null,
                            valor: 0,
                            porcentaje: 0,
                            cantidad: 0
                        },
                        renta: {
                            lista: null,
                            base: 0,
                            porcentaje: 0,
                            cantidad: 0
                        }
                    }
                ]
            },
            disabled_asiento: false,
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
            pagos: {
                estado: false,
                datos: [
                    {
                        metodo_pago: "",
                        banco_pago: "",
                        cantidad_pago: 0,
                        nro_trans: "",
                        fecha_pago: "",
                        cuenta: "",
                        plan_cuenta: null
                    }
                ]
            },
            bancos: [],
            totalef: 0,
            propinapr: null,
            pp_descuento: 1,
            sustentos: [],
            //tipo comprobantes
            tipo_comprobantes: [],
            formapagos: [],
            imports: [],
            preloader: {
                cliente: false,
                productos: false
            },
            error: {
                error: 0,
                factura: {
                    fecha_emision: [],
                    numero_factura: [],
                    numero_autorizacion: [],
                    tipo_sustento: [],
                    destino_pago: [],
                    tipo_comprobante: []
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
            listarbodegas: [],
            anticipoexistente: 0,
            //variables Contabilizar
            modalAsiento: false,
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
            creditos_asiento: [],
            retencion_iva: [],
            retencion_renta: [],
            pagos_asientos: [],
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
            num_mayor_iva: [],
            num_mayor_renta: [],
            posicion_iva: 0,
            posicion_renta: 0,
            errorchip_correo: [],
            chip_nombre: [],
            chip_correo: [],
            estado_asiento: "",

            //funcionalidad xml
            xmlmode: false,
            filemode: false,
            filexml: null,
            filepdf: null,
            reader: "",
            producto_xml: [],
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
        //computed gastos de importacion
        exist_import(){
            if(this.factura.gastos=='0g'){
                this.factura.importacion=null;
            }
        },
        ////////
        formulas() {
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
                    if (el.iva == 2) {
                        subtotal12 += el.precio * el.cantidad - el.descuento;
                    }
                    if (el.iva == 1) {
                        subtotal0 += el.precio * el.cantidad - el.descuento;
                    }
                    if (el.iva == 3) {
                        no_impuesto += el.precio * el.cantidad - el.descuento;
                    }
                    if (el.iva == 4) {
                        exento += el.precio * el.cantidad - el.descuento;
                    }
                    if (isNaN(parseFloat(el.descuento))) {
                        descuento += 0;
                    } else {
                        descuento += parseFloat(el.descuento);
                    }
                } else {
                    subtotal +=
                        el.precio * el.cantidad -
                        (el.cantidad * el.precio * el.descuento) / 100;
                    if (el.iva == 2) {
                        subtotal12 +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                    if (el.iva == 1) {
                        subtotal0 +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                    if (el.iva == 3) {
                        no_impuesto +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                    if (el.iva == 4) {
                        exento +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                    if (
                        isNaN(
                            (parseFloat(el.precio) *
                                parseFloat(el.cantidad) *
                                parseFloat(el.descuento)) /
                                100
                        )
                    ) {
                        descuento += 0;
                    } else {
                        descuento +=
                            (parseFloat(el.precio) *
                                parseFloat(el.cantidad) *
                                parseFloat(el.descuento)) /
                            100;
                    }
                }
                valor12 = subtotal12 * 0.12;
            });
            total += subtotal + valor12;

            if (this.pp_descuento == 1) {
                if (parseFloat(this.propinapr) >= 0) {
                    propina = parseFloat(this.propinapr);
                }
            } else {
                if (parseFloat(this.propinapr) >= 0) {
                    propina =
                        (parseFloat(total) * parseFloat(this.propinapr)) / 100;
                }
            }
            total = total + propina;
            if (this.valorretenciones.length == 1) {
                this.valorretenciones[0].baserenta = parseFloat(
                    subtotal
                ).toFixed(2);
                this.valorretenciones[0].baseiva = parseFloat(valor12).toFixed(
                    2
                );
            } else {
                if (this.valorretenciones.length == 2) {
                    this.valorretenciones[1].baserenta = parseFloat(
                        subtotal - this.valorretenciones[0].baserenta
                    ).toFixed(2);
                    this.valorretenciones[1].baseiva = parseFloat(
                        valor12 - this.valorretenciones[0].baseiva
                    ).toFixed(2);
                    console.log(this.valorretenciones[1].baserenta);
                } else {
                    if (this.valorretenciones.length == 3) {
                        this.valorretenciones[2].baserenta = parseFloat(
                            subtotal -
                                this.valorretenciones[0].baserenta -
                                this.valorretenciones[1].baserenta
                        ).toFixed(2);
                        this.valorretenciones[2].baseiva = parseFloat(
                            valor12 -
                                this.valorretenciones[0].baseiva -
                                this.valorretenciones[1].baseiva
                        ).toFixed(2);
                    }
                }
            }

            return {
                subtotal: subtotal.toFixed(2),
                subtotal12: subtotal12.toFixed(2),
                valor12: valor12.toFixed(2),
                subtotal0: subtotal0.toFixed(2),
                valor0: valor0.toFixed(2),
                no_impuesto: no_impuesto.toFixed(2),
                exento: exento.toFixed(2),
                descuento: descuento.toFixed(2),
                total: total.toFixed(2)
            };
        },
        pagoletra() {
            var res = 0;
            var res = this.creditos.monto / this.creditos.plazos;
            return res.toFixed(2);
        },
        total_pendiente() {
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
                if (parseFloat(el.cantidadiva) >= 0 && el.iva != null) {
                    iva = parseFloat(el.cantidadiva);
                }
                if (parseFloat(el.cantidadrenta) >= 0 && el.renta != null) {
                    renta = parseFloat(el.cantidadrenta);
                }
                retencion += iva + renta;
            });
            this.pagos.datos.forEach(el => {
                if (parseFloat(el.cantidad_pago) >= 0) {
                    paga = parseFloat(el.cantidad_pago);
                }
                pagas += paga;
            });
            if (this.creditos.monto <= 0) {
                creditos = 0;
            } else {
                creditos = this.creditos.monto;
            }

            total =
                parseFloat(this.formulas.total) -
                parseFloat(retencion) -
                parseFloat(creditos) -
                parseFloat(pagas);
            if (parseFloat(total) < 0.01 && parseFloat(total) >= -0.02) {
                total = 0;
            }
            return total.toFixed(2);
        },
        total_pagado() {
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
                if (parseFloat(el.cantidadiva) >= 0) {
                    iva = parseFloat(el.cantidadiva);
                }
                if (parseFloat(el.cantidadrenta) >= 0) {
                    renta = parseFloat(el.cantidadrenta);
                }
                retencion += iva + renta;
            });
            this.pagos.datos.forEach(el => {
                if (parseFloat(el.cantidad_pago) >= 0) {
                    paga = parseFloat(el.cantidad_pago);
                }
                pagas += paga;
            });
            if (this.creditos.estado) {
                if (this.creditos.monto <= 0) {
                    creditos = 0;
                } else {
                    creditos = parseFloat(this.creditos.monto);
                }
            }
            total =
                parseFloat(retencion) +
                parseFloat(creditos) +
                parseFloat(pagas);
            if (parseFloat(total) < 0.01) {
                total = 0;
            }
            return total.toFixed(2);
        },
        //computed de asientos
        suma_debe() {
            var total = 0;
            if (this.productos_asiento.length > 0) {
                this.productos_asiento.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }
            if (this.iva_asiento.length > 0) {
                this.iva_asiento.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }
            if (this.ice.length > 0) {
                this.ice.forEach(el => {
                    if (el.debe !== null) {
                        total += parseFloat(el.debe);
                    }
                });
            }

            this.total_debe = total.toFixed(2);
        },
        suma_haber() {
            var total = 0;
            if (this.creditos_asiento.length > 0) {
                this.creditos_asiento.forEach(el => {
                    if (el.haber !== null) {
                        total += parseFloat(el.haber);
                    }
                });
            }
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    total += parseFloat(el.haber);
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    total += parseFloat(el.haber);
                });
            }
            if (this.pagos_sin_plc.length > 0) {
                this.pagos_sin_plc.forEach(el => {
                    total += parseFloat(el.haber);
                });
            }
            if (this.pagos_con_plc.length > 0) {
                this.pagos_con_plc.forEach(el => {
                    total += parseFloat(el.haber);
                });
            }
            if (this.pagos_anticipo.length > 0) {
                this.pagos_anticipo.forEach(el => {
                    total += parseFloat(el.haber);
                });
            }

            this.total_haber = total.toFixed(2);
        },
        cambioDecimales() {
            if (this.creditos_asiento.length > 0) {
                this.creditos_asiento.forEach(el => {
                    if (el.haber !== null) {
                        el.haber = parseFloat(el.haber).toFixed(2);
                    }
                });
            }
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    el.haber = parseFloat(el.haber).toFixed(2);
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    el.haber = parseFloat(el.haber).toFixed(2);
                });
            }
            if (this.pagos_sin_plc.length > 0) {
                this.pagos_sin_plc.forEach(el => {
                    el.haber = parseFloat(el.haber).toFixed(2);
                });
            }
            if (this.pagos_con_plc.length > 0) {
                this.pagos_con_plc.forEach(el => {
                    el.haber = parseFloat(el.haber).toFixed(2);
                });
            }
            if (this.pagos_anticipo.length > 0) {
                this.pagos_anticipo.forEach(el => {
                    el.haber = parseFloat(el.haber).toFixed(2);
                });
            }
            if (this.productos_asiento.length > 0) {
                this.productos_asiento.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
            if (this.iva_asiento.length > 0) {
                this.iva_asiento.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
            if (this.ice.length > 0) {
                this.ice.forEach(el => {
                    if (el.debe !== null) {
                        el.debe = parseFloat(el.debe).toFixed(2);
                    }
                });
            }
        },
        Diferencia() {
            if (this.iva_asiento.length > 0) {
                if (this.iva_asiento.length == 1) {
                    if (
                        parseFloat(this.iva_asiento[0].debe) !==
                        parseFloat(this.total_doce_iva)
                    ) {
                        var diferencia_iva_asiento =
                            parseFloat(this.total_doce_iva) -
                            parseFloat(this.iva_asiento[0].debe);
                        var total_iva_asiento =
                            parseFloat(this.iva_asiento[0].debe) +
                            diferencia_iva_asiento;
                        this.iva_asiento[0].debe = total_iva_asiento;
                        //this.iva_asiento[0].haber=diferencia_iva_asiento;
                        console.log(
                            "Diferebcia en el debe: iva:" + total_iva_asiento
                        );
                    }
                }
            }
            if (this.creditos_asiento.length > 0) {
                if (this.creditos_asiento.length == 1) {
                    if (
                        parseFloat(this.creditos_asiento[0].haber) !==
                        parseFloat(this.creditos_asiento[0].total_pago)
                    ) {
                        var diferencia_creditos_asiento =
                            parseFloat(this.creditos_asiento[0].total_pago) -
                            parseFloat(this.creditos_asiento[0].haber);
                        var total_creditos_asiento =
                            parseFloat(this.creditos_asiento[0].haber) +
                            diferencia_creditos_asiento;
                        this.creditos_asiento[0].haber = total_creditos_asiento;
                        //this.creditos_asiento[0].haber=diferencia_creditos_asiento;
                        console.log(
                            "Diferebcia en el haber: creditos_asiento:" +
                                total_creditos_asiento
                        );
                    }
                }
            }
            if (this.pagos_sin_plc.length > 0) {
                if (this.pagos_sin_plc.length == 1) {
                    if (
                        parseFloat(this.pagos_sin_plc[0].haber) !==
                        parseFloat(this.total_pagos_sin_plc)
                    ) {
                        var diferencia_pagos_sin_plc =
                            parseFloat(this.total_pagos_sin_plc) -
                            parseFloat(this.pagos_sin_plc[0].haber);
                        var total_pagos_sin_plc =
                            parseFloat(this.pagos_sin_plc[0].haber) +
                            diferencia_pagos_sin_plc;
                        this.pagos_sin_plc[0].haber = total_pagos_sin_plc;
                        //this.pagos_sin_plc[0].haber=diferencia_pagos_sin_plc;
                        console.log(
                            "Diferebcia en el haber: pagos_sin_plc:" +
                                total_pagos_sin_plc
                        );
                    }
                }
            }
            if (this.pagos_con_plc.length > 0) {
                if (this.pagos_con_plc.length == 1) {
                    if (
                        parseFloat(this.pagos_con_plc[0].haber) !==
                        parseFloat(this.total_pagos_con_plc)
                    ) {
                        var diferencia_pagos_con_plc =
                            parseFloat(this.total_pagos_con_plc) -
                            parseFloat(this.pagos_con_plc[0].haber);
                        var total_pagos_con_plc =
                            parseFloat(this.pagos_con_plc[0].haber) +
                            diferencia_pagos_con_plc;
                        this.pagos_con_plc[0].haber = total_pagos_con_plc;
                        console.log(
                            "Diferebcia en el haber: pagos_con_plc:" +
                                total_pagos_con_plc
                        );
                    }
                }
            }
            if (this.pagos_anticipo.length > 0) {
                if (this.pagos_anticipo.length == 1) {
                    if (
                        parseFloat(this.pagos_anticipo[0].haber) !==
                        parseFloat(this.total_pagos_anticipo)
                    ) {
                        var diferencia_pagos_anticipo =
                            parseFloat(this.total_pagos_anticipo) -
                            parseFloat(this.pagos_anticipo[0].haber);
                        var total_pagos_anticipo =
                            parseFloat(this.pagos_anticipo[0].haber) +
                            diferencia_pagos_anticipo;
                        this.pagos_anticipo[0].haber = total_pagos_anticipo;
                        //this.pagos_anticipo[0].haber=diferencia_pagos_anticipo;
                        console.log(
                            "Diferebcia en el haber: pagos_anticipo:" +
                                total_pagos_anticipo
                        );
                    }
                }
            }
            if (this.retencion_iva.length > 0) {
                if (this.retencion_iva.length == 1) {
                    if (
                        parseFloat(this.retencion_iva[0].haber) !==
                        parseFloat(this.total_retencion_iva)
                    ) {
                        var diferencia_retencion_iva =
                            parseFloat(this.total_retencion_iva) -
                            parseFloat(this.retencion_iva[0].haber);
                        var total_retencion_iva =
                            parseFloat(this.retencion_iva[0].haber) +
                            diferencia_retencion_iva;
                        this.retencion_iva[0].haber = total_retencion_iva;
                        //this.retencion_iva[0].haber=diferencia_retencion_iva;
                        console.log(
                            "Diferebcia en el haber: retencion iva:" +
                                total_retencion_iva
                        );
                    }
                }
            }
            if (this.retencion_renta.length > 0) {
                if (this.retencion_renta.length == 1) {
                    if (
                        parseFloat(this.retencion_renta[0].haber) !==
                        parseFloat(this.total_retencion_renta)
                    ) {
                        var diferencia_retencion_renta =
                            parseFloat(this.total_retencion_renta) -
                            parseFloat(this.retencion_renta[0].haber);
                        var total_retencion_renta =
                            parseFloat(this.retencion_renta[0].haber) +
                            diferencia_retencion_renta;
                        this.retencion_renta[0].haber = total_retencion_renta;
                        //this.retencion_renta[0].haber=diferencia_retencion_renta;
                        console.log(
                            "Diferebcia en el haber: retencion renta:" +
                                total_retencion_renta
                        );
                    }
                }
            }

            if (this.total_debe > this.total_haber) {
                this.diferencia_debe = parseFloat(
                    this.total_haber - this.total_debe
                );
                console.log(this.total_debe + ": al debe");
            }
            if (this.total_debe < this.total_haber) {
                this.diferencia_haber = this.total_debe - this.total_haber;
                console.log(this.total_haber + ": al haber");
            }
            var diferencia = 0;
            // if(this.productos_asiento.length>0){
            //     if(parseFloat(this.total_debe).toFixed(2)!==parseFloat(this.total_haber).toFixed(2)){
            //         console.log(this.diferencia_debe+"sss"+this.diferencia_haber);
            //         diferencia=parseFloat(this.total_haber-this.total_debe).toFixed(2);
            //         console.log("diferencia total:"+diferencia);
            //     }
            //     if(diferencia!==0){
            //         var total_diferencia=this.productos_asiento[0].debe+diferencia;
            //         this.productos_asiento[0].debe=total_diferencia;
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
                                            debe:
                                                parseFloat(elemento.debe) +
                                                parseFloat(valorActual.debe)
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
                                                debe:
                                                    parseFloat(elemento.debe) +
                                                    parseFloat(valorActual.debe)
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
                                                debe:
                                                    parseFloat(elemento.debe) +
                                                    parseFloat(valorActual.debe)
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
                                    debe:
                                        parseFloat(elemento.debe) +
                                        parseFloat(valorActual.debe)
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
                                    return {
                                        ...elemento,
                                        haber:
                                            parseFloat(elemento.haber) +
                                            parseFloat(valorActual.haber)
                                    };
                                }
                                if (
                                    elemento.id_plan_cuentas ===
                                    valorActual.id_plan_cuentas
                                ) {
                                    elemento.acumula =
                                        parseFloat(elemento.haber) +
                                        parseFloat(valorActual.haber);
                                }
                                return elemento;
                            });
                        }

                        return [...acumulador, valorActual];
                    },
                    []
                );
                console.log(JSON.stringify(this.retencion_iva) + "proyecto");
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
            //console.log(JSON.stringify(this.retencion_renta)+"proyecto");
            //console.log(JSON.stringify(this.productos_asiento)+"proyecto");
            //console.log(JSON.stringify(array));
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
                if (this.retencion_iva[0].total_iva !== total_iva) {
                    cambio_iva = total_iva - this.retencion_iva[0].total_iva;
                    this.retencion_iva[otro_iva].haber =
                        this.retencion_iva[otro_iva].haber - cambio_iva;
                }
                //console.log(JSON.stringify(elementoYaExiste2)+"exist");
                //}
            }
            if (this.retencion_renta.length > 0) {
                if (this.retencion_renta[0].total_renta !== total_renta) {
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
        mayor() {
            var num = 0;
            if (this.retencion_iva.length > 0) {
                num = Math.max.apply(null, this.retencion_iva);
            }

            console.log(num);
        }
    },
    methods: {
        dividir_retenciones() {
            var subtotal = 0;
            var valor12 = 0;
            var subtotal12 = 0;

            this.producto.lista_productos.forEach(el => {
                if (el.p_descuento == 1) {
                    subtotal += el.precio * el.cantidad - el.descuento;
                    if (el.iva == 2) {
                        subtotal12 += el.precio * el.cantidad - el.descuento;
                    }
                } else {
                    subtotal +=
                        el.precio * el.cantidad -
                        (el.cantidad * el.precio * el.descuento) / 100;
                    if (el.iva == 2) {
                        subtotal12 +=
                            el.precio * el.cantidad -
                            (el.cantidad * el.precio * el.descuento) / 100;
                    }
                }
                valor12 = subtotal12 * 0.12;
            });

            this.valorretenciones.forEach(el => {
                el.baserenta = parseFloat(
                    subtotal / this.valorretenciones.length
                ).toFixed(2);
                el.baseiva = parseFloat(
                    valor12 / this.valorretenciones.length
                ).toFixed(2);
            });
        },
        listar_cliente(buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                this.preloader.cliente = false;
                axios
                    .get(
                        "/api/factura_compra/listar_proveedor?buscar=" +
                            buscar +
                            "&usuario=" +
                            this.usuario.id_empresa
                    )
                    .then(({ data }) => {
                        $(".busqueda_lista_proveedor").show();
                        this.cliente.clientes = data;
                        setTimeout(() => {
                            this.preloader.cliente = true;
                        }, 100);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 800);
        },
        listar_productos(buscar) {
            this.preloader.productos = false;
            $(".busqueda_producto_ls").show();
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                axios
                    .post("/api/notacredito/listar_productos1", {
                        buscar: buscar,
                        id_empresa: this.usuario.id_empresa,
                        id_establecimiento: this.usuario.id_establecimiento,
                        cliente: this.cliente.id_cliente
                    })
                    .then(({ data }) => {
                        this.preloader.productos = true;
                        if (data.length >= 1) {
                            if (
                                data[0].codigo_barras == buscar &&
                                data[0].codigo_barras.length >= 1
                            ) {
                                this.seleccionar_productos(data[0]);
                                this.producto.busqueda = "";
                                return;
                            } else {
                                this.producto.productos = data;
                                return;
                            }
                        } else {
                            this.producto.productos = [];
                            return;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                        this.preloader.productos = true;
                    });
            }, 800);
        },
        listar_creacion_cliente() {
            axios
                .get(
                    "/api/notacredito/listar_creacion_cliente/" +
                        this.usuario.id_empresa
                )
                .then(({ data }) => {
                    this.grupo_cliente_menu = data.grupo_cliente;
                    this.tipo_cliente_menu = data.tipo_cliente;
                    this.provincia_menu = data.provincia;
                    this.vendedor_menu = data.vendedor;
                    this.forma_pago_menu = data.forma_pago;
                    this.proyectos_menu = data.proyectos;
                    this.empresa = data.empresa;
                    this.factura.ambiente = data.empresa.ambiente;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        listarcanton(id) {
            axios
                .get("/api/notacredito/listar_canton/" + id)
                .then(({ data }) => {
                    this.canton_menu = data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        listarparroquia(id) {
            axios
                .get("/api/notacredito/listar_parroquia/" + id)
                .then(({ data }) => {
                    this.parroquia_menu = data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        listar_cuenta_contable(buscar) {
            axios
                .get(
                    "/api/notacredito/listar_cuenta_contable?empresa=" +
                        this.usuario.id_empresa +
                        "&buscar=" +
                        buscar
                )
                .then(({ data }) => {
                    this.plan_cuenta.lista = data;
                })
                .catch(error => {
                    console.log(error);
                });
        },
        seleccionar_cliente(tr) {
            this.cliente.clientes = [];
            this.cliente.busqueda = "";
            this.cliente.tipo = true;
            this.cliente.id_cliente = tr.id_proveedor;
            this.cliente.nombre = tr.nombre_proveedor;
            this.cliente.telefono = tr.contacto;
            this.cliente.email = tr.email;
            this.cliente.tipo_identificacion = tr.tipo_identificacion;
            this.cliente.identificacion = tr.identif_proveedor;
            this.cliente.direccion = tr.direccion_prov;
            $(".busqueda_lista_proveedor").hide();
            this.anticipover(tr.id_proveedor);
        },
        anticipover(id) {
            axios
                .get("/api/anticipototalcompra?id=" + id)
                .then(({ data }) => {
                    if (data) {
                        this.anticipoexistente = parseFloat(data).toFixed(2);
                    } else {
                        this.anticipoexistente = parseFloat(0).toFixed(2);
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        seleccionar_productos(tr) {
            this.producto.productos = [];
            this.producto.busqueda = "";
            this.producto.tipo = true;
            var subtotal = (tr.precio - tr.descuento).toFixed(2);
            var cantidad = 1;

            if (isNaN(parseInt(tr.existencia_total))) {
                tr.existencia_total = "";
            }
            if (isNaN(parseFloat(tr.precio))) {
                tr.precio = "";
            }
            if (isNaN(parseFloat(tr.descuento))) {
                tr.descuento = 0;
            }
            if (
                tr.sector == 1 &&
                (tr.id_producto_bodega === "undefined" ||
                    tr.id_producto_bodega == null)
            ) {
                cantidad = 0;
                tr.cantidad = 0;
                tr.id_producto_bodega = null;
                tr.nombrebodega = null;
            }
            var siiva = false;
            if (tr.iva == 1) {
                siiva = false;
            } else {
                siiva = true;
            }
            if (tr.sector == 1) {
                this.producto.lista_productos.push({
                    id_producto_bodega: tr.id_producto_bodega,
                    id_bodega: tr.id_bodega,
                    nombrebodega: tr.nombrebodega,
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
                    importacion:this.factura.gastos=='1g'?true:false,
                    proyecto: this.proyectos_menu[0].id_proyecto,
                    xmlmode: false,
                    lotes: [
                        {
                            cod_principal: tr.cod_principal,
                            nombre: tr.nombre,
                            cantidad: cantidad,
                            lote: "",
                            fecha_fabricacion: moment().format("YYYY-M-D"),
                            fecha_vencimiento: moment().format("YYYY-M-D"),
                            id_producto: tr.id_producto,
                            id_proyecto: this.proyectos_menu[0].id_proyecto,
                            id_bodega: tr.id_bodega
                        }
                    ]
                });
            } else {
                this.producto.lista_productos.push({
                    id_producto_bodega: tr.id_producto_bodega,
                    id_bodega: tr.id_bodega,
                    nombrebodega: tr.nombrebodega,
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
                    importacion:this.factura.gastos=='1g'?true:false,
                    proyecto: this.proyectos_menu[0].id_proyecto,
                    xmlmode: false,
                    lotes: []
                });
            }

            $(".focuspr").focus();
        },
        cambiarivas(index) {
            if (this.producto.lista_productos[index].siiva) {
                this.producto.lista_productos[index].iva = 1;
            } else {
                this.producto.lista_productos[
                    index
                ].iva = this.producto.lista_productos[index].iva2;
            }
        },
        // escoger_plan_cuenta(tr) {
        //     this.crear_cliente.cuenta_contable = tr.codcta;
        //     this.crear_cliente.id_cuenta_contable = tr.id_plan_cuentas;
        //     this.modalcontable.abrir = false;
        // },
        escoger_plan_cuenta1(tr) {
            this.pagos.datos[this.pagos.index].cuenta = tr.codcta;
            this.pagos.datos[this.pagos.index].plan_cuenta = tr.id_plan_cuentas;
            this.modalcontable.abrir = false;
        },
        abrir_modal_crear_cliente() {
            this.modal = {
                abrir: true,
                titulo: "Crear Proveedor",
                tipo: 1
            };
            this.crear_cliente = {
                codigo: "",
                nombre: "",
                tipo_identificacion: { label: "Seleccione", value: 0 },
                identificacion: "",
                grupo_cliente: "",
                tipo_cliente: "",
                grupo_tributario: "",
                direccion: "",
                provincia: null,
                canton: null,
                parroquia: null,
                parte_relacionada: "",
                e_mail: "",
                telefono: "",
                contacto: "",
                vendedor: null,
                estado: null,
                descuento: "",
                cuenta_contable: "",
                id_cuenta_contable: null,
                numero_pagos: "",
                lista_precios: "",
                forma_pago: null,
                limite_credito: "",
                comentario: ""
            };
        },
        guardar_proveedor(value) {
            console.log(JSON.stringify(value));
            axios
                .post("/api/agregarproveedor", {
                    cod_proveedor: value.cod_proveedor,
                    grupo: value.grupo,
                    nombre_proveedor: value.nombre_proveedor,
                    tipo_identificacion: value.tipo_identificacion,
                    identif_proveedor: value.identif_proveedor,
                    //tipo_proveedor:value.tipo,
                    contribuyente: value.contribuyente,
                    beneficiario: value.beneficiario,
                    //identif_benefic:value.identificacionBenf,
                    contacto: value.contacto,
                    email: value.email,
                    direccion_prov: value.direccion_prov,
                    nrcasa: value.nrcasa,
                    telefono_prov: value.telefono_prov,
                    //estado_prov: value.estado,
                    tipo_cuenta: value.tipo_cuenta,
                    cta_banco: value.cta_banco,
                    id: value.id,
                    //nrcta_interbancaria:value.nrctaInterbancaria,
                    pagos: value.pagos,
                    plazo: value.plazo,
                    dias_pago: value.dias_pago,
                    tip_comprob: value.tip_comprob,
                    serie: value.serie,
                    fvalidez: value.fvalidez,
                    comentario: value.comentario,
                    rangomax: value.rangomax,
                    rangomin: value.rangomin,
                    nrautorizacion: value.nrautorizacion,
                    contribuye_sri: value.contribuye_sri,
                    tip_electronico: value.tip_electronico,
                    imp_retencion: value.imp_retencion,
                    codsri_imp: value.codsri_imp,
                    retencion_iva: value.retencion_iva,
                    codsri_iva: value.codsri_iva,
                    cta_contable: value.cta_contable,
                    id_contable: value.id_contable,
                    id_provincia: value.id_provincia,
                    id_ciudad: value.id_ciudad,
                    id_banco: value.id_banco,
                    id_empresa: this.usuario.id_empresa,
                    tipo_contribuyente: value.tipo_contribuyente,
                    emails: value.emails,
                    factura_compra: 1
                })

                .then(res => {
                    if (res.data == "vacio") {
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                    }
                    if (res.data == "mal") {
                        this.$vs.notify({
                            title: "Este Proveedor ya existe",
                            text:
                                "La identificacion de este proveedor ya existe",
                            color: "danger"
                        });
                        return;
                    }

                    this.$vs.notify({
                        title: "Registro Guardado",
                        text: "Registro Guardado exitosamente",
                        color: "success"
                    });
                    this.cliente.nombre = value.nombre_proveedor;
                    this.cliente.telefono = value.telefono_prov;
                    this.cliente.email = value.emails.toString();
                    this.cliente.tipo_identificacion =
                        value.tipo_identificacion;
                    this.cliente.identificacion = value.identif_proveedor;
                    this.cliente.direccion = value.direccion_prov;
                    this.cliente.id_cliente = res.data;
                    this.cliente.tipo = true;
                    this.modal.abrir = false;
                    // console.log(this.cliente.id_cliente);
                })
                .catch(err => {});
        },
        cancelar_proveedor(c) {
            this.cliente.nombre = "";
            this.cliente.telefono = "";
            this.cliente.email = "";
            this.cliente.tipo_identificacion = "";
            this.cliente.identificacion = "";
            this.cliente.direccion = "";
            this.cliente.id_cliente = "";
            this.cliente.tipo = false;
            this.cliente.busqueda = "";
            this.modal.abrir = false;
        },
        abrir_plan_cuentas() {
            this.modalcontable = {
                abrir: true,
                titulo: "Crear Cliente",
                tipo: 1
            };
        },
        eliminar_producto(id) {
            this.producto.lista_productos.splice(id, 1);
            if (!this.producto.lista_productos.length) {
                this.producto.tipo = false;
            }
        },
        // verificarcliente() {
        //     axios
        //         .get(
        //             "/api/notacredito/verificarcliente/" +
        //                 this.usuario.id_empresa
        //         )
        //         .then(({ data }) => {
        //             if (data == "vacio") {
        //                 this.crear_cliente.codigo = "";
        //             } else {
        //                 this.crear_cliente.codigo = data;
        //             }
        //         })
        //         .catch(error => {
        //             console.log(error);
        //         });
        // },
        guardar_cliente() {
            axios
                .post("/api/notacredito/guardar_cliente", {
                    cliente: this.crear_cliente,
                    empresa: this.usuario.id_empresa
                })
                .then(({ data }) => {
                    this.$vs.notify({
                        time: 8000,
                        title: "Cliente guardado",
                        text: "El cliente se guardo exitosamente",
                        color: "success"
                    });
                    this.modal.abrir = false;
                    this.cliente.busqueda = "";
                })
                .catch(error => {
                    console.log(error);
                });
        },
        guardar_factura() {
            this.disabled = true;
            if (this.validar()) {
                this.disabled = false;
                return;
            }
            if (this.formulas.total !== this.total_pagado) {
                var n1 = Number(this.formulas.total);
                var n2 = Number(this.total_pagado);
                console.log(
                    this.total_pendiente +
                        " total_pendiente" +
                        parseFloat(n1 - n2).toFixed(2)
                );
                this.$vs.notify({
                    time: 8000,
                    title: "No se puede Guardar la factura",
                    text:
                        "Todavía existe un saldo pendiente de $ " +
                        parseFloat(n1 - n2).toFixed(2),
                    color: "danger"
                });
                this.disabled = false;
                return;
            }
            if (this.total_pendiente < 0) {
                this.$vs.notify({
                    time: 8000,
                    title: "No se puede Guardar la factura",
                    text: "No se puede guardar un saldo en negativo",
                    color: "danger"
                });
                this.disabled = false;
                return;
            }
            var alerta = false;
            var valoranticipo = 0;
            this.pagos.datos.forEach(el => {
                if (el.metodo_pago == "Anticipo") {
                    alerta = true;
                    valoranticipo += parseFloat(el.cantidad_pago);
                    console.log(el.metodo_pago);
                    console.log(valoranticipo);
                }
                //console.log(el.metodo_pago);
            });

            if (alerta) {
                if (valoranticipo > this.anticipoexistente) {
                    this.$vs.notify({
                        time: 8000,
                        title: "El valor a Pagar excede el limite",
                        text:
                            "El valor a pagar excede el anticipo maximo del cliente",
                        color: "danger"
                    });
                    this.disabled = false;
                    return;
                }
            }

            if (this.retenciones.estado) {
                if (
                    this.valorretenciones[0].iva != null ||
                    this.valorretenciones[0].renta != null
                ) {
                    axios
                        .post("/api/factura_compra/guardar_factura_clave", {
                            factura: this.factura,
                            id_empresa: this.usuario.id_empresa
                        })
                        .then(({ data }) => {
                            if (data == "repetido") {
                                var url =
                                    "/api/listarclaveretencion/" +
                                    this.usuario.id;
                                axios.get(url).then(res => {
                                    var fecha = moment(
                                        this.factura.fecha_emision
                                    ).format("DDMMYYYY");
                                    var rec = res.data.recupera[0];
                                    var secuencial = this.zeroFill(
                                        res.data.secuencial,
                                        9
                                    );
                                    var establecimiento = this.zeroFill(
                                        rec.establecimiento,
                                        3
                                    );
                                    var punto_emision = this.zeroFill(
                                        rec.punto_emision,
                                        3
                                    );
                                    var codigoacc =
                                        fecha +
                                        "07" +
                                        rec.ruc_empresa +
                                        rec.ambiente +
                                        establecimiento +
                                        punto_emision +
                                        secuencial +
                                        "12345678" +
                                        1;
                                    var acceso = this.Modulo11(codigoacc);
                                    this.factura.clave_acceso =
                                        codigoacc + acceso;
                                    this.enviarfactura();
                                });
                            } else {
                                this.enviarfactura();
                            }
                        })
                        .catch(error => {
                            console.log(error);
                            this.disabled = false;
                        });
                } else {
                    this.enviarfactura();
                }
            } else {
                this.enviarfactura();
            }
        },
        enviarfactura() {
            // let formData = new FormData();
            // if (this.factura != null)
            //     formData.append("factura", JSON.stringify(this.factura));
            // if (this.producto.lista_productos != null)
            //     formData.append(
            //         "productos",
            //         JSON.stringify(this.producto.lista_productos)
            //     );
            // if (this.empresa != null)
            //     formData.append("empresa", JSON.stringify(this.empresa));
            // if (this.usuario != null)
            //     formData.append("usuario", JSON.stringify(this.usuario));
            // if (this.cliente.id_cliente != null)
            //     formData.append(
            //         "cliente",
            //         JSON.stringify(this.cliente.id_cliente)
            //     );
            // if (this.formulas.subtotal != null)
            //     formData.append(
            //         "subtotal",
            //         JSON.stringify(this.formulas.subtotal)
            //     );
            // if (this.formulas.subtotal12 != null)
            //     formData.append(
            //         "subtotal12",
            //         JSON.stringify(this.formulas.subtotal12)
            //     );
            // if (this.formulas.valor12 != null)
            //     formData.append(
            //         "valor12",
            //         JSON.stringify(this.formulas.valor12)
            //     );
            // if (this.formulas.subtotal0 != null)
            //     formData.append(
            //         "subtotal0",
            //         JSON.stringify(this.formulas.subtotal0)
            //     );
            // if (this.formulas.valor0 != null)
            //     formData.append("valor0", JSON.stringify(this.formulas.valor0));
            // if (this.formulas.no_impuesto != null)
            //     formData.append(
            //         "no_impuesto",
            //         JSON.stringify(this.formulas.no_impuesto)
            //     );
            // if (this.formulas.exento != null)
            //     formData.append("exento", JSON.stringify(this.formulas.exento));
            // if (this.formulas.descuento != null)
            //     formData.append(
            //         "descuento",
            //         JSON.stringify(this.formulas.descuento)
            //     );
            // if (this.formulas.total != null)
            //     formData.append("total", JSON.stringify(this.formulas.total));
            // if (this.total_pendiente != null)
            //     formData.append(
            //         "total_pendiente",
            //         JSON.stringify(this.total_pendiente)
            //     );
            // if (this.total_pagado != null)
            //     formData.append(
            //         "total_pagado",
            //         JSON.stringify(this.total_pagado)
            //     );
            // if (this.propinapr != null)
            //     formData.append("propinapr", JSON.stringify(this.propinapr));
            // if (this.pp_descuento != null)
            //     formData.append(
            //         "pp_descuento",
            //         JSON.stringify(this.pp_descuento)
            //     );
            // if (this.creditos != null)
            //     formData.append("creditos", JSON.stringify(this.creditos));
            // if (this.retenciones.estado != null)
            //     formData.append(
            //         "retencion_estado",
            //         JSON.stringify(this.retenciones.estado)
            //     );
            // if (this.valorretenciones != null)
            //     formData.append(
            //         "valorretenciones",
            //         JSON.stringify(this.valorretenciones)
            //     );
            // if (this.pagos != null)
            //     formData.append("pagos", JSON.stringify(this.pagos));
            // if (this.cliente != null)
            //     formData.append("proveedor", JSON.stringify(this.cliente));
            // if (this.filexml != null) formData.append("filexml", this.filexml);
            // if (this.filepdf != null) formData.append("filepdf", this.filepdf);
            // axios
            //     .post("/api/factura_compra/guardar_factura", formData)
            this.sololotes = [];
            var conteo=0;
            // this.producto.lista_productos.forEach(el=>{
            //     if(typeof el.lotes!=='undefined'){
            //         if(el.lotes.length>0){
            //             conteo++;
            //             el.lotes.forEach(dl=>{
            //                 this.sololotes.push({
            //                     index:conteo,
            //                     cod_principal:dl.cod_principal,
            //                     lote:dl.lote,
            //                     nombre:dl.nombre,
            //                     cantidad:dl.cantidad,
            //                     fecha_fabricacion:dl.fecha_fabricacion,
            //                     fecha_vencimiento:dl.fecha_vencimiento,
            //                     id_producto:dl.id_producto,
            //                     id_proyecto: dl.id_proyecto,
            //                     id_bodega:dl.id_bodega
            //                 });
            //             });
            //         }
            //     }

            // });
            // console.log("Lotes:" + JSON.stringify(this.sololotes));
            axios
                .post("/api/factura_compra/guardar_factura", {
                    factura: this.factura,
                    productos: this.producto.lista_productos,
                    empresa: this.empresa,
                    usuario: this.usuario,
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
                    total_pendiente: this.total_pendiente,
                    total_pagado: this.total_pagado,
                    propinapr: this.propinapr,
                    pp_descuento: this.pp_descuento,
                    creditos: this.creditos,
                    retencion_estado: this.retenciones.estado,
                    valorretenciones: this.valorretenciones,
                    pagos: this.pagos,
                    proveedor: this.cliente,
                    sololotes: this.sololotes
                })
                .then(({ data }) => {
                    if (data == "error numero") {
                        this.$vs.notify({
                            time: 8000,
                            title: "Error de registro",
                            text:
                                "El número de factura ya existe, verifique nuevamente",
                            color: "danger"
                        });
                        this.disabled = false;
                        return;
                    }
                    if (data == "repetido clave retencion") {
                        this.$vs.notify({
                            time: 8000,
                            title: "Error de registro",
                            text:
                                "El número de retencion ya existe, verifique nuevamente",
                            color: "danger"
                        });
                        this.disabled = false;
                        return;
                    }
                    if (data == "error autorizacion") {
                        this.$vs.notify({
                            time: 8000,
                            title: "Error de registro",
                            text:
                                "El número de autorización ya existe, verifique nuevamente",
                            color: "danger"
                        });
                        this.disabled = false;
                        return;
                    }

                    this.$vs.notify({
                        time: 8000,
                        title: "Registro Guardado",
                        text: "Este registro se guardo exitosamente",
                        color: "success"
                    });
                    this.savefilesfact(data[0].id_factcompra);
                    if (this.retenciones.estado) {
                        if (
                            this.valorretenciones[0].iva != null ||
                            this.valorretenciones[0].renta != null
                        ) {
                            this.$vs.notify({
                                time: 8000,
                                title: "Enviando Retencion de Compra al SRI",
                                text:
                                    "La Retencion de Compra esta siendo enviada, por favor no recargar la página del sistema hasta completar el proceso",
                                color: "primary"
                            });
                            var dataf = data[0];
                            this.recueidfact = data[0].id_factcompra;
                            axios
                                .post("/api/factura/xml_compro_retenc", dataf)
                                .then(res => {
                                    var password = res.data.recupera.pass_firma;
                                    var firma =
                                        DATA_EMPRESA +
                                        this.usuario.id_empresa +
                                        "/firma/" +
                                        res.data.recupera.firma;
                                    var factura =
                                        DATA_EMPRESA +
                                        this.usuario.id_empresa +
                                        "/comprobantes/retencioncompra/" +
                                        this.factura.autorizacion +
                                        ".xml";
                                    var tipo = "retencion_compra";
                                    var carpeta =
                                        DATA_EMPRESA +
                                        this.usuario.id_empresa +
                                        "/comprobantes/retencioncompra/";
                                    var fecha_actual = moment(
                                        dataf.fech_validez
                                    ).format("LL");
                                    this.crearfacturacion(
                                        firma,
                                        password,
                                        factura,
                                        tipo,
                                        this.usuario,
                                        this.recueidfact,
                                        carpeta,
                                        fecha_actual,
                                        dataf.total_factura,
                                        dataf.logo,
                                        dataf.nombre_empresa
                                    );
                                });
                        } else {
                            this.enviadoAsiento(data[0].id_factcompra);
                        }
                    } else {
                        this.enviadoAsiento(data[0].id_factcompra);
                    }
                })
                .catch(error => {
                    this.disabled = false;
                    console.log(error);
                    this.$vs.notify({
                        time: 8000,
                        title: "Error en el guardado",
                        text:
                            "Hubo un problema con el ingreso de la factura, comunique al administrador",
                        color: "danger"
                    });
                });
        },
        listarclave() {
            if (!this.$route.params.id) {
                var url = "/api/listarclaveretencion/" + this.usuario.id;
                axios.get(url).then(res => {
                    var fecha = moment(this.factura.fecha_emision).format(
                        "DDMMYYYY"
                    );
                    var rec = res.data.recupera[0];
                    var secuencial = this.zeroFill(res.data.secuencial, 9);
                    var establecimiento = this.zeroFill(rec.establecimiento, 3);
                    var punto_emision = this.zeroFill(rec.punto_emision, 3);
                    var codigoacc =
                        fecha +
                        "07" +
                        rec.ruc_empresa +
                        rec.ambiente +
                        establecimiento +
                        punto_emision +
                        secuencial +
                        "12345678" +
                        1;
                    var acceso = this.Modulo11(codigoacc);
                    this.factura.clave_acceso = codigoacc + acceso;
                });
                return false;
            }
        },
        zeroFill(number, width) {
            width -= number.toString().length;
            if (width > 0) {
                return (
                    new Array(width + (/\./.test(number) ? 2 : 1)).join("0") +
                    number
                );
            }
            return number + "";
        },
        Modulo11(claveAcceso) {
            var multiplos = [2, 3, 4, 5, 6, 7];
            var i = 0;
            var cantidad = claveAcceso.length;
            var total = 0;
            while (cantidad > 0) {
                total +=
                    parseInt(claveAcceso.substring(cantidad - 1, cantidad)) *
                    multiplos[i];
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
        listarformapagos() {
            axios
                .get("/api/facturaformapagos/" + this.usuario.id_empresa)
                .then(res => {
                    this.formapagos = res.data;
                    this.formapagos.push({
                        id_forma_pagos: "Anticipo",
                        descripcion: "ANTICIPO"
                    });
                })
                .catch(err => {
                    console.log(err);
                });
        },
        cambioscreditos() {
            this.creditos.monto = this.total_pendiente;
            if (this.creditos.estado) {
                this.creditos.monto = (0).toFixed(2);
            }
        },
        cambiosretenciones() {
            if (this.retenciones.estado) {
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
                el.baserenta = parseFloat(
                    this.formulas.subtotal / this.valorretenciones.length
                ).toFixed(2);
            });
        },
        listarretenciones() {
            var url =
                "/api/listarretenciones?empresa=" + this.usuario.id_empresa;
            axios.get(url).then(({ data }) => {
                this.listretenciones = data;
            });
        },
        //retenciones iva y renta
        valorescalculodiv() {
            if (this.monto_credito) {
                var menor = this.monto_credito;
            } else {
                var menor = 0;
            }
            if (this.formulas.total > 0) {
                for (var i = 0; i < this.valorretenciones.length; i++) {
                    this.valorretenciones[i].baserenta = (
                        this.formulas.subtotal / this.valorretenciones.length
                    ).toFixed(2);
                    this.valorretenciones[i].baseiva = (
                        this.formulas.valor12 / this.valorretenciones.length
                    ).toFixed(2);
                    if (this.valorretenciones[i].iva != null) {
                        this.valorretenciones[i].porcentajeiva =
                            this.valorretenciones[i].iva.porcen_retencion + "%";
                        this.valorretenciones[i].cantidadiva = (
                            (this.valorretenciones[i].baseiva *
                                this.valorretenciones[i].iva.porcen_retencion) /
                            100
                        ).toFixed(2);
                    }
                    if (this.valorretenciones[i].renta != null) {
                        this.valorretenciones[i].porcentajerenta =
                            this.valorretenciones[i].renta.porcen_retencion +
                            "%";
                        this.valorretenciones[i].cantidadrenta = (
                            (this.valorretenciones[i].baserenta *
                                this.valorretenciones[i].renta
                                    .porcen_retencion) /
                            100
                        ).toFixed(2);
                    }
                }
            }
        },
        addretenciones() {
            this.valorretenciones.push({
                iva: null,
                porcentajeiva: null,
                cantidadiva: null,
                renta: null,
                baserenta: null,
                porcentajerenta: null,
                cantidadrenta: null,
                errorbase: []
            });
            //this.valorescalculodiv();
        },
        agregarretencioniva(index) {
            if (this.valorretenciones[index].iva != null) {
                this.valorretenciones[index].porcentajeiva =
                    this.valorretenciones[index].iva.porcen_retencion + "%";
                this.valorretenciones[index].cantidadiva = (
                    (this.valorretenciones[index].baseiva *
                        this.valorretenciones[index].iva.porcen_retencion) /
                    100
                ).toFixed(2);
            } else {
                this.valorretenciones[index].porcentajeiva = null;
                this.valorretenciones[index].cantidadiva = null;
            }
        },
        agregarretencionrenta(index) {
            if (this.valorretenciones[index].renta != null) {
                this.valorretenciones[index].porcentajerenta =
                    this.valorretenciones[index].renta.porcen_retencion + "%";
                this.valorretenciones[index].cantidadrenta = (
                    (this.valorretenciones[index].baserenta *
                        this.valorretenciones[index].renta.porcen_retencion) /
                    100
                ).toFixed(2);
            } else {
                this.valorretenciones[index].porcentajerenta = null;
                this.valorretenciones[index].cantidadrenta = null;
            }
        },
        agregarretencionivavalor(index, valor) {
            var total = parseFloat(this.formulas.valor12) - parseFloat(valor);
            var num = parseInt(this.valorretenciones.length) - 1;
            for (var i = 0; i < this.valorretenciones.length; i++) {
                if (i != index) {
                    this.valorretenciones[i].baseiva = (total / num).toFixed(2);
                }
            }
            this.agregarretencionivayrenta(index);
        },
        agregarretencionrentavalor(index, valor) {
            var total = parseFloat(this.formulas.subtotal) - parseFloat(valor);
            var num = parseInt(this.valorretenciones.length) - 1;
            for (var i = 0; i < this.valorretenciones.length; i++) {
                if (i != index) {
                    this.valorretenciones[i].baserenta = (total / num).toFixed(
                        2
                    );
                }
            }
            this.agregarretencionivayrenta(index);
        },
        agregarretencionivayrenta() {
            this.valorretenciones.forEach((el, index) => {
                if (this.valorretenciones[index].iva != null) {
                    this.valorretenciones[index].porcentajeiva =
                        this.valorretenciones[index].iva.porcen_retencion + "%";
                    this.valorretenciones[index].cantidadiva = (
                        (this.valorretenciones[index].baseiva *
                            this.valorretenciones[index].iva.porcen_retencion) /
                        100
                    ).toFixed(2);
                } else {
                    this.valorretenciones[index].porcentajeiva = null;
                    this.valorretenciones[index].cantidadiva = null;
                }
                if (this.valorretenciones[index].renta != null) {
                    this.valorretenciones[index].porcentajerenta =
                        this.valorretenciones[index].renta.porcen_retencion +
                        "%";
                    this.valorretenciones[index].cantidadrenta = (
                        (this.valorretenciones[index].baserenta *
                            this.valorretenciones[index].renta
                                .porcen_retencion) /
                        100
                    ).toFixed(2);
                } else {
                    this.valorretenciones[index].porcentajerenta = null;
                    this.valorretenciones[index].cantidadrenta = null;
                }
            });
        },
        eliminararrayretencion(id) {
            this.valorretenciones.splice(id, 1);
            this.valorescalculodiv();
        },
        listarbanco() {
            axios.get("/api/traerbancofactcomp").then(({ data }) => {
                this.bancos = data;
            });
        },
        cambiospagos() {
            setTimeout(() => {
                var total = 0;
                this.pagos.datos.forEach(el => {
                    total = this.total_pendiente / this.pagos.datos.length;
                    el.cantidad_pago = total;
                });
            }, 50);
        },
        cambiospagosrec() {
            this.pagos.datos.forEach(el => {
                el.cantidad_pago = (0).toFixed(2);
            });
            this.pagos.datos = [
                {
                    metodo_pago: "",
                    banco_pago: null,
                    cantidad_pago: 0,
                    nro_trans: "",
                    fecha_pago: this.factura.fecha_emision,
                    cuenta: "",
                    plan_cuenta: null
                }
            ];
            if (!this.pagos.estado) {
                this.pagos.datos[0].cantidad_pago = this.total_pendiente;
            }
        },
        eliminararraypagos(id) {
            this.pagos.datos.splice(id, 1);
        },
        addpagos() {
            this.pagos.datos.push({
                metodo_pago: "",
                banco_pago: "",
                cantidad_pago: 0,
                nro_trans: "",
                fecha_pago: this.factura.fecha_emision,
                cuenta: "",
                plan_cuenta: null
            });
        },
        listarsustento() {
            axios
                .get("/api/traersustento?empresa=" + this.usuario.id_empresa)
                .then(({ data }) => {
                    this.sustentos = data;
                });
        },
        listarTipoComprobante() {
            axios
                .get("/api/tipcomprob?id_empresa=" + this.usuario.id_empresa)
                .then(({ data }) => {
                    this.tipo_comprobantes = data.recupera;
                });
        },
        listarimportaciones() {
            axios
                .get("/api/traerimport?empresa=" + this.usuario.id_empresa)
                .then(response => {
                    this.imports = response.data;
                });
        },
        validar() {
            this.error = {
                error: 0,
                factura: {
                    fecha_emision: [],
                    numero_factura: [],
                    numero_autorizacion: [],
                    tipo_sustento: [],
                    destino_pago: [],
                    tipo_comprobante: []
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
            };

            if (!this.factura.fecha_emision) {
                this.error.factura.fecha_emision.push(
                    "Debe agregar la fecha de emisión"
                );
                this.error.error = 1;
                console.log(1);
            }
            if (!this.factura.tipo_sustento) {
                this.error.factura.tipo_sustento.push("Obligatorio");
                this.error.error = 1;
                console.log("1.0.1");
            }
            if (!this.factura.destino_pago) {
                this.error.factura.destino_pago.push("Obligatorio");
                this.error.error = 1;
                console.log("1.0.2");
            }
            if (!this.factura.tipo_comprobante) {
                this.error.factura.tipo_comprobante.push("Obligatorio");
                this.error.error = 1;
                console.log("1.0.3");
            }
            if (this.factura.nfactura.length != 15) {
                this.error.factura.numero_factura.push(
                    "Debe ingresar 15 números"
                );
                this.error.error = 1;
                console.log(2);
            }
            if (!this.factura.autorizacion) {
                this.error.factura.numero_autorizacion.push(
                    "Debe agregar N° autorización"
                );
                this.error.error = 1;
                console.log(3);
            }
            if (!this.cliente.tipo) {
                this.error.cliente.tipo.push(
                    "Debe agregar un proveedor al comprobante"
                );
                this.error.error = 1;
                console.log(4);
            }
            if (!this.producto.tipo) {
                this.error.producto.busqueda.push(
                    "Debe agregar un producto al comprobante"
                );
                this.error.error = 1;
                console.log(5);
            }
            for (var i = 0; i < this.producto.lista_productos.length; i++) {
                this.producto.lista_productos[i].errorcantidad_prod = [];
                this.producto.lista_productos[i].errorprecio = [];
                this.producto.lista_productos[i].errorproyecto = [];
                this.producto.lista_productos[i].errorid_bodega = [];
                if (!this.producto.lista_productos[i].cantidad) {
                    this.producto.lista_productos[i].errorcantidad.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                    console.log(6);
                } else {
                    if (
                        this.producto.lista_productos[i].cantidad <= 0 ||
                        this.producto.lista_productos[i].cantidad == "0" ||
                        this.producto.lista_productos[i].cantidad == 0
                    ) {
                        this.producto.lista_productos[
                            i
                        ].errorcantidad_prod.push("Obligatorio");
                        this.error.error = 1;
                        console.log(7.0);
                    }
                }
                console.log("sector" + this.producto.lista_productos[i].sector);
                if (!this.producto.lista_productos[i].precio) {
                    this.producto.lista_productos[i].errorprecio.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                    console.log(6.1);
                }
                if (!this.producto.lista_productos[i].proyecto) {
                    this.producto.lista_productos[i].errorproyecto.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                    console.log(6.2);
                }
                /*if(this.producto.lista_productos[i].id_producto_bodega){

                }else if(this.producto.lista_productos[i].sector){

                }*/

                if (this.producto.lista_productos[i].id_producto == null) {
                    this.$vs.notify({
                        time: 8000,
                        title: "Debe elegir un  Producto para:",
                        text: this.producto.lista_productos[i].nomb,
                        color: "danger"
                    });
                    this.error.error = 1;
                    console.log(6.3);
                }
                if (
                    this.producto.lista_productos[i].id_bodega === null &&
                    (this.producto.lista_productos[i].sector == 1 ||
                        this.producto.lista_productos[i].sector == null)
                ) {
                    this.producto.lista_productos[i].errorid_bodega.push(
                        "Obligatorio"
                    );
                    this.error.error = 1;
                    console.log(6.3);
                }
            }
            if (this.creditos.estado) {
                if (!this.creditos.periodo) {
                    this.error.creditos.periodo.push("Ingrese Periodo");
                    this.error.error = 1;
                    console.log(8);
                }
                if (!this.creditos.tiempo) {
                    this.error.creditos.tiempo.push("Obligatorio");
                    this.error.error = 1;
                    console.log(9);
                }
                if (!this.creditos.plazos) {
                    this.error.creditos.plazos.push("Obligatorio");
                    this.error.error = 1;
                    console.log(10);
                }
                if (parseFloat(this.creditos.monto) <= 0) {
                    this.error.creditos.monto.push("Obligatorio");
                    this.error.error = 1;
                    console.log(11);
                }
            }
            if (this.retenciones.estado) {
                for (var i = 0; i < this.valorretenciones.length; i++) {
                    this.valorretenciones[i].errorbase = [];
                    if (this.valorretenciones[i].renta) {
                        if (
                            parseFloat(this.valorretenciones[i].baserenta) <=
                                0 ||
                            !this.valorretenciones[i].baserenta
                        ) {
                            this.valorretenciones[i].errorbase.push(
                                "Obligatorio"
                            );
                            this.error.error = 1;
                            console.log(12);
                        }
                    }
                }
            }
            if (this.pagos.estado) {
                for (var i = 0; i < this.pagos.datos.length; i++) {
                    this.pagos.datos[i].errormetodo = [];
                    this.pagos.datos[i].errorcantidad = [];
                    if (!this.pagos.datos[i].metodo_pago) {
                        this.pagos.datos[i].errormetodo.push("Obligatorio");
                        this.error.error = 1;
                        console.log(13);
                    }
                    if (parseFloat(this.pagos.datos[i].cantidad_pago) <= 0) {
                        this.pagos.datos[i].errorcantidad.push("Obligatorio");
                        this.error.error = 1;
                        console.log(14);
                    }
                }
            }

            if (this.error.error) {
                setTimeout(() => {
                    var valor =
                        $(".text-danger:first-child").offset().top - 300;
                    $("html, body").animate(
                        {
                            scrollTop: valor
                        },
                        500
                    );
                }, 50);
            }
            return this.error.error;
        },
        bodegas() {
            axios
                .get(
                    "/api/factura_compra/traerbodegas?empresa=" +
                        this.usuario.id_empresa +
                        "&establecimiento=" +
                        this.usuario.id_establecimiento
                )
                .then(({ data }) => {
                    this.listarbodegas = data;
                });
        },
        abrir_plan_cuentas_pagos(index) {
            this.modalcontable = {
                abrir: true,
                titulo: "Escoger plan de cuenta",
                tipo: 2
            };
            this.pagos.index = index;
        },
        abrir_plan_cuentas_pagos1(index) {
            this.modalcontable = {
                abrir: true,
                titulo: "Escoger plan de cuenta",
                tipo: 3
            };
            this.pagos.index = index;
        },
        eliminarplc(index) {
            this.pagos.index = index;
            this.pagos.datos[this.pagos.index].cuenta = "";
            this.pagos.datos[this.pagos.index].plan_cuenta = null;
        },
        //Facturación
        enviado() {
            this.$router.push("/compras/factura-compra");
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
                    title: "Retención Enviada",
                    text: "La Retención se generó exitosamente",
                    color: "success"
                });
                this.enviadoAsiento(id_factura);
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
        intlRound(numero, decimales = 2, usarComa = false) {
            var opciones = {
                maximumFractionDigits: decimales,
                useGrouping: false
            };
            usarComa = usarComa ? "es" : "en";
            return new Intl.NumberFormat(usarComa, opciones).format(numero);
        },
        NumbersOnly(evt) {
            evt = evt ? evt : window.event;
            var charCode = evt.which ? evt.which : evt.keyCode;
            if (
                charCode > 31 &&
                (charCode < 48 || charCode > 57) &&
                charCode !== 46
            ) {
                evt.preventDefault();
            } else {
                return true;
            }
        },
        recuperar_orden() {
            if (this.$route.params.id_orden) {
                axios
                    .get("/api/abrirorden/" + this.$route.params.id_orden)
                    .then(({ data }) => {
                        this.factura.fecha_emision = moment(
                            data.orden.fecha_emision
                        ).format("YYYY-MM-DD");
                        this.factura.fecha_validez = moment(
                            data.orden.fecha_expiracion
                        ).format("YYYY-MM-DD");
                        this.factura.id_orden = this.$route.params.id_orden;
                        this.cliente.tipo = true;
                        this.cliente.id_cliente = data.proveedor.id_proveedor;
                        this.cliente.nombre = data.proveedor.nombre_proveedor;
                        this.cliente.telefono = data.proveedor.telefono_prov;
                        this.cliente.email = data.proveedor.email;
                        this.cliente.tipo_identificacion =
                            data.proveedor.tipo_identificacion;
                        this.cliente.identificacion =
                            data.proveedor.identif_proveedor;
                        this.cliente.direccion = data.proveedor.direccion_prov;
                        this.producto.tipo = true;
                        data.productos.forEach((el, index) => {
                            let iva = false;
                            let iva2 = 1;
                            let siiva = false;
                            if (el.id_iva) {
                                iva = true;
                                iva2 = 2;
                                siiva = true;
                            }
                            this.producto.lista_productos.push({
                                id_producto: el.id_producto,
                                cod_alterno: el.cod_alterno,
                                cod_principal: el.cod_principal,
                                nombre: el.nombre,
                                proyecto: el.id_proyecto,
                                cantidad: el.cantidad,
                                precio: el.precio,
                                descuento: el.descuento,
                                p_descuento: el.p_descuento,
                                subtotal: el.subtotal,
                                iva: el.id_iva,
                                ice: el.id_ice,
                                sector: el.sector,
                                iva2: iva2,
                                siiva: siiva,
                               importacion:this.factura.gastos=='1g'?true:false
                            });
                        });
                    });
            }
        },
        //metodos para asiento automatico sin retencion
        enviadoAsiento(id) {
            var url =
                "/api/factura_compravercontabilidad/" +
                id +
                "?id_empresa=" +
                this.usuario.id_empresa;
            this.$vs.dialog({
                type: "confirm",
                color: "success",
                title: `Confirmar`,
                text: "¿Desea Guardar El Asiento de esta Factura?",

                accept: () => {
                    axios
                        .get(url)
                        .then(({ data }) => {
                            // this.lista.factura = data.factura;
                            // this.lista.cliente = data.cliente;
                            // //this.lista.productos = data.productos;
                            // this.lista.creditos = data.creditos;
                            // this.lista.iva = data.iva;
                            // this.lista.renta = data.renta;
                            var serie1 = data.factura.descripcion.substring(
                                0,
                                3
                            );
                            var serie2 = data.factura.descripcion.substring(
                                3,
                                6
                            );
                            var documento = data.factura.descripcion.substring(
                                6,
                                15
                            );
                            var cambio_renta = 0;
                            this.fecha_rol = data.factura.fech_emision;
                            var fecha = moment(this.fecha_rol).format(
                                "MMMM YYYY"
                            );
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
                                this.codigo = "FC-" + data.codigo_anterior;
                                this.contabilizado = data.factura.contabilidad;
                            } else {
                                this.codigo = "FC-" + data.codigo;
                                this.contabilizado = null;
                            }
                            this.concepto =
                                "Compra " +
                                serie1 +
                                "-" +
                                serie2 +
                                "-" +
                                documento +
                                " Proveedor: " +
                                this.razon_social;
                            this.productos_asiento = data.producto_asientos;
                            this.iva_asiento = data.doce_iva_asiento;
                            this.pagos_sin_plc = data.pagos_asientos_sin_plc;
                            this.pagos_con_plc = data.pagos_asientos_con_plc;
                            this.pagos_anticipo = data.pagos_asientos_anticipo;
                            this.total_pagos_sin_plc = data.total_pagos_sin_plc;
                            this.total_pagos_con_plc = data.total_pagos_con_plc;
                            this.total_pagos_anticipo =
                                data.total_pagos_anticipo;
                            this.creditos_asiento = data.cliente;
                            this.retencion_iva = data.iva_retencion_asiento;
                            this.retencion_renta = data.retencion_asiento;
                            this.total_retencion_iva = data.total_retencion_iva;
                            this.total_retencion_renta =
                                data.total_retencion_renta;
                            this.num_mayor_iva = this.retencion_iva;
                            this.num_mayor_renta = this.retencion_renta;
                            this.total_doce_iva = data.factura.iva_12;
                            //this.ice=data.ice;
                            // setTimeout(() => {
                            //     this.modalAsiento=true;
                            // },4000);

                            this.estado_asiento = data.asiento_permitido;
                            // setTimeout(() => {
                            //     this.Decimales;
                            // },2000);
                            this.id_factura = id;
                            this.id_proyecto = data.id_proyecto;
                            this.cuadrarAsiento();
                            //console.log("total cambio"+cambio_renta);
                            //console.log("numero renta"+max+"posicion renta");
                            //console.log("numero iva"+this.num_mayor_iva+"posicion iva"+this.posicion_iva);
                        })
                        .catch(error => {
                            console.log(error);
                        });
                },
                cancel: () => {
                    this.$router.push("/compras/factura-compra");
                }
            });
        },
        cuadrarAsiento() {
            this.$vs.notify({
                title: "Cargando este Registro",
                text: "Este proceso puede demorar por favor espere..",
                color: "warning"
            });
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
                        pagos += parseFloat(el.debe);
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
                            this.iva_asiento[x].debe >
                            this.iva_asiento[indiceDelMayor].debe
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.iva_asiento[indiceDelMayor].debe);
                    total_diferencia_pago = n3 + res;
                    this.iva_asiento[
                        indiceDelMayor
                    ].debe = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.iva_asiento[indiceDelMayor].debe +
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
                if (this.creditos_asiento.length > 1) {
                    this.creditos_asiento.forEach(el => {
                        pagos += parseFloat(el.haber);
                    });
                    //console.log("cantidad pagos:"+this.creditos_asiento.length+" diferencia pago: "+pagos+" total pago:"+this.creditos_asiento[0].total);
                    var n1 = Number(this.creditos_asiento[0].total_pago);
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

                    for (var x = 1; x < this.creditos_asiento.length; x++) {
                        if (
                            this.creditos_asiento[x].haber >
                            this.creditos_asiento[indiceDelMayor].haber
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(
                        this.creditos_asiento[indiceDelMayor].haber
                    );
                    total_diferencia_pago = n3 + res;
                    this.creditos_asiento[
                        indiceDelMayor
                    ].haber = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.creditos_asiento[indiceDelMayor].haber +
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
                        pagos += parseFloat(el.haber);
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
                            this.pagos_anticipo[x].haber >
                            this.pagos_anticipo[indiceDelMayor].haber
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.pagos_anticipo[indiceDelMayor].haber);
                    total_diferencia_pago = n3 + res;
                    this.pagos_anticipo[
                        indiceDelMayor
                    ].haber = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.pagos_anticipo[indiceDelMayor].haber +
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
                        pagos += parseFloat(el.haber);
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
                            this.pagos_con_plc[x].haber >
                            this.pagos_con_plc[indiceDelMayor].haber
                        ) {
                            indiceDelMayor = x;
                        }
                    }

                    var n3 = Number(this.pagos_con_plc[indiceDelMayor].haber);
                    total_diferencia_pago = n3 + res;
                    this.pagos_con_plc[
                        indiceDelMayor
                    ].haber = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.pagos_con_plc[indiceDelMayor].haber +
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
                        pagos += parseFloat(el.haber);
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
                            this.pagos_sin_plc[x].haber >
                            this.pagos_sin_plc[indiceDelMayor].haber
                        ) {
                            indiceDelMayor = x;
                        }
                    }
                    //}

                    var n3 = Number(this.pagos_sin_plc[indiceDelMayor].haber);
                    total_diferencia_pago = n3 + res;
                    this.pagos_sin_plc[
                        indiceDelMayor
                    ].haber = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.pagos_sin_plc[indiceDelMayor].haber +
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
                var total_diferencia_pago = 0;
                if (this.retencion_iva.length > 1) {
                    this.retencion_iva.forEach(el => {
                        pagos += parseFloat(el.haber);
                    });

                    //console.log("cantidad pagos:"+this.retencion_iva.length+" diferencia pago: "+pagos+" total pago:"+this.retencion_iva[0].total);
                    var n1 = Number(this.total_retencion_iva);
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

                    for (var x = 1; x < this.retencion_iva.length; x++) {
                        if (
                            this.retencion_iva[x].haber >
                            this.retencion_iva[indiceDelMayor].haber
                        ) {
                            indiceDelMayor = x;
                        }
                    }
                    //}

                    var n3 = Number(this.retencion_iva[indiceDelMayor].haber);
                    total_diferencia_pago = n3 + res;
                    this.retencion_iva[
                        indiceDelMayor
                    ].haber = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.retencion_iva[indiceDelMayor].haber +
                            " diferencia pago: " +
                            res +
                            " total pago:" +
                            total_diferencia_pago
                    );
                }
                resolve(total_diferencia_pago);
            });
        },
        IgualarRetencionRenta() {
            return new Promise(resolve => {
                var pagos = 0;
                console.log("Entro RetencionRenta");
                var total_diferencia_pago = 0;
                if (this.retencion_renta.length > 1) {
                    this.retencion_renta.forEach(el => {
                        pagos += parseFloat(el.haber);
                    });

                    //console.log("cantidad pagos:"+this.retencion_renta.length+" diferencia pago: "+pagos+" total pago:"+this.retencion_renta[0].total);
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

                    for (var x = 1; x < this.retencion_renta.length; x++) {
                        if (
                            this.retencion_renta[x].haber >
                            this.retencion_renta[indiceDelMayor].haber
                        ) {
                            indiceDelMayor = x;
                        }
                    }
                    //}

                    var n3 = Number(this.retencion_renta[indiceDelMayor].haber);
                    total_diferencia_pago = n3 + res;
                    this.retencion_renta[
                        indiceDelMayor
                    ].haber = total_diferencia_pago;
                    console.log(
                        "pago:" +
                            this.retencion_renta[indiceDelMayor].haber +
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
                console.log("Entro Decimales");
                var total_diferencia = 0;
                if (this.productos_asiento.length > 0) {
                    // if(this.total_debe<this.total_haber){
                    diferencia = parseFloat(
                        this.total_haber - this.total_debe
                    ).toFixed(2);
                    // }
                    if (diferencia != 0) {
                        var debe_producto = Number(
                            this.productos_asiento[0].debe
                        );
                        var df = Number(diferencia);
                        total_diferencia = debe_producto + df;
                        this.productos_asiento[0].debe = total_diferencia;
                        console.log(
                            "diferencia total:" +
                                diferencia +
                                " " +
                                total_diferencia
                        );
                    }
                }
                resolve(total_diferencia);
            });
        },
        validacion_asiento() {
            var error = 0;
            //console.log(this.productos_asiento.length);
            if (this.productos_asiento.length > 0) {
                this.productos_asiento.forEach(el => {
                    if (el.sector == "producto" && el.iva == "cero") {
                        if (el.id_plan_cuentas_iva_0 == null) {
                            error++;
                            console.log("producto asiento producto cero");
                        }
                    }
                    if (el.sector == "producto" && el.iva == "doce") {
                        if (el.id_plan_cuentas_iva_12 == null) {
                            error++;
                            console.log("producto asiento producto doce");
                        }
                    }
                    if (el.sector == "servicio") {
                        if (el.id_plan_cuentas_servicio == null) {
                            error++;
                            console.log("producto asiento servicio");
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
            if (this.creditos_asiento.length > 0) {
                this.creditos_asiento.forEach(el => {
                    if (el.exist_plan_cuenta_prov == "si") {
                        if (el.id_plan_cuenta_prov == null) {
                            error++;
                            console.log("creditos plan_cuenta_prov");
                        }
                    } else {
                        if (el.id_plan_cuenta_grupo_prov == null) {
                            error++;
                            console.log("creditos plan_cuenta_grupo_prov");
                        }
                    }

                    if (el.id_proyecto == null) {
                        error++;
                        console.log("creditos proyecto");
                    }
                });
            }
            if (this.retencion_iva.length > 0) {
                this.retencion_iva.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                        console.log("retencion_iva plan_cuentas");
                    }
                    if (el.id_proyecto == null) {
                        error++;
                        console.log("retencion_iva proyecto");
                    }
                });
            }
            if (this.retencion_renta.length > 0) {
                this.retencion_renta.forEach(el => {
                    if (el.id_plan_cuentas == null) {
                        error++;
                        console.log("retencion_renta plan_cuentas");
                    }
                    if (el.id_proyecto == null) {
                        error++;
                        console.log("retencion_renta proyecto");
                    }
                });
            }
            return error;
        },
        crearasiento(id) {
            this.disabled_asiento = true;
            var total = 0;
            total = this.total_debe - this.total_haber;
            console.log("total diferencia:" + total);
            if (this.validacion_asiento()) {
                this.$vs.notify({
                    color: "danger",
                    title: "Verifique los datos antes de guardar el Asiento"
                });
                this.disabled_asiento = false;
                return;
            }

            if (total !== 0) {
                this.$vs.notify({
                    color: "danger",
                    title: "No esta cuadrado el Asiento"
                });
                this.disabled_asiento = false;
                return;
            }
            if (this.estado_asiento == "no") {
                this.$vs.notify({
                    color: "danger",
                    title:
                        "Por Cierre del Periodo no se puede guardar este Asiento con esta fecha"
                });
                this.disabled_asiento = false;
                return;
            }
            var codigo_asiento = this.codigo.substr(3, this.codigo.length);
            var fecha_hoy = new Date();
            axios
                .post("/api/factura_compra/agregar/asiento", {
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
                    this.disabled_asiento = false;
                });
        },
        cancelarasiento() {
            // this.modalAsiento=false;
            // this.estado_asiento="";
            // this.$router.push("/compras/factura-compra");
            this.principal_fact_compra()
                .then(value => {
                    this.$router.push("/compras/factura-compra");
                })
                .catch(err => {
                    console.log("ERROR::[cancelar_asiento]" + err);
                });
        },
        principal_fact_compra() {
            return new Promise(resolve => {
                this.modalAsiento = false;
                this.estado_asiento = "";
                resolve((this.modalAsiento = false));
            });
        },
        crearasientoDetalle(id) {
            axios
                .post("/api/factura_compra/agregar/asiento_detalle", {
                    proyecto: this.nombre_proyecto,
                    productos: this.productos_asiento,
                    iva_12: this.iva_asiento,
                    pagos_sin_plc: this.pagos_sin_plc,
                    pagos_con_plc: this.pagos_con_plc,
                    pagos_anticipo: this.pagos_anticipo,
                    creditos: this.creditos_asiento,
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
                    this.disabled_asiento = false;
                    // this.modalAsiento=false;
                    // this.estado_asiento="";

                    // this.$router.push("/compras/factura-compra");
                    this.cancelarasiento();
                })
                .catch(err => {
                    this.$vs.notify({
                        color: "danger",
                        title: "Asiento No Agregado",
                        text: err
                    });
                    console.log(err);
                    this.disabled_asiento = false;
                });
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
        //
        /*             -------------------------FUNCIONES PARA FACTURA CON ARCHIVOS XML----------------------------------------                */
        selectxmlfile() {
            $(".selectbuttomxml").click();
        },
        uploadxml(e) {
            if (!e.target.files) {
                return;
            } else {
                this.xmlmode = true;
                this.filemode = true;
                this.filexml = e.target.files[0];
            }
            let me = this;
            var reader = new FileReader();
            reader.onload = function(e) {
                var doc = { fecha_emision: null, fecha_validez: null };
                var xmlresult = e.target.result;
                var xmlDoc = $.parseXML(xmlresult),
                    $xml = $(xmlDoc),
                    $comprobante = $xml.find("comprobante").text();
                //console.log($xml.find('ambiente').text());
                var cdata = $.parseXML($comprobante),
                    $factura = $(cdata);
                //console.log($factura.find('fechaEmision').text());
                var producto = $factura.find("detalle");
                doc.nfactura =
                    $factura.find("estab").text() +
                    $factura.find("ptoEmi").text() +
                    $factura.find("secuencial").text();
                doc.fecha_emision = $factura.find("fechaEmision").text();
                doc.fecha_emision =
                    doc.fecha_emision.slice(6) +
                    "-" +
                    doc.fecha_emision.slice(3, -5) +
                    "-" +
                    doc.fecha_emision.slice(0, -8);
                doc.fecha_validez = $xml.find("fechaAutorizacion").text();
                doc.fecha_validez =
                    doc.fecha_validez.slice(6) +
                    "-" +
                    doc.fecha_validez.slice(3, -5) +
                    "-" +
                    doc.fecha_validez.slice(0, -8);
                doc.numero_autorizacion = $xml
                    .find("numeroAutorizacion")
                    .text();
                doc.tipo_comprobantes = $factura.find("codDoc").text();
                doc.identif_proveedor = $factura.find("ruc").text();
                //console.log(doc.ruc + 'hi---'+ doc.nombreComercial);
                var ruc_info_empresa = me.empresa_info.ruc_empresa.substr(
                    0,
                    10
                );
                var ruc_info_xml = $factura
                    .find("identificacionComprador")
                    .text()
                    .substr(0, 10);
                if (ruc_info_empresa == ruc_info_xml) {
                    //setting datos
                    me.factura.nfactura = doc.nfactura;
                    me.factura.fecha_emision = moment(doc.fecha_emision).format(
                        "YYYY/MM/DD"
                    );
                    me.factura.fecha_validez = moment(doc.fecha_emision).format(
                        "YYYY/MM/DD"
                    );
                    me.factura.autorizacion = doc.numero_autorizacion;
                    me.factura.tipo_sustento = me.sustentos[1].id_sustento;
                    me.factura.destino_pago = "Pago a residentes";
                    //tipo comprobante
                    me.factura.tipo_comprobante = me.tipo_comprobantes.findIndex(
                        tip => tip.cod_tipcomprob == doc.tipo_comprobantes
                    );
                    me.factura.tipo_comprobante =
                        me.tipo_comprobantes[
                            me.factura.tipo_comprobante
                        ].id_tipcomprobante;
                    //proveedor
                    axios
                        .get(
                            `/api/proveedor_ident/${doc.identif_proveedor}/${me.usuario.id_empresa}`
                        )
                        .then(({ data }) => {
                            if (data.length > 0) {
                                me.cliente.clientes = [];
                                me.cliente.busqueda = "";
                                me.cliente.tipo = true;
                                me.cliente.id_cliente = data[0].id_proveedor;
                                me.cliente.nombre = data[0].nombre_proveedor;
                                me.cliente.telefono = data[0].telefono_prov;
                                me.cliente.email = data[0].email;
                                me.cliente.tipo_identificacion =
                                    data[0].tipo_identificacion;
                                me.cliente.identificacion =
                                    data[0].identif_proveedor;
                                me.cliente.direccion = data[0].direccion_prov;
                                me.anticipover(data[0].id_proveedor);
                            } else {
                                me.datos_xml_prov = $factura.find("ruc").text();
                                me.modal.abrir = true;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                    //productos
                    var prod = [];
                    for (var a = 0; a < producto.length; a++) {
                        var tarif = producto[a].getElementsByTagName(
                            "codigoPorcentaje"
                        )[0].textContent;
                        var iva = 1;
                        if (tarif == "2") {
                            tarif = true;
                            iva = 2;
                        } else if (tarif == "0") {
                            tarif = false;
                            iva = 1;
                        } else if (tarif == "6") {
                            tarif = false;
                            iva = 3;
                        } else if (tarif == "7") {
                            tarif = false;
                            iva = 4;
                        } else if (tarif == "3") {
                            tarif = true;
                            iva = 5;
                        }
                        prod.push({
                            id_producto_bodega: null,
                            id_bodega: null,
                            nombrebodega: null,
                            id_producto: null,
                            cod_principal: null,
                            sector: null,
                            nomb: producto[a].getElementsByTagName(
                                "descripcion"
                            )[0].textContent,
                            cantidad: producto[a].getElementsByTagName(
                                "cantidad"
                            )[0].textContent,
                            cantidadreal: producto[a].getElementsByTagName(
                                "cantidad"
                            )[0].textContent,
                            precio: producto[a].getElementsByTagName(
                                "precioUnitario"
                            )[0].textContent,
                            descuento: producto[a].getElementsByTagName(
                                "descuento"
                            )[0].textContent,
                            iva: iva,
                            siiva: tarif,
                            importacion:me.factura.gastos=='1g'?true:false
                        });
                        me.seleccionar_productosxml(prod[a]);
                    }
                } else {
                    me.$vs.notify({
                        title: "Archivo XML Equivocado",
                        text: "El archivo no pertenece a su empresa",
                        color: "danger"
                    });
                }
            };
            reader.readAsText(this.filexml);
        },
        selectpdffile() {
            $(".selectbuttompdf").click();
        },
        uploadpdf(e) {
            if (!e.target.files) {
                return;
            } else {
                this.filemode = true;
                this.filepdf = e.target.files[0];
            }
        },
        seleccionar_productosxml(tr) {
            this.producto.productos = [];
            this.producto.busqueda = "";
            this.producto.tipo = true;
            var subtotal = (tr.precio - tr.descuento).toFixed(2);

            if (isNaN(parseInt(tr.existencia_total))) {
                tr.existencia_total = "";
            }
            if (isNaN(parseFloat(tr.precio))) {
                tr.precio = "";
            }
            if (isNaN(parseFloat(tr.descuento))) {
                tr.descuento = 0;
            }
            if (
                tr.sector == 1 &&
                (tr.id_producto_bodega === "undefined" ||
                    tr.id_producto_bodega == null)
            ) {
                tr.cantidad = 0;
                tr.id_producto_bodega = null;
                tr.nombrebodega = null;
            }
            this.producto.lista_productos.push({
                id_producto_bodega: tr.id_producto_bodega,
                id_bodega: tr.id_bodega,
                nombrebodega: tr.nombrebodega,
                id_producto: tr.id_producto,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                proyecto: !this.proyectos_menu[0]
                    ? ""
                    : this.proyectos_menu[0].id_proyecto,
                nombre: tr.nombre,
                nomb: tr.nomb,
                cantidad: tr.cantidad,
                cantidadreal: tr.cantidad,
                precio: tr.precio,
                descuento: tr.descuento,
                p_descuento: 1,
                subtotal: subtotal,
                iva: tr.iva,
                ice: tr.ice,
                sector: tr.sector,
                iva2: tr.iva,
                siiva: tr.siiva,
                importacion:this.factura.gastos=='1g'?true:false,
                xmlmode: true,
                lotes:
                    tr.sector == 1
                        ? [
                              {
                                  cod_alterno: tr.cod_alterno,
                                  cod_principal: tr.cod_principal,
                                  nombre: tr.nombre,
                                  cantidad: cantidad,
                                  lote: "",
                                  fecha_fabricacion: moment().format(
                                      "YYYY-M-D"
                                  ),
                                  fecha_vencimiento: moment().format(
                                      "YYYY-M-D"
                                  ),
                                  id_producto: tr.id_producto,
                                  id_proyecto: this.proyectos_menu[0]
                                      .id_proyecto,
                                  id_bodega: tr.id_bodega
                              }
                          ]
                        : []
            });
        },
        select_producto_xml(i, e) {
            this.producto.lista_productos[i].cod_principal = this.producto_xml[
                e
            ].cod_principal;
            this.producto.lista_productos[i].id_producto = this.producto_xml[
                e
            ].id_producto;
            this.producto.lista_productos[i].nombre = this.producto_xml[
                e
            ].nombre;
            this.producto.lista_productos[i].ice = this.producto_xml[e].ice;
            this.producto.lista_productos[i].sector = this.producto_xml[
                e
            ].sector;
            if (this.producto.lista_productos[i].sector == 1) {
                this.producto.lista_productos[
                    i
                ].id_bodega = this.listarbodegas[0].id_bodega;

                this.producto.lista_productos[i].lotes = [];
                this.producto.lista_productos[i].lotes.push({
                    cod_principal: this.producto.lista_productos[i]
                        .cod_principal,
                    nombre: this.producto.lista_productos[i].nombre,
                    cantidad: this.producto.lista_productos[i].cantidad,
                    lote: "",
                    fecha_fabricacion: moment().format("YYYY-M-D"),
                    fecha_vencimiento: moment().format("YYYY-M-D"),
                    id_producto: this.producto.lista_productos[i].id_producto,
                    id_proyecto: this.proyectos_menu[0].id_proyecto,
                    id_bodega: this.producto.lista_productos[i].id_bodega
                });
            }
        },
        listar_productos_xml() {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                axios
                    .post("/api/factura_compra/listar_productoxml", {
                        buscar: "",
                        id_empresa: this.usuario.id_empresa
                    })
                    .then(({ data }) => {
                        this.producto_xml = data;
                        return;
                    })
                    .catch(error => {});
            }, 800);
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
        savefilesfact(id) {
            if (this.filemode == true) {
                let formData = new FormData();
                if (this.filexml != null)
                    formData.append("filexml", this.filexml);
                if (this.filepdf != null)
                    formData.append("filepdf", this.filepdf);
                if (id != null) formData.append("id_factcomp", id);
                if (this.usuario.id_empresa != null)
                    formData.append("id_empresa", this.usuario.id_empresa);
                axios
                    .post("/api/savefilesfactcompra", formData)
                    .then(res => {
                        console.log("save files");
                    })
                    .catch(err => {
                        console.log("error save files");
                    });
            }
        },
        //// funciones para guardar lotes
        pushlotes(index) {
            // this.producto.lista_productos[index].lotes = [];
            // this.producto.lista_productos[index].lotes.push({
            //     cod_principal: this.producto.lista_productos[index].cod_principal,
            //     nombre: this.producto.lista_productos[index].nombre,
            //     cantidad: this.producto.lista_productos[index].cantidad,
            //     lote: "",
            //     fecha_fabricacion: moment().format("YYYY-M-D"),
            //     fecha_vencimiento: moment().format("YYYY-M-D")
            // });
            if (this.producto.lista_productos[index].sector == 1) {
                this.producto.lista_productos[index].lotes = [];
                this.producto.lista_productos[index].lotes.push({
                    cod_principal: this.producto.lista_productos[index]
                        .cod_principal,
                    nombre: this.producto.lista_productos[index].nombre,
                    cantidad: this.producto.lista_productos[index].cantidad,
                    lote: "",
                    fecha_fabricacion: moment().format("YYYY-M-D"),
                    fecha_vencimiento: moment().format("YYYY-M-D"),
                    id_producto: this.producto.lista_productos[index]
                        .id_producto,
                    id_proyecto: this.producto.lista_productos[index].proyecto,
                    id_bodega: this.producto.lista_productos[index].id_bodega
                });
            }
        },
        abrirlotes(index) {
            this.popuplotes = true;
            this.lugar = index;
            this.calculo_lotes();
        },
        calculo_lotes() {
            if (this.lugar != null) {
                this.totales_lotes = { cantidad_ingreso: 0, total_lote: 0 };
                this.totales_lotes.cantidad_ingreso = this.producto.lista_productos[
                    this.lugar
                ].cantidad;
                for (
                    let i = 0;
                    i < this.producto.lista_productos[this.lugar].lotes.length;
                    i++
                ) {
                    this.totales_lotes.total_lote += parseFloat(
                        this.producto.lista_productos[this.lugar].lotes[i]
                            .cantidad
                    );
                }
            }
        },
        add_lote() {
            this.producto.lista_productos[this.lugar].lotes.push({
                cod_principal: this.producto.lista_productos[this.lugar]
                    .cod_principal,
                nombre: this.producto.lista_productos[this.lugar].nombre,
                cantidad: 0,
                lote: "",
                fecha_fabricacion: moment().format("YYYY-M-D"),
                fecha_vencimiento: moment().format("YYYY-M-D"),
                id_producto: this.producto.lista_productos[this.lugar]
                    .id_producto,
                id_proyecto: this.producto.lista_productos[this.lugar].proyecto,
                id_bodega: this.producto.lista_productos[this.lugar].id_bodega
            });
        },
        eliminar_lote(index) {
            this.producto.lista_productos[this.lugar].lotes.splice(index, 1);
             this.calculo_lotes();
        },
        guardar_lotes() {
            var total = 0;
            var ceros = false;
            for (
                let i = 0;
                i < this.producto.lista_productos[this.lugar].lotes.length;
                i++
            ) {
                total += parseFloat(
                    this.producto.lista_productos[this.lugar].lotes[i].cantidad
                );
                if (
                    parseFloat(
                        this.producto.lista_productos[this.lugar].lotes[i]
                            .cantidad
                    ) == 0
                ) {
                    ceros = true;
                }
            }
            if (
                total == this.producto.lista_productos[this.lugar].cantidad &&
                ceros == false
            ) {
                this.popuplotes = false;
            } else {
                console.log("Total cant ing:" + total + " ceros:" + ceros);
                this.$vs.notify({
                    title: "Error al guardar",
                    text: "El total de lotes no es igual al total de ingreso",
                    color: "danger"
                });
            }
        }
    },
    mounted() {
        this.listar_creacion_cliente();
        this.listar_cuenta_contable(this.plan_cuenta.buscar);
        this.listarclave();
        this.listarformapagos();
        this.listarretenciones();
        this.listarbanco();
        this.listarsustento();
        this.listarTipoComprobante();
        this.listarimportaciones();
        this.bodegas();
        this.recuperar_orden();
        $(document).on("click", function(e) {
            var container = $(".busqueda_lista");
            if (
                !container.is(e.target) &&
                container.has(e.target).length === 0
            ) {
                $(".busqueda_lista").hide();
            }
        });
        $(".focuspr").focus();
        this.listar_productos_xml();
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
.busqueda_cliente input {
    height: 50px;
    padding-left: 45px !important;
}
.busqueda_cliente_icono {
    position: absolute !important;
    top: 11px;
    left: 25px;
}
.busqueda_lista {
    position: absolute;
    width: 97%;
    z-index: 9;
}
.ul_busqueda_lista {
    min-width: 160px;
    margin: -2px 0 0;
    list-style: none;
    font-size: 13.5px;
    text-align: left;
    background-color: #fff;
    border: 1px solid #ccc;
    border: 1px solid rgba(0, 0, 0, 0.15);
    border-radius: 2px;
    -webkit-box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.175);
    background-clip: padding-box;
}
.ul_busqueda_lista li {
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
.ul_busqueda_lista li:hover {
    background: rgba(16, 22, 58, 0.38);
    cursor: pointer;
    color: #fff;
}
.busqueda_cliente .input-span-placeholder {
    padding-left: 50px;
    margin-top: 3px;
}
.buscar_otro {
    position: absolute;
    margin-top: -35px;
    margin-left: 14px;
    cursor: pointer;
}
.eliminar_producto_icono {
    display: none;
}
.eliminar_producto_icono svg {
    margin-top: 8px;
}
.fila_lista:hover .eliminar_producto_icono {
    display: block;
}
.cabezera_total span {
    float: right;
    margin-right: 25px;
}
.cabezera_total > div {
    margin-left: 20px;
    padding: 9px 3px;
}
.cabezera_total {
    margin-top: 15px;
}
.vs-input--placeholder {
    top: 0px;
}
.modal-xl .vs-popup {
    width: 1250px;
}
.tablavista td {
    padding: 10px 15px;
}
.tablavista:hover {
    cursor: pointer;
    background: rgba(0, 0, 0, 0.2);
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
.btnmoremore {
    position: absolute;
    z-index: 9;
    right: 18px;
    margin-top: -45px;
    font-size: 31px;
    background: #fff;
    cursor: pointer;
}
.derecha input {
    text-align: end;
}
.derecha .vs-input--placeholder {
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
.lista_preloader {
    padding: 8px;
    display: flex;
    justify-content: center;
    align-items: center;
}
.derecha input {
    text-align: end;
}
.derecha .vs-input--placeholder {
    text-align: end;
}
.nombrearreglo span {
    font-size: 11px;
    letter-spacing: -0.5px;
    line-height: 15px;
    display: block;
}
.button_upload {
}
</style>
