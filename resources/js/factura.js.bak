const axios = require("axios");
import $ from "jquery";
import utf8 from "./uft8";


function obtener_comprobante_firmado() {
    const getAll = ({ factura, id_factura, tipo }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let { data } = await axios.post("/api/leerFacturaphp", { factura: factura });
                resolve({ data });
            } catch (error) {
                let errora = {
                    estado: 'Error',
                    estado1: 'Error 1',
                    mensaje: 'Error en la lectura de factura',
                    informacion: 'Comuniquese con el administrador'
                };
                reject(errorreject(errora, id_factura, tipo));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function lectura_firma() {
    const getAll = ({ firma, id_factura, tipo }) => {
        return new Promise(async(resolve, reject) => {
            try {
                var oReq = new XMLHttpRequest();
                oReq.open("GET", firma, true);
                oReq.responseType = "arraybuffer";
                oReq.onload = oEvent => {
                    // AQUI ESE BLOB NO SE ESTA USANDO
                    var blob = new Blob([oReq.response], { type: "application/x-pkcs12" });
                    let resultado = [oReq.response];
                    resolve({ resultado });
                };
                oReq.send();
            } catch (error) {
                let errora = {
                    estado: 'Error',
                    estado1: 'Error 2',
                    mensaje: 'Error en la lectura de la factura',
                    informacion: 'Comuniquese con el administrador'
                };
                reject(errorreject(errora, id_factura, tipo));
            }
        });
    };
    return {
        getAll: getAll
    };
}

function firmar_comprobante() {
    const getAll = ({contenido, password, comprobante, id_factura, tipo}) => {
        return new Promise(async (resolve, reject) => {
            try{
                var arrayUint8 = new Uint8Array(contenido);
                var p12B64 = forge.util.binary.base64.encode(arrayUint8);
                var p12Der = forge.util.decode64(p12B64);
                var p12Asn1 = forge.asn1.fromDer(p12Der);
                var p12 = forge.pkcs12.pkcs12FromAsn1(p12Asn1, password);
                var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
                var signaturesQuantity = certBags[forge.oids.certBag];
                var count = 0;
                var positionSignature = 0;
                //Comienza Cambio Danny 06/10/2021
                    
                        // actualizacion Consejo y ANF Cambio Danny 06/10/2021
                    try {
                        var entidad = signaturesQuantity[0].attributes.friendlyName[0];
                    }
                    catch(err) {
                        var entidad = 'CONSEJO DE LA JUDICATURA';
                    }
                    // actualizacion Consejo y ANF Cambio Danny 06/10/2021
                if (certBags[forge.oids.certBag][0].cert.issuer.attributes[2].value) {
                      var uanataca = certBags[forge.oids.certBag][0].cert.issuer.attributes[2].value
                }
                if (certBags[forge.oids.certBag][0].cert.extensions[5].value) {
                    var anf = certBags[forge.oids.certBag][0].cert.extensions[5].value;
                }
                    
                    if(/ANF/i.test(anf)) {
                        entidad = 'ANF'
                        var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
                
                        if(/ANF_Global_Root/.test(anf)){
                            var cert = certBags[forge.oids.certBag][2].cert;
                            var issuerName = 'O=ANFAC Autoridad de Certificacion Ecuador CA,OU=ANF Autoridad Raiz Ecuador,C=EC,CN=ANF Ecuador CA1,2.5.4.5=#130d31373932363031323135303031';
                        }else{
                            var cert = certBags[forge.oids.certBag][0].cert;
                            var issuerName = 'CN=ANF High Assurance Ecuador Intermediate CA,OU=ANF Autoridad intermedia  EC,O=ANFAC AUTORIDAD DE CERTIFICACION ECUADOR C.A.,C=EC,2.5.4.5=#130d31373932363031323135303031';
                        }
                        
                    }

                    else if(/UANATACA S.A./i.test(uanataca)) {
                        entidad = 'UANATACA'
                        var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
                        var cert = certBags[forge.oids.certBag][0].cert;
                        var issuerName = '2.5.4.97=#0c0f56415445532d413636373231343939,CN=UANATACA CA2 2016,OU=TSP-UANATACA,O=UANATACA S.A.,L=Barcelona (see current address at www.uanataca.com/address),C=ES';
                    }
                    else{
                    // actualizacion Banco Central y security data Danny 06/10/2021
                                if (/BANCO CENTRAL/i.test(entidad)) {
                                    entidad = 'BANCO_CENTRAL';
                                    var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
                
                                    var cert = certBags[forge.oids.certBag][1].cert;
                                    // issuerName
                                    var issuerName = 'CN=AC BANCO CENTRAL DEL ECUADOR,L=QUITO,OU=ENTIDAD DE CERTIFICACION DE INFORMACION-ECIBCE,O=BANCO CENTRAL DEL ECUADOR,C=EC';
                                } else if (/SECURITY DATA/i.test(entidad)) {
                                    entidad = 'SECURITY_DATA';
                                    var contador = 0;
                                    var max = 0;
                                    var attributes_array=[];        
                                    certBags[forge.oids.certBag].forEach(function (entry) {
                                        var bag = entry.cert;
                                        var attributes = bag.extensions;
                
                                        attributes_array[contador] = attributes;
                                        attributes_array.sort().reverse();
                                        max = attributes_array[0].length;
                
                                        contador++;
                                        /*if (attributes.length >= 23) {
                                            cert = bag;
                                        }*/    
                                    });
                
                                    certBags[forge.oids.certBag].forEach(function (entry) {
                                        var bag = entry.cert;
                                        var attributes = bag.extensions;
                                        if (attributes.length >= max) {
                                            cert = bag;
                                        }   
                                    });
                
                
                
                                    // issuerName
                                    var issuerName = 'CN=AUTORIDAD DE CERTIFICACION SUB SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A.,C=EC';
                                }else if (/CONSEJO DE LA JUDICATURA/i.test(entidad)) {
                
                                    var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
                                    var cert = certBags[forge.oids.certBag][0].cert;
                                    // issuerName
                                    var issuerName = 'CN=ENTIDAD DE CERTIFICACION ICERT-EC,OU=SUBDIRECCION NACIONAL DE SEGURIDAD DE LA INFORMACION DNTICS,O=CONSEJO DE LA JUDICATURA,L=DM QUITO,C=EC';
                
                                }
                                else {
                                    var cert = certBags[forge.oids.certBag][0].cert;
                                    var tipoSecurityData = certBags[forge.oids.certBag][0].cert.extensions[3].value;
                
                                    if(/SUBCA-2/i.test(tipoSecurityData)) {
                                        var issuerName = 'CN=AUTORIDAD DE CERTIFICACION SUBCA-2 SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A. 2,C=EC';
                                    }else if(/SUBCA-3/i.test(tipoSecurityData)){
                                        var issuerName = 'CN=AUTORIDAD DE CERTIFICACION SUBCA-3 SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A. 3,C=EC';
                                    }else{
                                        var issuerName = 'CN=AUTORIDAD DE CERTIFICACION SUBCA-1 SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A. 1,C=EC';
                                    }
                
                                    entidad = 'SECURITY_DATA'; 
                                }
                    }
                    //Validar Fecha de vencimiento del p12
                    var fechaInicio = cert.validity['notBefore'];
                    var fechaFin = cert.validity['notAfter'];
                
                
                    // var url_venc="/api/fecha_vencimiento";
                    // axios.post(url_venc,{
                    //     fechaInicio:fechaInicio,
                    //     fechaFin:fechaFin
                    // }).then(resp=>{
                    //     console.log("CErtificado "+resp.data);
                    // }).catch(err=>{
                    //     console.log("[ERROR] CErtificado "+err);
                    // });

                
                    var pkcs8bags = p12.getBags({bagType: forge.pki.oids.pkcs8ShroudedKeyBag});
                    
                    
                    
                        // actualizacion Banco Central Cambio Danny 06/10/2021
                    
                    if (entidad == 'BANCO_CENTRAL') {
                        var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][1];
                    } else {
                        var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][0];
                    }
                    
                    var key = pkcs8.key;
                    if (key == null) {
                        key = pkcs8.asn1;
                    }
                
                    var certificateX509_pem = forge.pki.certificateToPem(cert);
                
                    var certificateX509 = certificateX509_pem;
                    certificateX509 = certificateX509.substr(certificateX509.indexOf('\n'));
                    certificateX509 = certificateX509.substr(0, certificateX509.indexOf('\n-----END CERTIFICATE-----'));
                
                    var certificateX509 = certificateX509.replace(/\r?\n|\r/g, '').replace(/([^\0]{76})/g, '$1\n');
                
                    //Pasar certificado a formato DER y sacar su hash:
                    var certificateX509_asn1 = forge.pki.certificateToAsn1(cert);
                    var certificateX509_der = forge.asn1.toDer(certificateX509_asn1).getBytes();
                    var certificateX509_der_hash = sha1_base64(certificateX509_der);
                
                
                    var X509SerialNumber="";
                
                        //  actualizacion agregar archivo HexToTin Danny 06/10/2021
                    //Serial Number
                    if(entidad == 'CONSEJO DE LA JUDICATURA' || entidad == 'ANF' || entidad == 'UANATACA'  ){
                        // axios.post("/api/hexToInt",{
                        //     hex: cert.serialNumber,
                        //     id_comprobante:id_factura,
                        //     tipo:tipo
                        // }).then(resp=>{
                        //     X509SerialNumber="1013266204098971572897353243";
                        // });
                        X509SerialNumber="2248920180628776086";
                    }else{
                            
                            X509SerialNumber = parseInt(cert.serialNumber, 16);
                    }
                    
                    var exponent = hexToBase64(key.e.data[0].toString(16));
                    var modulus = bigint2base64(key.n);
                
                    var comprobantes = comprobante.replace(/\t|\r/g, "");
                
                    var sha1_comprobante = sha1_base64(utf8.encode(comprobantes.replace('<?xml version="1.0" encoding="UTF-8"?>', '')));



                    var xmlns = 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#"';
                
                
                    //numeros involucrados en los hash:
                
                    //var Certificate_number = 1217155;//p_obtener_aleatorio(); //1562780 en el ejemplo del SRI
                    var Certificate_number = p_obtener_aleatorio(); //1562780 en el ejemplo del SRI
                
                    //var Signature_number = 1021879;//p_obtener_aleatorio(); //620397 en el ejemplo del SRI
                    var Signature_number = p_obtener_aleatorio(); //620397 en el ejemplo del SRI
                
                    //var SignedProperties_number = 1006287;//p_obtener_aleatorio(); //24123 en el ejemplo del SRI
                    var SignedProperties_number = p_obtener_aleatorio(); //24123 en el ejemplo del SRI
                
                    //numeros fuera de los hash:
                
                    //var SignedInfo_number = 696603;//p_obtener_aleatorio(); //814463 en el ejemplo del SRI
                    var SignedInfo_number = p_obtener_aleatorio(); //814463 en el ejemplo del SRI
                
                    //var SignedPropertiesID_number = 77625;//p_obtener_aleatorio(); //157683 en el ejemplo del SRI
                    var SignedPropertiesID_number = p_obtener_aleatorio(); //157683 en el ejemplo del SRI
                
                    //var Reference_ID_number = 235824;//p_obtener_aleatorio(); //363558 en el ejemplo del SRI
                    var Reference_ID_number = p_obtener_aleatorio(); //363558 en el ejemplo del SRI
                
                    //var SignatureValue_number = 844709;//p_obtener_aleatorio(); //398963 en el ejemplo del SRI
                    var SignatureValue_number = p_obtener_aleatorio(); //398963 en el ejemplo del SRI
                
                    //var Object_number = 621794;//p_obtener_aleatorio(); //231987 en el ejemplo del SRI
                    var Object_number = p_obtener_aleatorio(); //231987 en el ejemplo del SRI
                
                
                
                
                
                
                
                    var SignedProperties = '';
                
                    SignedProperties += '<etsi:SignedProperties Id="Signature' + Signature_number + '-SignedProperties' + SignedProperties_number + '">';  //SignedProperties
                    SignedProperties += '<etsi:SignedSignatureProperties>';
                    SignedProperties += '<etsi:SigningTime>';
                
                    //SignedProperties += '2016-12-24T13:46:43-05:00';//moment().format('YYYY-MM-DD\THH:mm:ssZ');
                    SignedProperties += moment().format('YYYY-MM-DD\THH:mm:ssZ');
                
                    SignedProperties += '</etsi:SigningTime>';
                    SignedProperties += '<etsi:SigningCertificate>';
                    SignedProperties += '<etsi:Cert>';
                    SignedProperties += '<etsi:CertDigest>';
                    SignedProperties += '<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
                    SignedProperties += '</ds:DigestMethod>';
                    SignedProperties += '<ds:DigestValue>';
                
                    SignedProperties += certificateX509_der_hash;
                
                    SignedProperties += '</ds:DigestValue>';
                    SignedProperties += '</etsi:CertDigest>';
                    SignedProperties += '<etsi:IssuerSerial>';
                    SignedProperties += '<ds:X509IssuerName>';
                    SignedProperties += issuerName;
                    SignedProperties += '</ds:X509IssuerName>';
                    SignedProperties += '<ds:X509SerialNumber>';
                
                    SignedProperties += X509SerialNumber;
                
                    SignedProperties += '</ds:X509SerialNumber>';
                    SignedProperties += '</etsi:IssuerSerial>';
                    SignedProperties += '</etsi:Cert>';
                    SignedProperties += '</etsi:SigningCertificate>';
                    SignedProperties += '</etsi:SignedSignatureProperties>';
                    SignedProperties += '<etsi:SignedDataObjectProperties>';
                    SignedProperties += '<etsi:DataObjectFormat ObjectReference="#Reference-ID-' + Reference_ID_number + '">';
                    SignedProperties += '<etsi:Description>';
                
                    SignedProperties += 'contenido comprobante';
                
                    SignedProperties += '</etsi:Description>';
                    SignedProperties += '<etsi:MimeType>';
                    SignedProperties += 'text/xml';
                    SignedProperties += '</etsi:MimeType>';
                    SignedProperties += '</etsi:DataObjectFormat>';
                    SignedProperties += '</etsi:SignedDataObjectProperties>';
                    SignedProperties += '</etsi:SignedProperties>'; //fin SignedProperties
                
                    var SignedProperties_para_hash = SignedProperties.replace('<etsi:SignedProperties', '<etsi:SignedProperties ' + xmlns);
                
                    var sha1_SignedProperties = sha1_base64(SignedProperties_para_hash);
                
                
                    var KeyInfo = '';
                
                    KeyInfo += '<ds:KeyInfo Id="Certificate' + Certificate_number + '">';
                    KeyInfo += '\n<ds:X509Data>';
                    KeyInfo += '\n<ds:X509Certificate>\n';
                
                    //CERTIFICADO X509 CODIFICADO EN Base64 
                    KeyInfo += certificateX509;
                
                    KeyInfo += '\n</ds:X509Certificate>';
                    KeyInfo += '\n</ds:X509Data>';
                    KeyInfo += '\n<ds:KeyValue>';
                    KeyInfo += '\n<ds:RSAKeyValue>';
                    KeyInfo += '\n<ds:Modulus>\n';
                
                    //MODULO DEL CERTIFICADO X509
                    KeyInfo += modulus;
                
                    KeyInfo += '\n</ds:Modulus>';
                    KeyInfo += '\n<ds:Exponent>';
                
                    //KeyInfo += 'AQAB';
                    KeyInfo += exponent;
                
                    KeyInfo += '</ds:Exponent>';
                    KeyInfo += '\n</ds:RSAKeyValue>';
                    KeyInfo += '\n</ds:KeyValue>';
                    KeyInfo += '\n</ds:KeyInfo>';
                
                    var KeyInfo_para_hash = KeyInfo.replace('<ds:KeyInfo', '<ds:KeyInfo ' + xmlns);
                
                    var sha1_certificado = sha1_base64(KeyInfo_para_hash);
                
                
                    var SignedInfo = '';
                
                    SignedInfo += '<ds:SignedInfo Id="Signature-SignedInfo' + SignedInfo_number + '">';
                    SignedInfo += '\n<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315">';
                    SignedInfo += '</ds:CanonicalizationMethod>';
                    SignedInfo += '\n<ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1">';
                    SignedInfo += '</ds:SignatureMethod>';
                    SignedInfo += '\n<ds:Reference Id="SignedPropertiesID' + SignedPropertiesID_number + '" Type="http://uri.etsi.org/01903#SignedProperties" URI="#Signature' + Signature_number + '-SignedProperties' + SignedProperties_number + '">';
                    SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
                    SignedInfo += '</ds:DigestMethod>';
                    SignedInfo += '\n<ds:DigestValue>';
                
                    //HASH O DIGEST DEL ELEMENTO <etsi:SignedProperties>';
                    SignedInfo += sha1_SignedProperties;
                
                    SignedInfo += '</ds:DigestValue>';
                    SignedInfo += '\n</ds:Reference>';
                    SignedInfo += '\n<ds:Reference URI="#Certificate' + Certificate_number + '">';
                    SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
                    SignedInfo += '</ds:DigestMethod>';
                    SignedInfo += '\n<ds:DigestValue>';
                
                    //HASH O DIGEST DEL CERTIFICADO X509
                    SignedInfo += sha1_certificado;
                
                    SignedInfo += '</ds:DigestValue>';
                    SignedInfo += '\n</ds:Reference>';
                    SignedInfo += '\n<ds:Reference Id="Reference-ID-' + Reference_ID_number + '" URI="#comprobante">';
                    SignedInfo += '\n<ds:Transforms>';
                    SignedInfo += '\n<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature">';
                    SignedInfo += '</ds:Transform>';
                    SignedInfo += '\n</ds:Transforms>';
                    SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
                    SignedInfo += '</ds:DigestMethod>';
                    SignedInfo += '\n<ds:DigestValue>';
                
                    //HASH O DIGEST DE TODO EL ARCHIVO XML IDENTIFICADO POR EL id="comprobante" 
                    SignedInfo += sha1_comprobante;
                
                    SignedInfo += '</ds:DigestValue>';
                    SignedInfo += '\n</ds:Reference>';
                    SignedInfo += '\n</ds:SignedInfo>';
                
                    var SignedInfo_para_firma = SignedInfo.replace('<ds:SignedInfo', '<ds:SignedInfo ' + xmlns);
                
                    var md = forge.md.sha1.create();
                    md.update(SignedInfo_para_firma, 'utf8');
                
                    var signature = btoa(key.sign(md)).match(/.{1,76}/g).join("\n");
                
                
                    var xades_bes = '';
                
                    //INICIO DE LA FIRMA DIGITAL 
                    xades_bes += '<ds:Signature ' + xmlns + ' Id="Signature' + Signature_number + '">';
                    xades_bes += '\n' + SignedInfo;
                
                    xades_bes += '\n<ds:SignatureValue Id="SignatureValue' + SignatureValue_number + '">\n';
                
                    //VALOR DE LA FIRMA (ENCRIPTADO CON LA LLAVE PRIVADA DEL CERTIFICADO DIGITAL) 
                    xades_bes += signature;
                
                    xades_bes += '\n</ds:SignatureValue>';
                
                    xades_bes += '\n' + KeyInfo;
                
                    xades_bes += '\n<ds:Object Id="Signature' + Signature_number + '-Object' + Object_number + '">';
                    xades_bes += '<etsi:QualifyingProperties Target="#Signature' + Signature_number + '">';
                
                    //ELEMENTO <etsi:SignedProperties>';
                    xades_bes += SignedProperties;
                
                    xades_bes += '</etsi:QualifyingProperties>';
                    xades_bes += '</ds:Object>';
                    xades_bes += '</ds:Signature>';
                //return  comprobante.replace(/(<[^<]+)$/, xades_bes + '$1');
                let data = comprobante.replace(/(<[^<]+)$/, xades_bes + '$1');
                resolve({ data }); 
            }catch(error){
                let errora = {
                    estado: 'Error',
                    estado1: 'Error 3',
                    mensaje: 'Error en la firma de comprobante',
                    informacion: 'Comuniquese con el administrador'
                };
                reject(errorreject(errora, id_factura, tipo));
            }
        });
    }
    return {
        getAll:getAll
    };
}


