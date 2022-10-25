const axios = require("axios");

const obtenerComprobanteFirmado_sri = (ruta_certificado,pwd_p12,ruta_factura, id_empresa) => {
    var contenido_p12 =[];
    var contenido_comprobante = [];
    var recueidfact = null;
    axios.post("/api/leerFacturaphp", { ruta_factura: ruta_factura }).then(respuesta => {
        contenido_comprobante = respuesta.data;
        var xmlDoc1 = $.parseXML(contenido_comprobante),$xml1 = $(xmlDoc1),$claveAcceso1 = $xml1.find("claveAcceso");
        alert($claveAcceso1.text());
        console.log($claveAcceso1.text());
        return;
        var oReq = new XMLHttpRequest();
        oReq.open("GET", ruta_certificado, true);
        oReq.responseType = "arraybuffer";
        oReq.onload = function(oEvent) {
            var blob = new Blob([oReq.response], {type: "application/x-pkcs12"});
            contenido_p12 = [oReq.response];
            var comprobanteFirmado_xml = firmarComprobante(contenido_p12[0],pwd_p12,contenido_comprobante);
            axios.post("/api/firmaphp", {mensaje: comprobanteFirmado_xml,id_empresa: id_empresa, claveAcceso: $claveAcceso1.text()}).then(respuesta => {
                var service = "Validar Comprobante";
                var xmlDoc = $.parseXML(contenido_comprobante),$xml = $(xmlDoc),$claveAcceso = $xml.find("claveAcceso");
                axios.post("/api/validarComprobantephp", {service: service, claveAcceso: $claveAcceso.text(), id_empresa: id_empresa}).then(respuestaValidarComprobante => {
                    respuesta = decodeURIComponent( respuestaValidarComprobante.data);
                    respuesta = respuesta.toString();
                    var validar_comprobante = respuestaValidarComprobante.data;
                    if (/RECIBIDA/i.test(respuesta) ||/CLAVE ACCESO REGISTRADA/i.test(respuesta)) {
                        service = "Autorizacion Comprobante";
                        (xmlDoc = $.parseXML(contenido_comprobante)),($xml = $(xmlDoc)),($claveAcceso = $xml.find("claveAcceso"));
                        axios.post("/api/autorizacionComprobantephp",{service: service,claveAcceso: $claveAcceso.text(),id_empresa:id_empresa}).then(respuestaAutorizacionComprobante => {
                            var autorizacion_comprobante = respuestaAutorizacionComprobante.data;
                            var envioestado ="/api/respfactura";
                            var enviourl = {estado: "Enviado",id: recueidfact};
                            axios.post( envioestado, enviourl).then(res => {
                                $vs.notify({
                                    time: 8000,
                                    title:"Factura Exitoso",
                                    text:"Factura agregado con éxito y enviado al correo electrónico del cliente",
                                    color:"success"
                                });
                                $router.push("/facturacion/factura-venta");
                            }).catch(err => {
                                $vs.notify({
                                    time: 8000,
                                    title:"Error en el sistema, Intente mas tarde",
                                    text: "La factura no fue autorizada 3 o hubo un error en el envio, Intente mas tarde",
                                    color:"danger"
                                });
                                $router.push("/facturacion/factura-venta");
                            });            
                        }).catch(err => {
                            $vs.notify({
                                time: 8000,
                                title: "Factura Erronea",
                                text:"La factura no fue autorizada 2 o hubo un error en el envio, Intente mas tarde",
                                color: "danger"
                            });
                            $router.push("/facturacion/factura-venta");
                        });
                    } else {
                        var envioestado ="/api/respfactura";
                        var enviourl = {estado: "No autorizado",id: recueidfact};
                        axios.post(envioestado, enviourl);
                        $vs.notify({
                            time: 8000,
                            title: "Factura Erronea",
                            text:"La factura no fue autorizada 1 o hubo un error en el envio, Intente mas tarde",
                            color: "danger"
                        });
                        $router.push("/facturacion/factura-venta");
                    }
                }).catch(err => {
                    var envioestado = "/api/respfactura";
                    var enviourl = {estado: "Error",id: recueidfact};
                    axios.post(envioestado, enviourl);
                    $vs.notify({
                        time: 8000,
                        title: "Factura Erronea",
                        text:"La factura no pudo ser validada, verifique la factura o Intente mas tarde",
                        color: "danger"
                    });
                    $router.push("/facturacion/factura-venta");
                });
            }).catch(err => {
                var envioestado = "/api/respfactura";
                var enviourl = {estado: "Error",id: recueidfact};
                axios.post(envioestado, enviourl);
                $vs.notify({
                    time: 8000,
                    title: "Error SRI",
                    text:"El sistema del SRI se encuentra saturado, Intente mas tarde",
                    color: "danger"
                });
                $router.push("/facturacion/factura-venta");
            });
        };
        oReq.send();
    }).catch(err => {
        $vs.notify({
            time: 8000,
            title: "Error",
            text: "Hubo un error con la factura",
            color: "danger"
        });
        $router.push("/facturacion/factura-venta");
    });
}

