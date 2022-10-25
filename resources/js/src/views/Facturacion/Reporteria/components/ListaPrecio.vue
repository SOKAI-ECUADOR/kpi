<template>
    <vx-card>
        <div class="flex flex-wrap">
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
                        @input="listartipo"
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
                        placeholder="Seleccione"
                    ></v-select>
                </div>
                <!--<div class="sm:w-full md:w-1/full w-1/full mb-6">
                    <label for="sections" class="flex mb-2"
                        >Productos
                    </label>
                    <v-select
                       
                        id="sections"
                        :options="products.productsList"
                        label="name"
                        v-model="products.selectedProduct"
                        placeholder="Seleccione los productos"
                    ></v-select>
                </div>-->
                <div class="flex flex-wrap">
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
const axios = require("axios");
export default {
    name: "reporteria-lista-precio",
    data(){
        return{
            products: {
                productsList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedProduct: { id: 0,name:"TODOS", nombre: null },
                withProducts: false
            },
            tipo_reporte: {
                reporteList: [
                { id: 0, nombre: "TODOS" },
                { id: 1, nombre: "Servicio" },
                { id: 2, nombre: "Producto" },
                ],
                selectedReporte: null
            },
            marca: {
                marcaList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedMarca: { id: 0,name:"TODOS", nombre: null },
                withMarca: false
            },
            model: {
                modelList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedModel: { id: 0,name:"TODOS", nombre: null },
                withModel: false
            },
            presentacion: {
                presentacionList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedPresentacion: { id: 0,name:"TODOS", nombre: null },
                withPresentacion: false
            },
            lineaProduct: {
                lineaProductList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedLineaProduct: { id: 0,name:"TODOS", nombre: null },
                withLineaProduct: false
            },
            tipoProducto: {
                tipoProductoList: [{ id: 0,name:"TODOS", nombre: null }],
                selectedTipoProducto: { id: 0,name:"TODOS", nombre: null },
                withTipoProducto: false
            },
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
        },
        
    },
    methods: {
        sendData() {
            let response = {
                tipo_lista:this.tipo_reporte.reporteList,
                products: this.products.selectedProduct,
                company:this.idEmpresa,
                rol_user:this.usuario.id_rol,
                user:this.usuario.id,
                modelo:this.model.selectedModel,
                marca:this.marca.selectedMarca,
                presentacion:this.presentacion.selectedPresentacion,
                linea_product:this.lineaProduct.selectedLineaProduct,
                tipo_product:this.tipoProducto.selectedTipoProducto,
            };
            this.$emit("generateReport", response);
        },
        listartipo(id){
            //console.log("Cambio linea"+id[0]);
            //var id_linea=JSON.parse(id);
            // this.tipoProducto.tipoProductoList.push(
            //     service.tipoProducto.getAll({
            //         idEmpresa: this.idEmpresa,
            //         idlinea:id["id"]
            //     })
            // );
            if(id["id"]!==0){
                axios.get(`/api/tipoproductosallr/${this.idEmpresa}?id=${id["id"]}`).then(res=>{
                    res.data.forEach(el => {
                        this.tipoProducto.tipoProductoList.push({
                            id:el.id_tipo_producto,
                            name:el.nombre,
                            nombre:el.nombre
                        });
                    });
                    
                }).catch(err=>{
                    console.log("ERROR tipo producto:"+err);
                });
            }
            
            console.log("Cambio linea"+JSON.stringify(this.tipoProducto.tipoProductoList));
        },
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
        
        /*this.products.productsList.push(
                ...(await service.products.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );*/
    }
};
</script>