function verificar_firma() {
    const getAll = ({ comprobante, mensaje, tipo, id_factura, carpeta }) => {
        return new Promise(async(resolve, reject) => {
            try {
                var xmlDoc = $.parseXML(comprobante),
                    $xml = $(xmlDoc),
                    $claveAcceso = $xml.find("claveAcceso");
                let { data } = await axios.post("/api/firmaphp", { mensaje: mensaje, carpeta: carpeta, claveAcceso: $claveAcceso.text(), });
                resolve({ data });
            } catch (error) {
                let errora = {
                    estado: 'Error',
                    estado1: 'Error 4',
                    mensaje: 'Error en la firma electrónica',
                    informacion: 'Comuniquese con el administrador'
                };
                reject(errorreject(errora, id_factura, tipo));
            }
        });
    }
    return {
        getAll: getAll
    };
}

function validar_comprobante() {
    const getAll = ({ comprobante, tipo, id_factura, carpeta, id_empresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                var service = 'Validar Comprobante';
                var xmlDoc = $.parseXML(comprobante),
                    $xml = $(xmlDoc),
                    $claveAcceso = $xml.find("claveAcceso");
                let { data } = await axios.post("/api/validarComprobantephp", { service: service, claveAcceso: $claveAcceso.text(), carpeta: carpeta, id_factura: id_factura, id_empresa: id_empresa });
                resolve({ data });
            } catch (error) {
                let error_sri = error.response.data;
                reject(errorreject(error_sri, id_factura, tipo));
            }
        });
    }
    return {
        getAll: getAll
    };
}

