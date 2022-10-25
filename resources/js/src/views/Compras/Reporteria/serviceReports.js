const axios = require("axios");
const fetchGet = ({ urlResource, data }) =>
    new Promise(async (resolve, reject) => {
        try {
            let resp = await axios({
                url: urlResource,
                method: "GET",
                responseType: "arraybuffer",
                params: data
            });
            // var decodedString = String.fromCharCode.apply(
            //     null,
            //     new Uint8Array(resp.data)
            // );
            // if (decodedString.includes("no-data-report")) {
            //     reject({
            //         title: "Sin registros",
            //         text:
            //             "No se encontraron registros con los datos proporcionados",
            //         color: "warning"
            //     });
            // }
            let { headers } = resp;
            let nameFile = headers["content-disposition"]
                .split(";")[1]
                .split("=")[1]
                .replace(/"/g, "");
            const url = window.URL.createObjectURL(
                new Blob([resp.data], { type: "application/pdf" })
            );
            resolve({ url: url, nameFile: nameFile });
        } catch (error) {
            reject(error);
        }
    });

    export default {
      purchaseInvoiceReport: fetchGet,
      accountsPayableReport: fetchGet
    }
