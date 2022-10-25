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
            <div class="sm:w-full md:w-6/12 w-6/12 md:ml-auto mb-6" v-if="usuario.id_rol!=2">
                <div class="sm:pl-0 md:pl-3 sm:w-full pl-3">
                    <label for="sellers">Usuario</label>
                    <v-select
                        id="sellers"
                        :options="sellers.sellersList"
                        label="fullname"
                        v-model="sellers.selectedSeller"
                        placeholder="Seleccione el vendedor"
                    ></v-select>
                </div>
            </div>
        </div>
        <div class="flex flex-wrap mt-3 mb-3" style="align-items: center;">
            <label class="mr-2">Nota Credito</label>
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
        <div class="flex flex-wrap">
            <div class="w-full mb-6">
                <label for="sections" class="flex mb-2"
                    >Productos
                    <vs-switch v-model="products.withProducts" class="ml-5" />
                </label>
                <v-select
                    v-if="products.withProducts"
                    multiple
                    id="sections"
                    :options="products.productsList"
                    label="name"
                    v-model="products.selectedProduct"
                    placeholder="Seleccione los productos"
                ></v-select>
            </div>
        </div>
        <div class="flex flex-wrap">
            <vs-button color="success" @click="sendData" type="filled"
                >Generar</vs-button
            >
        </div>
    </vx-card>
</template>

<script>
import service from "../service";
import Datepicker from "vuejs-datepicker";
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
            products: {
                productsList: [],
                selectedProduct: null,
                withProducts: false
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
                user:this.usuario.id,
                user_name:this.usuario.nombres,
                rol_user:this.usuario.id_rol,
            };
            this.$emit("generateReport", response);
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
        }
    },
    components: {
        Datepicker
    },
    async mounted() {
        try {
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
            this.products.productsList = await service.products.getAll({
                idEmpresa: this.idEmpresa
            });
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
