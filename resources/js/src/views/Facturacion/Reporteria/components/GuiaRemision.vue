<template>
    <vx-card>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="establishment">Establecimiento:</label>
                <v-select
                    id="establishment"
                    :options="invoice.establishment.establishmentsList"
                    label="name"
                    v-model="invoice.establishment.selectedEstablishment"
                    @input="searchByEstablishment"
                    placeholder="Seleccione el establecimiento"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pr-3 pl-3">
                <label for="pointOfEmission">Punto de emision:</label>
                <!-- pretty-ignore -->
                <v-select
                    id="pointOfEmission"
                    :options="
                        invoice.pointOfEmission
                            .pointsOfEmissionListByEstablishment
                    "
                    label="name"
                    v-model="invoice.pointOfEmission.selectedPointOfEmission"
                    placeholder="Seleccione el punto de emision"
                ></v-select>
            </div>
            
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Proyecto:</label>
                <v-select
                    id="project"
                    :options="invoice.project.projectsList"
                    label="name"
                    v-model="invoice.project.selectedProject"
                    placeholder="Seleccione el proyecto"
                ></v-select>
            </div>
        </div>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="establishment">Grupo Cliente:</label>
                <v-select
                    id="establishment"
                    :options="GroupClient.groupclientList"
                    label="name"
                    v-model="GroupClient.selectedGroupClient"
                    
                    placeholder="Seleccione el Grupo Cliente"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pr-3 pl-3">
                <label for="pointOfEmission">Tipo Cliente:</label>
                <!-- pretty-ignore -->
                <v-select
                    id="pointOfEmission"
                    :options="
                        TipoCliente
                            .tipoClienteList
                    "
                    label="name"
                    v-model="TipoCliente.selectedTipoCliente"
                    placeholder="Seleccione el punto de emision"
                ></v-select>
            </div>
            
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Grupo Tributario:</label>
                <v-select
                    id="project"
                    :options="GrupoTributario.grupoTributarioList"
                    label="name"
                    v-model="GrupoTributario.selectedGrupoTributario"
                    placeholder="Seleccione el proyecto"
                ></v-select>
            </div>
        </div>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/2 w-1/2 mb-6">
                <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                    <label for="clients">Cliente</label>
                    <v-select
                        id="clients"
                        :options="clients.clientsList"
                        label="nombre"
                        v-model="clients.selectedClient"
                        placeholder="Seleccione el cliente"
                    ></v-select>
                </div>
            </div>
            <div class="sm:w-full md:w-1/2 w-1/2 md:ml-auto mb-6" v-if="usuario.id_rol!=2">
                <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                    <label for="sellers">Usuario</label>
                    <v-select
                        id="sellers"
                        :options="sellers.sellersList"
                        label="fullname"
                        v-model="sellers.selectedSeller"
                        placeholder="Seleccione el Usuario"
                    ></v-select>
                </div>
            </div>
            <!-- <div class="sm:w-full md:w-1/3 w-1/3 " v-if="usuario.id_rol!=2">
                <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                    <label for="sellers">Vendedor</label>
                    <v-select
                        id="sellers"
                        :options="Vendedor.VendedorList"
                        label="fullname"
                        v-model="Vendedor.selectedVendedor"
                        placeholder="Seleccione el Vendedor"
                    ></v-select>
                </div>
            </div> -->
        </div>
        <!-- <div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
            <label class="mr-2">Facturas</label>
            <vs-switch
                v-if="!invoice.all"
                v-model="invoice.credit"
                class="mr-2"
            >
                <span slot="on">con credito</span>
                <span slot="off">sin credito</span>
            </vs-switch>
            <vs-switch
                v-if="!invoice.all"
                v-model="invoice.retention"
                class="mr-2"
            >
                <span slot="on">con retenciones</span>
                <span slot="off">sin retenciones</span>
            </vs-switch>
            <vs-switch v-model="invoice.all" class="mr-2">
                <span slot="on">Todas</span>
            </vs-switch>
            <label class="mr-2 ml-2">con valor total</label>
            <vs-radio
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="1"
                >mayor</vs-radio
            >
            <vs-radio
                class="mr-2 ml-2"
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="0"
                >igual</vs-radio
            >
            <vs-radio
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="-1"
                >menor</vs-radio
            >
            <vs-radio
                class="ml-2"
                v-model="invoice.typeSearch"
                vs-name="invoice"
                vs-value="2"
                >todas</vs-radio
            >
            <label
                v-if="invoice.typeSearch != 2 && invoice.typeSearch"
                class="ml-2"
                >a:</label
            >
            <vs-input
                class="ml-4"
                maxlength="7"
                v-if="invoice.typeSearch != 2 && invoice.typeSearch"
                v-model="invoice.totalCount"
                @keypress="onlyNumbers($event)"
                @blur="changeToDecimal"
                placeholder=" $ "
            />
        </div> -->
        
        <div class="flex flex-wrap">

            <div class="w-full mb-6">
                <label for="sections" class="flex mb-2"
                    >Productos
                    <vs-switch v-model="products.withProducts" class="ml-5" />
                </label>
                <!-- <v-select
                    v-if="products.withProducts"
                    multiple
                    id="sections"
                    :options="products.productsList"
                    label="name"
                    v-model="products.selectedProduct"
                    placeholder="Seleccione los productos"
                ></v-select> -->
            </div>
            
        </div>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/2 w-1/2 mb-6  pr-3" v-if="products.withProducts">
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
            <div class="sm:w-full md:w-1/2 w-1/2 mb-6" v-if="products.withProducts">
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
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6" v-if="products.withProducts">
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
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pr-3 pl-3" v-if="products.withProducts">
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
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6" v-if="products.withProducts">
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
            <div class="sm:w-full md:w-full w-full ml-auto mr-2 mb-6" v-if="products.withProducts">
                <label for="sections" class="flex mb-2">Productos </label>
                <v-select
                    id="sections"
                    multiple
                    :options="products.productsList"
                    label="name"
                    v-model="products.selectedProduct"
                    placeholder="Seleccione los productos"
                ></v-select>
            </div>
        </div>
        <div class="vx-col w-full mt-6">
            <vs-button color="success" @click="sendData" type="filled"
                >Generar</vs-button
            >
            <vs-button color="warning" @click="exportar" type="filled"
                >Exportar</vs-button
            >
            
        </div>
        
    </vx-card>
