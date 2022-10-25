const {
    default: routes
} = require("../../../layouts/components/vertical-nav-menu/navMenuItems");
const axios = require("axios");
const {default: ServiceReports} = require("./serviceReports")

const NAME_MODULE = "Contabilidad";
async function generateSectionList() {
    let sections = routes.find(route => route.name === NAME_MODULE);
    sections = sections.submenu.map((element, index) => {
        var otronombre = "";
        if(element.i18n.replace(/_/g, " ")=="Asientos Contables"){
            otronombre = "Informes financieros";
        }else{
            otronombre = element.i18n.replace(/_/g, " ");
        }
        return { id: index + 1, name: otronombre };
    });
    sections.pop();
    return sections;
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
                            name: project.descripcion
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
// function plan_cuentas() {
//     const getAll = ({ idEmpresa }) => {
//         return new Promise(async (resolve, reject) => {
//             try {
//                 let {
//                     data: { recupera }
//                 } = await axios.get(`/api/listarplan_cuenta/${idEmpresa}`);
//                 resolve(
//                     recupera.map(project => {
//                         return {
//                             id: project.id_plan_cuentas,
//                             name: `${project.codcta}-${project.nomcta}`,
//                             codcta:project.codcta,
//                             nomcta:project.nomcta
//                         };
//                     })
//                 );
//             } catch (error) {
//                 reject(errorHandler(error));
//             }
//         });
//     };
//     return {
//         getAll: getAll
//     };
// }
function generateReport() {
    return {
        AsientoReport: ServiceReports.purchaseInvoiceReport,
        accountsPayableReport: ServiceReports.accountsPayableReport
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
    projects: projects(),
    generateReport:generateReport(),
    generateDate: generateDateForQuery,
    //plan_cta:plan_cuentas()
};