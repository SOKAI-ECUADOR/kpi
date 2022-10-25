const {
    default: routes
} = require("../../../layouts/components/vertical-nav-menu/navMenuItems");

const { default: serviceReports } = require("./serviceReports");

const axios = require("axios");

const NAME_MODULE = "Facturacion";

function errorHandler(error) {
    return { error: true, message: error.message };
}

function generateReport() {
    return {
        invoiceReport: serviceReports.invoiceReport,
        proformaReport: serviceReports.proformaReport,
        accountsReceivableReport: serviceReports.accountsReceivableReport,
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
    generateReport: generateReport(),
    generateDate: generateDateForQuery
};
