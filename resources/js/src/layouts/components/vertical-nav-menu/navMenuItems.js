import store from "../../../store/store";
import $ from "jquery";
if (store.state.Roles.length) {
    var pep = store.state.Roles;
    //empresa
    var empresa_ver = false;
    var empresa_array_ver = $.grep(pep, function(e) {
        return e.value == 1;
    });
    if (empresa_array_ver.length) {
        empresa_ver = empresa_array_ver[0].ver;
    }
    //Establecimiento
    var establecimiento_ver = false;
    var establecimiento_array_ver = $.grep(pep, function(e) {
        return e.value == 2;
    });
    if (establecimiento_array_ver.length) {
        establecimiento_ver = establecimiento_array_ver[0].ver;
    }
    //punto de emision
    var emision_ver = false;
    var emision_array_ver = $.grep(pep, function(e) {
        return e.value == 3;
    });
    if (emision_array_ver.length) {
        emision_ver = emision_array_ver[0].ver;
    }
    //usuarios
    var usuario_ver = false;
    var usuario_array_ver = $.grep(pep, function(e) {
        return e.value == 4;
    });
    if (usuario_array_ver.length) {
        usuario_ver = usuario_array_ver[0].ver;
    }
    //codigo-impuesto
    var impuesto_ver = false;
    var impuesto_array_ver = $.grep(pep, function(e) {
        return e.value == 5;
    });
    if (impuesto_array_ver.length) {
        impuesto_ver = impuesto_array_ver[0].ver;
    }
    //codigo-ice
    var impuesto_ice_ver = false;
    var impuesto_ice_array_ver = $.grep(pep, function(e) {
        return e.value == 65;
    });
    if (impuesto_ice_array_ver.length) {
        impuesto_ice_ver = impuesto_ice_array_ver[0].ver;
    }
    //tipo comprobante
    var comprobante_ver = false;
    var comprobante_array_ver = $.grep(pep, function(e) {
        return e.value == 6;
    });
    if (comprobante_array_ver.length) {
        comprobante_ver = comprobante_array_ver[0].ver;
    }
    //impuestos retenciones
    var retenciones_ver = false;
    var retenciones_array_ver = $.grep(pep, function(e) {
        return e.value == 7;
    });
    if (retenciones_array_ver.length) {
        retenciones_ver = retenciones_array_ver[0].ver;
    }
    //tipo sustento
    var sustento_ver = false;
    var sustento_array_ver = $.grep(pep, function(e) {
        return e.value == 8;
    });
    if (sustento_array_ver.length) {
        sustento_ver = sustento_array_ver[0].ver;
    }
    //plan de cuentas
    var cuentas_ver = false;
    var cuentas_array_ver = $.grep(pep, function(e) {
        return e.value == 9;
    });
    if (cuentas_array_ver.length) {
        cuentas_ver = cuentas_array_ver[0].ver;
    }
    //asientos contable
    var asientos_ver = false;
    var asientos_array_ver = $.grep(pep, function(e) {
        return e.value == 10;
    });
    if (asientos_array_ver.length) {
        asientos_ver = asientos_array_ver[0].ver;
    }
    //nota de credito
    var credito_ver = false;
    var credito_array_ver = $.grep(pep, function(e) {
        return e.value == 11;
    });
    if (credito_array_ver.length) {
        credito_ver = credito_array_ver[0].ver;
    }
    var credito_crear = false;
    var credito_array_crear = $.grep(pep, function(e) {
        return e.value == 11;
    });
    if (credito_array_crear.length) {
        credito_crear = credito_array_crear[0].editar;
    }
    //facturas
    var factura_ver = false;
    var factura_array_ver = $.grep(pep, function(e) {
        return e.value == 12;
    });
    if (factura_array_ver.length) {
        factura_ver = factura_array_ver[0].ver;
    }
    //factura_acumulada
    var factura_acumulada_ver = false;
    var factura_acumulada_array_ver = $.grep(pep, function(e) {
        return e.value == 70;
    });
    if (factura_acumulada_array_ver.length) {
        factura_acumulada_ver = factura_acumulada_array_ver[0].ver;
    }
    //guia_remision
    var guia_remision_ver = false;
    var guia_remision_array_ver = $.grep(pep, function(e) {
        return e.value == 71;
    });
    if (guia_remision_array_ver.length) {
        guia_remision_ver = guia_remision_array_ver[0].ver;
    }
    //proforma
    var proforma_ver = false;
    var proforma_array_ver = $.grep(pep, function(e) {
        return e.value == 13;
    });
    if (proforma_array_ver.length) {
        proforma_ver = proforma_array_ver[0].ver;
    }
    //reporteria -facturacion
    var reporteria_ver = false;
    var reporteria_array_ver = $.grep(pep, function(e) {
        return e.value == 38;
    });
    //reporteria
    var reporteria_campras_ver = false;
    var reporteria_campras_array_ver = $.grep(pep, function(e) {
        return e.value == 39;
    });
    if (reporteria_campras_array_ver.length) {
        reporteria_campras_ver = reporteria_campras_array_ver[0].ver;
    }
    //pago_Clinete
    var pago_cliente_ver = false;
    var pago_cliente_array_ver = $.grep(pep, function(e) {
        return e.value == 23;
    });
    if (pago_cliente_array_ver.length) {
        pago_cliente_ver = pago_cliente_array_ver[0].ver;
    }
    //cliente
    var cliente_ver = false;
    var cliente_array_ver = $.grep(pep, function(e) {
        return e.value == 15;
    });
    if (cliente_array_ver.length) {
        cliente_ver = cliente_array_ver[0].ver;
    }
    //vendedor
    var vendedor_ver = false;
    var vendedor_array_ver = $.grep(pep, function(e) {
        return e.value == 16;
    });
    if (vendedor_array_ver.length) {
        vendedor_ver = vendedor_array_ver[0].ver;
    }
    //bodega
    var bodega_ver = false;
    var bodega_array_ver = $.grep(pep, function(e) {
        return e.value == 17;
    });
    if (bodega_array_ver.length) {
        bodega_ver = bodega_array_ver[0].ver;
    }
    //catalogo
    var catalogo_ver = false;
    var catalogo_array_ver = $.grep(pep, function(e) {
        return e.value == 18;
    });
    if (catalogo_array_ver.length) {
        catalogo_ver = catalogo_array_ver[0].ver;
    }
 
    //proveedor
    var proveedor_ver = false;
    var proveedor_array_ver = $.grep(pep, function(e) {
        return e.value == 20;
    });
    if (proveedor_array_ver.length) {
        proveedor_ver = proveedor_array_ver[0].ver;
    }
    //facturas compra
    var facturas_compra_ver = false;
    var facturas_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 21;
    });
    if (facturas_compra_array_ver.length) {
        facturas_compra_ver = facturas_compra_array_ver[0].ver;
    }
 
    //cuentas por pagar
    var cuentas_pagar_compra_ver = false;
    var cuentas_pagar_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 14;
    });
    if (cuentas_pagar_compra_array_ver.length) {
        cuentas_pagar_compra_ver = cuentas_pagar_compra_array_ver[0].ver;
    }
 
    //seguimiento
    var seguimiento_ver = false;
    var seguimiento_array_ver = $.grep(pep, function(e) {
        return e.value == 73;
    });
    if (seguimiento_array_ver.length) {
        seguimiento_ver = seguimiento_array_ver[0].ver;
    }

   
    //calendario
    var calendario_compra_ver = false;
    var calendario_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 27;
    });
    if (calendario_compra_array_ver.length) {
        calendario_compra_ver = calendario_compra_array_ver[0].ver;
    }
    //orden-compra
    var orden_compra_ver = false;
    var orden_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 28;
    });
    if (orden_compra_array_ver.length) {
        orden_compra_ver = orden_compra_array_ver[0].ver;
    }
    //ingresos
    var ingreso_egreso_ver = false;
    var ingreso_egreso_array_ver = $.grep(pep, function(e) {
        return e.value == 29;
    });
    if (ingreso_egreso_array_ver.length) {
        ingreso_egreso_ver = ingreso_egreso_array_ver[0].ver;
    }
    //parametrizacion
    var parametrizacion_ver = false;
    var parametrizacion_array_ver = $.grep(pep, function(e) {
        return e.value == 64;
    });
    if (parametrizacion_array_ver.length) {
        parametrizacion_ver = parametrizacion_array_ver[0].ver;
    }
    //rol-pago
    var rol_pago_ver = false;
    var rol_pago_array_ver = $.grep(pep, function(e) {
        return e.value == 30;
    });
    if (rol_pago_array_ver.length) {
        rol_pago_ver = rol_pago_array_ver[0].ver;
    }
    //proyectos
    var proyecto_ver = false;
    var proyecto_array_ver = $.grep(pep, function(e) {
        return e.value == 31;
    });
    if (proyecto_array_ver.length) {
        proyecto_ver = proyecto_array_ver[0].ver;
    }
    //asignar
    var asignar_ingreso_ver = false;
    var asignar_ingreso_array_ver = $.grep(pep, function(e) {
        return e.value == 32;
    });
    if (asignar_ingreso_array_ver.length) {
        asignar_ingreso_ver = asignar_ingreso_array_ver[0].ver;
    }
    //rol-provision
    var rol_provision_ver = false;
    var rol_provision_array_ver = $.grep(pep, function(e) {
        return e.value == 33;
    });
    if (rol_provision_array_ver.length) {
        rol_provision_ver = rol_provision_array_ver[0].ver;
    }
    // pago empleado
    var pago_empleado_ver = false;
    var pago_empleado_array_ver = $.grep(pep, function(e) {
        return e.value == 67;
    });
    if (pago_empleado_array_ver.length) {
        pago_empleado_ver = pago_empleado_array_ver[0].ver;
    }
    //Cobranzas
    var cobranzas_ver = false;
    var cobranzas_array_ver = $.grep(pep, function(e) {
        return e.value == 34;
    });
    if (cobranzas_array_ver.length) {
        cobranzas_ver = cobranzas_array_ver[0].ver;
    }
    //Mora
    var mora_ver = false;
    var mora_array_ver = $.grep(pep, function(e) {
        return e.value == 35;
    });
    if (mora_array_ver.length) {
        mora_ver = mora_array_ver[0].ver;
    }
    //Forma de pago
    var forma_pago_ver = false;
    var forma_pago_array_ver = $.grep(pep, function(e) {
        return e.value == 36;
    });
    if (forma_pago_array_ver.length) {
        forma_pago_ver = forma_pago_array_ver[0].ver;
    }
    //Forma de cobro
    var forma_cobro_ver = false;
    var forma_cobro_array_ver = $.grep(pep, function(e) {
        return e.value == 37;
    });
    if (forma_cobro_array_ver.length) {
        forma_cobro_ver = forma_cobro_array_ver[0].ver;
    }

    // concillacion_bancaria
    var concillacion_bancaria_ver = false;
    var concillacion_bancaria_array_ver = $.grep(pep, function(e) {
        return e.value == 40;
    });
    if (concillacion_bancaria_array_ver.length) {
        concillacion_bancaria_ver = concillacion_bancaria_array_ver[0].ver;
    }

    // anexo_sri
    var anexo_sri_ver = false;
    var anexo_sri_array_ver = $.grep(pep, function(e) {
        return e.value == 41;
    });
    if (anexo_sri_array_ver.length) {
        anexo_sri_ver = anexo_sri_array_ver[0].ver;
    }

    // indices_financieros
    var indices_financieros_ver = false;
    var indices_financieros_array_ver = $.grep(pep, function(e) {
        return e.value == 42;
    });
    if (indices_financieros_array_ver.length) {
        indices_financieros_ver = indices_financieros_array_ver[0].ver;
    }

    // reporteria contabilidad
    var reporteria_contabilidad_ver = false;
    var reporteria_contabilidad_array_ver = $.grep(pep, function(e) {
        return e.value == 43;
    });
    if (reporteria_contabilidad_array_ver.length) {
        reporteria_contabilidad_ver = reporteria_contabilidad_array_ver[0].ver;
    }

    // Nota debito
    var nota_debito_ver = false;
    var nota_debito_array_ver = $.grep(pep, function(e) {
        return e.value == 44;
    });
    if (nota_debito_array_ver.length) {
        nota_debito_ver = nota_debito_array_ver[0].ver;
    }

    // Liquidacion compra
    var liquidacion_compra_ver = false;
    var liquidacion_compra_array_ver = $.grep(pep, function(e) {
        return e.value == 45;
    });
    if (liquidacion_compra_array_ver.length) {
        liquidacion_compra_ver = liquidacion_compra_array_ver[0].ver;
    }

    // Nota de credito compras
    var nota_de_credito_ver = false;
    var nota_de_credito_array_ver = $.grep(pep, function(e) {
        return e.value == 46;
    });
    if (nota_de_credito_array_ver.length) {
        nota_de_credito_ver = nota_de_credito_array_ver[0].ver;
    }

    // Nota de debito compras
    var nota_de_debito_ver = false;
    var nota_de_debito_array_ver = $.grep(pep, function(e) {
        return e.value == 47;
    });
    if (nota_de_debito_array_ver.length) {
        nota_de_debito_ver = nota_de_debito_array_ver[0].ver;
    }

    // Reporteria inventario
    var reporteria_inventario_ver = false;
    var reporteria_inventario_array_ver = $.grep(pep, function(e) {
        return e.value == 53;
    });
    if (reporteria_inventario_array_ver.length) {
        reporteria_inventario_ver = reporteria_inventario_array_ver[0].ver;
    }

    // Departamento
    var departamento_ver = false;
    var departamento_array_ver = $.grep(pep, function(e) {
        return e.value == 54;
    });
    if (departamento_array_ver.length) {
        departamento_ver = departamento_array_ver[0].ver;
    }

    // Area de trabajo
    var area_trabajo_ver = false;
    var area_trabajo_array_ver = $.grep(pep, function(e) {
        return e.value == 55;
    });
    if (area_trabajo_array_ver.length) {
        area_trabajo_ver = area_trabajo_array_ver[0].ver;
    }

    // Cargo
    var cargo_ver = false;
    var cargo_array_ver = $.grep(pep, function(e) {
        return e.value == 56;
    });
    if (cargo_array_ver.length) {
        cargo_ver = cargo_array_ver[0].ver;
    }

    // Grupo ocupacional
    var grupo_ocupacional_ver = false;
    var grupo_ocupacional_array_ver = $.grep(pep, function(e) {
        return e.value == 57;
    });
    if (grupo_ocupacional_array_ver.length) {
        grupo_ocupacional_ver = grupo_ocupacional_array_ver[0].ver;
    }

    // Informacion basica
    var informacion_basica_ver = false;
    var informacion_basica_array_ver = $.grep(pep, function(e) {
        return e.value == 58;
    });
    if (informacion_basica_array_ver.length) {
        informacion_basica_ver = informacion_basica_array_ver[0].ver;
    }

    // Detalle del cargo
    var detalle_cargo_ver = false;
    var detalle_cargo_array_ver = $.grep(pep, function(e) {
        return e.value == 59;
    });
    if (detalle_cargo_array_ver.length) {
        detalle_cargo_ver = detalle_cargo_array_ver[0].ver;
    }

    // Cargas
    var cargas_ver = false;
    var cargas_array_ver = $.grep(pep, function(e) {
        return e.value == 60;
    });
    if (cargas_array_ver.length) {
        cargas_ver = cargas_array_ver[0].ver;
    }

    // Reporteria nomina
    var reporteria_nomina_ver = false;
    var reporteria_nomina_array_ver = $.grep(pep, function(e) {
        return e.value == 61;
    });
    if (reporteria_nomina_array_ver.length) {
        reporteria_nomina_ver = reporteria_nomina_array_ver[0].ver;
    }

    // Forma de pago SRI
    var formas_pagos_sri_ver = false;
    var formas_pagos_sri_array_ver = $.grep(pep, function(e) {
        return e.value == 63;
    });
    if (formas_pagos_sri_array_ver.length) {
        formas_pagos_sri_ver = formas_pagos_sri_array_ver[0].ver;
    }

    // ejercicio contable
    var ejercicio_contable_ver = false;
    var ejercicio_contable_array_ver = $.grep(pep, function(e) {
        return e.value == 68;
    });
    if (ejercicio_contable_array_ver.length) {
        ejercicio_contable_ver = ejercicio_contable_array_ver[0].ver;
    }

    // Balance Inicial
    var balance_inicial_ver = false;
    var balance_inicial_array_ver = $.grep(pep, function(e) {
        return e.value == 69;
    });
    if (balance_inicial_array_ver.length) {
        balance_inicial_ver = balance_inicial_array_ver[0].ver;
    }

}
var cont = [];
if (
    store.state.AppActiveUser.id_rol == 3 &&
    store.state.AppActiveUser.id_rol == 3
) {
    cont.push({
        url: "/administrar/empresa",
        name: "AdministrarNegocio",
        icon: "EditIcon",
        i18n: "Empresas"
    });
} else {
    cont.push({
        url: "/",
        name: "Inicio",
        slug: "/",
        icon: "HomeIcon",
        i18n: "Inicio"
    });
    if (
        sustento_ver == 1 ||
        retenciones_ver == 1 ||
        comprobante_ver == 1 ||
        usuario_ver == 1 ||
        emision_ver == 1 ||
        establecimiento_ver == 1 ||
        empresa_ver == 1 ||
        proyecto_ver == 1 ||
        impuesto_ver == 1 ||
        impuesto_ice_ver == 1 ||
        cobranzas_ver == 1 ||
        mora_ver == 1 ||
        forma_pago_ver == 1 ||
        forma_cobro_ver == 1 ||
        formas_pagos_sri_ver == 1 ||
        ejercicio_contable_ver == 1 ||
        balance_inicial_ver == 1
    ) {
        cont.push({
            url: null,
            name: "AdministrarNegocio",
            icon: "SettingsIcon",
            i18n: "Administrar",
            submenu: [{
                    url: "/administrar/empresa",
                    name: "PerfilNeg",
                    slug: "empresa",
                    i18n: "Empresas"
                },
                {
                    url: "/administrar/establecimiento",
                    name: "Establecimeinto",
                    slug: "establecimiento",
                    i18n: "Establecimiento"
                },
                {
                    url: "/administrar/emision",
                    name: "PtoEmision",
                    slug: "emision",
                    i18n: "Puntos_de_Emision"
                },
                {
                    url: "/administrar/usuarios",
                    name: "Usuarios",
                    slug: "usuarios",
                    i18n: "Usuarios"
                },
                {
                    url: "/administrar/cobranzas",
                    name: "Cobranzas",
                    slug: "Cobranzas",
                    i18n: "Cobranzas"
                },
                {
                    url: "/administrar/mora",
                    name: "Mora",
                    slug: "Mora",
                    i18n: "Mora"
                },
            
                {
                    url: "/administrar/forma_cobros",
                    name: "Forma_cobros",
                    slug: "Forma_cobros",
                    i18n: "Forma_cobros"
                },
                
            ]
        });
    }
        
    if (seguimiento_ver == 1) {
        cont.push({
            url: null,
            name: "Seguimiento",
            slug: "seguimiento",
            icon: "SlackIcon",
            i18n: "Módulo Activos",
            submenu: [
                {
                url: "/verificacion_activos/activos",
                name: "Verificador Activos",
                slug: "Bodega",
                i18n: "Verificador Activos"
                },
                {
                    url: "/verificacion_activos/actas",
                    name: "Verificador Actas",
                    slug: "Bodega",
                    i18n: "Verificador Actas"
                },
                {
                    url: "/verificacion_activos/inmuebles",
                    name: "Verificador Inmuebles",
                    slug: "Bodega",
                    i18n: "Verificador Inmuebles"
                }
            ]
        });
    }
    seguimiento_ver=0;
    if (seguimiento_ver == 1) {
        cont.push({
            url: null,
            name: "Seguimiento",
            slug: "seguimiento",
            icon: "SlackIcon",
            i18n: "Seguimiento",
            submenu: [{
                    url: "/seguimiento/clientes",
                    name: "clientes",
                    slug: "Bodega",
                    i18n: "Clientes"
                },
                {
                    url: "/seguimiento/servicios",
                    name: "servicios",
                    slug: "catalogo",
                    i18n: "Servicios"
                },
                {
                    url: "/seguimiento/obligaciones",
                    name: "obligaciones",
                    slug: "reportes",
                    i18n: "Obligaciones"
                },
                {
                    url: "/seguimiento/proforma",
                    name: "asignar",
                    slug: "reportes",
                    i18n: "Asignar"
                },
                {
                    url: "/seguimiento/proforma",
                    name: "proforma",
                    slug: "proforma",
                    i18n: "Proforma"
                }
            ]
        });
    }
    calendario_compra_ver=0;
    if (calendario_compra_ver == 1) {
        cont.push({
            url: "/calendario",
            name: "Calendario",
            slug: "calendario",
            icon: "CalendarIcon",
            i18n: "Calendario"
        });
    }
}
export default cont;