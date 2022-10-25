<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1, buscar, '', '')"
                        v-bind:placeholder="i18nbuscar"
                    />
                    
                    <div class="dropdown-button-container mr-3">
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="settings"
                                style="border-radius: 5px;"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirTipo()"
                                    >Tipo Activo Fijo</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirGrupo()"
                                    >Grupo Activo Fijo</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirArea()"
                                    >Area Activo Fijo</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div> 
                    <div class="dropdown-button-container mr-3" v-if="crearrol">
                        <!--@click="abrirModal()"-->
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/activos-fijos/registro/agregar"
                            >Agregar</vs-button
                        >
                    </div>
                </div>
            </div>
            <br>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                <vs-th>Nombre</vs-th>
                <vs-th>Nro Factura</vs-th>
                <vs-th>Proveedor</vs-th>
                <vs-th>Valor Bien</vs-th>
                <!--<vs-th>Total Ingresos</vs-th>
                <vs-th>Total Egresos</vs-th>-->
                <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{data}">
                    <vs-tr :key="datos.cod_rol_pago" v-for="datos in data">
                        <vs-td v-if="datos.nombre">{{datos.nombre}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.descricion_factura">{{datos.descricion_factura.substring(0,3)}}-{{datos.descricion_factura.substring(3,6)}}-{{datos.descricion_factura.substring(6,15)}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.prov_nombre">{{datos.prov_nombre}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.valor_bien">{{datos.valor_bien |currency}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <!--<vs-td v-if="datos.total_ingreso">{{datos.total_ingreso}}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.total_egreso">{{datos.total_egreso}}</vs-td>
                        <vs-td v-else>0.00</vs-td>-->
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                            
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                v-if="editarrol"
                                @click.stop="editar(datos.id_activos_fijos)"
                            />
                            <feather-icon
                                
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="cursor-pointer"
                                v-if="eliminarroles && datos.observaciones==null"
                                @click.stop="eliminar(datos.id_activos_fijos)"
                            />
                            <!--
                            <feather-icon
                                v-else
                                icon="EyeIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click.stop="ver(datos.cod_rol_pago)"
                            />
                            
                            <feather-icon
                                icon="MailIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click.stop="generarPapeletas(datos.id_departamento,datos.fechrol)"
                            />
                            
                            <feather-icon
                                icon="ClipboardIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click="IngresosEgresos(datos.id_departamento,datos.fechrol)"
                            />
                            <feather-icon
                                v-if="datos.cont==1"
                                icon="SlidersIcon"
                                svgClasses="w-5 h-5 fill-current text-success"
                                class="cursor-pointer"
                                @click="verempleado(datos.cod_rol_pago,datos.cont)"
                            />
                            <feather-icon
                                v-else
                                icon="SlidersIcon"
                                svgClasses="w-5 h-5 fill-current text-primary"
                                class="cursor-pointer"
                                @click="verempleado(datos.cod_rol_pago,datos.cont)"
                            />
                            <feather-icon icon="CheckIcon"  v-if="datos.cont==1" svgClasses="w-5 h-5"/>-->
                            <!--@click="Contabiliidad(datos.cod_rol_pago,datos.fechrol)"-->
                            <!--@click="reporte_liquidacion(datos.id_importacion)"-->
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <vs-popup title="Tipo Activo Fijo" :class="'peque2'" :active.sync="modalTipo">
            <vx-card>
                    <div class="vx-row">
                        <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                            <div class="flex flex-wrap justify-between items-center mb-3">
                                            <!-- ITEMS PER PAGE -->
                                            <div
                                                class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                            ></div>
                                            <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                                <vs-input
                                                    class="mb-4 md:mb-0 mr-4"
                                                    v-model="buscar2"
                                                    @keyup="
                                                        listarTipo(1, buscar2)
                                                    "
                                                    v-bind:placeholder="i18nbuscar2"
                                                />
                                                <div>
                                                    <vs-button
                                                        class="btnx"
                                                        type="filled"
                                                        divider
                                                        @click="agregarTipo()"
                                                        >Agregar Nuevo</vs-button
                                                    >
                                                </div>
                                            </div>
                                            
                            </div>
                            <vs-table stripe :data="tipo_activo">
                                                <template slot="thead">
                                                    <vs-th>Código</vs-th>
                                                    <vs-th>Nombre</vs-th>
                                                    <vs-th>Opciones</vs-th>
                                                </template>
                                                <template slot-scope="{ data }">
                                                    <vs-tr
                                                    :key="datos.id_activo_fijo_tipo"
                                                    v-for="datos in data"
                                                    >
                                                        <vs-td
                                                            v-if="datos.codigo"
                                                            >{{
                                                                datos.codigo
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td
                                                            v-if="datos.nombre"
                                                            >{{
                                                                datos.nombre
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td class="whitespace-no-wrap">
                                                            <feather-icon
                                                            icon="EditIcon"
                                                            class="cursor-pointer"
                                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                            @click.stop="verTipo(datos.id_activo_fijo_tipo)"
                                                        />
                                                        <feather-icon
                                                            icon="TrashIcon"
                                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                            class="ml-2 cursor-pointer"
                                                            @click.stop="eliminarTipo(datos.id_activo_fijo_tipo)"
                                                        />
                                                        </vs-td>
                                                    </vs-tr>
                                                </template>
                            </vs-table>
                        </div>
                    </div>
                <vs-popup
                    :class="'peque3'"
                    title="Eliminar Tipo Activo"
                    :active.sync="modal_eliminar_tipo"
                >
                    <p>Desea eliminar Este reguistro</p>
                    <div class="vx-col w-full">
                    <br>
                    <vs-button color="warning" type="filled" @click="acceptAlertTipo(idrecupera_tipo)">BORRAR</vs-button>
                    </div>
                </vs-popup>
                <vs-popup
                    :class="'peque2'"
                    title="Agregar Tipo Activo"
                    :active.sync="modal_agregar_tipo"
                >
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            
                            <div class="vx-col sm:w-full w-full mb-6">
                                    <label for="" class="vs-input--label">Nombre:</label>
                                    <vs-input class="w-full" v-model="nombre_tipo"/>
                            </div>
                            <div v-show="errorTipo" v-if="!nombre_tipo">
                                <div
                                    v-for="err in errorNombreTipo"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <br>
                        <div class="vx-col w-full">
                        <vs-button color="success" type="filled" @click="guardarTipo()" v-if="!idrecupera_tipo">GUARDAR</vs-button>
                        <vs-button color="success" type="filled" @click="editarTipo()" v-else>GUARDAR</vs-button>
                        <vs-button color="warning" type="filled" @click="vaciarTipo()">BORRAR</vs-button>
                        <vs-button color="danger"  type="filled" @click="cancelarTipo()">CANCELAR</vs-button>
                        </div>
                    </div>
                </vs-popup>
            </vx-card>
        </vs-popup>
        <vs-popup title="Grupo Activo Fijo" :class="'peque2'" :active.sync="modalGrupo">
            <vx-card>
                    <div class="vx-row">
                        <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                            <div class="flex flex-wrap justify-between items-center mb-3">
                                            <!-- ITEMS PER PAGE -->
                                            <div
                                                class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                            ></div>
                                            <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                                <vs-input
                                                    class="mb-4 md:mb-0 mr-4"
                                                    v-model="buscar3"
                                                    @keyup="
                                                        listarGrupo(1, buscar3)
                                                    "
                                                    v-bind:placeholder="i18nbuscar3"
                                                />
                                                <div>
                                                    <vs-button
                                                        class="btnx"
                                                        type="filled"
                                                        divider
                                                        @click="agregarGrupo()"
                                                        >Agregar Nuevo</vs-button
                                                    >
                                                </div>
                                            </div>
                                            
                            </div>
                            <vs-table stripe :data="grupo_activo">
                                                <template slot="thead">
                                                    <vs-th>Código</vs-th>
                                                    <vs-th>Nombre</vs-th>
                                                    <vs-th>Años</vs-th>
                                                    <vs-th>Valor Residual</vs-th>
                                                    <vs-th>Porcentaje</vs-th>
                                                    <vs-th>Opciones</vs-th>
                                                </template>
                                                <template slot-scope="{ data }">
                                                    <vs-tr
                                                    :key="datos.id_activo_fijo_grupo"
                                                    v-for="datos in data"
                                                    >
                                                        <vs-td
                                                            v-if="datos.codigo"
                                                            >{{
                                                                datos.codigo
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td
                                                            v-if="datos.nombre"
                                                            >{{
                                                                datos.nombre
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td
                                                            v-if="datos.anios"
                                                            >{{
                                                                datos.anios
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td
                                                            v-if="datos.valor_residual"
                                                            >{{
                                                                datos.valor_residual
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td
                                                            v-if="datos.porcentaje"
                                                            >{{
                                                                datos.porcentaje
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td class="whitespace-no-wrap">
                                                            <feather-icon
                                                            icon="EditIcon"
                                                            class="cursor-pointer"
                                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                            @click.stop="verGrupo(datos.id_activo_fijo_grupo)"
                                                        />
                                                        <feather-icon
                                                            icon="TrashIcon"
                                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                            class="ml-2 cursor-pointer"
                                                            @click.stop="eliminarGrupo(datos.id_activo_fijo_grupo)"
                                                        />
                                                        </vs-td>
                                                    </vs-tr>
                                                </template>
                            </vs-table>
                        </div>
                </div>
                <vs-popup
                    :class="'peque3'"
                    title="Eliminar Grupo Activo"
                    :active.sync="modal_eliminar_grupo"
                >
                    <p>Desea eliminar Este reguistro</p>
                    <div class="vx-col w-full">
                    <br>
                    <vs-button color="warning" type="filled" @click="acceptAlertGrupo(idrecupera_grupo)">BORRAR</vs-button>
                    </div>
                </vs-popup>
                <vs-popup
                    :class="'peque2'"
                    title="Agregar Grupo Activo"
                    :active.sync="modal_agregar_grupo"
                >
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            
                            <div class="vx-col sm:w-1/4 w-full mb-6">
                                    <label for="" class="vs-input--label">Nombre:</label>
                                    <vs-input class="w-full" v-model="nombre_grupo"/>
                                    <div v-show="errorGrupo" v-if="!nombre_grupo">
                                        <div
                                            v-for="err in errorNombreGrupo"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                            </div>
                            <div class="vx-col sm:w-1/5 w-full mb-6">
                                    <label for="" class="vs-input--label">Años:</label>
                                    <vx-tooltip text="Tooltip Default">
                                        <vs-input-number v-model="anios" />
                                    </vx-tooltip>
                                    <div v-show="errorGrupo" v-if="!anios">
                                        <div
                                            v-for="err in errorAnio"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                            </div>
                            <div class="vx-col sm:w-1/4 w-full mb-6">
                                    <label for="" class="vs-input--label">Porcentaje Depreciacion:</label>
                                    <vs-input class="w-full" v-model="porcentaje"/>
                                    <div v-show="errorGrupo" v-if="!porcentaje">
                                        <div
                                            v-for="err in errorPorcentaje"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
                                    </div>
                            </div>
                            <div class="vx-col sm:w-1/4 w-full mb-6">
                                    <label for="" class="vs-input--label">Valor Residual:</label>
                                    <vx-input-group class>
                                        <vs-input class="w-full" v-model="valor_residual"/>
                                        <template slot="append">
                                            %
                                        </template>
                                    </vx-input-group>
                                    
                                    <div v-show="errorGrupo" v-if="!valor_residual">
                                        <div
                                            v-for="err in errorValor"
                                            :key="err"
                                            v-text="err"
                                            class="text-danger"
                                        ></div>
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
                            </div>
                        </div>
                        <br>
                        <div class="vx-col w-full">
                        <vs-button color="success" type="filled" @click="guardarGrupo()" v-if="!idrecupera_grupo">GUARDAR</vs-button>
                        <vs-button color="success" type="filled" @click="editarGrupo()" v-else>GUARDAR</vs-button>
                        <vs-button color="warning" type="filled" @click="vaciarGrupo()">BORRAR</vs-button>
                        <vs-button color="danger"  type="filled" @click="cancelarGrupo()">CANCELAR</vs-button>
                        </div>
                    </div>
                    <vs-popup
                        title="Plan Cuentas"
                        :class="'peque2'"
                        :active.sync="activePrompt3"
                    >
                        <div class="con-exemple-prompt">
                            <vs-input
                                class="mb-4 md:mb-0 mr-4 w-full"
                                v-model="buscar_plcta"
                                @keyup="listarPlancuenta(1, buscar_plcta)"
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
                </vs-popup>
            </vx-card>
        </vs-popup>
        <vs-popup title="Area Activo Fijo" :class="'peque2'" :active.sync="modalArea">
            <vx-card>
                    <div class="vx-row">
                        <div class="vx-col md:w-full w-full mb-6" id="ag-grid-demo">
                            <div class="flex flex-wrap justify-between items-center mb-3">
                                            <!-- ITEMS PER PAGE -->
                                            <div
                                                class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                            ></div>
                                            <div class="flex flex-wrap items-center justify-between ag-grid-table-actions-right">
                                                <vs-input
                                                    class="mb-4 md:mb-0 mr-4"
                                                    v-model="buscar4"
                                                    @keyup="
                                                        listarArea(1, buscar4)
                                                    "
                                                    v-bind:placeholder="i18nbuscar4"
                                                />
                                                <div>
                                                    <vs-button
                                                        class="btnx"
                                                        type="filled"
                                                        divider
                                                        @click="agregarArea()"
                                                        >Agregar Nuevo</vs-button
                                                    >
                                                </div>
                                            </div>
                                            
                            </div>
                            <vs-table stripe :data="area_activo">
                                                <template slot="thead">
                                                    <vs-th>Código</vs-th>
                                                    <vs-th>Nombre</vs-th>
                                                    <vs-th>Opciones</vs-th>
                                                </template>
                                                <template slot-scope="{ data }">
                                                    <vs-tr
                                                    :key="datos.id_activo_fijo_area"
                                                    v-for="datos in data"
                                                    >
                                                        <vs-td
                                                            v-if="datos.codigo"
                                                            >{{
                                                                datos.codigo
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td
                                                            v-if="datos.nombre"
                                                            >{{
                                                                datos.nombre
                                                            }}</vs-td
                                                        >
                                                        <vs-td v-else>-</vs-td>
                                                        <vs-td class="whitespace-no-wrap">
                                                            <feather-icon
                                                            icon="EditIcon"
                                                            class="cursor-pointer"
                                                            svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                            @click.stop="verArea(datos.id_activo_fijo_area)"
                                                        />
                                                        <feather-icon
                                                            icon="TrashIcon"
                                                            svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                            class="ml-2 cursor-pointer"
                                                            @click.stop="eliminarArea(datos.id_activo_fijo_area)"
                                                        />
                                                        </vs-td>
                                                    </vs-tr>
                                                </template>
                            </vs-table>
                        </div>
                </div>
                <vs-popup
                    :class="'peque3'"
                    title="Eliminar Area Activo"
                    :active.sync="modal_eliminar_area"
                >
                    <p>Desea eliminar Este reguistro</p>
                    <div class="vx-col w-full">
                    <br>
                    <vs-button color="warning" type="filled" @click="acceptAlertArea(idrecupera_area)">BORRAR</vs-button>
                    </div>
                </vs-popup>
                <vs-popup
                    :class="'peque2'"
                    title="Agregar Area Activo"
                    :active.sync="modal_agregar_area"
                >
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            
                            <div class="vx-col sm:w-full w-full mb-6">
                                    <label for="" class="vs-input--label">Nombre:</label>
                                    <vs-input class="w-full" v-model="nombre_area"/>
                            </div>
                            <div v-show="errorArea" v-if="!nombre_area">
                                <div
                                    v-for="err in errorNombreArea"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <br>
                        <div class="vx-col w-full">
                        <vs-button color="success" type="filled" @click="guardarArea()" v-if="!idrecupera_area">GUARDAR</vs-button>
                        <vs-button color="success" type="filled" @click="editarArea()" v-else>GUARDAR</vs-button>
                        <vs-button color="warning" type="filled" @click="vaciarArea()">BORRAR</vs-button>
                        <vs-button color="danger"  type="filled" @click="cancelarArea()">CANCELAR</vs-button>
                        </div>
                    </div>
                </vs-popup>
            </vx-card>
        </vs-popup>
    </div>
</template>
<script>
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { AgGridVue } from "ag-grid-vue";
import moment from "moment";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        flatPickr,
        FormWizard,
        TabContent
    },
    data(){
        return{
            buscar:"",
            criterio:"",
            cantidadp:"",
            i18nbuscar: this.$t("i18nbuscar"),
            contenido:[],
            //variables tipo
            modalTipo:false,
            buscar2:"",
            i18nbuscar2: this.$t("i18nbuscar"),
            tipo_activo:[],
            modal_eliminar_tipo:false,
            modal_agregar_tipo:false,
            idrecupera_tipo:"",
            nombre_tipo:"",
            errorTipo:0,
            errorNombreTipo:[],
            //variables grupo
            modalGrupo:false,
            buscar3:"",
            i18nbuscar3: this.$t("i18nbuscar"),
            grupo_activo:[],
            modal_eliminar_grupo:false,
            modal_agregar_grupo:false,
            idrecupera_grupo:"",
            cuenta_debito:"",
            cuenta_credito:"",
            idContable_debito:"",
            idContable_credito:"",
            nombre_grupo:"",
            anios:1,
            valor_residual:"",
            porcentaje:"",
            errorGrupo:0,
            errorNombreGrupo:[],
            errorAnio:[],
            errorValor:[],
            errorPorcentaje:[],
            //valores plan cuenta debito
            buscar_plcta:"",
            cuentaarray3: [],
            contenido_credito:[],
            activePrompt3:false,
            //valores plan cuenta credito
            buscar_credito:"",
            cuentaarray3_credito:[],
            activePrompt4:false,
            existe_proveedor:true,
            //variables area
            modalArea:false,
            buscar4:"",
            i18nbuscar4: this.$t("i18nbuscar"),
            area_activo:[],
            modal_eliminar_area:false,
            modal_agregar_area:false,
            idrecupera_area:"",
            nombre_area:"",
            errorArea:0,
            errorNombreArea:[],
        };
    },
    computed:{
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        crearrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Activos fijos"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            //console.log(res+"Rol");
            return res;
            
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[15].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Activos fijos"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarroles() {
        var res = 0;
                // if (this.usuario.id_rol == 1) {
                //     res = 1;
                // } else {
                //     res = this.$store.state.Roles[15].eliminar;
                // }
                if (this.usuario.id_rol == 1) {
                    res = 1;
                } else {
                    this.$store.state.Roles.forEach(el => {
                        if(el.nombre == "Activos fijos"){
                            res = el.eliminar;
                            return res;
                        }
                    });
                }
                return res;
        },
    },
    methods:{

        listar(page,buscar,cantidad,otro){
            let me = this;
            var url =
                "/api/activo_fijo/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.contenido = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        editar(id){
            ///activos-fijos/:id/editar
            this.$router.push(`/activos-fijos/registro/${id}/editar`);
        },
        eliminar(id) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Eliminar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            //console.log(parameters);
            axios.delete("/api/eliminar/activo_fijo/" + parameters).then(res=>{
                this.$vs.notify({
                    color: "success",
                    title: "Reguistro Eliminado  ",
                    text: "El Reguistro selecionado fue eliminado con exito"
                });
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al eliminar reguistro",
                    text: "Este reguistro esta siendo utilizado en otro modulo"
                });
            });
            
            this.listar(1, this.buscar, this.cantidadp);
        },
        //methodos Tipo
        abrirTipo(){
            this.modalTipo=true;
            this.listarTipo(1,this.buscar2);
        },
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
                    me.tipo_activo = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        agregarTipo(){
            this.idrecupera_tipo="";
            this.nombre_tipo="";
            this.modal_agregar_tipo=true;
        },
        verTipo($id){
            let me = this;
            var url =
                "/api/abrir/tipo_activo/" +
                $id;
                
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    //console.log(response.data[0].id_activo_fijo_tipo);
                    me.idrecupera_tipo=response.data[0].id_activo_fijo_tipo;
                    me.nombre_tipo=response.data[0].nombre;
                    me.modal_agregar_tipo=true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarTipo(cd) {
            this.modal_eliminar_tipo=true;
            this.idrecupera_tipo=cd;
        },
        acceptAlertTipo(parameters) {
            axios.delete("/api/eliminar/tipo_activo/" + parameters)
            .then(res =>{
                this.$vs.notify({
                color: "success",
                title: "Reguistro Eliminado  ",
                text: "El reguistro selecionado fue eliminado con exito"
                });
                this.modal_eliminar_tipo=false;
                this.idrecupera_tipo=null;
                this.listarTipo(1,this.buscar2);
            }).catch(err => {
                this.$vs.notify({
                color: "danger",
                title: "Error al eliminar",
                text: "Ha ocurrido un error al momento de eliminar reguistro"
                });
            });
            
        },
        guardarTipo(){
            if(this.validarTipo()){
                return;
            }
            axios.post("/api/guardar/tipo_activo", {
                    nombre:this.nombre_tipo,
                    ucrea:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                        //this.modalTipo = false;
                        this.modal_agregar_tipo=false;
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.listarTipo(1,this.buscar2);
                        this.vaciarTipo();
                    
                })
                .catch(err => {});
        },
        editarTipo(){
            if(this.validarTipo()){
                    return;
                }
            axios.put("/api/actualizar/tipo_activo", {
                    id: this.idrecupera_tipo,
                    nombre:this.nombre_tipo,
                    umodifica:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                    //this.modalTipo = false;
                    this.modal_agregar_tipo=false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarTipo(1,this.buscar2);
                    this.vaciarTipo();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarTipo(){
            this.idrecupera_tipo="";
            this.nombre_tipo="";
        },
        cancelarTipo() {
                
                (this.idrecupera_tipo = ""),
                (this.nombre_tipo = ""),
                (this.modal_eliminar_tipo = false),
                (this.modal_agregar_tipo = false);
        },
        validarTipo(){
            this.errorTipo=0;
            this.errorNombreTipo=[];
            if(!this.nombre_tipo){
                this.errorNombreTipo.push("Campo Obligatorio");
                this.errorTipo=1;
            }
            return this.errorTipo;
        },
        //methodos Grupo
        abrirGrupo(){
            this.modalGrupo=true;
            this.listarGrupo(1,this.buscar3);
        },
        listarGrupo(page,buscar2){
            let me = this;
            var url =
                "/api/grupo_activo/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.grupo_activo = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarPlancuenta(page, buscar){
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
        handleSelectedDebito(tr) {
                (this.cuenta_debito = `${tr.nomcta}`),
                (this.idContable_debito = `${tr.id_plan_cuentas}`),
                (this.activePrompt3 = false),
                (this.buscar_plcta="");
        },
        handleSelectedCredito(tr) {
                (this.cuenta_credito = `${tr.nomcta}`),
                (this.idContable_credito = `${tr.id_plan_cuentas}`),
                (this.activePrompt4 = false),
                (this.buscar_credito="");
        },
        agregarGrupo(){
            this.idrecupera_grupo="";
            this.nombre_grupo="";
            this.anios = 1;
            this.valor_residual = "";
            this.porcentaje = "";
            this.idContable_debito="";
            this.idContable_credito="";
            this.cuenta_debito="";
            this.cuenta_credito="";
            this.modal_agregar_grupo=true;
            this.listarPlancuenta(1,this.buscar_plcta);
        },
        verGrupo($id){
            let me = this;
            var url =
                "/api/abrir/grupo_activo/" +
                $id;
                
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.idrecupera_grupo=response.data[0].id_activo_fijo_grupo;
                    me.nombre_grupo=response.data[0].nombre;
                    me.anios=response.data[0].anios;
                    me.valor_residual=response.data[0].valor_residual;
                    me.porcentaje=response.data[0].porcentaje;
                    me.idContable_debito=response.data[0].id_plan_cuenta_debito;
                    me.idContable_credito=response.data[0].id_plan_cuenta_credito;
                    me.cuenta_debito=response.data[0].nombre_cuenta_debito;
                    me.cuenta_credito=response.data[0].nombre_cuenta_credito;
                    me.listarPlancuenta(1,me.buscar_plcta);
                    me.modal_agregar_grupo=true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarGrupo(cd) {
            this.modal_eliminar_grupo=true;
            this.idrecupera_grupo=cd;
        },
        acceptAlertGrupo(parameters) {
            axios.delete("/api/eliminar/grupo_activo/" + parameters)
            .then(res =>{
                this.$vs.notify({
                color: "success",
                title: "Reguistro Eliminado  ",
                text: "El reguistro selecionado fue eliminado con exito"
                });
                this.modal_eliminar_grupo=false;
                this.idrecupera_grupo=null;
                this.listarGrupo(1,this.buscar3);
            }).catch(err => {
                this.$vs.notify({
                color: "danger",
                title: "Error al eliminar",
                text: "Ha ocurrido un error al momento de eliminar reguistro"
                });
            });
            
        },
        guardarGrupo(){
            if(this.validarGrupo()){
                return;
            }
            axios.post("/api/guardar/grupo_activo", {
                    nombre:this.nombre_grupo,
                    anios:this.anios,
                    valor_residual:this.valor_residual,
                    porcentaje:this.porcentaje,
                    ucrea:this.usuario.id,
                    id_empresa:this.usuario.id_empresa,
                    id_plan_cuenta_debito:this.idContable_debito,
                    id_plan_cuenta_credito:this.idContable_credito
                })
                .then(res => {
                        //this.modalGrupo = false;
                        this.modal_agregar_grupo=false;
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.listarGrupo(1,this.buscar3);
                        this.vaciarGrupo();
                    
                })
                .catch(err => {});
        },
        editarGrupo(){
            if(this.validarGrupo()){
                    return;
                }
            axios.put("/api/actualizar/grupo_activo", {
                    id: this.idrecupera_grupo,
                    nombre:this.nombre_grupo,
                    anios:this.anios,
                    valor_residual:this.valor_residual,
                    porcentaje:this.porcentaje,
                    umodifica:this.usuario.id,
                    id_empresa:this.usuario.id_empresa,
                    id_plan_cuenta_debito:this.idContable_debito,
                    id_plan_cuenta_credito:this.idContable_credito
                })
                .then(res => {
                    //this.modalGrupo = false;
                    this.modal_agregar_grupo=false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarGrupo(1,this.buscar3);
                    this.vaciarGrupo();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarGrupo(){
            this.idrecupera_grupo="";
            this.nombre_grupo="";
            this.anios = 1;
            this.valor_residual = "";
            this.porcentaje = "";
        },
        cancelarGrupo() {
                
                (this.idrecupera_grupo = ""),
                (this.nombre_grupo = ""),
                (this.anios = ""),
                (this.valor_residual = ""),
                (this.porcentaje = ""),
                (this.modal_eliminar_grupo = false),
                (this.modal_agregar_grupo = false);
        },
        validarGrupo(){
            this.errorGrupo=0;
            this.errorNombreGrupo=[];
            this.errorAnio=[];
            this.errorValor=[];
            this.errorPorcentaje=[];
            if(!this.nombre_grupo){
                this.errorNombreGrupo.push("Campo Obligatorio");
                this.errorGrupo=1;
            }
            if(!this.anios){
                this.errorAnio.push("Campo Obligatorio");
                this.errorGrupo=1;
            }
            if(!this.valor_residual){
                this.errorValor.push("Campo Obligatorio");
                this.errorGrupo=1;
            }
            if(!this.porcentaje){
                this.errorPorcentaje.push("Campo Obligatorio");
                this.errorGrupo=1;
            }
            return this.errorGrupo;
        },
        //methodos Area
        abrirArea(){
            this.modalArea=true;
            this.listarArea(1,this.buscar4);
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
                    me.area_activo = respuesta.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        agregarArea(){
            this.idrecupera_area="";
            this.nombre_area="";
            this.modal_agregar_area=true;
        },
        verArea($id){
            let me = this;
            var url =
                "/api/abrir/area_activo/" +
                $id;
                
            axios
                .get(url)
                .then(function(response) {
                    var respuesta = response.data;
                    me.idrecupera_area=response.data[0].id_activo_fijo_area;
                    me.nombre_area=response.data[0].nombre;
                    me.modal_agregar_area=true;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarArea(cd) {
            this.modal_eliminar_area=true;
            this.idrecupera_area=cd;
        },
        acceptAlertArea(parameters) {
            axios.delete("/api/eliminar/area_activo/" + parameters)
            .then(res =>{
                this.$vs.notify({
                color: "success",
                title: "Reguistro Eliminado  ",
                text: "El reguistro selecionado fue eliminado con exito"
                });
                this.modal_eliminar_area=false;
                this.idrecupera_area=null;
                this.listarArea(1,this.buscar4);
            }).catch(err => {
                this.$vs.notify({
                color: "danger",
                title: "Error al eliminar",
                text: "Ha ocurrido un error al momento de eliminar reguistro"
                });
            });
            
        },
        guardarArea(){
            if(this.validarArea()){
                return;
            }
            axios.post("/api/guardar/area_activo", {
                    nombre:this.nombre_area,
                    ucrea:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                        //this.modalArea = false;
                        this.modal_agregar_area=false;
                        this.$vs.notify({
                            title: "Registro Guardado",
                            text: "Registro Guardado exitosamente",
                            color: "success"
                        });
                        this.listarArea(1,this.buscar4);
                        this.vaciarArea();
                    
                })
                .catch(err => {});
        },
        editarArea(){
            if(this.validarArea()){
                    return;
                }
            axios.put("/api/actualizar/area_activo", {
                    id: this.idrecupera_area,
                    nombre:this.nombre_area,
                    umodifica:this.usuario.id,
                    id_empresa:this.usuario.id_empresa
                })
                .then(res => {
                    //this.modalArea = false;
                    this.modal_agregar_area=false;
                    this.$vs.notify({
                        title: "Registro Editado",
                        text: "Registro Editado exitosamente",
                        color: "success"
                    });
                    this.listarArea(1,this.buscar4);
                    this.vaciarArea();
                })
                .catch(err => {
                    this.$vs.notify({
                        title: "Error al Editar",
                        text: "Revise sus datos antes de editar",
                        color: "danger"
                    });
                });
        },
        vaciarArea(){
            this.idrecupera_area="";
            this.nombre_area="";
        },
        cancelarArea() {
                
                (this.idrecupera_area = ""),
                (this.nombre_area = ""),
                (this.modal_eliminar_area = false),
                (this.modal_agregar_area = false);
        },
        validarArea(){
            this.errorArea=0;
            this.errorNombreArea=[];
            if(!this.nombre_area){
                this.errorNombreArea.push("Campo Obligatorio");
                this.errorArea=1;
            }
            return this.errorArea;
        },
    },
    mounted(){
        this.listar(1,this.buscar,"","");
    }
}
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
.vs-popup {
  width: 800px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.peque2 .vs-popup {
    width: 900px !important;
}
.peque3 .vs-popup {
    width: 400px !important;
}
.peque4 .vs-popup {
    width: 1080px !important;
}
</style>