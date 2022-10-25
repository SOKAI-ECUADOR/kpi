const {
    default: routes
} = require("../../../layouts/components/vertical-nav-menu/navMenuItems");

const axios = require("axios");
const {default: ServiceReports} = require("./serviceReports")

const NAME_MODULE = "Nomina";
async function generateSectionList() {
    let sections = routes.find(route => route.name === NAME_MODULE);
    sections = sections.submenu.map((element, index) => {
        return { id: index + 1, name: element.i18n.replace(/_/g, " ") };
    });
    sections.pop();
    return sections;
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
function providers() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera: productslist }
                } = await axios.get(`/api/departamento/listar/${idEmpresa}`);
                resolve(
                    productslist.map(product => {
                        return {
                            id: product.id_departamento,
                            nombre: product.dep_nombre.toUpperCase()
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
function cargos() {
    const getAll = ({ idEmpresa }) => {
        return new Promise(async (resolve, reject) => {
            try {
                let {
                    data: { recupera: productslist }
                } = await axios.get(`/api/cargo/listar/${idEmpresa}`);
                resolve(
                    productslist.map(product => {
                        return {
                            id: product.id_cargo,
                            nombre: product.car_nombre.toUpperCase()
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
function areas(){
    const getAll = ({idEmpresa}) =>
        new Promise(async (resolve, reject) => {
            try {
                let { data } = await axios.get("/api/area/"+idEmpresa);
                resolve(
                    data.map(client => {
                        return {
                            id: client.id_area,
                            nombre: `${
                                client.are_nombre.toUpperCase()
                            }`
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

export default{
    sections: generateSectionList(),
    generateReport: generateReport(),
    generateDate: generateDateForQuery,
    department:providers(),
    cargo:cargos(),
    area:areas()
}
