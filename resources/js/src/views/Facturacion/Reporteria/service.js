const {
    default: routes
} = require("../../../layouts/components/vertical-nav-menu/navMenuItems");

const { default: serviceReports } = require("./serviceReports");

const { default: serviceExport } = require("./serviceExport");

const axios = require("axios");

const NAME_MODULE = "Facturacion";

function errorHandler(error) {
    return { error: true, message: error.message };
}
async function generateSectionList() {
    let sections = routes.find(route => route.name === NAME_MODULE);
    sections = sections.submenu.map((element, index) => {
        var otronombre = "";
        if(element.i18n.replace(/_/g, " ")=="CobroClientes"){
            otronombre = "Cobro Cliente";
        }else{
            otronombre = element.i18n.replace(/_/g, " ");
        }
        return { id: index + 1, name: otronombre };
    });
    sections.pop();
    sections.push({id:10,name:"Lista de Precios"});
    sections.push({id:11,name:"Cierre Caja"});
    sections.push({id:12,name:"Check List"});
    return sections;
}

function clients() {
const getAll = ({ idEmpresa }) => {
    return new Promise(async (resolve, reject) => {
        try {
            let {
                data: { recupera: datos }
            } = await axios.get(`/api/clientes/${idEmpresa}`);
            resolve(
                datos.map(client => {
                    return {
                        id: client.id_cliente,
                        nombre: `${client.codigo}: ${
                            client.identificacion
                        } - ${client.nombre.toUpperCase()}`
                    };
                })
            );
        } catch (error) {
            reject(errorHandler(error));
        }
    });
};

return {
    getAll: getAll
};
}

