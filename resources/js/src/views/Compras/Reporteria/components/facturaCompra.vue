<template>
    <vx-card>
        <div class="flex flex-wrap mt-5 mb-5">
            <div class="w-1/4 flex">
                <label for="sections" v-if="dates.currentDate.active"
                    >Fecha de emision actual</label
                >
                <label for="sections" v-else>Fecha de emision en rango</label>
                <vs-switch class="ml-5" v-model="dates.currentDate.active" />
            </div>
        </div>
        <div class="flex flex-wrap mt-5 mb-5" v-if="!dates.currentDate.active">
            <div class="sm:w-full md:w-6/12 w-6/12">
                <div class="sm:pr-0 md:pr-3 sm:w-full pr-3">
                    <datepicker
                        id="dt-initial"
                        :minimumView="'day'"
                        :maximumView="'year'"
                        placeholder="Fecha de inicio"
                        v-model="dates.currentDate.dateRange.initialDate"
                    ></datepicker>
                </div>
            </div>
            <div class="sm:w-full md:w-6/12 w-6/12 md:ml-auto">
                <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                    <datepicker
                        id="dt-final"
                        :minimumView="'day'"
                        :maximumView="'year'"
                        placeholder="Fecha final"
                        v-model="dates.currentDate.dateRange.finalDate"
                    ></datepicker>
                </div>
            </div>
        </div>
        <vs-divider border-style="solid" color="dark"></vs-divider>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="establishment">Establecimiento:</label>
                <v-select
                    id="establishment"
                    :options="establishment.establishmentsList"
                    label="name"
                    v-model="establishment.selectedEstablishment"
                    @input="searchByEstablishment"
                    placeholder="Seleccione el establecimiento"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pr-3 pl-3">
                <label for="pointOfEmission">Punto de emision:</label>
                <!-- prettier-ignore -->
                <v-select
                    id="pointOfEmission"
                    :options="
                        pointOfEmission.pointsOfEmissionListByEstablishment"
                    label="name"
                    v-model="pointOfEmission.selectedPointOfEmission"
                    placeholder="Seleccione el punto de emision"
                ></v-select>
            </div>
            
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Proyecto:</label>
                <v-select
                    id="project"
                    :options="project.projectsList"
                    label="name"
                    v-model="project.selectedProject"
                    placeholder="Seleccione el proyecto"
                ></v-select>
            </div>
        </div>
        <div class="flex flex-wrap">
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6">
                <label for="project">Proveedor:</label>
                <v-select
                    id="project"
                    :options="provider.providersList"
                    label="nombre"
                    v-model="provider.selectedProvider"
                    placeholder="Seleccione el proveedor"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6 pl-3 pr-3">
                <label for="project">Forma de pago:</label>
                <v-select
                    id="project"
                    :options="wayToPay.listOfPaymentMethods"
                    label="nombre"
                    v-model="wayToPay.selectedPaymentMethod"
                    placeholder="Seleccione la forma de pago"
                ></v-select>
            </div>
            <div class="sm:w-full md:w-1/3 w-1/3 mb-6" v-if="usuario.id_rol!==2">
                <label for="sellers">Usuario:</label>
                <v-select
                    id="sellers"
                    :options="user.usersList"
                    label="fullname"
                    v-model="user.selectedUser"
                    placeholder="Seleccione el vendedor"
                ></v-select>
            </div>
        </div>
        <div class="flex flex-wrap">
            <div class="w-1/4 mb-6" v-if="!invoice.allType">
                <label for="sections" class="flex mb-2"
                    >Gastos de importacion
                    <vs-switch class="ml-5" v-model="invoice.importCosts" />
                </label>
            </div>
            <div class="w-1/4 mb-6" v-if="!invoice.allType">
                <label for="sections" class="flex mb-2"
                    >Documento tributario
                    <vs-switch class="ml-5" v-model="invoice.taxDocument" />
                </label>
            </div>
            <div class="w-1/4 mb-6">
                <label for="sections" class="flex mb-2"
                    >Todas
                    <vs-switch class="ml-5" v-model="invoice.allType" />
                </label>
            </div>
        </div>
        <div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
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
        </div>
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
        <div class="flex flex-wrap">
            <vs-button
                class="mt-5"
                color="success"
                @click="sendData"
                type="filled"
                >Generar</vs-button
            >
        </div>
    </vx-card>
</template>