function autorizar_comprobante() {
    const getAll = ({ comprobante, validado, usuario, tipo, id_factura, carpeta, fecha, valor, logo, nombre_empresa }) => {
        return new Promise(async(resolve, reject) => {
            try {
                var respuesta = decodeURIComponent(validado);
                respuesta = respuesta.toString();
                var validar_comprobante = validado;
                if (/RECIBIDA/i.test(respuesta) || /CLAVE ACCESO REGISTRADA/i.test(respuesta)) {
                    var service = 'Autorizacion Comprobante';
                    var xmlDoc = $.parseXML(comprobante),
                        $xml = $(xmlDoc),
                        $claveAcceso = $xml.find("claveAcceso");
                    let { data } = await axios.post("/api/autorizacionComprobantephp", { service: service, claveAcceso: $claveAcceso.text(), usuario: usuario, tipo: tipo, carpeta: carpeta, fecha: fecha, valor: valor, logo: logo, nombre_empresa: nombre_empresa, id_factura: id_factura });
                    resolve({ data });
                }
            } catch (error) {
                let error_sri = error.response.data;
                reject(errorreject(error_sri, id_factura, tipo));
            }
        });
    }
    return {
        getAll: getAll
    };
}

function autorizado_comprobante() {
    const getAll = ({ recibida, tipo, id_factura }) => {
        return new Promise(async(resolve, reject) => {
            try {
                let errora = {
                    estado: 'Enviado',
                    estado1: 'Enviado',
                    mensaje: 'Comprobante enviado',
                    informacion: 'el Comprobante se generó exitosamente'
                };
                await axios.post('/api/respfactura', { estado: errora, id: id_factura, tipo: tipo });
                let data = "enviado";
                resolve({ data });
            } catch (error) {
                let errora = {
                    estado: 'Error',
                    estado1: 'Error',
                    mensaje: 'Error en el guardado de la respuesta',
                    informacion: 'Comuniquese con el administrador'
                };
                reject(errorreject(errora, id_factura, tipo));
            }
        });
    }
    return {
        getAll: getAll
    };
}