function group_clients() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data
                } = await axios.get(`/api/grupo_cliente/${idEmpresa}`);
                resolve(
                    data.map(client => {
                        return {
                            id: client.id_grupo_cliente,
                            name: `${client.nombre_grupo.toUpperCase()}`
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    
    return {
        getAll: getAll
    };
}

function tipo_clients() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data
                } = await axios.get(`/api/grupotipocliente/${idEmpresa}`);
                resolve(
                    data.map(client => {
                        return {
                            id: client.id_tipo_cliente,
                            name: `${client.descripcion_tipo_cliente.toUpperCase()}`
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    
    return {
        getAll: getAll
    };
}


function sellersAdmin() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera: datos }
                } = await axios.get(`/api/traer/vendedores/admin/${idEmpresa}`);
                resolve(
                    datos.map(user => {
                        return {
                            id: user.id_vendedor,
                            fullname: `${user.nombre_vendedor}`.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}
function sellers() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera: datos }
                } = await axios.get(`/api/traer/vendedores/${idEmpresa}`);
                resolve(
                    datos.map(user => {
                        return {
                            id: user.id_vendedor,
                            fullname: `${user.nombre_vendedor}`.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}
function Users() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera: datos }
                } = await axios.get(`/api/vendedores/${idEmpresa}`);
                resolve(
                    datos.map(user => {
                        return {
                            id: user.id_vendedor,
                            fullname: `${user.nombre_vendedor}`.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}


function products() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data: { recupera: productslist }
                } = await axios.get(`/api/productos_reporte/${idEmpresa}`);
                resolve(
                    productslist.map(product => {
                        return {
                            id: product.id_producto,
                            name: `Cod: ${
                                product.cod_principal
                            } - ${product.nombre.toUpperCase()}`,
                            nombre: product.nombre.toUpperCase()
                                //name: product.nombre.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function marca() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data: { recupera }
                } = await axios.get(`/api/marcaallpdf/${idEmpresa}`);
                resolve(
                    recupera.map(product => {
                        return {
                            id: product.id_marca,
                            name: product.nombre.toUpperCase(),
                            nombre: product.nombre.toUpperCase()
                                //name: product.nombre.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function modelo() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data
                } = await axios.get(`/api/modeloall/${idEmpresa}`);
                resolve(
                    data.map(product => {
                        return {
                            id: product.id_modelo,
                            name: product.nombre.toUpperCase(),
                            nombre: product.nombre.toUpperCase()
                                //name: product.nombre.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function presentacion() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data
                } = await axios.get(`/api/presentacionall/${idEmpresa}`);
                resolve(
                    data.map(product => {
                        return {
                            id: product.id_presentacion,
                            name: product.nombre.toUpperCase(),
                            nombre: product.nombre.toUpperCase()
                                //name: product.nombre.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function LineaProducto() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data
                } = await axios.get(`/api/lineaproductosall/${idEmpresa}`);
                resolve(
                    data.map(product => {
                        return {
                            id: product.id_linea_producto,
                            name: product.nombre.toUpperCase(),
                            nombre: product.nombre.toUpperCase()
                                //name: product.nombre.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function TipoProducto() {
    const getAll = ({ idEmpresa, idlinea }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data
                } = await axios.get(`/api/tipoproductosallr/${idEmpresa}?id=${idlinea}`);
                resolve(
                    data.map(product => {
                        return {
                            id: product.id_tipo_producto,
                            name: product.nombre.toUpperCase(),
                            nombre: product.nombre.toUpperCase()
                                //name: product.nombre.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function establishments() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera }
                } = await axios.get(`/api/establecimiento/${idEmpresa}`);
                resolve(
                    recupera.map(establishment => {
                        return {
                            id: establishment.id_establecimiento,
                            name: establishment.nombre.toUpperCase(),
                            tradeName: establishment.nombre_comercial.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function pointsOfEmission() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera }
                } = await axios.get(`/api/ptoemision/${idEmpresa}`);
                resolve(
                    recupera.map(point => {
                        return {
                            id: point.id_punto_emision,
                            name: point.nombre.toUpperCase(),
                            establishment: point.id_establecimiento
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function projects() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera }
                } = await axios.get(`/api/listarproyecto/${idEmpresa}`);
                resolve(
                    recupera.map(project => {
                        return {
                            id: project.id_proyecto,
                            name: project.descripcion.toUpperCase()
                        };
                    })
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    };
    return {
        getAll: getAll
    };
}
function usersAdmin(){
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
                            }`.toUpperCase()
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
                let { data } = await axios.get(`/api/user/cuenta_pagar/${idEmpresa}`);
                resolve(
                    data.map(provider => {
                        return {
                            id: provider.id,
                            fullname: `${provider.nombres} ${
                                provider.apellidos
                            }`.toUpperCase()
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
function paymentMethods() {
  
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

function generateReport() {
    return {
        invoiceReport: serviceReports.invoiceReport,
        proformaReport: serviceReports.proformaReport,
        accountsReceivableReport: serviceReports.accountsReceivableReport,
    };
}

function generateExcel(){
    return{
        factura_venta: serviceExport.ExcelFactura
    };
}

function generateDateForQuery({ date }) {
    return `${date.getFullYear()}-${
        date.getMonth().toString().length === 1
            ? "0" + (date.getMonth() + 1)
            : date.getMonth() + 1
    }-${
        date.getDate().toString().length === 1
            ? "0" + date.getDate()
            : date.getDate()
    }`;
}

export default {
    sections: generateSectionList(),
    clients: clients(),
    sellers: sellersAdmin(),
    sellers_usu: sellers(),
    products: products(),
    users: usersAdmin(),
    users_usu: users(),
    paymentMethods: paymentMethods(),
    establishments: establishments(),
    pointsOfEmission: pointsOfEmission(),
    projects: projects(),
    generateReport: generateReport(),
    ExcelFactura:generateExcel(),
    models:modelo(),
    marcas:marca(),
    presentacion:presentacion(),
    lineaProducto:LineaProducto(),
    tipoProducto:TipoProducto(),
    generateDate: generateDateForQuery,
    group_clients:group_clients(),
    tipo_clients:tipo_clients()
};
