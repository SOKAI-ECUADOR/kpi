<template>
    <div id="ag-grid-demo">
        {{ traerProducto }}
        <vx-card>
            <div class="flex flex-wrap justify-between items-center mb-3">
                <div class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"></div>
                <div
                    class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                >
                    <vs-input
                        class="mb-4 md:mb-0 mr-4"
                        v-model="buscar"
                        @keyup="listar(1, buscar, cantidadp)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <div class="dropdown-button-container mr-3">
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="settings"
                                style="border-radius: 5px"
                            ></vs-button>
                            <vs-dropdown-menu style="width: 13em">
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirlinea()"
                                    >Linea de producto</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirtipo()"
                                    >Tipo de producto</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirmarca()"
                                    >Marca</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirmodelo()"
                                    >Modelo</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirpresentacion()"
                                    >Presentación</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/inventario/catalogo/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width: 13em">
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
                                <vs-dropdown-item
                                    class="text-center"
                                    @click="abrirModalReporteProd()"
                                    >Reporte Producto</vs-dropdown-item
                                >
                                <!--   <vs-dropdown-item class="text-center" divider
                                    >Generar PDF</vs-dropdown-item
                                >-->
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe max-items="25" pagination :data="contenido">
                <template slot="thead">
                    <vs-th style="width: 5%">Cod.</vs-th>
                    <vs-th style="width: 25%">Nombre</vs-th>
                    <vs-th class="text-center" style="width: 31%"
                        >Descripcion</vs-th
                    >
                    <vs-th class="text-center" style="width: 10%">Marca</vs-th>
                    <vs-th class="text-center" style="width: 10%">Modelo</vs-th>
                    <vs-th class="text-center" style="width: 7%">Costo</vs-th>
                    <vs-th class="text-center" style="width: 7%">Precio</vs-th>
                    <vs-th class="text-center" style="width: 5%"
                        >Opciones</vs-th
                    >
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.cod_principal" v-for="datos in data">
                        <vs-td v-if="datos.cod_alterno">{{ datos.cod_alterno }}</vs-td
                        ><vs-td v-else>{{ datos.cod_principal }}</vs-td>
                        <vs-td v-if="datos.nombre">{{ datos.nombre }}</vs-td>
                        <vs-td v-else class="text-center">-</vs-td>
                        <vs-td v-if="datos.descripcion" class="text-center">{{
                            datos.descripcion
                        }}</vs-td>
                        <vs-td v-else class="text-center">-</vs-td>
                        <vs-td v-if="datos.nombremarca" class="text-center">{{
                            datos.nombremarca
                        }}</vs-td>
                        <vs-td v-else class="text-center">-</vs-td>
                        <vs-td v-if="datos.nombremodelo" class="text-center">{{
                            datos.nombremodelo
                        }}</vs-td>
                        <vs-td v-else class="text-center">-</vs-td>
                        <vs-td
                            v-if="datos.costo_unitario"
                            class="text-center"
                            >{{ datos.costo_unitario }}</vs-td
                        >
                        <vs-td v-else class="text-center">-</vs-td>
                        <vs-td v-if="datos.pvp_precio1" class="text-center">{{
                            datos.pvp_precio1
                        }}</vs-td>
                        <vs-td v-else class="text-center">-</vs-td>
                        <vs-td class="whitespace-no-wrap text-center">
                            <vx-tooltip
                                text="Editar Producto"
                                position="top"
                                style="display: inline-flex"
                            >
                                <feather-icon
                                    v-if="editarrol"
                                    icon="EditIcon"
                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                    class="pointer"
                                    @click.stop="editar(datos.id_producto)"
                                />
                            </vx-tooltip>
                            <vx-tooltip
                                text="Borrar Producto"
                                position="top"
                                style="display: inline-flex"
                            >
                                <feather-icon
                                    v-if="eliminarrol"
                                    icon="TrashIcon"
                                    svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                    class="ml-2 pointer"
                                    @click.stop="eliminarpro(datos.id_producto)"
                                />
                            </vx-tooltip>
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>
        <!-- Modales-->
        <vs-popup :title="titulomodal" :active.sync="modal">
            <div class="vx-row">
                <!-- Modal Linea de Producto-->
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
                                                listarlinea(
                                                    1,
                                                    buscar1,
                                                    cantidadp1
                                                )
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div class="dropdown-button-container">
                                            <div
                                                class="dropdown-button-container"
                                            >
                                                <vs-button
                                                    class="btnx"
                                                    type="filled"
                                                    @click="
                                                        agregar(
                                                            'lineas',
                                                            'guardar'
                                                        )
                                                    "
                                                    >Agregar Nuevo</vs-button
                                                >
                                                <vs-dropdown>
                                                    <vs-button
                                                        class="btn-drop"
                                                        type="filled"
                                                        icon="expand_more"
                                                    ></vs-button>
                                                    <vs-dropdown-menu
                                                        style="width: 13em"
                                                    >
                                                        <vs-dropdown-item
                                                            class="text-center"
                                                            @click="
                                                                importar1 = true
                                                            "
                                                            >Importar
                                                            registros</vs-dropdown-item
                                                        >
                                                    </vs-dropdown-menu>
                                                </vs-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidolinea">
                                    <template slot="thead">
                                        <vs-th>Código</vs-th>
                                        <vs-th>Línea Producto</vs-th>
                                        <vs-th>Cuenta Compras IVA</vs-th>
                                        <vs-th>Cuenta Compras IVA O</vs-th>
                                        <vs-th>Cuenta Ventas IVA</vs-th>
                                        <vs-th>Cuenta Ventas IVA 0</vs-th>
                                        <vs-th>Cuenta Costo</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_producto"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.codigo">{{
                                                datos.codigo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.nombre">{{
                                                datos.nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.cta_civa">{{
                                                datos.cta_civa
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.cta_civa0">{{
                                                datos.cta_civa0
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.cta_viva">{{
                                                datos.cta_viva
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.cta_viva0">{{
                                                datos.cta_viva0
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.cta_costo">{{
                                                datos.cta_costo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                class="whitespace-no-wrap text-center"
                                                style="width: 5%"
                                            >
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'lineas',
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
                                                            datos.id_linea_producto;
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
                <!-- Modal Tipo de Producto-->
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
                                            v-model="buscar2"
                                            @keyup="listartipo(1, buscar2)"
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <div
                                                class="dropdown-button-container"
                                            >
                                                <vs-button
                                                    class="btnx"
                                                    type="filled"
                                                    @click="
                                                        agregar(
                                                            'tipos',
                                                            'guardar'
                                                        )
                                                    "
                                                    >Agregar Nuevo</vs-button
                                                >
                                                <vs-dropdown>
                                                    <vs-button
                                                        class="btn-drop"
                                                        type="filled"
                                                        icon="expand_more"
                                                    ></vs-button>
                                                    <vs-dropdown-menu
                                                        style="width: 13em"
                                                    >
                                                        <vs-dropdown-item
                                                            class="text-center"
                                                            @click="
                                                                importar2 = true
                                                            "
                                                            >Importar
                                                            registros</vs-dropdown-item
                                                        >
                                                    </vs-dropdown-menu>
                                                </vs-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidotipo">
                                    <template slot="thead">
                                        <vs-th>Código</vs-th>
                                        <vs-th>Tipo Producto</vs-th>
                                        <vs-th>Utilidad</vs-th>
                                        <vs-th>Línea de Productos</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.codigo"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.codigo">{{
                                                datos.codigo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.nombre">{{
                                                datos.nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.utilidad">{{
                                                datos.utilidad
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.nombrelinea">{{
                                                datos.nombrelinea
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                class="whitespace-no-wrap text-center"
                                                style="width: 5%"
                                            >
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'tipos',
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
                                                        (ideliminar =
                                                            datos.id_tipo_producto),
                                                            (eliminar = true),
                                                            (tipoaccionmodal = 2)
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
                <!-- Modal Marca de Producto-->
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
                                            v-model="buscar3"
                                            @keyup="
                                                listarmarca(
                                                    1,
                                                    buscar3,
                                                    cantidadp3
                                                )
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <div
                                                class="dropdown-button-container"
                                            >
                                                <vs-button
                                                    class="btnx"
                                                    type="filled"
                                                    @click="
                                                        agregar(
                                                            'marcas',
                                                            'guardar'
                                                        )
                                                    "
                                                    >Agregar Nuevo</vs-button
                                                >
                                                <vs-dropdown>
                                                    <vs-button
                                                        class="btn-drop"
                                                        type="filled"
                                                        icon="expand_more"
                                                    ></vs-button>
                                                    <vs-dropdown-menu
                                                        style="width: 13em"
                                                    >
                                                        <vs-dropdown-item
                                                            class="text-center"
                                                            @click="
                                                                importar3 = true
                                                            "
                                                            >Importar
                                                            registros</vs-dropdown-item
                                                        >
                                                    </vs-dropdown-menu>
                                                </vs-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidomarca">
                                    <template slot="thead">
                                        <vs-th>nombre</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.codigo"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.nombre">{{
                                                datos.nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.descripcion">{{
                                                datos.descripcion
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                class="whitespace-no-wrap text-center"
                                                style="width: 5%"
                                            >
                                                <feather-icon
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        agregar(
                                                            'marcas',
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
                                                        (ideliminar =
                                                            datos.id_marca),
                                                            (eliminar = true),
                                                            (tipoaccionmodal = 3)
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
                <!-- Modal Modelo de Producto-->
                <div
                    class="vx-col sm:w-full w-full mb-6"
                    v-if="tipoaccion == 4"
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
                                    <div
                                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                    ></div>
                                    <div
                                        class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                                    >
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar4"
                                            @keyup="
                                                listarmodelo(
                                                    1,
                                                    buscar4,
                                                    cantidadp4
                                                )
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <div
                                                class="dropdown-button-container"
                                            >
                                                <vs-button
                                                    class="btnx"
                                                    type="filled"
                                                    @click="
                                                        agregar(
                                                            'modelos',
                                                            'guardar'
                                                        )
                                                    "
                                                    >Agregar Nuevo</vs-button
                                                >
                                                <vs-dropdown>
                                                    <vs-button
                                                        class="btn-drop"
                                                        type="filled"
                                                        icon="expand_more"
                                                    ></vs-button>
                                                    <vs-dropdown-menu
                                                        style="width: 13em"
                                                    >
                                                        <vs-dropdown-item
                                                            class="text-center"
                                                            @click="
                                                                importar4 = true
                                                            "
                                                            >Importar
                                                            registros</vs-dropdown-item
                                                        >
                                                    </vs-dropdown-menu>
                                                </vs-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidomodelo">
                                    <template slot="thead">
                                        <vs-th>nombre</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.codigo"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.nombre">{{
                                                datos.nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.descripcion">{{
                                                datos.descripcion
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                class="whitespace-no-wrap text-center"
                                                style="width: 5%"
                                            >
                                                <feather-icon
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        agregar(
                                                            'modelos',
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
                                                        (ideliminar =
                                                            datos.id_modelo),
                                                            (eliminar = true),
                                                            (tipoaccionmodal = 4)
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
                <!-- Modal Presentacion de Producto-->
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
                                            v-model="buscar6"
                                            @keyup="
                                                listarpresentacion(
                                                    1,
                                                    buscar6,
                                                    cantidadp6
                                                )
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <div
                                                class="dropdown-button-container"
                                            >
                                                <vs-button
                                                    class="btnx"
                                                    type="filled"
                                                    @click="
                                                        agregar(
                                                            'presentaciones',
                                                            'guardar'
                                                        )
                                                    "
                                                    >Agregar Nuevo</vs-button
                                                >

                                                <vs-dropdown>
                                                    <vs-button
                                                        class="btn-drop"
                                                        type="filled"
                                                        icon="expand_more"
                                                    ></vs-button>
                                                    <vs-dropdown-menu
                                                        style="width: 13em"
                                                    >
                                                        <vs-dropdown-item
                                                            class="text-center"
                                                            @click="
                                                                importar5 = true
                                                            "
                                                            >Importar
                                                            registros</vs-dropdown-item
                                                        >
                                                    </vs-dropdown-menu>
                                                </vs-dropdown>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidopresentacion">
                                    <template slot="thead">
                                        <vs-th>nombre</vs-th>
                                        <vs-th>Descripción</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.codigo"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.nombre">{{
                                                datos.nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.descripcion">{{
                                                datos.descripcion
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                class="whitespace-no-wrap text-center"
                                                style="width: 5%"
                                            >
                                                <feather-icon
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        agregar(
                                                            'presentaciones',
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
                                                        (ideliminar =
                                                            datos.id_presentacion),
                                                            (eliminar = true),
                                                            (tipoaccionmodal = 6)
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
            </div>
            <!-- Opciones para agregar-->
            <!-- Modal Agregar Linea de Producto-->
            <vs-popup
                :title="titulo"
                :class="'med'"
                :active.sync="agregarlinea"
            >
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
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
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Código:"
                                v-model="codigo"
                            />
                            <div v-show="error" v-if="!codigo">
                                <div
                                    v-for="err in errorcodigo"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <!-- <vs-input
                                class="w-full"
                                label="Cuenta Compras IVA:"
                                v-model="cta_civa"
                            />-->
                            <label class="vs-input--label"
                                >Cuenta Compras IVA:</label
                            >
                            <vx-input-group>
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cta_civa"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta(
                                                    'Cuenta Compras IVA'
                                                )
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <!--<vs-input
                                class="w-full"
                                label="Cuenta Compras IVA 0:"
                                v-model="cta_civa0"
                            />-->
                            <label class="vs-input--label"
                                >Cuenta Compras IVA 0:</label
                            >
                            <vx-input-group>
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cta_civa0"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta(
                                                    'Cuenta Compras IVA 0'
                                                )
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <!-- <vs-input
                                class="w-full"
                                label="Cuenta Ventas IVA:"
                                v-model="cta_viva"
                            />-->
                            <label class="vs-input--label"
                                >Cuenta Ventas IVA:</label
                            >
                            <vx-input-group>
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cta_viva"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta('Cuenta Ventas IVA')
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <!--<vs-input
                                class="w-full"
                                label="Cuenta Ventas IVA 0:"
                                v-model="cta_viva0"
                            />-->
                            <label class="vs-input--label"
                                >Cuenta Ventas IVA 0:</label
                            >
                            <vx-input-group>
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cta_viva0"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="
                                                modalcuenta(
                                                    'Cuenta Ventas IVA 0'
                                                )
                                            "
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <!-- <vs-input
                                class="w-full"
                                label="Cuenta Costo:"
                                v-model="cta_costo"
                            />-->
                            <label class="vs-input--label">Cuenta Costo:</label>
                            <vx-input-group>
                                <vs-input
                                    disabled
                                    class="w-full"
                                    v-model="cta_costo"
                                />
                                <template slot="append">
                                    <div class="append-text btn-addon">
                                        <vs-button
                                            color="primary"
                                            @click="modalcuenta('Cuenta Costo')"
                                            >Buscar</vs-button
                                        >
                                    </div>
                                </template>
                            </vx-input-group>
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarlinea()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                v-else
                                @click="editarlinea()"
                                >Editar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="(agregarlinea = false), cancelar()"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
                <!-- Popup cuenta contable -->
                <vs-popup
                    :title="titulomodalcuenta"
                    class="peque"
                    :active.sync="popupcuentacontable"
                >
                    <div class="con-exemple-prompt">
                        <vs-input
                            class="mb-4 md:mb-0 mr-4 w-full"
                            v-model="buscarc"
                            @keyup="listarcuenta(buscarc)"
                            v-bind:placeholder="i18nbuscar"
                        />
                        <vs-table
                            stripe
                            @selected="selectcta"
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
            </vs-popup>
            <!-- Modal Agregar Tipo de Producto-->
            <vs-popup :title="titulo" :class="'med'" :active.sync="agregartipo">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-select
                                placeholder="Buscar Línea de Producto"
                                label="Seleccione Línea de Producto:"
                                vs-multiple
                                autocomplete
                                class="selectExample w-full"
                                v-model="seleclinea"
                            >
                                <vs-select-item
                                    :key="item.id_linea_producto"
                                    :value="item.id_linea_producto"
                                    :text="item.nombre"
                                    v-for="item in optionlinea"
                                />
                            </vs-select>
                            <div v-show="error" v-if="!seleclinea">
                                <div
                                    v-for="err in errorseleclinea"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Código:"
                                v-model="codtipo"
                            />
                            <div v-show="error" v-if="!codtipo">
                                <div
                                    v-for="err in errorcodtipo"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="nombretipo"
                            />
                            <div v-show="error" v-if="!nombretipo">
                                <div
                                    v-for="err in errornombretipo"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Utilidad:"
                                v-model="utilidadtipo"
                            />
                        </div>
                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                v-if="tipoaccionmodal == 1"
                                @click="guardartipo()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                v-else
                                @click="editartipo()"
                                >Editar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="(agregartipo = false), cancelar()"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <!-- Modal Agregar Marca-->
            <vs-popup
                :title="titulo"
                :class="'med1'"
                :active.sync="agregarmarca"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Nombre"
                                    v-model="nombremarca"
                                />
                                <div v-show="error" v-if="!nombremarca">
                                    <div
                                        v-for="err in errornombremarca"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-full w-full mb-6 vs-card">
                                <label class="vs-input--label"
                                    >Descripción</label
                                >
                                <vs-textarea
                                    v-model="descripcionmarca"
                                    rows="3"
                                />
                            </div>
                            <div class="vx-col sm:w-full w-full">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="guardarmarca()"
                                    >Guardar</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-else
                                    @click="editarmarca()"
                                    >Editar</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="(agregarmarca = false), cancelar()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!-- Modal Agregar Modelo-->
            <vs-popup
                :title="titulo"
                :class="'med1'"
                :active.sync="agregarmodelo"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Nombre"
                                    v-model="nombremodelo"
                                />
                                <div v-show="error" v-if="!nombremodelo">
                                    <div
                                        v-for="err in errornombremodelo"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-full w-full mb-6">
                                <label class="vs-input--label"
                                    >Descripción</label
                                >
                                <vs-textarea
                                    v-model="descripcionmodelo"
                                    rows="3"
                                />
                            </div>
                            <div class="vx-col sm:w-full w-full">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="guardarmodelo()"
                                    >Guardar</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-else
                                    @click="editarmodelo()"
                                    >Editar</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="agregarmodelo = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!-- Modal Agregar Presentación-->
            <vs-popup
                :title="titulo"
                :class="'med1'"
                :active.sync="agregarpresentacion"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Nombre"
                                    v-model="nombrepresentacion"
                                />
                                <div v-show="error" v-if="!nombrepresentacion">
                                    <div
                                        v-for="err in errornombrepresentacion"
                                        :key="err"
                                        v-text="err"
                                        class="text-danger"
                                    ></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-full w-full mb-6">
                                <label class="vs-input--label"
                                    >Descripción</label
                                >
                                <vs-textarea
                                    v-model="descripcionpresentacion"
                                    rows="3"
                                />
                            </div>
                            <div class="vx-col sm:w-full w-full">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="guardarpresentacion()"
                                    >Guardar</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-else
                                    @click="editarpresentacion()"
                                    >Editar</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="agregarpresentacion = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!--  MOdal para eliminaciones  -->
            <vs-popup
                title="eliminar registro"
                :class="'peque'"
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
                                    <br />¡Se eliminará de forma permanente!
                                </label>
                                <div>
                                    <label
                                        class="text-center"
                                        v-if="tipoaccionmodal == 1"
                                        >Si esta linea contiene tipos de
                                        productos tambien se borrarán del
                                        sistema!!!</label
                                    >
                                </div>
                            </div>
                            <div class="vx-col sm:w-full w-full text-center">
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="eliminarlinea(ideliminar)"
                                    >Eliminar linea</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 2"
                                    @click="eliminartipo(ideliminar)"
                                    >Eliminar tipo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 3"
                                    @click="eliminarmarca(ideliminar)"
                                    >Eliminar marca</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 4"
                                    @click="eliminarmodelo(ideliminar)"
                                    >Eliminar modelo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else
                                    @click="eliminarpresentacion(ideliminar)"
                                    >Eliminar presentacion</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!--Modal para importar linea de productos excel-->
            <vs-popup
                :class="'peque2'"
                title="Importar Excel"
                :active.sync="importar1"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <label class="vs-input--label"
                                    >Subir Archivo</label
                                >
                                <div class="vx-col md:w-full w-full mb-6">
                                    <div style="display: none">
                                        <input
                                            id="input-upload"
                                            type="file"
                                            class="custom-file-input inputexcel1"
                                            @change="subirArchivo($event)"
                                            accept=".XLSX, .CSV"
                                        />
                                    </div>
                                    <div
                                        class="centimg vx-card input"
                                        @click="importarexcel1()"
                                    >
                                        <img src="/images/upload.png" />
                                        <div
                                            v-if="file.length === 0"
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            Click para subir Archivo
                                        </div>
                                        <div
                                            v-else
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            {{ file[0].name }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-button
                                    color="success"
                                    @click="importardatosLineasProducto()"
                                    >Subir Archivo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelarLinea()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!--fin modal importar linea de productos de importar-->
            <!--Modal para importar tipo de productos excel-->
            <vs-popup
                :class="'peque2'"
                title="Importar Excel"
                :active.sync="importar2"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <label class="vs-input--label"
                                    >Subir Archivo</label
                                >
                                <div class="vx-col md:w-full w-full mb-6">
                                    <div style="display: none">
                                        <input
                                            id="input-upload"
                                            type="file"
                                            class="custom-file-input inputexcel2"
                                            @change="subirArchivo($event)"
                                            accept=".XLSX, .CSV"
                                        />
                                    </div>
                                    <div
                                        class="centimg vx-card input"
                                        @click="importarexcel2()"
                                    >
                                        <img src="/images/upload.png" />
                                        <div
                                            v-if="file.length === 0"
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            Click para subir Archivo
                                        </div>
                                        <div
                                            v-else
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            {{ file[0].name }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-button
                                    color="success"
                                    @click="importardatosTipoProducto()"
                                    >Subir Archivo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelarTipo()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!--fin modal -->
            <!--Modal para importar marca de productos excel-->
            <vs-popup
                :class="'peque2'"
                title="Importar Excel"
                :active.sync="importar3"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <label class="vs-input--label"
                                    >Subir Archivo</label
                                >
                                <div class="vx-col md:w-full w-full mb-6">
                                    <div style="display: none">
                                        <input
                                            id="input-upload"
                                            type="file"
                                            class="custom-file-input inputexcel3"
                                            @change="subirArchivo($event)"
                                            accept=".XLSX, .CSV"
                                        />
                                    </div>
                                    <div
                                        class="centimg vx-card input"
                                        @click="importarexcel3()"
                                    >
                                        <img src="/images/upload.png" />
                                        <div
                                            v-if="file.length === 0"
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            Click para subir Archivo
                                        </div>
                                        <div
                                            v-else
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            {{ file[0].name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-button
                                    color="success"
                                    @click="importardatosMarcaProducto()"
                                    >Subir Archivo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelarMarca()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!--fin modal -->
            <!--Modal para importar modelos de productos excel-->
            <vs-popup
                :class="'peque2'"
                title="Importar Excel"
                :active.sync="importar4"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <label class="vs-input--label"
                                    >Subir Archivo</label
                                >
                                <div class="vx-col md:w-full w-full mb-6">
                                    <div style="display: none">
                                        <input
                                            id="input-upload"
                                            type="file"
                                            class="custom-file-input inputexcel4"
                                            @change="subirArchivo($event)"
                                            accept=".XLSX, .CSV"
                                        />
                                    </div>
                                    <div
                                        class="centimg vx-card input"
                                        @click="importarexcel4()"
                                    >
                                        <img src="/images/upload.png" />
                                        <div
                                            v-if="file.length === 0"
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            Click para subir Archivo
                                        </div>
                                        <div
                                            v-else
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            {{ file[0].name }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-button
                                    color="success"
                                    @click="importardatosModelosProducto()"
                                    >Subir Archivo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelarModelo()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!--fin modal -->
            <!--Modal para importar presentacion de productos excel-->
            <vs-popup
                :class="'peque2'"
                title="Importar Excel"
                :active.sync="importar5"
            >
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <label class="vs-input--label"
                                    >Subir Archivo</label
                                >
                                <div class="vx-col md:w-full w-full mb-6">
                                    <div style="display: none">
                                        <input
                                            id="input-upload"
                                            type="file"
                                            class="custom-file-input inputexcel5"
                                            @change="subirArchivo($event)"
                                            accept=".XLSX, .CSV"
                                        />
                                    </div>
                                    <div
                                        class="centimg vx-card input"
                                        @click="importarexcel5()"
                                    >
                                        <img src="/images/upload.png" />
                                        <div
                                            v-if="file.length === 0"
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            Click para subir Archivo
                                        </div>
                                        <div
                                            v-else
                                            style="position: absolute; margin-top: 60px; color: #000"
                                        >
                                            {{ file[0].name }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-button
                                    color="success"
                                    @click="importardatosPresentacionProducto()"
                                    >Subir Archivo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="cancelarPresentacion()"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!--fin modal -->
        </vs-popup>
        <!--Modal para exportar excel-->
        <vs-popup
            :class="'peque1'"
            title="Exportar Excel"
            :active.sync="exportar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-input
                                v-model="nombreexportar"
                                placeholder="Nombre del archivo..."
                                class="w-full"
                            />
                            <div class="flex mb-4">
                                <span class="mr-4"
                                    >Celda con ancho predefinido:</span
                                >
                                <vs-switch v-model="cellancho"
                                    >Ancho de los campos del archivo</vs-switch
                                >
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mt-5">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="exportardatos"
                                >Descargar Excel</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="exportar = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!--Modal para importar excel-->
        <vs-popup
            :class="'peque2'"
            title="Importar Excel"
            :active.sync="importar"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="vx-col sm:w-full w-full mb-6">
                            <label class="vs-input--label">Subir Archivo</label>
                            <div class="vx-col md:w-full w-full mb-6">
                                <div style="display: none">
                                    <input
                                        id="input-upload"
                                        type="file"
                                        class="custom-file-input inputexcel"
                                        @change="subirArchivo($event)"
                                        accept=".XLSX, .CSV"
                                    />
                                </div>
                                <div
                                    class="centimg vx-card input"
                                    @click="importarexcel()"
                                >
                                    <img src="/images/upload.png" />
                                    <div
                                        v-if="file.length === 0"
                                        style="position: absolute; margin-top: 60px; color: #000"
                                    >
                                        Click para subir Archivo
                                    </div>
                                    <div
                                        v-else
                                        style="position: absolute; margin-top: 60px; color: #000"
                                    >
                                        {{ file[0].name }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="importardatos()"
                                >Subir Archivo</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="cancelar1()"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!--fin modal de importar-->
        <!--inicio modal reporte-->
        <vs-popup
            :class="'peque2'"
            title="Reporte Producto"
            :active.sync="modalReporte"
        >
            <vx-card>
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        <div class="flex flex-wrap mt-5 mb-5">
                            <div class="w-1/4 flex">
                                <label for="sections">Todos</label>
                                <vs-switch
                                    class="ml-5"
                                    v-model="todos_product"
                                />
                            </div>
                        </div>
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-select
                                placeholder="Buscar Producto"
                                label="Seleccione Producto:"
                                vs-multiple
                                autocomplete
                                class="selectExample w-full"
                                v-model="producto_report"
                            >
                                <vs-select-item
                                    :key="item.id_producto"
                                    :value="item.id_producto"
                                    :text="item.nombre_producto"
                                    v-for="item in productos_reporte"
                                />
                            </vs-select>
                        </div>

                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-button color="success" @click="generarReporte()"
                                >Generar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="cancelar1()"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        <!--fin modal reporte-->
    </div>
</template>
<script>
import ImportExcel from "@/components/excel/ImportExcel.vue";
import { AgGridVue } from "ag-grid-vue";
import $ from "jquery";
import vSelect from "vue-select";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        ImportExcel
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        // crearrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[17].crear;
        //     }
        //     return res;
        // },
        // editarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[17].editar;
        //     }
        //     return res;
        // },
        // eliminarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[17].eliminar;
        //     }
        //     return res;
        // },
        crearrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[1].crear;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Catalogo"){
                        res = el.crear;
                        return res;
                    }
                });
            }
            return res;
        },
        editarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[1].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Catalogo"){
                        res = el.editar;
                        return res;
                    }
                });
            }
            return res;
        },
        eliminarrol() {
            var res = 0;
            // if (this.usuario.id_rol == 1) {
            //     res = 1;
            // } else {
            //     res = this.$store.state.Roles[1].eliminar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Catalogo"){
                        res = el.eliminar;
                        return res;
                    }
                });
            }
            return res;
        },
        traerProducto() {
            if (this.modalReporte == true) {
                this.listarProductoRep();
                console.log("se executo el listar productos");
            }
        }
    },
    data() {
        return {
            //Datos para la importaciond de archivos
            importar: false,
            importar1: false,
            importar2: false,
            importar3: false,
            importar4: false,
            importar5: false,
            //excel import
            file: [],
            tableData: [],
            header: [],
            sheetName: "",
            //variables modal
            titulomodal: "",
            modal: false,
            tipoaccion: 0,
            tipoaccionmodal: [],
            popupcuentacontable: false,
            //elimina seleccion de producto al crear formula de produccion: 0,
            titulo: "",
            id: null,
            agregarlinea: false,
            agregartipo: false,
            agregarmarca: false,
            agregarmodelo: false,
            agregarpresentacion: false,
            eliminar: false,
            ideliminar: 0,
            tipoeliminar: null,
            //paginaciones
            pagination: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination1: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination2: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination3: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination4: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination5: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagination6: {
                total: 0,
                current_page: 0,
                per_page: 0,
                last_page: 0,
                from: 0,
                to: 0,
                count: 0
            },
            pagina: 1,
            pagina1: 1,
            pagina2: 1,
            pagina3: 1,
            pagina4: 1,
            pagina5: 1,
            pagina6: 1,
            cantidadp: 100000,
            cantidadp1: 100000,
            cantidadp2: 100000,
            cantidadp3: 100000,
            cantidadp4: 100000,
            cantidadp5: 100000,
            cantidadp6: 100000,
            offset: 3,
            offset1: 3,
            offset2: 3,
            offset3: 3,
            offset4: 3,
            offset5: 3,
            offset6: 3,
            //buscador
            buscar: "",
            buscar1: "",
            buscar2: "",
            buscar3: "",
            buscar4: "",
            buscar5: "",
            buscar6: "",
            //otros valores
            gridApi: null,
            contenido: [],
            contenidolinea: [],
            contenidotipo: [],
            contenidomarca: [],
            contenidomodelo: [],
            contenidobodega: [],
            contenidopresentacion: [],
            contenidocuenta: [],
            titulomodalcuenta: "",
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //variables linea producto
            nombre: "",
            codigo: "",
            cta_civa: "",
            cta_civa_id: "",
            cta_civa0: "",
            cta_civa0_id: "",
            cta_viva: "",
            cta_viva_id: "",
            cta_viva0: "",
            cta_viva0_id: "",
            cta_costo: "",
            cta_costo_id: "",
            //variables tipo producto
            seleclinea: "",
            codtipo: "",
            nombretipo: "",
            utilidadtipo: "",
            //variables marca
            nombremarca: "",
            descripcionmarca: "",
            //variables modelo
            nombremodelo: "",
            descripcionmodelo: "",
            //variables presentación
            nombrepresentacion: "",
            descripcionpresentacion: "",
            //opciones linea de producto para tipo de producto
            optionlinea: [],
            //Variables listar plan de cuentas
            buscarc: "",
            //variables para validacion
            //valida linea de producto
            error: 0,
            errornombre: [],
            errorcodigo: [],
            //validar tipo de producto
            errorseleclinea: [],
            errorcodtipo: [],
            errornombretipo: [],
            //validar marca
            errornombremarca: [],
            //valida modelo
            errornombremodelo: [],
            //valida presentacion
            errornombrepresentacion: [],
            //campos reporte
            modalReporte: false,
            productos_reporte: [],
            producto_report: null,
            todos_product: false,
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            //campos que existen para exportar
            campos: [
                "cod_principal",
                "cod_alterno",
                "imagen",
                "nombre",
                "codigo_barras",
                "form_prod",
                "descripcion",
                "caracteristicas",
                "normativa",
                "uso",
                "nombrec",
                "sector",
                "tipo_servicio",
                "ubicacion_fisica",
                "unidad_entrada",
                "unidad_salida",
                "vencimiento",
                "existencia_maxima",
                "existencia_minima",
                "numero_unidad",
                "estado",
                "vehiculo",
                "placa",
                "pais_origen",
                "ano_fabricacionv",
                "color",
                "carroceria",
                "combustible",
                "motor",
                "cilindraje",
                "chasis",
                "clase",
                "subclase",
                "numero_pasajeros",
                "iva",
                "ice",
                "arancel_advalorem",
                "arancel_especifico",
                "arancel_fodinfa",
                "comision",
                "salvaguardia",
                "pvp_precio1",
                "precio2",
                "precio3",
                "precio4",
                "precio5",
                "descuento",
                "utilidad",
                "fecha_fabricacion",
                "ultimo_costo",
                "costo_promedio",
                "costo_total",
                "existencia_total",
                "fcrea",
                "fmodifica",
                "ucrea",
                "umodifica",
                "id_linea_producto",
                "id_tipo_producto",
                "id_marca",
                "id_modelo",
                "id_presentacion",
                "id_tipo_medida",
                "id_unidad_medida",
                "id_empresa",
                "id_formula_produccion",
                "id_plan_cuentas"
            ],
            //campos elegidos a exportar
            indexs: [
                "cod_principal",
                "sector",
                "cod_alterno",
                "nombre",
                "imagen",
                "codigo_barras",
                "id_plan_cuentas",
                "id_formula_produccion",
                "descripcion",
                "caracteristicas",
                "normativa",
                "uso",
                "id_linea_producto",
                "id_tipo_producto",
                "id_marca",
                "id_modelo",
                "id_presentacion",
                "estado",
                "vencimiento",
                "existencia_maxima",
                "existencia_minima",
                "id_tipo_medida",
                "id_unidad_medida",
                "numero_unidad",
                "unidad_salida",
                "iva",
                "ice",
                "arancel_advalorem",
                "arancel_especifico",
                "arancel_fodinfa",
                "comision",
                "salvaguardia",
                "pvp_precio1",
                "precio2",
                "precio3",
                "precio4",
                "precio5",
                "descuento",
                "utilidad",
                "fecha_fabricacion",
                "ultimo_costo",
                "costo_promedio",
                "costo_total",
                "existencia_total",
                "vehiculo",
                "placa",
                "pais_origen",
                "ano_fabricacionv",
                "color",
                "carroceria",
                "combustible",
                "motor",
                "cilindraje",
                "chasis",
                "clase",
                "subclase",
                "numero_pasajeros",
                "form_prod",
                "nombrec",
                "tipo_servicio",
                "ubicacion_fisica",
                "unidad_entrada",
                "id_empresa"
            ],
            //cabezera que imprimira en el excel
            cabezera: [
                "cod_principal",
                "sector_producto_o_servicio",
                "codigo_alterno",
                "nombre",
                "imagen",
                "codigo_barras",
                "id_plan_cuentas",
                "id_formula_produccion",
                "descripcion",
                "caracteristicas",
                "normativa",
                "uso",
                "id_linea_producto",
                "id_tipo_producto",
                "id_marca",
                "id_modelo",
                "id_presentacion",
                "estado",
                "fecha_vencimiento",
                "existencia_maxima",
                "existencia_minima",
                "id_tipo_medida",
                "id_unidad_medida",
                "numero_de_unidad",
                "unidad_salida",
                "iva",
                "ice",
                "arancel_ad_valorem",
                "arancel_especifico",
                "arancel_fodinfa",
                "comision",
                "salvaguardia",
                "pvp_precio1",
                "precio2",
                "precio3",
                "precio4",
                "precio5",
                "descuento",
                "utilidad",
                "fecha_fabricacion",
                "ultimo_costo",
                "costo_promedio",
                "costo_total",
                "existencia_total",
                "vehiculo",
                "placa",
                "pais_origen",
                "ano_fabricacion",
                "color",
                "carroceria",
                "combustible",
                "motor",
                "cilindraje",
                "chasis",
                "clase",
                "subclase",
                "numero_pasajeros",
                "form_prod",
                "nombrec",
                "tipo_servicio",
                "ubicacion_fisica",
                "unidad_entrada",
                "id_empresa"
            ],
            timeout: null
        };
    },
    methods: {
        //importar archivos catalogo
        cancelar1() {
            this.importar = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },
        //preload
        readyFile($e) {
            // Este muestra una lista FileList
            console.log($e.target.files);
            // Este muestra el valor actual del input
            console.log($e.target.value);
        },
        subirArchivo(e) {
            this.file = [];
            let tempFile = e.target.files[0];
            var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
            if (!allowedExtensions.exec(tempFile.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .csv, .xls, .xlsx",
                    color: "danger"
                });
                return;
            }
            this.file.push(tempFile);
        },
        importarexcel() {
            $(".inputexcel").click();
        },

        importardatos() {
            let formData = new FormData();
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/importarproductosexcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar = false;
                    this.listar(1, this.buscar);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado 'Sin Exito'",
                        color: "danger"
                    });
                    this.importar = false;
                    this.listar(1, this.buscar);
                    this.file = [];
                });
        },

        //import tabla linea producto
        importardatosLineasProducto() {
            let formData = new FormData();
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/importarlineaproductosexcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar1 = false;
                    this.listarlinea(1, this.buscar1);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado 'sin exito'",
                        color: "danger"
                    });
                    this.importar1 = false;
                    this.listarlinea(1, this.buscar1);
                    this.file = [];
                });
        },
        importarexcel1() {
            $(".inputexcel1").click();
        },
        cancelarLinea() {
            this.importar1 = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },

        //import tabla tipo producto
        importarexcel2() {
            $(".inputexcel2").click();
        },
        importardatosTipoProducto() {
            let formData = new FormData();
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/importartipoproductosexcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar2 = false;
                    this.listartipo(1, this.buscar2);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado 'sin exito'",
                        color: "danger"
                    });
                    this.importar2 = false;
                    this.listartipo(1, this.buscar2);
                    this.file = [];
                });
        },
        cancelarTipo() {
            this.importar2 = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },
        //import tabla marca producto
        importarexcel3() {
            $(".inputexcel3").click();
        },
        importardatosMarcaProducto() {
            let formData = new FormData();
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/importarmarcaproductosexcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar3 = false;
                    this.listarmarca(1, this.buscar3);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado 'sin exito'",
                        color: "danger"
                    });
                    this.importar3 = false;
                    this.listarmarca(1, this.buscar3);
                    this.file = [];
                });
        },
        cancelarMarca() {
            this.importar3 = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },
        //import tabla modelo producto
        importarexcel4() {
            $(".inputexcel4").click();
        },

        importardatosModelosProducto() {
            let formData = new FormData();
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/importarmodelosproductosexcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";
                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar4 = false;
                    this.listarmodelo(1, this.buscar4);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado 'sin exito'",
                        color: "danger"
                    });
                    this.importar4 = false;
                    this.listarmodelo(1, this.buscar4);
                    this.file = [];
                });
        },
        cancelarModelo() {
            this.importar4 = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },

        //import tabla presentacion producto
        importarexcel5() {
            $(".inputexcel5").click();
        },
        importardatosPresentacionProducto() {
            let formData = new FormData();
            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file[0]);
            axios
                .post("/api/importarpresentacionproductosexcel", formData, {})
                .then(res => {
                    document.getElementById("input-upload").value = "";

                    this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success"
                    });
                    this.importar5 = false;
                    this.listarpresentacion(1, this.buscar6);
                    this.file = [];
                })
                .catch(err => {
                    this.$vs.notify({
                        text: "Archivo Importado 'sin exito'",
                        color: "danger"
                    });
                    this.importar5 = false;
                    this.listarpresentacion(1, this.buscar6);
                    this.file = [];
                });
        },
        cancelarPresentacion() {
            this.importar5 = false;
            this.file = [];
            document.getElementById("input-upload").value = "";
        },

        //funciones reporte producto
        abrirModalReporteProd() {
            this.modalReporte = true;
        },
        listarProductoRep() {
            axios
                .get("/api/reporte/solo_productos", {
                    params: {
                        id_empresa: this.usuario.id_empresa
                    }
                })
                .then(resp => {
                    this.productos_reporte = resp.data;
                    console.log(resp.data);
                })
                .catch(err => {
                    console.log("Error al traer producto:" + err);
                });
        },
        generarReporte() {
            if (this.todos_product == true) {
                this.producto_report = "";
            } else {
                if (!this.producto_report) {
                    this.$vs.notify({
                        text: "Debe ingresar un producto",
                        color: "danger"
                    });
                    return;
                }
            }
            axios({
                url: "/api/pdf/solo_productos",
                method: "GET",
                responseType: "arraybuffer",
                params: {
                    todos: this.todos_product,
                    id_empresa: this.usuario.id_empresa,
                    id_producto: this.producto_report
                }
            })
                .then(resp => {
                    console.log("ejecutado empleado");
                    //this.contenidopr=res.data;
                    console.log("resp:" + resp);
                    console.log("resp data:" + resp.data);

                    //   var decodedString = String.fromCharCode.apply(
                    //     null,
                    //     new Uint8Array(resp.data)
                    // );
                    // if (decodedString.includes("no-data-report")) {
                    //     this.$vs.notify({
                    //       title: "Sin Registros",
                    //       text: "Los Datos que escogio no tienen registros",
                    //       color: "danger"
                    //     });
                    // }

                    let { headers } = resp;
                    let nameFile = headers["content-disposition"]
                        .split(";")[1]
                        .split("=")[1]
                        .replace(/"/g, "");
                    const url = window.URL.createObjectURL(
                        new Blob([resp.data], { type: "application/pdf" })
                    );
                    console.log("nombre:" + nameFile + "url:" + url);
                    //return({ url: url, nameFile: nameFile });
                    console.log("URL_NAME::", url, nameFile);
                    const link = document.createElement("a");
                    link.href = url;
                    link.download = "Reporte.pdf";
                    link.setAttribute("download", nameFile);
                    document.body.appendChild(link);
                    link.click();
                    this.$vs.notify({
                        title: "Reporte Generado",
                        text: "Su reporte esta siendo descargado exitosamente!",
                        color: "success"
                    });
                    /*this.modal_proyecto=false;
                this.dep_rol="";
                this.fechrolprov="";
                this.datos_proyecto.selectedproyecto=[];*/
                })
                .catch(err => {
                    console.log("ERROR" + err);
                });
        },

        //valida formato de imagen admitidos

        //fin importar archivos
        /*
         *Funciones para abrir modales
         */
        //abrir modal linea de producto
        abrirlinea() {
            this.tipoaccion = 1;
            this.modal = true;
            this.titulomodal = "Líneas de Producto";
        },
        //abrir modal tipo de producto
        abrirtipo() {
            this.tipoaccion = 2;
            this.modal = true;
            this.titulomodal = "Tipos de Producto";
        },
        //abrir modal marca de producto
        abrirmarca() {
            this.tipoaccion = 3;
            this.modal = true;
            this.titulomodal = "Marcas de Producto";
        },
        //abrir modal modela de producto
        abrirmodelo() {
            this.tipoaccion = 4;
            this.modal = true;
            this.titulomodal = "Modelos de Producto";
        },
        //abrir modal presentacion de producto
        abrirpresentacion() {
            this.tipoaccion = 6;
            this.modal = true;
            this.titulomodal = "Presentaciones de Producto";
        },
        //Funcion listar productos
        listar(page, buscar) {
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
            this.timeout = setTimeout(() => {
                var url =
                    "/api/productos/" +
                    this.usuario.id_empresa +
                    "?page=" +
                    page +
                    "&buscar=" +
                    buscar;
                axios
                    .get(url)
                    .then(res => {
                        var respuesta = res.data;
                        this.contenido = respuesta.recupera;
                    })
                    .catch(function(error) {
                        console.log(error);
                    });
            }, 800);
        },
        //Funcion listar linea producto
        listarlinea(page1, buscar1) {
            var url =
                "/api/lineaproductos/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidolinea = respuesta.recupera;
            });
        },
        //Funcion listar tipo producto
        listartipo(page2, buscar2) {
            var url =
                "/api/tipoproductos/" +
                this.usuario.id_empresa +
                "?page=" +
                page2 +
                "&buscar=" +
                buscar2;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidotipo = respuesta.recupera;
            });
        },
        //Funcion listar marca producto
        listarmarca(page3, buscar3) {
            var url =
                "/api/marca/" +
                this.usuario.id_empresa +
                "?page=" +
                page3 +
                "&buscar=" +
                buscar3;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomarca = respuesta.recupera;
            });
        },
        //Funcion listar modelo producto
        listarmodelo(page4, buscar4) {
            var url =
                "/api/modelo/" +
                this.usuario.id_empresa +
                "?page=" +
                page4 +
                "&buscar=" +
                buscar4;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidomodelo = respuesta.recupera;
            });
        },
        //Funcion listar presentacion producto
        listarpresentacion(page6, buscar6) {
            var url =
                "/api/presentacion/" +
                this.usuario.id_empresa +
                "?page=" +
                page6 +
                "&buscar=" +
                buscar6;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.contenidopresentacion = respuesta.recupera;
            });
        },
        //Funcion elimina linea de producto seleccionada
        eliminarlinea(id) {
            axios.delete("/api/eliminarlinea/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarlinea(1, this.buscar1, this.cantidadp1);
        },
        //Funcion elimina tipo de producto seleccionado
        eliminartipo(id) {
            axios.delete("/api/eliminartipo/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listartipo(1, this.buscar2);
            this.listarlinea(1, "", this.cantidadp1);
        },
        //Funcion elimina modelo de producto seleccionado
        eliminarmodelo(id) {
            axios.delete("/api/eliminarmodelo/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarmodelo(1, this.buscar3, this.cantidadp3);
        },
        //Funcion elimina marca de producto seleccionado
        eliminarmarca(id) {
            axios.delete("/api/eliminarmarca/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarmarca(1, this.buscar4, this.cantidadp4);
        },
        //Funcion elimina presentacion de producto seleccionado
        eliminarpresentacion(id) {
            axios.delete("/api/eliminarpresentacion/" + id);
            this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarpresentacion(1, this.buscar6, this.cantidadp6);
        },
        //lista todas las lineas de producto para select en tipo de producto
        todaslinea() {
            let me = this;
            var url = "/api/lineaproductosall/" + this.usuario.id_empresa;
            axios
                .get(url)
                .then(function(response) {
                    me.optionlinea = response.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        //Funcion eliminar registro para eliminar producto , v-dialog confirm
        eliminarpro(cd) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Elimnar este registro?`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: cd
            });
        },
        /*
         *Funcion para eliminar el prodcto seleccionado
         *@acceptAlert
         */
        acceptAlert(parameters) {
            axios.delete("/api/eliminarproductos/" + parameters);
            this.$vs.notify({
                color: "success",
                title: "Producto Eliminado  ",
                text: "El producto selecionado fue eliminado con exito"
            });
            this.listar(1, this.buscar, this.cantidadp);
        },
        //Gestiona vista de popups para cada una de las tablas asociadas a producto
        //filtra tipo de popup en primer case; segundo case selecciona si editar o guardar nuevo
        agregar(tipo, accion, dato) {
            switch (tipo) {
                case "lineas": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarlinea = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar linea de producto";
                            this.nombre = "";
                            this.codigo = "";
                            this.cta_civa = "";
                            this.cta_civa0 = "";
                            this.cta_viva = "";
                            this.cta_viva0 = "";
                            this.cta_costo = "";
                            break;
                        }
                        case "editar": {
                            this.agregarlinea = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar linea de producto";
                            this.id = dato.id_linea_producto;
                            this.nombre = dato.nombre;
                            this.codigo = dato.codigo;
                            this.cta_civa_id = dato.cta_civa_id;
                            this.cta_civa = dato.cta_civa;
                            this.cta_civa0_id = dato.cta_civa0_id;
                            this.cta_civa0 = dato.cta_civa0;
                            this.cta_viva_id = dato.cta_viva_id;
                            this.cta_viva = dato.cta_viva;
                            this.cta_viva0_id = dato.cta_viva0_id;
                            this.cta_viva0 = dato.cta_viva0;
                            this.cta_costo_id = dato.cta_costo_id;
                            this.cta_costo = dato.cta_costo;
                            break;
                        }
                    }
                    break;
                }
                case "tipos": {
                    switch (accion) {
                        case "guardar": {
                            this.agregartipo = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar tipo de producto";
                            this.seleclinea = "";
                            this.codtipo = "";
                            this.nombretipo = "";
                            this.utilidadtipo = "";
                            break;
                        }
                        case "editar": {
                            this.agregartipo = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar tipo de producto";
                            this.id = dato.id_tipo_producto;
                            this.seleclinea = dato.id_linea_producto;
                            this.codtipo = dato.codigo;
                            this.nombretipo = dato.nombre;
                            this.utilidadtipo = dato.utilidad;
                            break;
                        }
                    }
                    break;
                }
                case "marcas": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarmarca = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar marca";
                            this.nombremarca = "";
                            this.descripcionmarca = "";
                            break;
                        }
                        case "editar": {
                            this.agregarmarca = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar marca";
                            this.id = dato.id_marca;
                            this.nombremarca = dato.nombre;
                            this.descripcionmarca = dato.descripcion;
                            break;
                        }
                    }
                    break;
                }
                case "modelos": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarmodelo = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar modelo";
                            this.nombremodelo = "";
                            this.descripcionmodelo = "";
                            break;
                        }
                        case "editar": {
                            this.agregarmodelo = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar modelo";
                            this.id = dato.id_modelo;
                            this.nombremodelo = dato.nombre;
                            this.descripcionmodelo = dato.descripcion;
                            break;
                        }
                    }
                    break;
                }
                case "presentaciones": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarpresentacion = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar presentacion";
                            this.nombrepresentacion = "";
                            this.descripcionpresentacion = "";
                            break;
                        }
                        case "editar": {
                            this.agregarpresentacion = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar presentacion";
                            this.id = dato.id_presentacion;
                            this.nombrepresentacion = dato.nombre;
                            this.descripcionpresentacion = dato.descripcion;
                            break;
                        }
                    }
                    break;
                }
            }
        },
        //Funcion envia id de producto para cargar en Formulario de Edicion de Producto
        editar(id) {
            this.$router.push(`/inventario/catalogo/${id}/editar`);
        },
        //Funcion guarda formulario de crear nueva linea de producto
        guardarlinea() {
            if (this.validarlinea()) {
                return;
            }
            axios
                .post("/api/guardarlinea", {
                    nombre: this.nombre,
                    codigo: this.codigo,
                    cta_civa: this.cta_civa,
                    cta_civa_id: this.cta_civa_id,
                    cta_civa0: this.cta_civa0,
                    cta_civa0_id: this.cta_civa0_id,
                    cta_viva: this.cta_viva,
                    cta_viva_id: this.cta_viva_id,
                    cta_viva0: this.cta_viva0,
                    cta_viva0_id: this.cta_viva0_id,
                    cta_costo: this.cta_costo,
                    cta_costo_id: this.cta_costo_id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarlinea = false;
                    this.cancelar();
                    this.listarlinea(1, this.buscar1, this.cantidadp1);
                    this.todaslinea();
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de edicion de linea de producto
        editarlinea() {
            if (this.validarlinea()) {
                return;
            }
            axios
                .post("/api/editarlinea", {
                    id: this.id,
                    nombre: this.nombre,
                    codigo: this.codigo,
                    cta_civa: this.cta_civa,
                    cta_civa_id: this.cta_civa_id,
                    cta_civa0: this.cta_civa0,
                    cta_civa0_id: this.cta_civa0_id,
                    cta_viva: this.cta_viva,
                    cta_viva_id: this.cta_viva_id,
                    cta_viva0: this.cta_viva0,
                    cta_viva0_id: this.cta_viva0_id,
                    cta_costo: this.cta_costo,
                    cta_costo_id: this.cta_costo_id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarlinea = false;
                    this.cancelar();
                    this.listarlinea(1, this.buscar1, this.cantidadp1);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de crear nuevo tipo de producto
        guardartipo() {
            if (this.validartipo()) {
                return;
            }
            axios
                .post("/api/guardartipo", {
                    seleclinea: this.seleclinea,
                    codtipo: this.codtipo,
                    nombretipo: this.nombretipo,
                    utilidadtipo: this.utilidadtipo,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregartipo = false;
                    this.cancelar();
                    this.listartipo(1, this.buscar2);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de edicion de tipo de producto
        editartipo() {
            if (this.validartipo()) {
                return;
            }
            axios
                .post("/api/editartipo", {
                    id: this.id,
                    seleclinea: this.seleclinea,
                    codtipo: this.codtipo,
                    nombretipo: this.nombretipo,
                    utilidadtipo: this.utilidadtipo,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregartipo = false;
                    this.cancelar();
                    this.listartipo(1, this.buscar2);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de crear nueva marca de producto
        guardarmarca() {
            if (this.validarmarca()) {
                return;
            }
            axios
                .post("/api/guardarmarca", {
                    nombre: this.nombremarca,
                    descripcion: this.descripcionmarca,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmarca = false;
                    this.cancelar();
                    this.listarmarca(1, this.buscar3, this.cantidadp3);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de edicion marca de producto
        editarmarca() {
            if (this.validarmarca()) {
                return;
            }
            axios
                .post("/api/editarmarca", {
                    id: this.id,
                    nombre: this.nombremarca,
                    descripcion: this.descripcionmarca,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmarca = false;
                    this.cancelar();
                    this.listarmarca(1, this.buscar3, this.cantidadp3);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de crear nuevo modelo de producto
        guardarmodelo() {
            if (this.validarmodelo()) {
                return;
            }
            axios
                .post("/api/guardarmodelo", {
                    nombre: this.nombremodelo,
                    descripcion: this.descripcionmodelo,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmodelo = false;
                    this.cancelar();
                    this.listarmodelo(1, this.buscar4, this.cantidadp4);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de edicion modelo de producto
        editarmodelo() {
            if (this.validarmodelo()) {
                return;
            }
            axios
                .post("/api/editarmodelo", {
                    id: this.id,
                    nombre: this.nombremodelo,
                    descripcion: this.descripcionmodelo,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarmodelo = false;
                    this.cancelar();
                    this.listarmodelo(1, this.buscar4, this.cantidadp4);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de crear nueva presentacion de producto
        guardarpresentacion() {
            if (this.validarpresentacion()) {
                return;
            }
            axios
                .post("/api/guardarpresentacion", {
                    nombre: this.nombrepresentacion,
                    descripcion: this.descripcionpresentacion,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarpresentacion = false;
                    this.cancelar();
                    this.listarpresentacion(1, this.buscar6, this.cantidadp6);
                })
                .catch(err => {});
        },
        //Funcion guarda formulario de edicion presentacion de producto
        editarpresentacion() {
            if (this.validarpresentacion()) {
                return;
            }
            axios
                .post("/api/editarpresentacion", {
                    id: this.id,
                    nombre: this.nombrepresentacion,
                    descripcion: this.descripcionpresentacion,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarpresentacion = false;
                    this.cancelar();
                    this.listarpresentacion(1, this.buscar6, this.cantidadp6);
                })
                .catch(err => {});
        },
        //Funcion de export excel
        exportardatos() {
            import("../../vendor/Export2ProductosExcel").then(excel => {
                const list = this.contenido;
                const data = this.formatJson(this.indexs, list);
                excel.export_json_to_excel({
                    header: this.cabezera,
                    data,
                    filename: this.nombreexportar,
                    autoWidth: this.cellancho,
                    bookType: this.tipoformatoexportar
                });
                this.nombreexportar = "";
                this.exportar = false;
            });
        },
        formatJson(filterVal, jsonData) {
            return jsonData.map(v =>
                filterVal.map(j => {
                    return v[j];
                })
            );
        },
        //lista cotenido de cuenta contable
        listarcuenta(buscarc) {
            axios
                .get(
                    "/api/select_plan_cuentas/" +
                        this.usuario.id_empresa +
                        "?buscar=" +
                        buscarc
                )
                .then(res => {
                    this.contenidocuenta = res.data.recupera;
                });
        },
        modalcuenta(titulo) {
            switch (titulo) {
                case "Cuenta Compras IVA": {
                    this.popupcuentacontable = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
                case "Cuenta Compras IVA 0": {
                    this.popupcuentacontable = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
                case "Cuenta Ventas IVA": {
                    this.popupcuentacontable = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
                case "Cuenta Ventas IVA 0": {
                    this.popupcuentacontable = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
                case "Cuenta Costo": {
                    this.popupcuentacontable = true;
                    this.titulomodalcuenta = titulo;
                    break;
                }
            }
        },
        selectcta(tr) {
            if (tr.id_grupo == 2) {
                this.popupcuentacontable = false;
                switch (this.titulomodalcuenta) {
                    case "Cuenta Compras IVA": {
                        this.cta_civa_id = `${tr.id_plan_cuentas}`;
                        this.cta_civa = `${tr.nomcta}`;
                        break;
                    }
                    case "Cuenta Compras IVA 0": {
                        this.cta_civa0_id = `${tr.id_plan_cuentas}`;
                        this.cta_civa0 = `${tr.nomcta}`;
                        break;
                    }
                    case "Cuenta Ventas IVA": {
                        this.cta_viva_id = `${tr.id_plan_cuentas}`;
                        this.cta_viva = `${tr.nomcta}`;
                        break;
                    }
                    case "Cuenta Ventas IVA 0": {
                        this.cta_viva0_id = `${tr.id_plan_cuentas}`;
                        this.cta_viva0 = `${tr.nomcta}`;
                        break;
                    }
                    case "Cuenta Costo": {
                        this.cta_costo_id = `${tr.id_plan_cuentas}`;
                        this.cta_costo = `${tr.nomcta}`;
                        break;
                    }
                }
                this.buscarc = "";
                this.listarcuenta(this.buscarc);
            } else {
                this.$vs.notify({
                    title: "Error",
                    text: "La Cuenta seleccionada no es válida",
                    color: "danger"
                });
            }
        },
        //Funciones de Validacion de Datos
        validarlinea() {
            this.error = 0;
            this.errornombre = [];
            this.errorcodigo = [];

            if (!this.nombre) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.codigo) {
                this.errorcodigo.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            return this.error;
        },
        validartipo() {
            this.error = 0;
            this.errorseleclinea = [];
            this.errorcodtipo = [];
            this.errornombretipo = [];

            if (!this.seleclinea) {
                this.errorseleclinea.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.codtipo) {
                this.errorcodtipo.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            if (!this.nombretipo) {
                this.errornombretipo.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            return this.error;
        },
        validarmarca() {
            this.error = 0;
            this.errornombremarca = [];
            if (!this.nombremarca) {
                this.errornombremarca.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            return this.error;
        },
        validarmodelo() {
            this.error = 0;
            this.errornombremodelo = [];
            if (!this.nombremodelo) {
                this.errornombremodelo.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            return this.error;
        },
        validarpresentacion() {
            this.error = 0;
            this.errornombrepresentacion = [];
            if (!this.nombrepresentacion) {
                this.errornombrepresentacion.push("Campo obligatorio");
                this.error = 1;
                window.scrollTo(0, 0);
            }
            return this.error;
        },
        //funcion cancelar
        cancelar() {
            //variables linea producto
            this.nombre = "";
            this.codigo = "";
            this.cta_civa = "";
            this.cta_civa_id = "";
            this.cta_civa0 = "";
            this.cta_civa0_id = "";
            this.cta_viva = "";
            this.cta_viva_id = "";
            this.cta_viva0 = "";
            this.cta_viva0_id = "";
            this.cta_costo = "";
            this.cta_costo_id = "";
            //variables tipo producto
            this.seleclinea = "";
            this.codtipo = "";
            this.nombretipo = "";
            this.utilidadtipo = "";
            //variables marca
            this.nombremarca = "";
            this.descripcionmarca = "";
            //variables modelo
            this.nombremodelo = "";
            this.descripcionmodelo = "";
            //variables presentación
            this.nombrepresentacion = "";
            this.descripcionpresentacion = "";
            //valida linea de producto
            this.error = 0;
            this.errornombre = [];
            this.errorcodigo = [];
            //valida tipo de producto
            this.errorseleclinea = [];
            this.errorcodtipo = [];
            this.errornombretipo = [];
            //validar marca
            this.errornombremarca = [];
            //valida modelo
            this.errornombremodelo = [];
            //valida presentacion
            this.errornombrepresentacion = [];
        }
    },
    mounted() {
        this.listar(1, this.buscar);
        this.listarlinea(1, this.buscar1);
        this.todaslinea();
        this.listartipo(1, this.buscar2);
        this.listarmarca(1, this.buscar3);
        this.listarmodelo(1, this.buscar4);
        this.listarpresentacion(1, this.buscar6);
        this.listarcuenta(this.buscarc);
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

.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}

.vs-popup {
    width: 1400px !important;
}
.med .vs-popup {
    width: 1000px !important;
}
.med1 .vs-popup {
    width: 800px !important;
}
.peque .vs-popup {
    width: 700px !important;
}
.peque1 .vs-popup {
    width: 500px !important;
}
.peque2 .vs-popup {
    width: 500px !important;
}
.con-vs-dialog .vs-dialog {
    max-width: 950px;
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
.con-vs-dropdown--menu {
    z-index: 9999999999999999;
}
</style>
