const {
    default: routes
} = require("../../../layouts/components/vertical-nav-menu/navMenuItems");

const { default: serviceReports } = require("./serviceReports");

const axios = require("axios");

const NAME_MODULE = "Inventario";

function errorHandler(error) {
    return { error: true, message: error.message };
}
async function generateSectionList() {
    let sections = routes.find(route => route.name === NAME_MODULE);
    sections = sections.submenu.map((element, index) => {
        var otronombre = "";
        if (element.i18n.replace(/_/g, " ") == "CobroClientes") {
            otronombre = "Cobro Cliente";
        } else {
            otronombre = element.i18n.replace(/_/g, " ");
        }
        return { id: index + 1, name: otronombre };
    });
    sections.pop();
    sections.push({ id: 3, name: "Kardex" });
    sections.push({ id: 4, name: "Inventario" });
    return sections;
}

function bodega() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data
                } = await axios.get(`/api/bodegaall/${idEmpresa}`)
                resolve(
                    data.map(bodega => {
                        return {
                            id: bodega.id_bodega,
                            name: bodega.nombre.toUpperCase(),
                            nombre: bodega.nombre.toUpperCase()
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

function products() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let {
                    data: { recupera: productslist }
                } = await axios.get(`/api/list-product/${idEmpresa}`);
                resolve(
                    productslist.map(product => {
                        return {
                            id: product.id_producto,
                            name: product.cod_alterno!==null? 
                            `CodPrin: ${
                                product.cod_principal
                            } - CodAlt:${product.cod_alterno} - ${product.nombre.toUpperCase()}`
                            :
                            `Cod: ${
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

function projects() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async(resolve, reject) => {
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

function generateReport() {
    return {
        invoiceReport: serviceReports.invoiceReport,
    };
}

function generateDateForQuery({ date }) {
    return `${date.getUTCFullYear()}-${
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
    products: products(),
    bodega: bodega(),
    projects: projects(),
    generateReport: generateReport(),
    models: modelo(),
    marcas: marca(),
    presentacion: presentacion(),
    lineaProducto: LineaProducto(),
    tipoProducto: TipoProducto(),
    generateDate: generateDateForQuery
};