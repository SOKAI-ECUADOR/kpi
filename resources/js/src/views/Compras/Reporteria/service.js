const {
    default: routes
} = require("../../../layouts/components/vertical-nav-menu/navMenuItems");

const { default: service } = require("../../Facturacion/Reporteria/service");

const {default: ServiceReports} = require("./serviceReports")

const axios = require("axios");

const NAME_MODULE = "Proveedor";

function errorHandler(error) {
    return { error: true, message: error.message };
}
async function generateSectionList() {
    let sections = routes.find(route => route.name === NAME_MODULE);
    sections = sections.submenu.map((element, index) => {
        var otronombre = "";
        if(element.i18n.replace(/_/g, " ")=="PagoProveedores"){
            otronombre = "Pago Proveedores";
        }else{
            otronombre = element.i18n.replace(/_/g, " ");
        }
        return { id: index + 1, name: otronombre  };
    });
    sections.pop();
    return sections;
}

function providers() {
    const getAll = ({idEmpresa}) =>
        new Promise(async (resolve, reject) => {
            try {
                let { data:{ recupera: productslist } } = await axios.get(`/api/proveedor/${idEmpresa}`);
                resolve(
                    productslist.map(provider => {
                        return {
                            id: provider.id_proveedor,
                            nombre: provider.nombre_proveedor.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    return {
        getAll: getAll
    };
}
function group_providers() {
    const getAll = ({idEmpresa}) =>
        new Promise(async (resolve, reject) => {
            try {
                let { data:{ recupera: productslist } } = await axios.get(`/api/grupoprov/${idEmpresa}`);
                resolve(
                    productslist.map(provider => {
                        return {
                            id: provider.id_grupoprov,
                            nombre: provider.nombre_grupoprov.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    return {
        getAll: getAll
    };
}

function banco() {
    const getAll = () =>
        new Promise(async (resolve, reject) => {
            try {
                let { data } = await axios.get("/api/traerbancoprov");
                resolve(
                    data.map(provider => {
                        return {
                            id: provider.id_banco,
                            nombre: provider.nombre_banco.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    return {
        getAll: getAll()
    };
}

function paymentMethods() {
    /*const getAll = () =>
        new Promise(async (resolve, reject) => {
            try {
                const { data: paymentMethods } = await axios.get(
                    "/api/facturaformapagos"
                );
                resolve(
                    paymentMethods.map(pm => {
                        return {
                            id: pm.id_forma_pagos,
                            nombre: pm.descripcion.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    return {
        getAll: getAll()
    };*/
    const getAll = ({idEmpresa}) =>
        new Promise(async (resolve, reject) => {
            try {
                let { data } = await axios.get(`/api/form_pago/cuenta_pagar/${idEmpresa}`);
                resolve(
                    data.map(provider => {
                        return {
                            id: provider.id_forma_pagos,
                            nombre: provider.descripcion.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    return {
        getAll: getAll
    };
}
function users(){
    const getAll = ({idEmpresa}) =>
        new Promise(async (resolve, reject) => {
            try {
                let { data } = await axios.get(`/api/user/cuenta_pagar/admin/${idEmpresa}`);
                resolve(
                    data.map(provider => {
                        return {
                            id: provider.id,
                            fullname: `${provider.nombres} ${
                                provider.apellidos
                            }`.toUpperCase(),
                            empresa:provider.nombre_empresa
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    return {
        getAll: getAll
    };
}

function generateReport() {
    return {
        purchaseInvoiceReport: ServiceReports.purchaseInvoiceReport,
        accountsPayableReport: ServiceReports.accountsPayableReport
    };
}

export default {
    sections: generateSectionList(),
    providers: providers(),
    group:group_providers(),
    users: users(),
    products: service.products,
    establishments: service.establishments,
    pointsOfEmission: service.pointsOfEmission,
    projects: service.projects,
    paymentMethods: paymentMethods(),
    generateReport: generateReport(),
    models:service.models,
    marcas:service.marcas,
    presentacion:service.presentacion,
    lineaProducto:service.lineaProducto,
    tipoProducto:service.tipoProducto,
    generateDate: service.generateDate,
    banco:banco()
};
