<template>
    <div class="vx-row">
        {{ datos_traer }}
        <div class="vx-col w-full mb-base">
            <vx-card>
                <h4 v-if="!idrecupera">Agregar Proveedor</h4>
                <h4 v-else>Editar Proveedor</h4>
                <br />
                <div class="vx-row">
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full txt-center"
                            label="Código"
                            v-model="codigo_proveedor"
                            disabled
                            maxlength="5"
                        />
                        <!-- <vs-input
                            class="w-full"
                            v-else
                            label="Código"
                            disabled
                            :value="codigo_proveedor"
                        /> -->
                    </div>
                    {{ validar_identificaion }}
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-select
                            class="selectExample w-full"
                            label="Tipo Identificacion"
                            vs-multiple
                            autocomplete
                            v-model="tipoIdent"
                        >
                            <vs-select-item value="Cedula" text="Cedula" />
                            <vs-select-item value="Ruc" text="Ruc" />
                            <vs-select-item
                                value="Pasaporte"
                                text="Pasaporte"
                            />
                            <vs-select-item
                                value="Extranjero"
                                text="Extranjero"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!tipoIdent">
                            <div
                                v-for="err in errortipoIdent"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <!-- <div class="vx-col sm:w-1/5 w-full mb-6" v-if="!tipoIdent">
                        <vs-input
                            class="w-full"
                            label="C.I"
                            v-model="identificacion"
                            v-if="tipoIdent == 'Cedula'"
                            @keypress="solonumeros($event)"
                            @keyup="validarcedula"
                            maxlength="10"
                        />
                        <vs-input
                            class="w-full"
                            label="Ruc"
                            v-model="identificacion"
                            v-else-if="tipoIdent == 'Ruc'"
                            @keypress="solonumeros($event)"
                            @keyup="validarruc"
                            maxlength="13"
                        />
                        
                        <vs-input
                            class="w-full"
                            label="Identificacion"
                            v-model="identificacion"
                            v-else
                            maxlength="19"
                        />
                        <div v-show="errorcedula" v-if="tipoIdent == 'Cedula'">
                            <span
                                class="text-danger"
                                v-for="err in erroridentificacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                        <div v-show="errorrucprov" v-else>
                            <span
                                class="text-danger"
                                v-for="err in erroridentificacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6" v-else>
                        <vs-input
                            class="w-full"
                            label="C.I"
                            v-model="identificacion"
                            v-if="tipoIdent == 'Cedula'"
                            @keypress="solonumeros($event)"
                            @keyup="validarcedula"
                            maxlength="10"
                        />
                        <vs-input
                            class="w-full"
                            label="Ruc"
                            v-model="identificacion"
                            v-else-if="tipoIdent == 'Ruc'"
                            @keypress="solonumeros($event)"
                            @keyup="validarruc"
                            maxlength="13"
                        />
                        
                        <vs-input
                            class="w-full"
                            label="Identificacion"
                            v-model="identificacion"
                            v-else
                            maxlength="15"
                        />
                        <div v-show="errorcedula" v-if="tipoIdent == 'Cedula'">
                            <span
                                class="text-danger"
                                v-for="err in erroridentificacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                        <div v-show="errorrucprov" v-else>
                            <span
                                class="text-danger"
                                v-for="err in erroridentificacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div> -->
                    <div
                        class="vx-col sm:w-1/5 w-full mb-6"
                        v-if="tipoIdent == 'Cedula'"
                    >
                        <vs-input
                            class="w-full"
                            label="C.I"
                            v-model="identificacion"
                            @keypress="solonumeros($event)"
                            @keyup="validarcedula"
                            maxlength="10"
                        />
                        <div v-show="errorcedula" v-if="tipoIdent == 'Cedula'">
                            <span
                                class="text-danger"
                                v-for="err in erroridentificacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div
                        class="vx-col sm:w-1/5 w-full mb-6"
                        v-else-if="tipoIdent == 'Ruc'"
                    >
                        <div class="vx-row sm:w-full w-full ">
                            <div
                                class="vx-col sm:w-4/5 w-full"
                                style="padding-right: inherit;"
                            >
                                <vs-input
                                    class="w-full"
                                    label="Ruc"
                                    v-model="identificacion"
                                    @keypress="solonumeros($event)"
                                    
                                    maxlength="13"
                                />
                                <!--@keyup="validarruc"-->
                            </div>
                            <div
                                class="vx-col sm:w-1/5 w-full"
                                style="padding: inherit; margin-top: auto;"
                            >
                                <vs-button
                                    color="primary"
                                    type="filled"
                                    icon="search"
                                    @click="seachRuc(identificacion)"
                                ></vs-button>
                            </div>
                            <div
                                v-show="errorrucprov"
                                v-if="tipoIdent == 'Ruc'"
                            >
                                <span
                                    class="text-danger"
                                    v-for="err in erroridentificacion"
                                    :key="err"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6" v-else>
                        <vs-input
                            class="w-full"
                            label="Identificación"
                            v-model="identificacion"
                        />
                        <div v-show="errorrucprov">
                            <span
                                class="text-danger"
                                v-for="err in erroridentificacion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>

                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <vs-input
                            type="email"
                            class="w-full"
                            label="Nombre"
                            v-model="nombre"
                            @input="nombreaddicional(nombre)"
                            maxlength="199"
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
                </div>

                <div class="vx-row">
                    <div class="vx-col sm:w-2/5 w-full mb-6">
                        <vs-input
                            type="email"
                            class="w-full"
                            label="Nombre Adicional"
                            v-model="nombre_adicional"
                            
                            maxlength="249"
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
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            class="selectExample w-full"
                            label="Grupo"
                            vs-multiple
                            autocomplete
                            v-model="grupo"
                            @change="getGrupo()"
                        >
                            <vs-select-item
                                v-for="(data, index) in grupos"
                                :key="index"
                                :value="data.id_grupoprov"
                                :text="data.nombre_grupoprov"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            class=" w-full selectExample"
                            label="Estado"
                            vs-multiple
                            autocomplete
                            v-model="estado"
                        >
                            <vs-select-item value="1" text="Activo" />
                            <vs-select-item value="0" text="Inactivo" />
                        </vs-select>
                    </div>
                    <div
                        class="vx-col sm:w-1/5 w-full mb-6"
                        style="margin-top: 5px; margin-bottom: 0.2rem !important;"
                    >
                        <vs-select
                            class="selectExample w-full"
                            label="Tipo Contribuyente"
                            vs-multiple
                            autocomplete
                            v-model="tipo_contribuyente"
                        >
                            <vs-select-item
                                value="Persona Natural"
                                text="Persona Natural"
                            />
                            <vs-select-item
                                value="Persona Juridica"
                                text="Persona Juridica"
                            />
                        </vs-select>
                    </div>
                    
                </div>

                <div class="vx-row">
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Contacto"
                            v-model="contacto"
                        />
                        <div v-show="error" v-if="!contacto">
                            <span
                                class="text-danger"
                                v-for="err in errorcontacto"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/6 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Beneficiario"
                            v-model="beneficiario"
                        />
                    </div>
                    <div
                        class="vx-col sm:w-1/6 w-full mb-6"
                        style="margin-top: 5px; margin-bottom: 0.2rem !important;"
                    >
                        <label class="vs-input--label"
                            >Contribuyente Especial</label
                        >
                        <vs-checkbox
                            v-model="contribuyente"
                            vs-value="1"
                        ></vs-checkbox>
                    </div>
                    <div class="vx-col sm:w-1/2 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Direccion"
                            v-model="direccion"
                        />
                        <div v-show="error" v-if="!direccion">
                            <span
                                class="text-danger"
                                v-for="err in errordireccion"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <!--<vs-input
                            class="w-full"
                            label="E-mail"
                            v-model="email"
                        />-->
                        <vs-chips
                            color="rgb(145, 32, 159)"
                            label="E-mail"
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
                            >despues de agregar un correo pulse la tecla
                            enter</span
                        >

                        <div v-show="error" v-if="chip_correo.length < 1">
                            <span
                                class="text-danger"
                                v-for="err in erroremail"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Telefono"
                            v-model="telefono"
                            @keypress="solonumeros($event)"
                            maxlength="15"
                        />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Número de Casa"
                            v-model="nrcasa"
                        />
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione Provincia"
                            class="selectExample w-full"
                            label="Provincia"
                            vs-multiple
                            autocomplete
                            v-model="provincia"
                            @change="getCiudades()"
                        >
                            <!--<vs-select-item value=1 text="Pichincha" />-->
                            <vs-select-item
                                v-for="(data, index) in provincias"
                                :key="index"
                                :value="data.id_provincia"
                                :text="data.nombre"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!provincia">
                            <span
                                class="text-danger"
                                v-for="err in errorprovincia"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            placeholder="Seleccione Ciudad"
                            class="selectExample w-full"
                            label="Ciudad"
                            vs-multiple
                            autocomplete
                            v-model="ciudad"
                            @selected="getCiudades()"
                        >
                            <!--<vs-select-item value=1 text="Quito" />-->
                            <vs-select-item
                                v-for="(data, index) in ciudades"
                                :key="index"
                                :value="data.id_ciudad"
                                :text="data.nombre"
                            />
                        </vs-select>
                        <div v-show="error" v-if="!ciudad">
                            <span
                                class="text-danger"
                                v-for="err in errorciudad"
                                :key="err"
                                v-text="err"
                            ></span>
                        </div>
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/4 w-full mb-6 ml-auto">
                        <vs-select
                            placeholder="Seleccione Banco"
                            class="selectExample w-full"
                            label="Banco"
                            vs-multiple
                            autocomplete
                            v-model="banco"
                            @change="getBancos()"
                        >
                            <!--<vs-select-item value=1 text="Pichincha" />-->
                            <vs-select-item
                                v-for="(data, index) in bancos"
                                :key="index"
                                :value="data.id_banco"
                                :text="data.nombre_banco"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6">
                        <vs-select
                            class="selectExample w-full"
                            label="Tipo Cuenta"
                            vs-multiple
                            autocomplete
                            v-model="tipCuenta"
                        >
                            <vs-select-item value="1" text="Corriente" />
                            <vs-select-item value="2" text="Ahorros" />
                            <vs-select-item value="3" text="Virtual" />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-6 mr-auto">
                        <vs-input
                            class="w-full"
                            label="Cuenta Banco"
                            v-model="ctaBanco"
                        />
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/5 w-full mb-6 ml-auto">
                        <vs-input
                            class="w-full"
                            label="Pagos"
                            v-model="pago"
                            @keypress="solodecimales($event)"
                            maxlength="10"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Plazo"
                            v-model="plazo"
                            @keypress="solonumeros($event)"
                            maxlength="8"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Dias pago"
                            v-model="dpagos"
                            @keypress="solonumeros($event)"
                            maxlength="6"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6 mr-auto">
                        <label class="vs-input--label">Cuenta Contable</label>
                        <vx-input-group class>
                            <vs-input
                                class="w-full"
                                v-model="ctacontable"
                                :value="idContable"
                                maxlength="200"
                                disabled
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="activePrompt3 = true"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                        </vx-input-group>
                        <!--<div class="row mb-2">
                            <div
                                class="col-xl-6 col-lg-12 justify-content-end clicker"
                                style="position: relative;"
                            >
                                <div
                                    class="input-group mb-3"
                                    style="width: 100%;"
                                >
                                    <vs-input
                                        label="Cuenta Contable"
                                        type="text"
                                        class="w-full"
                                        placeholder="Buscar Cuenta Contable"
                                        v-model="ctacontable"
                                        @keyup="
                                            listarcuenta(ctacontable),
                                                abrirlista()
                                        "
                                    />
                                </div>
                                <div
                                    class="menuescoger"
                                    style="display:none"
                                    id="menuescoger"
                                    v-if="ctacontable"
                                >
                                    <template v-if="contenidocuenta.length">
                                        <ul
                                            v-for="(tr,
                                            index) in contenidocuenta"
                                            :key="index"
                                            @click="handleSelectedCuenta(tr)"
                                        >
                                            <li>
                                                {{ tr.nomcta }}
                                                <span class="posicion">
                                                    <template v-if="tr.codcta"
                                                        ><span
                                                            >Codigo:
                                                            {{ tr.codcta }}
                                                        </span>
                                                    </template>
                                                </span>
                                            </li>
                                        </ul>
                                    </template>
                                    <template v-else>
                                        <ul
                                            style="padding: 7px;text-align: center;"
                                        >
                                            <li>
                                                Cuenta Contable no existe
                                            </li>
                                        </ul>
                                    </template>
                                </div>
                            </div>
                        </div>-->
                    </div>
                </div>
                <div>
                    <label class="vs-input--label">Comentario</label>
                    <vs-textarea v-model="comentario" height="80" />
                </div>
                <!-- <vs-divider position="left" >Datos Sri</vs-divider>
                <div class="vx-row" >
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-select
                            class="selectExample w-full"
                            label="Tipo Comprobante"
                            vs-multiple
                            autocomplete
                            v-model="tcomprobante"
                            @change="getTipoComprob()"
                        >
                            <vs-select-item
                                v-for="(data, index) in tipcomprob"
                                :key="index"
                                :value="data.id_tipcomprobante"
                                :text="data.descrip_tipcomprob"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Serie"
                            v-model="serie"
                            @keypress="solonumeros($event)"
                            maxlength="8"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <label class="vs-input--label">Fecha Validez</label>
                        <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="fvalidez"
                            placeholder="Elegir Fecha"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Factura Inicial"
                            v-model="rangmin"
                            maxlength="15"
                            @keypress="solonumeros($event)"
                        />
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6">
                        <vs-input
                            class="w-full"
                            label="Factura Final"
                            v-model="ranmax"
                            maxlength="15"
                            @keypress="solonumeros($event)"
                        />
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-1/4 w-full mb-6 ml-auto">
                        <vs-input
                            class="w-full"
                            label="#Autorizacion"
                            v-model="nroAutorizacion"
                            @keypress="solonumeros($event)"
                            maxlength="30"
                        />
                    </div>
                    <div
                        class="vx-col sm:w-1/4 w-full mb-6"
                        style="margin-top: 5px; margin-bottom: 0.2rem !important;"
                    >
                        <label class="vs-input--label">Contribuye Sri</label>
                        <vs-checkbox
                            v-model="contribuyeSri"
                            vs-value="1"
                        ></vs-checkbox>
                    </div>

                    <div class="vx-col sm:w-1/4 w-full mb-6 mr-auto">
                        <ul class="demo-alignment">
                            <li>
                                <vs-radio v-model="tipElectronico" vs-value="0"
                                    >Offline</vs-radio
                                >
                            </li>
                            <li>
                                <vs-radio v-model="tipElectronico" vs-value="1"
                                    >Online</vs-radio
                                >
                            </li>
                        </ul>
                    </div>
                </div> -->
                <vs-divider position="left">Retenciones Aplicables</vs-divider>
                <div class="vx-row">
                    <div class="vx-col sm:w-3/5 w-full mb-6 ml-auto">
                        <vs-select
                            class="selectExample w-full"
                            label="Impuesto Retencion"
                            vs-multiple
                            autocomplete
                            v-model="impstRetencion"
                            @change="getImpFuente()"
                        >
                            <vs-select-item
                                v-for="(data, index) in retfuente"
                                :key="index"
                                :value="index"
                                :text="data.descrip_retencion"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6 mr-auto">
                        <vs-select
                            class="selectExample"
                            label="Codigo Sri Impuesto"
                            vs-multiple
                            autocomplete
                            v-model="codSriImp"
                        >
                            <vs-select-item
                                v-for="(data, index) in impfuente"
                                :key="index"
                                :value="data.cod_imp"
                                :text="data.cod_imp"
                            />
                        </vs-select>
                    </div>
                </div>
                <div class="vx-row">
                    <div class="vx-col sm:w-3/5 w-full mb-6 ml-auto">
                        <vs-select
                            class="selectExample w-full"
                            label="Retencion Iva"
                            vs-multiple
                            autocomplete
                            v-model="retencionIva"
                            @change="getImpIva()"
                        >
                            <vs-select-item
                                v-for="(data, index) in retiva"
                                :key="index"
                                :value="index"
                                :text="data.descrip_retencion"
                            />
                        </vs-select>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-6 mr-auto">
                        <vs-select
                            class="selectExample"
                            label="Codigo Sri Iva"
                            vs-multiple
                            autocomplete
                            v-model="codSriIva"
                        >
                            <vs-select-item
                                v-for="(data, index) in impiva"
                                :key="index"
                                :value="data.cod_imp"
                                :text="data.cod_imp"
                            />
                        </vs-select>
                    </div>
                </div>
                <vs-divider />
                <div class="vx-col sm:w-1/5 w-full mb-6 mr-auto ml-auto">
                    <vs-input
                        class="w-full"
                        label="Cash Manager"
                        v-model="idbanco"
                        @keypress="solonumeros($event)"
                        maxlength="15"
                    />
                </div>
                <vs-divider />
                <div class="vx-row">
                    <div class="vx-col w-full">
                        <vs-button
                            color="success"
                            type="filled"
                            @click="guardar(factura)"
                            v-if="!idrecupera"
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="success"
                            type="filled"
                            @click="editar()"
                            v-else
                            >GUARDAR</vs-button
                        >
                        <vs-button
                            color="warning"
                            type="filled"
                            @click="borrar()"
                            >BORRAR</vs-button
                        >
                        <vs-button
                            color="danger"
                            type="filled"
                            @click="cancelar(factura)"
                            >CANCELAR</vs-button
                        >
                    </div>
                </div>
                <vs-popup
                    title="Plan Cuentas"
                    class="peque"
                    :active.sync="activePrompt3"
                >
                    <div class="con-exemple-prompt">
                        <vs-input
                            class="mb-4 md:mb-0 mr-4 w-full"
                            v-model="buscar"
                            @keyup="listar(1, buscar)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <vs-table
                            stripe
                            v-model="cuentaarray3"
                            @selected="handleSelected3"
                            :data="contenido"
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
            </vx-card>
        </div>
    </div>
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import Datepicker from "vuejs-datepicker";
import moment from "moment";
const $ = require("jquery");
const axios = require("axios");
export default {
    data() {
        return {
            idrecupera: null,
            codigo_proveedor: "",
            grupo: "",
            nombre: "",
            nombre_adicional:"",
            tipoIdent: "",
            identificacion: "",
            tipo: "",
            tipopasaporte: 0,
            tipo_contribuyente: "",
            contribuyente: null,
            contribesp_valor: "0",
            contribuye_valor: "0",
            beneficiario: "",
            //identificacionBenf:"",
            contacto: "",
            email: "",
            direccion: "",
            nrcasa: "",
            provincia: 17,
            ciudad: 178,
            telefono: "",
            estado: "",
            banco: "",
            tipCuenta: "",
            ctaBanco: "",
            idbanco: "",
            //correos
            chip_correo: [],
            //nrctaInterbancaria:"",
            pago: "",
            plazo: "",
            dpagos: "",
            ctacontable: "",
            contenidocuenta: [],
            comentario: "",
            tcomprobante: "",
            serie: "",
            fvalidez: "",
            rangmin: "",
            ranmax: "",
            nroAutorizacion: "",
            contribuyeSri: null,
            tipElectronico: "0",
            impstRetencion: "I.R.F. Por Pagar (8%) Arriendos",
            impstRetencionporcent: "",
            retencionIva: "I.V.A. Retenido por Pagar (70%)",
            codSriImp: "",
            codSriIva: "",
            configdateTimePicker: {
                locale: SpanishLocale
            },
            event_at: new Date(),
            activePrompt3: false,
            idContable: "",
            cuentaarray3: [],
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina: 1,
            cantidadp: 1000,
            offset: 3,
            //buscador
            buscar: "",
            criterio: "codcta",
            //otros valores
            gridApi: null,
            contenido: [],
            //errores
            error: 0,
            errorcedula: 0,
            errorrucprov: 0,
            erroridentificacion: [],
            errorcodigo_proveedor: [],
            errorgrupo: [],
            errornombre: [],
            errortipoIdent: [],
            erroridentificacion2: false,
            erroridentificacion3: false,
            errortipo: [],
            errorcontribuyente: [],
            errorbeneficiario: [],

            errorcontacto: [],
            errordireccion: [],
            erroremail: [],
            errornrcasa: [],
            errorprovincia: [],
            errorciudad: [],
            errortelefono: [],
            errorestado: [],
            errorbanco: [],
            errortipCuenta: [],
            errorctaBanco: [],
            erroridbanco: [],

            errorpago: [],
            errorplazo: [],
            errordpagos: [],
            errorctacontable: [],
            errorcomentario: [],
            errortcomprobante: [],
            errorserie: [],
            errorfvalidez: [],
            errorrangmin: [],
            errorranmax: [],
            errornroAutorizacion: [],
            errorcontribuyeSri: [],
            errortipElectronico: [],
            errorimpstRetencion: [],
            errorretencionIva: [],
            errorcodSriImp: [],
            errorcodSriIva: [],
            //traer
            provincias: [],
            ciudades: [],
            bancos: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //traer grupo-proveedor
            grupos: [],
            //traer impuesto de retencion a la fuente
            impfuente: [],
            //traer impuesto de retencion al iva
            impiva: [],
            //traer tipo comprobante
            tipcomprob: [],
            //traer retencion fuente compra
            retfuente: [],
            ////traer retencion iva compra
            retiva: [],
            codigoen: 0,
            codigoprov: [],
            tipocod: 0,
            retencion_nombre: "",
            retencion_iva: ""
        };
    },
    props: {
        factura: null,
        valores: null
    },
    mounted() {
        this.listar2();
        this.listar(1, this.buscar);
        this.leercodigoprov();
        this.getProvincias();
        this.getCiudades();
        this.getBancos();
        this.getGrupoInicio();
        this.getImpFuente();
        this.getImpIva();
        this.getTipoComprob();
        this.getRetFuente();
        this.getRetIva();
        this.traerdatos();
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        validar_identificaion() {
            if (this.tipoIdent) {
                if (this.tipoIdent == "Cedula") {
                    this.validarcedula();
                    //this.erroridentificacion3 =false;
                    if (this.erroridentificacion2 == true) {
                        this.validarcedula();
                        this.error = 1;
                    }
                } else {
                    //this.erroridentificacion2 =false;
                    if (this.tipoIdent == "Ruc") {
                        // this.validarruc();
                        // if (this.erroridentificacion3 == true) {
                        //     this.validarruc();
                        //     this.error = 1;
                        // }
                    }
                }
            }

            console.log("Error Identificacion");
            console.log(this.erroridentificacion);
        },
        datos_traer() {
            if (this.value !== null) {
                console.log(JSON.stringify(this.value));
            }
        }
    },
    components: {
        flatPickr,
        Datepicker
    },
    methods: {
        leercodigoprov() {
            // if (!this.$route.params.id) {
            //     axios
            //         .get("/api/codigo?id=" + this.usuario.id_empresa)
            //         .then(res => {
            //             this.codigoprov = res.data;
            //             if (this.codigoprov == "vacio") {
            //                 this.tipocod = 1;
            //             } else {
            //                 this.tipocod = 0;
            //                 this.codigo_proveedor = this.codigoprov;
            //             }
            //         });
            // }
            if (!this.$route.params.id) {
                axios
                    .get("/api/verificarproveedor/" + this.usuario.id_empresa)
                    .then(res => {
                        this.codigo_proveedor = res.data;
                    });
            }
        },
        handleSelected3(tr) {
            (this.ctacontable = `${tr.nomcta}`),
                (this.idContable = `${tr.id_plan_cuentas}`),
                (this.activePrompt3 = false);
        },
        listarcuenta(buscar1) {
            axios
                .get(
                    "/api/selcuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscar1
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                    //this.contenidocuentadesc = res.data.recupera;
                    //this.contenidocuentaantcp = res.data.recupera;
                });
        },
        abrirlista() {
            $(".menuescoger").show();
        },
        handleSelectedCuenta(tr) {
            this.idContable = `${tr.id_plan_cuentas}`;
            this.ctacontable = `${tr.codcta}`;
        },
        guardar($factura = null) {
            if (this.validar()) {
                return;
            }
            if (this.codigoen) {
                this.codigo_proveedor = this.codigo_proveedor + "-1";
            }

            // if form have no errors
            if ($factura !== null) {
                console.log("Estas en factura Compra");
                let response = {
                    cod_proveedor: this.codigo_proveedor,
                    grupo: this.grupo,
                    nombre_proveedor: this.nombre,
                    nombre_adicional:this.nombre_adicional,
                    tipo_identificacion: this.tipoIdent,
                    identif_proveedor: this.identificacion,
                    //tipo_proveedor:this.tipo,
                    contribuyente: this.contribuyente,
                    beneficiario: this.beneficiario,
                    //identif_benefic:this.identificacionBenf,
                    contacto: this.contacto,
                    email: this.email,
                    direccion_prov: this.direccion,
                    nrcasa: this.nrcasa,
                    telefono_prov: this.telefono,
                    //estado_prov: this.estado,
                    tipo_cuenta: this.tipCuenta,
                    cta_banco: this.ctaBanco,
                    id: this.idbanco,
                    //nrcta_interbancaria:this.nrctaInterbancaria,
                    pagos: this.pago,
                    plazo: this.plazo,
                    dias_pago: this.dpagos,
                    tip_comprob: this.tcomprobante,
                    serie: this.serie,
                    fvalidez: this.fvalidez,
                    comentario: this.comentario,
                    rangomax: this.ranmax,
                    rangomin: this.rangmin,
                    nrautorizacion: this.nroAutorizacion,
                    contribuye_sri: this.contribuyeSri,
                    tip_electronico: this.tipElectronico,
                    imp_retencion: this.impstRetencion,
                    codsri_imp: this.codSriImp,
                    retencion_iva: this.retencionIva,
                    codsri_iva: this.codSriIva,
                    cta_contable: this.ctacontable,
                    id_contable: this.idContable,
                    id_provincia: this.provincia,
                    id_ciudad: this.ciudad,
                    id_banco: this.banco,
                    id_empresa: this.usuario.id_empresa,
                    tipo_contribuyente: this.tipo_contribuyente,
                    emails: this.chip_correo
                };
                this.$emit("CreateProveedor", response);
                return;
            } else {
                axios
                    .post("/api/agregarproveedor", {
                        cod_proveedor: this.codigo_proveedor,
                        grupo: this.grupo,
                        nombre_proveedor: this.nombre,
                        nombre_adicional:this.nombre_adicional,
                        tipo_identificacion: this.tipoIdent,
                        identif_proveedor: this.identificacion,
                        //tipo_proveedor:this.tipo,
                        contribuyente: this.contribuyente,
                        beneficiario: this.beneficiario,
                        //identif_benefic:this.identificacionBenf,
                        contacto: this.contacto,
                        email: this.email,
                        direccion_prov: this.direccion,
                        nrcasa: this.nrcasa,
                        telefono_prov: this.telefono,
                        //estado_prov: this.estado,
                        tipo_cuenta: this.tipCuenta,
                        cta_banco: this.ctaBanco,
                        id: this.idbanco,
                        //nrcta_interbancaria:this.nrctaInterbancaria,
                        pagos: this.pago,
                        plazo: this.plazo,
                        dias_pago: this.dpagos,
                        tip_comprob: this.tcomprobante,
                        serie: this.serie,
                        fvalidez: this.fvalidez,
                        comentario: this.comentario,
                        rangomax: this.ranmax,
                        rangomin: this.rangmin,
                        nrautorizacion: this.nroAutorizacion,
                        contribuye_sri: this.contribuyeSri,
                        tip_electronico: this.tipElectronico,
                        imp_retencion: this.impstRetencion,
                        codsri_imp: this.codSriImp,
                        retencion_iva: this.retencionIva,
                        codsri_iva: this.codSriIva,
                        cta_contable: this.ctacontable,
                        id_contable: this.idContable,
                        id_provincia: this.provincia,
                        id_ciudad: this.ciudad,
                        id_banco: this.banco,
                        id_empresa: this.usuario.id_empresa,
                        tipo_contribuyente: this.tipo_contribuyente,
                        emails: this.chip_correo,
                        factura_compra: null
                    })

                    .then(res => {
                        if (res.data == "vacio") {
                            this.$vs.notify({
                                title: "Registro Guardado",
                                text: "Registro Guardado exitosamente",
                                color: "success"
                            });
                            this.$router
                                .push("/compras/proveedor")
                                .catch(() => {});
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
                        if (res.data == "bien") {
                            this.$vs.notify({
                                title: "Registro Guardado",
                                text: "Registro Guardado exitosamente",
                                color: "success"
                            });

                            this.$router
                                .push("/compras/proveedor")
                                .catch(() => {});
                        }
                    })
                    .catch(err => {});
            }
        },
        editar() {
            axios
                .put("/api/actualizarproveedor", {
                    id_proveedor: this.idrecupera,
                    cod_proveedor: this.codigo_proveedor,
                    grupo: this.grupo,
                    nombre_proveedor: this.nombre,
                    nombre_adicional:this.nombre_adicional,
                    tipo_identificacion: this.tipoIdent,
                    identif_proveedor: this.identificacion,
                    //tipo_proveedor:this.tipo,
                    contribuyente: this.contribuyente,
                    beneficiario: this.beneficiario,
                    //identif_benefic:this.identificacionBenf,
                    contacto: this.contacto,
                    email: this.email,
                    direccion_prov: this.direccion,
                    nrcasa: this.nrcasa,
                    telefono_prov: this.telefono,
                    estado_prov: this.estado,
                    tipo_cuenta: this.tipCuenta,
                    cta_banco: this.ctaBanco,
                    id: this.idbanco,
                    //nrcta_interbancaria:this.nrctaInterbancaria,
                    pagos: this.pago,
                    plazo: this.plazo,
                    dias_pago: this.dpagos,
                    tip_comprob: this.tcomprobante,
                    serie: this.serie,
                    fvalidez: this.fvalidez,
                    comentario: this.comentario,
                    rangomax: this.ranmax,
                    rangomin: this.rangmin,
                    nrautorizacion: this.nroAutorizacion,
                    contribuye_sri: this.contribuyeSri,
                    tip_electronico: this.tipElectronico,
                    imp_retencion: this.retencion_nombre,
                    codsri_imp: this.codSriImp,
                    retencion_iva: this.retencion_iva,
                    codsri_iva: this.codSriIva,
                    cta_contable: this.ctacontable,
                    id_contable: this.idContable,
                    id_provincia: this.provincia,
                    id_ciudad: this.ciudad,
                    id_banco: this.banco,
                    id_empresa: this.usuario.id_empresa,
                    tipo_contribuyente: this.tipo_contribuyente,
                    emails: this.chip_correo
                })
                .then(res => {
                    if (res.data == "vacio") {
                        this.$vs.notify({
                            title: "Registro Editado",
                            text: "Registro Editado exitosamente",
                            color: "success"
                        });

                        this.$router.push("/compras/proveedor").catch(() => {});
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
                    if (res.data == "bien") {
                        this.$vs.notify({
                            title: "Registro Editado",
                            text: "Registro Editado exitosamente",
                            color: "success"
                        });

                        this.$router.push("/compras/proveedor").catch(() => {});
                    }
                })
                .catch(err => {
                    //console.log(err);
                });
        },
        borrar() {
            //(this.codigo_proveedor = ""),
            (this.grupo = ""),
                (this.nombre = ""),
                (this.tipoIdent = ""),
                (this.identificacion = ""),
                (this.tipo = ""),
                (this.contribuyente = null),
                (this.beneficiario = ""),
                //this.identificacionBenf="",
                (this.contacto = ""),
                (this.contacto = ""),
                (this.direccion = ""),
                (this.nrcasa = ""),
                (this.provincia = ""),
                (this.ciudad = ""),
                (this.telefono = ""),
                (this.estado = ""),
                (this.banco = ""),
                (this.tipCuenta = ""),
                (this.ctaBanco = ""),
                (this.idbanco = ""),
                //this.nrctaInterbancaria="",
                (this.pago = ""),
                (this.plazo = ""),
                (this.dpagos = ""),
                (this.ctacontable = ""),
                (this.idContable = ""),
                (this.comentario = ""),
                (this.tcomprobante = ""),
                (this.serie = ""),
                (this.fvalidez = ""),
                (this.rangmin = ""),
                (this.ranmax = ""),
                (this.nroAutorizacion = ""),
                (this.contribuyeSri = null),
                (this.tipElectronico = "0"),
                (this.impstRetencion = null),
                (this.retencionIva = null),
                //(this.retencionIva = ""),
                //(this.retencion_iva = ""),
                (this.codSriImp = ""),
                (this.codSriIva = "");
        },
        cancelar($factura = null) {
            if ($factura !== null) {
                var Compra = 1;
                this.$emit("CancelarCreate", Compra);
                return "Cancelar factura";
            } else {
                this.$router.push("/compras/proveedor");
            }
        },
        validar() {
            this.error = 0;
            this.errorrucprov = 0;
            this.errorcodigo_proveedor = [];
            this.errorgrupo = [];
            this.errornombre = [];
            this.errortipoIdent = [];
            //this.erroridentificacion2=null;
            this.errortipo = [];
            this.errorcontribuyente = [];
            this.errorbeneficiario = [];
            this.errordireccion = [];
            (this.erroremail = []), (this.errorprovincia = []);
            this.errorciudad = [];
            this.errorcontacto = [];

            if (!this.nombre) {
                this.errornombre.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.tipoIdent) {
                this.errortipoIdent.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            } else {
                if (this.tipoIdent == "Cedula") {
                    this.validarcedula();
                    if (this.erroridentificacion2 == true) {
                        this.validarcedula();
                        this.error = 1;
                    }
                } else {
                    if (this.tipoIdent == "Ruc") {
                        this.erroridentificacion=[];
                        if(!this.identificacion){
                             this.erroridentificacion.push("Campo Obligatorio");
                             this.error = 1;
                             this.errorrucprov = 1;
                             window.scrollTo(0, 0);
                        }else{
                            if(this.identificacion.length!==13){
                                this.erroridentificacion.push("Ruc Invalido");
                                this.error = 1;
                                this.errorrucprov = 1;
                                window.scrollTo(0, 0);
                            }
                        }
                        // this.validarruc();
                        // if (this.erroridentificacion3 == true) {
                        //     this.validarruc();
                        //     this.error = 1;
                        // }
                    }
                }
            }
            if (!this.direccion) {
                this.errordireccion.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.contacto) {
                this.errorcontacto.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (this.chip_correo.length < 1 || !this.chip_correo) {
                this.erroremail.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
                console.log("Error correos:" + this.chip_correo.length);
            }
            if (!this.provincia) {
                this.errorprovincia.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.ciudad) {
                this.errorciudad.push("Campo Obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }

            return this.error;
        },
        //correos
        remove_chip_correo(item) {
            this.chip_correo.splice(this.chip_correo.indexOf(item), 1);
        },
        validarcedula($event) {
            this.errorcedula = 0;
            //this.error = 0;
            this.erroridentificacion2 = false;
            this.erroridentificacion = [];
            if (!this.identificacion) {
                this.erroridentificacion.push("Campo Obligatorio");
                console.log("Campo Obligatorio Cedula 1");
                this.errorcedula = 1;
                this.erroridentificacion2 = true;
                window.scrollTo(0, 0);
            } else {
                if (
                    this.identificacion.length < 10 ||
                    this.identificacion.length > 10
                ) {
                    this.erroridentificacion.push("Cedula invalida");
                    console.log("Campo Obligatorio Cedula 2");
                    this.errorcedula = 1;
                    this.erroridentificacion2 = true;
                    window.scrollTo(0, 0);
                }
            }

            if (
                typeof this.identificacion == "string" &&
                this.identificacion.length == 10 &&
                /^\d+$/.test(this.identificacion)
            ) {
                var digitos = this.identificacion.split("").map(Number);
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
                        console.log("Campo Obligatorio Cedula 3");
                        this.errorcedula = 1;
                        window.scrollTo(0, 0);
                        this.erroridentificacion2 = true;
                    }
                } else {
                    this.erroridentificacion.push("Cédula inválida");
                    console.log("Campo Obligatorio Cedula 4");
                    this.errorcedula = 1;
                    window.scrollTo(0, 0);
                    this.erroridentificacion2 = true;
                }
            }

            return this.errorcedula;
        },
        cambio(c) {
            this.identificacion = "";
            this.tipopasaporte = 0;
            //Validar cédula
            if (c === "Cédula de Identidad") {
            }
            //validar ruc
            if (c === "Ruc") {
            }
            //validar pasaporte
            if (c === "Pasaporte") {
                this.tipopasaporte = 1;
            }
            //validar consumidor final
            if (c === "Consumidor Final") {
                this.identificacion = "9999999999999";
            }
        },
        validarruc($event) {
            this.errorrucprov = 0;
            this.erroridentificacion = [];
            this.erroridentificacion3 = false;
            var numero = this.identificacion;
            var suma = 0;
            var residuo = 0;
            var pri = false;
            var pub = false;
            var nat = false;
            var numeroProvincias = 22;
            var modulo = 11;

            /* Verifico que el campo no contenga letras */
            var ok = 1;
            /*for (var i=0; i<numeroProvincias ;i++){
      alert('El código de la provincia (dos primeros dígitos) es inválido'); return false;
      }*/
            /*
      if (typeof(this.identificacion) == 'string' && this.identificacion.length == 10 && /^\d+$/.test(this.identificacion)) {
      var digitos = numero.split('').map(Number);
          var codigo_provincia = digitos[0] * 10 + digitos[1];
          
          //if (codigo_provincia >= 1 && (codigo_provincia <= 24 || codigo_provincia == 30) && digitos[2] < 6) {
            if(codigo_provincia<24 && codigo_provincia <= 1){
              this.erroridentificacion.push("Ruc inválido");
              this.error=1;
              return ;
            }
      }*/
            /* Aqui almacenamos los digitos de la cedula en variables. */
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

            /* El tercer digito es: */
            /* 9 para sociedades privadas y extranjeros */
            /* 6 para sociedades publicas */
            /* menor que 6 (0,1,2,3,4,5) para personas naturales */

            if (d3 == 7 || d3 == 8) {
                this.erroridentificacion.push(
                    "El tercer dígito ingresado es inválido"
                );
                this.errorrucprov = 1;
                //return false;
                window.scrollTo(0, 0);
            }

            /* Solo para personas naturales (modulo 10) */
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
                /* Solo para sociedades publicas (modulo 11) */
                /* Aqui el digito verficador esta en la posicion 9, en las otras 2 en la pos. 10 */
                if(numero=='0963493226001'){
                    console.log("Entro sociedades publicas 096");
                    pub = true;
                    p1 = d1 * 2;
                    p2 = d2 * 1;
                    p3 = d3 * 2;
                    p4 = d4 * 1;
                    p5 = d5 * 2;
                    p6 = d6 * 1;
                    p7 = d7 * 2;
                    p8 = d8 * 1;
                    p9 = d9 * 2;
                }else{
                    console.log("Entro sociedades publicas");
                    pub = true;
                    p1 = d1 * 3;
                    p2 = d2 * 2;
                    p3 = d3 * 7;
                    p4 = d4 * 6;
                    p5 = d5 * 5;
                    p6 = d6 * 7;
                    p7 = d7 * 3;
                    p8 = d8 * 2;
                    p9 = 0;
                }
                
            } else if (d3 == 9) {
                /* Solo para entidades privadas (modulo 11) */
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
            if(pub==true && numero=='0963493226001'){
                if(p1>9){
                    p1=p1-9;
                }
                if(p2>9){
                    p2=p2-9;
                }
                if(p3>9){
                    p3=p3-9;
                }
                if(p4>9){
                    p4=p4-9;
                }
                if(p5>9){
                    p5=p5-9;
                }
                if(p6>9){
                    p6=p6-9;
                }
                if(p7>9){
                    p7=p7-9;
                }
                if(p8>9){
                    p8=p8-9;
                }
                if(p9>9){
                    p9=p9-9;
                }
                var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
                var residuo = suma % 10;
                var residuo10=(suma-residuo)+10;
                console.log("residuo11_0:"+residuo10);
                console.log("residuo11:"+residuo);
                console.log("suma11:"+suma);
                /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
                var digitoVerificador = residuo == 0 ? 0 : residuo10-suma;
            }else{
                var suma = p1 + p2 + p3 + p4 + p5 + p6 + p7 + p8 + p9;
                var residuo = suma % modulo;
                console.log("modulo10:"+modulo);
                console.log("residuo10:"+residuo);
                /* Si residuo=0, dig.ver.=0, caso contrario 10 - residuo*/
                var digitoVerificador = residuo == 0 ? 0 : modulo - residuo;
            }
            
            

            /* ahora comparamos el elemento de la posicion 10 con el dig. ver.*/
            if (!this.identificacion) {
                this.erroridentificacion.push("Campo Obligatorio");
                this.erroridentificacion3 = true;
                this.errorrucprov = 1;
                window.scrollTo(0, 0);
            } else {
                if (pub == true) {
                    if(numero=='0963493226001'){
                        console.log("digitoVerificador:"+digitoVerificador);
                        console.log("d9:"+d10);
                        console.log(numero.substr(10, 3));
                        if (digitoVerificador != d10) {
                            this.erroridentificacion.push("Ruc invalido");
                            this.erroridentificacion3 = true;
                            this.errorrucprov = 1;
                            //return false;
                            window.scrollTo(0, 0);
                        }
                        /* El ruc de las empresas del sector publico terminan con 0001*/
                        if (numero.substr(10, 3) != "001") {
                            this.erroridentificacion.push("Ruc invalido");
                            this.erroridentificacion3 = true;
                            this.errorrucprov = 1;
                            //return false;
                            window.scrollTo(0, 0);
                        }
                    }else{
                        console.log("digitoVerificador:"+digitoVerificador);
                        console.log("d9:"+d9);
                        console.log(numero.substr(9, 4));
                        if (digitoVerificador != d9) {
                            this.erroridentificacion.push("Ruc invalido");
                            this.erroridentificacion3 = true;
                            this.errorrucprov = 1;
                            //return false;
                            window.scrollTo(0, 0);
                        }
                        /* El ruc de las empresas del sector publico terminan con 0001*/
                        if (numero.substr(9, 4) != "0001") {
                            this.erroridentificacion.push("Ruc invalido");
                            this.erroridentificacion3 = true;
                            this.errorrucprov = 1;
                            //return false;
                            window.scrollTo(0, 0);
                        }
                    }
                    
                } else if (pri == true) {
                    if (digitoVerificador != d10) {
                        this.erroridentificacion.push("Ruc invalido");
                        this.erroridentificacion3 = true;
                        this.errorrucprov = 1;
                        //return false;
                        window.scrollTo(0, 0);
                    }
                    if (numero.substr(10, 3) != "001") {
                        this.erroridentificacion.push("Ruc invalido");
                        this.erroridentificacion3 = true;
                        this.errorrucprov = 1;
                        //return false;
                        window.scrollTo(0, 0);
                    }
                } else if (nat == true) {
                    if (digitoVerificador != d10) {
                        //console.log('El número de cédula de la persona natural es incorrecto.');
                        this.erroridentificacion.push("Ruc invalido");
                        this.erroridentificacion3 = true;
                        this.errorrucprov = 1;
                        return false;
                        window.scrollTo(0, 0);
                    }
                    if (numero.length < 14 && numero.substr(10, 12) != "001") {
                        //console.log('El ruc de la persona natural debe terminar con 001');
                        this.erroridentificacion.push("Ruc invalido");
                        this.erroridentificacion3 = true;
                        this.errorrucprov = 1;
                        //return false;
                        window.scrollTo(0, 0);
                    }
                }
            }
            return this.errorrucprov;
        },
        listar(page, buscar) {
            let me = this;
            var url = "/api/notacredito/listar_cuenta_contable";
            //  +
            // this.usuario.id_empresa +
            // "?page=" +
            // page +
            // "&buscar=" +
            // buscar
            axios
                .get(url, {
                    params: {
                        empresa: this.usuario.id_empresa,
                        buscar: buscar
                    }
                })
                .then(({ data }) => {
                    var respuesta = data;
                    me.contenido = respuesta;
                })
                .catch(function(error) {
                    //console.log(error);
                });
        },
        listar2() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/abrirproveedor";
                axios
                    .put(url, { id: this.idrecupera })
                    .then(({ data }) => {
                        //let data = res.data[0];

                        this.codigo_proveedor = data.recupera[0].cod_proveedor;
                        this.grupo = data.recupera[0].id_grupo_proveedor;
                        this.nombre = data.recupera[0].nombre_proveedor;
                        this.nombre_adicional = data.recupera[0].nombre_adicional;
                        this.tipoIdent = data.recupera[0].tipo_identificacion;

                        //this.tipo=data.recupera[0].tipo_proveedor
                        this.contribuyente = data.recupera[0].contribuyente;
                        this.beneficiario = data.recupera[0].beneficiario;
                        //this.identificacionBenf=data.recupera[0].identif_benefic
                        this.contacto = data.recupera[0].contacto;
                        this.email = data.recupera[0].email;
                        this.direccion = data.recupera[0].direccion_prov;
                        this.nrcasa = data.recupera[0].nrcasa;
                        this.provincia = data.recupera[0].id_provincia;
                        this.ciudad = data.recupera[0].id_ciudad;
                        this.telefono = data.recupera[0].telefono_prov;
                        this.estado = data.recupera[0].estado_prov;
                        this.banco = data.recupera[0].id_banco;
                        this.tipCuenta = data.recupera[0].tipo_cuenta;
                        this.ctaBanco = data.recupera[0].cta_banco;
                        this.idbanco = data.recupera[0].id;
                        // this.nrctaInterbancaria=data.recupera[0].nrcta_interbancaria
                        this.pago = data.recupera[0].pagos;
                        this.plazo = data.recupera[0].plazo;
                        this.dpagos = data.recupera[0].dias_pago;
                        this.ctacontable = data.recupera[0].cuenta_resultado;
                        this.idContable = data.recupera[0].id_plan_cuentas;
                        this.comentario = data.recupera[0].comentario;
                        this.tcomprobante = data.recupera[0].tip_comprob;
                        this.serie = data.recupera[0].serie;
                        this.fvalidez = data.recupera[0].fvalidez;
                        this.rangmin = data.recupera[0].rangomin;
                        this.ranmax = data.recupera[0].rangomax;
                        this.nroAutorizacion = data.recupera[0].nrautorizacion;
                        this.contribuyeSri = data.recupera[0].contribuye_sri;
                        this.tipElectronico = data.recupera[0].tip_electronico;
                        this.impstRetencion = data.recupera[0].imp_retencion;
                        this.retencionIva = data.recupera[0].retencion_iva;
                        this.codSriImp = data.recupera[0].codsri_imp;
                        this.codSriIva = data.recupera[0].codsri_iva;
                        this.retencion_nombre = data.recupera[0].imp_retencion;
                        this.retencion_iva = data.recupera[0].retencion_iva;
                        this.tipo_contribuyente =
                            data.recupera[0].tipo_contribuyente;
                        this.chip_correo = data.emails;
                        this.identificacion =
                            data.recupera[0].identif_proveedor;
                        console.log(this.identificacion + ":ident");
                        console.log("hola:" + data.recupera[0].cod_proveedor);
                    })
                    .catch(err => {
                        //console.log(err);
                    });
            } else {
                this.idrecupera = null;
            }
        },
        nombreaddicional(nom) {
            this.nombre_adicional = nom;
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
        solodecimales: function($event) {
            let keyCode = $event.keyCode ? $event.keyCode : $event.which;
            if ((keyCode < 48 || keyCode > 57) && keyCode !== 46) {
                // 46 is dot
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
        getProvincias: function() {
            axios.get("/api/traerprovinciaprov").then(
                function(response) {
                    this.provincias = response.data;
                    this.provs == this.id_provincia;
                }.bind(this)
            );
        },
        getCiudades: function() {
            axios
                .get("/api/traerciudadprov", {
                    params: {
                        provincia: this.provincia
                    }
                })
                .then(
                    function(response) {
                        this.ciudades = response.data;
                    }.bind(this)
                );
        },
        getBancos: function() {
            axios.get("/api/traerbancoprov").then(
                function(response) {
                    this.bancos = response.data;
                    //this.provs==this.id_provincia
                }.bind(this)
            );
        },
        getGrupo() {
            axios.get("/api/traergruprov/" + this.usuario.id_empresa).then(
                function(response) {
                    this.grupos = response.data;
                    //this.grupo = this.grupos[0].id_grupoprov;
                    //this.provs==this.id_provincia
                }.bind(this)
            );
        },
        getGrupoInicio() {
            axios.get("/api/traergruprov/" + this.usuario.id_empresa).then(
                function(response) {
                    this.grupos = response.data;
                    if(!this.$route.params.id){
                        this.grupo = this.grupos[0].id_grupoprov;
                    }
                    
                    //this.provs==this.id_provincia
                }.bind(this)
            );
        },
        getImpFuente() {
            var r = 0;
            var id_ret;
            if (this.retfuente.length >= 1) {
                if (this.impstRetencion != null) {
                    r = this.retfuente[this.impstRetencion].porcen_retencion;
                    this.retencion_nombre = this.retfuente[
                        this.impstRetencion
                    ].descrip_retencion;
                } else {
                    r = 95;
                }
            }
            var url = "/api/traerimpfuente/" + this.usuario.id_empresa;
            axios
                .get(url, {
                    params: {
                        porcen_impret: r
                    }
                })
                .then(
                    function(response) {
                        if (response.data) {
                            this.impfuente = response.data;
                        } else {
                            this.impfuente = 0;
                        }
                    }.bind(this)
                );
        },
        getImpIva() {
            var por = 0;
            if (this.retiva.length >= 1) {
                if (this.retencionIva != null) {
                    por = this.retiva[this.retencionIva].porcen_retencion;
                    this.retencion_iva = this.retiva[
                        this.retencionIva
                    ].descrip_retencion;
                } else {
                    por = 95;
                }
            }
            var url = "/api/traerimpiva/" + this.usuario.id_empresa;
            axios
                .get(url, {
                    params: {
                        porcen_imp: por
                    }
                })
                .then(
                    function(response) {
                        if (response.data) {
                            this.impiva = response.data;
                        } else {
                            this.impiva = 0;
                        }
                    }.bind(this)
                );
        },
        getTipoComprob() {
            var url = "/api/traertipcomprob/" + this.usuario.id_empresa;
            axios.get(url).then(
                function(response) {
                    this.tipcomprob = response.data;
                    //this.provs==this.id_provincia
                }.bind(this)
            );
        },
        getRetFuente() {
            var url = "/api/traerretfuente/" + this.usuario.id_empresa;
            axios.get(url).then(
                function(response) {
                    this.retfuente = response.data;
                    //this.provs==this.id_provincia
                }.bind(this)
            );
        },
        getRetIva() {
            var url = "/api/traerretiva/" + this.usuario.id_empresa;
            axios.get(url).then(
                function(response) {
                    this.retiva = response.data;
                    //this.provs==this.id_provincia
                }.bind(this)
            );
        },
        seachRuc(ruc) {
            axios
                .get(`/auth_ruc_sri/${ruc}`)
                .then(res => {
                    if (res.data == "Ruc no existe") {
                        this.$vs.notify({
                            text: "No existe Este RUC",
                            color: "danger"
                        });
                        return;
                    } else {
                        // this.contenidocuenta = res.data.recupera;
                        this.nombre = res.data.razon_social;
                        this.nombre_adicional = res.data.nombre_comercial;
                        if (
                            res.data.estado_contribuyente_ruc_activo == "Activo"
                        ) {
                            this.estado = "1";
                        } else {
                            this.estado = "0";
                        }
                        if (res.data.tipo_contribuyente == "Persona Natural") {
                            this.tipo_contribuyente = "Persona Natural";
                        } else {
                            this.tipo_contribuyente = "Persona Jurídica";
                        }
                        if (res.data.obligado_contabilidad == "SI") {
                            //this.radios2 = true;
                        } else if (res.data.obligado_contabilidad == "NO") {
                            //this.radios = false;
                        }
                        //this.regimen_contribuyente = res.data.categoria_my_pymes;
                        this.comentario = res.data.actividad_economica;
                        var dir = res.data.ubicacion_comercial.split("/ ");
                        console.log(dir);
                        if (dir.length >= 2) {
                            this.direccion = dir[2].trim();
                            this.buscarProviCiudad(dir);
                        }
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        traerdatos() {
            if (typeof this.valores!=="undefined") {
                if(this.valores!==null){
                    console.log("Entro"+this.valores);
                    this.tipoIdent = "Ruc";
                    this.identificacion = this.valores;
                    this.seachRuc(this.identificacion);
                }
            }
        },
        // traerdatos(){
        //     if(typeof this.valores!=="undefined"){
        //         console.log("entro XML DATA");
        //         console.log(JSON.stringify(this.valores));
        //         console.log(this.valores[0].id);
        //         switch(this.valores[0].tipo_identificacion){
        //             case "04":
        //                 this.tipoIdent="Ruc";
        //                 break;
        //             case "05":
        //                 this.tipoIdent="Cedula";
        //                 break;
        //             case "06":
        //                 this.tipoIdent="Pasaporte";
        //                 break;
        //         }
        //         this.identificacion=this.valores[0].identificacion;
        //         this.nombre=this.valores[0].nombre;
        //         this.direccion=this.valores[0].direccion;
        //         if(typeof this.valores[0].email !=="undefined"){
        //             this.chip_correo=this.valores[0].email.split(";");
        //         }

        //     }
        // },
        buscarProviCiudad(dir) {
            axios
                .get("/api/buscarprovciudad", {
                    params: {
                        valor_prov: dir[0],
                        valor_ciud: dir[1]
                    }
                })
                .then(({ data }) => {
                    if (
                        data !== "no exite provincia" ||
                        data !== "no exite ciudad"
                    ) {
                        this.provincia = data.id_provincia;
                        this.ciudad = data.id_ciudad;
                    } else {
                        if (data == "no exite provincia") {
                            this.$vs.notify({
                                text: "No existe Esa Provincia",
                                color: "danger"
                            });
                        } else {
                            this.$vs.notify({
                                text: "No existe Esa Ciudad",
                                color: "danger"
                            });
                        }
                    }
                })
                .catch(err => {});
        }
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
/*
.vs-dialog {
  max-width: 1024px !important;
}*/
.menuescoger {
    position: absolute;
    margin-top: -11px;
    width: 100%;
    background: #fff;
    z-index: 999;
    border: 1px solid #dfdfdf;
    border-radius: 0 0 8px 8px;
}

.menuescoger ul {
    list-style: none;
    padding: 8px 15px 25px 15px;
    margin: 0;
    cursor: pointer;
    color: #848484;
    font-weight: 600;
    font: 14px arial, sans-serif;
    position: relative;
    border-bottom: 1px solid #eaeaea;
}

.menuescoger ul:hover {
    background: rgba(0, 0, 0, 0.1);
}

.menuescoger span {
    font-size: 12px;
}
.posicion {
    bottom: 5px;
    position: absolute;
    left: 15px;
}
.posicion span {
    font-size: 10px;
}
.vs-popup {
    width: 900px !important;
}
.peque .vs-popup {
    width: 1060px !important;
}
</style>