</template>

<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
const axios = require("axios");
export default {
    name: "reporteria-factura-venta",
    data() {
        return {
            clients: {
                clientsList: [{ id: 0, nombre: "TODOS" }],
                selectedClient: { id: 0, nombre: "TODOS" }
            },
            sellers: {
                sellersList: [{ id: 0, fullname: "TODOS" }],
                selectedSeller: { id: 0, fullname: "TODOS" }
            },
            Vendedor: {
                VendedorList: [{ id: 0, fullname: "TODOS" }],
                selectedVendedor: { id: 0, fullname: "TODOS" }
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
            },
            invoice: {
                totalCount: null,
                typeSearch: 2,
                dateOfIssue: null,
                establishment: {
                    establishmentsList: [{ id: 0, name: "TODOS" }],
                    selectedEstablishment: { id: 0, name: "TODOS" }
                },
                pointOfEmission: {
                    pointsOfEmissionList: [],
                    pointsOfEmissionListByEstablishment: [{ "id": 0, "name": "TODOS" }],
                    selectedPointOfEmission: { "id": 0, "name": "TODOS" }
                },
                project: {
                    projectsList: [{ id: 0, name: "TODOS" }],
                    selectedProject: { id: 0, name: "TODOS" }
                },
                retention: false,
                credit: false,
                all: true
            },
            GroupClient:{
                groupclientList: [{ id: 0, name: "TODOS" }],
                selectedGroupClient: { id: 0, name: "TODOS" }
            },
            TipoCliente:{
                tipoClienteList: [{ id: 0, name: "TODOS" }],
                selectedTipoCliente: { id: 0, name: "TODOS" }
            },
            GrupoTributario:{
                grupoTributarioList: [{ id: 0, name: "TODOS" },{ id: 1, name: "Persona Natural" },{ id: 2, name: "Persona Jurídica" }],
                selectedGrupoTributario: { id: 0, name: "TODOS" }
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
        changeToDecimal() {
            if (this.invoice.totalCount != null) {
                this.invoice.totalCount = parseFloat(
                    this.invoice.totalCount
                ).toFixed(2);
            }
        },
        sendData() {
            let response = {
                client: this.clients.selectedClient,
                seller: this.sellers.selectedSeller,
                products: this.products.withProducts
                    ? this.products.selectedProduct
                    : [],
                totalCount:
                    this.invoice.typeSearch != 2
                        ? this.invoice.totalCount
                        : "ALL",
                typeSearchTotalCount: this.invoice.typeSearch,
                selectedEstablishment: this.invoice.establishment
                    .selectedEstablishment,
                selectedPointOfEmission: this.invoice.pointOfEmission
                    .selectedPointOfEmission,
                selectedProject: this.invoice.project.selectedProject,
                retention: this.invoice.retention,
                credit: this.invoice.credit,
                company:this.idEmpresa,
                all: this.invoice.all,
                rol_user:this.usuario.id_rol,
                user:this.usuario.id,
                vendedor:this.Vendedor.selectedVendedor,
                presentacion:this.presentacion.selectedPresentacion,
                model:this.model.selectedModel,
                marca:this.marca.selectedMarca,
                tipo_producto:this.tipoProducto.selectedTipoProducto,
                linea_producto:this.lineaProduct.selectedLineaProduct,
                user:this.usuario.id,
                user_name:this.usuario.nombres,
                grupoCliente:this.GroupClient.selectedGroupClient,
                tipoCliente:this.TipoCliente.selectedTipoCliente,
                grupoTributario:this.GrupoTributario.selectedGrupoTributario
            };
            this.$emit("generateReport", response);
        },
        exportar() {
            let response = {
                client: this.clients.selectedClient,
                seller: this.sellers.selectedSeller,
                products: this.products.withProducts
                    ? this.products.selectedProduct
                    : [],
                totalCount:
                    this.invoice.typeSearch != 2
                        ? this.invoice.totalCount
                        : "ALL",
                typeSearchTotalCount: this.invoice.typeSearch,
                selectedEstablishment: this.invoice.establishment
                    .selectedEstablishment,
                selectedPointOfEmission: this.invoice.pointOfEmission
                    .selectedPointOfEmission,
                selectedProject: this.invoice.project.selectedProject,
                retention: this.invoice.retention,
                credit: this.invoice.credit,
                company:this.idEmpresa,
                all: this.invoice.all,
                rol_user:this.usuario.id_rol,
                user:this.usuario.id
            };
            this.$emit("generateExport", response);
        },
        searchByEstablishment(establishment) {
            if (establishment !== null) {
                this.invoice.pointOfEmission.pointsOfEmissionListByEstablishment = [
                    { id: 0, name: "TODOS" }
                ];
                let lista =
                    establishment.id == 0
                        ? this.invoice.pointOfEmission.pointsOfEmissionList
                        : this.invoice.pointOfEmission.pointsOfEmissionList.filter(
                              point => point.establishment === establishment.id
                          );
                lista.length != 1
                    ? this.invoice.pointOfEmission.pointsOfEmissionListByEstablishment.push(
                          ...lista
                      )
                    : (this.invoice.pointOfEmission.pointsOfEmissionListByEstablishment = lista);
            }
        },
        onlyNumbers(event) {
            this.$emit("onlyNumbers", event);
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
                            name: `Cod:${
                                res.data[i].cod_principal
                            } - ${res.data[i].nombre.toUpperCase()}`,
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
    },
    components: {
        Datepicker
    },
    async mounted() {
        try {
            
            this.GroupClient.groupclientList.push(
                ...(await service.group_clients.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.TipoCliente.tipoClienteList.push(
                ...(await service.tipo_clients.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.clients.clientsList.push(
                ...(await service.clients.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            // if(this.usuario.id_rol!==2){
            //     this.sellers.sellersList.push(
            //         ...(await service.sellers.getAll({
            //             idEmpresa: this.idEmpresa
            //         }))
            //     );
            // }else{
            //     this.sellers.sellersList.push(
            //         ...(await service.sellers_usu.getAll({
            //             idEmpresa: this.usuario.id
            //         }))
            //     );
            // }
            if(this.usuario.id_rol!==2){
                this.sellers.sellersList.push(
                    ...(await service.users.getAll({
                        idEmpresa: this.idEmpresa
                    }))
                );
            }else{
                this.sellers.sellersList.push(
                    ...(await service.sellers_usu.getAll({
                        idEmpresa: this.usuario.id
                    }))
                );
            }
            // Vendedor: {
            //     VendedorList: [{ id: 0, fullname: "TODOS" }],
            //     selectedVendedor: { id: 0, fullname: "TODOS" }
            // },
            if(this.usuario.id_rol!==2){
                this.Vendedor.VendedorList.push(
                    ...(await service.sellers.getAll({
                        idEmpresa: this.idEmpresa
                    }))
                );
            }else{
                this.Vendedor.VendedorList.push(
                    ...(await service.sellers_usu.getAll({
                        idEmpresa: this.usuario.id
                    }))
                );
            }
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
            // this.products.productsList = await service.products.getAll({
            //     idEmpresa: this.idEmpresa
            // });
            this.invoice.establishment.establishmentsList.push(
                ...(await service.establishments.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.invoice.pointOfEmission.pointsOfEmissionList.push(
                ...(await service.pointsOfEmission.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.invoice.project.projectsList.push(
                ...(await service.projects.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
        } catch (error) {
            console.error(error.message);
        }
    }
};
</script>
