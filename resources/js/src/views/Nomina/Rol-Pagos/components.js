const axios = require("axios");
var tabs = [{
    name: 'Inicio',
    componente: {
        template: '<div>Componente Inicio</div>'
    }
},
{
    name: 'Resultados',
    componente: {
    template: '<div>Componente resultado</div>'
    }
},
{
    name: 'Archivos',
    componente: {
    template: '<div>Componente archivos</div>',
    }
}
]
function generateReport() {
    const invoiceReport = ({ data }) => {
        return new Promise(async (resolve, reject) => {
            try{
                let resp = await axios({
                    url: "/api/generarrolpdf",
                    method: "GET",
                    responseType: "arraybuffer",
                    params: data
                });
                var decodedString = String.fromCharCode.apply(
                    null,
                    new Uint8Array(resp.data)
                );
                if (decodedString.includes("no-data-report")) {
                    reject({
                        title: "Sin registros",
                        text:
                            "No se encontraron registros con los datos proporcionados",
                        color: "warning"
                    });
                }
                
                let { headers } = resp;
                let nameFile = headers["content-disposition"]
                    .split(";")[1]
                    .split("=")[1]
                    .replace(/"/g, "");
                const url = window.URL.createObjectURL(
                    new Blob([resp.data], { type: "application/pdf" })
                );
                resolve({ url: url, nameFile: nameFile });
            }catch (error) {
                console.log(error);
            }
        });
    };
    return {
        invoiceReport: invoiceReport
    };
}
/*new Vue({
el: '#app',
data: {
    tabs: tabs,
    actualTab: tabs[0]
}
})*/
export default{
    generateReport: generateReport(),
}