function errorreject(error, id, tipo) {
    axios.post('/api/respfactura', { estado: error, id: id, tipo: tipo });
    return error;
}


function sha1_base64(txt) {
    var md = forge.md.sha1.create();
    md.update(txt);
    return new window.buffer.Buffer(md.digest().toHex(), 'hex').toString('base64');
}

function hexToBase64(str) {
    var hex = ('00' + str).slice(0 - str.length - str.length % 2);
    return btoa(String.fromCharCode.apply(null, hex.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" ")));
}

function bigint2base64(bigint) {
    var base64 = '';
    base64 = btoa(bigint.toString(16).match(/\w{2}/g).map(function(a) { return String.fromCharCode(parseInt(a, 16)); }).join(""));
    base64 = base64.match(/.{1,76}/g).join("\n");
    return base64;
}

function p_obtener_aleatorio() {
    return Math.floor(Math.random() * 999000) + 990;
}
export default {
    obtener_comprobante_firmado: obtener_comprobante_firmado(),
    lectura_firma: lectura_firma(),
    firmar_comprobante: firmar_comprobante(),
    verificar_firma: verificar_firma(),
    validar_comprobante: validar_comprobante(),
    autorizar_comprobante: autorizar_comprobante(),
    autorizado_comprobante: autorizado_comprobante(),
};

