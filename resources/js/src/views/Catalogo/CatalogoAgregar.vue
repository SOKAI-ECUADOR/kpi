<template>
    <vx-card>
        <div class="vx-row">
            <div
                class="vx-col md:w-full sm:w-full w-full mb-3"
                style="text-align: center;"
            >
                <vs-radio
                    :disabled="idrecupera"
                    v-model="sector"
                    vs-value="1"
                    @change="categoria = null"
                    >Producto</vs-radio
                >
                <vs-radio
                    :disabled="idrecupera"
                    v-model="sector"
                    vs-value="2"
                    @change="categoria = null"
                    >Servicio</vs-radio
                >
                <div v-show="error" v-if="!sector">
                    <div
                        v-for="err in errorsector"
                        :key="err"
                        v-text="err"
                        class="text-danger"
                    ></div>
                </div>
            </div>
            <div
                v-if="sector == '1'"
                class="vx-col md:w-full sm:w-full w-full  mb-3"
                style="text-align: center;"
            >
                <vs-select
                    :disabled="idrecupera"
                    placeholder="seleccione una categoría:"
                    class="selectExample md:w-1/3 sm:w-1/3 w-full mr-auto ml-auto"
                    style="text-aling:center;"
                    label="Categoría de Producto:"
                    autocomplete
                    v-model="categoria"
                >
                    <vs-select-item
                        :key="index"
                        :value="item"
                        :text="item"
                        v-for="(item, index) in optionscategoria"
                    />
                </vs-select>
            </div>
            <!-----------------------------------------------------------------------------Para todos------------------------------------------------------>
            <vs-divider border-style="solid" color="dark" v-if="sector == '2'"
                >Formulario de Ingreso de Servicios</vs-divider
            >
            <vs-divider
                border-style="solid"
                color="dark"
                v-if="categoria == 'General'"
                >Formulario de Ingreso de Productos Generales</vs-divider
            >
            <div
                class="vx-col md:w-2/3 w-full mb-3"
                v-if="categoria || sector == '2'"
            >
                <div class="vx-row">
                    <div class="vx-col md:w-2/5 sm:w-full w-full mb-3">
                        <vs-input
                            class="w-full"
                            label="Código Alterno:"
                            v-model="cod_alterno"
                            :maxlength="25"
                        />
                    </div>
                    <div class="vx-col md:w-3/5 sm:w-full w-full mb-3">
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
                    <div class="vx-col sm:w-1/2 w-full mb-3">
                        <vs-input
                            class="w-full"
                            label="Código de Barras:"
                            v-model="cod_barras"
                        />
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-3">
                        <vs-select
                            placeholder="Seleccione estado:"
                            class="selectExample w-full"
                            label="Estado:"
                            label-placeholder="Estado"
                            vs-multiple
                            autocomplete
                            v-model="estado"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in options1"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!estado">
                            <div
                                v-for="err in errorestado"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-3/3 w-full mb-3">
                        <label class="vs-input--label">Descripción:</label>
                        <vs-textarea v-model="descripcion" rows="3" />
                        <div v-show="error" v-if="!descripcion">
                            <div
                                v-for="err in errordescripcion"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                </div>
            </div>
            <div
                class="vx-col md:w-1/3 w-full mb-3"
                v-if="categoria || sector == '2'"
            >
                <div style="display:none">
                    <input
                        type="file"
                        class="filepre seleccionarimagen"
                        accept="image/*"
                        @change="seleccionarimagen"
                    />
                </div>
                <div class="verimagen" v-if="!imagen">
                    <img
                        src="/images/upload.png"
                        @click="agregarimagen"
                        class="imagenpre"
                        style="padding: 20px;"
                    />
                </div>
                <div class="verimagen" v-else style="position: relative;">
                    <template v-if="recuperaimagen">
                        <img
                            :src="
                                '/' +
                                    this.usuario.id_empresa +
                                    '/productos/' +
                                    imagen
                            "
                            @click="agregarimagen"
                            class="imagenpre"
                        />
                        <div class="alerta estiloborrar">
                            <div class="text-center">
                                <span class="spanborrar" @click="eliminarimagen"
                                    >X</span
                                >
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <img
                            :src="imagenprevisualizar"
                            @click="agregarimagen"
                            class="imagenpre"
                        />
                        <div class="alerta estiloborrar">
                            <div class="text-center">
                                <span class="spanborrar" @click="eliminarimagen"
                                    >X</span
                                >
                            </div>
                        </div>
                    </template>
                </div>
            </div>
            <!-----------------------------------------------------------------------------Servicios------------------------------------------------------>
            <vs-divider border-style="solid" color="dark" v-if="sector == '2'"
                >Rubros de Servicio</vs-divider
            >
            <div
                class="vx-row sm:w-full w-full mb-3 ml-auto mr-auto"
                v-if="sector == '2'"
            >
                <div class="vx-col sm:w-1/4 w-full mb-3">
                    <label class="vs-input--label">Cuenta Contable</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="cta_prod1" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupActive = true"
                                    >Buscar</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                    <div v-show="error" v-if="!cta_prod">
                        <div
                            v-for="err in errorcta_prod"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>

                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione tipo de servicio"
                        class="selectExample w-full"
                        label="Tipo de Servicio:"
                        label-placeholder="Tipo de Servicio"
                        vs-multiple
                        v-model="tipo_servicio"
                    >
                        <vs-select-item value="Compra" text="Compra" />
                        <vs-select-item value="Venta" text="Venta" />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_servicio">
                        <div
                            v-for="err in errortipo_servicio"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
            </div>
            <div
                class="vx-row sm:w-full w-full flex flex-wrap"
                v-if="sector == '2'"
            >
                <div
                    class="vx-row sm:w-full w-full flex flex-wrap"
                    v-if="!imagen"
                >
                    <div
                        class="w-1/4 ml-auto bg-grid-color h-12"
                        style="text-align: right;"
                    >
                        <vx-tooltip
                            color="warning"
                            text="Aun no ha añadido una Imagen"
                            position="top"
                            style="display: inline-flex;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            ></vx-tooltip
                        >
                    </div>
                    <div
                        class="w-1/4  bg-grid-color-secondary h-12"
                        style="text-align: center;"
                    >
                        <vs-button
                            color="warning"
                            type="border"
                            @click="cerrar()"
                            v-if="!idrecupera"
                            >BORRAR</vs-button
                        >
                    </div>
                    <div
                        class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                        style="text-align: left;"
                    >
                        <vs-button
                            color="danger"
                            type="border"
                            to="/inventario/catalogo"
                            >CANCELAR</vs-button
                        >
                    </div>
                </div>
                <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                    <div
                        class="w-1/4 ml-auto bg-grid-color h-12"
                        style="text-align: right;"
                    >
                        <vs-button
                            color="success"
                            type="border"
                            @click="guardar()"
                            v-if="!idrecupera"
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="success"
                            type="border"
                            @click="editar()"
                            v-else
                            >GUARDAR</vs-button
                        >
                    </div>
                    <div
                        class="w-1/4  bg-grid-color-secondary h-12"
                        style="text-align: center;"
                    >
                        <vs-button
                            color="warning"
                            type="border"
                            @click="cerrar()"
                            v-if="!idrecupera"
                            >BORRAR</vs-button
                        >
                    </div>
                    <div
                        class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                        style="text-align: left;"
                    >
                        <vs-button
                            color="danger"
                            type="border"
                            to="/inventario/catalogo"
                            >CANCELAR</vs-button
                        >
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN DE Servicio------------------------------------------------------>
            <!-----------------------------------------------------------------------------TODOS los Productos General------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'General' || categoria == 'Cortinas'"
            >
                <div class="vx-col sm:w-full w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Fórmula de Producción</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="form_prod" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupformprod = true"
                                    >Añadir</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Productos General------------------------------------------------------>
            <!----------------------------------------------------------------------------- Productos Seguridad Industrial------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'Seguridad Industrial'"
            >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Normativa:</label>
                    <vs-textarea v-model="normativa" rows="2" />
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Uso:</label>
                    <vs-textarea v-model="uso" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Fórmula de Producción</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="form_prod" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupformprod = true"
                                    >Añadir</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Productos Seguridad Insdustrial------------------------------------------------------>
            <!----------------------------------------------------------------------------- Productos Vehiculos------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'Vehículos'"
            >
                <div class="vx-col sm:w-full w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Fórmula de Producción</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="form_prod" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupformprod = true"
                                    >Añadir</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos de Vehiculo</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input class="w-full" label="Placa:" v-model="placa" />
                    <div v-show="error" v-if="!placa">
                        <div
                            v-for="err in errorplaca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="País Origen:"
                        v-model="pais_origen"
                    />
                    <div v-show="error" v-if="!pais_origen">
                        <div
                            v-for="err in errorpais_origen"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Año Fabricación:"
                        v-model="ano_fabricacionv"
                    />
                    <div v-show="error" v-if="!ano_fabricacionv">
                        <div
                            v-for="err in errorano_fabricacionv"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input class="w-full" label="Color:" v-model="color" />
                    <div v-show="error" v-if="!color">
                        <div
                            v-for="err in errorcolor"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Carrocería:"
                        v-model="carroceria"
                    />
                    <div v-show="error" v-if="!carroceria">
                        <div
                            v-for="err in errorcarroceria"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Combustible"
                        class="selectExample w-full"
                        label="Combustible:"
                        label-placeholder="Combustible"
                        vs-multiple
                        autocomplete
                        v-model="combustible"
                    >
                        <vs-select-item
                            :key="index"
                            :value="item.value"
                            :text="item.text"
                            v-for="(item, index) in options3"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!combustible">
                        <div
                            v-for="err in errorcombustible"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input class="w-full" label="Motor:" v-model="motor" />
                    <div v-show="error" v-if="!motor">
                        <div
                            v-for="err in errormotor"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Cilindraje:"
                        v-model="cilindraje"
                    />
                    <div v-show="error" v-if="!cilindraje">
                        <div
                            v-for="err in errorcilindraje"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input class="w-full" label="Chasis:" v-model="chasis" />
                    <div v-show="error" v-if="!chasis">
                        <div
                            v-for="err in errorchasis"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input class="w-full" label="Clase:" v-model="clase" />
                    <div v-show="error" v-if="!clase">
                        <div
                            v-for="err in errorclase"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Subclase:"
                        v-model="subclase"
                    />
                    <div v-show="error" v-if="!subclase">
                        <div
                            v-for="err in errorsubclase"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Número de Pasajeros</label>
                    <vs-input-number
                        v-model="numero_pasajeros"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                    <div v-show="error" v-if="!numero_pasajeros">
                        <div
                            v-for="err in errornumero_pasajeros"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Productos Vehiculos------------------------------------------------------>
            <!----------------------------------------------------------------------------- Productos Electrodomesticos------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'Electrodomésticos'"
            >
                <div class="vx-col sm:w-full w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Fórmula de Producción</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="form_prod" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupformprod = true"
                                    >Añadir</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Productos Electrodomesticos------------------------------------------------------>
            <!----------------------------------------------------------------------------- Productos Licores------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'Licores'"
            >
                <div class="vx-col sm:w-full w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Fórmula de Producción</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="form_prod" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupformprod = true"
                                    >Añadir</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Grados de Alcohol:"
                        v-model="grados_alcohol"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Productos Licores------------------------------------------------------>
            <!----------------------------------------------------------------------------- Productos Tecnologicos------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'Tecnología '"
            >
                <div class="vx-col sm:w-full w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Fórmula de Producción</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="form_prod" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupformprod = true"
                                    >Añadir</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Tecnologia------------------------------------------------------>
            <!----------------------------------------------------------------------------- Productos Alimentos------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'Alimentos'"
            >
                <div class="vx-col sm:w-full w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <label class="vs-input--label">Fórmula de Producción</label>
                    <vx-input-group>
                        <vs-input disabled class="w-full" v-model="form_prod" />
                        <template slot="append">
                            <div class="append-text btn-addon">
                                <vs-button
                                    color="primary"
                                    @click="popupformprod = true"
                                    >Añadir</vs-button
                                >
                            </div>
                        </template>
                    </vx-input-group>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Productos Alimentos------------------------------------------------------>
            <!----------------------------------------------------------------------------- Productos MEDICAMENTOS------------------------------------------------------>
            <div
                class="vx-row sm:w-full w-full ml-auto mr-auto"
                v-if="categoria == 'Medicamentos' && usuario.id_rol != 2"
            >
                <div class="vx-col sm:w-full w-full mb-3">
                    <label class="vs-input--label">Características:</label>
                    <vs-textarea v-model="caracteristicas" rows="2" />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Datos generales</vs-divider
                >
                <div
                    class="vx-col sm:w-1/4 w-full mb-3 ml-auto"
                    style="margin-top: 30px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="medicamento_controlado"
                        @change="psicotropicos=''"
                    >
                        <template v-if="medicamento_controlado">
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
                        | Medicamento Controlado
                    </vs-checkbox>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3" v-if="medicamento_controlado">
                    <vs-select
                        placeholder="Buscar..."
                        class="selectExample w-full"
                        label="Psicotrópico o Estupefaciente"
                        vs-multiple
                        autocomplete
                        v-model="psicotropicos"
                    >
                        <vs-select-item
                            :key="res.value"
                            :value="res.value"
                            :text="res.text"
                            v-for="res in contenidopsicotropicos"
                        />
                    </vs-select>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3">
                    <vs-select
                        placeholder="Buscar línea de producto"
                        class="selectExample w-full"
                        label="Línea de Producto:"
                        label-placeholder="Línea de Producto"
                        vs-multiple
                        autocomplete
                        v-model="linea_producto"
                        @change="
                            listartipo(linea_producto);
                            tipo_producto = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_linea_producto"
                            :value="res.id_linea_producto"
                            :text="res.nombre"
                            v-for="res in contenidolinea"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!linea_producto">
                        <div
                            v-for="err in errorlinea"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3 mr-auto" v-if="linea_producto">
                    <template v-if="contenidotipo.length != 0">
                        <vs-select
                            placeholder="Buscar tipo"
                            class="selectExample w-full"
                            label="Tipo de Producto:"
                            label-placeholder="Tipo de Producto"
                            vs-multiple
                            autocomplete
                            v-model="tipo_producto"
                        >
                            <vs-select-item
                                :key="res.id_tipo_producto"
                                :value="res.id_tipo_producto"
                                :text="res.nombre"
                                v-for="res in contenidotipo"
                            />
                        </vs-select>
                    </template>
                    <template v-else>
                        <vs-input
                            class="w-full"
                            label="Tipo de Producto:"
                            value="Esta linea de producto no contiene tipo"
                            disabled
                        ></vs-input>
                    </template>
                    <div v-show="error" v-if="!tipo_producto">
                        <div
                            v-for="err in errortipo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/4 w-full mb-3 mr-auto" v-else>
                    <vs-input
                        class="w-full"
                        label="Tipo de Producto:"
                        value="Debes escoger linea de producto"
                        disabled
                    ></vs-input>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar marca"
                        class="selectExample w-full"
                        label="Marca:"
                        label-placeholder="Marca"
                        vs-multiple
                        autocomplete
                        v-model="marca"
                    >
                        <vs-select-item
                            :key="res.id_marca"
                            :value="res.id_marca"
                            :text="res.nombre"
                            v-for="res in contenidomarca"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!marca">
                        <div
                            v-for="err in errormarca"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar modelo"
                        class="selectExample w-full"
                        label="Modelo:"
                        label-placeholder="Modelo"
                        vs-multiple
                        autocomplete
                        v-model="modelo"
                    >
                        <vs-select-item
                            :key="res.id_modelo"
                            :value="res.id_modelo"
                            :text="res.nombre"
                            v-for="res in contenidomodelo"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!modelo">
                        <div
                            v-for="err in errormodelo"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Buscar presentación"
                        class="selectExample w-full"
                        label="Presentación:"
                        label-placeholder="Presentación"
                        vs-multiple
                        autocomplete
                        v-model="presentacion"
                    >
                        <vs-select-item
                            :key="res.id_presentacion"
                            :value="res.id_presentacion"
                            :text="res.nombre"
                            v-for="res in contenidopresentacion"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!presentacion">
                        <div
                            v-for="err in errorpresentacion"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Mínima:</label>
                    <vs-input-number
                        v-model="existencia_min"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <div class="vx-col sm:w-1/2 md:w-1/2 w-full mb-3">
                    <label class="vs-input--label">Existencia Máxima:</label>
                    <vs-input-number
                        v-model="existencia_max"
                        icon-inc="expand_less"
                        icon-dec="expand_more"
                        style="height: 36px;margin: 1px;"
                    />
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Dimensiones del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-select
                        placeholder="Tipo de Medida"
                        class="selectExample w-full"
                        label="Tipo de Medida:"
                        label-placeholder="Tipo de Medida"
                        vs-multiple
                        autocomplete
                        v-model="tipo_medida"
                        @change="
                            listarunidadmedidar(tipo_medida);
                            unidad_medida = '';
                        "
                    >
                        <vs-select-item
                            :key="res.id_tipo_medida"
                            :value="res.id_tipo_medida"
                            :text="res.nombre"
                            v-for="res in contenidotipomedida"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_medida">
                        <div
                            v-for="err in errortipo_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Unidad de Medida"
                        class="selectExample w-full"
                        label="Unidad de Medida:"
                        label-placeholder="Unidad de Medida"
                        vs-multiple
                        autocomplete
                        v-model="unidad_medida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_medida">
                        <div
                            v-for="err in errorunidad_medida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Medida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <div
                    class="vx-col sm:w-1/2 w-full mb-3"
                    v-if="tipo_medida == 1"
                >
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Ingrese Número de Unidad:"
                        v-model="numero_unidad"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                    <div v-show="error" v-if="!numero_unidad">
                        <div
                            v-for="err in errornumero_unidad"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-if="tipo_medida">
                    <vs-select
                        placeholder="Seleccione unidad"
                        class="selectExample w-full"
                        label="Unidad de Salida:"
                        label-placeholder="Unidad de Salida:"
                        vs-multiple
                        v-model="unidad_salida"
                    >
                        <vs-select-item
                            :key="res.id_unidad_medida"
                            :value="res.id_unidad_medida"
                            :text="res.nombre"
                            v-for="res in contenidounidadmedidar"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!unidad_salida">
                        <div
                            v-for="err in errorunidad_salida"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3" v-else>
                    <vs-input
                        class="w-full"
                        label="Unidad de Salida:"
                        value="Debes escoger un tipo de medida"
                        disabled
                    ></vs-input>
                </div>
                <vs-divider border-style="solid" color="dark"
                    >Rubros del Producto</vs-divider
                >
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione IVA"
                        class="selectExample w-full"
                        label="IVA:"
                        label-placeholder="IVA:"
                        vs-multiple
                        v-model="iva"
                    >
                        <vs-select-item
                            :key="res.id_iva"
                            :value="res.id_iva"
                            :text="res.nombre"
                            v-for="res in contenidoiva"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!iva">
                        <div
                            v-for="err in erroriva"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-select
                        placeholder="Seleccione ICE"
                        class="selectExample w-full"
                        label="ICE:"
                        label-placeholder="ICE:"
                        vs-multiple
                        v-model="ice"
                    >
                        <vs-select-item
                            :key="res.id_ice"
                            :value="res.id_ice"
                            :text="res.nombre"
                            v-for="res in contenidoice"
                        />
                    </vs-select>
                    <div v-show="error" v-if="!ice">
                        <div
                            v-for="err in errorice"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-3">
                    <vs-input
                        disabled
                        class="w-full"
                        label="Total ICE:"
                        v-model="total_ice"
                    />
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Costo Unitario:"
                        v-model="costo_unitario"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                        @input="calculartodosprecio()"
                    />
                    <div v-if="!costo_unitario && cacular_precios">
                        <div class="text-danger">
                            Campo obligatorio para el cálculo de precios
                        </div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-3">
                    <vs-input
                        class="w-full"
                        label="Descuento (%):"
                        v-model="descuento"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    style="margin-top: 20px; margin-bottom: 0.2rem !important;"
                >
                    <vs-checkbox
                        @change="limpiarprecios()"
                        icon-pack="feather"
                        icon="icon-check"
                        v-model="cacular_precios"
                    >
                        <template v-if="cacular_precios">
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
                        | Calcular Precios en Base a Costo Unitario y Procentaje
                        de Utilidad
                    </vs-checkbox>
                </div>
                <template v-if="cacular_precios">
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 1):"
                            v-model="utilidad_precio1"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio1')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 2):"
                            v-model="utilidad_precio2"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio2')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 3):"
                            v-model="utilidad_precio3"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio3')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 4):"
                            v-model="utilidad_precio4"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio4')"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-3">
                        <vs-input
                            class="w-full"
                            type="text"
                            label="Porcentaje Utilidad (Precio 5):"
                            v-model="utilidad_precio5"
                            @keypress="solonumeros($event)"
                            maxlength="17"
                            @input="calcularprecio('precio5')"
                        /></div
                ></template>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        type="text"
                        label="PVP(Precio 1):"
                        v-model="precio1"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 2:"
                        v-model="precio2"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 3:"
                        v-model="precio3"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 4:"
                        v-model="precio4"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-3">
                    <vs-input
                        :disabled="cacular_precios"
                        class="w-full"
                        label="Precio 5 (Referencial):"
                        v-model="precio5"
                        @keypress="solonumeros($event)"
                        maxlength="17"
                    />
                </div>
                <div class="vx-row sm:w-full w-full flex flex-wrap mt-4 mb-3">
                    <div
                        class="vx-row sm:w-full w-full flex flex-wrap"
                        v-if="!imagen"
                    >
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vx-tooltip
                                color="warning"
                                text="Aun no ha añadido una Imagen"
                                position="top"
                                style="display: inline-flex;"
                            >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="guardar()"
                                    v-if="!idrecupera"
                                    >GUARDAR</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="border"
                                    @click="editar()"
                                    v-else
                                    >GUARDAR</vs-button
                                ></vx-tooltip
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                    <div class="vx-row sm:w-full w-ull flex flex-wrap" v-else>
                        <div
                            class="w-1/4 ml-auto bg-grid-color h-12"
                            style="text-align: right;"
                        >
                            <vs-button
                                color="success"
                                type="border"
                                @click="guardar()"
                                v-if="!idrecupera"
                                >GUARDAR</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                @click="editar()"
                                v-else
                                >GUARDAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4  bg-grid-color-secondary h-12"
                            style="text-align: center;"
                        >
                            <vs-button
                                color="warning"
                                type="border"
                                @click="cerrar()"
                                v-if="!idrecupera"
                                >BORRAR</vs-button
                            >
                        </div>
                        <div
                            class="w-1/4 mr-auto bg-grid-color-secondary h-12"
                            style="text-align: left;"
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/inventario/catalogo"
                                >CANCELAR</vs-button
                            >
                        </div>
                    </div>
                </div>
            </div>
            <!-----------------------------------------------------------------------------FIN Productos MEDICAMENTOS------------------------------------------------------>
            <!-----------------------------------------------------------------------------FIN DE DISEÑO NUEVO------------------------------------------------------>
        </div>
        <!-- Popup cuenta contable -->
        <vs-popup
            title="Seleccione una Cuenta Contable"
            :active.sync="popupActive"
            :class="'peque'"
        >
            <div class="con-exemple-prompt">
                <vs-input
                    class="mb-4 md:mb-0 mr-4 w-full"
                    v-model="buscar1"
                    @keyup="listarcuenta(buscar1)"
                    v-bind:placeholder="i18nbuscar"
                />
                <vs-table
                    stripe
                    @selected="handleSelected"
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
        <!-- Popup formula de Produccion -->
        <vs-popup
            title="Seleccione una Fórmula de Producción"
            :active.sync="popupformprod"
        >
            <div class="con-exemple-prompt">
                <div class="flex mb-4">
                    <div class="w-1/5 mr-2">
                        <vs-input
                            class="w-full"
                            v-model="buscar"
                            @keyup="listarformula(1, buscar)"
                            v-bind:placeholder="i18nbuscar"
                        />
                    </div>
                    <div class="w-2/5">
                        <vs-button
                            color="primary"
                            type="border"
                            @click="popucreaformprod = true"
                            >Crear Nueva Fórmula</vs-button
                        >
                    </div>
                </div>
                <vs-table
                    stripe
                    @selected="selectformula"
                    :data="contenidoformula"
                >
                    <template slot="thead">
                        <vs-th>No.Fórmula</vs-th>
                        <vs-th>Nombre Fórmula</vs-th>
                    </template>
                    <template slot-scope="{ data }">
                        <vs-tr
                            :data="tr"
                            :key="indextr"
                            v-for="(tr, indextr) in data"
                        >
                            <vs-td :data="data[indextr].codigo_produccion">{{
                                data[indextr].codigo_produccion
                            }}</vs-td>

                            <vs-td :data="data[indextr].nombre_form">{{
                                data[indextr].nombre_form
                            }}</vs-td>
                        </vs-tr>
                    </template>
                </vs-table>
            </div>
            <!-- Popup Crear Formula de Produccion -->
            <vs-popup
                title="Crear Nueva Fórmula de Producción"
                :active.sync="popucreaformprod"
            >
                <div class="vx-row">
                    <vs-divider position="left"
                        >Información de Fórmula</vs-divider
                    >
                    <div class="vx-col md:w-1/5 sm:w-full w-full mb-3">
                        <vs-input
                            disabled
                            class="w-full txt-center"
                            label="Código de Producción:"
                            v-model="cod_pro"
                        />
                        <div v-show="error1" v-if="!cod_pro">
                            <div
                                v-for="err in errorcod_pro"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>

                    <div class="vx-col md:w-3/5 sm:w-full w-full mb-3" id="sa">
                        <vs-input
                            class="w-full"
                            label="Nombre de Fórmula:"
                            v-model="nom_pro"
                        />
                        <div v-show="error1" v-if="!nom_pro">
                            <div
                                v-for="err in errornom_pro"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <vs-divider />
                    <vs-divider position="left"
                        >Productos a Producir</vs-divider
                    >
                    <!-- Agregar Productos Formula Produccion-->
                    <a
                        class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
                        @click="abrirproductos()"
                        >Añadir Productos</a
                    >
                    <div class="vx-col md:w-full sm:w-full w-full mb-3">
                        <vs-table :data="continprod">
                            <template slot="thead">
                                <vs-th>Código Producto</vs-th>
                                <vs-th class="text-center"
                                    >Nombre Producto</vs-th
                                >
                                <vs-th class="text-center"></vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :key="indextr"
                                    v-for="(tr, indextr) in data"
                                >
                                    <vs-td
                                        style="width:30%!important;"
                                        :data="data[indextr].cod_principal"
                                        >{{
                                            data[indextr].cod_principal
                                        }}</vs-td
                                    >
                                    <vs-td
                                        class="text-center"
                                        style="width:50%!important;"
                                        :data="data[indextr].nombre"
                                        >{{ data[indextr].nombre }}</vs-td
                                    >
                                    <vs-td
                                        class="text-center"
                                        style="width=20%!important;"
                                    >
                                        <vx-tooltip
                                            text="Eliminar"
                                            position="top"
                                            style="display: inline-flex;"
                                        >
                                            <feather-icon
                                                icon="TrashIcon"
                                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                class="pointer"
                                                @click="eliminarp(indextr)"
                                            />
                                        </vx-tooltip>
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                    <!--Agregar Ingredientes Formula Produccion-->
                    <vs-divider />
                    <vs-divider position="left"
                        >Ingredientes de Producto</vs-divider
                    >
                    <a
                        class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
                        @click="abriringredientes()"
                        >Añadir Ingredientes</a
                    >
                    <div v-show="error1" v-if="!contingred.length">
                        <div
                            v-for="err in erroringred"
                            :key="err"
                            v-text="err"
                            class="text-danger"
                        ></div>
                    </div>
                    <div class="vx-col md:w-full sm:w-full w-full mb-3">
                        <vs-table :data="contingred">
                            <template slot="thead">
                                <vs-th>Código Producto</vs-th>
                                <vs-th class="text-center"
                                    >Nombre Ingrediente</vs-th
                                >
                                <vs-th class="text-center"
                                    >Catidad por Unidad de Formula</vs-th
                                >
                                <vs-th class="text-center"> </vs-th>
                            </template>
                            <template slot-scope="{ data }">
                                <vs-tr
                                    :key="indextr"
                                    v-for="(tr, indextr) in data"
                                >
                                    <vs-td
                                        style="width:20%!important;"
                                        :data="data[indextr].cod_principal"
                                        >{{
                                            data[indextr].cod_principal
                                        }}</vs-td
                                    >
                                    <vs-td
                                        class="text-center"
                                        style="width:50%!important;"
                                        :data="data[indextr].nombre"
                                        >{{ data[indextr].nombre }}</vs-td
                                    >
                                    <vs-td
                                        class="text-center"
                                        style="width:20%!important;"
                                        :data="data[indextr].cant_form"
                                    >
                                        <vs-input
                                            class="w-full txt-center"
                                            placeholder="0.000000"
                                            v-model="tr.cant_form"
                                            onkeypress="return filterFloat(event, this)"
                                        />
                                        <div
                                            v-show="error1"
                                            v-if="!tr.cant_form"
                                        >
                                            <div
                                                v-for="err in tr.errorcant_form"
                                                :key="err"
                                                v-text="err"
                                                class="text-danger"
                                            ></div>
                                        </div>
                                    </vs-td>
                                    <vs-td
                                        class="text-center"
                                        style="width=10!important;"
                                    >
                                        <vx-tooltip
                                            text="Eliminar"
                                            position="top"
                                            style="display: inline-flex;"
                                        >
                                            <feather-icon
                                                icon="TrashIcon"
                                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                class="pointer"
                                                @click="eliminari(indextr)"
                                            />
                                        </vx-tooltip>
                                    </vs-td>
                                </vs-tr>
                            </template>
                        </vs-table>
                    </div>
                    <div class="vx-col w-full">
                        <vs-button
                            color="success"
                            type="border"
                            @click="guardarformula()"
                            >Guardar</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="border"
                            @click="cancelarformula()"
                            >Cancelar</vs-button
                        >
                    </div>
                    <!-- Popup Agregar Producto e Ingrediente Formula Produccion-->
                    <vs-popup
                        classContent="popup-example"
                        title="Seleccione el Producto"
                        :active.sync="popupprod"
                    >
                        <div class="vx-col w-full" v-if="tipot == 1">
                            <vs-input
                                class="mb-4 mr-4 w-full"
                                v-model="buscarp"
                                @keyup="listarp(1, buscarp)"
                                v-bind:placeholder="i18nbuscar"
                            />
                            <vs-table
                                stripe
                                v-model="contenidoingred"
                                @selected="handleSelectedp"
                                :data="arrayingrediente"
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
                                        <vs-td v-if="tr.cod_principal">{{
                                            tr.cod_principal
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
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
                        <div class="vx-col w-full" v-if="tipot == 2">
                            <vs-input
                                class="mb-4 mr-4 w-full"
                                v-model="buscarp"
                                @keyup="listarp(1, buscarp)"
                                v-bind:placeholder="i18nbuscar"
                            />
                            <vs-table
                                stripe
                                v-model="contenidoingred"
                                @selected="handleSelectedi"
                                :data="arrayingrediente"
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
                                        <vs-td v-if="tr.cod_principal">{{
                                            tr.cod_principal
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
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
                </div>
            </vs-popup>
        </vs-popup>
    </vx-card>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import VueUploadMultipleImage from "vue-upload-multiple-image";
import moment from "moment";
const $ = require("jquery");
const axios = require("axios");
export default {
    data() {
        return {
            //variebale de informacion de empresa
            empresa: [],
            //variable de recuperacion de id producto al editar
            idrecupera: null,
            //cuenta contable listar
            contenidocuenta: [],
            popupActive: false,
            //formula produccion
            contenidoformula: [],
            popupformprod: false,
            popupprod: false,
            popucreaformprod: false,
            //variables paginacion de las tablas
            pagination1: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina1: 1,
            cantidadp1: 1000000,
            offset: 3,
            gridApi: null,
            contenido: [],

            //buscador
            buscar: "",
            buscar1: "",
            criterio1: "codcta",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),

            //Variables Producto
            sector: "",
            categoria: "",
            cod_principal: "",
            cod_alterno: "",
            nombre: "",
            nombrec: "",
            cod_barras: "",
            cta_prod: "",
            cta_prod1: "",
            form_prod: "",
            formu_prod: "",
            descripcion: "",
            caracteristicas: "",
            normativa: "",
            uso: "",
            medicamento_controlado: false,
            psicotropicos: "",
            //Línea de Producto:
            //Línea de Producto: fk
            linea_producto: "",
            //tipo_producto fk listar
            tipo_producto: "",
            marca: "",
            modelo: "",
            presentacion: "",

            //sector: "",
            tipo_servicio: "",
            unidad_salida: "",
            vencimiento: "",
            existencia_max: 1,
            existencia_min: 1,
            //Dimensiones del Producto:
            tipo_medida: "",
            unidad_medida: "",
            numero_unidad: "",
            grados_alcohol: "",
            estado: 1,
            numero_serie: "",
            //estado controla si y no
            //VEHICULO
            vehiculo: false,
            placa: "",
            pais_origen: "",
            ano_fabricacionv: "",
            color: "",
            carroceria: "",
            combustible: "",
            motor: "",
            cilindraje: "",
            chasis: "",
            clase: "",
            subclase: "",
            numero_pasajeros: 1,
            //Rubros del Producto:
            iva: "",
            ice: "",
            arancel_advalorem: "",
            arancel_especifico: "",
            arancel_fodinfa: "",
            comision: "",
            salvaguardia: "",
            descuento: "",
            cacular_precios: false,
            costo_unitario: "",
            precio1: "",
            precio2: "",
            precio3: "",
            precio4: "",
            precio5: "",
            utilidad_precio1: "",
            utilidad_precio2: "",
            utilidad_precio3: "",
            utilidad_precio4: "",
            utilidad_precio5: "",
            fecha_fabricacion: moment().format("YYYY-M-D"),
            ultimo_costo: "",
            costo_promedio: "",
            costo_total: "",
            existencia_total: "",
            update: "",
            /**
             * Errores Validaciones variables
             * @variables
             */
            error: 0,
            errornombre: [],
            errorcta_prod: [],
            errordescripcion: [],
            errorcodpri: [],
            errorcodalt: [],
            errorcodbarras: [],
            errorcodcta: [],
            errorlinea: [],
            errortipo: [],
            errormarca: [],
            errormodelo: [],
            errorpresentacion: [],
            errorsector: [],
            errortipo_servicio: [],
            errorunidad_salida: [],
            errortipo_medida: [],
            errorunidad_medida: [],
            errornumero_unidad: [],
            errorestado: [],
            errorplaca: [],
            errorpais_origen: [],
            errorano_fabricacionv: [],
            errorcolor: [],
            errorcarroceria: [],
            errorcombustible: [],
            errormotor: [],
            errorcilindraje: [],
            errorchasis: [],
            errorclase: [],
            errorsubclase: [],
            errornumero_pasajeros: [],
            erroriva: [],
            errorice: [],
            //establece calendario español
            configdateTimePicker: {
                locale: SpanishLocale
            },

            optionscategoria: [
                // { text: "General", value: "General" },
                // { text: "Seguridad Industrial", value: "Seguridad Industrial" },
                // { text: "Vehículos", value: "Vehículos" },
                // { text: "Electrodomésticos", value: "Electrodomésticos" },
                // { text: "Licores", value: "Licores" },
                // { text: "Medicamentos", value: "Medicamentos" }
                // { text: "Tecnología", value: "Tecnología" },
                // { text: "Alimentos", value: "Alimentos" },
                // { text: "Insumos Médicos", value: "Insumos Médicos" }
            ],
            options1: [
                { text: "Activo", value: 1 },
                { text: "Inactivo", value: 0 }
            ],
            options2: [
                { text: "ubicacio1", value: 1 },
                { text: "ubicacion2", value: 2 }
            ],
            options3: [
                { text: "Gasolina 98", value: 1 },
                { text: "Gasolina 95", value: 2 },
                { text: "Bioetanol", value: 3 },
                { text: "Diésel normal", value: 4 },
                { text: "Diésel Plus", value: 5 },
                { text: "Biodiésel", value: 6 },
                { text: "Diésel 1D,2D, 4D", value: 7 },
                { text: "Gas Natural", value: 8 }
            ],
            contenidopsicotropicos: [
                { text: "Psicotrópico", value: "Psicotrópico" },
                { text: "Estupefacientes", value: "Estupefacientes" },
                { text: "Otros", value: "Otros" }
            ],
            //campos adicionales
            agregados: [],
            //arrays de carga de datos
            contenidolinea: [],
            contenidotipo: [],
            contenidomarca: [],
            contenidomodelo: [],
            contenidopresentacion: [],
            contenidotipomedida: [],
            contenidounidadmedidar: [],
            //por implemenar campsoa dicionales
            //contenidocamposadicionales: [],
            //ocult: false,
            //contenidocamposadicionalesbet: [],
            contenidoiva: [],
            contenidoice: [],
            imagen: "",
            imagenprevisualizar: [],
            recuperaimagen: 0,
            //Variables para crear formula de produccion
            cod_pro: "",
            nom_pro: "",
            prod_produ: "",
            cant_form: "",
            //buscar
            buscarp: "",
            i18nbuscar: this.$t("i18nbuscar"),
            //arrays formula
            arrayingrediente: [],
            arrayprodprodu: [],
            contenidoingred: [],
            continprod: [],
            contingred: [],
            tipot: 0,

            //validacion formula produccion
            error1: 0,
            errorcod_pro: [],
            errornom_pro: [],
            erroringred: [],
            errorprod_produ: [],
            errorcant_form: [],
            //test
            test: ""
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        total_ice() {
            var total_ice = null;
            var iceselec = [];
            var valorice = null;
            var formulacalcularice = "";
            for (let i = 0; i < this.contenidoice.length; i++) {
                if (this.contenidoice[i].id_ice == this.ice) {
                    valorice = this.contenidoice[i].valor;
                    if (this.contenidoice[i].formula == null) {
                        iceselec = this.contenidoice[i].formula;
                    } else {
                        iceselec = this.contenidoice[i].formula.split(" ");
                    }
                }
            }
            if (iceselec == null) {
                formulacalcularice = valorice;
            } else {
                for (let i = 0; i < iceselec.length; i++) {
                    switch (iceselec[i]) {
                        case "Vacio":
                            formulacalcularice = "0";
                            break;
                        case "+":
                            formulacalcularice += "+";
                            break;
                        case "-":
                            formulacalcularice += "-";
                            break;
                        case "*":
                            formulacalcularice += "*";
                            break;
                        case "/":
                            formulacalcularice += "/";
                            break;
                        case "(":
                            formulacalcularice += "(";
                            break;
                        case ")":
                            formulacalcularice += ")";
                            break;
                        case "Valor_ICE":
                            formulacalcularice += valorice;
                            break;
                        case "Grados_de_alcohol":
                            var grados_alcohol = 0;
                            if (this.grados_alcohol)
                                grados_alcohol = this.grados_alcohol;
                            formulacalcularice =
                                formulacalcularice + grados_alcohol;
                            break;
                        case "Litros":
                            var numero_unidad = 0;
                            if (this.numero_unidad)
                                numero_unidad = this.numero_unidad;
                            formulacalcularice =
                                formulacalcularice + numero_unidad;
                            break;
                        case "Numero_unidad":
                            var numero_unidad = 0;
                            if (this.numero_unidad)
                                numero_unidad = this.numero_unidad;
                            formulacalcularice =
                                formulacalcularice + numero_unidad;
                            break;
                    }
                }
            }
            console.log(formulacalcularice);
            if (formulacalcularice)
                total_ice = eval(formulacalcularice).toFixed(2);
            return total_ice;
        }
    },
    //importa calendario español
    components: {
        flatPickr,
        VueUploadMultipleImage
    },
    methods: {
        //funcionales de cmapso adicionales "no implementado"
        /*agregarcampo() {
            this.ocult = true;
            if (this.contenidocamposadicionales.length < 6) {
                this.contenidocamposadicionales.push({
                    nombrec: "",
                    contenido: ""
                });
            } else {
                this.$vs.notify({
                    title: "Error al Agregar Campo",
                    text: "No se puede agregar mas de 6 campos",
                    color: "danger"
                });
            }
        },
        quitarcampo(x) {
            this.contenidocamposadicionales.splice(x, 1);
        },*/
        /**
         * guardar los datos del formulario catalogo mediante
         * axios
         * @ funcion guardar
         * @metod
         *    axios envio de datos
         *    post
         */

        /*---------------------------------------------------------------------Funciones para controlar la vista de interfaz----------------------------------------------------*/

        /*------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/
        listar_empresa() {
            var url = "/api/productoempresa/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(res => {
                    this.empresa = res.data[0];
                })
                .catch(err => {
                    console.log(err);
                });
        },
        listar_categorias(){
            var url = "/api/productos/categorias/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(res => {
                    this.optionscategoria = res.data;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        guardar() {
            if (this.validar()) {
                return;
            }
            let formData = new FormData();
            if (this.categoria != null)
                formData.append("categoria", this.categoria);
            if (this.cod_principal != null)
                formData.append("cod_principal", this.cod_principal);
            if (this.cod_alterno != null)
                formData.append("cod_alterno", this.cod_alterno);
            if (this.nombre != null) formData.append("nombre", this.nombre);
            if (this.cod_barras != null)
                formData.append("cod_barras", this.cod_barras);
            if (this.cta_prod != null)
                formData.append("cta_prod", this.cta_prod);
            if (this.cta_prod1 != null)
                formData.append("cta_prod1", this.cta_prod1);
            if (this.form_prod != null)
                formData.append("form_prod", this.form_prod);
            if (this.descripcion != null)
                formData.append("descripcion", this.descripcion);
            if (this.caracteristicas != null)
                formData.append("caracteristicas", this.caracteristicas);
            if (this.normativa != null)
                formData.append("normativa", this.normativa);
            if (this.uso != null) formData.append("uso", this.uso);
            if (this.contenidocamposadicionales != null)
                formData.append("agregados", this.contenidocamposadicionales);
            if (this.nombrec != null) formData.append("nombrec", this.nombrec);
            if (this.contenido != null)
                formData.append("contenido", this.contenido);
            if (this.medicamento_controlado != null)
                formData.append(
                    "medicamento_controlado",
                    this.medicamento_controlado
                );
            if (this.psicotropicos != null)
                formData.append("psicotropicos", this.psicotropicos);
            if (this.linea_producto != null)
                formData.append("linea_producto", this.linea_producto);
            if (this.tipo_producto != null)
                formData.append("tipo_producto", this.tipo_producto);
            if (this.marca != null) formData.append("marca", this.marca);
            if (this.modelo != null) formData.append("modelo", this.modelo);
            if (this.presentacion != null)
                formData.append("presentacion", this.presentacion);
            if (this.sector != null) formData.append("sector", this.sector);
            if (this.tipo_servicio != null)
                formData.append("tipo_servicio", this.tipo_servicio);
            if (this.unidad_salida != null)
                formData.append("unidad_salida", this.unidad_salida);
            if (this.vencimiento != null)
                formData.append("vencimiento", this.vencimiento);
            if (this.existencia_max != null)
                formData.append("existencia_max", this.existencia_max);
            if (this.existencia_min != null)
                formData.append("existencia_min", this.existencia_min);
            if (this.tipo_medida != null)
                formData.append("tipo_medida", this.tipo_medida);
            if (this.unidad_medida != null)
                formData.append("unidad_medida", this.unidad_medida);
            if (this.numero_unidad != null)
                formData.append("numero_unidad", this.numero_unidad);
            if (this.grados_alcohol != null)
                formData.append("grados_alcohol", this.grados_alcohol);
            if (this.estado != null) formData.append("estado", this.estado);
            if (this.numero_serie != null)
                formData.append("numero_serie", this.numero_serie);
            if (this.vehiculo != null)
                if (this.vehiculo == true) {
                    formData.append("vehiculo", 1);
                } else {
                    formData.append("vehiculo", 0);
                }
            if (this.placa != null) formData.append("placa", this.placa);
            if (this.pais_origen != null)
                formData.append("pais_origen", this.pais_origen);
            if (this.ano_fabricacionv != null)
                formData.append("ano_fabricacion", this.ano_fabricacionv);
            if (this.color != null) formData.append("color", this.color);
            if (this.carroceria != null)
                formData.append("carroceria", this.carroceria);
            if (this.combustible != null)
                formData.append("combustible", this.combustible);
            if (this.motor != null) formData.append("motor", this.motor);
            if (this.cilindraje != null)
                formData.append("cilindraje", this.cilindraje);
            if (this.chasis != null) formData.append("chasis", this.chasis);
            if (this.clase != null) formData.append("clase", this.clase);
            if (this.subclase != null)
                formData.append("subclase", this.subclase);
            if (this.numero_pasajeros != null)
                formData.append("numero_pasajeros", this.numero_pasajeros);
            if (this.iva != null) formData.append("iva", this.iva);
            if (this.ice != null) formData.append("ice", this.ice);
            if (this.total_ice != null)
                formData.append("total_ice", this.total_ice);
            if (this.arancel_advalorem != null)
                formData.append("arancel_advalorem", this.arancel_advalorem);
            if (this.arancel_especifico != null)
                formData.append("arancel_especifico", this.arancel_especifico);
            if (this.arancel_fodinfa != null)
                formData.append("arancel_fodinfa", this.arancel_fodinfa);
            if (this.comision != null)
                formData.append("comision", this.comision);
            if (this.salvaguardia != null)
                formData.append("salvaguardia", this.salvaguardia);
            if (this.descuento != null)
                formData.append("descuento", this.descuento);
            if (this.costo_unitario != null)
                formData.append("costo_unitario", this.costo_unitario);
            if (this.precio1 != null) formData.append("precio1", this.precio1);
            if (this.precio2 != null) formData.append("precio2", this.precio2);
            if (this.precio3 != null) formData.append("precio3", this.precio3);
            if (this.precio4 != null) formData.append("precio4", this.precio4);
            if (this.precio5 != null) formData.append("precio5", this.precio5);
            if (this.utilidad_precio1 != null)
                formData.append("utilidad_precio1", this.utilidad_precio1);
            if (this.utilidad_precio2 != null)
                formData.append("utilidad_precio2", this.utilidad_precio2);
            if (this.utilidad_precio3 != null)
                formData.append("utilidad_precio3", this.utilidad_precio3);
            if (this.utilidad_precio4 != null)
                formData.append("utilidad_precio4", this.utilidad_precio4);
            if (this.utilidad_precio5 != null)
                formData.append("utilidad_precio5", this.utilidad_precio5);
            if (this.fecha_fabricacion != null)
                formData.append("fecha_fabricacion", this.fecha_fabricacion);
            if (this.ultimo_costo != null)
                formData.append("ultimo_costo", this.ultimo_costo);
            if (this.costo_promedio != null)
                formData.append("costo_promedio", this.costo_promedio);
            if (this.costo_total != null)
                formData.append("costo_total", this.costo_total);
            if (this.existencia_total != null)
                formData.append("existencia_total", this.existencia_total);
            if (this.usuario != null)
                formData.append("id_empresa", this.usuario.id_empresa);
            if (this.usuario != null)
                formData.append("id_rol", this.usuario.id_rol);
            if (this.imagen != null)
                formData.append("file_imagen", this.imagen);
            if (this.formu_prod != null)
                formData.append("id_formu_prod", this.formu_prod);
            axios
                .post("/api/guardarproductos", formData)
                .then(res => {
                    if (res.data == "cuentamal") {
                        window.scrollTo(0, 0);
                        this.$vs.notify({
                            title: "Cuenta Contable",
                            text: "La cuenta ingresada no existe",
                            color: "danger"
                        });
                    } else {
                        this.$vs.notify({
                            title: "Registro Exitoso",
                            text: "Registro agregado con éxito",
                            color: "success"
                        });
                        this.$router.push("/inventario/catalogo");
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /*
         *Editar formulario de Catalogo
         *funcion editar
         */
        editar() {
            if (this.validar()) {
                return;
            }
            let formData = new FormData();
            formData.append("id", this.$route.params.id);
            if (this.recuperaimagen != null)
                formData.append("recuperaimagen", this.recuperaimagen);
            if (this.categoria != null)
                formData.append("categoria", this.categoria);
            if (this.cod_principal != null)
                formData.append("cod_principal", this.cod_principal);
            if (this.cod_alterno != null)
                formData.append("cod_alterno", this.cod_alterno);
            if (this.nombre != null) formData.append("nombre", this.nombre);
            if (this.cod_barras != null)
                formData.append("cod_barras", this.cod_barras);
            if (this.cta_prod != null)
                formData.append("cta_prod", this.cta_prod);
            if (this.cta_prod1 != null)
                formData.append("cta_prod1", this.cta_prod1);
            if (this.form_prod != null)
                formData.append("form_prod", this.form_prod);
            if (this.descripcion != null)
                formData.append("descripcion", this.descripcion);
            if (this.caracteristicas != null)
                formData.append("caracteristicas", this.caracteristicas);
            if (this.normativa != null)
                formData.append("normativa", this.normativa);
            if (this.uso != null) formData.append("uso", this.uso);
            if (this.contenidocamposadicionales != null)
                formData.append("agregados", this.contenidocamposadicionales);
            if (this.nombrec != null) formData.append("nombrec", this.nombrec);
            if (this.contenido != null)
                formData.append("contenido", this.contenido);
            if (this.medicamento_controlado != null)
                formData.append(
                    "medicamento_controlado",
                    this.medicamento_controlado
                );
            if (this.psicotropicos != null)
                formData.append("psicotropicos", this.psicotropicos);
            if (this.linea_producto != null)
                formData.append("linea_producto", this.linea_producto);
            if (this.tipo_producto != null)
                formData.append("tipo_producto", this.tipo_producto);
            if (this.marca != null) formData.append("marca", this.marca);
            if (this.modelo != null) formData.append("modelo", this.modelo);
            if (this.presentacion != null)
                formData.append("presentacion", this.presentacion);
            if (this.sector != null) formData.append("sector", this.sector);
            if (this.tipo_servicio != null)
                formData.append("tipo_servicio", this.tipo_servicio);
            if (this.unidad_salida != null)
                formData.append("unidad_salida", this.unidad_salida);
            if (this.vencimiento != null)
                formData.append("vencimiento", this.vencimiento);
            if (this.existencia_max != null)
                formData.append("existencia_max", this.existencia_max);
            if (this.existencia_min != null)
                formData.append("existencia_min", this.existencia_min);
            if (this.tipo_medida != null)
                formData.append("tipo_medida", this.tipo_medida);
            if (this.unidad_medida != null)
                formData.append("unidad_medida", this.unidad_medida);
            if (this.numero_unidad != null)
                formData.append("numero_unidad", this.numero_unidad);
            if (this.grados_alcohol != null)
                formData.append("grados_alcohol", this.grados_alcohol);
            if (this.estado != null) formData.append("estado", this.estado);
            if (this.numero_serie != null)
                formData.append("numero_serie", this.numero_serie);
            if (this.vehiculo != null)
                if (this.vehiculo == true) {
                    formData.append("vehiculo", 1);
                } else {
                    formData.append("vehiculo", 0);
                }
            if (this.placa != null) formData.append("placa", this.placa);
            if (this.pais_origen != null)
                formData.append("pais_origen", this.pais_origen);
            if (this.ano_fabricacionv != null)
                formData.append("ano_fabricacion", this.ano_fabricacionv);
            if (this.color != null) formData.append("color", this.color);
            if (this.carroceria != null)
                formData.append("carroceria", this.carroceria);
            if (this.combustible != null)
                formData.append("combustible", this.combustible);
            if (this.motor != null) formData.append("motor", this.motor);
            if (this.cilindraje != null)
                formData.append("cilindraje", this.cilindraje);
            if (this.chasis != null) formData.append("chasis", this.chasis);
            if (this.clase != null) formData.append("clase", this.clase);
            if (this.subclase != null)
                formData.append("subclase", this.subclase);
            if (this.numero_pasajeros != null)
                formData.append("numero_pasajeros", this.numero_pasajeros);
            if (this.iva != null) formData.append("iva", this.iva);
            if (this.ice != null) formData.append("ice", this.ice);
            if (this.total_ice != null)
                formData.append("total_ice", this.total_ice);
            if (this.arancel_advalorem != null)
                formData.append("arancel_advalorem", this.arancel_advalorem);
            if (this.arancel_especifico != null)
                formData.append("arancel_especifico", this.arancel_especifico);
            if (this.arancel_fodinfa != null)
                formData.append("arancel_fodinfa", this.arancel_fodinfa);
            if (this.comision != null)
                formData.append("comision", this.comision);
            if (this.salvaguardia != null)
                formData.append("salvaguardia", this.salvaguardia);
            if (this.descuento != null)
                formData.append("descuento", this.descuento);
            if (this.costo_unitario != null)
                formData.append("costo_unitario", this.costo_unitario);
            if (this.precio1 != null) formData.append("precio1", this.precio1);
            if (this.precio2 != null) formData.append("precio2", this.precio2);
            if (this.precio3 != null) formData.append("precio3", this.precio3);
            if (this.precio4 != null) formData.append("precio4", this.precio4);
            if (this.precio5 != null) formData.append("precio5", this.precio5);
            if (this.utilidad_precio1 != null)
                formData.append("utilidad_precio1", this.utilidad_precio1);
            if (this.utilidad_precio2 != null)
                formData.append("utilidad_precio2", this.utilidad_precio2);
            if (this.utilidad_precio3 != null)
                formData.append("utilidad_precio3", this.utilidad_precio3);
            if (this.utilidad_precio4 != null)
                formData.append("utilidad_precio4", this.utilidad_precio4);
            if (this.utilidad_precio5 != null)
                formData.append("utilidad_precio5", this.utilidad_precio5);
            if (this.fecha_fabricacion != null)
                formData.append("fecha_fabricacion", this.fecha_fabricacion);
            if (this.ultimo_costo != null)
                formData.append("ultimo_costo", this.ultimo_costo);
            if (this.costo_promedio != null)
                formData.append("costo_promedio", this.costo_promedio);
            if (this.costo_total != null)
                formData.append("costo_total", this.costo_total);
            if (this.existencia_total != null)
                formData.append("existencia_total", this.existencia_total);
            if (this.usuario != null)
                formData.append("id_empresa", this.usuario.id_empresa);
            if (this.usuario != null)
                formData.append("id_rol", this.usuario.id_rol);
            if (this.imagen != null)
                formData.append("file_imagen", this.imagen);
            if (this.formu_prod != null)
                formData.append("id_formu_prod", this.formu_prod);
            axios
                .post("/api/actualizarproductos", formData)
                .then(res => {
                    if (res.data == "cuentamal") {
                        window.scrollTo(0, 0);
                        this.$vs.notify({
                            title: "Cuenta Contable",
                            text: "La cuenta ingresada no existe",
                            color: "danger"
                        });
                    } else {
                        this.$vs.notify({
                            title: "Registro Actualizado",
                            text: "Registro actualizado con éxito",
                            color: "success"
                        });
                        this.$router.push("/inventario/catalogo");
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        /**
         * funcion para listar datos catalogo, para editar
         *@ funcion listar
         */
        listar() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrirproductos/" + this.idrecupera;
                axios
                    .get(url)
                    .then(res => {
                        this.sector = res.data.sector;
                        this.categoria = res.data.categoria;
                        setTimeout(() => {
                            this.cod_principal = res.data.cod_principal;
                            this.cod_alterno = res.data.cod_alterno;
                            this.nombre = res.data.nombre;
                            this.cod_barras = res.data.codigo_barras;
                            this.descripcion = res.data.descripcion;
                            this.caracteristicas = res.data.caracteristicas;
                            this.normativa = res.data.normativa;
                            this.uso = res.data.uso;
                            this.cta_prod = res.data.id_plan_cuentas;
                            this.cta_prod1 = res.data.cta_prod1;
                            this.form_prod = res.data.form_prod;
                            this.imagen = res.data.imagen;
                            /* axios.get("/api/camposadicionales").then(response => {
                            var adicional = res.data.nombrec.split("||");
                            var finalffcamposs = [];
                            for (var f = 0; f < response.res.data.length; f++) {
                                finalffcamposs.push(response.res.data[f].nombre);
                            }
                            var finalff = [];
                            for (var i = 0; i < finalffcamposs.length; i++) {
                                finalff.push({
                                    nombre: adicional[i],
                                    descripcion: finalffcamposs[i]
                                });
                            }
                            this.contenidocamposadicionales = finalff;
                        });*/
                            //tipo_producto fk listar
                            this.marca = res.data.id_marca;
                            this.modelo = res.data.id_modelo;
                            this.presentacion = res.data.id_presentacion;
                            this.tipo_servicio = res.data.tipo_servicio;
                            this.unidad_salida = res.data.unidad_salida;
                            this.vencimiento = res.data.vencimiento;
                            this.existencia_max = res.data.existencia_maxima;
                            this.existencia_min = res.data.existencia_minima;
                            //Dimensiones del Producto:
                            this.tipo_medida = res.data.id_tipo_medida;
                            //Línea de Producto:
                            //Línea de Producto: fk
                            this.medicamento_controlado =
                                res.data.medicamento_controlado;
                            this.psicotropicos = res.data.psicotropicos;
                            this.linea_producto = res.data.id_linea_producto;
                            setTimeout(() => {
                                this.unidad_medida = res.data.id_unidad_medida;
                                this.tipo_producto = res.data.id_tipo_producto;
                            }, 200);
                            this.numero_unidad = res.data.numero_unidad;
                            this.grados_alcohol = res.data.grados_alcohol;
                            this.estado = res.data.estado;
                            this.numero_serie = res.data.numero_serie;
                            //VEHICULO
                            if (res.data.vehiculo == 1) {
                                this.vehiculo = true;
                            } else if (res.data.vehiculo == 0) {
                                this.vehiculo = false;
                            }
                            this.placa = res.data.placa;
                            this.pais_origen = res.data.pais_origen;
                            this.ano_fabricacionv = res.data.ano_fabricacionv;
                            this.color = res.data.color;
                            this.carroceria = res.data.carroceria;
                            this.combustible = res.data.combustible;
                            this.motor = res.data.motor;
                            this.cilindraje = res.data.cilindraje;
                            this.chasis = res.data.chasis;
                            this.clase = res.data.clase;
                            this.subclase = res.data.subclase;
                            this.numero_pasajeros = res.data.numero_pasajeros;
                            //Rubros del Producto:
                            this.iva = res.data.iva;
                            this.ice = res.data.ice;
                            this.arancel_advalorem = res.data.arancel_advalorem;
                            this.arancel_especifico =
                                res.data.arancel_especifico;
                            this.arancel_fodinfa = res.data.arancel_fodinfa;
                            this.comision = res.data.comision;
                            this.salvaguardia = res.data.salvaguardia;
                            this.descuento = res.data.descuento;
                            this.costo_unitario = res.data.costo_unitario;
                            this.precio1 = res.data.pvp_precio1;
                            this.precio2 = res.data.precio2;
                            this.precio3 = res.data.precio3;
                            this.precio4 = res.data.precio4;
                            this.precio5 = res.data.precio5;
                            this.utilidad_precio1 = res.data.utilidad_precio1;
                            this.utilidad_precio2 = res.data.utilidad_precio2;
                            this.utilidad_precio3 = res.data.utilidad_precio3;
                            this.utilidad_precio4 = res.data.utilidad_precio4;
                            this.utilidad_precio5 = res.data.utilidad_precio5;
                            this.fecha_fabricacion = res.data.fecha_fabricacion;
                            this.ultimo_costo = res.data.ultimo_costo;
                            this.costo_promedio = res.data.costo_promedio;
                            this.costo_total = res.data.costo_total;
                            this.existencia_total = res.data.existencia_total;
                            if (
                                this.utilidad_precio1 ||
                                this.utilidad_precio2 ||
                                this.utilidad_precio3 ||
                                this.utilidad_precio4 ||
                                this.utilidad_precio5
                            ) {
                                this.cacular_precios = true;
                            }

                            if (this.imagen) {
                                this.recuperaimagen = 1;
                            }
                        }, 100);
                    })
                    .catch(err => {
                        console.log(err);
                    });
            } else {
                this.idrecupera = null;
            }
        },
        /**
         * validar, valida campso obligatios para guardado de producto
         * @ funcion validar
         */
        validar() {
            this.error = 0;
            this.errornombre = [];
            this.errorcta_prod = [];
            this.errordescripcion = [];
            this.errorcodpri = [];
            this.errorlinea = [];
            this.errortipo = [];
            this.errormarca = [];
            this.errormodelo = [];
            this.errorpresentacion = [];
            this.errorsector = [];
            this.errortipo_servicio = [];
            this.errorunidad_salida = [];
            this.errortipo_medida = [];
            this.errorunidad_medida = [];
            this.errornumero_unidad = [];
            this.errorestado = [];
            this.errorplaca = [];
            this.errorpais_origen = [];
            this.errorano_fabricacionv = [];
            this.errorcolor = [];
            this.errorcarroceria = [];
            this.errorcombustible = [];
            this.errormotor = [];
            this.errorcilindraje = [];
            this.errorchasis = [];
            this.errorclase = [];
            this.errorsubclase = [];
            this.errornumero_pasajeros = [];
            this.erroriva = [];
            this.errorice = [];
            if (this.sector == "") {
                this.error = 1;
                this.errorsector.push("Debe seleccionar uno");
                window.scrollTo(0, 0);
                this.$vs.notify({
                    title: "Sector no especificado",
                    text: "Seleccione Producto o Servicio antes de Guardar",
                    color: "danger"
                });
            } else if (this.sector == 1) {
                if (!this.nombre) {
                    this.errornombre.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.linea_producto) {
                    this.errorlinea.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.tipo_producto) {
                    this.errortipo.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.marca) {
                    this.errormarca.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.modelo) {
                    this.errormodelo.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.presentacion) {
                    this.errorpresentacion.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.unidad_salida) {
                    this.errorunidad_salida.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.tipo_medida) {
                    this.errortipo_medida.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.unidad_medida) {
                    this.errorunidad_medida.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.numero_unidad) {
                    this.errornumero_unidad.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                /*if (!this.estado) {
                    this.errorestado.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }*/
                if (this.vehiculo == 1) {
                    if (!this.placa) {
                        this.errorplaca.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.pais_origen) {
                        this.errorpais_origen.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.ano_fabricacionv) {
                        this.errorano_fabricacionv.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.color) {
                        this.errorcolor.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.carroceria) {
                        this.errorcarroceria.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.combustible) {
                        this.errorcombustible.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.motor) {
                        this.errormotor.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.cilindraje) {
                        this.errorcilindraje.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.chasis) {
                        this.errorchasis.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.clase) {
                        this.errorclase.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.subclase) {
                        this.errorsubclase.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                    if (!this.numero_pasajeros) {
                        this.errornumero_pasajeros.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                }
                if (!this.iva) {
                    this.erroriva.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.ice) {
                    this.errorice.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            } else if (this.sector == 2) {
                if (!this.nombre) {
                    this.errornombre.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (this.empresa.obligado_contabilidad == 1) {
                    if (!this.cta_prod) {
                        this.errorcta_prod.push("Campo obligatorio");
                        this.error = 1;
                        window.scrollTo(0, 0);
                    }
                }
                if (!this.descripcion) {
                    this.errordescripcion.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.iva) {
                    this.erroriva.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.ice) {
                    this.errorice.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
                if (!this.tipo_servicio) {
                    this.errortipo_servicio.push("Campo obligatorio");
                    this.error = 1;
                    window.scrollTo(0, 0);
                }
            }
            if (this.cacular_precios == true) {
                if (!this.utilidad_precio1) {
                    this.error = 1;
                    this.$vs.notify({
                        title: "Precios no calculados",
                        text:
                            "Ingrese almenos un porcentaje de utilidad para calcular el precio",
                        color: "danger"
                    });
                }
            }
            return this.error;
        },
        /**
         * solonumeros pertime solo en ingreso de nuemeros en el formulario.
         * @  funcion solonumeros
         */
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
        /**
         * Cerrar borrar todos los campos del formulario.
         * @ funcion cerrar
         */
        cerrar() {
            this.cod_principal = "";
            this.cod_alterno = "";
            this.nombre = "";
            this.nombre = "";
            this.cod_barras = "";
            this.cta_prod = "";
            this.cta_prod1 = "";
            this.form_prod = "";
            this.formu_prod = "";
            this.descripcion = "";
            this.caracteristicas = "";
            this.normativa = "";
            this.uso = "";
            // Campos adicionales
            this.nombrec = "";
            this.contenido = "";
            //Línea de Producto:
            //Línea de Producto: fk
            this.medicamento_controlado = false;
            this.psicotropicos = false;
            this.linea_producto = "";
            //tipo_producto fk listar
            this.tipo_producto = "";
            this.marca = "";
            this.modelo = "";
            this.presentacion = "";
            this.sector = "";
            this.unidad_salida = "";
            this.vencimiento = "";
            this.existencia_max = "";
            this.existencia_min = "";
            //Dimensiones del Producto:
            this.tipo_medida = "";
            this.unidad_medida = "";
            this.numero_unidad = "";
            this.grados_alcohol = "";
            this.estado = "";
            this.numero_serie = "";
            /*VEHICULO*/
            this.vehiculo = false;
            this.placa = "";
            this.pais_origen = "";
            this.ano_fabricacionv = "";
            this.color = "";
            this.carroceria = "";
            this.combustible = "";
            this.motor = "";
            this.cilindraje = "";
            this.chasis = "";
            this.clase = "";
            this.subclase = "";
            this.numero_pasajeros = "";
            //Rubros del Producto:
            this.iva = "";
            this.ice = "";
            this.total_ice = "";
            this.arancel_advalorem = "";
            this.arancel_especifico = "";
            this.arancel_fodinfa = "";
            this.comision = "";
            this.salvaguardia = "";
            this.descuento = "";
            this.costo_unitario = "";
            this.precio1 = "";
            this.precio2 = "";
            this.precio3 = "";
            this.precio4 = "";
            this.precio5 = "";
            this.utilidad_precio1 = "";
            this.utilidad_precio2 = "";
            this.utilidad_precio3 = "";
            this.utilidad_precio4 = "";
            this.utilidad_precio5 = "";
            this.fecha_fabricacion = "";
            this.ultimo_costo = "";
            this.costo_promedio = "";
            this.costo_total = "";
            this.existencia_total = "";
            //listados
            this.contenidolinea = [];
            this.contenidotipo = [];
            this.contenidomarca = [];
            this.contenidomodelo = [];
            this.contenidopresentacion = [];
            this.contenidotipomedida = [];

            this.contenidounidadmedidar = [];
            this.contenidoiva = [];
            this.contenidoice = [];
        },
        //lista linea de producto para select en formulario
        listarlinea() {
            var url = "/api/lineaproductosall/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.contenidolinea = res.data;
            });
        },
        //lista tipo de producto para select en formulario
        listartipo(id) {
            var url =
                "/api/tipoproductosallr/" +
                this.usuario.id_empresa +
                "?id=" +
                id;
            axios.get(url).then(res => {
                this.contenidotipo = res.data;
            });
        },
        //lista marca de producto para select en formulario
        listarmarca() {
            var url = "/api/marcaall/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.contenidomarca = res.data;
            });
        },
        //lista modelo de producto para select en formulario
        listarmodelo() {
            var url = "/api/modeloall/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.contenidomodelo = res.data;
            });
        },
        //lista presentacion de producto para select en formulario
        listarpresentacion() {
            var url = "/api/presentacionall/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.contenidopresentacion = res.data;
            });
        },
        //lista tipo de medida producto para select en formulario
        listartipomedida() {
            let me = this;
            var url = "/api/tipomedida";
            axios
                .get(url)
                .then(function(response) {
                    me.contenidotipomedida = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        //lista unidad de medida para editar
        listarunidadmedidar(id) {
            let me = this;
            var url = "/api/unidadmedidar?id=" + id;
            axios
                .get(url)
                .then(function(response) {
                    me.contenidounidadmedidar = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        //funcion campos adicionales no implementado aun
        /*listarcamposadicionales() {
            if (this.$route.params.id) {
                let me = this;
                var url = "/api/camposadicionales";
                axios
                    .get(url)
                    .then(function(response) {
                        me.contenidocamposadicionales = response.data;
                        if (me.contenidocamposadicionales.length >= 1) {
                            me.ocult = true;
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        },
       octl() {
            if (this.ocult == true) {
                this.ocult = false;
            } else {
                this.ocult = true;
            }
        },*/
        //lista cotenido de iva para select formulario productos
        listariva() {
            let me = this;
            var url = "/api/iva";
            axios
                .get(url)
                .then(function(response) {
                    me.contenidoiva = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        //lista cotenido de ice para select formulario productos
        listarice() {
            let me = this;
            var url = "/api/selectice/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                    me.contenidoice = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        //lista cotenido de cuenta contable
        listarcuenta(buscar1) {
            axios
                .get(
                    "/api/select_plan_cuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscar1
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                });
        },
        abrirlista() {
            $(".menuescoger").show();
        },
        /*
         *Funcion para cargar imagen de un producto para visualizar y editar
         *@obtenerimagen valida que elf ormato de imagen sea permitido
         *@cargarimagen almacena la imagen en una variable para su almacenamiento
         */
        //Archivo de imagen de empresa
        agregarimagen() {
            this.recuperaimagen = 0;
            $(".seleccionarimagen").click();
        },
        seleccionarimagen(event) {
            this.recuperaimagen = 0;
            var allowedExtensions = /(.jpg|.jpeg|.png|.gif|.webp)$/i;
            if (!allowedExtensions.exec($(".seleccionarimagen").val())) {
                this.imagen = "";
                $(".seleccionarimagen").val("");
                this.$vs.notify({
                    time: 8000,
                    color: "danger",
                    title: "Formato inválido ",
                    text: "Solo se acepta archivos imagen"
                });
            } else {
                var tam = parseInt(event.target.files[0].size / 1024);
                if (tam > 1024) {
                    this.imagen = "";
                    $(".seleccionarimagen").val("");
                    this.$vs.notify({
                        time: 8000,
                        color: "danger",
                        title: "Formato inválido",
                        text: "El tamaño de la imagen no puede ser mayor a 1 MB"
                    });
                    return;
                }
                this.imagen = event.target.files[0];
                this.previsualizarimagen(this.imagen);
            }
        },
        eliminarimagen(event) {
            this.recuperaimagen = 0;
            this.imagen = "";
            $(".seleccionarimagen").val("");
        },
        previsualizarimagen(file) {
            this.recuperaimagen = 0;
            let reader = new FileReader();
            reader.onload = e => {
                this.imagenprevisualizar = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        //funcion selecciona y establece contendio de cuenta contable en formulario
        handleSelected(tr) {
            if (tr.id_grupo == 2) {
                this.cta_prod = `${tr.id_plan_cuentas}`;
                this.cta_prod1 = `${tr.nomcta}`;
                this.popupActive = false;
            } else {
                this.$vs.notify({
                    title: "Error",
                    text: "La Cuenta seleccionada no es válida",
                    color: "danger"
                });
            }
        },
        /*
         *Funciones caculo de precios
         *Lista congunto de funciones para calcular automaticamente el precio seugn el costo y utilidad
         */
        //limpia variables check de calcular precio o no
        limpiarprecios() {
            this.precio1 = "";
            this.precio2 = "";
            this.precio3 = "";
            this.precio4 = "";
            this.precio5 = "";
            this.utilidad_precio1 = "";
            this.utilidad_precio2 = "";
            this.utilidad_precio3 = "";
            this.utilidad_precio4 = "";
            this.utilidad_precio5 = "";
        },
        calculartodosprecio() {
            if (this.utilidad_precio1) {
                this.calcularprecio("precio1");
            }
            if (this.utilidad_precio2) {
                this.calcularprecio("precio2");
            }
            if (this.utilidad_precio3) {
                this.calcularprecio("precio3");
            }
            if (this.utilidad_precio4) {
                this.calcularprecio("precio4");
            }
            if (this.utilidad_precio5) {
                this.calcularprecio("precio5");
            }
        },
        //funcion para calcular el valor de cada precio
        calcularprecio(precio) {
            if (precio == "precio1") {
                var total =
                    (parseFloat(this.costo_unitario) *
                        parseFloat(this.utilidad_precio1)) /
                        100 +
                    parseFloat(this.costo_unitario);

                this.precio1 = total.toFixed(2);
            } else if (precio == "precio2") {
                var total =
                    (parseFloat(this.costo_unitario) *
                        parseFloat(this.utilidad_precio2)) /
                        100 +
                    parseFloat(this.costo_unitario);

                this.precio2 = total.toFixed(2);
            } else if (precio == "precio3") {
                var total =
                    (parseFloat(this.costo_unitario) *
                        parseFloat(this.utilidad_precio3)) /
                        100 +
                    parseFloat(this.costo_unitario);

                this.precio3 = total.toFixed(2);
            } else if (precio == "precio4") {
                var total =
                    (parseFloat(this.costo_unitario) *
                        parseFloat(this.utilidad_precio4)) /
                        100 +
                    parseFloat(this.costo_unitario);

                this.precio4 = total.toFixed(2);
            } else if (precio == "precio5") {
                var total =
                    (parseFloat(this.costo_unitario) *
                        parseFloat(this.utilidad_precio5)) /
                        100 +
                    parseFloat(this.costo_unitario);

                this.precio5 = total.toFixed(2);
            }
        },
        /*
         *Funciones agregar formula de produccion
         *Lista congunto de funciones para agregar formula de produccion
         */
        //lista formulas de produccion para añadirla en formulario de catalogo
        listarformula(page, buscar) {
            var url =
                "/api/formula/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoformula = respuesta.recupera;
            });
        },
        //seleciona una formula en lista y establece las variables en el formulario
        selectformula(tr) {
            this.popupformprod = false;
            this.form_prod = `${tr.nombre_form}`;
            this.formu_prod = tr.id_formula_produccion;
        },
        /*crear fomula de produccion
         *Funciones para crear una nueva formula de produccion
         */
        //lista el nuevo codigo autoincrementable para la formula a crear
        listarcodprod() {
            var url = "/api/codfomr/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.cod_pro = res.data.codigo_produccion;
            });
        },
        //lista las formula de produccion
        listarprod() {
            var url = "/api/traerformprod/" + this.idrecupera;
            axios
                .get(url)
                .then(res => {
                    this.continprod = res.data.datos;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        // lista los ingredientes de la formula de produccion
        listaringred() {
            var url = "/api/traerformingred/" + this.idrecupera;
            axios
                .get(url)
                .then(res => {
                    this.contingred = res.data.datos;
                })
                .catch(err => {
                    console.log(err);
                });
        },
        //lista productos para agregar en la formula de produccion
        listarp(pagep, buscarp) {
            var url =
                "/api/productos/" +
                this.usuario.id_empresa +
                "?page=" +
                pagep +
                "&buscar=" +
                buscarp;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.arrayingrediente = respuesta.recupera;
            });
        },
        //abre popup correspondeinte para formula produccion "productos"
        abrirproductos() {
            this.popupprod = true;
            this.tipot = 1;
        },
        //abre popup correspondeinte para formula produccion "ingredientes"
        abriringredientes() {
            this.popupprod = true;
            this.tipot = 2;
        },
        //añade contenido de producto de formula de produccion a crear formula de produccion
        handleSelectedp(tr) {
            this.popupprod = false;
            this.continprod.push({
                id: tr.id_producto,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre
            });
        },
        //añade contenido de ingrediente de formula de produccion a crear formula de produccion
        handleSelectedi(tr) {
            this.popupprod = false;
            this.contingred.push({
                id: tr.id_producto,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                cant_form: null
            });
        },
        //elimina seleccion de producto al crear formula de produccion
        eliminarp(id) {
            this.continprod.splice(id, 1);
        },
        //elimina seleccion de ingrediente al crear formula de produccion
        eliminari(id) {
            this.contingred.splice(id, 1);
        },
        /*
         *Funcion para guardar formula de produccion creada en popup
         *@guardarformula
         */
        guardarformula() {
            if (this.validarformula()) {
                return;
            }
            axios
                .post("/api/agregarformula", {
                    //formula
                    codigo_produccion: this.cod_pro,
                    nombre_form: this.nom_pro,
                    id_empresa: this.usuario.id_empresa,
                    //productos
                    productos: this.continprod,
                    ingredientes: this.contingred
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Fórmula Guardada",
                        text: "Fórmula agregada con éxito",
                        color: "success"
                    });
                    this.form_prod = this.nom_pro;
                    this.formu_prod = res.data;
                    this.popucreaformprod = false;
                    this.popupformprod = false;
                });
        },
        /**
         * Funcion valida campos obligatorios para guardar
         */
        validarformula() {
            this.error1 = 0;

            this.errorcod_pro = [];
            this.errornom_pro = [];
            this.erroringred = [];
            this.errorcant_form = [];

            if (!this.cod_pro) {
                this.errorcod_pro.push("Campo obligatorio");
                this.error1 = 1;
                $(".vs-popup--content").scrollTop(0);
            }
            if (!this.nom_pro) {
                this.errornom_pro.push("Campo obligatorio");
                this.error1 = 1;
                $(".vs-popup--content").scrollTop(0);
            }
            if (!this.contingred.length) {
                this.erroringred.push("Debe añadir almenos un ingrediente");
                this.error1 = 1;
                $(".vs-popup--content").scrollTop(0);
            }

            for (var i = 0; i < this.contingred.length; i++) {
                this.contingred[i].errorcant_form = [];
                if (!this.contingred[i].cant_form) {
                    this.contingred[i].errorcant_form.push("Campo obligatorio");
                    this.error1 = 1;
                    $(".vs-popup--content").scrollTop(0);
                }
            }

            return this.error1;
        },
        //cancela el guardado de formula de produccion en formulario de agregar producto
        cancelarformula() {
            this.popucreaformprod = false;
            this.nom_pro = "";
            this.continprod = [];
            this.contingred = [];
        }
    },
    mounted() {
        this.listar_categorias();
        this.listartipomedida();
        this.listarlinea();
        this.listarmarca();
        this.listarmodelo();
        this.listarpresentacion();
        //this.listarcamposadicionales();
        this.listariva();
        this.listarice();
        this.listar_empresa();
        this.listar();
        this.listarcuenta(this.buscar1);
        //metodos formula produccion
        this.listarformula(1, this.buscar);
        this.listarcodprod();
        this.listarp(1, this.buscarp);
    }
};
</script>

<style>
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
.btn-upload-all {
    display: none;
}
.btn-upload-file {
    display: none;
}
.image-container[data-v-10e59822] {
    width: 100%;
    height: 270px;
    border: 1px dashed #d6d6d6;
    border-radius: 4px;
    background-color: #fff;
}

.centered[data-v-10e59822] {
    width: 100%;
    height: 100%;
}

.show-img[data-v-10e59822] {
    max-width: 100%;
    max-height: 185px;
    width: 100%;
}

.image-overlay[data-v-10e59822] {
    height: 170px;
}

.image-overlay-details[data-v-10e59822] {
    top: 62%;
}

.image-list-container .image-list-item .show-img[data-v-10e59822] {
    max-width: 30px;
    max-height: 33px;
    height: 30px;
}

.imagenpre {
    height: 100%;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.centimg {
    height: 284px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.verimagen {
    overflow: hidden;
    padding: 0px;
    height: 284px;
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    border-radius: 20px;
    background: rgba(255, 255, 255, 0.8) !important;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
    border: 5px solid rgba(0, 0, 0, 0.3);
}

.imagenpre:hover {
    -moz-transform: scale(1.03);
    -webkit-transform: scale(1.03);
    -o-transform: scale(1.03);
    -ms-transform: scale(1.03);
    transform: scale(1.03);
}

.centimg:hover {
    background: rgba(255, 255, 255, 0.6) !important;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}

.centimg img {
    max-width: 100%;
    max-height: 100px;
    cursor: pointer;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.relativo {
    position: relative;
}
.iconelim {
    float: none;
    position: absolute;
    right: 16px;
    padding: 1px !important;
    margin-top: -4px;
    width: 23px !important;
    height: 23px !important;
    cursor: pointer;
    z-index: 9;
}
.vs-popup {
    width: 900px !important;
}
.peque .vs-popup {
    width: 700px !important;
}
.estiloborrar {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.estiloborrar:hover {
    cursor: pointer;
    position: absolute;
    bottom: -21px;
    width: 100%;
    box-shadow: 0px -20px 20px 20px red;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.verimagen:hover .spanborrar {
    margin-bottom: 0px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
.spanborrar {
    margin-bottom: -999px;
    position: absolute;
    bottom: 29px;
    color: red;
    font-size: 44px;
    font-weight: bold;
    margin-left: -10px;
    -webkit-transition: all 0.3s ease;
    -moz-transition: all 0.3s ease;
    -ms-transition: all 0.3s ease;
    -o-transition: all 0.3s ease;
    transition: all 0.3s ease;
}
</style>
