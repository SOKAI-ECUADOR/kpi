<template>
    <vx-card>
        <div class="flex flex-wrap">
            <div class="flex flex-wrap mt-5 mb-5 w-full">
            
                <div class="w-1/4 flex">
                    <label for="sections" v-if="dates.currentDate.active"
                        >Fecha de emision actual</label
                    >
                    <label for="sections" v-else>Fecha de emision en rango</label>
                    <vs-switch class="ml-5" v-model="dates.currentDate.active" />
                </div>
            </div>
            <div class="flex flex-wrap mt-5 mb-5 w-full" v-if="!dates.currentDate.active">
                <!--<div class="sm:w-full md:w-6/12 w-6/12">
                    <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                        <datepicker
                            id="dt-initial"
                            :minimumView="'day'"
                            :maximumView="'year'"
                            placeholder="Fecha de inicio"
                            :language="es"
                            v-model="dates.currentDate.dateRange.initialDate"
                        ></datepicker>
                    </div>
                </div>-->
                <!--<div class="sm:w-full md:w-6/12 w-6/12 md:ml-auto">-->
                    <div class="sm:w-full md:w-6/12 w-6/12">
                        <datepicker
                            id="dt-final"
                            :minimumView="'day'"
                            :maximumView="'year'"
                            placeholder="Fecha final"
                            :language="es"
                            v-model="dates.currentDate.dateRange.finalDate"
                        ></datepicker>
                    </div>
               <!-- </div>>-->
            </div>
            <!--
                marca: {
                marcaList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedMarca: null,
                withMarca: false
            },
            model: {
                modelList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedModel: null,
                withModel: false
            },
            presentacion: {
                presentacionList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedPresentacion: null,
                withPresentacion: false
            },
            -->
            <div class="sm:w-full md:w-1/2 w-1/2 mb-6  pr-3">
                <label for="project">Linea Producto:</label>
                <v-select
                    id="project"
                    :options="lineaProduct.lineaProductList"
                    label="name"
                    v-model="lineaProduct.selectedLineaProduct"
                    @input="listartipo(), listarproducto()"
                    placeholder="Seleccione"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/2 w-1/2 mb-6">
                <label for="project">Tipo Producto:</label>
                <v-select
                    id="project"
                    :options="tipoProducto.tipoProductoList"
                    label="name"
                    v-model="tipoProducto.selectedTipoProducto"
                    @input="listarproducto()"
                    placeholder="Seleccione"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Marca:</label>
                <v-select
                    id="project"
                    :options="marca.marcaList"
                    label="name"
                    v-model="marca.selectedMarca"
                    @input="listarproducto()"
                    placeholder="Seleccione"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pr-3 pl-3">
                <label for="project">Modelo:</label>
                <v-select
                    id="project"
                    :options="model.modelList"
                    label="name"
                    v-model="model.selectedModel"
                    @input="listarproducto()"
                    placeholder="Seleccione"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Presentacion:</label>
                <v-select
                    id="project"
                    :options="presentacion.presentacionList"
                    label="name"
                    v-model="presentacion.selectedPresentacion"
                    @input="listarproducto()"
                    placeholder="Seleccione"
                ></v-select>
            </div>
            <div class="sm:w-2/3 md:w-2/3 w-2/3">
                <label for="sections" class="flex mb-2">Productos </label>
                <v-select
                    id="sections"
                    :options="products.productsList"
                    label="name"
                    v-model="products.selectedProduct"
                    @input="listarbodega()"
                    placeholder="Seleccione los productos"
                ></v-select>
            </div>
            <div class="sm:w-1/4 md:w-1/4 w-1/4 mr-auto ml-2 mb-6">
                <label for="sections" class="flex mb-2">Bodega </label>
                <v-select
                    id="sections"
                    :options="bodega.bodegaList"
                    label="name"
                    v-model="bodega.selectedBodega"
                    placeholder="Seleccione los productos"
                ></v-select>
            </div>
            <div
                class="sm:w-full md:full w-full mb-6"
                style="text-align:center;"
            >
                <vs-button color="success" @click="sendData" type="filled"
                    >Generar</vs-button
                >
            </div>
        </div>
    </vx-card>