/*
export default({ firma, password, factura, tipo, usuario, id_factura }) {
    return new Promise(async (resolve, reject) => {
        try {
            let { data: comprobante } = await obtener_comprobante_firmado({ factura:factura });
            let { data: contenido } = await lectura_firma({ firma:firma });
            let { data: certificado } = await firmar_comprobante({ contenido:contenido[0], password:password, comprobante:comprobante });
            let { data: quefirma } = await verificar_firma({ mensaje:certificado, usuario:usuario, tipo:tipo });
            let { data: validado } = await validar_comprobante({ comprobante:comprobante, usuario:usuario, tipo:tipo });
            let { data: recibida } = await autorizar_comprobante({ comprobante:comprobante, validado:validado, usuario:usuario, tipo:tipo });
            let { data: registrado } = await autorizado_comprobante({ recibida:recibida, tipo:tipo, id_factura:id_factura });
            resolve({registrado});
        } catch (error) {
            reject(errorreject(error));
        }
    });
};
*/
/*obtenerComprobanteFirmado_sri(ruta_certificado,pwd_p12,ruta_factura,tipofactura) {
    var response = [];
    axios.post("/api/leerFacturaphp", { ruta_factura: ruta_factura }).then(respuesta => {
        this.contenido_comprobante = respuesta.data;
        var oReq = new XMLHttpRequest();
        oReq.open("GET", ruta_certificado, true);
        oReq.responseType = "arraybuffer";
        oReq.onload = oEvent => {
            var blob = new Blob([oReq.response], {type: "application/x-pkcs12"});
            this.contenido_p12 = [oReq.response];
            var comprobanteFirmado_xml = this.firmarComprobante(this.contenido_p12[0],pwd_p12,this.contenido_comprobante);
            axios.post("/api/firmaphp", {mensaje: comprobanteFirmado_xml,id_empresa: this.usuario.id_empresa, tipo:tipofactura}).then(res => {
                var service = 'Validar Comprobante';
                var xmlDoc = $.parseXML(this.contenido_comprobante),$xml = $(xmlDoc),$claveAcceso = $xml.find("claveAcceso");
                axios.post("/api/validarComprobantephp", {service: service, claveAcceso: $claveAcceso.text(), id_empresa: this.usuario.id_empresa, tipo:tipofactura}).then(respuestaValidarComprobante => {
                    respuesta = decodeURIComponent(respuestaValidarComprobante.data);
                    respuesta = respuesta.toString();
                    var validar_comprobante = respuestaValidarComprobante.data;   
                    if (/RECIBIDA/i.test(respuesta) || /CLAVE ACCESO REGISTRADA/i.test(respuesta)) {
                        var service = 'Autorizacion Comprobante';
                        var xmlDoc = $.parseXML(this.contenido_comprobante),$xml = $(xmlDoc),$claveAcceso = $xml.find("claveAcceso");
                        axios.post("/api/autorizacionComprobantephp",{service: service,claveAcceso: $claveAcceso.text(),id_empresa:this.usuario.id_empresa, tipo:tipofactura}).then(respuestaAutorizacionComprobante => {
                            var autorizacion_comprobante = respuestaAutorizacionComprobante.data;
                            response[0] = validar_comprobante;
                            response[1] = autorizacion_comprobante;
                            var envioestado ="/api/respfactura";
                            var enviourl = {estado: "Enviado",id: this.recueidfact,tipo:tipofactura};
                            axios.post(envioestado, enviourl).then( () => {
                                this.$vs.notify({
                                    tithis: 8000,
                                    title: "Factura Enviada",
                                    text:"La factura se generó exitosamente",
                                    color: "success"
                                });
                                this.$router.push("/facturacion/nota-credito");
                            }).catch( err => {
                                this.errorf(err,tipofactura);
                            });
                        }).catch( err => {
                            this.errorf(err,tipofactura);
                        });
                    } else {
                        if(/ERROR SECUENCIAL REGISTRADO/i.test(respuesta)){
                            var envioestado = "/api/respfactura";
                            var enviourl = {estado: "Error",id: this.recueidfact,tipo:tipofactura};
                            axios.post(envioestado, enviourl).then( () => {
                                this.$vs.notify({
                                    tithis: 8000,
                                    title: "Factura Erronea",
                                    text:"La secuencia utilizada ya esta registrada, Ingrese otro número secuencial",
                                    color: "danger"
                                });
                                this.$router.push("/facturacion/nota-credito");
                            }).catch( err => {
                                this.$vs.notify({
                                    tithis: 8000,
                                    title: "Factura Erronea",
                                    text:"La secuencia utilizada ya esta registrada, Ingrese otro número secuencial",
                                    color: "danger"
                                });
                                this.$router.push("/facturacion/nota-credito");
                            });
                        }else{
                            response[0] = validar_comprobante;
                            this.errorf(response,tipofactura);
                        }
                    }
                }).catch( err => {
                    this.errorf(err,tipofactura);
                });
            }).catch( err => {
                this.errorf(err,tipofactura);
            });


        };
        oReq.send();
    }).catch( err => {
        this.errorf(err,tipofactura);
    });
}
errorf(err,tipofactura){
    var envioestado = "/api/respfactura";
    var enviourl = {estado: "Error",id: this.recueidfact,tipo:tipofactura};
    axios.post(envioestado, enviourl).then( () => {
        this.$vs.notify({
            tithis: 8000,
            title: "Factura Erronea",
            text:"No se pudo enviar al SRI, Intente mas tarde",
            color: "danger"
        });
    }).catch( () => {
        this.$vs.notify({
            tithis: 8000,
            title: "Factura Erronea",
            text:"No se pudo enviar al SRI, Intente mas tarde",
            color: "danger"
        });
    });
    this.$router.push("/facturacion/nota-credito");
}
firmarComprobante(mi_contenido_p12, mi_pwd_p12, comprobante) {
    var arrayUint8 = new Uint8Array(mi_contenido_p12);
    var p12B64 = forge.util.binary.base64.encode(arrayUint8);
    var p12Der = forge.util.decode64(p12B64);
    var p12Asn1 = forge.asn1.fromDer(p12Der);
    var p12 = forge.pkcs12.pkcs12FromAsn1(p12Asn1, mi_pwd_p12);
    var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
    var signaturesQuantity = certBags[forge.oids.certBag];
    var count = 0;
    var positionSignature = 0;
    var entidad = signaturesQuantity[0].attributes.friendlyName[0];
    if (/BANCO CENTRAL/i.test(entidad)) {
        entidad = 'BANCO_CENTRAL';
        var certBags = p12.getBags({bagType: forge.pki.oids.certBag})
        var cert = certBags[forge.oids.certBag][1].cert;
        var issuerName = 'CN=AC BANCO CENTRAL DEL ECUADOR,L=QUITO,OU=ENTIDAD DE CERTIFICACION DE INFORMACION-ECIBCE,O=BANCO CENTRAL DEL ECUADOR,C=EC';
    }else if(/SECURITY DATA/i.test(entidad)) {
        entidad = 'SECURITY_DATA';
        var contador = 0;
        var max = 0;
        var attributes_array=[];        
        certBags[forge.oids.certBag].forEach(function (entry) {
            var bag = entry.cert;
            var attributes = bag.extensions;
            attributes_array[contador] = attributes;
            attributes_array.sort().reverse();
            max = attributes_array[0].length;   
            contador++;   
        });
        certBags[forge.oids.certBag].forEach(function (entry) {
            var bag = entry.cert;
            var attributes = bag.extensions;
            if (attributes.length >= max) { cert = bag; }   
        });
        var issuerName = 'CN=AUTORIDAD DE CERTIFICACION SUB SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A.,C=EC';
    }else {
        me.$vs.notify({
            time: 8000,
            title:"Error de factura",
            text:"Tipo de certificado no registrado",
            color:"success"
        });
    }
    var fechaInicio = cert.validity['notBefore'];
    var fechaFin = cert.validity['notAfter'];
    var pkcs8bags = p12.getBags({bagType: forge.pki.oids.pkcs8ShroudedKeyBag});
    if (entidad == 'BANCO_CENTRAL') {
        var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][1];
    } else {
        var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][0];
    }
    var key = pkcs8.key;
    if (key == null) {
        key = pkcs8.asn1;
    }
    var certificateX509_pem = forge.pki.certificateToPem(cert);
    var certificateX509 = certificateX509_pem;
    certificateX509 = certificateX509.substr(certificateX509.indexOf('\n'));
    certificateX509 = certificateX509.substr(0, certificateX509.indexOf('\n-----END CERTIFICATE-----'));
    certificateX509 = certificateX509.replace(/\r?\n|\r/g, '').replace(/([^\0]{76})/g, '$1\n');
    var certificateX509_asn1 = forge.pki.certificateToAsn1(cert);
    var certificateX509_der = forge.asn1.toDer(certificateX509_asn1).getBytes();
    var certificateX509_der_hash = this.sha1_base64(certificateX509_der);
    var X509SerialNumber = parseInt(cert.serialNumber, 16);
    var exponent = this.hexToBase64(key.e.data[0].toString(16));
    var modulus = this.bigint2base64(key.n);
    var comprobante = comprobante.replace(/\t|\r/g, "")
    var sha1_comprobante = this.sha1_base64(comprobante.replace('<?xml version="1.0" encoding="UTF-8"?>', ''));
    var xmlns = 'xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#"';
    var Certificate_number = this.p_obtener_aleatorio();
    var Signature_number = this.p_obtener_aleatorio();
    var SignedProperties_number = this.p_obtener_aleatorio();
    var SignedInfo_number = this.p_obtener_aleatorio();
    var SignedPropertiesID_number = this.p_obtener_aleatorio();
    var Reference_ID_number = this.p_obtener_aleatorio();
    var SignatureValue_number = this.p_obtener_aleatorio();
    var Object_number = this.p_obtener_aleatorio();
    var SignedProperties = '';
    SignedProperties += '<etsi:SignedProperties Id="Signature' + Signature_number + '-SignedProperties' + SignedProperties_number + '">';
    SignedProperties += '<etsi:SignedSignatureProperties>';
    SignedProperties += '<etsi:SigningTime>';
    SignedProperties += moment().format('YYYY-MM-DD\THH:mm:ssZ');
    SignedProperties += '</etsi:SigningTime>';
    SignedProperties += '<etsi:SigningCertificate>';
    SignedProperties += '<etsi:Cert>';
    SignedProperties += '<etsi:CertDigest>';
    SignedProperties += '<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedProperties += '</ds:DigestMethod>';
    SignedProperties += '<ds:DigestValue>';
    SignedProperties += certificateX509_der_hash;
    SignedProperties += '</ds:DigestValue>';
    SignedProperties += '</etsi:CertDigest>';
    SignedProperties += '<etsi:IssuerSerial>';
    SignedProperties += '<ds:X509IssuerName>';
    SignedProperties += issuerName;
    SignedProperties += '</ds:X509IssuerName>';
    SignedProperties += '<ds:X509SerialNumber>';
    SignedProperties += X509SerialNumber;
    SignedProperties += '</ds:X509SerialNumber>';
    SignedProperties += '</etsi:IssuerSerial>';
    SignedProperties += '</etsi:Cert>';
    SignedProperties += '</etsi:SigningCertificate>';
    SignedProperties += '</etsi:SignedSignatureProperties>';
    SignedProperties += '<etsi:SignedDataObjectProperties>';
    SignedProperties += '<etsi:DataObjectFormat ObjectReference="#Reference-ID-' + Reference_ID_number + '">';
    SignedProperties += '<etsi:Description>';
    SignedProperties += 'contenido comprobante';
    SignedProperties += '</etsi:Description>';
    SignedProperties += '<etsi:MimeType>';
    SignedProperties += 'text/xml';
    SignedProperties += '</etsi:MimeType>';
    SignedProperties += '</etsi:DataObjectFormat>';
    SignedProperties += '</etsi:SignedDataObjectProperties>';
    SignedProperties += '</etsi:SignedProperties>';
    var SignedProperties_para_hash = SignedProperties.replace('<etsi:SignedProperties', '<etsi:SignedProperties ' + xmlns);
    var sha1_SignedProperties = this.sha1_base64(SignedProperties_para_hash);
    var KeyInfo = '';
    KeyInfo += '<ds:KeyInfo Id="Certificate' + Certificate_number + '">';
    KeyInfo += '\n<ds:X509Data>';
    KeyInfo += '\n<ds:X509Certificate>\n';
    KeyInfo += certificateX509;
    KeyInfo += '\n</ds:X509Certificate>';
    KeyInfo += '\n</ds:X509Data>';
    KeyInfo += '\n<ds:KeyValue>';
    KeyInfo += '\n<ds:RSAKeyValue>';
    KeyInfo += '\n<ds:Modulus>\n';
    KeyInfo += modulus;
    KeyInfo += '\n</ds:Modulus>';
    KeyInfo += '\n<ds:Exponent>';
    KeyInfo += exponent;
    KeyInfo += '</ds:Exponent>';
    KeyInfo += '\n</ds:RSAKeyValue>';
    KeyInfo += '\n</ds:KeyValue>';
    KeyInfo += '\n</ds:KeyInfo>';
    var KeyInfo_para_hash = KeyInfo.replace('<ds:KeyInfo', '<ds:KeyInfo ' + xmlns);
    var sha1_certificado = this.sha1_base64(KeyInfo_para_hash);
    var SignedInfo = '';
    SignedInfo += '<ds:SignedInfo Id="Signature-SignedInfo' + SignedInfo_number + '">';
    SignedInfo += '\n<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315">';
    SignedInfo += '</ds:CanonicalizationMethod>';
    SignedInfo += '\n<ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1">';
    SignedInfo += '</ds:SignatureMethod>';
    SignedInfo += '\n<ds:Reference Id="SignedPropertiesID' + SignedPropertiesID_number + '" Type="http://uri.etsi.org/01903#SignedProperties" URI="#Signature' + Signature_number + '-SignedProperties' + SignedProperties_number + '">';
    SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedInfo += '</ds:DigestMethod>';
    SignedInfo += '\n<ds:DigestValue>';
    SignedInfo += sha1_SignedProperties;
    SignedInfo += '</ds:DigestValue>';
    SignedInfo += '\n</ds:Reference>';
    SignedInfo += '\n<ds:Reference URI="#Certificate' + Certificate_number + '">';
    SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedInfo += '</ds:DigestMethod>';
    SignedInfo += '\n<ds:DigestValue>';
    SignedInfo += sha1_certificado;
    SignedInfo += '</ds:DigestValue>';
    SignedInfo += '\n</ds:Reference>';
    SignedInfo += '\n<ds:Reference Id="Reference-ID-' + Reference_ID_number + '" URI="#comprobante">';
    SignedInfo += '\n<ds:Transforms>';
    SignedInfo += '\n<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature">';
    SignedInfo += '</ds:Transform>';
    SignedInfo += '\n</ds:Transforms>';
    SignedInfo += '\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedInfo += '</ds:DigestMethod>';
    SignedInfo += '\n<ds:DigestValue>';
    SignedInfo += sha1_comprobante;
    SignedInfo += '</ds:DigestValue>';
    SignedInfo += '\n</ds:Reference>';
    SignedInfo += '\n</ds:SignedInfo>';
    var SignedInfo_para_firma = SignedInfo.replace('<ds:SignedInfo', '<ds:SignedInfo ' + xmlns);
    var md = forge.md.sha1.create();
    md.update(SignedInfo_para_firma, 'utf8');
    var signature = btoa(key.sign(md)).match(/.{1,76}/g).join("\n");
    var xades_bes = '';
    xades_bes += '<ds:Signature ' + xmlns + ' Id="Signature' + Signature_number + '">';
    xades_bes += '\n' + SignedInfo;
    xades_bes += '\n<ds:SignatureValue Id="SignatureValue' + SignatureValue_number + '">\n';
    xades_bes += signature;
    xades_bes += '\n</ds:SignatureValue>';
    xades_bes += '\n' + KeyInfo;
    xades_bes += '\n<ds:Object Id="Signature' + Signature_number + '-Object' + Object_number + '">';
    xades_bes += '<etsi:QualifyingProperties Target="#Signature' + Signature_number + '">';
    xades_bes += SignedProperties;
    xades_bes += '</etsi:QualifyingProperties>';
    xades_bes += '</ds:Object>';
    xades_bes += '</ds:Signature>';
    return  comprobante.replace(/(<[^<]+)$/, xades_bes + '$1');
}
sha1_base64(txt) {
    var md = forge.md.sha1.create();
    md.update(txt);
    return new window.buffer.Buffer(md.digest().toHex(), 'hex').toString('base64');
}
hexToBase64(str) {
    var hex = ('00' + str).slice(0 - str.length - str.length % 2);
    return btoa(String.fromCharCode.apply(null,hex.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" ")));
}
bigint2base64(bigint) {
    var base64 = '';
    base64 = btoa(bigint.toString(16).match(/\w{2}/g).map(function (a) { return String.fromCharCode(parseInt(a, 16)); }).join(""));
    base64 = base64.match(/.{1,76}/g).join("\n");
    return base64;
}
p_obtener_aleatorio() {
    return Math.floor(Math.random() * 999000) + 990;
}*/
