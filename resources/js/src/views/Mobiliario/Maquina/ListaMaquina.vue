<template>
    <div id="ag-grid-demo">
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <!-- ITEMS PER PAGE -->
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <!--botón de herramientas-->
                    <div class="dropdown-button-container mr-3">
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="settings"
                                style="border-radius: 5px;"
                            ></vs-button>
                            <vs-dropdown-menu style="width: 13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirTipoActivo()"
                                    >Tipo de Activo</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirMarca()"
                                    >Marca</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirModelo()"
                                    >Modelo</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirColor()"
                                    >Color</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirMaterial()"
                                    >Material</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirDimension()"
                                    >Dimensi&oacute;n</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirConservacion()"
                                    >Estado de Conservaci&oacute;n</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirMantenimiento()"
                                    >Mantenimiento</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirUbicacionGeneral()"
                                    >Ubicaci&oacute;n General</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirUbicacionEspecifica()"
                                    >Ubicaci&oacute;n Especifica</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirCustodio()"
                                    >Custodio</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirIdentificador()"
                                    >Identificador</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirTipo()"
                                    >Tipo</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <!--Fin de bóton de herramientas-->
                    <div class="dropdown-button-container">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/mobiliario/maquina/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width: 13em;">
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="importar = true"
                                    >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="exportar = true"
                                    >Exportar registros</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe max-items=25 pagination :data="contenido">
                <template slot="thead">
                    <vs-th class="text-center">Código Bien</vs-th>
                    <vs-th class="text-center">Descripción</vs-th>
                    <vs-th class="text-center">Código Anterior</vs-th>
                    <vs-th class="text-center">Custodio</vs-th>
                    <vs-th class="text-center">Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr
                        :key="datos.id_maquina"
                        v-for="datos in data"
                        class="text-center"
                    >
                        <vs-td v-if="datos.codigo_identificacion_maquina">{{
                            datos.codigo_identificacion_maquina
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.descripcion_actualizada_maquina">{{
                            datos.descripcion_actualizada_maquina
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.codigo_anterior_maquina">{{
                            datos.codigo_anterior_maquina
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.nombre_custodio">{{
                            datos.nombre_custodio
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                
                                icon="EditIcon"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                class="cursor-pointer"
                                @click.stop="editar(datos.id_maquina)"
                            />
                            <feather-icon
                                
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer"
                                @click.stop="eliminarVehiculo(datos.id_maquina)"
                            />
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <!--========================================================Modales================================================-->
        <vs-popup :title="titulomodal" :active.sync="modal">
            <div class="vx-row">
                <!--=================================== Modal Principal Tipo Activo =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 1"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listartipoactivo(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('tipoactivo', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidotipoactivo">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_tipo_activo_mobiliario"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.descripcion_tipo_activo
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'tipoactivo',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_tipo_activo_mobiliario;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Marca =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 2"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarmarca(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('marca', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidomarca">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_marca"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.nombre_marca
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'marca',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_marca;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Modelo =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 3"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarmodelo(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('modelo', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidomodelo">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_modelo"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.nombre_modelo
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'modelo',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_modelo;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--=================================== Modal Principal Color =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 5"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarcolor(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('color', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidocolor">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_color"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.nombre_color
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'color',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_color;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Material =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 6"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarmaterial(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('material', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidomaterial">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_material"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.descripcion_material
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'material',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_material;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Conservacion =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 7"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarconservacion(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('conservacion', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidoconservacion">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_conservacion"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.descripcion_conservacion
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'conservacion',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_conservacion;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Mantenimiento =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 8"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarmantenimiento(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('mantenimiento', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidomantenimiento">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_mantenimiento"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.descripcion_mantenimiento
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'mantenimiento',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_mantenimiento;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Ubicacion General =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 9"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarubicaciongeneral(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('ubicaciongeneral', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidoubicaciongeneral">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_ubicacion_general"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.descripcion_ubicacion_general
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'ubicaciongeneral',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_ubicacion_general;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Ubicacion Especifica =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 10"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarubicacionespecifica(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('ubicacionespecifica', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidoubicacionespecifica">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_ubicacion_especifica"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.descripcion_ubicacion_especifica
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'ubicacionespecifica',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_ubicacion_especifica;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Custodio =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 11"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listarcustodio(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('custodio', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidocustodio">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>C&eacute;dula</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_custodio"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.cedula_custodio
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.nombre_custodio
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'custodio',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_custodio;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
                <!--=================================== Modal Principal Dimension =======================================-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 12"
                >
                    <vx-card>
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
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar1"
                                            @keyup="
                                                listardimension(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('dimension', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidodimension">
                                    <template slot="thead">
                                        <vs-th>Nro.</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_dimension"
                                            v-for="(datos, index) in data"
                                        >
                                            <vs-td>{{
                                                index+1
                                            }}</vs-td>
                                            <vs-td>{{
                                                datos.descripcion_dimension
                                            }}</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'dimension',
                                                            'editar',
                                                            datos
                                                        )
                                                    "
                                                />
                                                <feather-icon
                                                    icon="TrashIcon"
                                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                    class="ml-2 cursor-pointer"
                                                    @click.stop="
                                                        ideliminar =
                                                            datos.id_dimension;
                                                        eliminar = true;
                                                        tipoaccionmodal = 1;
                                                    "
                                                />
                                            </vs-td>
                                        </vs-tr>
                                    </template>
                                </vs-table>
                            </div>
                        </div>
                    </vx-card>
                </div>
                <!--===========================================Fin=========================================================-->
            </div>
            <vs-popup :title="titulo" :active.sync="agregartipoactivo">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripcion:"
                                v-model="descripcion_tipoactivo"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardartipoactivo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editartipoactivo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregartipoactivo = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarmarca">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="nombre_marca"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarmarca()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarmarca()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarmarca = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarmodelo">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="nombre_modelo"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarmodelo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarmodelo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarmodelo = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarcolor">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="nombre_color"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarcolor()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarcolor()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarcolor = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarmaterial">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripcion:"
                                v-model="descripcion_material"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarmaterial()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarmaterial()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarmaterial = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarconservacion">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripcion:"
                                v-model="descripcion_conservacion"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarconservacion()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarconservacion()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarconservacion = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarmantenimiento">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripcion:"
                                v-model="descripcion_mantenimiento"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarmantenimiento()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarmantenimiento()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarmantenimiento = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarubicaciongeneral">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripcion:"
                                v-model="descripcion_ubicaciongeneral"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarubicaciongeneral()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarubicaciongeneral()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarubicaciongeneral = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarubicacionespecifica">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripcion:"
                                v-model="descripcion_ubicacionespecifica"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarubicacionespecifica()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarubicacionespecifica()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarubicacionespecifica = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregarcustodio">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Cédula:"
                                v-model="cedula_custodio"
                            />
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="nombre_custodio"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarcustodio()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editarcustodio()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregarcustodio = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup :title="titulo" :active.sync="agregardimension">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripcion:"
                                v-model="descripcion_dimension"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="border"
                                v-if="tipoaccionmodal == 1"
                                @click="guardardimension()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="border"
                                v-else
                                @click="editardimension()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="border"
                                @click="agregardimension = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <vs-popup
                title="eliminar registro"
                class="peque"
                :active.sync="eliminar"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div
                                class="vx-col sm:w-full w-full mb-6 text-center"
                            >
                                <label class="text-center">
                                    Esta seguro que desea eliminar este registro
                                    <br />
                                </label>
                            </div>
                            <div class="vx-col sm:w-full w-full text-center">
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-if="tipoaccion == 1"
                                    @click="eliminartipoactivo(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 2"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarmarca(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 3"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarmodelo(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 5"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarcolor(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 6"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarmaterial(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 7"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarconservacion(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 8"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarmantenimiento(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 9"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarubicaciongeneral(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 10"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarubicacionespecifica(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 11"
                                    color="danger"
                                    type="filled"
                                    @click="eliminarcustodio(ideliminar)"
                                    >Eliminar</vs-button
                                >
                                <vs-button
                                    v-else-if="tipoaccion == 12"
                                    color="danger"
                                    type="filled"
                                    @click="eliminardimension(ideliminar)"
                                    >Eliminar</vs-button
                                >

                                <vs-button
                                    color="primary"
                                    type="filled"
                                    @click="eliminar = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
        </vs-popup>
        
    </div>
    
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import vSelect from "vue-select";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        ImportExcel,
        vSelect
    },
    data() {
        return {
            contenido: [],
            modal: false,
            eliminar: false,
            agregartipoactivo: false,
            agregarmarca: false,
            agregarmodelo: false,
            agregarcolor: false,
            agregarmaterial: false,
            agregarconservacion: false,
            agregarmantenimiento: false,
            agregarubicaciongeneral: false,
            agregarubicacionespecifica: false,
            agregarcustodio: false,
            agregardimension: false,
            titulomodal: "",
            //segun esto es el modal y el valor corresponde al orden en el menu
            tipoaccion: 1,
            contenidotipoactivo: [],
            contenidomarca: [],
            contenidomodelo: [],
            contenidocolor: [],
            contenidomaterial: [],
            contenidoconservacion: [],
            contenidomantenimiento: [],
            contenidoubicaciongeneral: [],
            contenidoubicacionespecifica: [],
            contenidocustodio: [],
            contenidodimension: [],
            i18nbuscar: this.$t("i18nbuscar"),
            buscar: "",
            buscar1: "",
            titulo: "",
            tipoaccionmodal: 1,
            descripcion_tipoactivo: "",
            nombre_marca: "",
            nombre_modelo: "",
            nombre_color: "",
            descripcion_material: "",
            descripcion_conservacion: "",
            descripcion_mantenimiento: "",
            descripcion_ubicaciongeneral: "",
            descripcion_ubicacionespecifica: "",
            cedula_custodio: "",
            nombre_custodio: "",
            descripcion_dimension: "",
            ideliminar: null,
            id: null
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        }
    },
    methods: {
        listar(page1, buscar1) {
            var url =
                "/api/maquinamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                console.log("res.data");
                console.log(respuesta.recupera);
                this.contenido = respuesta.recupera;
            });
        },
        editar(id) {
            this.$router.push(`/mobiliario/maquina/${id}/editar`);
        },
        eliminarVehiculo(id) {
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
        acceptAlert(id) {
            axios.delete("/api/eliminarmaquinamobiliario/" + id);
            this.$vs.notify({
                color: "danger",
                title: "Maquinaria Eliminada  ",
                text: "La maquinaria selecionado fue eliminada con exito"
            });
            this.listar(this.buscar);
            
        },
        abrirTipoActivo() {
            this.tipoaccion = 1;
            this.modal = true;
            this.titulomodal = "Tipo Activo";
        },
        abrirMarca() {
            this.tipoaccion = 2;
            this.modal = true;
            this.titulomodal = "Marca";
        },
        abrirModelo() {
            this.tipoaccion = 3;
            this.modal = true;
            this.titulomodal = "Modelo";
        },
        abrirColor() {
            this.tipoaccion = 5;
            this.modal = true;
            this.titulomodal = "Color";
        },
        abrirMaterial() {
            this.tipoaccion = 6;
            this.modal = true;
            this.titulomodal = "Material";
        },
        abrirConservacion() {
            this.tipoaccion = 7;
            this.modal = true;
            this.titulomodal = "Estado de Conservación";
        },
        abrirMantenimiento() {
            this.tipoaccion = 8;
            this.modal = true;
            this.titulomodal = "Mantenimiento";
        },
        abrirUbicacionGeneral() {
            this.tipoaccion = 9;
            this.modal = true;
            this.titulomodal = "Ubicación General";
        },
        abrirUbicacionEspecifica() {
            this.tipoaccion = 10;
            this.modal = true;
            this.titulomodal = "Ubicación Especifica";
        },
        abrirCustodio() {
            this.tipoaccion = 11;
            this.modal = true;
            this.titulomodal = "Custodio";
        },
        abrirDimension() {
            this.tipoaccion = 12;
            this.modal = true;
            this.titulomodal = "Dimensión";
        },
        agregar(tipo, accion, dato){
            console.log("entro aqui");
            console.log(tipo);
            switch (tipo) {
                case "tipoactivo": {
                    switch (accion) {
                        case "guardar": {
                            this.agregartipoactivo = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Tipo Activo";
                            this.descripcion_tipoactivo = "";
                            break;
                        }
                        case "editar": {
                            this.agregartipoactivo = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Tipo Activo";
                            this.id = dato.id_tipo_activo_mobiliario;
                            this.descripcion_tipoactivo = dato.descripcion_tipo_activo;
                            break;
                        }
                    }
                    break;
                }
                case "marca": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarmarca = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Marca";
                            this.nombre_marca = "";
                            break;
                        }
                        case "editar": {
                            this.agregarmarca = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Marca";
                            this.id = dato.id_marca;
                            this.nombre_marca = dato.nombre_marca;
                            break;
                        }
                    }
                    break;
                }
                case "modelo": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarmodelo = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Modelo";
                            this.nombre_modelo = "";
                            break;
                        }
                        case "editar": {
                            this.agregarmodelo = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Modelo";
                            this.id = dato.id_modelo;
                            this.nombre_modelo = dato.nombre_modelo;
                            break;
                        }
                    }
                    break;
                }
                case "color": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarcolor = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Color";
                            this.nombre_color = "";
                            break;
                        }
                        case "editar": {
                            this.agregarcolor = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Color";
                            this.id = dato.id_color;
                            this.nombre_color = dato.nombre_color;
                            break;
                        }
                    }
                    break;
                }
                case "material": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarmaterial = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Material";
                            this.descripcion_material = "";
                            break;
                        }
                        case "editar": {
                            this.agregarmaterial = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Material";
                            this.id = dato.id_material;
                            this.descripcion_material = dato.descripcion_material;
                            break;
                        }
                    }
                    break;
                }
                case "conservacion": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarconservacion = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Estado de Conservación";
                            this.descripcion_conservacion = "";
                            break;
                        }
                        case "editar": {
                            this.agregarconservacion = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Estado de Conservación";
                            this.id = dato.id_conservacion;
                            this.descripcion_conservacion = dato.descripcion_conservacion;
                            break;
                        }
                    }
                    break;
                }
                case "mantenimiento": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarmantenimiento = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Mantenimiento";
                            this.descripcion_mantenimiento = "";
                            break;
                        }
                        case "editar": {
                            this.agregarmantenimiento = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Mantenimiento";
                            this.id = dato.id_mantenimiento;
                            this.descripcion_mantenimiento = dato.descripcion_mantenimiento;
                            break;
                        }
                    }
                    break;
                }
                case "ubicaciongeneral": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarubicaciongeneral = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Ubicación General";
                            this.descripcion_ubicaciongeneral = "";
                            break;
                        }
                        case "editar": {
                            this.agregarubicaciongeneral = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Ubicación General";
                            this.id = dato.id_ubicacion_general;
                            this.descripcion_ubicaciongeneral = dato.descripcion_ubicacion_general;
                            break;
                        }
                    }
                    break;
                }
                case "ubicacionespecifica": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarubicacionespecifica = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Ubicación Especifica";
                            this.descripcion_ubicacionespecifica = "";
                            break;
                        }
                        case "editar": {
                            this.agregarubicacionespecifica = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Ubicación Especifica";
                            this.id = dato.id_ubicacion_especifica;
                            this.descripcion_ubicacionespecifica = dato.descripcion_ubicacion_especifica;
                            break;
                        }
                    }
                    break;
                }
                case "custodio": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarcustodio = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Custodio";
                            this.cedula_custodio = "";
                            this.nombre_custodio = "";
                            break;
                        }
                        case "editar": {
                            this.agregarcustodio = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Custodio";
                            this.id = dato.id_custodio;
                            this.nombre_custodio = dato.nombre_custodio;
                            break;
                        }
                    }
                    break;
                }
                case "dimension": {
                    switch (accion) {
                        case "guardar": {
                            this.agregardimension = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Dimensión";
                            this.descripcion_dimension = "";
                            break;
                        }
                        case "editar": {
                            this.agregardimension = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Dimensión";
                            this.id = dato.id_dimension;
                            this.descripcion_dimension = dato.descripcion_dimension;
                            break;
                        }
                    }
                    break;
                }
            }
        },
        listartipoactivo(page1, buscar1) {
            var url =
                "/api/tipoactivomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidotipoactivo = respuesta.recupera;
            });
        },
        guardartipoactivo() {
            axios
                .post("/api/guardartipoactivomobiliario", {
                    descripcion_tipoactivo: this.descripcion_tipoactivo,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregartipoactivo = false;
                    this.listartipoactivo(1, this.buscar1);
                })
                .catch(err => {});
        },
        editartipoactivo() {
            axios
                .post("/api/editartipoactivomobiliario", {
                    id: this.id,
                    descripcion_tipoactivo: this.descripcion_tipoactivo
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregartipoactivo = false;
                    this.listartipoactivo(1, this.buscar1);
                });
        },
        eliminartipoactivo(id) {
            axios.delete("/api/eliminartipoactivomobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listartipoactivo(1, this.buscar1);
        },
        listarmarca(page1, buscar1) {
            var url =
                "/api/marcamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomarca = respuesta.recupera;
            });
        },
        guardarmarca() {
            axios
                .post("/api/guardarmarcamobiliario", {
                    nombre_marca: this.nombre_marca,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmarca = false;
                    this.listarmarca(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarmarca() {
            axios
                .post("/api/editarmarcamobiliario", {
                    id: this.id,
                    nombre_marca: this.nombre_marca
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmarca = false;
                    this.listarmarca(1, this.buscar1);
                });
        },
        eliminarmarca(id) {
            axios.delete("/api/eliminarmarcamobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarmarca(1, this.buscar1);
        },
        listarmodelo(page1, buscar1) {
            var url =
                "/api/modelomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomodelo = respuesta.recupera;
            });
        },
        guardarmodelo() {
            axios
                .post("/api/guardarmodelomobiliario", {
                    nombre_modelo: this.nombre_modelo,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmodelo = false;
                    this.listarmodelo(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarmodelo() {
            axios
                .post("/api/editarmodelomobiliario", {
                    id: this.id,
                    nombre_modelo: this.nombre_modelo
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmodelo = false;
                    this.listarmodelo(1, this.buscar1);
                });
        },
        eliminarmodelo(id) {
            axios.delete("/api/eliminarmodelomobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarmodelo(1, this.buscar1);
        },
        listarcolor(page1, buscar1) {
            var url =
                "/api/colormobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocolor = respuesta.recupera;
            });
        },
        guardarcolor() {
            axios
                .post("/api/guardarcolormobiliario", {
                    nombre_color: this.nombre_color,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarcolor = false;
                    this.listarcolor(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarcolor() {
            axios
                .post("/api/editarcolormobiliario", {
                    id: this.id,
                    nombre_color: this.nombre_color
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarcolor = false;
                    this.listarcolor(1, this.buscar1);
                });
        },
        eliminarcolor(id) {
            axios.delete("/api/eliminarcolormobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarcolor(1, this.buscar1);
        },
        listarmaterial(page1, buscar1) {
            var url =
                "/api/materialmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomaterial = respuesta.recupera;
            });
        },
        guardarmaterial() {
            axios
                .post("/api/guardarmaterialmobiliario", {
                    descripcion_material: this.descripcion_material,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmaterial = false;
                    this.listarmaterial(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarmaterial() {
            axios
                .post("/api/editarmaterialmobiliario", {
                    id: this.id,
                    descripcion_material: this.descripcion_material
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmaterial = false;
                    this.listarmaterial(1, this.buscar1);
                });
        },
        eliminarmaterial(id) {
            axios.delete("/api/eliminarmaterialmobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarmaterial(1, this.buscar1);
        },
        listarconservacion(page1, buscar1) {
            var url =
                "/api/conservacionmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoconservacion = respuesta.recupera;
            });
        },
        guardarconservacion() {
            axios
                .post("/api/guardarconservacionmobiliario", {
                    descripcion_conservacion: this.descripcion_conservacion,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarconservacion = false;
                    this.listarconservacion(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarconservacion() {
            axios
                .post("/api/editarconservacionmobiliario", {
                    id: this.id,
                    descripcion_conservacion: this.descripcion_conservacion
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarconservacion = false;
                    this.listarconservacion(1, this.buscar1);
                });
        },
        eliminarconservacion(id) {
            axios.delete("/api/eliminarconservacionmobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarconservacion(1, this.buscar1);
        },
        listarmantenimiento(page1, buscar1) {
            var url =
                "/api/mantenimientomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomantenimiento = respuesta.recupera;
            });
        },
        guardarmantenimiento() {
            axios
                .post("/api/guardarmantenimientomobiliario", {
                    descripcion_mantenimiento: this.descripcion_mantenimiento,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmantenimiento = false;
                    this.listarmantenimiento(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarmantenimiento() {
            axios
                .post("/api/editarmantenimientomobiliario", {
                    id: this.id,
                    descripcion_mantenimiento: this.descripcion_mantenimiento
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmantenimiento = false;
                    this.listarmantenimiento(1, this.buscar1);
                });
        },
        eliminarmantenimiento(id) {
            axios.delete("/api/eliminarmantenimientomobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarmantenimiento(1, this.buscar1);
        },
        listarubicaciongeneral(page1, buscar1) {
            var url =
                "/api/ubicaciongeneralmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoubicaciongeneral = respuesta.recupera;
            });
        },
        guardarubicaciongeneral() {
            axios
                .post("/api/guardarubicaciongeneralmobiliario", {
                    descripcion_ubicaciongeneral: this.descripcion_ubicaciongeneral,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarubicaciongeneral = false;
                    this.listarubicaciongeneral(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarubicaciongeneral() {
            axios
                .post("/api/editarubicaciongeneralmobiliario", {
                    id: this.id,
                    descripcion_ubicaciongeneral: this.descripcion_ubicaciongeneral
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarubicaciongeneral = false;
                    this.listarubicaciongeneral(1, this.buscar1);
                });
        },
        eliminarubicaciongeneral(id) {
            axios.delete("/api/eliminarubicaciongeneralmobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarubicaciongeneral(1, this.buscar1);
        },
        listarubicacionespecifica(page1, buscar1) {
            var url =
                "/api/ubicacionespecificamobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidoubicacionespecifica = respuesta.recupera;
            });
        },
        guardarubicacionespecifica() {
            axios
                .post("/api/guardarubicacionespecificamobiliario", {
                    descripcion_ubicacionespecifica: this.descripcion_ubicacionespecifica,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarubicacionespecifica = false;
                    this.listarubicacionespecifica(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarubicacionespecifica() {
            axios
                .post("/api/editarubicacionespecificamobiliario", {
                    id: this.id,
                    descripcion_ubicacionespecifica: this.descripcion_ubicacionespecifica
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarubicacionespecifica = false;
                    this.listarubicacionespecifica(1, this.buscar1);
                });
        },
        eliminarubicacionespecifica(id) {
            axios.delete("/api/eliminarubicacionespecificamobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarubicacionespecifica(1, this.buscar1);
        },
        listarcustodio(page1, buscar1) {
            var url =
                "/api/custodiomobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidocustodio = respuesta.recupera;
            });
        },
        guardarcustodio() {
            axios
                .post("/api/guardarcustodiomobiliario", {
                    cedula_custodio: this.cedula_custodio,
                    nombre_custodio: this.nombre_custodio,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarcustodio = false;
                    this.listarcustodio(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarcustodio() {
            axios
                .post("/api/editarcustodiomobiliario", {
                    id: this.id,
                    cedula_custodio: this.cedula_custodio,
                    nombre_custodio: this.nombre_custodio
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarcustodio = false;
                    this.listarcustodio(1, this.buscar1);
                });
        },
        eliminarcustodio(id) {
            axios.delete("/api/eliminarcustodiomobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarcustodio(1, this.buscar1);
        },
        listardimension(page1, buscar1) {
            var url =
                "/api/dimensionmobiliario/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidodimension = respuesta.recupera;
            });
        },
        guardardimension() {
            axios
                .post("/api/guardardimensionmobiliario", {
                    descripcion_dimension: this.descripcion_dimension,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregardimension = false;
                    this.listardimension(1, this.buscar1);
                })
                .catch(err => {});
        },
        editardimension() {
            axios
                .post("/api/editardimensionmobiliario", {
                    id: this.id,
                    descripcion_dimension: this.descripcion_dimension
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregardimension = false;
                    this.listardimension(1, this.buscar1);
                });
        },
        eliminardimension(id) {
            axios.delete("/api/eliminardimensionmobiliario/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listardimension(1, this.buscar1);
        },
    },
    mounted() {
        this.listar(1, "");
        this.listartipoactivo(1, this.buscar1);
        this.listarmarca(1, this.buscar1);
        this.listarmodelo(1, this.buscar1);
        this.listarcolor(1, this.buscar1);
        this.listarmaterial(1, this.buscar1);
        this.listarconservacion(1, this.buscar1);
        this.listarmantenimiento(1, this.buscar1);
        this.listarubicaciongeneral(1, this.buscar1);
        this.listarubicacionespecifica(1, this.buscar1);
        this.listarcustodio(1, this.buscar1);
        this.listardimension(1, this.buscar1);
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
.vs-popup {
    width: 1060px !important;
}
.peque .vs-popup {
    width: 600px !important;
}
.peque1 .vs-popup {
    width: 500px !important;
}
.peque2 .vs-popup {
    width: 500px !important;
}
input[type="”file”"]#nuestroinput {
    width: 0.1px;
    height: 0.1px;
    opacity: 0;
    overflow: hidden;
    position: absolute;
    z-index: -1;
}
label[for=" nuestroinput"] {
    font-size: 14px;
    font-weight: 600;
    color: #fff;
    background-color: #106ba0;
    display: inline-block;
    transition: all 0.5s;
    cursor: pointer;
    padding: 15px 40px !important;
    text-transform: uppercase;
    width: fit-content;
    text-align: center;
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
    height: 225px;
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
    height: 300px;
    height: 300px;
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
.centimg {
    height: 225px;
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
.demo-alignment > * {
    margin-right: 1.5rem;
    margin-top: 0.8rem;
}
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
</style>