</template>
<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
import { es } from 'vuejs-datepicker/dist/locale';
const axios = require("axios");
export default {
    name: "reporteria-lista-precio",
    data() {
        return {
            es:es,
            dates: {
                optionSelected: 3,
                optionSelectedValue: null,
                currentDate: {
                    active: true,
                    value: null,
                    dateRange: {
                        initialDate: null,
                        finalDate: null
                    }
                }
            },
            products: {
                productsList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedProduct: { id: 0, name: "TODOS", nombre: null },
                withProducts: false
            },
            bodega: {
                bodegaList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedBodega: { id: 0, name: "TODOS", nombre: null },
                withBodega: false
            },
            marca: {
                marcaList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedMarca: { id: 0, name: "TODOS", nombre: null },
                withMarca: false
            },
            model: {
                modelList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedModel: { id: 0, name: "TODOS", nombre: null },
                withModel: false
            },
            presentacion: {
                presentacionList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedPresentacion: { id: 0, name: "TODOS", nombre: null },
                withPresentacion: false
            },
            lineaProduct: {
                lineaProductList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedLineaProduct: { id: 0, name: "TODOS", nombre: null },
                withLineaProduct: false
            },
            tipoProducto: {
                tipoProductoList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedTipoProducto: { id: 0, name: "TODOS", nombre: null },
                withTipoProducto: false
            }
        };
    },
    computed: {
        idEmpresa() {
            let user = this.$store.state.AppActiveUser;
            return user.id_empresa;
        },
        usuario() {
            return this.$store.state.AppActiveUser;
        },
        token() {
            return this.$store.state.Token;
        }
    },
    methods: {
        sendData() {
            let response = {
                currentDate: this.dates.currentDate.active,
                dates: this.dates,
                products: this.products.selectedProduct,
                bodega: this.bodega.selectedBodega,
                company: this.idEmpresa,
                modelo: this.model.selectedModel,
                marca: this.marca.selectedMarca,
                presentacion: this.presentacion.selectedPresentacion,
                linea_product: this.lineaProduct.selectedLineaProduct,
                tipo_product: this.tipoProducto.selectedTipoProducto
            };
            this.$emit("generateReport", response);
        },
        listartipo() {
            axios
                .get(
                    `/api/tipoproductosallr/${this.idEmpresa}?id=${this.lineaProduct.selectedLineaProduct["id"]}`
                )
                .then(res => {
                    this.tipoProducto.tipoProductoList = [
                        { id: 0, name: "TODOS", nombre: null }
                    ];
                    this.tipoProducto.selectedTipoProducto = {
                        id: 0,
                        name: "TODOS",
                        nombre: null
                    };
                    for (let i = 0; i < res.data.length; i++) {
                        this.tipoProducto.tipoProductoList.push({
                            id: res.data[i].id_tipo_producto,
                            name: res.data[i].nombre.toUpperCase(),
                            nombre: res.data[i].nombre.toUpperCase()
                        });
                    }
                })
                .catch(err => {
                    console.log("ERROR tipo producto:" + err);
                    this.tipoProductotipoProductoList = [
                        {
                            id: 0,
                            name: "No se encontró resultados",
                            nombre: null
                        }
                    ];
                });
        },
        listarproducto() {
            axios
                .get(
                    `/api/product-filter?id_empresa=${this.idEmpresa}
                        &id_linea=${this.lineaProduct.selectedLineaProduct["id"]}
                        &id_tipo=${this.tipoProducto.selectedTipoProducto["id"]}
                        &id_marca=${this.marca.selectedMarca["id"]}
                        &id_modelo=${this.model.selectedModel["id"]}
                        &id_presentacion=${this.presentacion.selectedPresentacion["id"]}`
                )
                .then(res => {
                    this.products.productsList = [
                        { id: 0, name: "TODOS", nombre: null }
                    ];
                    this.products.selectedProduct = {
                        id: 0,
                        name: "TODOS",
                        nombre: null
                    };
                    for (let i = 0; i < res.data.length; i++) {
                        this.products.productsList.push({
                            id: res.data[i].id_producto,
                            name: res.data[i].cod_alterno!==null
                            ?
                            `CodPrin:${
                                res.data[i].cod_principal
                            } - CodAlt: ${ res.data[i].cod_alterno} - ${res.data[i].nombre.toUpperCase()}`
                            :
                            `CodPrin:${
                                res.data[i].cod_principal
                            } - ${res.data[i].nombre.toUpperCase()}`
                            ,
                            nombre: res.data[i].nombre.toUpperCase()
                        });
                    }
                })
                .catch(err => {
                    console.log("ERROR  Producto:" + err);
                    this.products.productsList = [
                        {
                            id: null,
                            name: "No se encontró resultados",
                            nombre: null
                        }
                    ];
                });
        },
        listarbodega() {
            axios
                .get(
                    `/api/bodega-filter?id_empresa=${this.idEmpresa}
                        &id_producto=${this.products.selectedProduct["id"]}`
                )
                .then(res => {
                    this.bodega.bodegaList = [
                        { id: 0, name: "TODOS", nombre: null }
                    ];
                    this.bodega.selectedBodega = {
                        id: 0,
                        name: "TODOS",
                        nombre: null
                    };
                    for (let i = 0; i < res.data.length; i++) {
                        this.bodega.bodegaList.push({
                            id: res.data[i].id_bodega,
                            name: res.data[i].nombre.toUpperCase(),
                            nombre: res.data[i].nombre.toUpperCase()
                        });
                    }
                })
                .catch(err => {
                    console.log("ERROR  Producto:" + err);
                    this.products.productsList = [
                        {
                            id: null,
                            name: "No se encontró resultados",
                            nombre: null
                        }
                    ];
                });
        }
    },
    components: {
        Datepicker
    },
    async mounted() {
        this.lineaProduct.lineaProductList.push(
            ...(await service.lineaProducto.getAll({
                idEmpresa: this.idEmpresa
            }))
        );
        this.marca.marcaList.push(
            ...(await service.marcas.getAll({
                idEmpresa: this.idEmpresa
            }))
        );
        this.model.modelList.push(
            ...(await service.models.getAll({
                idEmpresa: this.idEmpresa
            }))
        );
        this.presentacion.presentacionList.push(
            ...(await service.presentacion.getAll({
                idEmpresa: this.idEmpresa
            }))
        );
        this.products.productsList.push(
            ...(await service.products.getAll({
                idEmpresa: this.idEmpresa
            }))
        );
        this.bodega.bodegaList.push(
            ...(await service.bodega.getAll({
                idEmpresa: this.idEmpresa
            }))
        );
    }
};
</script>
