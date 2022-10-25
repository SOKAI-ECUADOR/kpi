<template>
    <div class="vx-row">
        <div class="vx-col w-full mb-base">
            <vx-card title="Agregar Usuario">
                <form
                    action
                    method="post"
                    enctype="multipart/form-data"
                    class="form-horizontal"
                >
                    <div class="vx-row">
                        <div class="vx-col sm:w-1/2 w-full mb-2">
                            <vs-input
                                class="w-full"
                                label="Nombres"
                                v-model="nombre"
                            />
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in errornombre"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/2 w-full mb-2">
                            <vs-input
                                class="w-full"
                                label="Apellidos"
                                v-model="apellido"
                            />
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in errorapellido"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-6">
                            <vs-select
                                placeholder="Seleccione el establecimiento"
                                class="selectExample w-full"
                                label="Establecimiento"
                                vs-multiple
                                autocomplete
                                v-model="establecimeinto"
                                @change="
                                    listarpunto_emision(establecimeinto);
                                    punto_emision = null;
                                "
                            >
                                <vs-select-item
                                    v-for="(tr, index) in arrayestablecimientos"
                                    :key="index"
                                    :text="tr.nombre"
                                    :value="tr.id_establecimiento"
                                />
                            </vs-select>
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in errorestablecimeinto"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-6">
                            <vs-select
                                :disabled="establecimeinto == null"
                                placeholder="Seleccione punto de emisión"
                                class="selectExample w-full"
                                label="punto de emisión"
                                vs-multiple
                                autocomplete
                                v-model="punto_emision"
                            >
                                <vs-select-item
                                    v-for="(tr, index) in arraypunto_emision"
                                    :key="index"
                                    :text="tr.nombre"
                                    :value="tr.id_punto_emision"
                                />
                            </vs-select>
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in errorpunto_emision"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-6">
                            <vs-select
                                placeholder="Seleccione Estado"
                                class="selectExample w-full"
                                label="Estado"
                                vs-multiple
                                v-model="estado"
                            >
                                <vs-select-item
                                    v-for="(tr, index) in arrayestado"
                                    :key="index"
                                    :text="tr.nombre"
                                    :value="tr.id"
                                />
                            </vs-select>
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in errorestado"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-6" style="margin-top: 1.4%;">
                            <label class="vs-input--label mr-1"
                                >Filtrado de Registros:</label
                            >
                            <vs-radio
                                v-model="filtro_list"
                                vs-value="0"
                                >No</vs-radio
                            >
                            <vs-radio
                                v-model="filtro_list"
                                vs-value="1"
                                >Si</vs-radio
                            >
                        </div>
                        <vs-divider border-style="solid" color="dark"
                            >Cambio de ingreso del usuario</vs-divider
                        >
                        <div class="vx-col sm:w-1/4 w-full mb-2">
                            <vs-input
                                class="w-full"
                                label="Contraseña"
                                v-model="password"
                                type="password"
                                autocomplete="new-password"
                            />
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in errorpassword"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col sm:w-1/4 w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Repetir Contraseña"
                                v-model="repassword"
                                type="password"
                                autocomplete="new-password"
                            />
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in errorrepassword"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col w-full mb-6 sm:w-1/4">
                            <vs-input
                                class="w-full"
                                label="Correo electrónico"
                                v-model="email"
                            />
                            <div v-show="error">
                                <span
                                    class="text-danger"
                                    style="font-size:12px;"
                                    v-for="(err, index) in erroremail"
                                    :key="index"
                                    v-text="err"
                                ></span>
                            </div>
                        </div>
                        <div class="vx-col w-full mb-6 sm:w-1/4">
                            <vs-input
                                class="w-full"
                                label="Teléfono"
                                v-model="telefono"
                            />
                        </div>
                        <vs-divider
                            border-style="solid"
                            color="dark"
                            v-if="
                                (usuario.id_rol == 1 && rol != 1) ||
                                    !$route.params.id
                            "
                            >Permisos de módulos de usuario</vs-divider
                        >
                        <div
                            class="vx-col sm:w-full w-full mb-6"
                            v-if="
                                (usuario.id_rol == 1 && rol != 1) ||
                                    !$route.params.id
                            "
                        >
                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_administrar_n"
                            >
                                Administrar
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_administrar"
                                    @click="administrar()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_administrar_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 1"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_contabilidad_n"
                            >
                                Contabilidad
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_contabilidad"
                                    @click="contabilidad()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_contabilidad_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 2"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_facturacion_n"
                            >
                                Facturación
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_facturacion"
                                    @click="facturacion()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_facturacion_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 3"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_compras_n"
                            >
                                Compras
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_compras"
                                    @click="compras()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_compras_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 4"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_inventario_n"
                            >
                                Inventario
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_inventario"
                                    @click="inventario()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_inventario_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 5"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_nomina_n"
                            >
                                Nómina
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_nomina"
                                    @click="nomina()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_nomina_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 6"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_importacion_n"
                            >
                                Importación
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_importacion"
                                    @click="importacion()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_importacion_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 7"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_produccion_n"
                            >
                                Producción
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_produccion"
                                    @click="produccion()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_produccion_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 8"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_activos_n"
                            >
                                Activos fijos
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_activos"
                                    @click="activos()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_activos_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 9"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>
                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_salud_n"
                            >
                                Salud
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_salud"
                                    @click="salud()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_salud_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 10"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>

                            <div
                                slot="header"
                                style="margin-left: 13px;padding: 16px 0;font-weight: bold;font-size: 22px;"
                                v-if="b_calendario_n"
                            >
                                Calendario
                                <vs-checkbox
                                    class="selectall"
                                    v-model="b_calendario"
                                    @click="calendario()"
                                />
                            </div>
                            <table
                                class="w-full"
                                style="margin: 0 20px;"
                                v-if="b_calendario_n"
                            >
                                <tr>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width:40%"
                                    >
                                        Modulo
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Ver
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Editar
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Crear
                                    </th>
                                    <th
                                        class="font-semibold text-base text-left px-3 py-2"
                                        style="width: 15%;"
                                    >
                                        Eliminar
                                    </th>
                                </tr>
                                <tr
                                    v-for="(val, index) in items"
                                    :key="index"
                                    v-if="val.lugar == 11"
                                >
                                    <td class="px-3 py-2">{{ val.nombre }}</td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox v-model="val.ver" />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.editar"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.crear"
                                        />
                                    </td>
                                    <td class="px-3 py-2">
                                        <vs-checkbox
                                            v-if="val.value != 1"
                                            v-model="val.eliminar"
                                        />
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="vx-row">
                        <div class="vx-col w-full">
                            <vs-button
                                color="success"
                                type="filled"
                                @click="guardar()"
                                v-if="!$route.params.id"
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="success"
                                type="filled"
                                @click="editar()"
                                v-else
                                >Guardar</vs-button
                            >
                            <vs-button
                                color="danger"
                                type="filled"
                                to="/administrar/usuarios"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </form>
            </vx-card>
        </div>
    </div>
