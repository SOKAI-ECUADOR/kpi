<template>
    <div id="invoice-page">
        <vx-card>
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full mb-6">
                    <label for="" class="vs-input--label">Codigo de Barras:</label>
                    <vs-input class="w-full" v-model="codigo_barra"/>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6">
                    <label for="" class="vs-input--label">Nombre:</label>
                    <vs-input class="w-full" v-model="nombre_activo"/>
                    <div v-show="error" v-if="!nombre_activo">
                        <div v-for="err in errornombre" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full mb-6" >
                    <label for="" class="vs-input--label">Tipo Activo Fijo:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="tipo_activo"
                    >
                    <vs-select-item
                        v-for="data in activo_tipo"
                        :key="data.id_activo_fijo_tipo"
                        :value="data.id_activo_fijo_tipo"
                        :text="data.nombre"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_activo">
                        <div v-for="err in errortipo_activo_fijo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <!--<div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                    <label for="" class="vs-input--label">Tipo Activo Fijo:</label>
                    <vs-select
                    disabled
                    class="selectExample w-full"
                    autocomplete
                    v-model="tipo_activo"
                    >
                    <vs-select-item
                        v-for="data in activo_tipo"
                        :key="data.id_activo_fijo_tipo"
                        :value="data.id_activo_fijo_tipo"
                        :text="data.nombre"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!tipo_activo">
                        <div v-for="err in errortipo_activo_fijo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>-->
                <div class="vx-col sm:w-1/3 w-full mb-6" >
                    <label for="" class="vs-input--label">Grupo Activo Fijo:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="grupo_activo"
                    @change="grupo_valor(grupo_activo)"
                    >
                    <vs-select-item
                        v-for="data in activo_grupo"
                        :key="data.id_activo_fijo_grupo"
                        :value="data.id_activo_fijo_grupo"
                        :text="data.nombre"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!grupo_activo">
                        <div v-for="err in errorgrupo_activo_fijo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <!--<div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                    <label for="" class="vs-input--label">Grupo Activo Fijo:</label>
                    <vs-select
                    disabled
                    class="selectExample w-full"
                    autocomplete
                    v-model="grupo_activo"
                    @change="grupo_valor(grupo_activo)"
                    >
                    <vs-select-item
                        v-for="data in activo_grupo"
                        :key="data.id_activo_fijo_grupo"
                        :value="data.id_activo_fijo_grupo"
                        :text="data.nombre"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!grupo_activo">
                        <div v-for="err in errorgrupo_activo_fijo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>-->
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label for="" class="vs-input--label">Area Activo Fijo:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="area_activo"
                    >
                    <vs-select-item
                        v-for="data in activo_area"
                        :key="data.id_activo_fijo_area"
                        :value="data.id_activo_fijo_area"
                        :text="data.nombre"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!area_activo">
                        <div v-for="err in errorarea_activo_fijo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <!--<div class="vx-col sm:w-1/3 w-full mb-6" v-else>
                    <label for="" class="vs-input--label">Area Activo Fijo:</label>
                    <vs-select
                    disabled
                    class="selectExample w-full"
                    autocomplete
                    v-model="area_activo"
                    >
                    <vs-select-item
                        v-for="data in activo_area"
                        :key="data.id_activo_fijo_area"
                        :value="data.id_activo_fijo_area"
                        :text="data.nombre"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!area_activo">
                        <div v-for="err in errorarea_activo_fijo" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>-->
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label for="" class="vs-input--label">Departamento:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="id_departamento"
                    @change="getEmpleado()"
                    >
                    <vs-select-item
                        v-for="data in departamentos"
                        :key="data.id_departamento"
                        :value="data.id_departamento"
                        :text="data.dep_nombre"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!id_departamento">
                        <div v-for="err in errordepartamento" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label for="" class="vs-input--label">Responsable:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="id_empleado"
                    @selected="getEmpleado()"
                    >
                    <vs-select-item
                        v-for="data in empleados"
                        :key="data.id_empleado"
                        :value="data.id_empleado"
                        :text="data.nombre_empleado"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!id_empleado">
                        <div v-for="err in errorempleado" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/3 w-full mb-6">
                    <label for="" class="vs-input--label">Proyecto:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="id_proyecto"
                    >
                    <vs-select-item
                        v-for="data in proyectos"
                        :key="data.id_proyecto"
                        :value="data.id_proyecto"
                        :text="data.descripcion"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!id_proyecto">
                        <div v-for="err in errorproyecto" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/2 w-full mb-6">
                    <label for="" class="vs-input--label">Cuenta Debito:</label>
                        <vx-input-group class>
                            <vs-input
                                class="w-full"
                                v-model="cuenta_debito"
                                :value="idContable_debito"
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
                        <div v-show="error" v-if="!idContable_debito">
                            <div v-for="err in errorcuenta_debito" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                </div>
                <div class="vx-col sm:w-1/2 w-full mb-6">
                    <label for="" class="vs-input--label">Cuenta Credito:</label>
                    <vx-input-group class>
                            <vs-input
                                class="w-full"
                                v-model="cuenta_credito"
                                :value="idContable_credito"
                                maxlength="200"
                                disabled
                            />
                            <template slot="append">
                                <div class="append-text btn-addon">
                                    <vs-button
                                        color="primary"
                                        @click="activePrompt4  = true"
                                        >Buscar</vs-button
                                    >
                                </div>
                            </template>
                    </vx-input-group>
                        <div v-show="error" v-if="!idContable_credito">
                            <div v-for="err in errorcuenta_credito" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                </div>
                <!-- <div class="vx-col sm:w-1/3 w-full mb-6">
                    
                </div> -->
            </div> 
            
            <vs-divider position="left">
                <h3>Factura</h3>
            </vs-divider>
            <div class="vx-col sm:w-full w-full mb-6 relative">
                <!--
                    id_activos_fijos:null,
                            fecha_emision:tr.fech_emision,
                            descripcion:tr.descripcion,
                            nro_autorizacion:tr.nro_autorizacion,
                            proveedor:tr.nombre_proveedor,
                            id_factura_compra:tr.id_factcompra
                -->
                  <div class="vx-row p-base" v-for="(tr,index) in valorproveedores" :key="index">
                    <div class="vx-col sm:w-1/5 w-full mb-2" v-if="existe_proveedor">
                      <h6>Fecha Emision:</h6>

                      <flat-pickr
                            class="w-full"
                            disabled
                            :config="configdateTimePicker"
                            v-model="tr.fecha_emision"
                            placeholder="Elegir Fecha"
                        />
                        <div v-show="error" v-if="!tr.fecha_emision">
                            <div
                                v-for="err in tr.errorfecha"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/5 w-full mb-2" v-else>
                      <h6>Fecha Emision:</h6>
                      <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="tr.fecha_emision"
                            placeholder="Elegir Fecha"
                        />
                        <div v-show="error" >
                            <div
                                v-for="err in tr.errorfecha"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2" v-if="existe_proveedor">
                      <h6>Nro Factura:</h6>
                      <vs-input disabled class="w-full" v-model="tr.descripcion" />
                      <div v-show="error" v-if="!tr.descripcion">
                            <div
                                v-for="err in tr.errordescricion"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2" v-else>
                      <h6>Nro Factura:</h6>
                      <vs-input  class="w-full" v-model="tr.descripcion" />
                      <div v-show="error" v-if="!tr.descripcion">
                            <div
                                v-for="err in tr.errordescricion"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2" v-if="existe_proveedor">
                      <h6>Nro Autorizacion:</h6>        
                      <vs-input disabled class="w-full" v-model="tr.nro_autorizacion" />
                      <div v-show="error" v-if="!tr.nro_autorizacion">
                            <div
                                v-for="err in tr.errornro_autorizacion"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2" v-else>
                      <h6>Nro Autorizacion:</h6>        
                      <vs-input class="w-full" v-model="tr.nro_autorizacion" />
                      <div v-show="error" v-if="!tr.nro_autorizacion">
                            <div
                                v-for="err in tr.errornro_autorizacion"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2" v-if="existe_proveedor">
                      <h6>Proveedor:</h6>
                      <vs-input disabled class="w-full" v-model="tr.proveedor" />
                      <div v-show="error" v-if="!tr.proveedor">
                            <div
                                v-for="err in tr.errorproveedor"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <div class="vx-col sm:w-1/4 w-full mb-2" v-else>
                      <h6>Proveedor:</h6>
                      <vs-select class="selectExample w-full"
                            autocomplete v-model="tr.id_proveedor">
                        <vs-select-item
                          :key="item.id_proveedor"
                          :value="item.id_proveedor"
                          :text="item.nombre_proveedor"
                          v-for="(item) in proveedores"
                        />
                      </vs-select>
                      <div v-show="error" v-if="!tr.id_proveedor">
                            <div
                                v-for="err in tr.errorid_proveedor"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                    </div>
                    <feather-icon
                              
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer"
                                @click="borrarprov(index)"
                              />
                  </div>
            </div>
            
            <!--<div class="vx-col sm:w-full w-full mb-6 relative"  v-if="proveedor_tipo==true && factura_tipo==false">
                    <vs-input class="w-full busqueda_cliente" placeholder="Escoge algun Proveedor" v-model="busqueda_cliente" @keyup="listar_cliente(busqueda_cliente)"/>
                    <feather-icon icon="SearchIcon" svgClasses="w-7 h-7 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                    <div class="busqueda_lista busqueda_lista_proveedor">
                        <ul class="ul_busqueda_lista" v-if="contenidoprov.length">
                            <li v-for="(tr,index) in contenidoprov" :key="index" @click="seleccionar_cliente(tr,'prov')"> {{ tr.nombre_proveedor }} </li>
                        </ul>
                        <ul class="ul_busqueda_lista" v-else-if="preloader_prov == true && contenidoprov.length<1">
                            <li>
                                    ESTE PROVEEDOR NO SE ENCUENTRA REGISTRADO
                            </li>
                        </ul>
                    </div>
                    <div v-show="error" v-if="valorproveedores.length<1">
                        <div v-for="err in errorproveedor" :key="err" v-text="err" class="text-danger"></div>
                    </div>
            </div>-->
            <div class="vx-col sm:w-full w-full mb-6 relative"  v-if="proveedor_tipo==true && factura_tipo==true">
                <vs-input class="w-full busqueda_cliente" placeholder="Busca al Proveedor por Factura" v-model="busqueda_cliente" @keyup="documentos()"/>
                <feather-icon icon="SearchIcon" svgClasses="w-7 h-7 hover:text-primary stroke-current cursor-pointer" class="busqueda_cliente_icono"/>
                <div class="busqueda_lista busqueda_lista_proveedor">
                        <ul class="ul_busqueda_lista" v-if="contenidoprov.length">
                            <li v-for="(tr,index) in contenidoprov" :key="index" @click="seleccionar_cliente(tr,'fact')"> {{ tr.prov_nombre }} </li>
                        </ul>
                        <ul class="ul_busqueda_lista" v-else-if="preloader_prov == true && contenidoprov.length<1">
                            <li @click="crear()">
                                    ESTA FACTURA NO SE ENCUENTRA REGISTRADO,ESCRIBIR FACTURA
                            </li>
                        </ul>
                        <div v-show="error" v-if="valorproveedores.length<1">
                            <div v-for="err in errorfactura" :key="err" v-text="err" class="text-danger"></div>
                        </div>
                </div>
            </div>
            <br>
            <vs-divider position="left">
                <h3>Depreciacion</h3>
            </vs-divider>
            {{total_valor_residual}}
            {{total_valor_depreciar}}
            {{total_valor_depreciacion}}
            {{total_valor_actual}}
            {{cambioDecimal}}
            <div class="vx-row">
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label for="" class="vs-input--label">Depreciacion Mensual:</label>
                    <vs-checkbox
                            v-model="depreciacion_mensual"
                            vs-value="1"
                    >
                    <label for="" class="vs-input--label" v-if="depreciacion_mensual==1">Si</label>
                    <label for="" class="vs-input--label" v-else>No</label>
                    </vs-checkbox>
                    
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6" v-if="!$route.params.id">
                    <label for="" class="vs-input--label">Valor Bien:</label>
                    <vs-input class="w-full" v-model="valor_bien" @blur="cambiarADecimal('bien')"/>
                    <div v-show="error" v-if="!valor_bien">
                        <div v-for="err in errorvalor_bien" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6" v-else>
                    <label for="" class="vs-input--label">Valor Bien:</label>
                    <vs-input disabled class="w-full" v-model="valor_bien" @blur="cambiarADecimal('bien')"/>
                    <div v-show="error" v-if="!valor_bien">
                        <div v-for="err in errorvalor_bien" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label for="" class="vs-input--label">Valor Residual:</label>
                    <vs-input class="w-full" disabled v-model="valor_residual"/>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label for="" class="vs-input--label">Valor Depreciar:</label>
                    <vs-input class="w-full" disabled v-model="valor_depreciar"/>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label for="" class="vs-input--label">Valor Depreciacion:</label>
                    <vs-input class="w-full" disabled v-model="valor_depreciacion"/>
                </div>
            </div>
            <div class="vx-row">
                <div class="vx-col sm:w-1/5 w-full mb-6">
                    <label for="" class="vs-input--label">Moneda:</label>
                    <vs-select
                    class="selectExample w-full"
                    autocomplete
                    v-model="id_moneda"
                    >
                    <vs-select-item
                        v-for="data in monedas"
                        :key="data.id_moneda"
                        :value="data.id_moneda"
                        :text="data.nomb_moneda"
                    />
                    </vs-select>
                    <div v-show="error" v-if="!id_moneda">
                        <div v-for="err in errormoneda" :key="err" v-text="err" class="text-danger"></div>
                    </div>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6 " v-if="!$route.params.id">
                    <label for="" class="vs-input--label">Depreciacion Acumulada:</label>
                    <vs-input class="w-full"  v-model="depreciacion_acumulada" @blur="cambiarADecimal('acumulado')"/>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6 " v-else>
                    <label for="" class="vs-input--label">Depreciacion Acumulada:</label>
                    <vs-input disabled class="w-full"  v-model="depreciacion_acumulada"/>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6" >
                    <label for="" class="vs-input--label">Valor Reavaluo:</label>
                    <vs-input class="w-full" v-model="valor_reavaluo" @blur="cambiarADecimal('reavaluo')"/>
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6" v-if="!$route.params.id">
                    <label for="" class="vs-input--label">Ultima Depreciacion:</label>
                    <flat-pickr
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="ultima_depreciacion"
                            placeholder="Elegir Fecha"
                        />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6" v-else>
                    <label for="" class="vs-input--label">Ultima Depreciacion:</label>
                    <flat-pickr
                            disabled
                            class="w-full"
                            :config="configdateTimePicker"
                            v-model="ultima_depreciacion"
                            placeholder="Elegir Fecha"
                        />
                </div>
                <div class="vx-col sm:w-1/5 w-full mb-6 " >
                    <label for="" class="vs-input--label">Valor Actual:</label>
                    <vs-input class="w-full" disabled v-model="valor_actual" />
                </div>
            </div>
            <br>
            <div class="vx-col w-full">
                        <vs-button color="success" type="filled" @click="guardar()" v-if="!$route.params.id">GUARDAR</vs-button>
                        <vs-button color="success" type="filled" @click="editar()" v-else>GUARDAR</vs-button>
                        <vs-button color="warning" type="filled" @click="vaciar()">BORRAR</vs-button>
                        <vs-button color="danger"  type="filled" @click="cancelar()">CANCELAR</vs-button>
            </div>
            <vs-popup
                    title="Plan Cuentas"
                    :class="'peque2'"
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
                            @selected="handleSelectedDebito"
                            :data="contenido_credito"
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
                <vs-popup
                    title="Plan Cuentas"
                    :class="'peque2'"
                    :active.sync="activePrompt4"
                >
                    <div class="con-exemple-prompt">
                        <vs-input
                            class="mb-4 md:mb-0 mr-4 w-full"
                            v-model="buscar_credito"
                            @keyup="listar(1, buscar_credito)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <vs-table
                            stripe
                            v-model="cuentaarray3_credito"
                            @selected="handleSelectedCredito"
                            :data="contenido_credito"
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
</template>
<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import vSelect from "vue-select";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
const $ = require("jquery");
const axios = require("axios");
export default {
    components: {
        flatPickr,
        "v-select": vSelect
    },
    data(){
        return{
            codigo_barra:"",
            tipo_activo:"",
            grupo_activo:"",
            area_activo:"",
            activo_area:[],
            activo_grupo:[],
            activo_tipo:[],
            id_proyecto:"",
            proyectos:[],
            id_empleado:"",
            empleados:[],
            id_departamento:"",
            departamentos:[],
            id_moneda:"",
            monedas:[],
            id_banco:"",
            bancos:[],
            configdateTimePicker: {
                locale: SpanishLocale
            },
            fecha_movimiento:"",
            cuenta_debito:"",
            cuenta_credito:"",
            idContable_debito:"",
            idContable_credito:"",
            depreciacion_mensual:null,
            valor_bien:"",
            valor_residual:"",
            valor_depreciar:"",
            valor_depreciacion:"",
            depreciacion_acumulada:"",
            valor_reavaluo:"",
            ultima_depreciacion:"",
            valor_actual:"",
            valor_residual_grupo:"",
            porcentaje_grupo:"",
            anio_grupo:"",
            proveedores:[],
            nombre_activo:"",
            valor_acumulado:"",
            //valores plan cuenta debito
            buscar:"",
            i18nbuscar: this.$t("i18nbuscar"),
            cuentaarray3: [],
            contenido_credito:[],
            activePrompt3:false,
            //valores plan cuenta credito
            buscar_credito:"",
            cuentaarray3_credito:[],
            activePrompt4:false,
            existe_proveedor:true,
            //variables proveedor
            valorproveedores:[],
            proveedor_tipo:true,
            factura_tipo:true,
            busqueda_cliente:"",
            contenidoprov:[],
            preloader_prov:false,
            tipo_identificacion_menu: [
                { text: "Seleccione", value: 0 },
                { text: "Cédula de Identidad", value: "Cedula" },
                { text: "Ruc", value: "Ruc" },
                { text: "Pasaporte", value: "Pasaporte" },
                { text: "Extranjero", value: "Extranjero" },
                { text: "Consumidor Final", value: 4 }
            ],
            
            //errores proveedor
            error: 0,
            errornombre:[],
            errortipo_activo_fijo:[],
            errorgrupo_activo_fijo:[],
            errorarea_activo_fijo:[],
            errordepartamento:[],
            errorempleado:[],
            errorproyecto:[],
            errorcuenta_debito:[],
            errorcuenta_credito:[],
            errormoneda:[],
            errorfactura:[],
            errorvalor_bien:[],
            errorproveedor:[],
        };
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        total_valor_residual(){
            if(this.valor_bien && this.valor_residual_grupo){
                this.valor_residual=parseFloat((this.valor_residual_grupo/100)*this.valor_bien);
            }else{
                this.valor_residual="";
            }
        },
        total_valor_depreciar(){
            if(this.valor_bien && this.valor_residual){
                this.valor_depreciar=parseFloat(this.valor_bien-this.valor_residual);
            }else{
                this.valor_depreciar="";
            }
        },
        total_valor_depreciacion(){
            if(this.valor_depreciar && this.anio_grupo){
                if(this.depreciacion_mensual==null){
                    this.valor_depreciacion=parseFloat((this.valor_depreciar/this.anio_grupo));
                    console.log("valor_depreciar: "+this.valor_depreciar+" anio_grupo: "+this.anio_grupo+" porcentaje grupo: "+this.porcentaje_grupo/100+" valor_depreciacion "+(this.valor_depreciar/(this.anio_grupo))*(this.porcentaje_grupo/100));
                }else{
                    this.valor_depreciacion=parseFloat((this.valor_depreciar/this.anio_grupo/12));
                    console.log("valor_depreciar: "+this.valor_depreciar+" anio_grupo: "+this.anio_grupo+" si es mensual:"+parseFloat(this.anio_grupo/12).toFixed(2)+" porcentaje grupo: "+this.porcentaje_grupo/100+" valor_depreciacion "+(this.valor_depreciar/(this.anio_grupo/12))*(this.porcentaje_grupo/100));
                }
                
            }else{
                this.valor_depreciacion="";
            }
        },
        // total_depreciacion_acumulada(){
        //     if(!this.$route.params.id){
        //             if(this.valor_depreciacion){

        //                 this.depreciacion_acumulada=parseFloat(this.valor_depreciacion+this.valor_acumulado);
        //                 console.log("Valor depreciacion: "+this.valor_depreciacion+" valor acum: "+this.valor_acumulado+" suma: "+parseFloat(this.valor_depreciacion+this.valor_acumulado));
        //                 //console.log("valor_depreciar: "+this.valor_depreciar+" anio_grupo: "+this.anio_grupo+" si es mensual:"+this.anio_grupo/12+" valor_depreciacion "+this.valor_depreciar/(this.anio_grupo/12));
                    
                    
        //             }else{
        //                 this.depreciacion_acumulada="";
        //             }
        //     }
            
        // },
        total_valor_actual(){
            if(this.valor_bien){
                this.valor_actual=parseFloat(this.valor_bien-this.depreciacion_acumulada);
            }else{
                this.valor_depreciacion="";
            }
        },
        cambioDecimal(){
            if(this.valor_residual){
                this.valor_residual=parseFloat(this.valor_residual).toFixed(2);
            }
            if(this.valor_depreciar){
                this.valor_depreciar=parseFloat(this.valor_depreciar).toFixed(2);
            }
            if(this.valor_depreciacion){
                this.valor_depreciacion=parseFloat(this.valor_depreciacion).toFixed(2);
            }
            // if(this.valor_acumulado){
            //     this.valor_acumulado=parseFloat(this.valor_acumulado).toFixed(2);
            // }
            // if(this.depreciacion_acumulada){
            //     this.depreciacion_acumulada=parseFloat(this.depreciacion_acumulada).toFixed(2);
            // }
            if(this.valor_actual){
                this.valor_actual=parseFloat(this.valor_actual).toFixed(2);
            }
        }
    },
    methods:{
        listarTipo(page,buscar2){
            let me = this;
            var url =
                "/api/tipo_activo/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.activo_tipo = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarGrupo(page3,buscar3){
            let me = this;
            var url =
                "/api/grupo_activo/" +
                this.usuario.id_empresa +
                "?page=" +
                page3 +
                "&buscar=" +
                buscar3;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.activo_grupo = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        grupo_valor(grupo_activo){
            let me = this;
            var url =
                "/api/abrir/valores/grupo_activo/" +
                grupo_activo+
                "?id_empresa="+this.usuario.id_empresa;
                
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.valor_residual_grupo=respuesta.recupera[0].valor_residual;
                    me.porcentaje_grupo=respuesta.recupera[0].porcentaje;
                    me.anio_grupo=respuesta.recupera[0].anios;
                    if(respuesta.acum_valor_depreciacion.length>0){
                        me.valor_acumulado=parseFloat(respuesta.acum_valor_depreciacion[0].acum_valor_depreciacion);
                    }else{
                        me.valor_acumulado=0;
                    }
                    
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarArea(page,buscar2){
            let me = this;
            var url =
                "/api/area_activo/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.activo_area = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarDepartamento(page1, buscar1) {
            var url = "/api/departamento/listar/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(res => {
                    this.departamentos = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getEmpleado(){
            axios
                .get("/api/empleados/departamento", {
                    params: {
                        id_departamento: this.id_departamento
                    }
                })
                .then(
                    function(response) {
                        this.empleados = response.data;
                    }.bind(this)
                );
        },
        listarProyecto(){
            var url = "/api/listarproyecto/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(res => {
                    this.proyectos = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        getBancos: function() {
            axios.get("/api/traerbancoprov").then(
                function(response) {
                    this.bancos = response.data;
                    //this.provs==this.id_provincia
                }.bind(this)
            );
        },
        listarmonedas() {
            axios.get("/api/traermoneda").then(res => {
                this.monedas = res.data;
            });
        },
        cambiarADecimal(tipo){
            if(tipo=='bien'){
                if(this.valor_bien){
                    this.valor_bien=parseFloat(this.valor_bien).toFixed(2);
                }
            }
            if(tipo=='acumulado'){
                if(this.depreciacion_acumulada){
                    this.depreciacion_acumulada=parseFloat(this.depreciacion_acumulada).toFixed(2);
                }
            }
            if(tipo=='reavaluo'){
                if(this.valor_reavaluo){
                    this.valor_reavaluo=parseFloat(this.valor_reavaluo).toFixed(2);
                }
            }
            
        },
        listar_cliente(buscar){
            axios.get('/api/factura_compra/listar_proveedor?buscar=' + buscar + "&usuario=" + this.usuario.id_empresa).then( ({data}) => {
                $(".busqueda_lista_proveedor").show();
                this.contenidoprov = data;
                if(this.contenidoprov.length>0){
                  this.preloader_prov =false;
                }else{
                  this.preloader_prov =true;
                }
                
                //this.cliente.clientes = data;
                console.log(this.contenidoprov);
            }).catch( error => {
                console.log(error);
            });
        },
        crear(){
            this.proveedor_tipo=true;
            this.factura_tipo=false;
            this.existe_proveedor=false;
            this.valorproveedores.push(
                        {
                            id_activos_fijos:null,
                            fecha_emision:"",
                            descripcion:"",
                            nro_autorizacion:"",
                            proveedor:"",
                            id_factura_compra:null,
                            id_proveedor:null
                        },
                        );
            
            this.busqueda_cliente="";
            this.preloader_prov =false;
        },
        listarProveedor(page, buscar) {
            var url =
                "/api/proveedor/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(res => {
                    var respuesta = res.data;
                    this.proveedores = respuesta.recupera;
                })
                .catch(function(error) {
                    this.$vs.notify({
                        title: "Error al Guardar",
                        text: "Verifique bien sus datos al momento de guardar",
                        color: "danger"
                    });
                });
        },
        borrarprov(id) {
            this.valorproveedores.splice(id, 1);
            this.proveedor_tipo=true;
            this.factura_tipo=true;
        },
        documentos(){
            //if(this.busqueda_cliente.length==15){
                axios.post('/api/proveedor/buscarfactura', {
                    factura:this.busqueda_cliente,
                    id_empresa: this.usuario.id_empresa,
                }).then( ({data}) => {
                    if(data=='error'){
                        this.$vs.notify({
                            title: "Factura erronea",
                            text: "Esta factura no consta en nuestro sistema",
                            color: "danger"
                        }); 
                        this.contenidoprov=[];
                        if(this.contenidoprov.length>0){
                            this.preloader_prov =false;
                        }else{
                            this.preloader_prov =true;
                        }
                        
                    }else{
                        this.contenidoprov = data.proveedor;
                        
                        if(this.contenidoprov.length>0){
                            this.preloader_prov =false;
                        }else{
                            this.preloader_prov =true;
                        }
                        
                    }
                }); 
            //}
        },
        seleccionar_cliente(tr,tipo){
                this.contenidoprov=[];
                // if(this.valorproveedores.length<1){
                //   this.valorproveedores.splice(0, 1);
                //   this.proveedor_tipo=true;
                //   $(".busqueda_lista_proveedor").hide();
                // }
                //console.log(tr);
                if(tipo=='prov'){
                    if(this.valorproveedores.length<1){
                        this.valorproveedores.push(
                        {
                            id_activos_fijos:null,
                            id_proveedor:tr.id_proveedor,
                            nombre: tr.nombre_proveedor,
                            telefono: tr.telefono_prov,
                            grupo: tr.id_grupo_proveedor,
                            tipo_identificacion: tr.tipo_identificacion,
                            identificacion: tr.identif_proveedor,
                            direccion: tr.direccion_prov,
                            nombre_grupo:tr.nombre_grupoprov
                        },
                        );
                        this.proveedor_tipo=true;
                        this.factura_tipo=false;
                        this.busqueda_cliente="";
                        //this.listar_cliente("");
                        $(".busqueda_lista_proveedor").show();
                    }
                    if(this.valorproveedores.length>=1){
                        this.proveedor_tipo=false;
                        this.factura_tipo=false;
                        $(".busqueda_lista_proveedor").hide();
                    }
                }else{
                    if(this.valorproveedores.length<1){
                        this.valorproveedores.push(
                            {
                                id_activos_fijos:null,
                                fecha_emision:tr.fech_emision,
                                descripcion:tr.descripcion,
                                nro_autorizacion:tr.nro_autorizacion,
                                proveedor:tr.nombre_proveedor,
                                id_factura_compra:tr.id_factcompra,
                                id_proveedor:tr.id_proveedor
                            },
                        );
                        this.proveedor_tipo=true;
                        this.factura_tipo=true;
                        this.busqueda_cliente="";
                        //this.listar_cliente("");
                        $(".busqueda_lista_proveedor").show();
                    }
                    if(this.valorproveedores.length>=1){
                        this.proveedor_tipo=false;
                        this.factura_tipo=false;
                        $(".busqueda_lista_proveedor").hide();
                    }
                }
                
                // this.cliente.clientes = [];
                // this.cliente.busqueda = '';
                // this.cliente.tipo = true;
                // this.cliente.id_cliente = tr.id_proveedor;
                // this.cliente.nombre = tr.nombre_proveedor;
                // this.cliente.telefono = tr.contacto;
                // this.cliente.email = '';
                // this.cliente.tipo_identificacion = tr.tipo_identificacion;
                // this.cliente.identificacion = tr.identif_proveedor;
                // this.cliente.direccion = tr.direccion_prov;
                //$(".busqueda_lista_proveedor").hide();
                //this.anticipover(tr.id_proveedor);
        },
        //methodos plan cuenta
        handleSelectedCredito(tr) {
                (this.cuenta_credito = `${tr.nomcta}`),
                (this.idContable_credito = `${tr.id_plan_cuentas}`),
                (this.activePrompt4 = false),
                (this.buscar_credito="");
        },
        handleSelectedDebito(tr) {
                (this.cuenta_debito = `${tr.nomcta}`),
                (this.idContable_debito = `${tr.id_plan_cuentas}`),
                (this.activePrompt3 = false),
                (this.buscar="");
        },
        listar(page, buscar) {
            let me = this;
            var url =
                "/api/notacredito/listar_cuenta_contable"
                //  +
                // this.usuario.id_empresa +
                // "?page=" +
                // page +
                // "&buscar=" +
                // buscar
                ;
            axios
                .get(url,{
                    params:{
                        empresa:this.usuario.id_empresa,
                        buscar:buscar
                    }
                })
                .then(({data})=> {
                    var respuesta = data;
                    me.contenido_credito = respuesta;
                })
                .catch(function(error) {
                    //console.log(error);
                });
        },
        guardar(){
            /*
            tipo_activo:"",
            grupo_activo:"",
            area_activo:"",
            */
            if(this.validar()){
                return;
            }
            var fecha=null;
            var nro_factura=null;
            var nro_autorizacion=null;
            var nombre_proveedor=null;
            var id_factura_compra=null;
            var id_proveedor=null;
            //console.log(this.valorproveedores[0].fecha_emision);
            if(this.valorproveedores.length>0){
                if(typeof this.valorproveedores[0].fecha_emision!=='undefined'){
                    console.log(fecha+"Fecha emision");
                    fecha=this.valorproveedores[0].fecha_emision;
                }
                if(typeof this.valorproveedores[0].descripcion!=='undefined'){
                    nro_factura=this.valorproveedores[0].descripcion;
                }
                if(typeof this.valorproveedores[0].nro_autorizacion!=='undefined'){
                    nro_autorizacion=this.valorproveedores[0].nro_autorizacion;
                }
                if(typeof this.valorproveedores[0].proveedor!=='undefined'){
                    nombre_proveedor=this.valorproveedores[0].proveedor;
                }
                if(typeof this.valorproveedores[0].id_factura_compra!=='undefined'){
                    id_factura_compra=this.valorproveedores[0].id_factura_compra;
                }
                if(typeof this.valorproveedores[0].id_factura_compra!=='undefined'){
                    id_factura_compra=this.valorproveedores[0].id_factura_compra;
                }
                if(typeof this.valorproveedores[0].id_proveedor!=='undefined'){
                    id_proveedor=this.valorproveedores[0].id_proveedor;
                }
            }
            
            axios
                .post("/api/guardar/activo_fijo", {
                    codigo_barra:this.codigo_barra,
                    nombre:this.nombre_activo,
                    depreciacion_mensual:this.depreciacion_mensual,
                    valor_bien:this.valor_bien,
                    valor_residual:this.valor_residual,
                    valor_depreciar:this.valor_depreciar,
                    valor_depreciacion:this.valor_depreciacion,
                    valor_reavaluo:this.valor_reavaluo,
                    depreciacion_acumulada:this.depreciacion_acumulada,
                    valor_actual:this.valor_actual,
                    fecha_movimiento:this.fecha_movimiento,
                    ultima_depreciacion:this.ultima_depreciacion,
                    fecha_factura:fecha,
                    nro_factura:nro_factura,
                    nro_autorizacion:nro_autorizacion,
                    ucrea:this.usuario.id,
                    nombre_proveedor:nombre_proveedor,
                    id_plan_cuentas_debito:this.idContable_debito,
                    id_plan_cuentas_credito:this.idContable_credito,
                    id_area_activo_fijo:this.area_activo,
                    id_departamento:this.id_departamento,
                    id_empresa:this.usuario.id_empresa,
                    id_grupo_activo_fijo:this.grupo_activo,
                    id_tipo_activo_fijo:this.tipo_activo,
                    id_factura_compra:id_factura_compra,
                    id_empleado:this.id_empleado,
                    id_proveedor:id_proveedor,
                    id_proyecto:this.id_proyecto,
                    id_moneda:this.id_moneda
                })
                .then(res => {
                    this.$vs.notify({
                    title: "Registro Guardado",
                    text: "Registro Guardado exitosamente",
                    color: "success"
                    });
                    this.$router.push("/activos-fijos/registro");
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Guardar",
                        text: "Verifique bien sus datos al momento de guardar",
                        color: "danger"
                    });
                });
        },
        editar(){
            axios
                .put("/api/actualizar/activo_fijo", {
                    id:this.$route.params.id,
                    codigo_barra:this.codigo_barra,
                    nombre:this.nombre_activo,
                    depreciacion_mensual:this.depreciacion_mensual,
                    valor_bien:this.valor_bien,
                    valor_residual:this.valor_residual,
                    valor_depreciar:this.valor_depreciar,
                    valor_depreciacion:this.valor_depreciacion,
                    valor_reavaluo:this.valor_reavaluo,
                    depreciacion_acumulada:this.depreciacion_acumulada,
                    valor_actual:this.valor_actual,
                    fecha_movimiento:this.fecha_movimiento,
                    ultima_depreciacion:this.ultima_depreciacion,
                    fecha_factura:this.valorproveedores[0].fecha_emision,
                    nro_factura:this.valorproveedores[0].descripcion,
                    nro_autorizacion:this.valorproveedores[0].nro_autorizacion,
                    umodifica:this.usuario.id,
                    nombre_proveedor:this.valorproveedores[0].proveedor,
                    id_plan_cuentas_debito:this.idContable_debito,
                    id_plan_cuentas_credito:this.idContable_credito,
                    id_area_activo_fijo:this.area_activo,
                    id_departamento:this.id_departamento,
                    id_empresa:this.usuario.id_empresa,
                    id_grupo_activo_fijo:this.grupo_activo,
                    id_tipo_activo_fijo:this.tipo_activo,
                    id_factura_compra:this.valorproveedores[0].id_factura_compra,
                    id_empleado:this.id_empleado,
                    id_proveedor:this.valorproveedores[0].id_proveedor,
                    id_proyecto:this.id_proyecto,
                    id_moneda:this.id_moneda
                })
                .then(res => {
                    this.$vs.notify({
                    title: "Registro Editado",
                    text: "Registro Editado exitosamente",
                    color: "success"
                    });
                    this.$router.push("/activos-fijos/registro");
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Verifique bien sus datos al momento de editar",
                        color: "danger"
                    });
                });
        },
        validar(){
            this.error= 0;
            this.errornombre=[];
            this.errortipo_activo_fijo=[];
            this.errorgrupo_activo_fijo=[];
            this.errorarea_activo_fijo=[];
            this.errordepartamento=[];
            this.errorempleado=[];
            this.errorproyecto=[];
            this.errorcuenta_debito=[];
            this.errorcuenta_credito=[];
            this.errormoneda=[];
            this.errorfactura=[];
            this.errorvalor_bien=[];
            if(!this.tipo_activo){
                this.errornombre.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.tipo_activo){
                this.errortipo_activo_fijo.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.grupo_activo){
                this.errorgrupo_activo_fijo.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.area_activo){
                this.errorarea_activo_fijo.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.id_departamento){
                this.errordepartamento.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.id_empleado){
                this.errorempleado.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.id_proyecto){
                this.errorproyecto.push("Campo Obligatorio");
                this.error=1;
            }
            /*if(!this.idContable_debito){
                this.errorcuenta_debito.push("Campo Obligatorio");
                this.error=1;
            }
            if(!this.idContable_credito){
                this.errorcuenta_credito.push("Campo Obligatorio");
                this.error=1;
            }*/
            if(!this.id_moneda){
                this.errormoneda.push("Campo Obligatorio");
                this.error=1;
            }
            // if(this.valorproveedores.length<=0){
            //     this.errorfactura.push("Campo Obligatorio");
            //     this.error=1;
            // }else
            if(this.valorproveedores.length>0){
                /*
                            fecha_emision:"",
                            descripcion:"",
                            nro_autorizacion:"",
                            proveedor:"",
                            id_factura_compra:null,
                            id_proveedor:null
                */
                for (var i = 0; i < this.valorproveedores.length; i++) {
                    this.valorproveedores[i].errorfecha = [];
                    this.valorproveedores[i].errordescricion = [];
                    this.valorproveedores[i].errornro_autorizacion = [];
                    this.valorproveedores[i].errorproveedor = [];
                    this.valorproveedores[i].errorid_proveedor = [];
                    if(!this.valorproveedores[i].fecha_emision){
                        this.valorproveedores[i].errorfecha.push("Campo Obligatorio");
                        this.error=1; 
                    }
                    if(!this.valorproveedores[i].descripcion){
                        this.valorproveedores[i].errordescricion.push("Campo Obligatorio");
                        this.error=1;
                    }
                    // if(!this.valorproveedores[i].nro_autorizacion){
                    //     this.valorproveedores[i].errornro_autorizacion.push("Campo Obligatorio");
                    //     this.error=1;
                    // }
                    if(this.existe_proveedor==true){
                        if(!this.valorproveedores[i].proveedor){
                            this.valorproveedores[i].errorproveedor.push("Campo Obligatorio");
                            this.error=1;
                        }
                    }else{
                        if(!this.valorproveedores[i].id_proveedor){
                            this.valorproveedores[i].errorid_proveedor.push("Campo Obligatorio");
                            this.error=1;
                        }
                    }
                }
            }
            if(!this.valor_bien){
                this.errorvalor_bien.push("Campo Obligatorio");
                this.error=1;
            }
            return this.error;
        },
        vaciar(){
            let me = this;
                        me.codigo_barra="";
                        me.nombre_activo="";
                        me.depreciacion_mensual=null;
                        me.valor_bien="";
                        //me.valor_residual=response.data[0].valor_residual;
                        //me.valor_depreciar=response.data[0].valor_depreciar;
                        //me.valor_depreciacion=response.data[0].valor_depreciacion;
                        me.valor_reavaluo="";
                        //me.depreciacion_acumulada=response.data[0].depreciacion_acumulada;
                        //me.valor_actual=response.data[0].valor_actual;
                        me.fecha_movimiento="";
                        me.ultima_depreciacion="";
                        me.idContable_credito="";
                        me.cuenta_credito=""; 
                        me.area_activo="";
                        me.id_departamento="";
                        me.grupo_activo="";
                        me.tipo_activo="";
                        // this.valorproveedores[0].id_factura_compra=,
                        // this.valorproveedores[0].fecha_emision=,
                        // this.valorproveedores[0].descripcion=,
                        // this.valorproveedores[0].nro_autorizacion=,
                        // this.valorproveedores[0].proveedor=,
                        // this.valorproveedores[0].id_proveedor
                        me.idContable_debito="";
                        me.cuenta_debito="";
                        me.id_empleado="";
                        me.id_proyecto="";
                        me.id_moneda="";
                        me.valorproveedores=[];
                        me.busqueda_cliente="";
                        me.proveedor_tipo=true;
                        me.factura_tipo=true;
        },
        cancelar(){
            this.$router.push("/activos-fijos");
        },
        listarActivoFijo(){
            if(this.$route.params.id){
                let me = this;
                var url =
                    "/api/abrir/activo_fijo/" +
                    this.$route.params.id;
                    
                axios
                    .get(url)
                    .then(function(response) {
                        var respuesta = response.data;
                        console.log(response.data[0].codigo_barra+"Nombre activo");
                        //console.log(response.data[0].id_activo_fijo_tipo);
                        me.codigo_barra=response.data[0].codigo_barra;
                        me.nombre_activo=response.data[0].nombre;
                        me.depreciacion_mensual=response.data[0].depreciacion_mensual;
                        me.valor_bien=response.data[0].valor_bien;
                        //me.valor_residual=response.data[0].valor_residual;
                        //me.valor_depreciar=response.data[0].valor_depreciar;
                        //me.valor_depreciacion=response.data[0].valor_depreciacion;
                        me.valor_reavaluo=response.data[0].valor_reavaluo;
                        me.depreciacion_acumulada=response.data[0].depreciacion_acumulada;
                        //me.valor_actual=response.data[0].valor_actual;
                        me.fecha_movimiento=response.data[0].fecha_movimiento;
                        me.ultima_depreciacion=response.data[0].ultima_depreciacion;
                        me.idContable_credito=response.data[0].id_plan_cuentas_credito;
                        me.cuenta_credito=response.data[0].nombre_cuenta_credito; 
                        me.area_activo=response.data[0].id_area_activo_fijo;
                        me.id_departamento=response.data[0].id_departamento;
                        me.grupo_activo=response.data[0].id_grupo_activo_fijo;
                        me.tipo_activo=response.data[0].id_tipo_activo_fijo;
                        // this.valorproveedores[0].id_factura_compra=,
                        // this.valorproveedores[0].fecha_emision=,
                        // this.valorproveedores[0].descripcion=,
                        // this.valorproveedores[0].nro_autorizacion=,
                        // this.valorproveedores[0].proveedor=,
                        // this.valorproveedores[0].id_proveedor
                        me.idContable_debito=response.data[0].id_plan_cuentas_debito;
                        me.cuenta_debito=response.data[0].nombre_cuenta_debito;
                        me.id_empleado=response.data[0].id_empleado;
                        me.id_proyecto=response.data[0].id_proyecto;
                        me.id_moneda=response.data[0].id_moneda;
                        if(response.data[0].id_factura_compra){
                            me.existe_proveedor=true;
                            me.proveedor_tipo=false;
                            me.factura_tipo=false;
                            me.valorproveedores.push(
                                {
                                    id_activos_fijos:response.data[0].id_activos_fijos,
                                    fecha_emision:response.data[0].fech_emision,
                                    descripcion:response.data[0].descripcion,
                                    nro_autorizacion:response.data[0].nro_autorizacion_factura,
                                    proveedor:response.data[0].prov_nombre,
                                    id_factura_compra:response.data[0].id_factura_compra,
                                    id_proveedor:response.data[0].id_proveedor
                                },
                            );
                        }else{
                            me.existe_proveedor=false;
                            me.proveedor_tipo=false;
                            me.factura_tipo=false;
                            me.valorproveedores.push(
                                {
                                    id_activos_fijos:response.data[0].id_activos_fijos,
                                    fecha_emision:response.data[0].fecha_factura,
                                    descripcion:response.data[0].nro_factura,
                                    nro_autorizacion:response.data[0].nro_autorizacion,
                                    proveedor:"",
                                    id_factura_compra:null,
                                    //proveedor:response.data[0].nombre_proveedor,
                                    //id_factura_compra:response.data[0].id_factura_compra,
                                    id_proveedor:response.data[0].id_proveedor
                                },
                            );
                        }
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }
        }
    },
    mounted(){
        this.listarTipo(1,"");
        this.listarGrupo(1,"");
        this.listarArea(1,"");
        this.listarDepartamento(1,"");
        this.listarProyecto();
        this.getEmpleado();
        this.listar(1, this.buscar);
        this.listarProveedor(1, "");
        this.listarmonedas();
        this.listarActivoFijo();
    }
}
</script>
<style lang="scss">
@import "@sass/vuexy/extraComponents/agGridStyleOverride.scss";
.vs-popup {
  width: 1060px !important;
}
.peque .vs-popup {
  width: 600px !important;
}
.peque2 .vs-popup {
  width: 900px !important;
}
.sindis .vs-input--input:focus {
  border-top: none !important;
  border-left: none !important;
  border-right: none !important;
}
.valores input {
  text-align:end;
}
.valores .vs-input--placeholder {
  text-align:end;
}
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
.botonstl{
  height: 100%;
    width: 38px;
    border: 1px solid #635ace;
    background: transparent;
    color: #635ace;
    font-size: 16px;
    cursor: pointer;
}
.elejido{
  background: #635ace!important;
  color:#fff!important;
}
.busqueda_cliente input{
        height: 50px;
        padding-left: 45px!important;
    }
    .busqueda_cliente_icono{
        position: absolute!important;
        top: 11px;
        left: 15px;
    }
    .busqueda_lista{
        position: absolute;
        width: 97%;
        z-index: 9;
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
    .lista_preloader{
        padding: 8px;
        display: flex;
        justify-content: center;
        align-items: center;
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
</style>