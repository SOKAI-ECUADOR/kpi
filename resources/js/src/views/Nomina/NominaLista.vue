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
                        @keyup.enter="listar(1, buscar)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <!--boton de herramientas-->
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
                                    @click="abrirDepartamento()"
                                    >Departamentos</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirArea()"
                                    >Área de Trabajo</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirCargo()"
                                    >Cargos</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirGrupo()"
                                    >Grupo Ocupacional</vs-dropdown-item
                                >
                                <vs-dropdown-item
                                    class="text-center"
                                    divider
                                    @click="abrirDocumento()"
                                    >Documentos Entregables</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                    <!--Fin deboton de herramientas-->
                    <div class="dropdown-button-container" v-if="crearrol">
                        <vs-button
                            class="btnx"
                            type="filled"
                            to="/nomina/empleados/agregar"
                            >Agregar</vs-button
                        >
                        <vs-dropdown>
                            <vs-button
                                class="btn-drop"
                                type="filled"
                                icon="expand_more"
                            ></vs-button>
                            <vs-dropdown-menu style="width:13em;">
                                <vs-dropdown-item class="text-center" divider
                                    @click="importar = true" >Importar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    @click="exportar = true">Exportar registros</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center" divider
                                    >Generar PDF</vs-dropdown-item
                                >
                                <vs-dropdown-item class="text-center"
                                    >Generar XML</vs-dropdown-item
                                >
                            </vs-dropdown-menu>
                        </vs-dropdown>
                    </div>
                </div>
            </div>
            <vs-table stripe :data="contenido">
                <template slot="thead">
                    <!--<vs-th>Código</vs-th>-->
                    <vs-th>Nombre</vs-th>
                    <vs-th>Apellido</vs-th>
                    <vs-th>Cédula</vs-th>
                    <vs-th>Edad</vs-th>
                    <vs-th>Inasistencias</vs-th>
                    <vs-th>Acciones</vs-th>
                </template>
                <template slot-scope="{ data }">
                    <vs-tr :key="datos.id_empleado" v-for="datos in data">
                        <!--<vs-td v-if="datos.id_empleado">
                            <a
                                href="javascript:void(0)"
                                @click="activePrompt = true"
                                >{{ datos.id_empleado }}</a
                            >
                        </vs-td>
                        <vs-td v-else>-</vs-td>-->
                        <vs-td v-if="datos.primer_nombre">{{
                            datos.primer_nombre
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.apellido_paterno">{{
                            datos.apellido_paterno
                        }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.dni">{{ datos.dni }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td v-if="datos.edad">{{ datos.edad }}</vs-td>
                        <vs-td v-else>-</vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                icon="SearchIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="
                                    abrirInasistencia(datos.id_empleado)
                                "
                            />
                        </vs-td>
                        <vs-td class="whitespace-no-wrap">
                            <feather-icon
                                v-if="editarrol"
                                icon="EditIcon"
                                class="cursor-pointer"
                                svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                @click.stop="editarEmpleado(datos.id_empleado)"
                            />
                            <feather-icon
                                v-if="eliminarrol"
                                icon="TrashIcon"
                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                class="ml-2 cursor-pointer"
                                @click.stop="
                                    eliminarEmpleado(datos.id_empleado)
                                "
                            />
                        </vs-td>
                    </vs-tr>
                </template>
            </vs-table>
        </vx-card>

        <vs-popup :title="titulomodal" :active.sync="modal">
            <div class="vx-row">
                <!-- Modal Departamento-->
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
                                                listarDepartamento(1, buscar1)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar(
                                                        'departamento',
                                                        'guardar'
                                                    )
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidodepartamento">
                                    <template slot="thead">
                                        <vs-th>Código</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Estado</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_departamento"
                                            v-for="datos in data"
                                        >
                                            <vs-td
                                                v-if="datos.id_departamento"
                                                >{{
                                                    datos.id_departamento
                                                }}</vs-td
                                            >
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.dep_nombre">{{
                                                datos.dep_nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.dep_estado">{{
                                                datos.dep_estado
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'departamento',
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
                                                            datos.id_departamento;
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
                <!-- Modal Área de Trabajo-->
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
                                            @keyup="listarArea(1, buscar2)"
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('area', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidoarea">
                                    <template slot="thead">
                                        <vs-th>Código</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Estado</vs-th>
                                        <vs-th>Departamento</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_area"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.id_area">{{
                                                datos.id_area
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.are_nombre">{{
                                                datos.are_nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.are_estado">{{
                                                datos.are_estado
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.nomb_departamento">{{
                                                datos.nomb_departamento
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    class="cursor-pointer"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    @click.stop="
                                                        agregar(
                                                            'area',
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
                                                            datos.id_area),
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
                <!-- Modal CARGO-->
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
                                            @keyup="listarCargo(1, buscar3)"
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('cargo', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidocargo">
                                    <template slot="thead">
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Sueldo</vs-th>
                                        <vs-th>Estado</vs-th>
                                        <vs-th>Área</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_cargo"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.car_nombre">{{
                                                datos.car_nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.car_sueldo">{{
                                                datos.car_sueldo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.car_estado">{{
                                                datos.car_estado
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.nomb_area">{{
                                                datos.nomb_area
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        agregar(
                                                            'cargo',
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
                                                            datos.id_cargo),
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
                <!-- Modal Grupo-->
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
                                    <!-- ITEMS PER PAGE -->
                                    <div
                                        class="mb-4 md:mb-0 mr-4 ag-grid-table-actions-left"
                                    ></div>
                                    <div
                                        class="flex flex-wrap items-center justify-between ag-grid-table-actions-right"
                                    >
                                        <vs-input
                                            class="mb-4 md:mb-0 mr-4"
                                            v-model="buscar4"
                                            @keyup="listarGrupo(1, buscar4)"
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar('grupo', 'guardar')
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidogrupo">
                                    <template slot="thead">
                                        <vs-th>Código</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Estado</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_grupo"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.id_grupo">{{
                                                datos.id_grupo
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.grup_nombre">{{
                                                datos.grup_nombre
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.grup_estado">{{
                                                datos.grup_estado
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        agregar(
                                                            'grupo',
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
                                                            datos.id_grupo),
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
                <!-- Modal Documentos entregables-->
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
                                            v-model="buscar5"
                                            @keyup="listarDocumento(1, buscar5)"
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar(
                                                        'documento',
                                                        'guardar'
                                                    )
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidodocumento">
                                    <template slot="thead">
                                        <vs-th>Código</vs-th>
                                        <vs-th>Nombre</vs-th>
                                        <vs-th>Estado</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_documento"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.id_documento">{{
                                                datos.id_documento
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td
                                                v-if="datos.doc_descripcion"
                                                >{{
                                                    datos.doc_descripcion
                                                }}</vs-td
                                            >
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.doc_estatus">{{
                                                datos.doc_estatus
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        agregar(
                                                            'documento',
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
                                                            datos.id_documento),
                                                            (eliminar = true),
                                                            (tipoaccionmodal = 5)
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
                <!-- ===================================Modal Inasistencias================================-->
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
                                    <!-- =================================ITEMS PER PAGE ==============================================-->
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
                                                listarInasistencias(1, buscar6)
                                            "
                                            v-bind:placeholder="i18nbuscar"
                                        />
                                        <div>
                                            <vs-button
                                                class="btnx"
                                                type="filled"
                                                divider
                                                @click="
                                                    agregar(
                                                        'inasistencia',
                                                        'guardar',
                                                        id_empleado
                                                    )
                                                "
                                                >Agregar Nuevo</vs-button
                                            >
                                        </div>
                                        
                                    </div>
                                </div>
                                <vs-table stripe :data="contenidoinasistencia">
                                    <template slot="thead">
                                        <vs-th>Fecha Inicio</vs-th>
                                        <vs-th>Fecha Fin</vs-th>
                                        <vs-th>Razón</vs-th>
                                        <vs-th>Opciones</vs-th>
                                    </template>
                                    <template slot-scope="{ data }">
                                        <vs-tr
                                            :key="datos.id_calen_emp"
                                            v-for="datos in data"
                                        >
                                            <vs-td v-if="datos.fecha_inicio">{{
                                                datos.fecha_inicio
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.fecha_fin">{{
                                                datos.fecha_fin
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td v-if="datos.razon">{{
                                                datos.razon
                                            }}</vs-td>
                                            <vs-td v-else>-</vs-td>
                                            <vs-td class="whitespace-no-wrap">
                                                <feather-icon
                                                    icon="EditIcon"
                                                    svgClasses="w-5 h-5 hover:text-primary stroke-current"
                                                    class="cursor-pointer"
                                                    @click.stop="
                                                        agregar(
                                                            'inasistencia',
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
                                                            datos.id_calen_emp),
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
            <!-- Modal Agregar Departamento-->
            <vs-popup :title="titulo" :active.sync="agregardepartamento">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        
                        <div class="vx-col sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre:"
                                v-model="dep_nombre"
                            />
                            <div v-show="errordep" v-if="!dep_nombre">
                                    <div v-for="err in errornombrdepartamento" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6" v-if="id_departamento">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Estado:"
                                label-placeholder="--Seleccione--"
                                vs-multiple
                                autocomplete
                                v-model="dep_estado"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item, index) in dep_estado_array"
                                />
                            </vs-select>
                        </div>

                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarDepartamento()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                v-else
                                @click="editarDepartamento()"
                                >Editar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="agregardepartamento = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <!-- Modal Áre de Trabajo-->
            <vs-popup :title="titulo" :active.sync="agregararea">
                <div class="vx-col sm:w-full w-full mb-6">
                    <div class="vx-row">
                        
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Nombre del Área:"
                                v-model="are_nombre"
                            />
                            <div v-show="errorarea" v-if="!are_nombre">
                                    <div v-for="err in errornombrarea" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6" v-if="id">
                            <vs-select
                                placeholder="--Seleccione--"
                                class="selectExample w-full"
                                label="Estado:"
                                label-placeholder="--Seleccione--"
                                vs-multiple
                                autocomplete
                                v-model="are_estado"
                            >
                                <vs-select-item
                                    :key="index"
                                    :value="item.text"
                                    :text="item.text"
                                    v-for="(item, index) in are_estado_array"
                                />
                            </vs-select>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-6">
                            <vs-select
                                placeholder="--Seleccione--"
                                label="Departamento:"
                                vs-multiple
                                autocomplete
                                class="selectExample w-full"
                                v-model="iddepa"
                            >
                                <vs-select-item
                                    v-for="data in departamento2"
                                    :key="data.id_departamento"
                                    :value="data.id_departamento"
                                    :text="data.dep_nombre"
                                />
                            </vs-select>
                           
                            <div v-show="errorarea" v-if="!iddepa">
                                    <div v-for="err in errornombrdeparea" :key="err" v-text="err" class="text-danger"></div>
                            </div>
                        </div>

                        <div class="vx-col sm:w-full w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                v-if="tipoaccionmodal == 1"
                                @click="guardarArea()"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                v-else
                                @click="editarArea()"
                                >Editar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                @click="agregararea = false"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </div>
            </vs-popup>
            <!-- Modal Cargo-->
            <vs-popup :title="titulo" :active.sync="agregarcargo">
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Nombre del Cargo:"
                                    v-model="car_nombre"
                                />
                                <div v-show="errorcarg" v-if="!car_nombre">
                                    <div v-for="err in errorcar_nombre" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6" v-if="id">
                                <vs-select
                                    placeholder="--Seleccione--"
                                    class="selectExample w-full"
                                    label="Estado:"
                                    label-placeholder="--Seleccione--"
                                    vs-multiple
                                    autocomplete
                                    v-model="car_estado"
                                >
                                    <vs-select-item
                                        :key="index"
                                        :value="item.text"
                                        :text="item.text"
                                        v-for="(item,
                                        index) in car_estado_array"
                                    />
                                </vs-select>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Salario:"
                                    v-model="car_sueldo"
                                />
                                <div v-show="errorcarg" v-if="!car_sueldo">
                                    <div v-for="err in errorcar_sueldo" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6" hidden>
                                <vs-select
                                    placeholder="--Seleccione--"
                                    label="Departamento:"
                                    vs-multiple
                                    autocomplete
                                    class="selectExample w-full"
                                    v-model="id_departamento2"
                                    @change="getArea()"
                                >
                                    <vs-select-item
                                        v-for="data in departamento2"
                                        :key="data.id_departamento"
                                        :value="data.id_departamento"
                                        :text="data.dep_nombre"
                                    />
                                </vs-select>
                                
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-select
                                    placeholder="--Seleccione--"
                                    label="Área de Trabajo:"
                                    vs-multiple
                                    autocomplete
                                    class="selectExample w-full"
                                    v-model="id_area"
                                >
                                    <vs-select-item
                                        v-for="data in area2"
                                        :key="data.id_area"
                                        :value="data.id_area"
                                        :text="data.are_nombre"
                                    />
                                </vs-select>
                                <div v-show="errorcarg" v-if="!id_area">
                                    <div v-for="err in errorcar_idarea" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>

                            <div class="vx-col sm:w-full w-full">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="guardarCargo()"
                                    >Guardar</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-else
                                    @click="editarCargo()"
                                    >Editar</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="agregarcargo = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!-- Modal Grupo-->
            <vs-popup :title="titulo" :active.sync="agregargrupo">
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Nombre:"
                                    v-model="grup_nombre"
                                />
                                <div v-show="errorgrup" v-if="!grup_nombre">
                                    <div v-for="err in errorgrup_nombre" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6" v-if="id">
                                <vs-select
                                    placeholder="--Seleccione--"
                                    class="selectExample w-full"
                                    label="Estado:"
                                    label-placeholder="--Seleccione--"
                                    vs-multiple
                                    autocomplete
                                    v-model="grup_estado"
                                >
                                    <vs-select-item
                                        :key="index"
                                        :value="item.text"
                                        :text="item.text"
                                        v-for="(item,
                                        index) in grup_estado_array"
                                    />
                                </vs-select>
                            </div>
                            <div class="vx-col sm:w-full w-full">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="guardarGrupo()"
                                    >Guardar</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-else
                                    @click="editarGrupo()"
                                    >Editar</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="agregargrupo = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!-- Modal Documentos entregables-->
            <vs-popup :title="titulo" :active.sync="agregardocumento">
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                            <div class="vx-col sm:w-full w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Nombre:"
                                    v-model="doc_descripcion"
                                />
                                <div v-show="errordoc" v-if="!doc_descripcion">
                                    <div v-for="err in errordoc_nombre" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6" v-if="id">
                                <vs-select
                                    placeholder="--Seleccione--"
                                    class="selectExample w-full"
                                    label="Estado:"
                                    label-placeholder="--Seleccione--"
                                    vs-multiple
                                    autocomplete
                                    v-model="doc_estatus"
                                >
                                    <vs-select-item
                                        :key="index"
                                        :value="item.text"
                                        :text="item.text"
                                        v-for="(item,
                                        index) in doc_estatus_array"
                                    />
                                </vs-select>
                            </div>
                            <div class="vx-col sm:w-full w-full">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="guardarDocumento()"
                                    >Guardar</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-else
                                    @click="editarDocumento()"
                                    >Editar</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="agregardocumento = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
            <!-- Modal Inasistencias-->
            <vs-popup :title="titulo" :active.sync="agregarinasistencia">
                <vx-card>
                    <div class="vx-col sm:w-full w-full mb-6">
                        <div class="vx-row">
                           
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <label class="vs-input--label"
                                    >Fecha Inicio:</label
                                >
                                <flat-pickr
                                    :config="configdateTimePicker"
                                    class="w-full"
                                    placeholder="--Seleccione--"
                                    v-model="fecha_inicio"
                                />
                                <div v-show="errorinac" v-if="!fecha_inicio">
                                    <div v-for="err in errorfecha_inicio" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                           
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <label class="vs-input--label"
                                    >Fecha Fin:</label
                                >
                                <flat-pickr
                                    :config="configdateTimePicker"
                                    class="w-full"
                                    placeholder="--Seleccione--"
                                    v-model="fecha_fin"
                                />
                                <div v-show="errorinac" v-if="!fecha_fin">
                                    <div v-for="err in errorfecha_fin" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <div class="vx-col sm:w-1/2 w-full mb-6">
                                <vs-input
                                    class="w-full"
                                    label="Razón:"
                                    v-model="razon"
                                />
                                <div v-show="errorinac" v-if="!razon">
                                    <div v-for="err in errorrazon" :key="err" v-text="err" class="text-danger"></div>
                                </div>
                            </div>
                            <!--
              <div class="vx-col sm:w-1/4 w-full mb-6">
                <label class="vs-input--label">Subir Archivo</label>
                <vx-input-group class="mb-base">
                  <vs-input class="w-full" v-model="doc_url" @keypress="solonumeros($event) " />
                  <template slot="append">
                    <div class="append-text btn-addon">
                      <vs-button color="primary" @click="popupActive=true">Elegir</vs-button>
                    </div>
                  </template>
                </vx-input-group>
              </div>
              -->
                            <div class="vx-col sm:w-full w-full">
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-if="tipoaccionmodal == 1"
                                    @click="guardarInasistencia()"
                                    >Guardar</vs-button
                                >
                                <vs-button
                                    color="success"
                                    type="filled"
                                    v-else
                                    @click="editarInasistencia()"
                                    >Editar</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    @click="agregarinasistencia = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
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
                                    @click="eliminarDepartamento(ideliminar)"
                                    >Eliminar Departamento</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 2"
                                    @click="eliminarArea(ideliminar)"
                                    >Eliminar Área de Trabajo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 3"
                                    @click="eliminarCargo(ideliminar)"
                                    >Eliminar Cargo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 4"
                                    @click="eliminarGrupo(ideliminar)"
                                    >Eliminar Grupo</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 5"
                                    @click="eliminarDocumento(ideliminar)"
                                    >Eliminar Documento</vs-button
                                >
                                <vs-button
                                    color="danger"
                                    type="filled"
                                    v-else-if="tipoaccionmodal == 6"
                                    @click="eliminarInasistencias(ideliminar)"
                                    >Eliminar Inasistencia</vs-button
                                >
                                <vs-button
                                    color="primary"
                                    type="filled"
                                    @click="agregarpresentacion = false"
                                    >Cancelar</vs-button
                                >
                            </div>
                        </div>
                    </div>
                </vx-card>
            </vs-popup>
        </vs-popup>
        <!-- =============================================Modal para exportar excel=======================================-->
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
        <!--===================================================fin modal de exportar=================================================== -->

        <!-- ================================================== Modal para importar excel ============================================== -->
       <!--
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

                            <div class="vx-col md:w-full w-full mb-6"  >
                                <div style="display:none" >
                                    <input
                                    
                                       
                                        type="file"
                                        class="custom-file-input inputexcel"
                                        @change="obtenerimagen"
                                        accept=".XLSX, .CSV"
                                         
                                    />
                                </div>

                                <div 
                                    class="centimg vx-card input"
                                    @click="importarexcel()"
                                    
                                >
                                    <img src="/images/upload.png" />
                                    <div class="demo-alignment"
                                        
                                        style="position:absolute;margin-top:60px;color:#000"
                                    >
                                        Click para subir Archivo
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
                                @click="importar = false"
                                >Cancelar</vs-button
                            >
                        </div>
           
                    </div>
                </div>
            </vx-card>
        </vs-popup>
        -->
        <!-- =============================================== fin modal de importar ===================================================== -->
   
    </div>
</template>
<script>
import { AgGridVue } from "ag-grid-vue";
import ImportExcel from "@/components/excel/ImportExcel.vue";
import $ from "jquery";
import { FormWizard, TabContent } from "vue-form-wizard";
import "vue-form-wizard/dist/vue-form-wizard.min.css";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import { concat } from "bytebuffer";
import Datepicker from "vuejs-datepicker";
const axios = require("axios");
export default {
    components: {
        AgGridVue,
        Datepicker,
        flatPickr,
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
        //         res = this.$store.state.Roles[19].crear;
        //     }
        //     return res;
        // },
        // editarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[19].editar;
        //     }
        //     return res;
        // },
        // eliminarrol() {
        //     var res = 0;
        //     if (this.usuario.id_rol == 1) {
        //         res = 1;
        //     } else {
        //         res = this.$store.state.Roles[19].eliminar;
        //     }
        //     return res;
        // }
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
                    if(el.nombre == "Empleados"){
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
            //     res = this.$store.state.Roles[15].editar;
            // }
            if (this.usuario.id_rol == 1) {
                res = 1;
            } else {
                this.$store.state.Roles.forEach(el => {
                    if(el.nombre == "Empleados"){
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
                    //     res = this.$store.state.Roles[15].eliminar;
                    // }
                    if (this.usuario.id_rol == 1) {
                        res = 1;
                    } else {
                        this.$store.state.Roles.forEach(el => {
                            if(el.nombre == "Empleados"){
                                res = el.eliminar;
                                return res;
                            }
                        });
                    }
                    return res;
        },
    },
    data() {
        return {
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
            cantidadp: 10,
            offset: 3,
            //buscador
            buscar: "",
            //otros valores
            gridApi: null,
            contenido: [],

            //variables modal
            titulomodal: "",
            modal: false,
            tipoaccion: 0,
            tipoaccionmodal: 0,
            titulo: "",
            id: null,
            agregardepartamento: false,
            agregararea: false,
            agregarcargo: false,
            agregargrupo: false,
            agregardocumento: false,
            agregarinasistencia: false,
            agregarpresentacion: false,

            eliminar: false,
            ideliminar: 0,
            tipoeliminar: null,
            //mapeo de datos
            //paginacion
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
            contenidodepartamento: [],
            contenidoarea: [],
            contenidocargo: [],
            contenidogrupo: [],
            contenidodocumento: [],
            contenidoinasistencia: [],
            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //variables departamento
            dep_nombre: "",
            dep_estado: "",
            id_departamento:"",
            //variables area de trabajo
            are_nombre: "",
            are_estado: "",
            iddepa: "",
            //variables cargo
            car_nombre: "",
            car_sueldo: "",
            car_estado: "",
            id_area: "",
            id_departamento2:"",
            //variables grupo
            grup_nombre: "",
            grup_estado: "",

            //variables documento
            doc_descripcion: "",
            doc_estatus: "",

            //variables inasistencias
            fecha_inicio: "",
            fecha_fin: "",
            razon: "",
            id_empleado:null,

            //errores departamento
            errordep:0,
            errornombrdepartamento:[],

            //errores area
            errorarea:0,
            errornombrarea:[],
            errornombrdeparea:[],

            //errores cargo
            errorcarg:0,
            errorcar_nombre:[],
            errorcar_sueldo:[],
            errorcar_idarea:[],

            //errores grupo
            errorgrup:0,
            errorgrup_nombre:[],

            //errores documentos
            errordoc:0,
            errordoc_nombre:[],

            //errores inasistencias
            errorinac:0,
            errorfecha_inicio:[],
            errorfecha_fin:[],
            errorrazon:[],

            optiondepartamento: [],
            optionarea: [],

            grupo_ocupacional2: [],
            departamento2: [],
            area2: [],
            cargo2: [],
            configdateTimePicker: {
                locale: SpanishLocale
            },

            //arrays
            dep_estado_array: [
                { text: "Activo", value: 0 },
                { text: "Inactivo", value: 1 }
            ],
            are_estado_array: [
                { text: "Activo", value: 0 },
                { text: "Inactivo", value: 1 }
            ],
            car_estado_array: [
                { text: "Activo", value: 0 },
                { text: "Inactivo", value: 1 }
            ],
            grup_estado_array: [
                { text: "Activo", value: 0 },
                { text: "Inactivo", value: 1 }
            ],
            doc_estatus_array: [
                { text: "Activo", value: 0 },
                { text: "Inactivo", value: 1 }
            ],

            //lenguaje
            i18nbuscar: this.$t("i18nbuscar"),
            //Datos para la importaciond de archivos
            importar: false,
            nombreimportar: "",
            //Datos para la Exportacion de archivos
            exportar: false,
            nombreexportar: "",
            formatoexportar: ["xlsx", "csv", "txt"],
            cellancho: true,
            tipoformatoexportar: "xlsx",
            //campos que existen para exportar
            campos: [
                "id_empleado",
                "tipo_dni",
                "dni",
                "primer_nombre",
                "segundo_nombre",
                "apellido_paterno",
                "apellido_materno",
                "fecha_nacimiento",
                "edad", "foto",
                "estado_civil",
                "sexo",
                "direccion_residencia",
                "telefono",
                "celular",
                "email", 
                "tipo_sangre",
                "profesion",
                "discapacidad",
                "discap_porcentaje",
                "tipo_iden_discap",
                "num_iden_discap",
                "tipo_cuenta", 
                "num_cuenta",
                "num_cargas",
                "carga",
                "estado",
                "observaciones",
                "fecha_ingreso",
                "fecha_salida",
                "tipo_horario",
                "tipo_contrato",
                "sueldo",
                "aporte_iess",
                "fondo_reserva",
                "decimo_tercero",
                "decimo_cuarto",
                "observacion_cargo",
                "lugar_residencia",
                "id_nacionalidad",
                "id_banco",
                "id_empresa",
                "id_departamento",
                "id_provincia",
                "id_ciudad",
                "id_parroquia",
                "id_grupo",
                "id_cargo",
                "id_plan_cuentas",
                "id_area_trabajo"
               
            ],
            //campos elegidos a exportar
            indexs: [
                "tipo_dni",
                "dni",
                "primer_nombre",
                "segundo_nombre",
                "apellido_paterno",
                "apellido_materno",
                "fecha_nacimiento",
                "edad", 
                "foto",
                "id_provincia",
                "id_ciudad",
                "id_parroquia",
                "id_nacionalidad",
                "lugar_residencia",
                "estado_civil",
                "sexo",
                "direccion_residencia",
                "telefono",
                "celular",
                "email", 
                "tipo_sangre",
                "profesion",
                "discapacidad",
                "discap_porcentaje",
                "tipo_iden_discap",
                "num_iden_discap",
                "id_banco",
                "tipo_cuenta", 
                "num_cuenta",
                "carga",
                "num_cargas",
                "observaciones",
                "id_departamento",
                "id_area_trabajo",
                "id_cargo",
                "fecha_ingreso",
                "fecha_salida",
                "id_grupo",
                "tipo_horario",
                "tipo_contrato",
                "sueldo",
                "aporte_iess",
                "fondo_reserva",
                "decimo_tercero",
                "decimo_cuarto",
                "id_plan_cuentas",
                "observacion_cargo",
                "estado",
                "id_empresa",  
            ],
            //cabezera que imprimira en el excel
            cabezera: [
               "tipo_dni",
                "dni",
                "primer_nombre",
                "segundo_nombre",
                "apellido_paterno",
                "apellido_materno",
                "fecha_nacimiento",
                "edad", 
                "foto",
                "id_provincia",
                "id_ciudad",
                "id_parroquia",
                "id_nacionalidad",
                "lugar_residencia",
                "estado_civil",
                "sexo",
                "direccion_residencia",
                "telefono",
                "celular",
                "email", 
                "tipo_sangre",
                "profesion",
                "discapacidad",
                "discap_porcentaje",
                "tipo_iden_discap",
                "num_iden_discap",
                "id_banco",
                "tipo_cuenta", 
                "num_cuenta",
                "carga",
                "num_cargas",
                "observaciones",
                "id_departamento",
                "id_area_trabajo",
                "id_cargo",
                "fecha_ingreso",
                "fecha_salida",
                "id_grupo",
                "tipo_horario",
                "tipo_contrato",
                "sueldo",
                "aporte_iess",
                "fondo_reserva",
                "decimo_tercero",
                "decimo_cuarto",
                "id_plan_cuentas",
                "observacion_cargo",
                "estado",
                "id_empresa", 
            ]
        };
    },
    methods: {
        /*
         * exportar archivo
         */
        exportardatos() {
            import("../../vendor/Export2EmpleadoExcel").then(excel => {
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
       
        /*
         *importar archivos
         *
         */
        /*
         obtenerimagen(e) {
            let file = e.target.files[0];
            var allowedExtensions = /(.csv|.xls|.xlsx)$/i;
            if (!allowedExtensions.exec(file.name)) {
                this.$vs.notify({
                    title: "Tipo de archivo no compatible",
                    text: "Formatos aceptados: .jpg, .jpeg, .png",
                    color: "danger"
                });
                return;
            }
            this.file = file;
        },
        loadDataInTable({ results, header, meta }) {
            this.header = header;
            this.tableData = results;
            this.sheetName = meta.sheetName;
        },
        importarexcel() {
            $(".inputexcel").click();
            
        },
        */
       /*
        importardatos() {
            let formData = new FormData();

            formData.append("id_empresa", this.usuario.id_empresa);
            formData.append("file", this.file);
            axios
                .post("/api/ImportarVendedoresExcel", formData, {})
                .then((res) => {
                    //console.log("error"+res);
                    /*
                    if (res == 'errores'){
                        this.$vs.notify({
                        text: "Archivo Importado 'Sin Exito'",
                        color: "danger",
                        });
                    }else{
                        this.$vs.notify({
                        text: "Archivo Importado con exito",
                        color: "success",
                        });

                        this.importar = false;
                        this.listar(1, this.buscar);

                    }
                    */
                   /*
                    try {
                        if (res === 'err'){
                            throw 'error1'
                        
                        }else{
                            throw 'error2'
                        }
                    }   
                    catch (e) {
                        if(e === "error1"){
                            this.$vs.notify({
                            text: "Archivo Importado 'Sin Exito'",
                            color: "danger",
                            });
                        }
                        if(e === "error2"){
                            this.$vs.notify({
                                text: "Archivo Importado con exito",
                                color: "success",
                            });
                            this.importar = false;
                            this.listar(1, this.buscar);
                        }
                    }
                })
                .catch((err) => {
                    /*
                    this.$vs.notify({
                        text: "Archivo Importado 'Sin prueba Exito'",
                        color: "danger",
                    });
                    */
                    /*
                   console.log(err);
                });
        },
        */
        //abren modales
        abrirDepartamento() {
            this.tipoaccion = 1;
            this.modal = true;
            this.titulomodal = "Departamentos";
            
        },
        abrirArea() {
            this.tipoaccion = 2;
            this.modal = true;
            this.titulomodal = "Áreas de Trabajo";
        },
        abrirCargo() {
            this.tipoaccion = 3;
            this.modal = true;
            this.titulomodal = "Cargos";
        },
        abrirGrupo() {
            this.tipoaccion = 4;
            this.modal = true;
            this.titulomodal = "Grupo Ocupacional";
        },
        abrirDocumento() {
            this.tipoaccion = 5;
            this.modal = true;
            this.titulomodal = "Documentos Entregables";
        },
        abrirInasistencia(id) {
            this.tipoaccion = 6;
            this.modal = true;
            this.titulomodal = "Inasistencias";
            //this.id=id;
            this.id_empleado=id;
            this.listarInasistencias(1, this.buscar6)
        },
        idInasistencia(id){
            this.id_empleado=id;
        },
        //listan tablas
        listarDepartamento(page1, buscar1) {
            var url =
                "/api/departamento/listar/" +
                this.usuario.id_empresa +
                "?page=" +
                page1 +
                "&buscar=" +
                buscar1;
            axios
                .get(url)
                .then(res => {
                    this.contenidodepartamento = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarArea(page2, buscar2) {
            var url =
                "/api/area_trabajo/listar/" +
                this.usuario.id_empresa +
                "?page=" +
                page2 +
                "&buscar=" +
                buscar2;
            axios
                .get(url)
                .then(res => {
                    this.contenidoarea = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarCargo(page3, buscar3) {
            var url =
                "/api/cargo/listar/" +
                this.usuario.id_empresa +
                "?page=" +
                page3 +
                "&buscar=" +
                buscar3;
            axios
                .get(url)
                .then(res => {
                    this.contenidocargo = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarGrupo(page4, buscar4) {
            var url =
                "/api/grupo_ocupacional/listar/" +
                this.usuario.id_empresa +
                "?page=" +
                page4 +
                "&buscar=" +
                buscar4;
            axios
                .get(url)
                .then(res => {
                    this.contenidogrupo = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarDocumento(page5, buscar5) {
            var url =
                "/api/documento/listar/" +
                this.usuario.id_empresa +
                "?page=" +
                page5 +
                "&buscar=" +
                buscar5;
            axios
                .get(url)
                .then(res => {
                    this.contenidodocumento = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        listarInasistencias(page6, buscar6) {
            var url =
                "/api/calendario/listar/"+this.id_empleado
                
            axios.get(url)
                .then(res => {
                    this.contenidoinasistencia = res.data;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        eliminarDepartamento(id) {
            axios.delete("/api/departamento/eliminar/" + id).then(res=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.eliminar = false;
                this.listarDepartamento(1, this.buscar1);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al Eliminar ",
                    text: "Este registro esta siendo usado en otra seccion"
                });
            });
            
        },
        eliminarArea(id) {
            axios.delete("/api/area_trabajo/eliminar/" + id).then(res=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.eliminar = false;
                this.listarArea(1, this.buscar2);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al Eliminar ",
                    text: "Este registro esta siendo usado en otra seccion"
                });
            });
            
        },
        eliminarCargo(id) {
            axios.delete("/api/cargo/eliminar/" + id).then(resp=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.eliminar = false;
                this.listarCargo(1, this.buscar3);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al Eliminar ",
                    text: "Este registro esta siendo usado en otra seccion"
                });
            });
            
        },
        eliminarGrupo(id) {
            axios.delete("/api/grupo_ocupacional/eliminar/" + id).then(res=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.eliminar = false;
                this.listarGrupo(1, this.buscar4);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al Eliminar ",
                    text: "Este registro esta siendo usado en otra seccion"
                });
            });
            
        },
        eliminarDocumento(id) {
            axios.delete("/api/documento/eliminar/" + id).then(res=>{
                this.$vs.notify({
                    title: "Registro eliminado",
                    text: "Este registro ha sido eliminado exitosamente",
                    color: "success"
                });
                this.eliminar = false;
                this.listarDocumento(1, this.buscar5);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al Eliminar ",
                    text: "Este registro esta siendo usado en otra seccion"
                });
            });
            
        },
        eliminarInasistencias(id) {
            axios.delete("/api/calendario/eliminar/" + id).then(res=>{
                this.$vs.notify({
                title: "Registro eliminado",
                text: "Este registro ha sido eliminado exitosamente",
                color: "success"
            });
            this.eliminar = false;
            this.listarInasistencias(1, this.buscar6);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al Eliminar ",
                    text: "Este registro esta siendo usado en otra seccion"
                });
            });
            
        },
        //Funcion eliminar departamento, v-dialog confirm
        getDepartamento() {
            axios
                .get("/api/departamento/" + this.usuario.id_empresa)
                .then(res => {
                    this.departamento2 = res.data;
                });
        },
        getArea() {
            axios
                .get("/api/area/" + this.usuario.id_empresa, {
                    params: { id_departamento: this.iddepa }
                })
                .then(res => {
                    this.area2 = res.data;
                });
        },
        //----------------------------------EMPLEADO---------------------------------------//
        listar(page, buscar) {
            var url =
                "/api/nomina/" +
                this.usuario.id_empresa +
                "?page=" +
                page +
                "&buscar=" +
                buscar;
            axios
                .get(url)
                .then(res => {
                    this.contenido = res.data.recupera;
                })
                .catch(function(error) {
                    console.log(error);
                });
        },
        guardarEmpleado() {
            this.$router.push("/nomina/empleados/agregar");
        },
        editarEmpleado(id) {
            this.$router.push(`/nomina/empleados/${id}/editar`);
        },
        updateSearchQuery(val) {
            this.gridApi.setQuickFilter(val);
        },
        eliminarEmpleado(id) {
            this.$vs.dialog({
                type: "confirm",
                color: "danger",
                title: `Confirmar`,
                text: `¿Desea Elimnar este registro?:`,
                acceptText: "Aceptar",
                cancelText: "Cancelar",
                accept: this.acceptAlert,
                parameters: id
            });
        },
        acceptAlert(parameters) {
            axios.delete("/api/empleado/eliminar/" + parameters).then(res=>{
                this.$vs.notify({
                    color: "success",
                    title: "Empleado Eliminado  ",
                    text: "Registro eliminado"
                });
                this.listar(1, this.buscar);
            }).catch(err=>{
                this.$vs.notify({
                    color: "danger",
                    title: "Error al Eliminar Empleado",
                    text: "Este registro esta siendo utilizado en otra seccion"
                });
            });
            
        },
        //-----------------------FIN EMPLEADO--------------------//
        guardarDepartamento() {
            if(this.validarDepartamento()){
                return;
            }
            axios
                .post("/api/departamento/agregar", {
                    dep_nombre: this.dep_nombre,
                    dep_estado: this.dep_estado,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregardepartamento = false;
                    this.listarDepartamento(1, this.buscar1);
                })
                .catch(err => {});
        },
        editarDepartamento() {
            if(this.validarDepartamento()){
                return;
            }
            axios
                .put("/api/departamento/editar", {
                    id: this.id_departamento,
                    dep_nombre: this.dep_nombre,
                    dep_estado: this.dep_estado,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregardepartamento = false;
                    this.listarDepartamento(1, this.buscar1);
                })
                .catch(err => {});
        },
        guardarArea() {
            if(this.validarArea()){
                return;
            }
            axios
                .post("/api/area_trabajo/agregar", {
                    are_nombre: this.are_nombre,
                    are_estado: this.are_estado,
                    iddepa: this.iddepa,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregararea = false;
                    this.listarArea(1, this.buscar2);
                })
                .catch(err => {});
        },
        editarArea() {
            if(this.validarArea()){
                return;
            }
            axios
                .put("/api/area_trabajo/editar/", {
                    id: this.id,
                    are_nombre: this.are_nombre,
                    are_estado: this.are_estado,
                    iddepa: this.iddepa,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregararea = false;
                    this.listarArea(1, this.buscar2);
                })
                .catch(err => {});
        },
        //mal cargo arreglar
        guardarCargo() {
            if(this.validarCargo()){
                return;
            }
            axios
                .post("/api/cargo/agregar", {
                    car_nombre: this.car_nombre,
                    car_sueldo: this.car_sueldo,
                    car_estado: this.car_estado,
                    id_area: this.id_area,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarcargo = false;
                    this.listarCargo(1, this.buscar3);
                })
                .catch(err => {});
        },
        editarCargo() {
            if(this.validarCargo()){
                return;
            }
            axios
                .put("/api/cargo/editar/", {
                    id: this.id,
                    car_nombre: this.car_nombre,
                    car_sueldo: this.car_sueldo,
                    car_estado: this.car_estado,
                    id_area: this.id_area,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarcargo = false;
                    this.listarCargo(1, this.buscar3);
                })
                .catch(err => {});
        },
        guardarGrupo() {
            if(this.validarGrupo()){
                return;
            }
            axios
                .post("/api/grupo_ocupacional/agregar", {
                    grup_nombre: this.grup_nombre,
                    grup_estado: this.grup_estado,
                    id_empresa: this.usuario.id_empresa,
                    ucrea:      this.usuario.id
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregargrupo = false;
                    this.listarGrupo(1, this.buscar4);
                })
                .catch(err => {});
        },
        editarGrupo() {
            if(this.validarGrupo()){
                return;
            }
            axios
                .put("/api/grupo_ocupacional/editar/", {
                    id: this.id,
                    grup_nombre: this.grup_nombre,
                    grup_estado: this.grup_estado,
                    id_empresa: this.usuario.id_empresa,
                    umodifica:  this.usuario.id
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregargrupo = false;
                    this.listarGrupo(1, this.buscar4);
                })
                .catch(err => {});
        },
        guardarDocumento() {
            if(this.validarDocumento()){
                return;
            }
            axios
                .post("/api/documento/agregar", {
                    doc_descripcion: this.doc_descripcion,
                    doc_estatus: this.doc_estatus,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregardocumento = false;
                    this.listarDocumento(1, this.buscar5);
                })
                .catch(err => {});
        },
        editarDocumento() {
            if(this.validarDocumento()){
                return;
            }
            axios
                .put("/api/documento/editar/", {
                    id: this.id,
                    doc_descripcion: this.doc_descripcion,
                    doc_estatus: this.doc_estatus,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregardocumento = false;
                    this.listarDocumento(1, this.buscar5);
                })
                .catch(err => {});
        },
        guardarInasistencia() {
            if(this.validarInasistencias()){
                return;
            }
            axios
                .post("/api/calendario/agregar", {
                    fecha_inicio: this.fecha_inicio,
                    fecha_fin: this.fecha_fin,
                    razon: this.razon,
                    idemplead:this.id_empleado,
                    id_usuario:this.usuario.id,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarinasistencia = false;
                    this.listarInasistencias(1, this.buscar6);
                })
                .catch(err => {});
        },
        editarInasistencia() {
            if(this.validarInasistencias()){
                return;
            }
            axios
                .put("/api/calendario/editar/", {
                    id: this.id,
                    fecha_inicio: this.fecha_inicio,
                    fecha_fin: this.fecha_fin,
                    razon: this.razon,
                    idemplead:this.id_empleado,
                    id_empresa: this.usuario.id_empresa
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Registro Exitoso",
                        text: "Registro agregado con éxito",
                        color: "success"
                    });
                    this.agregarinasistencia = false;
                    this.listarInasistencias(1, this.buscar6);
                })
                .catch(err => {});
        },
        agregar(tipo, accion, dato) {
            this.getDepartamento();
            this.getArea();
            switch (tipo) {
                case "departamento": {
                    switch (accion) {
                        case "guardar": {
                            this.agregardepartamento = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Departamento";
                            this.dep_nombre = "";
                            this.dep_estado = "";
                            this.id_departamento=null;
                            break;
                        }
                        case "editar": {
                            this.agregardepartamento = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Departamento";
                            this.id_departamento = dato.id_departamento;
                            this.dep_nombre = dato.dep_nombre;
                            this.dep_estado = dato.dep_estado;
                            break;
                        }
                    }
                    break;
                }
                case "area": {
                    switch (accion) {
                        case "guardar": {
                            this.agregararea = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Área de Trabajo";
                            this.are_nombre = "";
                            this.are_estado = "";
                            this.iddepa= "";
                            break;
                        }
                        case "editar": {
                            this.agregararea = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Área de Trabajo";
                            this.id = dato.id_area;
                            this.are_nombre = dato.are_nombre;
                            this.are_estado = dato.are_estado;
                            this.iddepa = dato.id_departamento;
                            break;
                        }
                    }
                    break;
                }
                case "cargo": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarcargo = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Cargo";
                            this.car_nombre = "";
                            (this.car_sueldo = ""), (this.car_estado = "");
                            this.id_area = "";
                            this.id="";
                            break;
                        }
                        case "editar": {
                            this.agregarcargo = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Cargo";
                            this.id = dato.id_cargo;
                            this.car_nombre = dato.car_nombre;
                            this.car_sueldo = dato.car_sueldo;
                            this.car_estado = dato.car_estado;
                            this.id_area = dato.id_area;
                            
                            break;
                        }
                    }
                    break;
                }
                case "grupo": {
                    switch (accion) {
                        case "guardar": {
                            this.agregargrupo = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Grupo Ocupacional";
                            this.grup_nombre = "";
                            this.grup_estado = "";
                            this.id="";
                            break;
                        }
                        case "editar": {
                            this.agregargrupo = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Grupo Ocupacional";
                            this.id = dato.id_grupo;
                            this.grup_nombre = dato.grup_nombre;
                            this.grup_estado = dato.grup_estado;
                            break;
                        }
                    }
                    break;
                }
                case "documento": {
                    switch (accion) {
                        case "guardar": {
                            this.agregardocumento = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Documento";
                            this.doc_descripcion = "";
                            this.doc_estatus = "";
                            this.id="";
                            break;
                        }
                        case "editar": {
                            this.agregardocumento = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Documento";
                            this.id = dato.id_documento;
                            this.doc_descripcion = dato.doc_descripcion;
                            this.doc_estatus = dato.doc_estatus;
                            break;
                        }
                    }
                    break;
                }
                case "inasistencia": {
                    switch (accion) {
                        case "guardar": {
                            this.agregarinasistencia = true;
                            this.tipoaccionmodal = 1;
                            this.titulo = "Agregar Inasistencia";
                            this.fecha_inicio = "";
                            this.fecha_fin = "";
                            this.razon = "";
                            break;
                        }
                        case "editar": {
                            this.agregarinasistencia = true;
                            this.tipoaccionmodal = 2;
                            this.titulo = "Editar Inasistencia";
                            this.id = dato.id_calen_emp;
                            this.fecha_inicio = dato.fecha_inicio;
                            this.fecha_fin = dato.fecha_fin;
                            this.razon = dato.razon;
                            break;
                        }
                    }
                    break;
                }
            }
        },
        limpiar() {
            //campos de departamento
            this.dep_nombre,
                this.dep_estado,
                //campos de area
                this.are_nombre,
                this.are_estado,
                this.iddepa,
                //campos cargo
                this.car_nombre,
                this.car_sueldo,
                this.car_estado,
                this.id_area,
                //campos grupo
                this.grup_nombre,
                this.grup_estado;
        },
        validarInasistencias(){
           this.errorinac=0;
           this.errorfecha_inicio=[];
           this.errorfecha_fin=[];
           this.errorrazon=[];
           if(!this.fecha_inicio){
               this.errorfecha_inicio.push("Campo Obligatorio");
               this.errorinac=1;
           }
           if(!this.fecha_fin){
               this.errorfecha_fin.push("Campo Obligatorio");
               this.errorinac=1;
           }
           if(!this.razon){
               this.errorrazon.push("Campo Obligatorio");
               this.errorinac=1;
           }

           return this.errorinac;
        },
        validarDepartamento(){
           this.errordep=0;
           this.errornombrdepartamento=[];
           if(!this.dep_nombre){
               this.errornombrdepartamento.push("Campo Obligatorio");
               this.errordep=1;
           }
           return this.errordep;

        },
        validarArea(){
           this.errorarea=0;
           this.errornombrarea=[];
           this.errornombrdeparea=[]; 
           if(!this.are_nombre){
               this.errornombrarea.push("Campo Obligatorio");
               this.errorarea=1;
           }
           if(!this.iddepa){
               this.errornombrdeparea.push("Campo Obligatorio");
               this.errorarea=1;
           }
           return this.errorarea;
        },
        validarCargo(){
           this.errorcarg=0;
           this.errorcar_nombre=[];
           this.errorcar_sueldo=[];
           this.errorcar_idarea=[]; 
           if(!this.car_nombre){
               this.errorcar_nombre.push("Campo Obligatorio");
               this.errorcarg=1;
           }
           if(!this.car_sueldo){
               this.errorcar_sueldo.push("Campo Obligatorio");
               this.errorcarg=1;
           }
           if(!this.id_area){
               this.errorcar_idarea.push("Campo Obligatorio");
               this.errorcarg=1;
           }
           return this.errorcarg;
        },
        validarGrupo(){
           this.errorgrup=0;
           this.errorgrup_nombre=[]; 
           if(!this.grup_nombre){
               this.errorgrup_nombre.push("Campo Obligatorio");
               this.errorgrup=1;
           }
           return this.errorgrup;
        },
        validarDocumento(){
           this.errordoc=0;
           this.errordoc_nombre=[];
           if(!this.doc_descripcion){
               this.errordoc_nombre.push("Campo Obligatorio");
               this.errordoc=1;
           }
           return this.errordoc;
        },

        
    },
    mounted() {
        this.listar(1, this.buscar);
        this.listarDepartamento(1, this.buscar1);
        this.listarArea(1, this.buscar2);
        this.listarCargo(1, this.buscar3);
        this.listarGrupo(1, this.buscar4);
        this.listarDocumento(1, this.buscar5);
        this.getDepartamento();
        this.getArea();

                        

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
</style>