const firmarComprobante = (mi_contenido_p12, mi_pwd_p12, comprobante) => {
    var arrayUint8 = new Uint8Array(mi_contenido_p12);
    var p12B64 = forge.util.binary.base64.encode(arrayUint8);
    var p12Der = forge.util.decode64(p12B64);
    var p12Asn1 = forge.asn1.fromDer(p12Der);
    var p12 = forge.pkcs12.pkcs12FromAsn1(p12Asn1, mi_pwd_p12);
    var certBags = p12.getBags({bagType: forge.pki.oids.certBag});
    var signaturesQuantity = certBags[forge.oids.certBag];
    var count = 0;
    var positionSignature = 0;
    var entidad = signaturesQuantity[0].attributes.friendlyName[0];
    alert("aqui llega 2")
    console.log("aqui llega 2")
    if (/BANCO CENTRAL/i.test(entidad)) {
        entidad = "BANCO_CENTRAL";
        var certBags = p12.getBags({bagType: forge.pki.oids.certBag});
        var cert = certBags[forge.oids.certBag][1].cert;
        var issuerName ="CN=AC BANCO CENTRAL DEL ECUADOR,L=QUITO,OU=ENTIDAD DE CERTIFICACION DE INFORMACION-ECIBCE,O=BANCO CENTRAL DEL ECUADOR,C=EC";
    } else if (/SECURITY DATA/i.test(entidad)) {
        entidad = "SECURITY_DATA";
        var contador = 0;
        var max = 0;
        var attributes_array = [];
        certBags[forge.oids.certBag].forEach(function(entry) {
            var bag = entry.cert;
            var attributes = bag.extensions;
            attributes_array[contador] = attributes;
            attributes_array.sort().reverse();
            max = attributes_array[0].length;
            contador++;
        });
        certBags[forge.oids.certBag].forEach(function(entry) {
            var bag = entry.cert;
            var attributes = bag.extensions;
            if (attributes.length >= max) {cert = bag;}
        });
        var issuerName ="CN=AUTORIDAD DE CERTIFICACION SUB SECURITY DATA,OU=ENTIDAD DE CERTIFICACION DE INFORMACION,O=SECURITY DATA S.A.,C=EC";
    } else {
        alert("Tipo de certificado no registrado");
    }
    var fechaInicio = cert.validity["notBefore"];
    var fechaFin = cert.validity["notAfter"];
    axios.post("/api/validarFechaCertificadophp", {fechaInicio: fechaInicio,fechaFin: fechaFin}).then(respuesta => {});
    var pkcs8bags = p12.getBags({bagType: forge.pki.oids.pkcs8ShroudedKeyBag});
    if (entidad == "BANCO_CENTRAL") {var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][1];} else {var pkcs8 = pkcs8bags[forge.oids.pkcs8ShroudedKeyBag][0];}
    var key = pkcs8.key;
    if (key == null) {key = pkcs8.asn1;}
    var certificateX509_pem = forge.pki.certificateToPem(cert);
    var certificateX509 = certificateX509_pem;
    certificateX509 = certificateX509.substr(certificateX509.indexOf("\n"));
    certificateX509 = certificateX509.substr(0,certificateX509.indexOf("\n-----END CERTIFICATE-----"));
    certificateX509 = certificateX509.replace(/\r?\n|\r/g, "").replace(/([^\0]{76})/g, "$1\n");
    var certificateX509_asn1 = forge.pki.certificateToAsn1(cert);
    var certificateX509_der = forge.asn1.toDer(certificateX509_asn1).getBytes();
    var certificateX509_der_hash = sha1_base64(certificateX509_der);
    var X509SerialNumber = parseInt(cert.serialNumber, 16);
    var exponent = hexToBase64(key.e.data[0].toString(16));
    var modulus = bigint2base64(key.n);
    var comprobante = comprobante.replace(/\t|\r/g, "");
    var sha1_comprobante = sha1_base64(comprobante.replace('<?xml version="1.0" encoding="UTF-8"?>',""));
    var xmlns ='xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:etsi="http://uri.etsi.org/01903/v1.3.2#"';
    var Certificate_number = p_obtener_aleatorio();
    var Signature_number = p_obtener_aleatorio();
    var SignedProperties_number = p_obtener_aleatorio();;
    var SignedInfo_number = p_obtener_aleatorio();
    var SignedPropertiesID_number = p_obtener_aleatorio();
    var Reference_ID_number = p_obtener_aleatorio();
    var SignatureValue_number = p_obtener_aleatorio();
    var Object_number = p_obtener_aleatorio();
    var SignedProperties = "";
    SignedProperties +='<etsi:SignedProperties Id="Signature' +Signature_number +"-SignedProperties" +SignedProperties_number +'">';
    SignedProperties += "<etsi:SignedSignatureProperties>";
    SignedProperties += "<etsi:SigningTime>";
    SignedProperties += moment().format("YYYY-MM-DDTHH:mm:ssZ");
    SignedProperties += "</etsi:SigningTime>";
    SignedProperties += "<etsi:SigningCertificate>";
    SignedProperties += "<etsi:Cert>";
    SignedProperties += "<etsi:CertDigest>";
    SignedProperties +='<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedProperties += "</ds:DigestMethod>";
    SignedProperties += "<ds:DigestValue>";
    SignedProperties += certificateX509_der_hash;
    SignedProperties += "</ds:DigestValue>";
    SignedProperties += "</etsi:CertDigest>";
    SignedProperties += "<etsi:IssuerSerial>";
    SignedProperties += "<ds:X509IssuerName>";
    SignedProperties += issuerName;
    SignedProperties += "</ds:X509IssuerName>";
    SignedProperties += "<ds:X509SerialNumber>";
    SignedProperties += X509SerialNumber;
    SignedProperties += "</ds:X509SerialNumber>";
    SignedProperties += "</etsi:IssuerSerial>";
    SignedProperties += "</etsi:Cert>";
    SignedProperties += "</etsi:SigningCertificate>";
    SignedProperties += "</etsi:SignedSignatureProperties>";
    SignedProperties += "<etsi:SignedDataObjectProperties>";
    SignedProperties +='<etsi:DataObjectFormat ObjectReference="#Reference-ID-' +Reference_ID_number +'">';
    SignedProperties += "<etsi:Description>";
    SignedProperties += "contenido comprobante";
    SignedProperties += "</etsi:Description>";
    SignedProperties += "<etsi:MimeType>";
    SignedProperties += "text/xml";
    SignedProperties += "</etsi:MimeType>";
    SignedProperties += "</etsi:DataObjectFormat>";
    SignedProperties += "</etsi:SignedDataObjectProperties>";
    SignedProperties += "</etsi:SignedProperties>";
    var SignedProperties_para_hash = SignedProperties.replace("<etsi:SignedProperties","<etsi:SignedProperties " + xmlns);
    var sha1_SignedProperties = sha1_base64(SignedProperties_para_hash);
    var KeyInfo = "";
    KeyInfo +='<ds:KeyInfo Id="Certificate' + Certificate_number + '">';
    KeyInfo += "\n<ds:X509Data>";
    KeyInfo += "\n<ds:X509Certificate>\n";
    KeyInfo += certificateX509;
    KeyInfo += "\n</ds:X509Certificate>";
    KeyInfo += "\n</ds:X509Data>";
    KeyInfo += "\n<ds:KeyValue>";
    KeyInfo += "\n<ds:RSAKeyValue>";
    KeyInfo += "\n<ds:Modulus>\n";
    KeyInfo += modulus;
    KeyInfo += "\n</ds:Modulus>";
    KeyInfo += "\n<ds:Exponent>";
    KeyInfo += exponent;
    KeyInfo += "</ds:Exponent>";
    KeyInfo += "\n</ds:RSAKeyValue>";
    KeyInfo += "\n</ds:KeyValue>";
    KeyInfo += "\n</ds:KeyInfo>";
    var KeyInfo_para_hash = KeyInfo.replace("<ds:KeyInfo","<ds:KeyInfo " + xmlns);
    var sha1_certificado = sha1_base64(KeyInfo_para_hash);
    var SignedInfo = "";
    SignedInfo += '<ds:SignedInfo Id="Signature-SignedInfo' +SignedInfo_number +'">';
    SignedInfo +='\n<ds:CanonicalizationMethod Algorithm="http://www.w3.org/TR/2001/REC-xml-c14n-20010315">';
    SignedInfo += "</ds:CanonicalizationMethod>";
    SignedInfo +='\n<ds:SignatureMethod Algorithm="http://www.w3.org/2000/09/xmldsig#rsa-sha1">';
    SignedInfo += "</ds:SignatureMethod>";
    SignedInfo +='\n<ds:Reference Id="SignedPropertiesID' +SignedPropertiesID_number +'" Type="http://uri.etsi.org/01903#SignedProperties" URI="#Signature' +Signature_number +"-SignedProperties" +SignedProperties_number +'">';
    SignedInfo +='\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedInfo += "</ds:DigestMethod>";
    SignedInfo += "\n<ds:DigestValue>";
    SignedInfo += sha1_SignedProperties;
    SignedInfo += "</ds:DigestValue>";
    SignedInfo += "\n</ds:Reference>";
    SignedInfo +='\n<ds:Reference URI="#Certificate' + Certificate_number + '">';
    SignedInfo +='\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedInfo += "</ds:DigestMethod>";
    SignedInfo += "\n<ds:DigestValue>";
    SignedInfo += sha1_certificado;
    SignedInfo += "</ds:DigestValue>";
    SignedInfo += "\n</ds:Reference>";
    SignedInfo +='\n<ds:Reference Id="Reference-ID-' + Reference_ID_number +'" URI="#comprobante">';
    SignedInfo += "\n<ds:Transforms>";
    SignedInfo +='\n<ds:Transform Algorithm="http://www.w3.org/2000/09/xmldsig#enveloped-signature">';
    SignedInfo += "</ds:Transform>";
    SignedInfo += "\n</ds:Transforms>";
    SignedInfo +='\n<ds:DigestMethod Algorithm="http://www.w3.org/2000/09/xmldsig#sha1">';
    SignedInfo += "</ds:DigestMethod>";
    SignedInfo += "\n<ds:DigestValue>";
    SignedInfo += sha1_comprobante;
    SignedInfo += "</ds:DigestValue>";
    SignedInfo += "\n</ds:Reference>";
    SignedInfo += "\n</ds:SignedInfo>";
    var SignedInfo_para_firma = SignedInfo.replace("<ds:SignedInfo","<ds:SignedInfo " + xmlns);
    var md = forge.md.sha1.create();
    md.update(SignedInfo_para_firma, "utf8");
    var signature = btoa(key.sign(md)).match(/.{1,76}/g).join("\n");
    var xades_bes = "";
    xades_bes +="<ds:Signature " + xmlns + ' Id="Signature' +Signature_number +'">';
    xades_bes += "\n" + SignedInfo;
    xades_bes +='\n<ds:SignatureValue Id="SignatureValue' + SignatureValue_number +'">\n';
    xades_bes += signature;
    xades_bes += "\n</ds:SignatureValue>";
    xades_bes += "\n" + KeyInfo;
    xades_bes += '\n<ds:Object Id="Signature' +Signature_number +"-Object" +Object_number +'">';
    xades_bes +='<etsi:QualifyingProperties Target="#Signature' +Signature_number +'">';
    xades_bes += SignedProperties;
    xades_bes += "</etsi:QualifyingProperties>";
    xades_bes += "</ds:Object>";
    xades_bes += "</ds:Signature>";
    return comprobante.replace(/(<[^<]+)$/, xades_bes + "$1");
}

const sha1_base64 = (txt) => {
    var md = forge.md.sha1.create();
    md.update(txt);
    return new window.buffer.Buffer(md.digest().toHex(),"hex").toString("base64");
}

const hexToBase64 = (str) => {
    var hex = ("00" + str).slice(0 - str.length - (str.length % 2));
    return btoa(String.fromCharCode.apply(null,hex.replace(/\r|\n/g, "").replace(/([\da-fA-F]{2}) ?/g, "0x$1 ").replace(/ +$/, "").split(" ")));
}

const bigint2base64 = (bigint) => {
    var base64 = "";
    base64 = btoa(bigint.toString(16).match(/\w{2}/g).map(function(a) {return String.fromCharCode(parseInt(a, 16));}).join(""));
    base64 = base64.match(/.{1,76}/g).join("\n");
    return base64;
}

const p_obtener_aleatorio = () => {
    return Math.floor(Math.random() * 999000) + 990;
}

module.exports = {
    obtenerComprobanteFirmado_sri,
    firmarComprobante,
    sha1_base64,
    hexToBase64,
    bigint2base64,
    p_obtener_aleatorio
}