<template>
    <vx-card title="Proceso de Producción" class="mt-10">
        <vs-divider>Información de Proceso de Producción</vs-divider>
        <vs-divider />
        <div class="vx-row sm:w-full w-full mt-5 tabproductos">
            <vs-tabs alignment="fixed">
                <!-- Orden de produccion-->
                <vs-tab
                    class="vx-col"
                    label="Orden"
                    icon-pack="feather"
                    icon="icon-layers"
                >
                    <vs-divider>Registro de Orden de Producción</vs-divider>
                    <vs-divider position="left"
                        >Información de Orden</vs-divider
                    >
                    <div class="vx-row">
                        <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
                            <vs-input
                                disabled
                                class="w-full txt-center"
                                label="Número de Orden"
                                v-model="num_orden"
                            />
                            <div v-show="errororden" v-if="!num_orden">
                                <div
                                    v-for="err in errornum_orden"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col md:w-3/5 sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full"
                                label="Descripción"
                                v-model="descrip_orden"
                                :disabled="recuperaorden"
                            />
                            <div v-show="errororden" v-if="!descrip_orden">
                                <div
                                    v-for="err in errordescrip_orden"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full txt-center"
                                disabled
                                label="Fecha de Inicio"
                                v-model="f_ini_orden"
                            />
                        </div>
                        <!--Orden Produccion Añadir Productos -->
                        <vs-divider position="left"
                            >Productos a Producir</vs-divider
                        >
                        <a
                            class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
                            v-if="!recuperaorden && !continprod.length"
                            @click="(popupprod = true), listarp(buscarp)"
                            >Añadir Productos</a
                        >
                        <div v-show="errororden" v-if="!continprod.length">
                            <div
                                v-for="err in errorordenproduct"
                                :key="err"
                                v-text="err"
                                class="text-danger"
                            ></div>
                        </div>
                        <div class="vx-col md:w-full sm:w-full w-full mb-6">
                            <vs-table :data="continprod">
                                <template slot="thead">
                                    <vs-th>Código Producto</vs-th>
                                    <vs-th class="text-center"
                                        >Nombre Producto</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Fórmula de Producción</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad a Producir</vs-th
                                    >
                                    <vs-th
                                        v-if="!recuperaorden"
                                        class="text-center"
                                    ></vs-th>
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td
                                            style="width:10%!important;"
                                            v-if="data[indextr].cod_alterno"
                                            >{{
                                                data[indextr].cod_alterno
                                            }}</vs-td
                                        ><vs-td
                                            style="width:10%!important;"
                                            v-else
                                            >{{
                                                data[indextr].cod_principal
                                            }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:30%!important;"
                                            :data="data[indextr].nombre"
                                            >{{ data[indextr].nombre }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:30%!important;"
                                            :data="data[indextr].form_prod"
                                            >{{
                                                data[indextr].form_prod
                                            }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:20%!important;"
                                        >
                                            <vs-input
                                                class="w-full txt-center"
                                                @keyup="recalculo()"
                                                v-model="tr.cant_prod"
                                                :disabled="recuperaorden"
                                            />
                                            <div
                                                v-show="errororden"
                                                v-if="!tr.cant_prod"
                                            >
                                                <div
                                                    v-for="err in tr.errorcant_prod"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            v-if="!recuperaorden"
                                            class="text-center"
                                            style="width=10%!important;"
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
                                                    @click="
                                                        eliminarp(
                                                            tr.id_form_prod,
                                                            indextr
                                                        )
                                                    "
                                                />
                                            </vx-tooltip>
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                        <!--Orden Produccion Añadir Productos -->
                        <vs-divider />
                        <vs-divider position="left" v-if="continprod.length > 0"
                            >Ingredientes Necesarios para Producción</vs-divider
                        >
                        <a
                            v-if="
                                !recuperaorden &&
                                    continprod.length > 0 &&
                                    bodega_orden
                            "
                            class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
                            @click="
                                (popunewingredorden = true),
                                    listnewproces(buscari)
                            "
                            >Añadir Ingredientes Extra</a
                        >
                        <div
                            class="vx-col md:w-1/3 sm:w-1/3 w-1/3 ml-auto mb-3"
                            v-if="continprod.length > 0"
                        >
                            <vs-select
                                :disabled="recuperaorden"
                                label="Proyecto: "
                                class="selectExample w-full"
                                v-model="proyecto_orden"
                                @change="bindProyecto"
                            >
                                <vs-select-item
                                    :key="res.id_proyecto"
                                    :value="res.id_proyecto"
                                    :text="res.descripcion"
                                    v-for="res in contproyect"
                                />
                            </vs-select>
                            <div v-show="errororden" v-if="!proyecto_orden">
                                <div
                                    v-for="err in errororden_proyect"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div
                            class="vx-col md:w-1/3 sm:w-1/3 w-1/3 mr-auto mb-3"
                            v-if="continprod.length > 0"
                        >
                            {{ buscarstockingred }}
                            {{ buscarstockingred2 }}
                            <vs-select
                                :disabled="recuperaorden"
                                label="Bodega para Ingredientes: "
                                class="selectExample w-full"
                                v-model="bodega_orden"
                            >
                                <vs-select-item
                                    :key="res.id_bodega"
                                    :value="res.id_bodega"
                                    :text="res.nombre"
                                    v-for="res in contenidobodegas"
                                />
                            </vs-select>
                            <div v-show="errororden" v-if="!bodega_orden">
                                <div
                                    v-for="err in errororden_bodega"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <div
                            class="vx-col md:w-full sm:w-full w-full mb-6"
                            v-if="continprod.length > 0"
                        >
                            <vs-table :data="contingred">
                                <template slot="thead">
                                    <vs-th>Cod.</vs-th>
                                    <vs-th class="text-center"
                                        >Nombre Ingrediente</vs-th
                                    >
                                    <vs-th class="text-center" v-if="contproyect.length>1"
                                        >Proyecto</vs-th
                                    >
                                    <vs-th class="text-center" v-if="contenidobodegas.length>1"
                                        >Bodega</vs-th
                                    >
                                    <vs-th
                                        v-if="!recuperaorden"
                                        class="text-center"
                                        >Stock Disponible</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad Necesaria</vs-th
                                    >
                                    <vs-th
                                        v-if="!recuperaorden"
                                        class="text-center"
                                        >Saldo</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Unitario</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Total</vs-th
                                    >
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :key="index"
                                        v-for="(tr, index) in data"
                                    >
                                        <vs-td
                                            style="width:5%!important;"
                                            v-if="tr.cod_alterno"
                                            >{{ tr.cod_alterno }}</vs-td
                                        ><vs-td
                                            style="width:5%!important;"
                                            v-else
                                            >{{ tr.cod_principal }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:15%!important;"
                                            :data="tr.nombre"
                                            >{{ tr.nombre }}
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            v-if="contproyect.length>1"
                                            >
                                            <vs-select
                                                :disabled="recuperaorden"
                                                class="selectExample w-full"
                                                v-model="tr.id_proyecto"
                                            >
                                                <vs-select-item
                                                    :key="res.id_proyecto"
                                                    :value="res.id_proyecto"
                                                    :text="res.descripcion"
                                                    v-for="res in contproyect"
                                                />
                                            </vs-select>
                                            <div v-show="errororden" v-if="!proyecto_orden">
                                                <div
                                                    v-for="err in errororden_proyect"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            v-if="contenidobodegas.length>1"
                                            >
                                            <vs-select
                                                :disabled="tr.sector != 1 || recuperaorden"
                                                class="selectExample w-full"
                                                v-model="tr.id_bodega"
                                                @change="index_ingrediente = index"
                                            >
                                                <vs-select-item
                                                    :key="res.id_bodega"
                                                    :value="res.id_bodega"
                                                    :text="res.nombre"
                                                    v-for="res in contenidobodegas"
                                                />
                                            </vs-select>
                                            <div v-show="errororden" v-if="!bodega_orden">
                                                <div
                                                    v-for="err in errororden_bodega"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            v-if="!recuperaorden"
                                            class="text-center"
                                            style="width:8%!important;"
                                            :data="tr.stock"
                                            >{{ tr.stock }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:8%!important;"
                                            :data="tr.canti"
                                            @change="calsaldo(tr)"
                                        >
                                            <vs-input
                                                :disabled="recuperaorden"
                                                class="w-full txt-center"
                                                v-model="tr.canti"
                                            />
                                        </vs-td>
                                        <vs-td
                                            v-if="!recuperaorden"
                                            class="text-center"
                                            style="width:8%!important;"
                                            :data="tr.saldo"
                                        >
                                            <span style="display:none">{{
                                                (tr.saldo = (
                                                    tr.stock - tr.canti
                                                ).toFixed(2))
                                            }}</span>
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.saldo"
                                            />
                                        </vs-td>
                                        <vs-td
                                            v-if="!recuperaorden"
                                            class="text-center"
                                            style="width:8%!important;"
                                            :data="tr.costo_unitario"
                                        >
                                            <vs-input
                                                :disabled="tr.sector == 1"
                                                class="w-full txt-center"
                                                v-model="tr.costo_unitario"
                                                @keypress="solonumeros($event)"
                                            />
                                            <div
                                                v-show="errororden"
                                                v-if="!tr.costo_unitario"
                                            >
                                                <div
                                                    v-for="err in tr.errorcosto_unitario"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            v-else
                                            class="text-center"
                                            style="width:8%!important;"
                                            :data="tr.costo_unitario"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.costo_unitario"
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:8%!important;"
                                            :data="tr.costo_total"
                                        >
                                            <span style="display:none">{{
                                                (tr.costo_total = (
                                                    tr.canti * tr.costo_unitario
                                                ).toFixed(4))
                                            }}</span>
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.costo_total"
                                            />
                                        </vs-td>
                                        <vs-td style="width:5%!important;">
                                            <feather-icon
                                                v-if="!recuperaorden"
                                                icon="TrashIcon"
                                                svgClasses="w-5 h-5 hover:text-danger stroke-current"
                                                class="pointer"
                                                @click="
                                                    eliminaringredorden(index)
                                                "
                                            />
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                        <div
                            class="vx-col w-1/3 ml-auto mt-6 text-center"
                            v-if="
                                continprod.length > 0 && contingred.length > 0
                            "
                        >
                            <vs-button
                                v-if="!recuperaorden"
                                color="success"
                                type="border"
                                :disabled="disabled_guardar"
                                @click="guardarorden()"
                                >Guardar</vs-button
                            >
                        </div>
                        <div
                            class="vx-col w-1/3 mr-auto mt-6 text-center"
                            v-if="
                                continprod.length > 0 && contingred.length > 0
                            "
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/produccion/proceso-produccion"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </vs-tab>
                <!-- Proceso Produccion-->
                <vs-tab
                    class="vx-col"
                    label="Proceso"
                    icon-pack="feather"
                    icon="icon-arrow-up"
                >
                    <vs-divider position="left"
                        >Información de Proceso de Producción</vs-divider
                    >
                    <div class="vx-row ">
                        <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
                            <vs-input
                                disabled
                                class="w-full txt-center"
                                label="Número de Orden"
                                v-model="num_orden"
                            />
                        </div>
                        <div class="vx-col md:w-3/5 sm:w-full w-full mb-6">
                            <vs-input
                                disabled
                                class="w-full"
                                label="Descripción"
                                v-model="descrip_orden"
                            />
                        </div>
                        <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full txt-center"
                                disabled
                                label="Fecha de Proceso"
                                v-model="f_proces"
                            />
                        </div>
                        <vs-divider position="left"
                            >Productos a Producir</vs-divider
                        >
                        <div class="vx-col md:w-full sm:w-full w-full mb-6">
                            <vs-table :data="continprod">
                                <template slot="thead">
                                    <vs-th>Código Producto</vs-th>
                                    <vs-th class="text-center"
                                        >Nombre Producto</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Fórmula de Producción</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad a Producir</vs-th
                                    >
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td
                                            style="width:10%!important;"
                                            v-if="data[indextr].cod_alterno"
                                            >{{
                                                data[indextr].cod_alterno
                                            }}</vs-td
                                        ><vs-td
                                            style="width:10%!important;"
                                            v-else
                                            >{{
                                                data[indextr].cod_principal
                                            }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:30%!important;"
                                            :data="data[indextr].nombre"
                                            >{{ data[indextr].nombre }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:30%!important;"
                                            :data="data[indextr].form_prod"
                                            >{{
                                                data[indextr].form_prod
                                            }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:20%!important;"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.cant_prod"
                                            />
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                        <!-- Inicio Agregar Ingredientes-->
                        <vs-divider />
                        <vs-divider position="left"
                            >Ingredientes Necesarios para Producción</vs-divider
                        >
                        <a
                            v-if="!recuperaproces && contingredprod.length > 0"
                            class="flex items-center cursor-pointer mb-4 ml-4 mr-4"
                            @click="
                                (popunewingred = true), listnewproces(buscari)
                            "
                            >Añadir Nuevos Ingredientes</a
                        >
                        <!-- INVOICE TASKS TABLE -->
                        <div
                            class="vx-col md:w-1/3 sm:w-1/3 w-1/3 ml-auto mb-3"
                            v-if="contingredprod.length > 0"
                        >
                            <vs-select
                                disabled
                                label="Proyecto: "
                                class="selectExample w-full"
                                v-model="proyecto_orden"
                            >
                                <vs-select-item
                                    :key="res.id_proyecto"
                                    :value="res.id_proyecto"
                                    :text="res.descripcion"
                                    v-for="res in contproyect"
                                />
                            </vs-select>
                        </div>
                        <div
                            class="vx-col md:w-1/3 sm:w-1/3 w-1/3 mr-auto mb-3"
                            v-if="contingredprod.length > 0"
                        >
                            <vs-select
                                disabled
                                label="Bodega para Ingredientes: "
                                class="selectExample w-full"
                                v-model="bodega_orden"
                            >
                                <vs-select-item
                                    :key="res.id_bodega"
                                    :value="res.id_bodega"
                                    :text="res.nombre"
                                    v-for="res in contenidobodegas"
                                />
                            </vs-select>
                        </div>
                        <div
                            class="vx-col md:w-full sm:w-full w-full mb-6"
                            v-if="contingredprod.length > 0"
                        >
                            <vs-table :data="contingredprod">
                                <template slot="thead">
                                    <vs-th>Cod</vs-th>
                                    <vs-th class="text-center"
                                        >Nombre Ingrediente</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Proyecto</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Bodega</vs-th
                                    >
                                    <vs-th
                                        class="text-center"
                                        v-if="!recuperaproces"
                                        >Stock Disponible</vs-th
                                    >
                                    <vs-th
                                        class="text-center"
                                        v-if="!recuperaproces"
                                        >Cantidad Orden</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad Extra</vs-th
                                    >
                                    <vs-th
                                        class="text-center"
                                        v-if="!recuperaproces"
                                        >Cantidad Total</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Unitario</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Total</vs-th
                                    >
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td
                                            style="width:5%!important;"
                                            v-if="tr.cod_alterno"
                                            >{{ tr.cod_alterno }}</vs-td
                                        ><vs-td
                                            style="width:5%!important;"
                                            v-else
                                            >{{ tr.cod_principal }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:35%!important;"
                                            :data="tr.nombre"
                                            >{{ tr.nombre }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            >
                                            <vs-select
                                                disabled
                                                class="selectExample w-full"
                                                v-model="tr.id_proyecto"
                                            >
                                                <vs-select-item
                                                    :key="res.id_proyecto"
                                                    :value="res.id_proyecto"
                                                    :text="res.descripcion"
                                                    v-for="res in contproyect"
                                                />
                                            </vs-select>
                                            <div v-show="errororden" v-if="!proyecto_orden">
                                                <div
                                                    v-for="err in errororden_proyect"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            >
                                            <vs-select
                                                disabled
                                                class="selectExample w-full"
                                                v-model="tr.id_bodega"
                                            >
                                                <vs-select-item
                                                    :key="res.id_bodega"
                                                    :value="res.id_bodega"
                                                    :text="res.nombre"
                                                    v-for="res in contenidobodegas"
                                                />
                                            </vs-select>
                                            <div v-show="errororden" v-if="!bodega_orden">
                                                <div
                                                    v-for="err in errororden_bodega"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            v-if="!recuperaproces"
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="tr.stock"
                                            >{{ tr.stock }}</vs-td
                                        >
                                        <vs-td
                                            v-if="!recuperaproces"
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="tr.cantidad_orden"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.cantidad_orden"
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="tr.cantidad_produccion"
                                        >
                                            <vs-input
                                                :disabled="recuperaproces"
                                                class="w-full txt-center"
                                                v-model="tr.cantidad_produccion"
                                                @keypress="solonumeros($event)"
                                                @input="
                                                    asigcantautomatic(indextr)
                                                "
                                            />
                                            <div
                                                v-show="errorproces"
                                                v-if="!tr.cantidad_produccion"
                                            >
                                                <div
                                                    v-for="err in tr.errorcantidad_produccion"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            v-if="!recuperaproces"
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="tr.cantotproces"
                                        >
                                            <span style="display:none">{{
                                                (tr.cantotproces = (
                                                    parseFloat(
                                                        tr.cantidad_orden
                                                    ) +
                                                    parseFloat(
                                                        tr.cantidad_produccion
                                                    )
                                                ).toFixed(4))
                                            }}</span>
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.cantotproces"
                                            />
                                        </vs-td>
                                        <vs-td
                                            v-if="!recuperaproces"
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="tr.costo_unitario_produccion"
                                        >
                                            <vs-input
                                                :disabled="tr.sector == 1"
                                                class="w-full txt-center"
                                                v-model="
                                                    tr.costo_unitario_produccion
                                                "
                                                @keypress="solonumeros($event)"
                                            />
                                            <div
                                                v-show="errorproces"
                                                v-if="
                                                    !tr.costo_unitario_produccion
                                                "
                                            >
                                                <div
                                                    v-for="err in tr.errorcosto_unitario_produccion"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            v-else
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="tr.costo_unitario_produccion"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="
                                                    tr.costo_unitario_produccion
                                                "
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="tr.costo_total_produccion"
                                        >
                                            <span style="display:none">{{
                                                (tr.costo_total_produccion = (
                                                    parseFloat(
                                                        tr.costo_unitario_produccion
                                                    ) *
                                                    parseFloat(
                                                        tr.cantidad_produccion
                                                    )
                                                ).toFixed(4))
                                            }}</span>
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="
                                                    tr.costo_total_produccion
                                                "
                                            />
                                        </vs-td>
                                        <vx-tooltip
                                            v-if="tr.cantidad_produccion > 0"
                                            text="Asignar Cantidades"
                                            position="top"
                                            class="icono_eliminar"
                                        >
                                            <feather-icon
                                                icon="FilePlusIcon"
                                                svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                                class="icono_eliminar"
                                                @click="
                                                    asignarcantidades(indextr)
                                                "
                                        /></vx-tooltip>
                                        <vx-tooltip
                                            v-if="
                                                !recuperaproces &&
                                                    tr.editable == true
                                            "
                                            text="Eliminar Ingrediente"
                                            position="top"
                                            class="icono_eliminar"
                                        >
                                            <feather-icon
                                                icon="TrashIcon"
                                                svgClasses="w-6 h-6 hover:text-primary stroke-current cursor-pointer"
                                                class="icono_eliminar"
                                                @click="
                                                    eliminar_ingrediente(
                                                        indextr
                                                    )
                                                "
                                        /></vx-tooltip>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                        <div
                            class="vx-col w-1/3 ml-auto mt-6 text-center"
                            v-if="
                                continprod.length > 0 &&
                                    contingredprod.length > 0
                            "
                        >
                            <vs-button
                                v-if="!recuperaproces"
                                color="success"
                                type="border"
                                :disabled="disabled_guardar"
                                @click="guardarproces()"
                                >Guardar</vs-button
                            >
                        </div>
                        <div
                            class="vx-col w-1/3 mr-auto mt-6 text-center"
                            v-if="
                                continprod.length > 0 &&
                                    contingredprod.length > 0
                            "
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/produccion/proceso-produccion"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </vs-tab>
                <!--------------------------------------------- Liquidación de Producción-------------------------------------------------------->
                <vs-tab
                    class="vx-col"
                    label="Liquidación"
                    icon-pack="feather"
                    icon="icon-repeat"
                >
                    <vs-divider position="left"
                        >Información de Liquidación de Proceso</vs-divider
                    >
                    <div class="vx-row">
                        <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
                            <vs-input
                                disabled
                                class="w-full txt-center"
                                label="Número de Orden"
                                v-model="num_orden"
                            />
                        </div>
                        <div class="vx-col md:w-3/5 sm:w-full w-full mb-6">
                            <vs-input
                                disabled
                                class="w-full"
                                label="Descripción"
                                v-model="descrip_orden"
                            />
                        </div>
                        <div class="vx-col md:w-1/5 sm:w-full w-full mb-6">
                            <vs-input
                                class="w-full txt-center"
                                disabled
                                label="Fecha de Liquidación"
                                v-model="f_liquid"
                            />
                        </div>
                        <div
                            class="vx-col md:w-1/3 sm:w-1/3 w-1/3 ml-auto mb-3"
                            v-if="
                                continprod.length > 0 &&
                                    contingredliq.length > 0
                            "
                        >
                            <vs-select
                                disabled
                                label="Proyecto: "
                                class="selectExample w-full"
                                v-model="proyecto_orden"
                            >
                                <vs-select-item
                                    :key="res.id_proyecto"
                                    :value="res.id_proyecto"
                                    :text="res.descripcion"
                                    v-for="res in contproyect"
                                />
                            </vs-select>
                        </div>
                        <div
                            class="vx-col md:w-1/3 sm:w-1/3 w-1/3 mr-auto mb-3"
                            v-if="
                                continprod.length > 0 &&
                                    contingredliq.length > 0
                            "
                        >
                            <vs-select
                                :disabled="recuperaliquid"
                                label="Bodega a Ingresar: "
                                class="selectExample w-full"
                                v-model="bodega_liquid"
                            >
                                <vs-select-item
                                    :key="res.id_bodega"
                                    :value="res.id_bodega"
                                    :text="res.nombre"
                                    v-for="res in contenidobodegas"
                                />
                            </vs-select>
                            <div v-show="errorliquid" v-if="!bodega_liquid">
                                <div
                                    v-for="err in errorbodega_liquid"
                                    :key="err"
                                    v-text="err"
                                    class="text-danger"
                                ></div>
                            </div>
                        </div>
                        <vs-divider position="left"
                            >Productos a Producir</vs-divider
                        >
                        <div class="vx-col md:w-full sm:w-full w-full mb-6">
                            <vs-table :data="continprod">
                                <template slot="thead">
                                    <vs-th>Código Producto</vs-th>
                                    <vs-th class="text-center"
                                        >Nombre Producto</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Fórmula de Producción</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad Producida</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Unitario</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Total</vs-th
                                    >
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td
                                            style="width:10%!important;"
                                            v-if="data[indextr].cod_alterno"
                                            >{{
                                                data[indextr].cod_alterno
                                            }}</vs-td
                                        ><vs-td
                                            style="width:10%!important;"
                                            v-else
                                            >{{
                                                data[indextr].cod_principal
                                            }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:35%!important;"
                                            :data="data[indextr].nombre"
                                            >{{ data[indextr].nombre }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:30%!important;"
                                            :data="data[indextr].form_prod"
                                            >{{
                                                data[indextr].form_prod
                                            }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.cant_prod"
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.costo_unitario"
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.costo_total"
                                            />
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>

                        <!-- Inicio Agregar Ingredientes-->
                        <vs-divider />
                        <vs-divider position="left"
                            >Ingredientes Usados para Producción</vs-divider
                        >
                        <!-- INVOICE TASKS TABLE -->
                        <div class="vx-col md:w-full sm:w-full w-full mb-6">
                            <vs-table :data="contingredliq">
                                <template slot="thead">
                                    <vs-th>Cod</vs-th>
                                    <vs-th class="text-center"
                                        >Nombre Ingrediente</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Proyecto</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Bodega</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad Orden</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad Proceso</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Cantidad Total</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Unitario Final</vs-th
                                    >
                                    <vs-th class="text-center"
                                        >Costo Total Final</vs-th
                                    >
                                </template>
                                <template slot-scope="{ data }">
                                    <vs-tr
                                        :key="indextr"
                                        v-for="(tr, indextr) in data"
                                    >
                                        <vs-td
                                            style="width:5%!important;"
                                            v-if="tr.cod_alterno"
                                            >{{ tr.cod_alterno }}</vs-td
                                        ><vs-td
                                            style="width:5%!important;"
                                            v-else
                                            >{{ tr.cod_principal }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            style="width:45%!important;"
                                            :data="data[indextr].nombre"
                                            >{{ data[indextr].nombre }}</vs-td
                                        >
                                        <vs-td
                                            class="text-center"
                                            >
                                            <vs-select
                                                disabled
                                                class="selectExample w-full"
                                                v-model="tr.id_proyecto"
                                            >
                                                <vs-select-item
                                                    :key="res.id_proyecto"
                                                    :value="res.id_proyecto"
                                                    :text="res.descripcion"
                                                    v-for="res in contproyect"
                                                />
                                            </vs-select>
                                            <div v-show="errororden" v-if="!proyecto_orden">
                                                <div
                                                    v-for="err in errororden_proyect"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            >
                                            <vs-select
                                                disabled
                                                class="selectExample w-full"
                                                v-model="tr.id_bodega"
                                            >
                                                <vs-select-item
                                                    :key="res.id_bodega"
                                                    :value="res.id_bodega"
                                                    :text="res.nombre"
                                                    v-for="res in contenidobodegas"
                                                />
                                            </vs-select>
                                            <div v-show="errororden" v-if="!bodega_orden">
                                                <div
                                                    v-for="err in errororden_bodega"
                                                    :key="err"
                                                    v-text="err"
                                                    class="text-danger"
                                                ></div>
                                            </div>
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="data[indextr].cantidad_orden"
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.cantidad_orden"
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="
                                                data[indextr]
                                                    .cantidad_produccion
                                            "
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="tr.cantidad_produccion"
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="
                                                data[indextr]
                                                    .cantidad_liquidacion
                                            "
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="
                                                    tr.cantidad_liquidacion
                                                "
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="
                                                data[indextr]
                                                    .costo_unitario_liquidacion
                                            "
                                        >
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="
                                                    tr.costo_unitario_liquidacion
                                                "
                                            />
                                        </vs-td>
                                        <vs-td
                                            class="text-center"
                                            style="width:10%!important;"
                                            :data="
                                                data[indextr]
                                                    .costo_total_liquidacion
                                            "
                                        >
                                            <span style="display:none">{{
                                                (tr.costo_total_liquidacion = (
                                                    tr.cantidad_liquidacion *
                                                    tr.costo_unitario_liquidacion
                                                ).toFixed(4))
                                            }}</span>
                                            <vs-input
                                                disabled
                                                class="w-full txt-center"
                                                v-model="
                                                    tr.costo_total_liquidacion
                                                "
                                            />
                                        </vs-td>
                                    </vs-tr>
                                </template>
                            </vs-table>
                        </div>
                        <div
                            class="vx-col w-1/3 ml-auto mt-6 text-center"
                            v-if="
                                continprod.length > 0 &&
                                    contingredliq.length > 0
                            "
                        >
                            <vs-button
                                v-if="!recuperaliquid"
                                color="success"
                                type="border"
                                :disabled="disabled_guardar"
                                @click="guardarliquid()"
                                >Guardar</vs-button
                            >
                        </div>
                        <div
                            class="vx-col w-1/3 mr-auto mt-6 text-center"
                            v-if="
                                continprod.length > 0 &&
                                    contingredliq.length > 0
                            "
                        >
                            <vs-button
                                color="danger"
                                type="border"
                                to="/produccion/proceso-produccion"
                                >Cancelar</vs-button
                            >
                        </div>
                    </div>
                </vs-tab>
            </vs-tabs>
            <!-- Popup Agregar Producto-->
            <vs-popup
                classContent="popup-example"
                title="Seleccione el Producto"
                :active.sync="popupprod"
            >
                <div class="vx-col w-full">
                    <vs-input
                        class="mb-4 mr-4 w-full"
                        v-model="buscarp"
                        @keyup="listarp(buscarp)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <vs-table
                        stripe
                        v-model="contenidoprod"
                        @selected="handleSelectedp"
                        :data="arrayproductos"
                    >
                        <template slot="thead">
                            <vs-th>Código</vs-th>
                            <vs-th>Nombre</vs-th>
                            <vs-th>Fórmula de Producción</vs-th>
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
                                <vs-td v-if="tr.cod_alterno">{{
                                    tr.cod_alterno
                                }}</vs-td
                                ><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td v-if="tr.nombre">{{ tr.nombre }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.form_prod">{{
                                    tr.form_prod
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
            <!-- Popup Agregar New Ingrediente Extra Orden-->
            <vs-popup
                classContent="popup-example"
                title="Seleccione el Ingrediente Extra"
                :active.sync="popunewingredorden"
            >
                <div class="vx-col w-full">
                    <vs-input
                        class="mb-4 mr-4 w-full"
                        v-model="buscari"
                        @keyup="listnewproces(buscari)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <vs-table
                        stripe
                        @selected="selectingredorden"
                        :data="contenidoingred"
                    >
                        <template slot="thead">
                            <vs-th style="width:15%;">Código</vs-th>
                            <vs-th style="width:45%;">Nombre</vs-th>
                            <vs-th style="width:20%;">Bodega</vs-th>
                            <vs-th style="width:20%;">Stock Disponible</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                :data="tr"
                                :key="indextr"
                                v-for="(tr, indextr) in data"
                            >
                                <vs-td v-if="tr.cod_alterno">{{
                                    tr.cod_alterno
                                }}</vs-td
                                ><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td v-if="tr.nombre">{{ tr.nombre }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.nombre_bodega">{{ tr.nombre_bodega }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.cantidad">{{
                                    tr.cantidad
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
            </vs-popup>
            <!-- Popup Agregar New Ingrediente Proceso-->
            <vs-popup
                classContent="popup-example"
                title="Seleccione el Ingrediente"
                :active.sync="popunewingred"
            >
                <div class="vx-col w-full">
                    <vs-input
                        class="mb-4 mr-4 w-full"
                        v-model="buscari"
                        @keyup="listnewproces(buscari)"
                        v-bind:placeholder="i18nbuscar"
                    />
                    <vs-table
                        max-items="25"
                        pagination
                        stripe
                        @selected="selectingredprod"
                        :data="contenidoingred"
                    >
                        <template slot="thead">
                            <vs-th style="width:15%;">Código</vs-th>
                            <vs-th style="width:45%;">Nombre</vs-th>
                            <vs-th style="width:20%;">Bodega</vs-th>
                            <vs-th style="width:20%;">Stock Disponible</vs-th>
                        </template>
                        <template slot-scope="{ data }">
                            <vs-tr
                                :data="tr"
                                :key="indextr"
                                v-for="(tr, indextr) in data"
                            >
                                <vs-td v-if="tr.cod_alterno">{{
                                    tr.cod_alterno
                                }}</vs-td
                                ><vs-td v-else>{{ tr.cod_principal }}</vs-td>
                                <vs-td v-if="tr.nombre">{{ tr.nombre }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.nombre_bodega">{{ tr.nombre_bodega }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td v-if="tr.cantidad">{{
                                    tr.cantidad
                                }}</vs-td>
                                <vs-td v-else>-</vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                </div>
            </vs-popup>
            <!-- Popup Asigna Cantidades de Produccion al Producto en Concreto-->
            <vs-popup
                classContent="popup-example"
                title="Asigne las cantidades del Ingrediente al Producto"
                :active.sync="popupcantproces"
            >
                <div class="vx-col w-full" v-if="indexcantprod != null">
                    <vs-table
                        stripe
                        :data="contingredprod[indexcantprod].proceso_cantidad"
                    >
                        <template slot="thead">
                            <vs-th style="width:15%;">Código</vs-th>
                            <vs-th style="width:65%;">Nombre</vs-th>
                            <vs-th style="width:20%;">Cantidades</vs-th>
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
                                <vs-td v-if="tr.nombre">{{ tr.nombre }}</vs-td>
                                <vs-td v-else>-</vs-td>
                                <vs-td>
                                    <vs-input
                                        :disabled="recuperaproces"
                                        class="w-full txt-center"
                                        v-model="tr.cantidad"
                                /></vs-td>
                            </vs-tr>
                        </template>
                    </vs-table>
                    <div class="vx-col w-1/3 ml-auto mr-auto mt-6 text-center">
                        <vs-button
                            v-if="!recuperaproces"
                            color="success"
                            type="border"
                            :disabled="disabled_guardar"
                            @click="guardarasigcantprod()"
                            >Guardar</vs-button
                        >
                        <vs-button
                            v-else
                            color="danger"
                            type="border"
                            @click="popupcantproces = false"
                            >Cerrar</vs-button
                        >
                    </div>
                </div>
            </vs-popup>
        </div>
    </vx-card>
</template>

<script>
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.min.css";
import { Spanish as SpanishLocale } from "flatpickr/dist/l10n/es.js";
import VueUploadMultipleImage from "vue-upload-multiple-image";
import moment from "moment";
import { AgGridVue } from "ag-grid-vue";
import vSelect from "vue-select";
import ActivoFijo from "../../Activos_fijos/ActivoFijo.vue";

const axios = require("axios");
const $ = require("jquery");
export default {
    data() {
        return {
            //variables encabezado
            idrecupera: null,
            recuperaorden: false,
            recuperaproces: false,
            recuperaliquid: false,
            //variables de orden de produccion
            num_orden: "",
            descrip_orden: "",
            f_ini_orden: moment().format("YYYY-MM-DD"),
            proyecto_orden: 1,
            bodega_orden: "",
            cant_prod: "",
            //arrays orden produccion
            contproyect: [],
            contenidobodegas: [],
            arrayproductos: [],
            contenidoprod: [],
            continprod: [],
            contingred: [],
            //variables de proceso produccion
            f_proces: moment().format("YYYY-MM-DD"),
            idprocespro: "",
            //arrays proceso produccion
            contenidobodegaproceso: [],
            contingredprod: [],
            contenidoingred: [],
            //variables liquidacion de proceso
            f_liquid: moment().format("YYYY-MM-DD"),
            bodega_liquid: "",
            //arryas liquidacion produccion
            contingredliq: [],
            //popups
            popupprod: false,
            popunewingredorden: false,
            popunewingred: false,
            popupcantproces: false,
            indexcantprod: null,
            asigcantprod: 0,
            //buscar
            buscarp: "",
            buscari: "",
            i18nbuscar: this.$t("i18nbuscar"),
            //establece calendario español
            configdateTimePicker: {
                locale: SpanishLocale
            },
            idprodform: null,
            contingred1: [],
            ingredid: [],

            //Validacion errores Orden
            errororden: [],
            errornum_orden: [],
            errordescrip_orden: [],
            errorordenproduct: [],
            errorcant_prod: [],
            errororden_proyect: [],
            errororden_bodega: [],
            //Validacion errores Proceso
            errorproces: [],
            errorsaldoproces: false,
            errorsaldoingredproces: [],
            //Validacion errores Liquidacion
            errorliquid: [],
            errorbodega_liquid: [],
            disabled_guardar: false,
            agregar_producto: true,
            index_ingrediente: 0
        };
    },
    //importa calendario español
    components: {
        flatPickr,
        VueUploadMultipleImage,
        AgGridVue,
        "v-select": vSelect,
        ActivoFijo
    },
    computed: {
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        },
        //busca el stock disponible en la bodegas elegidas de los ingredientes para la orden
        buscarstockingred() {
            if(this.contingred.length>0){
                //this.bindBodega();
                for (let i = 0; i < this.contingred.length; i++) {
                    if (this.contingred[i].sector == 1) {
                        //this.contingred[i].id_bodega = this.bodega_orden;
                        for (let j = 0; j < this.contenidobodegas.length; j++) {
                            if (
                                this.contenidobodegas[j].id_bodega ===
                                this.bodega_orden
                            ) {
                                var check = false;
                                for (
                                    let l = 0;
                                    l < this.contenidobodegas[j].stock.length;
                                    l++
                                ) {
                                    if (
                                        this.contenidobodegas[j].stock[l]
                                            .id_producto ===
                                        this.contingred[i].id_producto
                                    ) {
                                        if (
                                            this.contenidobodegas[j].stock[l]
                                                .cantidad -
                                                this.contingred[i].canti >=
                                            0
                                        ) {
                                            check = true;
                                            this.contingred[
                                                i
                                            ].stock = this.contenidobodegas[
                                                j
                                            ].stock[l].cantidad;
                                            this.contingred[
                                                i
                                            ].id_producto_bodega = this.contenidobodegas[
                                                j
                                            ].stock[l].id_producto_bodega;
                                            this.contingred[
                                                i
                                            ].costo_unitario = this.contenidobodegas[
                                                j
                                            ].stock[l].costo_unitario;
                                        } else {
                                            check = true;
                                            this.contingred[
                                                i
                                            ].stock = this.contenidobodegas[
                                                j
                                            ].stock[l].cantidad;
                                            this.contingred[
                                                i
                                            ].id_producto_bodega = null;
                                            this.contingred[
                                                i
                                            ].costo_unitario = this.contenidobodegas[
                                                j
                                            ].stock[l].costo_unitario;
                                            this.$vs.notify({
                                                title: "Bodega seleccionada",
                                                text:
                                                    "no tiene stock suficiente de: " +
                                                    this.contingred[i].nombre,
                                                color: "warning"
                                            });
                                        }
                                    }
                                    if (
                                        l + 1 ==
                                            this.contenidobodegas[j].stock.length &&
                                        check == false
                                    ) {
                                        this.contingred[i].stock = 0;
                                        this.contingred[
                                            i
                                        ].id_producto_bodega = null;
                                        this.contingred[i].costo_unitario = 0;
                                        this.$vs.notify({
                                            title: "Bodega seleccionada",
                                            text:
                                                "no tiene stock de: " +
                                                this.contingred[i].nombre,
                                            color: "danger"
                                        });
                                    }
                                }
                            }
                        }
                    } else {
                        this.contingred[i].stock = "-";
                        this.contingred[i].id_producto_bodega = null;
                        this.contingred[i].saldo = "-";
                    }
                }
            }
        },
        buscarstockingred2() {
            
            if(this.contingred.length>0){

                console.log(this.index_ingrediente);
                let i = this.index_ingrediente;
                if (this.contingred[i].sector == 1) {
                    for (let j = 0; j < this.contenidobodegas.length; j++) {
                        if (
                            this.contenidobodegas[j].id_bodega ===
                            this.contingred[i].id_bodega
                        ) {
                            var check = false;
                            for (
                                let l = 0;
                                l < this.contenidobodegas[j].stock.length;
                                l++
                            ) {
                                if (
                                    this.contenidobodegas[j].stock[l]
                                        .id_producto ===
                                    this.contingred[i].id_producto
                                ) {
                                    if (
                                        this.contenidobodegas[j].stock[l]
                                            .cantidad -
                                            this.contingred[i].canti >=
                                        0
                                    ) {
                                        console.log("entro 2");
                                        check = true;
                                        this.contingred[
                                            i
                                        ].stock = this.contenidobodegas[
                                            j
                                        ].stock[l].cantidad;
                                        console.log(this.contingred[
                                            i
                                        ].stock);
                                        this.contingred[
                                            i
                                        ].id_producto_bodega = this.contenidobodegas[
                                            j
                                        ].stock[l].id_producto_bodega;
                                        this.contingred[
                                            i
                                        ].costo_unitario = this.contenidobodegas[
                                            j
                                        ].stock[l].costo_unitario;
                                    } else {
                                        console.log("entro 1");
                                        check = true;
                                        this.contingred[
                                            i
                                        ].stock = this.contenidobodegas[
                                            j
                                        ].stock[l].cantidad;
                                        this.contingred[
                                            i
                                        ].id_producto_bodega = null;
                                        this.contingred[
                                            i
                                        ].costo_unitario = this.contenidobodegas[
                                            j
                                        ].stock[l].costo_unitario;
                                        this.$vs.notify({
                                            title: "Bodega seleccionada",
                                            text:
                                                "no tiene stock suficiente de: " +
                                                this.contingred[i].nombre,
                                            color: "warning"
                                        });
                                    }
                                }
                                if (
                                    l + 1 ==
                                        this.contenidobodegas[j].stock.length &&
                                    check == false
                                ) {
                                    this.contingred[i].stock = 0;
                                    this.contingred[
                                        i
                                    ].id_producto_bodega = null;
                                    this.contingred[i].costo_unitario = 0;
                                    this.$vs.notify({
                                        title: "Bodega seleccionada",
                                        text:
                                            "no tiene stock de: " +
                                            this.contingred[i].nombre,
                                        color: "danger"
                                    });
                                }
                            }
                        }
                    }
                } else {
                    this.contingred[i].stock = "-";
                    this.contingred[i].id_producto_bodega = null;
                    this.contingred[i].saldo = "-";
                }
            }
        },
        //Coordinar bodega general con bodega individual en ingredientes de orden
        bindBodega(){
            for(let i=0; i<this.contingred.length; i++){
                if(this.contingred[i].sector == 1){
                    this.contingred[i].id_bodega = this.bodega_orden;
                }
            }
        }
    },
    methods: {
        //Funcion lista contenido de proyectos para select
        listproyect() {
            if (this.contproyect.length <= 0) {
                var url = "/api/getproyect/" + this.usuario.id_empresa;
                axios.get(url).then(res => {
                    this.contproyect = res.data;
                });
            }
        },
        //Funciones Orden de Produccion
        listarnumorden() {
            if (!this.$route.params.id) {
                var url = "/api/traercodproducion/" + this.usuario.id_empresa;
                axios
                    .get(url)
                    .then(res => {
                        this.num_orden = res.data.num_orden;
                    })
                    .catch(err => {
                        console.log(err);
                    });
            }
        },
        //lista productos
        listarp(buscarp) {
            var url =
                "/api/producformu/" +
                this.usuario.id_empresa +
                "?buscar=" +
                buscarp;
            axios.get(url).then(res => {
                var respuesta = res.data;
                this.arrayproductos = respuesta.recupera;
                //this.arrayprodprodu = respuesta.recupera;
            });
        },
        //lista bodegas
        liststockbodegaingred() {
            axios
                .get(
                    `/api/ordenstockbodegaingred?id_empresa=${this.usuario.id_empresa}&id_establecimiento=${this.usuario.id_establecimiento}`
                )
                .then(res => {
                    this.contenidobodegas = res.data;
                });
        },
        //seleccionador de producto
        handleSelectedp(tr) {
            if(this.agregar_producto){
                this.agregar_producto = false;
                this.popupprod = false;
                this.continprod.push({
                    id: tr.id_producto,
                    cod_alterno: tr.cod_alterno,
                    cod_principal: tr.cod_principal,
                    nombre: tr.nombre,
                    cant_prod: 1,
                    id_form_prod: tr.id_form_prod,
                    form_prod: tr.form_prod
                });
                this.idprodform = tr.id_form_prod;
                this.listari();
            }
        },
        listari() {
            axios
                .get(
                    "/api/traerprocesingred?id_formula_produccion=" +
                        this.idprodform
                )
                .then(res => {
                    if (this.contingred.length == 0) {
                        this.contingred = res.data;
                        for (let s in this.contingred) {
                            this.contingred[s].id_proyecto = 1;
                            this.contingred[s].stock = 0;
                            this.contingred[s].nuevaclave = [
                                {
                                    formula: this.contingred[s]
                                        .id_formula_produccion,
                                    valor: this.contingred[s].cant_unit_prod
                                }
                            ];
                        }
                    } else {
                        for (let s in this.contingred) {
                            for (let i in res.data) {
                                if (
                                    this.contingred[s].id_producto ==
                                    res.data[i].id_producto
                                ) {
                                    this.contingred[s].canti =
                                        this.contingred[s].canti +
                                        res.data[i].canti;
                                    this.contingred[s].nuevaclave.push({
                                        formula:
                                            res.data[i].id_formula_produccion,
                                        valor: res.data[i].cant_unit_prod
                                    });
                                }
                            }
                        }
                        res.data.forEach(el => {
                            let recu = this.contingred.find(
                                e => e.id_producto == el.id_producto
                            );
                            if (!recu) {
                                this.contingred.push(el);
                                el.id_proyecto = 1;
                                el.nuevaclave = [
                                    {
                                        formula: el.id_formula_produccion,
                                        valor: el.cant_unit_prod
                                    }
                                ];
                            }
                        });
                    }
                })
                .catch(err => {
                    console.log(err);
                });
        },
        recalculo() {
            for (let t in this.contingred) {
                this.contingred[t].canti = 0;
            }
            for (let d in this.continprod) {
                for (let t in this.contingred) {
                    const res = this.contingred[t].nuevaclave.find(
                        e => e.formula === this.continprod[d].id_form_prod
                    );
                    if (res) {
                        if (!this.continprod[d].cant_prod) {
                            this.contingred[t].canti = 0;
                        } else {
                            this.contingred[t].canti =
                                parseFloat(this.contingred[t].canti) +
                                parseFloat(res.valor) *
                                    parseFloat(this.continprod[d].cant_prod);
                        }
                    }
                }
            }
        },
        eliminarp(id, tr) {
            agregar_producto = true;
            for (let t = this.contingred.length - 1; t >= 0; t = t - 1) {
                const res = this.contingred[t].nuevaclave.find(
                    e => e.formula == id
                );
                if (res) {
                    if (this.contingred[t].nuevaclave.length <= 1) {
                        this.contingred.splice(t, 1);
                    } else {
                        for (
                            let u = this.contingred[t].nuevaclave.length - 1;
                            u >= 0;
                            u = u - 1
                        ) {
                            if (
                                this.contingred[t].nuevaclave[u].formula == id
                            ) {
                                this.contingred[t].nuevaclave.splice(u, 1);
                                break;
                            }
                        }
                    }
                    setTimeout(() => {
                        this.recalculo();
                    }, 200);
                }
            }
            this.continprod.splice(tr, 1);
        },
        /*buscarstockingred2(i) {
            
            if(this.contingred.length>0){
                //let i = 3;
                if (this.contingred[i].sector == 1) {
                    for (let j = 0; j < this.contenidobodegas.length; j++) {
                        if (
                            this.contenidobodegas[j].id_bodega ===
                            this.contingred[i].id_bodega
                        ) {
                            var check = false;
                            for (
                                let l = 0;
                                l < this.contenidobodegas[j].stock.length;
                                l++
                            ) {
                                if (
                                    this.contenidobodegas[j].stock[l]
                                        .id_producto ===
                                    this.contingred[i].id_producto
                                ) {
                                    if (
                                        this.contenidobodegas[j].stock[l]
                                            .cantidad -
                                            this.contingred[i].canti >=
                                        0
                                    ) {
                                        console.log("entro 2");
                                        check = true;
                                        this.contingred[
                                            i
                                        ].stock = this.contenidobodegas[
                                            j
                                        ].stock[l].cantidad;
                                        console.log(this.contingred[
                                            i
                                        ].stock);
                                        this.contingred[
                                            i
                                        ].id_producto_bodega = this.contenidobodegas[
                                            j
                                        ].stock[l].id_producto_bodega;
                                        this.contingred[
                                            i
                                        ].costo_unitario = this.contenidobodegas[
                                            j
                                        ].stock[l].costo_unitario;
                                    } else {
                                        console.log("entro 1");
                                        check = true;
                                        this.contingred[
                                            i
                                        ].stock = this.contenidobodegas[
                                            j
                                        ].stock[l].cantidad;
                                        this.contingred[
                                            i
                                        ].id_producto_bodega = null;
                                        this.contingred[
                                            i
                                        ].costo_unitario = this.contenidobodegas[
                                            j
                                        ].stock[l].costo_unitario;
                                        this.$vs.notify({
                                            title: "Bodega seleccionada",
                                            text:
                                                "no tiene stock suficiente de: " +
                                                this.contingred[i].nombre,
                                            color: "warning"
                                        });
                                    }
                                }
                                if (
                                    l + 1 ==
                                        this.contenidobodegas[j].stock.length &&
                                    check == false
                                ) {
                                    this.contingred[i].stock = 0;
                                    this.contingred[
                                        i
                                    ].id_producto_bodega = null;
                                    this.contingred[i].costo_unitario = 0;
                                    this.$vs.notify({
                                        title: "Bodega seleccionada",
                                        text:
                                            "no tiene stock de: " +
                                            this.contingred[i].nombre,
                                        color: "danger"
                                    });
                                }
                            }
                        }
                    }
                } else {
                    this.contingred[i].stock = "-";
                    this.contingred[i].id_producto_bodega = null;
                    this.contingred[i].saldo = "-";
                }
            }
        },*/
        //Coordinar proyecto general con proyecto individual en ingredientes de orden
        bindProyecto(){
            for(let i=0; i<this.contingred.length; i++){
                this.contingred[i].id_proyecto = this.proyecto_orden;
            }
        },
        //funcion añade ingrediente nuevo seleccionado a proceso
        selectingredorden(tr) {
            this.popunewingredorden = false;
            console.log(tr);
            this.contingred.push({
                id_formula_ingrediente: null,
                id_producto: tr.id_producto,
                id_formula_produccion: null,
                cant_unit_prod: 0,
                nombre: tr.nombre,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                sector: tr.sector,
                saldo: tr.stock,
                stock: tr.stock,
                costo_unitario: tr.costo_unitario,
                costo_total: tr.costo_total,
                id_producto_bodega: tr.id_producto_bodega 
            });
        },
        eliminaringredorden(id) {
            this.contingred.splice(id, 1);
        },
        //guarda orden de produccion
        guardarorden() {
            this.disabled_guardar = true;
            if (this.validarorden()) {
                this.disabled_guardar = false;
                return;
            }
            axios
                .post("/api/agregarprocesoorden", {
                    //encabezado
                    detalle: this.descrip_orden,
                    fecha_inicio: this.f_ini_orden,
                    id_proyecto: this.proyecto_orden,
                    id_bodega: this.bodega_orden,
                    id_empresa: this.usuario.id_empresa,
                    id_establecimiento: this.usuario.id_establecimiento,
                    //productos
                    productos: this.continprod,
                    //ingredientes
                    ingredientes: this.contingred
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Orden de Producción Realizada",
                        text: "Guardado exitoso",
                        color: "success"
                    });
                    this.$router.push("/produccion/proceso-produccion");
                });
        },
        validarorden() {
            this.errororden = 0;

            this.errornum_orden = [];
            this.errordescrip_orden = [];
            this.errororden_proyect = [];
            this.errororden_bodega = [];
            this.errorordenproduct = [];
            if (!this.num_orden) {
                this.errornum_orden.push("Campo obligatorio");
                this.errororden = 1;
                window.scrollTo(0, 0);
            }
            if (!this.descrip_orden) {
                this.errordescrip_orden.push("Campo obligatorio");
                this.errororden = 1;
                window.scrollTo(0, 0);
            }
            if (!this.continprod.length) {
                this.errorordenproduct.push("Debe añadir almenos un producto");
                this.errororden = 1;
                window.scrollTo(0, 0);
            }
            if (!this.proyecto_orden) {
                this.errororden_proyect.push("Campo obligatorio");
                this.errororden = 1;
            }
            if (!this.bodega_orden) {
                this.errororden_bodega.push("Campo obligatorio");
                this.errororden = 1;
            }
            for (var i = 0; i < this.continprod.length; i++) {
                this.continprod[i].errorcant_prod = [];
                if (!this.continprod[i].cant_prod) {
                    this.continprod[i].errorcant_prod.push("Campo obligatorio");
                    this.errororden = 1;
                    window.scrollTo(0, 0);
                }
            }
            for (let j = 0; j < this.contingred.length; j++) {
                this.contingred[j].errorcosto_unitario = [];
                if (
                    this.contingred[j].sector == 1 &&
                    this.contingred[j].saldo < 0
                ) {
                    this.$vs.notify({
                        title: "Ingredientes Insuficientes:",
                        text: this.contingred[j].nombre,
                        color: "danger"
                    });
                    this.errororden = 1;
                }

                if (!this.contingred[j].costo_unitario) {
                    this.contingred[j].errorcosto_unitario.push("Obligatorio");
                    this.errororden = 1;
                }
            }
            return this.errororden;
        },
        listorden() {
            if (this.$route.params.id) {
                this.idrecupera = this.$route.params.id;
                var url = "/api/traerordenprod/" + this.idrecupera;
                axios.get(url).then(res => {
                    this.num_orden = res.data.num_orden;
                    this.descrip_orden = res.data.detalle;
                    this.proyecto_orden = res.data.id_proyecto;
                    this.bodega_orden = res.data.ingredientes[0].id_bodega;
                    this.continprod = res.data.productos;
                    this.contingred = res.data.ingredientes;
                    console.log(res.data.ingredientes);
                    if (res.data.estado == 1) {
                        this.f_ini_orden = res.data.fecha_inicio;
                        this.recuperaorden = true;
                        this.idprocespro = res.data.id_proceso_produccion;
                        this.contingredprod = res.data.ingredientes;
                        this.bodegaproces();
                    }
                    if (res.data.estado == 2) {
                        this.f_ini_orden = res.data.fecha_inicio;
                        this.f_proces = res.data.fecha_proceso;
                        this.recuperaorden = true;
                        this.recuperaproces = true;
                        this.idprocespro = res.data.id_proceso_produccion;
                        this.bodega_liquid = res.data.ingredientes[0].id_bodega;
                        this.contingredprod = res.data.ingredientes;
                        this.contingredliq = res.data.ingredientes;
                    }
                    if (res.data.estado == 3) {
                        this.f_ini_orden = res.data.fecha_inicio;
                        this.f_proces = res.data.fecha_proceso;
                        this.f_liquid = res.data.fecha_fin;
                        this.recuperaorden = true;
                        this.recuperaproces = true;
                        this.recuperaliquid = true;
                        this.idprocespro = res.data.id_proceso_produccion;
                        this.bodega_liquid = res.data.id_bodega;
                        this.contingredprod = res.data.ingredientes;
                        this.contingredliq = res.data.ingredientes;
                    }
                });
            } else {
                this.idrecupera = null;
            }
        },
        //---------------------------------------------------------------------------------------Funciones Proceso Produccion------------------------------------------------------------------//
        //establece array de bodega para proceso
        bodegaproces() {
            for (let i in this.contenidobodegas) {
                if (this.contenidobodegas[i].id_bodega == this.bodega_orden) {
                    this.contenidobodegaproceso = this.contenidobodegas[
                        i
                    ].stock;
                }
            }
            this.stockproceso();
        },
        //Funcion Calcula cantidad total y saldo de ingredeintes en proceso
        stockproceso() {
            for (let i in this.contingredprod) {
                if (this.contingredprod[i].id_producto_bodega == null) {
                    this.contingredprod[i].stock = "-";
                    this.contingredprod[i].cantidad_produccion = 0;
                } else {
                    for (let j in this.contenidobodegaproceso) {
                        if (
                            this.contenidobodegaproceso[j].id_producto_bodega ==
                            this.contingredprod[i].id_producto_bodega
                        ) {
                            this.contingredprod[
                                i
                            ].stock = this.contenidobodegaproceso[j].cantidad;
                            this.contingredprod[
                                i
                            ].costo_unitario_produccion = this.contenidobodegaproceso[
                                j
                            ].costo_unitario;
                            this.contingredprod[i].cantidad_produccion = 0;
                        }
                    }
                }
            }
        },
        //asigna cantidades de proceso a cada producto
        asignarcantidades(index) {
            this.popupcantproces = true;
            this.indexcantprod = index;
            this.asigcantprod = this.contingredprod[index].cantidad_produccion;
        },
        //asigna cantidades de proceso automatico
        asigcantautomatic(index) {
            if (this.continprod.length == 1) {
                this.contingredprod[
                    index
                ].proceso_cantidad[0].cantidad = this.contingredprod[
                    index
                ].cantidad_produccion;
            }
        },
        guardarasigcantprod() {
            var sumcant = 0;
            for (
                let i = 0;
                i <
                this.contingredprod[this.indexcantprod].proceso_cantidad.length;
                i++
            ) {
                sumcant =
                    sumcant +
                    parseFloat(
                        this.contingredprod[this.indexcantprod]
                            .proceso_cantidad[i].cantidad
                    );
            }
            if (sumcant == this.asigcantprod) {
                this.$vs.notify({
                    title: "Cantidad asignada correctamente",
                    text: "Registro Exitoso",
                    color: "success"
                });
                this.popupcantproces = false;
                this.indexcantprod = null;
                this.asigcantprod = 0;
            } else {
                this.$vs.notify({
                    title:
                        "Cantidad asignada debe ser igual a la ingresada en el ingrediente",
                    text: "Error de Asignacion",
                    color: "danger"
                });
            }
        },
        //Funcion Guarda proceso de proudccion ingresado
        guardarproces() {
            this.disabled_guardar = true;
            if (this.validarproces()) {
                this.disabled_guardar = false;
                return;
            }
            axios
                .post("/api/agregarprocesoproces", {
                    //encabezado
                    id_proceso_produccion: this.idprocespro,
                    fecha_proceso: this.f_proces,
                    id_bodega: this.bodega_orden,
                    id_empresa: this.usuario.id_empresa,
                    //productos
                    productos: this.continprod,
                    //ingredientes
                    ingredientes: this.contingredprod
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Proceso de Producción Modificado",
                        text: "Guardado exitoso",
                        color: "success"
                    });
                    this.$router.push("/produccion/proceso-produccion");
                });
        },
        validarproces() {
            this.errorproces = 0;
            for (var i = 0; i < this.contingredprod.length; i++) {
                this.contingredprod[i].errorcantidad_produccion = [];
                this.contingredprod[i].errorcosto_unitario_produccion = [];
                if (
                    this.contingredprod[i].cantidad_produccion == null ||
                    this.contingredprod[i].cantidad_produccion.length == 0
                ) {
                    this.contingredprod[i].errorcantidad_produccion.push(
                        "Obligatorio"
                    );
                    this.errorproces = 1;
                }
                if (
                    !this.contingredprod[i].costo_unitario_produccion &&
                    this.contingredprod[i].sector == 2
                ) {
                    this.contingredprod[i].errorcosto_unitario_produccion.push(
                        "Obligatorio"
                    );
                    this.errorproces = 1;
                }
                if (this.contingredprod[i].sector == 1) {
                    if (
                        parseFloat(this.contingredprod[i].cantidad_produccion) >
                        parseFloat(this.contingredprod[i].stock)
                    ) {
                        this.$vs.notify({
                            title: "Ingredientes Insuficientes:",
                            text: this.contingredprod[i].nombre,
                            color: "danger"
                        });
                        this.errorproces = 1;
                    }
                }
                if (
                    this.contingredprod[i].cantidad_produccion == 0 &&
                    this.contingredprod[i].editable == true
                ) {
                    this.$vs.notify({
                        title: "La cantidad no puede ser 0 en:",
                        text: this.contingredprod[i].nombre,
                        color: "danger"
                    });
                    this.errorproces = 1;
                }
                var cant = 0;
                for (
                    let j = 0;
                    j < this.contingredprod[i].proceso_cantidad.length;
                    j++
                ) {
                    cant =
                        cant +
                        parseFloat(
                            this.contingredprod[i].proceso_cantidad[j].cantidad
                        );
                }
                if (this.contingredprod[i].cantidad_produccion != cant) {
                    this.$vs.notify({
                        title: "Error de Asignacion de Cantidades:",
                        text: "Ingrediente: " + this.contingredprod[i].nombre,
                        color: "danger"
                    });
                    this.errorproces = 1;
                }
            }

            return this.errorproces;
        },
        //Funcion lista nuevos ingredientes a añadir en proceso
        listnewproces(buscari) {
            var url = `/api/getnewingred/${this.bodega_orden}/${this.usuario.id_empresa}?buscar=${buscari}`;
            axios.get(url).then(res => {
                this.contenidoingred = res.data.recupera;
            });
        },
        //funcion añade ingrediente nuevo seleccionado a proceso
        selectingredprod(tr) {
            this.popunewingred = false;
            this.contingredprod.push({
                id_proceso_ingrediente: null,
                cantidad_orden: 0,
                cantidad_produccion: 0,
                cantidad_liquidacion: null,
                costo_unitario_orden: 0,
                costo_unitario_produccion: tr.costo_unitario,
                costo_unitario_liquidacion: null,
                id_producto: tr.id_producto,
                id_proceso_produccion: this.contingredprod[0]
                    .id_proceso_produccion,
                id_bodega: tr.id_bodega,
                id_producto_bodega: tr.id_producto_bodega,
                id_bodega_egreso_detalle_orden: null,
                id_bodega_egreso_detalle_produccion: null,
                id_bodega_egreso_detalle_liquidacion: null,
                cod_alterno: tr.cod_alterno,
                cod_principal: tr.cod_principal,
                nombre: tr.nombre,
                sector: tr.sector,
                stock: tr.stock,
                cantotproces: "",
                costo_total_produccion: "",
                editable: true,
                proceso_cantidad: []
            });
            for (let i = 0; i < this.continprod.length; i++) {
                this.contingredprod[
                    this.contingredprod.length - 1
                ].proceso_cantidad.push({
                    id_proceso_cantidad: null,
                    cantidad: 0,
                    id_proceso_produccion: this.idprocespro,
                    id_proceso_producto: this.continprod[i].id_proceso_producto,
                    id_proceso_ingrediente: this.contingredprod[
                        this.contingredprod.length - 1
                    ].id_proceso_ingrediente,
                    cod_principal: this.continprod[i].cod_principal,
                    nombre: this.continprod[i].nombre
                });
            }
        },
        //elimina ingrediente selccionado
        eliminar_ingrediente(id) {
            this.contingredprod.splice(id, 1);
        },
        //-----------------------------------funciones Liquidacion produccion---------------------------------------------------//
        //funcion guarda formulario de liquidacion
        guardarliquid() {
            this.disabled_guardar = true;
            if (this.validarliquid()) {
                this.disabled_guardar = true;
                return;
            }
            axios
                .post("/api/agregarliquidproces", {
                    //encabezado
                    id_proceso_produccion: this.idprocespro,
                    fecha_fin: this.f_liquid,
                    id_empresa: this.usuario.id_empresa,
                    id_bodega: this.bodega_liquid,
                    id_establecimiento: this.usuario.id_establecimiento,
                    //productos
                    productos: this.continprod,
                    ingredientes: this.contingredliq
                })
                .then(res => {
                    this.$vs.notify({
                        title: "Proceso de Producción Liquidado",
                        text: "Guardado exitoso",
                        color: "success"
                    });
                    this.$router.push("/produccion/proceso-produccion");
                });
        },
        //Validaciones
        soloenteros: function($event) {
            //  return /^-?(?:\d+(?:,\d*)?)$/.test($event);
            var num = /^\d+$/;
            if (
                $event.charCode === 0 ||
                num.test(String.fromCharCode($event.charCode))
            ) {
                return true;
            } else {
                $event.preventDefault();
            }
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
        validarliquid() {
            this.errorliquid = 0;
            this.errorbodega_liquid = [];
            if (!this.bodega_liquid) {
                this.errorbodega_liquid.push("Campo obligatorio");
                this.errorliquid = 1;
                window.scrollTo(0, 0);
            }

            return this.errorliquid;
        },
        ainicio() {
            $(".tabproductos")
                .find("li:nth-child(1)>button")
                .click();
            this.motivo_trans = "";
            this.receptor_trans = "";
        }
    },
    mounted() {
        this.liststockbodegaingred();
        //orden Produccion
        this.listproyect();
        this.listarnumorden();
        this.listorden();
    }
};
</script>

<style lang="scss">
.txt-center > div > input {
    text-align: center;
}
.text-center > .vs-table-text {
    text-align: center !important;
    display: block;
}
.theme-dark .vx-card select {
    background: #262c49;
}
.vs-tabs--btn {
    display: block !important;
}
.vs-popup {
    width: 1060px !important;
}
.icono_eliminar {
    vertical-align: middle;
    display: table-cell;
    height: 100%;
    width: 1%;
}
</style>
