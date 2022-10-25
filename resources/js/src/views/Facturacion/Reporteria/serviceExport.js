const axios = require("axios");
function generateExcelFActura(){
    const getAll=({ urlResource, datos }) =>
        new Promise(async (resolve, reject) => {
            try {
                let resp = await axios.get(urlResource,{
                    params:{
                        data:datos
                    }
                });

                resolve(
                    "hecho"
                );
            } catch (error) {
                reject(errorHandler(error));
            }
        });
    return {
        getAll: getAll
    };
}
export default {
    ExcelFacturaVenta:generateExcelFActura
}