<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
export default {
    name: "reporteria-factura-compra",
    data() {
        return {
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
            provider: {
                providersList: [{ id: 0, nombre: "TODOS" }],
                selectedProvider: { id: 0, nombre: "TODOS" }
            },
            wayToPay: {
                listOfPaymentMethods: [{ id: 0, nombre: "TODOS" }],
                selectedPaymentMethod: { id: 0, nombre: "TODOS" }
            },
            user: {
                usersList: [{ id: 0, fullname: "TODOS" }],
                selectedUser: { id: 0, fullname: "TODOS" }
            },
            products: {
                productsList: [{ id: 0, name: "TODOS", nombre: null }],
                selectedProduct: { id: 0, name: "TODOS", nombre: null },
                withProducts: false
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
                retention: false,
                credit: false,
                all: true,
                typeSearch: 2,
                totalCount: null,
                importCosts: false,
                taxDocument: false,
                allType: true
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
            let dates = this.dates.currentDate.active
                ? {
                      option: this.dates.optionSelected,
                      value: service.generateDate({ date: new Date() })
                  }
                : {
                      option: this.dates.optionSelected,
                      range: {
                          initial: this.dates.currentDate.dateRange.initialDate
                              ? service.generateDate({
                                    date: new Date(
                                        this.dates.currentDate.dateRange.initialDate
                                    )
                                })
                              : service.generateDate({ date: new Date() }),
                          final: this.dates.currentDate.dateRange.finalDate
                              ? service.generateDate({
                                    date: new Date(
                                        this.dates.currentDate.dateRange.finalDate
                                    )
                                })
                              : service.generateDate({ date: new Date() })
                      }
                  };
            if (dates.range) {
                let initial = dates.range.initial;
                let final = dates.range.final;
                dates.range.initial = initial > final ? final : initial;
                dates.range.final = final < initial ? initial : final;
            }
            let response = {
                company: this.idEmpresa,
                currentDate: this.dates.currentDate.active,
                dates: dates,
                products: this.products.withProducts
                    ? this.products.selectedProduct
                    : [],
                establishment: this.establishment.selectedEstablishment,
                pointOfEmission: this.pointOfEmission.selectedPointOfEmission,
                project: this.project.selectedProject,
                provider: this.provider.selectedProvider,
                wayToPay: this.wayToPay.selectedPaymentMethod,
                user: this.user.selectedUser,
                rol_user:this.usuario.id_rol,
                id_user:this.usuario.id,
                invoice: this.invoice,
                presentacion:this.presentacion.selectedPresentacion,
                model:this.model.selectedModel,
                marca:this.marca.selectedMarca,
                tipo_producto:this.tipoProducto.selectedTipoProducto,
                linea_producto:this.lineaProduct.selectedLineaProduct,
            };
            this.$emit("generateReport", response);
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
        searchByEstablishment(establishment) {
            if (establishment !== null) {
                this.pointOfEmission.pointsOfEmissionListByEstablishment = [
                    { id: 0, name: "TODOS" }
                ];
                console.log(
                    ">>",
                    this.pointOfEmission.pointsOfEmissionListByEstablishment
                );
                let lista =
                    establishment.id == 0
                        ? this.pointOfEmission.pointsOfEmissionList
                        : this.pointOfEmission.pointsOfEmissionList.filter(
                              point => point.establishment === establishment.id
                          );
                console.log("<<", lista);
                lista.length != 1
                    ? this.pointOfEmission.pointsOfEmissionListByEstablishment.push(
                          ...lista
                      )
                    : (this.pointOfEmission.pointsOfEmissionListByEstablishment = lista);
            }
        },
        onlyNumbers(event) {
            this.$emit("onlyNumbers", event);
        }
    },
    components: {
        Datepicker
    },
    async mounted() {
        try {
            this.user.usersList.push(...(await service.users.getAll({
                idEmpresa: this.idEmpresa
            })));
            this.establishment.establishmentsList.push(
                ...(await service.establishments.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.pointOfEmission.pointsOfEmissionList = await service.pointsOfEmission.getAll(
                { idEmpresa: this.idEmpresa }
            );
            this.provider.providersList.push(
                ...(await service.providers.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.project.projectsList.push(
                ...(await service.projects.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
            this.wayToPay.listOfPaymentMethods.push(
                ...(await service.paymentMethods.getAll({
                    idEmpresa: this.idEmpresa
                }))
            );
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
        } catch (error) {
            console.error(error.message);
        }
    }
};
</script>
