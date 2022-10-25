<template>
    <vx-card>
        <!-- Cabecera asientos contables manuales -->
        <div>
            <h4 style="color: #636363;">Cabecera</h4>
            
            <vs-divider></vs-divider>
            <span>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Número:"
                            :disabled="true"
                            v-model="codigoComprobante"
                            @keypress="solonumeros($event)"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errornumero"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <!-- PROYECTO -->
                    <div class="vx-col sm:w-5/12 w-full mb-6" hidden>
                        <label class="vs-input--label">Proyecto:</label>
                        <vx-input-group>
                            <vs-input
                                class="w-full"
                                :disabled="true"
                                v-model="cabeceraAsientoContable.proyecto"
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="abrirlinea()"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorproyecto"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-3/12 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione"
                            class="selectExample w-full"
                            label="Comprobante:"
                            vs-multiple
                            autocomplete
                            v-model="cabeceraAsientoContable.comprobante"
                            @change="seleccionDeComprobante"
                            :disabled="cabeceraAsientoContable.comprobante>3"
                        >
                            <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.text"
                                v-for="(item, index) in comprobante_array"
                            />
                        </vs-select>
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorcomprobante"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <label class="vs-input--label">Fecha:</label>

                        <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="cabeceraAsientoContable.fecha"
                            placeholder="Elegir Fecha"
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorfecha"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    {{cambio_fecha_pago}}
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="tipo_identificacion">
                        <vs-input
                            class="selectExample w-full"
                            label="Razón Social:"
                            vs-multiple
                            autocomplete
                            v-model="cabeceraAsientoContable.razon_social"
                            
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorrazon_social"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6" v-else>
                        <vs-input
                            class="selectExample w-full"
                            label="Razón Social:"
                            vs-multiple
                            autocomplete
                            v-model="cabeceraAsientoContable.razon_social"
                            
                        />
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorrazon_social"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="tipo_identificacion">
                    <vs-select
                                    
                                    class="selectExample w-full"    
                                    label="Tipo Identificacion"
                                    vs-multiple
                                    autocomplete
                                    v-model="tipo_identificacion"
                                    >
                                    <vs-select-item
                                        value="Cedula"
                                        text="Cedula"
                                    />
                                    <vs-select-item
                                        value="Ruc"
                                        text="Ruc"
                                    />
                                    <vs-select-item
                                        value="Pasaporte"
                                        text="Pasaporte"
                                    />
                                    </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6" v-else>
                    <vs-select
                                    
                                    class="selectExample w-full"    
                                    label="Tipo Identificacion"
                                    vs-multiple
                                    autocomplete
                                    v-model="tipo_identificacion"
                                    >
                                    <vs-select-item
                                        value="Cedula"
                                        text="Cedula"
                                    />
                                    <vs-select-item
                                        value="Ruc"
                                        text="Ruc"
                                    />
                                    </vs-select>
                                    <div v-show="error">
                                        <span class="text-danger" v-for="err in errortipo_identificacion" :key="err" v-text="err" ></span>
                                    </div>

                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-if="!tipo_identificacion" hidden>
                        <vs-input class="selectExample w-full" label="Identificacion:" vs-multiple autocomplete v-if="tipo_identificacion=='Ruc'" @keypress="solonumeros($event)" maxlength="13" v-model="cabeceraAsientoContable.ruc_ci" @keyup="validarruc"/>
                        <vs-input class="selectExample w-full" label="Identificacion:" vs-multiple autocomplete v-else-if="tipo_identificacion=='Cedula'" @keypress="solonumeros($event)" maxlength="10" v-model="cabeceraAsientoContable.ruc_ci" @keyup="validarcedula"/>
                        <vs-input class="selectExample w-full" label="Identificacion:" vs-multiple autocomplete v-else  maxlength="19" v-model="cabeceraAsientoContable.ruc_ci" />
                        <div v-show="errorruc" v-if="tipo_identificacion=='Ruc'">
                            <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
                        </div>
                        <div v-show="errorcedula" v-else>
                            <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err" ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                        <vs-input class="selectExample w-full" label="Identificacion:" vs-multiple autocomplete v-if="tipo_identificacion=='Ruc'" @keypress="solonumeros($event)" maxlength="13" v-model="cabeceraAsientoContable.ruc_ci" @keyup="validarruc"/>
                        <vs-input class="selectExample w-full" label="Identificacion:" vs-multiple autocomplete v-else-if="tipo_identificacion=='Cedula'" @keypress="solonumeros($event)" maxlength="10" v-model="cabeceraAsientoContable.ruc_ci" @keyup="validarcedula"/>
                        <vs-input class="selectExample w-full" label="Identificacion:" vs-multiple autocomplete v-else  maxlength="19" v-model="cabeceraAsientoContable.ruc_ci" />
                        <div v-show="errorruc" v-if="tipo_identificacion=='Ruc'">
                            <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err"></span>
                        </div>
                        <div v-show="errorcedula" v-else>
                            <span class="text-danger" v-for="err in erroridentificacion" :key="err" v-text="err" ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <vs-input
                            class="selectExample w-full"
                            label="Concepto:"
                            vs-multiple
                            autocomplete
                            v-model="cabeceraAsientoContable.concepto"
                            
                        />
                        <!--@keypress="sololetras($event)"-->
                        <div v-show="error">
                            <span
                                class="text-danger"
                                v-for="err in errorconcepto"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                </div>
            </span>
        </div>

        <!-- Boton agregar detalle -->
        <h4 style="color: #636363; display:flex; align-items: center;">
            <div
                style="display: flex;align-items: center; margin-right: 9px;"
                v-if="listaAsientoscontables.length < 1"
            >
                <vs-button
                    v-if="usuario.id_rol == 1"
                    color="primary"
                    style="padding: 8px 20px;"
                    type="border"
                    @click="agregarcampo()"
                    icon-pack="feather"
                    icon="icon-plus"
                ></vs-button>
            </div>
            <span>Agregar detalle</span>
        </h4>
        <vs-divider></vs-divider>
        <!-- Cabecera asiento contable -->
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Proyecto</label>
                    </div>
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <label class="vs-input--label">Cuenta Contable</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label">Debe</label>
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <label class="vs-input--label">Haber</label>
                    </div>
                    
                </div>
            </div>
        </div>
        <!-- asiento contable -->
        <div
            id="one-row"
            class="vx-row"
            v-for="(add, index) in listaAsientoscontables"
            v-bind:key="index"
        >
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <!--Proyecto-->
                    <div class="vx-col sm:w-1/5 w-full mb-2">
                        <vs-select
                            
                            placeholder="--Seleccione--"
                            autocomplete
                            class="selectExample w-full"
                            v-model="listaAsientoscontables[index].detalle.id_proyecto"
                        >
                        <!--canton = '';
                                parroquia = '';-->
                            <vs-select-item
                                v-for="data in contenidoproyecto"
                                :key="data.id_proyecto"
                                :value="data.id_proyecto"
                                :text="data.descripcion"
                            />
                        </vs-select>
                    </div>
                    <!--CUENTA CONTABLE-->
                    <div class="vx-col sm:w-1/3 w-full mb-6">
                        <vx-input-group>
                            <!-- prettier-ignore -->
                            <vs-input
                                class="w-full"
                                v-model="listaAsientoscontables[index].detalle.cuentaContable"
                                :disabled="true"
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="abrirPopupCuentaContable(index)"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                    </div>
                    <!--DEBE-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full valores"
                            v-model="listaAsientoscontables[index].detalle.debe"
                            maxlength="15"
                            :disabled="listaAsientoscontables[index].detalle.haber.length > 0"
                            @keypress="solonumeros($event)"
                            @keyup="cuadreAsientoContable(index)"
                            @blur="cambiarADecimal(index,'d')"
                        />
                    </div>
                    <!--HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            class="w-full valores"
                            v-model="listaAsientoscontables[index].detalle.haber"
                            maxlength="15"
                            :disabled="listaAsientoscontables[index].detalle.debe.length > 0"
                            @keypress="solonumeros($event)"
                            @keyup="cuadreAsientoContable(index)"
                            @blur="cambiarADecimal(index,'h')"
                        />
                    </div>
                    
                    <!--FECHA BANCO-->
                    <!-- prettier-ignore -->
                    <div 
                        class="vx-col sm:w-1/12 w-full mb-6" 
                        v-if="listaAsientoscontables[index].detalle.fecha"
                    >
                        <!--<flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="listaAsientoscontables[index].detalle.fecha"
                            placeholder="Elegir Fecha de pago"
                        />-->
                        <vs-button
                            type="filled"
                            style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                            color="success"
                            @click="agregarcampoConciliacion(index)"
                            >C</vs-button
                        >
                         
                    </div>
                    <!--<div class="vx-col sm:w-2/12 w-full mb-6" 
                        v-if="listaAsientoscontables[index].detalle.fecha">
                        <vs-select
                                    
                                    class="selectExample w-full"    
                                    l
                                    vs-multiple
                                    autocomplete
                                    v-model="listaAsientoscontables[index].detalle.id_forma_pago"
                                    @change="listaAsientoscontables[index].detalle.id_pago_sri===listaAsientoscontables[index].detalle.id_forma_pago"
                                    >
                                    <vs-select-item
                                        v-for="data in formas_pagos"
                                        :key="data.id_forma_pagos"
                                        :value="data.id_forma_pagos"
                                        :text="data.descripcion"
                                    />
                                    </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/12 w-full mb-6" 
                        v-if="listaAsientoscontables[index].detalle.fecha && listaAsientoscontables[index].detalle.id_forma_pago">
                        <vs-input
                                
                                class="w-full"
                                v-model="listaAsientoscontables[index].detalle.id_pago_sri"
                                maxlength="30"
                                @keypress="solonumeros($event)"
                            />
                    </div>-->
                    
                    <vs-popup title="Conciliacion" :active.sync="modal_conciliacion">
                      <div
                            class="vx-row"

                        >
                            <div class="vx-col sm:w-1/4 w-full mb-6" >
                            <label class="vs-input--label">Fecha Pago</label>
                                <flat-pickr
                                            label="Fecha Pago"
                                            class="w-full"
                                            :config="configdateTimePicker"
                                            v-model="listaAsientoscontables[indextipoarreglo].detalle.fecha"
                                            placeholder="Elegir Fecha de pago"
                                        />
                            </div>
                            <div class="vx-col sm:w-1/3 w-full mb-6">
                                <vs-select
                                    
                                    class="selectExample w-full"    
                                    label="Forma Pago"
                                    vs-multiple
                                    autocomplete
                                    v-model="listaAsientoscontables[indextipoarreglo].detalle.id_forma_pago"
                                    >
                                    <vs-select-item
                                        v-for="data in formas_pagos"
                                        :key="data.id_forma_pagos"
                                        :text="data.descripcion"
                                        :value="data.id_forma_pagos"
                                    />
                                    
                                    </vs-select>
                                    
                            </div>
                            
                            <div class="vx-col sm:w-1/4 w-full mb-6" v-if="listaAsientoscontables[indextipoarreglo].detalle.id_forma_pago && listaAsientoscontables[indextipoarreglo].detalle.id_forma_pago.descripcionfps!=='TARJETA PREPAGO' && listaAsientoscontables[indextipoarreglo].detalle.id_forma_pago.descripcionfps!=='COMPENSACIÓN DE DEUDAS' && listaAsientoscontables[indextipoarreglo].detalle.id_forma_pago.descripcionfps!=='ENDOSO DE TÍTULOS' && listaAsientoscontables[indextipoarreglo].detalle.id_forma_pago.descripcionfps!=='SIN UTILIZACION DEL SISTEMA FINANCIERO' ">
                                <vs-input
                                label="No Documento"
                                class="w-full"
                                v-model="listaAsientoscontables[indextipoarreglo].detalle.no_documento"
                                maxlength="30"
                                @keypress="solonumeros($event)"
                            />
                            </div>
                        </div>
                        
                    </vs-popup> 
                </div>
            </div>
            <div class="vx-col xs:w-1/12 sm:w-1/12 flex">
                <!-- prettier-ignore -->
                <vs-button
                    type="filled"
                    v-if="usuario.id_rol == 1 && listaAsientoscontables.length > 1"
                    color="danger"
                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 0px;"
                    @click="quitarcampo(index)"
                    >x</vs-button
                >
                <!-- prettier-ignore -->
                <vs-button
                    type="filled"
                    style="height: 1.5em;padding: 0px;width: 1.5em; margin:5px 5px;"
                    v-if="usuario.id_rol == 1 && (listaAsientoscontables.length === index + 1)"
                    color="primary"
                    @click="agregarcampo()"
                    >+</vs-button
                >
            </div>
        </div>
        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/5 w-full mb-6"></div>
                    <div class="vx-col sm:w-1/3 w-full mb-6"></div>
                    <!--TOTAL DEBE-->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            id="total-debe"
                            label="Total"
                            :disabled="true"
                            class="w-full valores"
                            v-model="cuadreAsientosContables.total.debe"
                        />
                    </div>
                    <!--TOTAL HABER-->
                    <!-- prettier-ignore -->
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <vs-input
                            id="total-haber"
                            class="w-full valores"
                            label="Total"
                            :disabled="true"
                            v-model="cuadreAsientosContables.total.haber"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="vx-row">
            <div class="vx-col xs:w-10/12 sm:w-11/12">
                <div class="vx-row">
                    <div class="vx-col sm:w-1/5 w-full mb-6"></div>
                    <div class="vx-col sm:w-1/3 w-full mb-6"></div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                    
                        <!-- prettier-ignore -->
                        <vs-input
                            id="diferencia"
                            class="w-full valores"
                            label="Diferencia"
                            v-if="
                                cuadreAsientosContables.diferencia > 0 &&
                                parseFloat(cuadreAsientosContables.total.debe) <
                                parseFloat(cuadreAsientosContables.total.haber)"
                            :disabled="true"
                            v-model="cuadreAsientosContables.diferencia"
                        />
                    </div>
                    <div class="vx-col sm:w-2/12 w-full mb-6">
                        <!-- prettier-ignore -->
                        <vs-input
                            id="diferencia"
                            class="w-full valores"
                            label="Diferencia"
                            v-if="
                                cuadreAsientosContables.diferencia > 0 &&
                                parseFloat(cuadreAsientosContables.total.haber) <
                                parseFloat(cuadreAsientosContables.total.debe)"
                            :disabled="true"
                            v-model="cuadreAsientosContables.diferencia"
                        />
                    </div>
                </div>
            </div>
        </div>
            
        <!-- Seccion botones guardar - borrar -->
        <div class="flex w-full">
            <vs-button
                style="margin: 0px 1em;"
                class="btnx"
                color="danger"
                type="filled"
                to="/contabilidad/asientos-contables"
                >CANCELAR</vs-button
            >
            <vs-button
                color="success"
                type="filled"
                :disabled="disabled_asiento"
                @click="guardarAsientoContable()"
                v-if="listaAsientoscontables.length > 0"
                >GUARDAR</vs-button
            >
        </div>
        <!-- Popup cuenta contable de cuenta-->
        <vs-popup
            title="Seleccione una Cuenta Contable"
            :active.sync="popupActive"
        >
            <div class="con-exemple-prompt">
                <vs-input
                    class="mb-4 md:mb-0 mr-4 w-full"
                    v-model="buscar"
                    @keyup="listarcuenta(1, buscar)"
                    v-bind:placeholder="i18nbuscar"
                />
                <vs-table
                    stripe
                    v-model="cuentaarray3"
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

        <!-- Modales-->
        <vs-popup :title="titulomodal" :active.sync="modal">
            <div class="vx-row">
                <!-- Modal principal proyecto-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 1"
                >
                    <div class="vx-row">
                        <div
                            class="vx-col md:w-full w-full mb-6"
                            id="ag-grid-demo"
                        >
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
                                    <!-- prettier-ignore -->
                                    <vs-input
                                        class="mb-4 md:mb-0 mr-4"
                                        v-model="buscar2"
                                        @keyup="buscarProyecto(buscar2)"
                                        v-bind:placeholder="i18nbuscar"
                                    />
                                </div>
                            </div>
                            <vs-table
                                stripe
                                v-model="cuentaarray4"
                                @selected="handleSelectedi"
                                :data="contenidoproyecto"
                            >
                                <template slot="thead">
                                    <vs-th>id</vs-th>
                                    <vs-th>Código</vs-th>
                                    <vs-th>Descripción</vs-th>
                                    <vs-th>Ubicación</vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :data="tr"
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td v-if="tr.id_proyecto">{{
                                            tr.id_proyecto
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
                                        <vs-td v-if="tr.codigo">{{
                                            tr.codigo
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
                                        <vs-td v-if="tr.descripcion">{{
                                            tr.descripcion
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
                                        <vs-td v-if="tr.ubicacion">{{
                                            tr.ubicacion
                                        }}</vs-td>
                                        <vs-td v-else>-</vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                    </div>
                </div>
            </div>
        </vs-popup>
        
    </vx-card>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import vSelect from "vue-select";

import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import { concat } from "bytebuffer";
import { Script } from "vm";
import $ from "jquery";
const axios = require("axios");

export default {
    data() {
        return {
            fecha: null,
            configdateTimePicker: {
                locale: SpanishLocale
            },
            configdateFromTimePicker:{
                locale: SpanishLocale,
                minDate: new Date(),
                maxDate: null
            },
            traer: {},
            comprobante_array: [{ text: "Seleccione", value: 0 }],
            fecha_cambio_pago:"",
            modofact: 1,
            disabled_asiento:false,
            //ERRORES
            error: 0,
            errorcedula:0,
            errorruc:0,
            erroridentificacion: [],
            errorcomprobante: [],
            errornumero: [],
            errorfecha: [],
            errorrazon_social: [],
            errorruc_ci: [],
            errorconcepto: [],
            errorproyecto: [],
            errorrcodigo: [],
            errordescripcion: [],
            errorubicacion: [],
            errortipo_identificacion:[],
            erroridentificacion2:false,
            erroridentificacion3:false,
            //detalle
            contenidopr: [],
            //
            contingred: [],
            modal: false,

            //variables para traer una columna plan ctas
            cuentaarray3: [],
            cuentaarray4: [],
            //cuenta contable listar
            contenidoproyecto: [],
            popupActive: false,
            modal: false,
            //buscador
            buscar: "",
            cuenta_contable: "",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            // cantidadp11: 10,
            offset: 3,
            offset1: 3,
            offset2: 3,
            offset3: 3,
            //buscador
            buscar: "",
            buscar1: "",
            buscar2: "",
            buscar3: "",
            //buscador
            criterio1: "codcta",
            criterio11: "codcta",
            //otros valores
            gridApi: null,
            contenido1: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //variables modal
            titulomodal: "",
            modal: false,
            tipoaccion: 0,
            tipoaccionmodal: 0,
            titulo: "",
            id: null,
            agregarlinea: false,
            eliminar: false,
            ideliminar: 0,
            tipoeliminar: null,
            contenidoproyecto: [],
            contenidocuenta: [],
            codigo: "",
            descripcion: [],
            ubicacion: "",
            proyecto: "",
            //campos dicionales
            contenidocamposadicionales: [],
            idContable: "",
            idContable1: "",
            nombrec: [],
            contenido2: [],
            listaAsientoscontables: [
                {
                    detalle: {
                        debe: "",
                        haber: "",
                        cuentaContable: "",
                        idCuentaContable: "",
                        fecha_pago:"",
                        id_forma_pago:null,
                        id_pago_sri:"",
                        no_documento:null,
                        id_proyecto:null
                    }
                }
            ],
            listaCuadreAsientosContables: [],
            cuadreAsientosContables: {
                total: {
                    debe: 0,
                    haber: 0
                },
                diferencia: 0,
                mayor: null
            },
            cabeceraAsientoContable: {
                proyecto: "",
                idProyecto: null,
                comprobante: "",
                numero: "",
                fecha: new Date(),
                razon_social: "",
                ruc_ci: "",
                concepto: ""
            },
            modal_conciliacion:false,
            fecha_pago:"",
            formas_pagos:[],
            tipo_identificacion:"",
            indextipoarreglo:0,
            id_proyecto_asiento:""
        };
    },
    components: {
        AgGridVue,
        flatPickr
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
            if (this.usuario == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[9].crear;
            }
            return res;
        },
        editarrol() {
            var res = 0;
            if (this.usuario == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[9].editar;
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            if (this.usuario == 1) {
                res = 1;
            } else {
                res = this.$store.state.Roles[9].eliminar;
            }
            return res;
        },
        cambio_fecha_pago(){
            if(this.cabeceraAsientoContable.fecha){
                this.fecha_cambio_pago=this.cabeceraAsientoContable.fecha;
            }
        },
        codigoComprobante() {
            let codigo = this.comprobante_array.find(
                data => data.value == this.cabeceraAsientoContable.comprobante
            );
            codigo = codigo.value
                ? `${codigo.text[0].toUpperCase()}-${
                      this.cabeceraAsientoContable.numero
                  }`
                : "";
            return codigo;
        }
    },
    methods: {
        obtenerComprobantes() {
            const url = `/api/asientos-contables/manuales/comprobantes/`;
            axios.get(url).then(({ data: listaDeComprobantes }) => {
                for (const comprobantes of listaDeComprobantes) {
                    if(comprobantes.id_asientos_comprobante<4){
                        this.comprobante_array.push({
                            text: comprobantes.tipo.toUpperCase(),
                            value: comprobantes.id_asientos_comprobante
                        });
                    }
                    
                }
            });
        },
        cambiarADecimal(index, cod) {
            if (
                cod === "d" &&
                this.listaAsientoscontables[index].detalle.debe.length > 0
            ) {
                var decimal=this.listaAsientoscontables[index].detalle.debe.indexOf(".");
              
                     this.listaAsientoscontables[index].detalle.debe = parseFloat(
                    this.listaAsientoscontables[index].detalle.debe
                    ).toFixed(2);
                
                
            }
            if (
                cod === "h" &&
                this.listaAsientoscontables[index].detalle.haber.length > 0
            ) {
                var decimal=this.listaAsientoscontables[index].detalle.haber.indexOf(".");
                
                     this.listaAsientoscontables[index].detalle.haber = parseFloat(
                    this.listaAsientoscontables[index].detalle.haber
                    ).toFixed(2);
                
            }
        },
        seleccionDeComprobante() {
            let comprobante = this.comprobante_array.find(
                data => data.value === this.cabeceraAsientoContable.comprobante
            );
            this.obtenerUltimoNumeroDeAsientosContables(
                this.cabeceraAsientoContable.comprobante
            );
        },
        abrirPopupCuentaContable(index) {
            this.idProyectoTemporal = index;
            this.popupActive = true;
        },
        cuadreAsientoContable(index) {
            if (this.listaCuadreAsientosContables[index] != undefined) {
                if (
                    this.listaAsientoscontables[index].detalle.debe.length > 0
                ) {
                    this.listaCuadreAsientosContables[index] = {
                        index: index,
                        debe: parseFloat(
                            this.listaAsientoscontables[index].detalle.debe
                        )
                    };
                }
                if (
                    this.listaAsientoscontables[index].detalle.haber.length > 0
                ) {
                    this.listaCuadreAsientosContables[index] = {
                        index: index,
                        haber: parseFloat(
                            this.listaAsientoscontables[index].detalle.haber
                        )
                    };
                }
            } else {
                if (
                    this.listaAsientoscontables[index].detalle.debe.length > 0
                ) {
                    this.listaCuadreAsientosContables.push({
                        index: index,
                        debe: parseFloat(
                            this.listaAsientoscontables[index].detalle.debe
                        )
                    });
                }
                if (
                    this.listaAsientoscontables[index].detalle.haber.length > 0
                ) {
                    this.listaCuadreAsientosContables.push({
                        index: index,
                        haber: parseFloat(
                            this.listaAsientoscontables[index].detalle.haber
                        )
                    });
                }
            }
            let totalDebe = 0;
            let totalHaber = 0;
            for (const asiento of this.listaCuadreAsientosContables) {
                if (asiento.debe > 0) {
                    totalDebe += asiento.debe;
                }
                if (asiento.haber > 0) {
                    totalHaber += asiento.haber;
                }
            }
            if (totalDebe !== totalHaber) {
                this.cuadreAsientosContables.diferencia =
                    totalDebe > totalHaber
                        ? parseFloat((totalDebe - totalHaber).toFixed(2))
                        : parseFloat((totalHaber - totalDebe).toFixed(2));
            } else {
                this.cuadreAsientosContables.diferencia = 0;
            }
            this.cuadreAsientosContables.mayor =
                this.cuadreAsientosContables.total.debe >
                this.cuadreAsientosContables.total.haber
                    ? true
                    : false;
            console.log("Es mayor >", this.cuadreAsientosContables.mayor);
            this.cuadreAsientosContables.total.debe = totalDebe.toFixed(2);
            this.cuadreAsientosContables.total.haber = totalHaber.toFixed(2);
        },
        guardarAsientoContable() {
            this.disabled_asiento=true;
            console.log("ACM", this.listaAsientoscontables);
            // validacoin de cabeceras.
            if (this.validar()) {
                this.$vs.notify({
                    time: 5000,
                    title: "Cabecera incorrecta",
                    text: "La cabecera tiene campos obligatorios",
                    color: "danger"
                });
                window.scroll(0, 0);
                this.disabled_asiento=false;
                return;
            }
            // validacion de acientos contables
            let cont = 0;
            for (const asiento of this.listaAsientoscontables) {
                if (
                    (asiento.detalle.debe.length > 0 &&
                        asiento.detalle.haber.length > 0) ||
                    (asiento.detalle.debe.length === 0 &&
                        asiento.detalle.haber.length === 0) ||
                    asiento.detalle.idCuentaContable === null ||
                    asiento.detalle.cuentaContable.length < 1 ||
                    asiento.detalle.id_proyecto===null
                    
                ) {
                    this.$vs.notify({
                        time: 5000,
                        title: "Asientos incorrectos",
                        text:
                            "Los asientos que intenta ingresar son incorrectos",
                        color: "danger"
                    });
                    this.disabled_asiento=false;
                    return;
                } else {
                    cont += 1;
                }
            }
            let cont_2 = 0;
            for (const asiento of this.listaAsientoscontables) {
                if(asiento.detalle.typeCount===1 && asiento.detalle.id_forma_pago===null){
                    this.$vs.notify({
                        time: 5000,
                        title: "Asientos incorrectos",
                        text:"Los asientos de cuenta banco deben tener forma de pago",
                        color: "danger"
                    });
                    this.disabled_asiento=false;
                    return;
                }else{
                    if((asiento.detalle.typeCount===1 && this.cabeceraAsientoContable.comprobante=="1") && asiento.detalle.debe.length <= 0){
                        this.$vs.notify({
                            time: 5000,
                            title: "Error cuenta banco",
                            text:"El asiento cuenta banco en Ingresos debe ser reguistrado en el debe",
                            color: "danger"
                        });
                        this.disabled_asiento=false;
                        //console.log("Se reguistro error en el debe cta banco");
                        return;
                    }else{
                        if((asiento.detalle.typeCount===1 && this.cabeceraAsientoContable.comprobante=="2") && asiento.detalle.haber.length <= 0){
                            this.$vs.notify({
                                time: 5000,
                                title: "Error cuenta banco",
                                text:"El asiento cuenta banco en Egresos debe ser reguistrado en el haber",
                                color: "danger"
                            });
                            this.disabled_asiento=false;
                        //console.log("Se reguistro error en el haber cta banco");
                        return;
                        }else{
                            cont_2 += 1;
                            //console.log("no se reguistro error:"+cont_2);
                        }
                        
                    }
                }
                
            }
            if (this.listaAsientoscontables.length < 1) {
                this.$vs.notify({
                    time: 5000,
                    title: "Ningun asiento",
                    text: "No has ingresado ningun asiento contable",
                    color: "danger"
                });
                this.disabled_asiento=false;
                return;
            }
            if (
                this.cuadreAsientosContables.diferencia > 0 ||
                this.cuadreAsientosContables.diferencia.length > 0
            ) {
                this.$vs.notify({
                    time: 5000,
                    title: "Cuadrar asientos",
                    text:
                        "La sumatoria del DEBE debe ser igual a la sumatoria del HABER ",
                    color: "danger"
                });
                this.disabled_asiento=false;
                return;
            }
            this.validacionCheque()
            .then(value => {
                if(value==="bien"){
                    let url = `/api/asientos-contables/manuales/guardar`;
                    axios
                        .post(url, {
                            cabecera: this.cabeceraAsientoContable,
                            id_proyecto_asiento:this.id_proyecto_asiento,
                            asientosContablesManuales: this.listaAsientoscontables,
                            ucrea:this.usuario.id,
                            id_empresa:this.usuario.id_empresa,
                            tipo_identificacion:this.tipo_identificacion
                        })
                        .then(response => {
                            this.vaciar();
                            this.listarproyecto(1, this.buscar2);
                            this.$vs.notify({
                                title: "Asientos Guardados",
                                text: "Los Asientos se han guardado correctamente",
                                color: "success"
                            });
                            this.$router.push("/contabilidad/asientos-contables");
                        })
                        .catch(err => {
                            this.$vs.notify({
                                title: "Error al Guardar",
                                text: "Error al guardar reguistros",
                                color: "danger"
                            });
                            this.disabled_asiento=false;
                        });
                }else{
                    this.$vs.notify({
                                title: "Ya existe No Documento",
                                text: "Error ya existe no documento en un plan cuentas",
                                color: "danger"
                            });
                            this.disabled_asiento=false;

                }
            }).catch(error=>{
                this.$vs.notify({
                                title: "Error",
                                text: "Error al validar",
                                color: "danger"
                            });
                            this.disabled_asiento=false;
            });

            
        },
        validacionCheque(){
            return new Promise((resolve,reject)=>{
                let url = `/api/validarcheque`;
                axios
                .post(url, {
                    
                    cabecera: this.cabeceraAsientoContable,
                    asientosContablesManuales: this.listaAsientoscontables,
                    ucrea:this.usuario.id,
                    tipo_identificacion:this.tipo_identificacion,
                    id_empresa:this.usuario.id_empresa
                    
                })
                .then(response => {
                     resolve(response.data);
                })
                .catch(err => {
                    reject(err);
                });
            });
            
        },
        /**
         * @author Gabriel Costta
         * Recupera el ultimo numero de los asientos contables
         * y lo guarda en this.cabeceraAsientoContable.numero
         */
        obtenerUltimoNumeroDeAsientosContables(comprobante) {
            if (comprobante !== "Seleccione") {
                var url=`/api/asientos-contables/manuales/ultimo-numero/${comprobante}`;
                axios
                    .get(
                        url,
                        {
                            params:{
                                id_empresa:this.usuario.id_empresa
                                }
                        }
                    )
                    .then(({ data: ultimoNumero }) => {
                        this.cabeceraAsientoContable.numero = ultimoNumero;
                    });
            }
        },
        vaciar(){
            this.indextipoarreglo=0;
            this.listaAsientoscontables = [
                        {
                            detalle: {
                                debe: "",
                                haber: "",
                                cuentaContable: "",
                                idCuentaContable: "",
                                fecha_pago:"",
                                id_forma_pago:null,
                                id_pago_sri:"",
                                no_documento:null,
                                id_proyecto:this.cabeceraAsientoContable.idProyecto
                            }
                        }
                    ];
                    this.cabeceraAsientoContable = {
                        proyecto: "",
                        idProyecto: null,
                        comprobante: "",
                        numero: "",
                        fecha: "",
                        razon_social: "",
                        ruc_ci: "",
                        concepto: ""
                    };
                    this.cuadreAsientosContables = {
                        total: {
                            debe: 0,
                            haber: 0
                        },
                        diferencia: 0
                    };
                    this.tipo_identificacion="";
        },
        borrarAsientosContables() {
            this.listaAsientoscontables = [
                {
                    detalle: {
                        debe: "",
                        haber: "",
                        cuentaContable: "",
                        idCuentaContable: ""
                    }
                }
            ];
            this.cabeceraAsientoContable = {
                proyecto: "",
                idProyecto: null,
                comprobante: "",
                numero: "",
                fecha: "",
                razon_social: "",
                ruc_ci: "",
                concepto: ""
            };
        },
        quitarcampo(x) {
            if (this.listaAsientoscontables[x].detalle.debe.length > 0) {
                this.cuadreAsientosContables.total.debe -= parseFloat(
                    this.listaAsientoscontables[x].detalle.debe
                );
            }
            if (this.listaAsientoscontables[x].detalle.haber.length > 0) {
                this.cuadreAsientosContables.total.haber -= parseFloat(
                    this.listaAsientoscontables[x].detalle.haber
                );
            }
            this.listaCuadreAsientosContables.splice(x, 1);
            this.listaAsientoscontables.splice(x, 1);
            let totalDebe = 0;
            let totalHaber = 0;
            for (const asiento of this.listaCuadreAsientosContables) {
                if (asiento.debe > 0) {
                    totalDebe += asiento.debe;
                }
                if (asiento.haber > 0) {
                    totalHaber += asiento.haber;
                }
            }
            if (totalDebe !== totalHaber) {
                this.cuadreAsientosContables.diferencia =
                    totalDebe > totalHaber
                        ? parseFloat((totalDebe - totalHaber).toFixed(2))
                        : parseFloat((totalHaber - totalDebe).toFixed(2));
            } else {
                this.cuadreAsientosContables.diferencia = 0;
            }
            this.cuadreAsientosContables.total.debe = totalDebe.toFixed(2);
            this.cuadreAsientosContables.total.haber = totalHaber.toFixed(2);
        },
        agregarcampo() {
            this.ocult = true;
            this.listaAsientoscontables.push({
                            detalle: {
                                debe: "",
                                haber: "",
                                cuentaContable: "",
                                idCuentaContable: "",
                                fecha_pago:"",
                                id_forma_pago:null,
                                id_pago_sri:"",
                                no_documento:null,
                                id_proyecto:this.cabeceraAsientoContable.idProyecto
                            }
                        
            });
        },
        agregarcampoConciliacion(index){
            this.modal_conciliacion=true;
            this.indextipoarreglo = index;
            if(!this.listaAsientoscontables[this.indextipoarreglo].detalle.id_forma_pago){
                this.listarfomaspagos(this.listaAsientoscontables[index].detalle.idCuentaContable); 
            }
            //
            console.log(this.listaAsientoscontables[this.indextipoarreglo].detalle);
        },

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
        sololetras: function($event) {
            var letra = /^\d*\.?\d*$/;
            if (
                $event.charCode === 0 ||
                !letra.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
        },
        abrirproductos() {
            this.popupActive2 = true;
            this.tipomodal = 2;
            this.listarp(1, this.buscarp, this.cantidadpp);
        },

        listar(page, buscar, cantidadp) {
            let me = this;
            var url =
                "/api/asientoscontables?page=" +
                page +
                "&buscar=" +
                buscar +
                "&cantidadp=" +
                cantidadp;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.contenido = respuesta.recupera.data;
                    me.pagination = respuesta.pagination;
                    if (cantidadp > me.pagination.total) {
                        cantidadp = me.pagination.total;
                    }
                })
                .catch(function(error) {});
        },
        acceptAlert() {
            this.$vs.notify({
                color: "danger",
                title: "Asiento Eliminado  ",
                text: "El asiento selecionado fue eliminado con exito"
            });
            this.listar(1, this.buscar, this.cantidadp);
        },
        listarcuenta(page1, buscar1) {
            var url =
                "/api/notacredito/listar_cuenta_contable" 
                // +
                // this.usuario.id_empresa +
                // "?page=" +
                // page3 +
                // "&buscar=" +
                // buscar3
                ;
            axios.get(url,{
                params:{
                empresa:this.usuario.id_empresa,
                buscar:buscar1
                }
            }).then(({data}) => {
                        var respuesta = data;
                        this.contenidocuenta = respuesta;
                    });
        },
        handleSelected(tr) {
            console.log("tr", tr);
            this.popupActive = false;
            if (tr.codcta[tr.codcta.length - 1] === ".") {
                this.$vs.notify({
                    time: 5000,
                    title: "Cuenta incorrecta",
                    text: "No puede seleccionar esa cuenta contable",
                    color: "danger"
                });
            } else {
                this.listaAsientoscontables[
                    this.idProyectoTemporal
                ].detalle.idCuentaContable = tr.id_plan_cuentas;
                
                this.listaAsientoscontables[
                    this.idProyectoTemporal
                //].detalle.cuentaContable = `${tr.codcta} - ${tr.nomcta}`;
                ].detalle.cuentaContable = `${tr.nomcta}`;
                let esBanco = tr.codcta.split(".");
                esBanco.pop();
                esBanco = esBanco.toString().replace(/,/g, ".");
                if (parseInt(tr.bansel)==1 || parseInt(tr.bansel)==2) {
                    var f = new Date();
                    this.listaAsientoscontables[
                        this.idProyectoTemporal
                    ].detalle.fecha = this.fecha_cambio_pago;
                    this.fecha_pago= this.fecha_cambio_pago;
                    this.listaAsientoscontables[
                        this.idProyectoTemporal
                    ].detalle.typeCount = 1;
                } else {
                    this.listaAsientoscontables[
                        this.idProyectoTemporal
                    ].detalle.fecha = null;
                    this.listaAsientoscontables[
                        this.idProyectoTemporal
                    ].detalle.typeCount = null;
                }
            }
            console.log("<><>", this.listaAsientoscontables);
        },

        validar() {
            var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            this.error = 0;
            this.errorcomprobante = [];
            this.errornumero = [];
            this.errorfecha = [];
            this.errorrazon_social = [];
            this.errorconcepto = [];
            this.errorproyecto = [];

            if (
                !this.cabeceraAsientoContable.comprobante ||
                this.cabeceraAsientoContable.comprobante == "Seleccione"
            ) {
                this.errorcomprobante.push("Campo obligatorio");
                this.error = 1;
            }

            if (!this.cabeceraAsientoContable.numero) {
                this.errornumero.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.cabeceraAsientoContable.fecha) {
                this.errorfecha.push("Campo obligatorio");
                this.error = 1;
            }

            if (!this.cabeceraAsientoContable.razon_social) {
                this.errorrazon_social.push("Campo obligatorio");
                this.error = 1;
            }

            if (!this.cabeceraAsientoContable.concepto) {
                this.errorconcepto.push("Campo obligatorio");
                this.error = 1;
            }

            /*if (!this.cabeceraAsientoContable.proyecto) {
                this.errorproyecto.push("Campo obligatorio");
                this.error = 1;
            }*/

                if(this.tipo_identificacion=="Cedula"){
                    this.validarcedula();
                    if(this.erroridentificacion2==true){
                        this.validarcedula();
                        this.error = 1;
                    }  
                }else{
                    if(this.tipoIdent=="Ruc"){
                        this.validarruc();
                        if(this.erroridentificacion3==true){
                            this.validarruc();
                            this.error = 1;
                        }  
                    }
                }
            

            return this.error;
        },
        abrirlinea() {
            this.tipoaccion = 1;
            this.modal = true;
            this.titulomodal = "Proyectos";
        },
        // seleccionar proyecto --- Gabriel
        handleSelectedi(tr) {
            this.cabeceraAsientoContable.proyecto = tr.descripcion;
            this.cabeceraAsientoContable.idProyecto = tr.id_proyecto;
            this.modal = false;
        },
        buscarProyecto(busqueda) {
            axios
                .post(`/api/asientos-contables/manuales/buscar`, {
                    query: busqueda,
                    id_empresa:this.usuario.id_empresa
                })
                .then(({ data: listaDeAsientosContables }) => {
                    this.contenidoproyecto = listaDeAsientosContables;

                });
        },
        listarproyecto(page2, buscar2) {
            var url =
                "/api/listarproyecto/asientos/" +
                this.usuario.id_empresa +
                "?page=" +
                page2 +
                "&buscar=" +
                buscar2;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoproyecto = respuesta.recupera;
                console.log("Id_proyecto:"+respuesta.recupera[0].id_proyecto);
                this.listaAsientoscontables[0].detalle.id_proyecto=respuesta.recupera[0].id_proyecto;
                this.cabeceraAsientoContable.idProyecto=respuesta.recupera[0].id_proyecto;
                this.id_proyecto_asiento=respuesta.recupera[0].id_proyecto;
            });
        },
        //validacionvalida de ruc
        /*validarruc($event) {
            this.error = 0;
            this.errorruc_ci = [];
            var numero = this.cabeceraAsientoContable.ruc_ci;
            var suma = 0;
            var residuo = 0;
            var pri = false;
            var pub = false;
            var nat = false;
            var numeroProvincias = 22;
            var modulo = 11;

            // Verifico que el campo no contenga letras 
            var ok = 1;

            // Aqui almacenamos los digitos de la cedula en variables. 
            var d1 = numero.substr(0, 1);
            var d2 = numero.substr(1, 1);
            var d3 = numero.substr(2, 1);
            var d4 = numero.substr(3, 1);
            var d5 = numero.substr(4, 1);
            var d6 = numero.substr(5, 1);
            var d7 = numero.substr(6, 1);
            var d8 = numero.substr(7, 1);
            var d9 = numero.substr(8, 1);
            var d10 = numero.substr(9, 1);

            // El tercer digito es: 
            // 9 para sociedades privadas y extranjeros 
            // 6 para sociedades publicas 
            // menor que 6 (0,1,2,3,4,5) para personas naturales 

            if (d3 == 7 || d3 == 8) {
                //
                this.errorruc_ci.push("El tercer dígito ingresado es inválido");
                this.error = 1;
                return false;
            }

            // Solo para personas naturales (modulo 10) 
            if (d3 < 6) {
                nat = true;
                p1 = d1 * 2;
                if (p1 >= 10) p1 -= 9;
                p2 = d2 * 1;
                if (p2 >= 10) p2 -= 9;
                p3 = d3 * 2;
                if (p3 >= 10) p3 -= 9;
                p4 = d4 * 1;
                if (p4 >= 10) p4 -= 9;
                p5 = d5 * 2;
                if (p5 >= 10) p5 -= 9;
                p6 = d6 * 1;
                if (p6 >= 10) p6 -= 9;
                p7 = d7 * 2;
                if (p7 >= 10) p7 -= 9;
                p8 = d8 * 1;
                if (p8 >= 10) p8 -= 9;
                p9 = d9 * 2;
                if (p9 >= 10) p9 -= 9;
                modulo = 10;
            } else if (d3 == 6) {
                // Solo para sociedades publicas (modulo 11) 
                // Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 
                pub = true;
                p1 = d1 * 3;
                p2 = d2 * 2;
                p3 = d3 * 7;
                p4 = d4 * 6;
                p5 = d5 * 5;
                p6 = d6 * 4;
                p7 = d7 * 3;
                p8 = d8 * 2;
                p9 = 0;
            } else if (d3 == 9) {
                // Solo para entidades privadas (modulo 11) 
                var pri = true;
                var p1 = d1 * 4;
                var p2 = d2 * 3;
                var p3 = d3 * 2;
                var p4 = d4 * 7;
                var p5 = d5 * 6;
                var p6 = d6 * 5;
                var p7 = d7 * 4;
                var p8 = d8 * 3;
                var p9 = d9 * 2;
            }

            var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
            var residuo = suma % modulo;

            // Si residuo=0, dig.ver.=0, caso contrario 10 - residuo
            var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

            // ahora comparamos el elemento de la posicion 10 con el dig. ver.
            if (pub == true) {
                if (digitoVerificador != d9) {
                    //
                    this.errorruc_ci.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
                // El ruc de las empresas del sector publico terminan con 0001
                if (numero.substr(9, 4) != "0001") {
                    this.errorruc_ci.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
            } else if (pri == true) {
                if (digitoVerificador != d10) {
                    this.errorruc_ci.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
                if (numero.substr(10, 3) != "001") {
                    this.errorruc_ci.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
            } else if (nat == true) {
                if (digitoVerificador != d10) {
                    //
                    this.errorruc_ci.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
                if (numero.length < 14 && numero.substr(10, 12) != "001") {
                    //
                    this.errorruc_ci.push("Ruc invalido");
                    this.error = 1;
                    return false;
                }
            }
            return true;
        },*/
        validarruc($event) {
            this.errorruc = 0;
            this.erroridentificacion = [];
            this.erroridentificacion3=false;
            var numero = this.cabeceraAsientoContable.ruc_ci;
            var suma = 0;
            var residuo = 0;
            var pri = false;
            var pub = false;
            var nat = false;
            var numeroProvincias = 22;
            var modulo = 11;

            // Verifico que el campo no contenga letras 
            var ok = 1;
            
            //Aqui almacenamos los digitos de la cedula en variables.
            var d1 = numero.substr(0, 1);
            var d2 = numero.substr(1, 1);
            var d3 = numero.substr(2, 1);
            var d4 = numero.substr(3, 1);
            var d5 = numero.substr(4, 1);
            var d6 = numero.substr(5, 1);
            var d7 = numero.substr(6, 1);
            var d8 = numero.substr(7, 1);
            var d9 = numero.substr(8, 1);
            var d10 = numero.substr(9, 1);

            // El tercer digito es: */
            // 9 para sociedades privadas y extranjeros */
            // 6 para sociedades publicas */
            // menor que 6 (0,1,2,3,4,5) para personas naturales */
            
            if (d3 == 7 || d3 == 8) {

                this.erroridentificacion.push(
                    "El tercer dígito ingresado es inválido"
                );
                this.errorruc = 1;
                //return false;
                window.scrollTo(0, 0);

            }

            // Solo para personas naturales (modulo 10) */
            if (d3 < 6) {
                nat = true;
                p1 = d1 * 2;
                if (p1 >= 10) p1 -= 9;
                p2 = d2 * 1;
                if (p2 >= 10) p2 -= 9;
                p3 = d3 * 2;
                if (p3 >= 10) p3 -= 9;
                p4 = d4 * 1;
                if (p4 >= 10) p4 -= 9;
                p5 = d5 * 2;
                if (p5 >= 10) p5 -= 9;
                p6 = d6 * 1;
                if (p6 >= 10) p6 -= 9;
                p7 = d7 * 2;
                if (p7 >= 10) p7 -= 9;
                p8 = d8 * 1;
                if (p8 >= 10) p8 -= 9;
                p9 = d9 * 2;
                if (p9 >= 10) p9 -= 9;
                modulo = 10;
            } else if (d3 == 6) {
                // Solo para sociedades publicas (modulo 11) */
                // Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
                pub = true;
                p1 = d1 * 3;
                p2 = d2 * 2;
                p3 = d3 * 7;
                p4 = d4 * 6;
                p5 = d5 * 5;
                p6 = d6 * 4;
                p7 = d7 * 3;
                p8 = d8 * 2;
                p9 = 0;
            } else if (d3 == 9) {
                // Solo para entidades privadas (modulo 11) */
                var pri = true;
                var p1 = d1 * 4;
                var p2 = d2 * 3;
                var p3 = d3 * 2;
                var p4 = d4 * 7;
                var p5 = d5 * 6;
                var p6 = d6 * 5;
                var p7 = d7 * 4;
                var p8 = d8 * 3;
                var p9 = d9 * 2;
            }

            var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
            var residuo = suma % modulo;

            // Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
            var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;

            // ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
            if(!this.cabeceraAsientoContable.ruc_ci){
                this.erroridentificacion.push("Campo Obligatorio");
                this.erroridentificacion3=true;
                this.errorruc = 1;
                window.scrollTo(0, 0);
            }else{
                if (pub == true) {
                    if (digitoVerificador != d9) {

                        this.erroridentificacion.push("Ruc invalido ");
                        this.erroridentificacion3=true;
                        this.errorruc = 1;
                        //return false;
                        window.scrollTo(0, 0);
                        
                    }
                    // El ruc de las empresas del sector publico terminan con 0001*/
                    if (numero.substr(9, 4) != "0001") {
                        this.erroridentificacion.push("Ruc invalido");
                        this.erroridentificacion3=true;
                        this.errorruc = 1;
                        //return false;
                        window.scrollTo(0, 0);
                        
                    }
            } else if (pri == true) {
                if (digitoVerificador != d10) {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorruc = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    
                }
                if (numero.substr(10, 3) != "001") {
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorruc = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    
                }
            } else if (nat == true) {
                if (digitoVerificador != d10) {
                    //console.log('El número de cédula de la persona natural es incorrecto.');
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorruc = 1;
                    return false;
                    window.scrollTo(0, 0);
                    
                }
                if (numero.length < 14 && numero.substr(10, 12) != "001") {
                    //console.log('El ruc de la persona natural debe terminar con 001');
                    this.erroridentificacion.push("Ruc invalido");
                    this.erroridentificacion3=true;
                    this.errorruc = 1;
                    //return false;
                    window.scrollTo(0, 0);
                    
                }
            }
            }
            return this.errorruc;
        },
        validarcedula($event) {
            this.errorcedula = 0;
            //this.error = 0;
            this.erroridentificacion2=false;
            this.erroridentificacion = [];
            if(!this.cabeceraAsientoContable.ruc_ci){
                this.erroridentificacion.push("Campo Obligatorio");
                this.errorcedula = 1;
                this.erroridentificacion2=true;
                window.scrollTo(0, 0);
            }else{
                if (this.cabeceraAsientoContable.ruc_ci.length < 10) {
                this.erroridentificacion.push("Cedula invalida");
                this.errorcedula = 1;
                this.erroridentificacion2=true;
                window.scrollTo(0, 0);
            }
            }
            
            if (
                typeof this.cabeceraAsientoContable.ruc_ci == "string" &&
                this.cabeceraAsientoContable.ruc_ci.length == 10 &&
                /^\d+$/.test(this.cabeceraAsientoContable.ruc_ci)
            ) {
                var digitos = this.cabeceraAsientoContable.ruc_ci.split("").map(Number);
                var codigo_provincia = digitos[0] * 10 + digitos[1];

                //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {

                if (
                    codigo_provincia >= 1 &&
                    (codigo_provincia <= 24 || codigo_provincia == 30)
                ) {
                    var digito_verificador = digitos.pop();

                    var digito_calculado =
                        digitos.reduce(function(
                            valorPrevio,
                            valorActual,
                            indice
                        ) {
                            return (
                                valorPrevio -
                                ((valorActual * (2 - (indice % 2))) % 9) -
                                (valorActual == 9) * 9
                            );
                        },
                        1000) % 10;
                    //return digito_calculado === digito_verificador;
                    if (digito_calculado === digito_verificador) {
                        this.erroridentificacion = [];
                    } else {
                        this.erroridentificacion.push("Cédula inválida");
                        this.errorcedula = 1;
                        window.scrollTo(0, 0);
                        this.erroridentificacion2=true;
                    }
                } else {
                    this.erroridentificacion.push("Cédula inválida");
                    this.errorcedula = 1;
                    window.scrollTo(0, 0);
                    this.erroridentificacion2=true;
                }
            }
            return this.errorcedula;
        },
        listarfomaspagos(id_plc=null){
            console.log("Plan Cuenta:"+id_plc);
           var url =
                "/api/administrar/forma_pagos/listar/asientos"
            axios.get(url,{
                params:{
                    id_empresa:this.usuario.id_empresa,
                    id_plc:id_plc
                }
            }).then(res => {
                var respuesta = res.data;
                this.formas_pagos = res.data;
                console.log(res.data);
            }); 
        },
    },
    mounted() {
        this.listar(1, this.buscar, this.cantidadp);
        this.listarcuenta(1, this.buscar1);
        //this.listarfomaspagos();
        this.listarproyecto(1, this.buscar2);
        this.obtenerComprobantes();
        if (this.traer.id) {
            var id = this.traer.id;
            this.listarasiento(id);
        }
    }
};
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.vs-popup {
    width: 600px !important;
}
.peque .vs-popup {
    width: 1060px !important;
}
.depa .vs-popup {
    width: 650px !important;
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
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
}
#diferencia.vs-input--input:disabled {
    background-color: rgb(253, 121, 121) !important;
    color: black !important;
    opacity: 0.7;
    cursor: default;
    pointer-events: none;
}
#total-debe.vs-input--input:disabled {
    background-color: white !important;
    color: black !important;
    opacity: 0.7;
    cursor: default;
    pointer-events: none;
}
#total-haber.vs-input--input:disabled {
    background-color: white !important;
    color: black !important;
    opacity: 0.7;
    cursor: default;
    pointer-events: none;
}
</style>