</template>
<script>
const axios = require("axios");
const $ = require("jquery");
export default {
    data() {
        return {
            checkBox2: [],
            popupActive: false,
            codigo: "",
            nombre: "",
            apellido: "",
            password: "",
            repassword: "",
            email: "",
            telefono: "",

            establecimeinto: null,
            punto_emision: null,
            rol: null,
            estado: 1,
            filtro_list: 0,

            arrayestablecimientos: [],
            arraypunto_emision: [],
            arrayroles: [],
            arraymodulos: [],
            arrayestado: [
                { id: 1, nombre: "Activo" },
                { id: 0, nombre: "Inactivo" }
            ],
            items: [],
            error: 0,
            errorcodigo: [],
            errornombre: [],
            errorapellido: [],
            errorpassword: [],
            errorrepassword: [],
            erroremail: [],
            errorestablecimeinto: [],
            errorpunto_emision: [],
            errorrol: [],
            errorestado: [],

            selected: [],
            selectAll: false,
            selected1: [],
            selectAll1: false,
            selected2: [],
            selectAll2: false,
            selected3: [],
            selectAll3: false,
            selected4: [],
            selectAll4: false,
            selected5: [],
            selectAll5: false,
            selected6: [],
            selectAll6: false,
            empresa: "",

            b_administrar: 0,
            b_contabilidad: 0,
            b_facturacion: 0,
            b_compras: 0,
            b_inventario: 0,
            b_nomina: 0,
            b_importacion: 0,
            b_produccion: 0,
            b_activos: 0,
            b_salud: 0,
            b_calendario: 0,

            b_administrar_n: false,
            b_contabilidad_n: false,
            b_facturacion_n: false,
            b_compras_n: false,
            b_inventario_n: false,
            b_nomina_n: false,
            b_importacion_n: false,
            b_produccion_n: false,
            b_activos_n: false,
            b_salud_n:false,
            b_calendario_n: false
        };
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    methods: {
        listar() {
            if (this.$route.params.id >= 1) {
                var url = "/api/recregusuario/" + this.$route.params.id;
                axios.get(url).then(res => {
                    var dat = res.data.datos;
                    this.nombre = dat.nombres;
                    this.apellido = dat.apellidos;
                    this.email = dat.email;
                    this.telefono = dat.telefono;
                    this.filtro_list = dat.filtro_list;
                    if (dat.id_establecimiento == null) {
                        this.establecimeinto = 0;
                        setTimeout(() => {
                            this.punto_emision = 0;
                        }, 200);
                    } else {
                        this.establecimeinto = dat.id_establecimiento;
                        setTimeout(() => {
                            this.punto_emision = dat.id_punto_emision;
                        }, 200);
                    }
                    this.rol = dat.id_rol;
                    this.estado = dat.estado;
                    //recupera los roles del usuario mediante su id, al no existir id recupera los roles de la emppresa (los permisos totales de la emoresa)
                    //esto es para la edicion de dicho usuario
                    this.items = res.data.roles;
                    this.verificaritems();
                });
            }
        },
        //verifica los items de la empresa al por lista de módulos si son activos o no para poner los vistos en las listas
        verificaritems() {
            this.items.forEach(el => {
                if (el.lugar == 1) {
                    if (el.estado != 0) {
                        this.b_administrar_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_administrar = 1;
                    }
                } else if (el.lugar == 2) {
                    if (el.estado != 0) {
                        this.b_contabilidad_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_contabilidad = 1;
                    }
                } else if (el.lugar == 3) {
                    if (el.estado != 0) {
                        this.b_facturacion_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_facturacion = 1;
                    }
                } else if (el.lugar == 4) {
                    if (el.estado != 0) {
                        this.b_compras_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_compras = 1;
                    }
                } else if (el.lugar == 5) {
                    if (el.estado != 0) {
                        this.b_inventario_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_inventario = 1;
                    }
                } else if (el.lugar == 6) {
                    if (el.estado != 0) {
                        this.b_nomina_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_nomina = 1;
                    }
                } else if (el.lugar == 7) {
                    if (el.estado != 0) {
                        this.b_importacion_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_importacion = 1;
                    }
                } else if (el.lugar == 8) {
                    if (el.estado != 0) {
                        this.b_produccion_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_produccion = 1;
                    }
                } else if (el.lugar == 9) {
                    if (el.estado != 0) {
                        this.b_activos_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_activos = 1;
                    }
                } else if(el.lugar == 10){
                    if (el.estado != 0) {
                        this.b_salud_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_salud = 1;
                    }
                }else{
                    if (el.estado != 0) {
                        this.b_calendario_n = true;
                    }
                    if (el.ver == 1) {
                        this.b_calendario = 1;
                    }
                }
            });
        },
        listarestablecimientos() {
            var url = "/api/esttodo/" + this.usuario.id_empresa;
            axios.get(url).then(res => {
                this.arrayestablecimientos = res.data;
            });
        },
        listarpunto_emision(id) {
            this.arraypunto_emision = [];
            var url = "/api/pttodo/" + this.usuario.id_empresa + "?id=" + id;
            axios.get(url).then(res => {
                this.arraypunto_emision = res.data;
            });
        },
        guardarm() {
            var todo = this.items;
            // var todo = this.checkBox1;
            var url = "/api/guardarmodulo";
            var datos = {
                iduser: this.$route.params.id,
                checkBox1: todo
            };
            axios.post(url, datos).then(res => {
                console.log(res.data);
            });
        },
        guardar() {
            if (this.validar()) {
                return;
            }
            var url = "/api/regusuario";
            var datos = {
                nombre: this.nombre,
                apellido: this.apellido,
                password: this.password,
                email: this.email,
                establecimeinto: this.establecimeinto,
                punto_emision: this.punto_emision,
                rol: this.rol,
                estado: this.estado,
                filtro_list: this.filtro_list,
                empresa: this.usuario.id_empresa,
                usuario: this.usuario.id,
                telefono: this.telefono,
                //items
                items: this.items
            };
            axios.post(url, datos).then(res => {
                this.$vs.notify({
                    title: "Registro guardado",
                    text: "Registro guardado exitosamente",
                    color: "success"
                });

                this.$router.push("/administrar/usuarios");
            });
        },
        editar() {
            if (this.validar()) {
                return;
            }
            var url = "/api/editarregusuario";
            var datos = {
                id: this.$route.params.id,
                nombre: this.nombre,
                apellido: this.apellido,
                password: this.password,
                email: this.email,
                establecimeinto: this.establecimeinto,
                punto_emision: this.punto_emision,
                rol: this.rol,
                estado: this.estado,
                filtro_list: this.filtro_list,                
                empresa: this.usuario.id_empresa,
                usuario: this.usuario.id,
                telefono: this.telefono,
                //items
                items: this.items
            };
            this.$vs.notify({
                title: "Registro guardado",
                text: "Registro guardado exitosamente",
                color: "success"
            });
            axios
                .post(url, datos)
                .then(res => {
                    this.$router.push("/administrar/usuarios");
                })
                .catch(error => {
                    console.log(error);
                });
        },
        validar() {
            this.error = 0;
            this.errornombre = [];
            this.errorapellido = [];
            this.errorpassword = [];
            this.errorrepassword = [];
            this.erroremail = [];
            this.errorestablecimeinto = [];
            this.errorpunto_emision = [];
            this.errorrol = [];
            this.errorestado = [];

            if (!this.nombre) {
                this.errornombre.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.apellido) {
                this.errorapellido.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.$route.params.id) {
                if (!this.password) {
                    this.errorpassword.push("Campo obligatorio");
                    this.error = 1;
                }
                if (this.password != this.repassword) {
                    this.errorrepassword.push("Las contraseñas no coinciden");
                    this.error = 1;
                }
            }

            if (!this.email) {
                this.erroremail.push("Campo obligatorio");
                this.error = 1;
            }
            if (this.establecimeinto == null) {
                this.errorestablecimeinto.push("Campo obligatorio");
                this.error = 1;
            }
            if (!this.punto_emision == null) {
                this.errorpunto_emision.push("Campo obligatorio");
                this.error = 1;
            }
            if (this.error) {
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

            return this.error;
        },
        todos() {
            //recupera los roles o permisos generales de la empresa
            //este metodo es primordial para los permisos
            //al no existir permisos de empresa escogera el general
            axios
                .get("/api/todos-roles/" + this.usuario.id_empresa)
                .then(res => {
                    this.items = res.data;
                    this.verificaritems();
                })
                .catch(err => {
                    console.log(err);
                });
        },
        administrar() {
            if (!this.b_administrar == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 1) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 1) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        contabilidad() {
            if (!this.b_contabilidad == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 2) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 2) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        facturacion() {
            if (!this.b_facturacion == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 3) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 3) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        compras() {
            if (!this.b_compras == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 4) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 4) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        inventario() {
            if (!this.b_inventario == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 5) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 5) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        nomina() {
            if (!this.b_nomina == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 6) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 6) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        importacion() {
            if (!this.b_importacion == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 7) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 7) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        produccion() {
            if (!this.b_produccion == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 8) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 8) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        activos() {
            if (!this.b_activos == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 9) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 9) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        salud(){
            if (!this.b_salud == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 10) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 10) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        },
        calendario() {
            if (!this.b_calendario == 1) {
                this.items.forEach(el => {
                    if (el.lugar == 11) {
                        el.ver = 1;
                        el.editar = 1;
                        el.crear = 1;
                        el.eliminar = 1;
                    }
                });
            } else {
                this.items.forEach(el => {
                    if (el.lugar == 11) {
                        el.ver = 0;
                        el.editar = 0;
                        el.crear = 0;
                        el.eliminar = 0;
                    }
                });
            }
        }
    },
    mounted() {
        this.listarestablecimientos();
        if (this.$route.params.id >= 1) {
            this.listar();
        } else {
            this.todos();
        }
    }
};
</script>
<style>
.vs-popup {
    width: 800px !important;
}
.text-base {
    text-align: center !important;
}
.px-3 {
    text-align: center;
}
.px-3:nth-child(1) {
    text-align: left !important;
}
.text-base:nth-child(1) {
    text-align: left !important;
}

.vs-checkbox-primary {
    display: inline-flex;
}
.selectall {
    position: absolute;
    margin-top: 8px;
    margin-left: 20px;
}
</style>
